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
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contact_auto_id')->nullable();
            $table->string('contact_type')->nullable();
            $table->string('contact_related')->nullable();
            $table->string('name');
            $table->string('business_name')->nullable();
            $table->integer('total_employees')->nullable();
            $table->string('phone')->nullable();
            $table->string('landline')->nullable();
            $table->string('email')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->mediumText('address')->nullable();
            $table->mediumText('shipping_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->boolean('status')->default(true);
            $table->string('print_name')->nullable();
            $table->string('nid_no')->nullable();
            $table->text('additional_information')->nullable();
            $table->string('print_ledger_code')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('print_status')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('print_ledger_name')->nullable();
            $table->string('billing_account')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('contact_status')->nullable();
            $table->string('contact_mailing_name')->nullable();
            $table->string('contact_post_office')->nullable();
            $table->string('contact_police_station')->nullable();
            $table->string('contact_currency')->nullable();
            $table->string('contact_fax')->nullable();
            $table->string('primary_mobile')->nullable();
            $table->string('contact_send_sms')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('mailing_name')->nullable();
            $table->string('mailing_address')->nullable();
            $table->string('mailing_email')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_number')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_send_sms')->nullable();
            $table->mediumText('alternative_address')->nullable();
            $table->string('alternative_name')->nullable();
            $table->string('alternative_post_office')->nullable();
            $table->string('alternative_zip_code')->nullable();
            $table->string('alternative_police_station')->nullable();
            $table->string('alternative_state')->nullable();
            $table->string('alternative_city')->nullable();
            $table->string('alternative_fax')->nullable();
            $table->string('alternative_send_sms')->nullable();
            $table->string('alternative_email')->nullable();
            $table->string('contact_file')->nullable();
            $table->mediumText('contact_document')->nullable();
            $table->string('alternative_file')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_name')->nullable();
            $table->string('tax_category')->nullable();
            $table->mediumText('tax_address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_A_C_number')->nullable();
            $table->string('bank_currency')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->string('trade_license_no')->nullable();
            $table->string('known_person')->nullable();
            $table->string('known_person_phone')->nullable();
            $table->string('partner_name')->nullable();
            $table->integer('sales_team')->nullable();
            $table->float('percentage')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('created_by_id')->references('id')->on(config('database.connections.mysql.database').'.users')->onDelete('SET NULL');
            $table->foreign('ref_id')->references('id')->on(config('database.connections.crm.database').'.sources')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
