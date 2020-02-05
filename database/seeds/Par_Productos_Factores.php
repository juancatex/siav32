<?php

use Illuminate\Database\Seeder;

class Par_Productos_Factores extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__productos__factores')->insert(['nombrefactor'=>'Factor 1','descripcion'=>'- 30% Historial de Pago -30% Monto Solicitado -40% Antiguedad ', 'refvalor'=>'0.4', 'aprobacion'=>'80']);
        DB::table('par__productos__factores')->insert(['nombrefactor'=>'Factor 2','descripcion'=>'- 30% Historial de Pago -30% Monto Solicitado -40% Antiguedad ', 'refvalor'=>'2.0', 'aprobacion'=>'80']);
        DB::table('par__productos__factores')->insert(['nombrefactor'=>'Factor 3','descripcion'=>'- 40% Historial de Pago -30% Monto Solicitado -30% Antiguedad ', 'refvalor'=>'2.0', 'aprobacion'=>'80']);
        DB::table('par__productos__factores')->insert(['nombrefactor'=>'Factor 4','descripcion'=>'- 80% Historial de Préstamos -0% Estado de Préstamo -20% Monto Solicitado ', 'refvalor'=>'2.0', 'aprobacion'=>'50']);
    }
}
