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
        Schema::create('asset_requests', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->unsignedBigInteger('request_for_id')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();

            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('CASCADE');
            $table->foreign('request_for_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL');

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
        Schema::dropIfExists('asset_requests');
    }
};
