<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\SupplierImport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SupplierImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('contacts::supplier_import.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('contacts::supplier_import.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
        ]);

        Excel::import(new SupplierImport, $request->import_file);

        return redirect()->back()->with('success', 'Successfully imported!');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('contacts::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('contacts::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
