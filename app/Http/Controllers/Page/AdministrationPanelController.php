<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log;

class AdministrationPanelController extends Controller
{
    /**
     * Display Create Event.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showCreateEvent(Request $request)
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
            // next and next and next
            'idCategory' => ['required', 'integer', 'gt:0', 'exists:category,id_category'],
        ]);

        // $input = $request->only('username', 'password');
        // $input = $request->except(['credit_card']);

        // $request->whenHas('name', function (string $input) {
        //     // The "name" value is present...
        // }, function () {
        //     // The "name" value is not present...
        // });


        $description = strip_tags($request->description, '<p><ul><ol><li><strong><em><blockquote><pre>');

        // insertGetId()
        $url = '-3';
        return redirect()->to(route('event.page', [$url]));
    }

    /**
     * Display Edit Event.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showEditEvent(Request $request, int $id)
    {
        if (!DB::table('event')->where('id_event', $id)->exists()) {
            return back();
            // throw ValidationException::withMessages([
            //     'id' => 'Invalid event identifier',
            // ]);
        }

        return view('admin-edit-event', ['id' => $id]);
    }

    /**
     * Handle edit event.
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

        $url = '-' . $id;
        return redirect()->to(route('event.page', [$url]));
    }

    /**
     * Display Orders.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function orders(Request $request)
    {
        $orders = DB::table('orders_list')
            ->get();

        return view('admin-orders', ['orders' => $orders]);
    }

    /**
     * Display Change Password.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function changePassword(Request $request)
    {
        return view('admin-change-password');
    }

    /**
     * Show add moderator form.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showAddModerator(Request $request)
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
                's' => ['required', 'string', 'max:50'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back();
        }

        $search = $request->s;
        $events = DB::table('events_list')
            ->whereRaw(DB::raw("MATCH (events_list.name, events_list.description, events_list.tags) AGAINST ('$search' IN NATURAL LANGUAGE MODE)"))
            ->get();

        return view('admin-search', ['events' => $events]);
    }

    /**
     * Display Administration Panel dashboard.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        $ordersStats = DB::table('order')
            ->selectRaw('COUNT(id_order) AS orders_count, SUM(price) AS total_revenue')
            ->get()->first();
        // ->groupBy('id_order')


        // Log::debug($ordersStats->toSql());

        $availableEventsCount = DB::table('event')
            ->where('is_draft', false)
            ->where('start_datetime', '>', 'CURRENT_TIMESTAMP')
            ->where('ticket_count', '>', 0)
            ->count();

        // $orders = DB::table('orders')
        //     ->select('department', DB::raw('SUM(price) as total_sales'))
        //     ->groupBy('department')
        //     ->havingRaw('SUM(price) > ?', [2500])
        //     ->get();

        $recent_orders = DB::table('orders_list')
            ->orderBy('created_datetime', 'desc')
            ->limit('25')
            ->get();

        return view('admin-dashboard', ['ordersStats' => $ordersStats, 'availableEventsCount' => $availableEventsCount, 'orders' => $recent_orders]);
    }
}
