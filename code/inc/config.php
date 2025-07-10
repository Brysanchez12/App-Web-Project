<?php

// Zona horaria local
setlocale(LC_TIME, 'es_ES.UTF-8');
setlocale(LC_MONETARY, 'en_US');
date_default_timezone_set('America/Guayaquil');
// Cabeceras
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
define('DB_HOST', 'www.ecuinfo.net');
define('DB_USER', 'ugcs');
define('DB_PASS', 'universidadCS2025');
define('DB_NAME', 'ugcs');
define('DEBUG_MODE', 1); // 1 = DESARROLLO; 0 = PRODUCCION
//86399 1 dia ,200 3 minutos,900 15 minutos
define('TOKENMAXTIME', 11900);
define('RUTA', "/xammp/htdocs/ug/");
/* ---- */
//http Codes
define('CODE200', 200); // OK
define('CODE204', 204); // Empty
define('CODE400', 400); // Error
define('CODE401', 401); // Access Error
define('CODE404', 404); // Not Found
//antes
define('E_1001', 'E_1001'); //Exception
define('E_1002', 'E_1002'); //Exception acceso
define('E_1003', 'E_1003'); //Exception BD
define('E_2001', 'E_2001'); //Acceso
define('E_2002', 'E_2002'); //usuario vacio
define('E_2003', 'E_2003'); //contraseña vacia
define('E_2004', 'E_2004'); //usuario incorrecto
define('E_2005', 'E_2005'); //contraseña incorrecta
define('E_2006', 'E_2006'); //Token inexistente
define('E_2007', 'E_2007'); //Token invalido
define('E_2008', 'E_2008'); //Parametros inexistentes
define('E_2009', 'E_2009'); //Error actualizar IP
define('E_3001', 'E_3001'); //Error validacion
define('E_3002', 'E_3002'); //Error al traer registros