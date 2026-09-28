<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function settingsPageGet(Request $request){
        return view("settings");
    }
}
