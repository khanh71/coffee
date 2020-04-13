<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('position', function (Blueprint $table) {
			$table->increments('idpos');
			$table->string('posname', 100);
			$table->float('coefficient', 20, 4)->default(0);
			$table->integer('shopid');
			$table->jsonb('permissions')->default('{}');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('position');
	}
}
