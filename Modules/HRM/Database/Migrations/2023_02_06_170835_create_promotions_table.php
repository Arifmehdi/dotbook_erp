<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\HRM\Entities\HrmDepartment;
use Modules\HRM\Entities\Designation;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Section;
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

        Schema::connection('hrm')->create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(HrmDepartment::class, 'previous_hrm_department_id')->constrained('hrm_departments')->onDelete('cascade');
            $table->foreignIdFor(Section::class, 'previous_section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignIdFor(SubSection::class, 'previous_subsection_id')->constrained('sub_sections')->onDelete('cascade');
            $table->foreignIdFor(Designation::class, 'previous_designation_id')->constrained('designations')->onDelete('cascade');
            $table->foreignIdFor(HrmDepartment::class, 'new_hrm_department_id')->constrained('hrm_departments')->onDelete('cascade');
            $table->foreignIdFor(Section::class, 'new_section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignIdFor(SubSection::class, 'new_subsection_id')->constrained('sub_sections')->onDelete('cascade');
            $table->foreignIdFor(Designation::class, 'new_designation_id')->constrained('designations')->onDelete('cascade');
            // $table->foreignIdFor(User::class, 'user_id')->constrained(config('database.connections.mysql.database') . '.users')->onDelete('cascade');
            $table->foreignIdFor(Employee::class, 'user_id')->constrained('employees')->onDelete('cascade');
            $table->date('promoted_date')->nullable();
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
        Schema::connection('hrm')->dropIfExists('promotions');
    }
};
