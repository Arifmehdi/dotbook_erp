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

        Schema::create('asset_revokes', function (Blueprint $table) {
            $table->id();
            $table->string('revoke_code')->nullable();
            $table->unsignedBigInteger('allocation_id')->nullable()->comment('Contains the allocation information of specific id');
            $table->unsignedBigInteger('asset_id')->nullable()->comment('Contains the asset information of specific id');
            $table->decimal('quantity');
            $table->dateTime('revoke_date')->comment('The time when the revoke information inserted');
            $table->unsignedBigInteger('revoke_by_id')->nullable()->comment('Which person insert the revoke information first time');
            $table->string('reason')->nullable()->comment('Wht is the reason to revoke');
            $table->timestamps();

            $table->unsignedBigInteger('updated_by_id')->nullable();

            $table->foreign('allocation_id')->references('id')->on('asset_allocations')->onDelete('SET NULL');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('SET NULL');
            $table->foreign('revoke_by_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_revokes');
    }
};
