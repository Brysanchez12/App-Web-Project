<?php
include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
GenFunc::logSys("(fac orden) I:Ingreso en opcion");
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Factura Orden de Trabajo</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid" id="paso1">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Lista de Ordenes por Facturar</h3>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap" id="tbord">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="tabla-ordenes">
                </tbody>
              </table>
            </div>
            <button type="button" onclick="selorden()" id="btnSiguiente" class="btn btn-primary">Siguiente</button>
          </div>
        </div>

      </div>
    </div>
    <input type="hidden" id="selorden" value="">
    <div class="container-fluid col-sm-10" id="paso2" style="display: none;">
      <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="fas fa-clipboard-list me-2"></i> Detalle de la Orden de Trabajo # <span id="orden-id"></span>
        </h5>
      </div>

      <div class="card-body shadow">
        <div class="row g-3">
          <div class="col-md-6">
            <p><strong>ID:</strong> <span id="id"></span></p>
            <p><strong>Cliente:</strong> <span id="cliente"></span></p>
            <p><strong>Fecha:</strong> <span id="fecha"></span></p>
            <p><strong>Estado:</strong> <span id="estado" class="badge bg-secondary"></span></p>
          </div>
          <div class="col-md-6">
            <p><strong>Marca:</strong> <span id="marca"></span></p>
            <p><strong>Modelo:</strong> <span id="modelo"></span></p>
            <p><strong>IMEI:</strong> <span id="imei"></span></p>
          </div>
          <div class="col-12">
            <p><strong>Problema reportado:</strong> <span id="problema"></span></p>
          </div>
        </div>
        <hr>
        <div class="row">
          <table class="table">
            <thead>
              <tr>
                <th>Detalles</th>
              </tr>
            </thead>
            <tbody id="detalle-orden"></tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-md-12 d-flex justify-content-end">
            <div id="resumen-pago" class="shadow-sm p-4 rounded bg-light" style="width: 300px; font-family: 'Segoe UI', sans-serif;">
              <h5 class="text-center mb-3 text-primary">Resumen de Pago</h5>
              <div class="d-flex justify-content-between mb-2">
                <span>Subtotal:</span>
                <span id="subtotal" class="text-muted">$0.00</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Impuesto (15%):</span>
                <span id="impuesto" class="text-muted">$0.00</span>
              </div>
              <hr class="my-2">
              <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Total:</span>
                <span id="total" class="text-success">$0.00</span>
              </div>
            </div>
          </div>
        </div>

        <div class="text-end">
          <button class="btn btn-success" onclick="facturar()">
            <i class="bi bi-pencil-square"></i> Facturar
          </button>
        </div>
      </div>
    </div>
  </section>
</div>
<script>
  // Cargar ordenes
  function cargaOrdfac() {
    fetch(`cnt/OrdenCnt.php/fac`)
         .then(response => response.json())
         .then(resdata => {
           if (resdata.data && Array.isArray(resdata.data)) {
             const tbody = document.getElementById('tabla-ordenes');
             tbody.innerHTML = '';
             // Iterar sobre los datos y crear filas
             const radioName = 'ordenSeleccionada';

             resdata.data.forEach(function (orden) {
               const tr = document.createElement('tr');

               // Celda: ID
               const tdId = document.createElement('td');
               tdId.textContent = orden.id;
               tr.appendChild(tdId);

               // Celda: Código cliente
               const tdCodcli = document.createElement('td');
               tdCodcli.textContent = '[' + orden.codcli + '] ' + orden.clinom;
               tr.appendChild(tdCodcli);

               // Celda: Fecha ingreso
               const tdFecing = document.createElement('td');
               tdFecing.textContent = orden.fecing;
               tr.appendChild(tdFecing);

               // Celda: Radio button
               const tdRadio = document.createElement('td');
               const radio = document.createElement('input');
               Object.assign(radio, {
                 type: 'radio',
                 name: radioName,
                 value: orden.id
               });

               // Evento para cambiar color de la fila
               radio.addEventListener('change', function () {
                 // Reiniciar color de todas las filas
                 const filas = tbody.getElementsByTagName('tr');
                 for (let i = 0; i < filas.length; i++) {
                   filas[i].style.backgroundColor = '';
                 }

                 // Cambiar color de la fila seleccionada
                 tr.style.backgroundColor = '#d1ecf1'; // Celeste claro
               });

               tdRadio.appendChild(radio);
               tr.appendChild(tdRadio);

               tbody.appendChild(tr);
             });
           }
         })
         .catch(error => console.error('Error al cargar ordenes:', error));
  }

  function selorden() {
    const seleccion = document.querySelector('input[name="ordenSeleccionada"]:checked');

    if (!seleccion) {
      Swal.fire({
        icon: 'warning',
        title: 'Atención',
        text: 'Por favor seleccione una orden.',
        confirmButtonText: 'Entendido'
      });
      return;
    }

    const ordenId = seleccion.value;

    // Guardar el id en el input oculto
    document.getElementById('selorden').value = ordenId;
    document.getElementById('orden-id').textContent = ordenId;

    // Consultar detalle de la orden desde el servidor
    fetch('cnt/OrdenCnt.php/id/' + encodeURIComponent(ordenId))
         .then(function (res) {
           if (!res.ok) {
             throw new Error('Error al consultar la orden');
           }
           return res.json();
         })
         .then(function (data) {
           // Mostrar paso2
           const paso1 = document.getElementById('paso1');
           const paso2 = document.getElementById('paso2');

           if (paso1)
             paso1.style.display = 'none';
           if (paso2)
             paso2.style.display = 'block';

           // Llenar campos del paso2 con los datos recibidos
           document.getElementById('id').textContent = data.id || '';
           document.getElementById('cliente').textContent = data.codcli || '';
           document.getElementById('fecha').innerHTML = data.fecing || '';
           document.getElementById('modelo').textContent = data.modelo || '';
           document.getElementById('marca').textContent = data.marca || '';
           document.getElementById('imei').textContent = data.imei || '';
           document.getElementById('problema').textContent = data.problema || '';
           document.getElementById('estado').textContent = data.estado || '';
           console.log('detalles');
           // Luego cargar detalle de orden
           fetch('cnt/Orddetcnt.php/' + encodeURIComponent(ordenId))
                .then(function (res) {
                  if (!res.ok)
                    throw new Error('Error al consultar detalle de orden');
                  return res.json();
                })
                .then(function (detalles) {
                  // Insertar filas en la tabla de detalle
                  const tbody = document.getElementById('detalle-orden');

                  // Limpiar contenido anterior
                  while (tbody.firstChild) {
                    tbody.removeChild(tbody.firstChild);
                  }


                  let subtotal = 0;
                  detalles.forEach(function (item) {
                    const tr = document.createElement('tr');

                    let detalle = '';

                    if (item.tipdet == '1') {
                      detalle = 'Producto: [' + item.iteid + '] ' + item.itenom + ' - ' + item.cantid +
                           ' $' + ( item.cantid * item.prcvta ).toFixed(2);
                      subtotal = subtotal + ( item.cantid * item.prcvta );
                    }
                    if (item.tipdet == '2') {
                      detalle = 'Servicio: [' + item.iteid + '] ' + item.itenom + ' -  $' + item.prcvta.toFixed(2);
                      subtotal = subtotal + item.prcvta;
                    }
                    if (item.tipdet == '0') {
                      detalle = 'Observacion: ' + item.observ;
                    }

                    const tdItem = document.createElement('td');
                    tdItem.textContent = detalle;
                    tr.appendChild(tdItem);

                    tbody.appendChild(tr);
                  });
                  document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
                  const impuesto = subtotal * 0.15; // 15% de impuesto
                  document.getElementById('impuesto').textContent = '$' + impuesto.toFixed(2);
                  const total = subtotal + impuesto;
                  document.getElementById('total').textContent = '$' + total.toFixed(2);

                });
         })
         .catch(function (error) {
           console.error(error);
           Swal.fire({
             icon: 'error',
             title: 'Error',
             text: 'No se pudo obtener la información de la orden.'
           });
         });
  }

  function facturar() {
    // Guardar el id en el input oculto
    const ordenId = document.getElementById('selorden').value;

    // Consultar detalle de la orden desde el servidor
    fetch('cnt/FacturaCnt.php/facoid/' + encodeURIComponent(ordenId))
         .then(function (res) {
           if (!res.ok)
             throw new Error('Error al facturar orden');
           return res.json();
         })
         .then(function (resp) {
           if (resp.data) {
             Swal.fire({
               icon: 'info',
               title: 'Atención',
               text: 'Orden Procesada correctamente. Factura: ' + resp.data.facturaId,
               confirmButtonText: 'Entendido'
             });
             paso1.style.display = 'block';
             paso2.style.display = 'none';
             cargaOrdfac();
             return;
           }
         })
         .catch(function (error) {
           console.error(error);
           Swal.fire({
             icon: 'error',
             title: 'Error',
             text: 'No se pudo facturar la orden.'
           });
         });
  }
</script>