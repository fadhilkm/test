<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware(['auth','user']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       return view('pages.home');
    }
    public function test(){
        $user = \App\User::whereHas('roles', function($query){
            $query->where('name','admin');
        })->findOrFail(1);
        return $user;
    }
}
