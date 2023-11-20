<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('expense_id')->nullable()->index();
            $table->unsignedBigInteger('purchase_id')->nullable()->index();
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('loan_company_id')->nullable()->index();
            $table->unsignedBigInteger('account_id')->nullable()->index();
            $table->unsignedBigInteger('loan_account_id')->nullable()->index();
            $table->tinyInteger('type')->nullable();
            $table->decimal('loan_amount', 22)->default(0);
            $table->decimal('due', 22)->default(0);
            $table->decimal('total_paid', 22)->default(0);
            $table->decimal('total_receive', 22)->default(0);
            $table->timestamp('report_date')->nullable();
            $table->text('loan_reason')->nullable();
            $table->string('loan_by', 191)->nullable();
            $table->unsignedBigInteger('created_user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
