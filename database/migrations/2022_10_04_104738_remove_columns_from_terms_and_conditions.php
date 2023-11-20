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
        Schema::table('terms_and_conditions', function (Blueprint $table) {
            $exists = Schema::hasColumn('terms_and_conditions', 'terms_for')
                        && Schema::hasColumn('terms_and_conditions', 'supplier_id')
                        && Schema::hasColumn('terms_and_conditions', 'customer_id');
            if ($exists) {
                $table->dropForeign('terms_and_conditions_supplier_id_foreign');
                $table->dropForeign('terms_and_conditions_customer_id_foreign');
                $table->dropColumn([
                    'terms_for',
                    'supplier_id',
                    'customer_id',
                ]);
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
        Schema::table('terms_and_conditions', function (Blueprint $table) {
            $exists = ! Schema::hasColumn('terms_and_conditions', 'terms_for')
            && ! Schema::hasColumn('terms_and_conditions', 'supplier_id')
            && ! Schema::hasColumn('terms_and_conditions', 'customer_id');

            if ($exists) {
                $table->integer('terms_for')->nullable()->after('category_id');
                $table->unsignedBigInteger('supplier_id')->nullable()->after('terms_for');
                $table->unsignedBigInteger('customer_id')->nullable()->after('supplier_id');

                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('CASCADE');
                $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('CASCADE');
            }
        });
    }
};
