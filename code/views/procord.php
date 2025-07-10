<?php

include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
GenFunc::logSys("(proc orden) I:Ingreso en opcion");
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Procesa Orden de Trabajo</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Órdenes Activas</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap" id="tbord">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Modelo</th>
                                        <th>Marca</th>
                                        <th>IMEI</th>
                                        <th>Problema</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-ordenes">
                                    <!-- Se cargarán las órdenes aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="detalle-orden" style="display:none;" class="card mt-4 shadow-lg border-0">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i> Detalle de la Orden de Trabajo
                    </h5>
                </div>

                <div class="card-body">
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
                    <div class="text-end">
                        <button class="btn btn-success" onclick="abrirFormulario()">
                            <i class="bi bi-pencil-square"></i> Registrar acciones
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>


<!-- Nuevo modal divido por pestañas -->
<div id="modal-acciones" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div>
        <h3 id="modalTitle">Registrar Acciones</h3>

        <div class="tabs">
            <button class="tab active" onclick="mostrarTab('tab-producto')">Producto</button>
            <button class="tab" onclick="mostrarTab('tab-servicio')">Servicio</button>
            <button class="tab" onclick="mostrarTab('tab-proceso')">Proceso</button>
        </div>

        <div class="tab-content active" id="tab-producto">
            <label for="producto">Producto utilizado:</label>
            <select id="producto">
                <option value="">-- Seleccione producto --</option>
            </select>

            <label for="cantidad">Cantidad a usar:</label>
            <input type="number" id="cantidad" min="1" placeholder="Ingrese cantidad" />
        </div>

        <div class="tab-content" id="tab-servicio">
            <label for="servicio">Servicio realizado:</label>
            <select id="servicio">
                <option value="">-- Seleccione servicio --</option>
            </select>
        </div>

        <div class="tab-content" id="tab-proceso">
            <label for="proceso">Proceso realizado:</label>
            <textarea id="proceso" placeholder="Describe el proceso..."></textarea>
        </div>

        <div style="text-align:right; margin-top:15px;">
            <button onclick="guardarAcciones()" class="btn btn-success">Guardar</button>
            <button onclick="cerrarModal()" class="btn btn-danger">Cancelar</button>
        </div>
    </div>
</div>

<script>
    // Cargar órdenes activas
    function cargaProcord() {
        fetch(`cnt/OrdenCnt.php/act/0000000001`)
            .then(response => response.json())
            .then(resdata => {
                if (resdata.data && Array.isArray(resdata.data)) {
                    const tbody = document.getElementById('tabla-ordenes');
                    tbody.innerHTML = '';
                    resdata.data.forEach(orden => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
              <td>${orden.id}</td>
              <td>${orden.codcli}</td>
              <td>${orden.fecing}</td>
              <td>${orden.modelo}</td>
              <td>${orden.marca}</td>
              <td>${orden.imei}</td>
              <td>${orden.observ}</td>
              <td>
                <button class="btn btn-success btn-sm" onclick="procesarOrden(this, '1')">Ver</button>
                <button class="btn btn-danger btn-sm" onclick="procesarOrden(this, '2')">Cerrar</button>
              </td>`;
                        tbody.appendChild(tr);
                    });
                }
            })
            .catch(error => console.error('Error al cargar órdenes:', error));
    }

    // Mostrar detalle orden al aceptar
    function procesarOrden(button, estado) {
        const row = button.closest('tr');
        const celdas = row.querySelectorAll('td');
        if (estado === '1') {
            document.getElementById('id').textContent = celdas[0].textContent;
            document.getElementById('cliente').textContent = celdas[1].textContent;
            document.getElementById('fecha').textContent = celdas[2].textContent;
            document.getElementById('modelo').textContent = celdas[3].textContent;
            document.getElementById('marca').textContent = celdas[4].textContent;
            document.getElementById('imei').textContent = celdas[5].textContent;
            document.getElementById('problema').textContent = celdas[6].textContent;
            document.getElementById('estado').textContent = "Aceptado";
            document.getElementById('detalle-orden').style.display = 'block';
        } else if (estado === '2') {
            const codigo = celdas[0].textContent;

            Swal.fire({
                title: '¿Cerrar orden?',
                text: '¿Está seguro de cerrar esta orden?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('cnt/OrdenCnt.php/cerrar/' + encodeURIComponent(codigo))
                        .then(response => response.json())
                        .then(data => {
                            if (data.data) {
                                Swal.fire({
                                    title: 'Orden cerrada',
                                    text: 'La orden fue cerrada correctamente.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                cargaProcord(); // Recargar o actualizar datos
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se pudo cerrar la orden.',
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al cerrar la orden:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error inesperado.',
                                icon: 'error'
                            });
                        });
                }
            });
        }
    }
    // Abrir modal y cargar opciones
    function abrirFormulario() {
        cargarOpciones();
        document.getElementById('modal-acciones').style.display = 'flex';
    }


    // funcion  
    function mostrarTab(tabId) {
        document.querySelectorAll('#modal-acciones .tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelectorAll('#modal-acciones .tab-content').forEach(content => {
            content.classList.remove('active');
        });

        const activeTab = document.querySelector(`#modal-acciones .tab[onclick*="${tabId}"]`);
        if (activeTab) activeTab.classList.add('active');

        const activeContent = document.getElementById(tabId);
        if (activeContent) activeContent.classList.add('active');
    }


    // Cerrar modal
    function cerrarModal() {
        document.getElementById('modal-acciones').style.display = 'none';
        // Limpiar formulario
        document.getElementById('producto').value = '';
        document.getElementById('cantidad').value = '';
        document.getElementById('servicio').value = '';
        document.getElementById('proceso').value = '';
    }

    // Guardar acciones
    function guardarAcciones() {
        const productoSelect = document.getElementById('producto');
        const productoId = productoSelect.value;
        const stock = parseInt(productoSelect.options[productoSelect.selectedIndex]?.dataset.stock || "0");
        const cantidad = parseInt(document.getElementById('cantidad').value, 10);
        const servicio = document.getElementById('servicio').value;
        const proceso = document.getElementById('proceso').value.trim();
        const ordId = document.getElementById('id').textContent;

        if (!ordId) {
            alert('No hay orden seleccionada.');
            return;
        }
        if (!productoId && !servicio && proceso === '') {
            alert('Debe seleccionar un producto, un servicio o ingresar un proceso.');
            return;
        }
        if (productoId && (isNaN(cantidad) || cantidad <= 0)) {
            alert('Ingrese una cantidad válida para el producto.');
            return;
        }
        if (productoId && cantidad > stock) {
            alert(`Stock insuficiente. Disponible: ${stock}`);
            return;
        }

        // Preparar objeto para enviar
        const datos = {
            ordid: ordId,
            tipdet: productoId ? 1 : (servicio ? 2 : 0), // 1=Repuesto, 2=Servicio, 0=Detalle simple
            iteid: productoId || servicio || '',
            cantid: productoId ? cantidad : (servicio ? 1 : 0),
            observ: proceso,
        };

        fetch(`cnt/OrddetCnt.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        })
            .then(res => {
                if (!res.ok) throw new Error('Error al guardar detalle');
                return res.json();
            })
            .then(response => {
                if (response.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error: ' + response.error,
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Acción guardada con éxito',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        cerrarModal();
                        // Opcional: refrescar lista o detalles
                    });
                }
            })
            .catch(err => alert('Error al guardar: ' + err.message));
    }

    // Cargar productos y servicios para selects
    function cargarOpciones() {
        // Cargar productos
        fetch(`cnt/ProductosCnt.php`)
            .then(res => res.json())
            .then(data => {
                const prodSelect = document.getElementById('producto');
                prodSelect.innerHTML = '<option value="">-- Seleccione producto --</option>';
                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(p => {
                        const option = document.createElement('option');
                        option.value = p.id;
                        option.textContent = `${p.nombre} (Stock: ${p.cantid})`;
                        option.dataset.stock = p.cantid;
                        prodSelect.appendChild(option);
                    });
                }
            })
            .catch(() => {
                alert('Error cargando productos');
            });

        // Cargar servicios
        fetch(`cnt/ServiciosCnt.php`)
            .then(res => res.json())
            .then(data => {
                const servSelect = document.getElementById('servicio');
                servSelect.innerHTML = '<option value="">-- Seleccione servicio --</option>';
                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(s => {
                        const option = document.createElement('option');
                        option.value = s.id;
                        option.textContent = s.nombre;
                        servSelect.appendChild(option);
                    });
                }
            })
            .catch(() => {
                alert('Error cargando servicios');
            });
    }

    // Cargar órdenes al inicio
    window.addEventListener('load', cargaProcord);
</script>