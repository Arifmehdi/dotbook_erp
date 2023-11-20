<?php

namespace Modules\Asset\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\Allocation;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\Revoke;
use Yajra\DataTables\Facades\DataTables;

class RevokeController extends Controller
{
    public function index(Request $request)
    {

        if (! auth()->user()->can('asset_revokes_index')) {
            abort(403, 'Access denied.');
        }

        $generalSettings = DB::table('general_settings')->first();

        $revokes = Revoke::with(['allocation.asset:id,asset_name', 'allocation.allocated_to_user:id,prefix,name,last_name', 'revokedBy:id,prefix,name,last_name'])->get();

        if ($request->ajax()) {

            return DataTables::of($revokes)

                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('asset_revokes_update')) {
                        $html .= '<a class="dropdown-item" id="edit" href="'.route('assets.revoke.edit', [$row->id]).'"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->can('asset_revokes_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('assets.revoke.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })

                ->editColumn('allocated_to_user', function ($row) {
                    return $row->allocation->allocated_to_user ? $row->allocation->allocated_to_user->prefix.' '.$row->allocation->allocated_to_user->name.' '.$row->allocation->allocated_to_user->last_name : '';
                })

                ->editColumn('revoke_date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->revoke_date));
                })

                ->editColumn('asset_name', function ($row) {
                    return $row->allocation->asset ? $row->allocation->asset->asset_name : '';
                })

                ->editColumn('revokedBy', function ($row) {
                    return $row->revokedBy ? $row->revokedBy->prefix.' '.$row->revokedBy->name.' '.$row->revokedBy->last_name : '';
                })

                ->rawColumns(['action', 'allocated_to_user',  'asset_name', 'revokedBy', 'revoked_date'])

                ->make(true);
        }

        return view('asset::Revoke.index');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('asset_revokes_update')) {
            abort(403, 'Access denied.');
        }

        $revoke = Revoke::where('id', $id)->first();
        // return $revoke;
        $users = User::select('id', 'prefix', 'name', 'last_name')->get();

        $asset_id = Asset::select('id', 'asset_name')->get();

        return view('asset::Revoke.ajax_view.edit', [
            'asset_id' => $asset_id,
            'users' => $users,
            'revoke' => $revoke,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('asset_revokes_update')) {

            abort(403, 'Access denied.');
        }

        $request->validate([
            'quantity' => 'required',
            'date' => 'required',
        ]);

        $revoke = Revoke::with('allocation', 'allocation.revokes', 'asset', 'asset.unit')->where('id', $id)->first();

        $totalRevoked = 0;

        if (count($revoke->allocation->revokes) > 0) {

            foreach ($revoke->allocation->revokes as $revoke) {

                $totalRevoked += $revoke->quantity;
            }
        }

        $old_revoke_quantity = $revoke->quantity;
        $current_revoke_quantity = $request->quantity;

        $allocation = Allocation::where('asset_id', $revoke->asset->id)->get();

        $allocationQty = 0;

        foreach ($allocation as $key => $single_allocation) {
            $allocationQty += $single_allocation->quantity;
        }

        $current_allocatable_quantity = $revoke->asset->quantity - ($allocationQty - $totalRevoked);

        // current_allocatable_quantity = $asset_quantity - ($allocationQty - $totalRevoked)
        // current allocatable asset quantity >= revoke quantity - revoke edit quantity   success $current_allocatable_quantity >= $old_revoke_quantity - $current_revoke_quantity

        if ($current_allocatable_quantity < ($old_revoke_quantity - $current_revoke_quantity)) {
            return response()->json('Insufficient asset quantity for revoke');

        }
        $allocation_info = Allocation::where('asset_id', $revoke->asset->id)->first();

        $allocation_start_date = date('Y-m-d', strtotime($allocation_info->start_date));
        $allocation_end_date = date('Y-m-d', strtotime($allocation_info->end_date));
        $revoke_date = date('Y-m-d', strtotime($request->date));

        if ($allocation_start_date > $revoke_date || $allocation_end_date < $revoke_date) {
            return response()->json(['errorMsg' => 'Please select date between '.$allocation_start_date.' to '.$allocation_end_date]);

        }

        $allocatedQty = $revoke->allocation->quantity;

        $compareQty = $allocatedQty - $totalRevoked + $revoke->quantity;
        if ($request->quantity > $compareQty) {

            return response()->json(['errorMsg' => 'Only '.$compareQty.' '.$revoke->asset->unit->name.' is available For revoking.']);
        }

        $revoke->quantity = $request->quantity;
        $revoke->revoke_date = date('Y-m-d', strtotime($request->date));
        $revoke->reason = $request->description;
        $revoke->save();

        return response()->json('Revoke updated successfully');
    }

    public function destroy(Request $request)
    {
        if (! auth()->user()->can('asset_revokes_delete')) {

            abort(403, 'Access denied.');
        }

        $revoke = Revoke::find($request->id);
        $revoke_quantity = $revoke->quantity;
        $asset_id = $revoke->asset_id;

        $allocation = Allocation::where('asset_id', $asset_id)->get();

        $allocationQty = 0;

        foreach ($allocation as $key => $single_allocation) {
            $allocationQty += $single_allocation->quantity;
        }

        $current_allocation = $allocationQty - $revoke_quantity;

        $asset = Asset::find($asset_id);

        if (($asset->quantity - $current_allocation) < $revoke_quantity) {
            return response()->json('Insufficient asset quantity for revoke');

        }
        $revoke->delete();

        return response()->json('Revoke deleted successfully');
    }
}
