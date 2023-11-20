<?php

namespace App\Http\Controllers\Manufacturing;

use App\Http\Controllers\Controller;

class ManufacturingDashboardController extends Controller
{
    public function index()
    {

        return view('manufacturing.dashboard.index');
    }
}
