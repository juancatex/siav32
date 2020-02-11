<template>
<main>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <div class="tablatit titcard">
                        <div class="tcelda" v-text="'Filial '+regFilial.nommunicipio"></div>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <button class="btn btn-primary" style="margin-top:0" @click="nuevaOficina()">Nueva Oficina</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="text-right" style="padding-bottom:10px">
                <div class="vervigente">Ver: &nbsp;
                    <input type="radio" name="estado" id="r1" @click="listaOficinas(regFilial.idfilial,1)">Vigentes &nbsp;
                    <input type="radio" name="estado" id="r0" @click="listaOficinas(regFilial.idfilial,0)">Inactivos
                </div>
                <button class="btn btn-success btn-sm icon-printer" title="Vista de impresión" style="margin-left:10px"
                    @click="reporteOficinas(regFilial.idfilial)"></button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th align="center"><span class="badge badge-success" v-text="arrayOficinas.length+' items'"></span></th>
                            <th align="center">Código</th>
                            <th>Oficina</th>
                            <th>Responsable</th>
                            <th>Dependencia </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="oficina in arrayOficinas" :key="oficina.id" :class="oficina.activo?'':'txtdesactivado'"> 
                            <td v-if="oficina.activo" align="center" nowrap>
                                <button class="btn btn-warning btn-sm icon-pencil" title="Editar Datos"
                                    @click="editarOficina(oficina)"></button>
                                <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Oficina"
                                    @click="estadoOficina(oficina)"></button>
                            </td>
                            <td v-else align="center">
                                <button class="btn btn-warning ing btn-sm icon-action-redo" title="Reactivar Oficina"
                                    @click="estadoOficina(oficina)"></button>
                            </td>
                            <td v-text="oficina.codoficina" align="center"></td>
                            <td v-text="oficina.nomoficina"></td>
                            <td v-text="oficina.nomresponsable" nowrap></td>
                            <td v-text="oficina.nomunidad"></td>                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal" :class="modalOficina?'mostrar':''" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nueva':'Modificar'"></span> Oficina</h4>
                    <button class="close" @click="modalOficina=0">x</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado" v-text="'Filial '+regFilial.nommunicipio"></h4>
                    <br>
                    <div class="tabla100">
                        <div class="tcelda">Código (según Dependencia):</div>
                        <div class="tcelda">
                            <input type="text" class="form-control txtnegrita text-center" readonly v-model="codoficina">
                        </div>
                    </div>
                    <br>Nombre Oficina: <span class="txtasterisco"></span>
                        <input type="text" class="form-control" v-model="nomoficina"
                            name="nom" :class="{'invalido':errors.has('nom')}" v-validate="'required|alpha_spaces'">
                        <p v-if="errors.has('nom')" class="txtvalidador">Dato requerido</p>
                    <br>Dependencia: <span class="txtasterisco"></span>
                        <select class="form-control" v-model="idunidad" @change="generarCodigo(regFilial.idfilial,idunidad)"
                            name="dep" :class="{'invalido':errors.has('dep')}" v-validate="'required'">
                            <option v-for="unidad in arrayUnidades" :key="unidad.id" 
                                :value="unidad.idunidad" v-text="unidad.nomunidad"></option>
                        </select>
                        <p v-if="errors.has('dep')" class="txtvalidador">Selecione una Dependencia</p>
                    <br>
                    <div class="tabla100">
                        <div class="tcelda">Responsable:</div>
                        <div class="tcelda text-right">
                            <input type="checkbox" v-model="esDirectivo">Socio Directivo
                        </div>
                    </div>
                    <select v-if="esDirectivo" class="form-control" v-model="idresponsable">
                        <option v-for="directivo in arrayDirectivos" :key="directivo.id" :value="directivo.idsocio">
                            <span v-text="directivo.apaterno"></span>
                            <span v-text="directivo.amaterno"></span>
                            <span v-text="directivo.nombre"></span> 
                        </option>
                    </select>
                    <select v-else class="form-control" v-model="idresponsable">
                        <option v-for="empleado in arrayEmpleados" :key="empleado.id" :value="empleado.idempleado">
                            <span v-text="empleado.apaterno"></span>
                            <span v-text="empleado.amaterno"></span>
                            <span v-text="empleado.nombre"></span> 
                        </option>
                    </select>
                    <div class="txtobligatorio text-right"></div>                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalOficina=0">Cancelar</button>
                    <button class="btn btn-primary" @click="validarOficina()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
</main>    
</template>

<script>

import * as reporte from '../../functions.js';

export default {
    props:['regFilial'],

    data(){ return {
        modalOficina:0, accion:1, ipbirt:'',
        arrayOficinas:[], arrayDirectivos:[], arrayEmpleados:[], arrayUnidades:[],
        regResponsable:[], esDirectivo:'', tiporesponsable:'', idfilial:'',
        idunidad:'', idoficina:'', codoficina:'', nomoficina:'', idresponsable:'', 
    }}, 

    methods: {
        listaOficinas(idfilial,activo){
            $('#r'+activo).prop('checked',true);
            var url='/fil_oficina/listaOficinas?idfilial='+idfilial
            +'&responsables=1'+'&orden=codunidad&activo='+activo;
            axios.get(url).then(response=>{
                this.arrayOficinas=response.data.oficinas;
                this.ipbirt=response.data.ipbirt;
            });
        },

        listaEmpleados(idfilial){
            var url='/rrh_empleado/listaEmpleados?activo=1&sedelp='+idfilial;
            //url+=idfilial==1?'1':'0';
            axios.get(url).then(response=>{
                this.arrayEmpleados=response.data.empleados;
            });
        },

        listaDirectivos(idfilial){
            var url='/fil_directivo/listaDirectivos?idfilial='+idfilial+'&activo=1';
            axios.get(url).then(response=>{
                this.arrayDirectivos=response.data.directivos;
            });
        },
        
        listaUnidades(){
            var url='/fil_unidad/listaUnidades?activo=1';
            axios.get(url).then(response=>{
                this.arrayUnidades=response.data.unidades;
            });
        },

        async generarCodigo(idfilial,idunidad){
            var url='/fil_oficina/listaOficinas?idfilial='+idfilial+'&idunidad='+idunidad;
            let response=await axios.get(url);
            var arrayCodigos=response.data.oficinas;
            var tam=arrayCodigos.length-1;
            this.codoficina=0;
            if(tam>=0) this.codoficina=arrayCodigos[tam].codoficina;
            this.codoficina++;
            if(this.codoficina<10) this.codoficina='0'+this.codoficina;
        },

        nuevaOficina(){
            this.modalOficina=1;
            this.accion=1;
            this.listaUnidades();            
            this.listaEmpleados(this.regFilial.idfilial);
            this.listaDirectivos(this.regFilial.idfilial);
            this.idunidad='';
            this.idfilial='';
            this.nomoficina='';
            this.idresponsable='';
            this.tiporesponsable='';
            this.$validator.reset();
        },

        editarOficina(oficina){          
            window.scroll({top:0,left:0,behavior:'smooth'});  
            this.modalOficina=1;
            this.accion=2;
            this.listaUnidades();
            this.listaEmpleados(this.regFilial.idfilial);
            this.listaDirectivos(this.regFilial.idfilial);
            this.idoficina=oficina.idoficina;
            this.codoficina=oficina.codoficina;
            this.idunidad=oficina.idunidad;
            this.idfilial=oficina.idfilial;
            this.nomoficina=oficina.nomoficina;
            this.tiporesponsable=oficina.tiporesponsable;
            this.esDirectivo=oficina.tiporesponsable=='s'?true:false;
            this.idresponsable=oficina.idresponsable;
        },

        validarOficina(){
            this.$validator.validateAll().then(result=>{
                if(!result) {swal('Datos inválidos','Revise los errores','error'); return;}
                this.accion==1?this.storeOficina():this.updateOficina();
            });
        },

        storeOficina(){
            axios.post('fil_oficina/storeOficina',{
                'idfilial':this.regFilial.idfilial,
                'idcargo':this.idcargo,
                'codoficina':this.codoficina,
                'idunidad':this.idunidad,
                'nomoficina':this.nomoficina.toUpperCase(),
                'idresponsable':this.idresponsable,
                'tiporesponsable':this.esDirectivo?'s':'c'
            }).then(response=>{
                swal('Oficina creada correctamente','','success');
                this.listaOficinas(this.regFilial.idfilial,1);
                this.modalOficina=0;
            });
        },

        updateOficina(){
            axios.put('fil_oficina/updateOficina',{
                'idoficina':this.idoficina,
                'idunidad':this.idunidad,
                'nomoficina':this.nomoficina.toUpperCase(),
                'idresponsable':this.idresponsable,
                'tiporesponsable':this.esDirectivo?'s':'c'
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalOficina=0;
                this.listaOficinas(this.regFilial.idfilial,1);
            });
        },

        estadoOficina(oficina){
            this.idoficina=oficina.idoficina;
            if(oficina.activo){
                swal({title:'Desactivará la Oficina<br>'+oficina.nomoficina, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Oficina',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{confirmar.value?this.switchOficina(0):''});
            }
            else this.switchOficina(1);
        },

        switchOficina(activo){
            var url='/fil_oficina/switchOficina?idoficina='+this.idoficina;
            axios.put(url).then(response=>{
                swal(activo?'Activado correctamente':'Desactivado correctamente','','success');
                this.listaOficinas(this.regFilial.idfilial,activo);
            });
        },

        reporteOficinas(idfilial){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/filiales');
            url.push('/fil_oficinas.rptdesign'); //archivo
            url.push('&idfilial='+idfilial); 
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Oficinas');
        },
    }, 

    mounted(){
        this.listaOficinas(this.regFilial.idfilial,1);
    }

}
</script>




