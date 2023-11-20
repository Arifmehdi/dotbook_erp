<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\HRM\Entities\Shift;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('hrm')->create('shift_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Shift::class)->constrained()->cascadeOnDelete();
            $table->time('start_time');
            $table->time('end_time');
            $table->time('late_count');
            $table->date('applied_date_from');
            $table->date('applied_date_to');
            $table->boolean('with_break')->default(false);
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->softDeletes();
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
        Schema::connection('hrm')->dropIfExists('shift_adjustments');
    }
};
