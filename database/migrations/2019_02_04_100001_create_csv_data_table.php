<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_data', function (Blueprint $table) {
            $table->increments('idcsvdata');
            $table->integer('idlote')->unsigned();
            $table->string('csv_filename',50);
            $table->date('fecha_archivo')->nullable();
            $table->string('glosa',255)->nullable();
            $table->integer('idpersonal')->unsigned()->nullable();
            $table->integer('tipoaporte')->unsigned()->nullable();
            $table->boolean('activo')->default(1);
            $table->string('tipodocdebito')->nullable();
            $table->string('numdocdebito',100)->nullable();
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
        Schema::dropIfExists('csv_data');
    }
}
