<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Communication\Entities\EmailTemplate;
use Modules\HRM\Entities\Interview;
use Modules\Website\Entities\JobApply;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('hrm')->create('interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Interview::class, 'interview_id')->constrained(config('database.connections.hrm.database') . '.interviews', 'id')->cascadeOnDelete();
            $table->dateTime('date_time');
            $table->string('interviewers')->nullable();

            $table->foreignIdFor(JobApply::class, 'applicant_id')
                // ->constrained(config('database.connections.website.database').'.job_applies', 'id')
                ->nullable();

            $table->foreignIdFor(EmailTemplate::class, 'email_template_id')
                ->constrained(config('database.connections.mysql.database') . '.email_templates', 'id')
                ->nullable();

            $table->text('descriptions')->nullable();
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
        Schema::connection('hrm')->dropIfExists('interview_schedules');
    }
};
