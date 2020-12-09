<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCidColumnToIntOnCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('cid')->change();
        });
//        DB::statement("ALTER TABLE categories ALTER COLUMN cid TYPE INTEGER USING cid::INTEGER ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('cid')->change();
        });
//        DB::statement("ALTER TABLE categories ALTER COLUMN cid TYPE VARCHAR USING cid::VARCHAR ;");
    }
}
