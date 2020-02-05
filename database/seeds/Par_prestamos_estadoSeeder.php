<?php

use Illuminate\Database\Seeder;

class Par_prestamos_estadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__prestamos__estados')->insert(['idestado'=>'1','nombreestado'=>'Pendiente de Desembolso']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'2','nombreestado'=>'Vigente']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'3','nombreestado'=>'Mora']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'4','nombreestado'=>'Refinanciado']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'5','nombreestado'=>'Cancelado']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'6','nombreestado'=>'Cancelado - No Desembolsado']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'7','nombreestado'=>'Eliminado - Observado']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'8','nombreestado'=>'Eliminado - Revertido']);

        DB::table('par__prestamos__estados')->insert(['idestado'=>'10','nombreestado'=>'Enviado por Ascii']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'11','nombreestado'=>'Cobrado por plataforma']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'12','nombreestado'=>'Cobrado por Ascii']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'13','nombreestado'=>'Cobrado por Refinanciamiento']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'14','nombreestado'=>'Cobrado por Liquidacion del prestamo']); 
        DB::table('par__prestamos__estados')->insert(['idestado'=>'15','nombreestado'=>'Cobrado por Amortizacion']); 
        DB::table('par__prestamos__estados')->insert(['idestado'=>'16','nombreestado'=>'Cobrado por Alta Garante']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'17','nombreestado'=>'Cobrado por Saldos Menores']);
        DB::table('par__prestamos__estados')->insert(['idestado'=>'18','nombreestado'=>'Cobrado por Acreedores']);  

        DB::table('par__prestamos__estados')->insert(['idestado'=>'20','nombreestado'=>'Desembolso']); 
 
    }
}
