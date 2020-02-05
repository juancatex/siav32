<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Par_FuerzasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par_fuerzas')->insert(['idfuerza'=>3,'nomfuerza'=>'Ejército' ]);
        DB::table('par_fuerzas')->insert(['idfuerza'=>4,'nomfuerza'=>'Aerea']);
        DB::table('par_fuerzas')->insert(['idfuerza'=>5,'nomfuerza'=>'Armada']);
    }
}
