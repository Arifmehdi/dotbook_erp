<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    public function create()
    {
        return view('inventories.import.create_v2');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'import_file' => 'required',
        ]);

        Excel::import(new ProductImport, $request->import_file);

        return redirect()->back()->with('success', 'Successfully imported!');
    }
}
