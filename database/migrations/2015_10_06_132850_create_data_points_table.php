<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_points', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id');
            $table->integer('purchase_id');
            $table->string('bid_inv_item');
            $table->string('city');
            $table->string('state');
            $table->integer('quantity');
            $table->float('price');
            $table->timestamps();
        });
    }

    /**Bid Inv.
Item
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('data_points');
    }
}
