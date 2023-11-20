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
        Schema::table('income_descriptions', function (Blueprint $table) {
            if (! Schema::hasColumn('income_descriptions', 'is_delete_in_update')) {
                $table->boolean('is_delete_in_update')->after('income_account_id')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_descriptions', function (Blueprint $table) {
            $table->dropColumn('is_delete_in_update');
        });
    }
};
