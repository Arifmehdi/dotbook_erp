<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Communication\Entities\SmsTemplate;
use Yajra\DataTables\Facades\DataTables;

class SmsTemplateController extends Controller
{
    public function smsBody(Request $request)
    {
        // putenv ("CUSTOM_VARIABLE=hero");
        // return env('CUSTOM_VARIABLE');

        if ($request->ajax()) {

            $sms = SmsTemplate::orderBy('id', 'DESC')->get();

            return DataTables::of($sms)

                ->addColumn('format_name', function ($row) {
                    $html = '';
                    $body_format = $row['body_format'];
                    $body_format = substr($row['body_format'], 0, 60);
                    $html = '<p><strong>'.$row['format_name'].'</strong> - '.strip_tags($body_format).'...</p>';

                    return $html;
                })

                ->addColumn('sms_subject', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['sms_subject'].'</strong> </p>';

                    return $html;
                })

                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="sms_id[]" value="'.$row->id.'" id="check1" class="mt-2 check1">
                                <label for="check1"></label>
                            </div>';

                    return $html;
                })

                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row['status'] == 1) {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.sms.body.important', [$row->id, 1]).'" id="status"><i class="fa-solid fa-star fa-lg"></i></a></div>';
                    } else {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.sms.body.important', [$row->id, 2]).'" id="status"><i class="fa-thin fa-star fa-lg"></i></a></div>';
                    }

                    return $html;
                })
                ->addColumn('view', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.sms.body.view', $row->id).'" id="smsBodyView"><i class="fa-solid fa-pen-to-square"></i></a></div>';

                    return $html;
                })
                ->addColumn('delete', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.sms.body.delete', $row->id).'" id="delete"><i class="fa-solid fa-trash-can"></i></a></div>';

                    return $html;
                })

                ->rawColumns(['format_name', 'sms_subject', 'check', 'status', 'delete', 'view'])
                ->make(true);
        }

        return view('communication::sms.sms-body');
    }

    public function smsBodyStore(Request $request)
    {
        $request->validate([
            'format_name' => 'required',
            'sms_subject' => 'required',
            // 'format_name' =>'required|unique:sms_templates,format_name',
        ]);

        $template = '';
        $template = SmsTemplate::where('id', $request->format_primary_id)->first();

        if (! $template) {
            $template = new SmsTemplate();
        }
        $template->format_name = $request->format_name;
        $template->sms_subject = $request->sms_subject;
        $template->body_format = $request->body_format;
        $template->save();

        return response()->json(['status' => 'success', 'template' => $template]);

    }

    public function view($id)
    {
        $template = SmsTemplate::find($id);

        return response()->json(['status' => 'view for edit sms body', 'template' => $template]);
    }

    public function importantBody(Request $request, $id, $flag)
    {
        $sms = SmsTemplate::find($id);
        if ($flag == 1) {
            $sms->status = false;
            $sms->save();

            return response()->json('This Sms Body is Marked as Unimportant');
        } else {
            $sms->status = true;
            $sms->save();

            return response()->json('This Sms Body is Marked as Important');
        }
    }

    public function deleteAllBody(Request $request)
    {
        if (! isset($request->sms_id)) {
            return response()->json(['errorMsg' => 'Select Sms first']);
        }
        foreach ($request->sms_id as $key => $items) {
            $sms = SmsTemplate::find($items);
            $sms->delete();
        }

        return response()->json(['errorMsg' => 'Sms Deleted Successfully']);
    }

    public function deleteBody(Request $request, $id)
    {
        $sms = SmsTemplate::find($id);
        $sms->delete();

        return response()->json(['errorMsg' => 'Sms Body Deleted Successfully']);
    }
}
