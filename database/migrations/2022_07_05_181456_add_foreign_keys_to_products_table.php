<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['tax_id'])->references(['id'])->on('taxes')->onDelete('SET NULL');
            $table->foreign(['warranty_id'])->references(['id'])->on('warranties')->onDelete('SET NULL');
            $table->foreign(['brand_id'])->references(['id'])->on('brands')->onDelete('SET NULL');
            $table->foreign(['parent_category_id'])->references(['id'])->on('categories')->onDelete('SET NULL');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('CASCADE');
            $table->foreign(['category_id'])->references(['id'])->on('categories')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_tax_id_foreign');
            $table->dropForeign('products_warranty_id_foreign');
            $table->dropForeign('products_brand_id_foreign');
            $table->dropForeign('products_parent_category_id_foreign');
            $table->dropForeign('products_unit_id_foreign');
            $table->dropForeign('products_category_id_foreign');
        });
    }
}
