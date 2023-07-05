<?php

require_once './models/Venta.php';
require_once '../vendor/tecnickcom/tcpdf/tcpdf.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PDFController
{
    public function generarPDF(Request $request, Response $response, $args)
    {

        $asc = $args['asc'];

        $asc = $asc == 'asc' ? true : false;

        $array = Venta::obtenerUltimoMes($asc);

        // Crear una instancia de TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

        // Agregar una página
        $pdf->AddPage();

        // Configurar algunas propiedades del documento
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Crear una tabla para mostrar los datos de la lista de objetos
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetFillColor(255, 255, 255);

        // Cabecera de la tabla
        $pdf->Cell(30, 10, 'VENTA ID', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'CANTIDAD', 1, 0, 'C', 1);
        $pdf->Cell(40, 10, 'FECHA', 1, 0, 'C', 1);
        $pdf->Cell(45, 10, 'ARMA', 1, 0, 'C', 1);
        $pdf->Cell(45, 10, 'USUARIO', 1, 1, 'C', 1);

        // Filas de la tabla
        foreach ($array as $fila) {
            $pdf->Cell(30, 10, $fila['id'], 1, 0, 'C', 1);
            $pdf->Cell(30, 10, $fila['cantidad'], 1, 0, 'C', 1);
            $pdf->Cell(40, 10, $fila['fecha'], 1, 0, 'C', 1);
            $pdf->Cell(45, 10, $fila['arma'], 1, 0, 'C', 1);
            $pdf->Cell(45, 10, $fila['usuario'], 1, 1, 'C', 1);
        }

        // Generar el archivo PDF
        $pdf->Output('VentasUltimoMes.pdf', 'D');

        $response->withHeader('Content-Disposition', 'attachment; filename="archivo.pdf"');
        $response->getBody()->write($pdf->output());

        // Retornar la respuesta del controlador
        return $response;
    }
}
