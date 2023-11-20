<?php

namespace App\Http\Controllers;

class SalesDashboardController extends Controller
{
    public function index()
    {
        return view('sales_app.dashboard');
    }
}
