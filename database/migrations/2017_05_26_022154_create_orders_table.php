<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid')->nullable();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('address_id')->index();
            $table->json('date_of_delivery_variants');
            $table->dateTime('date_of_delivery');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->decimal('price')->default(0.00);
            $table->integer('bottles')->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('address_id')
                ->references('id')
                ->on('address')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('orders_products', function (Blueprint $table) {
            $table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('product_id')->index();
            $table->unsignedInteger('product_count')->default(1)->index();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_products');
        Schema::dropIfExists('orders');
    }
}
