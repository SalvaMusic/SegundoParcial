<?php

class Venta
{
    public $id;
    public $cantidad;
    public $fecha;
    public $foto;
    
    public function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO venta (cantidadId, fecha, foto) VALUES (:cantidadId, :fecha, :foto)");

        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM venta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

   /* public static function obtenerVenta($codVenta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM venta WHERE cod_venta = :codVenta");
        $consulta->bindValue(':codVenta', $codVenta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Venta');
    }*/

}