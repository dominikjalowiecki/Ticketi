<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display FAQ view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('faq');
    }
}
