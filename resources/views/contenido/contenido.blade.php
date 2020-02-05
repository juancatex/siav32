    @extends('principal')
    @section('contenido')
    
       <template v-if="menu==0">
            <principal></principal>
        </template>  

        <?php 
        use App\Http\Controllers\AdmUserController;
        $var2 = AdmUserController::listaventanamodulostodo(); 
            foreach ($var2 as $data2) { 
              echo '<template v-if="menu=='.$data2->idventanamodulo.'">
                        <'.$data2->template.' :object="object" permisos="22" idmodulo="'.$data2->idmodulo.'" idventanamodulo="'.$data2->idventanamodulo.'"></'.$data2->template.'>
                    </template>';
            }                                                                                                                                               
        ?>
   
    @endsection
    @section('bodyheader') 
        <?php   
        $user = AdmUserController::getUser();  
        echo '<template> 
                 <Body_header user="'. $user->username.'"></Body_header> 
              </template>';
                                                                                                                                                      
        ?>
    @endsection