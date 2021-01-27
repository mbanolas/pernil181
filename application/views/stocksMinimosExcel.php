<?php
//$lineas
$this->load->library('excel');
$hoja=0;
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);
//name the worksheet
$this->excel->getActiveSheet()->setTitle("Ventas mensuales");
$this->excel->getActiveSheet()->setCellValue('A1', "Ventas mensuales últimos 12 meses y promedio mensual (stock mínimo)");
$this->excel->getActiveSheet()->setCellValue('A2', date('d/m/Y'));
$columnas=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O');
$row=4;
$this->excel->getActiveSheet()->getStyle('A'.$row.':O'.$row)->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle('C3:O'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
foreach ($lineas['Código 13'] as $k1 => $v1) {
            $this->excel->getActiveSheet()->setCellValue($columnas[$k1].$row, $v1);
        }
$row++;
foreach($lineas as $k=>$v){
    if($k==0) continue;
    foreach($v as $k1=>$v1){
        $this->excel->getActiveSheet()->setCellValue($columnas[$k1].$row, $v1);
    }
    $row++;
}
$row++;
//$this->excel->getActiveSheet()->setCellValue('A3', "Periodo: ".$textoPeriodo);
//$this->excel->getActiveSheet()->setCellValue('A4', $nombreTipoTaller);

//change the font size
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);

//$this->excel->getActiveSheet()->getStyle('A3:A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$this->excel->getActiveSheet()->getStyle('B3:B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//
$this->excel->getActiveSheet()->getStyle('A4:A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);


$filename='Ventas productos mensuales.xls'; //save our workbook as this file name
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
            
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  


//force user to download the Excel file without writing it to server's HD
//
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
$objWriter->save('php://output');