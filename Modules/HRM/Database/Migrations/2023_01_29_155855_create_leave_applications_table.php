<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\LeaveType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('hrm')->create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(LeaveType::class)->constrained()->onDelete('cascade');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('approve_day');
            $table->text('reason')->nullable();
            $table->text('attachment')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('is_paid')->default(false);
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
        Schema::connection('hrm')->dropIfExists('leave_applications');
    }
};
