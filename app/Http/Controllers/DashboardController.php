<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function menu()
{
    return view('dashboard.menu');
}

}
