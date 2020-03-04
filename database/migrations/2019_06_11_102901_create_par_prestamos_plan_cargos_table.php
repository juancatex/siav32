<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParPrestamosPlanCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__prestamos__plan__cargos', function (Blueprint $table) {
            $table->bigIncrements('idcargos');
            $table->bigInteger('idplan')->unsigned()->index();
            $table->integer('idperfilcuentadetalle')->unsigned();
            $table->float('cargo');
            $table->integer('rev')->default(0); 
            $table->timestamps(); 
            $table->foreign('idperfilcuentadetalle')->references('idperfilcuentadetalle')->on('con__perfilcuentadetalles'); 
            $table->foreign('idplan')->references('idplan')->on('par__prestamos__plans')->onDelete('cascade'); 
            // $table->foreign('idplan')->references('idplan')->on('par__prestamos__plans'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par__prestamos__plan__cargos');
    }
}
