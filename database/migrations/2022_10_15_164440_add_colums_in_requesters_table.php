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
        Schema::table('requesters', function (Blueprint $table) {
            $table->string('phone_number')->after('name')->nullable();
            $table->string('area')->after('phone_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requesters', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('area');
        });
    }
};
