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
        Schema::create('incomes', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('voucher_no')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('attachment')->nullable();
            $table->mediumText('note')->nullable();
            $table->mediumText('category_ids')->nullable();
            $table->decimal('tax', 22)->default(0);
            $table->decimal('tax_amount', 22)->default(0);
            $table->decimal('total_amount', 22)->default(0);
            $table->decimal('net_total_amount', 22)->default(0);
            $table->decimal('paid', 22)->default(0);
            $table->decimal('total_due')->default(0);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->string('payment_note')->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('cascade');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
};
