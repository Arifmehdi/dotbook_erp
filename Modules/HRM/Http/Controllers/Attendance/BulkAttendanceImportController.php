<?php

namespace Modules\HRM\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HRM\Imports\BulkAttendanceImport;

class BulkAttendanceImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        abort_if(! auth()->user()->can('hrm_bulk_attendance_import_index'), 403, 'Access forbidden');

        return view('hrm::attendance.bulk-imports.index');
    }

    public function importFromTextFile(Request $request)
    {
        abort_if(! auth()->user()->can('hrm_bulk_attendance_import_text_file'), 403, 'Access forbidden');
        $request->validate([
            'file' => 'required|file',
        ]);

        Excel::import(new BulkAttendanceImport(), $request->file);
        $tempImportDir = storage_path('framework/laravel-excel');
        if (isset($tempImportDir)) {
            File::deleteDirectory($tempImportDir);
        }
        session()->flash('successMsg', 'Successfully attendance file is imported');

        return redirect()->back();

    }
}
