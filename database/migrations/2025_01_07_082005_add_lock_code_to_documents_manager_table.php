<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents_has_users', function (Blueprint $table) {
            // Add the lock_code column
            $table->string('lock_code')->nullable();  // Adjust after() if needed
        });

        Schema::table('documents_has_users_files', function (Blueprint $table) {
            // Add the lock_code column
            $table->string('lock_code')->nullable();  // Adjust after() if needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('documents_has_users', function (Blueprint $table) {
            // Drop the lock_code column in case of rollback
            $table->dropColumn('lock_code');
        });

        Schema::table('documents_has_users_files', function (Blueprint $table) {
            // Drop the lock_code column in case of rollback
            $table->dropColumn('lock_code');
        });
        
    }
};
