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
        Schema::table('requisitions', function (Blueprint $table) {
            $table->string('hod_approval')->nullable();
            $table->string('admin_approval')->nullable();
            $table->string('chairman_approval')->nullable();
            $table->string('hod_remark')->nullable();
            $table->string('admin_remark')->nullable();
            $table->string('chairman_remark')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisitions', function (Blueprint $table) {
            //
        });
    }
};
