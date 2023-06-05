<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserProfileController extends Controller
{
    /**
     * Display User Profile dashboard.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        $verified = $request->verified;
        $status = '';
        if ($verified) $status = 'Your email has been verified';

        $user = $request->user();

        return view('user-dashboard', ['user' => $user, 'status' => $status]);
    }

    /**
     * Change user password.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', Rules\Password::defaults()],
            'newPassword' => ['required', Rules\Password::defaults()],
        ]);

        $user = $request->user();
        $password = $request->password;
        $newPassword = $request->newPassword;

        if (!Hash::check($password, $user->password))
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);

        User::where('id_user', $user->id_user)->update([
            'password' => Hash::make($newPassword)
        ]);

        return redirect()->to(route('user-profile'))->with('status', 'Password has been changed');
    }

    /**
     * Display User Profile tickets.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function tickets(Request $request)
    {
        $user = $request->user();
        $tickets = DB::table('order')
            ->join('events_list', 'order.id_event', '=', 'events_list.id_event')
            ->where('order.id_user', $user->id_user)
            ->select('events_list.*', 'order.id_order', 'order.price', 'order.created_datetime as purchase_datetime')
            ->orderBy('purchase_datetime', 'desc')
            ->paginate(config('ticketi.pagination'));

        return view('user-tickets', ['tickets' => $tickets]);
    }

    /**
     * Display User Profile contact form.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function contactForm(Request $request)
    {
        return view('user-contact-form');
    }

    /**
     * Display User Profile contact form.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function sendContactMail(Request $request)
    {
        $request->validate([
            'subject' => ['required', 'integer', 'gt:0'],
            'content' => ['required', 'string', 'max:300'],
        ]);

        $subjectId = $request->subject;
        $subject = '';

        switch ($subjectId) {
            case 1:
                $subject = 'Information';
                break;
            case 2:
                $subject = 'Tickets returns';
                break;
            case 3:
                $subject = 'Technical support';
                break;
            default:
                return back();
        }

        $content = htmlspecialchars($request->content, ENT_QUOTES, 'UTF-8');

        // Mail::raw('This is a simple text', function ($m) {
        //     $m->to('toemail@abc.com')->subject('Email Subject');
        //   });
        // Mail::to($request->user())->send(new OrderShipped($order));

        // Mail::to('receiver-email-id')->send(new NotifyMail());

        // if (Mail::failures()) {
        //     return response()->Fail('Sorry! Please try again latter');
        // } else {
        //     return response()->success('Great! Successfully send in your mail');
        // }

        //=======================================
        // Mail::to($request->user())
        //     ->cc($moreUsers)
        //     ->bcc($evenMoreUsers)
        //     ->queue(new OrderShipped($order));
        // $message = (new OrderShipped($order))
        //     ->onConnection('sqs')
        //     ->onQueue('emails');

        // Mail::to($request->user())
        //     ->cc($moreUsers)
        //     ->bcc($evenMoreUsers)
        //     ->queue($message);

        // Mail::to($request->user())
        //     ->cc($moreUsers)
        //     ->bcc($evenMoreUsers)
        //     ->later(now()->addMinutes(10), new OrderShipped($order));
        //=======================================
        // email zwrotny Thank you for contact, dont respond ! {{ $content }}

        return back()->with('status', 'Contact mail has been sent');
    }

    /**
     * Show followed events.
     * 
     * @return \Illuminate\View\View
     */
    public function followed()
    {
        $user = Auth::user();

        $followed = DB::table('follow')
            ->join('events_list', 'follow.id_event', '=', 'events_list.id_event')
            ->select('events_list.*')
            ->where('follow.id_user', $user->id_user)
            ->orderBy('follow.created_datetime', 'desc')
            ->paginate(config('ticketi.pagination'));

        return view('followed', ['followed_events' => $followed]);
    }
}
