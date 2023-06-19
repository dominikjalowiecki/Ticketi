<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdministrationPanelController extends Controller
{
    /**
     * Show Create Event.
     *
     * @return \Illuminate\View\View
     */
    public function showCreateEvent()
    {
        $categories = DB::table('category')->get();
        return view('admin-create-event', ['categories' => $categories]);
    }

    /**
     * Handle creating event.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function storeCreateEvent(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:500'],
            'idCategory' => ['required', 'integer', 'gt:0', 'exists:category,id_category'],
            'tags' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'min:3', 'max:45'],
            'street' => ['required', 'string', 'max:65'],
            'postalCode' => ['required', 'string', 'max:6'],
            'ticketPrice' => ['required',  'regex:/^(\d{1,4}(\.\d{1,2})?)$/'],
            'ticketCount' => ['required', 'integer', 'gt:0', 'lt:100000'],
            'startDatetime' => ['required', 'date', 'after:tomorrow'],
            'images' => ['required', 'array', 'max:4'],
            'images.*' => ['file', 'max:1024', 'mimes:jpg,png,gif'],
            'video' => ['file', 'max:10240', 'mimetypes:video/mp4,video/mov'],
        ], [
            'images.*.max' => 'Maximum allowed image size is 1MB',
            'images.*.mimes' => 'Image must be of format jpg, png or gif',
            'video.max' => 'Maximum allowed video size is 10MB',
            'video.mimetypes' => 'Video must be of format mp4 or mov',
        ]);

        [
            'name' => $name,
            'description' => $description,
            'idCategory' => $idCategory,
            'tags' => $tags,
            'city' => $city,
            'street' => $street,
            'postalCode' => $postalCode,
            'ticketPrice' => $ticketPrice,
            'ticketCount' => $ticketCount,
            'startDatetime' => $startDatetime,
        ] = $request;

        $name = ucfirst($name);
        $city = ucfirst($city);
        $description = strip_tags($request->description, '<p><ul><ol><li><strong><em><blockquote><pre>');
        $tags = implode(',', json_decode($tags));
        $url = 0;

        $isDraft = false;
        $isAdultOnly = true;
        if ($request->has('isDraft')) $isDraft = true;
        if ($request->has('childAllowed')) $isAdultOnly = false;

        DB::transaction(function () use (
            $request,
            $name,
            $description,
            $idCategory,
            $tags,
            &$url,
            $city,
            $street,
            $postalCode,
            $ticketPrice,
            $ticketCount,
            $startDatetime,
            $isDraft,
            $isAdultOnly
        ) {
            // Handle city validation and creation
            $idCity = 0;
            $cityRow = DB::table('city')
                ->where('name', $city)
                ->get();

            if (!$cityRow->isEmpty()) {
                $idCity = ($cityRow->first())->id_city;
            } else {
                $geocodeApiUrl = 'https://maps.googleapis.com/maps/api/geocode/json?key=' . config('ticketi.maps') . '&address=' . $city . '&components=country:PL';

                $response = Http::accept('application/json')->get($geocodeApiUrl);
                $json = $response->json();

                if (
                    !$response->ok() ||
                    $json['status'] == 'OVER_QUERY_LIMIT'
                )
                    throw ValidationException::withMessages([
                        'city' => 'API error occurred',
                    ]);

                if (
                    $json['status'] != 'OK' ||
                    $json['results'][0]['types'][0] != 'locality'
                )
                    throw ValidationException::withMessages([
                        'city' => 'Passed city does not exists!',
                    ]);

                $city = $json['results'][0]['address_components'][0]['long_name'];

                $location = $json['results'][0]['geometry']['location'];

                $idCity = DB::table('city')
                    ->insertGetId([
                        'name' => $city,
                        'center' => DB::raw("ST_GeomFromText('POINT({$location['lat']} {$location['lng']})', 4326)"),
                    ]);
            }

            // Handle event creation
            $idEvent = DB::table('event')
                ->insertGetId(
                    [
                        'name' => $name,
                        'description' => $description,
                        'tags' => $tags,
                        'id_category' => $idCategory,
                        'start_datetime' => DB::raw("STR_TO_DATE('$startDatetime','%Y-%m-%dT%H:%iZ')"),
                        'ticket_price' => $ticketPrice,
                        'ticket_count' => $ticketCount,
                        'postal_code' => $postalCode,
                        'id_city' => $idCity,
                        'street' => $street,
                        'is_adult_only' => $isAdultOnly,
                        'is_draft' => $isDraft,
                        'id_user' => $request->user()->id_user,
                    ]
                );

            // Update event url path
            $url = Str::slug($name, '-') . '-' . $idEvent;
            DB::table('event')
                ->where('id_event', $idEvent)
                ->update(['url' => $url]);

            if (get_directory_size(Storage::path('')) <= config('ticketi.uploadDirMaxSize')) {
                // Handle adding images
                $mediaInsert = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('images');

                    $idMedium = DB::table('medium')
                        ->insertGetId(
                            [
                                'url' => $path,
                                'type' => 'IMAGE',
                            ]
                        );

                    $mediaInsert[] = ['id_event' => $idEvent, 'id_medium' => $idMedium];
                }

                // Handle adding video
                if ($request->has('video')) {
                    $video = $request->file('video');
                    $path = $video->store('videos');

                    $idMedium = DB::table('medium')
                        ->insertGetId(
                            [
                                'url' => $path,
                                'type' => 'VIDEO',
                            ]
                        );

                    $mediaInsert[] = ['id_event' => $idEvent, 'id_medium' => $idMedium];
                }

                // Handle adding medium-event relations
                if (count($mediaInsert) > 0) {
                    DB::table('event_medium')
                        ->insert($mediaInsert);
                }
            } else {
                Log::warning('Uploads directory size limit exceeded!');
            }
        });

        if ($isDraft)
            return redirect()->to(route('admin.search', ['s' => $name]))->with('status', 'Event has been added');

        return redirect()->to(route('event.page', [$url]))->with('status', 'Event has been added');
    }

    /**
     * Show Edit Event.
     *
     * @return \Illuminate\View\View
     */
    public function showEditEvent(int $id)
    {
        if (!DB::table('event')->where('id_event', $id)->exists()) {
            return back();
        }

        $categories = DB::table('category')->get();
        $event = DB::table('event')
            ->join('category', 'event.id_category', '=', 'category.id_category')
            ->join('city', 'event.id_city', '=', 'city.id_city')
            ->select('event.*', DB::raw("DATE_FORMAT(event.start_datetime, '%Y-%m-%dT%H:%iZ') AS start_datetime"), 'category.name AS category_name', 'city.name AS city_name')
            ->where('id_event', $id)
            ->get();

        $event = $event->first();

        $event->description = htmlspecialchars($event->description, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $allowed_tags = ['p', 'ul', 'ol', 'li', 'strong', 'em', 'blockquote', 'pre'];
        foreach ($allowed_tags as $tag) {
            $event->description = str_replace("&lt;$tag&gt;", "<$tag>", $event->description);
            $event->description = str_replace("&lt;/$tag&gt;", "</$tag>", $event->description);
        }

        return view('admin-edit-event', ['categories' => $categories, 'event' => $event]);
    }

    /**
     * Handle Edit event.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function storeEditEvent(Request $request, int $id)
    {
        if (!DB::table('event')->where('id_event', $id)->exists()) {
            throw ValidationException::withMessages([
                'id' => 'Invalid event identifier',
            ]);
        }

        $request->validate([
            'description' => ['required', 'string', 'max:500'],
            'idCategory' => ['required', 'integer', 'gt:0', 'exists:category,id_category'],
            'tags' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'min:3', 'max:45'],
            'street' => ['required', 'string', 'max:65'],
            'postalCode' => ['required', 'string', 'max:6'],
            'ticketPrice' => ['required',  'regex:/^(\d{1,4}(\.\d{1,2})?)$/'],
            'addTickets' => ['nullable', 'integer', 'gt:0', 'lt:10000'],
            'startDatetime' => ['required', 'date', 'after:tomorrow'],
            'images' => ['array', 'max:4'],
            'images.*' => ['file', 'max:1024', 'mimes:jpg,png,gif'],
            'video' => ['file', 'max:10240', 'mimetypes:video/mp4,video/mov'],
        ], [
            'images.*.max' => 'Maximum allowed image size is 1MB',
            'images.*.mimes' => 'Image must be of format jpg, png or gif',
            'video.max' => 'Maximum allowed video size is 10MB',
            'video.mimetypes' => 'Video must be of format mp4 or mov',
        ]);

        [
            'description' => $description,
            'idCategory' => $idCategory,
            'tags' => $tags,
            'city' => $city,
            'street' => $street,
            'postalCode' => $postalCode,
            'ticketPrice' => $ticketPrice,
            'startDatetime' => $startDatetime,
        ] = $request;

        $city = ucfirst($city);
        $description = strip_tags($request->description, '<p><ul><ol><li><strong><em><blockquote><pre>');
        $tags = implode(',', json_decode($tags));

        $isDraft = false;
        $isAdultOnly = true;
        if ($request->has('isDraft')) $isDraft = true;
        if ($request->has('childAllowed')) $isAdultOnly = false;

        $addTickets = $request->addTickets ?? 0;

        DB::transaction(function () use (
            $id,
            $request,
            $description,
            $idCategory,
            $tags,
            $city,
            $street,
            $postalCode,
            $ticketPrice,
            $addTickets,
            $startDatetime,
            $isDraft,
            $isAdultOnly
        ) {
            // Handle city validation and creation
            $idCity = 0;
            $cityRow = DB::table('city')
                ->where('name', $city)
                ->get();

            if (!$cityRow->isEmpty()) {
                $idCity = ($cityRow->first())->id_city;
            } else {
                $geocodeApiUrl = 'https://maps.googleapis.com/maps/api/geocode/json?key=' . config('ticketi.maps') . '&address=' . $city . '&components=country:PL';

                $response = Http::accept('application/json')->get($geocodeApiUrl);
                $json = $response->json();

                if (
                    !$response->ok() ||
                    $json['status'] == 'OVER_QUERY_LIMIT'
                )
                    throw ValidationException::withMessages([
                        'city' => 'API error occurred',
                    ]);

                if (
                    $json['status'] != 'OK' ||
                    $json['results'][0]['types'][0] != 'locality'
                )
                    throw ValidationException::withMessages([
                        'city' => 'Passed city does not exists!',
                    ]);

                $city = $json['results'][0]['address_components'][0]['long_name'];

                $location = $json['results'][0]['geometry']['location'];

                $idCity = DB::table('city')
                    ->insertGetId([
                        'name' => $city,
                        'center' => DB::raw("ST_GeomFromText('POINT({$location['lat']} {$location['lng']})', 4326)"),
                    ]);
            }

            // Handle event edit
            DB::table('event')
                ->where('id_event', $id)
                ->increment(
                    'ticket_count',
                    $addTickets,
                    [
                        'description' => $description,
                        'tags' => $tags,
                        'id_category' => $idCategory,
                        'start_datetime' => DB::raw("STR_TO_DATE('$startDatetime','%Y-%m-%dT%H:%iZ')"),
                        'ticket_price' => $ticketPrice,
                        'postal_code' => $postalCode,
                        'id_city' => $idCity,
                        'street' => $street,
                        'is_adult_only' => $isAdultOnly,
                        'is_draft' => $isDraft,
                        'id_user' => $request->user()->id_user,
                    ]
                );

            if (get_directory_size(Storage::path('')) <= config('ticketi.uploadDirMaxSize')) {
                $mediaInsert = [];

                // Handle overriding of images
                if ($request->has('images')) {
                    $media = DB::table('medium')
                        ->join('event_medium', 'medium.id_medium', '=', 'event_medium.id_medium')
                        ->where('event_medium.id_event', $id)
                        ->where('medium.type', 'IMAGE')
                        ->select('medium.id_medium', 'medium.url')
                        ->get();

                    $ids = [];

                    foreach ($media as $medium) {
                        $ids[] = $medium->id_medium;
                        Storage::delete($medium->url);
                    }

                    $media = DB::table('medium')
                        ->whereIn('id_medium', $ids)
                        ->delete();

                    foreach ($request->file('images') as $image) {
                        $path = $image->store('images');

                        $idMedium = DB::table('medium')
                            ->insertGetId(
                                [
                                    'url' => $path,
                                    'type' => 'IMAGE',
                                ]
                            );

                        $mediaInsert[] = ['id_event' => $id, 'id_medium' => $idMedium];
                    }
                }

                // Handle overriding of video
                if ($request->has('video')) {
                    $media = DB::table('medium')
                        ->join('event_medium', 'medium.id_medium', '=', 'event_medium.id_medium')
                        ->where('event_medium.id_event', $id)
                        ->where('medium.type', 'VIDEO')
                        ->select('medium.id_medium', 'medium.url')
                        ->get();


                    if (!$media->empty()) {
                        $medium = $media->first();
                        $id = $medium->id_medium;
                        Storage::delete($medium->url);

                        $media = DB::table('medium')
                            ->where('id_medium', $id)
                            ->delete();
                    }

                    $video = $request->file('video');
                    $path = $video->store('videos');

                    $idMedium = DB::table('medium')
                        ->insertGetId(
                            [
                                'url' => $path,
                                'type' => 'VIDEO',
                            ]
                        );

                    $mediaInsert[] = ['id_event' => $id, 'id_medium' => $idMedium];
                }

                // Handle adding medium-event relations
                if (count($mediaInsert) > 0) {
                    DB::table('event_medium')
                        ->insert($mediaInsert);
                }
            } else {
                Log::warning('Uploads directory size limit exceeded!');
            }
        });

        if ($isDraft)
            return redirect()->to(route('admin.dashboard'))->with('status', 'Event has been updated');

        return redirect()->to(route('event.page', ['-' . $id]))->with('status', 'Event has been updated');
    }

    /**
     * Display Orders.
     *
     * @return \Illuminate\View\View
     */
    public function orders()
    {
        $orders = DB::table('orders_list')
            ->get();

        return view('admin-orders', ['orders' => $orders]);
    }

    /**
     * Display Change Password.
     *
     * @return \Illuminate\View\View
     */
    public function changePassword()
    {
        return view('admin-change-password');
    }

    /**
     * Show add moderator form.
     *
     * @return \Illuminate\View\View
     */
    public function showAddModerator()
    {
        return view('admin-add-moderator');
    }

    /**
     * Handle adding moderator.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function storeAddModerator(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:45'],
            'surname' => ['required', 'string', 'max:65'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:user'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => ucfirst($request->name),
            'surname' => ucfirst($request->surname),
            'email' => $request->email,
            'birthdate' => '1970-01-01',
            'role' => 'MODERATOR',
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return back()->with('status', 'Successfully added new moderator!');
    }

    /**
     * Display Search page.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        try {
            $request->validate([
                's' => ['nullable', 'string', 'max:50'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back();
        }

        $query = DB::table('event')
            ->join('category', 'event.id_category', '=', 'category.id_category');

        if ($request->s != null) {
            $search = $request->s;
            $query = $query->whereRaw(DB::raw("MATCH (event.name, event.description, event.tags) AGAINST ('$search' IN NATURAL LANGUAGE MODE)"));
        }

        $events = $query->select('event.*', 'category.name AS category_name')
            ->get();

        return view('admin-search', ['events' => $events]);
    }

    /**
     * Display Administration Panel dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $ordersStats = DB::table('order')
            ->selectRaw('COUNT(id_order) AS orders_count, SUM(price) AS total_revenue')
            ->get()
            ->first();

        $availableEventsCount = DB::table('event')
            ->where('is_draft', false)
            ->whereRaw('start_datetime > CURRENT_TIMESTAMP')
            ->where('ticket_count', '>', 0)
            ->count();

        $recent_orders = DB::table('orders_list')
            ->orderBy('created_datetime', 'desc')
            ->limit('25')
            ->get();

        return view('admin-dashboard', ['ordersStats' => $ordersStats, 'availableEventsCount' => $availableEventsCount, 'orders' => $recent_orders]);
    }
}
