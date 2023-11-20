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
        Schema::create('account_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            // $table->integer('group_head_number')->nullable();
            // $table->integer('group_type_number')->nullable();
            $table->unsignedBigInteger('parent_group_id')->nullable();
            $table->boolean('is_reserved')->default(0);
            $table->boolean('is_bank_or_cash_ac')->default(0);
            $table->boolean('is_fixed_tax_calculator')->default(0);
            $table->boolean('is_default_tax_calculator')->default(0);
            $table->boolean('is_main_group')->default(0);
            $table->boolean('is_sub_group')->default(0);
            $table->boolean('is_parent_sub_group')->default(0);
            $table->boolean('is_sub_sub_group')->default(0);
            $table->boolean('is_parent_sub_sub_group')->default(0);
            $table->integer('main_group_number')->nullable();
            $table->integer('sub_group_number')->nullable();
            $table->integer('sub_sub_group_number')->nullable();
            $table->string('main_group_name')->nullable();
            $table->string('sub_group_name')->nullable();
            $table->string('sub_sub_group_name')->nullable();
            $table->timestamps();
            $table->foreign('parent_group_id')->references('id')->on('account_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_groups');
    }
};
