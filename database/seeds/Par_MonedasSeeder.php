<?php

use Illuminate\Database\Seeder; 

class Par_MonedasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__monedas')->insert(['nommoneda'=>'Bolivianos','codmoneda'=>'Bs','tipocambio'=>'1']);
        DB::table('par__monedas')->insert(['nommoneda'=>'Dolares Americanos','codmoneda'=>'$us','tipocambio'=>'6.96']); 
    }
}
