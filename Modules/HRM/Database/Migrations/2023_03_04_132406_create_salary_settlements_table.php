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
        Schema::connection('hrm')->create('salary_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class, 'employee_id')->constrained(config('database.connections.hrm.database').'.employees')->onDelete('cascade');
            $table->tinyInteger('amount_type')->comment('1=Fixed;2=Percent');
            $table->tinyInteger('salary_type')->comment('1=Increment;2=Decrement');
            $table->decimal('previous', 22);
            $table->decimal('how_much_amount', 22);
            $table->decimal('after_updated', 22);
            $table->text('remarks')->nullable();
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
        Schema::connection('hrm')->dropIfExists('salary_settlements');
    }
};
