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
        Schema::connection('crm')->create('crm_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('rate')->nullable();
            $table->unsignedBigInteger('assignees_user_id')->nullable();
            $table->unsignedBigInteger('followers_user_id')->nullable();
            $table->date('s_date')->nullable();
            $table->date('e_date')->nullable();
            $table->text('subscription_name')->nullable();
            $table->string('priority')->nullable();
            $table->string('repeat_every')->nullable();
            $table->string('related')->nullable();
            $table->text('file')->nullable();
            $table->foreign('assignees_user_id')->references('id')->on(config('database.connections.mysql.database').'.users')->onDelete('cascade');
            $table->foreign('followers_user_id')->references('id')->on(config('database.connections.mysql.database').'.users')->onDelete('cascade');
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
        Schema::connection('crm')->dropIfExists('crm_tasks');
    }
};
