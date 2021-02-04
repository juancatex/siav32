<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;
use Rats\Zkteco\Lib\Helper\Util; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Rrh_Empleado;

class RrhhBiometrico extends Controller
{
    public function getdatee(Request $request)
    { 
       return date('Y-m-d H:i:s').''; 
    }
    public function setUsersdos(Request $request)
    { 
        // if (!$request->ajax()) return redirect('/');

        $zk = new ZKTeco(config('app.ipbiometrico')); 
        $res=$zk->connect();
        if($res){ 
            $zk->disableDevice();   
            // $zk->setUser(2, '2', 'APAZA PAUCARA EPIFANIA','',Util::LEVEL_USER);
            // $zk->setUser(30, '30', 'BUSTAMANTE MAMANI MARISOL','',Util::LEVEL_USER);
            // $zk->setUser(3, '3', 'admin','',Util::LEVEL_USER);
            // $zk->setUser(4, '4', 'CHOQUE HUARISTE TATIANA','',Util::LEVEL_USER);
            // $zk->setUser(5, '5', 'COLQUE QUISPE JUAN CARLOS','',Util::LEVEL_USER);
            // $zk->setUser(6, '6', 'COLQUEHUANCA ALEJO FREDDY HUGO','',Util::LEVEL_USER);
            // $zk->setUser(7, '7', 'CONDORI LIMACHI ROCIO','',Util::LEVEL_USER);
            // $zk->setUser(8, '8', 'COPACALLE LUNA NORAH','',Util::LEVEL_USER);
            // $zk->setUser(9, '9', 'CRIALES CARMEN','',Util::LEVEL_USER);
            // $zk->setUser(10, '10', 'DELGADO APARICIO JULIO CESAR','',Util::LEVEL_USER);
            // $zk->setUser(11, '11', 'DIAZ FERNANDEZ DAMARIS','',Util::LEVEL_USER);
            // $zk->setUser(12, '12', 'FELIPE FERRANO IVON','',Util::LEVEL_USER);
            // $zk->setUser(13, '13', 'GEMIO CRUZ ESTHER','',Util::LEVEL_USER);
            // $zk->setUser(14, '14', 'LAURA MAMANI JUAN JHONNY','',Util::LEVEL_USER);
            // $zk->setUser(15, '15', 'LEQUEPÌ CONDORENA RUTH','',Util::LEVEL_USER);
            // $zk->setUser(16, '16', 'LUNA ARCE ANGELICA','',Util::LEVEL_USER);
            // $zk->setUser(17, '17', 'MAMANI UTURUNCO SERGIO','',Util::LEVEL_USER);
            // $zk->setUser(18, '18', 'MEDRANO RISCO MARCELA','',Util::LEVEL_USER);
            // $zk->setUser(19, '19', 'MERCADO VARGAS RAIZA','',Util::LEVEL_USER);
            // $zk->setUser(20, '20', 'MORALES RAMOS DAKMAR','',Util::LEVEL_USER);
            // $zk->setUser(21, '21', 'NINA CALLE SERGIO','',Util::LEVEL_USER);
            // $zk->setUser(22, '22', 'NUÑEZ RADA ALFREDO','',Util::LEVEL_USER);
            // $zk->setUser(23, '23', 'PAREDES C. ROSYMAR','',Util::LEVEL_USER);
            // $zk->setUser(24, '24', 'PATZI AGUILAR LENNY','',Util::LEVEL_USER);
            // $zk->setUser(25, '25', 'PINTO PEÑA VICTOR ISAC ','',Util::LEVEL_USER);
            // $zk->setUser(26, '26', 'QUISPE APAZA ARIEL','',Util::LEVEL_USER);
            // $zk->setUser(27, '27', 'RAMOS POMA MARTHA','',Util::LEVEL_USER);
            // $zk->setUser(28, '28', 'ROJAS APAZA LILIANA','',Util::LEVEL_USER);
            // $zk->setUser(29, '29', 'ROJAS SONABI WILMER','',Util::LEVEL_USER);
            // $zk->setUser(30, '30', 'RUFFO VELASCO ESTEFANI','',Util::LEVEL_USER);
            // $zk->setUser(31, '31', 'SALAZAR JEMIO JULIAN','',Util::LEVEL_USER); 
            // $zk->setUser(33, '33', 'TAPIA VARGAS DANIELA','',Util::LEVEL_USER);
            // $zk->setUser(34, '34', 'URI UTURUNCO ARTURO','',Util::LEVEL_USER);
            // $zk->setUser(32, '32', 'USCAMAYTA YUGAR BRIAN','',Util::LEVEL_USER);
            // $zk->setUser(36, '36', 'QUISPE HUANCA JOHANA','',Util::LEVEL_USER);
            // $zk->setUser(50, '37', 'TRUJILLO CHAVEZ LAURA','',Util::LEVEL_USER);
            // $zk->setUser(51, '38', 'CORTEZ CARI CINTHIA','',Util::LEVEL_USER);
            // $zk->setUser(52, '39', 'RAMIREZ ALEJO EVELIN','',Util::LEVEL_USER);
            // $zk->setUser(53, '40', 'CORTEZ CARI ABIGAIL','',Util::LEVEL_USER);
            // $zk->setUser(54, '41', 'COLQUE CASIHUANCA JENY','',Util::LEVEL_USER);
            // $zk->setUser(55, '42', 'ZEGARRA GAMBOA DAYANA','',Util::LEVEL_USER);
            // $zk->setUser(56, '43', 'ILLANES NICOL FABIOLA','',Util::LEVEL_USER);
            // $zk->setUser(57, '44', 'MACUCHAPI APAZA VANESSA','',Util::LEVEL_USER);
            // $zk->setUser(58, '45', 'CABRERA AGUILAR MARIA','',Util::LEVEL_USER);
            // $zk->setUser(59, '46', 'ACARAPI CABRERA MELVI','',Util::LEVEL_USER);
            // $zk->setUser(60, '47', 'VALDEZ QUISPE FRANZ','',Util::LEVEL_USER);
            // $zk->setUser(61, '48', 'APAZA BLANCO KATHERIN','',Util::LEVEL_USER);
            // $zk->setUser(62, '49', 'YAHUASI LAURA GONZALO','',Util::LEVEL_USER);
            
            //$zk->setUser(63, '70', 'QUISPE QUISPE YESSICA','',Util::LEVEL_USER);
            $zk->setUser(64, '71', 'CASTANETA U ANDREA','',Util::LEVEL_USER);

            // $zk->setUser(50, '50', 'admin2','',Util::LEVEL_USER);
            // $zk->setUser(51, '51', 'SOM. ALMANZA WILSON','',Util::LEVEL_USER);
            // $zk->setUser(52, '52', 'SOF. RAMIREZ WILFREDO','',Util::LEVEL_USER);
            // $zk->setUser(53, '53', 'SOF. ZAPATA ANGEL','',Util::LEVEL_USER);
            // $zk->setUser(54, '54', 'SOF. GUARDIA NELSON','',Util::LEVEL_USER);
            // $zk->setUser(55, '55', 'SOM. CUENTAS JUAN','',Util::LEVEL_USER);
            // $zk->setUser(56, '56', 'SOF. CALLIZAYA WILGER','',Util::LEVEL_USER);
            // $zk->setUser(57, '57', 'SOF. LARICO MAXIMILIANO','',Util::LEVEL_USER);
            // $zk->setUser(58, '58', 'SOF. GARCIA EMILIO','',Util::LEVEL_USER);
            // $zk->setUser(59, '59', 'SO2. RAMOS RICARDO','',Util::LEVEL_USER); 
            // $zk->setUser(60, '60', 'SOF. MAIZMAN MARTIN','',Util::LEVEL_USER);
            // $zk->setUser(61, '61', 'SOF. CALLAPA JOSE RAMIRO','',Util::LEVEL_USER);
            // $zk->setUser(62, '62', 'SOI. MENDOZA HANS CARLOS','',Util::LEVEL_USER);
           


            $zk->enableDevice();
            $zk->disconnect(); 
            return ['data'=>'ok'];
        }else{
            return ['data'=>'Error connect'];
        } 
    }
    public function getfotoBio(Request $request)
    {  if (!$request->ajax()) return redirect('/');
        $full_path = Storage::path('AFI/bio.jpg');
        $base64 = base64_encode(Storage::get('AFI/bio.jpg')); 
        return ['foto'=>'data:'.mime_content_type($full_path) . ';base64,' . $base64];
        // return response()->json(array('id' => $producto->idproducto), 200);
     }
    public function getUsers(Request $request)
    { 
        // if (!$request->ajax()) return redirect('/');
         
       
            $zk = new ZKTeco(config('app.ipbiometrico')); 
            $res=$zk->connect(); 
        
        if($res){
            $zk->disableDevice(); 
            $users = $zk->getUser();sleep(1); 
            $attendance = $zk->getAttendance();sleep(1);

            $data=[]; 
            $data['fecha']=$zk->getTime();
            $data['deviceName']=$zk->deviceName(); 
            $zk->enableDevice();
            $zk->disconnect(); 

            $usersFinal=[];   
                foreach ($users as $uItem) {
                    $arrayuservalue=[];
                    $arrayuservalue['uid']=$uItem['uid'];
                    $arrayuservalue['userid']=$uItem['userid'];
                    $arrayuservalue['name']=($uItem['name']);
                    $arrayuservalue['role']=Util::getUserRole($uItem['role']);  
                    // $usersFinal[$uItem['userid']]=$arrayuservalue; 
                    array_push($usersFinal,$arrayuservalue);
                }
            // $attendance = array_reverse($attendance, true);

            $arraylista=[];
            foreach ($attendance as $attItem){
                $out=[];
                $out['uid']= $attItem['uid'];
                $out['id']= $attItem['id'];
                $out['state']= Util::getAttState($attItem['state']);
                $out['timestamp']= $attItem['timestamp'];
                $out['date']= date("d-m-Y", strtotime($attItem['timestamp']));
                $out['time']= date("H:i:s", strtotime($attItem['timestamp']));
                $out['type']= Util::getAttType($attItem['type']);
                array_push($arraylista,$out); 
              
            }
            $empleado=Rrh_Empleado::select('codbiom')->where('idempleado','=',Auth::user()->idempleado)->get()->toArray()[0];
             return ['users'=>$usersFinal,'data'=>$data,'attendance'=>$arraylista,'bio'=>$empleado['codbiom']];
        }else{
            return ['users'=>array(),'data'=>array(),'attendance'=>array(),'bio'=>''];
        } 
   
    }
    function getmarcados($attendance,$desde=null,$hasta=null,$user=null){
         
         if($desde!=null&&$hasta==null){
            echo '1';
            $arraylista=[];
            foreach ($attendance as $attItem){
              if(date("d-m-Y", strtotime($attItem['timestamp'])) >= date("d-m-Y",strtotime($desde))){
               if($user!=null){ 
                    if($user==$attItem['id']){
                        $out=[];
                        $out['uid']= $attItem['uid'];
                        $out['id']= $attItem['id'];
                        $out['state']= Util::getAttState($attItem['state']);
                        $out['date']= date("d-m-Y", strtotime($attItem['timestamp']));
                        $out['time']= date("H:i:s", strtotime($attItem['timestamp']));
                        $out['type']= Util::getAttType($attItem['type']);
                        array_push($arraylista,$out);
                    }
               }else{
                $out=[];
                $out['uid']= $attItem['uid'];
                $out['id']= $attItem['id'];
                $out['state']= Util::getAttState($attItem['state']);
                $out['date']= date("d-m-Y", strtotime($attItem['timestamp']));
                $out['time']= date("H:i:s", strtotime($attItem['timestamp']));
                $out['type']= Util::getAttType($attItem['type']);
                array_push($arraylista,$out);
               }  
              }
            }
            return $arraylista;
        }elseif($desde==null&&$hasta!=null){
            echo '2';
            $arraylista=[];
            foreach ($attendance as $attItem){
              if(date("d-m-Y", strtotime($attItem['timestamp']))<=date("d-m-Y",strtotime($hasta))){
                if($user!=null){ 
                    if($user==$attItem['id']){
                        $out=[];
                        $out['uid']= $attItem['uid'];
                        $out['id']= $attItem['id'];
                        $out['state']= Util::getAttState($attItem['state']);
                        $out['date']= date("d-m-Y", strtotime($attItem['timestamp']));
                        $out['time']= date("H:i:s", strtotime($attItem['timestamp']));
                        $out['type']= Util::getAttType($attItem['type']);
                        array_push($arraylista,$out);
                    }
               }else{
                $out=[];
                $out['uid']= $attItem['uid'];
                $out['id']= $attItem['id'];
                $out['state']= Util::getAttState($attItem['state']);
                $out['date']= date("d-m-Y", strtotime($attItem['timestamp']));
                $out['time']= date("H:i:s", strtotime($attItem['timestamp']));
                $out['type']= Util::getAttType($attItem['type']);
                array_push($arraylista,$out);
               } 
              }
            }
            return $arraylista;
        }elseif($desde!=null&&$hasta!=null){
            echo '3<br>';
            $arraylista=[];
            $startDate = date('d-m-Y', strtotime($desde));
            $endDate = date('d-m-Y', strtotime($hasta));
            foreach ($attendance as $attItem){
                $currentDate= date("d-m-Y", strtotime(strval ($attItem['timestamp'])));
                echo $startDate.' al '.$endDate.'_______________' .$currentDate.'='.((($currentDate >= $startDate) && ($currentDate <= $endDate))?'si':'no').'<br>';
                if (($currentDate >= $startDate) && ($currentDate <= $endDate)){
                if($user!=null){ 
                    if($user==$attItem['id']){
                        $out=[];
                        $out['uid']= $attItem['uid'];
                        $out['id']= $attItem['id'];
                        $out['state']= Util::getAttState($attItem['state']);
                        $out['date']= $currentDate;
                        $out['time']= date("H:i:s", strtotime($attItem['timestamp']));
                        $out['type']= Util::getAttType($attItem['type']);
                        array_push($arraylista,$out);
                    }
               }else{
                $out=[];
                $out['uid']= $attItem['uid'];
                $out['id']= $attItem['id'];
                $out['state']= Util::getAttState($attItem['state']);
                $out['date']= date("d-m-Y", strtotime($attItem['timestamp']));
                $out['time']= date("H:i:s", strtotime($attItem['timestamp']));
                $out['type']= Util::getAttType($attItem['type']);
                array_push($arraylista,$out);
               } 
              }
            }
            return $arraylista;
        }else{
            echo '4';
            $arraylista=[];
            foreach ($attendance as $attItem){
                if($user!=null){ 
                    if($user==$attItem['id']){
                        $out=[];
                        $out['uid']= $attItem['uid'];
                        $out['id']= $attItem['id'];
                        $out['state']= Util::getAttState($attItem['state']);
                        $out['date']= date("d-m-Y", strtotime($attItem['timestamp']));
                        $out['time']= date("H:i:s", strtotime($attItem['timestamp']));
                        $out['type']= Util::getAttType($attItem['type']);
                        array_push($arraylista,$out);
                    }
               }else{
                $out=[];
                $out['uid']= $attItem['uid'];
                $out['id']= $attItem['id'];
                $out['state']= Util::getAttState($attItem['state']);
                $out['date']= date("d-m-Y", strtotime($attItem['timestamp']));
                $out['time']= date("H:i:s", strtotime($attItem['timestamp']));
                $out['type']= Util::getAttType($attItem['type']);
                array_push($arraylista,$out);
               } 
            }
            return $arraylista;
        }

       
    }
}
