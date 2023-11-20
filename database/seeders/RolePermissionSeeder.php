<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.debug') && User::count() == 0) {
            echo 'The app in DEBUG mode and No user found. Running "php artisan db:seed"' . PHP_EOL;
            Artisan::call('db:seed');

            return Command::SUCCESS;
        }
        echo PHP_EOL;
        echo '-: Role and Permission Reset :- ';
        echo '==============================================================' . PHP_EOL;
        echo PHP_EOL;

        $this->truncateRolePermissionData();
        Artisan::call('optimize:clear');
        $this->createRolePermission();
        Artisan::call('optimize:clear');
        $this->syncRolesPermissions();
        echo 'Assign Role to Super-admin and Admin...';
        $this->call(UserRoleSeeder::class);
        echo ' DONE!' . PHP_EOL;
    }

    public function truncateRolePermissionData(): void
    {
        echo 'Erasing old `permissions` table...';
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        DB::statement('ALTER TABLE `permissions` AUTO_INCREMENT = 1');
        echo ' DONE!' . PHP_EOL;
        Schema::enableForeignKeyConstraints();
    }

    public function createRolePermission(): void
    {
        $roles = $this->getRolesArray();
        foreach ($roles as $role) {
            $roleAlreadyExists = Role::where('name', $role['name'])->exists();
            if (!$roleAlreadyExists) {
                Role::create(['name' => $role['name']]);
                echo 'Creating ROLE: ' . $role['name'];
            }
        }

        $permissions = $this->getPermissionsArray();
        foreach ($permissions as $key => $permission) {
            $existingPermission = Permission::where('id', $permission['id'])->where('name', $permission['name'])->first();
            if (!$existingPermission) {
                Permission::create(['id' => $permission['id'], 'name' => $permission['name'], 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]);
                echo ($key + 1) . '. Creating PERMISSION: ' . $permission['name'] . PHP_EOL;
            }
        }
        echo ' DONE!' . PHP_EOL;
    }

    public function syncRolesPermissions(): void
    {
        $allPermissions = Permission::all();

        $superAdminRole = Role::first();
        echo 'Syncing permissions for: ' . $superAdminRole->name . PHP_EOL;
        $superAdminPermissions = $allPermissions->filter(fn ($permission) => $permission->name !== 'view_own_sale');
        $superAdminRole->syncPermissions($superAdminPermissions);

        $adminRole = Role::skip(1)->first();
        echo 'Syncing permissions for: ' . $adminRole->name . PHP_EOL;
        $adminPermissions = $allPermissions->filter(fn ($permission) => $permission->name !== 'view_own_sale');
        $adminRole->syncPermissions($adminPermissions);
        echo ' DONE!' . PHP_EOL;
    }

    public function getRolesArray(): array
    {
        $roles = [
            ['id' => '1', 'name' => 'superadmin'],
            ['id' => '2', 'name' => 'admin'],
            ['id' => '3', 'name' => 'Accountant'],
            ['id' => '4', 'name' => 'SR'],
            ['id' => '5', 'name' => 'Store'],
            ['id' => '6', 'name' => 'Scale and delivery'],
            ['id' => '7', 'name' => 'Order Creator'],
        ];

        return $roles;
    }

    public function getPermissionsArray(): array
    {
        $permissions = [
            ['id' => '1', 'name' => 'user_view'],
            ['id' => '2', 'name' => 'user_add'],
            ['id' => '3', 'name' => 'user_edit'],
            ['id' => '4', 'name' => 'user_delete'],
            ['id' => '5', 'name' => 'role_view'],
            ['id' => '6', 'name' => 'role_add'],
            ['id' => '7', 'name' => 'role_edit'],
            ['id' => '8', 'name' => 'role_delete'],
            ['id' => '9', 'name' => 'supplier_all'],
            ['id' => '10', 'name' => 'supplier_add'],
            ['id' => '11', 'name' => 'supplier_import'],
            ['id' => '12', 'name' => 'supplier_edit'],
            ['id' => '13', 'name' => 'supplier_delete'],
            ['id' => '14', 'name' => 'customer_all'],
            ['id' => '15', 'name' => 'customer_add'],
            ['id' => '16', 'name' => 'customer_import'],
            ['id' => '17', 'name' => 'customer_edit'],
            ['id' => '18', 'name' => 'customer_delete'],
            ['id' => '19', 'name' => 'customer_group'],
            ['id' => '20', 'name' => 'customer_report'],
            ['id' => '21', 'name' => 'supplier_report'],
            ['id' => '22', 'name' => 'product_all'],
            ['id' => '23', 'name' => 'product_add'],
            ['id' => '24', 'name' => 'product_edit'],
            ['id' => '25', 'name' => 'openingStock_add'],
            ['id' => '26', 'name' => 'product_delete'],
            ['id' => '27', 'name' => 'categories'],
            ['id' => '28', 'name' => 'brand'],
            ['id' => '29', 'name' => 'units'],
            ['id' => '30', 'name' => 'variant'],
            ['id' => '31', 'name' => 'warranties'],
            ['id' => '32', 'name' => 'selling_price_group'],
            ['id' => '33', 'name' => 'generate_barcode'],
            ['id' => '34', 'name' => 'product_settings'],
            ['id' => '35', 'name' => 'stock_report'],
            ['id' => '36', 'name' => 'stock_in_out_report'],
            ['id' => '37', 'name' => 'purchase_all'],
            ['id' => '38', 'name' => 'purchase_add'],
            ['id' => '39', 'name' => 'purchase_edit'],
            ['id' => '40', 'name' => 'purchase_delete'],
            ['id' => '41', 'name' => 'purchase_payment_index'],
            ['id' => '42', 'name' => 'purchase_return'],
            ['id' => '43', 'name' => 'status_update'],
            ['id' => '44', 'name' => 'purchase_settings'],
            ['id' => '45', 'name' => 'purchase_report'],
            ['id' => '46', 'name' => 'purchase_sale_report'],
            ['id' => '47', 'name' => 'pro_purchase_report'],
            // array('id' => '48', 'name' => 'purchase_payment_report', 'guard_name' => 'web', 'created_at' => '2022-08-10 12:23:43', 'updated_at' => '2022-08-10 12:23:43'),
            ['id' => '49', 'name' => 'stock_adjustments_all'],
            ['id' => '50', 'name' => 'stock_adjustments_add'],
            // 51 id is available for future
            // array('id' => '51', 'name' => 'adjustment_add_from_warehouse', 'guard_name' => 'web', 'created_at' => '2022-08-10 12:23:43', 'updated_at' => '2022-08-10 12:23:43'),
            ['id' => '52', 'name' => 'stock_adjustments_delete'],
            ['id' => '53', 'name' => 'stock_adjustment_report'],
            ['id' => '54', 'name' => 'view_expense'],
            ['id' => '55', 'name' => 'add_expense'],
            ['id' => '56', 'name' => 'edit_expense'],
            ['id' => '57', 'name' => 'delete_expense'],
            ['id' => '58', 'name' => 'expense_category'],
            ['id' => '59', 'name' => 'category_wise_expense'],
            ['id' => '60', 'name' => 'expanse_report'],
            ['id' => '61', 'name' => 'create_add_sale'],
            ['id' => '62', 'name' => 'view_sales'],
            ['id' => '63', 'name' => 'edit_sale'],
            ['id' => '64', 'name' => 'delete_sale'],
            ['id' => '65', 'name' => 'sale_settings'],
            ['id' => '66', 'name' => 'sale_draft'],
            ['id' => '67', 'name' => 'add_quotation'],
            ['id' => '68', 'name' => 'sale_quotation_list'],
            ['id' => '69', 'name' => 'sale_quotation_edit'],
            ['id' => '70', 'name' => 'sale_quotation_delete'],
            ['id' => '71', 'name' => 'sale_order_add'],
            ['id' => '72', 'name' => 'sale_order_all'],
            ['id' => '73', 'name' => 'sale_order_edit'],
            ['id' => '74', 'name' => 'sale_order_do_approval'],
            ['id' => '75', 'name' => 'sale_order_delete'],
            ['id' => '76', 'name' => 'do_add'],
            ['id' => '77', 'name' => 'do_all'],
            ['id' => '78', 'name' => 'do_edit'],
            ['id' => '79', 'name' => 'do_delete'],
            ['id' => '80', 'name' => 'change_expire_date'],
            ['id' => '81', 'name' => 'do_to_final'],
            ['id' => '82', 'name' => 'quotation_notification'],
            ['id' => '83', 'name' => 'sales_order_notification'],
            ['id' => '84', 'name' => 'do_notification'],
            ['id' => '85', 'name' => 'do_approval_notification'],
            // ['id' => '86', 'name' => 'receive_payment_index'],
            ['id' => '87', 'name' => 'edit_price_sale_screen'],
            ['id' => '88', 'name' => 'edit_discount_sale_screen'],
            // ['id' => '89', 'name' => 'shipment_access'],
            ['id' => '90', 'name' => 'view_product_cost_is_sale_screed'],
            ['id' => '91', 'name' => 'view_own_sale'],
            ['id' => '92', 'name' => 'discounts'],
            ['id' => '93', 'name' => 'pos_all'],
            ['id' => '94', 'name' => 'pos_add'],
            ['id' => '95', 'name' => 'pos_edit'],
            ['id' => '96', 'name' => 'pos_delete'],
            ['id' => '97', 'name' => 'edit_price_pos_screen'],
            ['id' => '98', 'name' => 'edit_discount_pos_screen'],
            ['id' => '99', 'name' => 'pos_sale_settings'],
            ['id' => '100', 'name' => 'view_sales_return'],
            ['id' => '101', 'name' => 'add_sales_return'],
            ['id' => '102', 'name' => 'edit_sales_return'],
            ['id' => '103', 'name' => 'delete_sales_return'],
            ['id' => '104', 'name' => 'sales_report'],
            ['id' => '105', 'name' => 'sale_return_statements'],
            ['id' => '106', 'name' => 'pro_sale_report'],
            // array('id' => '107', 'name' => 'sale_payment_report', 'guard_name' => 'web', 'created_at' => '2022-08-10 12:23:44', 'updated_at' => '2022-08-10 12:23:44'),
            // array('id' => '108', 'name' => 'c_register_report', 'guard_name' => 'web', 'created_at' => '2022-08-10 12:23:44', 'updated_at' => '2022-08-10 12:23:44'),
            // array('id' => '109', 'name' => 'sale_representative_report', 'guard_name' => 'web', 'created_at' => '2022-08-10 12:23:44', 'updated_at' => '2022-08-10 12:23:44'),
            ['id' => '110', 'name' => 'register_view'],
            ['id' => '111', 'name' => 'register_close'],
            ['id' => '112', 'name' => 'another_register_close'],
            ['id' => '113', 'name' => 'loss_profit_report'],
            ['id' => '114', 'name' => 'tax_report'],
            ['id' => '115', 'name' => 'payroll_report'],
            ['id' => '116', 'name' => 'payroll_payment_report'],
            ['id' => '117', 'name' => 'attendance_report'],
            ['id' => '118', 'name' => 'production_report'],
            ['id' => '119', 'name' => 'financial_report'],
            ['id' => '120', 'name' => 'g_settings'],
            ['id' => '121', 'name' => 'p_settings'],
            ['id' => '122', 'name' => 'barcode_settings'],
            ['id' => '123', 'name' => 'about_all'],
            ['id' => '124', 'name' => 'reset'],
            ['id' => '125', 'name' => 'tax'],
            ['id' => '126', 'name' => 'branch'],
            ['id' => '127', 'name' => 'warehouse'],
            ['id' => '128', 'name' => 'inv_sc'],
            ['id' => '129', 'name' => 'inv_lay'],
            ['id' => '130', 'name' => 'cash_counters'],
            ['id' => '131', 'name' => 'dash_data'],
            ['id' => '132', 'name' => 'ac_access'],
            ['id' => '133', 'name' => 'hrm_dashboard'],
            ['id' => '134', 'name' => 'leave_type'],
            ['id' => '135', 'name' => 'leave_assign'],
            ['id' => '136', 'name' => 'shift'],
            ['id' => '137', 'name' => 'attendance'],
            ['id' => '138', 'name' => 'view_allowance_and_deduction'],
            ['id' => '139', 'name' => 'payroll'],
            ['id' => '140', 'name' => 'holiday'],
            ['id' => '141', 'name' => 'department'],
            ['id' => '142', 'name' => 'designation'],
            ['id' => '143', 'name' => 'assign_todo'],
            ['id' => '144', 'name' => 'work_space'],
            ['id' => '145', 'name' => 'memo'],
            ['id' => '146', 'name' => 'msg'],
            ['id' => '147', 'name' => 'process_view'],
            ['id' => '148', 'name' => 'process_add'],
            ['id' => '149', 'name' => 'process_edit'],
            ['id' => '150', 'name' => 'process_delete'],
            ['id' => '151', 'name' => 'production_view'],
            ['id' => '152', 'name' => 'production_add'],
            ['id' => '153', 'name' => 'production_edit'],
            ['id' => '154', 'name' => 'production_delete'],
            ['id' => '155', 'name' => 'manuf_settings'],
            ['id' => '156', 'name' => 'process_report'],
            ['id' => '157', 'name' => 'manuf_report'],
            ['id' => '158', 'name' => 'proj_view'],
            ['id' => '159', 'name' => 'proj_create'],
            ['id' => '160', 'name' => 'proj_edit'],
            ['id' => '161', 'name' => 'proj_delete'],
            ['id' => '162', 'name' => 'ripe_add_invo'],
            ['id' => '163', 'name' => 'ripe_edit_invo'],
            ['id' => '164', 'name' => 'ripe_view_invo'],
            ['id' => '165', 'name' => 'ripe_delete_invo'],
            ['id' => '166', 'name' => 'change_invo_status'],
            ['id' => '167', 'name' => 'ripe_jop_sheet_status'],
            ['id' => '168', 'name' => 'ripe_jop_sheet_add'],
            ['id' => '169', 'name' => 'ripe_jop_sheet_edit'],
            ['id' => '170', 'name' => 'ripe_jop_sheet_delete'],
            ['id' => '171', 'name' => 'ripe_only_assinged_job_sheet'],
            ['id' => '172', 'name' => 'ripe_view_all_job_sheet'],
            ['id' => '173', 'name' => 'superadmin_access_pack_subscrip'],
            ['id' => '174', 'name' => 'e_com_sync_pro_cate'],
            ['id' => '175', 'name' => 'e_com_sync_pro'],
            ['id' => '176', 'name' => 'e_com_sync_order'],
            ['id' => '177', 'name' => 'e_com_map_tax_rate'],
            ['id' => '178', 'name' => 'today_summery'],
            ['id' => '179', 'name' => 'communication'],
            ['id' => '180', 'name' => 'create_requisition'],
            ['id' => '181', 'name' => 'all_requisition'],
            ['id' => '182', 'name' => 'edit_requisition'],
            ['id' => '183', 'name' => 'approve_requisition'],
            ['id' => '184', 'name' => 'delete_requisition'],
            ['id' => '185', 'name' => 'create_po'],
            ['id' => '186', 'name' => 'all_po'],
            ['id' => '187', 'name' => 'edit_po'],
            ['id' => '188', 'name' => 'delete_po'],
            ['id' => '189', 'name' => 'requisition_notification'],
            ['id' => '190', 'name' => 'po_notification'],
            ['id' => '191', 'name' => 'view_purchase_return'],
            ['id' => '192', 'name' => 'add_purchase_return'],
            ['id' => '193', 'name' => 'edit_purchase_return'],
            ['id' => '194', 'name' => 'delete_purchase_return'],
            ['id' => '195', 'name' => 'transfer_wh_to_bl'],
            ['id' => '196', 'name' => 'transfer_bl_wh'],
            ['id' => '197', 'name' => 'transfer_bl_bl'],
            ['id' => '198', 'name' => 'banks'],
            ['id' => '199', 'name' => 'accounts'],
            ['id' => '200', 'name' => 'assets'],
            ['id' => '201', 'name' => 'loans'],
            ['id' => '202', 'name' => 'contra'],
            ['id' => '203', 'name' => 'balance_sheet'],
            ['id' => '204', 'name' => 'trial_balance'],
            ['id' => '205', 'name' => 'cash_flow'],
            ['id' => '206', 'name' => 'profit_loss_ac'],
            ['id' => '207', 'name' => 'daily_profit_loss'],
            ['id' => '208', 'name' => 'notice_board'],
            ['id' => '209', 'name' => 'email'],
            ['id' => '210', 'name' => 'email_settings'],
            ['id' => '211', 'name' => 'sms'],
            ['id' => '212', 'name' => 'sms_settings'],
            ['id' => '213', 'name' => 'media'],
            ['id' => '214', 'name' => 'calender'],
            ['id' => '215', 'name' => 'announcement'],
            ['id' => '216', 'name' => 'activity_log'],
            ['id' => '217', 'name' => 'database_backup'],
            ['id' => '218', 'name' => 'asset_settings'],
            ['id' => '219', 'name' => 'asset_index'],
            ['id' => '220', 'name' => 'asset_create'],
            ['id' => '221', 'name' => 'asset_view'],
            ['id' => '222', 'name' => 'asset_update'],
            ['id' => '223', 'name' => 'asset_delete'],
            ['id' => '224', 'name' => 'asset_components_index'],
            ['id' => '225', 'name' => 'asset_components_create'],
            ['id' => '226', 'name' => 'asset_components_view'],
            ['id' => '227', 'name' => 'asset_components_update'],
            ['id' => '228', 'name' => 'asset_components_delete'],
            ['id' => '229', 'name' => 'asset_allocation_index'],
            ['id' => '230', 'name' => 'asset_allocation_create'],
            ['id' => '231', 'name' => 'asset_allocation_view'],
            ['id' => '232', 'name' => 'asset_allocation_update'],
            ['id' => '233', 'name' => 'asset_allocation_delete'],
            ['id' => '234', 'name' => 'asset_depreciation_index'],
            ['id' => '235', 'name' => 'asset_depreciation_create'],
            ['id' => '236', 'name' => 'asset_depreciation_view'],
            ['id' => '237', 'name' => 'asset_depreciation_update'],
            ['id' => '238', 'name' => 'asset_depreciation_delete'],
            ['id' => '239', 'name' => 'asset_licenses_index'],
            ['id' => '240', 'name' => 'asset_licenses_create'],
            ['id' => '241', 'name' => 'asset_licenses_view'],
            ['id' => '242', 'name' => 'asset_licenses_update'],
            ['id' => '243', 'name' => 'asset_licenses_delete'],
            ['id' => '244', 'name' => 'asset_manufacturer_index'],
            ['id' => '245', 'name' => 'asset_manufacturer_create'],
            ['id' => '246', 'name' => 'asset_manufacturer_view'],
            ['id' => '247', 'name' => 'asset_manufacturer_update'],
            ['id' => '248', 'name' => 'asset_manufacturer_delete'],
            ['id' => '249', 'name' => 'asset_categories_index'],
            ['id' => '250', 'name' => 'asset_categories_create'],
            ['id' => '251', 'name' => 'asset_categories_view'],
            ['id' => '252', 'name' => 'asset_categories_update'],
            ['id' => '253', 'name' => 'asset_categories_delete'],
            ['id' => '254', 'name' => 'asset_locations_index'],
            ['id' => '255', 'name' => 'asset_locations_create'],
            ['id' => '256', 'name' => 'asset_locations_view'],
            ['id' => '257', 'name' => 'asset_locations_update'],
            ['id' => '258', 'name' => 'asset_locations_delete'],
            ['id' => '259', 'name' => 'asset_units_index'],
            ['id' => '260', 'name' => 'asset_units_create'],
            ['id' => '261', 'name' => 'asset_units_view'],
            ['id' => '262', 'name' => 'asset_units_update'],
            ['id' => '263', 'name' => 'asset_units_delete'],
            ['id' => '264', 'name' => 'asset_requests_index'],
            ['id' => '265', 'name' => 'asset_requests_create'],
            ['id' => '266', 'name' => 'asset_requests_view'],
            ['id' => '267', 'name' => 'asset_requests_update'],
            ['id' => '268', 'name' => 'asset_requests_delete'],
            ['id' => '269', 'name' => 'asset_warranties_index'],
            ['id' => '270', 'name' => 'asset_warranties_create'],
            ['id' => '271', 'name' => 'asset_warranties_view'],
            ['id' => '272', 'name' => 'asset_warranties_update'],
            ['id' => '273', 'name' => 'asset_warranties_delete'],
            ['id' => '274', 'name' => 'asset_audits_index'],
            ['id' => '275', 'name' => 'asset_audits_create'],
            ['id' => '276', 'name' => 'asset_audits_view'],
            ['id' => '277', 'name' => 'asset_audits_update'],
            ['id' => '278', 'name' => 'asset_audits_delete'],
            ['id' => '279', 'name' => 'asset_revokes_index'],
            ['id' => '280', 'name' => 'asset_revokes_create'],
            ['id' => '281', 'name' => 'asset_revokes_view'],
            ['id' => '282', 'name' => 'asset_revokes_update'],
            ['id' => '283', 'name' => 'asset_revokes_delete'],
            ['id' => '284', 'name' => 'asset_terms_and_conditions_index'],
            ['id' => '285', 'name' => 'asset_terms_and_conditions_create'],
            ['id' => '286', 'name' => 'asset_terms_and_conditions_view'],
            ['id' => '287', 'name' => 'asset_terms_and_conditions_update'],
            ['id' => '288', 'name' => 'asset_terms_and_conditions_delete'],
            ['id' => '289', 'name' => 'asset_term_condition_categories_index'],
            ['id' => '290', 'name' => 'asset_term_condition_categories_create'],
            ['id' => '291', 'name' => 'asset_term_condition_categories_view'],
            ['id' => '292', 'name' => 'asset_term_condition_categories_update'],
            ['id' => '293', 'name' => 'asset_term_condition_categories_delete'],
            ['id' => '294', 'name' => 'print_invoice'],
            ['id' => '295', 'name' => 'print_challan'],
            ['id' => '296', 'name' => 'print_weight'],
            ['id' => '297', 'name' => 'stock_issue'],
            ['id' => '298', 'name' => 'stock_issue_index'],
            ['id' => '299', 'name' => 'stock_issue_create'],
            ['id' => '300', 'name' => 'stock_issue_view'],
            ['id' => '301', 'name' => 'stock_issue_update'],
            ['id' => '302', 'name' => 'stock_issue_delete'],
            ['id' => '303', 'name' => 'daily_stock'],
            ['id' => '304', 'name' => 'daily_stock_index'],
            ['id' => '305', 'name' => 'daily_stock_create'],
            ['id' => '306', 'name' => 'daily_stock_view'],
            ['id' => '307', 'name' => 'daily_stock_update'],
            ['id' => '308', 'name' => 'daily_stock_delete'],
            ['id' => '309', 'name' => 'daily_stock_report'],
            ['id' => '310', 'name' => 'stock_out_report'],
            ['id' => '311', 'name' => 'opening_lc'],
            ['id' => '312', 'name' => 'opening_lc_index'],
            ['id' => '313', 'name' => 'opening_lc_create'],
            ['id' => '314', 'name' => 'opening_lc_view'],
            ['id' => '315', 'name' => 'opening_lc_update'],
            ['id' => '316', 'name' => 'opening_lc_delete'],
            ['id' => '317', 'name' => 'import_purchase_order'],
            ['id' => '318', 'name' => 'import_purchase_order_index'],
            ['id' => '319', 'name' => 'import_purchase_order_create'],
            ['id' => '320', 'name' => 'import_purchase_order_view'],
            ['id' => '321', 'name' => 'import_purchase_order_update'],
            ['id' => '322', 'name' => 'import_purchase_order_delete'],
            ['id' => '323', 'name' => 'exporters'],
            ['id' => '324', 'name' => 'exporters_index'],
            ['id' => '325', 'name' => 'exporters_create'],
            ['id' => '326', 'name' => 'exporters_view'],
            ['id' => '327', 'name' => 'exporters_update'],
            ['id' => '328', 'name' => 'exporters_delete'],
            ['id' => '329', 'name' => 'insurance_companies'],
            ['id' => '330', 'name' => 'insurance_companies_index'],
            ['id' => '331', 'name' => 'insurance_companies_create'],
            ['id' => '332', 'name' => 'insurance_companies_view'],
            ['id' => '333', 'name' => 'insurance_companies_update'],
            ['id' => '334', 'name' => 'insurance_companies_delete'],
            ['id' => '335', 'name' => 'cnf_agents'],
            ['id' => '336', 'name' => 'cnf_agents_index'],
            ['id' => '337', 'name' => 'cnf_agents_create'],
            ['id' => '338', 'name' => 'cnf_agents_view'],
            ['id' => '339', 'name' => 'cnf_agents_update'],
            ['id' => '340', 'name' => 'cnf_agents_delete'],
            ['id' => '341', 'name' => 'sales_order_report'],
            ['id' => '342', 'name' => 'ordered_item_report'],
            ['id' => '343', 'name' => 'do_vs_sales_report'],
            ['id' => '344', 'name' => 'add_new_recent_price'],
            ['id' => '345', 'name' => 'all_previous_recent_price'],
            ['id' => '346', 'name' => 'today_recent_price'],
            ['id' => '347', 'name' => 'price_update_notification'],
            // array('id' => '348', 'name' => 'receive_payment_create', 'guard_name' => 'web', 'created_at' => '2022-09-07 11:18:15', 'updated_at' => '2022-09-07 11:18:15'),
            // array('id' => '349', 'name' => 'receive_payment_view', 'guard_name' => 'web', 'created_at' => '2022-09-09 18:22:28', 'updated_at' => '2022-09-09 18:22:28'),
            // array('id' => '350', 'name' => 'receive_payment_update', 'guard_name' => 'web', 'created_at' => '2022-09-09 18:22:28', 'updated_at' => '2022-09-09 18:22:28'),
            // array('id' => '351', 'name' => 'receive_payment_delete', 'guard_name' => 'web', 'created_at' => '2022-09-09 18:22:28', 'updated_at' => '2022-09-09 18:22:28'),
            // array('id' => '352', 'name' => 'purchase_payment_create', 'guard_name' => 'web', 'created_at' => '2022-09-09 18:22:28', 'updated_at' => '2022-09-09 18:22:28'),
            // array('id' => '353', 'name' => 'purchase_payment_view', 'guard_name' => 'web', 'created_at' => '2022-09-09 18:25:56', 'updated_at' => '2022-09-09 18:25:56'),
            // array('id' => '354', 'name' => 'purchase_payment_update', 'guard_name' => 'web', 'created_at' => '2022-09-09 18:22:28', 'updated_at' => '2022-09-09 18:22:28'),
            // array('id' => '355', 'name' => 'purchase_payment_delete', 'guard_name' => 'web', 'created_at' => '2022-09-09 18:25:56', 'updated_at' => '2022-09-09 18:25:56'),
            ['id' => '356', 'name' => 'customer_manage'],
            ['id' => '357', 'name' => 'customer_payment_receive_voucher'],
            ['id' => '358', 'name' => 'customer_status_change'],
            ['id' => '359', 'name' => 'asset_licenses_categories_index'],
            ['id' => '360', 'name' => 'asset_licenses_categories_create'],
            ['id' => '361', 'name' => 'asset_licenses_categories_view'],
            ['id' => '362', 'name' => 'asset_licenses_categories_update'],
            ['id' => '363', 'name' => 'asset_licenses_categories_delete'],
            ['id' => '364', 'name' => 'feedback_index'],
            ['id' => '365', 'name' => 'website_link'],
            ['id' => '366', 'name' => 'hrm_menu'],
            ['id' => '367', 'name' => 'modules_page'],
            ['id' => '368', 'name' => 'purchase_by_scale_index'],
            ['id' => '369', 'name' => 'purchase_by_scale_create'],
            ['id' => '370', 'name' => 'purchase_by_scale_view'],
            ['id' => '371', 'name' => 'purchase_by_scale_update'],
            ['id' => '372', 'name' => 'purchase_by_scale_delete'],
            ['id' => '373', 'name' => 'stock_issue_report'],
            ['id' => '374', 'name' => 'stock_issued_items_report'],
            ['id' => '375', 'name' => 'requested_product_report'],
            ['id' => '376', 'name' => 'weighted_product_report'],
            ['id' => '377', 'name' => 'incomes_index'],
            ['id' => '378', 'name' => 'incomes_show'],
            ['id' => '379', 'name' => 'incomes_create'],
            ['id' => '380', 'name' => 'incomes_edit'],
            ['id' => '381', 'name' => 'incomes_delete'],
            ['id' => '382', 'name' => 'income_report'],

            ['id' => '383', 'name' => 'hrm_departments_index'],
            ['id' => '384', 'name' => 'hrm_departments_create'],
            ['id' => '385', 'name' => 'hrm_departments_view'],
            ['id' => '386', 'name' => 'hrm_departments_update'],
            ['id' => '387', 'name' => 'hrm_departments_delete'],

            ['id' => '388', 'name' => 'hrm_settings_index'],
            ['id' => '389', 'name' => 'hrm_settings_create'],
            ['id' => '390', 'name' => 'hrm_settings_view'],
            ['id' => '391', 'name' => 'hrm_settings_update'],
            ['id' => '392', 'name' => 'hrm_settings_delete'],

            ['id' => '393', 'name' => 'hrm_grades_index'],
            ['id' => '394', 'name' => 'hrm_grades_create'],
            ['id' => '395', 'name' => 'hrm_grades_view'],
            ['id' => '396', 'name' => 'hrm_grades_update'],
            ['id' => '397', 'name' => 'hrm_grades_delete'],

            ['id' => '398', 'name' => 'hrm_leave_types_index'],
            ['id' => '399', 'name' => 'hrm_leave_types_create'],
            ['id' => '400', 'name' => 'hrm_leave_types_view'],
            ['id' => '401', 'name' => 'hrm_leave_types_update'],
            ['id' => '402', 'name' => 'hrm_leave_types_delete'],

            ['id' => '403', 'name' => 'hrm_sections_index'],
            ['id' => '404', 'name' => 'hrm_sections_create'],
            ['id' => '405', 'name' => 'hrm_sections_view'],
            ['id' => '406', 'name' => 'hrm_sections_update'],
            ['id' => '407', 'name' => 'hrm_sections_delete'],

            ['id' => '408', 'name' => 'hrm_sub_sections_index'],
            ['id' => '409', 'name' => 'hrm_sub_sections_create'],
            ['id' => '410', 'name' => 'hrm_sub_sections_view'],
            ['id' => '411', 'name' => 'hrm_sub_sections_update'],
            ['id' => '412', 'name' => 'hrm_sub_sections_delete'],

            ['id' => '413', 'name' => 'hrm_designations_index'],
            ['id' => '414', 'name' => 'hrm_designations_create'],
            ['id' => '415', 'name' => 'hrm_designations_view'],
            ['id' => '416', 'name' => 'hrm_designations_update'],
            ['id' => '417', 'name' => 'hrm_designations_delete'],

            ['id' => '418', 'name' => 'hrm_shifts_index'],
            ['id' => '419', 'name' => 'hrm_shifts_create'],
            ['id' => '420', 'name' => 'hrm_shifts_view'],
            ['id' => '421', 'name' => 'hrm_shifts_update'],
            ['id' => '422', 'name' => 'hrm_shifts_delete'],

            ['id' => '423', 'name' => 'receive_stocks_index'],
            ['id' => '424', 'name' => 'receive_stocks_view'],
            ['id' => '425', 'name' => 'receive_stocks_create'],
            ['id' => '426', 'name' => 'receive_stocks_update'],
            ['id' => '427', 'name' => 'receive_stocks_delete'],

            ['id' => '428', 'name' => 'hrm_divisions_create'],
            ['id' => '429', 'name' => 'hrm_divisions_view'],
            ['id' => '430', 'name' => 'hrm_divisions_update'],
            ['id' => '431', 'name' => 'hrm_divisions_delete'],
            ['id' => '432', 'name' => 'hrm_divisions_index'],

            ['id' => '433', 'name' => 'hrm_thana_index'],
            ['id' => '434', 'name' => 'hrm_thana_create'],
            ['id' => '435', 'name' => 'hrm_thana_view'],
            ['id' => '436', 'name' => 'hrm_thana_update'],
            ['id' => '437', 'name' => 'hrm_thana_delete'],

            ['id' => '438', 'name' => 'hrm_union_index'],
            ['id' => '439', 'name' => 'hrm_union_create'],
            ['id' => '440', 'name' => 'hrm_union_view'],
            ['id' => '441', 'name' => 'hrm_union_update'],
            ['id' => '442', 'name' => 'hrm_union_delete'],

            ['id' => '443', 'name' => 'hrm_districts_index'],
            ['id' => '444', 'name' => 'hrm_districts_create'],
            ['id' => '445', 'name' => 'hrm_districts_view'],
            ['id' => '446', 'name' => 'hrm_districts_update'],
            ['id' => '447', 'name' => 'hrm_districts_delete'],

            ['id' => '448', 'name' => 'hrm_holidays_calendar_index'],
            ['id' => '449', 'name' => 'hrm_holidays_calendar_create'],
            ['id' => '450', 'name' => 'hrm_holidays_calendar_view'],
            ['id' => '451', 'name' => 'hrm_holidays_calendar_update'],
            ['id' => '452', 'name' => 'hrm_holidays_calendar_delete'],

            ['id' => '453', 'name' => 'hrm_shift_adjustments_index'],
            ['id' => '454', 'name' => 'hrm_shift_adjustments_create'],
            ['id' => '455', 'name' => 'hrm_shift_adjustments_view'],
            ['id' => '456', 'name' => 'hrm_shift_adjustments_update'],
            ['id' => '457', 'name' => 'hrm_shift_adjustments_delete'],

            ['id' => 458, 'name' => 'crm_business_leads_index'],
            ['id' => 459, 'name' => 'crm_business_leads_create'],
            ['id' => 460, 'name' => 'crm_business_leads_view'],
            ['id' => 461, 'name' => 'crm_business_leads_update'],
            ['id' => 462, 'name' => 'crm_business_leads_delete'],

            ['id' => 463, 'name' => 'crm_individual_leads_index'],
            ['id' => 464, 'name' => 'crm_individual_leads_create'],
            ['id' => 465, 'name' => 'crm_individual_leads_view'],
            ['id' => 466, 'name' => 'crm_individual_leads_update'],
            ['id' => 467, 'name' => 'crm_individual_leads_delete'],

            ['id' => 468, 'name' => 'crm_followup_category_index'],
            ['id' => 469, 'name' => 'crm_followup_category_create'],
            ['id' => 470, 'name' => 'crm_followup_category_view'],
            ['id' => 471, 'name' => 'crm_followup_category_update'],
            ['id' => 472, 'name' => 'crm_followup_category_delete'],

            ['id' => 473, 'name' => 'crm_followup_index'],
            ['id' => 474, 'name' => 'crm_followup_create'],
            ['id' => 475, 'name' => 'crm_followup_view'],
            ['id' => 476, 'name' => 'crm_followup_update'],
            ['id' => 477, 'name' => 'crm_followup_delete'],

            ['id' => 478, 'name' => 'crm_sources_index'],
            ['id' => 479, 'name' => 'crm_sources_create'],
            ['id' => 480, 'name' => 'crm_sources_view'],
            ['id' => 481, 'name' => 'crm_sources_update'],
            ['id' => 482, 'name' => 'crm_sources_delete'],

            ['id' => 483, 'name' => 'crm_proposals_template_index'],
            ['id' => 484, 'name' => 'crm_proposals_template_create'],
            ['id' => 485, 'name' => 'crm_proposals_template_view'],
            ['id' => 486, 'name' => 'crm_proposals_template_update'],
            ['id' => 487, 'name' => 'crm_proposals_template_delete'],

            ['id' => 488, 'name' => 'crm_proposals_index'],
            ['id' => 489, 'name' => 'crm_proposals_create'],
            ['id' => 490, 'name' => 'crm_proposals_view'],
            ['id' => 491, 'name' => 'crm_proposals_update'],
            ['id' => 492, 'name' => 'crm_proposals_delete'],

            ['id' => 493, 'name' => 'crm_appointments_index'],
            ['id' => 494, 'name' => 'crm_appointments_create'],
            ['id' => 495, 'name' => 'crm_appointments_view'],
            ['id' => 496, 'name' => 'crm_appointments_update'],
            ['id' => 497, 'name' => 'crm_appointments_delete'],

            ['id' => 498, 'name' => 'crm_settings_index'],
            ['id' => 499, 'name' => 'crm_settings_create'],
            ['id' => 500, 'name' => 'crm_settings_view'],
            ['id' => 501, 'name' => 'crm_settings_update'],
            ['id' => 502, 'name' => 'crm_settings_delete'],

            ['id' => 503, 'name' => 'hrm_employees_index'],
            ['id' => 504, 'name' => 'hrm_employees_create'],
            ['id' => 505, 'name' => 'hrm_employees_view'],
            ['id' => 506, 'name' => 'hrm_employees_update'],
            ['id' => 507, 'name' => 'hrm_employees_delete'],

            ['id' => 508, 'name' => 'manage_sr_index'],
            ['id' => 509, 'name' => 'manage_sr_manage'],
            ['id' => 510, 'name' => 'manage_sr_create'],
            ['id' => 511, 'name' => 'manage_sr_edit'],
            ['id' => 512, 'name' => 'manage_sr_delete'],

            ['id' => 513, 'name' => 'index_weight_scale'],
            ['id' => 514, 'name' => 'single_view_weight_scale'],
            ['id' => 515, 'name' => 'add_weight_scale'],
            ['id' => 516, 'name' => 'delete_weight_scale'],
            ['id' => 517, 'name' => 'index_weight_scale_client'],
            ['id' => 518, 'name' => 'add_weight_scale_client'],
            ['id' => 519, 'name' => 'edit_weight_scale_client'],
            ['id' => 520, 'name' => 'delete_weight_scale_client'],

            ['id' => 521, 'name' => 'hrm_leave_applications_index'],
            ['id' => 522, 'name' => 'hrm_leave_applications_create'],
            ['id' => 523, 'name' => 'hrm_leave_applications_view'],
            ['id' => 524, 'name' => 'hrm_leave_applications_update'],
            ['id' => 525, 'name' => 'hrm_leave_applications_delete'],

            ['id' => 526, 'name' => 'banks_index'],
            ['id' => 527, 'name' => 'banks_add'],
            ['id' => 528, 'name' => 'banks_edit'],
            ['id' => 529, 'name' => 'banks_delete'],

            ['id' => 530, 'name' => 'account_groups_index'],
            ['id' => 531, 'name' => 'account_groups_add'],
            ['id' => 532, 'name' => 'account_groups_edit'],
            ['id' => 533, 'name' => 'account_groups_delete'],

            ['id' => 534, 'name' => 'accounts_index'],
            ['id' => 535, 'name' => 'accounts_ledger'],
            ['id' => 536, 'name' => 'accounts_add'],
            ['id' => 537, 'name' => 'accounts_edit'],
            ['id' => 538, 'name' => 'accounts_delete'],

            ['id' => 539, 'name' => 'chart_of_accounts_index'],

            ['id' => 540, 'name' => 'receipts_index'],
            ['id' => 541, 'name' => 'receipts_add'],
            ['id' => 542, 'name' => 'receipts_edit'],
            ['id' => 543, 'name' => 'receipts_delete'],

            ['id' => 544, 'name' => 'payments_index'],
            ['id' => 545, 'name' => 'payments_add'],
            ['id' => 546, 'name' => 'payments_edit'],
            ['id' => 547, 'name' => 'payments_delete'],

            ['id' => 548, 'name' => 'journals_index'],
            ['id' => 549, 'name' => 'journals_add'],
            ['id' => 550, 'name' => 'journals_edit'],
            ['id' => 551, 'name' => 'journals_delete'],

            ['id' => 552, 'name' => 'contras_index'],
            ['id' => 553, 'name' => 'contras_add'],
            ['id' => 554, 'name' => 'contras_edit'],
            ['id' => 555, 'name' => 'contras_delete'],

            ['id' => 556, 'name' => 'hrm_el_payments_index'],
            ['id' => 557, 'name' => 'hrm_el_payments_create'],
            ['id' => 558, 'name' => 'hrm_el_payments_view'],
            ['id' => 559, 'name' => 'hrm_el_payments_update'],
            ['id' => 560, 'name' => 'hrm_el_payments_delete'],

            ['id' => 561, 'name' => 'hrm_payments_types_index'],
            ['id' => 562, 'name' => 'hrm_payments_types_create'],
            ['id' => 563, 'name' => 'hrm_payments_types_view'],
            ['id' => 564, 'name' => 'hrm_payments_types_update'],
            ['id' => 565, 'name' => 'hrm_payments_types_delete'],

            ['id' => 566, 'name' => 'hrm_attendance_index'],
            ['id' => 567, 'name' => 'hrm_attendance_create'],
            ['id' => 568, 'name' => 'hrm_attendance_view'],
            ['id' => 569, 'name' => 'hrm_attendance_update'],
            ['id' => 570, 'name' => 'hrm_attendance_delete'],
            ['id' => 571, 'name' => 'hrm_bulk_appointment_letter_index'],
            ['id' => 572, 'name' => 'hrm_master_list_index'],

            ['id' => 573, 'name' => 'hrm_promotion_index'],
            ['id' => 574, 'name' => 'hrm_promotion_create'],
            ['id' => 575, 'name' => 'hrm_promotion_view'],
            ['id' => 576, 'name' => 'hrm_promotion_update'],
            ['id' => 577, 'name' => 'hrm_promotion_delete'],

            ['id' => 578, 'name' => 'hrm_visit_index'],
            ['id' => 579, 'name' => 'hrm_visit_create'],
            ['id' => 580, 'name' => 'hrm_visit_view'],
            ['id' => 581, 'name' => 'hrm_visit_update'],
            ['id' => 582, 'name' => 'hrm_visit_delete'],

            ['id' => 583, 'name' => 'website_clients_index'],
            ['id' => 584, 'name' => 'website_clients_create'],
            ['id' => 585, 'name' => 'website_clients_view'],
            ['id' => 586, 'name' => 'website_clients_update'],
            ['id' => 587, 'name' => 'website_clients_delete'],

            //report
            ['id' => 588, 'name' => 'hrm_leave_application_report'],
            ['id' => 589, 'name' => 'hrm_salaryAdjustment_report'],

            ['id' => 590, 'name' => 'fund_flow'],
            ['id' => 591, 'name' => 'day_book'],
            ['id' => 592, 'name' => 'outstanding_receivables'],
            ['id' => 593, 'name' => 'outstanding_payables'],

            ['id' => 594, 'name' => 'cost_centres_index'],
            ['id' => 595, 'name' => 'cost_centres_add'],
            ['id' => 596, 'name' => 'cost_centres_edit'],
            ['id' => 597, 'name' => 'cost_centres_delete'],
            ['id' => 598, 'name' => 'cost_centre_categories_add'],
            ['id' => 599, 'name' => 'cost_centre_categories_edit'],
            ['id' => 600, 'name' => 'cost_centre_categories_delete'],

            ['id' => 601, 'name' => 'hrm_overtimeAdjustments_index'],
            ['id' => 602, 'name' => 'hrm_overtimeAdjustments_create'],
            ['id' => 603, 'name' => 'hrm_overtimeAdjustments_view'],
            ['id' => 604, 'name' => 'hrm_overtimeAdjustments_update'],
            ['id' => 605, 'name' => 'hrm_overtimeAdjustments_delete'],

            ['id' => 606, 'name' => 'hrm_employeeTaxAdjustments_index'],
            ['id' => 607, 'name' => 'hrm_employeeTaxAdjustments_create'],
            ['id' => 608, 'name' => 'hrm_employeeTaxAdjustments_view'],
            ['id' => 609, 'name' => 'hrm_employeeTaxAdjustments_update'],
            ['id' => 610, 'name' => 'hrm_employeeTaxAdjustments_delete'],

            ['id' => 611, 'name' => 'hrm_awards_index'],
            ['id' => 612, 'name' => 'hrm_awards_create'],
            ['id' => 613, 'name' => 'hrm_awards_view'],
            ['id' => 614, 'name' => 'hrm_awards_update'],
            ['id' => 615, 'name' => 'hrm_awards_delete'],

            ['id' => 616, 'name' => 'hrm_salaryAdjustments_index'],
            ['id' => 617, 'name' => 'hrm_salaryAdjustments_create'],
            ['id' => 618, 'name' => 'hrm_salaryAdjustments_view'],
            ['id' => 619, 'name' => 'hrm_salaryAdjustments_update'],
            ['id' => 620, 'name' => 'hrm_salaryAdjustments_delete'],

            // salary statement permission neither use yet
            ['id' => 621, 'name' => 'hrm_salary_settlement_index'],
            ['id' => 622, 'name' => 'hrm_salary_settlement_create'],
            ['id' => 623, 'name' => 'hrm_salary_settlement_view'],
            ['id' => 624, 'name' => 'hrm_salary_settlement'],
            ['id' => 625, 'name' => 'hrm_salary_settlement_delete'],

            ['id' => 626, 'name' => 'hrm_salary_advances_index'],
            ['id' => 627, 'name' => 'hrm_salary_advances_create'],
            ['id' => 628, 'name' => 'hrm_salary_advances_view'],
            ['id' => 629, 'name' => 'hrm_salary_advances_update'],
            ['id' => 630, 'name' => 'hrm_salary_advances_delete'],

            ['id' => 631, 'name' => 'hrm_notice_index'],
            ['id' => 632, 'name' => 'hrm_notice_create'],
            ['id' => 633, 'name' => 'hrm_notice_view'],
            ['id' => 634, 'name' => 'hrm_notice_update'],
            ['id' => 635, 'name' => 'hrm_notice_delete'],

            // Website permission
            ['id' => 636, 'name' => 'web_manage_client'],
            ['id' => 637, 'name' => 'web_add_client'],
            ['id' => 638, 'name' => 'web_edit_client'],
            ['id' => 639, 'name' => 'web_delete_client'],

            ['id' => 640, 'name' => 'web_requisition_show'],
            ['id' => 641, 'name' => 'web_requisition_delete'],

            ['id' => 642, 'name' => 'web_manage_partner'],
            ['id' => 643, 'name' => 'web_add_partner'],
            ['id' => 644, 'name' => 'web_edit_partner'],
            ['id' => 645, 'name' => 'web_delete_partner'],

            ['id' => 646, 'name' => 'web_manage_team'],
            ['id' => 647, 'name' => 'web_add_team'],
            ['id' => 648, 'name' => 'web_edit_team'],
            ['id' => 649, 'name' => 'web_delete_team'],

            ['id' => 650, 'name' => 'web_manage_category'],
            ['id' => 651, 'name' => 'web_add_category'],
            ['id' => 652, 'name' => 'web_edit_category'],
            ['id' => 653, 'name' => 'web_delete_category'],

            ['id' => 654, 'name' => 'web_manage_product'],
            ['id' => 655, 'name' => 'web_add_product'],
            ['id' => 656, 'name' => 'web_edit_product'],
            ['id' => 657, 'name' => 'web_delete_product'],

            ['id' => 658, 'name' => 'web_manage_job_category'],
            ['id' => 659, 'name' => 'web_add_job_category'],
            ['id' => 660, 'name' => 'web_edit_job_category'],
            ['id' => 661, 'name' => 'web_delete_job_category'],

            ['id' => 662, 'name' => 'web_manage_job'],
            ['id' => 663, 'name' => 'web_add_job'],
            ['id' => 664, 'name' => 'web_edit_job'],
            ['id' => 665, 'name' => 'web_delete_job'],

            ['id' => 666, 'name' => 'web_job_applied_download'],
            ['id' => 667, 'name' => 'web_job_applied_delete'],

            ['id' => 668, 'name' => 'web_manage_gallery_category'],
            ['id' => 669, 'name' => 'web_add_gallery_category'],
            ['id' => 670, 'name' => 'web_edit_gallery_category'],
            ['id' => 671, 'name' => 'web_delete_gallery_category'],

            ['id' => 672, 'name' => 'web_manage_gallery'],
            ['id' => 673, 'name' => 'web_add_gallery'],
            ['id' => 674, 'name' => 'web_edit_gallery'],
            ['id' => 675, 'name' => 'web_delete_gallery'],

            ['id' => 676, 'name' => 'web_manage_blog_category'],
            ['id' => 677, 'name' => 'web_add_blog_category'],
            ['id' => 678, 'name' => 'web_edit_blog_category'],
            ['id' => 679, 'name' => 'web_delete_blog_category'],

            ['id' => 680, 'name' => 'web_manage_blog'],
            ['id' => 681, 'name' => 'web_add_blog'],
            ['id' => 682, 'name' => 'web_edit_blog'],
            ['id' => 683, 'name' => 'web_delete_blog'],

            ['id' => 684, 'name' => 'web_manage_comment'],
            ['id' => 685, 'name' => 'web_edit_comment'],
            ['id' => 686, 'name' => 'web_delete_comment'],

            ['id' => 687, 'name' => 'web_manage_page'],
            ['id' => 688, 'name' => 'web_add_page'],
            ['id' => 689, 'name' => 'web_edit_page'],
            ['id' => 690, 'name' => 'web_delete_page'],
            ['id' => 691, 'name' => 'web_about_us'],
            ['id' => 692, 'name' => 'web_history'],
            ['id' => 693, 'name' => 'web_message_of_director'],

            ['id' => 694, 'name' => 'web_manage_testimonial'],
            ['id' => 695, 'name' => 'web_add_testimonial'],
            ['id' => 696, 'name' => 'web_edit_testimonial'],
            ['id' => 697, 'name' => 'web_delete_testimonial'],

            ['id' => 698, 'name' => 'web_manage_campaign'],
            ['id' => 699, 'name' => 'web_add_campaign'],
            ['id' => 700, 'name' => 'web_edit_campaign'],
            ['id' => 701, 'name' => 'web_delete_campaign'],

            ['id' => 702, 'name' => 'web_manage_faq'],
            ['id' => 703, 'name' => 'web_add_faq'],
            ['id' => 704, 'name' => 'web_edit_faq'],
            ['id' => 705, 'name' => 'web_delete_faq'],

            ['id' => 706, 'name' => 'web_manage_buet_test'],
            ['id' => 707, 'name' => 'web_add_buet_test'],
            ['id' => 708, 'name' => 'web_edit_buet_test'],
            ['id' => 709, 'name' => 'web_delete_buet_test'],

            ['id' => 710, 'name' => 'web_manage_dealership_requests'],
            ['id' => 711, 'name' => 'web_delete_dealership_request'],

            ['id' => 712, 'name' => 'web_manage_slider'],
            ['id' => 713, 'name' => 'web_add_slider'],
            ['id' => 714, 'name' => 'web_edit_slider'],
            ['id' => 715, 'name' => 'web_delete_slider'],

            ['id' => 716, 'name' => 'web_manage_video'],
            ['id' => 717, 'name' => 'web_add_video'],
            ['id' => 718, 'name' => 'web_edit_video'],
            ['id' => 719, 'name' => 'web_delete_video'],

            ['id' => 720, 'name' => 'web_manage_country'],
            ['id' => 721, 'name' => 'web_add_country'],
            ['id' => 722, 'name' => 'web_edit_country'],
            ['id' => 723, 'name' => 'web_delete_country'],

            ['id' => 724, 'name' => 'web_manage_city'],
            ['id' => 725, 'name' => 'web_add_city'],
            ['id' => 726, 'name' => 'web_edit_city'],
            ['id' => 727, 'name' => 'web_delete_city'],

            ['id' => 728, 'name' => 'general_setting'],
            ['id' => 729, 'name' => 'seo'],
            ['id' => 730, 'name' => 'social_link'],
            ['id' => 731, 'name' => 'banner'],
            ['id' => 732, 'name' => 'contact'],
            // HRM Person Wise Attendance Permission
            ['id' => 733, 'name' => 'hrm_person_wise_attendance'],
            ['id' => 734, 'name' => 'hrm_person_wise_attendance_create'],
            ['id' => 735, 'name' => 'hrm_person_wise_attendance_edit'],
            ['id' => 736, 'name' => 'hrm_person_wise_attendance_update'],
            ['id' => 737, 'name' => 'hrm_person_wise_attendance_delete'],
            ['id' => 738, 'name' => 'hrm_person_wise_attendance_show'],
            ['id' => 739, 'name' => 'hrm_person_wise_attendance_index'],

            ['id' => 740, 'name' => 'hrm_bulk_attendance_import_index'],
            ['id' => 741, 'name' => 'hrm_bulk_attendance_import_text_file'],
            ['id' => 742, 'name' => 'hrm_daily_attendance_custom_pdf'],
            ['id' => 743, 'name' => 'hrm_daily_attendance_custom_excel'],

            ['id' => 744, 'name' => 'hrm_attendance_log_update'],
            ['id' => 745, 'name' => 'hrm_attendance_log_view'],

            ['id' => 746, 'name' => 'hrm_daily_attendance_index'],
            ['id' => 747, 'name' => 'hrm_daily_attendance_view'],
            ['id' => 748, 'name' => 'hrm_daily_attendance_update'],
            // HRM Section Wise Attendance Permission
            ['id' => 749, 'name' => 'hrm_section_wise_attendance'],
            ['id' => 750, 'name' => 'hrm_section_wise_attendance_store'],

            // HRM Attendance Log Permission
            ['id' => 751, 'name' => 'hrm_attendance_log_index'],
            // HRM Daily Attendance Report Permission
            ['id' => 752, 'name' => 'hrm_daily_attendance_report_index'],

            // HRM Absent Report Permission
            ['id' => 753, 'name' => 'hrm_absent_report'],
            // HRM Rapid Update Attendance Permission
            ['id' => 754, 'name' => 'hrm_attendance_rapid_update'],
            ['id' => 755, 'name' => 'hrm_attendance_rapid_update_employee_wise'],
            ['id' => 756, 'name' => 'hrm_attendance_rapid_update_date_wise'],
            // HRM Job Card Permission
            ['id' => 757, 'name' => 'hrm_attendance_job_card'],
            ['id' => 758, 'name' => 'hrm_attendance_job_card_print'],
            ['id' => 759, 'name' => 'hrm_attendance_job_card_calculate'],
            // HRM Date Range Absent Checker Permission
            ['id' => 760, 'name' => 'hrm_date_range_absent_checker'],
            // HRM EL Calculation Permission
            ['id' => 761, 'name' => 'hrm_el_calculation_index'],
            // HRM EL Calculation Permission
            ['id' => 762, 'name' => 'hrm_el_calculation_view'],
            // HRM Job Card Summery Calculation Permission
            ['id' => 763, 'name' => 'hrm_attendance_job_summary_print'],
            ['id' => 764, 'name' => 'hrm_attendance_job_summary_view'],
            // HRM Leave Register Permission
            ['id' => 765, 'name' => 'hrm_leave_register_view'],
            // HRM Leave Permission
            ['id' => 766, 'name' => 'hrm_leave_view'],
            // HRM Organogram Permission
            ['id' => 767, 'name' => 'hrm_organogram_index'],
            ['id' => 768, 'name' => 'hrm_final_settlement_index'],

            ['id' => 769, 'name' => 'hrm_interview_index'],
            ['id' => 770, 'name' => 'hrm_interview_create'],
            ['id' => 771, 'name' => 'hrm_interview_show'],
            ['id' => 772, 'name' => 'hrm_interview_update'],
            ['id' => 773, 'name' => 'hrm_interview_delete'],

            // New 16.06.2023
            ['id' => 774, 'name' => 'sr_wise_order_report'],
            ['id' => 775, 'name' => 'ordered_item_qty_report'],
            ['id' => 776, 'name' => 'do_report'],
            ['id' => 777, 'name' => 'sales_return_report'],
            ['id' => 778, 'name' => 'sales_returned_items_report'],

            // New 17.06.2023
            ['id' => 779, 'name' => 'received_stocks_report'],
            ['id' => 780, 'name' => 'purchase_return_report'],
            ['id' => 781, 'name' => 'purchase_returned_items_report'],
            // calculation checker
            ['id' => 782, 'name' => 'hrm_calculation_checker_jobVsSalary'],
            ['id' => 783, 'name' => 'hrm_calculation_checker_summaryVsSalary'],
            ['id' => 784, 'name' => 'hrm_calculation_checker_allCalculation'],

            ['id' => 785, 'name' => 'hrm_payroll_index'],
            ['id' => 786, 'name' => 'hrm_payroll_salary_generate'],
            ['id' => 787, 'name' => 'hrm_payroll_payslip_generate'],
            ['id' => 788, 'name' => 'hrm_payroll_custom_excel'],

            // other employee permission
            ['id' => 789, 'name' => 'hrm_employees_bulk_import_index'],
            ['id' => 790, 'name' => 'hrm_bulk_attendance_import_store'],
            ['id' => 791, 'name' => 'hrm_employees_bulk_import_store'],
            ['id' => 792, 'name' => 'hrm_id_card_print_index'],
            ['id' => 793, 'name' => 'hrm_id_card_print'],
            ['id' => 794, 'name' => 'hrm_appointment_letter_index'],
            ['id' => 795, 'name' => 'hrm_appointment_letter_print'],
            ['id' => 796, 'name' => 'hrm_appointment_with_select_letter_index'],
            ['id' => 797, 'name' => 'hrm_appointment_with_select_letter_print'],
            ['id' => 798, 'name' => 'hrm_shift_change_index'],
            ['id' => 799, 'name' => 'hrm_shift_change_action'],
            ['id' => 800, 'name' => 'hrm_resigned_employee_index'],
            ['id' => 801, 'name' => 'hrm_left_employee_index'],
            ['id' => 802, 'name' => 'hrm_trashed_employee_index'],
            //first reference index no 768
            ['id' => 803, 'name' => 'hrm_final_settlement_action'],
            // holiday manage
            ['id' => 804, 'name' => 'hrm_holidays_index'],
            ['id' => 805, 'name' => 'hrm_holidays_create'],
            ['id' => 806, 'name' => 'hrm_holidays_view'],
            ['id' => 807, 'name' => 'hrm_holidays_update'],
            ['id' => 808, 'name' => 'hrm_holidays_delete'],
            //Recruitment HRM
            ['id' => 809, 'name' => 'hrm_recruitment_job_onboarding_index'],
            ['id' => 810, 'name' => 'hrm_recruitment_job_onboarding_view'],
            ['id' => 811, 'name' => 'hrm_recruitment_job_onboarding_download'],
            ['id' => 812, 'name' => 'hrm_recruitment_job_onboarding_delete'],
            ['id' => 813, 'name' => 'hrm_recruitment_job_onboarding_bulk_select_for_interview'],
            ['id' => 814, 'name' => 'hrm_recruitment_select_for_interview_index'],
            ['id' => 815, 'name' => 'hrm_recruitment_select_for_interview_view'],
            ['id' => 816, 'name' => 'hrm_recruitment_select_for_interview_download'],
            ['id' => 817, 'name' => 'hrm_recruitment_select_for_interview_delete'],
            ['id' => 818, 'name' => 'hrm_recruitment_select_for_interview_bulk_mail_sent'],
            ['id' => 819, 'name' => 'hrm_recruitment_select_for_interview_select_email_format'],
            ['id' => 820, 'name' => 'hrm_recruitment_already_mail_for_interview_index'],
            ['id' => 821, 'name' => 'hrm_recruitment_already_mail_for_interview_view'],
            ['id' => 822, 'name' => 'hrm_recruitment_already_mail_for_interview_download'],
            ['id' => 823, 'name' => 'hrm_recruitment_bulk_select_interview_participants'],
            ['id' => 824, 'name' => 'hrm_recruitment_interview_participants_index'],
            ['id' => 825, 'name' => 'hrm_recruitment_interview_participants_view'],
            ['id' => 826, 'name' => 'hrm_recruitment_interview_participants_download'],
            ['id' => 826, 'name' => 'hrm_recruitment_interview_participants_download'],
            ['id' => 827, 'name' => 'hrm_recruitment_interview_participants_delete'],
            ['id' => 828, 'name' => 'hrm_recruitment_interview_participants_bulk_for_final_select'],
            ['id' => 829, 'name' => 'hrm_recruitment_interview_final_select_index'],
            ['id' => 830, 'name' => 'hrm_recruitment_interview_final_select_view'],
            ['id' => 831, 'name' => 'hrm_recruitment_interview_final_select_download'],
            ['id' => 832, 'name' => 'hrm_recruitment_interview_final_select_delete'],
            ['id' => 833, 'name' => 'hrm_recruitment_interview_final_bulk_select_for_offer_letter'],
            ['id' => 834, 'name' => 'hrm_recruitment_applicants_offer_letter_index'],
            ['id' => 835, 'name' => 'hrm_recruitment_applicants_offer_letter_view'],
            ['id' => 836, 'name' => 'hrm_recruitment_applicants_offer_letter_download'],
            ['id' => 837, 'name' => 'hrm_recruitment_applicants_offer_letter_delete'],
            ['id' => 838, 'name' => 'hrm_recruitment_applicants_bulk_hired'],
            ['id' => 839, 'name' => 'hrm_recruitment_applicants_hired_index'],
            ['id' => 840, 'name' => 'hrm_recruitment_applicants_hired_view'],
            ['id' => 841, 'name' => 'hrm_recruitment_applicants_hired_download'],
            ['id' => 842, 'name' => 'hrm_recruitment_applicants_hired_delete'],
            ['id' => 843, 'name' => 'hrm_recruitment_applicants_bulk_select_for_reject'],
            ['id' => 844, 'name' => 'hrm_recruitment_convert_employee_index'],
            ['id' => 845, 'name' => 'hrm_recruitment_convert_employee_view'],
            ['id' => 846, 'name' => 'hrm_recruitment_convert_employee_download'],
            ['id' => 847, 'name' => 'hrm_recruitment_convert_employee_delete'],
            ['id' => 848, 'name' => 'hrm_recruitment_convert_employee_bulk_select_for_reject'],
            ['id' => 849, 'name' => 'hrm_recruitment_applicant_reject_index'],
            ['id' => 850, 'name' => 'hrm_recruitment_applicant_reject_view'],
            ['id' => 851, 'name' => 'hrm_recruitment_applicant_reject_download'],
            ['id' => 852, 'name' => 'hrm_recruitment_applicant_reject_delete'],
            ['id' => 853, 'name' => 'hrm_recruitment_applicant_reject_bulk_permanent_delete'],
            ['id' => 854, 'name' => 'hrm_recruitment_interview_schedule_index'],
            ['id' => 855, 'name' => 'hrm_recruitment_interview_schedule_create'],
            ['id' => 856, 'name' => 'hrm_recruitment_interview_schedule_view'],
            ['id' => 857, 'name' => 'hrm_recruitment_interview_schedule_update'],
            ['id' => 858, 'name' => 'hrm_recruitment_interview_schedule_delete'],
            ['id' => 859, 'name' => 'hrm_recruitment_interview_question_index'],
            ['id' => 860, 'name' => 'hrm_recruitment_interview_question_create'],
            ['id' => 861, 'name' => 'hrm_recruitment_interview_question_view'],
            ['id' => 862, 'name' => 'hrm_recruitment_interview_question_update'],
            ['id' => 863, 'name' => 'hrm_recruitment_interview_question_delete'],


        ];

        return $permissions;
    }
}
