<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    /**
     * Show FAQ view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('faq');
    }
}
