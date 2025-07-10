<?php

/* Modelo servicio */
class Servicio{

  protected
     $db;
  public
     $id;
  public
     $nombre;
  public
     $refere;
  public
     $prcvta;
  public
     $estado;

  public
     function __construct(PDO $db){
    $this->db = $db;
  }

//    private function read($row) {
//        $result = new Cliente();
//        $result->id = $row["id"];
//        $result->nombre = $row["nombre"];
//        $result->correo = $row["correo"];
//        $result->direcc = $row["direcc"];
//        $result->telefono = $row["telefono"];
//        $result->estado = $row["estado"];
//        return $result;
//    }

  /*
   * Obtiene los datos de un servicio por id
   * param $id string  Id del Servicio
   */

  public
     function getById(string $id){
    try {
      $sql = "SELECT * FROM items WHERE id = :id";
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
      $sql = "SELECT count(id) FROM items WHERE estado = 0 and tipo = 1";
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
     function getAll(){
    try {
      error_log('servicio getall');
      $sql = "SELECT * FROM items where estado=0 and tipo=1";
      $stmt = $this->db->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
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
    error_log('insert servicio');
    try {
      $sql = "INSERT INTO items (tipo,nombre,refere,prcvta,estado)"
         . " VALUES ('1', :nombre, :refere, :prcvta, :estado)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':nombre', $datos['nombre'], PDO::PARAM_STR);
      $stmt->bindValue(':refere', $datos['refere'], PDO::PARAM_STR);
      $stmt->bindValue(':prcvta', $datos['prcvta'], PDO::PARAM_STR);
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
     function update($datos){
    error_log('update servicio');
    try {
      $sql = "UPDATE items SET "
         . "nombre = :nombre,"
         . "refere = :refere,"
         . "prcvta = :prcvta"
         . " WHERE id = :id";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':id', $datos['id'], PDO::PARAM_STR);
      $stmt->bindValue(':nombre', $datos['nombre'], PDO::PARAM_STR);
      $stmt->bindValue(':refere', $datos['refere'], PDO::PARAM_STR);
      $stmt->bindValue(':prcvta', $datos['prcvta'], PDO::PARAM_STR);
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
     function remove($id){
    error_log('remove servicio');
    try {
      $sql = "UPDATE items SET estado = :estado"
         . " WHERE id = :id";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_STR);
      $stmt->bindValue(':estado', '9', PDO::PARAM_STR);
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

}
