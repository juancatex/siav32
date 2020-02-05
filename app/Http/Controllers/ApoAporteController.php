<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apo_Aporte;
use Illuminate\Support\Facades\DB;
use App\Socio;
use App\AsinalssClass\AsientoMaestroClass;
use App\Con_Asientomaestro;
use App\Apo_TotalAporte;

class ApoAporteController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        //$aportes = Apo_Aporte::orderBy('idaporte', 'desc')->paginate(10);
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        $raw = DB::raw("CONCAT(par_grados.nomgrado,' ',nombre, ' ', apaterno,' ',amaterno) as fullname");
        $raw2= DB::raw("CONCAT(ci,' ',par_departamentos.abrvdep) as carnet");

        /*$aportes = Apo_Aporte::join('socios','apo__aportes.numpapeleta','=','socios.numpapeleta')
                                join('apo__tipoaportes','apo__aportes.idtipoaporte','=','apo__tipoaportes.idtipoaporte')
                                ->join('con__perfilcuentamaestros','apo__aportes.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                ->join('par_grados','socios.idgrado','=','par_grados.idgrado')
                                ->join('par_fuerzas','par_grados.idfuerza','=','par_fuerzas.idfuerza')
                                //->select('apo__ciudades.idciudad','apo__ciudades.iddepartamento','apo__ciudades.nomciudad','apo__departamentos.nomdepartamento','apo__ciudades.activo')
                                ->select('apo__aportes.idaporte','socios.idsocio',$raw,'apo__aportes.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
                                ->where($criterio, 'like', '%'.$buscar.'%')
                                ->distinct('socios.idsocio')
                                ->get();

                                consulta sql funciona con numpapeleta join aportes.
                                */ 
        
        if($buscar<>'')
        {
            $socios=Socio::join('par_grados','socios.idgrado','=','par_grados.idgrado')
                ->join('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
                ->join('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento')
                ->join('apo__total_aportes','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
                ->select('socios.idsocio',$raw,$raw2,'socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado','socios.activo','obligatorios','jubilados')
                //->where('socios.'.$criterio, 'like', '%'.$buscar.'%')
                ->where('nombre','like', '%'.$buscar.'%')
                ->orwhere('apaterno','like', '%'.$buscar.'%')
                ->orwhere('amaterno','like', '%'.$buscar.'%')
                ->orwhere('socios.numpapeleta','like', '%'.$buscar.'%')
                ->orwhere('socios.ci','like', '%'.$buscar.'%')
                ->orderBy('socios.numpapeleta','asc')
                ->orderBy('socios.apaterno','asc')
                ->orderBy('socios.amaterno','asc')
                ->orderBy('socios.nombre','asc')
                ->orderBy('socios.ci','asc')
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
                    //'aportes'=>$aportes,
                    'socios'=>$socios
                ];

        }
    }

    public function selectAporte(Request $request){
        if (!$request->ajax()) return redirect('/');

        /*$aportes = Apo_Aporte::where('activo','=','1')
        ->select('idaporte','descripcion')->orderBy('descripcion', 'asc')->get();
        */
        $numpapeleta=$request->numpapeleta;

        

        $aportes = Apo_Aporte::join('apo__idsaportes','apo__aportes.idaporte','=','apo__idsaportes.idaporte')
                                ->join('socios','apo__aportes.numpapeleta','=','socios.numpapeleta')
                                ->join('apo__tipoaportes','apo__aportes.idtipoaporte','=','apo__tipoaportes.idtipoaporte')
                                ->join('con__perfilcuentamaestros','apo__aportes.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                ->join('con__asientomaestros','apo__aportes.idasientomaestro','=','con__asientomaestros.idasientomaestro')
                                
                                ->select('apo__aportes.fechaaporte',
                                            'apo__aportes.idaporte',
                                            'apo__aportes.obsaporte',
                                            'apo__aportes.aporte',
                                            'apo__tipoaportes.descripcion',
                                            'con__perfilcuentamaestros.nomperfil',
                                            'apo__aportes.metodoaporte',
                                            'apo__aportes.idtipoaporte',
                                            'apo__aportes.idperfilcuentamaestro',
                                            'apo__aportes.numdocumento',
                                            'apo__aportes.tipodocumento',
                                            'con__asientomaestros.estado as estadoconta',
                                            'con__asientomaestros.observaciones',
                                            'con__asientomaestros.idasientomaestro')
                                ->where('apo__idsaportes.numpapeleta', '=', $numpapeleta )
                                ->where('apo__idsaportes.tipodevolucion','=','0')
                                ->where('metodoaporte','=','formulario-aporte')
                                ->where(function($query) {
                                    $query->where('con__asientomaestros.estado', 0)
                                        ->orWhere('con__asientomaestros.estado', 1)
                                        ->orWhere('con__asientomaestros.estado', 3);
                                })
                                ->orderBy('apo__aportes.created_at','desc')
                                ->get()->take(4);
        
        return ['aportes' => $aportes];
    }

    public function selectAporteDebito(Request $request){
       if (!$request->ajax()) return redirect('/');

        
        $numpapeleta=$request->numpapeleta;
        $fechabusqueda=$request->fechabusqueda;

       
        $raw=DB::raw("IFNULL( apo__aportes.aporte -(SELECT SUM(monto) FROM apo__debitos WHERE idaporte=apo__aportes.idaporte ),apo__aportes.aporte) sumatotal");

        $fecha = strtotime($fechabusqueda);
        $aniobusqueda= date("Y", $fecha); // Year (2003)
        $mesbusqueda= date("m", $fecha); // Month (12)
        //echo date("d", $date); // day (14)
        $rawMes=DB::raw("MONTH(apo__aportes.fechaaporte)");
        $rawAnio=DB::raw("YEAR(apo__aportes.fechaaporte)");
        
        if(!$fechabusqueda)
        {
        $debitos = Apo_Aporte::join('apo__tipoaportes','apo__aportes.idtipoaporte','=','apo__tipoaportes.idtipoaporte')
                               ->join('con__perfilcuentamaestros','apo__aportes.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                               ->join('apo__idsaportes','apo__aportes.idaporte','=','apo__idsaportes.idaporte')
                               ->select('apo__aportes.fechaaporte',
                                            'apo__aportes.idaporte',
                                            'apo__aportes.aporte as sumatotal' ,
                                            'apo__tipoaportes.descripcion',
                                            'apo__aportes.metodoaporte',
                                            'apo__aportes.idperfilcuentamaestro')
                                ->where('apo__aportes.activo','=','1')
                                ->where('apo__idsaportes.numpapeleta', '=', $numpapeleta )
                                ->where('apo__idsaportes.tipodevolucion','=','0')
                                //->where('apo__aportes.activo=1')
                                //->groupBy('apo__aportes.idaporte')
                                ->orderBy('apo__aportes.fechaaporte','desc')
                                ->paginate(6);
        }
        else {
            $debitos = Apo_Aporte::join('apo__tipoaportes','apo__aportes.idtipoaporte','=','apo__tipoaportes.idtipoaporte')
                               ->join('con__perfilcuentamaestros','apo__aportes.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                               ->join('apo__idsaportes','apo__aportes.idaporte','=','apo__idsaportes.idaporte')
                               ->select('apo__aportes.fechaaporte',
                                            
                                            'apo__aportes.idaporte',
                                            'apo__aportes.aporte as sumatotal',
                                            'apo__tipoaportes.descripcion',
                                            'apo__aportes.metodoaporte',
                                            'apo__aportes.idperfilcuentamaestro')
                                ->where('apo__idsaportes.numpapeleta', '=', $numpapeleta )
                                ->where('apo__idsaportes.tipodevolucion','=','0')
                                ->where($rawMes,'=',$mesbusqueda)
                                ->where($rawAnio,'=',$aniobusqueda)
                                ->where('apo__aportes.activo','=','1')
                                ->groupBy('apo__aportes.idaporte')
                                ->orderBy('apo__aportes.fechaaporte','desc')
                                ->paginate(6);
            
          
        }



        return [
            'paginationDebito' => [
                'total'        => $debitos->total(),
                'current_page' => $debitos->currentPage(),
                'per_page'     => $debitos->perPage(),
                'last_page'    => $debitos->lastPage(),
                'from'         => $debitos->firstItem(),
                'to'           => $debitos->lastItem(),
            ],
            'debitos' => $debitos];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) 
            return redirect('/');
        
       
        // verificar que validar 

            /* $validator = Validator::make($request->all(), [
                'descripcion' => 'unique:apo__aportes'
            ]);   

            if ($validator->fails()) {
                //echo $validator->messages()->first(); exit();
                return ($validator->messages()->first());
        }
            */
        //return $idperfilcuentamaestro;

        $idperfilcuentamaestro=$request->idperfilcuentamaestro;
        $tipodocumento=$request->tipodocumento;
        $numdocumento=$request->numdocumento;
        $glosa=$request->obsaporte;
        $importetotal=$request->aporte;
        $idmodulo=$request->idmodulo;
        $fecharegistro=null;
        $numpapeleta=$request->numpapeleta;
        $filial=1;
        //dd($fecharegistro);


 /*   $asientomaestro= new AsientoMaestroClass($idperfilcuentamaestro, $tipodocumento,$numdocumento,$glosa,$importetotal);
        $asientomaestro->AsientosMaestroDetalle();
*/

        
                

        DB::beginTransaction();
        {
            try{       
              
                $aporte = new Apo_Aporte();
                $aporte->numpapeleta=$numpapeleta;
                $aporte->idtipoaporte=$request->idtipoaporte;
                $aporte->aporte=$importetotal;
                $aporte->fechaaporte=$request->fechaaporte;
                $aporte->metodoaporte=$request->metodoaporte;
                $aporte->idperfilcuentamaestro=$idperfilcuentamaestro;
                $aporte->obsaporte=$glosa;
                $aporte->tipodocumento=$tipodocumento;
                $aporte->numdocumento=$numdocumento;
                $aporte->save();
                $idaporte=$aporte->idaporte;
                
                $asientomaestro= new AsientoMaestroClass();
                $idasientomaestro=$asientomaestro->AsientosMaestroDetalle($idperfilcuentamaestro, $tipodocumento,$numdocumento,$glosa,$importetotal,$idmodulo,$fecharegistro,$filial,$numpapeleta);
                DB::unprepared('CALL agregarasientomaestro('.$idasientomaestro.',0,'.$idaporte.')');
                /* $actualizacion=Apo_TotalAporte::where('numpapeleta',$request->numpapeleta)->update(['cantobligados'=>$cantobligados,
                                                                                                'totalobligados'=>$totalobligados]);
 */
                DB::commit();
                return $idaporte;
            }
            catch(\Exception $e)
            {
                $error = $e->getMessage();
                DB::rollback();

            }
           
        } 
        
    }
 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $idaporte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       /* $validator = Validator::make($request->all(), [
            'descripcion' => 'unique:apo__aportes'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
            */        
        
        
        if (!$request->ajax()) return redirect('/');

        $aporte = Apo_Aporte::findOrFail($request->idaporte);
        $aporte->numpapeleta=$request->numpapeleta;
        $aporte->idtipoaporte=$request->idtipoaporte;
        $aporte->aporte=$request->aporte;
        $aporte->fechaaporte=$request->fechaaporte;
        $aporte->metodoaporte=$request->metodoaporte;
        $aporte->activo = '1';
        $aporte->idperfilcuentamaestro=$request->idperfilcuentamaestro;
        $aporte->obsaporte=$request->obsaporte;
        $aporte->numdocumento=$request->numdocumento;
        $aporte->tipodocumento=$request->tipodocumento;
        $aporte->save();
    }
    public function eliminarAporte(Request $request)
    {
       /* $validator = Validator::make($request->all(), [
            'descripcion' => 'unique:apo__aportes'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
            */        
        
        
        if (!$request->ajax()) return redirect('/');

        $aporte = Apo_Aporte::findOrFail($request->idaporte);
        $aporte->activo = '0';
        $aporte->save();
        $aporte = Con_Asientomaestro::findOrFail($request->idasientomaestro);
        $aporte->estado = '2';
        $aporte->save();


        //TODO:actualizar total aportes cuando se elimina un aporte o agregar el aporte a total aportes solo cuando se ha validado el comprobante contable.
       /*  $maxaportes = Con_Configuracion::select('codigo','valor')
        ->where(function($query){
            $query->where('codigo','=','AO')
                ->orwhere('codigo','=','AJ');
        })
                ->get()
                ->toArray();
        foreach ($maxaportes as $indice => $valor) {
            if($valor['codigo']=='AO')
                $obligatorios=$valor['valor'];
            
            if($valor['codigo']=='AJ')
                $jubilacion=$valor['valor'];
        }


        $total_aportes=Apo_TotalAporte::where('numpapeleta',$request->numpapeleta)
                                        ->get()->toArray();
        
        if($total_aportes[0]['obligatorios']==0 && $total_aportes[0]['jubilados']==0)
        {
            $cantobligados=$total_aportes[0]['cantobligados']-1;
            $totalobligados=$total_aportes[0]['totalobligados']-$request->monto;
            $actualizacion=Apo_TotalAporte::where('numpapeleta',$request->numpapeleta)->update(['cantobligados'=>$cantobligados,
                                                                                                'totalobligados'=>$totalobligados]);
        }
        else if($total_aportes[0]['obligatorios']==1 && $total_aportes[0]['jubilados']==0)
        {
            
        } */



    }
    
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $aporte = Apo_Aporte::findOrFail($request->idaporte);

        $aporte->activo = '0';
        $aporte->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $aporte = Apo_Aporte::findOrFail($request->idaporte);
        $aporte->activo = '1';
        $aporte->save();
    }
}
