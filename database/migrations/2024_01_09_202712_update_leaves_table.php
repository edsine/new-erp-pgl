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
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('hod_approval')->nullable();
            $table->string('admin_approval')->nullable();
            $table->string('chairman_approval')->nullable();
            $table->string('hod_remark')->nullable();
            $table->string('admin_remark')->nullable();
            $table->string('chairman_remark')->nullable();
            $table->integer('department_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('hod_approval');
            $table->dropColumn('admin_approval');
            $table->dropColumn('chairman_approval');
            $table->dropColumn('hod_remark');
            $table->dropColumn('admin_remark');
            $table->dropColumn('chairman_remark');
        });
    }
};
