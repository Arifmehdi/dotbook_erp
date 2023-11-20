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
        Schema::connection('website')->create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('city_name');
            $table->string('status', 191)->nullable();
            $table->unsignedBigInteger('country_id')->nullable()->index('country_id_foreign');
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
        Schema::connection('website')->dropIfExists('cities');
    }
};
