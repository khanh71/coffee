<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('material', function (Blueprint $table) {
			$table->increments('idma');
			$table->string('maname', 100);
			$table->float('maamount', 20, 4)->default(0);
			$table->float('maprice', 20, 4)->default(0);
			$table->string('unit', 50);
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
		Schema::dropIfExists('material');
	}
}
