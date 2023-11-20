<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\PaymentType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('hrm')->create('el_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class, 'employee_id')->constrained(config('database.connections.hrm.database').'.employees')->onDelete('cascade');
            $table->integer('year');
            $table->integer('el_days');
            $table->date('payment_date');
            $table->double('payment_amount', 10, 2);
            $table->foreignIdFor(PaymentType::class)->constrained()->onDelete('cascade');
            $table->text('remarks')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::connection('hrm')->dropIfExists('el_payments');
    }
};
