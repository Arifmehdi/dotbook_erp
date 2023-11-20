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
        Schema::connection('crm')->create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('billing_plan')->nullable();
            $table->integer('quantity')->nullable();
            $table->date('date')->nullable();
            $table->text('subscription_name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('currency')->nullable();
            $table->string('tax')->nullable();
            $table->text('terms')->nullable();
            $table->foreign('customer_id')->references('id')->on(config('database.connections.mysql.database').'.customers')->onDelete('cascade');
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
        Schema::connection('crm')->dropIfExists('subscriptions');
    }
};
