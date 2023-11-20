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
        Schema::create('asset_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->integer('seats')->nullable();
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->string('licensed_to_name')->nullable();
            $table->string('licensed_to_email')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('order_number')->nullable();
            $table->string('purchase_order_number')->nullable();
            $table->text('product_key')->nullable();
            $table->integer('purchase_cost')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->unsignedBigInteger('depreciation_id')->nullable();
            $table->boolean('re_assignable')->default(false);
            $table->boolean('maintained')->default(false);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('CASCADE');
            $table->foreign('category_id')->references('id')->on('licenses_categories')->onDelete('SET NULL');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_licenses');
    }
};
