<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBilldetailTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('billdetail', function (Blueprint $table) {
			$table->increments('idbillde');
			$table->integer('billid');
			$table->integer('procateid');
			$table->integer('proid');
			$table->integer('billamount');
			$table->float('billprice', 20, 4);
			$table->float('billtotal', 20, 4);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('billdetail');
	}
}
