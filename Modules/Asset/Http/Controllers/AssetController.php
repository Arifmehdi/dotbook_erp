<?php

namespace Modules\Asset\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Interface\FileUploaderServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Modules\Asset\Entities\Allocation;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetCategory;
use Modules\Asset\Entities\AssetDepreciation;
use Modules\Asset\Entities\AssetLocation;
use Modules\Asset\Entities\AssetRequest;
use Modules\Asset\Entities\AssetSupplier;
use Modules\Asset\Entities\AssetUnit;
use Modules\Asset\Entities\AssetWarranty;
use Modules\Asset\Entities\Components;
use Modules\Asset\Entities\Revoke;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('asset_index')) {

            abort(403, 'Access denied.');
        }

        if ($request->ajax()) {

            $assets = '';
            $query = Asset::with([
                'category',
                'unit',
                'creator',
                'location',
                'allocations',
                'revokes',
                'supplier',
            ]);

            if ($request->category_id) {

                $query->where('asset_category_id', $request->category_id);
            }
            if ($request->supplier_id) {

                $query->where('asset_supplier_id', $request->supplier_id);
            }
            if ($request->location_id) {

                $query->where('asset_location_id', $request->location_id);
            }
            if ($request->unit_id) {

                $query->where('asset_unit_id', $request->unit_id);
            }
            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('purchase_date', $date_range); // Final
            }

            $assets = $query->orderBy('id', 'desc')->get();

            return DataTables::of($assets)
                ->addColumn('action', function ($row) {

                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('asset_update')) {

                        $html .= '<a class="dropdown-item" href="'.route('assets.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->can('asset_delete')) {

                        $html .= '<a class="dropdown-item" id="delete" href="'.route('assets.assets.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('is_allocated', function ($row) {
                    return ($row->is_allocated == 1) ? 'YES' : 'NO';
                })
                ->addColumn('unit_name', function ($row) {

                    return $row->unit->name ?? 'N/A';
                })
                ->addColumn('supplier_name', function ($row) {

                    return $row->supplier->name ?? 'N/A';
                })
                ->addColumn('my_category_name', function ($row) {

                    return $row->category->name ?? 'N/A';
                })
                ->addColumn('creator', function ($row) {

                    return $row->creator->name ?? 'N/A';
                })
                ->editColumn('allocated_quantity', function ($row) {

                    $total = 0;

                    if (count($row->allocations) > 0) {

                        foreach ($row->allocations as $allocation) {

                            $total += $allocation->quantity;
                        }
                    }

                    return $total;
                })

                ->editColumn('revoked_quantity', function ($row) {

                    $totalRevoke = 0;

                    if (count($row->revokes) > 0) {

                        foreach ($row->revokes as $revoke) {

                            $totalRevoke += $revoke->quantity;
                        }

                    }

                    return $totalRevoke;
                })

                ->editColumn('current_allocated', function ($row) {

                    $totalAllocated = 0;

                    if (count($row->allocations) > 0) {

                        foreach ($row->allocations as $allocation) {

                            $totalAllocated += $allocation->quantity;
                        }
                    }

                    $totalRevoked = 0;

                    if (count($row->revokes) > 0) {

                        foreach ($row->revokes as $revoke) {

                            $totalRevoked += $revoke->quantity;
                        }

                    }

                    return $totalAllocated - $totalRevoked;
                })
                ->editColumn('unused_assets', function ($row) {

                    $totalAllocated = 0;

                    if (count($row->allocations) > 0) {

                        foreach ($row->allocations as $allocation) {
                            $totalAllocated += $allocation->quantity;
                        }
                    }

                    $totalRevoked = 0;

                    if (count($row->revokes) > 0) {

                        foreach ($row->revokes as $revoke) {

                            $totalRevoked += $revoke->quantity;
                        }

                    }

                    return $row->quantity - ($totalAllocated - $totalRevoked);
                })

                ->editColumn('is_allocated', function ($row) {
                    return ($row->is_allocated == 1) ? 'Asset is allocated now' : 'Asset is not allocated yet';
                })
                ->rawColumns(['action', 'unit_name', 'allocated_quantity', 'revoked_quantity', 'current_allocated', 'my_category_name', 'creator'])
                ->make(true);
        }
        $locations = AssetLocation::all();
        $supplier = AssetSupplier::all();
        $asset_categories = AssetCategory::all();
        $units = AssetUnit::all();
        $components = Components::all();

        return view('asset::assets.index', [
            'locations' => $locations,
            'units' => $units,
            'asset_categories' => $asset_categories,
            'components' => $components,
            'supplier' => $supplier,
        ]);
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerationService, FileUploaderServiceInterface $fileUploaderService)
    {
        if (! auth()->user()->can('asset_create')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'asset_name' => 'required',
            'quantity' => 'required',
            'units_id' => 'required',
            'depreciation_method' => 'required',
            'salvage_value' => 'required',
            'purchase_type' => 'required',
            'depreciation_year' => 'required',
            'categories_id' => 'required',
            'location_id' => 'required',
            'supplier_id' => 'required',
            'unit_price' => 'required',
            'purchase_date' => 'required|date',
            'photo' => 'sometimes|image|max:2048',
        ]);

        $addAsset = new Asset();

        $componentsJsonString = json_encode($request->components_id);
        $addAsset->components = $componentsJsonString;

        $addAsset->created_by_id = auth()->user()->id;

        $addAsset->asset_code = $request->asset_code ? $request->asset_code : $codeGenerationService->generate('assets', 'asset_code', 'AST');

        $addAsset->asset_name = $request->asset_name;
        $addAsset->quantity = $request->quantity;
        $addAsset->asset_unit_id = $request->units_id;
        $addAsset->asset_supplier_id = $request->supplier_id;
        $addAsset->asset_category_id = $request->categories_id;
        $addAsset->asset_location_id = $request->location_id;
        $addAsset->model = $request->model;

        if (isset($request->components_id)) {
            $components = Components::all();

            foreach ($components as $key => $component) {
                foreach ($request->components_id as $req_component_id) {
                    if ($component->id == $req_component_id) {
                        $component->checker = $component->checker + 1;
                        $component->save();
                    }
                }
            }

            $encoded_components = json_encode($request->components_id);
            $addAsset->components = $encoded_components;
        }

        $addAsset->serial = $request->serial_number;
        $addAsset->unit_price = $request->unit_price;
        $addAsset->purchase_type = $request->purchase_type;
        $addAsset->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        $purchase_date = date('Y-m-d', strtotime($request->purchase_date));

        if (isset($request->expire_date)) {

            $expire_date = date('Y-m-d', strtotime($request->expire_date));

            if ($purchase_date > $expire_date) {

                return response()->json(['errorMsg' => 'Please select expire date after purchase date']);
            }

            $addAsset->expire_date = $expire_date;
        }
        $addAsset->is_allocatable = isset($request->is_allocatable) ? 1 : 0;
        $addAsset->is_visible = isset($request->is_visible) ? 1 : 0;
        $addAsset->description = $request->description;

        if ($request->hasFile('photo')) {
            $addAsset->image = $fileUploaderService->upload($request->file('photo'), 'uploads/asset/');
        }
        if ($request->hasFile('additional_files')) {
            $addAsset->additional_files = $fileUploaderService->uploadMultiple($request->file('additional_files'), 'uploads/asset/additional_files');
        }
        $addAsset->save();

        $createdAssetId = $addAsset->id;

        $check_part = $request->w_start_dates;

        if (isset($check_part)) {

            foreach ($check_part as $key => $item) {

                $addWarranty = new AssetWarranty();
                $addWarranty->start_date = date('Y-m-d', strtotime($request->w_start_dates[$key]));
                $addWarranty->warranty_month = $request->warranty_months[$key];
                $addWarranty->additional_cost = $request->additional_costs[$key];
                $addWarranty->additional_description = $request->additional_descriptions[$key];
                $addWarranty->asset_id = $createdAssetId;
                $addWarranty->save();
            }
        }

        $depreciation = new AssetDepreciation();
        $depreciation->asset_id = $createdAssetId;
        $depreciation->salvage_value = $request->salvage_value;
        $depreciation->dep_method = $request->depreciation_method;

        $depreciation->dep_year = $request->depreciation_year;
        $depreciation->save();

        return response()->json('Asset Created Successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('asset_update')) {
            abort(403, 'Access denied.');
        }

        $asset = Asset::where('id', $id)->first();
        $depreciation = AssetDepreciation::where('asset_id', $id)->first();
        $warranties = AssetWarranty::where('asset_id', $id)->get();

        $locations = AssetLocation::all();
        $asset_categories = AssetCategory::all();
        $units = AssetUnit::all();
        $components = Components::all();
        $supplier = AssetSupplier::all();

        return view('asset::assets.ajax_view.edit', [
            'locations' => $locations,
            'units' => $units,
            'asset_categories' => $asset_categories,
            'asset' => $asset,
            'warranties' => $warranties,
            'depreciation' => $depreciation,
            'components' => $components,
            'supplier' => $supplier,
        ]);
    }

    public function update(Request $request, $id, CodeGenerationServiceInterface $codeGenerationService, FileUploaderServiceInterface $fileUploaderService)
    {
        if (! auth()->user()->can('asset_update')) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'asset_name' => 'required',
            'depreciation_method' => 'required',
            'quantity' => 'required',
            'salvage_value' => 'required',
            'depreciation_year' => 'required',
            'units_id' => 'required',
            'categories_id' => 'required',
            'location_id' => 'required',
            'unit_price' => 'required',
            'purchase_date' => 'required|date',
        ], [
            'units_id.required' => 'Unit field is required',
            'categories_id.required' => 'Category field is required',
            'location_id.required' => 'Location field is required',
        ]);

        $editAsset = Asset::where('id', $id)->first();

        $editAsset->asset_code = $editAsset->asset_code ? $editAsset->asset_code : $codeGenerationService->generate('assets', 'asset_code', 'AST');
        $editAsset->asset_name = $request->asset_name;
        // checker
        $check_allocation_quantity = 0;
        $check_asset_allocation = Allocation::where('asset_id', $id)->get();
        foreach ($check_asset_allocation as $key => $items) {

            $check_allocation_quantity += $items->quantity;
        }

        $check_asset_revoke = 0;
        $check_revoke_quantity = Revoke::where('asset_id', $id)->get();

        foreach ($check_revoke_quantity as $key => $items) {

            $check_asset_revoke += $items->quantity;
        }

        if (($check_allocation_quantity - $check_asset_revoke) > $request->quantity) {

            return response()->json('Insufficient asset quantity');
        }

        $editAsset->quantity = $request->quantity;
        $editAsset->asset_unit_id = $request->units_id;
        $editAsset->asset_category_id = $request->categories_id;
        $editAsset->asset_location_id = $request->location_id;
        $editAsset->model = $request->model;
        $editAsset->serial = $request->serial_number;
        $editAsset->unit_price = $request->unit_price;
        $editAsset->purchase_type = $request->purchase_type;

        $components = Components::all();

        $old_components_id = json_decode($editAsset->components);

        if (isset($old_components_id)) {
            foreach ($components as $key => $component) {
                foreach ($old_components_id as $old_component_id) {
                    if ($component->id == $old_component_id) {
                        $component->checker = $component->checker - 1;
                        $component->save();
                    }
                }
            }
        }

        if (isset($request->components_id)) {
            foreach ($components as $key => $component) {
                foreach ($request->components_id as $req_component_id) {
                    if ($component->id == $req_component_id) {
                        $component->checker = $component->checker + 1;
                        $component->save();
                    }
                }
            }
        }

        $encoded_components = json_encode($request->components_id);
        $editAsset->components = $encoded_components;

        if ($request->hasFile('additional_files')) {
            $newAdditionalFilesString = $fileUploaderService->uploadMultiple($request->file('additional_files'), 'uploads/asset/additional_files');
            $newAdditionalFilesArray = json_decode($newAdditionalFilesString);
            if ($editAsset->additional_files) {
                $oldAdditionalFilesArray = \json_decode($editAsset->additional_files, true);
                $mergedFilesArray = array_merge($oldAdditionalFilesArray, $newAdditionalFilesArray);
                $editAsset->additional_files = json_encode($mergedFilesArray);
            } else {
                $editAsset->additional_files = json_encode($newAdditionalFilesArray);
            }
        }
        $editAsset->purchase_date = date('Y-m-d', strtotime($request->purchase_date));

        if (isset($request->expire_date)) {
            if ($request->purchase_date > $request->expire_date) {
                return response()->json(['errorMsg' => 'Please select a valid date']);
            }
            $editAsset->expire_date = date('Y-m-d', strtotime($request->expire_date));
        }

        if (! isset($request->is_allocatable)) {
            if ($editAsset->is_allocated == 1) {
                return response()->json('Assets is already allocated!');
            } else {
                $editAsset->is_allocatable = 0;
            }
        } else {
            $editAsset->is_allocatable = 1;
        }
        $editAsset->is_visible = isset($request->is_visible) ? 1 : 0;
        $editAsset->description = $request->description;
        $old_photo = $request->old_photo;

        if ($request->file('photo')) {

            if (isset($editAsset->image) && ! empty($editAsset->image) && file_exists('uploads/asset/'.$old_photo)) {

                unlink(public_path('uploads/asset/'.$old_photo));
            }

            $AssetPhoto = $request->file('photo');
            $AssetPhotoName = uniqid().'.'.$AssetPhoto->getClientOriginalExtension();
            Image::make($AssetPhoto)->resize(250, 250)->save('uploads/asset/'.$AssetPhotoName);
            $editAsset->image = $AssetPhotoName;
        }

        $editAsset->save();

        $latest_asset_id = $editAsset->id;

        // depreciation part
        $depreciation = AssetDepreciation::where('id', $request->depreciation_method_id)->first();
        $depreciation->dep_method = $request->depreciation_method;
        $depreciation->salvage_value = $request->salvage_value;
        $depreciation->dep_year = $request->depreciation_year;
        $depreciation->save();

        $deletePreviousWarranties = AssetWarranty::where('asset_id', $editAsset->id)->get();

        foreach ($deletePreviousWarranties as $deletePreviousWarranty) {

            $deletePreviousWarranty->delete();
        }

        $check_part = $request->w_start_dates;

        if (isset($check_part)) {

            foreach ($check_part as $key => $item) {

                $addWarranty = new AssetWarranty();
                $addWarranty->start_date = date('Y-m-d', strtotime($request->w_start_dates[$key]));
                $addWarranty->warranty_month = $request->warranty_months[$key];
                $addWarranty->additional_cost = $request->additional_costs[$key];
                $addWarranty->additional_description = $request->additional_descriptions[$key];
                $addWarranty->asset_id = $editAsset->id;
                $addWarranty->save();
            }
        }

        return response()->json('Assets updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        if (! auth()->user()->can('asset_delete')) {
            abort(403, 'Access denied.');
        }

        $allocation = Allocation::where('asset_id', $id)->get();
        $count = 0;
        foreach ($allocation as $key => $all) {
            $count += $all->quantity;
        }
        if ($count > 0) {
            return response()->json('Asset is on allocation section', 400);
        }

        $components = Components::all();

        $asset = Asset::where('id', $id)->first();

        $deletable_components = json_decode($asset->components);

        if (isset($deletable_components) && count($deletable_components) > 0) {
            foreach ($components as $key => $component) {
                foreach ($deletable_components as $old_component_id) {
                    if ($component->id == $old_component_id) {
                        $component->checker = $component->checker - 1;
                        $component->save();
                    }
                }
            }
        }

        if ($asset->additional_files) {

            $additional_files = $asset->additional_files;
            $additional_files = json_decode($additional_files, true);

            foreach ($additional_files as $key => $file) {
                try {
                    unlink(\public_path('uploads/asset/additional_files/'.$file));
                } catch (\Exception$e) {
                } finally {
                    unset($additional_files[$key]);
                }
            }
        }
        $photo = $asset->image;
        if ($photo != null) {
            unlink(public_path('uploads/asset/'.$photo));
        }
        $asset->delete();

        return response()->json(['errorMsg' => 'Assets deleted successfully']);
    }

    public function deleteFileFromAdditionalFilesJson($id, $get_file)
    {
        $asset = Asset::where('id', $id)->first();
        $additional_files = $asset->additional_files;
        $additional_files = json_decode($additional_files, true);

        foreach ($additional_files as $key => $file) {
            if ($get_file == $file) {
                try {
                    unlink(\public_path('uploads/asset/additional_files/'.$file));
                } catch (\Exception$e) {
                } finally {
                    unset($additional_files[$key]);
                }
            }
        }

        $asset->additional_files = json_encode($additional_files);
        $asset->save();

        return ['message' => 'Additional file deleted!'];
    }

    public function AssetImageDelete($id)
    {
        $asset = Asset::find($id);
        unlink(public_path('uploads/asset/'.$asset->image));
        $asset->image = null;
        $asset->save();

        return ['message' => 'Image Deleted!'];
    }

    public function dashboard()
    {
        $asset = Asset::all();
        $asset_count = count($asset);
        $allocation = count(Allocation::all());
        $request = count(AssetRequest::all());

        return view('asset::dashboard', compact('asset_count', 'allocation', 'request', 'asset'));
    }
}
