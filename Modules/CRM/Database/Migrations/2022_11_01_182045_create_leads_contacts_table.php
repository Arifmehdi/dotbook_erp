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
        Schema::connection('crm')->create('leads_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('sales_commission')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->boolean('allow_login')->default(false);
            $table->softDeletes();
            $table->timestamps();

            // $dbName = \DB::connection('mysql')->getDatabaseName();
            $table->foreign('customer_id')->references('id')->on(config('database.connections.mysql.database').'.customers')->onDelete('CASCADE');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('crm')->dropIfExists('leads_contacts');
    }
};
