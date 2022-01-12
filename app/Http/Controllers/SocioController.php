<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

use App\Socio;    
use App\Adm_User;

class SocioController extends Controller
{
    public function index(Request $request)
    {  
      //  if (!$request->ajax()) return redirect('/');
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        if (sizeof($buscararray)>0){
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                } 
            }            
            $socios = Socio::
            select('par_departamentos.abrvdep','socios.*','par_fuerzas.nomfuerza','par_grados.nomgrado','par_grados.abrev','par__tiposocios.nomtiposocio','par_especialidades.nomespecialidad') 
            ->join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento')
            ->join ('par__tiposocios','socios.idtiposocio','=','par__tiposocios.idtiposocio')
            ->join ('par_especialidades','socios.idespecialidad','=','par_especialidades.idespecialidad')
            ->whereraw($sqls)                        
            ->orderBy('socios.nombre', 'asc')
            ->paginate(10);
        }
        else{
            $socios = Socio::
            select('par_departamentos.abrvdep','socios.*','par_fuerzas.nomfuerza','par_grados.nomgrado','par_grados.abrev','par__tiposocios.nomtiposocio','par_especialidades.nomespecialidad') 
                ->join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
                ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
                ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento')
                ->join ('par__tiposocios','socios.idtiposocio','=','par__tiposocios.idtiposocio')
                ->join ('par_especialidades','socios.idespecialidad','=','par_especialidades.idespecialidad')
                ->orderBy('socios.nombre', 'asc')->paginate(10);
        }



        //para sacar los permisos 
        $user_sis = Auth::user()->username; 
        $escalafones_permisos = Adm_User::join('adm__roleusers','adm__users.id','adm__roleusers.iduser')
        ->join('adm__rolepermisos','adm__roleusers.idrole','adm__rolepermisos.idrole')
        ->join('adm__permisos','adm__rolepermisos.idpermiso','adm__permisos.idpermiso')
        ->join('par__ventanamodulos','adm__permisos.idventanamodulo','par__ventanamodulos.idventanamodulo')
        ->join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
        ->select('adm__users.username','par__modulos.nommodulo','par__ventanamodulos.nomventanamodulo','par__ventanamodulos.template','adm__permisos.nompermiso','adm__permisos.metodo') 
        ->where ('adm__users.username','=',$user_sis)
        ->where ('par__ventanamodulos.template','=','socio')
        ->orderby ('par__modulos.nommodulo')->paginate(10);

        return [
            'pagination' => [
                'total'        => $socios->total(),
                'current_page' => $socios->currentPage(),
                'per_page'     => $socios->perPage(),
                'last_page'    => $socios->lastPage(),
                'from'         => $socios->firstItem(),
                'to'           => $socios->lastItem(),
            ],
            'escalafones_permisos'  => $escalafones_permisos,
            'socios' => $socios
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        // $rutas=config('app.ruta_imagen');  
        // $imageData = $request->get('image'); 
        // $fileName =  "foto".$request->numpapeleta. ".jpg" ;
        // Image::make($request->get('image'))->save(public_path($rutas['DIRE_FOTO_SOCIO']).$fileName);
        // guarda las fotos en la carpeta del server tomcat para ser visto en el reporte
        // Image::make($request->get('image'))->save(($rutas['DIRE_FOTO_SOCIO_REPORTES']).$fileName);


        $var = Str::random(32);
        $var.='.jpg'; 
        $value = substr($request->image, strpos($request->image, ',') + 1); 
        $value = base64_decode($value); 
        Storage::put('app/public/socio/'.$var, $value);
 
        $socio = new Socio();
        $socio->numpapeleta=$request->numpapeleta; 
        $socio->rutafoto = $var;
        $socio->codsocio=$request->codsocio;
        $socio->nombre = $request->nombre;
        $socio->apaterno=$request->apaterno;
        $socio->amaterno=$request->amaterno;
        $socio->aesposo=$request->aesposo;
        $socio->idfuerza=$request->idfuerza; 
        $socio->sexo=$request->sexo;
        $socio->carnetmilitar = $request->carnetmilitar;
        $socio->ci = $request->ci;
        $socio->iddepartamentoexpedido = $request->iddepartamentoexpedido;
        $socio->fechanacimiento = $request->fechanacimiento;
        $socio->fechaegreso = $request->fechaegreso;
        $socio->fechaincorporacion = $request->fechaincorporacion;
        $socio->fechainscripcion = $request->fechainscripcion;
        $socio->idmunicipionacimiento = $request->idmunicipionacimiento;
        $socio->idgrado = $request->idgrado;
        $socio->iddestino = $request->iddestino;
        $socio->idescalafon = $request->idescalafon;
        $socio->idespecialidad = $request->idespecialidad;
        $socio->idestadocivil = $request->idestadocivil;
        $socio->direcciondomicilio = $request->direcciondomicilio;
        $socio->telfijo = $request->telfijo;
        $socio->telcelular = $request->telcelular;
        $socio->email = $request->email;
        $socio->observaciones = $request->observaciones;
        $socio->idestafiliaciones = $request->idestafiliaciones;
        $socio->idusuarioregistro = '1';
        $socio->idusuariomodificacion = '1';
        $socio->idtiposocio = $request->idtiposocio;
        $socio->idestservicios=$request->idestservicios;
        $socio->save();
    }

    
    public function updatefoto(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
       
        // $rutas=config('app.ruta_imagen');
        // $imageData = $request->get('image');   
        // $fileName =  "foto".$socio->numpapeleta. ".jpg" ;
        // Image::make($request->get('image'))->save(public_path($rutas['DIRE_FOTO_SOCIO']).$fileName);
        // //guarda las fotos en la carpeta del server tomcat para ser visto en el reporte
        // Image::make($request->get('image'))->save(($rutas['DIRE_FOTO_SOCIO_REPORTES']).$fileName);
        
        $socio = Socio::findOrFail($request->idsocio); 
        $var = Str::random(32);
        $var.='.jpg'; 
        $value = substr($request->image, strpos($request->image, ',') + 1); 
        $value = base64_decode($value); 
        Storage::put('app/public/socio/'.$var, $value);


        $socio->rutafoto = $var;
        $socio->activo = '1';
        $socio->save();
    }
    public function upfotosocioegresado(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'files' => 'required' ,
            'files.*' => 'image|mimes:jpg|max:2240' 
            ]);
            if ($request->hasFile('files')) {
                $files = $request->file('files'); 
                foreach ($files as $file) {
                     Storage::putFileAs('app/public/socio/e/', $file,$file->getClientOriginalName()); 
                }
            }else{
                return response()->json([
                    'message' => 'Datos invalidos'
                               ], 400);
            }
    }
    public function getfotoCR(Request $request)
    {  if (!$request->ajax()) return redirect('/');
        $full_path = Storage::path('AFI/cr.jpg');
        $base64 = base64_encode(Storage::get('AFI/cr.jpg'));

        $full_path2 = Storage::path('AFI/cra.jpg');
        $base642 = base64_encode(Storage::get('AFI/cra.jpg'));

        $full_path3 = Storage::path('AFI/avatar.jpg');
        $base643 = base64_encode(Storage::get('AFI/avatar.jpg'));

        return ['foto'=>'data:'.mime_content_type($full_path) . ';base64,' . $base64,
                'fotoa'=>'data:'.mime_content_type($full_path2) . ';base64,' . $base642,
                'avatar'=>'data:'.mime_content_type($full_path3) . ';base64,' . $base643];
        // return response()->json(array('id' => $producto->idproducto), 200);
     }
    
    public function getfotoCRV(Request $request)
    {  if (!$request->ajax()) return redirect('/');
        $full_path = Storage::path('AFI/crvn.jpg');
        $base64 = base64_encode(Storage::get('AFI/crvn.jpg'));

        $full_path2 = Storage::path('AFI/crvan.jpg');
        $base642 = base64_encode(Storage::get('AFI/crvan.jpg'));

        $full_path3 = Storage::path('AFI/avatar.jpg');
        $base643 = base64_encode(Storage::get('AFI/avatar.jpg'));

        return ['foto'=>'data:'.mime_content_type($full_path) . ';base64,' . $base64,
                'fotoa'=>'data:'.mime_content_type($full_path2) . ';base64,' . $base642,
                'avatar'=>'data:'.mime_content_type($full_path3) . ';base64,' . $base643]; 
     }
    public function getfotoCRVN(Request $request)
    {  if (!$request->ajax()) return redirect('/');
        $full_path = Storage::path('AFI/crvn.jpg');
        $base64 = base64_encode(Storage::get('AFI/crvn.jpg'));

        $full_path2 = Storage::path('AFI/crvan.jpg');
        $base642 = base64_encode(Storage::get('AFI/crvan.jpg'));

        $full_path3 = Storage::path('AFI/avatar.jpg');
        $base643 = base64_encode(Storage::get('AFI/avatar.jpg'));

        return ['foto'=>'data:'.mime_content_type($full_path) . ';base64,' . $base64,
                'fotoa'=>'data:'.mime_content_type($full_path2) . ';base64,' . $base642,
                'avatar'=>'data:'.mime_content_type($full_path3) . ';base64,' . $base643]; 
     }

     public function getfotoBENE(Request $request)
     {  if (!$request->ajax()) return redirect('/');
         $full_path = Storage::path('AFI/ben.jpg');
         $base64 = base64_encode(Storage::get('AFI/ben.jpg'));
 
         $full_path2 = Storage::path('AFI/ben2.jpg');
         $base642 = base64_encode(Storage::get('AFI/ben2.jpg'));
 
         if(Storage::exists('app/public/bene/'.$request->foto)){
            $full_path3 = Storage::path('app/public/bene/'.$request->foto);
            $base643 = base64_encode(Storage::get('app/public/bene/'.$request->foto));
          }else{
            $full_path3 = Storage::path('AFI/avatare.jpg');
            $base643 = base64_encode(Storage::get('AFI/avatare.jpg'));
          }
 
         return ['foto'=>'data:'.mime_content_type($full_path) . ';base64,' . $base64,
                 'fotoa'=>'data:'.mime_content_type($full_path2) . ';base64,' . $base642,
                 'avatar'=>'data:'.mime_content_type($full_path3) . ';base64,' . $base643]; 
      }

     public function getfotoCRV_cen(Request $request)
     {  if (!$request->ajax()) return redirect('/');
         $full_path = Storage::path('AFI/crcen.jpg');
         $base64 = base64_encode(Storage::get('AFI/crcen.jpg'));
 
         $full_path2 = Storage::path('AFI/crcen2.jpg');
         $base642 = base64_encode(Storage::get('AFI/crcen2.jpg'));
 
         if(Storage::exists('fotos/cen/'.$request->foto)){
            $full_path3 = Storage::path('fotos/cen/'.$request->foto);
            $base643 = base64_encode(Storage::get('fotos/cen/'.$request->foto));
          }else{
            $full_path3 = Storage::path('AFI/avatar.jpg');
            $base643 = base64_encode(Storage::get('AFI/avatar.jpg'));
          }
 
         return ['foto'=>'data:'.mime_content_type($full_path) . ';base64,' . $base64,
                 'fotoa'=>'data:'.mime_content_type($full_path2) . ';base64,' . $base642,
                 'avatar'=>'data:'.mime_content_type($full_path3) . ';base64,' . $base643];
          
      }
      public function getfotoCRV_emp(Request $request)
      {  if (!$request->ajax()) return redirect('/');
          $full_path = Storage::path('AFI/cremp.jpg');
          $base64 = base64_encode(Storage::get('AFI/cremp.jpg'));
  
          $full_path2 = Storage::path('AFI/crcen2.jpg');
          $base642 = base64_encode(Storage::get('AFI/crcen2.jpg'));
   if($request->foto){
    if(Storage::exists('app/public/emp/'.$request->foto)){
        $full_path3 = Storage::path('app/public/emp/'.$request->foto);
        $base643 = base64_encode(Storage::get('app/public/emp/'.$request->foto));
      }else{
        $full_path3 = Storage::path('AFI/avatare.jpg');
        $base643 = base64_encode(Storage::get('AFI/avatare.jpg'));
      }
   }else{$full_path3 = Storage::path('AFI/avatare.jpg');
        $base643 = base64_encode(Storage::get('AFI/avatare.jpg'));} 
          return ['foto'=>'data:'.mime_content_type($full_path) . ';base64,' . $base64,
                  'fotoa'=>'data:'.mime_content_type($full_path2) . ';base64,' . $base642,
                  'avatar'=>'data:'.mime_content_type($full_path3) . ';base64,' . $base643];
          
       }
  
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $socio = Socio::findOrFail($request->idsocio);
        $socio->numpapeleta=$request->numpapeleta;
        $socio->codsocio=$request->codsocio;
        $socio->nombre = $request->nombre;
        $socio->apaterno=$request->apaterno;
        $socio->amaterno=$request->amaterno;
        $socio->aesposo=$request->aesposo;
        $socio->idfuerza=$request->idfuerza;
        $socio->sexo=$request->sexo;
        $socio->carnetmilitar = $request->carnetmilitar;
        $socio->ci = $request->ci;
        $socio->iddepartamentoexpedido = $request->iddepartamentoexpedido;
        $socio->fechanacimiento = $request->fechanacimiento;
        $socio->fechaegreso = $request->fechaegreso;
        $socio->fechaincorporacion = $request->fechaincorporacion;
        $socio->fechainscripcion = $request->fechainscripcion;
        $socio->idmunicipionacimiento = $request->idmunicipionacimiento;
        $socio->idgrado = $request->idgrado;
        $socio->iddestino = $request->iddestino;
        $socio->idescalafon = $request->idescalafon;
        $socio->idespecialidad = $request->idespecialidad;
        $socio->idestadocivil = $request->idestadocivil;
        $socio->direcciondomicilio = $request->direcciondomicilio;
        $socio->telfijo = $request->telfijo;
        $socio->telcelular = $request->telcelular;
        $socio->email = $request->email;
        $socio->observaciones = $request->observaciones;
        $socio->idestafiliaciones = $request->idestafiliaciones;
        $socio->idusuarioregistro = '1';
        $socio->idusuariomodificacion = '1';
        $socio->idtiposocio = $request->idtiposocio;
        $socio->idestservicios=$request->idestservicios;
        $socio->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $socio = Socio::findOrFail($request->idsocio);
        $socio->activo = '0';
        $socio->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $socio = Socio::findOrFail($request->idsocio);
        $socio->activo = '1';
        $socio->save();
    }


    /*
    public function uploadFile(Request $request) {
        $file = Input::file('file');
        $filename = $file->getClientOriginalName();
        $path = hash( 'sha256', time());
        if(Storage::disk('uploads')->put($path.'/'.$filename,  File::get($file))) {
            $input['filename'] = $filename;
            $input['mime'] = $file->getClientMimeType();
            $input['path'] = $path;
            $input['size'] = $file->getClientSize();
            $file = FileEntry::create($input);
            return response()->json(['success'=>true,'id'=>$file->id], 200);
        }
        return response()->json(['success'=>false], 500);
    }
    */

    public function updateliquido(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');
        $socio = Socio::findOrFail($request->idsocio);
        $socio->liquidopagable_papeleta=$request->liquido;
        $socio->fecha_papeleta_pago=$request->fechapapeleta; 
        $socio->save();
    }

  
    public function listaSocios(Request $request) //pal autocomplete
    {
        $arrayCadena=explode(" ",$request->cadena);
        for($i=0; $i<count($arrayCadena); $i++)
        {   $valor=$arrayCadena[$i];
            $criterio="(apaterno like '$valor%' or amaterno like '$valor%' or nombre like '$valor%' 
            or ci like '$valor%' or numpapeleta like '%$valor%') ";
            if($i==0) $filtro=$criterio;
            else $filtro.=" and ".$criterio;
        }
        if($request->idsocio) $filtro="idsocio=$request->idsocio";
        $sql="select idsocio,nomgrado,nombre,apaterno,amaterno,ci,numpapeleta, 
        concat(nomgrado,' ',nombre,' ',apaterno,' ',amaterno) as nomcompleto from socios 
        join par_grados on par_grados.idgrado=socios.idgrado where $filtro";
        $socios=DB::select($sql);
        return ['socios'=>$socios];
    }

    public function listaSociosCivil(Request $request) //pal autocomplete
    { 
        if($request->idsocio) { 
            if ($request->tipo=='socio') {
                $filtro="idsocio=$request->idsocio";
                $sql="select idsocio,nomgrado,nombre,apaterno,amaterno,ci,numpapeleta, 
                concat(nombre,' ',apaterno,' ',amaterno) as nomcompleto, 'socio' as tipo 
                from socios 
                join par_grados on par_grados.idgrado=socios.idgrado where $filtro                
                ";
                $socios=DB::select($sql);
                return ['socios'=>$socios];
            }

            else if ($request->tipo=='beneficiario') {
                $filtro2="idbeneficiario=$request->idsocio";
                $sql="select be.idbeneficiario as idsocio, be.idsocio as temp, 'Sr(a).' as nomgrado, be.nombre,
                (case when be.apaterno!='' then be.apaterno else '' end) as apaterno, 
                (case when be.amaterno!='' then be.amaterno else '' end) as amaterno,                 
                be.ci, 
                concat(COALESCE(be.nombre,''),' ',COALESCE(be.apaterno,''),' ',COALESCE(be.amaterno,''),'(',COALESCE(be.parentesco,''),') del Socio: SOF: ',COALESCE(ss.nombre,''), ' ',COALESCE(ss.apaterno,'') ) as nomcompleto, 'beneficiario' as tipo
                from afi__beneficiarios be, socios ss 
                where $filtro2
                and ss.idsocio = be.idsocio
                ";
                $socios=DB::select($sql);
                return ['socios'=>$socios];
            }
                
            else if ($request->tipo=='civil') {
                $filtro1="idcivil=$request->idsocio";
                $sql="select sc.idcivil as idsocio, 'Sr(a).' as nomgrado, sc.nombre, 
                (case when sc.apaterno!='' then sc.apaterno else '' end) as apaterno, 
                (case when sc.amaterno!='' then sc.amaterno else '' end) as amaterno, 
                sc.ci, 
                concat(COALESCE(sc.nombre,''),' ',COALESCE(sc.apaterno,''),' ',COALESCE(sc.amaterno,'')) as nomcompleto,
                'civil' as tipo
                from ser__civils sc 
                where $filtro1
                ";
                $socios=DB::select($sql);
                return ['socios'=>$socios];
            }      
        } 

        else {  //busca a la persona dentro de la tabla socios, civil o beneficiarios
            $arrayCadena=explode(" ",$request->cadena); 
            for($i=0; $i<count($arrayCadena); $i++)
            {   $valor=$arrayCadena[$i];
                $criterio="(apaterno like '$valor%' or amaterno like '$valor%' or nombre like '$valor%' 
                or ci like '$valor%' or numpapeleta like '%$valor%') ";
                $criterio1="(sc.apaterno like '$valor%' or sc.amaterno like '$valor%' or sc.nombre like '$valor%' 
                or sc.ci like '$valor%') ";
                $criterio2="(be.apaterno like '$valor%' or be.amaterno like '$valor%' or be.nombre like '$valor%' 
                or be.ci like '$valor%') ";
                
                if($i==0) {
                    $filtro=$criterio;
                    $filtro1=$criterio1;
                    $filtro2=$criterio2;
                }
                    
                else {
                    $filtro.=" and ".$criterio;
                    $filtro1.=" and ".$criterio1;
                    $filtro2.=" and ".$criterio2;
                } 
            }

            $sql="select idsocio, '' as temp, nomgrado,nombre,apaterno,amaterno,ci,numpapeleta, 
                    concat(nomgrado,' ',nombre,' ',apaterno,' ',amaterno) as nomcompleto, 'socio' as tipo 
                    from socios 
                    join par_grados on par_grados.idgrado=socios.idgrado where $filtro       
                    union 
                    select sc.idcivil as idsocio, '' as temp, 'Sr(a).' as nomgrado, sc.nombre, sc.apaterno, sc.amaterno, sc.ci, 0 as numpapeleta,
                    concat(sc.nombre,' ',sc.apaterno,' ',sc.amaterno) as nomcompleto, 'civil' as tipo
                    from ser__civils sc 
                    where $filtro1
                    union 
                    select be.idbeneficiario as idsocio, be.idsocio as temp, 'Sr(a).' as nomgrado, be.nombre, be.apaterno, be.amaterno, be.ci, 0 as numpapeleta,
                    concat(be.nombre,' ',be.apaterno,' ',be.amaterno) as nomcompleto, 'beneficiario' as tipo
                    from afi__beneficiarios be 
                    where $filtro2
                    ";
            $socios=DB::select($sql);
            return ['socios'=>$socios];
        }                           
    }

    public function verSocio(Request $request)
    {   
        $socio=Socio::select('idsocio','idestadocivil','par__destinos.idmunicipio')
        ->join('par__destinos','par__destinos.iddestino','socios.iddestino')
        ->join('par_municipios','par_municipios.idmunicipio','par__destinos.idmunicipio')
        ->where('idsocio',$request->idsocio)->get();
        return ['socio'=>$socio];
    }
}
