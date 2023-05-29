<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Show event page.
     *
     * @param  string $id
     * @return \Illuminate\View\View
     */
    public function show(string $idString)
    {
        $idStringExplode = explode('-', $idString);
        $id = end($idStringExplode);

        $event = DB::table('event')
            ->where('id_event', $id)
            ->get();

        if ($event->isEmpty()) return abort(404);

        // $comments = DB::table('comment')->where('id_event', $id)->get();
        // $media = DB::table('event_medium')->join('medium', 'event_medium.id_medium',  '=', 'medium.id_medium');

        return view('event', ['event' => $event->first()]);
    }
}
