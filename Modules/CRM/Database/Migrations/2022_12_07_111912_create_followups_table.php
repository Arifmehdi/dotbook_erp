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
        Schema::connection('crm')->create('followups', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('individual_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('leads_individual_or_business')->nullable();
            $table->string('customers_or_leads')->nullable();
            $table->string('status')->nullable();
            $table->date('date')->nullable();
            $table->text('followup_type')->nullable();
            $table->text('followup_category')->nullable();
            $table->text('assign_to')->nullable();
            $table->string('file')->nullable();
            $table->boolean('send_notification')->default(false);
            $table->text('description')->nullable();
            $table->foreign('individual_id')->references('id')->on('individual_leads')->onDelete('SET NULL');
            $table->foreign('business_id')->references('id')->on('business_leads')->onDelete('SET NULL');
            $table->foreign('customer_id')->references('id')->on(config('database.connections.mysql.database').'.customers')->onDelete('SET NULL');
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
        Schema::connection('crm')->dropIfExists('followups');
    }
};
