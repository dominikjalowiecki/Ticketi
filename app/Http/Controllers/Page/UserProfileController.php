<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display User Profile.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $verified = $request->verified;
        return view('dashboard', ['verified' => $verified]);
    }
}
