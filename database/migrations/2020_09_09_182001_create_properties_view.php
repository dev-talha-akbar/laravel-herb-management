<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<STATEMENT
            CREATE VIEW admin_properties AS
            SELECT
                items.id AS id,
                items.value AS name
            FROM items
            WHERE items.type = 'properties'
STATEMENT);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW admin_properties");
    }
}
