<?php

class Venta
{
    public $id;
    public $cantidad;
    public $fecha;
    public $armaId;
    public $usuarioId;
    public $foto;
    
    public function guardar()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO venta (cantidad, fecha, armaId, usuarioId, foto) VALUES (:cantidad, :fecha, :armaId, :usuarioId :foto)");
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha);
        $consulta->bindValue(':armaId', $this->armaId, PDO::PARAM_INT);
        $consulta->bindValue(':usuarioId', $this->usuarioId, PDO::PARAM_INT);
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

    public static function obtenerTodosPaisFecha($pais, $fechaInicio, $fechaFin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT v.* FROM venta as v 
                                                        JOIN armas as a on a.id = v.armaId
                                                        WHERE v.fecha >= :fechaInicio AND v.fecha <= :fechaFin
                                                        AND a.nacionalidad = :nacionalidad");
        $consulta->bindValue(':fechaInicio', $fechaInicio);
        $consulta->bindValue(':fechaFin', $fechaFin);
        $consulta->bindValue(':nacionalidad', $pais);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

}