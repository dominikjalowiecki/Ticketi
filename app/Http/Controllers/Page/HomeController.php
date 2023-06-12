<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display Home view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $events = Cache::remember('popular_events', 60 * 60, function () {
            return DB::table('events_list')
                ->orderBy('likes_count', 'desc')
                ->limit(3)
                ->get();
        });

        return view('home', ['popular_events' => $events]);
    }
}
