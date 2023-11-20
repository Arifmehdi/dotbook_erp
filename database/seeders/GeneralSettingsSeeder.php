<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $generalSettings = [
            ['id' => '1', 'business' => '{"shop_name":"DotBook ERP","address":"Sector-4, Road No 7, Uttara, Dhaka, Bangladesh","phone":"0455747","email":"speeddigitinfo@gamil.com","start_date":"07-04-2021","default_profit":0,"currency":"TK.","currency_placement":null,"date_format":"d-m-Y","stock_accounting_method":"1","time_format":"12","business_logo":null,"timezone":"Asia\\/Dhaka"}', 'tax' => '{"tax_1_name":null,"tax_1_no":null,"tax_2_name":null,"tax_2_no":null,"is_tax_en_purchase_sale":0}', 'product' => '{"product_code_prefix":"SD","default_unit_id":"null","is_enable_brands":1,"is_enable_categories":1,"is_enable_sub_categories":1,"is_enable_price_tax":1,"is_enable_warranty":1}', 'sale' => '{"default_sale_discount":"0.00","default_tax_id":"null","sales_cmsn_agnt":"select_form_cmsn_list","default_price_group_id":"null", "default_sale_discount_type": "null"}', 'pos' => '{"is_enabled_multiple_pay":1,"is_enabled_draft":1,"is_enabled_quotation":1,"is_enabled_suspend":1,"is_enabled_discount":1,"is_enabled_order_tax":1,"is_show_recent_transactions":1,"is_enabled_credit_full_sale":1,"is_enabled_hold_invoice":1}', 'purchase' => '{"is_edit_pro_price":1,"is_enable_status":1,"is_enable_lot_no":1}', 'dashboard' => '{"view_stock_expiry_alert_for":"31"}', 'system' => '{"theme_color":"dark-theme","datatable_page_entry":"50"}', 'prefix' => '{"purchase_invoice":"PI","sale_invoice":"SI","purchase_return":"PRI","stock_transfer":"ST","stock_djustment":"SA","sale_return":"SRV","expenses":"ER","supplier_id":"S-","customer_id":"C-","purchase_payment":"PPV","sale_payment":"SPV","expanse_payment":"EPV"}', 'send_es_settings' => '{"send_inv_via_email":0,"send_notice_via_sms":0,"cmr_due_rmdr_via_email":0,"cmr_due_rmdr_via_sms":0}', 'email_setting' => '[]', 'sms_setting' => '[]', 'modules' => '{"purchases":1,"add_sale":1,"pos":1,"transfer_stock":1,"stock_adjustment":1,"expenses":1,"accounting":1,"contacts":1,"hrms":1,"requisite":1,"manufacturing":1,"service":1}', 'reward_poing_settings' => '{"enable_cus_point":1,"point_display_name":"Reward Point","amount_for_unit_rp":"10","min_order_total_for_rp":"100","max_rp_per_order":"50","redeem_amount_per_unit_rp":"0.10","min_order_total_for_redeem":"500","min_redeem_point":"30","max_redeem_point":""}', 'mf_settings' => '{"production_ref_prefix":"MF","enable_editing_ingredient_qty":1,"enable_updating_product_price":1}', 'multi_branches' => '0', 'hrm' => '0', 'services' => '0', 'manufacturing' => '0', 'projects' => '0', 'essentials' => '0', 'e_commerce' => '0', 'created_at' => null, 'updated_at' => '2022-05-18 11:53:44'],
        ];

        DB::table('general_settings')->insert($generalSettings);
    }
}
