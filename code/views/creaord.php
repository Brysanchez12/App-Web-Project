<?php
include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
GenFunc::logSys("(crea orden) I:Ingreso en opcion");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Creación de Orden de Trabajo</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Crea OT</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-10">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-tools mr-2"></i>Nueva Orden de Trabajo</h3>
            </div>
            <form id="ordForm">
              <div class="card-body">
                <div class="row">
                  <!-- Cliente -->
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="cliente">Cliente</label>
                      <select class="form-control select2"
                              id="cliente"
                              name="clicod" required style="width: 100%;">
                        <!-- Opciones se cargarán dinámicamente -->
                      </select>
                    </div>
                  </div>
                  <!-- Fecha -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="fecha">Fecha</label>
                      <div class="input-group date" id="fechaPicker"
                           data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input"
                               data-target="#fechaPicker"
                               name="fecha"
                               id="fecha" required />
                        <div class="input-group-append" data-target="#fechaPicker"
                             data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Técnico -->
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="tecnico">Técnico</label>
                      <select class="form-control select2"
                              id="tecnico"
                              name="teccod" required style="width: 100%;">
                        <!-- Opciones se cargarán dinámicamente -->
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Modelo -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="modelo">Modelo</label>
                      <input type="text" class="form-control"
                             id="modelo" name="modelo" placeholder="Ingrese el modelo">
                    </div>
                  </div>
                  <!-- Marca -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="marca">Marca</label>
                      <input type="text" class="form-control"
                             id="marca" name="marca" placeholder="Ingrese la marca">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Modelo -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="imei">IMEI</label>
                      <input type="text" class="form-control solo-numeros" data-dec="0"
                             id="imei" name="imei" placeholder="Ingrese el modelo">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="observacion">Observación</label>
                  <textarea class="form-control"
                            id="observacion"
                            name="observ" rows="3"
                            placeholder="Ingrese observaciones relevantes al problema"></textarea>
                </div>
              </div>

              <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save mr-1"></i>Guardar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script>
  function cargaOrden() {
    const form = document.getElementById('ordForm');
    const campos = ['cliente', 'tecnico', 'marca', 'modelo', 'imei', 'observacion'];

    // Remueve error al escribir
    campos.forEach(id => {
      const input = document.getElementById(id);
      input.addEventListener('input', () => {
        const valor = input.value ?? '';
        if (valor.trim()) {
          input.classList.remove('is-invalid');
          $(input).tooltip('dispose');
        }
      });
    });

    form.addEventListener('submit', async function (e) {
      e.preventDefault();

      let valid = true;

      // Validación de campos vacíos
      campos.forEach(id => {
        const input = document.getElementById(id);

        if (!input)
          return; // si no existe, saltar

        const valor = input.value ?? '';

        input.classList.remove('is-invalid');
        $(input).tooltip('dispose');

        if (valor.trim() === '') {
          input.classList.add('is-invalid');
          input.setAttribute('title', 'Este campo es obligatorio');
          $(input).tooltip({trigger: 'manual', placement: 'top'}).tooltip('show');
          valid = false;
        }
      });

      if (!valid) {
        Swal.fire({
          icon: 'warning',
          title: 'Campos obligatorios',
          text: 'Por favor completa todos los campos requeridos.',
          timer: 3000
        });
        return;
      }

      // Recolectar datos
      const formData = new FormData(form);
      const data = {};
      formData.forEach((value, key) => data[key] = value);
      // Convertir fecha al formato MySQL: YYYY-MM-DD HH:mm:ss
      if (data.fecha) {
        const fecha = moment(data.fecha, 'DD/MM/YYYY'); // Ajusta el formato según tu datetimepicker
        const hora = moment().format('HH:mm:ss'); // O usa un input si tienes campo hora
        data.fecha = fecha.format('YYYY-MM-DD') + ' ' + hora;
      }
      try {
        const response = await fetch('/cnt/OrdenCnt.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.data) {
          Swal.fire({
            icon: 'success',
            title: 'Orden guardada',
            text: 'Se ha registrado correctamente.',
            timer: 2500
          });
          form.reset();
          $('.select2').val(null).trigger('change');
        } else if (result.err) {
          Swal.fire({
            icon: 'error',
            title: 'Error al guardar',
            text: result.err.msg || 'No se pudo registrar la orden.'
          });
          console.error('Error interno:', result.err);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Respuesta inválida',
            text: 'El servidor no devolvió un formato esperado.'
          });
          console.error('Respuesta inesperada:', result);
        }

      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error de red',
          text: 'No se pudo conectar al servidor.'
        });
        console.error(error);
      }
    });

    $('.select2').select2();
    // Obtener fechas de hoy y hace 3 días
    const today = moment().endOf('day');
    const threeDaysAgo = moment().subtract(3, 'days').startOf('day');

    $('#fechaPicker').datetimepicker({
      format: 'L', // o 'YYYY-MM-DD' si prefieres el formato ISO
      defaultDate: today,
      maxDate: today,
      minDate: threeDaysAgo,
      useCurrent: false // evita que se seleccione automáticamente una fecha inválida
    });

    $('#cliente').select2({
      minimumInputLength: 1,
      language: "es",
      allowClear: true,
      placeholder: 'Seleccione un cliente',
      ajax: {
        url: '/cnt/ClientesCnt.php/lista',
        dataType: 'json',
        delay: 250,
        processResults: function (resdata) {
          if (resdata && resdata.data && Array.isArray(resdata.data)) {
            return {
              results: resdata.data.map(function (cliente) {
                return {
                  id: cliente.id,
                  text: cliente.nombre
                };
              })
            };
          } else {
            // Manejo en caso de error inesperado en estructura
            console.error("Respuesta no válida en select2:", resdata.err);
            return {results: []};
          }
        },
        cache: true
      },
      width: '100%'
    });

    $('#tecnico').select2({
      minimumInputLength: 1,
      language: "es",
      allowClear: true,
      placeholder: 'Seleccione un tecnivo',
      ajax: {
        url: '/cnt/TecnicosCnt.php/lista',
        dataType: 'json',
        delay: 250,
        processResults: function (resdata) {
          if (resdata && resdata.data && Array.isArray(resdata.data)) {
            return {
              results: resdata.data.map(function (tec) {
                return {
                  id: tec.id,
                  text: tec.nombre
                };
              })
            };
          } else {
            // Manejo en caso de error inesperado en estructura
            console.error("Respuesta no válida en select2:", resdata.err);
            return {results: []};
          }
        },
        cache: true
      },
      width: '100%'
    });


    // Validar texto con letras, números, tildes, ñ
    $('#ordForm input[type="text"]').on('keydown', function (event) {
      const key = event.key;
      const isAllowed =
              /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]$/.test(key) ||
              ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(key);

      if (!isAllowed) {
        event.preventDefault();
      }
    }).on('paste', function (event) {
      const paste = (event.originalEvent || event).clipboardData.getData('text');
      if (!/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/.test(paste)) {
        event.preventDefault();
      }
    });

    // Solo números
    $('#ordForm input.solo-numeros').on('keydown', function (event) {
      const allowedKeys = [8, 9, 13, 27, 46, 37, 38, 39, 40];
      if (allowedKeys.includes(event.which) || (event.ctrlKey && [65, 67, 86, 88].includes(event.which))) {
        return;
      }
      if (event.which < 48 || event.which > 57) {
        event.preventDefault();
      }
    }).on('paste', function (event) {
      const paste = (event.originalEvent || event).clipboardData.getData('text');
      if (!/^[0-9]+$/.test(paste)) {
        event.preventDefault();
      }
    });
  }
</script>