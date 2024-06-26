<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->bigInteger('priority')->default(0);
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->tinyInteger('discount_type')->default(0);
            $table->decimal('discount_amount', 22)->default(0);
            $table->unsignedBigInteger('price_group_id')->nullable()->index();
            $table->boolean('apply_in_customer_group')->default(false);
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('discounts');
    }
}
