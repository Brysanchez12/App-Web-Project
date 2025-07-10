<?php

class AppException extends Exception {

  protected $errCode;  // Código lógico (como 'E110', 'GENERIC', etc.)
  protected $errMsg;   // Mensaje correspondiente al código lógico

  /**
   * Constructor de la excepción personalizada.
   *
   * @param string $codeOrMsg Código lógico de error (como 'E110') o mensaje directo.
   * @param int $httpCode Código HTTP (por defecto 400).
   * @param Throwable|null $previous Excepción previa (para encadenar).
   */
  public function __construct($codeOrMsg, $httpCode = 400, Throwable $previous = null) {
    // Capturar la ubicación desde donde se lanzó la excepción
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $caller = $trace[1] ?? ['file' => __FILE__, 'line' => __LINE__];
    $archivo = basename($caller['file']);
    $linea = $caller['line'];

    // Validar si el código está definido en ErrCod
    if (defined("ErrCod::{$codeOrMsg}")) {
      $this->errCode = $codeOrMsg;
      $this->errMsg = constant("ErrCod::{$codeOrMsg}");
    } else {
      // Si no es un código definido, usarlo como mensaje directamente
      $this->errCode = 'GENERIC';
      $this->errMsg = $codeOrMsg;
    }
    // Llamar constructor padre
    parent::__construct($this->errMsg, $httpCode, $previous);

    // Log de contexto
    GenFunc::logSys("({$archivo}:{$linea}) E:{$this->errCode} {$this->errMsg}");
  }

  /**
   * Devuelve un arreglo para respuesta JSON.
   *
   * @return array
   */
  public function toArray() {
    GenFunc::sendJsonResponse([
        'code' => $this->getcode(),
        'msg' => $this->getMessage(),
        'code_err' => $this->errCode]);
  }

  public function getErrCode() {
    return $this->errCode;
  }
}
