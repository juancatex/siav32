<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoDebitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apo__debitos', function (Blueprint $table) {
            $table->increments('iddebito');
            $table->integer('idaporte')->unsigned()->index();
            
            $table->decimal('monto',6,2);
            
            $table->boolean('activo')->default(1);
            $table->integer('idperfilcuentamaestro')->unsigned();
            $table->string('obsdebito',256)->nullable();
            $table->string('numdocumentodeb',100)->nullable();
            $table->string('tipodocumentodeb',255)->nullable();
            $table->integer('idlotecargaascii')->unsigned()->nullable();
            $table->integer('idasientomaestro')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('idaporte')->references('idaporte')->on('apo__aportes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apo__debitos');
    }
}
