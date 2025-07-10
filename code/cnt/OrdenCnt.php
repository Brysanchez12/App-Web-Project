<?php

/*
 * Orden Controller
 */
include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
require_once dirname(__FILE__) . '/../inc/DBHandler.php';
include dirname(__FILE__) . "/../models/Orden.php";
//header('Content-Type: application/json');
session_start();
$dbcon = \GenFunc::dbConnect();
$orden = new Orden($dbcon);
// Lee la ruta y el método HTTP
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
//$id = isset($request[0]) ? (int) $request[0] : null;
// Lee el cuerpo JSON si existe
$input = json_decode(file_get_contents('php://input'), true);
// CRUD según método
switch ($method) {
  case 'GET':
    if (isset($request[0])) {
      switch ($request[0]) {
        case 'id':
          // Obtener
          $id = isset($request[1]) ? (int) $request[1] : null;
          error_log('Obtener orden por ID: ' . $id);
          $result = $orden->getById($id);
          if ($result) {
            echo json_encode($result);
          }
          else {
            http_response_code(404);
            echo json_encode(['error' => 'Cliente no encontrado']);
          }
          break;
        case 'act':
          // Activos para tecnico
          $list = $orden->getOrdenByTec($request[1]);
          GenFunc::sendJsonResponse(['data' => $list]);
          break;
        case 'fac':
          // Activos para facturar
          $list = $orden->getAll(Orden::CERRADO);
          GenFunc::sendJsonResponse(['data' => $list]);
          break;
        case 'lista':
          // Combo
          $list = $orden->getSelectOptions();
          GenFunc::sendJsonResponse(['data' => $list]);
          break;
        case 'count':
          $recnum = $orden->getCount();
          GenFunc::sendJsonResponse(['data' => $recnum]);
          break;
        case 'cerrar':
          if (!isset($request[1])) {
            GenFunc::sendJsonResponse(['code' => 400, 'msg' => 'Falta el ID de la orden', 'code_error' => 'E151']);
          }
          $ordenId = (int) $request[1];
          $ok = $orden->cerrar($ordenId);
          if ($ok) {
            GenFunc::sendJsonResponse(['data' => 1]);
          }
          else {
            GenFunc::sendJsonResponse(['data' => 0]);
          }
          break;
      }
      break;
    }
    if ($id) {
      // Obtener
      error_log('Obtener orden por ID: ' . $id);
      $result = $orden->getById($id);
      if ($result) {
        echo json_encode($result);
      }
      else {
        http_response_code(404);
        echo json_encode(['error' => 'Cliente no encontrado']);
      }
    }
    //else {
    // Listar todos (o implementar paginación si quieres)
    //  $all = $orden->getAll();
    //  echo json_encode($all);
    // }
    break;
  case 'POST':
    // Crear nuevo orden
    $requiredFields = ['clicod', 'teccod', 'fecha', 'marca', 'modelo', 'imei', 'observ'];
    // Validación de campos
    foreach ($requiredFields as $field) {
      if (empty($input[$field])) {
        GenFunc::sendJsonResponse([
           'code' => 400,
           'msg' => "El campo '$field' es obligatorio",
           'code_error' => 'E151',
        ]);
      }
    }

    error_log('Nuevo orden recibido');
    $ok = $orden->insert($input);
    if ($ok) {
      GenFunc::sendJsonResponse(['data' => 1]);
    }
    else {
      throw new AppException('E206', 500);
    }
    break;
  //  case 'PUT':
//    error_log('edita cliente');
//    $ok = $orden->update($input);
//      GenFunc::sendJsonResponse(['data' => 1]);
//    break;
//  case 'DELETE':
//    if (!$id) {
//      http_response_code(400);
//      echo json_encode(['error' => 'Falta el ID para eliminar']);
//      break;
//    }
//    // Eliminar
//    $ok = $orden->remove($id);
//      GenFunc::sendJsonResponse(['data' => 1]);
//    break;
  default:
    GenFunc::sendJsonResponse(['code' => 405, 'msg' => Errcod::E150]);
    break;
}