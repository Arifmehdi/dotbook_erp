<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWorkspaceUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workspace_users', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['workspace_id'])->references(['id'])->on('workspaces')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workspace_users', function (Blueprint $table) {
            $table->dropForeign('workspace_users_user_id_foreign');
            $table->dropForeign('workspace_users_workspace_id_foreign');
        });
    }
}
