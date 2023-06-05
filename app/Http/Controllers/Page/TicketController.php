<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     *  Get ticket.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $id = $request->id;

        $ticket = (DB::table('orders_list')
            ->where('id_order', $id)
            ->get())->first();

        return view('ticket', ['ticket' => $ticket]);
    }
}
