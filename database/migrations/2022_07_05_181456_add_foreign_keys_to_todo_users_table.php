<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTodoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('todo_users', function (Blueprint $table) {
            $table->foreign(['todo_id'])->references(['id'])->on('todos')->onDelete('CASCADE');
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
        Schema::table('todo_users', function (Blueprint $table) {
            $table->dropForeign('todo_users_todo_id_foreign');
            $table->dropForeign('todo_users_user_id_foreign');
        });
    }
}
