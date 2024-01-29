<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if($request->session()->get('flag')==false){
            toast('Welcome '.Auth::user()->first_name.' '.Auth::user()->last_name,'success')->background('#fff')->timerProgressBar();
            $request->session()->put('flag', true);
        }
        return view('dashboard');
    }

    public function check(Request $request)
    {
        return $request->all();
    }
}
