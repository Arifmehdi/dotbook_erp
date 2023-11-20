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
        Schema::connection('hrm')->create('grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->integer('basic')->nullable();
            $table->integer('house_rent')->nullable();
            $table->integer('medical')->nullable();
            $table->integer('food')->nullable();
            $table->integer('transport')->nullable();
            $table->integer('other')->nullable();
            $table->softDeletes();
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
        Schema::connection('hrm')->dropIfExists('grades');
    }
};
