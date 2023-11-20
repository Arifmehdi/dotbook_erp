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
        Schema::connection('website')->create('job_applies', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('mobile');
            $table->string('email');
            $table->text('resume')->nullable();
            $table->text('cover_letter')->nullable();
            $table->text('website_url')->nullable();
            $table->text('linkedin_url')->nullable();
            $table->text('skill')->nullable();
            $table->text('sourch')->nullable();
            $table->text('location')->nullable();
            $table->text('education')->nullable();
            $table->text('experience')->nullable();
            $table->integer('country')->nullable();
            $table->integer('city')->nullable();
            $table->text('photo')->nullable();
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
        Schema::connection('website')->dropIfExists('job_applies');
    }
};
