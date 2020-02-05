<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Estados
          <button
            type="button"
            @click="abrirModal('estado','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nomestado">Nombre</option>
                  <option value="idmodulo">Modulo</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarEstadomodulo(1,buscar,criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                >
                <button
                  type="submit"
                  @click="listarEstadomodulo(1,buscar,criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Modulo</th>
                <th>Estado Modulo</th>
                <th>Activo</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="estado in arrayEstadomodulo" :key="estado.idestadomodulo">
                <td>
                  <button
                    type="button"
                    @click="abrirModal('estado','actualizar',estado)"
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button> &nbsp;
                  <template v-if="estado.activo">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="desactivarEstado(estado.idestadomodulo)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="activarEstado(estado.idestadomodulo)"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>
                </td>
                <td v-text="estado.nomestado"></td>
                <td v-text="estado.nommodulo"></td>
                <td v-text="estado.idestado"></td>
                <td>
                  <div v-if="estado.activo">
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
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)"
                >Ant</a>
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(page,buscar,criterio)"
                  v-text="page"
                ></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                >Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <form @submit.prevent="validateBeforeSubmit">
      <div
        class="modal fade"
        tabindex="-1"
        :class="{'mostrar' : modal}"
        role="dialog"
        aria-labelledby="myModalLabel"
        style="display: none;"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-primary modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <form action method="post" enctype="multipart/form-data" class="form-horizontal">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Nombre del Estado</label>
                  <div class="col-md-9">
                    <input
                      v-validate.initial="'required'"
                      type="text"
                      v-model="nomestado"
                      class="form-control"
                      placeholder="Estado"
                      name="Nombre Estado"
                      autofocus
                    >
                    <span class="text-error">{{ errors.first('Nombre Estado')}}</span>
                    <!--Lineas Agregadas<-->
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Modulo</label>
                  <div class="col-md-9">
                    <select
                      class="form-control"
                      v-model="idmodulo"
                      v-validate.initial="'required'"
                      name="Modulo"
                    >
                      <option value="0">Seleccione</option>
                      <option
                        v-for="modulo in arrayModulo"
                        :key="modulo.idmodulo"
                        :value="modulo.idmodulo"
                        v-text="modulo.nommodulo"
                      ></option>
                    </select>
                    <span class="text-error">{{ errors.first('Modulo')}}</span>
                    <!--Lineas Agregadas<-->
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                  <div class="col-md-9">
                    <input
                      v-validate.initial="'required'"
                      type="text"
                      v-model="idestado"
                      class="form-control"
                      ref="search"
                      id="desti"
                      placeholder="Estado Socio Modulo"
                      name="Estado"
                    >
                    <span class="text-error">{{ errors.first('Estado')}}</span>
                    <!--Lineas Agregadas<-->
                  </div>
                </div>
                <!-- no se utiliza esta parte porque ya no valida con la funcion antigua
                                <div v-show="errorDepartamento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDepartamento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                -->
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
              <!-- modificar boton aceptar -->
              <button
                :disabled="errors.any ()"
                type="submit"
                v-if="tipoAccion==1"
                class="btn btn-primary"
                @click="registrarEstado()"
              >Guardar</button>
              <button
                :disabled="errors.any ()"
                type="submit"
                v-if="tipoAccion==2"
                class="btn btn-primary"
                @click="actualizarEstado()"
              >Actualizar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </form>
    <!--Fin del modal-->
  </main>
</template>

<script>
import Vue from "vue";
import VeeValidate from "vee-validate";

const VueValidationEs = require("vee-validate/dist/locale/es");
Vue.use(VeeValidate, {
  locale: "es",
  dictionary: {
    es: VueValidationEs
  }
});

Vue.use(VeeValidate);

export default {
  data() {
    return {
      estado_id: 0,
      nomestado: "",
      nommodulo: "",
      idmodulo: "",
      idestado: "",
      arrayEstadomodulo: [],
      arrayModulo: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorEstado: 0,
      errorMostrarMsjEstado: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nomestado",
      buscar: ""
    };
  },

  formOptions: {
    validateAfterLoad: true,
    validateAfterChanged: true
  },

  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  methods: {
    //metodo agregado para la validacion
    validateBeforeSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          //alert('enviado');
          //return this.result;
          return;
        }

        //alert('no enviado');
        return;
        ////alert(result);
      });
    },

    selectModulo() {
      let me = this;
      var url = "/par_modulo/selectModulo";
      axios
        .get(url)
        .then(function(response) {
          console.log(response);
          var respuesta = response.data;
          me.arrayModulo = respuesta.modulos;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    listarEstadomodulo(page, buscar, criterio) {
      let me = this;
      var url =
        "/par_estadomodulo?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayEstadomodulo = respuesta.estados.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(response) {
          console.log(response);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarEstadomodulo(page, buscar, criterio);
    },
    registrarEstado() {
      /*if (this.validarDepartamento()){
                    return;
                }*/

      let me = this;

      axios
        .post("/par_estadomodulo/registrar", {
          nomestado: this.nomestado,
          idmodulo: this.idmodulo,
          idestado: this.idestado
        })
        .then(function(response) {
          if (response.data.length) {
            //console.log(response)
            swal("El Valor ya Existe", "Ingresa un dato Diferente", "error");
          } else {
            me.cerrarModal();
            me.listarEstadomodulo(1, "", "nomestado");
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarEstado() {
      /*if (this.validarDepartamento()){
                    return;
                }*/

      let me = this;

      axios
        .put("/par_estadomodulo/actualizar", {
          nomestado: this.nomestado,
          idmodulo: this.idmodulo,
          idestado: this.idestado,
          idestadomodulo: this.estado_id
        })
        .then(function(response) {
          if (response.data.length) {
            swal("El Valor ya Existe", "Ingresa un dato Diferente", "error");
          } else {
            me.cerrarModal();
            me.listarEstadomodulo(1, "", "nomestado");
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    desactivarEstado(idestadomodulo) {
      swal({
        title: "Esta seguro de desactivar este Estado?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;

          axios
            .put("/par_estadomodulo/desactivar", {
              idestadomodulo: idestadomodulo
            })
            .then(function(response) {
              me.listarEstadomodulo(1, "", "nomestado");
              swal(
                "Desactivado!",
                "El registro ha sido desactivado con éxito.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },
    activarEstado(idestadomodulo) {
      swal({
        title: "Esta seguro de activar este Estado?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;

          axios
            .put("/par_estadomodulo/activar", {
              idestadomodulo: idestadomodulo
            })
            .then(function(response) {
              me.listarEstadomodulo(1, "", "nomestado");
              swal(
                "Activado!",
                "El registro ha sido activado con éxito.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },

    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nomestado = "";
    },
    abrirModal(modelo, accion, data = []) { 
      switch (modelo) {
        case "estado": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Estado";
              this.nomestado = "";
              this.idmodulo = "";
              this.idestado = "";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              //console.log(data);
              this.modal = 1;
              this.tituloModal = "Actualizar Estado";
              this.tipoAccion = 2;
              this.estado_id = data["idestadomodulo"];
              this.nomestado = data["nomestado"];
              this.idmodulo = data["idmodulo"];
              this.idestado = data["idestado"];

              break;
            }
          }
        }
      }
      this.selectModulo();
    }
  },
  mounted() {
    this.listarEstadomodulo(1, this.buscar, this.criterio);
  }
};
</script>

