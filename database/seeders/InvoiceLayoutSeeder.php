<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InvoiceLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoice_layouts = [
            ['id' => '1', 'name' => 'Default layout', 'layout_design' => '1', 'show_shop_logo' => '1', 'header_text' => null, 'is_header_less' => '0', 'gap_from_top' => null, 'show_seller_info' => '1', 'customer_name' => '1', 'customer_tax_no' => '0', 'customer_address' => '1', 'customer_phone' => '1', 'sub_heading_1' => null, 'sub_heading_2' => null, 'sub_heading_3' => null, 'invoice_heading' => 'BILL', 'quotation_heading' => 'Quotation', 'draft_heading' => 'Draft', 'challan_heading' => 'Challan', 'branch_landmark' => '0', 'branch_city' => '1', 'branch_state' => '1', 'branch_country' => '1', 'branch_zipcode' => '1', 'branch_phone' => '1', 'branch_alternate_number' => '1', 'branch_email' => '1', 'product_img' => '0', 'product_cate' => '0', 'product_brand' => '0', 'product_imei' => '1', 'product_w_type' => '1', 'product_w_duration' => '1', 'product_w_discription' => '0', 'product_discount' => '1', 'product_tax' => '1', 'product_price_inc_tax' => '0', 'product_price_exc_tax' => '0', 'invoice_notice' => 'If you need any support, Feel free to contact. phone: 9561646, 088-7165665 Mobile : 01819220726', 'sale_note' => '0', 'show_total_in_word' => '1', 'footer_text' => null, 'bank_name' => null, 'bank_branch' => null, 'account_name' => null, 'account_no' => null, 'is_default' => '1', 'created_at' => '2021-03-02 12:24:36', 'updated_at' => '2022-05-29 06:55:55'],
            ['id' => '2', 'name' => 'Pos Printer Layout', 'layout_design' => '2', 'show_shop_logo' => '1', 'header_text' => null, 'is_header_less' => '0', 'gap_from_top' => null, 'show_seller_info' => '1', 'customer_name' => '1', 'customer_tax_no' => '1', 'customer_address' => '1', 'customer_phone' => '1', 'sub_heading_1' => null, 'sub_heading_2' => null, 'sub_heading_3' => null, 'invoice_heading' => 'Invoice', 'quotation_heading' => 'Quotation', 'draft_heading' => 'Draft', 'challan_heading' => 'Challan', 'branch_landmark' => '1', 'branch_city' => '1', 'branch_state' => '1', 'branch_country' => '0', 'branch_zipcode' => '1', 'branch_phone' => '1', 'branch_alternate_number' => '1', 'branch_email' => '1', 'product_img' => '0', 'product_cate' => '0', 'product_brand' => '0', 'product_imei' => '0', 'product_w_type' => '1', 'product_w_duration' => '1', 'product_w_discription' => '0', 'product_discount' => '1', 'product_tax' => '1', 'product_price_inc_tax' => '0', 'product_price_exc_tax' => '0', 'invoice_notice' => 'Invoice Notice', 'sale_note' => '0', 'show_total_in_word' => '1', 'footer_text' => 'Footer Text', 'bank_name' => 'ff', 'bank_branch' => 'ff', 'account_name' => 'ff', 'account_no' => 'ff', 'is_default' => '0', 'created_at' => '2021-03-03 10:20:30', 'updated_at' => '2021-06-26 12:08:26'],
            ['id' => '3', 'name' => 'Header Less', 'layout_design' => '1', 'show_shop_logo' => '1', 'header_text' => null, 'is_header_less' => '1', 'gap_from_top' => '2', 'show_seller_info' => '1', 'customer_name' => '1', 'customer_tax_no' => '1', 'customer_address' => '1', 'customer_phone' => '1', 'sub_heading_1' => null, 'sub_heading_2' => null, 'sub_heading_3' => null, 'invoice_heading' => null, 'quotation_heading' => null, 'draft_heading' => null, 'challan_heading' => null, 'branch_landmark' => '0', 'branch_city' => '1', 'branch_state' => '1', 'branch_country' => '0', 'branch_zipcode' => '1', 'branch_phone' => '1', 'branch_alternate_number' => '1', 'branch_email' => '1', 'product_img' => '0', 'product_cate' => '0', 'product_brand' => '0', 'product_imei' => '1', 'product_w_type' => '1', 'product_w_duration' => '1', 'product_w_discription' => '1', 'product_discount' => '1', 'product_tax' => '1', 'product_price_inc_tax' => '1', 'product_price_exc_tax' => '1', 'invoice_notice' => null, 'sale_note' => '0', 'show_total_in_word' => '1', 'footer_text' => null, 'bank_name' => null, 'bank_branch' => null, 'account_name' => null, 'account_no' => null, 'is_default' => '0', 'created_at' => '2021-03-03 10:22:42', 'updated_at' => '2021-05-04 07:53:03'],
        ];

        \DB::table('invoice_layouts')->insert($invoice_layouts);
    }
}
