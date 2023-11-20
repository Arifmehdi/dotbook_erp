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
        Schema::create('sms_servers', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name')->nullable();
            $table->string('url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('sender_number')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('sms_servers');
    }
};
