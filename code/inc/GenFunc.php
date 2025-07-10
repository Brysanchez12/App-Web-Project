<?php

require_once 'AppException.php';
/*
 * Fuciones generales
 *
 */

class GenFunc {
  /*
   * Función de conexión a base de datos utilizando PDO.
   * Establece y retorna una conexión segura a MySQL con configuración de errores.
   * En caso de error, lo registra y devuelve una respuesta en formato JSON.
   */

  public static function dbConnect() {
    try {
      // Crear una nueva conexión PDO a la base de datos con los parámetros definidos como constantes
      $connection = new PDO(
              'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
              DB_USER,
              DB_PASS
      );

      // Configurar el modo de errores para que lance excepciones
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Retorna la conexión PDO para ser utilizada en consultas
      return $connection;
    } catch (PDOException $e) {
      // Captura errores específicos de PDO (problemas de conexión, sintaxis, etc.)
      error_log($e->getMessage());  // Registra el error en el log del sistema
      self::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . " " . $e->getMessage());

      // Preparar respuesta JSON con código de error y mensaje
      $response["code"] = CODE400;
      $response["msg"] = $e->getMessage();
      $response["code_error"] = $e->getCode();
      echo json_encode($response);
    } catch (Exception $e) {
      // Captura cualquier otro tipo de excepción genérica
      error_log($e->getMessage());
      self::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . " " . $e->getMessage());

      // Preparar respuesta JSON con código de error y mensaje
      $response["code"] = CODE400;
      $response["msg"] = $e->getMessage();
      $response["code_error"] = $e->getCode();
      echo json_encode($response);
    }
  }

  /*
   * Verificar la existencia del usuario en la base de datos
   * param db  DBHandler
   * param string userid  Id del usuario
   * param string pass     Contraseña
   * return boolean
   */

  public static function checkUser($db, string $userid, string $pass): bool {
    try {
      self::logSys("(" . __FUNCTION__ . ") I:Verifica Ingreso");

      $login = $db->checkLogin($userid, $pass);

      // Validación de respuesta
      if (!is_array($login)) {
        self::logSys("(" . __FUNCTION__ . ") E:Acceso Denegado: " . ErrCod::E103);
        throw new AppException('E103', 404);
      }

      if (empty($login)) {
        self::logSys("(" . __FUNCTION__ . ") W:Acceso Denegado: " . ErrCod::E104);
        throw new AppException('E104', 401);
      }

      // Verificación de contraseña
      if (!password_verify($pass, $login['password'])) {
        self::logSys("(" . __FUNCTION__ . ") W:Acceso Denegado: " . ErrCod::E105);
        throw new AppException('E105', 401);
      }

      return true;
    } catch (AppException $ae) {
      // Re-lanzar para que sea atrapado en login.php
      throw $ae;
    } catch (Exception $e) {
      self::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
      throw new AppException($e->getMessage(), 400, $e);
    }
  }

  public static function sendJsonResponse($params) {
    header('Content-Type: application/json');

    if (isset($params['data']) && $params['data']) :
      $response = ['data' => $params['data'], 'err' => 0];
    else:
      $response = [
          'err' => [
              'code' => $params['code'],
              'msg' => $params['msg'],
              'code_error' => $params['code_error']
          ]
      ];
    endif;

    echo json_encode($response);
    exit;
  }

  /*
   * Función de grabación de bitácora (log)
   * Registra mensajes en un archivo de log incluyendo fecha, IP y tipo de mensaje.
   *
   * @param string $msg Texto del mensaje a guardar, se espera que contenga un formato como "(123) E:Mensaje de error"
   */

  public static function logSys($msg) {
    try {
      // Definir ruta del archivo de log
      $logfile = dirname(__FILE__) . "/../logs/CS.log";

      // Si el archivo existe y pesa más de 2MB, hacer respaldo y limpiar
      if (file_exists($logfile) && filesize($logfile) > 2000000):
        // Crear nombre de backup con fecha y hora
        $backupName = dirname($logfile) . "/CS_" . date("Ymd_His") . ".log";
        // Renombrar el archivo actual como respaldo
        rename($logfile, $backupName);
      endif;

      // Abrir archivo en modo de escritura al final del archivo
      $fp = fopen($logfile, 'a');

      // Obtener la fecha y hora actual en formato legible
      $today = date("d M Y H:i:s");

      // Convertir la IP del cliente en un formato codificado de 3 dígitos por byte
      $ip = '';
      $addr = inet_pton($_SERVER['REMOTE_ADDR']);
      foreach (str_split($addr) as $char) :
        $ip = $ip . str_pad(ord($char), 3, '0', STR_PAD_LEFT) . '.';
      endforeach;
      $ipx = substr($ip, 0, -1); // Eliminar el punto final
      // Extraer tipo de mensaje del texto ($msg), esperando un formato como "(123) E:Texto"
      preg_match('/\((\d+)\)\s([A-Za-z]):/', $msg, $matches);
      $logLevel = isset($matches[2]) ? $matches[2] : 'I'; // Nivel de log (I = info por defecto)
      $logMessage = isset($matches[3]) ? $matches[3] : $msg; // Texto real del mensaje
      // Formatear línea del log: fecha + IP + tipo + mensaje
      $logdat = $today . ' [' . $ipx . '] ' . $logLevel . ':' . $logMessage . PHP_EOL;

      // Escribir mensaje en archivo
      fwrite($fp, $logdat);
      fclose($fp);
    } catch (Exception $e) {
      // En caso de error, registrar en el log de errores de PHP y responder con JSON
      error_log($e->getMessage());
      self::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
      $response["code"] = CODE400;
      $response["msg"] = $e->getMessage();
      $response["code_error"] = $e->getCode();
      echo json_encode($response);
    }
  }

// Función para formatear valores numéricos de acuerdo al tipo solicitado
//
// @param mixed $valor El valor numérico a formatear (por defecto 0)
// @param int $tipo Tipo de formato:
//      1 - Número con decimales (ej: 1,234.56)
//      2 - Entero sin decimales (ej: 1,234)
//      3 - Porcentaje (ej: 12.34%)
//      otro (default) - Formato monetario con símbolo de moneda
// @param int $dec Número de decimales para los tipos que lo requieren (por defecto 2)
// @return string Valor formateado como texto
  public static function val_format($valor = 0, $tipo = 0, $dec = 2) {
    switch ($tipo):
      case 1:  // Formato numérico con decimales
        // Ejemplo: val_format(1234.56, 1, 2) => "  1,234.56"
        $frm = GFunc::m_format('%#11.' . $dec . 'n', $valor, false);
        break;
      case 2:  // Formato entero sin decimales
        // Ejemplo: val_format(1234.56, 2) => "      1235"
        $frm = GFunc::m_format('%#11', $valor, false);
        break;
      case 3:  // Formato porcentaje con dos decimales y símbolo "%"
        // Ejemplo: val_format(12.345, 3) => "12.35%"
        $frm = GFunc::m_format('%#3.2n', $valor, false) . '%';
        break;
      default: // Formato monetario (por defecto), incluyendo símbolo de moneda
        // Ejemplo: val_format(1234.56) => "$ 1,234.56"
        $frm = GFunc::m_format('%#11.2n', $valor, true);
        break;
    endswitch;

    // Devolver el valor ya formateado como string
    return $frm;
  }

  /**
   * Función personalizada para formateo numérico y monetario siguiendo una cadena de formato específica.
   * Similar a printf() pero adaptada para formatos internacionales de moneda con control de signos, símbolos y alineaciones.
   *
   * @param string $format Cadena de formato, con una sintaxis similar a printf y opciones extra:
   *   - %: indica inicio del patrón
   *   - =x : caracter de relleno
   *   - ^  : sin separador de miles
   *   - !  : sin símbolo de moneda
   *   - -  : alinear a la izquierda
   *   - + o ( : muestra el signo '+' o entre paréntesis si es negativo
   *   - [ancho] : ancho mínimo de la cadena
   *   - #[ancho_entero] : longitud mínima para la parte entera
   *   - .[decimales] : cantidad de decimales
   *   - [i|n|%] : tipo de conversión (i = moneda internacional, n = moneda local)
   * @param float $number El número a formatear.
   * @param bool $curr Si se debe incluir símbolo monetario. Por defecto `false`.
   *
   * @return string Número formateado según el formato solicitado.
   */
  private static function m_format($format, $number, $curr = false) {
    // Expresión regular para extraer los patrones del formato
    $regex = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?' .
            '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';

    // Configurar la localización monetaria si está en "C"
    if (setlocale(LC_MONETARY, 0) == 'C') {
      setlocale(LC_MONETARY, '');
    }
    $locale = localeconv(); // Obtener configuración regional
    // Buscar todas las coincidencias de patrones
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
    foreach ($matches as $fmatch) {
      $value = floatval($number); // Convertir a número flotante
      // Obtener banderas del formato
      $flags = array(
          'fillchar' => preg_match('/\=(.)/', $fmatch[1], $match) ? $match[1] : ' ',
          'nogroup' => preg_match('/\^/', $fmatch[1]) > 0, // sin separador de miles
          'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? $match[0] : '+',
          'nosimbol' => preg_match('/\!/', $fmatch[1]) > 0, // sin símbolo monetario
          'isleft' => preg_match('/\-/', $fmatch[1]) > 0             // alineado a la izquierda
      );

      $width = trim($fmatch[2]) ? (int) $fmatch[2] : 0;             // ancho mínimo total
      $left = trim($fmatch[3]) ? (int) $fmatch[3] : 0;              // ancho de parte izquierda
      $right = trim($fmatch[4]) ? (int) $fmatch[4] : $locale['int_frac_digits']; // decimales
      $conversion = $fmatch[5];                                     // tipo: i (internacional) o n (nacional)
      // Manejo de signo
      $positive = true;
      if ($value < 0) {
        $positive = false;
        $value *= -1;
      }
      $letter = $positive ? 'p' : 'n';

      // Inicializar partes del formato
      $prefix = $suffix = $cprefix = $csuffix = $signal = '';

      $signal = $positive ? '' : $locale['negative_sign'];

      // Posicionamiento del signo según configuración regional
      switch (true) {
        case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
          $prefix = $signal;
          break;
        case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
          $suffix = $signal;
          break;
        case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
          $cprefix = $signal;
          break;
        case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
          $csuffix = $signal;
          break;
        case $flags['usesignal'] == '(':
        case $locale["{$letter}_sign_posn"] == 0:
          $prefix = '(';
          $suffix = ')';
          break;
      }

      // Símbolo de moneda si está habilitado
      if (!$flags['nosimbol']) {
        $currency = $cprefix .
                ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                $csuffix;
      } else {
        $currency = '';
      }

      if ($curr == false) {
        $currency = '';
      }

      $space = $locale["{$letter}_sep_by_space"] ? ' ' : '';

      // Formatear el número según los separadores regionales
      $value = number_format(
              $value,
              $right,
              $locale['mon_decimal_point'],
              $flags['nogroup'] ? '' : $locale['mon_thousands_sep']
      );
      $value = @explode($locale['mon_decimal_point'], $value);

      // Rellenar con caracteres si se especifica ancho a la izquierda
      $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
      if ($left > 0 && $left > $n) {
        $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
      }

      $value = trim(implode($locale['mon_decimal_point'], $value));

      // Ordenar las partes según la configuración regional
      if ($locale["{$letter}_cs_precedes"]) {
        $value = $prefix . $currency . $value . $suffix;
      } else {
        $value = $prefix . $value . $space . $currency . $suffix;
      }

      // Ajustar a ancho total si es necesario
      if ($width > 0) {
        $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                STR_PAD_RIGHT : STR_PAD_LEFT);
      }

      // Reemplazar el patrón por el valor formateado en la cadena original
      $format = str_replace($fmatch[0], $value, $format);
    }

    // Devolver el string formateado final
    return $format;
  }
}
