<template>
    <main class="main">
    
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Entidades Bancarias
                <button type="button" @click="abrirModal('entidadbancaria','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th>Opciones</th>
                            <th>Entidad Bancaria</th>
                            <th>Sigla</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="entidadbancaria in arrayEntidadbancaria" :key="entidadbancaria.identidadbancaria">
                            <td align="center" nowrap>
                                <button class="btn btn-warning btn-sm icon-pencil" @click="abrirModal('entidadbancaria','actualizar',entidadbancaria)"></button> 
                                <button v-if="entidadbancaria.activo" class="btn btn-danger btn-sm icon-trash" @click="desactivarEntidadbancaria(entidadbancaria.identidadbancaria)"></button>
                                <button v-else class="btn btn-info btn-sm icon-check" @click="activarEntidadbancaria(entidadbancaria.identidadbancaria)"></button>
                            </td>
                            <td v-text="entidadbancaria.nomentidadbancaria"></td>
                            <td v-text="entidadbancaria.siglaentidadbancaria"></td>
                            <td align="center">
                                <span v-if="entidadbancaria.activo" class="badge badge-success">Activo</span>
                                <span v-else class="badge badge-danger">Desactivado</span>
                            </td>
                        </tr>                                
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL BANCO MODAL BANCO MODAL BANCO MODAL BANCO -->
    <div class="modal" :class="modal?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button class="close" @click="cerrarModal()">x</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Nombre de la Entidad Bancaria</label>
                        <div class="col-md-9">
                            <input  v-validate.initial= "'required'"   
                                    type="text" 
                                    v-model="nomentidadbancaria" 
                                    class="form-control" 
                                    placeholder="Entidad Bancaria"
                                    name='Entidad Bancaria'
                                    autofocus>
                            <span class="text-error">{{ errors.first('Entidad Bancaria')}}</span>   <!--Lineas Agregadas<-->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Sigla de la Entidad Bancaria</label>
                        <div class="col-md-9">
                            <input  type="text" 
                                    v-model="siglaentidadbancaria" 
                                    class="form-control" 
                                    placeholder="Sigla Entidad Bancaria"
                                    name='Sigla'>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEntidadbancaria()">Guardar</button>
                    <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEntidadbancaria()">Actualizar</button>
                </div>
            </div>           
        </div>
    </div>   
</main>
</template>

<script>
 
export default {
    data (){ return {
            entidadbancaria_id: 0,
            nomentidadbancaria : '',
            siglaentidadbancaria: '',
            arrayEntidadbancaria : [],
            modal : 0,
            tituloModal : '',
            tipoAccion : 0,
    }},

    methods:{
        listarEntidadbancaria(page,buscar,criterio){
            let me=this;
            var url= '/con_entidadbancaria';
            axios.get(url).then(function (response) {
                me.arrayEntidadbancaria = response.data.entidadbancarias;
            });
        },

        registrarEntidadbancaria(){
            let me = this;
            axios.post('/con_entidadbancaria/registrar',{
                'nomentidadbancaria': this.nomentidadbancaria,
                'siglaentidadbancaria': this.siglaentidadbancaria
            }).then(function(response){
                if(response.data.length) 
                    swal('El valor ya Existe','Ingresa un dato Diferente','error');
                else{
                    me.cerrarModal();
                    me.listarEntidadbancaria(1,'','nomentidadbancaria');
                }
            })
        },

        actualizarEntidadbancaria(){
            let me = this;
            axios.put('/con_entidadbancaria/actualizar',{
                'identidadbancaria': this.entidadbancaria_id,
                'nomentidadbancaria': this.nomentidadbancaria,
                'siglaentidadbancaria':this.siglaentidadbancaria
            }).then(function (response) {
                if(response.data.length)
                    swal('El Valor ya Existe','Ingresa un dato Diferente','error')
                else{
                    me.cerrarModal();
                    me.listarEntidadbancaria(1,'','nomentidadbancaria');
                }
            }); 
        },

        desactivarEntidadbancaria(identidadbancaria){
            swal({
                title: 'Esta seguro de desactivar este Entidad Bancaria?',
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
            }).then(result => {
                if (result.value) {
                    let me = this;
                    var url='/con_entidadbancaria/desactivar?identidadbancaria='+identidadbancaria;
                    axios.put(url).then(function () {
                        swal('Desactivado!','El registro ha sido desactivado con éxito.','success');
                        me.listarEntidadbancaria(1,'','nomentidadbancaria');
                    });                
                } 
            });
        },

        activarEntidadbancaria(identidadbancaria){
            swal({
                title: 'Esta seguro de activar este Entidadbancaria?',
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
            }).then(result => {
                if (result.value) {
                    let me = this;
                    var url='/con_entidadbancaria/activar?identidadbancaria='+identidadbancaria;
                    axios.put(url).then(function(){
                        me.listarEntidadbancaria(1,'','nomentidadbancaria');
                        swal('Activado!','El registro ha sido activado con éxito.','success');
                    });
                }
            });
        },

        cerrarModal(){
            this.modal=0;
            this.tituloModal='';
            this.nomentidadbancaria='';
            this.siglaentidadbancaria='';    
        },

        abrirModal(modelo, accion, data = []){
            switch(modelo){
                case "entidadbancaria":{
                    switch(accion){
                        case 'registrar': {
                            this.modal = 1;
                            this.tituloModal = 'Registrar Entidad Bancaria';
                            this.nomentidadbancaria= '';
                            this.siglaentidadbancaria='';
                            this.tipoAccion = 1;
                            break;
                        }
                        case 'actualizar':{
                            this.modal=1;
                            this.tituloModal='Actualizar Entidad Bancaria';
                            this.tipoAccion=2;
                            this.entidadbancaria_id=data['identidadbancaria'];
                            this.nomentidadbancaria = data['nomentidadbancaria'];
                            this.siglaentidadbancaria=data['siglaentidadbancaria'];
                            break;
                        }
                    }
                }
            }
        }
        },
        mounted() {
            this.listarEntidadbancaria(1,this.buscar,this.criterio);
        }
    }
</script>
