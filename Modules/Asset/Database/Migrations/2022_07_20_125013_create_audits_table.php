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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('auditor_id')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->integer('status')->nullable();
            $table->date('audit_date')->nullable();
            $table->text('reason')->nullable();

            $table->foreign('auditor_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('CASCADE');
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
        Schema::dropIfExists('audits');
    }
};
