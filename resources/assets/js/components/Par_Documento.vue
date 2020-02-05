<template>
<main class="main">
    <div class="breadcrumb titmodulo">Parámetros > Documentos</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 titcard">
                        <div class="tablatit">
                            <div class="tcelda" v-if="buscado">Resultado de la búsqueda</div>
                            <div class="tcelda" v-else>Módulo
                                <span v-text="regModulo.nommodulo" style="text-transform:capitalize"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 noprint">
                        <autocomplete dedonde="/par_documento/listaDocumentos?buscado=" ></autocomplete>
                        <!--
                        <div class="input-group">
                            <input type="text" class="form-control" @keyup.enter="listaDocumentos()" v-model="buscado">
                            <span class="input-group-append">
                                <button class="btn btn-primary icon-magnifier" style="margin-top:0px"
                                @click="listaDocumentos()"></button>
                            </span>
                        </div>
                        -->
                    </div>
                    <div class="col-md-2 noprint text-right">
                        <div class="input-group-append" style="display:inline;">
                            <button class="btn btn-primary dropdown-toggle" style="margin-top:0px" data-toggle="dropdown" aria-expanded="false">
                                Módulo...<span class="caret"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                <a href="#" v-for="modulo in arrayModulos" :key="modulo.idmodulo"
                                    class="dropdown-item" v-text="modulo.nommodulo" @click="listaDocumentos(modulo.idmodulo)"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-block"  style="margin-top:0px" 
                        @click="nuevoDocumento()" title="Nuevo Documento">Nuevo Documento</button>
                    </div>
                </div>

            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr class="tcabecera">
                            <th><span class="badge badge-success" v-text="arrayDocumentos.length+' items'"></span></th>
                            <th>Documento</th>
                            <!-- <th>Módulos Asociados</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="documento in arrayDocumentos" :key="documento.iddocumento" :class="documento.activo?'':'txtdesactivado'">
                            <template v-if="delModulo(documento.idmodulo)">
                                <td v-if="documento.activo" align="center" nowrap>
                                    <button class="btn btn-warning btn-sm icon-pencil"  title="Editar Documento"
                                        @click="editarDocumento(documento)"></button> 
                                    <button class="btn btn-danger btn-sm icon-trash"  title="Desactivar documento"
                                        @click="estadoDocumento(documento)"></button>
                                </td>
                                <td v-else align="center">
                                    <button class="btn btn-warning btn-sm icon-action-redo" title="Activar Documento"
                                        @click="estadoDocumento(documento)"></button>
                                </td>
                                <td v-text="documento.nomdocumento"></td>
                                <!--
                                <td><span v-text="documento.idmodulo"></span> el ides:
                                    {{idmodulo}}
                                </td>
                                -->
                            </template>
                        </tr>                                
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- MODAL DOCUMENTO MODAL DOCUMENTO MODAL DOCUMENTO MODAL DOCUMENTO MODAL DOCUMENTO -->
    <!-- MODAL DOCUMENTO MODAL DOCUMENTO MODAL DOCUMENTO MODAL DOCUMENTO MODAL DOCUMENTO -->
    <!-- MODAL DOCUMENTO MODAL DOCUMENTO MODAL DOCUMENTO MODAL DOCUMENTO MODAL DOCUMENTO -->
    <div class="modal" :class="modalDocumento?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Documento</h4>
                    <button class="close" @click="modalDocumento=0">x</button>
                </div>
                <div class="modal-body">
                    Nombre del documento <span class="txtasterisco"></span>
                    <input type="text" class="form-control" v-model="nomdocumento" @keyup="valDocumento()">
                    <br>
                    <!--
                    Módulo Relacionado <span class="txtasterisco"></span>
                    <select class="form-control" v-model="idmodulo" @change="valDocumento()">
                        <option v-for="modulo in arrayModulos" :key="modulo.idmodulo"
                            :value="modulo.idmodulo" v-text="modulo.nommodulo"></option>
                    </select>
                    -->
                    <center class="titcampo">Módulos Asociados <span class="txtasterisco"></span></center>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <ul style="list-style:none; padding-left:15px">
                                <li v-for="(modulo,index) in arrayModulos" :key="modulo.idmodulo" >
                                    <template v-if="index<=mitad">
                                        <input type="checkbox" :value="modulo.idmodulo" v-model="arrayIDmodulos" @change="valDocumento()">
                                        <span style="text-transform:capitalize" v-text="modulo.nommodulo"></span>
                                    </template>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <ul style="list-style:none; padding-left:15px">
                                <li v-for="(modulo,index) in arrayModulos" :key="modulo.idmodulo" >
                                    <template v-if="index>mitad">
                                        <input type="checkbox" :value="modulo.idmodulo" v-model="arrayIDmodulos" @change="valDocumento()">
                                        <span style="text-transform:capitalize" v-text="modulo.nommodulo"></span>
                                    </template>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="txtvalidador text-center">* Datos obligatorios</div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalDocumento=0">Cerrar</button>
                    <button class="btn btn-primary" :disabled="!completo"  @click="accion==1?storeDocumento():updateDocumento()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
    
</main>
</template>

<script>
export default {
    data (){ return {
        buscado:'',
        modalDocumento:0, accion:1, completo:'', 
        iddocumento:'', idmodulo:7, nomdocumento:'', mitad:'',
        arrayDocumentos:[], arrayModulos:[], regModulo:[], arrayIDmodulos:[]
    }},

    methods:{
        listaDocumentos(idmodulo){
            this.idmodulo=idmodulo;
            if(this.arrayModulos.length) this.verModulo(idmodulo);
            //this.buscado='';
            var url='/par_documento/listaDocumentos?idmodulo='+idmodulo;
            axios.get(url).then(response => {
                this.arrayDocumentos = response.data.documentos;
            });
        },

        listaModulos() {
            var url="/par_modulo/selectModulo";
            axios.get(url).then(response=>{
                this.arrayModulos = response.data.modulos;
                this.mitad=Math.floor(this.arrayModulos.length/2);
                this.verModulo(this.idmodulo);
            });
        },

        verModulo(idmodulo){
            for(var i=0; i<this.arrayModulos.length; i++)
                if(this.arrayModulos[i].idmodulo==idmodulo)
                    {this.regModulo=this.arrayModulos[i]; return;}
        },

        delModulo(idmodulo){
            var arrayModulos=JSON.parse('['+idmodulo+']');
            for(var i=0; i<arrayModulos.length; i++)
                if(this.idmodulo==arrayModulos[i]) return true;
        },

        nuevoDocumento(){
            this.modalDocumento=1;
            this.accion=1;
            this.completo=0;
            this.nomdocumento='';
            this.arrayIDmodulos=[];
        },

        editarDocumento(documento){
            this.modalDocumento=1;
            this.accion=2;
            this.completo=1;
            this.iddocumento=documento.iddocumento;
            this.nomdocumento=documento.nomdocumento;
            this.arrayIDmodulos=JSON.parse('['+documento.idmodulo+']');
        },

        valDocumento(){
            this.completo=0;
            if((this.nomdocumento)&&(this.arrayIDmodulos.length)) this.completo=1;
        },

        storeDocumento(){
            axios.post('/par_documento/storeDocumento',{
                'nomdocumento': this.nomdocumento,
                'idmodulo': this.arrayIDmodulos.join()
            }).then(response=>{
                swal('Documento registrado correctamente','','success');
                this.modalDocumento=0;
                this.listaDocumentos(this.idmodulo);
            });
        },

        updateDocumento(){
            axios.put('/par_documento/updateDocumento',{
                'iddocumento':this.iddocumento,
                'idmodulo':this.arrayIDmodulos.join(),
                'nomdocumento':this.nomdocumento
            }).then(response=> {
                swal('Datos Actualizados','','success');
                this.modalDocumento=0;
                this.listaDocumentos(this.idmodulo);
            });
        },

        /*
        buscarDocumento(){
            this.idmodulo='';
            let me=this;
            var url='/par_documento/searchDocumento?buscado='+this.buscado;
            axios.get(url).then(function(response){
                me.arrayDocumentos=response.data.documentos;
            });
        },
        */

        estadoDocumento(documento){
            this.iddocumento=documento.iddocumento;
            this.idmodulo=documento.idmodulo;
            if(documento.activo){
                swal({title:'Desactivará '+documento.nomdocumento, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Documento',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then( confirmar => {
                    if(confirmar.value) this.switchDocumento(1);
                });
            }
            else this.switchDocumento(0);
        },

        switchDocumento(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/par_documento/switchDocumento?iddocumento='+this.iddocumento;
            axios.put(url).then(response => {
                swal(titswal+' correctamente','','success');
                this.listaDocumentos(this.idmodulo);
            });
        },


    },

    mounted() {
        this.listaModulos();
        this.listaDocumentos(7);
    }
}
</script>

