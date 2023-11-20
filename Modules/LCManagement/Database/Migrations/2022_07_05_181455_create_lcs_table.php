<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lcs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lc_no', 191)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->timestamp('opening_date')->nullable();
            $table->timestamp('last_date')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->decimal('lc_amount', 22)->default(0);
            $table->string('currency')->nullable();
            $table->decimal('currency_rate', 22)->default(0);
            $table->decimal('total_amount', 22)->default(0);
            $table->decimal('lc_margin_amount', 22)->default(0);
            $table->string('insurance_company', 191)->nullable();
            $table->decimal('insurance_payable_amt', 22)->default(0);
            $table->tinyInteger('shipment_mode')->nullable()->comment('1=CNF,2=FOB,3=FCA');
            $table->decimal('mode_of_amount', 22)->default(0);
            $table->decimal('total_payable_amt', 22)->default(0);
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            $table->unsignedBigInteger('advising_bank_id')->nullable()->index();
            $table->unsignedBigInteger('issuing_bank_id')->nullable()->index();
            $table->unsignedBigInteger('opening_bank_id')->nullable()->index();
            $table->unsignedBigInteger('created_by_id')->nullable()->index();
            $table->unsignedBigInteger('updated_by_id')->nullable()->index();
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
        Schema::dropIfExists('lcs');
    }
}
