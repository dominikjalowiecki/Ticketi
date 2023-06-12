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
use App\Mail\ContactMail;
use App\Mail\ContactResponseMail;
use Illuminate\Support\Facades\RateLimiter;

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

        // return redirect()->to(route('user-profile'))->with('status', 'Password has been changed');
        return back()->with('status', 'Password has been changed');
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

        $user = $request->user();
        $throttleKey = 'sendContactMail:' . '|' . $user->id_user;

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            return back()->with('info', 'Contact form limit exceeded!');
        }

        RateLimiter::hit($throttleKey);

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

        Mail::to(config('ticketi.contactFormEmail'))
            ->send((new ContactMail($user, $subject, $content))->onQueue('low'));

        // Response mail
        Mail::to($user->email)
            ->send((new ContactResponseMail($subject, $content))->onQueue('low'));

        // dispatch(function () {
        //     Mail::to('taylor@example.com')->send(new WelcomeMessage);
        // })->afterResponse();

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
