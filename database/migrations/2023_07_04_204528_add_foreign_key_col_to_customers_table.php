<?php

use App\Utils\DatabaseUtils\TableIndexList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $indexes = TableIndexList::getIndexes('customers');
            if (! in_array('customers_created_by_id_foreign', $indexes) && Schema::hasColumn('customers', 'created_by_id')) {
                $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
        });
    }
};
