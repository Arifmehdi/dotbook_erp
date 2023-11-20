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
        Schema::connection('crm')->create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('proposal_id')->nullable();
            $table->unsignedBigInteger('proposal_template_id')->nullable();
            $table->foreign('proposal_template_id')->references('id')->on('proposal_templates')->onDelete('cascade');
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
        Schema::connection('crm')->dropIfExists('proposals');
    }
};
