<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToxicityContraindicationsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<STATEMENT
            CREATE VIEW admin_toxicity_contraindications AS
            SELECT
                items.id AS id,
                items.value AS name
            FROM items
            WHERE items.type = 'toxicity_contraindications'
STATEMENT);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW admin_toxicity_contraindications");
    }
}
