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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('weight_details');

        Schema::create('weight_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weight_scale_id')->nullable()->references('id')->on('weights')->onDelete('cascade');
            $table->tinyInteger('weight_type')->comment('1=GrossWeight;2=TareWeight');
            $table->decimal('weight', 22, 2)->nullable();
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->onDelete('SET NULL');
            $table->decimal('quantity', 22, 2)->default(0);
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
        Schema::dropIfExists('weight_details');
    }
};
