<?php

use Illuminate\Database\Seeder;

class Con_FirmasautorizadasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__firmaautorizadas')->insert(['idpersona'=>'0', 'tipo_persona'=>'0','activo'=>'1','orden'=>'1']);
    }
}
