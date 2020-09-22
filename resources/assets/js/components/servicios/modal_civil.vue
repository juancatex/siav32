<template>
    <main class="main">    
        <div class="modal fade " tabindex="-1"  role="dialog"  aria-hidden="false" id="modal_civil" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Registro de Huesped civil</h4>
                    <button class="close" @click="cerrarmodaleve('modal_civil',0)">x</button>
                </div>
                <div class="modal-body" style="overflow-y: scroll;max-height: 400px;">          
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Registro 
                    </div>
                    <div class="form-group row">

                                            <table border="2" align="center">
                                                <tr>
                                                    <td colspan="12" align="center"><b>REGISTRAR HUESPED (CIVIL)</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Nombre</td>
                                                    <td><input type="text" class="form-control" v-model="nombre" v-validate.initial="'required'" name="nombre" >
                                                    <span class="text-error">{{ errors.first('nombre')}}</span> </td>
                                                    <td>Paterno</td>
                                                    <td><input type="text" class="form-control" v-model="apaterno"> </td>
                                                    <td>Materno</td>
                                                    <td><input type="text" class="form-control" v-model="amaterno"> </td>
                                                </tr>
                                            </table>
                                            <table border="2" align="center">
                                                <tr>
                                                    <td>CI</td>
                                                    <td><input type="text" class="form-control" v-model="ci" v-validate.initial="'required'" name="ci"> 
                                                    <span class="text-error">{{ errors.first('ci')}}</span></td>
                                                    <td><select class="form-control" v-model="iddepartamento" v-validate.initial="'required'" name="dep">
                                                            <option v-for="departamento in arrayDepartamentos" :key="departamento.iddepartamento"
                                                            :value="departamento.iddepartamento" v-text="departamento.abrvdep"></option>
                                                        </select>
                                                        <span class="text-error">{{ errors.first('dep')}}</span>
                                                    </td>
                                                    <td>Celular</td>
                                                    <td><input type="text" class="form-control" v-model="telcelular"> </td>
                                                </tr>
                                            </table>
                                            <table border="2" align="center">
                                                <tr>
                                                    <td>Fecha Nac.</td>
                                                    <td > <input type="date" class="form-control" v-model="fechanac" v-validate.initial="'required'" name="fecnac"> 
                                                    <span class="text-error">{{ errors.first('fecnac')}}</span></td>
                                                    <td>Sexo</td>
                                                    <td > 
                                                        <input type="radio" value="m" v-model="sexo">
                                                        <label for="male">Masculino</label><br>
                                                        <input type="radio" value="f" v-model="sexo" checked>
                                                        <label for="female">Femenino</label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="cerrarmodaleve('modal_civil',0)">Cerrar</button>
                    <button  :disabled = "errors.any()" class="btn btn-primary" @click="registrarHuesped()"> Guardar </button>
                </div>
            </div>
        </div>
    </div>



    </main>
</template>
<script>        
    import * as repo from '../../functions.js';
    import VeeValidate from 'vee-validate';
    export default {
        data (){
            return {               
                tipoAccion : 0,
                errorEscalafon : 0,
                errorMostrarMsjEscalafon : [],
                offset : 10,                
                buscar : '',
                nombre:'', apaterno:'',amaterno:'',telcelular:'',ci:'',iddepartamento:'',fechanac:'',sexo:'',
                arrayDepartamentos:[],
                idcliente:'',
            }
        },
       
        computed: {

        },

        formOptions: {

        },  
        
        methods : {
            
            cerrarmodaleve(id,idcliente){ 
                this.modaltraspaso.closeModal(id);
                this.$emit('cerrarvuecivil',idcliente);
            }, 
                 
            registrarHuesped(){
                let me=this;
                var url='/ser_civil/store';
                axios.post(url,{
                    'nombre': this.nombre.toUpperCase(),
                    'apaterno':this.apaterno.toUpperCase(),
                    'amaterno':this.amaterno.toUpperCase(),
                    'ci':this.ci,
                    'iddepartamento':this.iddepartamento,
                    'telcelular':this.telcelular,
                    'fechanac':this.fechanac,
                    'sexo':this.sexo,
                }).then(function(response){
                    swal({title:'Registro correcto',
                        html:'Se han guardado los datos del nuevo huésped.<br>Proceda al registro de entrada.',
                        type:'success'});
                    axios.get('/ser_civil/ultimo').then(function(response){
                        //console.log(response.data);
                        me.idcliente=response.data.civil[0]; //console.log(response.data.civil[0]);
                        me.cerrarmodaleve('modal_civil',me.idcliente);
                    });
                });
            },

            showVue(asignacion) {
                this.modaltraspaso=new _pl.Modals();
                this.modaltraspaso.addModal('modal_civil'); 
                this.modaltraspaso.openModal('modal_civil'); 
            },

            departamentos() {
                let me=this;
                var url='/par_departamento/selectDepartamento';
                axios.get(url).then(function(response){
                    me.arrayDepartamentos=response.data.departamentos;
                })
            }
        },
            
        mounted() {
            this.departamentos();        
        }
        
    }
</script>
