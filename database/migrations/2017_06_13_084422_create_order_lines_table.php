<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('orderlines', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('order_id');
          $table->integer('product_id');
          $table->integer('amount');
          $table->decimal('price', 10, 4);
          $table->decimal('total', 10, 4);
          $table->text('currency');
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
        Schema::dropIfExists('orderlines');
    }
}
