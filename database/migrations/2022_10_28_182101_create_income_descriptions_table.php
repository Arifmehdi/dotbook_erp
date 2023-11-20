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
        if (! Schema::hasTable('income_descriptions')) {
            Schema::create('income_descriptions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('income_id');
                $table->unsignedBigInteger('income_account_id');
                $table->decimal('amount', 22, 2)->default(0);
                $table->foreign(['income_id'])->references(['id'])->on('incomes')->onDelete('cascade');
                $table->foreign(['income_account_id'])->references(['id'])->on('accounts')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_descriptions');
    }
};
