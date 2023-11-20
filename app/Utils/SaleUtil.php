<?php

namespace App\Utils;

use App\Models\PurchaseProduct;
use App\Models\PurchaseSaleProductChain;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleUtil
{
    protected $customerUtil;

    protected $productStockUtil;

    protected $accountUtil;

    protected $converter;

    protected $purchaseUtil;

    protected $userActivityLogUtil;

    protected $deliveryOrderUtil;

    protected $UserWiseCustomerAmountUtil;

    public function __construct(
        CustomerUtil $customerUtil,
        ProductStockUtil $productStockUtil,
        AccountUtil $accountUtil,
        Converter $converter,
        PurchaseUtil $purchaseUtil,
        UserActivityLogUtil $userActivityLogUtil,
        DeliveryOrderUtil $deliveryOrderUtil,
        UserWiseCustomerAmountUtil $UserWiseCustomerAmountUtil
    ) {
        $this->customerUtil = $customerUtil;
        $this->productStockUtil = $productStockUtil;
        $this->accountUtil = $accountUtil;
        $this->converter = $converter;
        $this->purchaseUtil = $purchaseUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->deliveryOrderUtil = $deliveryOrderUtil;
        $this->UserWiseCustomerAmountUtil = $UserWiseCustomerAmountUtil;
    }

    public function addSale($request, $srUserId, $codeGenerationService)
    {
        $invoiceId = $codeGenerationService->generateMonthWise('sales', 'invoice_id', auth()->user()->user_id, 4, 13, '-', '-');

        $addSale = new Sale();
        $addSale->invoice_id = $invoiceId;
        $addSale->sr_user_id = $srUserId;
        $addSale->sale_by_id = auth()->user()->id;
        $addSale->sale_account_id = $request->sale_account_id;
        $addSale->customer_account_id = $request->customer_account_id;
        $addSale->status = $request->status;
        $addSale->all_price_type = $request->all_price_type;
        $addSale->date = $request->date;
        $addSale->time = date('h:i:s a');
        $addSale->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addSale->total_item = $request->total_item ? $request->total_item : 0;
        $addSale->total_sold_qty = $request->total_qty ? $request->total_qty : 0;
        $addSale->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount ? $request->order_discount : 0;
        $addSale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $addSale->tax_ac_id = $request->order_tax_ac_id;
        $addSale->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0.00;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $addSale->total_payable_amount = $request->total_invoice_amount;
        $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addSale->sale_note = $request->sale_note;
        $addSale->shipping_address = $request->shipping_address;
        $addSale->payment_note = $request->payment_note;
        $addSale->save();

        return $addSale;
    }

    public function updateSale($updateSale, $request, $srUserId)
    {
        $updateSale->sr_user_id = $srUserId;
        $updateSale->status = $request->status;
        $updateSale->all_price_type = $request->all_price_type;
        $updateSale->sale_account_id = $request->sale_account_id;
        $updateSale->customer_account_id = $request->customer_account_id;
        $updateSale->date = $request->date;
        $updateSale->total_item = $request->total_item;
        $updateSale->total_sold_qty = $request->total_qty;
        $updateSale->net_total_amount = $request->net_total_amount;
        $updateSale->order_discount_type = $request->order_discount_type;
        $updateSale->order_discount = $request->order_discount;
        $updateSale->order_discount_amount = $request->order_discount_amount;
        $updateSale->tax_ac_id = $request->order_tax_ac_id;
        $updateSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $updateSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $updateSale->total_payable_amount = $request->total_invoice_amount;
        $updateSale->sale_note = $request->sale_note;
        $updateSale->shipping_address = $request->shipping_address;
        $updateSale->payment_note = $request->payment_note;
        $time = date(' H:i:s', strtotime($updateSale->report_date));
        $updateSale->report_date = date('Y-m-d H:i:s', strtotime($request->date.$time));
        $updateSale->save();

        return $updateSale;
    }

    public function deleteSale($request, $saleId)
    {
        $deleteSale = Sale::with([
            'saleProducts',
            'saleProducts.purchaseSaleProductChains',
            'saleProducts.purchaseSaleProductChains.purchaseProduct',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.product.comboProducts',
            'return',
        ])->where('id', $saleId)->first();

        $storedDeliveryOrderId = $deleteSale->delivery_order_id;
        $storedSaleProducts = $deleteSale->saleProducts;
        $storeStatus = $deleteSale->status;

        if ($deleteSale->status == 1 || $deleteSale->status == 3) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: $deleteSale->status == 1 ? 7 : 8, data_obj: $deleteSale);
        }

        $deleteSale->delete();

        if ($storeStatus == 1) {

            foreach ($storedSaleProducts as $saleProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($saleProduct->product_id, $saleProduct->product_variant_id);

                if ($saleProduct->stock_warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($saleProduct->product_id, $saleProduct->product_variant_id, $saleProduct->stock_warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($saleProduct->product_id, $saleProduct->product_variant_id);
                }

                foreach ($saleProduct->purchaseSaleProductChains as $purchaseSaleProductChain) {

                    if ($purchaseSaleProductChain->purchaseProduct) {

                        $this->purchaseUtil->adjustPurchaseLeftQty($purchaseSaleProductChain->purchaseProduct);
                    }
                }
            }
        }

        if ($storedDeliveryOrderId) {

            $do = Sale::where('id', $storedDeliveryOrderId)->first();
            $this->deliveryOrderUtil->calculateDoLeftQty($do);
        }
    }

    public function addSaleTable($request, $customerAccountId, $srUserId)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $converter = $this->converter;
        $sales = '';

        $query = Sale::query()->with(
            'references:id,payment_description_id,sale_id,amount',
            'references.paymentDescription:id,payment_id',
            'references.paymentDescription.payment:id,date,payment_type',
            'references.paymentDescription.payment.descriptions:id,payment_id,account_id,payment_method_id',
            'references.paymentDescription.payment.descriptions.paymentMethod:id,name',
            'references.paymentDescription.payment.descriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.paymentDescription.payment.descriptions.account.bank:id,name',
            'references.paymentDescription.payment.descriptions.account.group:id,sub_sub_group_number',
        );

        if ($customerAccountId) {

            $query->where('sales.customer_account_id', $customerAccountId);
        }

        if ($srUserId) {

            $query->where('sales.sr_user_id', $srUserId);
        }

        if ($request->user_id) {

            $query->where('sales.sr_user_id', $request->user_id);
        }

        if ($request->sale_account_id) {

            $query->where('sales.sale_account_id', $request->sale_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.sr_user_id', auth()->user()->id);
        }

        $this->filteredQuery($request, $query)->where('sales.status', 1)->where('sales.created_by', 1);

        $sales = $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('sales as do', 'sales.delivery_order_id', 'do.id')
            ->leftJoin('users as sr', 'sales.sr_user_id', 'sr.id')
            ->leftJoin('weight_scales', 'sales.id', 'weight_scales.sale_id')
            ->select(
                'sales.id',
                'sales.invoice_id',
                'sales.do_to_inv_challan_no',
                'sales.date',
                'sales.report_date',
                'sales.total_sold_qty',
                'sales.total_payable_amount',
                'sales.delivery_order_id',
                'sales.paid',
                'sales.payment_note',
                'do.do_id',
                'sr.prefix as sr_prefix',
                'sr.name as sr_name',
                'sr.last_name as sr_last_name',
                'weight_scales.do_car_number',
                'weight_scales.first_weight',
                'weight_scales.second_weight',
                'customers.name as customer_name',
            )->orderBy('sales.report_date', 'desc');

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                $html .= '<a id="details_btn" class="dropdown-item" href="'.route('sales.show', [$row->id]).'"> View</a>';

                if (auth()->user()->can('receipts_add')) {

                    $html .= '<a class="dropdown-item" id="add_sale_receipt" href="'.route('sales.receipts.create', $row->id).'"> Add Receipt</a>';
                }

                if (auth()->user()->can('edit_sale')) {

                    $html .= '<a class="dropdown-item" href="'.route('sales.edit', [$row->id]).'"> Edit</a>';
                }

                if (auth()->user()->can('delete_sale')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('sales.delete', [$row->id]).'"> Delete</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                return date($__date_format, strtotime($row->report_date));
            })

            ->editColumn('net_weight', function ($row) use ($converter) {

                if ($row->first_weight) {

                    $netWeight = $row->second_weight - $row->first_weight;

                    return '<span class="net_weight" data-value="'.$netWeight.'">'.$converter->format_in_bdt($netWeight).'</span>';
                }
            })

            ->editColumn('invoice_id', function ($row) {

                return '<a href="'.route('sales.show', [$row->id]).'" id="details_btn" class="fw-bold">'.$row->invoice_id.'</a>';
            })

            ->editColumn('do_id', function ($row) {

                if ($row->delivery_order_id) {

                    return '<a href="'.route('sales.delivery.order.show', [$row->delivery_order_id]).'" id="details_btn" class="fw-bold">'.$row->do_id.'</a>';
                }
            })

            ->editColumn('receipt_details', function ($row) use ($generalSettings) {

                $html = '';
                if (count($row->references)) {

                    $index = 1;
                    foreach ($row->references as $reference) {

                        $date = $reference?->paymentDescription->payment?->date;
                        $descriptions = $reference?->paymentDescription?->payment?->descriptions;

                        $filteredCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                            return $description?->account?->group->sub_sub_group_number == 1 || $description?->account?->group->sub_sub_group_number == 2 || $description?->account?->group->sub_sub_group_number == 11;
                        });

                        $cashBankAccount = $filteredCashOrBankAccounts->first();
                        $accountNo = $cashBankAccount->account->account_number ? '-'.substr($cashBankAccount->account->account_number, -4) : '';
                        $bankBranch = $cashBankAccount?->account?->bank_branch ? '('.$cashBankAccount?->account?->bank_branch.')' : '';
                        $bank = $cashBankAccount?->account?->bank ? '-'.$cashBankAccount?->account?->bank->name.$bankBranch : '';
                        $method = $cashBankAccount?->paymentMethod ? '-'.$cashBankAccount?->paymentMethod->name : '';
                        $html .= '<p class="m-0 p-0 fw-bold" style="font-size:10px!important;line-height:13px;">'.$index.'. '.$cashBankAccount->account->name.' '.$accountNo.' '.$bank.' - '.$date.$method.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).' '.json_decode($generalSettings->business, true)['currency'];
                        $index++;
                    }
                }

                return $html.($row->payment_note ? '<p class="m-0 p-0" style="font-size:9px!important;"><strong>P.N.:</strong> '.$row->payment_note.'</p>' : '');
            })

            ->editColumn('sr', fn ($row) => $row->sr_prefix.' '.$row->sr_name.' '.$row->sr_last_name)

            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')
            ->editColumn('total_sold_qty', fn ($row) => '<span class="total_sold_qty" data-value="'.$row->total_sold_qty.'">'.$this->converter->format_in_bdt($row->total_sold_qty).'</span>')
            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="'.$row->total_payable_amount.'">'.$this->converter->format_in_bdt($row->total_payable_amount).'</span>')
            ->editColumn('paid', fn ($row) => '<span class="paid" data-value="'.$row->paid.'">'.$this->converter->format_in_bdt($row->paid).'</span>')
            ->rawColumns(['action', 'date', 'invoice_id', 'do_id', 'from', 'customer', 'total_sold_qty', 'net_weight', 'total_payable_amount', 'paid', 'receipt_details'])
            ->make(true);
    }

    public function posSaleTable($request)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $sales = '';
        $query = DB::table('sales')->leftJoin('customers', 'sales.customer_id', 'customers.id');

        $query->select(
            'sales.*',
            'customers.name as customer_name',
        );

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.created_by_id', auth()->user()->id);
        }

        $sales = $this->filteredQuery($request, $query)
            ->where('sales.created_by', 2)
            ->where('sales.status', 1)
            ->orderBy('sales.report_date', 'desc');

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';

                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                $html .= '<a class="dropdown-item details_button" href="'.route('sales.pos.show', [$row->id]).'"><i class="far fa-eye text-primary"></i> View</a>';

                if (auth()->user()->can('shipment_access')) {

                    $html .= '<a class="dropdown-item" id="edit_shipment" href="'.route('sales.shipment.edit', [$row->id]).'"><i class="fas fa-truck text-primary"></i> Edit Shipping</a>';
                }

                // if (auth()->user()->can('receive_payment_index')) {

                //     if ($row->due > 0) {

                //         $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Payment</a>';
                //     }
                // }

                // if (auth()->user()->can('receive_payment_index')) {

                //     $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal" data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                // }

                // if ($row->sale_return_due > 0) {

                //     if (auth()->user()->can('receive_payment_index')) {

                //         $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Pay Return Amount</a>';
                //     }
                // }

                if (auth()->user()->can('pos_edit')) {

                    $html .= '<a class="dropdown-item" href="'.route('sales.pos.edit', [$row->id]).'"><i class="far fa-edit text-primary"></i> Edit</a>';
                }

                if (auth()->user()->can('pos_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('sales.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                }

                // $html .= '<a class="dropdown-item" id="items_notification" href="#"><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', fn ($row) => date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date)))
            ->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';

                return $html;
            })

            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="'.$row->total_payable_amount.'">'.$this->converter->format_in_bdt($row->total_payable_amount).'</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="'.$row->paid.'">'.$this->converter->format_in_bdt($row->paid).'</span>')

            ->editColumn('due', fn ($row) => '<span class="due text-danger"  data-value="'.$row->due.'">'.$this->converter->format_in_bdt($row->due).'</span>')

            ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount text-danger"  data-value="'.$row->sale_return_amount.'">'.$this->converter->format_in_bdt($row->sale_return_amount).'</span>')

            ->editColumn('sale_return_due', fn ($row) => '<span class="sale_return_due text-danger" data-value="'.$row->sale_return_due.'">'.$this->converter->format_in_bdt($row->sale_return_due).'</span>')

            ->editColumn('paid_status', function ($row) {

                $payable = $row->total_payable_amount - $row->sale_return_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
            ->make(true);
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('sales.sr_user_id', $request->user_id);
        }

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->payment_status) {

            if ($request->payment_status == 1) {

                $query->where('sales.due', '=', 0);
            } else {

                $query->where('sales.due', '>', 0);
            }
        }

        return $query;
    }

    public function adjustSaleInvoiceAmounts($sale)
    {
        $userId = $sale->sr_user_id ? $sale->sr_user_id : auth()->user()->id;

        $totalSaleReceived = DB::table('payment_description_references')
            ->leftJoin('payment_descriptions', 'payment_description_references.payment_description_id', 'payment_descriptions.id')
            ->leftJoin('payments', 'payment_descriptions.payment_id', 'payments.id')
            ->where('payment_description_references.sale_id', $sale->id)->where('payments.payment_type', 1)
            ->select(DB::raw('sum(payment_description_references.amount) as total_received'))
            ->groupBy('payment_description_references.sale_id')
            ->get();

        $return = DB::table('sale_returns')
            ->where('sale_returns.sale_id', $sale->id)
            ->select(DB::raw('sum(total_return_amount) as total_return_amount'))
            ->groupBy('sale_returns.sale_id')
            ->get();

        $returnAmount = $return->sum('total_return_amount') ? $return->sum('total_return_amount') : 0;

        $due = $sale->total_payable_amount - $totalSaleReceived->sum('total_received') - $returnAmount;

        $returnDue = $returnAmount - ($sale->total_payable_amount - $totalSaleReceived->sum('total_received'));

        // if (!$sale->delivery_order_id) {

        //     $sale->paid = $totalSaleReceived->sum('total_received');
        //     $sale->due = $due;
        // }

        $sale->paid = $totalSaleReceived->sum('total_received');
        $sale->due = $due;

        $sale->sale_return_amount = $returnAmount;
        $sale->save();

        return $sale;
    }

    public function addPurchaseSaleProductChain($sale, $stockAccountingMethod)
    {
        foreach ($sale->saleProducts as $saleProduct) {

            if ($saleProduct->product->is_manage_stock == 1) {

                $variant_id = $saleProduct->product_variant_id ? $saleProduct->product_variant_id : null;

                $purchaseProducts = '';

                if ($stockAccountingMethod == '1') {

                    $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                        ->where('product_id', $saleProduct->product_id)
                        ->where('product_variant_id', $variant_id)
                        ->orderBy('created_at', 'asc')->get();
                } elseif ($stockAccountingMethod == '2') {

                    $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                        ->where('product_id', $saleProduct->product_id)
                        ->where('product_variant_id', $variant_id)
                        ->orderBy('created_at', 'desc')->get();
                }

                if (count($purchaseProducts) > 0) {

                    $sold_qty = $saleProduct->quantity;

                    foreach ($purchaseProducts as $purchaseProduct) {

                        if ($sold_qty > $purchaseProduct->left_qty) {

                            if ($sold_qty > 0) {

                                $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                $addPurchaseSaleChain->sale_product_id = $saleProduct->id;
                                $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                                $addPurchaseSaleChain->save();
                                $sold_qty -= $purchaseProduct->left_qty;
                                $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                            } else {

                                break;
                            }
                        } elseif ($sold_qty == $purchaseProduct->left_qty) {

                            if ($sold_qty > 0) {

                                $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                $addPurchaseSaleChain->sale_product_id = $saleProduct->id;
                                $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                                $addPurchaseSaleChain->save();
                                $sold_qty -= $purchaseProduct->left_qty;
                                $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                            } else {

                                break;
                            }
                        } elseif ($sold_qty < $purchaseProduct->left_qty) {

                            if ($sold_qty > 0) {

                                $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                $addPurchaseSaleChain->sale_product_id = $saleProduct->id;
                                $addPurchaseSaleChain->sold_qty = $sold_qty;
                                $addPurchaseSaleChain->save();
                                $sold_qty -= $sold_qty;
                                $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                            } else {

                                break;
                            }
                        }
                    }
                }
            } else {

                $addPurchaseSaleChain = new PurchaseSaleProductChain();
                $addPurchaseSaleChain->sale_product_id = $saleProduct->id;
                $addPurchaseSaleChain->sold_qty = $saleProduct->quantity;
                $addPurchaseSaleChain->save();
            }
        }
    }

    public function updatePurchaseSaleProductChain($sale, $stockAccountingMethod)
    {
        foreach ($sale->saleProducts as $saleProduct) {

            if ($saleProduct->product->is_manage_stock == 1) {

                $variant_id = $saleProduct->product_variant_id ? $saleProduct->product_variant_id : null;

                $sold_qty = $saleProduct->quantity;

                $salePurchaseProductChains = PurchaseSaleProductChain::with('purchaseProduct')
                    ->where('sale_product_id', $saleProduct->id)->get();

                foreach ($salePurchaseProductChains as $salePurchaseProductChain) {

                    $salePurchaseProductChain->purchaseProduct->left_qty += $salePurchaseProductChain->sold_qty;
                    $salePurchaseProductChain->purchaseProduct->save();

                    if ($sold_qty > $salePurchaseProductChain->purchaseProduct->left_qty) {

                        //$dist_qty = $salePurchaseProductChain->purchaseProduct->left_qty;
                        $salePurchaseProductChain->sold_qty = $salePurchaseProductChain->purchaseProduct->left_qty;
                        $salePurchaseProductChain->save();
                        $sold_qty = $sold_qty - $salePurchaseProductChain->purchaseProduct->left_qty;
                        $this->purchaseUtil->adjustPurchaseLeftQty($salePurchaseProductChain->purchaseProduct);
                    } elseif ($sold_qty == $salePurchaseProductChain->purchaseProduct->left_qty) {

                        //$dist_qty = $salePurchaseProductChain->purchaseProduct->left_qty;
                        $salePurchaseProductChain->sold_qty = $salePurchaseProductChain->purchaseProduct->left_qty;
                        $salePurchaseProductChain->save();
                        $sold_qty = $sold_qty - $salePurchaseProductChain->purchaseProduct->left_qty;
                        $this->purchaseUtil->adjustPurchaseLeftQty($salePurchaseProductChain->purchaseProduct);
                    } elseif ($sold_qty < $salePurchaseProductChain->purchaseProduct->left_qty) {

                        //$dist_qty = $sold_qty;
                        $salePurchaseProductChain->sold_qty = $sold_qty;
                        $salePurchaseProductChain->save();
                        $sold_qty = $sold_qty - $sold_qty;
                        $this->purchaseUtil->adjustPurchaseLeftQty($salePurchaseProductChain->purchaseProduct);
                    }
                }

                if ($sold_qty > 0) {

                    $purchaseProducts = '';
                    if ($stockAccountingMethod == '1') {

                        $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                            ->where('product_id', $saleProduct->product_id)
                            ->where('product_variant_id', $variant_id)
                            ->orderBy('created_at', 'asc')->get();
                    } elseif ($stockAccountingMethod == '2') {

                        $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                            ->where('product_id', $saleProduct->product_id)
                            ->where('product_variant_id', $variant_id)
                            ->orderBy('created_at', 'desc')->get();
                    }

                    if (count($purchaseProducts) > 0) {

                        foreach ($purchaseProducts as $purchaseProduct) {

                            if ($sold_qty > $purchaseProduct->left_qty) {

                                if ($sold_qty > 0) {

                                    $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                    $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                    $addPurchaseSaleChain->sale_product_id = $saleProduct->id;
                                    $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                                    $addPurchaseSaleChain->save();
                                    $sold_qty -= $purchaseProduct->left_qty;
                                    $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } elseif ($sold_qty == $purchaseProduct->left_qty) {

                                if ($sold_qty > 0) {

                                    $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                    $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                    $addPurchaseSaleChain->sale_product_id = $saleProduct->id;
                                    $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                                    $addPurchaseSaleChain->save();
                                    $sold_qty -= $purchaseProduct->left_qty;
                                    $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } elseif ($sold_qty < $purchaseProduct->left_qty) {

                                if ($sold_qty > 0) {

                                    $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                    $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                    $addPurchaseSaleChain->sale_product_id = $saleProduct->id;
                                    $addPurchaseSaleChain->sold_qty = $sold_qty;
                                    $addPurchaseSaleChain->save();
                                    $sold_qty -= $sold_qty;
                                    $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function getStockLimitProducts($sale)
    {
        $qty_limits = [];

        foreach ($sale->saleProducts as $saleProduct) {

            if ($saleProduct->product->is_manage_stock == 0) {

                $qty_limits[] = PHP_INT_MAX;
            } else {

                if ($saleProduct->stock_warehouse_id) {

                    $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $saleProduct->stock_warehouse_id)
                        ->where('product_id', $saleProduct->product_id)->first();

                    if ($saleProduct->product->type == 2) {

                        $qty_limits[] = 500000;
                    } elseif ($saleProduct->product_variant_id) {

                        $productWarehouseVariant = DB::table('product_warehouse_variants')
                            ->where('product_warehouse_id', $productWarehouse->id)
                            ->where('product_id', $saleProduct->product_id)
                            ->where('product_variant_id', $saleProduct->product_variant_id)
                            ->first();

                        $qty_limits[] = $productWarehouseVariant->variant_quantity;
                    } else {

                        $qty_limits[] = $productWarehouse->product_quantity;
                    }
                } else {

                    $productBranch = DB::table('product_branches')->where('product_id', $saleProduct->product_id)->first();

                    if ($saleProduct->product->type == 2) {

                        $qty_limits[] = 500000;
                    } elseif ($saleProduct->product_variant_id) {

                        $productBranchVariant = DB::table('product_branch_variants')
                            ->where('product_branch_id', $productBranch->id)
                            ->where('product_id', $saleProduct->product_id)
                            ->where('product_variant_id', $saleProduct->product_variant_id)
                            ->first();

                        $qty_limits[] = $productBranchVariant->variant_quantity;
                    } else {

                        $qty_limits[] = $productBranch->product_quantity;
                    }
                }
            }
        }

        return $qty_limits;
    }

    public function customerCopySaleProductsQuery($saleId, $onlyForSoldItem = false)
    {
        $customerCopySaleProducts = '';

        $query = DB::table('sale_products')
            ->where('sale_products.sale_id', $saleId)
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('warranties', 'products.warranty_id', 'warranties.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('units as saleUnit', 'sale_products.unit_id', 'saleUnit.id')
            ->leftJoin('units as baseUnit', 'saleUnit.base_unit_id', 'baseUnit.id');

        if ($onlyForSoldItem == true) {

            $query->where('sale_products.quantity', '>', 0);
        }

        $customerCopySaleProducts = $query->select(
            'sale_products.product_id',
            'sale_products.product_variant_id',
            'sale_products.description',
            'sale_products.unit',
            // 'sale_products.quantity',
            'sale_products.unit_price_exc_tax',
            'sale_products.unit_price_inc_tax',
            'sale_products.price_type',
            'sale_products.pr_amount',
            'sale_products.unit_discount_amount',
            'sale_products.unit_tax_percent',
            // 'sale_products.subtotal',
            'saleUnit.name as unit_name',
            'saleUnit.code_name as unit_code_name',
            'saleUnit.base_unit_multiplier',
            'baseUnit.code_name as base_unit_code_name',
            'products.name as p_name',
            'products.product_code',
            'products.warranty_id',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'warranties.duration as w_duration',
            'warranties.duration_type as w_duration_type',
            'warranties.description as w_description',
            'warranties.type',
            DB::raw('SUM(sale_products.ordered_quantity) as ordered_quantity'),
            DB::raw('SUM(sale_products.do_qty) as do_qty'),
            DB::raw('SUM(sale_products.do_delivered_qty) as do_delivered_qty'),
            DB::raw('SUM(sale_products.do_left_qty) as do_left_qty'),
            DB::raw('SUM(sale_products.quantity) as quantity'),
            DB::raw('SUM(sale_products.subtotal) as subtotal'),
        )
            ->groupBy('sale_products.product_id')
            ->groupBy('sale_products.product_variant_id')
            ->groupBy('sale_products.description')
            ->groupBy('sale_products.unit')
            // ->groupBy('sale_products.quantity')
            ->groupBy('sale_products.unit_price_exc_tax')
            ->groupBy('sale_products.unit_price_inc_tax')
            ->groupBy('sale_products.price_type')
            ->groupBy('sale_products.pr_amount')
            ->groupBy('sale_products.unit_discount_amount')
            ->groupBy('sale_products.unit_tax_percent')
            ->groupBy('saleUnit.name')
            ->groupBy('saleUnit.code_name')
            ->groupBy('saleUnit.base_unit_multiplier')
            ->groupBy('baseUnit.code_name')
            // ->groupBy('sale_products.subtotal')
            ->groupBy('products.warranty_id')
            ->groupBy('products.name')
            ->groupBy('products.product_code')
            ->groupBy('warranties.duration')
            ->groupBy('warranties.duration_type')
            ->groupBy('warranties.type')
            ->groupBy('warranties.description')
            ->groupBy('product_variants.variant_name')
            ->groupBy('product_variants.variant_code')
            ->get();

        return $customerCopySaleProducts;
    }

    public function changeDeliveryQtyStatus($sale)
    {
        if ($sale->total_delivered_qty <= 0) {

            $sale->delivery_qty_status = 0; // Pending
        } elseif ($sale->total_delivered_qty > 0 && $sale->total_delivered_qty < $sale->total_ordered_qty) {

            $sale->delivery_qty_status = 1; // Partial
        } elseif ($sale->total_delivered_qty >= $sale->total_ordered_qty) {

            $sale->delivery_qty_status = 2; // Completed
        }

        $sale->save();
    }

    public static function deliveryQtyStatus()
    {
        return [
            1 => 'Pending',
            2 => 'Partial',
            3 => 'Completed',
        ];
    }

    public static function saleStatus()
    {
        return [
            1 => 'Final',
            // 3 => 'Ordered',
            // 2 => 'Draft',
            4 => 'Quotation',
        ];
    }

    public static function saleShipmentStatus()
    {
        return [
            1 => 'Ordered',
            2 => 'Packed',
            3 => 'Shipped',
            4 => 'Delivered',
            5 => 'Cancelled',
        ];
    }

    public function checkCreditLimit($request)
    {
        if ($request->current_balance_amount > 0 && $request->current_balance_side == 'Dr') {

            $customerCreditLimit = DB::table('accounts')
                ->where('accounts.id', $request->customer_account_id)
                ->leftJoin('customers', 'accounts.customer_id', 'customers.id')
                ->select('customers.customer_type', 'customers.credit_limit')
                ->first();

            $customer_type = $customerCreditLimit ? $customerCreditLimit->customer_type : 1;
            $creditLimit = $customerCreditLimit ? $customerCreditLimit->credit_limit : 0;
            $__credit_limit = $creditLimit ? $creditLimit : 0;
            $msg_1 = 'Customer does not have any credit limit.';
            $msg_2 = "Customer Credit Limit is ${__credit_limit}.";
            $msg_3 = 'Customer is non-credit. Can not sale in credit.';
            $__show_msg = $__credit_limit ? $msg_2 : $msg_1;

            if ($customer_type == 1) {

                // return response()->json(['errorMsg' => $msg_3]);
                return ['pass' => false, 'msg' => $msg_3];
            } elseif ($request->current_balance_amount > $__credit_limit) {

                // return response()->json(['errorMsg' => $__show_msg]);
                return ['pass' => false, 'msg' => $__show_msg];
            }
        }

        return ['pass' => true];
    }
}
