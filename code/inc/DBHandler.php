<?php

/*
 * Clase manejadora de acceso a base de datos.
 */

class DbHandler {

    public $dbcon;

    public function __construct($dbcon) {
        $this->dbcon = $dbcon;
    }

    /*
     * Funcion para obtener el Token
     * param string $userid  Id del usuario
     * return  Token
     */

    public function getUserToken(string $userid) {
        $resp = "";
        try {
            $sql = "SELECT token,fecexp FROM registro"
                    . " WHERE userid =:userid";
            $stmt = $this->dbcon->prepare($sql);
            $stmt->bindValue(':userid', $userid, PDO::PARAM_STR);
            $result = $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $resp = $result;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        } catch (Exception $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        }

        return $resp;
    }

    /*
     * Funcion de actualizacion de fecha de token
     * param string $userid Id del usuario
     * param string $ipnum  Ip del cliente
     * param $fecha  Fecha
     */

    public function updateFechaToken($userid, $ipnum, $fecha) {
        try {
            $sql = "UPDATE registro SET ipnum = :ipnum, fecexp = :fecha"
                    . " WHERE userid = :userid";
            $stmt = $this->dbcon->prepare($sql);
            $stmt->bindValue(':ipnum', $ipnum, PDO::PARAM_STR);
            $stmt->bindValue(':userid', $userid, PDO::PARAM_STR);
            $stmt->bindValue(':fecing', $fecha, PDO::PARAM_STR);
            $result = $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        } catch (Exception $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        }
    }

    /*
     * Funcion que revisa si el Token ha expirado
     * param string $param1 De acuerdo a tipo
     * param int    $tipo   0 Token, 1 UserIdr
     * return boolean
     */

    public function checkExpireToken(string $param1, int $tipo = 0) {
        try {
            if ($tipo === 0) {
                $sql = "SELECT UNIX_TIMESTAMP(fecexp) as fecha FROM registro"
                        . " WHERE token =:param1";
            } else {
                $sql = "SELECT UNIX_TIMESTAMP(fecexp) as fecha FROM registro"
                        . " WHERE userid =:param1";
            }

            $stmt = $this->dbcon->prepare($sql);
            $stmt->bindValue(':param1', $param1, PDO::PARAM_STR);
            $result = $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (isset($result['fecha']) && is_numeric($result['fecha']) && $result['fecha'] + TOKENMAXTIME < time()) {
                return true;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        } catch (Exception $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        }

        return false;
    }

    /*
     * Funcion para eliminar el registro de token viejo
     * param string userid  Id del usuario
     */

    public function deleteTokenRegister(string $userid) {
        try {
            error_log('delete token');
            $stmt1 = $this->dbcon->prepare("DELETE FROM registro WHERE userid =:userid");
            $stmt1->bindValue(':userid', $userid, PDO::PARAM_STR);
            $stmt1->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        } catch (Exception $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        }
    }

    /*
     * Funcion de verificacion de Entrada
     * param string userid  Id de Usuario
     * return datos de usuario
     */

    public function checkLogin(string $userid) {
        try {
            $sql = "SELECT password FROM users WHERE userid = :userid";
            $stmt = $this->dbcon->prepare($sql);
            $stmt->bindValue(':userid', $userid, PDO::PARAM_STR);
            $result = $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        } catch (Exception $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        }
    }

    /*
     * Funcion para registrar el Token de usuario
     * param string userid Id del usuario
     * param token  Token generado
     * param ipnum  Ip del cliente
     * param fecexp Fecha de expiracion
     */

    public function userTokenRegister(string $userid, $token, $fecexp) {
        try {
            $addr = inet_pton($_SERVER['REMOTE_ADDR']);
            $ip = '';
            foreach (str_split($addr) as $char) :
                $ip = $ip . str_pad(ord($char), 3, '0', STR_PAD_LEFT) . '.';
            endforeach;
            $ipnum = substr($ip, 0, -1);
            $sql = "INSERT INTO registro (userid,token,ipnum,fecexp)"
                    . " VALUES (:userid, :token, :ipnum, :fecexp)";
            $stmt = $this->dbcon->prepare($sql);
            $stmt->bindValue(':userid', $userid, PDO::PARAM_STR);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR);
            $stmt->bindValue(':ipnum', $ipnum, PDO::PARAM_STR);
            $stmt->bindValue(':fecexp', $fecexp, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        } catch (Exception $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        }
    }

    public function getProductos() {
        try {
            $sql = "SELECT * FROM items where tipo = 0";
            $stmt = $this->dbcon->prepare($sql);
            $all = $stmt->execute();
            $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // echo json_encode($all);
            return $all;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        } catch (Exception $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        }
    }

    public function getServicios() {
        try {
            $sql = "SELECT * FROM items where tipo = 1";
            $stmt = $this->dbcon->prepare($sql);
            $all = $stmt->execute();
            $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // echo json_encode($all);
            return $all;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        } catch (Exception $e) {
            error_log($e->getMessage());
            GenFunc::logSys("(" . __FUNCTION__ . ") E:Error " . $e->getCode() . ": " . $e->getMessage());
            $response["code"] = CODE400;
            $response["msg"] = $e->getMessage();
            $response["code_error"] = $e->getCode();
            GenFunc::echoResponse(CODE400, $response);
        }
    }
}
