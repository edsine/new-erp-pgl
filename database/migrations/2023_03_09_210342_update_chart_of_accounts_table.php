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
        Schema::table(
            'chart_of_accounts',
            function (Blueprint $table) {
                $table->integer('sub_type_level_2')->default(0);
                $table->integer('sub_type_level_3')->default(0);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'chart_of_accounts',
            function (Blueprint $table) {
                $table->dropColumn('sub_type_level_2');
                $table->dropColumn('sub_type_level_3');
            }
        );
    }
};
