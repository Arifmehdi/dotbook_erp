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
        Schema::connection('website')->create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('job_type');
            $table->integer('job_category_id');
            $table->string('vacancy', 20);
            $table->string('designation')->nullable();
            $table->text('responsibility')->nullable();
            $table->text('facilities')->nullable();
            $table->longText('description')->nullable();
            $table->longText('education_req')->nullable();
            $table->longText('skill')->nullable();
            $table->longText('experience')->nullable();
            $table->string('salary')->nullable();
            $table->string('salary_type', 100)->nullable();
            $table->text('location')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->date('deadline')->nullable();
            $table->string('city')->nullable();
            $table->string('image')->nullable();
            $table->string('slug')->nullable();
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
        Schema::connection('website')->dropIfExists('jobs');
    }
};
