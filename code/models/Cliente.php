<?php

/* Modelo cliente */
class Cliente{

  protected
     $db;
  public
     $id;
  public
     $nombre;
  public
     $direcc;
  public
     $correo;
  public
     $telefono;
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
   * Obtiene los datos de un cliente por id
   * param $id string  Id del Cliente
   */

  public
     function getById(string $id){
    try {
      $sql = "SELECT * FROM cliente WHERE id = :id";
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
      $sql = "SELECT count(id) FROM cliente WHERE estado = 0";
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
     function getSelectOptions(){
    try {
      $sql = "SELECT id, nombre FROM cliente where estado = 0 ORDER BY nombre";
      $stmt = $this->db->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
      error_log('clientes getall' . Errcod::E103);
      $sql = "SELECT * FROM cliente where estado=0";
      $q = $this->db->prepare($sql);
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
    error_log('insert cliente');
    try {
      $sql = "INSERT INTO cliente (id,nombre,correo,direcc,telefono,estado)"
         . " VALUES (:id, :nombre, :correo, :direcc, :telefono, :estado)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':id', $datos['id'], PDO::PARAM_STR);
      $stmt->bindValue(':nombre', $datos['nombre'], PDO::PARAM_STR);
      $stmt->bindValue(':correo', $datos['correo'], PDO::PARAM_STR);
      $stmt->bindValue(':direcc', $datos['direcc'], PDO::PARAM_STR);
      $stmt->bindValue(':telefono', $datos['telefono'], PDO::PARAM_STR);
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
    error_log('update cliente');
    try {
      $sql = "UPDATE cliente SET "
         . "nombre = :nombre,"
         . "correo = :correo,"
         . "direcc = :direcc,"
         . "telefono = :telefono"
         . " WHERE id = :id";
      $stmt = $this->db->prepare($sql);
      error_log($datos['id']);
      $stmt->bindValue(':id', $datos['id'], PDO::PARAM_STR);
      $stmt->bindValue(':nombre', $datos['nombre'], PDO::PARAM_STR);
      $stmt->bindValue(':correo', $datos['correo'], PDO::PARAM_STR);
      $stmt->bindValue(':direcc', $datos['direcc'], PDO::PARAM_STR);
      $stmt->bindValue(':telefono', $datos['telefono'], PDO::PARAM_STR);
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
    error_log('remove cliente');
    try {
      $sql = "UPDATE cliente SET estado = :estado"
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
