<?php

class Log
{
    public $id;
    public $usuarioId;
    public $armaId;
    public $accion;
    public $fecha;

    public function guardar()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logs (usuarioId, armaId, accion, fecha) VALUES (:usuarioId, :armaId, :accion, :fecha)");

        $consulta->bindValue(':usuarioId', $this->usuarioId, PDO::PARAM_STR);
        $consulta->bindValue(':armaId', $this->armaId);
        $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM logs");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public function borrar()
    {
        var_dump($this);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM logs WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        var_dump($consulta->execute());

    }
}