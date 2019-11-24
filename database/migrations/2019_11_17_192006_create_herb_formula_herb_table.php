<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHerbFormulaHerbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('herb_formula_herb', function (Blueprint $table) {
            $table->unsignedBigInteger('herb_formula_id');
            $table->unsignedBigInteger('herb_id');
            $table->decimal('dosage_start', 10, 2);
            $table->decimal('dosage_end', 10, 2);
            $table->string('dosage_unit')->default('g');
            $table->timestamps();

            $table->foreign('herb_formula_id')->references('id')->on('herb_formulas')->onDelete('cascade');
            $table->foreign('herb_id')->references('id')->on('herbs')->onDelete('cascade');
            $table->unique(['herb_formula_id', 'herb_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('herb_formula_herb');
    }
}
