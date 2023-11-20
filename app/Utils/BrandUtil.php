<?php

namespace App\Utils;

use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class BrandUtil
{
    public function brandsTable()
    {
        $brands = DB::table('brands')->orderBy('id', 'DESC')->get();

        return DataTables::of($brands)
            ->addIndexColumn()
            ->editColumn('photo', function ($row) {

                $imgSrc = (isset($row->photo) && file_exists(public_path('uploads/brand/' . $row->photo))) ? asset('uploads/brand/' . $row->photo) : asset('images/default.jpg');
                return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $imgSrc . '">';
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';
                $html .= '<a href="' . route('product.brands.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="' . route('product.brands.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['photo', 'action'])
            ->make(true);
    }

    public function addBrand($request)
    {
        $addBrand = '';
        if ($request->file('photo')) {

            $brandPhoto = $request->file('photo');
            $brandPhotoName = uniqid() . '.' . $brandPhoto->getClientOriginalExtension();
            Image::make($brandPhoto)->resize(250, 250)->save('uploads/brand/' . $brandPhotoName);

            $addBrand = Brand::create([
                'name' => $request->name,
                'photo' => $brandPhotoName,
            ]);
        } else {

            $addBrand = Brand::create([
                'name' => $request->name,
            ]);
        }

        return $addBrand;
    }

    public function updateBrand($request, $id)
    {
        $updateBrand = Brand::where('id', $id)->first();

        if ($request->file('photo')) {

            if ($updateBrand->photo) {

                if (file_exists(public_path('uploads/brand/' . $updateBrand->photo))) {

                    unlink(public_path('uploads/brand/' . $updateBrand->photo));
                }
            }

            $brandPhoto = $request->file('photo');
            $brandPhotoName = uniqid() . '.' . $brandPhoto->getClientOriginalExtension();
            Image::make($brandPhoto)->resize(250, 250)->save('uploads/brand/' . $brandPhotoName);

            $updateBrand->update([
                'name' => $request->name,
                'photo' => $brandPhotoName,
            ]);
        } else {

            $updateBrand->update([
                'name' => $request->name,
            ]);
        }

        return $updateBrand;
    }

    public function deleteBrand($id)
    {
        $deleteBrand = Brand::find($id);

        if ($deleteBrand->photo) {

            if (file_exists(public_path('uploads/brand/' . $deleteBrand->photo))) {

                unlink(public_path('uploads/brand/' . $deleteBrand->photo));
            }
        }

        $deleteBrand->delete();

        return $deleteBrand;
    }
}
