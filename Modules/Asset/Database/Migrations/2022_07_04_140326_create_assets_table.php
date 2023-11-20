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
        Schema::create('assets', function (Blueprint $table) {

            $table->id();
            $table->string('asset_code');
            $table->string('asset_name');
            $table->decimal('quantity');
            $table->unsignedBigInteger('asset_unit_id')->nullable();
            $table->string('model')->nullable();
            $table->string('serial')->nullable();
            $table->unsignedBigInteger('asset_category_id')->nullable();
            $table->unsignedBigInteger('asset_location_id')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->decimal('unit_price')->nullable();
            $table->enum('purchase_type', ['Owned', 'Rented', 'Leased'])->default('Owned');
            $table->date('purchase_date');
            $table->date('expire_date')->nullable();
            $table->boolean('is_allocatable')->default(false);
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_allocated')->default(false);
            $table->unsignedBigInteger('asset_supplier_id')->nullable();
            $table->string('image')->nullable();
            $table->text('additional_files')->nullable();
            $table->text('components')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('asset_category_id')->references('id')->on('asset_categories')->onDelete('SET NULL');
            $table->foreign('asset_unit_id')->references('id')->on('asset_units')->onDelete('SET NULL');
            $table->foreign('asset_location_id')->references('id')->on('asset_locations')->onDelete('SET NULL');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('asset_supplier_id')->references('id')->on('assets_suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
