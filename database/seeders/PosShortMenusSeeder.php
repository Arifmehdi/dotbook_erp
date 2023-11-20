<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosShortMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('ALTER TABLE pos_short_menus AUTO_INCREMENT = 1');
        $pos_short_menus = [
            ['url' => 'product.categories.index', 'name' => 'Categories', 'icon' => 'fas fa-th-large', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'product.subcategories.index', 'name' => 'SubCategories', 'icon' => 'fas fa-code-branch', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'product.brands.index', 'name' => 'Brands', 'icon' => 'fas fa-band-aid', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'products.all.product', 'name' => 'Product List', 'icon' => 'fas fa-sitemap', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'products.add.view', 'name' => 'Add Product', 'icon' => 'fas fa-plus-circle', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'product.variants.index', 'name' => 'Variants', 'icon' => 'fas fa-align-center', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'product.import.create', 'name' => 'Import Products', 'icon' => 'fas fa-file-import', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'product.selling.price.groups.index', 'name' => 'Price Group', 'icon' => 'fas fa-layer-group', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'barcode.index', 'name' => 'G.Barcode', 'icon' => 'fas fa-barcode', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'product.warranties.index', 'name' => 'Warranties ', 'icon' => 'fas fa-shield-alt', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'contacts.supplier.index', 'name' => 'Suppliers', 'icon' => 'fas fa-address-card', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'contacts.suppliers.import.create', 'name' => 'Import Suppliers', 'icon' => 'fas fa-file-import', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'contacts.customers.index', 'name' => 'Customers', 'icon' => 'far fa-address-card', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'contacts.customers.import.create', 'name' => 'Import Customers', 'icon' => 'fas fa-file-upload', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'purchases.create', 'name' => 'Add Purchase', 'icon' => 'fas fa-shopping-cart', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'purchases.index', 'name' => 'Purchase List', 'icon' => 'fas fa-list', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'purchases.returns.index', 'name' => 'Purchase Return', 'icon' => 'fas fa-undo', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'sales.create', 'name' => 'Add Sale', 'icon' => 'fas fa-cart-plus', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'sales.index', 'name' => 'Add Sale List', 'icon' => 'fas fa-tasks', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'sales.pos.create', 'name' => 'POS', 'icon' => 'fas fa-cash-register', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'sales.pos.list', 'name' => 'POS List', 'icon' => 'fas fa-tasks', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'sales.quotations', 'name' => 'Quotation List', 'icon' => 'fas fa-quote-right', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'sales.returns.index', 'name' => 'Sale Returns', 'icon' => 'fas fa-undo', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'expanses.create', 'name' => 'Add Expense', 'icon' => 'fas fa-plus-square', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'expenses.index', 'name' => 'Expense List', 'icon' => 'far fa-list-alt', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'expenses.categories.index', 'name' => 'Expense Categories Categories', 'icon' => 'fas fa-cubes', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'users.create', 'name' => 'Add User', 'icon' => 'fas fa-user-plus', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'users.index', 'name' => 'User List', 'icon' => 'fas fa-list-ol', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'users.role.create', 'name' => 'Add Role', 'icon' => 'fas fa-plus-circle', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'users.role.index', 'name' => 'Role List', 'icon' => 'fas fa-th-list', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'accounting.banks.index', 'name' => 'Bank', 'icon' => 'fas fa-university', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'accounting.accounts.index', 'name' => 'Accounts', 'icon' => 'fas fa-th', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'accounting.balance.sheet', 'name' => 'Balance Sheet', 'icon' => 'fas fa-balance-scale', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'accounting.trial.balance', 'name' => 'Trial Balance', 'icon' => 'fas fa-balance-scale-right', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'accounting.cash.flow', 'name' => 'Cash Flow', 'icon' => 'fas fa-money-bill-wave', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'settings.general.index', 'name' => 'General Settings', 'icon' => 'fas fa-cogs', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'settings.taxes.index', 'name' => 'Taxes', 'icon' => 'fas fa-percentage', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'invoices.schemas.index', 'name' => 'Invoice Schemas', 'icon' => 'fas fa-file-invoice-dollar', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'invoices.layouts.index', 'name' => 'Invoice Layouts', 'icon' => 'fas fa-file-invoice', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'settings.barcode.index', 'name' => 'Barcode Settings', 'icon' => 'fas fa-barcode', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
            ['url' => 'settings.cash.counter.index', 'name' => 'Cash Counter', 'icon' => 'fas fa-store', 'created_at' => '2021-08-21 09:41:00', 'updated_at' => '2021-08-21 09:41:00'],
        ];

        DB::table('pos_short_menus')->insert($pos_short_menus);

        /* `old_erp`.`pos_short_menu_users` */
        $pos_short_menu_users = [
            ['short_menu_id' => '1', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2021-09-04 08:47:13', 'updated_at' => '2022-04-07 08:08:04'],
            ['short_menu_id' => '2', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-04-07 08:08:01', 'updated_at' => '2022-04-07 08:08:04'],
            ['short_menu_id' => '3', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-04-07 08:08:01', 'updated_at' => '2022-04-07 08:08:04'],
            ['short_menu_id' => '4', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-04-07 08:08:02', 'updated_at' => '2022-04-07 08:08:04'],
            ['short_menu_id' => '5', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-04-07 08:08:02', 'updated_at' => '2022-04-07 08:08:04'],
            ['short_menu_id' => '6', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-04-07 08:08:04', 'updated_at' => '2022-04-07 08:08:04'],
            ['short_menu_id' => '7', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-04-07 08:08:04', 'updated_at' => '2022-04-07 08:08:04'],
        ];

        DB::table('pos_short_menu_users')->insert($pos_short_menu_users);
    }
}
