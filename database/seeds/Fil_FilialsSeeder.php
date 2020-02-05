<?php

use Illuminate\Database\Seeder;

class Fil_FilialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fil__filials')->insert(['iddepartamento'=>2,'idmunicipio'=>30 ,'sigla'=>'LP','codfilial'=>'01','direccion'=>'Calle Diaz Romero Esq. Lucas Jaimes Nro 1783 - Miraflores','telcelular'=>'71270945 ','telfijo'=>'2229635']);
        DB::table('fil__filials')->insert(['iddepartamento'=>3,'idmunicipio'=>117,'sigla'=>'CB','codfilial'=>'02','direccion'=>'Calle Mariano Melgarejo Nro. 1795, entre Av. Gral. Pando','telcelular'=>'71224845','telfijo'=>'44481268']);
        DB::table('fil__filials')->insert(['iddepartamento'=>7,'idmunicipio'=>250,'sigla'=>'SC','codfilial'=>'03','direccion'=>'Calle Manuel Ignacio Salvatierra Nro 997 - Barrio Lindo','telcelular'=>'71225133','telfijo'=>'33368223']);
        DB::table('fil__filials')->insert(['iddepartamento'=>7,'idmunicipio'=>275,'sigla'=>'CA','codfilial'=>'04','direccion'=>'Calle Sucre Esq. Sargento Maceda','telcelular'=>'71224407']);
        DB::table('fil__filials')->insert(['iddepartamento'=>9,'idmunicipio'=>325,'sigla'=>'CO','codfilial'=>'05','direccion'=>'Calle Villa De la Cruz']);
        DB::table('fil__filials')->insert(['iddepartamento'=>8,'idmunicipio'=>309,'sigla'=>'GU','codfilial'=>'06','direccion'=>'Av. Oruro Nro. 383 (media cuadra Plaza German Busch)','telcelular'=>'71224794']);
        DB::table('fil__filials')->insert(['iddepartamento'=>4,'idmunicipio'=>164,'sigla'=>'OR','codfilial'=>'07','direccion'=>'Av. Sgto. Flores entre calle Vasquez y 6 de Octubre Nro. 403','telcelular'=>'71224754','telfijo'=>'25241750']);
        DB::table('fil__filials')->insert(['iddepartamento'=>5,'idmunicipio'=>199,'sigla'=>'PO','codfilial'=>'08','direccion'=>'Calle Smith Esq. Chayanta Nro. 403','telcelular'=>'','telfijo'=>'26263045']);
        DB::table('fil__filials')->insert(['iddepartamento'=>7,'idmunicipio'=>300,'sigla'=>'PS','codfilial'=>'09','direccion'=>'Av. Mariscal Sucre s/n, frente exrestaurant Avenida','telcelular'=>'']);
        DB::table('fil__filials')->insert(['iddepartamento'=>8,'idmunicipio'=>308,'sigla'=>'RI','codfilial'=>'10','direccion'=>'Av. Nicolas Suarez Esq. Calle Santa Cruz','telcelular'=>'71224847']);
        DB::table('fil__filials')->insert(['iddepartamento'=>7,'idmunicipio'=>266,'sigla'=>'RO','codfilial'=>'11','direccion'=>'Calle La Paz Nro 447','telcelular'=>'71224828']);
        DB::table('fil__filials')->insert(['iddepartamento'=>8,'idmunicipio'=>313,'sigla'=>'RU','codfilial'=>'12','direccion'=>'Av. Aniceto Arce']);
        DB::table('fil__filials')->insert(['iddepartamento'=>1,'idmunicipio'=>1  ,'sigla'=>'SU','codfilial'=>'13','direccion'=>'Calle Almirante Grau Nro 126','telcelular'=>'71224795']);
        DB::table('fil__filials')->insert(['iddepartamento'=>6,'idmunicipio'=>239,'sigla'=>'TA','codfilial'=>'14','direccion'=>'Av. Victor Paz Estensoro Nro 838','telcelular'=>'71224751','telfijo'=>'46645299']);
        DB::table('fil__filials')->insert(['iddepartamento'=>8,'idmunicipio'=>306,'sigla'=>'TR','codfilial'=>'15','direccion'=>'Av. Marban Nro 137 OfIcina 143','telcelular'=>'71224831']);    
        DB::table('fil__filials')->insert(['iddepartamento'=>7,'idmunicipio'=>301,'sigla'=>'PQ','codfilial'=>'16','direccion'=>'Av. Naval media cuadra de la Ruta Virgen de Cotoca s/n','telcelular'=>'71224851']);
        DB::table('fil__filials')->insert(['iddepartamento'=>7,'idmunicipio'=>244,'sigla'=>'VL','codfilial'=>'17','direccion'=>'Av. Ayacucho Esq. Subteniente Barrau','telcelular'=>'71224825']);
        DB::table('fil__filials')->insert(['iddepartamento'=>5,'idmunicipio'=>220,'sigla'=>'TU','codfilial'=>'18','direccion'=>'Calle Bolivar Nro. 134']);
        DB::table('fil__filials')->insert(['iddepartamento'=>6,'idmunicipio'=>242,'sigla'=>'YA','codfilial'=>'19','direccion'=>'Calle Comercio entre calle Paraguay y Hernando Silez - Zona Sud','telfijo'=>'46824503']);
    }
}
