<?php

namespace App\Utils;

use App\Models\Warranty;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WarrantyUtil
{
    public function warrantiesTable()
    {
        $warranties = DB::table('warranties')->orderBy('id', 'DESC')->get();

        return DataTables::of($warranties)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';

                $html .= '<a href="'.route('product.warranties.edit', $row->id).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';

                $html .= '<a href="'.route('product.warranties.delete', $row->id).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';

                $html .= '</div>';

                return $html;
            })
            ->editColumn('type', function ($row) {

                $row->type == 1 ? 'Warranty' : 'Guaranty';
            })
            ->editColumn('duration', function ($row) {

                return $row->duration.' '.$row->duration_type;
            })
            ->rawColumns(['action', 'type', 'duration_type'])
            ->smart(true)
            ->make(true);
    }

    public function addWarranty($request)
    {
        $addWarranty = Warranty::create([
            'name' => $request->name,
            'type' => $request->type,
            'duration' => $request->duration,
            'duration_type' => $request->duration_type,
            'description' => $request->description,
        ]);

        return $addWarranty;
    }

    public function updateWarranty($request, $id)
    {
        $updateWarranty = Warranty::where('id', $id)->first();

        $updateWarranty->update([
            'name' => $request->name,
            'type' => $request->type,
            'duration' => $request->duration,
            'duration_type' => $request->duration_type,
            'description' => $request->description,
        ]);

        return $updateWarranty;
    }

    public function deleteWarranty($id)
    {
        $deleteWarranty = Warranty::with('products')->where('id', $id)->first();

        $errorMsg = '';
        if (count($deleteWarranty->products) > 0) {

            $errorMsg = 'Warranty can not be deleted. This warranty associated with one or more products';
        } else {

            $deleteWarranty->delete();
        }

        return ['deleteWarranty' => $deleteWarranty, 'errorMsg' => $errorMsg];
    }
}
