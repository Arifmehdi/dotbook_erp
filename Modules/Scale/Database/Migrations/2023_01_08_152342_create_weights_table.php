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
        Schema::dropIfExists('weights');

        Schema::create('weights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('weight_id')->nullable();
            $table->tinyInteger('weight_type')->comment('1=GrossWeight;2=TareWeight');
            $table->decimal('quantity', 22, 2)->default(0);
            $table->integer('is_done')->default(0);
            $table->integer('status')->default(0);
            $table->foreignId('created_by_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->foreignId('client_id')->nullable()->references('id')->on('weight_clients')->onDelete('SET NULL');
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->onDelete('SET NULL');
            $table->string('vehicle_number')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('challan_date')->nullable();
            $table->string('date')->nullable();
            $table->decimal('gross_weight', 22, 2)->default(0);
            $table->decimal('tare_weight', 22, 2)->default(0);
            $table->decimal('net_weight', 22, 2)->default(0);
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weights');
    }
};
