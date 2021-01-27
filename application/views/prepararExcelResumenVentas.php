<?php
$this->load->library('excel');

$hoja = 0;
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);
//name the worksheet
$this->excel->getActiveSheet()->setTitle(substr("Resumen Ventas", 0, 30));
$this->excel->getActiveSheet()->setCellValue('A1', $tituloCabecera);
$this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
// $hoy = date('d/m/Y');
// $this->excel->getActiveSheet()->setCellValue('A2', "Fecha: $hoy");
$encabezados=json_decode($titulares);

$l=5;


$this->excel->getActiveSheet()->getCell("A".$l)->setValueExplicit($encabezados[0], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("b".$l)->setValueExplicit($encabezados[1], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("c".$l)->setValueExplicit($encabezados[2], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("d".$l)->setValueExplicit($encabezados[3], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("e".$l)->setValueExplicit($encabezados[4], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("f".$l)->setValueExplicit($encabezados[5], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("g".$l)->setValueExplicit($encabezados[6], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("h".$l)->setValueExplicit($encabezados[7], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("i".$l)->setValueExplicit($encabezados[8], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("j".$l)->setValueExplicit($encabezados[9], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("k".$l)->setValueExplicit($encabezados[10], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("l".$l)->setValueExplicit($encabezados[11], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("m".$l)->setValueExplicit($encabezados[12], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("n".$l)->setValueExplicit($encabezados[13], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("o".$l)->setValueExplicit($encabezados[14], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("p".$l)->setValueExplicit($encabezados[15], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("q".$l)->setValueExplicit($encabezados[16], PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("r".$l)->setValueExplicit($encabezados[17], PHPExcel_Cell_DataType::TYPE_STRING);

// foreach ($result as $k => $v) {
//     $this->excel->getActiveSheet()->getCell("A". ($k + $l+2))->setValueExplicit($v->id, PHPExcel_Cell_DataType::TYPE_STRING);
//     $this->excel->getActiveSheet()->getCell("b". ($k + $l+2))->setValueExplicit($v->codigo_producto, PHPExcel_Cell_DataType::TYPE_STRING);
//     $this->excel->getActiveSheet()->getCell("c". ($k + $l+2))->setValueExplicit($v->id_producto, PHPExcel_Cell_DataType::TYPE_STRING);
//     $this->excel->getActiveSheet()->getCell("d". ($k + $l+2))->setValueExplicit($v->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
//     $this->excel->getActiveSheet()->getCell("e". ($k + $l+2))->setValueExplicit($v->peso_real, PHPExcel_Cell_DataType::TYPE_NUMERIC); 
//     $this->excel->getActiveSheet()->getCell("f". ($k + $l+2))->setValueExplicit($v->nombre_grupo, PHPExcel_Cell_DataType::TYPE_STRING);
//     $this->excel->getActiveSheet()->getCell("g". ($k + $l+2))->setValueExplicit($v->nombre_familia, PHPExcel_Cell_DataType::TYPE_STRING);
//     $this->excel->getActiveSheet()->getCell("h". ($k + $l+2))->setValueExplicit($v->precio_compra, PHPExcel_Cell_DataType::TYPE_NUMERIC);
//     $this->excel->getActiveSheet()->getCell("i". ($k + $l+2))->setValueExplicit($v->tipo_unidad, PHPExcel_Cell_DataType::TYPE_STRING);
//     $this->excel->getActiveSheet()->getCell("j". ($k + $l+2))->setValueExplicit($v->nombre_proveedor, PHPExcel_Cell_DataType::TYPE_STRING);
//     $this->excel->getActiveSheet()->getCell("k". ($k + $l+2))->setValueExplicit($v->tarifa_venta, PHPExcel_Cell_DataType::TYPE_NUMERIC);
//     $this->excel->getActiveSheet()->getCell("l". ($k + $l+2))->setValueExplicit($v->control_stock, PHPExcel_Cell_DataType::TYPE_STRING);
//     $this->excel->getActiveSheet()->getCell("m". ($k + $l+2))->setValueExplicit($v->stock_total, PHPExcel_Cell_DataType::TYPE_NUMERIC);
//     $this->excel->getActiveSheet()->getCell("n". ($k + $l+2))->setValueExplicit($v->valoracion, PHPExcel_Cell_DataType::TYPE_NUMERIC);
//     $this->excel->getActiveSheet()->getCell("o". ($k + $l+2))->setValueExplicit($v->margen, PHPExcel_Cell_DataType::TYPE_NUMERIC);
//     $this->excel->getActiveSheet()->getCell("p". ($k + $l+2))->setValueExplicit($v->url_imagen_portada, PHPExcel_Cell_DataType::TYPE_STRING);
// }    


// $this->excel->getActiveSheet()->getColumnDimension("A")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("b")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("c")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("d")->setWidth(80);
// $this->excel->getActiveSheet()->getColumnDimension("e")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("f")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("g")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("h")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("i")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("j")->setWidth(80);
// $this->excel->getActiveSheet()->getColumnDimension("k")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("l")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("m")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("n")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("o")->setWidth(15);
// $this->excel->getActiveSheet()->getColumnDimension("p")->setWidth(15);
    

// $this->excel->getActiveSheet()->getCell("b".($l+1))->setValueExplicit($codigo_producto, PHPExcel_Cell_DataType::TYPE_STRING);
// $this->excel->getActiveSheet()->getCell("c".($l+1))->setValueExplicit($id_producto, PHPExcel_Cell_DataType::TYPE_STRING);
// $this->excel->getActiveSheet()->getCell("d".($l+1))->setValueExplicit($producto, PHPExcel_Cell_DataType::TYPE_STRING);
// $this->excel->getActiveSheet()->getCell("e".($l+1))->setValueExplicit($peso_real, PHPExcel_Cell_DataType::TYPE_STRING);
// $this->excel->getActiveSheet()->getCell("i".($l+1))->setValueExplicit($tipo_unidad, PHPExcel_Cell_DataType::TYPE_STRING);
// $this->excel->getActiveSheet()->getCell("j".($l+1))->setValueExplicit($proveedor, PHPExcel_Cell_DataType::TYPE_STRING);
// $this->excel->getActiveSheet()->getCell("h".($l+1))->setValueExplicit($precio_compra, PHPExcel_Cell_DataType::TYPE_STRING);
// $this->excel->getActiveSheet()->getCell("k".($l+1))->setValueExplicit($tarifa_venta, PHPExcel_Cell_DataType::TYPE_STRING);
// $this->excel->getActiveSheet()->getCell("m".($l+1))->setValueExplicit($stock_total, PHPExcel_Cell_DataType::TYPE_STRING);
// $this->excel->getActiveSheet()->getCell("n".($l+1))->setValueExplicit($valoracion, PHPExcel_Cell_DataType::TYPE_STRING);

// $this->excel->getActiveSheet()->getStyle("A".($l+1).":p".($l+1))->getFont()->setItalic(true);










$this->excel->removeSheetByIndex(-1);
$filename = "ResumenVentas.xls";
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
