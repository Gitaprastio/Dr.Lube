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
            $table->bigInteger('quantity');
            $table->unsignedBigInteger('product_agreement_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamps();

            $table->foreign('product_agreement_id')->references('id')->on('product_agreements');
            $table->foreign('product_id')->references('id')->on('products'); 
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
