<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lcs', function (Blueprint $table) {
            $table->foreign(['issuing_bank_id'])->references(['id'])->on('banks')->onDelete('SET NULL');
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onDelete('SET NULL');
            $table->foreign(['advising_bank_id'])->references(['id'])->on('banks')->onDelete('SET NULL');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['opening_bank_id'])->references(['id'])->on('banks')->onDelete('SET NULL');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lcs', function (Blueprint $table) {
            $table->dropForeign('lcs_issuing_bank_id_foreign');
            $table->dropForeign('lcs_supplier_id_foreign');
            $table->dropForeign('lcs_advising_bank_id_foreign');
            $table->dropForeign('lcs_created_by_id_foreign');
            $table->dropForeign('lcs_opening_bank_id_foreign');
            $table->dropForeign('lcs_updated_by_id_foreign');
            $table->dropForeign('lcs_branch_id_foreign');
        });
    }
}
