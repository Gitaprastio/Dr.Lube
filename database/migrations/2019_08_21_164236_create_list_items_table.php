<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements_list_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('cost');
            $table->integer('quantity');
            $table->unsignedBigInteger('product_agreements_id')->nullable();
            $table->unsignedBigInteger('products_id')->nullable();
            $table->timestamps();

            $table->foreign('product_agreements_id')->references('id')->on('product_agreements');
            $table->foreign('products_id')->references('id')->on('products'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agreements_list_items');
    }
}
