<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\RecentPrice;
use App\Utils\Converter;
use App\Utils\SmsTemplates\PriceUpdateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RecentPriceController extends Controller
{
    public $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('all_previous_recent_price')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $converter = $this->converter;
            $prices = DB::table('recent_prices')->orderBy('id', 'DESC')
                ->leftJoin('users', 'recent_prices.created_by_id', 'users.id')
                ->leftJoin('products', 'recent_prices.product_id', 'products.id')
                ->leftJoin('product_variants', 'recent_prices.variant_id', 'product_variants.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('categories as subcategories', 'products.parent_category_id', 'subcategories.id')
                ->select(
                    'recent_prices.*',
                    'users.prefix',
                    'users.name',
                    'users.last_name',
                    'products.name as p_name',
                    'product_variants.variant_name as v_name',
                    'categories.name as cate_name',
                    'subcategories.name as sub_cate_name',
                )->orderBy('recent_prices.created_at', 'desc');

            return DataTables::of($prices)

                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('sales.recent.price.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('startAndEndTime', function ($row) {

                    return '<p class="p-0 m-0 text-success"><strong>'.date('d-m-y h:i:s a', strtotime($row->start_time)).'</strong></p>'.'<p class="p-0 m-0 text-danger"><strong>'.date('d-m-y h:i:s a', strtotime($row->end_time)).'</strong></p>';
                })
                ->editColumn('categoryAndSubCategory', function ($row) {

                    return '<p class="p-0 m-0">'.$row->cate_name.'</p>'.($row->sub_cate_name ? ' <p class="p-0 m-0"> -- '.$row->sub_cate_name.'</p>' : '---');
                })
                ->editColumn('product_name', function ($row) {

                    return $row->p_name.($row->v_name ? ' - '.$row->v_name : '');
                })
                ->editColumn('category', function ($row) {

                    return $row->cate_name;
                })
                ->editColumn('subcategory', function ($row) {

                    return $row->sub_cate_name ? $row->sub_cate_name : '---';
                })
                ->editColumn('previous_price', fn ($row) => $this->converter->format_in_bdt($row->previous_price))

                ->editColumn('new_price', fn ($row) => $this->converter->format_in_bdt($row->new_price))

                ->editColumn('start_time', function ($row) {

                    return date('d-m-Y h:i:s a', strtotime($row->start_time));
                })
                ->editColumn('end_time', function ($row) {

                    return date('d-m-Y h:i:s a', strtotime($row->end_time));
                })
                ->editColumn('created_by', function ($row) {

                    return $row->prefix.' '.$row->name.' '.$row->last_name;
                })
                ->rawColumns(['action', 'startAndEndTime', 'categoryAndSubCategory', 'category', 'subcategory', 'product_name', 'previous_price', 'start_time', 'end_time', 'created_by'])->smart(true)->make(true);
        }

        return view('sales_app.recent_prices.index');
    }

    public function recentPriceForCreatePage(Request $request)
    {
        if (! auth()->user()->can('all_previous_recent_price')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $converter = $this->converter;
            $prices = DB::table('recent_prices')->leftJoin('products', 'recent_prices.product_id', 'products.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('categories as subcategories', 'products.parent_category_id', 'subcategories.id')
                ->select(
                    'categories.name as cate_name',
                    'subcategories.name as sub_cate_name',
                    'recent_prices.new_price',
                    'recent_prices.start_time',
                    'recent_prices.end_time',
                    'recent_prices.created_at',
                )->orderBy('recent_prices.created_at', 'desc')
                ->distinct();

            return DataTables::of($prices)
                ->editColumn('startAndEndTime', function ($row) {

                    return '<p class="p-0 m-0 text-success"><strong>'.date('d-m-Y h:i:s a', strtotime($row->start_time)).'</strong></p>'.'<p class="p-0 m-0 text-danger"><strong>'.date('d-m-Y h:i:s a', strtotime($row->end_time)).'</strong></p>';
                })

                ->editColumn('categoryAndSubCategory', function ($row) {

                    return '<p class="p-0 m-0">'.$row->cate_name.'</p>'.($row->sub_cate_name ? ' <p class="p-0 m-0"> -- '.$row->sub_cate_name.'</p>' : '---');
                })

                ->editColumn('new_price', fn ($row) => $this->converter->format_in_bdt($row->new_price))

                ->rawColumns(['startAndEndTime', 'categoryAndSubCategory', 'new_price'])->smart(true)->make(true);
        }

        return view('sales_app.recent_prices.index');
    }

    public function create()
    {
        if (! auth()->user()->can('add_new_recent_price')) {

            abort(403, 'Access Forbidden.');
        }

        $categories = DB::table('categories')->where('parent_category_id', null)->get();

        return view('sales_app.recent_prices.create', compact('categories'));
    }

    public function store(Request $request, PriceUpdateTemplate $priceUpdateTemplate)
    {
        if (! auth()->user()->can('add_new_recent_price')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
        ]);

        if (isset($request->product_ids)) {

            $date = date('Y-m-d H:i:s');
            $index = 0;
            foreach ($request->product_ids as $productId) {

                if ($request->new_prices[$index] && $request->new_prices[$index] > 0) {

                    if ($request->variant_ids[$index]) {

                        $variant = ProductVariant::where('id', $request->variant_ids[$index])->first();
                        $variant->variant_profit = $request->x_margins[$index];
                        $variant->variant_price = $request->new_prices[$index];
                        $variant->save();
                    } else {

                        $product = Product::where('id', $productId)->first();
                        $product->profit = $request->x_margins[$index];
                        $product->product_price = $request->new_prices[$index];
                        $product->save();
                    }

                    $addRecentPrice = new RecentPrice();
                    $addRecentPrice->start_time = date('Y-m-d H:i:s', strtotime($request->start_date.' '.$request->start_time));
                    $addRecentPrice->end_time = date('Y-m-d H:i:s', strtotime($request->end_date.' '.$request->end_time));
                    $addRecentPrice->product_id = $productId;
                    $addRecentPrice->variant_id = $request->variant_ids[$index];
                    $addRecentPrice->previous_price = $request->current_prices[$index];
                    $addRecentPrice->new_price = $request->new_prices[$index];
                    $addRecentPrice->created_by_id = auth()->user()->id;
                    $addRecentPrice->created_at = $date;
                    $addRecentPrice->save();
                }

                $index++;
            }

            $priceUpdateTemplate->sendPriceUpdateSms();

            return response()->json('Successfully recent price is added.');
        } else {

            return response()->json(['errorMsg' => 'Pricing table is empty.']);
        }
    }

    public function todayPrices()
    {
        if (! auth()->user()->can('today_recent_price')) {

            abort(403, 'Access Forbidden.');
        }

        $maxDate = DB::table('recent_prices')->max('created_at');
        $__maxDate = date('Y-m-d', strtotime($maxDate));
        //$prices = DB::table('recent_prices')->whereDate('created_at', $__maxDate)->get();

        $prices = DB::table('recent_prices')->whereDate('recent_prices.created_at', $__maxDate)
            ->leftJoin('users', 'recent_prices.created_by_id', 'users.id')
            ->leftJoin('products', 'recent_prices.product_id', 'products.id')
            ->leftJoin('product_variants', 'recent_prices.variant_id', 'product_variants.id')
            ->select(
                'recent_prices.*',
                'users.prefix',
                'users.name',
                'users.last_name',
                'products.name as p_name',
                'product_variants.variant_name as v_name',
            )->get();

        return view('sales_app.recent_prices.today_prices', compact('prices'));
    }
}
