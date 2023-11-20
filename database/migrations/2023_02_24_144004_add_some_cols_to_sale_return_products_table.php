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
        Schema::table('sale_return_products', function (Blueprint $table) {
            $table->string('price_type', 10)->after('unit_price_inc_tax')->default(0);
            $table->decimal('pr_amount', 22, 2)->after('price_type')->default(0);
            $table->unsignedBigInteger('tax_ac_id')->after('unit_discount_amount')->nullable();

            $table->foreign('tax_ac_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_return_products', function (Blueprint $table) {
            $table->dropForeign(['tax_ac_id']);
            $table->dropColumn('tax_ac_id');
            $table->dropColumn('price_type');
            $table->dropColumn('pr_amount');
        });
    }
};
