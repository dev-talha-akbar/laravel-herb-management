<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsMaxDosageUsageHerbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('herbs', function (Blueprint $table) {
            $table->decimal('max_dosage', 10, 2)->nullable();
            $table->tinyInteger('usage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('herbs', function (Blueprint $table) {
            $table->dropColumn(['max_dosage', 'usage']);
        });
    }
}
