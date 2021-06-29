<?php

namespace App\AsinalssClass;
//namespace App\Http\Controllers;
use App\Con_Asientomaestro;
use App\Con_Asientodetalle;
use App\Con_Tipocomprobante;
use App\Con_Perfilcuentamaestro;
use App\con_Perfilcuentadetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



Class AsientoMaestroClass
{
    var $fecharegistro;
    var $idperfilcuentamaestro;
    var $tipodocumento;
    var $numdocumento;
    var $glosa;
    var $importetotal;
    var $idmodulo;
    var $hora;
    var $arrayDetalle;
    var $borrador;
    var $estado;
    var $agrupacion;
    var $segcobranza;
    var $filial;
    var $fechavalidacion;
    var $unidad;
    

    public function __construct()
    {    
        $this->idperfilcuentamaestro=0;
        $this->tipodocumento='';
        $this->numdocumento='';
        $this->glosa='';
        $this->importetotal=0;
        $this->idmodulo=0;
        $this->fecharegistro = date("Y-m-d H:i:s");
        $this->hora=date("H:i:s");
        $this->tipocomprobante='';
        $this->loteprestamos=0;
        $this->borrador=false;
        $this->estado=0;
        $this->fechavalidacion=date("Y-m-d H:i:s");        
        
        

        /*
        if(!$fecharegistro)
           $this->fecharegistro = date("Y-m-d");
        else
           $this->fecharegistro=$fecharegistro;
        
        $this->idperfilcuentamaestro=$idperfilcuentamaestro;
        $this->tipodocumento=$tipodocumento;
        $this->numdocumento=$numdocumento;
        $this->glosa=$glosa;
        $this->importetotal=$importetotal;
        $this->idmodulo=$idmodulo; */
    
    }   
    /* public function __construct()
    
        $a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        } 
     */    
       /* 
        

    /* function __construct1($a1) 
    { 
        echo('__construct with 1 param called: '.$a1.PHP_EOL); 
    } 
    
    function __construct2($a1,$a2) 
    { 
        echo('__construct with 2 params called: '.$a1.','.$a2.PHP_EOL); 
    } 
    
    function __construct3($a1,$a2,$a3) 
    { 
        echo('__construct with 3 params called: '.$a1.','.$a2.','.$a3.PHP_EOL); 
    }  */        
    public function AsientosMaestroDetalle($idperfilcuentamaestro, $tipodocumento,$numdocumento,$glosa,$importetotal,$idmodulo,$fecharegistro,$filial='',$numpapeleta='')
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
                    $asientodetalle->subcuenta=$numpapeleta;
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
    public function AsientosManualMaestroArray($tipocomprobante='',$tipodocumento='',$numdocumento='',$glosa='',$arrayDetalle=array(),$idmodulo='',$fecharegistro='',$borrador=false,$filial='',$unidad='')
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
        $this->filial=$filial;
        $this->unidad=$unidad;

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
                $asientomaestro->idunidad=$this->unidad;
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
    public function AsientosMaestroArrayASCII($idperfilcuentamaestro='',$tipodocumento='',$numdocumento='',$glosa='',$arrayDetalle=array(),$idmodulo='',$fecharegistro='',$loteprestamos=0,$agrupacion=0,$segcobranza=0,$filial='')
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
                $asientomaestro->desembolso=1;
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
    public function AsientosMaestroArrayAportes($idperfilcuentamaestro='',$tipodocumento='',$numdocumento='',$glosa='',$arrayDetalle=array(),$idmodulo='',$fecharegistro='',$loteprestamos=0,$agrupacion=0,$segcobranza=0,$filial='')
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
                $asientomaestro->desembolso=1;
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
    //////////////////////////////////////////////////////////////////////
    ///////////////////////////////////// metodo para agregar comprobantes agrupados
    public function AsientosMaestroAgrupado($idperfilcuentamaestro='',$tipocomprobante='',$tipodocumento='',$numdocumento='',$glosa='',$arrayDetalle=array(),$idmodulo='',$fecharegistro='',$listaids='',$filial=1)
    {
        $this->idperfilcuentamaestro=$idperfilcuentamaestro;
        $this->tipocomprobante=$tipocomprobante;
        $this->tipodocumento=$tipodocumento;
        $this->numdocumento=$numdocumento;
        $this->glosa=$glosa;
        $this->arrayDetalle=$arrayDetalle;
        $this->idmodulo=$idmodulo;
        $this->fecharegistro=$fecharegistro." ".$this->hora;
        $this->filial=$filial;
        
        
       

        DB::beginTransaction();
        {
            try
            {  
            
                $asientomaestro=new Con_Asientomaestro();
                $asientomaestro->idtipocomprobante=$tipocomprobante;
                $asientomaestro->idperfilcuentamaestro=$this->idperfilcuentamaestro;
                $asientomaestro->fecharegistro=$this->fecharegistro;
                $asientomaestro->fechavalidado=$this->fechavalidacion;
                $asientomaestro->tipodocumento=$this->tipodocumento;
                $asientomaestro->numdocumento=$this->numdocumento;
                $asientomaestro->glosa=$this->glosa;
                $asientomaestro->idfilial=$this->filial;
                $asientomaestro->idmodulo=$this->idmodulo;
                $asientomaestro->u_registro=Auth::id();
                $respuesta=$this->verificarfecha($this->fecharegistro,$tipocomprobante);
                $asientomaestro->cont_comprobante=$respuesta[0];
                $asientomaestro->cod_comprobante=$respuesta[1];
                $asientomaestro->estado=1;
                $asientomaestro->save();
                $idasientomaestro=$asientomaestro->idasientomaestro;

                foreach($this->arrayDetalle as $indice =>$valor)
                {
                    $asientodetalle=new Con_Asientodetalle();
                    $asientodetalle->idasientomaestro=$idasientomaestro;
                    foreach($valor as $i=>$v)
                    {
                        $asientodetalle->$i=$v;
                    }
                    $asientodetalle->usuarioregistro=Auth::id();
                    $asientodetalle->usuariomodifica=Auth::id();
                    $asientodetalle->save();
                    
                }
                
                $lista=$listaids;
                $arraylista=implode(",",$listaids);
                $arraylista=explode(",",$arraylista);
                //dd($arraylista);
                DB::table('con__asientomaestros')->whereIn('idasientomaestro', $arraylista)->update(['idagrupacion' => $idasientomaestro,
                                                                                                       'estado'=>1 ,
                                                                                                        'cod_comprobante'=>$respuesta[1]]);

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
        $mes = date("n", $fechaEntera);
        
        $raw2=DB::raw('month(fecharegistro)');

        $rawtime=DB::raw("concat(upper(left(Date_format(fecharegistro,'%M'),1)),substr(left(Date_format(fecharegistro,'%M'),3),2)) AS mes");
        


        $asientomaestros = Con_Asientomaestro::join('con__tipocomprobantes','con__tipocomprobantes.idtipocomprobante','=','con__asientomaestros.idtipocomprobante')
                                            ->select(DB::raw('max(cont_comprobante) as maximo'),
                                                    $rawtime,
                                                    'prefijo')
                                            ->where('con__tipocomprobantes.idtipocomprobante','=',$idtipocomprobante)
                                            ->where($raw2,'=',$mes)
                                            ->where('con__asientomaestros.gestion',0)
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


