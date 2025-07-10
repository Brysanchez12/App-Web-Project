<?php

/*
 * Index.php
 * Pantalla de inicio del sistema
 *
 */
setlocale(LC_TIME, 'es_ES.UTF-8');
setlocale(LC_MONETARY, 'en_US');
date_default_timezone_set('America/Guayaquil');
session_start();
$_SESSION["opcmnu"] = "index";
?>
<!DOCTYPE html>
<!--
    Proyecto UGCS
    ** Integrantes

    Guayaquil - Ecuador
-->
<html lang="es">
<!-- Header -->

<head>
  <?php include "views/header.html" ?>
  <?php include "views/js.html" ?>
</head>
<!-- Carga dinamica del contenido -->
<script>
  $(document).ready(function () {
    $('.nav-link[data-section]').on('click', function (e) {
      e.preventDefault();
      var section = $(this).data('section');
      loadSection(section);
    });
    // Funcion de carga de seccion
    // param section string  Nombre de la seccion
    function loadSection(section) {
      // verifica token si aun es valido;
      const isAuthenticated = checkAuth();
      if (!isAuthenticated) {
        return;
      }

      $('#content').load('views/' + section + '.php', function (response, status) {
        console.log('carga: ' + section);
        if (status !== 'success') {
          // En caso que no se encuentra
          $('#content').html('<p style="color:red;">Error cargando sección.</p>');
        } else {
          // Carga script
          switch (section) {
            case 'clientes':
              cargaClientes();
              break;
            case 'productos':
              cargaProductos();
              break;
            case 'servicios':
              cargaServicios();
              break;
            case 'tecnicos':
              cargaTecnicos();
              break;
            case 'procord':
              cargaProcord();
              break;
            case 'ordfac':
              cargaOrdfac();
              break;
            case 'dashboard':
              cargaDash();
              break;
            case 'creaord':
              cargaOrden();
              break;
          }
        }
      });
    }
    // Carga inicial
    loadSection('dashboard');
  });
</script>
<!-- Fin Header -->

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">
    <!-- Preloader and Navbar -->
    <?php include "views/nav.html" ?>
    <!-- aside Menu -->
    <?php include "views/aside.html" ?>
    <!-- Contenido -->
    <div id="content"></div>
    <!-- Footer -->
    <?php include "views/footer.html" ?>
  </div>
  <!-- Dialogo global -->
  <div id="globalDialog" style="display:none;"></div>
</body>

</html>