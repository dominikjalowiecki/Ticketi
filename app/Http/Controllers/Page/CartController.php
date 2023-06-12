<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Jobs\SendTickets;

class CartController extends Controller
{
    /**
     * Display Cart view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $cartItems = Session::get('cart_items', []);
        $initialItemsCount = count($cartItems);
        $items = [];
        $info = '';

        if ($initialItemsCount > 0) {
            $ids = array_keys($cartItems);

            $items = DB::table('event')
                ->select('id_event', 'name', 'url', 'ticket_price')
                ->whereIn('id_event', $ids)
                ->where('is_draft', false)
                ->where('start_datetime', '>', 'CURRENT_TIMESTAMP')
                ->where('ticket_count', '>', 0)
                ->get();


            Session::forget('cart_items');

            $newCartItems = [];
            foreach ($items as $item) {
                $newCartItems[$item->id_event] = [
                    'name' => $item->name,
                    'price' => $item->ticket_price,
                ];
            }

            Session::put('cart_items', $newCartItems);

            if ($initialItemsCount - count($newCartItems) > 0)
                $info = 'Item is no more available...';
        }

        return view('cart', ['items' => $items, 'info' => $info]);
    }

    /**
     * Add event to cart.
     * 
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'idEvent' => ['required', 'integer', 'gt:0'],
        ]);

        $idEvent = (int)$request->idEvent;

        // Check if ticket already in cart
        $cartItems = Session::get('cart_items', []);
        if (array_key_exists($idEvent, $cartItems)) return back()->with('info', 'Ticket already in cart...');

        $event = DB::table('event')
            ->where('id_event', $idEvent)
            ->where('is_draft', false)
            ->where('start_datetime', '>', 'CURRENT_TIMESTAMP')
            ->where('ticket_count', '>', 0)
            ->get();

        if ($event->isEmpty()) return back();
        $event = $event->first();

        $cartItems[$event->id_event] = [
            'name' => $event->name,
            'price' => $event->ticket_price,
        ];

        Session::put('cart_items', $cartItems);

        return redirect('cart')->with('status', 'Item added to cart!');
    }

    /**
     * Purchase cart.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function purchaseCart()
    {
        $cartItems = Session::get('cart_items', []);
        if (count($cartItems) <= 0) back();

        $ids = array_keys($cartItems);

        $user = Auth::user();

        $orders = DB::table('order')
            ->join('event', 'order.id_event', '=', 'event.id_event')
            ->whereIn('event.id_event', $ids)
            ->where('order.id_user', $user->id_user)
            ->select('event.id_event', 'event.name')
            ->get();

        // Checking if ticket already bought
        if (!$orders->isEmpty()) {
            $info = [];
            foreach ($orders as $order) {
                unset($cartItems[$order->id_event]);
                $info[] = 'Ticket for ' . $order->id_event . '. ' . $order->name . ' already bought';
            }

            Session::put('cart_items', $cartItems);

            return back()->with('info', $info);
        }

        $birthdate = Carbon::parse($user->birthdate);
        $currentDate = Carbon::today();
        $diff = $currentDate->diffInYears($birthdate);
        $isUserAdult = ($diff >= 18);

        // Checking if event is for adults
        if (!$isUserAdult) {
            $events = DB::table('event')
                ->whereIn('id_event', $ids)
                ->where('is_adult_only', true)
                ->select('event.id_event', 'event.name')
                ->get();

            if (!$events->isEmpty()) {
                $info = [];
                foreach ($events as $event) {
                    unset($cartItems[$event->id_event]);
                    $info[] = 'Event ' . $event->id_event . '. ' . $event->name . ' is adult only!';
                }

                Session::put('cart_items', $cartItems);

                return back()->with('info', $info);
            }
        }

        $insertedIds = [];

        DB::beginTransaction();

        try {
            $items = DB::table('event')
                ->select('id_event', 'ticket_price')
                ->whereIn('id_event', $ids)
                ->where('is_draft', false)
                ->where('start_datetime', '>', 'CURRENT_TIMESTAMP')
                ->where('ticket_count', '>', 0)
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                DB::rollBack();
                return back()->with('info', 'Item is no more available...');
            }

            foreach ($items as $item) {
                DB::table('event')
                    ->where('id_event', $item->id_event)
                    ->decrement('ticket_count');

                $hash = hash('sha256', $item->id_event . 'X' . $user->id_user);

                DB::table('order')->insertGetId([
                    'id_user' => $user->id_user,
                    'id_event' => $item->id_event,
                    'id_order' => $hash,
                    'price' => $item->ticket_price,
                ]);

                $insertedIds[] = $hash;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500, 'Something went wrong :(');
        }

        SendTickets::dispatch($insertedIds)
            ->afterCommit()
            ->onQueue('high');

        // Clear session cart data
        Session::forget('cart_items');

        return redirect()->to(route('user-tickets'))->with('status', 'Tickets will be send to your email soon');
    }

    /**
     * Purchase cart.
     * 
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'idEvent' => ['required', 'integer', 'gt:0'],
        ]);

        $idEvent = (int)$request->idEvent;

        $cartItems = Session::get('cart_items', []);
        unset($cartItems[$idEvent]);

        Session::put('cart_items', $cartItems);

        return redirect()->route('cart.show');
    }
}
