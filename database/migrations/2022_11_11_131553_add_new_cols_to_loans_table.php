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
        Schema::table('loans', function (Blueprint $table) {

            $table->string('voucher_no', 191)->after('id')->nullable();
            $table->decimal('interest_percent', 22, 2)->after('loan_amount')->default(0);
            $table->decimal('interest_amount', 22, 2)->after('interest_percent')->default(0);
            $table->decimal('additional_interest_amount', 22, 2)->after('interest_amount')->default(0);
            $table->bigInteger('installment_period')->after('additional_interest_amount')->default(1);
            $table->decimal('per_installment', 22, 2)->after('installment_period')->default(0);
            $table->decimal('total_payable', 22, 2)->after('per_installment')->default(0);
            $table->decimal('total_receivable', 22, 2)->after('total_payable')->default(0);
            $table->string('date')->after('total_receivable')->nullable();
            $table->renameColumn('total_receive', 'total_received');
            $table->unsignedBigInteger('created_by_id')->after('remarks')->nullable();

            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign('loans_created_by_id_foreign');

            $table->dropColumn('created_by_id');
            $table->dropColumn('voucher_no');
            $table->dropColumn('interest_percent');
            $table->dropColumn('interest_amount');
            $table->dropColumn('additional_interest_amount');
            $table->dropColumn('installment_period');
            $table->dropColumn('per_installment');
            $table->dropColumn('total_payable');
            $table->dropColumn('total_receivable');
            $table->dropColumn('date');
            $table->renameColumn('total_received', 'total_receive');
        });
    }
};
