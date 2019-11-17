<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHerbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('herbs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('chinese_name');
            $table->string('english_name');
            $table->string('pharmaceutical_name');
            $table->string('literal_name');
            $table->decimal('dosage_start', 10, 2);
            $table->decimal('dosage_end', 10, 2);
            $table->string('dosage_unit');
            $table->text('herb_image')->nullable();
            $table->text('constituent_images')->nullable();
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
        Schema::dropIfExists('herbs');
    }
}
