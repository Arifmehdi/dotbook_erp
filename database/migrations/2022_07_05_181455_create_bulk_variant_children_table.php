<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkVariantChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_variant_children', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bulk_variant_id')->nullable()->index();
            $table->string('child_name', 191)->nullable();
            $table->boolean('delete_in_update')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bulk_variant_children');
    }
}
