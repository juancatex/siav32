<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParFechaSistemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__fecha__sistemas', function (Blueprint $table) {
            $table->increments('id_fecha');
            $table->date('fechaSistema');
            $table->boolean('fechaCorte')->default(0);
            $table->boolean('activo')->default(1);
            $table->timestamp('created_at')->default(date("Y-m-d H:i:s"));
            $table->timestamp('updated_at')->default(date("Y-m-d H:i:s"));
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par__fecha__sistemas');
    }
}
