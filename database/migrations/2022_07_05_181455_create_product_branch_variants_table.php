<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBranchVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_branch_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_branch_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedBigInteger('product_variant_id')->nullable()->index();
            $table->decimal('variant_quantity', 22)->nullable()->default(0);
            $table->decimal('total_sale', 22)->default(0);
            $table->decimal('total_purchased', 22)->default(0);
            $table->decimal('total_adjusted', 22)->default(0);
            $table->decimal('total_transferred', 22)->default(0);
            $table->decimal('total_received', 22)->default(0);
            $table->decimal('total_opening_stock', 22)->default(0);
            $table->decimal('total_sale_return', 22)->default(0);
            $table->decimal('total_purchase_return', 22)->default(0);
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
        Schema::dropIfExists('product_branch_variants');
    }
}
