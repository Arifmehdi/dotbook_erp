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
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->dropForeign(['tax_id']);
            $table->dropColumn('tax_id');
            $table->decimal('unit_tax', 22, 2)->change()->default(0);
            $table->renameColumn('unit_tax', 'unit_tax_amount');
            $table->unsignedBigInteger('tax_ac_id')->after('subtotal')->nullable();
            $table->foreign('tax_ac_id')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->unsignedBigInteger('tax_id')->after('subtotal')->nullable();
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('set null');
            $table->renameColumn('unit_tax_amount', 'unit_tax');
            $table->decimal('unit_tax', 8, 2)->change()->default(0);
            $table->dropForeign(['tax_ac_id']);
            $table->dropColumn('tax_ac_id');
        });
    }
};
