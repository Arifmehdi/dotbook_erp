<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            echo 'Seeding Initial Data'.PHP_EOL;
            echo '============================================================================='.PHP_EOL;

            $this->call(UserSeeder::class);
            $this->call(RolePermissionSeeder::class);
            $this->call(GeneralSettingsSeeder::class); // 2
            $this->call(AddonsSeeder::class); // 3
            // $this->call(ItemsAndRelatedTablesSeeder::class);
            $this->call(BarcodeSettingsSeeder::class);
            $this->call(InvoiceLayoutSeeder::class);
            $this->call(InvoiceSchemaSeeder::class);
            $this->call(PaymentMethodSeeder::class);
            $this->call(PosShortMenusSeeder::class);
            $this->call(ShortMenusSeeder::class);
            $this->call(CurrenciesSeeder::class);
            // $this->call(BankSeeder::class);
            // $this->call(AccountsSeeder::class);
            // $this->call(AccountBranchesSeeder::class);

        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            echo '============================================================================='.PHP_EOL;
            echo 'Operation finished.'.PHP_EOL;
        }
    }
}
