<?php

namespace Modules\Asset\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\Allocation;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\Revoke;
use Yajra\DataTables\Facades\DataTables;

class AllocationController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_allocation_index')) {

            abort(403, 'Access denied.');
        }

        $asset_id = Asset::select('id', 'asset_name')->get();

        $users = User::select('id', 'prefix', 'name', 'last_name')->get();

        if ($request->ajax()) {

            $asset_allocations = '';
            $query = Allocation::with([
                'asset',
                'asset.unit:id,name',
                'asset.category:id,name',
                'allocated_to_user:id,prefix,name,last_name',
                'createdBy:id,prefix,name,last_name',
                'revokes',
            ]);

            if ($request->f_from_date) {
                $from_date = date('Y-m-d', strtotime($request->f_from_date));
                $to_date = $request->f_to_date ? date('Y-m-d', strtotime($request->f_to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('start_date', $date_range); // Final
            }

            if ($request->allocation_id) {

                $query->where('id', $request->allocation_id);
            }

            if ($request->f_asset_id) {
                $query->where('asset_id', $request->f_asset_id);
            }

            $generalSettings = DB::table('general_settings')->first();

            $asset_allocations = $query->orderBy('id', 'desc')->get();

            return DataTables::of($asset_allocations)

                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('asset_allocation_update')) {
                        $html .= '<a class="dropdown-item" href="'.route('assets.allocation.edit', [$row->id]).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->can('asset_allocation_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('assets.allocation.destroy', [$row->id, $row->asset_id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    if (auth()->user()->can('asset_revokes_index')) {
                        $html .= '<a class="dropdown-item" id="revoke" href="'.route('assets.allocation.revoke', [$row->id]).'"><i class="fa fa-undo text-primary"></i> Revoke</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })

                ->editColumn('allocation', function ($row) {
                    return $row?->allocated_to_user?->prefix.' '.$row?->allocated_to_user?->name.' '.$row?->allocated_to_user?->last_name ?? 'N/A';
                })

                ->editColumn('asset_name', function ($row) {
                    return $row?->asset?->asset_name ?? 'N/A';
                })

                ->editColumn('revokedBy', function ($row) {
                    return $row?->revokedBy?->name ?? 'N/A';
                })

                ->editColumn('category', function ($row) {
                    return $row?->asset?->category?->name ?? 'N/A';
                })

                ->editColumn('createdBy', function ($row) {
                    return $row?->createdBy?->name ?? 'N/A';
                })

                ->editColumn('start_date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->start_date));
                })
                ->editColumn('end_date', function ($row) use ($generalSettings) {
                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->end_date));
                })
                ->editColumn('allocated_quantity', function ($row) {
                    return $row->quantity.'/'.$row?->asset?->unit?->name;
                })

                ->editColumn('revoked_quantity', function ($row) {
                    $totalRevoke = 0;
                    if (count($row->revokes) > 0) {

                        foreach ($row->revokes as $revoke) {

                            $totalRevoke += $revoke->quantity;
                        }
                    }

                    return $totalRevoke.'/'.$row?->asset?->unit?->name;
                })

                ->editColumn('current_allocation_qty', function ($row) {
                    $totalRevoke = 0;
                    if (count($row->revokes) > 0) {

                        foreach ($row->revokes as $revoke) {

                            $totalRevoke += $revoke->quantity;
                        }
                    }

                    return $row->quantity - $totalRevoke.'/'.$row?->asset?->unit?->name;
                })
                ->rawColumns(['action', 'allocation', 'current_allocation_qty', 'asset_name', 'revokedBy', 'category', 'start_date', 'end_date', 'allocated_quantity', 'revoked_quantity'])
                ->smart(true)
                ->make(true);
        }

        $allocation = Allocation::all();
        $asset_id = Asset::all();

        return view('asset::allocation.index', [
            'asset_id' => $asset_id,
            'users' => $users,
            'allocation' => $allocation,
        ]);
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (! auth()->user()->can('asset_allocation_create')) {

            abort(403, 'Access denied.');
        }

        $request->validate([
            'quantity' => 'required',
            'allocated_to' => 'required',
            'asset_id' => 'required',
            'allocated_from' => 'required',
            'allocated_upto' => 'required',
        ]);
        $asset = Asset::with('allocations', 'revokes', 'unit')->where('id', $request->asset_id)->first();
        $allocation_permission = $asset->is_allocatable;
        if ($allocation_permission == 0) {

            return response()->json(['errorMsg' => 'This asset is not allowed to allocate !!']);
        }
        $purchase_date = $asset->purchase_date;
        $expire_date = $asset->expire_date;
        $allocated_date_start = date('Y-m-d', strtotime($request->allocated_from));
        $allocated_date_end = date('Y-m-d', strtotime($request->allocated_upto));

        if ($expire_date) {
            if ($allocated_date_start > $allocated_date_end) {
                return response()->json(['errorMsg' => 'Please select a valid date']);
            }
            if ($purchase_date > $allocated_date_start || $expire_date < $allocated_date_start) {
                return response()->json(['errorMsg' => 'Please select a date after '.$purchase_date.' to '.$expire_date]);
            }
            if ($purchase_date > $allocated_date_end || $expire_date < $allocated_date_end) {
                return response()->json(['errorMsg' => 'Please select a date after '.$purchase_date.' to '.$expire_date]);
            }
        } else {
            if ($purchase_date > $allocated_date_start) {
                return response()->json(['errorMsg' => 'Please select a date after '.$purchase_date]);
            }
        }
        $totalAllocated = 0;
        if (count($asset->allocations) > 0) {
            foreach ($asset->allocations as $allocation) {
                $totalAllocated += $allocation->quantity;
            }
        }

        $totalRevoked = 0;

        if (count($asset->revokes) > 0) {

            foreach ($asset->revokes as $revoke) {
                $totalRevoked += $revoke->quantity;
            }
        }

        $assetQty = $asset->quantity;

        $compareQty = $assetQty - ($totalAllocated - $totalRevoked);

        // check quantity
        if ($request->quantity > $compareQty) {
            return response()->json(['errorMsg' => 'Asset quantity only '.$compareQty.' '.$asset->unit->name.' is available.']);
        }
        $assetAllocation = new Allocation();
        $assetAllocation->code = $codeGenerationService->generate('asset_allocations', 'code', 'AL');
        $assetAllocation->asset_id = $request->asset_id;
        $assetAllocation->allocated_to = $request->allocated_to;
        $assetAllocation->quantity = $request->quantity;
        $assetAllocation->start_date = date('Y-m-d', strtotime($request->allocated_from));
        $assetAllocation->end_date = date('Y-m-d', strtotime($request->allocated_upto));
        $assetAllocation->description = $request->description;
        $assetAllocation->created_by_id = auth()->user()->id;
        $assetAllocation->save();

        $asset_info = Asset::where('id', $request->asset_id)->first();
        $asset_info->is_allocated = 1;
        $asset_info->save();

        return response()->json('Allocation created successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('asset_allocation_update')) {
            abort(403, 'Access denied.');
        }
        $allocation = Allocation::where('id', $id)->first();

        $asset_id = Asset::where('id', $allocation->asset_id)->first();

        $users = User::select('id', 'prefix', 'name', 'last_name')->get();

        return view('asset::allocation.ajax_view.edit', [
            'allocation' => $allocation,
            'asset_id' => $asset_id,
            'users' => $users,

        ]);
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('asset_allocation_update')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'quantity' => 'required',
            'allocated_to' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $asset_allocation = Allocation::where('id', $id)->first();

        $asset_id = $asset_allocation->asset_id;

        $asset = Asset::with('allocations', 'revokes', 'unit')->where('id', $asset_id)->first();

        $purchase_date = $asset->purchase_date;
        $expire_date = $asset->expire_date;
        $allocated_date_start = date('Y-m-d', strtotime($request->start_date));
        $allocated_date_end = date('Y-m-d', strtotime($request->end_date));

        if ($expire_date) {
            if ($allocated_date_start > $allocated_date_end) {
                return response()->json(['errorMsg' => 'Please select a date before '.date('Y-m-d', strtotime('+1 day', strtotime($allocated_date_end)))]);
            }

            if ($purchase_date > $allocated_date_start) {
                return response()->json(['errorMsg' => 'Please select a date after '.$purchase_date.' to '.$expire_date]);
            }

            if ($purchase_date > $allocated_date_end || $expire_date < $allocated_date_end) {
                return response()->json(['errorMsg' => 'Please select a date after '.$purchase_date.' to '.$expire_date]);
            }
        } else {
            if ($purchase_date > $allocated_date_start) {
                return response()->json(['errorMsg' => 'Please select a date after '.$purchase_date]);
            }
        }

        $totalAllocated = 0;
        if (count($asset->allocations) > 0) {

            foreach ($asset->allocations as $allocation) {

                $totalAllocated += $allocation->quantity;
            }
        }

        $totalRevoked = 0;
        if (count($asset->revokes) > 0) {

            foreach ($asset->revokes as $revoke) {

                $totalRevoked += $revoke->quantity;
            }
        }

        $assetQty = $asset->quantity + $asset_allocation->quantity;

        $compareQty = $assetQty - ($totalAllocated - $totalRevoked);

        $showQuantity = (($asset->quantity - ($totalAllocated - $totalRevoked)) + ($totalAllocated - $totalRevoked)) - $totalAllocated;

        if ($request->quantity > $compareQty) {

            return response()->json(['errorMsg' => 'Asset quantity only '.$showQuantity.' '.$asset->unit->name.' is available.']);
        }

        $asset_allocation->allocated_to = $request->allocated_to;
        $asset_allocation->quantity = $request->quantity;
        $asset_allocation->start_date = date('Y-m-d', strtotime($request->start_date));
        $asset_allocation->end_date = date('Y-m-d', strtotime($request->end_date));
        $asset_allocation->description = $request->description;
        $asset_allocation->save();

        return response()->json('Allocation updated successfully');
    }

    public function destroy(Request $request, $allocationId, $asset_id)
    {
        if (! auth()->user()->can('asset_allocation_delete')) {

            abort(403, 'Access denied.');
        }

        $asset_allocation = Allocation::find($allocationId);

        $revokes = Revoke::where('allocation_id', $allocationId)->first();

        if (isset($revokes)) {
            return response()->json(['errorMsg' => 'Allocation saved in revoke part!']);
        }

        $all_allocations = Allocation::where('asset_id', $request->asset_id)->get();
        $count = 0;
        foreach ($all_allocations as $key => $allocation) {
            $count++;
        }

        if ($count == 1) {
            $asset_info = Asset::where('id', $request->asset_id)->first();
            $asset_info->is_allocated = 0;
            $asset_info->save();
        }

        $asset_allocation->delete();

        return response()->json(['errorMsg' => 'Allocation deleted successfully']);
    }

    public function revoke_index($id)
    {
        $allocation = Allocation::where('id', $id)->first();
        $revoke = Revoke::all();

        return view('asset::allocation.ajax_view.revoke', [
            'allocation' => $allocation,
            'revoke' => $revoke,
        ]);
    }

    public function revoke_insert(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (! auth()->user()->can('asset_revokes_create')) {
            abort(403, 'Access denied.');
        }

        $id = $request->allocation_id;

        $request->validate([
            'quantity' => 'required',
            'revoke_date' => 'required',
        ]);

        $allocation = Allocation::with(['revokes', 'asset', 'asset.unit'])->where('id', $id)->first();

        $allocation_start_date = date('Y-m-d', strtotime($allocation->start_date));
        $allocation_end_date = date('Y-m-d', strtotime($allocation->end_date));
        $revoke_date = date('Y-m-d', strtotime($request->revoke_date));

        if ($allocation_start_date > $revoke_date || $allocation_end_date < $revoke_date) {
            return response()->json(['errorMsg' => 'Please select date between '.$allocation_start_date.' to '.$allocation_end_date]);
        }

        $allocatedQty = $allocation->quantity;

        $totalRevoked = 0;

        if (count($allocation->revokes) > 0) {

            foreach ($allocation->revokes as $revoke) {

                $totalRevoked += $revoke->quantity;
            }
        }

        $compareQty = $allocatedQty - $totalRevoked;

        if ($request->quantity > $compareQty) {
            return response()->json(['errorMsg' => 'Only '.$compareQty.' '.$allocation->asset->unit->name.' is available For revoking.']);
        }

        if ($compareQty == $request->quantity) {
            $asset_info = Asset::where('id', $allocation->asset_id)->first();
            $asset_info->is_allocated = 0;
            $asset_info->save();
        }

        $revoke = new Revoke();
        $revoke->revoke_code = $codeGenerationService->generate('asset_revokes', 'revoke_code', 'AR');
        $revoke->allocation_id = $request->allocation_id;
        $revoke->asset_id = $allocation->asset_id;
        $revoke->quantity = $request->quantity;
        $revoke->revoke_by_id = auth()->user()->id;
        $revoke->revoke_date = date('Y-m-d', strtotime($request->revoke_date));
        $revoke->reason = $request->description;
        $revoke->save();

        return response()->json('Allocation revoked successfully');
    }
}
