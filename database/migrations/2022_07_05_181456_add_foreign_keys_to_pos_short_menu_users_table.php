<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPosShortMenuUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_short_menu_users', function (Blueprint $table) {
            $table->foreign(['short_menu_id'])->references(['id'])->on('pos_short_menus')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pos_short_menu_users', function (Blueprint $table) {
            $table->dropForeign('pos_short_menu_users_short_menu_id_foreign');
            $table->dropForeign('pos_short_menu_users_user_id_foreign');
        });
    }
}
