<?php

use Illuminate\Database\Seeder;

class Par_prestamos_tipo_ejecucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__prestamos__tipo__ejecucion')->insert(['idejecucion'=>'1','Codigo'=>'PRN','nombre'=>'Normal','Detalle'=>'Prestamo Normal']);
        DB::table('par__prestamos__tipo__ejecucion')->insert(['idejecucion'=>'2','Codigo'=>'PRA','nombre'=>'Alta','Detalle'=>'Prestamo en Alta']);
        DB::table('par__prestamos__tipo__ejecucion')->insert(['idejecucion'=>'3','Codigo'=>'PAG','nombre'=>'Alta Garantes','Detalle'=>'Prestamo en Alta Garantes']);
        DB::table('par__prestamos__tipo__ejecucion')->insert(['idejecucion'=>'4','Codigo'=>'PAR','nombre'=>'Retorno de Alta Garante','Detalle'=>'Prestamo Retorno de Alta']);
        DB::table('par__prestamos__tipo__ejecucion')->insert(['idejecucion'=>'5','Codigo'=>'PRM','nombre'=>'Migracion','Detalle'=>'Prestamo de Migracion']);
        DB::table('par__prestamos__tipo__ejecucion')->insert(['idejecucion'=>'6','Codigo'=>'PRR','nombre'=>'Refinanciado','Detalle'=>'Prestamo Refinanciado']);
    }
}
