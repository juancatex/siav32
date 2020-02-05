<?php

use Illuminate\Database\Seeder;

class Par_Fecha_SistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <=31; $i++) {
            $dia=$i<10?'0'.$i:$i;  
        DB::unprepared('CALL insertfecha("2018-12-'.$dia.'")');
           }
       for ($i2 = 1; $i2 <=31; $i2++) {
            $dia2=$i2<10?'0'.$i2:$i2;  
        DB::unprepared('CALL insertfecha("2019-01-'.$dia2.'")');
           }   
    }
}
