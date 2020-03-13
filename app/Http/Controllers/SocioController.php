<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

use App\Socio;    
use App\Adm_User;

class SocioController extends Controller
{
    public function index(Request $request)
    {  
        if (!$request->ajax()) return redirect('/');
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
            select('par_departamentos.abrvdep','socios.*','par_fuerzas.nomfuerza','par_grados.nomgrado') 
            ->join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento')
            ->whereraw($sqls)                        
            ->orderBy('socios.nombre', 'asc')
            ->paginate(10);
        }
        else{
            $socios = Socio::
                select('par_departamentos.abrvdep','socios.*','par_fuerzas.nomfuerza','par_grados.nomgrado') 
                ->join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
                ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
                ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento')
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
        $rutas=config('app.ruta_imagen'); 
        $imageData = $request->get('image');
        $fileName =  "foto". time() . ".".explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        Image::make($request->get('image'))->save(public_path($rutas['DIRE_FOTO_SOCIO']).$fileName);
        //guarda las fotos en la carpeta del server tomcat para ser visto en el reporte
        //Image::make($request->get('image'))->save(($rutas['DIRE_FOTO_SOCIO_REPORTES']).$fileName);
        $socio = new Socio();
        $socio->numpapeleta=$request->numpapeleta; 
        $socio->rutafoto = $fileName;
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
        $rutas=config('app.ruta_imagen');
        $imageData = $request->get('image');      
        $fileName =  "foto". time() . ".".explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        Image::make($request->get('image'))->save(public_path($rutas['DIRE_FOTO_SOCIO']).$fileName);
        //guarda las fotos en la carpeta del server tomcat para ser visto en el reporte
        //Image::make($request->get('image'))->save(($rutas['DIRE_FOTO_SOCIO_REPORTES']).$fileName);
        $socio = Socio::findOrFail($request->idsocio);
        $socio->rutafoto = $fileName;
        $socio->activo = '1';
        $socio->save();
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



    public function verSocio(Request $request)
    {   
        $socio=Socio::select('idsocio','idestadocivil','par__destinos.idmunicipio')
        ->join('par__destinos','par__destinos.iddestino','socios.iddestino')
        ->join('par_municipios','par_municipios.idmunicipio','par__destinos.idmunicipio')
        ->where('idsocio',$request->idsocio)->get();
        return ['socio'=>$socio];
    }
}
