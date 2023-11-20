<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Communication\Entities\EmailServer;
use Yajra\DataTables\Facades\DataTables;

class EmailServerController extends Controller
{
    public function emailServerSetup(Request $request)
    {
        if ($request->ajax()) {
            $server = EmailServer::orderBy('id', 'DESC')->get();

            return DataTables::of($server)
                ->addColumn('status', function ($row) {
                    $html = '';
                    if ($row['status'] == 1) {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.email.server.active', [$row->id, 1]).'" id="status"><i class="fa-solid fa-check-to-slot"></i></a></div>';
                    } else {
                        $html .= '<div class="text-center"><a class="" href="'.route('communication.email.server.active', [$row->id, 2]).'" id="status"><i class="fa-regular fa-check-to-slot"></i></a></div>';
                    }

                    return $html;
                })
                ->addColumn('server_name', function ($row) {
                    $html = '';
                    $encryption = $row['encryption'];
                    $encryption = substr($row['encryption'], 0, 60);
                    $html = '<p><strong>'.$row['server_name'].'</strong> - '.strip_tags($encryption).'</p>';

                    return $html;
                })
                ->addColumn('host', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['host'].'</strong>'.'</p>';

                    return $html;
                })
                ->addColumn('port', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['port'].'</strong>'.'</p>';

                    return $html;
                })
                ->addColumn('user_name', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['user_name'].'</strong>'.'</p>';

                    return $html;
                })
                ->addColumn('password', function ($row) {
                    $html = '';
                    $html = '<p><strong>'.$row['password'].'</strong>'.'</p>';

                    return $html;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                <input type="checkbox" name="server_id[]" value="'.$row->id.'" id="check1" class="mt-2 check1">
                                <label for="check1"></label>
                            </div>';

                    return $html;
                })
                ->addColumn('edit', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.email.serve.edit', $row->id).'" id="emailServerEdit"><i class="fa-solid fa-pen-to-square"></i></a></div>';

                    return $html;
                })
                ->addColumn('delete', function ($row) {
                    $html = '';
                    $html .= '<div class="text-center"><a class="" href="'.route('communication.email.serve.delete', $row->id).'" id="delete"><i class="fa-solid fa-trash-can"></i></a></div>';

                    return $html;
                })
                ->rawColumns(['server_name', 'status', 'host', 'port', 'user_name', 'password', 'encryption', 'address', 'name', 'check', 'delete', 'edit'])
                ->make(true);
        }

        return view('communication::email.email-server-setup');
    }

    public function emailServerStore(Request $request)
    {
        $request->validate([
            'server_name' => 'required',
            'host' => 'required',
            'port' => 'required',
            'user_name' => 'required',
            'password' => 'required',
            'encryption' => 'required',
            // 'format_name' =>'required|unique:email_servers,server_name',
        ]);

        $serverCredential = '';
        $serverCredential = EmailServer::where('id', $request->mail_server_primary_id)->first();

        if (! $serverCredential) {
            $serverCredential = new EmailServer();
        }
        $serverCredential->server_name = $request->server_name;
        $serverCredential->host = $request->host;
        $serverCredential->port = $request->port;
        $serverCredential->user_name = $request->user_name;
        $serverCredential->password = $request->password;
        $serverCredential->encryption = $request->encryption;
        $serverCredential->address = $request->address;
        $serverCredential->name = $request->name;
        $serverCredential->save();

        return response()->json(['status' => 'success', 'template' => $serverCredential]);
    }

    public function activeServer(Request $request, $id, $flag)
    {
        $mails = EmailServer::find($id);
        if ($flag == 1) {
            $mails->status = false;
            $mails->save();

            return response()->json('This Mail Server is DeActive');
        } else {
            $mails->status = true;
            $mails->save();

            return response()->json('This Mail Server is Active');
        }
    }

    public function editServer($id)
    {
        $serverCredential = EmailServer::find($id);

        return response()->json(['status' => 'view for edit email serve', 'serverCredentialVal' => $serverCredential]);
    }

    public function deleteAllServer(Request $request)
    {
        if (! isset($request->server_id)) {
            return response()->json(['errorMsg' => 'Select mail first']);
        }
        foreach ($request->server_id as $key => $items) {
            $mails = EmailServer::find($items);
            $mails->delete();
        }

        return response()->json(['errorMsg' => 'Mail Server Deleted Successfully']);
    }

    public function deleteServe(Request $request, $id)
    {
        $mails = EmailServer::find($id);
        $mails->delete();

        return response()->json(['errorMsg' => 'Mail Server Deleted Successfully']);
    }
}
