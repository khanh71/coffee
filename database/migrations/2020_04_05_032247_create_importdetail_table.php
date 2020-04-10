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
            $table->engine = 'InnoDB';

            $table->increments('idimpde');
            $table->integer('impid');
            $table->integer('maid');
            $table->float('impamount');
            $table->float('impprice');
            $table->float('imptotal');
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
