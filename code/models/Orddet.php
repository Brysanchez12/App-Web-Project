<?php

include "Ordlog.php";
/**
 * Description of Detalle de Orden
 *
 * @author Galo Izquierdo
 */
class Orddet{

  protected
     $db;

  const
     DETALLE = 0;
  const
     REPUESTO = 1;
  const
     SERVICIO = 2;

  //  public $id;
//  public $codcli;
//  public $codtec;
//  public $marca;
//  public $modelo;
//  public $imei;
//  public $fecing;
//  public $fecfac;
//  public $estado;

  public
     function __construct(PDO $db){
    $this->db = $db;
  }

  public
     function getById(string $id){
    try {
      $sql = "SELECT orddet.*, items.nombre as itenom FROM orddet"
         . " left join items on orddet.iteid=items.id"
         . " WHERE orddet.ordid = :id";
      $q = $this->db->prepare($sql);
      $q->bindParam(":id", $id, PDO::PARAM_INT);
      $q->execute();
      $rows = $q->fetchAll();
      return $rows;
    }
    catch (PDOException $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E170, 500, $e);
    }
    catch (Exception $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E171, 500, $e);
    }
  }

  public
     function getAll($orden){
    try {
      error_log('ordenes detalles getall');
      $sql = "SELECT * FROM orddet where ordid=:orden";
      $q = $this->db->prepare($sql);
      $q->bindParam(":orden", $orden, PDO::PARAM_INT);
      $q->execute();
      $rows = $q->fetchAll();
      return $rows;
    }
    catch (PDOException $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E170, 500, $e);
    }
    catch (Exception $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E171, 500, $e);
    }
  }

  public
     function insert($datos){
    error_log('insert orddet');
    try {
      $this->db->beginTransaction();
      $sql = "INSERT INTO orddet (ordid,tipdet,fecdet,iteid,prcvta,cantid,observ,estado)"
         . " VALUES (:ordid, :tipdet, :fecdet, :iteid, :prcvta, :cantid, :observ, :estado)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':ordid', $datos['ordid'], PDO::PARAM_STR);
      $stmt->bindValue(':tipdet', $datos['tipdet'], PDO::PARAM_STR);
      $stmt->bindValue(':observ', $datos['observ'], PDO::PARAM_STR);
      $stmt->bindValue(':fecdet', $datos['fecdet'], PDO::PARAM_STR);
      $stmt->bindValue(':estado', '0', PDO::PARAM_STR);
      switch ($datos['tipdet']):
        case self::DETALLE:
          $stmt->bindValue(':iteid', '0', PDO::PARAM_STR);
          $stmt->bindValue(':prcvta', '0', PDO::PARAM_STR);
          $stmt->bindValue(':cantid', '0', PDO::PARAM_STR);
          break;
        case self::REPUESTO:
          $dite = self::itemgetById($datos['iteid']);
          if (!$dite) {
            throw new AppException('Repuesto no encontrado', 400);
          }

          // Verificar stock suficiente
          if ($dite['cantid'] < $datos['cantid']) {
            throw new AppException('Stock insuficiente para el repuesto', 400);
          }

          error_log($dite['prcvta']);
          $stmt->bindValue(':iteid', $datos['iteid'], PDO::PARAM_STR);
          $stmt->bindValue(':prcvta', $dite['prcvta'], PDO::PARAM_STR);
          $stmt->bindValue(':cantid', $datos['cantid'], PDO::PARAM_STR);
          // Descontar stock
          $sqlStock = "UPDATE items SET cantid = cantid - :xcantid WHERE id = :iteid";
          $stStock = $this->db->prepare($sqlStock);
          $stStock->bindValue(':xcantid', $datos['cantid'], PDO::PARAM_INT);
          $stStock->bindValue(':iteid', $datos['iteid'], PDO::PARAM_INT);
          $stStock->execute();
          break;
        case self::SERVICIO:
          $dite = self::itemgetById($datos['iteid']);
          $stmt->bindValue(':iteid', $datos['iteid'], PDO::PARAM_STR);
          $stmt->bindValue(':prcvta', $dite['prcvta'], PDO::PARAM_STR);
          $stmt->bindValue(':cantid', '1', PDO::PARAM_STR);
          break;
      endswitch;
      $stmt->execute();
      $dbcon = \GenFunc::dbConnect();
      $ordlog = new Ordlog($dbcon);
      $ordlog->insert($datos['ordid'], $datos['tipdet']);
      $this->db->commit();
      return true;
    }
    catch (PDOException $e) {
      $this->db->rollBack();
      error_log($e->getMessage());
      throw new AppException(ErrCod::E170, 500, $e);
    }
    catch (Exception $e) {
      $this->db->rollBack();
      error_log($e->getMessage());
      throw new AppException(ErrCod::E171, 500, $e);
    }
    return false;
  }

  private
     function itemgetById(string $id){
    try {
      $sql = "SELECT * FROM items WHERE id = :id";
      $q = $this->db->prepare($sql);
      $q->bindParam(":id", $id, PDO::PARAM_INT);
      $q->execute();
      $row = $q->fetch(PDO::FETCH_ASSOC);
      return $row;
    }
    catch (PDOException $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E170, 500, $e);
    }
    catch (Exception $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E171, 500, $e);
    }
  }

}
