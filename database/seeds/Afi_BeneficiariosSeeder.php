<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Afi_BeneficiariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('afi__beneficiarios')->insert(['idsocio'=>3210,'parentesco'=>'Espos@','nombre'=>'MARIA','apaterno'=>'LIMA','fechanac'=>'1950-10-22','sexo'=>'f','ci'=>'2342342','iddepartamento'=>2]);
        DB::table('afi__beneficiarios')->insert(['idsocio'=>8662,'parentesco'=>'Espos@','nombre'=>'MARIELA DEL CORAL','apaterno'=>'PATTI','amaterno'=>'AYALA','fechanac'=>'1983-03-03','sexo'=>'f','ci'=>'6151285','iddepartamento'=>2,'telcelular'=>'60608610']);
        DB::table('afi__beneficiarios')->insert(['idsocio'=>7648,'parentesco'=>'Espos@','nombre'=>'FANY','apaterno'=>'CAPIA','amaterno'=>'RODRIGUEZ','fechanac'=>'1986-10-12','sexo'=>'f','ci'=>'6538163','iddepartamento'=>3,'telcelular'=>'68335723']);
        DB::table('afi__beneficiarios')->insert(['idsocio'=>9513,'parentesco'=>'Herman@','nombre'=>'BETZA','apaterno'=>'APAZA','amaterno'=>'NINA','fechanac'=>'1978-03-14','sexo'=>'f','ci'=>'4904141','iddepartamento'=>2,'telcelular'=>'70193804']);

    }
}
