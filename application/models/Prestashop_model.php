<?php
class Prestashop_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function existe($pedido){
            $sql="SELECT * FROM pe_orders_prestashop WHERE id='$pedido'";
            if($this->db->query($sql)->num_rows()>0) return true;
            return false;
        }
        
        //analiza datos entrada archivo Prestashop
        public function analisisDatos(){
            $reportes=array();
           $pedidos=$this->db->query("SELECT order_no FROM pe_datos_prestashop GROUP BY order_no")->result();
           foreach($pedidos as $k=>$v){
               $result=$this->db->query("SELECT * FROM pe_datos_prestashop WHERE order_no='".$v->order_no."'")->result();
               $total=0;
               $total_products=$result[0]->total_products;
               foreach($result as $k1=>$v1){
                  $total+=$v1->product_quantity/1000*$v1->product_price;
               }
               $total_products/=1000;
               $total/=1000;
               if(abs($total_products-$total)>0.5)
                   $reportes[]="En pedido ".$v->order_no." NO coincide total_products ($total_products) con suma de product_quantity x product_price ($total)";
               
           }
           foreach($reportes as $k=>$v){
               echo $v.'<br>';
           }
        }
        
        public function bajarExcelProductos($desde,$hasta,$inicio="",$final=""){
            $dato['productos']=$this->getProductos($desde,$hasta); 
            $dato['productosTotales']=$this->getProductosTotales($desde,$hasta); 
            
            $this->load->model('productos_');
            $dato['productos']=$this->productos_->ordenarArray($dato['productos']);
            
            $this->load->library('excel');
            
            $this->excel->setActiveSheetIndex(0);
            
            $inicio=  fechaEuropeaSinHora($inicio);
            $final=  fechaEuropeaSinHora($final);
      
        $this->excel->getActiveSheet()->setCellValue('A1', "PRODUCTOS VENDIDOS TIENDA ONLINE EN EL PERIODO"); 
        $this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('A2', "Desde: $inicio"); 
        $this->excel->getActiveSheet()->setCellValue('A3', "Hasta: $final"); 
        $this->excel->getActiveSheet()->setCellValue('A4', "Desde Pedido: $desde"); 
        $this->excel->getActiveSheet()->setCellValue('A5', "Hasta Pedido: $hasta"); 
        
        $filaInicial=7;
        $this->excel->getActiveSheet()->setCellValue("A$filaInicial", "Código"); 
        $this->excel->getActiveSheet()->setCellValue("B$filaInicial", "Nombre"); 
        $this->excel->getActiveSheet()->setCellValue("C$filaInicial", "Pedidos"); 
        $this->excel->getActiveSheet()->setCellValue("D$filaInicial", "Unidades"); 
        $this->excel->getActiveSheet()->setCellValue("E$filaInicial", "Importe (€)"); 
        $this->excel->getActiveSheet()->getStyle("C$filaInicial:E$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:E$filaInicial")->getFont()->setBold(true);

        
        
        for($i=0;$i<count($dato['productos']);$i++){
            $filaInicial++;
            $importe=number_format($dato['productos'][$i]['importe']/100,2,',','.');
            $this->excel->getActiveSheet()->setCellValue("A$filaInicial", $dato['productos'][$i]['codigo']); 
            $this->excel->getActiveSheet()->setCellValue("B$filaInicial", $dato['productos'][$i]['nombre']);
            $this->excel->getActiveSheet()->setCellValue("C$filaInicial", $dato['productos'][$i]['pedidos']);
            $this->excel->getActiveSheet()->setCellValue("D$filaInicial", $dato['productos'][$i]['unidades']);
            $this->excel->getActiveSheet()->setCellValue("E$filaInicial", $importe);
            $this->excel->getActiveSheet()->getStyle("E$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        }
        $filaInicial++;
        $this->excel->getActiveSheet()->setCellValue("A$filaInicial", count($dato['productos'])); 
        $this->excel->getActiveSheet()->setCellValue("C$filaInicial", $dato['productosTotales']['pedidos']); 
        $this->excel->getActiveSheet()->setCellValue("C$filaInicial", $dato['productosTotales']['unidades']); 
        $importe=number_format($dato['productosTotales']['importe']/100,2,',','.');
        $this->excel->getActiveSheet()->setCellValue("E$filaInicial", $importe); 
        $this->excel->getActiveSheet()->getStyle("E$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:E$filaInicial")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        
        
        
        
        $filename = "Productos PrestaShop.xls";
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
           
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        //force user to download the Excel file without writing it to server's HD
        //$objWriter->save('php://output');

        $objWriter->save('facturas/'.$filename);
        
        return   'facturas/'.$filename;  
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
           
            
            
        }
        
       public function getPedidosPS($inicio, $final){
           $sql="SELECT count(id) as numPedidos, min(id) as primerPedido, max(id) as ultimoPedido FROM pe_orders_prestashop WHERE LEFT(fecha,10)>='$inicio' AND LEFT(fecha,10)<='$final'";
           
                $row=$this->db->query($sql)->row();
                $numPedidos=$row->numPedidos;
                if($numPedidos==0){
                    $primerPedido=0;
                    $ultimoPedido=0;
                    $fechaPrimerPedido='';
                    $fechaUltimoPedido='';
                   return array('numPedidos'=>$numPedidos,
                           'primerPedido'=>$primerPedido,
                           'ultimoPedido'=>$ultimoPedido,
                           'fechaPrimerPedido'=>$fechaPrimerPedido,
                           'fechaUltimoPedido'=>$fechaUltimoPedido
                           );

                }
                $primerPedido=$row->primerPedido;
                $fechaPrimerPedido=$this->db->query("SELECT fecha FROM pe_orders_prestashop WHERE id='$primerPedido'")->row()->fecha;
                $ultimoPedido=$row->ultimoPedido;
                $fechaUltimoPedido=$this->db->query("SELECT fecha FROM pe_orders_prestashop WHERE id='$ultimoPedido'")->row()->fecha;
                return array('numPedidos'=>$numPedidos,
                        'primerPedido'=>$primerPedido,
                        'ultimoPedido'=>$ultimoPedido,
                        'fechaPrimerPedido'=>$fechaPrimerPedido,
                        'fechaUltimoPedido'=>$fechaUltimoPedido
                        );
           
       }
       
       public function getVentasPorProducto($desde, $hasta){
           //vemos distintos tipos iva
           $periodo="id_order>='$desde' AND id_order<='$hasta'";
           $sql="SELECT tipo_iva FROM pe_lineas_orders_prestashop  WHERE $periodo GROUP BY tipo_iva ORDER BY tipo_iva";
           $ivas=$this->db->query($sql)->result();
           //var_dump($ivas);
           $resultados=array();
           foreach($ivas as $k=>$v){
               $tipo_iva=$v->tipo_iva;
               $sql="SELECT tipo_iva, sum(importe) as importe, sum(iva) as iva FROM pe_lineas_orders_prestashop WHERE $periodo AND tipo_iva='$tipo_iva'";
               $row=$this->db->query($sql)->row();
               $resultados[]=array('tipo_iva'=>$row->tipo_iva, 'base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );
           }
           //totales
               $sql="SELECT  sum(importe) as importe, sum(iva) as iva FROM pe_lineas_orders_prestashop WHERE $periodo ";
                $row=$this->db->query($sql)->row();
               $resultadosTotal=array('base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );

           
          // var_dump($resultados);
           return array('resultados'=> $resultados, 'resultadosTotal'=>$resultadosTotal);
       }
       
       
       public function getProductos($desde,$hasta,$agrupar=false){
           
           $periodo="id_order>='$desde' AND id_order<='$hasta'";
           $sql="SELECT p.id_producto as id_producto,count(l.id_order) as pedidos, p.codigo_producto as codigo, p.nombre as nombre, sum(l.cantidad) as unidades, sum(l.importe) as importe FROM pe_lineas_orders_prestashop l 
                 LEFT JOIN pe_productos p ON p.id=l.id_pe_producto
                 WHERE $periodo  GROUP BY l.id_pe_producto ORDER BY p.codigo_producto";
           if($agrupar)
               $sql="SELECT p.id_producto as id_producto,count(l.id_order) as pedidos, p.codigo_producto as codigo, p.nombre as nombre, sum(l.cantidad) as unidades, sum(l.importe) as importe FROM pe_lineas_orders_prestashop l 
                 LEFT JOIN pe_productos p ON p.id=l.id_pe_producto
                 WHERE $periodo  GROUP BY p.id_producto ORDER BY p.id_producto";
           //echo $sql; 
           $result=$this->db->query($sql)->result();
           $productos=array();
           foreach ($result as $k=>$v){
               if(!$agrupar)
                    $productos[]=array('id_producto'=>$v->id_producto,'pedidos'=>$v->pedidos,'nombre'=>$v->nombre, 'codigo'=>$v->codigo,'unidades'=>$v->unidades,'importe'=>$v->importe);
               else{
                   $nombre=$v->nombre;
                   if(!$v->id_producto) $nombre="Sin código báscula";
                    $productos[]=array('id_producto'=>$v->id_producto,'pedidos'=>$v->pedidos,'nombre'=>$nombre, 'codigo'=>$v->id_producto,'unidades'=>$v->unidades,'importe'=>$v->importe);
               }

           }
           
            return $productos;
        }
       
        
        
        public function getProductosTotales($desde,$hasta){
           $periodo="id_order>='$desde' AND id_order<='$hasta'";
           $sql="SELECT count(id_order) as pedidos,sum(l.cantidad) as unidades, sum(l.importe) as importe FROM pe_lineas_orders_prestashop l 
                 WHERE $periodo ";
           $row=$this->db->query($sql)->row();
            return array('pedidos'=>$row->pedidos,'unidades'=>$row->unidades,'importe'=>$row->importe);
        }
       
       
        public function getVentasPorTiposIvaEntreFechas($inicio,$final){
            $this->db->query('SET SQL_BIG_SELECTS=1');
            $final=$final.' 23:59:59';
            $sql="SELECT l.tipo_iva as tipo_iva, o.total as total FROM pe_orders_prestashop o"
                    . " LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id "
                    . " WHERE fecha>='$inicio' AND fecha<='$final' GROUP BY l.tipo_iva";
            //log_message('INFO', $sql);
            $ivas=$this->db->query($sql)->result();
            $resultados=array();
            foreach($ivas as $k=>$v){
                if(is_null( $v->tipo_iva)) continue;
                $tipo_iva=$v->tipo_iva;
                $sql="SELECT l.tipo_iva, round(sum(l.importe*o.total_producto/o.total)) as importe,sum(descuento) as descuento, round(sum(l.iva*o.total_producto/o.total)) as iva FROM pe_orders_prestashop o"
                    . " LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id "
                    . " WHERE fecha>='$inicio' AND fecha<='$final' AND tipo_iva='$tipo_iva' AND l.valid=1"; // GROUP BY l.tipo_iva";
                //echo $sql.';<br>';
                $sql="SELECT l.tipo_iva, sum(l.importe_con_descuento) as importe,sum(descuento) as descuento, sum(l.iva_con_descuento) as iva FROM pe_orders_prestashop o"
                    . " LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id "
                    . " WHERE fecha>='$inicio' AND fecha<='$final' AND tipo_iva='$tipo_iva' AND l.valid=1"; // GROUP BY l.tipo_iva";
          
                $row=$this->db->query($sql)->row();
                $resultados[]=array('tipo_iva'=>$row->tipo_iva, 'descuento'=>$row->descuento, 'base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );
            }
            //totales
            $sql="SELECT round(sum(l.importe*o.total_producto/o.total)) as importe,sum(descuento) as descuento, round(sum(l.iva*o.total_producto/o.total)) as iva FROM pe_orders_prestashop o"
                    . " LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id "
                    . " WHERE fecha>='$inicio' AND fecha<='$final' AND l.valid=1";
            //echo $sql.';<br>';
            $sql="SELECT l.tipo_iva, sum(l.importe_con_descuento) as importe,sum(descuento) as descuento, sum(l.iva_con_descuento) as iva FROM pe_orders_prestashop o"
                    . " LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id "
                    . " WHERE fecha>='$inicio' AND fecha<='$final'  AND l.valid=1"; // GROUP BY l.tipo_iva";
          
            
            $row=$this->db->query($sql)->row();
            $resultadosTotal=array('descuento'=>$row->descuento, 'base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );
          // var_dump($resultados);
           return array('resultados'=> $resultados, 'resultadosTotal'=>$resultadosTotal); 
        }
        
        
       public function getVentasPorTiposIva($desde, $hasta){
           //vemos distintos tipos iva
           $periodo="id_order>='$desde' AND id_order<='$hasta'";
           $sql="SELECT tipo_iva FROM pe_lineas_orders_prestashop  WHERE $periodo GROUP BY tipo_iva ORDER BY tipo_iva";
           $ivas=$this->db->query($sql)->result();
           //var_dump($ivas);
           $resultados=array();
           foreach($ivas as $k=>$v){
               $tipo_iva=$v->tipo_iva;
               $sql="SELECT tipo_iva, sum(importe) as importe, sum(iva) as iva FROM pe_lineas_orders_prestashop WHERE $periodo AND tipo_iva='$tipo_iva'";
               log_message('INFO','-----------------  ' .$sql);
               $row=$this->db->query($sql)->row();
               $resultados[]=array('tipo_iva'=>$row->tipo_iva, 'base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );
           }
           //totales
            $sql="SELECT  sum(importe) as importe, sum(iva) as iva FROM pe_lineas_orders_prestashop WHERE $periodo ";
            $row=$this->db->query($sql)->row();
            $resultadosTotal=array('base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );

          // var_dump($resultados);
           return array('resultados'=> $resultados, 'resultadosTotal'=>$resultadosTotal);
       }
       
        public function getTransportesPorTiposIva($desde, $hasta){
           //vemos distintos tipos iva
           $periodo="id>='$desde' AND id<='$hasta'";
           $sql="SELECT tipo_iva_transporte FROM pe_orders_prestashop  WHERE $periodo GROUP BY tipo_iva_transporte ORDER BY tipo_iva_transporte";
           $ivas=$this->db->query($sql)->result();
           
           $resultados=array();
           foreach($ivas as $k=>$v){
               $tipo_iva_transporte=$v->tipo_iva_transporte;
               $sql="SELECT tipo_iva_transporte, sum(transporte) as transporte, sum(base_transporte) as base_transporte, sum(iva_transporte) as iva_transporte FROM pe_orders_prestashop WHERE $periodo AND tipo_iva_transporte='$tipo_iva_transporte'";
           
               $row=$this->db->query($sql)->row();
               $resultados[]=array('tipo_iva_transporte'=>$row->tipo_iva_transporte, 'transporte'=>$row->transporte, 'base_transporte'=>$row->base_transporte,'iva_transporte'=>$row->iva_transporte );
           }
           //totales
            $sql="SELECT  sum(transporte) as transporte, sum(base_transporte) as base_transporte, sum(iva_transporte) as iva_transporte FROM pe_orders_prestashop WHERE $periodo ";
            $row=$this->db->query($sql)->row();
            $resultadosTotal=array('transporte'=>$row->transporte, 'base_transporte'=>$row->base_transporte,'iva_transporte'=>$row->iva_transporte );

          // var_dump($resultados);
           return array('resultados'=> $resultados, 'resultadosTotal'=>$resultadosTotal);
       }
       
       public function getTransportesPorTiposIvaEntreFechas($inicio,$final){
            $final=$final.' 23:59:59';
            $sql="SELECT o.tipo_iva_transporte FROM pe_orders_prestashop o"
                    . " LEFT JOIN pe_lineas_orders_prestashop l ON l.id_order=o.id "
                    . " WHERE fecha>='$inicio' AND fecha<='$final' GROUP BY o.tipo_iva_transporte";
            //log_message('INFO', $sql);
            $ivas=$this->db->query($sql)->result();
            $resultados=array();
            foreach($ivas as $k=>$v){
                $tipo_iva_transporte=$v->tipo_iva_transporte;
                if(is_null( $tipo_iva_transporte)) continue;
                
                $sql="SELECT tipo_iva_transporte, sum(transporte) as transporte, sum(base_transporte) as base_transporte, sum(iva_transporte) as iva_transporte FROM pe_orders_prestashop o"
                    
                    . " WHERE fecha>='$inicio' AND fecha<='$final' AND tipo_iva_transporte='$tipo_iva_transporte' GROUP BY tipo_iva_transporte";
                //log_message('INFO', $sql);
                //echo $sql.'<br>';
                $row=$this->db->query($sql)->row();
               $resultados[]=array('tipo_iva_transporte'=>$row->tipo_iva_transporte, 'transporte'=>$row->transporte, 'base_transporte'=>$row->base_transporte,'iva_transporte'=>$row->iva_transporte );
            }
            //totales
            $sql="SELECT tipo_iva_transporte, sum(transporte) as transporte, sum(base_transporte) as base_transporte, sum(iva_transporte) as iva_transporte FROM pe_orders_prestashop o"
                   
                    . " WHERE fecha>='$inicio' AND fecha<='$final'"; 
               
            $row=$this->db->query($sql)->row();
           $resultadosTotal=array('transporte'=>$row->transporte, 'base_transporte'=>$row->base_transporte,'iva_transporte'=>$row->iva_transporte );

          // var_dump($resultados);
           return array('resultados'=> $resultados, 'resultadosTotal'=>$resultadosTotal);
        }
        
         

       
       
        public function getResultadosTransportesPorTiposIva($desde, $hasta){
           //vemos distintos tipos iva
           $periodo="id_order>='$desde' AND id_order<='$hasta'";
           $sql="SELECT tipo_iva FROM pe_lineas_orders_prestashop  WHERE $periodo GROUP BY tipo_iva ORDER BY tipo_iva";
           $ivas=$this->db->query($sql)->result();
           //var_dump($ivas);
           $resultados=array();
           foreach($ivas as $k=>$v){
               $tipo_iva=$v->tipo_iva;
               $sql="SELECT tipo_iva, sum(importe) as importe, sum(iva) as iva FROM pe_lineas_orders_prestashop WHERE $periodo AND tipo_iva='$tipo_iva'";
               $row=$this->db->query($sql)->row();
               $resultados[]=array('tipo_iva'=>$row->tipo_iva, 'base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );
           }
           //totales
            $sql="SELECT  sum(importe) as importe, sum(iva) as iva FROM pe_lineas_orders_prestashop WHERE $periodo ";
            $row=$this->db->query($sql)->row();
            $resultadosTotal=array('base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );

          // var_dump($resultados);
           return array('resultados'=> $resultados, 'resultadosTotal'=>$resultadosTotal);
       }
       
       function getPedidoActualLineas($pedido,$codigo_producto,$cantidad){
           $id_pe_producto=$this->productos_->getId_pe_producto($codigo_producto);
           $sql="SELECT id,valid,cantidad,precio,importe,id_pe_producto FROM pe_lineas_orders_prestashop WHERE id_order='$pedido' AND id_pe_producto='$id_pe_producto' AND cantidad='$cantidad' LIMIT 1";
           // mensaje('getPedidoActualLineas '.$sql);
           if($this->db->query($sql)->num_rows()==0) return 0;
           $resultLineas=$this->db->query($sql)->result();
          // echo $resultLineas->valid.'<br>';
           return $resultLineas;
       }
       
       function getPedidoActual($pedido){
           $sql="SELECT id,descuento,total FROM pe_orders_prestashop WHERE id='$pedido'";
           if($this->db->query($sql)->num_rows()){
            $row=$this->db->query($sql)->row();
            return array('id'=>$row->id,'descuento'=>$row->descuento,'total'=>$row->total);
           }
           return array();
       }
       
}
        
