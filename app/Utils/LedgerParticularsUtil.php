<?php

namespace App\Utils;

class LedgerParticularsUtil
{
    public function particulars($request, $voucherType, $ledger, $by)
    {
        if ($voucherType == 0) {
            return $this->openingBalanceDetails($request, $ledger, $by);
        } elseif ($voucherType == 1) {
            return $this->salesDetails($request, $ledger);
        } elseif ($voucherType == 2) {
            return $this->salesReturnDetails($request, $ledger);
        } elseif ($voucherType == 3) {
            return $this->purchaseDetails($request, $ledger);
        } elseif ($voucherType == 4) {
            return $this->purchaseReturnDetails($request, $ledger);
        } elseif ($voucherType == 5) {
            return $this->expenseDetails($request, $ledger);
        } elseif ($voucherType == 7) {
            return $this->stockAdjustmentDetails($request, $ledger);
        } elseif ($voucherType == 8) {
            return $this->paymentOrReceiptDetails($request, $ledger);
        } elseif ($voucherType == 9) {
            return $this->paymentOrReceiptDetails($request, $ledger);
        } elseif ($voucherType == 12) {
            return $this->contraDetails($request, $ledger);
        } elseif ($voucherType == 13) {
            return $this->journalDetails($request, $ledger);
        } elseif ($voucherType == 15) {
            return $this->incomeReceiptDetails($request, $ledger);
        } elseif ($voucherType == 16) {
            return $this->saleProductTax($request, $ledger);
        } elseif ($voucherType == 17) {
            return $this->purchaseProductTax($request, $ledger);
        } elseif ($voucherType == 18) {
            return $this->saleReturnProductTax($request, $ledger);
        } elseif ($voucherType == 19) {
            return $this->purchaseReturnProductTax($request, $ledger);
        } elseif ($voucherType == 20) {
            return $this->dailyStockDetails($request, $ledger);
        }
    }

    public function openingBalanceDetails($request, $ledger, $by)
    {
        $particulars = '<p class="m-0 p-0">';
        $particulars .= '<strong>Opening Balance</strong>';
        $particulars .= ($by == 'userId' ? ' - '.(isset($ledger->account) ? $ledger?->account?->name : '').' - ' : '').($ledger?->user ? ' Sr.'.$ledger?->user?->prefix.' '.$ledger?->user?->name.' '.$ledger?->user?->last_name : '');

        return $particulars;
    }

    public function salesDetails($request, $ledger)
    {
        $showingAccount = $ledger->account_id == $ledger?->sale?->customer_account_id ? $ledger?->sale?->salesAccount?->name : $ledger?->sale?->customer?->name;
        $showingAccountId = $ledger->account_id == $ledger?->sale?->customer_account_id ? $ledger?->sale?->salesAccount?->id : $ledger?->sale?->customer?->id;

        $ledgerReferenceUser = $ledger?->user ? ('<strong>Sr.</strong> '.$ledger?->user?->prefix.' '.$ledger?->user?->name.' '.$ledger?->user?->last_name) : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$ledger?->sale?->sale_note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>('.__('menu.as_per_details').')'.':</strong></td>';
            $voucherDetails .= '</tr>';

            // if ($ledger->account_id != $ledger?->sale?->sale_account_id) {

            //     $voucherDetails .= '<tr>';
            //     $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>Customer</strong></td>';
            //     $voucherDetails .= '<td style="line-height:1 !important;"> : <a href="' . route('accounting.accounts.ledger', [($ledger?->sale?->customer_account_id ? $ledger?->sale?->customer_account_id : 'null'), 'accountId']) . '" class="text-black">' . $ledger?->sale?->customer?->name . '</a></td>';
            //     $voucherDetails .= '</tr>';
            // }

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->sale?->total_sold_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.sale_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1.2 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->sale?->order_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.sale_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->sale?->order_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_invoice_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->sale?->total_payable_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.payment_note').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.$ledger?->sale?->payment_note.'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->sale->saleProducts as $saleProduct) {

                if ($saleProduct->quantity > 0) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- '.$saleProduct?->product?->name.'</td>';
                    $baseUnitMultiplier = $saleProduct?->saleUnit?->base_unit_multiplier ? $saleProduct?->saleUnit?->base_unit_multiplier : 1;
                    $soldQty = $saleProduct->quantity / $baseUnitMultiplier;
                    $priceIncTax = $saleProduct->unit_price_inc_tax * $baseUnitMultiplier;
                    $inventoryDetails .= '<td style="line-height:1 !important;">'.\App\Utils\Converter::format_in_bdt($soldQty).'/'.$saleProduct?->saleUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($soldQty).'X'.$priceIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">='.\App\Utils\Converter::format_in_bdt($saleProduct->subtotal).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            }
            $inventoryDetails .= '</table>';
        }

        return '<p>'.$ledgerReferenceUser.'</p><p class="m-0 p-0"><strong><a href="'.route('accounting.accounts.ledger', [($showingAccountId ? $showingAccountId : 'null'), 'accountId']).'" target="_blank">'.$showingAccount.'</a></strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function salesReturnDetails($request, $ledger)
    {
        $showingAccount = $ledger->account_id == $ledger?->salesReturn?->customer_account_id ? $ledger?->salesReturn?->salesAccount?->name : $ledger?->salesReturn?->customer?->name;
        $showingAccountId = $ledger->account_id == $ledger?->salesReturn?->customer_account_id ? $ledger?->salesReturn?->salesAccount?->id : $ledger?->salesReturn?->customer?->id;

        $ledgerReferenceUser = $ledger?->user ? ('<strong>Sr.</strong> '.$ledger?->user?->prefix.' '.$ledger?->user?->name.' '.$ledger?->user?->last_name) : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$ledger?->salesReturn?->return_note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            // $voucherDetails .= '<tr>';
            // $voucherDetails .= '<td style="line-height:1.2 !important;"><strong>Customer</strong></td>';
            // $voucherDetails .= '<td style="line-height:1.2 !important;"> : ' . $ledger?->sale?->customer?->name . '</td>';
            // $voucherDetails .= '</tr>';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>('.__('menu.as_per_details').')'.':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.return_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->return_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.return_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->return_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_returned_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->total_return_amount).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->salesReturn->returnProducts as $returnProduct) {

                if ($returnProduct->return_qty) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- '.$returnProduct?->product?->name.'</td>';

                    $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                    $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                    $unitPriceIncTax = $returnProduct->unit_price_inc_tax * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">'.\App\Utils\Converter::format_in_bdt($returnedQty).'/'.$returnProduct?->returnUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($returnedQty).'X'.$unitPriceIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">='.\App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        return '<p>'.$ledgerReferenceUser.'</p><p class="m-0 p-0"><strong><a href="'.route('accounting.accounts.ledger', [($showingAccountId ? $showingAccountId : 'null'), 'accountId']).'" target="_blank">'.$showingAccount.'</a></strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function purchaseDetails($request, $ledger)
    {
        $showingAccount = $ledger->account_id == $ledger?->purchase?->supplier_account_id ? $ledger?->purchase?->purchaseAccount?->name : $ledger?->purchase?->supplier?->name;
        $showingAccountId = $ledger->account_id == $ledger?->purchase?->supplier_account_id ? $ledger?->purchase?->purchaseAccount?->id : $ledger?->purchase?->supplier?->id;

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$ledger->purchase->purchase_note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1.2 !important;"><strong>('.__('menu.as_per_details').')'.':</strong></td>';
            $voucherDetails .= '</tr>';

            // if ($ledger->account_id != $ledger?->purchase?->supplier_account_id) {

            //     $voucherDetails .= '<tr>';
            //     $voucherDetails .= '<td style="line-height:1 !important;"><strong>Supplier</strong></td>';
            //     $voucherDetails .= '<td style="line-height:1 !important;"> : <a href="' . route('accounting.accounts.ledger', [($ledger?->purchase?->supplier_account_id ? $ledger?->purchase?->supplier_account_id : 'null'), 'accountId']) . '" target="_blank" class="text-black">' . $ledger?->purchase?->supplier?->name . '</a></td>';
            //     $voucherDetails .= '</tr>';
            // }

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchase?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchase?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.purchase_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchase?->order_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.purchase_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchase?->purchase_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"class="w-60"><strong>'.__('menu.total_invoice_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchase?->total_purchase_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.payment_note').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.$ledger?->purchase?->payment_note.'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->purchase->purchaseProducts as $purchaseProduct) {

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- '.$purchaseProduct?->product?->name.'</td>';

                $baseUnitMultiplier = $purchaseProduct?->purchaseUnit?->base_unit_multiplier ? $purchaseProduct?->purchaseUnit?->base_unit_multiplier : 1;
                $purchasedQty = $purchaseProduct->quantity / $baseUnitMultiplier;
                $unitCostIncTax = $purchaseProduct->net_unit_cost * $baseUnitMultiplier;

                $inventoryDetails .= '<td style="line-height:1 !important;">'.\App\Utils\Converter::format_in_bdt($purchasedQty).'/'.$purchaseProduct->purchaseUnit?->code_name.'</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($purchasedQty).'X'.$unitCostIncTax.')</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">='.\App\Utils\Converter::format_in_bdt($purchaseProduct->line_total).'</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong><a href="'.route('accounting.accounts.ledger', [($showingAccountId ? $showingAccountId : 'null'), 'accountId']).'" target="_blank">'.$showingAccount.'</a></strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function purchaseReturnDetails($request, $ledger)
    {
        $showingAccount = $ledger->account_id == $ledger?->purchaseReturn?->supplier_account_id ? $ledger?->purchaseReturn?->purchaseAccount?->name : $ledger?->purchaseReturn?->supplier?->name;

        $showingAccountId = $ledger->account_id == $ledger?->purchaseReturn?->supplier_account_id ? $ledger?->purchaseReturn?->purchaseAccount?->id : $ledger?->purchaseReturn?->supplier?->id;

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$ledger?->purchaseReturn?->note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>('.__('menu.as_per_details').')'.':</strong></td>';
            $voucherDetails .= '</tr>';

            // if ($ledger->account_id != $ledger?->purchaseReturn?->purchase_account_id) {

            //     $voucherDetails .= '<tr>';
            //     $voucherDetails .= '<td style="line-height:1 !important;"><strong>Supplier</strong></td>';
            //     $voucherDetails .= '<td style="line-height:1 !important;"> : ' . $ledger?->purchaseReturn?->supplier?->name . '</td>';
            //     $voucherDetails .= '</tr>';
            // }

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.return_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->return_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.return_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->return_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>'.__('menu.total_returned_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->total_return_amount).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($ledger->purchaseReturn->returnProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->purchaseReturn->returnProducts as $returnProduct) {

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1!important;" class="w-50">- '.$returnProduct?->product?->name.'</td>';

                $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                $unitCostIncTax = $returnProduct->unit_cost_inc_tax * $baseUnitMultiplier;

                $inventoryDetails .= '<td style="line-height:1!important;">'.\App\Utils\Converter::format_in_bdt($returnedQty).'/'.$returnProduct?->returnUnit?->code_name.'</td>';

                $inventoryDetails .= '<td style="line-height:1!important;">('.\App\Utils\Converter::format_in_bdt($returnedQty).'X'.$unitCostIncTax.')</td>';

                $inventoryDetails .= '<td style="line-height:1!important;">='.\App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal).'</td>';
                $inventoryDetails .= '</tr>';
            }

            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong><a href="'.route('accounting.accounts.ledger', [($showingAccountId ? $showingAccountId : 'null'), 'accountId']).'" target="_blank">'.$showingAccount.'</a></strong></p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function expenseDetails($request, $ledger)
    {
        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$ledger?->expenseDescription?->expense?->note.'</p>';
        }

        // $__ledgerReferenceUser = '';
        // $ledgerReferenceUser = $ledger?->user;
        // if (isset($ledgerReferenceUser)) {

        //     $__ledgerReferenceUser = '<strong>Sr.</strong> ' .  $ledger->user->prefix . ' ' . $ledger->user->name . ' ' . $ledger->user->last_name;
        // }

        $collection = $ledger->expenseDescription->expense->expenseDescriptions;

        $descriptions = $collection->filter(function ($description, $key) use ($ledger) {

            return $description->id != $ledger->expense_description_id;
        });

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $detailsAmountType = $ledger->amount_type == 'debit' ? ' Cr.' : ' Dr.';

            $voucherDetails .= '<p class="p-0 m-0"><strong>'.$detailsAmountType.' ('.__('menu.as_per_details').')'.':</strong></p>';
            $voucherDetails .= '<table class="w-100 td_child_table">';

            foreach ($descriptions as $description) {

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
                // $assignedUser = $description?->user ? (' - Sr. ' . $description?->user?->prefix . ' ' . $description?->user?->name . ' ' . $description?->user?->last_name) : '';

                $voucherDetails .= '<tr style="line-height:1 !important;">';
                $voucherDetails .= '<td style="line-height:1 !important;" class="w-60">'.'<strong><a href="'.route('accounting.accounts.ledger', [($description?->account?->id ? $description?->account?->id : 'null'), 'accountId']).'" target="_blank">'.$description?->account?->name.'</a></strong></td>';
                $voucherDetails .= '<td style="line-height:1 !important;">: '.$amount.$amount_type.'</td>';

                if ($transactionDetails) {

                    $voucherDetails .= '<tr><td colspan="2" style="line-height:1 !important;">'.$transactionDetails.'</td></tr>';
                }

                $voucherDetails .= '</tr>';
            }

            $voucherDetails .= '</table>';
        } else {

            $description = $descriptions->first();

            $filteredCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                return $description?->account?->group->sub_sub_group_number == 1 || $description?->account?->group->sub_sub_group_number == 2 || $description?->account?->group->sub_sub_group_number == 11;
            });

            $filteredNotCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                return $description?->account?->group->sub_sub_group_number != 1 && $description?->account?->group->sub_sub_group_number != 2 && $description?->account?->group->sub_sub_group_number != 11;
            });

            $description = '';
            $ledgerAccountGroup = $ledger?->account?->group;
            if (
                $ledgerAccountGroup &&
                $ledgerAccountGroup->sub_sub_group_number != 1 &&
                $ledgerAccountGroup->sub_sub_group_number != 2 &&
                $ledgerAccountGroup->sub_sub_group_number != 11
            ) {

                $description = count($filteredCashOrBankAccounts) > 0 ? $filteredCashOrBankAccounts->first() : $descriptions->first();
            } else {

                $description = count($filteredNotCashOrBankAccounts) > 0 ? $filteredNotCashOrBankAccounts->first() : $descriptions->first();
            }

            $transactionDetails = '';
            // $assignedUser = $description?->user ? (' - Sr. ' . $description?->user?->prefix . ' ' . $description?->user?->name . ' ' . $description?->user?->last_name) : '';
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

            $voucherDetails .= '<p><strong><a href="'.route('accounting.accounts.ledger', [($description?->account?->id ? $description?->account?->id : 'null'), 'accountId']).'" target="_blank">'.$description?->account?->name.'</a>'.($transactionDetails ? '<p class="p-0 m-0">'.$transactionDetails.'</p>' : '');
        }

        return $voucherDetails.$note;
    }

    public function stockAdjustmentDetails($request, $ledger)
    {
        $showingAccount = '<a href="'.route('accounting.accounts.ledger', [($ledger?->stockAdjustment?->account?->id ? $ledger?->stockAdjustment?->account?->id : 'null'), 'accountId']).'" target="_blank">'.$ledger?->stockAdjustment?->account?->name.'</a>';
        $assignedUser = $ledger?->user ? (' - Sr. '.$ledger?->user?->prefix.' '.$ledger?->user?->name.' '.$ledger?->user?->last_name) : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$ledger?->stockAdjustment?->reason.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>('.__('menu.as_per_details').')'.' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->stockAdjustment?->stockAdjustment).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->stockAdjustment?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_recovered_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($ledger?->stockAdjustment?->recovered_amount).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($ledger->stockAdjustment->adjustmentProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->stockAdjustment->adjustmentProducts as $adjustmentProduct) {

                $baseUnitMultiplier = $adjustmentProduct?->stockAdjustmentUnit?->base_unit_multiplier ? $adjustmentProduct?->stockAdjustmentUnit?->base_unit_multiplier : 1;
                $adjustedQty = $adjustmentProduct->quantity / $baseUnitMultiplier;
                $unitCostIncTax = $adjustmentProduct->unit_cost_inc_tax * $baseUnitMultiplier;

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1 !important;" class="w-60">- '.$adjustmentProduct?->product?->name.'</td>';
                $inventoryDetails .= '<td style="line-height:1 !important;">'.\App\Utils\Converter::format_in_bdt($adjustedQty).'/'.$adjustmentProduct?->stockAdjustmentUnit?->code_name.'</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($adjustedQty).'X'.\App\Utils\Converter::format_in_bdt($unitCostIncTax).')</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">='.\App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal).'</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>'.$showingAccount.'</strong>'.$assignedUser.'</p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function paymentOrReceiptDetails($request, $ledger)
    {
        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$ledger?->paymentDescription?->payment?->remarks.'</p>';
        }

        $__ledgerReferenceUser = '';
        $ledgerReferenceUser = $ledger?->user;
        if (isset($ledgerReferenceUser)) {

            $__ledgerReferenceUser = '<strong>Sr.</strong> '.$ledger->user->prefix.' '.$ledger->user->name.' '.$ledger->user->last_name;
        }

        $collection = $ledger->paymentDescription->payment->descriptions;

        $descriptions = $collection->filter(function ($description, $key) use ($ledger) {

            return $description->id != $ledger->payment_description_id;
        });

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $detailsAmountType = $ledger->amount_type == 'debit' ? ' Cr.' : ' Dr.';
            $voucherDetails .= '<p class="p-0 m-0">'.$__ledgerReferenceUser.'</p>';
            $voucherDetails .= '<p class="p-0 m-0"><strong>'.$detailsAmountType.' ('.__('menu.as_per_details').')'.' :</strong></p>';
            $voucherDetails .= '<table class="w-100 td_child_table">';

            foreach ($descriptions as $description) {

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
                $assignedUser = $description?->user ? (' - Sr. '.$description?->user?->prefix.' '.$description?->user?->name.' '.$description?->user?->last_name) : '';

                $voucherDetails .= '<tr>';
                $voucherDetails .= '<td style="line-height:1 !important;" class="w-60">'.'<strong><a href="'.route('accounting.accounts.ledger', [($description?->account?->id ? $description?->account?->id : 'null'), 'accountId']).'" target="_blank">'.$description?->account?->name.'</a></strong>'.$assignedUser.'</td>';
                $voucherDetails .= '<td style="line-height:1 !important;">: '.$amount.$amount_type.'</td>';

                if ($transactionDetails) {

                    $voucherDetails .= '<tr><td colspan="2" style="line-height:1 !important;">'.$transactionDetails.'</td></tr>';
                }

                $voucherDetails .= '</tr>';

                if (count($description->references) > 0) {

                    $referencesDetails = '';
                    // $referencesDetails = '<tr style="line-height:1 !important;"><td colspan="2" style="line-height:1 !important;"> </td></tr>';
                    $referencesDetails .= '<tr><td colspan="2" style="line-height:1 !important;"><strong>(Against References) :</strong>';
                    foreach ($description->references as $reference) {

                        $sale = '';
                        if ($reference->sale) {

                            if ($reference->sale->order_status == 1) {

                                $sale = '<p class="fw-bold" style="line-height:14px">Sales-Order : <a href="'.route('sales.order.show', $reference->sale_id).'" id="details_btn">'.$reference->sale->order_id.' </a>= '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                            } else {

                                $sale = '<p class="fw-bold" style="line-height:14px">Sales : <a href="'.route('sales.show', $reference->sale_id).'" id="details_btn">'.$reference?->sale->invoice_id.' </a>= '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                            }
                        }

                        $purchase = '';
                        if ($reference->purchase) {

                            if ($reference->purchase->purchase_status == 1) {

                                $purchase = '<p class="fw-bold" style="line-height:14px">Purchase : <a href="'.route('purchases.show', $reference->purchase_id).'" id="details_btn">'.$reference?->purchase->invoice_id.' </a>= '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                            } else {

                                $purchase = '<p class="fw-bold" style="line-height:14px">PO : <a href="'.route('purchases.show.order', $reference->purchase_id).'" id="details_btn">'.$reference?->purchase->invoice_id.' </a>= '.\App\Utils\Converter::format_in_bdt($reference->amount).'<p>';
                            }
                        }

                        $stockAdjustment = '';
                        if ($reference->stockAdjustment) {

                            $stockAdjustment = '<p class="fw-bold" style="line-height:14px">PO : <a href="'.route('stock.adjustments.show', $reference->stock_adjustment_id).'" id="details_btn">'.$reference?->stockAdjustment->voucher_no.' </a>= '.\App\Utils\Converter::format_in_bdt($reference->amount);
                        }

                        $referencesDetails .= $sale.$purchase.$stockAdjustment;
                    }

                    $referencesDetails .= '</td></tr>';
                    $voucherDetails .= $referencesDetails;
                }
            }

            $voucherDetails .= '</table>';
        } else {

            // $description = $descriptions->first();

            $filteredCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                return $description?->account?->group->sub_sub_group_number == 1 || $description?->account?->group->sub_sub_group_number == 2 || $description?->account?->group->sub_sub_group_number == 11;
            });

            $filteredNotCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                return $description?->account?->group->sub_sub_group_number != 1 && $description?->account?->group->sub_sub_group_number != 2 && $description?->account?->group->sub_sub_group_number != 11;
            });

            $description = '';
            $ledgerAccountGroup = $ledger?->account?->group;
            if (
                $ledgerAccountGroup &&
                $ledgerAccountGroup->sub_sub_group_number != 1 &&
                $ledgerAccountGroup->sub_sub_group_number != 2 &&
                $ledgerAccountGroup->sub_sub_group_number != 11
            ) {

                $description = count($filteredCashOrBankAccounts) > 0 ? $filteredCashOrBankAccounts->first() : $descriptions->first();
            } else {

                $description = count($filteredNotCashOrBankAccounts) > 0 ? $filteredNotCashOrBankAccounts->first() : $descriptions->first();
            }

            $transactionDetails = '';
            $assignedUser = $description?->user ? (' - Sr '.$description?->user?->prefix.' '.$description?->user?->name.' '.$description?->user?->last_name) : '';
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

            $voucherDetails .= '<p>'.$__ledgerReferenceUser.'</p><p><strong><a href="'.route('accounting.accounts.ledger', [($description?->account?->id ? $description?->account?->id : 'null'), 'accountId']).'" target="_blank">'.$description?->account?->name.'</a></strong>'.$assignedUser.'</p>'.($transactionDetails ? '<p class="p-0 m-0">'.$transactionDetails.'</p>' : '');
        }

        return $voucherDetails.$note;
    }

    public function contraDetails($request, $ledger)
    {
        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$ledger?->contraDescription?->contra?->remarks.'</p>';
        }

        $collection = $ledger->contraDescription->contra->descriptions;

        $descriptions = $collection->filter(function ($description, $key) use ($ledger) {

            return $description->id != $ledger->contra_description_id;
        });

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $detailsAmountType = $ledger->amount_type == 'debit' ? ' Cr.' : ' Dr.';
            $voucherDetails .= '<p class="p-0 m-0"><strong>'.$detailsAmountType.' ('.__('menu.as_per_details').')'.' :</strong></p>';
            $voucherDetails .= '<table class="w-100 td_child_table">';

            foreach ($descriptions as $description) {

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
                        // $transactionDetails .= ' - R.Note : ' . $contraDescription->remarkable_note;
                    }
                }

                $amount = \App\Utils\Converter::format_in_bdt($description->amount);
                $amount_type = $description->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                $__amount = ' : '.$amount.$amount_type;

                $voucherDetails .= '<tr>';
                $voucherDetails .= '<td style="line-height:1 !important;" class="w-60">'.'<strong><a href="'.route('accounting.accounts.ledger', [($description?->account?->id ? $description?->account?->id : 'null'), 'accountId']).'" target="_blank">'.$description?->account?->name.'</a></strong></td>';
                $voucherDetails .= '<td style="line-height:1 !important;">: '.$amount.$amount_type.'</td>';
                $voucherDetails .= '</tr>';

                if ($transactionDetails) {

                    $voucherDetails .= '<tr><td colspan="2" style="line-height:1 !important;">'.$transactionDetails.'</td></tr>';
                }
            }

            $voucherDetails .= '</table>';
        } else {

            $description = $descriptions->first();

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
                    // $transactionDetails .= ' - R.Note : ' . $contraDescription->remarkable_note;
                }
            }

            $voucherDetails .= '<p><strong><a href="'.route('accounting.accounts.ledger', [($description?->account?->id ? $description?->account?->id : 'null'), 'accountId']).'" target="_blank">'.$description?->account?->name.'</a></strong></p>'.($transactionDetails ? '<p class="p-0 m-0">'.$transactionDetails.'</p>' : '');
        }

        return $voucherDetails.$note;
    }

    public function journalDetails($request, $ledger)
    {
        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$ledger?->journalEntry?->journal?->remarks.'</p>';
        }

        $__ledgerReferenceUser = '';
        $ledgerReferenceUser = $ledger?->user;
        if (isset($ledgerReferenceUser)) {

            $__ledgerReferenceUser = '<strong>Sr.</strong> '.$ledger?->user?->prefix.' '.$ledger?->user?->name.' '.$ledger?->user?->last_name;
        }

        $collection = $ledger?->journalEntry?->journal?->entries;

        $entries = null;

        if ($collection) {
            $entries = $collection->filter(function ($entry, $key) use ($ledger) {

                return $entry->id != $ledger->journal_entry_id;
            });
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1 && isset($entries)) {

            $detailsAmountType = $ledger->amount_type == 'debit' ? ' Cr.' : ' Dr.';
            $voucherDetails .= '<p class="p-0 m-0">'.$__ledgerReferenceUser.'</p>';
            $voucherDetails .= '<p class="p-0 m-0"><strong>'.$detailsAmountType.' ('.__('menu.as_per_details').')'.' :</strong></p>';
            $voucherDetails .= '<table class="w-100 td_child_table">';
            foreach ($entries as $entry) {

                $transactionDetails = '';
                if ($request->transaction_details == 1) {

                    if (
                        $entry->payment_method_id || $entry->transaction_no || $entry->cheque_no || $entry->cheque_serial_no || $entry->cheque_issue_date || $entry->remarkable_note
                    ) {

                        $transactionDetails .= $entry?->paymentMethod?->name;
                        $transactionDetails .= ' - TransNo: '.$entry->transaction_no;
                        $transactionDetails .= ' - ChequeNo: '.$entry->cheque_no;
                        $transactionDetails .= ' - SerialNo: '.$entry->cheque_serial_no;
                        $transactionDetails .= ' - IssueDate: '.$entry->cheque_issue_date;
                        $transactionDetails .= ' - R.Note : '.$entry->remarkable_note;
                    }
                }

                $amount = \App\Utils\Converter::format_in_bdt($entry->amount);
                $amount_type = $entry->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                $__amount = ' : '.$amount.$amount_type;
                $assignedUser = $entry?->assignedUser ? (' - Sr. '.$entry?->assignedUser?->prefix.' '.$entry?->assignedUser?->name.' '.$entry->assignedUser?->last_name) : '';

                $voucherDetails .= '<tr>';
                $voucherDetails .= '<td style="line-height:1 !important;" class="w-60">'.'<strong><a href="'.route('accounting.accounts.ledger', [($entry?->account?->id ? $entry?->account?->id : 'null'), 'accountId']).'" target="_blank">'.$entry?->account?->name.'</a></strong>'.$assignedUser.'</td>';
                $voucherDetails .= '<td style="line-height:1 !important;">: '.$amount.$amount_type.'</p>'.'</td>';
                $voucherDetails .= '</tr>';

                if ($transactionDetails) {

                    $voucherDetails .= '<tr><td colspan="2" style="line-height:1 !important;">'.$transactionDetails.'</td></tr>';
                }
            }

            $voucherDetails .= '</table>';
        } else {

            // $entry = $entries?->first();

            $filteredCashOrBankAccounts = $entries->filter(function ($entry, $key) {

                return $entry?->account?->group->sub_sub_group_number == 1 || $entry?->account?->group->sub_sub_group_number == 2 || $entry?->account?->group->sub_sub_group_number == 11;
            });

            $filteredNotCashOrBankAccounts = $entries->filter(function ($entry, $key) {

                return $entry?->account?->group->sub_sub_group_number != 1 && $entry?->account?->group->sub_sub_group_number != 2 && $entry?->account?->group->sub_sub_group_number != 11;
            });

            $ledgerAccountGroup = $ledger?->account?->group;
            if (
                $ledgerAccountGroup &&
                $ledgerAccountGroup->sub_sub_group_number != 1 &&
                $ledgerAccountGroup->sub_sub_group_number != 2 &&
                $ledgerAccountGroup->sub_sub_group_number != 11
            ) {

                $entry = count($filteredCashOrBankAccounts) > 0 ? $filteredCashOrBankAccounts->first() : $entries->first();
            } else {

                $entry = count($filteredNotCashOrBankAccounts) > 0 ? $filteredNotCashOrBankAccounts->first() : $entries->first();
            }

            $transactionDetails = '';
            $assignedUser = $entry?->assignedUser ? (' - Sr. '.$entry?->assignedUser?->prefix.' '.$entry?->assignedUser?->name.' '.$entry?->assignedUser?->last_name) : '';
            $transactionDetails = '';

            if ($request->transaction_details == 1 && isset($entry)) {

                if (
                    $entry->payment_method_id || $entry->transaction_no || $entry->cheque_no || $entry->cheque_serial_no || $entry->cheque_issue_date || $entry->remarkable_note
                ) {

                    $transactionDetails .= $entry?->paymentMethod?->name;
                    $transactionDetails .= ' - TransNo: '.$entry->transaction_no;
                    $transactionDetails .= ' - ChequeNo: '.$entry->cheque_no;
                    $transactionDetails .= ' - SerialNo: '.$entry->cheque_serial_no;
                    $transactionDetails .= ' - IssueDate: '.$entry->cheque_issue_date;
                    $transactionDetails .= ' - R.Note : '.$entry->remarkable_note;
                }
            }

            $voucherDetails .= '<p>'.$__ledgerReferenceUser.'</p><p><strong><a href="'.route('accounting.accounts.ledger', [($entry?->account?->id ? $entry?->account?->id : 'null'), 'accountId']).'" target="_blank">'.$entry?->account?->name.'</a></strong>'.$assignedUser.'</p>'.($transactionDetails ? '<p class="p-0 m-0">'.$transactionDetails.'</p>' : '');
        }

        return $voucherDetails.$note;
    }

    public function incomeReceiptDetails($request, $ledger)
    {
        return 'As Per Details Of Income Receipt';
    }

    public function saleProductTax($request, $ledger)
    {
        $saleProduct = $ledger?->saleProduct;

        $showingAccount = $ledger->account_id == $saleProduct?->sale?->customer_account_id ? $saleProduct?->sale?->salesAccount?->name : $saleProduct?->sale?->customer?->name;
        $assignedUser = $ledger?->user ? (' - Sr. '.$ledger?->user?->prefix.' '.$ledger?->user?->name.' '.$ledger?->user?->last_name) : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$saleProduct?->sale?->sale_note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:11px !important;"><strong>('.__('menu.as_per_details').')'.':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($saleProduct?->sale?->total_sold_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.sale_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($saleProduct?->sale?->order_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.sale_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"> : '.\App\Utils\Converter::format_in_bdt($saleProduct?->sale?->order_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_invoice_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($saleProduct?->sale?->total_payable_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.payment_note').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.$saleProduct?->sale?->payment_note.'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($saleProduct?->sale?->saleProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($saleProduct->sale->saleProducts as $saleProduct) {

                if ($saleProduct->quantity > 0) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- '.$saleProduct?->product?->name.'</td>';

                    $baseUnitMultiplier = $saleProduct?->saleUnit?->base_unit_multiplier ? $saleProduct?->saleUnit?->base_unit_multiplier : 1;
                    $soldQty = $saleProduct->quantity / $baseUnitMultiplier;
                    $priceIncTax = $saleProduct->unit_price_inc_tax * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">'.\App\Utils\Converter::format_in_bdt($soldQty).'/'.$saleProduct->saleUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($saleProduct->unit_tax_percent).'%='.\App\Utils\Converter::format_in_bdt($saleProduct->unit_tax_amount * $baseUnitMultiplier).')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($soldQty).'X'.$priceIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">='.\App\Utils\Converter::format_in_bdt($saleProduct->subtotal).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            }
            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>'.$showingAccount.'</strong>'.$assignedUser.'</p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function purchaseProductTax($request, $ledger)
    {
        $purchaseProduct = $ledger?->purchaseProduct;

        $showingAccount = $ledger->account_id == $purchaseProduct?->purchase?->supplier_account_id ? $purchaseProduct?->purchase?->purchaseAccount?->name : $purchaseProduct?->purchase?->supplier?->name;
        $assignedUser = $ledger?->user ? (' - Sr. '.$ledger?->user?->prefix.' '.$ledger?->user?->name.' '.$ledger?->user?->last_name) : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$purchaseProduct?->purchase?->sale_note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>('.__('menu.as_per_details').')'.':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.purchase_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->order_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.purchase_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->purchase_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_invoice_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->total_purchase_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.payment_note').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.$purchaseProduct?->purchase?->payment_note.'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($purchaseProduct?->purchase?->purchaseProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($purchaseProduct->purchase->purchaseProducts as $purchaseProduct) {

                if ($purchaseProduct->quantity > 0) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- '.$purchaseProduct?->product?->name.'</td>';

                    $baseUnitMultiplier = $purchaseProduct?->purchaseUnit?->base_unit_multiplier ? $purchaseProduct?->purchaseUnit?->base_unit_multiplier : 1;
                    $purchasedQty = $purchaseProduct->quantity / $baseUnitMultiplier;
                    $unitCostIncTax = $purchaseProduct->net_unit_cost * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">'.\App\Utils\Converter::format_in_bdt($purchasedQty).'/'.$purchaseProduct?->purchaseUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($purchaseProduct->unit_tax_percent).'%='.\App\Utils\Converter::format_in_bdt($purchaseProduct->unit_tax_amount * $baseUnitMultiplier).')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($purchasedQty).'X'.$unitCostIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">='.\App\Utils\Converter::format_in_bdt($purchaseProduct->line_total).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            }
            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>'.$showingAccount.'</strong>'.$assignedUser.'</p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function saleReturnProductTax($request, $ledger)
    {
        $salesReturnProduct = $ledger?->salesReturnProduct;
        $showingAccount = $ledger->account_id == $salesReturnProduct?->saleReturn?->customer_account_id ? $salesReturnProduct?->salesReturn?->salesAccount?->name : $salesReturnProduct?->salesReturn?->customer?->name;

        $assignedUser = $ledger?->user ? (' - Sr. '.$ledger?->user?->prefix.' '.$ledger?->user?->name.' '.$ledger?->user?->last_name) : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$salesReturnProduct?->salesReturn?->return_note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>('.__('menu.as_per_details').')'.':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_qty').' ('.__('menu.as_base_unit').')'.'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.return_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->return_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.return_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->return_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_returned_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->total_return_amount).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($salesReturnProduct->salesReturn->returnProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($salesReturnProduct->salesReturn->returnProducts as $returnProduct) {

                if ($returnProduct->return_qty) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- '.$returnProduct?->product?->name.'</td>';

                    $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                    $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                    $unitPriceIncTax = $returnProduct->unit_price_inc_tax * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">'.\App\Utils\Converter::format_in_bdt($returnedQty).'/'.$returnProduct?->returnUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($returnProduct->unit_tax_percent).'%='.\App\Utils\Converter::format_in_bdt($returnProduct->unit_tax_amount * $baseUnitMultiplier).')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($returnedQty).'X'.$unitPriceIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">='.\App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>'.$showingAccount.'</strong>'.$assignedUser.'</p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function purchaseReturnProductTax($request, $ledger)
    {
        $purchaseReturnProduct = $ledger?->purchaseReturnProduct;
        $showingAccount = $ledger->account_id == $purchaseReturnProduct?->purchaseReturn?->supplier_account_id ? $purchaseReturnProduct?->purchaseReturn?->purchaseAccount?->name : $purchaseReturnProduct?->purchaseReturn?->supplier?->name;

        $assignedUser = $ledger?->user ? (' - Sr. '.$ledger?->user?->prefix.' '.$ledger?->user?->name.' '.$ledger?->user?->last_name) : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">'.$purchaseReturnProduct?->purchaseReturn?->note.'</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>('.__('menu.as_per_details').')'.':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_qty').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->total_qty).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.net_total_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->net_total_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.return_discount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->return_discount_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.return_tax').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->return_tax_amount).'</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>'.__('menu.total_returned_amount').'</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : '.\App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->total_return_amount).'</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($purchaseReturnProduct->purchaseReturn->returnProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($purchaseReturnProduct->purchaseReturn->returnProducts as $returnProduct) {

                if ($returnProduct->return_qty) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- '.$returnProduct?->product?->name.'</td>';

                    $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                    $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                    $unitCostIncTax = $returnProduct->unit_cost_inc_tax * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">'.\App\Utils\Converter::format_in_bdt($returnedQty).'/'.$returnProduct?->returnUnit?->code_name.'</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($returnProduct->unit_tax_percent).'%='.\App\Utils\Converter::format_in_bdt($returnProduct->unit_tax_amount * $baseUnitMultiplier).')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">('.\App\Utils\Converter::format_in_bdt($returnedQty).'X'.$unitCostIncTax.')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">='.\App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal).'</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>'.$showingAccount.'</strong>'.$assignedUser.'</p>'.$voucherDetails.$inventoryDetails.$note;
    }

    public function dailyStockDetails($request, $ledger)
    {
        return 'As Per Details Of Daily Stock For Tax';
    }
}
