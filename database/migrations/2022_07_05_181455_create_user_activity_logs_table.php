<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('date', 30)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->tinyInteger('action')->nullable();
            $table->integer('subject_type')->nullable();
            $table->text('descriptions')->nullable();
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
        Schema::dropIfExists('user_activity_logs');
    }
}
