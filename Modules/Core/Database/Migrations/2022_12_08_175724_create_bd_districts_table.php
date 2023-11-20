<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\BdDivision;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bd_districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(BdDivision::class, 'division_id')->constrained('bd_divisions')->cascadeOnDelete();
            $table->string('name');
            $table->string('bn_name')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bd_districts');
    }
};
