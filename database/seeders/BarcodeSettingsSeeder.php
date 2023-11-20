<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BarcodeSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barcode_settings = [
            ['id' => '1', 'name' => '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 0.55\'\', Barcode 20 Per Sheet', 'description' => '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 0.55\'\', Barcode 20 Per Sheet', 'is_continuous' => '0', 'top_margin' => '0.1200', 'left_margin' => '0.1200', 'sticker_width' => '4.0000', 'sticker_height' => '0.5500', 'paper_width' => '8.5000', 'paper_height' => '11.0000', 'row_distance' => '1.0000', 'column_distance' => '1.0000', 'stickers_in_a_row' => '10', 'stickers_in_one_sheet' => '20', 'is_default' => '0', 'is_fixed' => '1', 'created_at' => null, 'updated_at' => '2021-07-01 11:09:33'],
            ['id' => '2', 'name' => 'Sticker Print, Continuous feed or rolls , Barcode Size: 38mm X 25mm', 'description' => null, 'is_continuous' => '1', 'top_margin' => '0.0000', 'left_margin' => '0.0000', 'sticker_width' => '2.0000', 'sticker_height' => '0.5000', 'paper_width' => '1.8000', 'paper_height' => '0.9843', 'row_distance' => '0.0000', 'column_distance' => '0.0000', 'stickers_in_a_row' => '1', 'stickers_in_one_sheet' => '1', 'is_default' => '1', 'is_fixed' => '1', 'created_at' => null, 'updated_at' => '2021-07-01 09:40:09'],
            ['id' => '3', 'name' => '40 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2\'\' * 0.39\'\', Barcode 40 Per Sheet', 'description' => null, 'is_continuous' => '0', 'top_margin' => '0.3000', 'left_margin' => '0.1000', 'sticker_width' => '2.0000', 'sticker_height' => '0.3900', 'paper_width' => '8.5000', 'paper_height' => '11.0000', 'row_distance' => '0.0000', 'column_distance' => '0.0000', 'stickers_in_a_row' => '10', 'stickers_in_one_sheet' => '30', 'is_default' => '0', 'is_fixed' => '1', 'created_at' => null, 'updated_at' => '2021-07-01 11:55:53'],
            ['id' => '4', 'name' => '30 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2.4\'\' * 0.55\'\', Barcode 30 Per Sheet', 'description' => null, 'is_continuous' => '0', 'top_margin' => '0.1000', 'left_margin' => '0.1000', 'sticker_width' => '2.4000', 'sticker_height' => '0.5500', 'paper_width' => '8.5000', 'paper_height' => '11.0000', 'row_distance' => '0.0000', 'column_distance' => '0.0000', 'stickers_in_a_row' => '30', 'stickers_in_one_sheet' => '30', 'is_default' => '0', 'is_fixed' => '1', 'created_at' => null, 'updated_at' => '2021-07-01 12:05:57'],
        ];

        \DB::table('barcode_settings')->insert($barcode_settings);
    }
}
