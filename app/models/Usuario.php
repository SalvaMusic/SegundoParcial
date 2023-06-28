<?php

class Usuario
{
    public $id;    
    public $email;
    public $clave;
    public $tipo;

    public function guardarUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        if($this->id == null){
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (email, clave, tipo) VALUES (:email, :clave, :tipo)");
        } else {
            $query = "UPDATE usuario SET                 
                email = :email,
                tipo = :tipo,
                clave = :clave
                WHERE id = :id";
            $consulta = $objAccesoDatos->prepararConsulta($query);
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        }

        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $this->id != null ? $this->id : $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $usuario = $consulta->fetchObject('Usuario');
        return $usuario;
    }

    public static function obtenerUsuarioPorEmail($email)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE email = :email");
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->execute();

        $usuario = $consulta->fetchObject('Usuario');
        return $usuario;
    }

   /* public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuario SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }*/
}