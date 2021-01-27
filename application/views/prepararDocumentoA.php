<?php


$factura=$_POST['factura'];
$descuento=0;
$transporte=0;
$hoja = 0;

$colorMarco="DDDDDD";  
$colorMarco="000000";  
$colorMarco="808080";  
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);
$this->excel->getActiveSheet()->getPageSetup()
                               ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$this->excel->getActiveSheet()->getPageMargins()->setTop(0.5);
$this->excel->getActiveSheet()->getPageMargins()->setBottom(0.5);
$this->excel->getActiveSheet()->getPageMargins()->setRight(0.5);
$this->excel->getActiveSheet()->getPageMargins()->setLeft(0.5);

$this->excel->getActiveSheet()->getColumnDimension("A")->setWidth(0);
$this->excel->getActiveSheet()->getColumnDimension("B")->setWidth(8);
$this->excel->getActiveSheet()->getColumnDimension("C")->setWidth(0);
$this->excel->getActiveSheet()->getColumnDimension("G")->setWidth(12);
$this->excel->getActiveSheet()->getColumnDimension("H")->setWidth(8);
$this->excel->getActiveSheet()->getColumnDimension("I")->setWidth(8);
$this->excel->getActiveSheet()->getColumnDimension("J")->setWidth(8);
$this->excel->getActiveSheet()->getColumnDimension("K")->setWidth(4);
$this->excel->getActiveSheet()->getColumnDimension("L")->setWidth(8);
$this->excel->getActiveSheet()->getColumnDimension("M")->setWidth(8);
$this->excel->getActiveSheet()->getColumnDimension("N")->setWidth(8);

//name the worksheet
$this->excel->getActiveSheet()->setTitle(substr("Factura", 0, 30));
$this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
$hoy = date('d/m/Y');

$this->exceldrawing->setName('Pernil181');
$this->exceldrawing->setDescription('Pernil181');
$logo = 'images/jamonarium.png'; // Provide path to your logo file
$this->exceldrawing->setPath($logo);  //setOffsetY has no effect
$this->exceldrawing->setCoordinates("B1");
$this->exceldrawing->setHeight(60); // logo height
$this->exceldrawing->setWorksheet($this->excel->getActiveSheet());

$this->excel->getActiveSheet()->setCellValue("H1", "Factura: $factura")->getStyle("H1")->getFont()->setBold(true)->setSize(16);
$this->excel->getActiveSheet()->setCellValue("B5", "181 Distribucions S.L.")->getStyle("B5")->getFont()->setBold(true)->setSize(14);
$this->excel->getActiveSheet()->setCellValue("B6", "ESB66154964")->getStyle("B6")->getFont()->setSize(14);
$this->excel->getActiveSheet()->setCellValue("B7", "Pg. Sant Joan 181")->getStyle("B7")->getFont()->setSize(14);
$this->excel->getActiveSheet()->setCellValue("B8", "Barcelona")->getStyle("B8")->getFont()->setSize(14);
$this->excel->getActiveSheet()->setCellValue("B9", "España")->getStyle("B9")->getFont()->setSize(14);
$this->excel->getActiveSheet()->setCellValue("B10", "(0034) 931 763 594")->getStyle("B10")->getFont()->setSize(14);
$this->excel->getActiveSheet()->setCellValue("H4", "Facturar a")->getStyle("H4")->getFont()->setBold(true)->setSize(14);
$lineaCuerpo=5;

$this->excel->getActiveSheet()->setCellValue("H$lineaCuerpo", ' '.$pedido->firstname . ' ' . $pedido->lastname);
$lineaCuerpo++;
$direccion=$pedido->delivery_address_line_1==''?" ----":' '.$pedido->delivery_address_line_1;

$this->excel->getActiveSheet()->setCellValue("H$lineaCuerpo", $direccion);
$lineaCuerpo++;
if ($pedido->delivery_address_line_2!='') {
    $this->excel->getActiveSheet()->setCellValue("H$lineaCuerpo", ' '.$pedido->delivery_address_line_2);
    $lineaCuerpo++;
}
$this->excel->getActiveSheet()->setCellValue("H$lineaCuerpo", ' '.$pedido->delivery_postcode . ' ' . $pedido->delivery_city);
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("H$lineaCuerpo", " Suiza");
$lineaCuerpo++;
$this->excel->getActiveSheet()->getStyle("H5:H$lineaCuerpo")->getFont()->setBold(false)->setSize(12);
$this->excel->getActiveSheet()->getStyle("H5:L$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
$this->excel->getActiveSheet()->getStyle("H$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
$this->excel->getActiveSheet()->getStyle("H5:H$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
$this->excel->getActiveSheet()->getStyle("L5:L$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));



$lineaCuerpo++;
$lineaCuerpo++;
$lineaCuerpo++;
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "Fecha");
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(true)->setSize(12);
$this->excel->getActiveSheet()->setCellValue("D$lineaCuerpo", fechaEuropea($datos['fecha']));
$this->excel->getActiveSheet()->getStyle("D$lineaCuerpo")->getFont()->setBold(false)->setSize(12);
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", "Forma de pago: Pago con tarjeta. Pago seguro con la entidad bancaria.");
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(true)->setSize(10);

$lineaCuerpo++;
$lineaCuerpo++;
$intercomunitario=$datos['tipoIvaTransporte']==0?true:false; //ver de forma indirecta si es intercomunitario

if(!$intercomunitario){
    $this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'TARIC');
    $this->excel->getActiveSheet()->setCellValue("D$lineaCuerpo", 'Descripción');
    $this->excel->getActiveSheet()->setCellValue("I$lineaCuerpo", 'Base price');
    $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", 'Qty');
    $this->excel->getActiveSheet()->setCellValue("K$lineaCuerpo", 'Total');
    $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", 'VAT Type');
    $this->excel->getActiveSheet()->setCellValue("M$lineaCuerpo", 'VAT Amount');
    $this->excel->getActiveSheet()->setCellValue("N$lineaCuerpo", 'Total');

}
else{
    $this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'TARIC');
    $this->excel->getActiveSheet()->setCellValue("D$lineaCuerpo", 'Descripción');
    $this->excel->getActiveSheet()->setCellValue("I$lineaCuerpo", 'Base price');
    $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", 'Unit price');
    $this->excel->getActiveSheet()->setCellValue("K$lineaCuerpo", 'Qty');
    $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", 'Total');
    $this->excel->getActiveSheet()->mergeCells("D$lineaCuerpo:H$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("D$lineaCuerpo:H$lineaCuerpo")
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                 
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'DDDDDD'))));
        

    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:B$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
    $this->excel->getActiveSheet()->getStyle("I$lineaCuerpo:I$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
    
    $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:J$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
    $this->excel->getActiveSheet()->getStyle("K$lineaCuerpo:K$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
    $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
        ));
}   
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:N$lineaCuerpo")->getFont()->setBold(true)->setSize(8);
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:N$lineaCuerpo")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$infoIvas=[];
$precioIvas=[];
$totalPrecio=0;

$anulado=false;
//lineas datos productos
for($i=0;$i<count($datos['lineas']);$i++){
    if($datos['lineas'][$i]['valid']==-1) $anulado=true ;
    $lineaCuerpo++;
    $nombre=$datos['lineas'][$i]['nombre'];
    if($datos['lineas'][$i]['esPack']==1)
            $nombre=$nombre." (".$datos['lineas'][$i]['cantidad']." Packs)";
    if($datos['lineas'][$i]['esComponentePack']==1) 
            $nombre="---- ".$nombre;
    //no se pone el código del producto
    $this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", '');
    $this->excel->getActiveSheet()->setCellValue("D$lineaCuerpo", ' '.$nombre);

    $precio=$datos['lineas'][$i]['precio']*$datos['lineas'][$i]['cantidad']/100;
    $basePrecio=$datos['lineas'][$i]['precio']/(100+$datos['lineas'][$i]['tipoIva']/100);
    $baseTotal=$basePrecio*$datos['lineas'][$i]['cantidad'];    
    $tipoIva=$datos['lineas'][$i]['tipoIva']/100;
    $infoIvas[$tipoIva]=$tipoIva;
    $varAmount=$precio-$baseTotal;
    $varAmount=number_format($varAmount*100,2)/100;
    
    if(!isset($precioIvas[$tipoIva])) $precioIvas[$tipoIva]=0;
    $precioIvas[$tipoIva]+=$precio;


    

    if($datos['lineas'][$i]['esPack']==0){
        $this->excel->getActiveSheet()->setCellValue("I$lineaCuerpo", $basePrecio.' € ');
        $this->excel->getActiveSheet()->getStyle("I$lineaCuerpo")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", $basePrecio.' € ');
        $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $this->excel->getActiveSheet()->setCellValue("K$lineaCuerpo", $datos['lineas'][$i]['cantidad'].' ');
        $this->excel->getActiveSheet()->getStyle("K$lineaCuerpo")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        if(!$intercomunitario){
            $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", number_format($baseTotal,2));
            $this->excel->getActiveSheet()->setCellValue("M$lineaCuerpo", number_format($tipoIva,2));
            $this->excel->getActiveSheet()->setCellValue("N$lineaCuerpo", number_format($varAmount,2));
         }
        $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", number_format($precio,2).' € ');
        $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $totalPrecio+=$precio;
        $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:N$lineaCuerpo")->getFont()->setBold(false)->setSize(10);

        
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:N$lineaCuerpo")->getFont()->setBold(false)->setSize(10);
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
      ));
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:B$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'left' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            ),
            'right' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
      ));
    $this->excel->getActiveSheet()->getStyle("I$lineaCuerpo:I$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'left' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
      ));
    $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:J$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
      ));
    $this->excel->getActiveSheet()->getStyle("K$lineaCuerpo:K$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'left' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
      ));
    $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'left' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            ),
            'right' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
      ));
    }
    
    //para packs
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
      ));
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:B$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'left' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            ),
            'right' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
      ));
    $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
            'right' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => $colorMarco)
            )
        )
      ));

    
 }
 
 $descuento=floatval($datos['descuento'])/1;
 $transporte=floatval($datos['transporte'])/10;
 
 if($descuento==$transporte){
    $descuento=0;
    $transporte=0;
    }   
 if($descuento!=0){
    $descuento=-$descuento/100;
    if($datos['tipoCliente']!=9){
        $lineaCuerpo++;
        $this->excel->getActiveSheet()->setCellValue("D$lineaCuerpo", 'Descuento');
        $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(8);

        $this->excel->getActiveSheet()->setCellValue("F$lineaCuerpo", '1');
        $this->excel->getActiveSheet()->setCellValue("G$lineaCuerpo", number_format($descuento,2));

            // $productos+="<tr class='linea'><td></td><td class='izda descuento'>Descuento</td><td></td><td class='dcha'>1</td><td></td><td></td><td></td><td></td><td class='dcha'>".number_format($descuento,2)." €</td></tr>";
    }     
            else{
                $this->excel->getActiveSheet()->setCellValue("D$lineaCuerpo", 'Descuento');
                $this->excel->getActiveSheet()->getStyle("D$lineaCuerpo")->getFont()->setBold(false)->setSize(8);

        $this->excel->getActiveSheet()->setCellValue("F$lineaCuerpo", '1');
        $this->excel->getActiveSheet()->setCellValue("G$lineaCuerpo", number_format($descuento,2));
        //  $productos+="<tr class='linea'><td></td><td class='izda descuento'>Descuento</td><td></td><td ></td><td class='dcha'>1</td><td class='dcha'>".number_format($descuento,2)." €</td></tr>";
            }
    }
// 

$factor=0;
if($totalPrecio!=0) {   
    $factor=1+$descuento/$totalPrecio;
}

$lineaCuerpo++;
$this->excel->getActiveSheet()->getRowDimension($lineaCuerpo)->setRowHeight(18);

if($intercomunitario){
    $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", 'Detalle');
    $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", 'Total');
}
else{
    $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", 'Detalle');
    $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", 'Total');
}
$this->excel->getActiveSheet()->getStyle("J$lineaCuerpo")->getFont()->setBold(true)->setSize(10);
$this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")->getFont()->setBold(true)->setSize(10);

$this->excel->getActiveSheet()->mergeCells("J$lineaCuerpo:K$lineaCuerpo");
$this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:K$lineaCuerpo")
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


      
$totalSinIva=0;
$totalAmount=0;
$totalIvaIncluido=0;

foreach($infoIvas as $index=>$value){
    $base=$precioIvas[$index]*$factor/(1+$index/100);
    $amount=$precioIvas[$index]*$factor-$base;
    $lineaCuerpo++;
    $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", 'Productos');
    $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo")->getFont()->setBold(false)->setSize(10);

    $this->excel->getActiveSheet()->mergeCells("J$lineaCuerpo:K$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:K$lineaCuerpo")
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    if(!$intercomunitario){
        $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", $value);
        $this->excel->getActiveSheet()->setCellValue("K$lineaCuerpo", $base);
        $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", $amount);
    }
    else{

        $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", $totalPrecio.' € ');
        $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")->getFont()->setBold(false)->setSize(10);

    }
    $totalSinIva+=$base;
    $totalAmount+=$amount;
    //console.log('precioIvas[index] '+precioIvas[index])
    //console.log('factor '+factor)
    $totalIvaIncluido+=$precioIvas[$index]*$factor;

}
if($transporte!=0){
    $transporte=$transporte/100;
    $index=$datos['tipoIvaTransporte']/1000;
    $base=$datos['baseTransporte']/1000;
    $amount=$transporte-$base;
    $lineaCuerpo++;
    // $ivas+="<tr ><td class='dcha'>Transportista</td>"
    $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", 'Transportista');
    $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo")->getFont()->setBold(false)->setSize(10);

    $this->excel->getActiveSheet()->mergeCells("J$lineaCuerpo:K$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:K$lineaCuerpo")
                                    ->getAlignment()
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")
                                    ->getAlignment()
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    if(!$intercomunitario){
        $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", number_format($index,3));
        $this->excel->getActiveSheet()->setCellValue("K$lineaCuerpo", number_format($base,2));
        $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", number_format($amount,2));
    }
    else{
        $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", number_format($transporte,2).' € ');
        $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")->getFont()->setBold(false)->setSize(10);

        $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    }
    
    $totalSinIva+=$base;
    $totalAmount+=$amount;
    $totalIvaIncluido+=$transporte;
    
    }
    $lineaCuerpo++;  
if(!$intercomunitario){  
    
    // ivas+="<tr style='height:5px;border-bottom: 1px solid lightgrey;'><td></td><td></td><td></td><td></td><td></td></tr>"
    $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", 'Total');
    $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", number_format($totalSinIva,2));
    


    // ivas+="<tr style=''><td class='dcha'>Total</td><td></td><td class='dcha'>"+totalSinIva.toFixed(2)+" €</td><td class='dcha'>"+totalAmount.toFixed(2)+" €</td><td class='dcha'>"+totalIvaIncluido.toFixed(2)+" €</td></tr>"
}  
else{
    // ivas+="<tr style='height:5px;border-bottom: 1px solid lightgrey;'><td></td><td></td><td></td></tr>"
    // $this->excel->getActiveSheet()->setCellValue("H$lineaCuerpo", 'Total');
    $this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", 'Total');
    $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo")->getFont()->setBold(false)->setSize(10);

    $this->excel->getActiveSheet()->mergeCells("J$lineaCuerpo:K$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:K$lineaCuerpo")
                                    ->getAlignment()
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


    $this->excel->getActiveSheet()->setCellValue("L$lineaCuerpo", number_format($totalIvaIncluido,2).' € ');
    $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")->getFont()->setBold(false)->setSize(10);
    $this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
        'borders' => array(
          'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
          )
        )
      ));


   
    // ivas+="<tr style=''><td class='dcha'>Total</td><td></td><td class='dcha'>"+totalIvaIncluido.toFixed(2)+" €</td></tr>"
}
// ivas+="</table>"
//console.log('totalIvaIncluido '+totalIvaIncluido)
$lineaCuerpo++;
$lineaCuerpo++;
$total='Total: '.number_format($totalIvaIncluido,2).' €';
$this->excel->getActiveSheet()->setCellValue("J$lineaCuerpo", $total);
$this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:L$lineaCuerpo")->getFont()->setBold(true)->setSize(12);
$this->excel->getActiveSheet()->getRowDimension($lineaCuerpo)->setRowHeight(18);
$this->excel->getActiveSheet()->getStyle("J$lineaCuerpo")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
$this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
$this->excel->getActiveSheet()->getStyle("J$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
$this->excel->getActiveSheet()->getStyle("L$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => $colorMarco)
        )
    )
    ));
$this->excel->getActiveSheet()->mergeCells("J$lineaCuerpo:L$lineaCuerpo");
$this->excel->getActiveSheet()->getStyle("J$lineaCuerpo:L$lineaCuerpo")
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


// var totalFinal="<table class='totalFinal'><tr><td >Total: "+totalIvaIncluido.toFixed(2)+" €</td></tr></div>"


               
$lineaCuerpo++;
$lineaCuerpo++;


$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'Origin of all products is Spain');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(10);

$lineaCuerpo++;

$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'All food products are vaccum packed or canned');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(10);

$lineaCuerpo++;

$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'Total weight '.$peso.' Kg');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(10);
$lineaCuerpo++;

$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'INCOTERM: DAP');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(10);


for($i=$lineaCuerpo;$i<51;$i++) $lineaCuerpo++;
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")->applyFromArray(array(
    'borders' => array(
      'bottom' => array(
        'style' => PHPExcel_Style_Border::BORDER_THIN,
        'color' => array('rgb' => $colorMarco)
      )
    )
  ));
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'www.jamonarium.com - info@jamonarium.com - (0034) 931 763 594');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(6);
$this->excel->getActiveSheet()->getRowDimension($lineaCuerpo)->setRowHeight(8);

$this->excel->getActiveSheet()->mergeCells("B$lineaCuerpo:L$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")
                                    ->getAlignment()
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", '181 Distribucions S.L. (ESB66154964) - Pg. Sant Joan 181 Barcelona España');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(6);
$this->excel->getActiveSheet()->getRowDimension($lineaCuerpo)->setRowHeight(8);

$this->excel->getActiveSheet()->mergeCells("B$lineaCuerpo:L$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")
                                    ->getAlignment()
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'Inscrita en el R.M.B. Tomo 44061, Folio 146, Hoja B 446064. Inscripción 1.');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(6);
$this->excel->getActiveSheet()->getRowDimension($lineaCuerpo)->setRowHeight(8);
$this->excel->getActiveSheet()->mergeCells("B$lineaCuerpo:L$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")
                                    ->getAlignment()
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'En cumplimiento de la Ley Orgánica 15/199, de protección de datos de carácter personal, sus datos facilitados, figuran en un ficheroautomatizado y protegido.');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(6);
$this->excel->getActiveSheet()->getRowDimension($lineaCuerpo)->setRowHeight(8);
$this->excel->getActiveSheet()->mergeCells("B$lineaCuerpo:L$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")
                                    ->getAlignment()
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", ' Estos datos no serán cedidos absolutamente a nadie y se utilizarán exclusivamente para establecer las facturas a su nombre y para nuestras comunicaciones dirigidas a ustedes.');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(6);
$this->excel->getActiveSheet()->getRowDimension($lineaCuerpo)->setRowHeight(8);
$this->excel->getActiveSheet()->mergeCells("B$lineaCuerpo:L$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")
                                    ->getAlignment()
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$lineaCuerpo++;
$this->excel->getActiveSheet()->setCellValue("B$lineaCuerpo", 'En cualquier momento, puede ejercer su derecho a la cancelación de sus datos de nuestro fichero, mediante una comunicación por escrito dirigida a nuestra atención. 025181. ');
$this->excel->getActiveSheet()->getStyle("B$lineaCuerpo")->getFont()->setBold(false)->setSize(6);
$this->excel->getActiveSheet()->getRowDimension($lineaCuerpo)->setRowHeight(8);
$this->excel->getActiveSheet()->mergeCells("B$lineaCuerpo:L$lineaCuerpo");
    $this->excel->getActiveSheet()->getStyle("B$lineaCuerpo:L$lineaCuerpo")
                                    ->getAlignment()
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$filename = "Factura.xls";
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
