    <template>
            <main class="main"> 
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Registro Factores</a></li>
            </ol>
            <div class="container-fluid">
                
                <div class="card animated fadeIn">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Factores
                        <template v-if="Bregistrar">
                        <button type="button" @click="abrirModal('registrar','factoresmodal')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo Factor
                        </button>
                        </template>
                    </div> 
                   
                     
                    <div class="card-body">

                         <table class="table table-bordered table-striped table-sm" id="tablaplugin">
                            <thead>
                                <tr>
                                    <th>Opciones</th> 
                                    <th>Nombre</th> 
                                    <th>Descripcion</th>                    
                                    <th>Valor de referencia</th>
                                    <th>Valor aceptable</th>
                                    <th>Estado</th>                
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="factor in arrayfactores" :key="factor.idfactor">                                   
                                    <td>
                                     <template v-if="Bedit">
                                            <template v-if="factor.activo">                                         
                                                    
                                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivar(factor.idfactor)">
                                                        <i class="icon-trash"></i>
                                                        </button>&nbsp;
                                                    
                                            </template>
                                            <template v-else>
                                                
                                                <button type="button" class="btn btn-success  btn-sm" @click="activar(factor.idfactor)">
                                                    <i class="icon-check"></i>
                                                </button> 
                                            
                                            </template>
                                    </template> 
                                        </td> 
                                    <td v-text="factor.nombrefactor"></td> 
                                    <td v-text="factor.descripcion"></td> 
                                    <td v-text="factor.refvalor"></td> 
                                    <td v-text="factor.aprobacion"></td>  
                                    <td>
                                        <div v-if="factor.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                       <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                
            </div>

 
       
                <div class="modal fade " tabindex="-1"  role="dialog" style="z-index: 1600;" aria-hidden="true" id="factoresmodal" >
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                            <div class="modal-content animated fadeIn"> 
                            <div class="modal-header"> 
                                <h4 class="modal-title" v-text="titulomodal"></h4>
                                <button type="button" class="close"  aria-hidden="true" aria-label="Close" @click="cerrarModal('factoresmodal')"><span aria-hidden="true">×</span></button>
                            </div>  

                            <div class="modal-body">  
                                 <div class="form-group row">  
                                  <label style="text-align: right;" class="col-md-3 form-control-label" for="text-input">Nombre del Factor :</label>
                                    <div class="col-md-9"> 
                                        <div class="input-group"> 
                                            <input v-validate.initial="'required'"
                                                :class="{'input': true, 'is-invalid': errors.has('nombre') }" 
                                                type="text" 
                                                v-model="nombre" 
                                                class="form-control"
                                                placeholder="Nombres" 
                                                name="nombre"  > 
                                        </div>
                                    <p class="text-error">{{ errors.first('nombre')}}</p>
                                    </div>
                                </div>
                                <div class="form-group row "> 
                                        <label style="text-align: right;" class="col-md-3 form-control-label" for="text-input">Descripción : </label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <textarea v-validate.initial="'required'"
                                                :class="{'is-invalid': errors.has('descripción del factor')}"
                                                class="col-md-12 form-control" rows="4" name="descripción del factor" v-model="descrip" placeholder="Descripción del Factor" ></textarea>
                                            </div> 
                                             <p class="text-error">{{ errors.first('descripción del factor')}}</p>
                                        </div>
                                </div>

                                <div class="form-group row">  
                                     <label style="text-align: right;display: grid;align-content: center;" class="col-md-3 form-control-label" for="text-input">Factor de Aprobación:</label>
                                    <div class="col-md-9">    
                                    <div class="input-group" style="display: block;border: 1px solid #80808070; padding: 8px;">
                                    <input type="text" class="js-range-slider" id="valor_porcentual"
                                        data-skin="round"
                                        data-min="0"
                                        data-max="100"
                                        data-from="80"
                                        data-postfix="%"
                                        data-grid="true">
                                    </div>
                                    </div>   
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal('factoresmodal')">Cerrar</button>
                                <button :disabled = "errors.any()" type="button" class="btn btn-primary" @click="registrarFactor()">Guardar</button>
                                
                            </div>  
                        </div>
                    </div>
                </div>

  







  
</main>

</template>

<script> 
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
                arrayPermisos:{Nuevo:0,Edición:0},
                Bregistrar:1,
                Bedit:1,

                titulomodal:'', 
                descrip:'',
                nombre:'',
                modal:null,
                slider:null,
                 
                arrayfactores:[],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3 
                 
               
            }
        },
        computed:{
                isActived: function(){
                    return this.pagination.current_page;
                },
              
                pagesNumber: function() {
                    if(!this.pagination.to) {
                        return [];
                    }
                    var from = this.pagination.current_page - this.offset; 
                    if(from < 1) {
                        from = 1;
                    }
                    var to = from + (this.offset * 2); 
                    if(to >= this.pagination.last_page){
                        to = this.pagination.last_page;
                    }  
                    var pagesArray = [];
                    while(from <= to) {
                        pagesArray.push(from);
                        from++;
                    }
                    return pagesArray;             

                }
           
        },


        methods : { 
		      activar(id){
                  console.log(this.slider.getValue('valor_porcentual'));
                swal({
                title: '¿Esta seguro de activar este factor?',
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

                    axios.put('/par_procesos_fatores/activar',{
                        'id': id
                    }).then(function (response) {
                        me.factoreslistar(1);
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                }
                }); 
              },
            desactivar(id){
                console.log(this.slider.getValue('valor_porcentual')); 
                 swal({
                title: '¿Esta seguro de desactivar este factor?',
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

                    axios.put('/par_procesos_fatores/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.factoreslistar(1);
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                }
                });

            },
            factoreslistar (page){ 
                let me=this;
                var url= '/par_procesos_fatores/factores?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;  
                   me.pagination= respuesta.pagination;
                   me.arrayfactores=respuesta.factores.data;
                }).catch(function (error) { 
                    console.log(error);
                });
            },
            registrarFactor(){
                let me=this;
               axios.post('/par_procesos_fatores/registrar',{
                    'nombre': me.nombre,
                    'des': me.descrip,
                    'apro': me.slider.getValue('valor_porcentual')
                }).then(function (response) {
                    me.factoreslistar ();
                    me.cerrarModal('factoresmodal');
                    swal("¡Se registraron los datos exitosamente!",'', "success");
                }).catch(function (error) {
                    console.log(error);
                    swal("¡ocurrio un problema al registrar el dato!",error, "error");
                }); 
            },   
             cambiarPagina(page){
                let me = this; 
                me.pagination.current_page = page; 
                me.factoreslistar(page);
            },
          
   
            cerrarModal(id){
            this.modal.closeModal(id);    
            this.nombre='';
            this.descrip='';  
            },
             
            abrirModal(accion,id, data = []){
            
                 this.modal.openModal(id);  
                        switch(accion){
                            case 'registrar':
                            {    this.nombre='';
                                 this.descrip=''; 
                                break;
                            }
                            case 'actualizar':
                            {
                                
                                break;
                            }
                        } 
            },
            abrirModalInfo(accion,id, data = []){ 
                 this.modal.openModal(id);
                 
                        switch(accion){
                            case 'registrar':
                            {    
                                break;
                            }
                            case 'actualizar':
                            {
                                
                                break;
                            }
                        } 
            }
        },
        mounted() {
            this.factoreslistar ();  
            this.modal = new _pl.Modals();
            this.slider = new _pl.Slider();
            this.slider.addSlider('valor_porcentual');
            this.modal.addModal('factoresmodal');  
        }
    }
</script>
<style>    
    
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: #c93f23 !important; 
        font-style: italic; 
    }
      
</style>