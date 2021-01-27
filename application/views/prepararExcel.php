<?php
$hoja = 0;
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);
//name the worksheet
$this->excel->getActiveSheet()->setTitle(substr("Listado Stocks", 0, 30));
$this->excel->getActiveSheet()->setCellValue('A1', "STOCKS TOTALES (productos activos con control stocks)");
$this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
$hoy = date('d/m/Y');
$this->excel->getActiveSheet()->setCellValue('A2', "Fecha: $hoy");




$l=5;
$this->excel->getActiveSheet()->getCell("A".$l)->setValueExplicit('Código 13', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("B".$l)->setValueExplicit('C. Báscula', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("c".$l)->setValueExplicit('Producto', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("d".$l)->setValueExplicit('Grupo', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("e".$l)->setValueExplicit('Familia', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("f".$l)->setValueExplicit('Proveedor', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("g".$l)->setValueExplicit('Cantidad-', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("h".$l)->setValueExplicit('Fecha modificación', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("i".$l)->setValueExplicit('Control Stock', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("j".$l)->setValueExplicit('Valoración Stock', PHPExcel_Cell_DataType::TYPE_STRING);


$this->excel->getActiveSheet()->getCell("A".($l+1))->setValueExplicit($codigo_producto, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("B".($l+1))->setValueExplicit($id_producto, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("C".($l+1))->setValueExplicit($producto, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("F".($l+1))->setValueExplicit($proveedor, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("G".($l+1))->setValueExplicit($stock, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("H".($l+1))->setValueExplicit($fecha, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("J".($l+1))->setValueExplicit($valor, PHPExcel_Cell_DataType::TYPE_STRING);

$this->excel->getActiveSheet()->getStyle("A".($l+1).":J".($l+1))->getFont()->setItalic(true);

$sumaValores=0;
foreach ($result as $k => $v) {
    $this->excel->getActiveSheet()->getCell("A" . ($k + $l+2))->setValueExplicit($v->codigo_producto, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("B" . ($k + $l+2))->setValueExplicit($v->id_producto, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("c" . ($k + $l+2))->setValueExplicit($v->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("d" . ($k + $l+2))->setValueExplicit($v->nombre_grupo, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("e" . ($k + $l+2))->setValueExplicit($v->nombre_familia, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("f" . ($k + $l+2))->setValueExplicit($v->nombre_proveedor, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("g" . ($k + $l+2))->setValueExplicit(intval($v->cantidad), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("h" . ($k + $l+2))->setValueExplicit(fechaEuropea($v->fecha_modificacion_stock), PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("i" . ($k + $l+2))->setValueExplicit($v->control_stock, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("j" . ($k + $l+2))->setValueExplicit($v->valoracion, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getStyle("j" . ($k + $l+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sumaValores+=$v->valoracion;
}
$filaTotal=$k + $l+3 +1;
$this->excel->getActiveSheet()->getCell("j" . $filaTotal)->setValueExplicit($sumaValores, PHPExcel_Cell_DataType::TYPE_NUMERIC);
$this->excel->getActiveSheet()->getStyle("j" . $filaTotal)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$this->excel->getActiveSheet()->getCell("a" . $filaTotal)->setValueExplicit('TOTAL', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getStyle("A". $filaTotal.":J".$filaTotal)->getFont()->setBold(true);




$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(80);
$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(60);
$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

$style = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    )
);

$this->excel->getActiveSheet()->getStyle("H1:H".($k + $l+2))->applyFromArray($style);
$this->excel->getActiveSheet()->getStyle("I1:I".($k + $l+2))->applyFromArray($style);



$this->excel->removeSheetByIndex(-1);
$filename = "Stocks.xls";
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache

//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');


//force user to download the Excel file without writing it to server's HD
//
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
$objWriter->save('php://output');
