<?php
include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
GenFunc::logSys("(integra) I:Ingreso en opcion");
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Tutor - Construccion de Software</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item active">Tutor</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container py-4">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center">
              <h4 class="mb-0">Tutor</h4>
            </div>
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                  <img src="/img/Parrales.jpg" alt="user-avatar" class="rounded-circle img-fluid shadow" style="width: 130px; height: 130px; object-fit: cover;">
                </div>
                <div class="col-12 col-md-8">
                  <h5 class="fw-bold mb-1">Ph.D. Franklin Parrales Bravo</h5>
                  <p class="text-muted mb-2 small">
                    Doctor en Ingeniería Informática, Universidad Complutense de Madrid, 2020.<br>
                    Máster en Inteligencia Artificial, Universidad Politécnica de Madrid, 2019.<br>
                    Máster en Ingeniería Informática, Universidad Complutense de Madrid, 2015.<br>
                    Ingeniero en Ciencias Computacionales, ESPOL, 2010 (mejor alumno de la promoción).
                  </p>
                  <p class="text-muted small mb-2">
                    Becado por SENESCYT-Ecuador (beca 8905-AR5G-2016) para tesis doctoral en E-Health. Colaboraciones internacionales HIPEAC (H2020-ICT-2015-687689 y H2020-ICT-2017-779656) con instituciones en Eslovenia y Portugal.<br>
                    Áreas de investigación: e-Health, minería de datos y aprendizaje automático.
                  </p>
                  <ul class="list-unstyled mb-0">
                    <li><i class="bi bi-envelope"></i> <strong>Email:</strong> franklin.parralesb@ug.edu.ec</li>
                    <li><i class="bi bi-person-badge"></i> <strong>Tutor en Construcción de Software</strong></li>
                    <li><i class="bi bi-card-text"></i> <strong>Cédula:</strong> 0925519316</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>