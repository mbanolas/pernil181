<?php

$hoja = 0;
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);
//name the worksheet

$this->excel->getActiveSheet()->setTitle("Cód bascula stocks 0 o menor");
$this->excel->getActiveSheet()->setCellValue('A1', "Códigos de báscula que, en total, tienen stock 0 ó negativo");

$fila = 3;
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
$this->excel->getActiveSheet()->getStyle('A' . $fila)->getFont()->setBold(true);

$this->excel->getActiveSheet()->getStyle('A' . $fila . ':H' . $fila)->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

$this->excel->getActiveSheet()->setCellValue('A' . $fila, "Código báscula");
$this->excel->getActiveSheet()->setCellValue('B' . $fila, "Código producto");
$this->excel->getActiveSheet()->setCellValue('C' . $fila, "Nombre");
$this->excel->getActiveSheet()->setCellValue('D' . $fila, "Tipo Unidad");
$this->excel->getActiveSheet()->setCellValue('E' . $fila, "Cantidad stock");
$this->excel->getActiveSheet()->setCellValue('F' . $fila, "Catalogado");
$this->excel->getActiveSheet()->setCellValue('G' . $fila, "Control stock");
$this->excel->getActiveSheet()->setCellValue('H' . $fila, "Total Código báscula");


$fila = 4;
$id_productoAnterior=0;
$letra=true;
$color='c3fdff';
$totales=array();
foreach ($productos as $k => $v) {
    $this->excel->getActiveSheet()->getStyle('A' . $fila . ':H' . $fila)->getFont()->setSize(12);
    if($id_productoAnterior===0) $id_productoAnterior=$v['id_producto'];

    if($v['id_producto']!=$id_productoAnterior){
        if($color==="c3fdff") $color="ffffb3";
        else $color="c3fdff";
        //$this->excel->getActiveSheet()->getStyle('A' . $fila . ':G' . $fila)->getFont()->setBold($letra);
        
        $id_productoAnterior=$v['id_producto'];
        $letra=!$letra;
        
    }
    else{

    }
    $this->excel->getActiveSheet()->getStyle('A' . $fila . ':H' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);;

    $id_productoAnterior=$v['id_producto'];
    $this->excel->getActiveSheet()->setCellValue('A' . $fila, $v['id_producto']);
    $this->excel->getActiveSheet()->setCellValue('B' . $fila, $v['codigo_producto']);
    $this->excel->getActiveSheet()->setCellValue('C' . $fila, $v['nombre']);
    $this->excel->getActiveSheet()->setCellValue('D' . $fila, $v['tipo_unidad']);
    $this->excel->getActiveSheet()->getStyle('D'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    
    if($v['tipo_unidad']='Und') $v['cantidad']=intval($v['cantidad']);
    if($v['tipo_unidad']='Kg') $v['cantidad']=($v['cantidad']/1000);
    $this->excel->getActiveSheet()->setCellValue('E' . $fila, $v['cantidad']);
    
    if($v['status_producto']) $status_producto='Sí'; else $status_producto='No';
    $this->excel->getActiveSheet()->setCellValue('F' . $fila, $status_producto);
    $this->excel->getActiveSheet()->getStyle('F'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('B'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $this->excel->getActiveSheet()->setCellValue('G' . $fila, $v['control_stock']);
    $this->excel->getActiveSheet()->getStyle('G'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $totales[$v['id_producto']]=array('linea'=>$fila,'total'=>isset($totales[$v['id_producto']]['total'])?$totales[$v['id_producto']]['total']+=$v['cantidad']:$totales[$v['id_producto']]['total']=$v['cantidad']);

    $fila++;
}
foreach ($productos as $k => $v) {
    $this->excel->getActiveSheet()->setCellValue('H' . $totales[$v['id_producto']]['linea'], $totales[$v['id_producto']]['total']);
    $this->excel->getActiveSheet()->getStyle('H'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
}

$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);


$this->excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$range = 'A1:A'.$fila;
$this->excel->getActiveSheet()
    ->getStyle($range)
    ->getNumberFormat()
    ->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
$this->excel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);




$filename = 'Cód báscula stock 0 o menor.xls'; //save our workbook as this file name
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
