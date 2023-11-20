<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('stock_event_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('warehouse_id')->nullable()->index();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->decimal('total_item', 22)->default(0);
            $table->decimal('total_qty', 22)->default(0);
            $table->decimal('net_total_value', 22)->default(0);
            $table->string('date')->nullable();
            $table->string('time', 20)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('stock_issues');
    }
}
