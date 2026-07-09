<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('Service','Visit','General','Rental','Cleaning','Commissioning')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('Service','Visit','General','Rental','Cleaning')");
    }
};
