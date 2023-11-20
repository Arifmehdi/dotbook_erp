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
        Schema::connection('crm')->create('proposal_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_template_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('name')->nullable();
            $table->string('details')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('rate')->nullable();
            $table->integer('tax')->nullable();
            $table->integer('tax_type')->nullable();
            $table->float('discount')->nullable();
            $table->string('discount_type')->nullable();
            $table->string('amount')->nullable();
            $table->foreign('proposal_template_id')->references('id')->on('proposal_templates')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on(config('database.connections.mysql.database').'.products')->onDelete('SET NULL');
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
        Schema::connection('crm')->dropIfExists('proposal_details');
    }
};
