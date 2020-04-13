<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPermissionTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('permission', function (Blueprint $table) {
			$table->foreign('position_idpos', 'role_pos')->references('idpos')->on('position')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('user_id', 'role_user')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('permission', function (Blueprint $table) {
			$table->dropForeign('role_pos');
			$table->dropForeign('role_user');
		});
	}
}
