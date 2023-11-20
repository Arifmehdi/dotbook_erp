<?php

use App\Models\PurchaseRequisition;

$purchaseRequisitions = PurchaseRequisition::all();

foreach ($purchaseRequisitions as $pr) {
    $str = $pr->requisition_no;
    $strInt = \preg_replace('/([a-zA-Z]+)/', '', $str);
    $newIntPart = \str_pad(intval($strInt), 4, '0', \STR_PAD_LEFT);
    $oldReady = \preg_replace('/([0-9]+)/', $newIntPart, $str);
    $toStore = preg_replace('/([a-zA-Z]+)/', '$1-'.date('ym-', strtotime($pr->created_at)), $oldReady);
    $pr->requisition_no = $toStore;

    echo $pr->requisition_no.PHP_EOL;
    $pr->save();
}
echo 'Finished!';
