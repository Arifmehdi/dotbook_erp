<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\HRM\Entities\Employee;
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

        Schema::connection('hrm')->create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Employee::class, 'employee_id')->constrained(config('database.connections.hrm.database').'.employees')->onDelete('cascade');
            $table->string('clock_in', 5)->nullable();
            $table->string('clock_out', 5)->nullable();
            $table->string('at_date', 10)->nullable();
            $table->timestamp('at_date_ts')->nullable();
            $table->timestamp('clock_in_ts')->nullable();
            $table->timestamp('clock_out_ts')->nullable();
            $table->string('month', 9)->nullable();
            $table->year('year')->nullable();
            $table->string('bm_clock_in', 5)->nullable();
            $table->timestamp('bm_clock_in_ts')->nullable();
            $table->string('bm_clock_out', 5)->nullable();
            $table->timestamp('bm_clock_out_ts')->nullable();
            $table->foreignIdFor(Shift::class)->constrained()->onDelete('cascade');
            // $table->integer('holiday_id')->nullable();
            // $table->string('shift', 191)->nullable();
            // $table->string('leave_type', 191)->nullable();
            $table->string('status', 10)->nullable();
            $table->boolean('manual_entry')->nullable()->default(false);
            $table->unique(['employee_id', 'at_date'], 'UserId_AttendanceDate_Unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('hrm')->dropIfExists('attendances');
    }
};
