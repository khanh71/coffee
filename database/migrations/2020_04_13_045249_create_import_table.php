<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('import', function (Blueprint $table) {
			$table->increments('idimp');
			$table->integer('suppid');
			$table->integer('userid');
			$table->date('impdate');
			$table->float('total', 10, 0)->default(0);
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
		Schema::dropIfExists('import');
	}
}
