<?php

namespace App\Http\Controllers;

use App\Interface\FileUploaderServiceInterface;
use App\Models\Announcement;
use App\Service\FileUploaderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    public function announcement(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $announcement = DB::table('announcements')->orderBy('id', 'DESC')->get();

            return DataTables::of($announcement)
                ->addIndexColumn()
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown" id="accordion">';
                    $html .= '<a href="'.route('announcements.edit', $row->id).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href=" '.route('announcements.show', $row->id).'" class="action-btn details_button c-show" title="show"><span class="far fa-eye text-success"></span></a>';
                    $html .= '<a href="'.route('announcements.delete', $row->id).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('created_at', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->created_at));
                })
                ->make(true);
        }
        $announcement = DB::table('announcements')->get();

        return view('reports.announcement.index', compact('announcement'));
    }

    // Announcement create
    public function announcementStore(Request $request, FileUploaderServiceInterface $uploader)
    {
        $announcementPhotoName = null;
        if ($request->hasFile('files')) {
            $announcementPhotoName = $uploader->upload($request->file('files'), 'uploads/announcement/');
        }
        $announcement = Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
            'files' => $announcementPhotoName,
        ]);

        return response()->json('Announcement created successfully!');
    }

    public function announcementDelete(Request $request, $id)
    {
        $deleteannouncement = Announcement::find($id);
        try {
            if (file_exists('uploads/announcement/'.$deleteannouncement->files)) {
                unlink('uploads/announcement/'.$deleteannouncement->files);
            }
        } catch (\Exception $e) {
        }
        $deleteannouncement->delete();

        return response()->json(['Announcement deleted successfully!']);
    }

    public function announcementEdit($id)
    {
        $announcement = DB::table('announcements')->where('id', $id)->first();

        return view('reports.announcement.ajax_view.edit', compact('announcement'));
    }

    public function announcementShow($id)
    {
        $announcement = DB::table('announcements')->where('id', $id)->first();

        return view('reports.announcement.ajax_view.show', compact('announcement'));
    }

    public function announcementUpdate(Request $request, $id, FileUploaderService $uploader)
    {
        $announcement = Announcement::find($id);
        $announcement->title = $request->title;
        $announcement->description = $request->description;
        if ($request->hasFile('files')) {
            $newFile = $uploader->upload($request->file('files'), 'uploads/announcement/');
            if (isset($announcement->files) && file_exists(public_path('uploads/announcement/'.$announcement->files))) {
                try {
                    unlink(public_path('uploads/announcement/'.$announcement->files));
                } catch (Exception $e) {
                }
            }
            $announcement->files = $newFile;
        }
        $announcement->save();

        return response()->json('Announcement updated successfully!');
    }

    public function printAnnouncement($id)
    {
        $announcement = Announcement::find($id);
        $pdf = PDF::loadView('reports.announcement.print', compact('announcement'));
        $pdf->stream('announcement.pdf');
    }
}
