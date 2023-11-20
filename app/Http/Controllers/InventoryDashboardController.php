<?php

namespace App\Http\Controllers;

class InventoryDashboardController extends Controller
{
    public function index()
    {

        return view('inventories.dashboard.index');
    }
}
