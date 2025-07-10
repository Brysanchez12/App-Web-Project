<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
/**
 * Description of Factura
 *
 * @author Galo Izquierdo
 */
class Factura{

  protected
     $db;

  public
     function __construct(PDO $db){
    $this->db = $db;
  }

  public
     function getById(int $id){
    try {
      $sql = "SELECT factura.totbbru, factura.totiva, factura.totnet,"
         . "factura.obsini, factura.obsfin, factura.fecemi,"
         . "orden.* cliente.nombre as clinom, tecnico.nombre as tecnom FROM factura"
         . " left join orden on factura.ordid=orden.id"
         . " left join cliente on factura.codcli=cliente.id"
         . " left join tecnico on factura.codtec=tecnico.id"
         . " where factura.estado=:estado";
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
     function getAll($estado){
    try {
      error_log('facturas getall');
      $sql = "SELECT factura.*, cliente.nombre as clinom, tecnico.nombre as tecnom FROM factura"
         . " left join cliente on factura.codcli=cliente.id"
         . " left join tecnico on factura.codtec=tecnico.id"
         . " where factura.estado=:estado";
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
     function insert(int $ordid){

    try {
      // Inicia transacción
      $this->db->beginTransaction();
      // Obtener detalles de la orden para calcular totales
      $sql = "SELECT iteid, prcvta, cantid FROM orddet WHERE ordid = :ordid AND estado = 0"; // Solo detalles activos
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':ordid', $ordid, PDO::PARAM_INT);
      $stmt->execute();
      $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (!$detalles) {
        throw new Exception("No se encontraron detalles activos para la orden $ordid");
      }

      // Calcular totales
      $subtotal = 0;
      foreach ($detalles as $item) {
        $subtotal += $item['prcvta'] * $item['cantid'];
      }
      $iva = $subtotal * 0.15;
      $total = $subtotal + $iva;
      // Obtener datos cabecera de la orden para factura
      $sql = "SELECT codcli, codtec FROM orden WHERE id = :ordid";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':ordid', $ordid, PDO::PARAM_INT);
      $stmt->execute();
      $ordenCab = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$ordenCab) {
        throw new Exception("Orden no encontrada: $ordid");
      }

      // Insertar factura
      $sql = "INSERT INTO factura (codcli, ordid, codtec, totbru, totiva, totnet, estado, fecemi)
              VALUES (:codcli, :ordid, :codtec, :totbru, :totiva, :totnet, 0, NOW())";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([
         ':codcli' => $ordenCab['codcli'],
         ':ordid' => $ordid,
         ':codtec' => $ordenCab['codtec'],
         ':totbru' => $subtotal,
         ':totiva' => $iva,
         ':totnet' => $total,
      ]);
      $facturaId = $this->db->lastInsertId();
      // Insertar detalles factura
      $sqlDet = "INSERT INTO facdet (facid, iteid, cantid, prcvta, totnet)"
         . " VALUES (:facid, :iteid, :cantid, :prcvta, :totnet)";
      $stmtDet = $this->db->prepare($sqlDet);
      foreach ($detalles as $item) {
        $totnet = $item['prcvta'] * $item['cantid'];
        $stmtDet->execute([
           ':facid' => $facturaId,
           ':iteid' => $item['iteid'],
           ':cantid' => $item['cantid'],
           ':prcvta' => $item['prcvta'],
           ':totnet' => $totnet,
        ]);
      }

      // Opcional: cambiar estado de orden a facturada (ejemplo)
      $sqlUpd = "UPDATE orden SET estado = 9, fecfac = NOW() WHERE id = :ordid";
      $stmtUpd = $this->db->prepare($sqlUpd);
      $stmtUpd->execute([':ordid' => $ordid]);
      $this->db->commit();
      GenFunc::sendJsonResponse(['data' => [
            'facturaId' => $facturaId,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total]]);
    }
    catch (Exception $e) {
      $this->db->rollBack();
      GenFunc::sendJsonResponse(['code' => 500, 'msg' => 'Error al generar factura: ' . $e->getMessage()]);
    }
  }

}
