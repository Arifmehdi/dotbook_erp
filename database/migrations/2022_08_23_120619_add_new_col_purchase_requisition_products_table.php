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
        Schema::table('purchase_requisition_products', function (Blueprint $table) {

            $table->decimal('purchase_qty', 22, 2)->after('quantity')->default(0);
            $table->decimal('left_qty', 22, 2)->after('purchase_qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_requisition_products', function (Blueprint $table) {
            //
        });
    }
};
