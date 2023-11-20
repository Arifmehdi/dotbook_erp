<?php

namespace App\Http\Controllers\Utilities;

use App\Http\Controllers\Controller;
use App\Interface\CodeGenerationServiceInterface;
use App\Interface\FileUploaderServiceInterface;
use App\Models\Utilities\DownloadManager;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DownloadManagerController extends Controller
{
    protected $invoiceVoucherRefIdUtil;

    public function __construct(InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $downloads = '';
            $downloads = DownloadManager::all();

            return DataTables::of($downloads)
                ->addColumn('action', function ($row) {

                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    $html .= '<a class="dropdown-item" href="'.route('downloads.download.view', [$row->id]).'" id="view"><i class="fa-regular fa-eye"></i> View</a>';
                    $html .= '<a class="dropdown-item" href="'.route('downloads.download.edit', [$row->id]).'" id="edit"><i class="fa-regular fa-edit"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" href="'.asset('uploads/downloads/'.$row->file).'" download><i class="fa-regular fa-download"></i> Download</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('downloads.download.destroy', [$row->id]).'"><i class="fa-regular fa-trash-alt"></i> Delete</a>';

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('type', function ($row) {
                    $html = '';
                    $file = explode('.', $row['file']);
                    $extension = end($file);
                    $image_extensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG', 'webp', 'jfif', 'pjpeg', 'pjp'];
                    $pdf_extensions = ['pdf'];
                    $audio_extensions = ['WAV', 'AIF', 'MP3', 'mp3', 'wav', 'aif'];
                    $video_extensions = ['MP4', 'mp4', 'MOV'.'mov', 'WMV', 'wmv', 'AVI', 'avi', 'AVCHD', 'avchd', 'FLV', 'flv', 'F4V', 'f4v', 'SWF', 'swf', 'MKV', 'mkv'];
                    $text_extension = ['txt', 'TXT', 'ltxd', 'LTXD', 'md'];
                    $database_extension = ['sql'];
                    $doc_extension = ['doc', 'DOC', 'docx'];
                    $excel_extension = ['xlsx'];
                    $csv_extension = ['csv'];
                    $gif_extension = ['gif'];
                    if (in_array($extension, $image_extensions)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-image-landscape fa-2x"></i></div>';
                    } elseif (in_array($extension, $pdf_extensions)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-file-pdf fa-2x"></i></div>';
                    } elseif (in_array($extension, $audio_extensions)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-file-music fa-2x"></i></div>';
                    } elseif (in_array($extension, $video_extensions)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-photo-film fa-2x"></i></div>';
                    } elseif (in_array($extension, $text_extension)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-file-lines fa-2x"></i></div>';
                    } elseif (in_array($extension, $doc_extension)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-file-word fa-2x"></i></div>';
                    } elseif (in_array($extension, $excel_extension)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-file-excel fa-2x"></i></div>';
                    } elseif (in_array($extension, $csv_extension)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-file-csv fa-2x"></i></div>';
                    } elseif (in_array($extension, $gif_extension)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-gif fa-2x"></i></div>';
                    } elseif (in_array($extension, $database_extension)) {
                        $html .= '<div class="text-center"><i class="fa-thin fa-database fa-2x"></i></div>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'file', 'type'])
                ->make(true);
        }
        $downloads = DownloadManager::all();

        return view('utilities.downloads.index', [
            'downloads' => $downloads,
        ]);
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService, FileUploaderServiceInterface $fileUploaderService)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required',
            'file' => 'required',
        ]);

        $downloads = new DownloadManager();

        $downloads->code = $codeGenerationService->generate('download_managers', 'code', 'DC');

        $downloads->title = $request->title;
        $downloads->date = date('Y-m-d', strtotime($request->date));
        $downloads->description = $request->description;

        if ($request->hasFile('file')) {
            $downloads->file = $fileUploaderService->upload($request->file('file'), 'uploads/downloads/');
        }

        $downloads->save();

        return response()->json('File uploaded successfully');
    }

    public function edit($id)
    {

        $downloads = DownloadManager::find($id);

        return view('utilities.downloads.ajax_view.edit', [
            'downloads' => $downloads,
        ]);
    }

    public function update(Request $request, $id, CodeGenerationServiceInterface $codeGenerationService, FileUploaderServiceInterface $fileUploaderService)
    {

        $request->validate([
            'e_title' => 'required',
            'e_date' => 'required',
            'e_description' => 'required',
        ]);

        $downloads = DownloadManager::find($id);
        $old_photo = $downloads->file;
        $downloads->title = $request->e_title;
        $downloads->date = date('Y-m-d', strtotime($request->e_date));
        $downloads->description = $request->e_description;
        if ($request->file('e_file')) {
            if (file_exists('uploads/downloads/'.$old_photo)) {

                unlink(public_path('uploads/downloads/'.$old_photo));
            }
            $downloads->file = $fileUploaderService->upload($request->file('e_file'), 'uploads/downloads/');
        }
        $downloads->save();

        return response()->json('File updated successfully');
    }

    public function destroy($id)
    {
        $downloads = DownloadManager::find($id);
        $file = $downloads->file;
        unlink(public_path('uploads/downloads/'.$file));
        $downloads->delete();

        return response()->json(['errorMsg' => 'Downloads deleted successfully']);
    }

    public function view($id)
    {
        $downloads = DownloadManager::find($id);

        return view('utilities.downloads.ajax_view.view', [
            'downloads' => $downloads,
        ]);
    }
}
