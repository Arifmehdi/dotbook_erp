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
        Schema::connection('crm')->create('proposal_templates', function (Blueprint $table) {
            $table->id();
            $table->longText('cc')->nullable();
            $table->longText('bcc')->nullable();
            $table->string('proposal_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('related')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->string('date')->nullable();
            $table->string('open_till')->nullable();
            $table->string('currency')->nullable();
            $table->string('discount_type')->nullable();
            $table->string('tags')->nullable();
            $table->string('to')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('comp_des_header')->nullable();
            $table->text('comp_des_footer')->nullable();
            $table->float('discount')->nullable();
            $table->float('sub_total')->nullable();
            $table->integer('total_item')->nullable();
            $table->integer('total_qty')->nullable();
            $table->float('total')->nullable();
            $table->float('taxes')->nullable();
            $table->text('body')->nullable();
            $table->longText('attachments')->nullable();
            $table->foreign('customer_id')->references('id')->on(config('database.connections.mysql.database').'.customers')->onDelete('SET NULL');
            $table->foreign('supplier_id')->references('id')->on(config('database.connections.mysql.database').'.suppliers')->onDelete('SET NULL');
            $table->foreign('lead_id')->references('id')->on('individual_leads')->onDelete('SET NULL');
            $table->boolean('status')->default(true);
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
        Schema::connection('crm')->dropIfExists('proposal_templates');
    }
};
