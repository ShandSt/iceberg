<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class ChangeImageFieldsForProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('preview_picture')->change();
            $table->text('detail_picture')->change();
        });
//        DB::statement("ALTER TABLE products ALTER COLUMN preview_picture TYPE TEXT;");
//        DB::statement("ALTER TABLE products ALTER COLUMN detail_picture TYPE TEXT;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('preview_picture')->change();
            $table->string('detail_picture')->change();
        });
//        DB::statement("ALTER TABLE products ALTER COLUMN preview_picture TYPE VARCHAR;");
//        DB::statement("ALTER TABLE products ALTER COLUMN detail_picture TYPE VARCHAR;");
    }
}
