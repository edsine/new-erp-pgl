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
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('expense_type');
            $table->integer('client_id')->nullable();
            $table->integer('project_id')->nullable();
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
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('expense_type');
            $table->dropColumn('client_id');
            $table->dropColumn('project_id');
            $table->dropColumn('department_id');
        });
    }
};
