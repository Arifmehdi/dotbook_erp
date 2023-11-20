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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('weight_clients');

        Schema::create('weight_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name');
            $table->string('phone')->unique()->nullable();
            $table->string('email')->nullable();
            $table->string('client_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('tax_no')->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weight_clients');
    }
};
