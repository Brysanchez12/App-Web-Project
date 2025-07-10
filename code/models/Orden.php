<?php

/**
 * Description of Orden
 *
 * @author Galo Izquierdo
 */
class Orden{

  protected
     $db;

  const
     ACTIVO = 0;
  const
     FACTURADO = 5;
  const
     CERRADO = 3;

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
      $sql = "SELECT * FROM orden WHERE id = :id";
      $q = $this->db->prepare($sql);
      $q->bindParam(":id", $id, PDO::PARAM_INT);
      $q->execute();
      $rows = $q->fetch(PDO::FETCH_ASSOC);
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
     function getCount(){
    try {
      $sql = "SELECT count(id) FROM orden";
      $q = $this->db->prepare($sql);
      $q->execute();
      $count = $q->fetchColumn(); // devuelve directamente el número
      return $count;
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
     function getAll($estado){
    try {
      error_log('ordenes abiertas getall');
      $sql = "SELECT orden.*, cliente.nombre as clinom, tecnico.nombre as tecnom FROM orden"
         . " left join cliente on orden.codcli=cliente.id"
         . " left join tecnico on orden.codtec=tecnico.id"
         . " where orden.  estado=:estado";
      $q = $this->db->prepare($sql);
      $q->bindParam(":estado", $estado, PDO::PARAM_INT);
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
     function getOrdenByTec($codtec){
    try {
      error_log('ordenes abiertas de un tecnico especifico');
      $sql = "SELECT * FROM orden where estado=0 and codtec=:codtec";
      $q = $this->db->prepare($sql);
      $q->bindParam(":codtec", $codtec, PDO::PARAM_INT);
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
    error_log('insert orden');
    try {
      $sql = "INSERT INTO orden (codcli,codtec,marca,modelo,imei,observ,fecing,estado)"
         . " VALUES (:codcli, :codtec, :marca, :modelo, :imei, :observ, :fecing, :estado)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':codcli', $datos['clicod'], PDO::PARAM_STR);
      $stmt->bindValue(':codtec', $datos['teccod'], PDO::PARAM_STR);
      $stmt->bindValue(':marca', $datos['marca'], PDO::PARAM_STR);
      $stmt->bindValue(':modelo', $datos['modelo'], PDO::PARAM_STR);
      $stmt->bindValue(':imei', $datos['imei'], PDO::PARAM_STR);
      $stmt->bindValue(':observ', $datos['observ'], PDO::PARAM_STR);
      $stmt->bindValue(':fecing', $datos['fecha'], PDO::PARAM_STR);
      $stmt->bindValue(':estado', '0', PDO::PARAM_STR);
      $stmt->execute();
      return true;
    }
    catch (PDOException $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E170, 500, $e);
    }
    catch (Exception $e) {
      error_log($e->getMessage());
      throw new AppException(ErrCod::E171, 500, $e);
    }
    return false;
  }

  public
     function cerrar($id){
    try {
      $sql = "UPDATE orden SET estado = :estado WHERE id = :id";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':estado', self::CERRADO, PDO::PARAM_INT);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->rowCount() > 0;
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
