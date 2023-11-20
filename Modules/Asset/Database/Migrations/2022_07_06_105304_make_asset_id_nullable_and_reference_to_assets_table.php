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
        Schema::table('asset_allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_id')->nullable()->change();
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_id')->nullable(false)->change();
            $table->dropForeign(['asset_allocations_asset_id_foreign']);
        });
    }
};
