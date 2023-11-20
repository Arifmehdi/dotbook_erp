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
        Schema::create('supplier_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('print_name')->nullable();
            $table->string('nid_no')->nullable();
            $table->string('additional_information')->nullable();
            $table->string('print_ledger_code')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('print_supplier_status')->nullable();
            $table->string('permanent_address')->nullable();
            $table->integer('supplier_type')->nullable();
            $table->float('credit_limit')->nullable();
            $table->string('print_ledger_name')->nullable();
            $table->string('billing_account')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('supplier_status')->nullable();
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
            $table->string('supplier_file')->nullable();
            $table->mediumText('supplier_document')->nullable();
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
            $table->float('total_sale_due')->nullable();
            $table->integer('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_details');
    }
};
