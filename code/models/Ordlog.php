<?php

// Zona horaria local
setlocale(LC_TIME, 'es_ES.UTF-8');
setlocale(LC_MONETARY, 'en_US');
date_default_timezone_set('America/Guayaquil');

/**
 * Description of Detalle de Orden
 *
 * @author Galo Izquierdo
 */
class Ordlog
{

  protected $db;

  //  public $id;
//  public $codcli;
//  public $codtec;
//  public $marca;
//  public $modelo;
//  public $imei;
//  public $fecing;
//  public $fecfac;
//  public $estado;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getById(string $id)
  {
    try {
      $sql = "SELECT * FROM ordlog WHERE id = :id";
      $q = $this->db->prepare($sql);
      $q->bindParam(":id", $id, PDO::PARAM_INT);
      $q->execute();
      $rows = $q->fetchAll();

      return $rows;
    } catch (PDOException $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E170, 500, $e);
    } catch (Exception $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E171, 500, $e);
    }
  }

  public function getAll($orden)
  {
    try {
      error_log('ordenes log getall');
      $sql = "SELECT * FROM ordlog where ordid=:orden";
      $q = $this->db->prepare($sql);
      $q->bindParam(":orden", $orden, PDO::PARAM_INT);
      $q->execute();
      $rows = $q->fetchAll();
      return $rows;
    } catch (PDOException $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E170, 500, $e);
    } catch (Exception $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E171, 500, $e);
    }
  }

  public function insert($orden, $tipo)
  {
    error_log('insert ordlog');
    try {
      $sql = "INSERT INTO ordlog (ordid,feclog,observ)"
        . " VALUES (:ordid, :feclog, :observ)";
      $stmt = $this->db->prepare($sql);

      $observ = "Se registra ingreso de ";
      switch ($tipo):
        case Orddet::DETALLE:
          $observ .= "Observacion";
          break;
        case Orddet::REPUESTO:
          $observ .= "Repuesto";
          break;
        case Orddet::SERVICIO:
          $observ .= "Servicio";
          break;
      endswitch;
      $fecha = date('Y-m-d H:i:s', strtotime('now'));
      $stmt->bindValue(':ordid', $orden, PDO::PARAM_STR);
      $stmt->bindValue(':feclog', $fecha, PDO::PARAM_STR);
      $stmt->bindValue(':observ', $observ, PDO::PARAM_STR);
      $stmt->execute();
      return true;
    } catch (PDOException $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E170, 500, $e);
    } catch (Exception $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E171, 500, $e);
    }
    return false;
  }

}