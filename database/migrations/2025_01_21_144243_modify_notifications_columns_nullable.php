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
        //
        Schema::table('notifications', function (Blueprint $table) {
            // Make 'type', 'data', and 'is_read' columns nullable
            $table->string('type')->nullable()->change();
            $table->text('data')->nullable()->change();
            $table->boolean('is_read')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('notifications', function (Blueprint $table) {
            // Revert columns to non-nullable (assuming they were not nullable before)
            $table->string('type')->nullable(false)->change();
            $table->text('data')->nullable(false)->change();
            $table->boolean('is_read')->nullable(false)->change();
        });
    }
};
