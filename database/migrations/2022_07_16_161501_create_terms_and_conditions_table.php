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
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->integer('terms_for')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->timestamps();
            $table->foreign(['category_id'])->references(['id'])->on('terms_condition_categories')->onDelete('CASCADE');
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onDelete('set null');
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onDelete('set null');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('set null');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trems_and_conditions');
    }
};
