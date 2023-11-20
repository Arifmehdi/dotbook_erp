<?php

namespace App\Utils;

class DayBookPrintParticularUtil
{
    public function voucherType($voucherTypeId)
    {
        $data = [
            1 => ['name' => 'Sales', 'id' => 'sale_id', 'voucher_no' => 'sales_voucher'],
            2 => ['name' => 'Sales Order', 'id' => 'sale_id', 'voucher_no' => 'sales_order_voucher'],
            3 => ['name' => 'Sales Return', 'id' => 'sale_return_id', 'voucher_no' => 'sale_return_voucher'],
            4 => ['name' => 'Purchase', 'id' => 'purchase_id', 'voucher_no' => 'purchase_voucher'],
            5 => ['name' => 'Purchase Order', 'id' => 'purchase_id', 'voucher_no' => 'purchase_voucher'],
            6 => ['name' => 'Purchase Return', 'id' => 'purchase_return_id', 'voucher_no' => 'purchase_return_voucher'],
            7 => ['name' => 'Expenses', 'id' => 'expense_id', 'voucher_no' => 'expense_voucher'],
            8 => ['name' => 'Stock Adjustment', 'id' => 'stock_adjustment_id', 'voucher_no' => 'stock_adjustment_voucher'],
            9 => ['name' => 'Receipt', 'id' => 'payment_id', 'voucher_no' => 'payment_voucher'],
            10 => ['name' => 'Payment', 'id' => 'payment_id', 'voucher_no' => 'payment_voucher'],
            11 => ['name' => 'Contra', 'id' => 'contra_id', 'voucher_no' => 'contra_voucher'],
            12 => ['name' => 'Journal', 'id' => 'journal_id', 'voucher_no' => 'journal_voucher'],
            13 => ['name' => 'Daily Stock', 'id' => 'daily_stock_id', 'voucher_no' => 'daily_stock_voucher'],
            14 => ['name' => 'Receive Stock', 'id' => 'receive_stock_id', 'voucher_no' => 'receive_stock_voucher'],
            15 => ['name' => 'Stock Issue', 'id' => 'stock_issue_id', 'voucher_no' => 'stock_issue_voucher'],
        ];

        return $data[$voucherTypeId];
    }

    public function particulars($request, $voucherType, $daybook)
    {
        if ($voucherType == 1) {
            return $this->salesDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 2) {
            return $this->salesDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 3) {
            return $this->salesReturnDetails($request, $daybook);
        } elseif ($voucherType == 4) {
            return $this->purchaseDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 5) {
            return $this->purchaseDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 6) {
            return $this->purchaseReturnDetails($request, $daybook);
        } elseif ($voucherType == 7) {
            return $this->expenseDetails($request, $daybook);
        } elseif ($voucherType == 8) {
            return $this->stockAdjustmentDetails($request, $daybook);
        } elseif ($voucherType == 9) {
            return $this->paymentOrReceiptDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 10) {
            return $this->paymentOrReceiptDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 11) {
            return $this->contraDetails($request, $daybook);
        } elseif ($voucherType == 12) {
            return $this->journalDetails($request, $daybook);
        } elseif ($voucherType == 13) {
            return $this->dailyStockDetails($request, $daybook);
        } elseif ($voucherType == 14) {
            return $this->receiveStockDetails($request, $daybook);
        } elseif ($voucherType == 15) {
            return $this->stockIssueDetails($request, $daybook);
        }
    }

    public function salesDetails($request, $voucherType, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->sale?->sale_note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:0.8 !important;"><strong>('.__('menu.as_per_details').')'.' :</strong></td>';
            $voucherDetails .= '</tr>';
            $totalQty = $voucherType == 1 ? $daybook?->sale?->total_sold_qty : $daybook?->sale?->total_ordered_qty;
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($totalQty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.($voucherType == 1 ? __('menu.sale_discount') : __('menu.order_discount')).'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->sale?->order_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.($voucherType == 1 ? __('menu.sale_tax') : __('menu.order_tax')).'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->sale?->order_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.($voucherType == 1 ? __('menu.total_invoice_amount') : __('menu.total_ordered_amount')).'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->sale?->total_payable_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.payment_note').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.$daybook?->sale?->payment_note.'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100">';
            foreach ($daybook->sale->saleProducts as $saleProduct) {

                if ($saleProduct->quantity > 0 || $saleProduct->ordered_quantity > 0) {

                    $variantName = $saleProduct->variant ? ' - '.$saleProduct->variant->name : '';
                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-50">- '.$saleProduct?->product?->name.$variantName.'</td>';

                    $baseUnitMultiplier = $saleProduct?->saleUnit?->base_unit_multiplier ? $saleProduct?->saleUnit?->base_unit_multiplier : 1;
                    $soldQty = $saleProduct->quantity / $baseUnitMultiplier;
                    $orderedQty = $saleProduct->ordered_quantity / $baseUnitMultiplier;
                    $showingQty = $soldQty > 0 ? $soldQty : $orderedQty;
                    $priceIncTax = $saleProduct->unit_price_inc_tax * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">'.\App\Utils\Converter::format_in_bdt($showingQty).'/'.$saleProduct?->saleUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">('.\App\Utils\Converter::format_in_bdt($showingQty).'X'.$priceIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">='.\App\Utils\Converter::format_in_bdt($saleProduct->subtotal).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            }
            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$productName.'</strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function salesReturnDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->salesReturn?->return_note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:0.8 !important;"><strong>('.__('menu.as_per_details').')'.':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.return_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->return_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.return_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->return_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_returned_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->total_return_amount).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100">';
            foreach ($daybook->salesReturn->returnProducts as $returnProduct) {

                if ($returnProduct->return_qty) {

                    $variantName = $returnProduct?->variant ? ' - '.$returnProduct->variant->name : '';
                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-50">- '.$returnProduct?->product?->name.$variantName.'</td>';

                    $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                    $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                    $unitPriceIncTax = $returnProduct->unit_price_inc_tax * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">'.\App\Utils\Converter::format_in_bdt($returnedQty).'/'.$returnProduct?->returnUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">('.\App\Utils\Converter::format_in_bdt($returnedQty).'X'.$unitPriceIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">='.\App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$productName.'</strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function purchaseDetails($request, $voucherType, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->purchase?->purchase_note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100">';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:0.8 !important;"><strong>('.__('menu.as_per_details').')'.' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchase?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchase?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.($voucherType == 4 ? __('menu.purchase_discount') : __('menu.order_discount')).'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchase?->order_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.($voucherType == 4 ? __('menu.purchase_tax') : __('menu.order_tax')).'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchase?->purchase_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.($voucherType == 4 ? __('menu.total_invoice_amount') : __('menu.total_ordered_amount')).'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchase?->total_purchase_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.payment_note').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.$daybook?->purchase?->payment_note.'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100">';
            if (count($daybook?->purchase?->purchaseProducts) > 0) {

                foreach ($daybook->purchase->purchaseProducts as $purchaseProduct) {

                    $variantName = $purchaseProduct?->variant ? ' - '.$purchaseProduct->variant->name : '';
                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-50">- '.$purchaseProduct?->product?->name.$variantName.'</td>';

                    $baseUnitMultiplier = $purchaseProduct?->purchaseUnit?->base_unit_multiplier ? $purchaseProduct?->purchaseUnit?->base_unit_multiplier : 1;
                    $purchasedQty = $purchaseProduct->quantity / $baseUnitMultiplier;
                    $unitCostIncTax = $purchaseProduct->net_unit_cost * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">'.\App\Utils\Converter::format_in_bdt($purchasedQty).'/'.$purchaseProduct?->purchaseUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">('.\App\Utils\Converter::format_in_bdt($purchasedQty).'X'.$unitCostIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">='.\App\Utils\Converter::format_in_bdt($purchaseProduct->line_total).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            } elseif (count($daybook->purchase->orderedProducts) > 0) {

                foreach ($daybook->purchase->orderedProducts as $orderProduct) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-50">- '.$orderProduct?->product?->name.'</td>';

                    $baseUnitMultiplier = $orderProduct?->orderUnit?->base_unit_multiplier ? $orderProduct?->orderUnit?->base_unit_multiplier : 1;
                    $orderedQty = $orderProduct->order_quantity / $baseUnitMultiplier;
                    $unitCostIncTax = $orderProduct->net_unit_cost * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">'.\App\Utils\Converter::format_in_bdt($orderedQty).'/'.$orderProduct?->orderUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">('.\App\Utils\Converter::format_in_bdt($orderedQty).'X'.$unitCostIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">='.\App\Utils\Converter::format_in_bdt($orderProduct->line_total).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$productName.'</strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function purchaseReturnDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->purchaseReturn?->note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:0.8 !important;"><strong>('.__('menu.as_per_details').')'.' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.return_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->return_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.return_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->return_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_returned_amount').'.</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->total_return_amount).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($daybook->purchaseReturn->returnProducts)) {

            $inventoryDetails .= '<table class="w-100">';
            foreach ($daybook->purchaseReturn->returnProducts as $returnProduct) {

                $variantName = $returnProduct?->variant ? ' - '.$returnProduct->variant->name : '';
                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:0.5!important;" class="w-50">- '.$returnProduct?->product?->name.$variantName.'</td>';

                $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                $unitCostIncTax = $returnProduct->unit_cost_inc_tax * $baseUnitMultiplier;

                $inventoryDetails .= '<td style="line-height:0.5!important;">'.\App\Utils\Converter::format_in_bdt($returnedQty).'/'.$returnProduct?->returnUnit?->code_name.'</td>';

                $inventoryDetails .= '<td style="line-height:0.5!important;">('.\App\Utils\Converter::format_in_bdt($returnedQty).'X'.$unitCostIncTax.')</td>';

                $inventoryDetails .= '<td style="line-height:0.5!important;">='.\App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal).'</td>';
                $inventoryDetails .= '</tr>';
            }

            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$productName.'</strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function expenseDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->expense?->note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<p class="p-0 m-0"><strong>('.__('menu.as_per_details').')'.' :</strong></p>';
            $voucherDetails .= '<table class="w-100">';

            foreach ($daybook?->expense?->expenseDescriptions as $description) {

                if ($description->account_id != $showingAccountId) {

                    $transactionDetails = '';
                    if ($request->transaction_details == 1) {

                        if (
                            $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
                        ) {

                            $transactionDetails .= $description?->paymentMethod?->name;
                            $transactionDetails .= ' - TransNo: '.$description->transaction_no;
                            $transactionDetails .= ' - ChequeNo: '.$description->cheque_no;
                            $transactionDetails .= ' - SerialNo: '.$description->cheque_serial_no;
                            $transactionDetails .= ' - IssueDate: '.$description->cheque_issue_date;
                            // $transactionDetails .= ' - R.Note : ' . $description->remarkable_note;
                        }
                    }

                    $amount = \App\Utils\Converter::format_in_bdt($description->amount);
                    $amount_type = $description->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                    $__amount = ' : '.$amount.$amount_type;
                    // $assignedUser = $description?->user ? (' - Sr ' . $description?->user?->prefix . ' ' . $description?->user?->name . ' ' . $description?->user?->last_name) : '';

                    $voucherDetails .= '<tr style="line-height:1 !important; padding:0.15em !important;">';
                    $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60">'.'<strong>'.$description?->account?->name.'</a></strong></td>';
                    $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">: '.$amount.$amount_type.'</td>';

                    if ($transactionDetails) {

                        $voucherDetails .= '<tr><td colspan="2" style="line-height:1 !important; padding:0.15em !important;">'.$transactionDetails.'</td></tr>';
                    }

                    $voucherDetails .= '</tr>';
                }
            }

            $voucherDetails .= '</table>';
        }

        $transactionDetails = '';
        if ($request->transaction_details == 1) {

            $description = $daybook?->expense?->expenseDescriptions->where('amount_type', 'dr')->first();

            if (
                $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
            ) {

                $transactionDetails .= $description?->paymentMethod?->name;
                $transactionDetails .= ' - TransNo: '.$description->transaction_no;
                $transactionDetails .= ' - ChequeNo: '.$description->cheque_no;
                $transactionDetails .= ' - SerialNo: '.$description->cheque_serial_no;
                $transactionDetails .= ' - IssueDate: '.$description->cheque_issue_date;
                // $transactionDetails .= ' - R.Note : ' . $description->remarkable_note;
            }
        }

        $transactionDetails = ($transactionDetails ? '<p class="p-0 m-0">'.$transactionDetails.'</p>' : '');

        // return $voucherDetails . $note;

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$productName.$transactionDetails.'</strong></p>'.$voucherDetails.$note;
    }

    public function stockAdjustmentDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->stockAdjustment?->reason.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:0.8 !important;"><strong>('.__('menu.as_per_details').')'.' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->stockAdjustment?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->stockAdjustment?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_recovered_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->stockAdjustment?->recovered_amount).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($daybook->stockAdjustment->adjustmentProducts)) {

            $inventoryDetails .= '<table class="w-100">';
            foreach ($daybook->stockAdjustment->adjustmentProducts as $adjustmentProduct) {

                $variantName = $adjustmentProduct?->variant ? ' - '.$adjustmentProduct->variant->name : '';

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-50">- '.$adjustmentProduct?->product?->name.$variantName.'</td>';
                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">'.\App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity).'/'.$adjustmentProduct->unit.'</td>';

                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">('.\App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity).'X'.$adjustmentProduct->unit_cost_inc_tax.')</td>';

                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">='.\App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal).'</td>';
                $inventoryDetails .= '</tr>';
            }

            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$productName.'</strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function paymentOrReceiptDetails($request, $voucherType, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->payment?->remarks.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<p class="p-0 m-0"><strong>('.__('menu.as_per_details').')'.' :</strong></p>';
            $voucherDetails .= '<table class="w-100">';

            foreach ($daybook?->payment?->descriptions as $description) {

                if ($showingAccountId != $description->account_id) {

                    $transactionDetails = '';
                    if ($request->transaction_details == 1) {

                        if (
                            $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
                        ) {

                            $transactionDetails .= $description?->paymentMethod?->name;
                            $transactionDetails .= '-TransNo:'.$description->transaction_no;
                            $transactionDetails .= '-ChequeNo: '.$description->cheque_no;
                            $transactionDetails .= '-SerialNo: '.$description->cheque_serial_no;
                            $transactionDetails .= '-IssueDate: '.$description->cheque_issue_date;
                            // $transactionDetails .= ' - R.Note : ' . $description->remarkable_note;
                        }
                    }

                    $amount = \App\Utils\Converter::format_in_bdt($description->amount);
                    $amount_type = $description->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                    $__amount = ' : '.$amount.$amount_type;
                    $assignedUser = $description?->user ? (' - <strong>Sr.</strong> '.$description?->user?->prefix.' '.$description?->user?->name.' '.$description?->user?->last_name) : '';

                    $voucherDetails .= '<tr>';
                    $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important" class="w-60">'.'<strong>'.$description?->account?->name.'</strong>'.$assignedUser.'</td>';
                    $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important">: '.$amount.$amount_type.'</td>';

                    if ($transactionDetails) {

                        $voucherDetails .= '<tr><td colspan="2" style="line-height:1 !important; padding:0.15em !important">'.$transactionDetails.'</td></tr>';
                    }

                    $voucherDetails .= '</tr>';

                    if (count($description->references) > 0) {

                        $referencesDetails = '';
                        // $referencesDetails = '<tr style="line-height:0.9 !important;"><td colspan="2" style="line-height:0.9 !important;"> </td></tr>';
                        $referencesDetails .= '<tr><td colspan="2" style="line-height:1 !important; padding:0.15em !important"><strong>(Against References) :</strong>';
                        foreach ($description->references as $reference) {

                            $sale = '';
                            if ($reference->sale) {

                                if ($reference->sale->order_status == 1) {

                                    $sale = '<p class="fw-bold" style="line-height:1">Sales-Order : '.$reference->sale->order_id.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                                } else {

                                    $sale = '<p class="fw-bold" style="line-height:1">Sales : '.$reference?->sale->invoice_id.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                                }
                            }

                            $purchase = '';
                            if ($reference->purchase) {

                                if ($reference->purchase->purchase_status == 1) {

                                    $purchase = '<p class="fw-bold" style="line-height:1">Purchase : '.$reference?->purchase->invoice_id.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                                } else {

                                    $purchase = '<p class="fw-bold" style="line-height:1">PO : '.$reference?->purchase->invoice_id.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                                }
                            }

                            $stockAdjustment = '';
                            if ($reference->stockAdjustment) {

                                $stockAdjustment = '<p class="fw-bold" style="line-height:1">PO : '.$reference?->stockAdjustment->voucher_no.' = '.\App\Utils\Converter::format_in_bdt($reference->amount);
                            }

                            $referencesDetails .= $sale.$purchase.$stockAdjustment;
                        }

                        $referencesDetails .= '</td></tr>';
                        $voucherDetails .= $referencesDetails;
                    }
                }
            }

            $voucherDetails .= '</table>';
        }

        $transactionDetails = '';

        $description = '';
        if ($voucherType == 9) {

            $description = $daybook?->payment?->descriptions->where('amount_type', 'cr')->first();
        } else {

            $description = $daybook?->payment?->descriptions->where('amount_type', 'dr')->first();
        }

        if ($request->transaction_details == 1 && $description) {

            if (
                $description->payment_method_id ||
                $description->transaction_no ||
                $description->cheque_no ||
                $description->cheque_serial_no ||
                $description->cheque_issue_date
            ) {

                $transactionDetails .= $description?->paymentMethod?->name;
                $transactionDetails .= ' - TransNo: '.$description->transaction_no;
                $transactionDetails .= ' - ChequeNo: '.$description->cheque_no;
                $transactionDetails .= ' - SerialNo: '.$description->cheque_serial_no;
                $transactionDetails .= ' - IssueDate: '.$description->cheque_issue_date;
                // $transactionDetails .= ' - R.Note : ' . $description->remarkable_note;
            }
        }

        $__ledgerReferenceUser = '';
        if (isset($description?->user)) {

            $__ledgerReferenceUser = '<strong> - Sr.</strong> '.$description?->user?->prefix.' '.$description?->user?->name.' '.$description?->user?->last_name;
        }

        $transactionDetails = ($transactionDetails ? '<p class="p-0 m-0">'.$transactionDetails.'</p>' : '');

        // $voucherDetails .= '<p>' . $__ledgerReferenceUser . '</p><p><strong><a href="' . route('accounting.accounts.ledger', [($description?->account?->id ? $description?->account?->id : 'null'), 'accountId']) . '" target="_blank">' . $description?->account?->name . '</a></strong>' . $assignedUser . '</p>' . ($transactionDetails ? '<p class="p-0 m-0">' . $transactionDetails . '</p>' : '');

        // return $voucherDetails . $note;

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$__ledgerReferenceUser.$productName.$transactionDetails.'</strong></p>'.$voucherDetails.$note;
    }

    public function contraDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->contra?->remarks.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<p class="p-0 m-0"><strong>('.__('menu.as_per_details').')'.' :</strong></p>';
            $voucherDetails .= '<table class="w-100">';

            foreach ($daybook->contra->descriptions as $description) {

                if ($showingAccountId != $description->account_id) {
                    $transactionDetails = '';
                    if ($request->transaction_details == 1) {

                        if (
                            $description->payment_method_id ||
                            $description->transaction_no ||
                            $description->cheque_no ||
                            $description->cheque_serial_no ||
                            $description->cheque_issue_date
                        ) {

                            $transactionDetails .= $description?->paymentMethod?->name;
                            $transactionDetails .= ' - TransNo: '.$description->transaction_no;
                            $transactionDetails .= ' - ChequeNo: '.$description->cheque_no;
                            $transactionDetails .= ' - SerialNo: '.$description->cheque_serial_no;
                            $transactionDetails .= ' - IssueDate: '.$description->cheque_issue_date;
                            // $transactionDetails .= ' - R.Note : ' . $contraDescription->remarkable_note;
                        }
                    }

                    $amount = \App\Utils\Converter::format_in_bdt($description->amount);
                    $amount_type = $description->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                    $__amount = ' : '.$amount.$amount_type;

                    $voucherDetails .= '<tr>';
                    $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60">'.'<strong>'.$description?->account?->name.'</strong></td>';
                    $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">: '.$amount.$amount_type.'</td>';
                    $voucherDetails .= '</tr>';

                    if ($transactionDetails) {

                        $voucherDetails .= '<tr><td colspan="2" style="line-height:1 !important; padding:0.15em !important;">'.$transactionDetails.'</td></tr>';
                    }
                }
            }

            $voucherDetails .= '</table></div>';
            $voucherDetails .= '</div>';
        }

        $transactionDetails = '';
        if ($request->transaction_details == 1) {

            $description = $daybook?->contra?->descriptions->where('amount_type', 'cr')->first();

            if (
                $description &&
                ($description->payment_method_id ||
                    $description->transaction_no ||
                    $description->cheque_no ||
                    $description->cheque_serial_no ||
                    $description->cheque_issue_date)
            ) {

                $transactionDetails .= $description?->paymentMethod?->name;
                $transactionDetails .= ' - TransNo: '.$description->transaction_no;
                $transactionDetails .= ' - ChequeNo: '.$description->cheque_no;
                $transactionDetails .= ' - SerialNo: '.$description->cheque_serial_no;
                $transactionDetails .= ' - IssueDate: '.$description->cheque_issue_date;
                // $transactionDetails .= ' - R.Note : ' . $description->remarkable_note;
            }
        }

        $transactionDetails = ($transactionDetails ? '<p class="p-0 m-0">'.$transactionDetails.'</p>' : '');

        // return $voucherDetails . $note;

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$productName.'</strong></p>'.$transactionDetails.$voucherDetails.$note;
    }

    public function journalDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->entries?->remarks.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<p class="p-0 m-0"><strong>('.__('menu.as_per_details').')'.' :</strong></p>';
            $voucherDetails .= '<table class="w-100">';

            foreach ($daybook?->journal?->entries as $description) {

                if ($showingAccountId != $description->account_id) {

                    $transactionDetails = '';
                    if ($request->transaction_details == 1) {

                        if (
                            $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
                        ) {

                            $transactionDetails .= $description?->paymentMethod?->name;
                            $transactionDetails .= '-TransNo:'.$description->transaction_no;
                            $transactionDetails .= '-ChequeNo: '.$description->cheque_no;
                            $transactionDetails .= '-SerialNo: '.$description->cheque_serial_no;
                            $transactionDetails .= '-IssueDate: '.$description->cheque_issue_date;
                            // $transactionDetails .= ' - R.Note : ' . $description->remarkable_note;
                        }
                    }

                    $amount = \App\Utils\Converter::format_in_bdt($description->amount);
                    $amount_type = $description->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                    $__amount = ' : '.$amount.$amount_type;
                    $assignedUser = $description?->user ? (' - <strong>Sr.</strong> '.$description?->user?->prefix.' '.$description?->user?->name.' '.$description?->user?->last_name) : '';

                    $voucherDetails .= '<tr>';
                    $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60">'.'<strong>'.$description?->account?->name.'</strong>'.$assignedUser.'</td>';
                    $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">: '.$amount.$amount_type.'</td>';

                    if ($transactionDetails) {

                        $voucherDetails .= '<tr><td colspan="2" style="line-height:1 !important; padding:0.15em !important;">'.$transactionDetails.'</td></tr>';
                    }

                    $voucherDetails .= '</tr>';

                    if (count($description->references) > 0) {

                        $referencesDetails = '';
                        // $referencesDetails = '<tr style="line-height:0.9 !important;"><td colspan="2" style="line-height:0.9 !important;"> </td></tr>';
                        $referencesDetails .= '<tr><td colspan="2" style="line-height:0.8 !important;"><strong>(Against References) :</strong>';
                        foreach ($description->references as $reference) {

                            $sale = '';
                            if ($reference->sale) {

                                if ($reference->sale->order_status == 1) {

                                    $sale = '<p class="fw-bold" style="line-height:14px">Sales-Order : '.$reference->sale->order_id.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                                } else {

                                    $sale = '<p class="fw-bold" style="line-height:14px">Sales : '.$reference?->sale->invoice_id.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                                }
                            }

                            $purchase = '';
                            if ($reference->purchase) {

                                if ($reference->purchase->purchase_status == 1) {

                                    $purchase = '<p class="fw-bold" style="line-height:14px">Purchase : '.$reference?->purchase->invoice_id.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                                } else {

                                    $purchase = '<p class="fw-bold" style="line-height:14px">PO : '.$reference?->purchase->invoice_id.' = '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                                }
                            }

                            $stockAdjustment = '';
                            if ($reference->stockAdjustment) {

                                $stockAdjustment = '<p class="fw-bold" style="line-height:14px">PO : '.$reference?->stockAdjustment->voucher_no.' '.\App\Utils\Converter::format_in_bdt($reference->amount);
                            }

                            $referencesDetails .= $sale.$purchase.$stockAdjustment;
                        }

                        $referencesDetails .= '</td></tr>';
                        $voucherDetails .= $referencesDetails;
                    }
                }
            }

            $voucherDetails .= '</table>';
        }

        $transactionDetails = '';
        $description = $daybook?->journal?->entries->where('amount_type', 'dr')->first();

        if ($request->transaction_details == 1 && $description) {

            if (
                $description->payment_method_id ||
                $description->transaction_no ||
                $description->cheque_no ||
                $description->cheque_serial_no ||
                $description->cheque_issue_date
            ) {

                $transactionDetails .= $description?->paymentMethod?->name;
                $transactionDetails .= ' - TransNo: '.$description->transaction_no;
                $transactionDetails .= ' - ChequeNo: '.$description->cheque_no;
                $transactionDetails .= ' - SerialNo: '.$description->cheque_serial_no;
                $transactionDetails .= ' - IssueDate: '.$description->cheque_issue_date;
                $transactionDetails .= ' - R.Note : '.$description->remarkable_note;
            }
        }

        $__ledgerReferenceUser = '';
        if (isset($description?->user)) {

            $__ledgerReferenceUser = '<strong> - Sr.</strong> '.$description?->user?->prefix.' '.$description?->user?->name.' '.$description?->user?->last_name;
        }

        $transactionDetails = ($transactionDetails ? '<p class="p-0 m-0">'.$transactionDetails.'</p>' : '');

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$__ledgerReferenceUser.$productName.$transactionDetails.'</strong></p>'.$voucherDetails.$note;
    }

    public function DailyStockDetails($request, $daybook)
    {
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->dailyStock?->note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:0.8 !important;"><strong>('.__('menu.as_per_details').')'.' :</strong></td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->dailyStock?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_stock_value').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->dailyStock?->total_stock_value).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100">';
            foreach ($daybook->dailyStock->dailyStockProducts as $dailyStockProduct) {

                $variantName = $dailyStockProduct?->variant ? ' - '.$dailyStockProduct->variant->name : '';

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-50">- '.$dailyStockProduct?->product?->name.$variantName.'</td>';

                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">'.\App\Utils\Converter::format_in_bdt($dailyStockProduct->quantity).'/'.$dailyStockProduct?->unit.'</td>';

                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">('.\App\Utils\Converter::format_in_bdt($dailyStockProduct->quantity).'X'.$dailyStockProduct->unit_cost_inc_tax.')</td>';

                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">='.\App\Utils\Converter::format_in_bdt($dailyStockProduct->subtotal).'</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>'.$showingProduct.'</strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function receiveStockDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->receiveStock?->note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:0.8 !important;"><strong>('.__('menu.as_per_details').')'.' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.challan_no').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.$daybook?->receiveStock?->challan_no.'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.challan_date').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.$daybook?->receiveStock?->challan_date.'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_item').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->receiveStock?->total_item).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->receiveStock?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100">';
            foreach ($daybook->receiveStock->receiveStockProducts as $receiveStockProduct) {

                $variantName = $receiveStockProduct?->variant ? ' - '.$receiveStockProduct->variant->name : '';
                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-50">- '.$receiveStockProduct?->product?->name.$variantName.'</td>';

                $baseUnitMultiplier = $receiveStockProduct?->receiveUnit?->base_unit_multiplier ? $receiveStockProduct?->receiveUnit?->base_unit_multiplier : 1;
                $receivedQty = $receiveStockProduct->quantity / $baseUnitMultiplier;

                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">'.\App\Utils\Converter::format_in_bdt($receivedQty).'/'.$receiveStockProduct?->receiveUnit?->code_name.'</td>';

                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? $showingAccount : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>'.$accountName.$productName.'</strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function StockIssueDetails($request, $daybook)
    {
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$daybook?->stockIssue?->note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:0.8 !important;"><strong>('.__('menu.as_per_details').')'.' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->stockIssue?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-60"><strong>'.__('menu.total_stock_value').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important; padding:0.15em !important;"> : '.\App\Utils\Converter::format_in_bdt($daybook?->stockIssue?->net_total_value).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100">';
            foreach ($daybook->stockIssue->issueProducts as $issueProduct) {

                $variantName = $issueProduct?->variant ? ' - '.$issueProduct->variant->name : '';

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;" class="w-50">- '.$issueProduct?->product?->name.$variantName.'</td>';

                $baseUnitMultiplier = $issueProduct?->issueUnit?->base_unit_multiplier ? $issueProduct?->issueUnit?->base_unit_multiplier : 1;
                $issuedQty = $issueProduct->quantity / $baseUnitMultiplier;
                $unitCostIncTax = $issueProduct->unit_cost_inc_tax * $baseUnitMultiplier;

                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">'.\App\Utils\Converter::format_in_bdt($issuedQty).'/'.$issueProduct?->issueUnit->code_name.'</td>';

                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">('.\App\Utils\Converter::format_in_bdt($issuedQty).'X'.$unitCostIncTax.')</td>';

                $inventoryDetails .= '<td style="line-height:1 !important; padding:0.15em !important;">='.\App\Utils\Converter::format_in_bdt($issueProduct->subtotal).'</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>'.$showingProduct.'</strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }
}
