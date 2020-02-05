<template>
<main class="main">
    <div class="breadcrumb titmodulo">Almacén > Suministros</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 titcard">
                        <div class="tablatit">
                            <div class="tcelda">Cuenta: <span v-text="regCuenta.nomcuenta"></span></div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="input-group-append" style="display:inline;">
                            <button class="btn btn-primary dropdown-toggle" style="margin-top:0px" data-toggle="dropdown" aria-expanded="false">
                                Otra cuenta...<span class="caret"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                <a href="#" v-for="cuenta in arrayCuentas" :key="cuenta.idcuenta"
                                    class="dropdown-item" v-text="cuenta.nomcuenta" @click="listaSuministros(cuenta.idcuenta)"></a>
                            </div>
                        </div>
                        <button class="btn btn-primary" style="margin-top:0px" @click="nuevoSuministro()">Nuevo Suministro</button>                        
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th><span class="badge badge-success noprint" v-text="arraySuministros.length+' items'"></span></th>
                            <th>Código </th>
                            <th>Descripción del Suministro</th>
                            <th>Máximo</th>
                            <th>Mínimo</th>
                            <th>Medida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="suministro in arraySuministros" :key="suministro.idsuministro" 
                            align="center" :class="suministro.activo?'':'txtdesactivado'">
                            <td v-if="suministro.activo" nowrap>
                                <button class="btn btn-warning btn-sm icon-pencil" title="Editar"
                                    @click="editarSuministro(suministro)"></button>
                                <button class="btn btn-warning btn-sm icon-graph" title="Ficha de Control"
                                    @click="verFicha(suministro)"></button>
                                <button class="btn btn-danger btn-sm icon-trash" title="Desactivar"
                                    @click="estadoSuministro(suministro)"></button>
                            </td>
                            <td v-else>
                                <button class="btn btn-warning btn-sm icon-action-redo" @click="estadoSuministro(suministro)"></button>
                            </td>
                            <td v-text="suministro.codsuministro"></td>
                            <td v-text="suministro.nomsuministro" align="left"></td>
                            <td v-text="suministro.maximo"></td>
                            <td v-text="suministro.minimo"></td>
                            <td v-text="suministro.nommedida"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL SUMINISTRO MODAL SUMINISTRO MODAL SUMINISTRO MODAL SUMINISTRO MODAL SUMINISTRO -->
    <!-- MODAL SUMINISTRO MODAL SUMINISTRO MODAL SUMINISTRO MODAL SUMINISTRO MODAL SUMINISTRO -->
    <div class="modal" :class="modalSuministro?'mostrar':''">
        <div class="modal-dialog modal-primary modal-sm">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Suministro</h4>
                    <button class="close" @click="modalSuministro=0">x</button>
                </div>            
                <div class="modal-body">
                    <h4 class="titsubrayado">Cuenta:<br><span v-text="regCuenta.nomcuenta"></span></h4>
                    <div class="tfila">
                        <div class="tcelda nowrap">Código Suministro: &nbsp;</div>
                        <div class="tcelda"><input type="text" class="form-control text-center txtnegrita" readonly v-model="codsuministro"></div>
                    </div>
                    
                    <br>Nombre Suministro:
                    <input type="text" class="form-control" v-model="nomsuministro">
                    Medida:
                    <select class="form-control" v-model="idmedida" >
                        <option v-for="medida in arrayMedidas" :key="medida.idmedida"
                            :value="medida.idmedida" v-text="medida.nommedida"></option>
                    </select> 
                    <div class="tfila">
                        <div class="tcelda">Cant Máxima
                            <div class="input-group">
                                <input type="text" class="form-control text-right" v-model="maximo">
                                <div class="input-group-append">
                                    <span class="input-group-text" v-text="nomMedida(idmedida)+'s'"></span>
                                </div>
                            </div>
                        </div>
                        <div class="tcelda" style="width:10px"></div>
                        <div class="tcelda">Cant Mínima
                            <div class="input-group">
                                <input type="text" class="form-control text-right" v-model="minimo">
                                <div class="input-group-append">
                                    <span class="input-group-text" v-text="nomMedida(idmedida)+'s'"></span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalSuministro=0">Cerrar</button>
                    <button class="btn btn-primary" @click="accion==1?storeSuministro():updateSuministro()">
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
    data(){ return {
        modalSuministro:0, accion:1, ipbirt:'',
        arraySuministros:[], arrayMedidas:[], arrayCuentas:[],
        regCuenta:[],
        codsuministro:'', idcuenta:82, idmedida:'', nomsuministro:'', maximo:'', minimo:'',
    }},

    methods:{
        listaSuministros(idcuenta){
            if(this.arrayCuentas.length) this.verCuenta(idcuenta);
            var url='/alm_suministro/listaSuministros?idcuenta='+idcuenta;
            this.idcuenta=idcuenta;
            axios.get(url).then(response=>{
                this.arraySuministros=response.data.suministros;
                this.ipbirt=response.data.ipbirt;
            });            
        },

        listaCuentas(){
            var url='/alm_suministro/listaCuentas';
            axios.get(url).then(response=>{
                this.arrayCuentas=response.data.cuentas;
                this.verCuenta(this.idcuenta);
            });
        },

        listaMedidas(){
            var url='/alm_suministro/listaMedidas';
            axios.get(url).then(response=>{
                this.arrayMedidas=response.data.medidas;
            });
        },

        nomMedida(idmedida){
            if(!idmedida) return '';
            for(var i=0; i<this.arrayMedidas.length; i++)
                if(this.arrayMedidas[i].idmedida==idmedida) 
                    return this.arrayMedidas[i].nommedida;
        },

        verCuenta(idcuenta){
            for(var i=0; i<this.arrayCuentas.length; i++)
                if(this.arrayCuentas[i].idcuenta==idcuenta)
                {   this.regCuenta=this.arrayCuentas[i]; return;  }
        },

        generarCodigo(){
            var tam=this.arraySuministros.length-1;
            this.codsuministro=0;
            if(tam>=0) this.codsuministro=this.arraySuministros[tam].codsuministro;
            this.codsuministro++;
            if(this.codsuministro<10) this.codsuministro='0'+this.codsuministro;
        },

        nuevoSuministro(){
            this.modalSuministro=1;
            this.accion=1;
            this.generarCodigo();
            this.idcuenta=this.regCuenta.idcuenta;
            this.nomsuministro='';
            this.idmedida='';
            this.maximo='';
            this.minimo='';
        },

        editarSuministro(suministro){
            this.modalSuministro=1;
            this.accion=2;
            this.idsuministro=suministro.idsuministro;
            this.codsuministro=suministro.codsuministro
            this.nomsuministro=suministro.nomsuministro;
            this.idmedida=suministro.idmedida;
            this.maximo=suministro.maximo;
            this.minimo=suministro.minimo;
        },

        storeSuministro(){
            //let me=this;
            axios.post('alm_suministro/storeSuministro',{
                'idcuenta':this.idcuenta,
                'codsuministro':this.codsuministro,
                'nomsuministro':this.nomsuministro.toUpperCase(),
                'idmedida':this.idmedida,
                'maximo':this.maximo,
                'minimo':this.minimo,
            }).then(response=>{
                swal('Suministro creado correctamenee','','success');
                this.modalSuministro=0;
                this.listaSuministros(this.idcuenta);
            });
        },
        
        updateSuministro(){
            //let me=this;
            axios.put('alm_suministro/updateSuministro',{
                'idsuministro':this.idsuministro,
                'nomsuministro':this.nomsuministro.toUpperCase(),
                'idmedida':this.idmedida,
                'maximo':this.maximo,
                'minimo':this.minimo,
            }).then(response=>{  
                swal('Datos Actualizados','','success');
                this.modalSuministro=0;
                this.listaSuministros(this.idcuenta);
            });
        },

        estadoSuministro(suministro){
            this.idsuministro=suministro.idsuministro;
            if(suministro.activo){
                swal({ title:'Desactivará<br>'+suministro.nomsuministro, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Suministro',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchSuministro(1);
                });
            }
            else this.switchSuministro(0);
        },

        switchSuministro(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            //let me=this;
            var url='/alm_suministro/switchSuministro?idsuministro='+this.idsuministro;
            axios.put(url).then(response=>{
                swal(titswal+' correctamente','','success');
                this.listaSuministros(this.idcuenta);
            });
        },

        verFicha(suministro){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/almacenes');
            url.push('/alm_ficha.rptdesign'); //archivo
            url.push('&idsuministro='+suministro.idsuministro); 
            url.push('&__format=pdf'); //formato
            //console.log(url.join(''));
            reporte.viewPDF(url.join(''),'Ficha de control');
        },

        
    },

    mounted(){
        this.listaCuentas();
        this.listaSuministros(82);
        this.listaMedidas();
    }
}
</script>
