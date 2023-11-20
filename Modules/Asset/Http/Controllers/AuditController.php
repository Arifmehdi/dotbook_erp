<?php

namespace Modules\Asset\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\Audit;
use Yajra\DataTables\Facades\DataTables;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_audits_index')) {
            abort(403, 'Access denied.');
        }

        if ($request->ajax()) {
            $audit = '';
            $query = Audit::with(
                [
                    'auditor:id,name',
                    'asset:id,asset_name',
                ]
            );
            if ($request->f_auditors) {
                $query->where('auditor_id', $request->f_auditors);
            }
            if ($request->f_asset) {
                $query->where('asset_id', $request->f_asset);
            }
            if ($request->f_status) {
                $query->where('status', $request->f_status);
            }

            if ($request->f_audit_start_date) {

                $from_date = date('Y-m-d', strtotime($request->f_audit_start_date));
                $to_date = $request->f_audit_end_date ? date('Y-m-d', strtotime($request->f_audit_end_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('audit_date', $date_range); // Final
            }

            $audit = $query->get();

            return DataTables::of($audit)
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('asset_audits_update')) {
                        $html .= '<a class="dropdown-item" href="'.route('assets.audit.edit', [$row->id]).'" id="edit_id"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('asset_audits_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('assets.audit.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('auditor', function ($row) {

                    return $row->auditor->name ?? 'N/A';
                })
                ->editColumn('asset', function ($row) {

                    return $row->asset->asset_name ?? 'N/A';
                })
                ->editColumn('status', function ($row) {

                    return ($row->status == 1) ? 'Accepted' : 'Rejected';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $users = User::all();
        $assets = Asset::all();

        return view('asset::audit.index', [
            'users' => $users,
            'assets' => $assets,

        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('asset_audits_create')) {
            abort(403, 'Access denied.');
        }
        $request->validate([
            'title' => 'required',
            'auditor_id' => 'required',
            'asset_id' => 'required',
            'status' => 'required',
            'audit_date' => 'required',
        ]);

        $asset = Asset::find($request->asset_id);

        if (date('Y-m-d', strtotime($asset->purchase_date)) > date('Y-m-d', strtotime($request->audit_date))) {
            return response()->json(['errorMsg' => 'Please enter a date after product purchased']);
        }

        $audit = new Audit;
        $audit->title = $request->title;
        $audit->auditor_id = $request->auditor_id;
        $audit->asset_id = $request->asset_id;
        $audit->status = $request->status;
        $audit->audit_date = date('Y-m-d', strtotime($request->audit_date));
        $audit->reason = $request->reason;
        $audit->save();

        return response()->json('Audit created successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('asset_audits_update')) {
            abort(403, 'Access denied.');
        }
        $users = User::all();
        $assets = Asset::all();
        $all_audits = DB::table('audits')->where('id', $id)->first();

        return view('asset::audit.ajax_view.edit', [
            'users' => $users,
            'assets' => $assets,
            'all_audits' => $all_audits,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('asset_audits_update')) {
            abort(403, 'Access denied.');
        }
        $request->validate([
            'title' => 'required',
            'auditor_id' => 'required',
            'asset_id' => 'required',
            'status' => 'required',
            'audit_date' => 'required',
            'reason' => 'required',
        ]);

        $edit_audit = Audit::where('id', $id)->first();
        $edit_audit->title = $request->title;
        $edit_audit->auditor_id = $request->auditor_id;
        $edit_audit->asset_id = $request->asset_id;
        $edit_audit->status = $request->status;
        $edit_audit->audit_date = date('Y-m-d', strtotime($request->audit_date));
        $edit_audit->reason = $request->reason;
        $edit_audit->updated_at = Carbon::now();
        $edit_audit->save();

        return response()->json('Audit updated successfully');
    }

    public function delete($id)
    {
        if (! auth()->user()->can('asset_audits_delete')) {
            abort(403, 'Access denied.');
        }
        $audit = Audit::find($id);
        $audit->delete();

        return response()->json('Audit deleted successfully');
    }
}
