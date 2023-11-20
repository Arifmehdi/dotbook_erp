<?php

namespace App\Http\Controllers;

class DatabaseBackupController extends Controller
{
    public function databaseBackup()
    {
        return view('database-backup.index');
    }
}
