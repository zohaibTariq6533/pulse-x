<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{
    public function dashboard(){
        $userId = Auth::id();
        $user = DB::table('users')->where('id', $userId)->first();
        return view('dashboard', ['user' => $user]);
    }
}

