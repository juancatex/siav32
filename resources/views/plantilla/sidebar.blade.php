<div class="sidebar noprint">
            <nav class="sidebar-nav">
                <ul class="nav" style="height: auto;">
                    <li @click="click(0)" class="nav-item" id="0">
                        <a class="nav-link active" href="#"><i class="icon-speedometer"></i> INICIO</a>
                    </li>
                    <li class="nav-title">
                        Menu Opciones
                    </li>
                    
                    <?php use App\Http\Controllers\AdmUserController;
                        $var = AdmUserController::acceso_menu(); 
                        $modulos=[]; $i=0;
                        foreach ($var as $data) {
                            $modulos[$i] = $data->nommodulo; 
                            $mo[$i] = $data->idmodulo; $i++;
                        } 
                        
                        $var1 = AdmUserController::listamodulos(); 
                        $lmodulos=[]; $idmodulos=[]; $j=0;
                        foreach ($var1 as $data1) {
                            $lmodulos[$j] = $data1->nommodulo; 
                            $idmodulos[$j] = $data1->idmodulo; $j++;
                        } 
                                                                                                
                    ?>
                    @for($i=0; $i<count($lmodulos); $i++)
                        @for($j=0; $j < count($modulos); $j++)
                            @if ($mo[$j] == $idmodulos[$i]) <!-- mostramos los modulos que tienen acceso el usuario -->
                                <li class="nav-item nav-dropdown menudown" >
                                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i>
                                    <font color="orange" style="text-transform:capitalize"><?php echo $modulos[$j] ?></font>
                                </a>
                                    <?php
                                        $var2 = AdmUserController::listaventanamodulos($mo[$j]); 
                                        $ventana=[]; $idventana=[]; $x=0;
                                        foreach ($var2 as $data2) {
                                            $ventana[$x] = $data2->nomventanamodulo; 
                                            $idventana[$x] = $data2->idventanamodulo; $x++;
                                        }
                                    ?>
                                    @for($x=0; $x<count($ventana);$x++)
                                    <ul class="nav-dropdown-items">
                                        <li @click="click({{$idventana[$x]}})" class="nav-item" id={{$idventana[$x]}}>
                                            <a class="nav-link" href="#"><i class="icon-link"></i> {{$ventana[$x]}}</a>
                                        </li>
                                    </ul>
                                    @endfor                                    
                                </li>
                            @endif
                        @endfor
                    @endfor
                              
                    <li @click="click(121)" class="nav-item" id="121">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
                    </li>
                    <li @click="click(122)" class="nav-item" id="122">
                        <a class="nav-link" href="#"><i class="icon-info"></i> Acerca de...<span class="badge badge-info">IT</span></a>
                    </li>  

                </ul>
            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>