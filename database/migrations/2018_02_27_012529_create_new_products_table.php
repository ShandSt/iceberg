<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_new', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid')->nullable();
            $table->string('preview_picture')->nullable();
            $table->string('detail_picture')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('category_id')->nullable()->unsigned();
            $table->decimal('price')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_new',function (Blueprint $table){
            $table->dropForeign('products_new_category_id_foreign');
        });
        Schema::dropIfExists('products_new');
    }
}
