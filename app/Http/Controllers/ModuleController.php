<?php

namespace App\Http\Controllers;

class ModuleController extends Controller
{
    public function purchases()
    {
        return view('module.purchases');
    }

    public function control()
    {
        return view('module.control');
    }
}
