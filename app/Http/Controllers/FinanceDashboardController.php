<?php

namespace App\Http\Controllers;

class FinanceDashboardController extends Controller
{
    public function index()
    {

        return view('finance.dashboard.index');
    }
}
