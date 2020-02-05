<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Adm_Role;
use App\Adm_Permiso;
use Illuminate\Support\Facades\Auth;

//Auth::User;

class AdmRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $roles = Adm_Role::orderBy('idrole', 'desc')->paginate(10);
        }
        else{
            $roles = Adm_Role::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idrole', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page'     => $roles->perPage(),
                'last_page'    => $roles->lastPage(),
                'from'         => $roles->firstItem(),
                'to'           => $roles->lastItem(),
            ],
            'roles' => $roles
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function rolepermiso(Request $request)
    {  
    
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $raw = DB::raw("GROUP_CONCAT(CONCAT(adm__permisos.nompermiso,' -> Mod: ', par__modulos.nommodulo)  SEPARATOR ',') as permisos");
        $raw1 = DB::raw("GROUP_CONCAT(adm__permisos.idpermiso SEPARATOR ',') as permisosid");
      
        if ($buscar==''){
            $rolepermisos = Adm_Role::join ('adm__rolepermisos','adm__roles.idrole','=','adm__rolepermisos.idrole')
            ->join ('adm__permisos','adm__rolepermisos.idpermiso','=','adm__permisos.idpermiso')
            ->join ('par__ventanamodulos','adm__permisos.idventanamodulo','par__ventanamodulos.idventanamodulo')
            ->join ('par__modulos','par__ventanamodulos.idmodulo','=','par__modulos.idmodulo')
            ->select ('adm__roles.nomrol','adm__roles.idrole',$raw, $raw1)
            ->where('adm__roles.activo', '=', '1')
            ->orderby('adm__roles.idrole')
            ->groupBy('adm__roles.idrole')->paginate(10);  
        } 
        else{
            $rolepermisos = Adm_Role::join ('adm__rolepermisos','adm__roles.idrole','=','adm__rolepermisos.idrole')
            ->join ('adm__permisos','adm__rolepermisos.idpermiso','=','adm__permisos.idpermiso')
            ->join ('par__ventanamodulos','adm__permisos.idventanamodulo','par__ventanamodulos.idventanamodulo')
            ->join ('par__modulos','par__ventanamodulos.idmodulo','=','par__modulos.idmodulo')
            ->select ('adm__roles.nomrol','adm__roles.idrole',$raw, $raw1)
            ->where('adm__roles.activo', '=', '1')
            ->where('adm__roles.nomrol','like','%'.$request->buscar.'%')
            ->orderby('adm__roles.idrole')
            ->groupBy('adm__roles.idrole')->paginate(10);  
        } 

       return [
           'pagination' => [
               'total'        => $rolepermisos->total(),
               'current_page' => $rolepermisos->currentPage(),
               'per_page'     => $rolepermisos->perPage(),
               'last_page'    => $rolepermisos->lastPage(),
               'from'         => $rolepermisos->firstItem(),
               'to'           => $rolepermisos->lastItem(),
           ],           
           'rolepermisos' => $rolepermisos
       ];
    }
    
    public function selectPermiso(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $permisos = Adm_Permiso::join ('par__ventanamodulos','adm__permisos.idventanamodulo','par__ventanamodulos.idventanamodulo')
        ->join ('par__modulos','par__ventanamodulos.idmodulo','=','par__modulos.idmodulo')
        ->where('adm__permisos.activo','=','1')
        ->select('adm__permisos.idpermiso','adm__permisos.nompermiso', 'par__ventanamodulos.idmodulo','par__ventanamodulos.template','adm__permisos.descripcion','par__modulos.nommodulo')->orderBy('idpermiso', 'asc')->get(); 
        return ['permisos' => $permisos];
    }
    
    public function registrar_rolepermiso(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        // 'idrole' => 'unique:adm__roleusers'
        // ]);
         
        // if ($validator->fails()) {
        //     return ($validator->messages()->first());
        // }
        
        if (!$request->ajax()) return redirect('/');
        
        //capturamos los datos: iduser y los idrole (1 o mas roles)        
        $bandera=0; $permisos_base='';    $x=1;
        $role = Adm_Role::find($request->idrole);
        foreach($request->idpermiso as $pe) {        

            $role->permisos()->attach($pe);
            
            for ($i=0; $i<count($request->permisos);$i++) {            
                $permisos = explode('-',$request->permisos[$i]);                 
                if ($permisos[0] == $pe) {                    
                    if ($x==1) {
                        $permisos_base = '[{'.$permisos[1].':1'; 
                        $x++;
                    }
                    else {
                        $permisos_base = $permisos_base.','.$permisos[1].':1';
                    }                                         
                    $bandera=1;
                }                
            }
            if ($bandera==1) {
                $permisos_base = $permisos_base .'}]';
                $bandera=0;
                DB::table('adm__rolepermisos')
                ->where('idrole', $role->idrole)
                ->where('idpermiso', $pe)
                ->update(['permisos' => $permisos_base]);
            }                                                     
        }      
    }
    
    public function actualizar_rolepermiso(Request $request)
    {
    //     $validator = Validator::make($request->all(), [
    //     'username' => 'unique:adm__users'
    //     ]);
         
    //     if ($validator->fails()) {
    //         return ($validator->messages()->first());
    //    }
        
        if (!$request->ajax()) return redirect('/');
        
        //capturamos los datos: iduser y los idrole (1 o mas roles)        
        $role = Adm_Role::find($request->idrole);
        $role->permisos()->sync($request->idpermiso); 

        $bandera=0;  
         
        foreach($request->idpermiso as $pe) { 
            $permisos_base='';$x=1;
            for ($i=0; $i<count($request->permisos);$i++) {            
                $permisos = explode('-',$request->permisos[$i]);                 
                if ($permisos[0] == $pe) {                    
                    if ($x==1) {
                        $permisos_base = '{"'.$permisos[1].'":1'; 
                        $x++;
                    }
                    else {
                        $permisos_base = $permisos_base.',"'.$permisos[1].'":1';
                    }                                         
                    $bandera=1;
                }                
            }
            if ($bandera==1) {
                $permisos_base = $permisos_base .'}';
                $bandera=0;
                DB::table('adm__rolepermisos')
                ->where('idrole', $role->idrole)
                ->where('idpermiso', $pe)
                ->update(['permisos' => $permisos_base]);
            }
        }
    }
    
    public function permisosbase(Request $request)
    {
       
        //print_r($request->role);
        $permisos = explode(',',$request->idpermiso);
        $mandar=[];$xx=0;

        for ($a=0; $a<count($permisos); $a++) {
            $sql = "select permisos 
            from adm__rolepermisos 
            where idrole=".$request->role." and idpermiso=".$permisos[$a]." ";
            $permisosbase=DB::select($sql);

            $va=[];
            foreach($permisosbase as $pe) {
                //echo 'permiso: '.$permisos[$a].'valores: '.$pe->permisos.'<br>';
                if ($pe->permisos!="") {
                    //echo 'valor: '. $pe->permisos; echo '<br>';
                    $va[] = (array)json_decode($pe->permisos); //print_r($va);echo '------';
                    
                    foreach($va[0] as $key => $value){
                        $mandar[$xx]=$permisos[$a].'-'.$key;
                        $xx++;
                    }        
                }                
            }            
        }
        //print_r($mandar);        
        //para retornar
        return ['permisosbase'=>$mandar];
    }


    public function selectPermisos(Request $request) {
                
        $modulo = $request->idmodulo;
        $idventanamodulo = $request->idventanamodulo;
        $idusuario = Auth::user()->id;  

        $sql = "select a.permisos
        from adm__rolepermisos a, adm__roleusers b, adm__permisos c, par__ventanamodulos d
        where a.idrole=b.idrole
        and c.idpermiso = a.idpermiso
        and permisos !=''
        and d.idventanamodulo =".$idventanamodulo ." 
        and c.idventanamodulo = d.idventanamodulo
        and b.iduser=".$idusuario ." and d.idmodulo=". $modulo."
        order by a.idrolepermiso asc";
        $data = DB::select($sql);

        return ['datapermiso'=>$data];

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomrol' => 'unique:adm__roles'
        ]);
         
        if ($validator->fails()) {
            return ($validator->messages()->first());
       }
        
        if (!$request->ajax()) return redirect('/');
        $role = new Adm_Role();
        $role->nomrol = $request->nomrol;
        $role->descripcionrol = $request->descripcionrol;
        $role->idrolepadre = $request->idrolepadre;
        $role->activo = '1';
        $role->save();
    }

    public function update(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');
        // $validator = Validator::make($request->all(), [
        // 'idrole' => 'unique:adm__roles'
        // ]);
         
        // if ($validator->fails()) {
        //     return ($validator->messages()->first());
        // }
        
        $role = Adm_Role::findOrFail($request->idrole); 
        $role->nomrol = $request->nomrol;
        $role->descripcionrol = $request->descripcionrol;
        $role->idrolepadre = $request->idrolepadre;
        $role->activo = '1';
        $role->save();
    }

    public function desactivar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');
        $role = Adm_Role::findOrFail($request->idrole);
        $role->activo = '0';
        $role->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $role = Adm_Role::findOrFail($request->idrole);
        $role->activo = '1';
        $role->save();
    }
   
}
