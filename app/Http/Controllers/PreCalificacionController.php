<?php

namespace App\Http\Controllers;
use App\Payment_plans\PaymentPlansClass;
use App\AsinalssClass\MetodoAmortizacion;

use App\Par_productos_perfilcuenta;
use App\Pre_Calificacion;
use App\Socio;
use App\Par_Escala;
use App\Par_Producto;
use App\Con_Cuentasocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PreCalificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
 
    public function plandepagos(Request $request){
      // if (!$request->ajax()) return redirect('/');
        $plandepagos=new MetodoAmortizacion();
        return $plandepagos->metodofrances($request->idproducto,$request->meses,$request->montosolicitado,$request->interesDiferido,$request->seg);
        // return $plandepagos->metodofrances(6,120,15000,0,1);
    }
 
    public function getcuota(Request $request){
        if (!$request->ajax()) return redirect('/');
        $plandepagos=new PaymentPlansClass();
        return  $plandepagos->getFeeByAccumulationOfInterest($request->tasa,$request->meses,$request->monto,$request->fecha,$request->corte);
    }
    public function getfechas(Request $request){
       // if (!$request->ajax()) return redirect('/'); 
        return  DB::select('select getfechas() as fechas');
    }

    public function datos_aportes(Request $request)
    {  
        if (!$request->ajax()) return redirect('/');

        $raw = DB::raw("count(apo__aportes.numpapeleta) as total_aportes");
        $raw2= DB::raw("sum(apo__aportes.aporte) as total_bs");
        $raw3 = DB::raw("TIMESTAMPDIFF(YEAR, socios.fechaincorporacion, getfecha2()) as servicio");

        $idsocio = $request->idsocio;        
         
        $socios=Socio::join('apo__aportes','socios.numpapeleta','=','apo__aportes.numpapeleta')                
            ->select('socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta', 
            $raw, $raw2, $raw3)
            ->where('socios.idsocio', '=' ,$idsocio)
            ->groupBy('socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','socios.fechaincorporacion')
            ->paginate(10);

        return [
            'pagination' => [
                'total'        => $socios->total(),
                'current_page' => $socios->currentPage(),
                'per_page'     => $socios->perPage(),
                'last_page'    => $socios->lastPage(),
                'from'         => $socios->firstItem(),
                'to'           => $socios->lastItem(),
            ],
            'socios' => $socios
        ];
    }

    public function selectproducto(Request $request){
        if (!$request->ajax()) return redirect('/');
        $idmoneda = $request->idmoneda; 
 
        $producto = Par_producto::where('activo','=','1')
        ->where('idmoneda','=', $idmoneda)
        ->select('idproducto','nomproducto')->orderBy('nomproducto', 'asc')->get();
        return ['productos' => $producto];
    }
    public function selectCuentaBancarias(Request $request){
        if (!$request->ajax()) return redirect('/');
       
 
        $cuentas = Con_Cuentasocio::select('con__cuentasocios.idcuentasocio','con__entidadbancarias.siglaentidadbancaria','con__cuentasocios.numcuentasocio')
        ->join('con__entidadbancarias','con__cuentasocios.identidadbancaria','=','con__entidadbancarias.identidadbancaria')
        ->where('con__cuentasocios.activo','=','1')
        ->where('con__entidadbancarias.activo','=','1')
        ->where('con__cuentasocios.idsocio','=', $request->idsocio)
        ->orderBy('con__cuentasocios.idcuentasocio', 'asc')->get();
        return ['cuentas' => $cuentas];
    }
    public function search_Socio(Request $request){
        if (!$request->ajax()) return redirect('/');
    
            
            $textodetalle = explode(" ",$request->texto);
            $Sql='';
            if(sizeof($textodetalle)>1){
                 
                foreach($textodetalle as $valor){
                    if(empty($Sql)){
                        $Sql="SELECT * FROM socios where activo=1 and (numpapeleta like '%".$valor."%' or nombre like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or ci like '%".$valor."%')";
                    }else{
                        $Sql.=" and (numpapeleta like '%".$valor."%' or nombre like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or ci like '%".$valor."%')";
                    } 
                }
                $Sql.=" order by nombre";
            }else{
                $Sql="SELECT * FROM socios where (numpapeleta like '%".$request->texto."%' or nombre like '%".$request->texto."%' or apaterno like '%".$request->texto."%' or amaterno like '%".$request->texto."%' or ci like '%".$request->texto."%') and activo=1 order by nombre" ;
            } 
            $socios = DB::select($Sql);
            return ['socios' => $socios];
        }

    public function pre_listasocio(Request $request)
    {  
      if (!$request->ajax()) return redirect('/');

        
       $buscararray = array();  
if(!empty($request->buscar)){
    $buscararray = explode(" ",$request->buscar);
} 
         $raw = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.cantobligados+apo__total_aportes.cantjubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion)) as cantaportes");
        $raw2 = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.totalobligados+apo__total_aportes.totaljubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion)) as totalaportes");
        // $raw = DB::raw("if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion) as cantaportes");
        // $raw2 = DB::raw("if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion) as totalaportes");
        $raw3 = DB::raw("valida_historial_garante(socios.idsocio) as totalgarantias");
        
         
        
        if (sizeof($buscararray)>0){
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                } 
            } 
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,'socios.idestprestamos','par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->whereraw($sqls)
            ->orderBy('socios.nombre', 'asc')->paginate(10);
           
        }elseif(empty($request->id)){
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,'socios.idestprestamos','par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->orderBy('socios.nombre', 'asc')->paginate(10);

        }else{
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,'socios.idestprestamos','par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->where('socios.idsocio','=',$request->id)
            ->orderBy('socios.nombre', 'asc')->get()->toArray();
        }
        
        if(empty($request->id)){
            return [
                'pagination' => [
                    'total'        => $socios->total(),
                    'current_page' => $socios->currentPage(),
                    'per_page'     => $socios->perPage(),
                    'last_page'    => $socios->lastPage(),
                    'from'         => $socios->firstItem(),
                    'to'           => $socios->lastItem(),
                ],
                'socios' => $socios
            ];
        }else{
            return ['socios' => $socios ];
        }
        

    }
    public function pre_listasocio_lista(Request $request)
    {  
      if (!$request->ajax()) return redirect('/');

        
       $buscararray = array();  
if(!empty($request->buscar)){
    $buscararray = explode(" ",$request->buscar);
} 
         $raw = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.cantobligados+apo__total_aportes.cantjubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion)) as cantaportes");
        $raw2 = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.totalobligados+apo__total_aportes.totaljubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion)) as totalaportes");
        // $raw = DB::raw("if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion) as cantaportes");
        // $raw2 = DB::raw("if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion) as totalaportes");
        $raw3 = DB::raw("valida_historial_garante(socios.idsocio) as totalgarantias");
        $someVariable=$request->idpro;
        $sum = DB::raw("getPrestamos(socios.idsocio,$someVariable,0) as totalcuotas"); 
         
        
        if (sizeof($buscararray)>0){
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                } 
            } 
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,$sum,'socios.idestprestamos','par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->whereraw($sqls)
            ->where('socios.idsocio','!=',$request->id)
            ->orderBy('socios.nombre', 'asc')->get();
           
        }else{
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,$sum,'socios.idestprestamos','par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->where('socios.idsocio','!=',$request->id)
            ->orderBy('socios.nombre', 'asc')->limit(10)->get();

        } 
 
        return ['socios' => $socios];
        

    }


    public function pre_listasocio_lista_beneficiario(Request $request)
    {  
    if (!$request->ajax()) return redirect('/');

        
       $buscararray = array();  
if(!empty($request->buscar)){
    $buscararray = explode(" ",$request->buscar);
} 
         $raw = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.cantobligados+apo__total_aportes.cantjubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion)) as cantaportes");
        $raw2 = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.totalobligados+apo__total_aportes.totaljubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion)) as totalaportes");
        $raw3 = DB::raw("valida_historial_garante(socios.idsocio) as totalgarantias");
        $someVariable=$request->idpro;
        $sum = DB::raw("getPrestamos(socios.idsocio,$someVariable,0) as totalcuotas"); 
         
        
        if (sizeof($buscararray)>0){
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                } 
            } 
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,$sum,'socios.idestprestamos','par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->whereraw($sqls) 
            ->orderBy('socios.nombre', 'asc')->get();
           
        }elseif(empty($request->id)){
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,$sum,'socios.idestprestamos','par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->orderBy('socios.nombre', 'asc')->limit(10)->get();

        }else{
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,$sum,'socios.idestprestamos','par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->where('socios.idsocio','=',$request->id)
            ->orderBy('socios.nombre', 'asc')->limit(10)->get(); 
        }
 
        return ['socios' => $socios]; 
    }


    public function pre_listasocio_prueba(Request $request)
    {  
     if (!$request->ajax()) return redirect('/');

        
       $buscararray = array();  
if(!empty($request->buscar)){
    $buscararray = explode(" ",$request->buscar);
} 
        $raw = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.cantobligados+apo__total_aportes.cantjubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion)) as cantaportes");
        $raw2 = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.totalobligados+apo__total_aportes.totaljubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion)) as totalaportes");
     // $raw = DB::raw("if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion) as cantaportes");
     // $raw2 = DB::raw("if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion) as totalaportes");
       
        $raw3 = DB::raw("valida_historial_garante(socios.idsocio) as totalgarantias");
        
         
        
        if (sizeof($buscararray)>0){
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                } 
            } 
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,'par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->whereraw($sqls)
            ->orderBy('socios.nombre', 'asc')->get();
           
        }
        elseif(empty($request->id)){
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,'par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->orderBy('socios.nombre', 'asc')->get();
        }else{
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,'par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->where('socios.idsocio','=',$request->id)
            ->orderBy('socios.nombre', 'asc')->get();
        }
        
        return ['socios' => $socios];
    }

    public function pre_listasociogarante(Request $request)
    {  
      if (!$request->ajax()) return redirect('/');

        
       $buscararray = array();  
if(!empty($request->buscar)){
    $buscararray = explode(" ",$request->buscar);
} 
         
        $raw = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.cantobligados+apo__total_aportes.cantjubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion)) as cantaportes");
        $raw2 = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.totalobligados+apo__total_aportes.totaljubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion)) as totalaportes");
        // $raw = DB::raw("if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion) as cantaportes");
        // $raw2 = DB::raw("if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion) as totalaportes");
       
        $raw3 = DB::raw("valida_historial_garante(socios.idsocio) as totalgarantias");
        $someVariable=$request->idpro;
        $sum = DB::raw("getPrestamos(socios.idsocio,$someVariable,0) as totalcuotas"); 
        
         
        
        if (sizeof($buscararray)>0){
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" or (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                } 
            } 
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,$sum,'par_departamentos.abrvdep','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->whereraw($sqls)
            ->whereraw('valida_historial_garante(socios.idsocio)<2')
            ->where('socios.idsocio','!=',$request->id)
            ->orderBy('socios.nombre', 'asc')->limit(20)->get();
           
        }
        else{
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2,$sum,'par_departamentos.abrvdep','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->where('socios.idsocio','!=',$request->id)
            ->whereraw('valida_historial_garante(socios.idsocio)<2')
            ->orderBy('socios.nombre', 'asc')->limit(20)->get();
        }
        
        $formulascobranza = Par_productos_perfilcuenta::join('par__productos','par__productos.cobranza_perfil_ascii','=','par__productos__perfilcuentas.idperfilcuentamaestro')
            ->select('par__productos.cobranza_perfil_ascii','par__productos__perfilcuentas.idperfilcuentadetalle',
            'par__productos__perfilcuentas.valor_abc',
            'par__productos__perfilcuentas.formula',
            'par__productos__perfilcuentas.iscomision',
            'par__productos__perfilcuentas.iscargo')
            ->where('par__productos__perfilcuentas.activo','=','1')
            ->where('par__productos.idproducto','=',$request->idpro)
            ->get(); 
        
     $productos = Par_Producto::join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->join('par__productos__factores','par__productos.idfactor','=','par__productos__factores.idfactor') 
            ->select('par__productos__factores.aprobacion','par__productos.idescala','par__productos.garantes','par__productos.max_prestamos','par__productos.lote','par__productos.activar_garante','par__productos.cancelarprestamos','par__productos.idfactor','par__productos.tasa','par__productos.codproducto','par__productos.idproducto','par__productos.nomproducto','par__productos.plazominimo','par__productos.plazomaximo','par__productos.activo','par__monedas.codmoneda','par__monedas.idmoneda','par__monedas.tipocambio')
            ->where('par__productos.idproducto','=',$request->idpro)  
            ->get();

   
            //$total=(DB::select("select sum(cuota) as total FROM par__prestamos WHERE  (fecharegistro BETWEEN getfecha() AND ? )and idsocio=? and idestado=3", array($request->fechapapeleta,$request->idsocio)))[0]->total;
             

        return ['garantes' => $socios,'cobranza'=>$formulascobranza,'productos'=>$productos];
    }
    public function pre_getgarantes(Request $request)
    {  
      if (!$request->ajax()) return redirect('/');

         ///cambiar sql
         
        $raw = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.cantobligados+apo__total_aportes.cantjubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.cantobligados,apo__total_aportes.cantjubilacion)) as cantaportes");
        $raw2 = DB::raw("if((SELECT count(*) FROM daa__devolucions dev  where dev.idsocio =socios.idsocio and dev.idtipodevolucion between 1 and 2)=0,(apo__total_aportes.totalobligados+apo__total_aportes.totaljubilacion),if(apo__total_aportes.obligatorios=0,apo__total_aportes.totalobligados,apo__total_aportes.totaljubilacion)) as totalaportes");
        $raw3 = DB::raw("valida_historial_garante(socios.idsocio) as totalgarantias");
      
        
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw3,$raw,$raw2, 'socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->where('socios.idsocio','!=',$request->id) 
            ->orderBy('socios.nombre', 'asc')->get();
        
            $productos = Par_Producto::join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->join('par__productos__factores','par__productos.idfactor','=','par__productos__factores.idfactor') 
            ->select('par__productos__factores.aprobacion','par__productos.idescala','par__productos.garantes','par__productos.max_prestamos','par__productos.lote',
            'par__productos.activar_garante','par__productos.cancelarprestamos','par__productos.idfactor','par__productos.tasa','par__productos.codproducto',
            'par__productos.idproducto','par__productos.nomproducto','par__productos.plazominimo','par__productos.plazomaximo','par__productos.activo',
            'par__monedas.codmoneda','par__monedas.idmoneda','par__monedas.tipocambio')
            ->where('par__productos.idproducto','=',$request->idpro)  
            ->get();
      

        return ['garantes' => $socios,'productos'=>$productos];
    }

    function redondeo_valor($valor){
        return round($valor,2);
    }
    public function getsaldocapital_desembolso(Request $request)
    { 
       if (!$request->ajax()) return redirect('/'); 
      
        //  $total=DB::select("select  ROUND(getcapitaltotal(?,?,?),2) as total", array($request->idsocio,$request->idpro,$request->cancelar));

         $afinanciar=0;
         
         $validate1=DB::table('par__prestamos')->where('idsocio',$request->idsocio)->where('idestado',1)->count();
         $validate2=DB::table('par__prestamos')->where('idsocio',$request->idsocio)
         ->where(function($query) {
            $query->where('apro_conta',0)
                ->orwhere('apro_conta',5);
            })->whereBetween('idestado',[2,3])->count();
        if($validate1>0){
            $afinanciar=-35;
        }elseif($validate2>0){
            $afinanciar=-25;
        }elseif($request->cancelar==1){ 
         $prestamossocio=DB::table('par__prestamos')
         ->join('par__productos','par__prestamos.idproducto','=','par__productos.idproducto') 
         ->join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda') 
         ->select('par__prestamos.idprestamo')
         ->where('par__productos.cobranza_perfil_refi','!=','0') 
         ->where('par__prestamos.idsocio',$request->idsocio)
         ->whereBetween('par__prestamos.idestado',[2,3])->get();
              $metodo=new MetodoAmortizacion();

            $cortesistema=DB::select('select checkcut() as corte')[0]->corte; 
            $cobrar_cuota_transito=1;
            $idmodulo=3;//prestamos =3
         
                foreach($prestamossocio as $valor){
                    
                    $metodoout= $metodo->cobranza_refinanciamiento($valor->idprestamo,$cobrar_cuota_transito,'Refinanciamiento - automatico',$idmodulo,$valor->idprestamo);
                    if($metodoout['conta']==1){ 
                        throw new ModelNotFoundException("El socio tiene prestamos pendientes de aprovación por contabilidad.");  
                    }elseif($metodoout['conta']==2){
                        throw new ModelNotFoundException("Existe una variación con el saldo capital y el monto solicitado, contactese con el administrador del Sistema parta verificación de datos."); 
                    }elseif($metodoout['conta']==3){
                        throw new ModelNotFoundException("El perfil del producto seleccionado no contiene la configuracion de cobranza por refinanciamiento."); 
                    } 

                    $afinanciar+=$metodoout['data']['cuotafinal'];
                }   
                        $moneda=DB::table('par__productos')->select('par__monedas.tipocambio')
                        ->join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda') 
                        ->where('par__productos.idproducto',$request->idpro)->first(); 
                        
            $afinanciar=$this->redondeo_valor($afinanciar/$moneda->tipocambio);
        }
         
     return ['capital'=>$afinanciar];
    }

    public function reporte1(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
      
        return config('app.rutaPrestamos');
    }

}
