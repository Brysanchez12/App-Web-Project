<?php
include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
GenFunc::logSys("(dashboard) I:Ingreso en opcion");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="fila-cuadros">
        <!-- cliente -->
        <div class="cuadro cliente">
          <span class="contador" id="contador-cliente">0</span>
          <i class="fa fa-solid fa-user"></i>
          <label>Clientes registrados</label>
        </div>
        <!-- tecnico -->
        <div class="cuadro tecnico">
          <span class="contador" id="contador-tecnicos">0</span>
          <i class="fa fa-solid fa-toolbox"></i>
          <label>Técnicos registrados</label>
        </div>
        <!-- celulares -->
        <div class="cuadro celular">
          <span class="contador" id="contador-ordenes">0</span>
          <i class="fa fa-solid fa-mobile"></i>
          <label>Ordenes Ingresadas</label>
        </div>
        <!-- items -->
        <div class="cuadro items">
          <span class="contador" id="contador-items">0</span>
          <i class="fa fa-solid fa-truck-medical"></i>
          <label>Items registrados</label>
        </div>
      </div>
      <!--
            <div class="bloque-estadisticas cuadro-estadisticas">
              <h2>EstadÃ­sticas Generales</h2>
              <div class="contenido-estadisticas">

                <div class="Container-circulo">
                  <svg class="progreso-ring" width="120" height="120">
                  <circle class="progreso-ring-bg" cx="60" cy="60" r="54" />
                  <circle class="progreso-ring-fill" cx="60" cy="60" r="54" />
                  </svg>
                  <div class="progreso-text">
                    <div id="reparaciones-numero">0</div>
                    <div>Reparaciones</div>
                  </div>
                </div>

                <div class="bloque-info">
                  <h3>Tasa de Ã©xito</h3>
                  <canvas id="graficoTorta" width="300" height="300"></canvas>
                </div>

              </div>
            </div>
          </div>
      -->
      <!-- Small boxes (Stat box) ->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>150</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body p-0">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Tareas de Programación</th>
                    <th>Progreso</th>
                    <th style="width: 40px">%</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1.</td>
                    <td>Creacion de Orden</td>
                    <td>
                      <div class="progress progress-xs">
                        <div class="progress-bar bg-danger" style="width: 90%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-danger">90%</span></td>
                  </tr>
                  <tr>
                    <td>2.</td>
                    <td>Procesa Orden (Tabla)</td>
                    <td>
                      <div class="progress progress-xs">
                        <div class="progress-bar bg-warning" style="width: 10%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-warning">10%</span></td>
                  </tr>
                  <tr>
                    <td>3.</td>
                    <td>Procesa Orden (Datos)</td>
                    <td>
                      <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-danger" style="width: 0%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-danger">0%</span></td>
                  </tr>
                  <tr>
                    <td>4.</td>
                    <td>Facturacion (Tabla)</td>
                    <td>
                      <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-success" style="width: 10%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-success">10%</span></td>
                  </tr>
                  <tr>
                    <td>5.</td>
                    <td>Facturacion (Datos)</td>
                    <td>
                      <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-success" style="width: 0%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-success">0%</span></td>
                  </tr>
                  <tr>
                    <td>6.</td>
                    <td>Integrantes</td>
                    <td>
                      <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-success">60%</span></td>
                  </tr>
                  <tr>
                    <td>7.</td>
                    <td>Tutor</td>
                    <td>
                      <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-success" style="width: 80%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-success">80%</span></td>
                  </tr>
                  <tr>
                    <td>8.</td>
                    <td>Acerca de...</td>
                    <td>
                      <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-success" style="width: 0%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-success">0%</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body p-0">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Tareas de Documentación</th>
                    <th>Progreso</th>
                    <th style="width: 40px">%</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1.</td>
                    <td>Definir el alcance del proyecto grupal para el primer parcial.</td>
                    <td>
                      <div class="progress progress-xs">
                        <div class="progress-bar progress-bar-danger" style="width: 0%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-danger">0%</span></td>
                  </tr>
                  <tr>
                    <td>2.</td>
                    <td>Entrega de avance 1 del proyecto y avance del artículo</td>
                    <td>
                      <div class="progress progress-xs">
                        <div class="progress-bar bg-warning" style="width: 0%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-warning">0%</span></td>
                  </tr>
                  <tr>
                    <td>3.</td>
                    <td>Entrega de avance 2, documento de diseño detallado</td>
                    <td>
                      <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-primary" style="width: 0%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-primary">0%</span></td>
                  </tr>
                  <tr>
                    <td>4.</td>
                    <td>Entrega de avance 3, bitácora de actividades del grupo</td>
                    <td>
                      <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-success" style="width: 0%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-success">0%</span></td>
                  </tr>
                  <tr>
                    <td>5.</td>
                    <td>Entrega de avance 3, Plan de pruebas</td>
                    <td>
                      <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-success" style="width: 90%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-success">90%</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.card -->
  </section>
</div>

<script>

  function cargaDash() {
    console.log('si');
    actualizarContador('contador-cliente', '/cnt/ClientesCnt.php/count');
    actualizarContador('contador-tecnicos', '/cnt/TecnicosCnt.php/count');
    actualizarContador('contador-ordenes', '/cnt/OrdenCnt.php/count');
    actualizarContador('contador-items', '/cnt/ProductosCnt.php/count');

    // fetch('/cnt/CelularesCnt.php/count')
    //   .then(response => response.json())
    // .then(data => {
    //   const totalCelulares = data.total;
    // document.getElementById('contador-celulares').textContent = totalCelulares;
    // animarContadorCircular('reparaciones-numero', totalCelulares, 1000, 1500);
    //animarCirculoSVG(totalCelulares, 1000); // limite de celulares
    //inicializarGraficoTorta(totalCelulares);
    //})
    //.catch(error => {
    //  console.error('Error al obtener celulares:', error);
    // });
  }

// actualiza el contador cuando ingresa un nuevo elemento
  function actualizarContador(idElemento, url) {
    console.log(url);
    fetch(url)
            .then(response => response.json())
            .then(data => {
              if (data) {
                console.log(data);
                document.getElementById(idElemento).innerHTML = data.data;
              } else {
                console.error(`Error al obtener ${idElemento}:`, data.err);
              }
            })
            .catch(error => {
              console.error(`Error al obtener ${idElemento}:`, error);
            });
  }
// la funcion se encargara de mostrar la comparacion de exitos y errores en el negocio asi demostrando la efectividad
  function inicializarGraficoTorta(reparados) {
    const fallidos = 120;
    const ctx = document.getElementById('graficoTorta').getContext('2d');

    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Exito', 'Fallo'],
        datasets: [{
            data: [reparados, fallidos],
            backgroundColor: ['#4CAF50', '#F44336']
          }]
      },
      options: {
        plugins: {
          legend: {position: 'bottom'},
          title: {
            display: true,
            text: 'Tasa de Ã‰xito en Reparaciones'
          }
        }
      }
    });
  }

// mientras mas celulares se arreglen la barra crecera
  function animarContadorCircular(idElemento, valorFinal, maxValor, duracion) {
    const elemento = document.getElementById(idElemento);
    let inicio = 0; // comenzamos con 0 celulares
    const paso = Math.ceil(valorFinal / (duracion / 16));

    const intervalo = setInterval(() => {
      inicio += paso;
      if (inicio >= valorFinal) {
        inicio = valorFinal;
        clearInterval(intervalo);
      }
      elemento.textContent = inicio.toLocaleString();
    }, 16);
  }

// la funcion se encarga de rellenar el circulo estadistico
  function animarCirculoSVG(valorActual, valorMaximo) {
    const circle = document.querySelector('.progreso-ring-fill');
    const radio = circle.r.baseVal.value;
    const circunferencia = 2 * Math.PI * radio;

    circle.style.strokeDasharray = `${circunferencia} ${circunferencia}`;
    circle.style.strokeDashoffset = circunferencia;

    const progreso = valorActual / valorMaximo;
    const offsetFinal = circunferencia * (1 - progreso);

    let frame = 0; // comienzo
    const duracion = 1000; // limite
    const fps = 60; // visualizacion
    const totalFrames = Math.round(duracion / (1000 / fps));
    const incremento = (circunferencia - offsetFinal) / totalFrames;

    const animacion = setInterval(() => {
      frame++;
      const offsetActual = circunferencia - incremento * frame;
      circle.style.strokeDashoffset = offsetActual;
      if (frame >= totalFrames)
        clearInterval(animacion);
    }, 1000 / fps);
  }
</script>