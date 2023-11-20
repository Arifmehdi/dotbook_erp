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
        Schema::connection('crm')->create('appointments', function (Blueprint $table) {
            $table->id();
            $table->date('schedule_date')->nullable();
            $table->time('schedule_time')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('appointor_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on(config('database.connections.mysql.database').'.customers')->onDelete('cascade');
            $table->foreign('appointor_id')->references('id')->on(config('database.connections.mysql.database').'.users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('crm')->dropIfExists('appointments');
    }
};
