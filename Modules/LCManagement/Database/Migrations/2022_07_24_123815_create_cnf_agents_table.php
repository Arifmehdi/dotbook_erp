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
        Schema::create('cnf_agents', function (Blueprint $table) {
            $table->id();
            $table->string('agent_id')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('alternative_phone', 191)->nullable();
            $table->string('landline')->nullable();
            $table->string('email')->nullable();
            $table->string('tax_number')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->decimal('opening_balance', 22)->default(0);
            $table->decimal('total_service', 22)->default(0);
            $table->decimal('total_paid', 22)->default(0);
            $table->decimal('closing_balance', 22)->default(0);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->timestamps();

            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('updated_by_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cnf_agents');
    }
};
