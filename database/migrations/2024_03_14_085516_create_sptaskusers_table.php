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
        Schema::create('sptaskusers', function (Blueprint $table) {
            $table->id();
            $table->integer('sptask_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('invited_by')->default(0);
            $table->integer('department_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sptaskusers');
    }
};
