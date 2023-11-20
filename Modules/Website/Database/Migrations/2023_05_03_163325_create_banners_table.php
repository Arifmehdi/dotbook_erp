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
        Schema::connection('website')->create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('banner1')->nullable();
            $table->text('banner2')->nullable();
            $table->text('banner3')->nullable();
            $table->text('banner4')->nullable();
            $table->text('banner5')->nullable();
            $table->text('banner6')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('website')->dropIfExists('banners');
    }
};
