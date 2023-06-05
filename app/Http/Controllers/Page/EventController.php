<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class EventController extends Controller
{
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

        if ($event->isEmpty() || $event->first()->is_draft) return abort(404);

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

        return view('event', ['event' => $event->first(), 'is_followed' => $is_followed, 'images' => $images, 'video' => $video, 'comments' => $comments]);
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
                'idEvent' => ['required', 'integer', 'gt:0'],
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

        $eventExists = (bool)$query->count();

        if (!$eventExists) return response('', 400);

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
