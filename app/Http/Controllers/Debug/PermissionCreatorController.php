<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionCreatorController extends Controller
{
    public function index()
    {
        $permission_gui = Permission::all();

        return view('dev_routes.index', compact('permission_gui'));
    }

    public function store(Request $request)
    {
        $permission_gui = Permission::create(['name' => $request->name]);

        return back()->with('success', 'Added!');
    }
}
