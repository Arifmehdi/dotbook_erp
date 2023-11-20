<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductionIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_ingredients', function (Blueprint $table) {
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('CASCADE');
            $table->foreign(['production_id'])->references(['id'])->on('productions')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_ingredients', function (Blueprint $table) {
            $table->dropForeign('production_ingredients_variant_id_foreign');
            $table->dropForeign('production_ingredients_product_id_foreign');
            $table->dropForeign('production_ingredients_unit_id_foreign');
            $table->dropForeign('production_ingredients_production_id_foreign');
        });
    }
}
