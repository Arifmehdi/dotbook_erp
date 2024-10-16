<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProcessIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_ingredients', function (Blueprint $table) {
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['process_id'])->references(['id'])->on('processes')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('process_ingredients', function (Blueprint $table) {
            $table->dropForeign('process_ingredients_variant_id_foreign');
            $table->dropForeign('process_ingredients_process_id_foreign');
            $table->dropForeign('process_ingredients_unit_id_foreign');
            $table->dropForeign('process_ingredients_product_id_foreign');
        });
    }
}
