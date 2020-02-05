<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProceduresController extends Controller
{ 
public function ip(Request $request){
return $request->ip();
}
    public function criterio(Request $request)
    {
     if (!$request->ajax()) return redirect('/');
      $suma=0;
      $reportcabeza=' <ul class="nav nav-tabs" role="tablist">';
      $reportcuerpo='<div class="tab-content">';
      $htmls = array();
      $pos = array();

       $valorfactor=DB::select("SELECT pf.nomfactor, ppp.idf FROM par__productos__parametros ppp,par__factors pf where ppp.idf=pf.idf and ppp.idfactor=?", array($request->factor));
       foreach ($valorfactor as $val){ 
          
        switch ($val->idf) {
          case 1:  
               $valor=json_decode((DB::select("select valida_historial(?,getPrestamosmora(?)) as total", array($request->factor,$request->idsocio)))[0]->total) ;
               $reportcabeza.='<li class="nav-item">
               <a class="nav-link active" data-toggle="tab" href="#historialPrestamos" role="tab" style="font-weight: 600; font-size: 16px;" aria-controls="historialPrestamos">
               '.$val->nomfactor.' 
               <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.$valor->value.'%</span>   
               </a>
              </li>';
                

              $reportcuerpo.='<div class="tab-pane active" id="historialPrestamos" role="tabpanel">
              <div class="chart-wrapper"><canvas id="barchatdata"></canvas> </div>
              <h5>total obtenido <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.round($valor->data, 2).'%</span></h5> </div>'; 
             
               $htmls['h1']=$valor->value; 
               array_push($pos,["tipo" => $val->idf ,"value"=> $valor->html]); 
               $suma +=$valor->data;
              break;
          case 2: 
          $valor=json_decode((DB::select("select valida_estado(?,?,?) as total", array($request->factor,$request->idmoneda,$request->idsocio)))[0]->total);
             $reportcabeza.='<li class="nav-item">
               <a class="nav-link " data-toggle="tab" href="#statusprestamo" role="tab" style="font-weight: 600; font-size: 16px;"  aria-controls="statusprestamo">
               '.$val->nomfactor.' 
               <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.$valor->value.'%</span>   
               </a>
              </li>';
              $reportcuerpo.='<div class="tab-pane" id="statusprestamo" role="tabpanel">
              <h5>total obtenido <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.round($valor->data, 2).'%</span></h5></div>';
              
              
             $suma +=$valor->data;
             array_push($pos,["tipo" => $val->idf ,"value"=> $valor->html]); 
             $htmls['e2']=$valor->value;
              break;
          case 3: 
             $valor=json_decode((DB::select("select valida_monto (?,?,?) as total", array($request->factor,$request->monto,$request->cuota)))[0]->total);
             $reportcabeza.='<li class="nav-item">
             <a class="nav-link " data-toggle="tab" href="#moneyprestamo" role="tab" style="font-weight: 600; font-size: 16px;" aria-controls="moneyprestamo">
             '.$val->nomfactor.'
             <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.$valor->value.'%</span>   
             </a>
            </li>';

            $reportcuerpo.='<div class="tab-pane" id="moneyprestamo" role="tabpanel">
            <h5>total obtenido <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.round($valor->data, 2).'%</span></h5></div>';
           
           $htmls['m3']=$valor->value;
           array_push($pos,["tipo" => $val->idf ,"value"=> $valor->html]); 
             $suma +=$valor->data;
              break;
      }

    }  
    $reportcabeza.='</ul>';
    $reportcuerpo.='</div>';
    $reportcabeza.=$reportcuerpo;
    return ['total' => $suma,'html'=>$reportcabeza,'datahtml'=>$htmls,'pos'=>$pos]; 
      
    }




    public function criteriogarante(Request $request)
    {
     if (!$request->ajax()) return redirect('/');
      $suma=0;
      $reportcabeza=' <ul class="nav nav-tabs" role="tablist">';
      $reportcuerpo='<div class="tab-content">';
      $htmls = array();
      $pos = array();

       $valorfactor=DB::select("SELECT pf.nomfactor, ppp.idf FROM par__productos__parametros ppp,par__factors pf where ppp.idf=pf.idf and ppp.idfactor=?", array($request->factor));
       foreach ($valorfactor as $val){ 
          
        switch ($val->idf) {
          case 1: 
               
               $valor=json_decode((DB::select("select valida_historial(?,getPrestamosmora(?)) as total", array($request->factor,$request->id)))[0]->total) ;
               $reportcabeza.='<li class="nav-item">
               <a class="nav-link active" data-toggle="tab" href="#historialPrestamosg" role="tab" style="font-weight: 600; font-size: 16px;" aria-controls="historialPrestamosg">
               '.$val->nomfactor.' 
               <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.$valor->value.'%</span>   
               </a>
              </li>';
                

              $reportcuerpo.='<div class="tab-pane active" id="historialPrestamosg" role="tabpanel">
              <div class="chart-wrapper"><canvas id="barchatdatagarante"></canvas> </div>
              <h5>total obtenido <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.round($valor->data, 2).'%</span></h5></div>'; 
             
               $htmls['h1']=$valor->value;
               array_push($pos,["tipo" => $val->idf ,"value"=> $valor->html]); 
               $suma +=$valor->data;
              break;
          case 2:  
             $valor=json_decode((DB::select("select valida_estado(?,?,?) as total", array($request->factor,$request->idmoneda,$request->id)))[0]->total);
             $reportcabeza.='<li class="nav-item">
               <a class="nav-link " data-toggle="tab" href="#statusprestamog" role="tab" style="font-weight: 600; font-size: 16px;"  aria-controls="statusprestamog">
               '.$val->nomfactor.' 
               <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.$valor->value.'%</span>     
               </a>
              </li>';
              $reportcuerpo.='<div class="tab-pane" id="statusprestamog" role="tabpanel">
              <h5>total obtenido <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.round($valor->data, 2).'%</span></h5></div>';
              
             
             $suma +=$valor->data;
             array_push($pos,["tipo" => $val->idf ,"value"=> $valor->html]); 
             $htmls['e2']=$valor->value;
              break;
          case 3: 
             $prestamos=(DB::select("select getPrestamos(?,?,0) as total", array($request->id,$request->idpro)))[0]->total; 
             $liquidopagable=(DB::select("SELECT liquidopagable_papeleta as total FROM socios where idsocio=?",array($request->id)))[0]->total;
             $liquidopagable>$prestamos?$liquidopagable-=$prestamos:$liquidopagable=0;

             $valor=json_decode((DB::select("select valida_monto (?,?,?) as total", array($request->factor,$liquidopagable,$request->cuota)))[0]->total);
             
             $reportcabeza.='<li class="nav-item">
             <a class="nav-link " data-toggle="tab" href="#moneyprestamog" role="tab" style="font-weight: 600; font-size: 16px;" aria-controls="moneyprestamog">
             '.$val->nomfactor.'
             <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.$valor->value.'%</span>   
             </a>
            </li>';

            $reportcuerpo.='<div class="tab-pane" id="moneyprestamog" role="tabpanel">
            <h5>total obtenido <span class="badge badge-'.($valor->value>0?($valor->value==$valor->data?'success':'warning'):'danger').'">'.round($valor->data, 2).'%</span></h5></div>';
          
           $htmls['m3']=$valor->value;
           array_push($pos,["tipo" => $val->idf ,"value"=> $valor->html]); 
             $suma +=$valor->data;
              break;
      }

    }  
    $reportcabeza.='</ul>';
    $reportcuerpo.='</div>';
    $reportcabeza.=$reportcuerpo;
    return ['total' => $suma,'html'=>$reportcabeza,'datahtml'=>$htmls,'pos'=>$pos]; 
      
    }
}
