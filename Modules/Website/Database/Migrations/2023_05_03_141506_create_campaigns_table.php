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
        Schema::connection('website')->create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->longText('thumbnail')->nullable();
            $table->longText('description')->nullable();
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
        Schema::connection('website')->dropIfExists('campaigns');
    }
};