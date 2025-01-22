<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
         // Path to the SQL file
         $sqlFilePath = database_path('sql/audits.sql');
 
         // Check if the file exists
         if (File::exists($sqlFilePath)) {
             // Get the content of the SQL file
             $sql = File::get($sqlFilePath);
 
             // Execute the SQL statements
             DB::unprepared($sql);
 
             DB::statement('SET FOREIGN_KEY_CHECKS=1');
         } else {
             // Throw an exception if the SQL file does not exist
             throw new \Exception("SQL file not found: $sqlFilePath");
         }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
