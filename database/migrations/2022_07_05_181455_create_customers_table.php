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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id');
            $table->string('contact_id')->nullable();
            $table->unsignedBigInteger('customer_group_id')->nullable();
            $table->tinyInteger('contact_type_id')->nullable();
            $table->string('name');
            $table->string('business_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('landline')->nullable();
            $table->string('email')->nullable();
            $table->string('nid_no')->nullable();
            $table->string('trade_license_no')->nullable();
            $table->string('known_person')->nullable();
            $table->string('known_person_phone')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('tax_number')->nullable();
            $table->decimal('opening_balance', 22)->default(0);
            $table->tinyInteger('customer_type')->default(1);
            $table->decimal('credit_limit', 22)->nullable();
            $table->tinyInteger('pay_term')->nullable()->comment('1=months,2=days');
            $table->integer('pay_term_number')->nullable();
            $table->mediumText('address')->nullable();
            $table->mediumText('shipping_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->decimal('total_sale', 22)->default(0);
            $table->decimal('total_paid', 22)->default(0);
            $table->decimal('total_less', 22)->default(0);
            $table->decimal('total_sale_due', 22)->default(0);
            $table->decimal('total_return', 22)->default(0);
            $table->decimal('total_sale_return_due', 22)->default(0);
            $table->decimal('point', 22)->default(0);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedBigInteger('life_stage_id')->nullable();
            $table->boolean('is_lead')->default(true);
            $table->text('assigned_to_ids')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone_number_prefix')->nullable();
            $table->string('post_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account_number')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
