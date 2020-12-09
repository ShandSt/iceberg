<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('delivery_sms')->default(false)->after('order_source');
            $table->boolean('back_call')->default(false)->after('delivery_sms');
            $table->boolean('intercom_does_not_work')->default(false)->after('back_call');
            $table->text('comment')->nullable()->after('intercom_does_not_work');
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
            $table->dropColumn('delivery_sms');
            $table->dropColumn('back_call');
            $table->dropColumn('intercom_does_not_work');
            $table->dropColumn('comment');
        });
    }
}
