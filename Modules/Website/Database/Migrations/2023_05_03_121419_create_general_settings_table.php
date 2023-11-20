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
        Schema::connection('website')->create('website_general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name', 100)->nullable();
            $table->string('app_url', 50)->nullable();
            $table->longText('description')->nullable();
            $table->string('backend_logo', 191)->nullable();
            $table->string('frontend_header_logo', 191)->nullable();
            $table->string('favicon')->nullable();
            $table->string('frontend_footer_logo', 191)->nullable();
            $table->longText('address1')->nullable();
            $table->longText('address2')->nullable();
            $table->longText('phone')->nullable();
            $table->string('email', 191)->nullable();
            $table->string('fax', 191)->nullable();
            $table->string('website', 191)->nullable();
            $table->longText('map')->nullable();
            $table->longText('office_time')->nullable();
            $table->longText('office_days')->nullable();
            $table->longText('call_hour')->nullable();
            $table->longText('get_in_touch')->nullable();
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
        Schema::connection('website')->dropIfExists('website_general_settings');
    }
};
