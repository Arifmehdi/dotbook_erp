<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\HRM\Entities\Employee;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // if (Schema::connection('hrm')->hasTable('overtime_adjustments')) {return;};
        Schema::connection('hrm')->create('overtime_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class, 'employee_id')->constrained(config('database.connections.hrm.database').'.employees')->onDelete('cascade');
            $table->tinyInteger('type')->comment('1=Addition;2=Deduction');
            $table->integer('ot_minutes');
            $table->tinyInteger('month');
            $table->year('year');
            $table->text('description')->nullable();
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
        Schema::connection('hrm')->dropIfExists('overtime_adjustments');
    }
};
