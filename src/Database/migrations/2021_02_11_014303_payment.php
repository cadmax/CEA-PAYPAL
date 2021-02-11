<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Payment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('payment_paypal_id');
			$table->date('date')->nullable();
            $table->string('status')->nullable();
            $table->integer('value');
			$table->text('info')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop('payments');
    }
}
