<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkdayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workday', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('idwd');
            $table->integer('userid');
            $table->date('wddate');
            $table->integer('hour');
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
        Schema::dropIfExists('workday');
    }
}
