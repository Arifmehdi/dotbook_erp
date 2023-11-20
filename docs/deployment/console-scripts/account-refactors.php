<?php

use App\Models\PurchaseProduct;
use App\Models\PurchaseRequisitionProduct;
use App\Models\ReceiveStock;
use App\Models\ReceiveStockProduct;
use App\Models\SaleProduct;
use App\Models\StockIssueProduct;
use Database\Seeders\RolePermissionSeeder;

Artisan::command('test', function () {
    // $permissions = (new RolePermissionSeeder)->getPermissionsArray();
    // $ids = array_column($permissions, 'id');
    // $all = range($ids[0], $ids[count($ids) - 1]);
    // $diff = array_diff($all, $ids);
    // foreach ($diff as $key => $value) {
    //     echo "$value id is available\n";
    // }

    // $saleProducts = SaleProduct::all();
    // foreach ($saleProducts as $saleProduct) {

    //     $unit = DB::table('units')->where('name', $saleProduct->unit)->select('id')->first();
    //     if ($unit) {
    //         $saleProduct->unit_id = $unit->id;
    //         $saleProduct->save();
    //     }
    // }

    // $purchaseProducts = PurchaseProduct::all();
    // foreach ($purchaseProducts as $purchaseProduct) {

    //     $unit = DB::table('units')->where('name', $purchaseProduct->unit)->select('id')->first();
    //     if ($unit) {
    //         $purchaseProduct->unit_id = $unit->id;
    //         $purchaseProduct->save();
    //     }
    // }

    // $stockIssueProducts = StockIssueProduct::with(['stockIssue'])->get();
    // foreach ($stockIssueProducts as $stockIssueProduct) {

    //     $stockIssueProduct->warehouse_id = $stockIssueProduct?->stockIssue?->warehouse_id;

    //     $unit = DB::table('units')->where('name', $stockIssueProduct->unit)->select('id')->first();
    //     if ($unit) {

    //         $stockIssueProduct->unit_id = $unit->id;
    //     }

    //     $stockIssueProduct->save();
    // }

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

    // $receiveStockProducts = ReceiveStockProduct::all();
    // foreach ($receiveStockProducts as $receiveStockProduct) {

    //     $unit = DB::table('units')->where('name', $receiveStockProduct->unit)->select('id')->first();
    //     if ($unit) {
    //         $receiveStockProduct->unit_id = $unit->id;
    //         $receiveStockProduct->save();
    //     }
    // }

});
