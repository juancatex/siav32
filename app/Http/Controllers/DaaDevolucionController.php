<?php

namespace App\Http\Controllers;

use App\Daa_Devolucion;
use App\Daa_Descuento;
use App\Daa_Tipodevolucion;
use App\Daa_Estudiomatematico;
use App\Con_Perfilcuentadetalle;
use App\Con_Perfilcuentamaestro;
use App\Con_Asientomaestro;
use App\Apo_Aporte;
use App\Socio;
use App\Apo_Total_Aporte;
use App\Config;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\AsinalssClass\AsientoMaestroClass;
use Illuminate\Support\Facades\Auth;

class DaaDevolucionController extends Controller
{

    public function store(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'nomtipodevolucion' => 'unique:daa__tipodevolucions'
        // ]);         
        // if ($validator->fails()) {
        //     return ($validator->messages()->first());
        // }
        
        if (!$request->ajax()) return redirect('/');        

        //para generar el asiento contable
        $fecharegistro=(DB::select("select getfecha() as total"))[0]->total;  //fecha del sistema
        
        //obteniendo numpapeleta
        $numpapeleta=(DB::select("select getpapeleta($request->idsocio) as papeleta"))[0]->papeleta;

        //obteniendo los id de las cuentas segin el perfil de cuenta
        $cuentas = Con_Perfilcuentadetalle::select ('idcuenta','tipocargo')
        ->where('idperfilcuentamaestro','=',$request->idperfilcuentamaestro)
        ->get();
        
        //armado del vector a ser llenado
        foreach ($cuentas as $cue) {           
            if ($cue->tipocargo == 'd') {
                $valor[] = array (
                    "idcuenta"=>$cue->idcuenta,
                    "subcuenta"=>$numpapeleta,
                    "documento"=>$request->tipodocumento,
                    "moneda" => 'bs',
                    "monto"=>$request->totaldevdescuento
                );
            }
            else if ($cue->tipocargo == 'h') {
                $valor[] = array (
                    "idcuenta"=>$cue->idcuenta,
                    "subcuenta"=>$numpapeleta,
                    "documento"=>$request->tipodocumento,
                    "moneda" => 'bs',
                    "monto"=>$request->totaldevdescuento*(-1)
                );
            }           
        }

        $asientomaestro = new AsientoMaestroClass();
        $respuesta=$asientomaestro->AsientosMaestroArray(
            $request->idperfilcuentamaestro,
            $request->tipodocumento,
            $request->numerodocumento,
            $request->glosa,
            $valor,
            $request->idmodulo,  //obtener el modulo
            $fecharegistro);

        $devolucion = new Daa_Devolucion();
        $devolucion->idsocio = $request->idsocio;
        $devolucion->idtipodevolucion = $request->idtipodevolucion;
        $devolucion->idestudiomatematico = $request->idestudiomatematico;
        $devolucion->totalparcial = $request->totalparcial;
        $devolucion->totalaporte = $request->totalaporte;
        $devolucion->rendimiento = $request->rendimiento;
        $devolucion->totaldevolucion = $request->totaldevdescuento;
        $devolucion->anualnominal = $request->anualnominal;
        $devolucion->capitalizacionmensual = $request->capitalizacionmensual;
        
        // if ($request->cobro == 1) { //si es por cumplimiento
        //     $devolucion->cobro1 = 1;
        //     $devolucion->cobro2 = 0;
        // }            
        // else if ($request->cobro == 2) { //cobro ambos, cumplimiento y jubilacion
        //     $devolucion->cobro1 = 1;
        //     $devolucion->cobro2 = 1;
        // }
            
        $devolucion->idperfilcuentamaestro = $request->idperfilcuentamaestro;
        //$devolucion->validadocontabilidad = 0; // por defecto no esta validado
        $devolucion->tipodocumento = $request->tipodocumento;
        $devolucion->numerodocumento = $request->numerodocumento;
        $devolucion->glosa = $request->glosa;
        $devolucion->idasientomaestro = $respuesta;
        $devolucion->activo = '1';
        $devolucion->idusuario = Auth::user()->username;
        $devolucion->save();
        
        //valores del descuentos:
        $otro = $devolucion->iddevolucion;
        $descuento = new Daa_Descuento();
        $descuento->iddevolucion = $otro;
        $descuento->emergencia = $request->emergencia;
        $descuento->regular = $request->regular;
        $descuento->servicio = $request->servicio;
        $descuento->juridica = $request->juridica;
        $descuento->retencion = $request->retencion;
        $descuento->activo = '1';
        $descuento->save();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Daa_Devolucion  $daa_Devolucion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        if (!$request->ajax()) return redirect('/');                

        //para generar el asiento contable
        $fecharegistro=(DB::select("select getfecha() as total"))[0]->total;  //fecha del sistema
        
        //obteniendo nupapeleta
        $numpapeleta=(DB::select("select getpapeleta($request->idsocio) as papeleta"))[0]->papeleta;

        //caso 1: en caso de que el operador tenga que actualizar sin que contabilidad dio la alerta
        //debemos de obetener el idmaestro anterior y actualizar el estado a 2 (borrado logico) en asientoamestro
        $asientoanterior = Daa_Devolucion::select('idasientomaestro')
        ->where('iddevolucion','=',$request->iddevolucion)->get(); 
        foreach ($asientoanterior as $ant) {
            $anterior = $ant->idasientomaestro;
        }                   
        $actualizaasientoanterior = Con_Asientomaestro::findOrFail($anterior);
        $actualizaasientoanterior->estado = 2; //borrado logico
        $actualizaasientoanterior->save();

        //obteniendo los id de las cuentas segun el perfil de cuenta
        $cuentas = Con_Perfilcuentadetalle::select ('idcuenta','tipocargo')
        ->where('idperfilcuentamaestro','=',$request->idperfilcuentamaestro)
        ->get();
        
        //armado del vector a ser llenado
        foreach ($cuentas as $cue) {           
            if ($cue->tipocargo == 'd') {
                $valor[] = array (
                    "idcuenta"=>$cue->idcuenta,
                    "subcuenta"=>$numpapeleta,
                    "documento"=>$request->tipodocumento,
                    "moneda" => 'bs',
                    "monto"=>$request->totaldevdescuento
                );
            }
            else if ($cue->tipocargo == 'h') {
                $valor[] = array (
                    "idcuenta"=>$cue->idcuenta,
                    "subcuenta"=>$numpapeleta,
                    "documento"=>$request->tipodocumento,
                    "moneda" => 'bs',
                    "monto"=>$request->totaldevdescuento*(-1)
                );
            }           
        }       

        $asientomaestro = new AsientoMaestroClass();
        $respuesta=$asientomaestro->AsientosMaestroArray(
            $request->idperfilcuentamaestro,
            $request->tipodocumento,
            $request->numerodocumento,
            $request->glosa,
            $valor,
            $request->idmodulo,  //obtener el modulo
            $fecharegistro);
        
        $devolucion = Daa_Devolucion::findOrFail($request->iddevolucion);        
        $devolucion->idsocio = $request->idsocio;
        $devolucion->idtipodevolucion = $request->idtipodevolucion;
        $devolucion->idestudiomatematico = $request->idestudiomatematico;
        $devolucion->totalparcial = $request->totalparcial;
        $devolucion->totaldevolucion = $request->totaldevdescuento;
        $devolucion->totalparcial = $request->totalparcial;
        $devolucion->totalaporte = $request->totalaporte;
        $devolucion->anualnominal = $request->anualnominal;
        $devolucion->capitalizacionmensual = $request->capitalizacionmensual;
        $devolucion->idperfilcuentamaestro = $request->idperfilcuentamaestro;
        $devolucion->tipodocumento = $request->tipodocumento;
        $devolucion->numerodocumento = $request->numerodocumento;
        $devolucion->idasientomaestro = $respuesta;
        $devolucion->glosa = $request->glosa;
        $devolucion->activo = '1';
        $devolucion->save();
        
        //para la tabla de descuentos
        
        $descuento1 = Daa_Descuento::where('iddevolucion','=',$request->iddevolucion)->select('iddescuentos')->get();        
        foreach ($descuento1 as $des) {
            $iddesc = $des->iddescuentos;
        }
        $descuento = Daa_Descuento::findOrFail($iddesc);
        $descuento->iddevolucion = $request->iddevolucion;
        $descuento->emergencia = $request->emergencia;
        $descuento->regular = $request->regular;
        $descuento->servicio = $request->servicio;
        $descuento->daaro = $request->daaro;
        $descuento->juridica = $request->juridica;
        $descuento->retencion = $request->retencion;
        $descuento->activo = '1';
        $descuento->save();        
    }

    public function selectPerfilcuenta(Request $request){
        if (!$request->ajax()) return redirect('/');

            $perfilcuenta = Con_Perfilcuentamaestro::where('activo', '=', '1')->where('idmodulo','=',$request->idmodulo)->get(); 
            return ['perfilcuenta' => $perfilcuenta];        
    }

    public function selectTipodevolucion(Request $request){
        if (!$request->ajax()) return redirect('/');

        $tipo_dev = $request->tipo; 
        $cantaportes = $request->cantaportes;

        if ($tipo_dev == 0 && $cantaportes >= 300) {   //eligio devolucion por cumplimiento
            $tipodevolucion1 = Daa_Tipodevolucion::select('daa__tipodevolucions.*')
            ->where('activo','=','1')
            ->where('idtipodevolucion','=','1')
            ->get();                
        } 
        else if ($tipo_dev == 0 && $cantaportes < 300){ //devolucion retiro o fallecido por cumplimiento
            $tipodevolucion1 = Daa_Tipodevolucion::select('daa__tipodevolucions.*')
            ->where('activo','=','1')
            ->where('idtipodevolucion','=','3')->orWhere('idtipodevolucion','=','4')
            ->get();                
        }

        if ($tipo_dev == 1 && $cantaportes == 420) {   //eligio devolucion por jubilacion
            $tipodevolucion1 = Daa_Tipodevolucion::select('daa__tipodevolucions.*')
            ->where('activo','=','1')
            ->where('idtipodevolucion','=','2')
            ->get();                
        } 
        else if ($tipo_dev == 1 && $cantaportes < 420){ //devolucion retiro o fallecido por jubilacion
            $tipodevolucion1 = Daa_Tipodevolucion::select('daa__tipodevolucions.*')
            ->where('activo','=','1')
            ->where('idtipodevolucion','=','5')->orWhere('idtipodevolucion','=','6')
            ->get();                
        }
        
        return ['tipodevolucion' => $tipodevolucion1];          
    }

    public function pre_listasociodev(Request $request) {  
      if (!$request->ajax()) return redirect('/');
        
        $buscararray = array();  
        if(!empty($request->buscar)){
        $buscararray = explode(" ",$request->buscar);
        } 
        $raw = DB::raw("(apo__total_aportes.cantobligados + apo__total_aportes.cantjubilacion) as cantaportes");
        $raw1 = DB::raw("(apo__total_aportes.cantjubilacion) as cantaportesjubilado");
        $raw2 = DB::raw("(apo__total_aportes.cantobligados) as cantaportesobligado");
        $raw3 = DB::raw("(select GROUP_CONCAT(b.nomtipodevolucion SEPARATOR ',') as resu 
        from db_sia.daa__devolucions a, 
        db_sia.daa__tipodevolucions b
        where a.idsocio=socios.idsocio and a.activo=1 
        and a.idtipodevolucion=b.idtipodevolucion) as estado_daaro");
        $raw4 = DB::raw("(apo__total_aportes.totalobligados + apo__total_aportes.totaljubilacion) as totalaportes");
        $raw5 = DB::raw("(select b.estado from daa__devolucions a, con__asientomaestros b
            where a.idasientomaestro=b.idasientomaestro and a.idsocio=socios.idsocio and a.activo=1 
            and (a.idtipodevolucion in (1,3,4))) as estado_cont_cump");
        $raw6 = DB::raw("(select b.estado from daa__devolucions a, con__asientomaestros b
            where a.idasientomaestro=b.idasientomaestro and a.idsocio=socios.idsocio and a.activo=1 
            and (a.idtipodevolucion in (2,5,6))) as estado_cont_jubi");    
        $raw7 = DB::raw("(select b.cod_comprobante  from daa__devolucions a, con__asientomaestros b
            where a.idasientomaestro=b.idasientomaestro and a.idsocio=socios.idsocio and a.activo=1 
            and (a.idtipodevolucion in (1,3,4))) as comprobante_cump");
        $raw8 = DB::raw("(select b.cod_comprobante  from daa__devolucions a, con__asientomaestros b
            where a.idasientomaestro=b.idasientomaestro and a.idsocio=socios.idsocio and a.activo=1 
            and (a.idtipodevolucion in (2,5,6))) as comprobante_jubi");    
        // $raw5 = DB::raw("(select GROUP_CONCAT(concat(b.estado,'*',a.idtipodevolucion,'*',b.cod_comprobante) SEPARATOR ',')
        //     from daa__devolucions a, con__asientomaestros b
        //     where a.idasientomaestro=b.idasientomaestro
        //     and a.idsocio=socios.idsocio) as contabilidad");
                
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
            ->select($raw,$raw1,$raw2,$raw3,$raw4,$raw5,$raw6,$raw7,$raw8,'par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            ->whereraw($sqls)
            ->orderBy('socios.nombre', 'asc')->paginate(10);
           
        }
        else{
            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select($raw,$raw1,$raw2,$raw3,$raw4,$raw5,$raw6,$raw7,$raw8,'par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo','socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado',)
            ->orderBy('socios.nombre', 'asc')->paginate(10); 
        }
        
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


    //revertir una operacion
    public function revertir(Request $request){
        if (!$request->ajax()) return redirect('/');
        $idsocio = $request->idsocio;         
        $estado = $request->estado;
        $observado = $request->observado;

        //encontramos en id de devolucion
        $iddevolucion = Daa_Devolucion::join('con__asientomaestros','daa__devolucions.idasientomaestro','con__asientomaestros.idasientomaestro')
        ->select('daa__devolucions.iddevolucion')
        ->where('daa__devolucions.idsocio','=',$idsocio)
        ->where('con__asientomaestros.estado','=',$estado)
        ->get();
        
        foreach ($iddevolucion as $id) {
            $iddevolucion_data =  $id->iddevolucion;
        }

        //actualizamos el datos en devolucion
        $devolucion = Daa_Devolucion::findOrFail($iddevolucion_data);                
        $devolucion->activo = '0';
        $devolucion->revertido_obs = $observado;
        $devolucion->save();
    }

    //obteniendo los datos de una devolucion
    public function devregistrado(Request $request){
        if (!$request->ajax()) return redirect('/');
        $idsocio = $request->idsocio;         

        $socioRegistrado = Daa_Devolucion::join('daa__descuentos','daa__devolucions.iddevolucion','daa__descuentos.iddevolucion')
        ->join('con__asientomaestros','daa__devolucions.idasientomaestro','con__asientomaestros.idasientomaestro')
        ->select ('con__asientomaestros.estado', 'daa__descuentos.*', 'daa__devolucions.*')
        ->where('daa__devolucions.idsocio','=',$idsocio)
        ->where('daa__devolucions.activo','=',1)
        //->where('con__asientomaestros.estado','!=',0)->toSql(); echo $socioRegistrado; exit();
        ->get();

        $raw1 = DB::raw("min(apo__aportes.fechaaporte) as minimo");
        $raw2 = DB::raw("max(apo__aportes.fechaaporte) as maximo");
        //recuperamos los valores de los aportes
        $aportesminimo = Apo_Aporte::join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
        ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
        ->select($raw1)
        ->where('apo__aportes.activo','=','1')
        // ->where('apo__idsaportes.tipodevolucion','=','0')
        ->where('socios.idsocio','=',$idsocio)->get();

        $aportesmaximo = Apo_Aporte::join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
        ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
        ->select($raw2)
        ->where('apo__aportes.activo','=','1')
        // ->where('apo__idsaportes.tipodevolucion','=','0')
        ->where('socios.idsocio','=',$idsocio)->get();

        return ['registrado' => $socioRegistrado,'aporteminimo'=>$aportesminimo,'aportemaximo'=>$aportesmaximo];
    }

    //verificar si esta validao contablemnete el jubiulado
    public function validaconta(Request $request) {
        if (!$request->ajax()) return redirect('/');
        $idsocio = $request->idsocio;         
        
        $result = Daa_Devolucion::join('daa__descuentos','daa__devolucions.iddevolucion','daa__descuentos.iddevolucion')
        ->join('con__asientomaestros','daa__devolucions.idasientomaestro','con__asientomaestros.idasientomaestro')
        ->select ('con__asientomaestros.estado', 'daa__descuentos.*', 'daa__devolucions.*')
        ->where('daa__devolucions.idsocio','=',$idsocio)
        ->where('con__asientomaestros.estado','=',1)
        ->whereIn('daa__devolucions.idtipodevolucion',[2,5,6])        
        ->get();
        return ['v_jubilado' => $result];
    }

    //obteniendo los datos de una devolucion jubilado
    public function devregistradojub(Request $request){
        if (!$request->ajax()) return redirect('/');
        $idsocio = $request->idsocio;         
        $raw = DB::raw("count(*) as total");
        $socioRegistrado = Daa_Devolucion::join('daa__descuentos','daa__devolucions.iddevolucion','daa__descuentos.iddevolucion')
        ->select($raw, 'daa__descuentos.*','daa__devolucions.*')
        ->where('daa__devolucions.idsocio','=',$idsocio)
        ->where('daa__devolucions.idtipodevolucion','!=','1')->get();

        $raw1 = DB::raw("min(apo__aportes.fechaaporte) as minimo");
        $raw2 = DB::raw("max(apo__aportes.fechaaporte) as maximo");
        //recuperamos los valores de los aportes
        $aportesminimo = Apo_Aporte::join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
        ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
        ->select($raw1)
        ->where('apo__aportes.activo','=','1')
        // ->where('apo__idsaportes.tipodevolucion','=','1')
        ->where('socios.idsocio','=',$idsocio)->get();

        $aportesmaximo = Apo_Aporte::join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
        ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
        ->select($raw2)
        ->where('apo__aportes.activo','=','1')
        // ->where('apo__idsaportes.tipodevolucion','=','1')
        ->where('socios.idsocio','=',$idsocio)->get(); 

        return ['registrado' => $socioRegistrado,'aporteminimo'=>$aportesminimo,'aportemaximo'=>$aportesmaximo];
    }

    public function calculomatematico(Request $request){
        if (!$request->ajax()) return redirect('/');

        $idtipodevolucion = $request->idtipodevolucion;  //2
        $idestudiomatematico = $request->idestudiomatematico;  //1
        $numpapeleta = $request->numpapeleta;  
        $cantidadaportes = $request->cantidadaportes; //300        
        $anual = $request->anual;
        $capital = $request->capital;

        //obtenemos las formula de la tabla de estudio matematico
        $dataformula = Daa_Estudiomatematico::select('idestudiomatematico','formula')->where('activo','=','1')->where('idestudiomatematico','=',$idestudiomatematico)->get();
        foreach ($dataformula as $formulas) {
            $formula = $formulas->formula;
        }
        $formu = explode(';', $formula);         
                       
        //obtenemos el porcentaje de retencion, aportes minimo y validado p                
        $datosdevolucion = Daa_Tipodevolucion::select('aporteminimo','porcentaje','valido')
        ->where('idtipodevolucion','=',$idtipodevolucion)
        ->get();
                
        $total_aporte = Apo_Total_Aporte::where('numpapeleta','=',$numpapeleta)->get();

        foreach ($total_aporte as $totapo) {
            $obligatorios = $totapo->obligatorios;
            $jubilados = $totapo->jubilados;
            $cantobligatorios = $totapo->cantobligados;
        }
        
        //echo 'asdf: '. $obligatorios .'-'. $jubilados .'-'. $idtipodevolucion.'-'. $cantidadaportes; exit();

        if ($obligatorios==1 && $jubilados==0 && $idtipodevolucion==1) {  //  que sea cumplimiento  -- con menos de los aportes necesario para cumplimiento, osea  < 300
            $rawSuma=DB::raw("sum(apo__aportes.aporte) as aporte");
            $rawPeriodo=DB::raw("distinct(concat(year(fechaaporte) ,'', MONTH(fechaaporte))) as periodo");
            $aportes = Apo_Aporte::join('apo__tipoaportes','apo__aportes.idtipoaporte','apo__tipoaportes.idtipoaporte')
            ->join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
            ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
            ->where('apo__aportes.activo','=','1')
            ->where('apo__idsaportes.tipodevolucion','=','1')
            ->where ('apo__aportes.numpapeleta','=',$numpapeleta)
            ->GroupBy('periodo')
            ->OrderBy('apo__aportes.fechaaporte')
            ->select($rawPeriodo,$rawSuma)            
            ->get(); 
        }
        else if ($obligatorios==1 && $jubilados==1 && $idtipodevolucion==2){ //si es jubilado
            $rawSuma=DB::raw("sum(apo__aportes.aporte) as aporte");
            $rawPeriodo=DB::raw("distinct(concat(year(fechaaporte) ,'', MONTH(fechaaporte))) as periodo");
            $aportes = Apo_Aporte::join('apo__tipoaportes','apo__aportes.idtipoaporte','apo__tipoaportes.idtipoaporte')
            ->join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
            ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
            ->where('apo__aportes.activo','=','1')
            ->where ('apo__aportes.numpapeleta','=',$numpapeleta)
            ->GroupBy('periodo')
            ->having('apo__idsaportes.tipodevolucion','=','2')
            ->OrderBy('apo__aportes.fechaaporte')
            ->select($rawPeriodo,'apo__idsaportes.tipodevolucion',$rawSuma)            
            ->get();
        }
        else if ($obligatorios==0 && $jubilados==0 && ($idtipodevolucion==1 || $idtipodevolucion==3 || $idtipodevolucion==4)){   // 300 retiro o fallecido --  si cant aporte > 300 && < 420 tomamos los primeros 300
            $rawSuma=DB::raw("sum(apo__aportes.aporte) as aporte");
            $rawPeriodo=DB::raw("distinct(concat(year(fechaaporte) ,'', MONTH(fechaaporte))) as periodo");
            $aportes = Apo_Aporte::join('apo__tipoaportes','apo__aportes.idtipoaporte','apo__tipoaportes.idtipoaporte')
            ->join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
            ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
            ->where('apo__aportes.activo','=','1')
            ->where ('apo__aportes.numpapeleta','=',$numpapeleta)
            ->GroupBy('periodo')
            ->having('apo__idsaportes.tipodevolucion','=','0')
            ->OrderBy('apo__aportes.fechaaporte')
            ->select($rawPeriodo,'apo__idsaportes.tipodevolucion',$rawSuma)            
            ->get();
        }

        else if ($obligatorios==1 && $jubilados==0 && ($idtipodevolucion==2 || $idtipodevolucion==5 || $idtipodevolucion==6)){
            $rawSuma=DB::raw("sum(apo__aportes.aporte) as aporte");
            $rawPeriodo=DB::raw("distinct(concat(year(fechaaporte) ,'', MONTH(fechaaporte))) as periodo");
            $aportes = Apo_Aporte::join('apo__tipoaportes','apo__aportes.idtipoaporte','apo__tipoaportes.idtipoaporte')
            ->join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
            ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
            ->where('apo__aportes.activo','=','1')
            ->where ('apo__aportes.numpapeleta','=',$numpapeleta)
            ->GroupBy('periodo')
            ->having('apo__idsaportes.tipodevolucion','=',0)
            ->OrderBy('apo__aportes.fechaaporte')
            ->select($rawPeriodo,'apo__idsaportes.tipodevolucion',$rawSuma)            
            ->get();
        }
        else {
            return 0;   //devuelve error        
        }
           
        //total de devolucion = totalaportado + total_rendimiento       
        $j=round($anual/100,4);   //interes anual nomical
        $m=$capital;      //capital mensual
        $suma=0; 
        $p=0;       //periodo
        $var0=0;
        $aporteacumulado=0;                
        $intacumulado=0;        
        $salida=[]; $aux=0;
        foreach ($aportes as $apo) {                                    
            $p++; 
            //$var0 = $apo->aporte * pow((1+($j/$m)),($cantidadaportes+1-$p));
            eval($formu[0].';');
            $suma = $suma + $var0;

            //datos para mandar a la pagina de detalles
            $salida['periodo'][$aux] = $p;
            $periodo1 = substr($apo->periodo,0,4); $periodo2 = substr($apo->periodo,4,2); if (strlen($periodo2)==1) $periodo2='0'.$periodo2; 
            $salida['fecha'][$aux] = $periodo2.'-'.$periodo1;
            $salida['aporte'][$aux] = round($apo->aporte, 2);
            $var0=$aporteacumulado += $apo->aporte;
            $salida['apoacu'][$aux] = round($var0,2);
            //$var1 = (pow((1+($j/$m)),($cantidadaportes+1-$p))-1); 
            eval($formu[1].';');
            $salida['factor'][$aux] = round($var1,6);
            $var2 = $var1 * $apo->aporte; 
            $salida['intact'][$aux] = round($var2,6);
            $intacumulado += $var2;
            $salida['intacu'][$aux] = round($intacumulado,2); 
            $var3 = $apo->aporte * pow((1+($j/$m)),($cantidadaportes+1-$p)); 
            $salida['total'][$aux] = round($var3,4);

            $aux++;
        }

        //para mandar las fechas de aporte
        $rawmin=DB::raw("min(apo__aportes.fechaaporte) as minimo");
        $rawmax=DB::raw("max(apo__aportes.fechaaporte) as maximo");

        if ($obligatorios==0) { //si tiene menos de aportes necesarios
            $fechasaportes = Apo_Aporte::join('apo__tipoaportes','apo__aportes.idtipoaporte','apo__tipoaportes.idtipoaporte') 
            ->join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
            ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
            ->select($rawmin, $rawmax)
            ->where('apo__aportes.activo','=','1')
            // ->where('apo__idsaportes.tipodevolucion','=','0')
            ->where('socios.numpapeleta','=',$numpapeleta)
            ->OrderBy('apo__aportes.fechaaporte')->get();
        }
        else {
            $fechasaportes = Apo_Aporte::join('apo__tipoaportes','apo__aportes.idtipoaporte','apo__tipoaportes.idtipoaporte') 
            ->join('socios','apo__aportes.numpapeleta','socios.numpapeleta')
            ->join('apo__idsaportes','apo__aportes.idaporte','apo__idsaportes.idaporte')
            ->select($rawmin, $rawmax)
            ->where('apo__aportes.activo','=','1')
            // ->where('apo__idsaportes.tipodevolucion','=','1')
            ->where('socios.numpapeleta','=',$numpapeleta)
            ->OrderBy('apo__aportes.fechaaporte')->get();
        }

        //$deudaservicio = DB::select('select borrame('.$numpapeleta.') as lp');
        $deudaservicio = 0;


        return ['totaldevolucion' => $suma, 'datosdevolucion' => $datosdevolucion, 'salida' => $salida, 'fechasaportes' => $fechasaportes, 'deudaservicio' => $deudaservicio];
    }
    
    public function rutas(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaDaaros');  
        return [ 
		//'RUTE_REPORT'=> $rutas['RUTE_REPORT'].'&user='.Auth::user()->id.'&id=',		
        'REP_DEV_SOCIOS'=> $rutas['REP_DEV_SOCIOS'].'&user='.Auth::user()->username.'&idsocio=',
        ];
    }
}
