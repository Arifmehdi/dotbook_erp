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
        Schema::create('communication_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('mail_status')->nullable();
            $table->string('sms_status')->nullable();
            $table->string('whatsapp_status')->nullable();
            $table->integer('create_by')->nullable();
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
        Schema::dropIfExists('communication_statuses');
    }
};
