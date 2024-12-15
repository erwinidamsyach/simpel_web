<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
        $arr = [];
        $arr['active'] = "dashboard";

        return view("pages.dashboard.index", $arr);
    }
}
