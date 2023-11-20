<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_person_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_id');
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phon')->nullable();
            $table->string('contact_person_dasignation')->nullable();
            $table->string('contact_person_landline')->nullable();
            $table->string('contact_person_alternative_phone')->nullable();
            $table->string('contact_person_fax')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_address')->nullable();
            $table->string('contact_person_post_office')->nullable();
            $table->string('contact_person_zip_code')->nullable();
            $table->string('contact_person_police_station')->nullable();
            $table->string('contact_person_state')->nullable();
            $table->string('contact_person_city')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_person_details');
    }
};
