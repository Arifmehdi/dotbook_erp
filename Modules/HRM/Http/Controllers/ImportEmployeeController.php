<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HRM\Imports\EmployeeImport;

class ImportEmployeeController extends Controller
{
    public function index()
    {
        abort_if(! auth()->user()->can('hrm_employees_bulk_import_index'), 403, 'Access forbidden');

        return view('hrm::employee-import.index');
    }

    public function store(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_bulk_attendance_import_store'), 403, 'Access forbidden');
        $request->validate([
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
        ]);
        Excel::import(new EmployeeImport, $request->import_file);
        session()->flash('success', 'Employee imported successfully!');

        return redirect()->route('hrm.employee-import.index');
    }
}
