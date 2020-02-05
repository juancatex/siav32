<?php

namespace App\AsinalssClass;
//namespace App\Http\Controllers;
use App\Con_Factura;
use App\Con_FacturaParametro;
use App\Con_Tipocomprobante;
use App\Con_Perfilcuentamaestro;
use App\con_Perfilcuentadetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


Class FacturaClass
{
    var $razonsocial;
    var $nit;
    var $detalle;
    var $importetotal;
    var $importecf;
    

    public function __construct()
    {    
        $this->razonsocial='';
        $this->nit='';
        $this->detalle='';
        $this->importetotal='0';
        $this->importecf='0';     
    }   
    
    public function RegistraFactura($razonsocial,$nit,$detalle,$importetotal,$importecf) {
            
        //obtenemos el id de parametros faturas que este activo
        $facturaActiva = Con_FacturaParametro::select('idfacturaparametro','numeroautorizacion','llave')->where('activo','=',1)->get();
        foreach($facturaActiva as $data) {
            $idfacpar = $data->idfacturaparametro;
            $nroautorizacion = $data->numeroautorizacion;
            $llave = $data->llave;
        }
        
        //obtener el numero de factura max
        $maxfactura = Con_Factura::orderBy('idfactura', 'desc')->where('activo','=','1')->max('numerofactura'); 
       
        //formato de detalle
        //detalle|importe|cantidad|total ej: pago telefono|23.23|1|23.23
       
        $this->razonsocial=$razonsocial;
        $this->nit=$nit;
        $this->detalle=$detalle;
        $this->importetotal=$importetotal;
        $this->importecf=$importecf;     

        $factura = new Con_factura();
        $factura->idfacturaparametro = $idfacpar;
        $factura->numerofactura = $maxfactura+1;
        $factura->codigocontrol = '00-00-00-00-00';
        $factura->razonsocial = $razonsocial;        
        $factura->nit = $nit;
        $factura->detalle = $detalle;
        $factura->importetotal = $importetotal;
        $factura->importecf = $importetotal;        
        $factura->activo = '1';
        $factura->save();
    }

    public function AsientosMaestroDetalle($idperfilcuentamaestro, $tipodocumento,$numdocumento,$glosa,$importetotal,$idmodulo,$fecharegistro,$filial='')
    {
       /* $idtipocomprobante='';
        if(!$tipocomprobante)
        {*/
        if(!$fecharegistro)
            $this->fecharegistro=$this->fecharegistro." ".$this->hora;
        else
            $this->fecharegistro=$fecharegistro." ".$this->hora;
        $this->idperfilcuentamaestro=$idperfilcuentamaestro;
        $this->tipodocumento=$tipodocumento;
        $this->numdocumento=$numdocumento;
        $this->glosa=$glosa;
        $this->importetotal=$importetotal;
        $this->idmodulo=$idmodulo;
        $this->filial=$filial;
        if($this->filial=='')
        $this->filial=1;



        $tipocomprobante = Con_Perfilcuentamaestro::join('con__tipocomprobantes','con__perfilcuentamaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                        ->where('con__perfilcuentamaestros.idperfilcuentamaestro','=',$this->idperfilcuentamaestro)                                            
                                                        ->select('con__tipocomprobantes.idtipocomprobante')
                                                        ->get()->toArray();

        $idtipocomprobante=$tipocomprobante[0]['idtipocomprobante'];
            

        $cuentadetalles = Con_Perfilcuentamaestro::join('con__perfilcuentadetalles','con__perfilcuentamaestros.idperfilcuentamaestro','=','con__perfilcuentadetalles.idperfilcuentamaestro')
                                ->join('con__cuentas','con__perfilcuentadetalles.idcuenta','=','con__cuentas.idcuenta')
                                //->join('con__tipocomprobantes','con__perfilcuentamaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                ->select('con__cuentas.idcuenta','con__perfilcuentadetalles.porcentaje','con__perfilcuentadetalles.tipocargo')                                
                                ->where('con__perfilcuentamaestros.idperfilcuentamaestro','=',$this->idperfilcuentamaestro)
                                ->orderBy('con__perfilcuentadetalles.tipocargo','desc')
                                ->get()->toArray();

        //dd($cuentadetalles);/*

        DB::beginTransaction();
        {
            try{  
         
                $cont=0;
                foreach($cuentadetalles as $indice=>$valor)
                ++$cont;
                
                $asientomaestro=new Con_Asientomaestro();
                $asientomaestro->idtipocomprobante=$idtipocomprobante;
                $asientomaestro->idperfilcuentamaestro=$this->idperfilcuentamaestro;
                $asientomaestro->fecharegistro=$this->fecharegistro;
                $asientomaestro->tipodocumento=$this->tipodocumento;
                $asientomaestro->numdocumento=$this->numdocumento;
                $asientomaestro->glosa=$this->glosa;
                //$asientomaestro->idfilial='1';//modificar con ide de filial
                $asientomaestro->idfilial=$this->filial;
                $asientomaestro->idmodulo=$this->idmodulo;
                $asientomaestro->u_registro=Auth::id();
                $asientomaestro->save();
                $idasientomaestro=$asientomaestro->idasientomaestro;


                for($i=0;$i<$cont;$i++)
                {          
                    $asientodetalle=new Con_Asientodetalle();
                    $asientodetalle->idasientomaestro=$idasientomaestro;
                    $asientodetalle->idcuenta=$cuentadetalles[$i]['idcuenta'];
                    
                    if($cuentadetalles[$i]['tipocargo']=="d")
                    {   
                        $asientodetalle->monto=(($cuentadetalles[$i]['porcentaje']*$this->importetotal)/100);
                        $asientodetalle->debe=(($cuentadetalles[$i]['porcentaje']*$this->importetotal)/100);
                    }
                    else 
                    {
                        $asientodetalle->monto=(($cuentadetalles[$i]['porcentaje']*$this->importetotal)/100)*-1;
                        $asientodetalle->haber=(($cuentadetalles[$i]['porcentaje']*$this->importetotal)/100);
                    }
                    
                    $asientodetalle->moneda='bs';//cambiar por moneda
                    $asientodetalle->usuarioregistro=Auth::id();
                    $asientodetalle->usuariomodifica=Auth::id();
                    $asientodetalle->save();
                    
                }
                DB::commit();
                return $idasientomaestro;
             }
            catch(\Exception $e)
            {
                $error = $e->getMessage();
                DB::rollback();

            }
        }
    }
    //////////////////////////////////////////funcion para almacenar asientos manuale en modo array
    public function AsientosManualMaestroArray($tipocomprobante='',$tipodocumento='',$numdocumento='',$glosa='',$arrayDetalle=array(),$idmodulo='',$fecharegistro='',$borrador=false,$filial='')
    {
        // definicion de array
        $this->tipocomprobante =$tipocomprobante;
        $this->tipodocumento=$tipodocumento;
        $this->numdocumento=$numdocumento;
        $this->glosa=$glosa;
        $this->arrayDetalle=$arrayDetalle;
        $this->idmodulo=$idmodulo;
        $this->fecharegistro=$fecharegistro." ".$this->hora;
        $this->borrador=$borrador;
       // echo $this->borrador." hola mundo";
        $this->filial=$filial;
        if($this->filial=='')
            $this->filial=1;

        if(!$this->borrador)
        {
            $estado=1;
            $respuesta=$this->verificarfecha($fecharegistro,$tipocomprobante);
           // echo $estado. " - entra if !borrador";
        }    
        else 
        {
           // echo "entra else if !borrador";
            $estado=5;
            $cont_tipocomprobante=0;
        }
            
        

        //dd($this->borrador);
        //$this->idperfilcuentamaestro=$idperfilcuentamaestro;

        

      DB::beginTransaction();
        {
            try
            {  
            
                $asientomaestro=new Con_Asientomaestro();
                $asientomaestro->idtipocomprobante=$this->tipocomprobante;
                $asientomaestro->idperfilcuentamaestro=$this->idperfilcuentamaestro;
                $asientomaestro->fechavalidado=$this->fecharegistro;
                $asientomaestro->idfilial=$this->filial;
                if($estado!=5)
                {
                    $asientomaestro->cont_comprobante=$respuesta[0];
                    $asientomaestro->cod_comprobante=$respuesta[1];
                }
                
                $asientomaestro->fecharegistro=$this->fecharegistro;
                $asientomaestro->tipodocumento=$this->tipodocumento;
                $asientomaestro->numdocumento=$this->numdocumento;
                $asientomaestro->glosa=$this->glosa;
                //$asientomaestro->idfilial='1';//modificar con ide de filial
                $asientomaestro->idmodulo=$this->idmodulo;
                $asientomaestro->estado=$estado;
                $asientomaestro->u_registro=Auth::id();
                if(!$this->borrador)
                    $asientomaestro->u_obs_val=Auth::id();
                $asientomaestro->save();
                $idasientomaestro=$asientomaestro->idasientomaestro;

                //dd($this->arrayDetalle);
                //TODO: verificar si es necesario guardar todos los elementos en un borrador 
                foreach($this->arrayDetalle as $indice =>$valor)
                {
                    if($valor['monto']==0)//que hace o que verifica esta parte
                    {
                        echo"entra monto =0";
                    }
                    else 
                    { 
                        $asientodetalle=new Con_Asientodetalle();
                        $asientodetalle->idasientomaestro=$idasientomaestro;
                    
                        foreach($valor as $i=>$v)
                        {
                            if($i=='monto')
                            {
                                if($v>0)
                                    $asientodetalle->debe=$v;
                                else 
                                    $asientodetalle->haber=$v*-1;
                            }
                            $asientodetalle->$i=$v;
                        }
                        $asientodetalle->usuarioregistro=Auth::id();
                        $asientodetalle->usuariomodifica=Auth::id();
                        $asientodetalle->save();
                    }
                }
                DB::commit();
                return $idasientomaestro;
                  
            }
            catch(\Exception $e)
            {
                $error = $e->getMessage();
                DB::rollback();
                return $error;

            }
        } 

        //TODO: es para ver como tener elementos por cambiar o por hacer
        //FIXME: ES arreglalme
    }
    
    //////////////////////////////////////// funcion para almacenar asientos automaticos en modo array
    public function AsientosMaestroArray($idperfilcuentamaestro='',$tipodocumento='',$numdocumento='',$glosa='',$arrayDetalle=array(),$idmodulo='',$fecharegistro='',$loteprestamos=0,$agrupacion=0,$segcobranza=0,$filial='')
    {
        // definicion de array
        //$this->tipocomprobante =$tipocomprobante;
        $this->tipodocumento=$tipodocumento;
        $this->numdocumento=$numdocumento;
        $this->glosa=$glosa;
        $this->arrayDetalle=$arrayDetalle;
        $this->idmodulo=$idmodulo;
        $this->fecharegistro=$fecharegistro." ".$this->hora;
        $this->idperfilcuentamaestro=$idperfilcuentamaestro;
        $this->loteprestamos=$loteprestamos;
        $this->agrupacion=$agrupacion;
        $this->segcobranza=$segcobranza;
        $this->filial=$filial;
        if($this->filial=='')
        $this->filial=1;

        $tipocomprobante = Con_Perfilcuentamaestro::join('con__tipocomprobantes','con__perfilcuentamaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
        ->where('con__perfilcuentamaestros.idperfilcuentamaestro','=',$this->idperfilcuentamaestro)                                            
        ->select('con__tipocomprobantes.idtipocomprobante')
        ->get()->toArray();

        $idtipocomprobante=$tipocomprobante[0]['idtipocomprobante'];

        DB::beginTransaction();
        {
            try
            {  
            
                $asientomaestro=new Con_Asientomaestro();
                $asientomaestro->loteprestamos=$this->loteprestamos;
                $asientomaestro->idtipocomprobante=$idtipocomprobante;
                $asientomaestro->idperfilcuentamaestro=$this->idperfilcuentamaestro;
                $asientomaestro->fecharegistro=$this->fecharegistro;
                $asientomaestro->tipodocumento=$this->tipodocumento;
                $asientomaestro->numdocumento=$this->numdocumento;
                $asientomaestro->glosa=$this->glosa;
                //$asientomaestro->idfilial='1';//modificar con ide de filial
                $asientomaestro->idfilial=$this->filial;
                $asientomaestro->idmodulo=$this->idmodulo;
                $asientomaestro->u_registro=Auth::id();
                $asientomaestro->agrupacion=$agrupacion;
                $asientomaestro->segcobranza=$this->segcobranza;
                $asientomaestro->save();
                $idasientomaestro=$asientomaestro->idasientomaestro;

                foreach($this->arrayDetalle as $indice =>$valor)
                {
                    $asientodetalle=new Con_Asientodetalle();
                    $asientodetalle->idasientomaestro=$idasientomaestro;
                    foreach($valor as $i=>$v)
                    {
                        if($i=='monto')
                        {
                            if($v>0)
                                $asientodetalle->debe=$v;
                            else 
                                $asientodetalle->haber=$v*-1;
                        }
                        $asientodetalle->$i=$v;
                    }
                    $asientodetalle->usuarioregistro=Auth::id();
                    $asientodetalle->usuariomodifica=Auth::id();
                    $asientodetalle->save();
                    
                }
                DB::commit();
                return $idasientomaestro;
                      
            }
            catch(\Exception $e)
            {
                $error = $e->getMessage();
                DB::rollback();
                return $error;
            }
        } 
    }
    public function verificarfecha($fecharegistro,$idtipocomprobante)
    {
        //$mesactual=date("m");

        $array = ["1"=>"Ene","2"=>"Feb","3"=>"Mar","4"=>"Abr","5"=>"May","6"=>"Jun","7"=>"Jul","8"=>"Ago","9"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dic"];


        DB::statement("SET lc_time_names = 'es_ES'");
        $fechaEntera = strtotime($fecharegistro);
        $mes = date("m", $fechaEntera);
        $raw2=DB::raw('month(fecharegistro)');

        $rawtime=DB::raw("concat(upper(left(Date_format(fecharegistro,'%M'),1)),substr(left(Date_format(fecharegistro,'%M'),3),2)) AS mes");
        


        $asientomaestros = Con_Asientomaestro::join('con__tipocomprobantes','con__tipocomprobantes.idtipocomprobante','=','con__asientomaestros.idtipocomprobante')
                                            ->select(DB::raw('max(cont_comprobante) as maximo'),
                                                    $rawtime,
                                                    'prefijo')
                                            ->where('con__tipocomprobantes.idtipocomprobante','=',$idtipocomprobante)
                                            ->where('con__asientomaestros.gestion',0)
                                            ->where($raw2,'=',$mes)
                                            ->get()
                                            ->toArray();
        $cont_tipocomprobante[0]=$asientomaestros[0]['maximo']+1;

        if(!$asientomaestros[0]['mes'])
        {
            $codmes=$array[$mes];
        }
        else {
            $codmes=$asientomaestros[0]['mes'];
        }
        $codigo=$codmes."-".$asientomaestros[0]['prefijo']."-".$cont_tipocomprobante[0];
        
        $cont_tipocomprobante[1]=$codigo;
        //dd($cont_tipocomprobante);

        return $cont_tipocomprobante;
    }
}


