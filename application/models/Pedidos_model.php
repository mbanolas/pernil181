<?php
class Pedidos_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->load->library('excel');
                $this->load->library('exceldrawing');
                $this->load->model('tickets_');
                $this->load->model('compras_model');
        }
        
        function getDatosProveedor($proveedor){
            if($proveedor>1000){$tabla='pe_acreedores';$proveedor/=1000;} else $tabla='pe_proveedores';
            $sql="SELECT * FROM $tabla WHERE id_proveedor='$proveedor'";
            $datosProveedor=$this->db->query($sql)->row();
            return $datosProveedor;    
        }
        
        function getNombreGenericoProducto($codigo_producto){
            $nombreProducto=$this->compras_model->getNombreGenericoProducto($codigo_producto);
            return $nombreProducto;
        }

        function getNombreProducto($codigo_producto){
            $nombreProducto=$this->compras_model->getNombreProducto($codigo_producto);
            return $nombreProducto;
        }
        
        function registrarArchivoPedido($idPedido, $filename){
            $sql="UPDATE pe_pedidos_proveedores SET nombreArchivoPedido='$filename' WHERE id='$idPedido'";
            
            $this->db->query($sql);
        }
        
        function excelPedido() {
       
         extract($_POST);
         
         //caberera Factura
        if(true){
         
        //echo $proveedor;   
         $datosProveedor = $this->getDatosProveedor($proveedor);

         //var_dump($datosProveedor);
        
        $this->exceldrawing->setName('Pernil181');
        $this->exceldrawing->setDescription('Pernil181');
        $logo = 'images/pernil181.png'; // Provide path to your logo file
        $this->exceldrawing->setPath($logo);  //setOffsetY has no effect
        $this->exceldrawing->setCoordinates('C2');
        $this->exceldrawing->setHeight(80); // logo height
        $this->exceldrawing->setWorksheet($this->excel->getActiveSheet()); 
        
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setCellValue('H3', "181 Distribucions S.L."); 
        $this->excel->getActiveSheet()->setCellValue('H4', "Pg.Sant Joan 181"); 
        $this->excel->getActiveSheet()->setCellValue('H5', "08037 Barcelona, España"); 
        $this->excel->getActiveSheet()->setCellValue('H6', "www.jamonarium.com"); 
        $this->excel->getActiveSheet()->setCellValue('H7', "info@jamonarium.com"); 
        $this->excel->getActiveSheet()->setCellValue('C9', "181 Distribucions S.L. Inscrita en el R.M.B. Tomo 44061, Folio 146, Hoja B 446064. Inscripción 1ª.  N.I.F B66154964       "); 
        $this->excel->getActiveSheet()->setCellValue('C11', "FECHA"); 
        $this->excel->getActiveSheet()->setCellValue('C12', "PROVEEDOR"); 
        $this->excel->getActiveSheet()->setCellValue('C13', "DIRECCION"); 
        $this->excel->getActiveSheet()->setCellValue('C14', "POBLACIÓN"); 
        $this->excel->getActiveSheet()->setCellValue('C15', "PAIS"); 
        $this->excel->getActiveSheet()->setCellValue('H11', "Nº PEDIDO"); 
        $this->excel->getActiveSheet()->setCellValue('H12', "CIF"); 
        $this->excel->getActiveSheet()->setCellValue('H13', "Nº PROVEEDOR"); 
        $this->excel->getActiveSheet()->setCellValue('H14', "C. POSTAL"); 
        $this->excel->getActiveSheet()->setCellValue('H15', "PROVINCIA"); 
        $this->excel->getActiveSheet()->getStyle('H11:H15')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'FCE9DA')));
        $this->excel->getActiveSheet()->getStyle('C11:C15')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'FCE9DA')));
        //control existencia datos cliente (caso que se haya borrado del listado clientes
      
        
        $fecha =  substr(fechaEuropea($fechaPedido),0,10);  //substr($ticket['fecha'],0,10);
        $this->excel->getActiveSheet()->setCellValue('D11', $fecha); 
        $this->excel->getActiveSheet()->setCellValue('I11', $numPedido); 
        $this->excel->getActiveSheet()->setCellValue('D12', $datosProveedor->nombre_proveedor); 
        $this->excel->getActiveSheet()->setCellValue('K12', $datosProveedor->email1); 
        $this->excel->getActiveSheet()->setCellValue('K13', $datosProveedor->email2); 
        $this->excel->getActiveSheet()->setCellValue('K14', 'Teléfono: '.$datosProveedor->telefono); 
        $this->excel->getActiveSheet()->setCellValue('K15', 'Móvil: '.$datosProveedor->movil); 
        $this->excel->getActiveSheet()->setCellValue('D13', $datosProveedor->domicilio); 
        $this->excel->getActiveSheet()->setCellValue('D14', $datosProveedor->poblacion); 
        $this->excel->getActiveSheet()->setCellValue('D15', $datosProveedor->pais); 
        $this->excel->getActiveSheet()->setCellValue('I12', $datosProveedor->cif); 
        $this->excel->getActiveSheet()->setCellValue('I13', $datosProveedor->id_proveedor); 
        $this->excel->getActiveSheet()->setCellValue('I14', $datosProveedor->cp); 
        $this->excel->getActiveSheet()->setCellValue('I15', $datosProveedor->provincia); 
        $this->excel->getActiveSheet()->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      //  $this->excel->getActiveSheet()->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      //  $this->excel->getActiveSheet()->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->getActiveSheet()->setCellValue('C17', "Ref."); 
        $this->excel->getActiveSheet()->setCellValue('D17', "Cantidad"); 
        $this->excel->getActiveSheet()->setCellValue('E17', ""); 
        $this->excel->getActiveSheet()->setCellValue('F17', "Descripción"); 
        $this->excel->getActiveSheet()->setCellValue('G17', "Precio"); 
        $this->excel->getActiveSheet()->setCellValue('H17', "Des. %"); 
        $this->excel->getActiveSheet()->setCellValue('I17', "Importe"); 
        //$this->excel->getActiveSheet()->setCellValue('J17', "Importe"); 
        $this->excel->getActiveSheet()->getStyle('C17:I17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C17:I17')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->getStyle('I11:I15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        
        $this->excel->getActiveSheet()->getStyle('C17:I17')->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
        
        $numero = $numPedido;  // $ticket['numero'];
     
        $linea=18;
        $totalFactura=0;
        
        }
         //Hasta aquí encabesado factura
         
        //Anchos columnas
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(18.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(6.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(56.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(27.17*.68);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(5);
        
        foreach($lineas as $k=>$v){
            $nombreProduco=$this->getNombreGenericoProducto($v['codigo_producto']);
            $this->excel->getActiveSheet()->setCellValue('C' . $linea,  $v['codigo_producto']);
            $this->excel->getActiveSheet()->setCellValue('D' . $linea,  $v['cantidades']);
            $this->excel->getActiveSheet()->setCellValue('E' . $linea,  $v['tiposUnidades']);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea,  $nombreProduco);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea,  $v['precios']);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea,  $v['descuentos']/100);
            $this->excel->getActiveSheet()->setCellValue('I' . $linea,  $v['totales']);
           
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 %');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');

            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
       
            
            if ($linea % 2 ==0){
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'e2e2e2')
                                )
                            )
                    );
                }
            $linea++;
        }
            
            ///// Formateado y pié
            $hasta=42;
           for ($k=0;$k<100;$k++){
           if ($linea >42*$k) {$hasta=42+$k*37;}
           }
           
           for ($k=$linea;$k<$hasta;$k++){
               $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(20);
                   $linea++;
           }
           
          
           $linea++;
           
            $this->excel->getActiveSheet()->getStyle('C'.($linea).':I'.$linea)->applyFromArray(array('borders' => array(
                    
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));

            $this->excel->getActiveSheet()->mergeCells('G'.$linea.':H'.$linea);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'TOTAL PEDIDO');
            $this->excel->getActiveSheet()->getStyle('G' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $totalLineas=floatval($totalPedido)-floatval($otrosCostes);
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalLineas);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            $linea++;
            if($otrosCostes>0){
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'Otros');
                
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $otrosCostes);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');

            
            $this->excel->getActiveSheet()->getStyle('G'.($linea-1).':H'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            }
            //pie
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'TOTAL PEDIDO');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalPedido);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');

            $this->excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 17);
            
            $this->excel->getProperties()->setTitle('Pedido núm: '.$numPedido);
            $this->excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->excel->getProperties()->getTitle() . '&RPágina &P de &N');
  
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I'.$linea)->applyFromArray(array('borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
           $this->excel->getActiveSheet()->getStyle('G' . $linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            
           $linea++;
           $linea++;
           $sizeFont=10;
           
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Direccion de entrega Passeig de Sant Joan 181, Barcelona (08037) tel: 93 459 23 36.');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Nota: Enviar albarán sin valorar.');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Condiciones de pago a 30 días fecha factura con vencimiento día 1 de mes.');
            $linea++; 
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Domiciliación bancaria ES48 0081 0138 74 0001421052  Banc de Sabadell');

              $lineUltima=$linea-14;
            
              
       //configuración impresora
       $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:J'.$linea);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
       $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
       $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);
       $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       $this->excel->getActiveSheet()->getPageMargins()->setTop(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setRight(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setLeft(0.7);
       $this->excel->getActiveSheet()->getPageMargins()->setBottom(0.75);
       $this->excel->getActiveSheet()->getPageMargins()->setHeader(0.3);
       $this->excel->getActiveSheet()->getPageMargins()->setFooter(0.3);
       $this->excel->getActiveSheet()->getPageSetup()->setHorizontalCentered(false);
       $this->excel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
 
        $filename = "Pedido_$numero.xls"; 
        $registro=$this->registrarArchivoPedido($idPedido, $filename);

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        
        $objWriter->save('pedidos/'.$filename);
        
        
        return $filename;
        
    }
         
}
        
