<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date_sent')->nullable();
            $table->date('date_received')->nullable();
            $table->double('amount');
            $table->date('date_paid')->nullable();
            $table->unsignedBigInteger('purchase_orders_id');
            $table->timestamps();

            $table->foreign('purchase_orders_id')->references('id')->on('purchase_orders');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
