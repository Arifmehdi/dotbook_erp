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
        Schema::connection('website')->create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title', 191)->nullable();
            $table->string('meta_tag', 191)->nullable();
            $table->string('meta_description', 191)->nullable();
            $table->string('meta_author', 191)->nullable();
            $table->string('google_analytics', 191)->nullable();
            $table->string('google_verification', 191)->nullable();
            $table->string('bing_verification', 191)->nullable();
            $table->string('alexa_analytics', 191)->nullable();
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
        Schema::connection('website')->dropIfExists('seo_settings');
    }
};
