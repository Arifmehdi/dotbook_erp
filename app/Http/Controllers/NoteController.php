<?php

namespace App\Http\Controllers;

use App\Interface\FileUploaderServiceInterface;
use App\Models\NoticeBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $notice = DB::table('notice_boards')->orderBy('id', 'DESC')->get();

            return DataTables::of($notice)
                ->addIndexColumn()
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown" id="accordion">';
                    $html .= '<a href="'.route('notice_boards.edit', $row->id).'" class="action-btn c-edit ckEditor" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href=" '.route('notice_boards.show', $row->id).'" class="action-btn details_button c-show" title="show"><span class="far fa-eye text-success"></span></a>';
                    $html .= '<a href="'.route('notice_boards.delete', $row->id).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('created_at', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->created_at));
                })
                ->make(true);
        }
        $notice = DB::table('notice_boards')->get();

        return view('note.index', compact('notice'));
    }

    public function store(Request $request, FileUploaderServiceInterface $uploader)
    {
        $notice = null;
        if ($request->hasFile('files')) {
            $notice = $uploader->upload($request->file('files'), 'uploads/notice/');
        }
        $noticeboard = NoticeBoard::create([
            'title' => $request->title,
            'description' => $request->description,
            'files' => $notice,
        ]);

        return response()->json('Notice board created successfully!');
    }

    public function show($id)
    {
        $notice = DB::table('notice_boards')->where('id', $id)->first();

        return view('notice_boards.ajax_view.show', compact('notice'));
    }

    public function edit($id)
    {
        $notice = DB::table('notice_boards')->where('id', $id)->first();

        return view('notice_boards.ajax_view.edit', compact('notice'));
    }

    public function update(Request $request, $id, FileUploaderServiceInterface $uploader)
    {
        $notice = NoticeBoard::find($id);
        $notice->title = $request->title;
        $notice->description = $request->description;
        if ($request->hasFile('files')) {
            $newFile = $uploader->upload($request->file('files'), 'uploads/notice/');
            if ($notice->files != null) {
                if (file_exists(public_path('uploads/notice/'.$notice->files))) {
                    try {
                        unlink(public_path('uploads/notice/'.$notice->files));
                    } catch (Exception $e) {
                    }
                }
            }

            $notice->files = $newFile;
        }
        $notice->save();

        return response()->json('Notice updated successfully!');
    }

    public function noticeDelete(Request $request, $id)
    {
        $notice = NoticeBoard::find($id);
        try {
            unlink('uploads/notice/'.$notice->files);
        } catch (\Exception $e) {
        }
        $notice->delete();

        return response()->json(['Notice board deleted successfully!']);
    }

    public function printNotice($id)
    {
        $notice = NoticeBoard::find($id);
        $pdf = PDF::loadView('notice_boards.print', compact('notice'));
        $pdf->stream('notice.pdf');
    }
}
