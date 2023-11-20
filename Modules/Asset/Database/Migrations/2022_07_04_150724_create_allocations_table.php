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
        Schema::create('asset_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('allocated_to')->nullable();
            $table->unsignedBigInteger('asset_id');
            $table->decimal('quantity');
            $table->decimal('revoked_quantity')->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();

            $table->foreign('allocated_to')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('updated_by_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        echo 'Dropping...';
        Schema::dropIfExists('asset_allocations');
    }
};
