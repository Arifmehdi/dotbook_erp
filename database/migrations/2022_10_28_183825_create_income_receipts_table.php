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
        if (! Schema::hasTable('income_receipts')) {
            Schema::create('income_receipts', function (Blueprint $table) {
                $table->id();
                $table->string('voucher_no', 191)->nullable()->index();
                $table->unsignedBigInteger('income_id')->nullable()->index();
                $table->unsignedBigInteger('payment_method_id')->nullable()->index();
                $table->unsignedBigInteger('account_id')->nullable()->index();
                $table->decimal('amount', 22, 2)->default(0);
                $table->text('note')->nullable();
                $table->string('date')->nullable()->index();
                $table->timestamp('report_date')->nullable();
                $table->unsignedBigInteger('created_by_id')->nullable()->index();
                $table->timestamps();

                $table->foreign(['income_id'])->references(['id'])->on('incomes')->onDelete('cascade');
                $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onDelete('cascade');
                $table->foreign(['account_id'])->references(['id'])->on('accounts')->onDelete('cascade');
                $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('cascade');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_receipts');
    }
};
