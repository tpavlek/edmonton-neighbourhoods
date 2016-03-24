<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeighbourhoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('neighbourhoods', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();

            $table->string('name');
            $table->string('slug');
            $table->integer('population')->unsigned();
            $table->string('ward');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::drop('neighbourhoods');
    }
}
