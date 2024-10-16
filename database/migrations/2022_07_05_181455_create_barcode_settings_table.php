<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->mediumText('description')->nullable();
            $table->boolean('is_continuous')->default(false);
            $table->double('top_margin', 22, 4)->default(0);
            $table->double('left_margin', 22, 4)->default(0);
            $table->double('sticker_width', 22, 4)->default(0);
            $table->double('sticker_height', 22, 4)->default(0);
            $table->double('paper_width', 22, 4)->default(0);
            $table->double('paper_height', 22, 4)->default(0);
            $table->double('row_distance', 22, 4)->default(0);
            $table->double('column_distance', 22, 4)->default(0);
            $table->bigInteger('stickers_in_a_row')->default(0);
            $table->bigInteger('stickers_in_one_sheet')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_fixed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barcode_settings');
    }
}
