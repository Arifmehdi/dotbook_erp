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
        Schema::connection('hrm')->create('advances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class, 'employee_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('permitted_by')->nullable();
            $table->date('date');
            $table->integer('amount');
            $table->integer('month');
            $table->year('year');
            $table->text('detail')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('permitted_by')->references('id')->on(config('database.connections.mysql.database').'.users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('hrm')->dropIfExists('advances');
    }
};
