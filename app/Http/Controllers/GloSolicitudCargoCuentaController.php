<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Glo_SolicitudCargoCuenta;
use App\Adm_Role;
use App\Adm_Roleuser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Rrh_Empleados;
use App\Adm_User;
use App\Config;
use App\Con_Asientomaestro;
use App\Con__Movimientobancario;


class GloSolicitudCargoCuentaController extends Controller
{
    //
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $usuariolog=Auth::id();
        
        //role muestra el rol o el cargo del usuario
        $role=Adm_Roleuser::select('idrole')
                                    ->where('iduser',$usuariolog)
                                    ->where('activo',1)
                                    ->get()->toArray();
        $idrol=$role[0]['idrole'];

        $buscar = $request->buscar;
        //$tipocargo = $request->tipocargo;
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        $raw2=DB::raw('DATE(glo__solicitud_cargo_cuentas.created_at) as fecha_solicitud');
        $raw3=DB::raw('DATE(fechavalidado) as fechav');
        //TODO:hacer una discriminacion de subcuenta cuando el tipo cargo sea exteno o interno, el join cambia de tabla a proveedores.
        if (!$buscar){
            
            $first = Glo_SolicitudCargoCuenta::join('rrh__empleados','rrh__empleados.idempleado','=','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                                        ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                            $raw,
                                                            $raw2,
                                                            'subcuenta',
                                                            //'idusuario',
                                                            //'idrole',
                                                            'glo__solicitud_cargo_cuentas.glosa',
                                                            'glo__solicitud_cargo_cuentas.monto',
                                                            'estado_aprobado',
                                                            'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                            $raw3,
                                                            'sidirectorio',
                                                            'glo__solicitud_cargo_cuentas.created_at')
                                                    ->where('idrole',$idrol)
                                                    ->where('sidirectorio',0)
                                                    ->where('glo__solicitud_cargo_cuentas.activo',1)
                                                    ->orderBy('glo__solicitud_cargo_cuentas.created_at','desc');
            
                                                    $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
            $solicitudes=Glo_SolicitudCargoCuenta::join('socios','socios.numpapeleta','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                    //->join('fil__directivos','fil__directivos.idsocio','socios.idsocio')
                                                    //->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                                    //->join('fil__unidads','fil__unidads.idunidad','fil__directivos.idunidad')
                                                    //->join('par_municipios','par_municipios.idmunicipio','fil__idfilial.idmunicipio')
                                                    ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                                    ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                            DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre) as nombres'),
                                                            $raw2,
                                                            DB::raw('numpapeleta as subcuenta'),
                                                            //'subcuenta',
                                                            //'idusuario',
                                                            //'idrole',
                                                            'glo__solicitud_cargo_cuentas.glosa',
                                                            'glo__solicitud_cargo_cuentas.monto',
                                                            'estado_aprobado',
                                                            'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                            $raw3,
                                                            'sidirectorio',
                                                            'glo__solicitud_cargo_cuentas.created_at')
                                                    ->where('idrole',$idrol)
                                                    ->where('sidirectorio',1)
                                                    ->where('glo__solicitud_cargo_cuentas.activo',1)
                                                    //->orderBy('glo__solicitud_cargo_cuentas.created_at','desc')
                                                    ->union($first)
                                                    ->orderBy('created_at','desc')
                                                    ->paginate(10);
        }
        else
        {
            $buscararray = array();  
            $buscararray = explode(" ",$request->buscar);
            
            if (sizeof($buscararray)>0) { 
                $sqls=''; 
                foreach($buscararray as $valor){
                    if(empty($sqls)){
                        $sqls="(ci like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or glo__solicitud_cargo_cuentas.glosa like '%".$valor."%' or monto like '%".$valor."%')";
                    }else{
                        $sqls.=" and (ci like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or glo__solicitud_cargo_cuentas.glosa like '%".$valor."%' or monto like '%".$valor."%')";
                    } 
                } 
            }
            
            $first = Glo_SolicitudCargoCuenta::join('rrh__empleados','rrh__empleados.idempleado','=','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                                            ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                            $raw,
                                                            $raw2,
                                                            'subcuenta',
                                                            //'idusuario',
                                                            //'idrole',
                                                            'glo__solicitud_cargo_cuentas.glosa',
                                                            'glo__solicitud_cargo_cuentas.monto',
                                                            'estado_aprobado',
                                                            'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                            $raw3,
                                                            'sidirectorio',
                                                            'glo__solicitud_cargo_cuentas.created_at')
                                                    ->where('idrole',$idrol)
                                                    ->where('sidirectorio',0)
                                                    ->where('glo__solicitud_cargo_cuentas.activo',1)
                                                    ->whereraw($sqls)
                                                    ->orderBy('glo__solicitud_cargo_cuentas.created_at','desc');
            
            $solicitudes=Glo_SolicitudCargoCuenta::join('socios','socios.numpapeleta','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                    ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                                    ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                            DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre) as nombres'),
                                                            $raw2,
                                                            DB::raw('numpapeleta as subcuenta'),
                                                            //'idusuario',
                                                            //'idrole',
                                                            'glo__solicitud_cargo_cuentas.glosa',
                                                            'glo__solicitud_cargo_cuentas.monto',
                                                            'estado_aprobado',
                                                            'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                            $raw3,
                                                            'sidirectorio',
                                                            'glo__solicitud_cargo_cuentas.created_at')
                                                    ->where('idrole',$idrol)
                                                    ->where('sidirectorio',1)
                                                    ->where('glo__solicitud_cargo_cuentas.activo',1)
                                                    ->whereraw($sqls)
                                                    ->union($first)
                                                    ->orderBy('created_at','desc')
                                                    ->paginate(10);
        }
        //$ids
        return [
            'pagination' => [
                'total'        => $solicitudes->total(),
                'current_page' => $solicitudes->currentPage(),
                'per_page'     => $solicitudes->perPage(),
                'last_page'    => $solicitudes->lastPage(),
                'from'         => $solicitudes->firstItem(),
                'to'           => $solicitudes->lastItem(),
            ],
            'solicitudes' => $solicitudes
        ];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) 
            return redirect('/');
        
        $usuariolog=Auth::id();
        
        $role=Adm_Roleuser::select('idrole')
                                ->where('iduser',$usuariolog)
                                ->where('activo',1)
                                ->get()->toArray();
        $idrol=$role[0]['idrole'];

        
        $solicitud = new Glo_SolicitudCargoCuenta();
        
        $solicitud->subcuenta = $request->subcuenta;
        $solicitud->sidirectorio=$request->directorio;
        $solicitud->idusuario = $usuariolog;
        $solicitud->idrole=$idrol;
        $solicitud->glosa=$request->glosa;
        $solicitud->monto=$request->monto;
        $solicitud->saldo_descargo=$request->monto;
        //$solicitud->tipocargo=$request->tipocargo;
        $solicitud->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $usuariolog=Auth::id();
        
        $role=Adm_Roleuser::select('idrole')
                                ->where('iduser',$usuariolog)
                                ->where('activo',1)
                                ->get()->toArray();
        $idrol=$role[0]['idrole'];

        $solicitud = Glo_SolicitudCargoCuenta::findOrFail($request->idsolccuenta);
        $solicitud->subcuenta = $request->subcuenta;
        $solicitud->idusuario = $usuariolog;
        $solicitud->idrole=$idrol;
        $solicitud->glosa=$request->glosa;
        $solicitud->monto=$request->monto;
       // $solicitud->tipocargo=$request->tipocargo;
        $solicitud->modificado=1;
        $solicitud->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $solicitud = Glo_SolicitudCargoCuenta::findOrFail($request->idsolccuenta);
        $solicitud->activo = '0';
        $solicitud->save();
    }
    public function listarconta(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        /*      
        0->Filtrar sin valor,
        1->Aprobado,
        2->Aprobado - Con Saldo,
        3->Aprobado - Sin Descargo,
        4->Aprobado - Con Descargo
        5->No Aprobado,
        */
        $filtro=$request->filtro;

        $buscar = $request->buscar;
       //$tipocargo = $request->tipocargo;
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        $raw2=DB::raw('DATE(glo__solicitud_cargo_cuentas.created_at) as fecha_solicitud');
        
        if (!$buscar){
                $first = Glo_SolicitudCargoCuenta::join('rrh__empleados','rrh__empleados.idempleado','=','glo__solicitud_cargo_cuentas.subcuenta')
                                                        ->join('adm__users','adm__users.id','=','idusuario')
                                                        ->join('adm__roles','adm__roles.idrole','=','glo__solicitud_cargo_cuentas.idrole')
                                                        ->join('fil__filials','fil__filials.idfilial','=','rrh__empleados.idfilial')
                                                        ->join('par_municipios','par_municipios.idmunicipio','=','fil__filials.idmunicipio')
                                                        ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                                        ->leftjoin('con__cuentas','con__cuentas.idcuenta','=','glo__solicitud_cargo_cuentas.idcuentadesembolso')
                                                        ->leftjoin('con___movimientobancarios','con___movimientobancarios.idmovimiento','=','glo__solicitud_cargo_cuentas.idmovbancario')
                                                        ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                                    'subcuenta',
                                                                    'rrh__empleados.idfilial',
                                                                    'par_municipios.nommunicipio',
                                                                    'sigla',
                                                                    $raw,
                                                                    $raw2,
                                                                    'adm__roles.idrole',
                                                                    'adm__users.id',
                                                                    'glo__solicitud_cargo_cuentas.glosa',
                                                                    'glo__solicitud_cargo_cuentas.monto',
                                                                    'estado_aprobado',
                                                                    'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                                    'username',
                                                                    'nomrol',
                                                                    'modificado',
                                                                    'saldo_descargo',
                                                                    'seg_descargoccuenta',
                                                                    'cod_comprobante',
                                                                    'idcuentadesembolso',
                                                                    'nomcuenta',
                                                                    'codcuenta',
                                                                    'con___movimientobancarios.numdocumento',
                                                                    'idmovimiento',
                                                                    'sidirectorio',
                                                                    'glo__solicitud_cargo_cuentas.created_at')
                                                    ->where('glo__solicitud_cargo_cuentas.activo',1)
                                                    ->where(function($query) use ($filtro)
                                                            {
                                                                if($filtro==0)
                                                                {
                                                                   // $query->where(1);
                                                                }
                                                                elseif($filtro==1) {//todos los cargos de cuenta ya sean aprobados, con saldo o con descargo total
                                                                    $query->where('estado_aprobado', 1);
                                                                }
                                                                elseif($filtro==2){ //todos los cargos de cuenta aprobados y con saldo
                                                                    $query->where('estado_aprobado',1)
                                                                            ->where('seg_descargoccuenta',1);
                                                                }
                                                                elseif($filtro==3){// todos los cargos de cuenta aprobados y que no tienen descargo
                                                                    $query->where('estado_aprobado',1)
                                                                    ->where('seg_descargoccuenta',0);
                                                                }
                                                                elseif($filtro==4){//todos los cargos de cuenta aprobados y con descargo y saldo 0
                                                                    $query->where('estado_aprobado',1)
                                                                    ->where('seg_descargoccuenta',2);
                                                                }
                                                                elseif($filtro==5){//todos los cargos de cuenta no aprobados
                                                                    $query->where('estado_aprobado',0);
                                                                   
                                                                }
                                                    
                                                            });



        $cargocuentas = Glo_SolicitudCargoCuenta::join('socios','socios.numpapeleta','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                    ->join('fil__directivos','fil__directivos.idsocio','socios.idsocio')
                                                    ->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                                    ->join('fil__unidads','fil__unidads.idunidad','fil__directivos.idunidad')
                                                    ->join('par_municipios','par_municipios.idmunicipio','=','fil__filials.idmunicipio')
                                                    ->join('adm__users','adm__users.id','=','idusuario')
                                                    ->join('adm__roles','adm__roles.idrole','=','glo__solicitud_cargo_cuentas.idrole')
                                                    ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                                    ->leftjoin('con__cuentas','con__cuentas.idcuenta','=','glo__solicitud_cargo_cuentas.idcuentadesembolso')
                                                    ->leftjoin('con___movimientobancarios','con___movimientobancarios.idmovimiento','=','glo__solicitud_cargo_cuentas.idmovbancario')
                                                    ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                                DB::raw('numpapeleta as subcuenta'),
                                                                'fil__directivos.idfilial',
                                                                'par_municipios.nommunicipio',
                                                                'sigla',
                                                                DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre) as nombres'),
                                                                $raw2,
                                                                'adm__roles.idrole',
                                                                'adm__users.id',
                                                                'glo__solicitud_cargo_cuentas.glosa',
                                                                'glo__solicitud_cargo_cuentas.monto',
                                                                'estado_aprobado',
                                                                'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                                'username',
                                                                'nomrol',
                                                                'modificado',
                                                                'saldo_descargo',
                                                                'seg_descargoccuenta',
                                                                'cod_comprobante',
                                                                'idcuentadesembolso',
                                                                'nomcuenta',
                                                                'codcuenta',
                                                                'con___movimientobancarios.numdocumento',
                                                                'idmovimiento',
                                                                'sidirectorio',
                                                                'glo__solicitud_cargo_cuentas.created_at')
                                                ->where('glo__solicitud_cargo_cuentas.activo',1)
                                                ->where(function($query) use ($filtro)
                                                        {
                                                            if($filtro==0)
                                                            {
                                                               // $query->where(1);
                                                            }
                                                            elseif($filtro==1) {//todos los cargos de cuenta ya sean aprobados, con saldo o con descargo total
                                                                $query->where('estado_aprobado', 1);
                                                            }
                                                            elseif($filtro==2){ //todos los cargos de cuenta aprobados y con saldo
                                                                $query->where('estado_aprobado',1)
                                                                        ->where('seg_descargoccuenta',1);
                                                            }
                                                            elseif($filtro==3){// todos los cargos de cuenta aprobados y que no tienen descargo
                                                                $query->where('estado_aprobado',1)
                                                                ->where('seg_descargoccuenta',0);
                                                            }
                                                            elseif($filtro==4){//todos los cargos de cuenta aprobados y con descargo y saldo 0
                                                                $query->where('estado_aprobado',1)
                                                                ->where('seg_descargoccuenta',2);
                                                            }
                                                            elseif($filtro==5){//todos los cargos de cuenta no aprobados
                                                                $query->where('estado_aprobado',0);
                                                               
                                                            }
                                                
                                                        })
                                                ->union($first)
                                                ->orderBy('created_at','desc')
                                                ->paginate(10);
            
        
        }
        else
        {
            $buscararray = array();  
            $buscararray = explode(" ",$request->buscar);
            
            
            if (sizeof($buscararray)>0) { 
                $sqls=''; 
                foreach($buscararray as $valor){
                    if(empty($sqls)){
                        $sqls="(ci like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or glo__solicitud_cargo_cuentas.glosa like '%".$valor."%' or monto like '%".$valor."%' or cod_comprobante like '%".$valor."%')";
                    }else{
                        $sqls.=" and (ci like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or glo__solicitud_cargo_cuentas.glosa like '%".$valor."%' or monto like '%".$valor."%' or cod_comprobante like '%".$valor."%')";
                    } 
                } 
            }
            $first = Glo_SolicitudCargoCuenta::join('rrh__empleados','rrh__empleados.idempleado','=','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->join('adm__users','adm__users.id','=','idusuario')
                                                    ->join('adm__roles','adm__roles.idrole','=','glo__solicitud_cargo_cuentas.idrole')
                                                    ->join('fil__filials','fil__filials.idfilial','=','rrh__empleados.idfilial')
                                                    ->join('par_municipios','par_municipios.idmunicipio','=','fil__filials.idmunicipio')
                                                    ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                                    ->leftjoin('con__cuentas','con__cuentas.idcuenta','=','glo__solicitud_cargo_cuentas.idcuentadesembolso')
                                                        ->leftjoin('con___movimientobancarios','con___movimientobancarios.idmovimiento','=','glo__solicitud_cargo_cuentas.idmovbancario')
                                                    ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                        'subcuenta',
                                                        'rrh__empleados.idfilial',
                                                        'par_municipios.nommunicipio',
                                                        'sigla',
                                                        $raw,
                                                        $raw2,
                                                        'adm__roles.idrole',
                                                        'adm__users.id',
                                                        'glo__solicitud_cargo_cuentas.glosa',
                                                        'glo__solicitud_cargo_cuentas.monto',
                                                        'estado_aprobado',
                                                        'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                        'username',
                                                        'nomrol',
                                                        'modificado',
                                                        'saldo_descargo',
                                                        'seg_descargoccuenta',
                                                        'cod_comprobante',
                                                        'idcuentadesembolso',
                                                        'nomcuenta',
                                                        'codcuenta',
                                                        'con___movimientobancarios.numdocumento',
                                                        'idmovimiento',
                                                        'sidirectorio',
                                                        'glo__solicitud_cargo_cuentas.created_at'
                                                        )
                                                ->where('glo__solicitud_cargo_cuentas.activo',1)
                                                ->whereraw($sqls)
                                                ->where(function($query) use ($filtro)
                                                {
                                                    if($filtro==0)
                                                    {
                                                       // $query->where(1);
                                                    }
                                                    elseif($filtro==1) {//todos los cargos de cuenta ya sean aprobados, con saldo o con descargo total
                                                        $query->where('estado_aprobado', 1);
                                                    }
                                                    elseif($filtro==2){ //todos los cargos de cuenta aprobados y con saldo
                                                        $query->where('estado_aprobado',1)
                                                                ->where('seg_descargoccuenta',1);
                                                    }
                                                    elseif($filtro==3){// todos los cargos de cuenta aprobados y que no tienen descargo
                                                        $query->where('estado_aprobado',1)
                                                        ->where('seg_descargoccuenta',0);
                                                    }
                                                    elseif($filtro==4){//todos los cargos de cuenta aprobados y con descargo y saldo 0
                                                        $query->where('estado_aprobado',1)
                                                        ->where('seg_descargoccuenta',2);
                                                    }
                                                    elseif($filtro==5){//todos los cargos de cuenta no aprobados
                                                        $query->where('estado_aprobado',0);
                                                       
                                                    }
                                        
                                                });
                                                

        $cargocuentas = Glo_SolicitudCargoCuenta::join('socios','socios.numpapeleta','glo__solicitud_cargo_cuentas.subcuenta')
                                                ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                ->join('fil__directivos','fil__directivos.idsocio','socios.idsocio')
                                                ->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                                ->join('fil__unidads','fil__unidads.idunidad','fil__directivos.idunidad')
                                                ->join('par_municipios','par_municipios.idmunicipio','=','fil__filials.idmunicipio')
                                                ->join('adm__users','adm__users.id','=','idusuario')
                                                ->join('adm__roles','adm__roles.idrole','=','glo__solicitud_cargo_cuentas.idrole')
                                                ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                                ->leftjoin('con__cuentas','con__cuentas.idcuenta','=','glo__solicitud_cargo_cuentas.idcuentadesembolso')
                                                ->leftjoin('con___movimientobancarios','con___movimientobancarios.idmovimiento','=','glo__solicitud_cargo_cuentas.idmovbancario')
                                                ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                            DB::raw('numpapeleta as subcuenta'),
                                                            'fil__directivos.idfilial',
                                                            'par_municipios.nommunicipio',
                                                            'sigla',
                                                            DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre) as nombres'),
                                                            $raw2,
                                                            'adm__roles.idrole',
                                                            'adm__users.id',
                                                            'glo__solicitud_cargo_cuentas.glosa',
                                                            'glo__solicitud_cargo_cuentas.monto',
                                                            'estado_aprobado',
                                                            'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                            'username',
                                                            'nomrol',
                                                            'modificado',
                                                            'saldo_descargo',
                                                            'seg_descargoccuenta',
                                                            'cod_comprobante',
                                                            'idcuentadesembolso',
                                                            'nomcuenta',
                                                            'codcuenta',
                                                            'con___movimientobancarios.numdocumento',
                                                            'idmovimiento',
                                                            'sidirectorio',
                                                            'glo__solicitud_cargo_cuentas.created_at',
                                                            'socios.numpapeleta'
                                                            )
                                            ->where('glo__solicitud_cargo_cuentas.activo',1)
                                            ->whereraw($sqls)
                                            ->where(function($query) use ($filtro)
                                                    {
                                                        if($filtro==0)
                                                        {
                                                           // $query->where(1);
                                                        }
                                                        elseif($filtro==1) {//todos los cargos de cuenta ya sean aprobados, con saldo o con descargo total
                                                            $query->where('estado_aprobado', 1);
                                                        }
                                                        elseif($filtro==2){ //todos los cargos de cuenta aprobados y con saldo
                                                            $query->where('estado_aprobado',1)
                                                                    ->where('seg_descargoccuenta',1);
                                                        }
                                                        elseif($filtro==3){// todos los cargos de cuenta aprobados y que no tienen descargo
                                                            $query->where('estado_aprobado',1)
                                                            ->where('seg_descargoccuenta',0);
                                                        }
                                                        elseif($filtro==4){//todos los cargos de cuenta aprobados y con descargo y saldo 0
                                                            $query->where('estado_aprobado',1)
                                                            ->where('seg_descargoccuenta',2);
                                                        }
                                                        elseif($filtro==5){//todos los cargos de cuenta no aprobados
                                                            $query->where('estado_aprobado',0);
                                                           
                                                        }
                                            
                                                    })
                                                ->union($first)
                                                ->orderBy('created_at','desc')
                                                ->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $cargocuentas->total(),
                'current_page' => $cargocuentas->currentPage(),
                'per_page'     => $cargocuentas->perPage(),
                'last_page'    => $cargocuentas->lastPage(),
                'from'         => $cargocuentas->firstItem(),
                'to'           => $cargocuentas->lastItem(),
            ],
            'cargocuentas' => $cargocuentas
        ];
    }
    public function observar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cargocuentas = Glo_SolicitudCargoCuenta::findOrFail($request->idsolccuenta);
        $cargocuentas->observacion_conta = $request->observado;
        $cargocuentas->estado_aprobado = 2;
        $cargocuentas->save();
    }
    public function listarobs(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cargocuentas = Glo_SolicitudCargoCuenta::select('observacion_conta')
                                                ->where('idsolccuenta',$request->idsolccuenta)
                                                ->get()->toArray();
        
        //dd($cargocuentas);
        return $cargocuentas[0]['observacion_conta'];
        
    }
    public function updatesegccuenta(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $solicitud = Glo_SolicitudCargoCuenta::findOrFail($request->idsolccuenta);
        $solicitud->seg_descargoccuenta = $request->valor;
        $solicitud->saldo_descargo=$request->saldocuenta;
        $solicitud->save();
        //echo "resid".$request->residasientomaestro;
        $asientomaestro=Con_Asientomaestro::findOrFail($request->residasientomaestro);
        $asientomaestro->idsolccuenta=$request->idsolccuenta;
        $asientomaestro->save();
    }
    public function listarTesoreria(Request $request)
    {   /*      
        0->Filtrar sin valor,
        1->Aprobado,
        2->Aprobado - Con Saldo,
        3->Aprobado - Sin Descargo,
        4->Aprobado - Con Descargo
        5->No Aprobado,
        */
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        $raw2=DB::raw('DATE(glo__solicitud_cargo_cuentas.created_at) as fecha_solicitud');
        $first = Glo_SolicitudCargoCuenta::join('rrh__empleados','rrh__empleados.idempleado','=','glo__solicitud_cargo_cuentas.subcuenta')
                                                ->join('fil__filials','fil__filials.idfilial','=','rrh__empleados.idfilial')
                                                ->join('par_municipios','par_municipios.idmunicipio','=','fil__filials.idmunicipio')
                                                ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                                ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                            'rrh__empleados.idfilial',
                                                            'par_municipios.nommunicipio',
                                                            'sigla',
                                                            $raw,
                                                            $raw2,
                                                            'subcuenta',
                                                            'idusuario',
                                                            'glo__solicitud_cargo_cuentas.glosa',
                                                            'glo__solicitud_cargo_cuentas.monto',
                                                            'estado_aprobado',
                                                            'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                            'modificado',
                                                            'cod_comprobante',
                                                            'fecha_desembolso',
                                                            'fechavalidado',
                                                            //'nrcuenta',
                                                            'sidirectorio',
                                                            'glo__solicitud_cargo_cuentas.created_at')
                                            ->where('glo__solicitud_cargo_cuentas.activo',1)
                                            ->where(function($query) {
                                                $query->where('estado_aprobado', 0) //cargos no aprobados no desembolsados
                                                    ->orWhere('estado_aprobado', 1) //cargos desembolsados y aprobados por conta
                                                    ->orWhere('estado_aprobado', 3); //cargos desembolsados
                                            });
    
    $cargocuentas = Glo_SolicitudCargoCuenta::join('socios','socios.numpapeleta','glo__solicitud_cargo_cuentas.subcuenta')
                                            ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                            ->join('fil__directivos','fil__directivos.idsocio','socios.idsocio')
                                            ->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                            ->join('fil__unidads','fil__unidads.idunidad','fil__directivos.idunidad')
                                            ->join('par_municipios','par_municipios.idmunicipio','=','fil__filials.idmunicipio')
                                            ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','glo__solicitud_cargo_cuentas.idasientomaestro')
                                            ->select('glo__solicitud_cargo_cuentas.idsolccuenta',
                                                        'fil__directivos.idfilial',
                                                        'par_municipios.nommunicipio',
                                                        'sigla',
                                                        DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre) as nombres'),
                                                        $raw2,
                                                        DB::raw('numpapeleta as subcuenta'),
                                                        'idusuario',
                                                        'glo__solicitud_cargo_cuentas.glosa',
                                                        'glo__solicitud_cargo_cuentas.monto',
                                                        'estado_aprobado',
                                                        'glo__solicitud_cargo_cuentas.idasientomaestro',
                                                        'modificado',
                                                        'cod_comprobante',
                                                        'fecha_desembolso',
                                                        'fechavalidado',
                                                        //'nrcuenta',
                                                        'sidirectorio',
                                                        'glo__solicitud_cargo_cuentas.created_at')
                                        ->where('glo__solicitud_cargo_cuentas.activo',1)
                                        ->where(function($query) {
                                            $query->where('estado_aprobado', 0) //cargos no aprobados no desembolsados
                                                ->orWhere('estado_aprobado', 1) //cargos desembolsados y aprobados por conta
                                                ->orWhere('estado_aprobado', 3); //cargos desembolsados
                                        })
                                        ->union($first)
                                        ->orderBy('created_at','desc')
                                        ->get();
    
        
      
        

        return ['cargocuentas' => $cargocuentas ];
    }
    public function desembolsar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $hora = time();
        $fecha= date('Y-m-d H:i:s',$hora);
        //echo $fecha;
        //echo date("d-m-Y (H:i:s)", $time);
        foreach ($request->arrayids as $indice => $valor) {
            $idmovimientobancario='';
            $solicitud=Glo_SolicitudCargoCuenta::findOrFail($valor['idsolccuenta']);
            $solicitud->estado_aprobado = 3;
            $solicitud->fecha_desembolso=$fecha;
            $solicitud->idcuentadesembolso=$request->idcuentadesembolso;
            if($valor['num_cheque'])
            {
                $solicitud->tipo_desembolso="cheque";
                //$solicitud->num_desembolso=$valor['num_cheque'];
                $mov_bancario= new Con__Movimientobancario();
                $mov_bancario->nomportador=$valor['portador'];
                $mov_bancario->idcuenta=$request->idcuentadesembolso;
                $mov_bancario->numdocumento=$valor['num_cheque'];
                $mov_bancario->importe=$valor['importe'];
                $mov_bancario->tipocargo='h';
                $mov_bancario->save();
                $idmovimientobancario=$mov_bancario->idmovimiento;                
            }
            else
                $solicitud->tipo_desembolso="web";
            
            $solicitud->idmovbancario=$idmovimientobancario;
            $solicitud->save();
        }


      /*   foreach ($request->arrayids as $indice => $valor) {
            
        } */
       
    }
    
}
