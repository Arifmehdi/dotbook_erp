<?php

namespace App\Http\Controllers;

use App\Interface\FileUploaderServiceInterface;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ChangelogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $changelog = DB::table('change_logs')->orderBy('id', 'DESC')->get();

            return DataTables::of($changelog)
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown" id="accordion">';
                    $html .= '<a href="'.route('change_log.edit', $row->id).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('change_log.show', $row->id).'" class="action-btn details_button c-show" title="show"><span class="far fa-eye text-success"></span></a>';
                    $html .= '<a href="'.route('change_log.delete', $row->id).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('created_at', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->created_at));
                })
                ->rawColumns(['action', 'created_at'])
                ->make(true);
        }
        // $changelog = DB::table('change_logs')->latest()->first();
        $changelog = DB::table('change_logs')->get();

        return view('reports.change_log.create', compact('changelog'));
    }

    public function store(Request $request, FileUploaderServiceInterface $uploader)
    {
        $changeimage = null;
        if ($request->hasFile('image')) {
            $changeimage = $uploader->upload($request->file('image'), 'uploads/changeLog/');
        }
        $changeLog = ChangeLog::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $changeimage,
        ]);

        return response()->json('Change log created successfully!');
    }

    public function edit($id)
    {
        $changelog = DB::table('change_logs')->where('id', $id)->first();

        return view('reports.change_log.ajax_view.edit', compact('changelog'));
    }

    public function show($id)
    {
        $changelog = DB::table('change_logs')->where('id', $id)->first();

        return view('reports.change_log.ajax_view.show', compact('changelog'));
    }

    public function update(Request $request, $id)
    {
        $changelog = ChangeLog::find($id);
        $changelog->title = $request->title;
        $changelog->description = $request->description;

        $changelog->save();

        return response()->json(['Change log updated successfully!']);
    }

    public function delete($id)
    {
        $changelog = ChangeLog::find($id);
        try {
            unlink('uploads/changeLog/'.$changelog->image);
        } catch (\Exception $e) {
        }
        $changelog->delete();

        return response()->json(['Change log deleted successfully!']);
    }
}
