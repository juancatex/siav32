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
        <div class="card-body table-responsive">
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr>
                        <th><span class="badge badge-success" v-text="arrayOficinas.length+' items'"></span></th>
                        <th>Cód.</th>
                        <th>Oficina</th>
                        <th>Responsable</th>
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
                        <td v-text="oficina.nomresponsable"></td>
                    </tr>
                </tbody>
            </table>

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
                        Código:
                            <input type="text" class="form-control txtnegrita text-center" v-model="codoficina" disabled>
                        
                        Nombre Oficina:
                            <input type="text" class="form-control" v-model="nomoficina">
                        
                        
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
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalOficina=0">Cancelar</button>
                    <button class="btn btn-primary" @click="accion==1?storeOficina():updateOficina()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
</main>    
</template>

<script>

export default {
    props:['regFilial'],

    data(){ return {
        modalOficina:0, accion:1, completo:0,
        arrayOficinas:[], arrayDirectivos:[], arrayEmpleados:[], 
        regResponsable:[], esDirectivo:'', tiporesponsable:'',
        idoficina:'', codoficina:'', nomoficina:'', idresponsable:'', 
    }}, 

    methods: {
        listaOficinas(idfilial){
            var url='/fil_oficina/listaOficinas?idfilial='+idfilial+'&responsables=1';
            axios.get(url).then(response=>{
                this.arrayOficinas=response.data.oficinas;
            });
        },

        listaEmpleados(idfilial){
            var url='/rrh_empleado/listaEmpleados?idfilial='+idfilial+'&activo=1';
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

        generarCodigo(){
            var tam=this.arrayOficinas.length-1;
            this.codoficina=0;
            if(tam>=0) this.codoficina=this.arrayOficinas[tam].codoficina;
            this.codoficina++;
            if(this.codoficina<10) this.codoficina='0'+this.codoficina;
        },

        nuevaOficina(){
            this.modalOficina=1;
            this.generarCodigo();
            this.listaEmpleados(this.regFilial.idfilial);
            this.listaDirectivos(this.regFilial.idfilial);
            this.nomoficina='';
            this.idresponsable='';
            this.tiporesponsable='';
        },

        editarOficina(oficina){            
            this.modalOficina=1;
            this.accion=2;
            this.completo=1;
            this.listaEmpleados(this.regFilial.idfilial);
            this.listaDirectivos(this.regFilial.idfilial);
            this.idoficina=oficina.idoficina;
            this.codoficina=oficina.codoficina;
            this.nomoficina=oficina.nomoficina;
            this.tiporesponsable=oficina.tiporesponsable;
            this.esDirectivo=oficina.tiporesponsable=='s'?true:false;
            this.idresponsable=oficina.idresponsable;
        },

        valOficina(){
            this.completo=0;
            if((this.nomoficina)
                &&(this.idcargo)
                &&(this.idresponsable)) this.completo=1;
        },

        storeOficina(){
            axios.post('fil_oficina/storeOficina',{
                'idfilial':this.idfilial,
                'idcargo':this.idcargo,
                'codoficina':this.codoficina,
                'nomoficina':this.nomoficina.toUpperCase(),
                'idresponsable':this.idresponsable,
                'tiporesponsable':this.esDirectivo?'s':'c'
            }).then(response=>{
                swal('Oficina creada correctamente','','success');
                this.listaOficinas(this.regFilial.idfilial);
                this.modalOficina=0;
            });
        },

        updateOficina(){
            axios.put('fil_oficina/updateOficina',{
                'idoficina':this.idoficina,
                'nomoficina':this.nomoficina,
                'idresponsable':this.idresponsable,
                'tiporesponsable':this.esDirectivo?'s':'c'
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalOficina=0;
                this.listaOficinas(this.regFilial.idfilial);
            });
        },

        estadoOficina(oficina){
            this.idoficina=oficina.idoficina;
            if(oficina.activo){
                swal({title:'Desactivará la Oficina '+oficina.nomoficina, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Oficina',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchOficina(1);
                });
            }
            else this.switchOficina(0);
        },

        switchOficina(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/fil_oficina/switchOficina?idoficina='+this.idoficina;
            axios.put(url).then(response=>{
                swal(titswal+' correctamente','','success');
                this.listaOficinas(this.regFilial.idfilial);
            });
        },
    }, 

    mounted(){
        this.listaOficinas(this.regFilial.idfilial);
    }

}
</script>




