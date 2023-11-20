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
        Schema::table('contras', function (Blueprint $table) {
            $table->decimal('debit_total', 22, 2)->after('amount')->default(0);
            $table->decimal('credit_total', 22, 2)->after('debit_total')->default(0);
            $table->tinyInteger('mode')->after('voucher_no')->default(1)->comment('1=singleMode,2=multipleMode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contras', function (Blueprint $table) {
            $table->dropColumn('debit_total');
            $table->dropColumn('credit_total');
            $table->dropColumn('mode');
        });
    }
};
