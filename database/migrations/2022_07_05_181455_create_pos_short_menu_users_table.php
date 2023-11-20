<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosShortMenuUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_short_menu_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('short_menu_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->boolean('is_delete_in_update')->default(false);
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
        Schema::dropIfExists('pos_short_menu_users');
    }
}
