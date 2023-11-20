<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Communication\Entities\EmailTemplate;
use Yajra\DataTables\Facades\DataTables;

class EmailTemplateController extends Controller
{
    public function emailBodyStore(Request $request)
    {
        $request->validate([
            'format_name' => 'required',
            'mail_subject' => 'required',
        ]);

        $template = '';
        $template = EmailTemplate::where('format_name', $request->format_name)->first();

        if (! $template) {
            $template = new EmailTemplate();
        }
        $template->format_name = $request->format_name;
        $template->mail_subject = $request->mail_subject;
        $template->body_format = $request->body_format;
        $template->save();

        return response()->json(['status' => 'success', 'template' => $template]);

    }

    public function emailBody(Request $request)
    {
        // putenv ("CUSTOM_VARIABLE=hero");
        // return env('CUSTOM_VARIABLE');

        if ($request->ajax()) {

            $email = EmailTemplate::all();

            return DataTables::of($email)

                ->addColumn('format_name', function ($row) {
                    $html = '';
                    $body_format = $row['body_format'];
                    $body_format = substr($row['body_format'], 0, 60);
                    $html = '<p><strong>'.$row['format_name'].'</strong> - '.strip_tags($body_format).'...</p>';

                    return $html;
                })

                ->addColumn('mail_subject', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['mail_subject'].'</strong> </p>';

                    return $html;
                })

                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="email_id[]" value="'.$row->id.'" id="check1" class="mt-2 check1">
                                <label for="check1"></label>
                            </div>';

                    return $html;
                })

                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row['status'] == 1) {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.email.body.important', [$row->id, 1]).'" id="status"><i class="fa-solid fa-star fa-lg"></i></a></div>';
                    } else {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.email.body.important', [$row->id, 2]).'" id="status"><i class="fa-thin fa-star fa-lg"></i></a></div>';
                    }

                    return $html;
                })
                ->addColumn('view', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.email.body.view', $row->id).'" id="emailBodyView"><i class="fa-sharp fa-solid fa-eye"></i></a></div>';

                    return $html;
                })
                ->addColumn('delete', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.email.body.delete', $row->id).'" id="delete"><i class="fa-solid fa-trash-can"></i></a></div>';

                    return $html;
                })

                ->rawColumns(['format_name', 'mail_subject', 'check', 'status', 'delete', 'view'])
                ->make(true);
        }

        return view('communication::email.email-body');
    }

    public function view($id)
    {
        $template = EmailTemplate::find($id);

        return response()->json(['status' => 'view email body', 'template' => $template]);
    }

    public function importantBody(Request $request, $id, $flag)
    {
        $mails = EmailTemplate::find($id);
        if ($flag == 1) {
            $mails->status = false;
            $mails->save();

            return response()->json('This Mail Body is Marked as Unimportant');
        } else {
            $mails->status = true;
            $mails->save();

            return response()->json('This Mail Body is Marked as Important');
        }
    }

    public function deleteAllBody(Request $request)
    {
        if (! isset($request->email_id)) {
            return response()->json(['errorMsg' => 'Select mail first']);
        }
        foreach ($request->email_id as $key => $items) {
            $mails = EmailTemplate::find($items);
            $mails->delete();
        }

        return response()->json(['errorMsg' => 'Mail Deleted Successfully']);
    }

    public function deleteBody(Request $request, $id)
    {
        $mails = EmailTemplate::find($id);
        $mails->delete();

        return response()->json(['errorMsg' => 'Mail Body Deleted Successfully']);
    }
}
