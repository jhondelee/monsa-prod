<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Factories\Dashboard\Factory as DashboardFactory;
use App\Factories\User\Factory as UserFactory;
use App\Role;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
                DashboardFactory $dashboard,
                UserFactory $user

     ){ 
        $this->user               = $user;
        $this->dashboard          = $dashboard;
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        $user = auth()->user()->id;
        $role = role::where('level',$user)->first();

        return view('pages.dashboard.index');
               
    }

}
