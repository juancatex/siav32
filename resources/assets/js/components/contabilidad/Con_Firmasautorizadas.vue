<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Firmas Autorizadas
            </div>
            <div class="card-body">
                <div class="col-7 col-form-label " style="border: 1px solid #c2cfd6 !important; border-radius: 5px;">
                    <div class="col-3 form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" v-model="directivo" value="1" checked @change="cambiaDirectivo('1')"> Directivo
                        </label>
                    </div>
                    <div class="col-3 form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" v-model="directivo" value="2" @change="cambiaDirectivo('2')">Personal
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-5" v-if="directivo=='1'">
                        <strong>Directivo:</strong>
                        <Ajaxselect  v-if="clearSelected"
                            ruta="/rrh_empleado/selectdirectivos?buscar=" @found="empleados" @cleaning="cleanempleados"
                            resp_ruta="empleados"
                            labels="nombres"
                            placeholder="Ingrese Texto..." 
                            idtabla="idsocio"
                            :id="idempleadoselected"
                            :clearable='true'>
                        </Ajaxselect>
                    </div>
                    <div class="form-group col-md-5" v-else>
                        <strong>Personal:</strong>
                        <Ajaxselect  v-if="clearSelected"
                            ruta="/rrh_empleado/selectempleados2?buscar=" @found="empleados" @cleaning="cleanempleados"
                            resp_ruta="empleados"
                            labels="nombres"
                            placeholder="Ingrese Texto..." 
                            idtabla="idempleado"
                            :id="idempleadoselected"
                            :clearable='true'>
                        </Ajaxselect>
                    </div>
                    <div v-if="orden<=maxfirmas" class="row">
                        <h5 style="padding-top: 25px; margin-right: 20px;margin-left:  20px" >Orden: {{ orden }}</h5>
                    
                        <div style="padding-top: 25px;">
                            <button type="button" class="btn btn-success" @click="registrarFirmas()" data-toggle="tooltip" data-placement="top" title="Registrar Firma" :disabled="!sipersonal">
                                <i class="icon-plus"></i>&nbsp;
                            </button>
                        </div>
                    </div>
                    <div v-else>
                        <h5 style="padding-top: 25px; margin-right: 20px;">Limite de Firmas {{ maxfirmas }}</h5>
                    </div>
                    
                </div>
                


                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Posicion</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Predeterminado Usuario Registro</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr v-for="firma in arrayFirmas" :key="firma.idfirmaautorizada">
                            <td v-text="firma.orden"></td>
                            <td v-text="firma.nombres"></td>
                            <td v-text="firma.cargo"></td>
                            <td>
                                <button type="button" @click="elminarfirma(firma.idfirmaautorizada)" class="btn btn-danger btn-sm">
                                    <i class="icon-trash"></i>
                                </button> &nbsp;
                            </td>
                        </tr>                                
                    </tbody>
                </table>
                
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
</main>

</template>

<script>

    import Vue from 'vue';
    import VeeValidate from 'vee-validate';

    const VueValidationEs = require('vee-validate/dist/locale/es');
        Vue.use(VeeValidate, 
        {
            locale: 'es',
            dictionary: 
            {
                es: VueValidationEs
            }
        });

    Vue.use(VeeValidate);

    export default {
        data (){
            return {
                maxfirmas:6,//cantidad maxima de firmas
                directivo:1,
                idempleadoselected:'',
                clearSelected:1,
                arrayFirmas:[],
                idempleado:[],
                orden:0,
                clearSelected:1,



                departamento_id: 0,
                nomdepartamento : '',
                abrvdep : '',
                arrayDepartamento : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorDepartamento : 0,
                errorMostrarMsjDepartamento : [],
            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{
            sipersonal(){
                if(this.idempleado.length!=0 && this.orden<=this.maxfirmas)
                    return true;
                return false;
            }
            
        },
        methods : {
            cambiaDirectivo(valor){
                let me=this;
                me.clearSelected=0;
                setTimeout(me.tiempo, 200); 
                me.directivo=valor;
                me.idempleado=[];
            },
            empleados(empleados){
                this.idempleado=[];
                for (const key in empleados) {
                    if (empleados.hasOwnProperty(key)) {
                        const element = empleados[key];
                        this.idempleado.push(element);
                    }
                }
            },
            tiempo(){
                this.clearSelected=1;
            },
            cleanempleados(){
                this.idempleado=[];
            },
            
            listarFirmas(){
                let me=this;
                var url= '/con_firmasautorizadas';

                me.clearSelected=0;
                setTimeout(me.tiempo, 200); 
                axios.get(url).then(function (response) {
                    
                    var respuesta= response.data;
                    me.arrayFirmas = respuesta;
                    let sw=0;
                    var cont=1;
                    while (sw!=1) {
                        cont ++;
                        let resultado= me.arrayFirmas.find(elem=>elem.orden==cont)
                        if(!resultado)
                        {
                            me.orden=cont;
                            sw=1;
                        }
                    }
                })
                .catch(function (response) {
                    console.log(response);
                });
            },
            registrarFirmas(){
                let me = this;
                axios.post('/con_firmasautorizadas/registrar',{
                    'idpersona': me.idempleado[0],
                    'tipo_persona': me.directivo,
                    'orden':me.orden,

                    
                }).then(function (response) {
                    if(response.data.length){
                       swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    }
                    else{
                        me.idempleado=[];
                        me.listarFirmas();
                        me.clearSelected=0;
                        setTimeout(me.tiempo, 200); 

                    }

                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            /* actualizarDepartamento(){
               
                let me = this;

                axios.put('/par_departamento/actualizar',{
                    'nomdepartamento': this.nomdepartamento, 
                    'iddepartamento': this.departamento_id,
                    'abrvdep': this.abrvdep
                }).then(function (response) {
                    if(response.data.length){
                        swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                        )
                    }
                    else{
                        me.cerrarModal();
                        me.listarDepartamento(1,'','nomdepartamento');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },*/

            elminarfirma(idfirma){
               swal({
                title: 'Esta Seguro de Eliminar esta Firma?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.put('/con_firmasautorizadas/desactivar',{
                        'idfirma': idfirma
                    }).then(function (response) {
                        me.idempleado=[];
                        me.listarFirmas();
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            }, 
            
        },
        mounted() {
            this.listarFirmas();
        }
    }
</script>
