<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rrh_SegurosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rrh__seguros')->insert(['tipo'=>1,'sigla'=>'FUTURO','nomseguro'=>'AFP Futuro de Bolivia']);
        DB::table('rrh__seguros')->insert(['tipo'=>1,'sigla'=>'PREVISION','nomseguro'=>'AFP Previsión']);
        DB::table('rrh__seguros')->insert(['tipo'=>2,'sigla'=>'CNS','nomseguro'=>'Caja Nacional de Salud']);
        DB::table('rrh__seguros')->insert(['tipo'=>2,'sigla'=>'CPS','nomseguro'=>'Caja Petrolera de Salud']);
        DB::table('rrh__seguros')->insert(['tipo'=>2,'sigla'=>'COSSMIL','nomseguro'=>'Corporación de Seguro Social Militar']);
    }
}

