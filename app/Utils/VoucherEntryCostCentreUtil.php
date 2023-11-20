<?php

namespace App\Utils;

use App\Models\VoucherEntryCostCentre;

class VoucherEntryCostCentreUtil
{
    public function addVoucherEntryCostCentres($entryId, $voucherType, $request, $rowIndexNo)
    {
        $arr = [
            'journal' => 'journal_entry_id',
            'payment' => 'payment_description_id',
            'expense' => 'expense_description_id',
        ];

        $index = 0;
        foreach ($request->cost_centre_ids[$rowIndexNo] as $cost_centre_id) {

            $addVoucherEntryCostCentre = new VoucherEntryCostCentre();
            $addVoucherEntryCostCentre->{$arr[$voucherType]} = $entryId;
            $addVoucherEntryCostCentre->cost_centre_id = $cost_centre_id;
            $addVoucherEntryCostCentre->amount = $request->cost_centre_amounts[$rowIndexNo][$index];
            $addVoucherEntryCostCentre->save();
            $index++;
        }
    }

    public function updateVoucherEntryCostCentres($entryId, $voucherType, $request, $index)
    {
        $arr = [
            'journal' => 'journal_entry_id',
            'payment' => 'payment_description_id',
            'expense' => 'expense_description_id',
        ];

        VoucherEntryCostCentre::where($arr[$voucherType], $entryId)->delete();

        if ($request->maintain_cost_centre == 1) {

            $rowIndexNo = $request->indexes[$index];
            if (isset($request->cost_centre_ids[$rowIndexNo])) {

                $index = 0;
                foreach ($request->cost_centre_ids[$rowIndexNo] as $cost_centre_id) {

                    $addVoucherEntryCostCentre = new VoucherEntryCostCentre();
                    $addVoucherEntryCostCentre->{$arr[$voucherType]} = $entryId;
                    $addVoucherEntryCostCentre->cost_centre_id = $cost_centre_id;
                    $addVoucherEntryCostCentre->amount = $request->cost_centre_amounts[$rowIndexNo][$index];
                    $addVoucherEntryCostCentre->save();
                    $index++;
                }
            }
        }
    }
}
