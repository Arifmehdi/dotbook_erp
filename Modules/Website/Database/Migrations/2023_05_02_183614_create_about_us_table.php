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
        Schema::connection('website')->create('about_us', function (Blueprint $table) {
            $table->id();
            $table->longText('about')->nullable();
            $table->longText('mission')->nullable();
            $table->longText('vission')->nullable();
            $table->longText('quality')->nullable();
            $table->longText('ideas')->nullable();
            $table->string('image', 191)->nullable();
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
        Schema::connection('website')->dropIfExists('about_us');
    }
};
