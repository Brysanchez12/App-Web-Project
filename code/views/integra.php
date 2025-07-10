<?php
include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
GenFunc::logSys("(integra) I:Ingreso en opcion");
?>

<style>

  .card.bg-light {
    border: none;
    box-shadow: 0 2px 12px rgba(0,123,255,0.06);
    border-radius: 0px;
    background: #fafdff;
    transition: box-shadow 0.2s, transform 0.2s;
    margin-bottom: 24px;
  }
  .card.bg-light:hover {
    box-shadow: 0 8px 32px rgba(0,123,255,0.13);
    transform: translateY(-4px) scale(1.015);
  }
  .card-header {
    background: none;
    color: #2563eb;
    font-weight: 700;
    font-size: 1.08rem;
    border-bottom: none;
    letter-spacing: 0.7px;
    padding-bottom: 6px;
    text-transform: uppercase;
    opacity: 0.85;
  }
  .lead {
    font-size: 1.12rem;
    color: #222;
    margin-bottom: 0.3rem;
    font-weight: 500;
  }
  .fa-ul li {
    margin-bottom: 0.3rem;
    font-size: 0.97rem;
    opacity: 0.85;
  }
  .img-circle {
    border-radius: 50%;
    border: none;
    width: 74px;
    height: 74px;
    object-fit: cover;
    background: #e3eefe;
    margin-top: 10px;
    box-shadow: 0 2px 8px rgba(37,99,235,0.08);
  }
  .breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 0;
  }
  .breadcrumb-item a {
    color: #2563eb;
    text-decoration: none;
    opacity: 0.85;
    transition: color 0.2s;
  }
  .breadcrumb-item a:hover {
    color: #1d4ed8;
    text-decoration: underline;
  }
  .breadcrumb-item.active {
    color: #8ca2c9;
    opacity: 0.8;
  }
  .row > [class^="col-"] {
    margin-bottom: 24px;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Integrantes</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item active">Integrantes</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="card card-solid">
      <div class="card-body pb-0">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card bg-light d-flex flex-fill">
              <div class="card-header text-muted border-bottom-0">
                Backend
              </div>
              <div class="card-body pt-0">
                <div class="row">
                  <div class="col-7">
                    <h2 class="lead"><b>Galo Izquierdo V.</b></h2>
                    <ul class="ml-4 mb-0 fa-ul text-muted">
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-mail-bulk"></i></span> Email: galo.izquierdov@ug.edu.ec</li>
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-info"></i></span> Grupo G-04 - Aplicaciones Web</li>
                    </ul>
                  </div>
                  <div class="col-5 text-center">
                    <img src="/img/giv.jpg" alt="user-avatar" class="img-circle img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card bg-light d-flex flex-fill">
              <div class="card-header text-muted border-bottom-0">
                fronted
              </div>
              <div class="card-body pt-0">
                <div class="row">
                  <div class="col-7">
                    <h2 class="lead"><b>Delgado Piguave Anthony Paul</b></h2>
                    <ul class="ml-4 mb-0 fa-ul text-muted">
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Email: anthony.delgadopig@ug.edu.ec</li>
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-info"></i></span> Grupo G04 - Aplicaciones Web</li>
                    </ul>
                  </div>
                  <div class="col-5 text-center">
                    <img src="/img/Pau.jpeg" alt="user-avatar" class="img-circle img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card bg-light d-flex flex-fill">
              <div class="card-header text-muted border-bottom-0">
                Documenter
              </div>
              <div class="card-body pt-0">
                <div class="row">
                  <div class="col-7">
                    <h2 class="lead"><b>Bryan Sanchez Parra</b></h2>
                 
                    <ul class="ml-4 mb-0 fa-ul text-muted">
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Email: Bryan.sanchezpar@ug.edu.ec</li>
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-info"></i></span> Grupo G04 - Aplicaciones Web</li>
                    </ul>
                  </div>
                  <div class="col-5 text-center">
                    <img src="/img/Bryan.jpeg" alt="user-avatar" class="img-circle img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card bg-light d-flex flex-fill">
              <div class="card-header text-muted border-bottom-0">
                Documenter
              </div>
              <div class="card-body pt-0">
                <div class="row">
                  <div class="col-7">
                    <h2 class="lead"><b>Josué Pita Franco </b></h2>
                 
                    <ul class="ml-4 mb-0 fa-ul text-muted">
                       <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Email: Josue.pitafra@ug.edu.ec</li>
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-info"></i></span> Grupo G04 - Aplicaciones Web</li>
                    </ul>
                  </div>
                  <div class="col-5 text-center">
                    <img src="/img/Pita.jpeg" alt="user-avatar" class="img-circle img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
       <!-- Bitacora  -->
    <div class="card mt-4">
      <div class="card-header bg-primary text-white">
        Bitácora del Grupo
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0">
            <thead class="thead-light">
              <tr>
                <th>Fecha</th>
                <th>Actividad</th>
                <th>Responsable(s)</th>
                <th>Descripción</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>16/06/2025</td>
                <td>Planificación</td>
                <td>Todo el grupo</td>
                <td>Reunión inicial para definir roles y tareas del proyecto.</td>
                <td><span class="badge badge-success">Completado</span></td>
              </tr>
              <tr>
                <td>24/06/2025</td>
                <td>Desarrollo Backend</td>
                <td>Galo Izquierdo</td>
                <td>Implementación de la estructura y lógica del servidor.</td>
                <td><span class="badge badge-success">Completado</span></td>
              </tr>
              <tr>
                <td>01/07/2025</td>
                <td>Desarrollo Frontend</td>
                <td>Anthony Delgado</td>
                <td>Creación de la interfaz de usuario y estilos.</td>
                <td><span class="badge badge-success">Completado</span></td>
              </tr>
              <tr>
                <td>03/07/2025</td>
                <td>Documentación</td>
                <td> Bryan sanchez Parra, Josué Pita Franco</td>
                <td>Redacción de la documentación técnica y manual de usuario.</td>
                <td><span class="badge badge-success">Completado</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>


    </div>
  </section>
</div>
