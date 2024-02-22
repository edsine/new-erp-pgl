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
        Schema::table('leads', function (Blueprint $table) {
            $table->text('lot_description')->nullable();
            $table->string('status')->nullable();
            $table->decimal('amount_bidded', 30, 2)->nullable();
            $table->date('date_of_submission')->nullable();
            $table->string('email_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('company')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('lot_description');
            $table->dropColumn('status');
            $table->dropColumn('amount_bidded', 30, 2);
            $table->dropColumn('date_of_submission');
            $table->dropColumn('email_address');
            $table->dropColumn('phone_number');
            $table->dropColumn('company');
        });
    }
};
