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
        Schema::create('purchase_by_scales', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('challan_date')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->decimal('first_weight', 22, 2)->default(0);
            $table->decimal('last_weight', 22, 2)->default(0);
            $table->decimal('net_weight', 22, 2)->default(0);
            $table->timestamp('date_ts')->nullable();
            $table->string('date')->nullable();
            $table->boolean('status')->default(0);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->boolean('is_done')->default(0);
            $table->timestamps();
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onDelete('cascade');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_by_scales');
    }
};
