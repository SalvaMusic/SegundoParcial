<?php

class Armas
{
    public $id;
    public $nombre;
    public $precio;
    public $foto;
    public $nacionalidad;

    public function guardar()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        if($this->id == null){
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO armas (nombre, precio, foto, nacionalidad) VALUES (:nombre, :precio, :foto, :nacionalidad)");
        } else {
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE armas SET nombre = :nombre,  precio = :precio, foto = :foto, nacionalidad = :nacionalidad
                                                            WHERE id = :id");
            $consulta->bindValue(':id', $this->id);
        }
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM armas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Armas');
    }

    public static function obtenerPorNacionalidad($nacionalidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM armas WHERE nacionalidad = :nacionalidad");
        $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Armas');
    }

    public static function obtenerArma($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM armas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Armas');
    }

    public static function actualizarfoto($id, $foto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE armas SET foto = :foto WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':foto', $foto, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function borrarArma()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM armas WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        var_dump($consulta->execute());

    }

}