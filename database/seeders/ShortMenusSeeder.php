<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShortMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('ALTER TABLE short_menus AUTO_INCREMENT = 1');
        $short_menus = [
            ['url' => 'product.categories.index', 'name' => 'Categories', 'icon' => 'fas fa-th-large', 'created_at' => null, 'updated_at' => null],
            ['url' => 'product.subcategories.index', 'name' => 'SubCategories', 'icon' => 'fas fa-code-branch', 'created_at' => null, 'updated_at' => null],
            ['url' => 'product.brands.index', 'name' => 'Brands', 'icon' => 'fas fa-band-aid', 'created_at' => null, 'updated_at' => null],
            ['url' => 'products.all.product', 'name' => 'Product List', 'icon' => 'fas fa-sitemap', 'created_at' => null, 'updated_at' => null],
            ['url' => 'products.add.view', 'name' => 'Add Product', 'icon' => 'fas fa-plus-circle', 'created_at' => null, 'updated_at' => null],
            ['url' => 'product.variants.index', 'name' => 'Variants', 'icon' => 'fas fa-align-center', 'created_at' => null, 'updated_at' => null],
            ['url' => 'product.import.create', 'name' => 'Import Products', 'icon' => 'fas fa-file-import', 'created_at' => null, 'updated_at' => null],
            ['url' => 'product.selling.price.groups.index', 'name' => 'Price Group', 'icon' => 'fas fa-layer-group', 'created_at' => null, 'updated_at' => null],
            ['url' => 'barcode.index', 'name' => 'G.Barcode', 'icon' => 'fas fa-barcode', 'created_at' => null, 'updated_at' => null],
            ['url' => 'product.warranties.index', 'name' => 'Warranties ', 'icon' => 'fas fa-shield-alt', 'created_at' => null, 'updated_at' => null],
            ['url' => 'contacts.supplier.index', 'name' => 'Suppliers', 'icon' => 'fas fa-address-card', 'created_at' => null, 'updated_at' => null],
            ['url' => 'contacts.suppliers.import.create', 'name' => 'Import Suppliers', 'icon' => 'fas fa-file-import', 'created_at' => null, 'updated_at' => null],
            ['url' => 'contacts.customers.index', 'name' => 'Customers', 'icon' => 'far fa-address-card', 'created_at' => null, 'updated_at' => null],
            ['url' => 'contacts.customers.import.create', 'name' => 'Import Customers', 'icon' => 'fas fa-file-upload', 'created_at' => null, 'updated_at' => null],
            ['url' => 'purchases.create', 'name' => 'Add Purchase', 'icon' => 'fas fa-shopping-cart', 'created_at' => null, 'updated_at' => null],
            ['url' => 'purchases.index', 'name' => 'Purchase List', 'icon' => 'fas fa-list', 'created_at' => null, 'updated_at' => null],
            ['url' => 'purchases.returns.index', 'name' => 'Purchase Return', 'icon' => 'fas fa-undo', 'created_at' => null, 'updated_at' => null],
            ['url' => 'sales.index', 'name' => 'Add Sale List', 'icon' => 'fas fa-tasks', 'created_at' => null, 'updated_at' => null],
            ['url' => 'sales.pos.create', 'name' => 'POS', 'icon' => 'fas fa-cash-register', 'created_at' => null, 'updated_at' => null],
            ['url' => 'sales.pos.list', 'name' => 'POS List', 'icon' => 'fas fa-tasks', 'created_at' => null, 'updated_at' => null],
            ['url' => 'sales.quotations', 'name' => 'Quotation List', 'icon' => 'fas fa-quote-right', 'created_at' => null, 'updated_at' => null],
            ['url' => 'sales.returns.index', 'name' => 'Sale Returns', 'icon' => 'fas fa-undo', 'created_at' => null, 'updated_at' => null],
            ['url' => 'expanses.create', 'name' => 'Add Expense', 'icon' => 'fas fa-plus-square', 'created_at' => null, 'updated_at' => null],
            ['url' => 'expenses.index', 'name' => 'Expense List', 'icon' => 'far fa-list-alt', 'created_at' => null, 'updated_at' => null],
            ['url' => 'users.create', 'name' => 'Add User', 'icon' => 'fas fa-user-plus', 'created_at' => null, 'updated_at' => null],
            ['url' => 'users.index', 'name' => 'User List', 'icon' => 'fas fa-list-ol', 'created_at' => null, 'updated_at' => null],
            ['url' => 'users.role.create', 'name' => 'Add Role', 'icon' => 'fas fa-plus-circle', 'created_at' => null, 'updated_at' => null],
            ['url' => 'users.role.index', 'name' => 'Role List', 'icon' => 'fas fa-th-list', 'created_at' => null, 'updated_at' => null],
            ['url' => 'accounting.banks.index', 'name' => 'Bank', 'icon' => 'fas fa-university', 'created_at' => null, 'updated_at' => null],
            ['url' => 'accounting.accounts.index', 'name' => 'Accounts', 'icon' => 'fas fa-th', 'created_at' => null, 'updated_at' => null],
            ['url' => 'accounting.balance.sheet', 'name' => 'Balance Sheet', 'icon' => 'fas fa-balance-scale', 'created_at' => null, 'updated_at' => null],
            ['url' => 'accounting.trial.balance', 'name' => 'Trial Balance', 'icon' => 'fas fa-balance-scale-right', 'created_at' => null, 'updated_at' => null],
            ['url' => 'accounting.cash.flow', 'name' => 'Cash Flow', 'icon' => 'fas fa-money-bill-wave', 'created_at' => null, 'updated_at' => null],
            ['url' => 'settings.general.index', 'name' => 'General Settings', 'icon' => 'fas fa-cogs', 'created_at' => null, 'updated_at' => null],
            ['url' => 'settings.taxes.index', 'name' => 'Taxes', 'icon' => 'fas fa-percentage', 'created_at' => null, 'updated_at' => null],
            ['url' => 'invoices.schemas.index', 'name' => 'Inv. Schemas', 'icon' => 'fas fa-file-invoice-dollar', 'created_at' => null, 'updated_at' => null],
            ['url' => 'invoices.layouts.index', 'name' => 'Inv. Layouts', 'icon' => 'fas fa-file-invoice', 'created_at' => null, 'updated_at' => null],
            ['url' => 'settings.barcode.index', 'name' => 'Barcode Settings', 'icon' => 'fas fa-barcode', 'created_at' => null, 'updated_at' => null],
            ['url' => 'settings.cash.counter.index', 'name' => 'Cash Counter', 'icon' => 'fas fa-store', 'created_at' => null, 'updated_at' => null],
        ];

        \DB::table('short_menus')->insert($short_menus);

        /* `old_erp`.`short_menu_users` */
        $short_menu_users = [
            ['short_menu_id' => '3', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2021-10-07 06:49:44', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '4', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2021-10-07 06:49:45', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '26', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2021-10-07 06:49:48', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '18', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2021-10-07 06:49:52', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '19', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2021-10-07 06:49:53', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '31', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-05-23 07:22:14', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '32', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-05-23 07:22:15', 'updated_at' => '2022-05-23 07:22:21'],
            ['short_menu_id' => '8', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-05-23 07:22:18', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '21', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-05-23 07:22:20', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '30', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-05-23 07:22:21', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '1', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-05-23 07:22:22', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '2', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-05-23 07:22:22', 'updated_at' => '2022-05-23 07:22:22'],
            ['short_menu_id' => '35', 'user_id' => '1', 'is_delete_in_update' => '0', 'created_at' => '2022-05-23 07:22:22', 'updated_at' => '2022-05-23 07:22:22'],
        ];
        \DB::table('short_menu_users')->insert($short_menu_users);
    }
}
