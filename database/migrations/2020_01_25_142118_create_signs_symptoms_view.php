<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSignsSymptomsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<STATEMENT
            CREATE VIEW admin_signs_symptoms AS
            SELECT
                items.id AS id,
                items.value AS name,
                sign_symptom_groups.group
            FROM items LEFT JOIN sign_symptom_groups
            ON items.id = sign_symptom_groups.item_id
            WHERE items.type = 'signs_symptoms'
STATEMENT);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW admin_signs_symptoms");
    }
}
