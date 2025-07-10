<?php

/*
 * Factura Controller
 */
include_once dirname(__FILE__) . '/../inc/config.php';
include_once dirname(__FILE__) . '/../inc/ErrCod.php';
require_once dirname(__FILE__) . '/../inc/GenFunc.php';
require_once dirname(__FILE__) . '/../inc/DBHandler.php';
include dirname(__FILE__) . "/../models/Factura.php";
session_start();
$dbcon = \GenFunc::dbConnect();
$factura = new Factura($dbcon);
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
$input = json_decode(file_get_contents('php://input'), true);
switch ($method) {
  case 'GET':
    if (isset($request[0])) {
      switch ($request[0]) {
        case 'facoid':
          $id = isset($request[1]) ? (int) $request[1] : null;
          if (!$id) {
            GenFunc::sendJsonResponse(['code' => 400, 'msg' => 'Falta el ID de la factura', 'code_error' => 'E151']);
          }

          error_log('Graba factura de orden: ' . $id);
          $result = $factura->insert($id);
          if ($result) {
            echo json_encode(['ok' => 1]);
          }
          else {
            http_response_code(404);
            echo json_encode(['error' => 'Factura no grabada']);
          }
          break;
        case 'id':
          $id = isset($request[1]) ? (int) $request[1] : null;
          if (!$id) {
            GenFunc::sendJsonResponse(['code' => 400, 'msg' => 'Falta el ID de la factura', 'code_error' => 'E151']);
          }

          error_log('Obtener factura por ID: ' . $id);
          $result = $factura->getById($id);
          if ($result) {
            echo json_encode($result);
          }
          else {
            http_response_code(404);
            echo json_encode(['error' => 'Factura no encontrada']);
          }
          break;
        case 'lista':
          $list = $factura->getAll();
          GenFunc::sendJsonResponse(['data' => $list]);
          break;
      }
    }
    else {
      // Si no se pasa ruta, retorna todas
      $list = $factura->getAll();
      GenFunc::sendJsonResponse(['data' => $list]);
    }
    break;
  default:
    GenFunc::sendJsonResponse(['code' => 405, 'msg' => ErrCod::E150]);
    break;
}
