<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display Home view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $events = DB::table('events_list')
            ->orderBy('likes_count', 'desc')
            ->limit(3)
            ->get();

        return view('home', ['popular_events' => $events]);
    }
}
