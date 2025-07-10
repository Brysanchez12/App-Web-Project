<?php

class ErrCod {

  const E102 = 'Error en acceso a DB.';
  const E103 = 'Datos de acceso no validos.';
  const E104 = 'Datos de acceso incorrectos, usuario no existe.';
  const E105 = 'Datos de acceso incorrectos, clave no válida.';
  const E106 = 'Token no válido.';
  const E107 = 'Usuario no válido.';
  const E108 = 'Contraseña no válido.';
  const E109 = 'Token no existe.';
  const E110 = 'Token no se pudo generar.';
  const E150 = 'Método no permitido';
  const E151 = 'El Campo es obligatorio';
  const E170 = 'Error inesperado Model PDO';
  const E171 = 'Error inesperado Model Exception';
  const E200 = 'Cliente no encontrado.';
  const E201 = 'Falta el ID para eliminar';
  const E203 = 'Técnico no encontrado';
  const E204 = 'Servicio no encontrado';
  const E205 = 'Producto no encontrado';
  const E206 = 'Error al insertar la orden';

  public static function getMessage($code) {
    return constant("self::$code") ?? null;
  }
}
