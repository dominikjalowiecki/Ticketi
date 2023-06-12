<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Log;


class EventController extends Controller
{
    /**
     * Show events page.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function list(Request $request)
    {
        $request->validate([
            'search' => ['string', 'max:50', 'nullable'],
            'category' => ['string', 'max:45', 'nullable'],
            'city' => ['string', 'max:45', 'nullable'],
        ]);

        $query = DB::table('events_list');

        if (Auth::check()) {
            $user = $request->user();

            $followedItems = DB::table('follow')
                ->where('id_user', $user->id_user);

            $query = $query
                ->leftJoinSub($followedItems, 'followed_items', function (JoinClause $join) {
                    $join->on('events_list.id_event', '=', 'followed_items.id_event');
                })
                ->select('events_list.*', 'followed_items.created_datetime as is_followed');
        }

        $search = $request->search;
        if ($search !== null) {
            $query = $query
                ->whereRaw(DB::raw("MATCH (events_list.name, events_list.description, events_list.tags) AGAINST ('$search' IN NATURAL LANGUAGE MODE)"));
        }

        $category = $request->category;
        if ($category !== null) {
            $query = $query
                ->where('events_list.category_name', $category);
        }

        $city = $request->city;
        if ($city !== null) {
            $query = $query
                ->whereRaw(DB::raw("events_list.id_city IN (get_cities_in_area('$city', 1000))"));
        }

        $underage = $request->underage;
        if ($underage !== null) {
            $query = $query
                ->where('events_list.is_adult_only', false);
        }

        $sort = $request->sort;
        switch ($sort) {
            case 'Lowest price':
                $query->orderBy('ticket_price', 'asc');
                break;
            case 'Highest price':
                $query->orderBy('ticket_price', 'desc');
                break;
            case 'Most likes':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'Starting soon':
                $query->orderBy('start_datetime', 'desc');
                break;
            case 'Newest':
            default:
                $query->orderBy('created_datetime', 'desc');
                break;
        }

        $events = $query
            ->paginate(config('ticketi.pagination'));

        $events->appends($request->except('page'));

        $categories = Cache::remember('categories', 60 * 60, function () {
            return DB::table('category')->get();
        });

        $cities = Cache::remember('cities', 60 * 60, function () {
            return DB::table('city')->get();
        });

        return view('events', ['events' => $events, 'categories' => $categories, 'cities' => $cities]);
    }

    /**
     * Show event page.
     *
     * @param  Request $request
     * @param  string $idString
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $idString)
    {
        $idStringExplode = explode('-', $idString);
        $idEvent = (int) end($idStringExplode);

        $event = DB::table('event')
            ->join('category', 'event.id_category', '=', 'category.id_category')
            ->join('city', 'event.id_city', '=', 'city.id_city')
            ->select('event.*', 'category.name as category_name', 'city.name as city_name')
            ->where('id_event', $idEvent)
            ->where('is_draft', false)
            ->where('start_datetime', '>', 'CURRENT_TIMESTAMP')
            ->get();

        if ($event->isEmpty()) return abort(404);

        $comments = DB::table('comment')
            ->join('user', 'comment.id_user', '=', 'user.id_user')
            ->where('id_event', $idEvent)
            ->orderBy('comment.created_datetime', 'desc')
            ->select('comment.*', 'user.name as user_name', 'user.surname as user_surname')
            ->limit(config('ticketi.pagination'))
            ->get();

        $media = DB::table('event_medium')
            ->join('medium', 'event_medium.id_medium',  '=', 'medium.id_medium')
            ->where('id_event', $idEvent)
            ->get();

        $images = [];
        $video = null;
        foreach ($media as $medium) {
            if ($medium->type === 'IMAGE') $images[] = $medium;
            elseif ($medium->type === 'VIDEO') $video = $medium;
        }

        $is_followed = false;

        if (Auth::user()) {
            $id_user = Auth::user()->id_user;

            $follow = DB::table('follow')
                ->where('id_event', $event->first()->id_event)
                ->where('id_user', $id_user)
                ->get();

            $is_followed = !$follow->isEmpty();
        }

        $event = $event->first();

        $event->description = htmlspecialchars($event->description, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $allowed_tags = ['p', 'ul', 'ol', 'li', 'strong', 'em', 'blockquote', 'pre'];
        foreach ($allowed_tags as $tag) {
            $event->description = str_replace("&lt;$tag&gt;", "<$tag>", $event->description);
            $event->description = str_replace("&lt;/$tag&gt;", "</$tag>", $event->description);
        }

        return view('event', ['event' => $event, 'is_followed' => $is_followed, 'images' => $images, 'video' => $video, 'comments' => $comments]);
    }

    /**
     * Handle like event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request)
    {
        try {
            $request->validate([
                'idEvent' => ['required', 'integer', 'gt:0', 'exists:event,id_event'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response('The given data was invalid.', 400);
        }

        $idEvent = (int)$request->idEvent;
        $throttleKey = 'likeEvent:' . $idEvent . '|' . optional($request->user())->id_user ?: $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            return response('Rate limit exceeded', 429);
        }

        RateLimiter::hit($throttleKey);

        $query = DB::table('event')->where('id_event', $idEvent);

        // $eventExists = (bool)$query->count();

        // if (!$eventExists) return response('', 400);

        // return response('hello', 200)->header('Content-Type', 'text/plain', 'Accept: 'application/json');
        // return response()->json(['hello' => $value], 201); 

        $query->increment('likes_count');

        $count = ($query->get())->first()->likes_count;

        return response($count, 200);
    }

    /**
     * Handle follow event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function follow(Request $request)
    {
        try {
            $request->validate([
                'idEvent' => ['required', 'integer', 'gt:0'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response('The given data was invalid.', 400);
        }

        $idEvent = (int)$request->idEvent;

        $user = Auth::user();

        // $isFollowed = !(DB::table('follow')
        //     ->where('id_user', $user->id_user)
        //     ->where('id_event', $idEvent)
        //     ->get())->isEmpty();

        $numberOfAffectedRows = DB::table('follow')
            ->where('id_user', $user->id_user)
            ->where('id_event', $idEvent)
            ->delete();

        if (!$numberOfAffectedRows) {
            DB::table('follow')
                ->insert([
                    'id_user' => $user->id_user,
                    'id_event' => $idEvent,
                ]);
        }

        return response('', 200);
    }

    /**
     * Create new event.
     *
     * @param  string $idString
     * @return \Illuminate\View\View
     */
    public function store(string $idString)
    {
    }
}
