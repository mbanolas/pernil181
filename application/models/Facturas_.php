<?php
class Facturas_ extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                 $this->load->library('excel');
                $this->load->library('exceldrawing');
                 $this->load->model('tickets_');
                  $this->load->model('clientes_');
        }
        
        
        public function getFacturaSiguiente(){
            $any=date('y');
            
            $sql="SELECT id_factura FROM pe_registroFacturas WHERE id_factura REGEXP 'B".$any."[0-9]{3}$' ORDER BY id_factura DESC LIMIT 1";
              $query=$this->db->query($sql);
            // mensaje($sql);
            $row = $query->row();
            if($row) 
            {
                $factura=$row->id_factura;
                $prefijo=  substr($factura, 0, 1);
                $numero=substr($factura, 1);
                $numeroSiguiente=floatval($numero)+1;
                $numeroSiguiente=  number_format($numeroSiguiente,0,"","");
                return  $prefijo.$numeroSiguiente;
            }
            else {
                $prefijo='B';
                $numeroSiguiente=date("y")."001";
                return  $prefijo.$numeroSiguiente;
            }
        }

        public function registrarFactura($numFactura, $fechaFactura,$id_cliente, $nombreArchivoFactura){
            $ordenacion=$fechaFactura.$numFactura;
            $sql="INSERT INTO pe_registroFacturas (id_factura, fecha_factura, id_cliente,nombreArchivoFactura,ordenacion) values('$numFactura', '$fechaFactura','$id_cliente','$nombreArchivoFactura','$ordenacion')";
            $query=$this->db->query($sql);
            return $query;
        }
        
        function comprobarId_factura($numFactura){
            
            $sql="SELECT id_factura FROM pe_registroFacturas WHERE id_factura='$numFactura'";
            $query=$this->db->query($sql);
            
           return $query->num_rows();
        }
        
        
        function excelFactura() {
            
         
            /*
         foreach($_POST[0] as $k=>$v){
             log_message('info', $k);
             log_message('info', $v);
         }    
         */
            
         $ticketsFactura=$_POST['ticketsFactura'];
         $fechaFactura=$_POST['fechaFactura'];
        
         //tratamiento transporte que se indica en formulario factura
         $numFactura=$_POST['numFactura'];
         $transporte=floatval($_POST['transporte']);
         $ivaTransporte=21;
         
         $bruto[$ivaTransporte]=$transporte;
        
        
        $valorIva=$transporte/(1+$ivaTransporte/100);
        $valorIva=round($valorIva,2);
        $valorBase=$transporte-$valorIva;
        //echo $valorIva;
        $iva[$ivaTransporte]=$valorIva;
        
        //echo $valorBase;
        $base[$ivaTransporte]=$valorBase;
             
        $transporte= $bruto[$ivaTransporte];
        
        
         $agrupar=false;
         if(isset($_POST['agrupar'])) $agrupar=true; 
         
         //se comprueba si la factura contiene 2 o más tickets iguales
         if(array_unique($ticketsFactura) != $ticketsFactura)
                return false;
         
         //caberera Factura
        if(true){
         
         $ticket = $this->tickets_->getTicketPorNumero($ticketsFactura[0], 'pe_boka');
         $datosCliente=$this->clientes_->getDatosCliente($ticket['cliente']);

         //var_dump($datosCliente);
        
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
        $this->excel->getActiveSheet()->setCellValue('C12', "RAZON SOCIAL"); 
        $this->excel->getActiveSheet()->setCellValue('C13', "DIRECCION"); 
        $this->excel->getActiveSheet()->setCellValue('C14', "POBLACIÓN"); 
        $this->excel->getActiveSheet()->setCellValue('C15', "PAIS"); 
        $this->excel->getActiveSheet()->setCellValue('H11', "Nº FACTURA"); 
        $this->excel->getActiveSheet()->setCellValue('H12', "CIF"); 
        $this->excel->getActiveSheet()->setCellValue('H13', "Nº CLIENTE"); 
        $this->excel->getActiveSheet()->setCellValue('H14', "C. POSTAL"); 
        $this->excel->getActiveSheet()->setCellValue('H15', "PROVINCIA"); 
        $this->excel->getActiveSheet()->getStyle('H11:H15')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'FCE9DA')));
        $this->excel->getActiveSheet()->getStyle('C11:C15')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'FCE9DA')));
        //control existencia datos cliente (caso que se haya borrado del listado clientes
      
        
        
        if(!isset($datosCliente['empresa'])) $datosCliente['empresa']="";
        
        $fecha =  substr(fechaEuropea($fechaFactura),0,10);  //substr($ticket['fecha'],0,10);
        $this->excel->getActiveSheet()->setCellValue('D11', $fecha); 
        $this->excel->getActiveSheet()->setCellValue('I11', $numFactura); 
        $this->excel->getActiveSheet()->setCellValue('D12', $datosCliente['empresa']); 
        $this->excel->getActiveSheet()->setCellValue('K12', $datosCliente['correo1']); 
        $this->excel->getActiveSheet()->setCellValue('K13', $datosCliente['correo2']); 
        $this->excel->getActiveSheet()->setCellValue('D13', $datosCliente['direccion']); 
        $this->excel->getActiveSheet()->setCellValue('D14', $datosCliente['poblacion']); 
        $this->excel->getActiveSheet()->setCellValue('D15', $datosCliente['pais']); 
        $this->excel->getActiveSheet()->setCellValue('I12', $datosCliente['nif']); 
        $this->excel->getActiveSheet()->setCellValue('I14', $datosCliente['codigoPostal']); 
        $this->excel->getActiveSheet()->setCellValue('I15', $datosCliente['provincia']); 
        
        
        
        
        
        //$this->excel->getActiveSheet()->setCellValue('D13', $ticket['empresa']); 
        $this->excel->getActiveSheet()->setCellValue('I13', $ticket['cliente']); 
        
        $this->excel->getActiveSheet()->getStyle('I13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->getActiveSheet()->setCellValue('C17', "Ref."); 
        $this->excel->getActiveSheet()->setCellValue('D17', "Unds."); 
        $this->excel->getActiveSheet()->setCellValue('E17', "Descripción"); 
        $this->excel->getActiveSheet()->setCellValue('F17', "IVA"); 
        $this->excel->getActiveSheet()->setCellValue('G17', "Kilos"); 
        $this->excel->getActiveSheet()->setCellValue('H17', "Precio"); 
        $this->excel->getActiveSheet()->setCellValue('I17', "Importe"); 
        $this->excel->getActiveSheet()->getStyle('C17:I17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C17:I17')->getFont()->setBold(true);

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
        
        $numero = $numFactura;  // $ticket['numero'];
     
        $linea=18;
        $totalFactura=0;
        
        }
         //Hasta aquí encabezado factura
        
        //lectura datos todos los tickets
        if(true){
        $numTickets=array();
        $productosPrecios=array(); 
        $productosPesos=array();
        $productosUnidades=array();
        $productosNombres=array();
        $productosCodigo=array();
        $productosPreciosU=array();
        $productosImportes=array();
        $productosIvas=array();
        $numUnidades=array();
        //variable que registra si existe un códigos de producto en varias lineas (todos los tickets)
        //para hacer la factura compilados por productos (si varias lineas con el mismo producto o
        //compilado por tickets.
        $compilacionPorProductos=false;
        
        foreach($ticketsFactura as $kt => $ticket){
            $ticket = $this->tickets_->getTicketPorNumero($ticket, 'pe_boka');
            
            $numTickets[]=$ticket['numero'];
            
            foreach ($ticket['unidades_pesos'] as $k => $v) {

                    $codProducto=$ticket['codigosProductos'][$k];
                    $precioProducto=$ticket['preciosUnitarios'][$k];
                    $codigoPrecio=$precioProducto*1000000000+$codProducto;
                    if(!in_array($codigoPrecio,$productosPrecios)){
                            $productosPrecios[]=$codigoPrecio;
                            $key = array_search($codigoPrecio, $productosPrecios);
                            $descuentos[$key]=0;
                            $productosPesos[$key]=0;
                            $productosUnidades[$key]=0;
                            $productosOtros[$key]=0;
                            $productosNombres[$key]="";
                            $productosPreciosU[$key]=$precioProducto;
                            $productosCodigo[$key]=$codProducto;
                            $productosImportes[$key]=0;
                            $numUnidades[$key]=0;
                    }
                    if (false !== $key = array_search($codigoPrecio, $productosPrecios)) {
                        $compilacionPorProductos=true;
                        $productosNombres[$key]=$ticket['productos'][$k];
                        $productosIvas[$key]=$ticket['tiposIva'][$k];
                        $productosImportes[$key]+=$ticket['precios'][$k];
                        $descuentos[$key]+=$ticket['descuentos'][$k];
                        $numUnidades[$key]+=$ticket['numUnidades'][$k];
                        if ($v == "1" || $v=="3" ) $productosUnidades[$key]+=$ticket['unidades'][$k];
                        if ($v == "0" || $v=="4" ) $productosPesos[$key]+=$ticket['pesos'][$k];
                        if($v==2) $productosOtros[$key]+=$ticket['unidades'][$k];
                    } else {
                        // do something else
                    }
                
            }
             
           // return array('productosPrecios'=>$productosPrecios,'productosCodigo'=>$productosCodigo );
        }
        }
         //lectura datos todos los tickets
        //aquí empieza salida agrupada por productos
        if($compilacionPorProductos  && $agrupar){
        foreach($numTickets as $k=>$v){
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Ticket núm ' . $v);
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
        foreach($productosPrecios as $k=>$v){
            if($productosCodigo[$k]==999){
                $transporte+=$productosUnidades[$k]*$productosPreciosU[$k];
            }
            if($productosCodigo[$k]!=999){
            if($productosUnidades[$k] || $productosPesos[$k] || $productosOtros[$k] ){
                if($productosUnidades[$k] || $productosPesos[$k]){
                    $tipoIVA=(int)$productosIvas[$k];    
                    $this->excel->getActiveSheet()->setCellValue('C' . $linea,  $productosCodigo[$k]);
                    $this->excel->getActiveSheet()->setCellValue('D' . $linea,  $numUnidades[$k]);
                    $this->excel->getActiveSheet()->setCellValue('E' . $linea,  $productosNombres[$k]);
                    $this->excel->getActiveSheet()->setCellValue('H' . $linea,  $productosPreciosU[$k]);
                    $this->excel->getActiveSheet()->setCellValue('F' . $linea,  $tipoIVA);

                    if (!isset($bruto[$tipoIVA])) $bruto[$tipoIVA]=0;
                }
                if($productosOtros[$k] ){
                    $tipoIVA=(int)$productosIvas[$k];    
                    $this->excel->getActiveSheet()->setCellValue('C' . $linea,  $productosCodigo[$k]);
                    $this->excel->getActiveSheet()->setCellValue('E' . $linea,  $productosNombres[$k]);
                    //$this->excel->getActiveSheet()->setCellValue('H' . $linea,  $productosPreciosU[$k]);
                    //$this->excel->getActiveSheet()->setCellValue('F' . $linea,  $tipoIVA);

                    if (!isset($bruto[$tipoIVA])) $bruto[$tipoIVA]=0;
                }
            
            //$productosImportes[$k]);
            if($productosUnidades[$k]) {
                $this->excel->getActiveSheet()->setCellValue('D' . $linea,  $productosUnidades[$k]);
                $totalFactura+=$productosUnidades[$k]*$productosPreciosU[$k];
                $bruto[$tipoIVA]+=$productosUnidades[$k]*$productosPreciosU[$k];
                //echo $bruto[$tipoIVA].'<br>';
                $this->excel->getActiveSheet()->setCellValue('I' . $linea,  $productosUnidades[$k]*$productosPreciosU[$k]);
            }
            if($productosPesos[$k]) {
                $this->excel->getActiveSheet()->setCellValue('G' . $linea,  $productosPesos[$k]);
                $totalFactura+=$productosPesos[$k]*$productosPreciosU[$k];
                $bruto[$tipoIVA]+=$productosPesos[$k]*$productosPreciosU[$k];
                //echo $bruto[$tipoIVA].'<br>';
                $this->excel->getActiveSheet()->setCellValue('I' . $linea,  $productosPesos[$k]*$productosPreciosU[$k]);
            }
            if($productosOtros[$k]) {
                //$this->excel->getActiveSheet()->setCellValue('G' . $linea,  $productosPesos[$k]);
                $totalFactura+=$productosImportes[$k];
                $bruto[$tipoIVA]+=$productosImportes[$k];
                //echo $bruto[$tipoIVA].'<br>';
                $this->excel->getActiveSheet()->setCellValue('I' . $linea,  $productosImportes[$k]);
            }
            $this->excel->getActiveSheet()->getStyle('G' . $linea)->getNumberFormat()->setFormatCode('#,##0.000');
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
       
            
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
            if($descuentos[$k]!=0) {
                $this->excel->getActiveSheet()->setCellValue('E' . $linea,  "Su ventaja");

                $totalFactura+=$descuentos[$k];
                $bruto[$tipoIVA]+=$descuentos[$k];
                $this->excel->getActiveSheet()->setCellValue('I' . $linea,  $descuentos[$k]);
   
            
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
       
            
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
            
            }
            }
            else {
                
            //$transporte+=$productosImportes[$k];
         
        $bruto[$ivaTransporte]+=$productosImportes[$k];
        
        
        $valorIva=$productosImportes[$k]/(1+$ivaTransporte/100);
        $valorIva=round($valorIva,2);
        $valorBase=$productosImportes[$k]-$valorIva;
        //echo $valorIva;
        $iva[$ivaTransporte]+=$valorIva;
        
        //echo $valorBase;
        $base[$ivaTransporte]+=$valorBase;
             
        //$transporte= $bruto[$ivaTransporte];    
            }
        }
        
        //Anchos columnas
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(18.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(56.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(27.17*.68);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(5);

        
            
        
        
        
        $pag=(int)(($linea+14)/54);
           $lineasProducto=$linea-14;
           $paginas=(int)($lineasProducto / 38)+1;
           $lineasProductoUltimaPagina= $lineasProducto % 38;
           
            
           //fijar las lineas con datos prodyctos con altura =20
           for($i=18;$i<$linea;$i++)
                $this->excel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
       
           for($i=0;$i<100;$i++){
           if($linea>43+37*$i && $linea<43+37*$i+12){
                for($j=$linea; $j<43+37*$i+12;$j++){
                   $this->excel->getActiveSheet()->getRowDimension($j)->setRowHeight(20);
                   $linea++;
                }
                break;
           }
           }
         
        //    $hasta=40;
        //    for ($k=0;$k<100;$k++){
        //         if ($linea >40*$k) {$hasta=40+$k*37;}
        //    }

        $hasta=37;
           for ($k=0;$k<100;$k++){
                if ($linea >37*$k) {$hasta=37+$k*35;}
           }
           
           for ($k=$linea;$k<$hasta;$k++){
               $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(20);
                   $linea++;
           }
           
           
          
          
           $linea++;
           //echo '$i '.$i. '$linea '.$linea. '$lineasProducto '.$lineasProducto.' $lineasProductoUltimaPagina '.$lineasProductoUltimaPagina;
          
    
            
           
            $this->excel->getActiveSheet()->getStyle('C'.($linea).':I'.$linea)->applyFromArray(array('borders' => array(
                    
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            

            $this->excel->getActiveSheet()->mergeCells('G'.$linea.':H'.$linea);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'TOTAL PRODUCTO');
           $this->excel->getActiveSheet()->getStyle('G' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'Transporte');
                
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $transporte);
            
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
            
          
            $totalFactura+=$transporte;          

            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'B.I.');
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, 'TOTAL IVA');
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
                        //$this->excel->getActiveSheet()->getStyle('C19:I19')->getFont()->setBold(true);
            $border=array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                );
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
        
        
            $linea++;
           ksort($bruto);
           $totales['base']=0;
              $totales['iva']=0;
              $totales['bruto']=0;
              
            foreach($bruto as $k=>$v){
              if($bruto[$k]>0)  {
                  $base[$k]=$bruto[$k]/(1+$k/100);
                    $totales['base']+= $base[$k];
                  $iva[$k]=$bruto[$k]-$base[$k];
              $totales['iva']+= $iva[$k];
              $totales['bruto']+= $bruto[$k];
              
              
              $this->excel->getActiveSheet()->setCellValue('F' . $linea, $k);
              $this->excel->getActiveSheet()->setCellValue('G' . $linea, $base[$k]);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $iva[$k]);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $bruto[$k]);
             $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border)); 
            }
             $linea++;
            }
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $totales['base']);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $totales['iva']);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totales['bruto']);
              $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         
        //    $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
      
            
            //pie
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'TOTAL FACTURA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');

            $this->excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 17);
            
            $this->excel->getProperties()->setTitle('Factura núm: '.$numFactura);
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
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'En cumplimiento de la Ley Orgánica 15/199, de proteción de datos de carácter personal, sus datos facilitados, figuran en un fichero');
            //   $linea++;
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'automatizado y protegido. Estos Datos no serán cedidos absolutamente a nadie y se utilizaran exclusivamente para estsblecer las facturas a');
            //   $linea++;
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'su nombre y para nuestros comunicados dirigidos a ustedes. En cualquier momento, pueden ejercer su derecho a la cancelación de sus datos ');
            //   $linea++; 
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'de nuestro fichero, mediante comunicación por escrito.');
            


            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Informarle que  tratamos la  información que nos  facilita con el  fin de  realizar pedidos  y  gestionar la  facturación de los  productos y servicios  contratados.');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Los  datos  proporcionados se  conservarán  mientras se  mantenga la  relación  comercial o  durante el  tiempo  necesario para  cumplir  con las  obligaciones');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'legales y  atender las posibles  responsabilidades que  pudieran  derivar del  cumplimiento de la  finalidad para la que los  datos fueron  recabados. Los datos ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'no se  cederán a  terceros  salvo en los casos  en que  exista una  obligación  legal. Usted  tiene  derecho a  obtener  información  de si  estamos  tratando sus ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'datos  personales,  por  lo  que   puede  ejercer  sus  derechos  de  acceso,  rectificación,  supresión  y  portabilidad  de  datos  y  oposición  y  limitación  a  su ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'tratamiento ante 181 Distribucions S.L. con CIF B66154964 Dirección Pg. Sant Joan, 181, local. 08037 Barcelona y Correo electrónico: info@jamonarium.com,');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'adjuntando copia de su DNI o documento  equivalente. Asimismo, y  especialmente si considera que no ha obtenido  satisfacción plena en el ejercicio de sus ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'derechos, podrá presentar una reclamación ante la autoridad nacional de control dirigiéndose a estos efectos a la Agencia Española de Protección de Datos, ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'calle Jorge Juan, 6 – 28001 Madrid.');
            // $linea++;
            

              $lineUltima=$linea-14;
            
              
       //$linea++;$linea++; 
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

          
        
        
        
            
            
            
            
        $filename = "Factura $numero.xls"; //save our workbook as this file name
        $registro=$this->facturas_->registrarFactura($numFactura, $fechaFactura,$ticket['cliente'],$filename);
        if (!$registro) return false;
         
        
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //
       
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        //force user to download the Excel file without writing it to server's HD
        //$objWriter->save('php://output');

        $objWriter->save('facturas/'.$filename);
        return $filename;
       
        }
       //aqui termina factura agrupada 
        
        //aqui inicio factura agrupadas por tickets 
         else {
        
        
        foreach($ticketsFactura as $kt => $ticket){
             
        //$ticket = $_POST['ticket'];
        $ticket = $this->tickets_->getTicketPorNumero($ticket, 'pe_boka');
        
        
        
        $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Ticket núm ' . $ticket['numero']);
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
        
        //unidades
       foreach ($ticket['unidades_pesos'] as $k => $v) {
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
            
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //productos unidades
            if ($v == "1" || $v=="3" ) {
              $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, $ticket['codigosProductos'][$k].'      ');
            $this->excel->getActiveSheet()->setCellValue('D' . $linea, $ticket['unidades'][$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
            
            $ticket['preciosUnitarios'][$k]=str_replace(",","",$ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['preciosUnitarios'][$k]);
            
            $ticket['precios'][$k]=str_replace(",","",$ticket['precios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['precios'][$k]);
            $totalFactura=$totalFactura+$ticket['precios'][$k];
            
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;
            $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
            if ($ticket['descuentos'][$k] !=0) {
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
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Su ventaja');
                $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
                $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $totalFactura=$totalFactura+$ticket['descuentos'][$k];
                $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
                $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }
            }
        }
       
        //pesos
       foreach ($ticket['unidades_pesos'] as $k => $v) {
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
            
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //productos pesos
            if ($v == "0" || $v=="4" ) { 
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, $ticket['codigosProductos'][$k].'      ');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->setCellValue('D' . $linea, $ticket['numUnidades'][$k].'      ');
            $this->excel->getActiveSheet()->getStyle('D' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
           
            $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            
            $ticket['pesos'][$k]=str_replace(",","",$ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $ticket['pesos'][$k]);
            
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
            
            $ticket['preciosUnitarios'][$k]=str_replace(",","",$ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['preciosUnitarios'][$k]);
            
            $ticket['precios'][$k]=str_replace(",","",$ticket['precios'][$k]);
            
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['precios'][$k]);
            $totalFactura=$totalFactura+$ticket['precios'][$k];
            $this->excel->getActiveSheet()->getStyle('G' . $linea)->getNumberFormat()->setFormatCode('#,##0.000');
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;  
            $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
            if ($ticket['descuentos'][$k] !=0) {
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
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Su ventaja');
                $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
                $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $totalFactura=$totalFactura+$ticket['descuentos'][$k];
                $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
                $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }
            
            }
       }
       
       //otros
       foreach ($ticket['unidades_pesos'] as $k => $v) {
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
            
            
            $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //productos entregas negativas
            
            
            if ($v == "2" ) { 
                $ticket['pesos'][$k]="";$ticket['unidades'][$k]=""; $ticket['productos'][$k]=  ucfirst(strtolower($ticket['productos'][$k]));

            $this->excel->getActiveSheet()->setCellValue('C' . $linea, $ticket['codigosProductos'][$k].'      ');
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->setCellValue('E' . $linea, $ticket['productos'][$k]);
            $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $ticket['pesos'][$k]=str_replace(",","",$ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $ticket['pesos'][$k]);
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
            $ticket['preciosUnitarios'][$k]=str_replace(",","",$ticket['preciosUnitarios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, $ticket['preciosUnitarios'][$k]);
            $ticket['precios'][$k]=str_replace(",","",$ticket['precios'][$k]);
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['precios'][$k]);
            $totalFactura=$totalFactura+$ticket['precios'][$k];
            $this->excel->getActiveSheet()->getStyle('H' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $linea++;  
            $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
            if ($ticket['descuentos'][$k] !=0) {
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
                $this->excel->getActiveSheet()->getStyle('C'.$linea.':I'.$linea)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('E' . $linea, 'Su ventaja');
                $this->excel->getActiveSheet()->getStyle('E'.$linea)->getFont()->setBold(true);
                $ticket['descuentos'][$k]=str_replace(",","",$ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('I' . $linea, $ticket['descuentos'][$k]);
                $this->excel->getActiveSheet()->setCellValue('F' . $linea, (int)$ticket['tiposIva'][$k]);
                $this->excel->getActiveSheet()->getStyle('F' . $linea.':G'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $totalFactura=$totalFactura+$ticket['descuentos'][$k];
                $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
                $this->excel->getActiveSheet()->getStyle('H' . $linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $linea++;
            }
            
            }
       }
      
       //Si no existen inicializamos variables para cada tipo de iva
       foreach ($ticket['tipoIvasSum'] as $k => $v) {
           $tipoIVA=(int)$v;
           //echo $tipoIVA.'<br />';
           $tipoIva[$tipoIVA]=$tipoIVA;
           if (!isset($base[$tipoIVA])) $base[$tipoIVA]=0;
           if (!isset($iva[$tipoIVA])) $iva[$tipoIVA]=0;
           if (!isset($bruto[$tipoIVA])) $bruto[$tipoIVA]=0;
        }
        //llemos los valores de este ticket y los sumamos a los valores existentes
        foreach ($ticket['tipoIvasSum'] as $k => $v) {
            $tipoIVA=(int)$v;
            $base[$tipoIVA]+=str_replace(",", "", $ticket['netos'][$k]);
            $iva[$tipoIVA]+=str_replace(",", "", $ticket['ivas'][$k]);
            $bruto[$tipoIVA]+=str_replace(",", "", $ticket['brutos'][$k]);
        }
     
        
        
       
         }

        $transporte=$_POST['transporte'];
        $ivaTransporte=21;
        if (!isset($iva[$ivaTransporte])) $iva[$ivaTransporte]=0;
        if (!isset($base[$ivaTransporte])) $base[$ivaTransporte]=0;   
        if (!isset($bruto[$ivaTransporte])) $bruto[$ivaTransporte]=0;                   
        
        
        $bruto[$ivaTransporte]+=$transporte;
        $valorIva=$transporte*$ivaTransporte/100;
        $valorIva=$valorIva/(1+$ivaTransporte/100);
        $valorIva=round($valorIva,2);
        //echo $valorIva;
        $iva[$ivaTransporte]+=$valorIva;
        $valorBase=$transporte-$valorIva;
        //echo $valorBase;
        $base[$ivaTransporte]+=$valorBase;
         
         //Anchos columnas
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(18.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(56.33*.68);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(17.83*.68);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(27.17*.68);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4.67*.68);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(5);

            //altura filas
            
           $pag=(int)(($linea+14)/54);
           $lineasProducto=$linea-14;
           $paginas=(int)($lineasProducto / 38)+1;
           $lineasProductoUltimaPagina= $lineasProducto % 38;
           
            
           //fijar las lineas con datos prodyctos con altura =20
           for($i=18;$i<$linea;$i++)
                $this->excel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
       
           for($i=0;$i<100;$i++){
           if($linea>43+37*$i && $linea<43+37*$i+12){
                for($j=$linea; $j<43+37*$i+12;$j++){
                   $this->excel->getActiveSheet()->getRowDimension($j)->setRowHeight(20);
                   $linea++;
                }
                break;
           }
           }
         
        //    $hasta=42;
        //    for ($k=0;$k<100;$k++){
        //    if ($linea >42*$k) {$hasta=42+$k*37;}
        //    }
        

        $hasta=37;
           for ($k=0;$k<100;$k++){
                if ($linea >37*$k) {$hasta=37+$k*35;}
           }
           
           for ($k=$linea;$k<$hasta;$k++){
               $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(20);
                   $linea++;
           }
           
           
          
          
           $linea++;
           //echo '$i '.$i. '$linea '.$linea. '$lineasProducto '.$lineasProducto.' $lineasProductoUltimaPagina '.$lineasProductoUltimaPagina;
          
    
            
           
            $this->excel->getActiveSheet()->getStyle('C'.($linea).':I'.$linea)->applyFromArray(array('borders' => array(
                    
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                    )
            )));
            
            
            

            $this->excel->getActiveSheet()->mergeCells('G'.$linea.':H'.$linea);
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'TOTAL PRODUCTO');
           $this->excel->getActiveSheet()->getStyle('G' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':H'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'Transporte');
                
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $_POST['transporte']);
            
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
            
          
            $totalFactura+=$_POST['transporte'];  
            
            

            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('F' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, 'B.I.');
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, 'TOTAL IVA');
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array(
                'rgb' => 'FCE9DA')));
                        //$this->excel->getActiveSheet()->getStyle('C19:I19')->getFont()->setBold(true);
            $border=array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                );
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
        
        
            $linea++;
           ksort($iva);
           $totales['base']=0;
              $totales['iva']=0;
              $totales['bruto']=0;
              
            foreach($iva as $k=>$v){
              if($base[$k]>0)  {
              $totales['base']+= $base[$k];
              $totales['iva']+= $iva[$k];
              $totales['bruto']+= $bruto[$k];
              
              
              $this->excel->getActiveSheet()->setCellValue('F' . $linea, $k);
              $this->excel->getActiveSheet()->setCellValue('G' . $linea, $base[$k]);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $iva[$k]);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $bruto[$k]);
             $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F'.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border)); 
            }
             $linea++;
            }
            $this->excel->getActiveSheet()->setCellValue('G' . $linea, $totales['base']);
             $this->excel->getActiveSheet()->setCellValue('H' . $linea, $totales['iva']);
             $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totales['bruto']);
              $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');
            $this->excel->getActiveSheet()->getStyle('G'.$linea.':I' . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         
        //    $this->excel->getActiveSheet()->getStyle('F'.$linea.':F'.$linea)->applyFromArray(array('borders' => $border));
        $this->excel->getActiveSheet()->getStyle('G'.$linea.':G'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('H'.$linea.':H'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('I'.$linea.':I'.$linea)->applyFromArray(array('borders' => $border
            ));
        $this->excel->getActiveSheet()->getStyle('F'.$linea.':I'.$linea)->getFont()->setBold(true);
      
            
            //pie
            $linea++;
            $linea++;
            $this->excel->getActiveSheet()->setCellValue('H' . $linea, 'TOTAL FACTURA');
            $this->excel->getActiveSheet()->setCellValue('I' . $linea, $totalFactura);
            $this->excel->getActiveSheet()->getStyle('I'.$linea)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H' . $linea.':I' . $linea)->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('I' . $linea)->getNumberFormat()->setFormatCode('#,##0.00 €');

            $this->excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 17);
            
            $this->excel->getProperties()->setTitle('Factura núm: '.$numFactura);
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
              //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'En cumplimiento de la Ley Orgánica 15/199, de proteción de datos de carácter personal, sus datos facilitados, figuran en un fichero');
            //   $linea++;
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'automatizado y protegido. Estos Datos no serán cedidos absolutamente a nadie y se utilizaran exclusivamente para estsblecer las facturas a');
            //   $linea++;
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'su nombre y para nuestros comunicados dirigidos a ustedes. En cualquier momento, pueden ejercer su derecho a la cancelación de sus datos ');
            //   $linea++; 
            //   $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            //   $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'de nuestro fichero, mediante comunicación por escrito.');
            


            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Informarle que  tratamos la  información que nos  facilita con el  fin de  realizar pedidos  y  gestionar la  facturación de los  productos y servicios  contratados.');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'Los  datos  proporcionados se  conservarán  mientras se  mantenga la  relación  comercial o  durante el  tiempo  necesario para  cumplir  con las  obligaciones');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'legales y  atender las posibles  responsabilidades que  pudieran  derivar del  cumplimiento de la  finalidad para la que los  datos fueron  recabados. Los datos ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'no se  cederán a  terceros  salvo en los casos  en que  exista una  obligación  legal. Usted  tiene  derecho a  obtener  información  de si  estamos  tratando sus ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'datos  personales,  por  lo  que   puede  ejercer  sus  derechos  de  acceso,  rectificación,  supresión  y  portabilidad  de  datos  y  oposición  y  limitación  a  su ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'tratamiento ante 181 Distribucions S.L. con CIF B66154964 Dirección Pg. Sant Joan, 181, local. 08037 Barcelona y Correo electrónico: info@jamonarium.com,');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'adjuntando copia de su DNI o documento  equivalente. Asimismo, y  especialmente si considera que no ha obtenido  satisfacción plena en el ejercicio de sus ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'derechos, podrá presentar una reclamación ante la autoridad nacional de control dirigiéndose a estos efectos a la Agencia Española de Protección de Datos, ');
            $linea++;
            $this->excel->getActiveSheet()->getStyle('C' . $linea)->getFont()->setSize($sizeFont);  
            $this->excel->getActiveSheet()->setCellValue('C' . $linea, 'calle Jorge Juan, 6 – 28001 Madrid.');
            // $linea++;
            
              $lineUltima=$linea-14;
            
              
       //$linea++;$linea++; 
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

       
        $filename = "Factura $numero.xls"; //save our workbook as this file name
        $registro=$this->facturas_->registrarFactura($numFactura, $fechaFactura,$ticket['cliente'],$filename);
        if (!$registro) return false;
         
        
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //
       
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        //force user to download the Excel file without writing it to server's HD
        //$objWriter->save('php://output');
        $objWriter->save('facturas/'.$filename);
        return $filename;
         } 
        //aqui termina factura agrupadas por tickets
    }
        
}
        
