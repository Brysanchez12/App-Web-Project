<?php

/*
 * Servicio Controller
 */
include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
require_once dirname(__FILE__) . '/../inc/DBHandler.php';
include dirname(__FILE__) . "/../models/Servicio.php";
//header('Content-Type: application/json');
session_start();
$dbcon = \GenFunc::dbConnect();
$servicio = new Servicio($dbcon);
// Lee la ruta y el método HTTP
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
$id = isset($request[0]) ? (int) $request[0] : null;
// Lee el cuerpo JSON si existe
$input = json_decode(file_get_contents('php://input'), true);
// CRUD según método
switch ($method) {
  case 'GET':
    if (isset($request[0]) && $request[0] === 'lista') {
      $list = $servicio->getSelectOptions();
      GenFunc::sendJsonResponse(['data' => $list]);
      break;
    }
    if (isset($request[0]) && $request[0] === 'count') {
      $recnum = $servicio->getCount();
      GenFunc::sendJsonResponse(['data' => $recnum]);
      break;
    }
    if ($id) {
      // Obtener un servicio
      $result = $servicio->getById($id);
      if ($result) {
        GenFunc::sendJsonResponse(['data' => $result]);
      } else {
        GenFunc::sendJsonResponse(['code' => 400, 'msg' => ErrCod::E204]);
      }
    } else {
      // Listar todos (o implementar paginación si quieres)
      $all = $servicio->getAll();
      GenFunc::sendJsonResponse(['data' => $all]);
    }
    break;
  case 'POST':
    // Crear nuevo servicio
    $ok = $servicio->insert($input);
    GenFunc::sendJsonResponse(['data' => 1]);
    break;
  case 'PUT':
    // edita servicio'
    $ok = $servicio->update($input);
    GenFunc::sendJsonResponse(['data' => 1]);
    break;
  case 'DELETE':
    if (!$id) {
      GenFunc::sendJsonResponse(['code' => 400, 'msg' => Errcod::E201]);
      break;
    }
    // Eliminar servicio
    $ok = $servicio->remove($id);
    GenFunc::sendJsonResponse(['data' => 1]);
    break;
  default:
    GenFunc::sendJsonResponse(['code' => 405, 'msg' => Errcod::E150]);
    break;
}

