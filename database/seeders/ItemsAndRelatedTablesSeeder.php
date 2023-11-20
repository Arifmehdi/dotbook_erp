<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ItemsAndRelatedTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::statement($this->unitsSql());
        \DB::statement($this->brandsSql());
        \DB::statement($this->categoriesSql());
        \DB::statement($this->productsSql());
    }

    public function unitsSql()
    {
        $sql = <<<'SQL'
            INSERT INTO `units` (`id`, `name`, `code_name`, `created_at`, `updated_at`) VALUES
            (4, 'Kg', 'Kg', '2020-11-02 18:41:16', '2022-08-10 19:07:36'),
            (13, 'Pieces', 'PC', '2022-08-10 19:06:56', '2022-08-10 19:06:56'),
            (14, 'Feet', 'FT', '2022-08-10 19:07:46', '2022-08-10 19:07:46'),
            (15, 'Pound', 'LBS', '2022-08-15 22:40:50', '2022-08-24 14:09:40'),
            (16, 'Drum', 'Drm', '2022-08-15 22:41:37', '2022-08-24 14:10:01'),
            (17, 'Litter', 'Ltr', '2022-08-16 16:25:56', '2022-08-24 14:09:09'),
            (18, 'Running Feet', 'RFT', '2022-08-17 21:06:28', '2022-08-24 14:08:43'),
            (19, 'CFT', '8', '2022-08-17 21:06:44', '2022-08-17 21:06:44'),
            (20, 'NOS', 'P.c', '2022-08-17 21:08:37', '2022-08-24 14:10:31'),
            (21, 'Pairs', 'Pair', '2022-08-17 21:11:21', '2022-08-24 14:10:45'),
            (22, 'Packet', 'Pkt', '2022-08-17 21:12:06', '2022-08-24 14:10:59'),
            (23, 'Meter', 'MTR', '2022-08-17 21:12:32', '2022-08-17 21:12:32'),
            (24, 'Roll', 'Rol', '2022-08-17 21:12:49', '2022-08-24 14:11:17'),
            (25, 'Set', 'St', '2022-08-17 21:13:00', '2022-08-24 14:11:31'),
            (26, 'Cylinder', 'Cyl', '2022-08-17 21:13:14', '2022-08-24 14:11:49'),
            (27, 'Box', 'Bx', '2022-08-17 21:13:27', '2022-08-24 14:12:20'),
            (28, 'Pail', '17', '2022-08-17 21:13:41', '2022-08-17 21:13:41'),
            (29, 'Gallon', '18', '2022-08-17 21:13:52', '2022-08-17 21:13:52'),
            (30, 'Coil', '19', '2022-08-17 21:14:18', '2022-08-17 21:14:18'),
            (31, 'Bottle', 'Btl', '2022-08-17 21:14:39', '2022-08-24 14:12:40'),
            (32, 'KB', '21', '2022-08-17 21:14:49', '2022-08-17 21:14:49'),
            (33, 'Rim', '22', '2022-08-17 21:15:03', '2022-08-17 21:15:03'),
            (34, 'SQ.Feet', '23', '2022-08-17 21:15:28', '2022-08-17 21:15:28')
            SQL;

        return $sql;
    }

    public function brandsSql()
    {
        $sql = <<<'SQL'
            INSERT INTO `brands` (`id`, `name`, `photo`, `status`, `created_at`, `updated_at`) VALUES
            (1, 'ASBRM', '62dd380ebb941.jpg', 1, '2022-07-24 22:16:14', '2022-07-24 22:16:14'),
            (2, 'Super V', 'images/default.jpg', 1, '2022-08-16 16:29:14', '2022-08-16 16:29:14'),
            (3, 'SKF', 'images/default.jpg', 1, '2022-08-23 16:52:14', '2022-08-23 16:52:14')
            SQL;

        return $sql;
    }

    public function categoriesSql()
    {
        $sql = <<<'SQL'
            INSERT INTO `categories` (`id`, `name`, `description`, `parent_category_id`, `photo`, `status`, `created_at`, `updated_at`) VALUES
            (5, 'Raw Materials', NULL, NULL, 'images/default.jpg', 1, '2022-08-10 19:05:18', '2022-08-10 19:05:18'),
            (7, 'Hardware', NULL, NULL, 'images/default.jpg', 1, '2022-08-15 22:36:55', '2022-08-15 22:36:55'),
            (8, 'Transport', NULL, NULL, 'images/default.jpg', 1, '2022-08-15 22:37:27', '2022-08-15 22:37:27'),
            (9, 'Hand Tools', NULL, NULL, 'images/default.jpg', 1, '2022-08-15 22:38:10', '2022-08-23 20:46:16'),
            (10, 'Lubricants', NULL, NULL, 'images/default.jpg', 1, '2022-08-15 22:38:31', '2022-08-23 20:41:38'),
            (12, 'REFACTORIES ITEM', NULL, NULL, 'images/default.jpg', 1, '2022-08-16 16:15:06', '2022-08-16 16:15:06'),
            (13, 'Metal', NULL, NULL, 'images/default.jpg', 1, '2022-08-16 16:20:08', '2022-08-23 16:03:38'),
            (15, 'BEARING', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 15:21:19', '2022-08-23 21:09:10'),
            (16, 'Chemicals', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 16:53:00', '2022-08-23 16:53:00'),
            (17, 'Electrical Components', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 20:42:51', '2022-08-23 20:42:51'),
            (18, 'Pipes', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 20:44:34', '2022-08-23 20:44:34'),
            (19, 'Hydraulic', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 20:44:50', '2022-08-23 20:44:50'),
            (20, 'Nut Bolt', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 20:45:10', '2022-08-23 20:45:10'),
            (21, 'Power Tools', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 20:45:50', '2022-08-23 20:45:50'),
            (22, 'I.C.T', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 20:46:51', '2022-08-23 20:59:28'),
            (23, 'Stationary', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 20:58:32', '2022-08-23 20:58:53'),
            (24, 'Motor', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:00:08', '2022-08-23 21:00:08'),
            (25, 'Pump', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:00:14', '2022-08-23 21:00:14'),
            (26, 'Medicine', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:00:45', '2022-08-23 21:00:45'),
            (27, 'CONSTRUCTION', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:01:03', '2022-08-23 21:01:03'),
            (28, 'FURNITURE', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:01:17', '2022-08-23 21:01:17'),
            (29, 'CERAMICS', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:01:29', '2022-08-23 21:01:29'),
            (30, 'FINISH GOODS', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:01:51', '2022-08-23 21:01:51'),
            (31, 'GAS GENERATOR', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:02:37', '2022-08-23 21:02:37'),
            (32, 'Battery', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:02:48', '2022-08-23 21:02:48'),
            (33, 'BLADE', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:03:27', '2022-08-23 21:03:27'),
            (34, 'Others Metal', NULL, NULL, 'images/default.jpg', 1, '2022-08-23 21:05:20', '2022-08-23 21:05:20')
            SQL;

        return $sql;
    }

    public function productsSql()
    {
        $sql = <<<SQL
            INSERT INTO `products` (`id`, `type`, `name`, `product_code`, `category_id`, `parent_category_id`, `brand_id`, `unit_id`, `tax_id`, `tax_type`, `warranty_id`, `product_cost`, `product_cost_with_tax`, `profit`, `product_price`, `offer_price`, `is_manage_stock`, `quantity`, `combo_price`, `alert_quantity`, `is_featured`, `is_combo`, `is_variant`, `is_show_in_ecom`, `is_show_emi_on_pos`, `is_for_sale`, `attachment`, `thumbnail_photo`, `expire_date`, `product_details`, `is_purchased`, `barcode_type`, `weight`, `product_condition`, `status`, `number_of_sale`, `total_transfered`, `total_adjusted`, `custom_field_1`, `custom_field_2`, `custom_field_3`, `created_at`, `updated_at`) VALUES
            (2, 1, 'MS Rod 60 Grade (400 w) 8 mm', 'R60G8MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '65.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fcc18ffa3.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:20:03', '2022-08-14 20:04:59'),
            (3, 1, 'MS Rod 60 Grade (400 w) 10 mm', 'R60G10MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fcaff41f2.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:37:33', '2022-08-14 20:04:59'),
            (4, 1, 'MS Rod 60 Grade (400 w) 12 mm', 'R60G12MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fca06b8ce.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:38:46', '2022-08-14 20:04:59'),
            (5, 1, 'MS Rod 60 Grade (400 w) 16 mm', 'R60G16MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fc91ad4f0.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:39:43', '2022-08-14 20:04:59'),
            (6, 1, 'MS Rod 60 Grade (400 w) 20 mm', 'R60G20MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fc828cb9a.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:40:21', '2022-08-14 20:04:59'),
            (7, 1, 'MS Rod 60 Grade (400 w) 22 mm', 'R60G22MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fc71be144.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:41:05', '2022-08-14 20:04:59'),
            (8, 1, 'MS Rod 60 Grade (400 w) 25 mm', 'R60G25MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fc5dedf44.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:41:43', '2022-08-14 20:04:59'),
            (9, 1, 'MS Rod 60 Grade (400 w) 28 mm', 'R60G28MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '19999900000.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fc4ba6bff.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '100000.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:42:35', '2022-08-22 17:42:55'),
            (10, 1, 'MS Rod 60 Grade (400 w) 32 mm', 'R60G32MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fc3d7f5a8.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:43:16', '2022-08-14 20:04:59'),
            (11, 1, 'MS Rod 75 Grade (500 w) 8 mm', 'R75G8MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '500000000.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fc2ba67d1.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:45:24', '2022-08-14 20:04:59'),
            (12, 1, 'MS Rod 75 Grade (500 w) 10 mm', 'R75G10MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '69980000.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fc151624d.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '20000.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:48:05', '2022-08-14 20:04:59'),
            (13, 1, 'MS Rod 75 Grade (500 w) 12 mm', 'R75G12MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '299999999.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fba0f3055.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:48:46', '2022-08-22 00:08:39'),
            (14, 1, 'MS Rod 75 Grade (500 w) 16 mm', 'R75G16MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '7001.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fb9036b27.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:49:31', '2022-08-14 20:04:59'),
            (15, 1, 'MS Rod 75 Grade (500 w) 20 mm', 'R75G20MM', NULL, NULL, 1, 4, NULL, 1, NULL, '0.00', '0.00', '100.00', '85.00', '0.00', 1, '800000000.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fb7f52a5f.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:50:24', '2022-08-14 20:04:59'),
            (16, 1, 'MS Rod 75 Grade (500 w) 22 mm', 'R75G22MM', NULL, NULL, 1, 4, NULL, 1, NULL, '100.00', '100.00', '-15.00', '85.00', '0.00', 1, '50005001.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fb711c0db.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:51:20', '2022-08-14 20:04:59'),
            (17, 1, 'MS Rod 75 Grade (500 w) 25 mm', 'R75G25MM', NULL, NULL, 1, 4, NULL, 1, NULL, '83.00', '83.00', '2.41', '85.00', '0.00', 1, '90006000.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fb60126e2.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:52:07', '2022-08-14 20:04:59'),
            (18, 1, 'MS Rod 75 Grade (500 w) 28 mm', 'R75G28MM', NULL, NULL, 1, 4, NULL, 1, NULL, '50.00', '50.00', '70.00', '85.00', '0.00', 1, '691499.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fb4d6bef3.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '2500.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:52:49', '2022-08-14 20:04:59'),
            (19, 1, 'MS Rod 75 Grade (500 w) 32 mm', 'R75G323MM', NULL, NULL, 1, 4, NULL, 1, NULL, '55.00', '55.00', '54.55', '85.00', '0.00', 1, '10000702600.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '62e0fb39c2254.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '7500.00', '0.00', '0.00', NULL, NULL, NULL, '2022-07-24 22:53:31', '2022-08-14 20:04:59'),
            (21, 1, 'Welding Rod 4.0MM', 'WR4MM', NULL, NULL, NULL, 4, NULL, 1, NULL, '20.00', '20.00', '-100.00', '0.00', '0.00', 1, '10.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-10 18:49:43', '2022-08-10 20:02:48'),
            (22, 1, 'Cutting Disc 4 inc', '10001', NULL, NULL, NULL, 4, NULL, 1, NULL, '5.00', '5.00', '-100.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-10 18:51:41', '2022-08-10 19:14:22'),
            (23, 1, 'Bearing 6203', '110', NULL, NULL, NULL, 13, NULL, 1, NULL, '10.00', '10.00', '-100.00', '0.00', '0.00', 1, '15.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-10 19:33:03', '2022-08-13 14:40:09'),
            (24, 1, 'Brass Shaft 60mm x  30\'\'', 'Shaft', NULL, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 17:45:49', '2022-08-14 17:45:49'),
            (25, 1, 'Brass Shaft 50mm x 30\'\'', 'Shaft', NULL, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 17:47:24', '2022-08-14 17:47:24'),
            (26, 1, 'Alenkey Nut Bolt 12mm x 1\"', '241157', NULL, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:29:57', '2022-08-23 16:46:41'),
            (27, 1, 'Alenkey Nut Bolt 12mm x 2\"', '683535', NULL, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:32:17', '2022-08-23 16:46:41'),
            (28, 1, 'Alenkey Bolt 6mm x 30mm', '465889', NULL, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:34:18', '2022-08-23 16:46:41'),
            (29, 1, 'Alenkey Bolt 8mm x 2\"', '524876', NULL, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:35:47', '2022-08-23 16:46:41'),
            (30, 1, 'Alenkey Spanner 14mm', '731583', NULL, NULL, NULL, 13, NULL, 1, NULL, '50.00', '50.00', '-100.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:37:35', '2022-08-23 16:46:41'),
            (31, 1, 'Alenkey Spanner 8mm', '978987', NULL, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:39:04', '2022-08-23 16:46:41'),
            (32, 1, 'Alenkey Spanner 5 No', '147965', NULL, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:40:25', '2022-08-23 16:46:41'),
            (33, 1, 'Diamond Tools Bit 30mm ( D Type )', '642197', NULL, NULL, NULL, 13, NULL, 1, NULL, '50.00', '50.00', '0.00', '50.00', '0.00', 1, '3.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:42:03', '2022-08-23 16:48:23'),
            (34, 1, 'Diamond Tools Bit 32mm ( V Type )', '888668', NULL, NULL, NULL, 13, NULL, 1, NULL, '200.00', '200.00', '0.00', '200.00', '0.00', 1, '5.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:43:03', '2022-08-23 16:48:23'),
            (35, 1, 'Diamond Tools Bit 40mm ( V Type )', '813539', NULL, NULL, NULL, 13, NULL, 1, NULL, '1.00', '1.00', '0.00', '1.00', '0.00', 1, '2.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-14 18:43:55', '2022-08-23 16:48:23'),
            (36, 1, 'Laddle Well Block', 'SD7853937', 12, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-16 16:15:35', '2022-08-16 16:15:35'),
            (37, 1, 'MS Channel 8\" x 3\"', 'SD5975347', 13, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-16 16:20:34', '2022-08-16 16:20:34'),
            (38, 1, 'MS Channel 6\" x 3\"', 'SD6132652', 13, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-16 16:22:31', '2022-08-16 16:22:31'),
            (39, 1, 'Mobil 20 W 50', 'SD9315594', 10, NULL, 2, 17, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-16 16:29:35', '2022-08-16 16:29:35'),
            (40, 1, 'Wood Stick', 'SD7523971', NULL, NULL, NULL, 13, NULL, 1, NULL, '15.00', '15.00', '0.00', '15.00', '0.00', 1, '1000.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-18 00:41:06', '2022-08-24 16:37:47'),
            (41, 1, 'Welding Holder 500A', 'SD4865732', 7, NULL, NULL, 13, NULL, 1, NULL, '350.00', '350.00', '0.00', '350.00', '0.00', 1, '20.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-18 00:42:15', '2022-08-24 16:37:47'),
            (46, 1, 'Ambient Item', 'ARM141295', NULL, NULL, NULL, 13, NULL, 1, NULL, '100.00', '100.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-22 23:23:15', '2022-08-22 23:23:15'),
            (47, 1, 'Bearing 6204', 'ARM2341868', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-23 15:30:47', '2022-08-23 21:18:45'),
            (48, 1, 'Ball Bearing 2316', 'ARM2677892', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-23 21:10:55', '2022-08-23 21:10:55'),
            (49, 1, 'Ball Bearing 6030', 'ARM4799573', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-23 21:13:03', '2022-08-23 21:13:03'),
            (50, 1, 'Test', '8186447', NULL, NULL, NULL, 4, NULL, 1, NULL, '50.00', '50.00', '-100.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 11:20:34', '2022-08-24 11:20:34'),
            (51, 1, 'Bearing 6305', '1759432', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 11:55:36', '2022-08-24 11:57:27'),
            (52, 1, 'Bearing 6409', '5742563', 15, NULL, NULL, 13, NULL, 1, NULL, '15.00', '15.00', '0.00', '15.00', '0.00', 1, '50.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 11:56:53', '2022-08-24 14:48:25'),
            (53, 1, 'Bearing 6208', '8832112', 15, NULL, NULL, 13, NULL, 1, NULL, '100.00', '100.00', '0.00', '100.00', '0.00', 1, '22.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 11:58:53', '2022-08-24 14:48:25'),
            (54, 1, 'Bearing 5306', '3779378', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 12:00:12', '2022-08-24 12:00:12'),
            (55, 1, 'Bearing 6013', '6178335', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 12:12:54', '2022-08-24 12:12:54'),
            (56, 1, 'Bearing 6205', '2842919', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 13:19:01', '2022-08-24 13:19:01'),
            (57, 1, 'Bearing 6206', '6111463', 15, NULL, NULL, 13, NULL, 1, NULL, '20.00', '20.00', '-100.00', '0.00', '0.00', 1, '1000.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 13:20:09', '2022-08-24 16:33:52'),
            (58, 1, 'Bearing 6207', '9597237', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 13:21:06', '2022-08-24 13:21:06'),
            (59, 1, 'Bearing 6209', '4981271', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 13:21:45', '2022-08-24 13:21:45'),
            (60, 1, 'Bearing 6210', '5655381', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 13:22:13', '2022-08-24 13:22:13'),
            (61, 1, 'Bearing 6211', '7781252', 15, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 13:22:39', '2022-08-24 13:22:39'),
            (62, 1, 'Bearing 6212', '5587268', 15, NULL, NULL, 13, NULL, 1, NULL, '500.00', '500.00', '0.00', '500.00', '0.00', 1, '3.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'images/default.jpg', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2022-08-24 13:23:00', '2022-08-24 17:27:51')
            SQL;

        return $sql;
    }
}
