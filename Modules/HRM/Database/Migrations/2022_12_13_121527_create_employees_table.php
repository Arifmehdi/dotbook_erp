<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\HRM\Entities\HrmDepartment;
use Modules\HRM\Entities\Designation;
use Modules\HRM\Entities\Grade;
use Modules\HRM\Entities\Section;
use Modules\HRM\Entities\Shift;
use Modules\HRM\Entities\SubSection;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('hrm')->create('employees', function (Blueprint $table) {
            $table->id('id');
            /**
             * Personal information
             */
            $table->string('name');
            $table->string('phone', 80)->nullable();
            $table->string('alternative_phone', 80)->nullable();
            $table->string('photo')->nullable();
            $table->date('dob')->nullable();
            $table->string('nid', 45)->nullable();
            $table->string('birth_certificate', 45)->nullable();
            $table->text('attachments')->nullable();
            $table->enum('marital_status', ['Married', 'Single', 'Divorced', 'Other'])->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('blood', 10)->nullable();
            $table->string('country')->nullable();
            $table->string('father_name', 191)->nullable();
            $table->string('mother_name', 191)->nullable();
            $table->string('religion', 191)->nullable();

            // Authentication related fields
            $table->string('email')->nullable();
            $table->boolean('login_access')->nullable()->default(false);
            $table->string('home_phone', 20)->nullable();
            $table->string('emergency_contact_person_name')->nullable();
            $table->string('emergency_contact_person_phone', 20)->nullable();
            $table->string('emergency_contact_person_relation')->nullable();
            $table->unsignedBigInteger('present_division_id')->nullable();
            $table->unsignedBigInteger('present_district_id')->nullable();
            $table->unsignedBigInteger('present_upazila_id')->nullable();
            $table->unsignedBigInteger('present_union_id')->nullable();
            $table->string('present_village')->nullable();
            //$table->string('present_postoffice')->nullable();

            $table->unsignedBigInteger('permanent_division_id')->nullable();
            $table->unsignedBigInteger('permanent_district_id')->nullable();
            $table->unsignedBigInteger('permanent_upazila_id')->nullable();
            $table->unsignedBigInteger('permanent_union_id')->nullable();
            $table->string('permanent_village')->nullable();
            //$table->string('permanent_postoffice')->nullable();

            /**
             * Official Information
             * =====================================================
             */

            // Required
            $table->string('employee_id', 25);
            $table->foreignIdFor(Shift::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(HrmDepartment::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Section::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SubSection::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Designation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Grade::class)->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('duty_type_id')->nullable();
            $table->date('joining_date');
            $table->tinyInteger('employee_type'); // 1=Admin;2=Staff;3=Employee;4=Worker;5=Other
            $table->integer('salary');
            $table->boolean('overtime_allowed')->default(true);

            // Optional
            $table->integer('starting_shift_id')->nullable();
            $table->integer('starting_salary')->nullable();
            $table->tinyInteger('employment_status')->default(1); // 1=Active; 2=Resigned; 3=Left; 4=Terminated;
            $table->date('resign_date')->nullable();
            $table->date('left_date')->nullable();
            $table->date('termination_date')->nullable();
            /**
             * Accounting Information
             * =====================================================
             */
            $table->string('bank_name', 191)->nullable();
            $table->string('bank_branch_name', 191)->nullable();
            $table->string('bank_account_name', 191)->nullable();
            $table->string('bank_account_number', 191)->nullable();
            $table->string('mobile_banking_provider')->nullable(); // "Bkash", "Nagad", "Upay", "Rocket", etc
            $table->string('mobile_banking_account_number')->nullable();
            $table->unsignedSmallInteger('print_count')->nullable();
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
        Schema::connection('hrm')->dropIfExists('employees');
    }
};
