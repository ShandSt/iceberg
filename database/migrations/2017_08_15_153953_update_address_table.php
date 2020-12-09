<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('street');
            $table->string('house');
            $table->string('comment')->nullable();
            $table->string('guid')->nullable()->change();
            $table->string('floor')->nullable()->change();
            $table->string('entrance')->nullable()->change();
            $table->string('apartment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->string('name');
            $table->dropColumn('street');
            $table->dropColumn('house');
            $table->dropColumn('comment');
            $table->string('guid')->nullable(false)->change();
            $table->string('floor')->nullable(false)->change();
            $table->string('entrance')->nullable(false)->change();
            $table->string('apartment')->nullable(false)->change();
        });
    }
}
