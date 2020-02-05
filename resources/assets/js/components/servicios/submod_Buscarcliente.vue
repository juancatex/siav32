<template>
<main>
    <!-- MODAL BUSCAR CLIENTE-->
    <div class="modal" :class="modalBuscarcliente?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class=modal-title> Búsqueda de socio / cliente</h4>
                    <button class="close" @click="modalBuscarcliente=0">×</button>                    
                </div>
                <div class="modal-body">
                    <!-- BARRA DE BÚSQUEDA v:1.0-->
                    <div class="row">
                        <div class="col-md-4" style="padding:5px 10px;">
                        <table width="100%"><tr>
                            <td>Criterio:</td>
                            <td><select class="form-control" v-model="criterio">
                                    <option value="apaterno">Ap. Paterno</option>
                                    <option value="amaterno">Ap. Materno</option>
                                    <option value="ci">Nro. C.I.</option>
                                    <option value="numpapeleta">Nro. Papeleta</option>
                                </select>
                            </td>
                        </tr></table>
                        </div>
                        <div class="col-md-3" style="padding:5px 10px;">
                        <input type="text" class="form-control" v-model="buscado" id="qq"  @keyup.enter="listarClientes()" autofocus="autofocus">
                        </div>
                        <div class="col-md-2 text-right" style="padding:5px 10px;">
                        <button class="btn btn-primary" @click="listarClientes()"><i class="fa fa-search"></i> Encontrar</button>
                        </div>
                        <div class="col-md-3 text-right" style="padding:5px 10px;" 
                            v-if="regAmbiente.codtiposervicio!='MUL'&&regAmbiente.codtiposervicio!='MAU'">
                        <button class="btn btn-primary" @click="nuevoCliente()"><i class="fa fa-user-plus"></i> Nuevo Cliente</button>
                        </div>               
                    </div>

                    <!-- RESULTADO D LA BÚSQUEDA v:1.0-->
                    <div v-if="divEncontrados>0">
                        <div class="text-right"> RESULTADO: 
                            <span v-text="arraySocios.length+' socios'"></span>
                            <span v-if="arrayCiviles.length>0" v-text="' ; '+arrayCiviles.length+' civiles'"></span>
                        </div>
                        <div class="scroll-resultado">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th>NR</th>
                                    <th>Grado</th>
                                    <th>Paterno</th>
                                    <th>Materno</th>
                                    <th>Nombre</th>
                                    <th>Fuerza</th>                    
                                    <th>CI</th>
                                    <th>Papeleta</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(cliente,index) in arrayCiviles" :key="cliente.idcliente" style="cursor:pointer;"> 
                                    <td v-text="index+1"></td>
                                    <td @click="asignarAmbiente(cliente)">CIVIL</td>
                                    <td @click="asignarAmbiente(cliente)" v-text="cliente.apaterno"></td>
                                    <td @click="asignarAmbiente(cliente)" v-text="cliente.amaterno"></td>
                                    <td @click="asignarAmbiente(cliente)" v-text="cliente.nombre" nowrap></td>
                                    <td @click="asignarAmbiente(cliente)"> ---- </td>
                                    <td v-text="cliente.ci+' '+cliente.abrvdep" nowrap></td>
                                    <td @click="asignarAmbiente(cliente)"> ---- </td>
                                    <td><button @click="editarCliente(cliente)" class="btn btn-success btn-sm"><i class="icon-pencil"></i></button>
                                    </td>                         
                                </tr> 
                                <tr v-for="(cliente,index) in arraySocios" :key="cliente.idcliente" style="cursor:pointer"> 
                                    <td v-text="index+1"></td>
                                    <td @click="asignarAmbiente(cliente)" v-text="cliente.nomgrado"></td>
                                    <td @click="asignarAmbiente(cliente)" v-text="cliente.apaterno"></td>
                                    <td @click="asignarAmbiente(cliente)" v-text="cliente.amaterno"></td>
                                    <td @click="asignarAmbiente(cliente)" v-text="cliente.nombre" nowrap></td>
                                    <td @click="asignarAmbiente(cliente)" v-text="cliente.nomfuerza"></td>
                                    <td v-text="cliente.ci+' '+cliente.abrvdep" nowrap></td>
                                    <td v-text="cliente.numpapeleta"></td>
                                </tr> 
                            </tbody>
                        </table>
                        </div>
                    </div>
                    
                    <div  v-if="divNuevocliente"><br>
                        <h5 class="titazul" v-text="tituloFormulario"></h5>
                        <div class="row">
                            <div class="col-md-4">
                                <table>
                                    <tr>
                                        <td>Nombre</td>
                                        <td colspan="2"><input type="text" class="form-control" v-model="nombre"> </td>
                                    </tr>
                                    <tr>
                                        <td>Paterno</td>
                                        <td colspan="2"> <input type="text" class="form-control" v-model="apaterno"> </td>
                                    </tr>
                                    <tr>
                                        <td>Materno</td>
                                        <td colspan="2"> <input type="text" class="form-control" v-model="amaterno"> </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table>
                                    <tr>
                                        <td nowrap>Nro C.I.</td>
                                        <td><input type="text" class="form-control" v-model="ci"> </td>
                                    </tr>
                                    <tr>
                                        <td>Expedido</td>
                                        <td><select class="form-control" v-model="iddepartamento">
                                                <option v-for="departamento in arrayDepartamentos" :key="departamento.iddepartamento"
                                                :value="departamento.iddepartamento" v-text="departamento.abrvdep"></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sexo</td>
                                        <td><input type="radio" name="sexo" value="m" v-model="sexo">Masculino
                                            <input type="radio" name="sexo" value="f" v-model="sexo">Femenino
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table>
                                    <tr>
                                        <td nowrap>Fecha Nacim.</td>
                                        <td><input type="date" class="form-control" v-model="fechanac"> </td>
                                    </tr>
                                    <tr>
                                        <td>Celular</td> <input type="hidden" v-model="idcivil"> 
                                        <td><input type="text" class="form-control" v-model="telcelular"> </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" @click="divNuevocliente=0">Cancelar</button>
                            <button class="btn btn-primary" v-if="tipoAccion==1" @click="storeCivil()">Guardar </button>
                            <button class="btn btn-primary" v-if="tipoAccion==2" @click="updateCivil()">Guardar cambios</button>
                        </div>
                    </div> <!-- formulario nuevo cliente-->
                </div> <!-- modal body-->
            </div>
        </div>
    </div> <!-- fin modal buscarcliente -->
</main>
</template>

<script>
export default {

    data(){ return {
        criterio:'apaterno', buscado:'', regAmbiente:[], regCivil:[],
        modalBuscarcliente:0, divEncontrados:0, 
        divNuevocliente:0,  tipoAccion:1, tituloFormulario:'',
        idcivil:'',nombre:'',apaterno:'',amaterno:'',ci:'',telcelular:'', sexo:'',fechanac:'',
        iddepartamento:'', arrayDepartamentos:[],

        arraySocios:[], arrayCiviles:[],

    }},

    methods:{
        cargarModal(ambiente){
            this.regAmbiente=ambiente;
            this.modalBuscarcliente=1;
            this.criterio='apaterno';
            this.buscado='';
            this.divEncontrados=0;
            this.divNuevocliente=0;
            //alert(this.modalBuscarcliente);
        },

        listarClientes(){
            if(this.buscado.length<2) {
                swal('Revise','Introduzca al menos DOS letras o dígitos para realizar la búsqueda');
                return;
            }
            this.divNuevocliente=0;
            this.divEncontrados=1;
            let me=this;
            var url='/ser_asignacion/buscarSocios?criterio='+this.criterio+'&buscado='+this.buscado;
            axios.get(url).then(function(response){
                me.arraySocios=response.data.socios;
            });
            if(this.regAmbiente.codtiposervicio=='MUL') return;
            if(this.criterio=='numpapeleta') return; 
            var url='/ser_asignacion/buscarCiviles?criterio='+this.criterio+'&buscado='+this.buscado;
            axios.get(url).then(function(response){
                me.arrayCiviles=response.data.civiles;
            });            
        },
        
        asignarAmbiente(cliente){
            this.modalBuscarcliente=0;
            this.$emit('clienteEncontrado',cliente);
        },

        nuevoCliente(){
            this.divEncontrados=0;
            this.divNuevocliente=1;
            this.tituloFormulario="Registrar nuevo cliente";
            this.nombre='';
            this.apaterno='';
            this.amaterno='';
            this.ci='';
            this.iddepartamento='';
            this.sexo='';
            this.fechanac='';
            this.telcelular='';
            let me=this;
            var url='/par_departamento/selectDepartamento';
            axios.get(url).then(function(response){
                me.arrayDepartamentos=response.data.departamentos;
            })
        },

        storeCivil(){
            let me=this;
            var url='/ser_civil/store';
            axios.post(url,{
                'nombre': this.nombre.toUpperCase(),
                'apaterno':this.apaterno.toUpperCase(),
                'amaterno':this.amaterno.toUpperCase(),
                'ci':this.ci,
                'iddepartamentoexpedido':this.iddepartamento,
                'telcelular':this.telcelular,
                'fechanac':this.fechanac,
                'sexo':this.sexo,
            }).then(function(response){
                swal({title:'Registro correcto',
                    html:'Se han guardado los datos del nuevo huésped.<br>Proceda al registro de entrada.',
                    type:'success'});
                axios.get('/ser_civil/ultimo').then(function(response){
                    me.regCivil=response.data.civil[0];
                    me.asignarAmbiente(me.regCivil);
                });
            });

        },

        editarCliente(cliente){
            //var regCliente=cliente;
            this.divEncontrados=0;
            this.divNuevocliente=1;
            this.tituloFormulario="Editar datos del cliente";
            this.tipoAccion=2;
            let me=this;
            var url='/ser_civil/encontrar?idcivil='+cliente.idcliente;
            axios.get(url).then(function(response){
                var regCliente=response.data.civil[0];
                me.idcivil=regCliente.idcivil;
                me.nombre=regCliente.nombre;
                me.apaterno=regCliente.apaterno;
                me.amaterno=regCliente.amaterno;
                me.ci=regCliente.ci;
                me.iddepartamento=regCliente.iddepartamentoexpedido;
                me.sexo=regCliente.sexo;
                me.fechanac=regCliente.fechanac;
                me.telcelular=regCliente.telcelular;
            });
            var url='/par_departamento/selectDepartamento';
            axios.get(url).then(function(response){
                me.arrayDepartamentos=response.data.departamentos;
            });

        },

        updateCivil(){
            let me=this;
            var url='/ser_civil/update';//?idcivil='+this.idcivil;
                this.regCivil['idcliente']=this.idcivil;
                this.regCivil['nombre']=this.nombre;
                this.regCivil['apaterno']=this.apaterno;
                this.regCivil['amaterno']=this.amaterno;
                this.regCivil['ci']=this.ci;
                this.regCivil['iddepartamento']=this.iddepartamento;
                this.regCivil['nomgrado']=this.sexo;
                this.regCivil['fechanac']=this.fechanac;
                this.regCivil['telcelular']=this.telcelular;           
            axios.put(url,{
                'idcivil':this.idcivil,
                'nombre':this.nombre.toUpperCase(),
                'apaterno':this.apaterno.toUpperCase(),
                'amaterno':this.amaterno.toUpperCase(),
                'ci':this.ci,
                'iddepartamentoexpedido':this.iddepartamento,
                'sexo':this.sexo,
                'fechanac':this.fechanac,
                'telcelular':this.telcelular
            }).then(function(){
                swal('Registro actualizado','Se han guardado los cambios correctamente','success');
                me.asignarAmbiente(me.regCivil);
            })
        },

    },

}
</script>

<style lang="css">
.scroll-resultado {
    position:relative;
    padding:15px;
    height:400px;
    overflow-y:scroll;
}    
</style>