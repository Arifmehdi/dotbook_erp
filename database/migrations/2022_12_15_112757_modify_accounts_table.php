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
        Schema::table('accounts', function (Blueprint $table) {

            $table->dropColumn('debit');
            $table->dropColumn('credit');
            $table->dropColumn('status');
            $table->string('phone', 50)->after('name')->nullable();
            $table->text('address')->after('phone')->nullable();
            $table->unsignedBigInteger('customer_id')->after('phone')->nullable();
            $table->unsignedBigInteger('supplier_id')->after('customer_id')->nullable();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            //
        });
    }
};
