<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\DiscountProduct;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DiscountController extends Controller
{
    public $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();

            $discounts = DB::table('discounts')
                ->leftJoin('brands', 'discounts.brand_id', 'brands.id')
                ->leftJoin('categories', 'discounts.category_id', 'categories.id')
                ->select('discounts.*', 'brands.name as b_name', 'categories.name as cate_name')
                ->get();

            return DataTables::of($discounts)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('sales.discounts.edit', [$row->id]).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('sales.discounts.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })

                ->editColumn('start_at', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->start_at));
                })
                ->editColumn('end_at', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->end_at));
                })
                ->editColumn('discount_type', function ($row) {

                    return $row->discount_type == 1 ? 'FIXED' : 'PERCENTAGE';
                })
                ->editColumn('status', function ($row) {

                    if ($row->is_active == 1) {

                        $html = '<div class="form-check form-switch">';
                        $html .= '<input class="form-check-input" id="change_status" data-url="'.route('sales.discounts.change.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked />';
                        $html .= '</div>';

                        return $html;
                    } else {

                        $html = '<div class="form-check form-switch">';
                        $html .= '<input class="form-check-input" id="change_status" data-url="'.route('sales.discounts.change.status', [$row->id]).'"style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                        $html .= '</div>';

                        return $html;
                    }
                })
                ->editColumn('products', function ($row) {

                    $products = DB::table('discount_products')
                        ->where('discount_products.discount_id', $row->id)
                        ->leftJoin('products', 'discount_products.product_id', 'products.id')
                        ->select('products.name', 'products.product_code')->get();
                    $list = '';

                    foreach ($products as $product) {

                        $list .= $product->name.'('.$product->product_code.'),<br/> ';
                    }

                    return $list;
                })

                ->editColumn('discount_amount', fn ($row) => $this->converter->format_in_bdt($row->discount_amount))
                ->rawColumns(['action', 'start_at', 'end_at', 'discount_type', 'is_active', 'status', 'products'])
                ->make(true);
        }

        $brands = DB::table('brands')->select('id', 'name')->get();
        $categories = DB::table('categories')->where('parent_category_id', null)->select('id', 'name')->get();

        $products = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->select('products.id', 'products.name', 'products.product_code')->get();

        $price_groups = DB::table('price_groups')->select('id', 'name')->get();

        $total = [
            'discount' => DB::table('discounts')->count(),
            'active' => DB::table('discounts')->where('is_active', 1)->count(),
            'inactive' => DB::table('discounts')->where('is_active', 0)->count(),
        ];

        return view('sales_app.sales.discounts.index', compact('brands', 'categories', 'products', 'price_groups', 'total'));
    }

    public function getStat()
    {
        $stat = [
            'discount' => DB::table('discounts')->count(),
            'active' => DB::table('discounts')->where('is_active', 1)->count(),
            'inactive' => DB::table('discounts')->where('is_active', 0)->count(),
        ];

        return response()->json($stat);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'priority' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required',
        ]);

        if (
            ! isset($request->product_ids) &&
            $request->brand_id == '' &&
            $request->category_id == ''
        ) {

            return response()->json(['errorMsg' => 'Please select a brand or category.']);
        }

        $addDiscount = new Discount();
        $addDiscount->name = $request->name;
        $addDiscount->priority = $request->priority;
        $addDiscount->start_at = date('Y-m-d', strtotime($request->start_at));
        $addDiscount->end_at = date('Y-m-d', strtotime($request->end_at));
        $addDiscount->discount_type = $request->discount_type;
        $addDiscount->discount_amount = $request->discount_amount;
        $addDiscount->price_group_id = $request->price_group_id;
        $addDiscount->is_active = isset($request->is_active) ? 1 : 0;
        $addDiscount->apply_in_customer_group = isset($request->apply_in_customer_group) ? 1 : 0;
        $addDiscount->save();

        if (isset($request->product_ids) && count($request->product_ids) > 0) {

            foreach ($request->product_ids as $product_id) {

                $addDiscountProduct = new DiscountProduct();
                $addDiscountProduct->discount_id = $addDiscount->id;
                $addDiscountProduct->product_id = $product_id;
                $addDiscountProduct->save();
            }
        } else {

            $addDiscount->brand_id = $request->brand_id;
            $addDiscount->category_id = $request->category_id;

            $addDiscount->save();
        }

        return response()->json('Offer created successfully');
    }

    public function edit($discountId)
    {
        $discount = DB::table('discounts')->where('id', $discountId)->first();

        $brands = DB::table('brands')->select('id', 'name')->get();
        $categories = DB::table('categories')->where('parent_category_id', null)->select('id', 'name')->get();

        $products = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->select('products.id', 'products.name', 'products.product_code')->get();

        $discountProducts = DB::table('discount_products')->where('discount_id', $discountId)->select('product_id')->get();

        $price_groups = DB::table('price_groups')->select('id', 'name')->get();

        return view('sales_app.sales.discounts.ajax_view.edit', compact('discount', 'discountProducts', 'brands', 'categories', 'products', 'price_groups'));
    }

    public function update(Request $request, $discountId)
    {
        $updateDiscount = Discount::with('discountProducts')->where('id', $discountId)->first();

        foreach ($updateDiscount->discountProducts as $discountProduct) {

            $discountProduct->is_delete_in_update = 1;
            $discountProduct->save();
        }

        $updateDiscount->name = $request->name;
        $updateDiscount->priority = $request->priority;
        $updateDiscount->start_at = date('Y-m-d', strtotime($request->start_at));
        $updateDiscount->end_at = date('Y-m-d', strtotime($request->end_at));
        $updateDiscount->discount_type = $request->discount_type;
        $updateDiscount->discount_amount = $request->discount_amount;
        $updateDiscount->price_group_id = $request->price_group_id;
        $updateDiscount->is_active = isset($request->is_active) ? 1 : 0;
        $updateDiscount->apply_in_customer_group = isset($request->apply_in_customer_group) ? 1 : 0;
        $updateDiscount->save();

        if (isset($request->product_ids) && count($request->product_ids) > 0) {

            $updateDiscount->brand_id = null;
            $updateDiscount->category_id = null;

            foreach ($request->product_ids as $product_id) {

                $discountProduct = DiscountProduct::where('discount_id', $updateDiscount->id)
                    ->where('product_id', $product_id)->first();

                if ($discountProduct) {

                    $discountProduct->is_delete_in_update = 0;
                    $discountProduct->save();
                } else {

                    $addDiscountProduct = new DiscountProduct();
                    $addDiscountProduct->discount_id = $updateDiscount->id;
                    $addDiscountProduct->product_id = $product_id;
                    $addDiscountProduct->save();
                }
            }
        } else {

            foreach ($updateDiscount->discountProducts as $discountProduct) {

                $discountProduct->delete();
            }

            $updateDiscount->brand_id = $request->brand_id;
            $updateDiscount->category_id = $request->category_id;
            $updateDiscount->save();
        }

        // Unused discount product
        $deleteDiscountProducts = DiscountProduct::where('discount_id', $updateDiscount->id)->where('is_delete_in_update', 1)->get();

        foreach ($deleteDiscountProducts as $deleteDiscountProduct) {

            $deleteDiscountProduct->delete();
        }

        return response()->json('Offer updated successfully');
    }

    public function delete($discountId)
    {
        $deleteDiscount = Discount::where('id', $discountId)->first();

        if (! is_null($deleteDiscount)) {

            $deleteDiscount->delete();
        }

        return response()->json('Offer deleted successfully');
    }

    public function changeStatus($discountId)
    {
        $discount = Discount::where('id', $discountId)->first();

        if ($discount->is_active == 1) {
            $discount->is_active = 0;
            $discount->save();
        } else {
            $discount->is_active = 1;
            $discount->save();
        }

        return response()->json('Offer status has been changed successfully');
    }
}
