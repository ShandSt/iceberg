<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersTableDateJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('date_of_delivery');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->json('date_of_delivery')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('date_of_delivery');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('date_of_delivery')->nullable();
        });
    }
}
