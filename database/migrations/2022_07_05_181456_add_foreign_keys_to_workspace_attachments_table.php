<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWorkspaceAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workspace_attachments', function (Blueprint $table) {
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
        Schema::table('workspace_attachments', function (Blueprint $table) {
            $table->dropForeign('workspace_attachments_workspace_id_foreign');
        });
    }
}
