<?php
include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
GenFunc::logSys("(tecnicos) I:Ingreso en opcion");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Técnicos</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Técnicos</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-table">&nbsp;</i>&nbsp;Listado de Técnicos</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div id="jsgTecnicos"></div>
      </div>
    </div>
  </section>
</div>
<script>
  function cargaTecnicos() {
    console.log('cargatecnicos');
    var db = {
      // Carga datos (READ)
      loadData: function (filter) {
        return $.ajax({
          type: "GET",
          url: "/cnt/TecnicosCnt.php",
          data: filter,
          dataType: "json"
        }).then(function (response) {
          if (response.err && response.err !== 0) {
            console.error("Error del servidor:", response.err);
            return []; // Devuelve un array vacío a jsGrid en caso de error
          }
          return response.data; // Devuelve los datos reales a jsGrid
        }).catch(function (jqXHR, status, err) {
          console.error('loadData – error:', status, err);
          return []; // jsGrid requiere que se devuelva un array
        });
      },

      // Inserta nuevo registro (CREATE)
      insertItem: function (item) {
        return $.ajax({
          type: "POST",
          url: "/cnt/TecnicosCnt.php",
          contentType: "application/json",
          data: JSON.stringify(item)
        }).then(function (response) {
          if (response.err && response.err !== 0) {
            alert("Error al insertar: " + response.err.msg);
            return $.Deferred().reject(); // Detiene la inserción
          }
          return response.data;
        }).catch(function (jqXHR, status, err) {
          console.error("insertItem – error:", status, err);
          return $.Deferred().reject();
        });
      },

      // Actualiza un registro existente (UPDATE)
      updateItem: function (item) {
        return $.ajax({
          type: "PUT",
          url: "/cnt/TecnicosCnt.php",
          contentType: "application/json",
          data: JSON.stringify(item)
        }).then(function (response) {
          if (response.err && response.err !== 0) {
            alert("Error al actualizar: " + response.err.msg);
            return $.Deferred().reject();
          }
          return response.data;
        }).catch(function (jqXHR, status, err) {
          console.error("updateItem – error:", status, err);
          return $.Deferred().reject();
        });
      }
    };

    $("#jsgTecnicos").jsGrid({
      width: "100%",
      height: "auto",

      filtering: false,
      editing: true,
      sorting: true,
      paging: true,
      autoload: true,
      confirmDeleting: false,

      pageSize: 15,
      pageButtonCount: 5,

      controller: db,

      onItemDeleting: function (args) {
        args.cancel = true; // Cancela la eliminación automática

        Swal.fire({
          title: '¿Está seguro?',
          text: `Desea eliminar al tecnico "${args.item.nombre}"`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            // Eliminación manual usando tu controller
            $.ajax({
              type: "DELETE",
              url: "/cnt/TecnicosCnt.php/" + args.item.id,
              dataType: "json"
            }).done(function (response) {
              if (response.err && response.err !== 0) {
                Swal.fire('Error', response.err.msg, 'error');
                return;
              }
              $("#jsgTecnicos").jsGrid("loadData");
              Swal.fire('Eliminado', 'El técnico ha sido eliminado.', 'success');
            }).fail(function () {
              Swal.fire('Error', 'No se pudo eliminar el tecnico.', 'error');
            });
          }
        });
      }
      ,

      primaryKey: "id",

      rowClick: function (args) {
        showDetailsDialog("Editar", args.item, "tecnico", db);
      },

      fields: [
        {name: "id", type: "text", title: "Cédula", width: 100},
        {name: "nombre", type: "text", title: "Nombre", width: 150},
        {name: "correo", type: "text", title: "Correo", width: 150},
        {name: "direcc", type: "text", title: "Dirección", width: 200},
        {name: "telefono", type: "text", title: "Teléfono", width: 60},
        {type: "control",
          modeSwitchButton: false,
          editButton: false,
          headerTemplate: function () {
            return $("<button>")
                    .addClass("btn btn-primary btn-sm")
                    .css({
                      "background-color": "#007bff",
                      "color": "#fff",
                      "border": "none",
                      "border-radius": "50%",
                      "width": "32px",
                      "height": "32px",
                      "font-size": "20px",
                      "line-height": "28px",
                      "text-align": "center",
                      "padding": "0",
                      "cursor": "pointer"
                    })
                    .html("+")
                    .attr("title", "Agregar nuevo tecnico")
                    .on("click", function () {
                      showDetailsDialog("Nuevo", {}, "tecnico", db);
                    });
          }
        }
      ]
    });
  }
</script>
