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
        Schema::table('notifications', function (Blueprint $table) {
            //
        // Adding new columns to the 'notifications' table
        $table->integer('action_id')->nullable()->after('id');  // Assuming 'id' is the first column
        $table->text('message')->nullable()->after('action_id');  // Nullable, and placed after 'action_id'
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            //
        });
    }
};
