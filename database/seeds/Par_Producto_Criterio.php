<?php

use Illuminate\Database\Seeder;

class Par_Producto_Criterio extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'1','valornumerico'=>'1','valorporcentual'=>'90']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'1','valornumerico'=>'2','valorporcentual'=>'70']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'1','valornumerico'=>'3','valorporcentual'=>'10']); 
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'2','valornumerico'=>'60','valorporcentual'=>'10']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'3','valornumerico'=>'70','valorporcentual'=>'40']);

        DB::table('par__productos__criterios')->insert(['idprodparam'=>'4','valornumerico'=>'1','valorporcentual'=>'80']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'4','valornumerico'=>'2','valorporcentual'=>'60']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'4','valornumerico'=>'3','valorporcentual'=>'40']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'4','valornumerico'=>'4','valorporcentual'=>'10']); 
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'5','valornumerico'=>'60','valorporcentual'=>'50']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'6','valornumerico'=>'70','valorporcentual'=>'40']);

        DB::table('par__productos__criterios')->insert(['idprodparam'=>'7','valornumerico'=>'1','valorporcentual'=>'90']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'7','valornumerico'=>'2','valorporcentual'=>'50']);  
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'8','valornumerico'=>'60','valorporcentual'=>'30']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'9','valornumerico'=>'70','valorporcentual'=>'40']);  

        DB::table('par__productos__criterios')->insert(['idprodparam'=>'10','valornumerico'=>'1','valorporcentual'=>'100']);
        DB::table('par__productos__criterios')->insert(['idprodparam'=>'11','valornumerico'=>'70','valorporcentual'=>'100']);
    }
}
