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
        Schema::table('exporters', function (Blueprint $table) {
            $table->decimal('opening_balance')->default(0)->nullable()->after('total_export');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exporters', function (Blueprint $table) {
            if (Schema::hasColumn('exporters', 'status')) {
                $table->dropColumn('opening_balance');
            }
        });
    }
};
