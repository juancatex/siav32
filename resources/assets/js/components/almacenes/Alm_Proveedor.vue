<template>
<main class="main">
    <div class="breadcrumb titmodulo">Almacén > Proveedores</div>
    <div class="container-fluid">
        <div class="card" >
            <div class="body" style="margin-top:15px">
                <nav class="text-center">
                    <ul class="pagination" style="display:inline-block">
                        <!-- :class="inicial.charCodeAt(0)==(i+1)?'active':''" -->
                        <li v-for="i in 26" :key="i" class="page-item" style="float:left" >
                            <a href="#" class="page-link" v-text="String.fromCharCode(i+64)" @click="listaProveedores(String.fromCharCode(i+64))"></a>
                        </li>
                        <li class="page-item" style="float:left">
                            <a href="#" class="page-link"  @click="listaProveedores()">Todos</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header titcard">
                <div class="row">
                    <div class="col-md-6">
                        <div class="ttabla">
                            <div class="tcelda">Listado de Proveedores</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="buscado" 
                                @keyup.enter="listaProveedores('')" placeholder="Nombre empresa o NIT">
                            <span class="input-group-append">
                                <button class="btn btn-primary icon-magnifier" style="margin-top:0px" 
                                @click="listaProveedores('')" title="Buscar nombre de empresa o NIT"></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-block" style="margin-top:0px"
                            @click="nuevoProveedor()" title="Nuevo Proveedor">Nuevo Proveedor</button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th><span class="badge badge-success" v-text="arrayProveedores.length+' items'"></span></th>
                            <th>Nombre</th>
                            <th>NIT</th>
                            <th>Representante</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Celular</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="proveedor in arrayProveedores" :key="proveedor.idproveedor"
                            :class="proveedor.activo?'':'txtdesactivado'">
                            <td v-if="proveedor.activo" align="center">
                                <button class="btn btn-warning btn-sm icon-pencil"  title="Editar Proveedor"
                                    @click="editarProveedor(proveedor)"></button> 
                                <button class="btn btn-danger btn-sm icon-trash"  title="Desactivar proveedor"
                                    @click="estadoProveedor(proveedor)"></button>
                            </td>
                            <td v-else align="center">
                                <button class="btn btn-warning btn-sm icon-action-redo" title="Activar Proveedor"
                                    @click="estadoProveedor(proveedor)"></button>
                            </td>
                            <td v-text="proveedor.nomproveedor"></td>
                            <td v-text="proveedor.nit" align="center"></td>
                            <td v-text="proveedor.nomcontacto"></td>
                            <td v-text="proveedor.direccion"></td>
                            <td v-text="proveedor.telefono" align="center"></td>
                            <td v-text="proveedor.celular" align="center"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR  -->
    <!-- MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR  -->
    <div class="modal" :class="modalProveedor?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button class="close" @click="modalProveedor=0">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            Nombre Proveedor
                            <input type="text" class="form-control" v-model="nomproveedor">
                            <br>Dirección
                            <input type="text" class="form-control" v-model="direccion">
                            <br>Nombre Contacto
                            <input type="text" class="form-control" v-model="nomcontacto">
                        </div>
                        <div class="col-md-4">
                            NIT
                            <input type="text" class="form-control" v-model="nit">
                            <br>Teléfono
                            <input type="text" class="form-control" v-model="telefono">
                            <br>Celular
                            <input type="text" class="form-control" v-model="celular">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="modalProveedor=0">Cerrar</button>
                    <button v-if="accion==1" class="btn btn-primary" @click="storeProveedor()">Guardar</button>
                    <button v-if="accion==2" class="btn btn-primary" @click="updateProveedor()">Guardar Modificaciones</button>
                </div>
            </div>
        </div>
    </div>

</main>
</template>

<script>
export default {
    data(){ return {
        modalProveedor:0, accion:1, tituloModal:'',
        arrayProveedores:[],
        regProveeedor:[],
        idproveedor:'', nomproveedor:'', nomcontacto:'', nit:'', direccion:'', telefono:'', celular:'',
        buscado:'', //inicial:'',
    }},

    methods: {
        listaProveedores(inicial){
            var url='/alm_proveedor/listaProveedores';
            if(inicial) url+='?inicial='+inicial
            if(this.buscado) url+='?buscado='+this.buscado;
            let me=this;
            axios.get(url).then(function(response){
                me.arrayProveedores=response.data.proveedores;
            });
        },

        resetProveedor(){
            this.nomproveedor='';
            this.nomcontacto='';
            this.nit='';
            this.direccion='';
            this.telefono='';
            this.celular='';
        },

        nuevoProveedor(){
            this.modalProveedor=1;
            this.accion=1;
            this.tituloModal='Nuevo Proveedor';
            this.resetProveedor();
        },

        editarProveedor(proveedor){
            this.modalProveedor=1;
            this.accion=2;
            this.tituloModal='Modificar datos';
            this.idproveedor=proveedor.idproveedor;
            this.nomproveedor=proveedor.nomproveedor;
            this.nomcontacto=proveedor.nomcontacto;
            this.direccion=proveedor.direccion;
            this.nit=proveedor.nit;
            this.telefono=proveedor.telefono;
            this.celular=proveedor.celular;
        },

        storeProveedor(){
            var url='/alm_proveedor/storeProveedor';
            let me=this;
            axios.post(url,{
                'nomproveedor':this.nomproveedor.toUpperCase(),
                'nomcontacto':this.nomcontacto.toUpperCase(),
                'nit':this.nit,
                'direccion':this.direccion,
                'telefono':this.telefono,
                'celular':this.celular
            }).then(function(){
                swal('Adicionado correctamente','','success');
                me.modalProveedor=0;
                me.listaProveedores();
            });
        },

        updateProveedor(){
            let me=this;
            axios.put('/alm_proveedor/updateProveedor',{
                'idproveedor':this.idproveedor,
                'nomproveedor':this.nomproveedor.toUpperCase(),
                'nomcontacto':this.nomcontacto.toUpperCase(),
                'nit':this.nit,
                'direccion':this.direccion,
                'telefono':this.telefono,
                'celular':this.celular
            }).then(function(){
                swal('Modificaciones guardadas','','success');
                me.modalProveedor=0;
                me.listaProveedores();
            });            
        },

        estadoProveedor(proveedor){
            this.idproveedor=proveedor.idproveedor;
            if(proveedor.activo){
                swal({title:'Desactivará '+proveedor.nomproveedor, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Proveedor',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchProveedor(1);
                });
            }
            else this.switchProveedor(0);
        },

        switchProveedor(activo){
            let me=this;
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/alm_proveedor/switchProveedor?idproveedor='+this.idproveedor;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','');
                me.listaProveedores();
            });
        },

    },

    mounted(){
        this.listaProveedores();
    }
}
</script>