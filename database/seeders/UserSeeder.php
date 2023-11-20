<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = $this->getQueryString();
        \DB::statement($sql);
    }

    public function getQueryString(): string
    {
        $sql = <<<'SQL'
            INSERT INTO `users` (`id`, `user_id`, `prefix`, `name`, `last_name`, `username`, `email`, `role_type`, `allow_login`, `email_verified_at`, `remember_token`, `branch_id`, `status`, `password`, `sales_commission_percent`, `max_sales_discount_percent`, `phone`, `date_of_birth`, `gender`, `marital_status`, `blood_group`, `photo`, `facebook_link`, `twitter_link`, `instagram_link`, `social_media_1`, `social_media_2`, `custom_field_1`, `custom_field_2`, `guardian_name`, `id_proof_name`, `id_proof_number`, `permanent_address`, `current_address`, `bank_ac_holder_name`, `bank_ac_no`, `bank_name`, `bank_identifier_code`, `bank_branch`, `tax_payer_id`, `language`, `created_at`, `updated_at`) VALUES
            (1, '1001', 'Mr', 'Super', 'Admin', 'superadmin', 'koalasoftsolution@gmail.com', 1, 1, NULL, NULL, NULL, 0, '$2y$10$iyMSXsNzgTiJtkANMCKuEON6vtaNJoI61HgdBIk7F8AtxAWGQEFIy', '0.00', '0.00', '0176000000', NULL, NULL, NULL, NULL, 'images/default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en', '2021-04-06 19:04:03', '2022-08-15 18:31:20'),
            (2, '1002', 'Mr', 'Admin', NULL, 'admin', 'admin@gmail.com', 2, 1, NULL, NULL, NULL, 0, '$2y$10$iyMSXsNzgTiJtkANMCKuEON6vtaNJoI61HgdBIk7F8AtxAWGQEFIy', '0.00', '0.00', '0179544333', NULL, NULL, NULL, NULL, 'images/default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-13 01:08:52', '2022-08-15 18:31:45'),
            (3, '1003', 'Mr', 'SR', 'Kamal', 'kamal', 'srkamal@gmail.com', 1, 1, NULL, NULL, NULL, 0, '$2y$10$iyMSXsNzgTiJtkANMCKuEON6vtaNJoI61HgdBIk7F8AtxAWGQEFIy.', '0.00', '0.00', '0175000000', NULL, NULL, NULL, NULL, 'images/default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-06-20 16:24:03', '2022-08-15 18:29:17'),
            (4, '1004', NULL, 'Delivery', 'Jamal', 'jamal', 'dj@gmail.com', 1, 1, NULL, NULL, NULL, 0, '$2y$10$iyMSXsNzgTiJtkANMCKuEON6vtaNJoI61HgdBIk7F8AtxAWGQEFIy', '0.00', '0.00', '0178965343', NULL, NULL, NULL, NULL, 'images/default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-06-20 17:06:19', '2022-08-15 18:33:18'),
            (5, '1005', 'Md.', 'Billal', 'Hossain', 'billal', 'billal@gmail.com', 3, 1, NULL, NULL, NULL, 0, '$2y$10$iyMSXsNzgTiJtkANMCKuEON6vtaNJoI61HgdBIk7F8AtxAWGQEFIy', '33.00', '60.00', '+1 (381) 548-5455', '06-Feb-1983', 'Female', 'Married', 'A laboris veritatis', 'images/default.jpg', 'Recusandae Aut volu', 'Voluptatem nulla cu', 'Dicta do itaque null', NULL, NULL, NULL, NULL, 'April Everett', 'Raven Mayer', '706', 'Odit aliquid archite', 'Sed ut voluptatum el', 'Galena Clay', 'Id ut aliquam deleni', 'Odysseus Kirk', 'Modi tenetur sed sit', 'Consectetur asperior', 'Quam est mollitia si', NULL, '2022-07-06 05:43:30', '2022-07-06 05:44:09'),
            (7, '1006', 'MR', 'Rakib', NULL, 'sr', 'sr@gmail.com', 3, 1, NULL, NULL, NULL, 0, '$2y$10$iyMSXsNzgTiJtkANMCKuEON6vtaNJoI61HgdBIk7F8AtxAWGQEFIy', '0.00', '0.00', '01700000877', '14/11/1998', 'Male', 'Married', NULL, 'images/default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-25 14:53:16', '2022-08-15 19:44:26'),
            (8, '1007', 'MR.', 'Procurement', NULL, 'procurement', 'procurement@gmail.com', 3, 1, NULL, NULL, NULL, 0, '$2y$10$iyMSXsNzgTiJtkANMCKuEON6vtaNJoI61HgdBIk7F8AtxAWGQEFIy', '0.00', '0.00', '018578554456', NULL, NULL, NULL, NULL, 'images/default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-26 15:07:55', '2022-08-15 18:35:19'),
            (9, '1008', 'MR', 'Scale Engineer', NULL, 'scale', 'Scale@gmail.com', 1, 1, NULL, NULL, NULL, 0, '$2y$10$iyMSXsNzgTiJtkANMCKuEON6vtaNJoI61HgdBIk7F8AtxAWGQEFIy', '0.00', '0.00', '017135764456', NULL, NULL, NULL, NULL, 'images/default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-26 15:56:17', '2022-08-15 18:33:47'),
            (10, '1010', NULL, 'Md . Mojakker Hossain', NULL, NULL, 'mojakkarhossan@gmail.com', 3, 1, NULL, NULL, NULL, 0, NULL, '0.00', '0.00', '01784215148', NULL, NULL, NULL, NULL, 'images/default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-08 21:08:24', '2022-08-08 21:08:24');
        SQL;

        return $sql;
    }
}
