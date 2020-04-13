<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bill', function (Blueprint $table) {
			$table->increments('idbill');
			$table->integer('userid');
			$table->integer('deskid');
			$table->date('billdate')->nullable();
			$table->float('total', 20, 4)->nullable();
			$table->boolean('pay')->default(0);
			$table->integer('voucherid')->nullable();
			$table->float('billsale', 20, 4)->nullable();
			$table->integer('shopid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('bill');
	}
}
