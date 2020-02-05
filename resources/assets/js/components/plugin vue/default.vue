<template>
            <main class="main"> 
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
                    </ol>   
            <div class="container-fluid">
                
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> defaut   
                    </div>
                    <div class="card-body">
                        <div class="form-group row" style="justify-content: flex-end;">
                            
                            <div class="col-md-6"> 
                                <div class="input-group" style="align-items: center;">
                                    <p  style="text-align: right;margin: 0px; margin-right: 10px; font-weight: 500;">Criterio de busqueda:</p> 
                                    <input type="text" v-model="buscar" @keyup.enter="listar(1,buscar)" class="form-control" placeholder="Ingresar  Nombres , Apellidos , Ci , Numero de Papeleta">
                                    <button type="submit" @click="listar(1,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                                             
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
              
            </div>
          
          

                
                <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="true" id="primarymodal" >
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content animated fadeIn"> 
                                <div class="modal-header"> 
                                    <h4 class="modal-title" id="modalOneLabel">titulo modal </h4>
                                    <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="classModal.closeModal('primarymodal')"><span aria-hidden="true">×</span></button>
                                </div> 

                                <div class="modal-body-modalPlugin">
                    
                                </div>  

                                <div class="modal-footer">  
                                <button type="button" class="btn btn-secondary" @click="classModal.closeModal('primarymodal')">Cerrar</button>              
                                </div>    
                        </div>
                    </div>
                </div>
  
        </main>
</template>

<script>

  

    export default {
        data (){
            return {
               classModal:null,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3, 
                
            }
        }, 
        computed:{  
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
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
             listar(page,buscar){
                let me=this; 
                var url= '/ruta?page=' + page + '&buscar='+ buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.pagination= respuesta.pagination;
                })
                .catch(function (response) {
                    console.log(response);
                });
            },
              cambiarPagina(page,buscar){
                let me = this; 
                me.pagination.current_page = page; 
               // me.listar(page,buscar);
            } 
        },
        mounted() {
            this.classModal=new _pl.Modals();
            this.classModal.addModal('primarymodal'); 
           
        }
    }
</script>
<style> 
 
      
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
    .fotosociomini{
	     display: inline-block;
        border:#efefef 1px solid;
        filter:drop-shadow(1px 0px 2px #333);
        width:40px;
    }
   
 

::-webkit-scrollbar {
  width: 6px;
  border-radius: 10px;
}
::-webkit-scrollbar-track {
  background: transparent;  
}
::-webkit-scrollbar-thumb {
  background: #2186aab0; 
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #276f8a; 
}
</style>
