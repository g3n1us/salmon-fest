<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_awards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename')->unique();
            $table->string('title')->nullable();
            $table->string('solicitation_number')->nullable();
            $table->string('contract_number')->nullable();
            $table->string('fiscal_year')->default('2015');
            $table->timestamp('solicitation_date')->nullable();
            $table->timestamp('date')->nullable();
            $table->integer('lost_cases')->nullable();            
            
            $table->text('extra1')->nullable();
            $table->text('extra2')->nullable();
            $table->text('extra3')->nullable();
            $table->text('extra4')->nullable();
            $table->text('extra5')->nullable();
            $table->text('extra6')->nullable();
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
        Schema::drop('purchase_awards');
    }
}
