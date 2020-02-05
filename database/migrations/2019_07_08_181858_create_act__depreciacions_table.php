<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActDepreciacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('act__depreciacions', function (Blueprint $table) {
            $table->increments('iddepreciacion');
            $table->string('gestion',4);
            $table->decimal('ufvini',6,5);
            $table->decimal('ufvfin',6,5);
            $table->string('consumido',12);
            $table->string('saldovida',12);
            $table->decimal('incranual',6,2);
            $table->decimal('depranual',6,2);
            $table->decimal('depracum',6,2);
            $table->decimal('valorfin',6,2);
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
        Schema::dropIfExists('act__depreciacions');
    }
}
