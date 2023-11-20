<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetPasswordForAdminCommand extends Command
{
    protected $signature = 'dev:reset';

    protected $description = 'Reset admin command to password';

    public function handle()
    {
        if (! config('app.debug')) {
            $this->error('This command is not available on production environment');
            exit(1);
        }

        $firstAdmin = \App\Models\User::role('superadmin')->first();
        $newPassword = 'password';
        $firstAdmin->password = bcrypt($newPassword);
        $firstAdmin->save();

        echo "Username: {$firstAdmin->username}\nPassword: {$newPassword}\n";

        $this->info('Reset succeed!');

        return 0;
    }
}
