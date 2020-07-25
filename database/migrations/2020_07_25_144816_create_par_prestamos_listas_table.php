<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParPrestamosListasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__prestamos__listas', function (Blueprint $table) {
            $table->bigIncrements('idlista');
            $table->integer('idsocio_beneficiario')->unsigned();
            $table->integer('idcuentasocio_beneficiario')->nullable();//se registra la cuenta bancaria en la que se abonara el monto del prestamo 
            $table->dateTime('fecharegistro')->nullable();
            $table->integer('idusuario_reg')->nullable()->comment('usuario quien hizo el registro de la lista');  
            $table->integer('idusuario_desembolso')->nullable()->comment('usuario quien hizo el desembolso');  
            $table->boolean('estado')->default(0)->comment('estado en el que se encuentra el desembolso, 1=desembolsado, 0=no desembolsado');  
            $table->timestamps();

            $table->foreign('idsocio_beneficiario')->references('idsocio')->on('socios'); 
        });
    }
// para ejcutar el migrate php artisan migrate:refresh --path=/database/migrations/2020_07_25_144816_create_par_prestamos_listas_table.php
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par_prestamos_listas');
    }
}
