<?php

/*
 * Proceso de login
 */
include_once dirname(__FILE__) . '/inc/config.php';
include_once dirname(__FILE__) . '/inc/ErrCod.php';
require_once dirname(__FILE__) . '/inc/GenFunc.php';
require_once dirname(__FILE__) . '/inc/DBHandler.php';
require_once dirname(__FILE__) . '/inc/AppException.php';

header('Content-Type: application/json');
session_start();

// Leer JSON
$input = json_decode(file_get_contents('php://input'), true);
$uid = trim($input['uid'] ?? '');
$pwd = trim($input['pwd'] ?? '');

try {
  // Usuario en blanco
  if (empty($uid)) {
    throw new AppException('E107', 401);
  }

  // Clave en blanco
  if (empty($pwd)) {
    throw new AppException('E108', 401);
  }

  // Conexión y validación
  $dbcon = GenFunc::dbConnect();
  $db = new DbHandler($dbcon);

  // Validar el usuario y la contraseña en la base de datos
  if (!GenFunc::checkUser($db, $uid, $pwd)) {
    throw new AppException('E109', 401);
  }

  GenFunc::logSys("(login) I:Ingreso Exitoso [$uid]");

  $tokenValido = $db->checkExpireToken($uid, 1);
  $token = '';
  $fecexp = '';

  if ($tokenValido) {
    GenFunc::logSys("(login) I:Devuelve el Token");
    $jwt = $db->getUserToken($uid);
    if (!$jwt) {
      throw new AppException('E110', 401); // token no encontrado
    }
    $token = $jwt['token'];
    $fecexp = $jwt['fecexp'];
  } else {
    // Generar y registrar nuevo token
    GenFunc::logSys("(login) I:Genera Token");
    $token = bin2hex(random_bytes(16));
    $fecexp = time() + TOKENMAXTIME;

    GenFunc::logSys("(login) I:Elimina Registro Anterior");
    $db->deleteTokenRegister($uid);

    GenFunc::logSys("(login) I:Crea Registro");
    $db->userTokenRegister($uid, $token, $fecexp);
  }
  // Respuesta final
  GenFunc::logSys("(login) I:Ingreso Exitoso");
  GenFunc::sendJsonResponse(['data' => [
          'token' => $token,
          'expira' => date('Y-m-d H:i:s.u', $fecexp),
  ]]);
} catch (AppException $ae) {
  GenFunc::sendJsonResponse([
      'code' => $ae->getCode(),
      'msg' => $ae->getMessage(),
      'code_error' => $ae->getErrCode()]);
} catch (Exception $e) {
  error_log($e->getMessage());
  GenFunc::logSys("(login Exception) E:" . $e->getCode() . ": " . $e->getMessage());
  GenFunc::sendJsonResponse([
      'code' => 400,
      'msg' => $e->getMessage(),
      'code_error' => $e->getCode()]);
} finally {
  $db = null;
  $dbcon = null;
}



