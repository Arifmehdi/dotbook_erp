<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->foreign(['price_group_id'])->references(['id'])->on('price_groups')->onDelete('SET NULL');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['category_id'])->references(['id'])->on('categories')->onDelete('CASCADE');
            $table->foreign(['brand_id'])->references(['id'])->on('brands')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropForeign('discounts_price_group_id_foreign');
            $table->dropForeign('discounts_branch_id_foreign');
            $table->dropForeign('discounts_category_id_foreign');
            $table->dropForeign('discounts_brand_id_foreign');
        });
    }
}
