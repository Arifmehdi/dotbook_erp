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
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('import_po_no')->nullable();
            $table->string('proforma_no')->nullable();
            $table->unsignedBigInteger('exporter_id')->nullable();
            $table->unsignedBigInteger('lc_id')->nullable();
            $table->unsignedBigInteger('ledger_account_id')->nullable();
            $table->unsignedBigInteger('goods_country_id')->nullable();
            $table->unsignedBigInteger('destination_country_id')->nullable();
            $table->text('terms_of_delivery')->nullable();
            $table->text('terms_of_payment')->nullable();
            $table->timestamp('order_date')->nullable();
            $table->timestamp('receive_date')->nullable();
            $table->decimal('lc_amount', 22, 2)->default(0);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->decimal('currency_rate', 22, 2)->default(0);
            $table->decimal('total_amount', 22, 2)->default(0);
            $table->unsignedBigInteger('insurance_company_id')->nullable();
            $table->decimal('insurance_payable_amt', 22, 2)->default(0);
            $table->tinyInteger('shipment_mode')->default(1);
            $table->unsignedBigInteger('cnf_agent_id')->nullable();
            $table->decimal('mode_of_amount', 22, 2)->default(0);
            $table->unsignedBigInteger('advising_bank_id')->nullable();
            $table->unsignedBigInteger('issuing_bank_id')->nullable();
            $table->unsignedBigInteger('opening_bank_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->decimal('net_total_amount', 22, 2)->default(0);
            $table->decimal('order_custom', 22, 2)->default(0);
            $table->tinyInteger('order_custom_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('order_custom_amount', 22, 2)->default(0);
            $table->decimal('order_duty', 22, 2)->default(0);
            $table->tinyInteger('order_duty_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('order_duty_amount', 22, 2)->default(0);
            $table->decimal('order_vat', 22, 2)->default(0);
            $table->tinyInteger('order_vat_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('order_vat_amount', 22, 2)->default(0);
            $table->decimal('order_at', 22, 2)->default(0);
            $table->tinyInteger('order_at_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('order_at_amount', 22, 2)->default(0);
            $table->decimal('order_ait', 22, 2)->default(0);
            $table->tinyInteger('order_ait_type')->default(1)->comment('1=Fixed,2=percentage');
            $table->decimal('order_ait_amount', 22, 2)->default(0);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('exporter_id')->references('id')->on('exporters')->onDelete('cascade');
            $table->foreign('lc_id')->references('id')->on('lcs')->onDelete('cascade');
            $table->foreign('ledger_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('insurance_company_id')->references('id')->on('insurance_companies')->onDelete('cascade');
            $table->foreign('cnf_agent_id')->references('id')->on('cnf_agents')->onDelete('cascade');
            $table->foreign('advising_bank_id')->references('id')->on('advising_banks')->onDelete('cascade');
            $table->foreign('issuing_bank_id')->references('id')->on('banks')->onDelete('cascade');
            $table->foreign('opening_bank_id')->references('id')->on('banks')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imports');
    }
};
