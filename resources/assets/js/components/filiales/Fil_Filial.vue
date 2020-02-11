<template>
<main class="main">
    <div class="breadcrumb">
        <div class="col-md-8 col-sm-11 titmodulo" style="padding:0px">
            <div class="tablatit">
                <div v-if="divFiliales==0" class="tcelda" style="padding-right:8px">
                    <button class="btn btn-success cui-arrow-left botonnav" @click="nivelPrevio()"></button>
                </div>
                <div class="tcelda">Filiales
                    <span v-if="divFiliales"> > Listado General</span>
                    <span v-else v-text="' > '+regFilial.nommunicipio"></span>
                    <span v-if="divDirectivos"> > Directivos</span>
                    <span v-if="divOficinas"> > Oficinas</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-1 text-right" style="padding:0px">
            <button class="btn btn-success cui-options botonnav"></button>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card" v-if="divFiliales">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 titcard">
                        <div class="tablatit">
                            <div v-if="iddepartamento" class="tcelda">Departamento de <span v-text="regDepartamento.nomdepartamento"></span></div>
                            <div v-else class="tcelda">Listado de Filiales</div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="input-group-append" style="display:inline;">
                            <button class="btn btn-primary dropdown-toggle" style="margin-top:0px" data-toggle="dropdown" aria-expanded="false">
                                Departamento...<span class="caret"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                <a href="#" class="dropdown-item" @click="listaFiliales('',1)">(Todos)</a>
                                <a href="#" v-for="departamento in arrayDepartamentos" :key="departamento.iddepartamento"
                                    class="dropdown-item" v-text="departamento.nomdepartamento" @click="listaFiliales(departamento.iddepartamento,1)"></a>
                            </div>
                        </div>
                        <button class="btn btn-primary" style="margin-top:0px" @click="nuevaFilial()">Crear nueva Filial</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="text-right" style="padding-bottom:10px">
                    <div class="vervigente"> Ver: &nbsp;
                        <input type="radio" name="estado" id="r1" @click="listaFiliales(iddepartamento,1)">Vigentes &nbsp;
                        <input type="radio" name="estado" id="r0" @click="listaFiliales(iddepartamento,0)">Inactivas
                    </div>
                    <button class="btn btn-success btn-sm icon-printer" title="Vista de Impresión" style="margin-left:10px"
                        @click="reporteFiliales()"></button>
                </div>                
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr>
                                <th align="center"><span class="badge badge-success" v-text="arrayFiliales.length+' items'"></span></th>
                                <th align="center">Código</th>
                                <th align="center">Sigla</th>
                                <th>Filial</th>
                                <th>Dirección</th>
                                <th align="center">Tel. Fijo</th>
                                <th align="center">Tel. Celular</th>
                                <th v-if="!iddepartamento" >Depto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="filial in arrayFiliales" :key="filial.idfilial" :class="filial.activo?'':'txtdesactivado'">
                                <td v-if="filial.activo" nowrap align="center">
                                    <button class="btn btn-warning btn-sm icon-pencil" title="Editar datos"
                                        @click="editarFilial(filial)"></button>
                                    <button class="btn btn-warning btn-sm icon-chart" title="Movimiento económico"
                                        @click="economicoFilial(filial)"></button>
                                    <button class="btn btn-warning btn-sm icon-people" title="Directorio"
                                        @click="directivosFilial(filial)"></button>
                                    <button class="btn btn-warning btn-sm icon-directions" title="Oficinas operativas"
                                        @click="oficinasFilial(filial)"></button>
                                    <button class="btn btn-danger btn-sm icon-trash" title="Desactivar filial"
                                        @click="estadoFilial(filial)"></button>
                                </td>
                                <td v-else align="center">
                                    <button class="btn btn-warning btn-sm icon-action-redo" title="Reactivar oficina"
                                        @click="estadoFilial(filial)"></button>
                                </td>
                                <td v-text="filial.codfilial" align="center"></td>
                                <td v-text="filial.sigla" align="center"></td>
                                <td v-text="filial.nommunicipio"></td>
                                <td v-text="filial.direccion"></td>
                                <td v-text="filial.telfijo" align="center"></td>
                                <td v-text="filial.telcelular" align="center"></td>
                                <td v-if="!iddepartamento" v-text="filial.abrvdep" align="center"></td>                                
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>

        <subOficina   v-if="divOficinas"   :regFilial="regFilial"></subOficina>
        <subDirectivo v-if="divDirectivos" :regFilial="regFilial"></subDirectivo>

    </div>

    <!-- MODAL FILIAL  MODAL FILIAL  MODAL FILIAL  MODAL FILIAL  MODAL FILIAL -->
    <!-- MODAL FILIAL  MODAL FILIAL  MODAL FILIAL  MODAL FILIAL  MODAL FILIAL -->
    <div class="modal" :class="modalFilial?'mostrar':''" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nueva':'Modificar'"></span> Filial</h4>
                    <button class="close" @click="modalFilial=0">x</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado">Departamento de <span v-text="regDepartamento.nomdepartamento"></span></h4>
                    Municipio: <span class="txtasterisco"></span>
                    <select class="form-control" v-model="idmunicipio"
                        name="mun" :class="{'invalido':errors.has('mun')}" v-validate="'required'">
                        <option v-for="municipio in arrayMunicipios" :key="municipio.idmunicipio"
                            :value="municipio.idmunicipio" v-text="municipio.nommunicipio"></option>
                    </select>
                    <p v-if="errors.has('mun')" class="txtvalidador">Seleccione un municipio</p>
                    <div><br>
                        <div class="tabla100">
                            <div class="tcelda" style="width:45%; vertical-align:top;">Código (autoasignado):
                                <input type="text" class="form-control text-center txtnegrita" readonly 
                                    maxlength="2" v-model="codfilial">
                            </div>
                            <div class="tcelda" style="width:10%"></div>
                            <div class="tcelda" style="width:45%;  vertical-align:top;">Sigla: <span class="txtasterisco"></span>
                                <input type="text" class="form-control text-center" v-model="sigla" 
                                    maxlength="2" placeholder="2 caracteres" 
                                    name="sig" :class="{'invalido':errors.has('sig')}" v-validate="'required|alpha'">
                                <p v-if="errors.has('sig')" class="txtvalidador">Dato requerido, 2 letras.</p>                                    
                            </div>
                        </div>
                    </div>
                    <br>Dirección  
                    <input type="text" class="form-control " v-model="direccion">
                    <div><br>
                        <div class="tabla100">
                            <div class="tcelda" style="width:45%">Tel. Fijo:
                                <input type="text" name="fijo" class="form-control tinput text-center" v-model="telfijo">
                            </div>
                            <div class="tcelda" style="width:10%"></div>
                            <div class="tcelda" style="width:45%">Tel. Celular:
                                <input type="text" name="celular" class="form-control tinput text-center" v-model="telcelular">
                            </div>
                        </div>
                    </div>
                    <div class="txtobligatorio text-center"></div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-secondary" @click="modalFilial=0">Cerrar</button>
                    <button class="btn btn-primary" @click="validarFilial()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>   

</main>    
</template>

<script>

Vue.component('subOficina',  require('./subOficina.vue').default);
Vue.component('subDirectivo',require('./subDirectivo.vue').default);

import * as reporte from '../../functions.js';

export default {
    data(){return{
        modalFilial:0, accion:1, ipbirt:'', 
        arrayDepartamentos:[], arrayMunicipios:[], arrayFiliales:[], 
        regDepartamento:[], regFilial:'',
        iddepartamento:'', idmunicipio:'', codfilial:'', 
        sigla:'', direccion:'', telfijo:'', telcelular:'',
        divFiliales:1, divOficinas:0, divDirectivos:0,
    }},

    methods:{
        listaFiliales(iddepartamento,activo){
            $('#r'+activo).prop('checked',true);
            if(this.arrayDepartamentos.length) this.verDepartamento(iddepartamento);
            this.iddepartamento=iddepartamento;
            var url='/fil_filial/listaFiliales?activo='+activo;
            if(iddepartamento) url+='&iddepartamento='+iddepartamento;
            axios.get(url).then(response=>{
                this.arrayFiliales=response.data.filiales;
                this.ipbirt=response.data.ipbirt;
            });
        },

        listaDepartamentos(){
            var url='/par_departamento/listaDepartamentos';
            axios.get(url).then(response=>{
                this.arrayDepartamentos=response.data.departamentos;
                this.verDepartamento(this.iddepartamento);
            });
        },

        listaMunicipios(iddepartamento){
            var url='/par_municipio/listaMunicipios?iddepartamento='+iddepartamento+'&activo=1';
            axios.get(url).then(response=>{
                this.arrayMunicipios=response.data.municipios;
            });
        },

        verDepartamento(iddepartamento){
            for(var i=0; i<this.arrayDepartamentos.length; i++)
                if(this.arrayDepartamentos[i].iddepartamento==iddepartamento)
                {   this.regDepartamento=this.arrayDepartamentos[i]; return; }
        },

        nivelPrevio(){
            this.divFiliales=1;
            this.divOficinas=0;
            this.divDirectivos=0;
        },

        oficinasFilial(filial){
            this.regFilial=filial;
            this.divFiliales=0;
            this.divOficinas=1;
        },

        directivosFilial(filial){
            this.regFilial=filial;
            this.divFiliales=0;
            this.divDirectivos=1;
        },

        generarCodigo(){
            var url='/fil_filial/listaFiliales?orden=desc';
            axios.get(url).then(response=>{
                var arrayCodigos=response.data.filiales;
                var codfilial=(arrayCodigos.length?(arrayCodigos[0].codfilial*1):0)+1;
                this.codfilial=codfilial<10?'0'+codfilial:codfilial;
            })
        },

        nuevaFilial(){
            if(!this.iddepartamento){ swal('Especifique un departamento','','error'); return; }
            this.listaMunicipios(this.iddepartamento);
            this.generarCodigo();
            this.modalFilial=1;
            this.accion=1;
            this.idmunicipio='';
            this.sigla='';
            this.direccion='';
            this.telfijo='';
            this.telcelular='';
        },

        editarFilial(filial){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalFilial=1;
            this.accion=2;
            this.listaMunicipios(filial.iddepartamento);
            this.verDepartamento(filial.iddepartamento);
            this.idfilial=filial.idfilial;
            this.iddepartamento=filial.iddepartamento;
            this.idmunicipio=filial.idmunicipio;
            this.sigla=filial.sigla;
            this.codfilial=filial.codfilial;
            this.direccion=filial.direccion;
            this.telfijo=filial.telfijo;
            this.telcelular=filial.telcelular;
        },

        validarFilial(){
            this.$validator.validateAll().then(result=>{
                if(!result) {swal('Datos inválidos','Revise los errores','error'); return; }
                this.accion==1?this.storeFilial():this.updateFilial();
            });
        },

        storeFilial(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            axios.post('/fil_filial/storeFilial',{
                'iddepartamento':this.iddepartamento,
                'idmunicipio':this.idmunicipio,
                'codfilial':this.codfilial,
                'sigla':this.sigla.toUpperCase(),
                'direccion':this.direccion,
                'telfijo':this.telfijo,
                'telcelular':this.telcelular
            }).then(response=>{
                swal('Filial creada correctamente','','success');
                this.modalFilial=0;
                this.listaFiliales(this.iddepartamento,1);
            });
        },

        updateFilial(){
            axios.put('/fil_filial/updateFilial',{
                'idfilial':this.idfilial,
                'idmunicipio':this.idmunicipio,
                'codfilial':this.codfilial,
                'sigla':this.sigla.toUpperCase(),
                'direccion':this.direccion,
                'telfijo':this.telfijo,
                'telcelular':this.telcelular
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalFilial=0;
                this.listaFiliales(this.iddepartamento,1);
            });
        },

        estadoFilial(filial){
            this.idfilial=filial.idfilial;
            if(filial.activo){
                swal({title:'Desactivará la filial '+filial.nommunicipio, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Filial',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{confirmar.value?this.switchFilial(0):''});
            }
            else this.switchFilial(1);
        },

        switchFilial(activo){
            var url='/fil_filial/switchFilial?idfilial='+this.idfilial;
            axios.put(url).then(response=>{
                swal(activo?'Activado correctamente':'Desactivado correctamente','','success');
                this.listaFiliales(this.iddepartamento,activo);
            });
        },

        economicoFilial(filial){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/filiales');
            url.push('/fil_ingresos.rptdesign&idfilial='+filial.idfilial);
            url.push('&__format=pdf');
            reporte.viewPDF(url.join(''),'Movimiento Económico');
        },

        reporteFiliales(){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/filiales');
            url.push('/fil_filiales.rptdesign'); //archivo
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Directivos');
        },
    },

    mounted() {
        this.listaDepartamentos();
        this.listaFiliales(this.iddepartamento,1);
    }

}
</script>

