<?php

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
        Schema::create('cost_centres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('parent_of_parent_category_id')->nullable();
            $table->unsignedBigInteger('parent_cost_centre_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('cost_centre_categories')->onDelete('cascade');
            $table->foreign('parent_of_parent_category_id')->references('id')->on('cost_centre_categories')->onDelete('cascade');
            $table->foreign('parent_cost_centre_id')->references('id')->on('cost_centres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_centres');
    }
};
