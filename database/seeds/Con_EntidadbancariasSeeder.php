<?php

use Illuminate\Database\Seeder;

class Con_EntidadbancariasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__entidadbancarias')->insert([
            'nomentidadbancaria'=>'Banco Nacional',
            'siglaentidadbancaria'=>'BNB',
        ]);
        DB::table('con__entidadbancarias')->insert([
            'nomentidadbancaria'=>'Banco de Crédito',
            'siglaentidadbancaria'=>'BCP',
        ]);      
        DB::table('con__entidadbancarias')->insert([
            'nomentidadbancaria'=>'Banco Mercantil',
            'siglaentidadbancaria'=>'BMSC',
        ]);
        DB::table('con__entidadbancarias')->insert([
            'nomentidadbancaria'=>'Banco Unión',
            'siglaentidadbancaria'=>'UNION',
        ]);

    }
}
