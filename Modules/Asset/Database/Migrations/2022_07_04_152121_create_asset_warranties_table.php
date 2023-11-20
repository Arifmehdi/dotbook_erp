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
        Schema::create('asset_warranties', function (Blueprint $table) {
            $table->id();
            $table->date('start_date')->nullable();
            $table->decimal('warranty_month')->nullable();
            $table->decimal('additional_cost')->nullable();
            $table->string('additional_description')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->timestamps();
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_warranties');
    }
};
