<?php

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\AccountLedger;
use App\Models\DayBook;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;

Artisan::command('my-test', function () {

    try {

        DB::beginTransaction();

        // $accountLedger = AccountLedger::with(['account', 'account.group'])->where('voucher_type', 9)->get();
        // foreach ($accountLedger as $entry) {

        //     if ($entry->account->group->sub_sub_group_number != 1 && $entry->account->group->sub_sub_group_number != 2) {

        //         $entry->is_cash_flow = 1;
        //         $entry->save();
        //     }
        // }

        // $accountLedger = AccountLedger::with(['account', 'account.group'])->get();
        // foreach ($accountLedger as $entry) {

        //     if (
        //         $entry->account->group->sub_sub_group_number == 1 ||
        //         $entry->account->group->sub_sub_group_number == 2 ||
        //         $entry->account->group->sub_sub_group_number == 11
        //     ) {
        //         $entry->is_cash_flow = 0;
        //         $entry->save();
        //     }
        // }

        // $accountLedger = AccountLedger::with(['paymentDescription', 'paymentDescription.payment'])->where('voucher_type', 8)->get();
        // foreach ($accountLedger as $entry) {

        //     $isDeleted = 0;
        //     if (! $entry?->paymentDescription) {

        //         $entry->delete();
        //         $isDeleted = 1;
        //     }

        //     if ($isDeleted == 0) {

        //         if (! $entry?->paymentDescription?->payment) {

        //             $entry->delete();
        //         }
        //     }
        // }

        echo 'done account ledgers table.'.PHP_EOL;

        $sales = Sale::with(['do'])->where('status', 1)->get();
        foreach ($sales as $sale) {

            if ($sale->do) {

                $sale->shipping_address = $sale->do->shipping_address;
                $sale->receiver_phone = $sale->do->receiver_phone;
                $sale->save();
            }
        }

        echo 'All Done'.PHP_EOL;

        DB::commit();
    } catch (Exception $e) {

        dd($e->getMessage());
        DB::rollBack();
    }

    echo 'All Done'.PHP_EOL;
});

Artisan::command('see-diff', function () {

    // L;ist all table names from the 'hrm' connection
    $hrmTables = \DB::connection('hrm')->getDoctrineSchemaManager()->listTableNames();

    // List all table names from the 'crm' connection
    $crmTables = \DB::connection('crm')->getDoctrineSchemaManager()->listTableNames();

    // Find the tables that are uncommon to both connections
    $uncommonTables = array_diff(array_merge($hrmTables, $crmTables), array_intersect($hrmTables, $crmTables));

    // Output the uncommon tables
    foreach ($uncommonTables as $table) {
        echo $table.PHP_EOL;
    }

    echo "\n\n";
    echo count($hrmTables).PHP_EOL;

    echo count($crmTables).PHP_EOL;
});

Artisan::command('play', function () {

    Schema::connection('hrm')->table('designations', function (Blueprint $table) {

        $table->unsignedBigInteger('parent_designation_id')->nullable()->after('id');
    });
});
