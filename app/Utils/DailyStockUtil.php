<?php

namespace App\Utils;

use App\Models\DailyStock;
use App\Models\DailyStockProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DailyStockUtil
{
    protected $purchaseSaleChainUtil;

    protected $converter;

    public function __construct(
        PurchaseSaleChainUtil $purchaseSaleChainUtil,
        Converter $converter
    ) {

        $this->purchaseSaleChainUtil = $purchaseSaleChainUtil;
        $this->converter = $converter;
    }

    public function dailyStockTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $dailyStocks = '';

        $query = DB::table('daily_stocks')
            ->leftJoin('warehouses', 'daily_stocks.warehouse_id', 'warehouses.id')
            ->leftJoin('users as createdBy', 'daily_stocks.created_by_id', 'createdBy.id');

        $query->select(
            'daily_stocks.id',
            'daily_stocks.voucher_no',
            'daily_stocks.date',
            'daily_stocks.reported_by',
            'daily_stocks.total_item',
            'daily_stocks.total_qty',
            'daily_stocks.total_stock_value',
            'warehouses.warehouse_name as w_name',
            'warehouses.warehouse_code as w_code',
            'createdBy.prefix as c_prefix',
            'createdBy.name as c_name',
            'createdBy.last_name as c_last_name',
        );

        if ($request->warehouse_id) {

            $query->where('daily_stocks.warehouse_id', $request->warehouse_id);
        }

        if ($request->user_id) {

            $query->where('daily_stocks.created_by_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('daily_stocks.report_date', $date_range); // Final
        }

        $dailyStocks = $query->orderBy('daily_stocks.report_date', 'desc');

        return DataTables::of($dailyStocks)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                $html .= '<a class="dropdown-item details_button" href="'.route('daily.stock.show', [$row->id]).'"> View</a>';

                if (auth()->user()->can('edit_sale')) {

                    $html .= '<a class="dropdown-item" href="'.route('daily.stock.edit', [$row->id]).'"> Edit</a>';
                }

                if (auth()->user()->can('delete_sale')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('daily.stock.delete', [$row->id]).'"> Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                return date($__date_format, strtotime($row->date));
            })

            ->editColumn('warehouse', function ($row) {

                if ($row->w_name) {

                    return $row->w_name.'/'.$row->w_code;
                } else {

                    return '..';
                }
            })

            ->editColumn('created_by', function ($row) {

                return $row->c_prefix.' '.$row->c_name.' '.$row->c_last_name;
            })

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.$this->converter->format_in_bdt($row->total_item).'</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.$this->converter->format_in_bdt($row->total_qty).'</span>')

            ->editColumn('total_stock_value', fn ($row) => '<span class="total_stock_value" data-value="'.$row->total_stock_value.'">'.$this->converter->format_in_bdt($row->total_stock_value).'</span>')

            ->rawColumns(['action', 'date', 'warehouse', 'created_by', 'total_item', 'total_qty', 'total_stock_value'])
            ->make(true);
    }

    public function addDailyStock($request, $codeGenerationService)
    {
        $voucherNo = $codeGenerationService->generateMonthWise(table: 'daily_stocks', column: 'voucher_no', prefix: 'DS', splitter: '-', suffixSeparator: '-');

        $addDailyStock = new DailyStock();
        $addDailyStock->voucher_no = $voucherNo;
        $addDailyStock->date = $request->date;
        $addDailyStock->created_by_id = auth()->user()->id;
        $addDailyStock->warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : null;
        $addDailyStock->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addDailyStock->reported_by = $request->reported_by;
        $addDailyStock->total_item = $request->total_item;
        $addDailyStock->total_qty = $request->total_qty;
        $addDailyStock->total_stock_value = $request->total_stock_value;
        $addDailyStock->production_details = $request->production_details;
        $addDailyStock->note = $request->note;
        $addDailyStock->save();

        return $addDailyStock;
    }

    public function updateDailyStock($updateDailyStock, $request)
    {
        $updateDailyStock->date = $request->date;
        $updateDailyStock->created_by_id = auth()->user()->id;
        $updateDailyStock->warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : null;
        $time = date(' H:i:s', strtotime($updateDailyStock->report_date));
        $updateDailyStock->report_date = date('Y-m-d H:i:s', strtotime($request->date.$time));
        $updateDailyStock->reported_by = $request->reported_by;
        $updateDailyStock->total_item = $request->total_item;
        $updateDailyStock->total_qty = $request->total_qty;
        $updateDailyStock->total_stock_value = $request->total_stock_value;
        $updateDailyStock->production_details = $request->production_details;
        $updateDailyStock->note = $request->note;
        $updateDailyStock->save();

        return $updateDailyStock;
    }

    public function updateItemCost($productId, $variantId, $tax_ac_id, $unit_cost_exc_tax, $unit_cost_inc_tax)
    {
        $product = Product::where('id', $productId)->first();
        $product->tax_ac_id = $tax_ac_id;

        if ($variantId == null) {

            $product->product_cost = $unit_cost_exc_tax;
            $product->product_cost_with_tax = $unit_cost_inc_tax;
        }

        $product->save();

        if ($variantId) {

            $variant = ProductVariant::where('id', $variantId)->first();
            $variant->variant_cost = $unit_cost_exc_tax;
            $variant->variant_cost_with_tax = $unit_cost_inc_tax;
            $variant->save();
        }
    }

    public function updateDailyStockProduct($request, $dailyStockId)
    {
        $index = 0;
        foreach ($request->product_ids as $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

            $dailyStockProduct = DailyStockProduct::where('daily_stock_id', $dailyStockId)
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)->first();

            if ($dailyStockProduct) {

                $dailyStockProduct->quantity = $request->quantities[$index];
                $dailyStockProduct->unit = $request->units[$index];
                $dailyStockProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
                $dailyStockProduct->tax_ac_id = $request->tax_ac_ids[$index];
                $dailyStockProduct->tax_percent = $request->tax_percents[$index];
                $dailyStockProduct->tax_type = $request->tax_types[$index];
                $dailyStockProduct->tax_amount = $request->tax_amounts[$index];
                $dailyStockProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $dailyStockProduct->subtotal = $request->subtotals[$index];
                $dailyStockProduct->is_delete_in_update = 0;
                $dailyStockProduct->save();

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'daily_stock_product_id',
                    transId: $dailyStockProduct->id,
                    productId: $productId,
                    quantity: $request->quantities[$index],
                    variantId: $variantId,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    sellingPrice: 0,
                    subTotal: $request->subtotals[$index],
                    createdAt: date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s'))),
                );
            } else {

                $addDailyStockProduct = new DailyStockProduct();
                $addDailyStockProduct->daily_stock_id = $dailyStockId;
                $addDailyStockProduct->product_id = $productId;
                $addDailyStockProduct->variant_id = $variantId;
                $addDailyStockProduct->quantity = $request->quantities[$index];
                $addDailyStockProduct->unit = $request->units[$index];
                $addDailyStockProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
                $addDailyStockProduct->tax_ac_id = $request->tax_ac_ids[$index];
                $addDailyStockProduct->tax_percent = $request->tax_percents[$index];
                $addDailyStockProduct->tax_type = $request->tax_types[$index];
                $addDailyStockProduct->tax_amount = $request->tax_amounts[$index];
                $addDailyStockProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $addDailyStockProduct->subtotal = $request->subtotals[$index];
                $addDailyStockProduct->save();

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'daily_stock_product_id',
                    transId: $addDailyStockProduct->id,
                    productId: $productId,
                    quantity: $request->quantities[$index],
                    variantId: $variantId,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    sellingPrice: 0,
                    subTotal: $request->subtotals[$index],
                    createdAt: date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s'))),
                );
            }

            $index++;
        }
    }
}
