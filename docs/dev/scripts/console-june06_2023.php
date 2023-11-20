<?php

use App\Models\Expanse;
use App\Models\Journal;
use App\Models\Payment;
use App\Models\PurchaseRequisitionProduct;
use App\Models\ReceiveStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Website\Entities\JobApply;

Artisan::command('test', function () {

    $requisitionProducts = PurchaseRequisitionProduct::all();
    foreach ($requisitionProducts as $requisitionProduct) {

        $unit = DB::table('units')->where('name', $requisitionProduct->unit)->select('id')->first();

        if ($unit) {

            $requisitionProduct->unit_id = $unit->id;
        }

        $requisitionProduct->save();
    }

    $receiveStocks = ReceiveStock::all();
    foreach ($receiveStocks as $receiveStock) {

        $account = DB::table('accounts')->where('supplier_id', $receiveStock->supplier_id)->first();

        if ($account) {

            if (! $receiveStock->supplier_account_id) {

                $receiveStock->supplier_account_id = $account->id;
                $receiveStock->save();
            }
        }
    }

    $journals = Journal::with('entries', 'entries.account', 'entries.account.group', 'entries.ledger')->get();
    foreach ($journals as $journal) {

        $cashAccountId = '';
        foreach ($journal->entries as $entry) {

            if ($entry?->account?->group?->sub_sub_group_number == 1 || $entry?->account?->group?->sub_sub_group_number == 2 || $entry?->account?->group?->sub_sub_group_number == 11) {

                $cashAccountId = $entry->account->id;
            }
        }

        if ($cashAccountId) {

            foreach ($journal->entries as $entry) {

                if ($entry?->account?->group?->sub_sub_group_number != 1 && $entry?->account?->group?->sub_sub_group_number != 2 && $entry?->account?->group?->sub_sub_group_number != 11) {

                    $entry->ledger->is_cash_flow = 1;
                    $entry->ledger->save();
                }
            }
        }
    }

    $expenses = Expanse::with('expenseDescriptions', 'expenseDescriptions.account', 'expenseDescriptions.account.group', 'expenseDescriptions.ledger')->get();
    foreach ($expenses as $expense) {

        $cashAccountId = '';
        foreach ($expense->expenseDescriptions as $description) {

            if ($description->account) {

                if ($description?->account?->group?->sub_sub_group_number == 1 || $description?->account?->group?->sub_sub_group_number == 2 || $description?->account?->group?->sub_sub_group_number == 11) {

                    $cashAccountId = $description->account->id;
                }
            }
        }

        if ($cashAccountId) {

            foreach ($expense->expenseDescriptions as $description) {

                if ($description->account) {

                    if ($description?->account?->group?->sub_sub_group_number != 1 && $description?->account?->group?->sub_sub_group_number != 2 && $description?->account?->group?->sub_sub_group_number != 11) {

                        $description->ledger->is_cash_flow = 1;
                        $description->ledger->save();
                    }
                }
            }
        }
    }

    $payments = Payment::with('descriptions', 'descriptions.account', 'descriptions.account.group', 'descriptions.ledger')->get();
    foreach ($payments as $payment) {

        $cashAccountId = '';
        foreach ($payment->descriptions as $description) {

            if ($description?->account?->group?->sub_sub_group_number == 1 || $description?->account?->group?->sub_sub_group_number == 2 || $description?->account?->group?->sub_sub_group_number == 11) {

                $cashAccountId = $description->account->id;
            }

        }

        if ($cashAccountId) {

            foreach ($payment->descriptions as $description) {

                if ($description?->account?->group?->sub_sub_group_number != 1 && $description?->account?->group?->sub_sub_group_number != 2 && $description?->account?->group?->sub_sub_group_number != 11) {

                    $description->ledger->is_cash_flow = 1;
                    $description->ledger->save();
                }
            }
        }
    }
});

/**
 * Dev route file routes
 */
Route::get('test', function () {

    // $mappedArray = [
    //     'main_group_id' => 1,
    //     'main_group_name' => 'Capital A/c',
    //     'opening_total_debit' => 0,
    //     'opening_total_credit' => 0,
    //     'curr_total_debit' => 0,
    //     'curr_total_credit' => 0,
    //     'groups' => [
    //         [
    //             'group_id' => 1,
    //             'group_name' => 'B',
    //             'sub_group_number' => 1,
    //             'sub_sub_group_number' => 12,
    //             'parent_group_name' => 'A',
    //             'opening_total_debit' => 0,
    //             'opening_total_credit' => 0,
    //             'curr_total_debit' => 0,
    //             'curr_total_credit' => 0,
    //         ],
    //         [
    //             'group_id' => 2,
    //             'group_name' => 'D',
    //             'sub_group_number' => 2,
    //             'sub_sub_group_number' => 13,
    //             'parent_group_name' => 'C',
    //             'opening_total_debit' => 0,
    //             'opening_total_credit' => 0,
    //             'curr_total_debit' => 0,
    //             'curr_total_credit' => 0,
    //         ],
    //         [
    //             'group_id' => 2,
    //             'group_name' => 'D',
    //             'sub_group_number' => 2,
    //             'sub_sub_group_number' => 13,
    //             'parent_group_name' => 'C',
    //             'opening_total_debit' => 0,
    //             'opening_total_credit' => 0,
    //             'curr_total_debit' => 0,
    //             'curr_total_credit' => 0,
    //         ],
    //         [
    //             'group_id' => 2,
    //             'group_name' => 'D',
    //             'sub_group_number' => 3,
    //             'sub_sub_group_number' => 14,
    //             'parent_group_name' => 'C',
    //             'opening_total_debit' => 0,
    //             'opening_total_credit' => 0,
    //             'curr_total_debit' => 0,
    //             'curr_total_credit' => 0,
    //         ]
    //     ],
    //     'accounts' => []
    // ];

    // $array = $mappedArray['groups'];
    // $arrIndex = '';
    // foreach($array as $key => $arr) {
    //     if($arr['sub_group_number'] == 3 && $arr['sub_sub_group_number'] == 14) {
    //         $arrIndex = $key;
    //         break;
    //     }
    // }

    // return $arrIndex;

    return $expenses = Expanse::with('expenseDescriptions', 'expenseDescriptions.account', 'expenseDescriptions.account.group', 'expenseDescriptions.ledger')->get();
});

Route::get('file-test', function () {
    $path = JobApply::whereNotNull('resume')->first()->resume;

    return Storage::disk('website')->get($path);
    // $img = asset("website/$path");
    // return "<img src=\"$img\" alt=\"\" />";
});
