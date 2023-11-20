<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->tinyInteger('ati_deduction_type')->after('purchase_tax_amount')->default(2);
            $table->decimal('ati_deduction', 22, 2)->after('ati_deduction_type')->default(0);
            $table->decimal('ati_deduction_amount', 22, 2)->after('ati_deduction')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
