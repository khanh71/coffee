<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportdetailTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('importdetail', function (Blueprint $table) {
			$table->increments('idimpde');
			$table->integer('impid');
			$table->integer('maid');
			$table->float('impamount', 20, 4);
			$table->float('impprice', 20, 4);
			$table->float('imptotal', 20, 4);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('importdetail');
	}
}
