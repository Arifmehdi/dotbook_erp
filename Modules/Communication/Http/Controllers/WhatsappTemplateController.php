<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Communication\Entities\WhatsappTemplate;
use Yajra\DataTables\Facades\DataTables;

class WhatsappTemplateController extends Controller
{
    public function whatsappBody(Request $request)
    {
        if ($request->ajax()) {
            $whatsapp = WhatsappTemplate::orderBy('id', 'DESC')->get();

            return DataTables::of($whatsapp)

                ->addColumn('format_name', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['format_name'].'</strong> '.'</p>';

                    return $html;
                })

                ->addColumn('body_format', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['body_format'].'</strong> - '.'</p>';

                    return $html;
                })

                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="whatsapp_id[]" value="'.$row->id.'" id="check1" class="mt-2 check1">
                                <label for="check1"></label>
                            </div>';

                    return $html;
                })

                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row['status'] == 1) {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.whatsapp.body.important', [$row->id, 1]).'" id="status"><i class="fa-solid fa-star fa-lg"></i></a></div>';
                    } else {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.whatsapp.body.important', [$row->id, 2]).'" id="status"><i class="fa-thin fa-star fa-lg"></i></a></div>';
                    }

                    return $html;
                })
                ->addColumn('view', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.whatsapp.body.view', $row->id).'" id="whatsappBodyView"><i class="fa-solid fa-pen-to-square"></i></a></div>';

                    return $html;
                })
                ->addColumn('delete', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.whatsapp.body.delete', $row->id).'" id="delete"><i class="fa-solid fa-trash-can"></i></a></div>';

                    return $html;
                })

                ->rawColumns(['format_name', 'body_format', 'check', 'status', 'delete', 'view'])
                ->make(true);
        }

        return view('communication::whatsapp.whatsapp-body');
    }

    public function whatsappBodyStore(Request $request)
    {
        $request->validate([
            'body_format' => 'required',
            'format_name' => 'required',
            // 'format_name' =>'required|unique:whatsapp_templates,format_name',
        ]);

        $template = '';
        $template = WhatsappTemplate::where('id', $request->format_primary_id)->first();

        if (! $template) {
            $template = new WhatsappTemplate();
        }
        $template->format_name = $request->format_name;
        $template->body_format = $request->body_format;
        $template->save();

        return response()->json(['status' => 'success', 'template' => $template]);

    }

    public function view($id)
    {
        $template = WhatsappTemplate::find($id);

        return response()->json(['status' => 'view for edit Whatsapp body', 'template' => $template]);
    }

    public function importantBody(Request $request, $id, $flag)
    {
        $whatsapp = WhatsappTemplate::find($id);
        if ($flag == 1) {
            $whatsapp->status = false;
            $whatsapp->save();

            return response()->json('This Whatsapp Body is Marked as Unimportant');
        } else {
            $whatsapp->status = true;
            $whatsapp->save();

            return response()->json('This Whatsapp Body is Marked as Important');
        }
    }

    public function deleteAllBody(Request $request)
    {
        if (! isset($request->whatsapp_id)) {
            return response()->json(['errorMsg' => 'Select whatsapp first']);
        }
        foreach ($request->whatsapp_id as $key => $items) {
            $whatsapp = WhatsappTemplate::find($items);
            $whatsapp->delete();
        }

        return response()->json(['errorMsg' => 'Whatsapp Deleted Successfully']);
    }

    public function deleteBody(Request $request, $id)
    {
        $whatsapp = WhatsappTemplate::find($id);
        $whatsapp->delete();

        return response()->json(['errorMsg' => 'Whatsapp Body Deleted Successfully']);
    }
}
