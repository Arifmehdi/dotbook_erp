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
        Schema::connection('website')->create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191)->nullable();
            $table->string('name', 191)->nullable();
            $table->longText('designation')->nullable();
            $table->longText('description')->nullable();
            $table->integer('rating')->nullable();
            $table->longText('image')->nullable();
            $table->tinyInteger('status')->default('0');
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
        Schema::connection('website')->dropIfExists('testimonials');
    }
};
