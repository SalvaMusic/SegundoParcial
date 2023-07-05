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
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO venta (cantidad, fecha, armaId, usuarioId) VALUES (:cantidad, :fecha, :armaId, :usuarioId)");
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha);
        $consulta->bindValue(':armaId', $this->armaId, PDO::PARAM_INT);
        $consulta->bindValue(':usuarioId', $this->usuarioId, PDO::PARAM_INT);
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

    public static function obtenerUltimoMes($asc)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        if($asc){
            $consulta = $objAccesoDatos->prepararConsulta("SELECT v.id, v.cantidad, v.fecha, a.Id as arma, u.email as usuario FROM venta as v 
                                                            LEFT JOIN usuario as u ON u.id = v.usuarioId 
                                                            LEFT JOIN armas as a ON a.id = v.armaId 
                                                            WHERE v.fecha >= :fecha ORDER BY v.fecha asc");
        } else {
            $consulta = $objAccesoDatos->prepararConsulta("SELECT v.id, v.cantidad, v.fecha, a.nombre as arma, u.email as usuario FROM venta as v 
                                                            JOIN usuario as u ON u.id = v.usuarioId 
                                                            JOIN armas as a ON a.id = v.armaId 
                                                            WHERE v.fecha >= :fecha ORDER BY v.fecha desc");  
        }
        $fechaActual = new DateTime(); 
        $fechaHaceUnMes = $fechaActual->modify('-1 month');
        $consulta->bindValue(':fecha', $fechaHaceUnMes->format('Y-m-d'));    
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
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

    public function moverFoto($fileFoto, $nombreArma){
        $rutaDestino = null;
        $tempFileName = $fileFoto["tmp_name"];
        if($tempFileName != null){
            $directorio = './FotosArma2023/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }
            $extension = pathinfo($fileFoto["name"], PATHINFO_EXTENSION);
            $nombreArma = str_replace(' ', '', $nombreArma);
            $nombreArma = str_replace('-', '', $nombreArma);
            $fecha = str_replace('-', '', $this->fecha);            
            $rutaDestino = $directorio . $nombreArma . $fecha .".". $extension;        
            if(move_uploaded_file($tempFileName, $rutaDestino)){
                $this->guardarFoto($rutaDestino);
            }
        }

        return $rutaDestino;
    }

    public function guardarFoto($foto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE venta SET foto = :foto WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':foto', $foto, PDO::PARAM_INT);
        $consulta->execute();
    }

}