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
        Schema::table('weight_scales', function (Blueprint $table) {
            $table->unsignedBigInteger('first_weighted_by_id')->after('id')->nullable();
            $table->unsignedBigInteger('second_weighted_by_id')->after('first_weighted_by_id')->nullable();
            $table->foreign('first_weighted_by_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('second_weighted_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weight_scales', function (Blueprint $table) {
            $table->dropForeign(['first_weighted_by_id']);
            $table->dropColumn('first_weighted_by_id');
            $table->dropForeign(['second_weighted_by_id']);
            $table->dropColumn('second_weighted_by_id');
        });
    }
};
