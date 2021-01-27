<?php



$hoja = 0;
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);
//name the worksheet
$this->excel->getActiveSheet()->setTitle(substr("Uso_y_destino", 0, 30));
$this->excel->getActiveSheet()->setCellValue('B5', "ADUANA DE BARCELONA");
$this->excel->getActiveSheet()->getStyle("B5")->getFont()->setBold(true);

$fecha = $_POST['fecha'];
switch (substr($fecha, 3, 2)) {
    case '01':
        $mes = "Enero";
        break;
    case '02':
        $mes = "Febrero";
        break;
    case '03':
        $mes = "Marzo";
        break;
    case '04':
        $mes = "Abril";
        break;
    case '05':
        $mes = "Mayo";
        break;
    case '06':
        $mes = "Junio";
        break;
    case '07':
        $mes = "Julio";
        break;
    case '08':
        $mes = "Agosto";
        break;
    case '09':
        $mes = "Septiembre";
        break;
    case '10':
        $mes = "Octubre";
        break;
    case '11':
        $mes = "Noviembre";
        break;
    case '12':
        $mes = "Diciembre";
        break;
}
$dia = substr($fecha, 0, 1) == '0' ? substr($fecha, 1, 1) : substr($fecha, 0, 2);
$factura = $_POST['factura'];
$fechaFactura = fechaEuropea($pedido->fecha);
$this->excel->getActiveSheet()->setCellValue('B11', "Barcelona, " . $dia . " de " . $mes . " " . substr($fecha, 6, 4));
$lineaCuerpo = 16;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "ATTN. ILUSTRISIMO SR. ADMINISTRADOR DE LA ADUANA DE BARCELONA");
$lineaCuerpo++;
$lineaCuerpo++;

$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "Le informamos que el material bajo factura nº $factura de fecha $fechaFactura,");
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "tiene como destino final:");
$lineaCuerpo++;
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", $pedido->firstname . ' ' . $pedido->lastname);
$lineaCuerpo++;
$direccion=$pedido->delivery_address_line_1==''?"----":$pedido->delivery_address_line_1;

$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", $direccion);
$lineaCuerpo++;
if ($pedido->delivery_address_line_2!='') {
    $this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", $pedido->delivery_address_line_2);
    $lineaCuerpo++;
}
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", $pedido->delivery_postcode . ' ' . $pedido->delivery_city);
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "Suiza");
$lineaCuerpo++;
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "Esta mercancía será utilizada para consumo personal.");
$lineaCuerpo++;
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "Sin otro particular, le saludamos muy atentamente,");
$lineaCuerpo++;
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "NOMBRE: Alex Torguet Noguera");
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "CARGO: Responsable Logística");
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "DNI: 46751374X");

$lineaCuerpo++;
$this->exceldrawing->setName('Pernil181');
$this->exceldrawing->setDescription('Pernil181');
$logo = 'images/sello_y_firma_alex.png'; // Provide path to your logo file
$this->exceldrawing->setPath($logo);  //setOffsetY has no effect
$this->exceldrawing->setCoordinates("E$lineaCuerpo");
$this->exceldrawing->setHeight(120); // logo height
$this->exceldrawing->setWorksheet($this->excel->getActiveSheet());

$lineaCuerpo++;
$lineaCuerpo++;
$lineaCuerpo++;
$lineaCuerpo++;
$lineaCuerpo++;
$lineaCuerpo++;

$this->excel->getActiveSheet()->setCellValue("F$lineaCuerpo", "FIRMA Y SELLO");


/*
$l=5;

$this->excel->getActiveSheet()->getCell("A".$l)->setValueExplicit('Núm', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("b".$l)->setValueExplicit('Código 13', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("c".$l)->setValueExplicit('C. Báscula', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("d".$l)->setValueExplicit('Producto', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("e".$l)->setValueExplicit('Peso (Kg)', PHPExcel_Cell_DataType::TYPE_STRING); 
$this->excel->getActiveSheet()->getCell("f".$l)->setValueExplicit('Grupo', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("g".$l)->setValueExplicit('Familia', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("h".$l)->setValueExplicit('Precio compra (€)', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("i".$l)->setValueExplicit('Tipo unidad', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("j".$l)->setValueExplicit('Proveedor', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("k".$l)->setValueExplicit('Tarifa venta (€)', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("l".$l)->setValueExplicit('Control stock', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("m".$l)->setValueExplicit('Stock total', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("n".$l)->setValueExplicit('Valoracion', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("o".$l)->setValueExplicit('Margen (%)', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("p".$l)->setValueExplicit('url_imagen_portada', PHPExcel_Cell_DataType::TYPE_STRING);

foreach ($result as $k => $v) {
    $this->excel->getActiveSheet()->getCell("A". ($k + $l+2))->setValueExplicit($v->id, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("b". ($k + $l+2))->setValueExplicit($v->codigo_producto, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("c". ($k + $l+2))->setValueExplicit($v->id_producto, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("d". ($k + $l+2))->setValueExplicit($v->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("e". ($k + $l+2))->setValueExplicit($v->peso_real, PHPExcel_Cell_DataType::TYPE_NUMERIC); 
    $this->excel->getActiveSheet()->getCell("f". ($k + $l+2))->setValueExplicit($v->nombre_grupo, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("g". ($k + $l+2))->setValueExplicit($v->nombre_familia, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("h". ($k + $l+2))->setValueExplicit($v->precio_compra, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("i". ($k + $l+2))->setValueExplicit($v->tipo_unidad, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("j". ($k + $l+2))->setValueExplicit($v->nombre_proveedor, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("k". ($k + $l+2))->setValueExplicit($v->tarifa_venta, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("l". ($k + $l+2))->setValueExplicit($v->control_stock, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("m". ($k + $l+2))->setValueExplicit($v->stock_total, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("n". ($k + $l+2))->setValueExplicit($v->valoracion, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("o". ($k + $l+2))->setValueExplicit($v->margen, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("p". ($k + $l+2))->setValueExplicit($v->url_imagen_portada, PHPExcel_Cell_DataType::TYPE_STRING);
    


}    


$this->excel->getActiveSheet()->getColumnDimension("A")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("b")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("c")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("d")->setWidth(80);
$this->excel->getActiveSheet()->getColumnDimension("e")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("f")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("g")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("h")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("i")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("j")->setWidth(80);
$this->excel->getActiveSheet()->getColumnDimension("k")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("l")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("m")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("n")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("o")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("p")->setWidth(15);
    

$this->excel->getActiveSheet()->getCell("b".($l+1))->setValueExplicit($codigo_producto, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("c".($l+1))->setValueExplicit($id_producto, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("d".($l+1))->setValueExplicit($producto, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("e".($l+1))->setValueExplicit($peso_real, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("i".($l+1))->setValueExplicit($tipo_unidad, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("j".($l+1))->setValueExplicit($proveedor, PHPExcel_Cell_DataType::TYPE_STRING);

$this->excel->getActiveSheet()->getStyle("A".($l+1).":p".($l+1))->getFont()->setItalic(true);










$this->excel->removeSheetByIndex(-1);
*/
$filename = "Uso_y_destino.xls";
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
?>
<!-- <script>
 $(document).ready(function() {
    <?php if(!isset($pedido->id)){ ?>
    $('.modal-title').html('Información');
    $('#myModal').show()


 <?php } ?> 

 })
</script> -->
