<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Adm_User;
use App\Adm_Role;
use App\Par_Modulo;
use App\Par_Ventanamodulo;

class AdmUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Mostrar la fecha del sistema.
     *
     * @return \Illuminate\Http\Response
     */
    public static function fecha_sistema() { 
        $fecha=(DB::select("select getfecha() as total"))[0]->total;  
        $fecha_sis = date('d-m-Y', strtotime($fecha ));
        return $fecha_sis;
    }
    
    public static function fecha_sistema_login() {
        DB::statement("SET lc_time_names = 'es_ES'");
        $fecha=(DB::select('select  (DATE_FORMAT(getfecha(), "La Paz, %d de %M de %Y")) as total'))[0]->total;  
        return $fecha;
    }

    public function index2(Request $request) {
                
        return view('contenido/contenido');
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
       
        if ($buscar==''){
            $raw=DB::raw('concat(rrh__empleados.nombre," ",rrh__empleados.apaterno ," ",rrh__empleados.amaterno) as nombre');
            $users = Adm_User::select('adm__users.*','rrh__empleados.idempleado', $raw)
            ->join('rrh__empleados','adm__users.idempleado','rrh__empleados.idempleado')
            ->orderBy('id', 'asc')            
            ->paginate(10);
        }
        else{
            $raw=DB::raw('concat(rrh__empleados.nombre," ",rrh__empleados.apaterno ," ",rrh__empleados.amaterno) as nombre');
            $users = Adm_User::select('adm__users.*','rrh__empleados.idempleado', $raw)
            ->join('rrh__empleados','adm__users.idempleado','rrh__empleados.idempleado')
            ->where($criterio, 'like', '%'. $buscar . '%')           
            ->orderBy('id', 'asc')->paginate(10);
        }       
       return [
           'pagination' => [
               'total'        => $users->total(),
               'current_page' => $users->currentPage(),
               'per_page'     => $users->perPage(),
               'last_page'    => $users->lastPage(),
               'from'         => $users->firstItem(),
               'to'           => $users->lastItem(),
           ],
           'users' => $users
       ];
    }    
    public function roleuser(Request $request)
    {  
        // $user = Adm_User::find(1);
        // echo $user->username; imprime el usuario
        // foreach($user->roles as $role) {
        //     echo "<br>Rol: " . $role->nomrol;
        // }
    
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $raw = DB::raw("GROUP_CONCAT(adm__roles.nomrol SEPARATOR ', ') as roles");
        $raw1 = DB::raw("GROUP_CONCAT(adm__roles.idrole SEPARATOR ',') as rolesid");
      
        if ($buscar==''){
            $roleusers = Adm_User::join ('adm__roleusers','adm__users.id','=','adm__roleusers.iduser')
            ->join ('adm__roles','adm__roleusers.idrole','=','adm__roles.idrole')
            ->select ('adm__users.username','adm__users.id',$raw, $raw1)
            ->where('adm__users.activo', '=', '1')
            ->groupBy('adm__users.username')->paginate(10);  
        } 
        else{
            $roleusers = Adm_User::join ('adm__roleusers','adm__users.id','=','adm__roleusers.iduser')
            ->join ('adm__roles','adm__roleusers.idrole','=','adm__roles.idrole')
            ->select ('adm__users.username',$raw, $raw1)
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->groupBy('adm__users.username')->paginate(10);             
        } 

       return [
           'pagination' => [
               'total'        => $roleusers->total(),
               'current_page' => $roleusers->currentPage(),
               'per_page'     => $roleusers->perPage(),
               'last_page'    => $roleusers->lastPage(),
               'from'         => $roleusers->firstItem(),
               'to'           => $roleusers->lastItem(),
           ],           
           'roleusers' => $roleusers
       ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'username' => 'unique:adm__users',
        'idempleado' => 'unique:adm__users'
        ]);
         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }
        
        if ($request->password != $request->password2) {
            return ('no');
        }

        if (!$request->ajax()) return redirect('/');
        $user = new Adm_User();
        $user->idempleado = $request->idempleado;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        //$user->activo = '1';
        $user->save();
    }

    public function update(Request $request)
    { 
        if ($request->password != $request->password2) {
            return ('no');
        }
        
        $user = Adm_User::findOrFail($request->iduser); 
        $user->idempleado = $request->idempleado;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
    }

    public function registrar_roleuser(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'iduser' => 'unique:adm__roleusers',        
        ]);
         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }
        
        if (!$request->ajax()) return redirect('/');
        
        //capturamos los datos: iduser y los idrole (1 o mas roles)        
        $user = Adm_User::find($request->iduser);
        foreach($request->idrole as $ro) {            
            $user->roles()->attach($ro);
        }                    
    }
    
    public function actualizar_roleuser(Request $request)
    {
    //     $validator = Validator::make($request->all(), [
    //     'username' => 'unique:adm__users'
    //     ]);
         
    //     if ($validator->fails()) {
    //         return ($validator->messages()->first());
    //    }
        
        if (!$request->ajax()) return redirect('/');
        
        //capturamos los datos: iduser y los idrole (1 o mas roles)        
        $user = Adm_User::find($request->iduser);
        $user->roles()->sync($request->idrole);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update1(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), [
        'nomrol' => 'unique:adm__roles'
        ]);
         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }
        
        $role = Adm_Role::findOrFail($request->idrole); 
        $role->nomrol = $request->nomrol;
        $role->descripcionrol = $request->descripcionrol;
        $role->activo = '1';
        $role->save();
    }

    public function selectUsuario(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $usuarios = Adm_User::where('activo','=','1')
        ->select('id','username')->orderBy('username', 'asc')->get(); 
        return ['usuarios' => $usuarios];
    }

    public function selectRole(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $roles = Adm_Role::where('activo','=','1')
        ->select('idrole','nomrol', 'descripcionrol')->orderBy('idrole', 'asc')->get(); 
        return ['roles' => $roles];
    }
 
    public function desactivar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');
        $user = Adm_User::findOrFail($request->iduser);
        $user->activo = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = Adm_User::findOrFail($request->iduser);
        $user->activo = '1';
        $user->save();
    }

    public static function acceso_menu() {
        $user_sis = Auth::user()->username;
        $data = Adm_User::join('adm__roleusers','adm__users.id','adm__roleusers.iduser')
        ->join('adm__rolepermisos','adm__rolepermisos.idrole','adm__roleusers.idrole')
        ->join('adm__permisos','adm__rolepermisos.idpermiso','adm__permisos.idpermiso')
        ->join('par__ventanamodulos','adm__permisos.idventanamodulo','par__ventanamodulos.idventanamodulo')
        ->join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
        ->where ('adm__users.username','=',$user_sis)
        ->select ('adm__users.username', 'par__modulos.nommodulo','par__modulos.idmodulo')
        ->distinct('adm__users.username')
        ->orderby ('adm__users.username')->get();        
        return ($data);        
    }

    public static function listamodulos() {        
        $data1 = Par_Modulo::where ('activo','=','1')
        ->select ('par__modulos.nommodulo', 'par__modulos.idmodulo')
        ->orderby ('par__modulos.nommodulo')->get();        
        return ($data1);
        
    }

    //lista solo los formularios que tiene permisos por modulo y usuario
    public static function listaventanamodulos($idmodulo) {        
        $user_sis = Auth::user()->username;
        $data1 = Adm_User::join('adm__roleusers','adm__users.id','adm__roleusers.iduser')
        ->join('adm__rolepermisos','adm__rolepermisos.idrole','adm__roleusers.idrole')
        ->join('adm__permisos','adm__rolepermisos.idpermiso','adm__permisos.idpermiso')
        ->join('par__ventanamodulos','adm__permisos.idventanamodulo','par__ventanamodulos.idventanamodulo')
        ->join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
        ->where ('par__modulos.idmodulo','=',$idmodulo)
        ->where ('adm__users.username','=',$user_sis)
        ->select ('adm__users.username', 'par__modulos.nommodulo','par__modulos.idmodulo', 'par__ventanamodulos.nomventanamodulo','par__ventanamodulos.idventanamodulo')
        ->distinct('adm__users.username')
        ->orderby ('par__ventanamodulos.nomventanamodulo')->get();
        return ($data1);
    }

    public static function listaventanamodulostodo() {        
        $data1 = Par_Ventanamodulo::join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
        ->where ('par__ventanamodulos.activo','=','1')
        ->select ('par__ventanamodulos.idmodulo','par__ventanamodulos.nomventanamodulo','par__ventanamodulos.idventanamodulo','par__ventanamodulos.template')
        ->orderby ('par__ventanamodulos.nomventanamodulo')->get();
        return ($data1);    
    }

    public static function getUser() {    
        return Auth::user();    
    }
    
}
