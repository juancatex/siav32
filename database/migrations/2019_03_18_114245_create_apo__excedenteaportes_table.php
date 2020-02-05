<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoExcedenteaportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apo__excedenteaportes', function (Blueprint $table) {
            $table->increments('idexcedente');
            $table->integer('idaporte');
            $table->string('numpapeleta',10)->index();
            $table->date('fechaaporte');
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
        Schema::dropIfExists('apo__excedenteaportes');
    }
}
