<?php
class Tickets_ extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->load->library('session');
                $this->load->helper(array('date', 'url','numeros'));
                ini_set('memory_limit','512M');
                $this->db2=$this->load->database('pernil181bcn',TRUE);
        }
        public function getProductos($inicio,$final){
            //cambiado  SUM(bt20)+SUM(bt30) as importe ,  por  SUM(bt20) as importe , 
            $sql="SELECT SNR1 as producto, 
                        
                         sum(pos1) as unidades,
                         sum(gew1) as peso, 
                         SUM(bt20)-SUM(bt40)+SUM(bt30) as base, 
                         SUM(bt40) as iva, 
                         SUM(bt20) as importe , 
                         FLOOR(avg(mwsa)) as ivaPorcentaje 
                   FROM `pe_boka` b 
                   WHERE STYP=2 AND 
                         
                         zeis>='$inicio' AND
                         zeis<='$final 23:59:59'
                   GROUP BY snr1
                   ";
            $query=$this->db->query($sql);
            $productos=array();
            
            foreach($query->result() as $k=>$v){
                
                $codigo=$v->producto;
                $sql="SELECT nombre FROM pe_productos WHERE id_producto='$codigo'";
                $query2=$this->db->query($sql);
                
                if ($query2->num_rows())
                    $nombre=$query2->row()->nombre ; 
                else {
                    $nombre="";
                }
                $productos[]=array('nombre'=>$nombre, 'codigo'=>$v->producto,'unidades'=>$v->unidades,'peso'=>$v->peso,'importe'=>$v->importe,'base'=>$v->base,'iva'=>$v->iva,'ivaPorcentaje'=>$v->ivaPorcentaje);
            }
            
            return $productos;
        }
       
        
        
        public function getProductosTotales($inicio,$final){
            $sql="SELECT count(snr1) as producto,
                         sum(pos1) as unidades,
                         sum(gew1) as peso, 
                         SUM(bt20)-SUM(bt40)+sum(bt30) as base, 
                         SUM(bt40) as iva, 
                         SUM(bt20) as importe,
                         FLOOR(avg(mwsa)) as ivaPorcentaje
                   FROM `pe_boka` b
                   WHERE STYP=2 AND 
                         zeis>='$inicio' AND
                         zeis<='$final 23:59:59'
                   ";
                   
            $query=$this->db->query($sql);
            return $query;
        }
        
        function buscarGrupoFamilia($codigo_producto){
            
             if(strlen($codigo_producto)==13)
                $sql="SELECT gr.nombre_grupo as grupo, fa.nombre_familia as familia "
                        . " FROM pe_productos pr "
                        . " LEFT JOIN pe_grupos gr ON gr.id_grupo=pr.id_grupo"
                        . " LEFT JOIN pe_familias fa ON fa.id_familia=pr.id_familia"
                        . " WHERE codigo_producto='$codigo_producto'";
             else
                 $sql="SELECT gr.nombre_grupo as grupo, fa.nombre_familia as familia "
                        . " FROM pe_productos pr "
                        . " LEFT JOIN pe_grupos gr ON gr.id_grupo=pr.id_grupo"
                        . " LEFT JOIN pe_familias fa ON fa.id_familia=pr.id_familia"
                        . " WHERE id_producto='$codigo_producto'";
           
            if($this->db->query($sql)->num_rows()>0){
            $row=$this->db->query($sql)->row();
            return array('grupo'=>$row->grupo,'familia'=>$row->familia);  
            }
            else array('grupo'=>'----','familia'=>'-----');  
        }
        
         public function bajarExcelProductos($cabecera,$titulos,$encabezados,$pies){
           
            $this->load->library('excel');
            
            $this->excel->setActiveSheetIndex(0);
        
      
        $this->excel->getActiveSheet()->setCellValue('A1', $cabecera); 
        $this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
        
        $linea=2;
        
        foreach($titulos as $k=>$v){
            $this->excel->getActiveSheet()->setCellValue('A'.$linea, $v); 
            $linea++;
        }
        
           
        $filaInicial=$linea+1;
        $columna='A';
        
        
        for($i=0;$i<count($encabezados);$i++){
            if($i==1){
                $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", 'Grupo'); 
                $columna++;
                $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", 'Familia'); 
                $columna++;
            }
            $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", $encabezados[$i]); 
            $columna++;
        }
        
       


        $this->excel->getActiveSheet()->getStyle("E$filaInicial:G$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:$columna$filaInicial")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("E")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $filaInicial++;
        $columna='A';
       
        $result=$this->db->query("SELECT * FROM pe_productos_excel WHERE 1")->result();
        //$final=987;
        foreach($result as $k=>$v){
            $this->excel->getActiveSheet()->setCellValue("A$filaInicial", $v->codigo_producto); 
            $this->excel->getActiveSheet()->getStyle("A$filaInicial")->getNumberFormat()->setFormatCode('0000000000000');

            $this->excel->getActiveSheet()->setCellValue("D$filaInicial", $v->nombre); 
            $this->excel->getActiveSheet()->setCellValue("E$filaInicial", $v->cantidad); 
            if($v->peso!=0){
                $this->excel->getActiveSheet()->setCellValue("F$filaInicial", $v->peso/1000); 
                $this->excel->getActiveSheet()->getStyle("F$filaInicial")->getNumberFormat()->setFormatCode('#,##0.000');
            }
            $this->excel->getActiveSheet()->setCellValue("G$filaInicial", $v->importe/100); 
            $this->excel->getActiveSheet()->getStyle("G$filaInicial")->getNumberFormat()->setFormatCode('#,##0.00');
            $grupoFamilia=$this->buscarGrupoFamilia($v->codigo_producto);
            $this->excel->getActiveSheet()->setCellValue("B$filaInicial", $grupoFamilia['grupo']);
            $this->excel->getActiveSheet()->setCellValue("C$filaInicial", $grupoFamilia['familia']);
            $filaInicial++;
        }
        
        
         $columna='A';
        for($i=0;$i<count($pies);$i++){
            if($i==1){$columna++;$columna++;}
            $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", $pies[$i]); 
            $columna++;
        }
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:H$filaInicial")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle("G1:G$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("A1:A$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        
        
        
        $cabecera=str_replace('/','_',$cabecera);
        $filename = $cabecera.".xls";
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
       
         public function bajarExcelProductosTotales($cabecera,$titulos,$encabezados,$pies,$lineas){
           
            $this->load->library('excel');
            
            $this->excel->setActiveSheetIndex(0);
            
          //  $inicio=  fechaEuropeaSinHora($inicio);
          //  $final=  fechaEuropeaSinHora($final);
      
        $this->excel->getActiveSheet()->setCellValue('A1', $cabecera); 
        $this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
        
        $linea=2;
        
        foreach($titulos as $k=>$v){
            $this->excel->getActiveSheet()->setCellValue('A'.$linea, $v); 
            $linea++;
        }
        
        
       /* 
        $filaInicial=7;
        $this->excel->getActiveSheet()->setCellValue("A$filaInicial", "Código"); 
        $this->excel->getActiveSheet()->setCellValue("B$filaInicial", "Nombre"); 
        $this->excel->getActiveSheet()->setCellValue("C$filaInicial", "Partidas"); 
        $this->excel->getActiveSheet()->setCellValue("D$filaInicial", "Peso (Kg)"); 
        $this->excel->getActiveSheet()->setCellValue("E$filaInicial", "Importe (€)"); 
        $this->excel->getActiveSheet()->getStyle("C$filaInicial:E$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:E$filaInicial")->getFont()->setBold(true);
*/
        
             $filaInicial=$linea+1;
             $columna='A';
        
        
        for($i=0;$i<count($encabezados);$i++){
            if($i==1){
                $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", 'Grupo'); 
                $columna++;
                $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", 'Familia'); 
                $columna++;
            }
            
            $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", $encabezados[$i]); 
            $columna++;
        }
        $this->excel->getActiveSheet()->setCellValue("H$filaInicial", 'Importe (€)');
        $this->excel->getActiveSheet()->setCellValue("F$filaInicial", 'Unidades');
        $this->excel->getActiveSheet()->setCellValue("G$filaInicial", 'Peso (Kg)');
        
        $this->excel->getActiveSheet()->getStyle("E$filaInicial:G$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:$columna$filaInicial")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("E")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $filaInicial++;
        $columna='A';
        $final=count($lineas);
        //$final=987;
        for($i=0;$i<$final;$i++){
            
            $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", $lineas[$i]); 
            //$this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", count($lineas)); 
            if($columna=='A') {
                $grupoFamilia=$this->buscarGrupoFamilia($lineas[$i]);
            }
            
            $columna++;
            if($columna=='B'){
                $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", $grupoFamilia['grupo']);
                $columna++;
                $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", $grupoFamilia['familia']);
                $columna++;
            }
            if($columna=='I') {
                $filaInicial++;$columna='A';
                $formateo='#,##0.000';
                if($encabezados[3]=='Unidades') $formateo='#,##0';
                $this->excel->getActiveSheet()->getStyle("F$filaInicial")->getNumberFormat()->setFormatCode($formateo);
                $this->excel->getActiveSheet()->getStyle("G$filaInicial")->getNumberFormat()->setFormatCode('#,##0.00');

            }
        }
        
         $this->excel->getActiveSheet()->getStyle("F5:F$final")->getNumberFormat()->setFormatCode('#,##0');
         $this->excel->getActiveSheet()->getStyle("G5:G$final")->getNumberFormat()->setFormatCode('#,##0.000');
          $this->excel->getActiveSheet()->getStyle("H5:H$final")->getNumberFormat()->setFormatCode('#,##0.00');
        
        $filaInicial++;
         $columna='A';
        for($i=0;$i<count($pies);$i++){
            if($i==1){$columna++;$columna++;}
            $this->excel->getActiveSheet()->setCellValue("$columna$filaInicial", $pies[$i]); 
            $columna++;
        }
        $this->excel->getActiveSheet()->setCellValue("H$filaInicial", $pies[$i-1]);
        $this->excel->getActiveSheet()->setCellValue("G$filaInicial", "");
        
        $this->excel->getActiveSheet()->getStyle("A$filaInicial:H$filaInicial")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("A$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle("G1:G$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle("A1:A$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $this->excel->getActiveSheet()->getStyle("H1:H$filaInicial")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


     
        
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        
        
        
        $cabecera=str_replace('/','_',$cabecera);
        $filename = $cabecera.".xls";
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
        
        public function existenTickets($fecha){
            $sql="SELECT * FROM pe_boka WHERE LEFT(ZEIS,10)='$fecha'";
            $result=$this->db->query($sql)->num_rows();
            return $result;
        }
        
         public function getDatosTicket(){
             $numTicket=$_POST['ticket'];
             
             $ticket=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
             
             $textoTicket="";
           // $textoTicket="<h3>Ticket núm ".$ticket['numero'].' '.$ticket['fecha']."</h3>";
            $textoTicket.='<div class="row">'.'<div class="col-md-12">'.'<table class="table ticket" >';
            $textoTicket.='<thead>'.'<tr><th colspan="3" class="col-md-12 izquierda">'.$ticket['modo'].'</th></tr>';
            if($ticket['cliente']!==""){
                $textoTicket.='<tr><td colspan="3" class="col-md-12 izquierda ">Núm Cliente: '.$ticket['cliente'].'</td></tr>';
                $textoTicket.='<tr><td colspan="3" class="col-md-12 izquierda ">'.$ticket['nombreCliente'].'</td></tr>';
                $textoTicket.='<tr><td colspan="3" class="col-md-12 izquierda "style=" border-bottom:1px solid black ">'.$ticket['empresa'].'</td></tr>';
            }
                $textoTicket.='<tr >';
                $textoTicket.='<td class="col-md-4 izquierda">Spdto '.$ticket['subDepartamento'] .'</td>';
                $textoTicket.='<td class="col-md-4 centro">Caja '.$ticket['numCaja'] .'</td>';
                $textoTicket.='<td class="col-md-4 ">#'.$ticket['referencia'] .'</td>';
                $textoTicket.='</tr >';
                
                $textoTicket.='<tr >';
                $textoTicket.='<td class="col-md-4 izquierda">'.$ticket['fecha'] .'</td>';
                $textoTicket.='<td class="col-md-4 centro">'.$ticket['numero'] .'/'.$ticket['numCaja'].'</td>';
                $textoTicket.='<td class="col-md-4 ">Depe '.$ticket['dependiente'] .'</td>';
                $textoTicket.='</tr >';
                
                $textoTicket.='</thead>';
                $textoTicket.='</table>';
                $textoTicket.='</div>';
                
                $textoTicket.='<div class="row">';
                if ($ticket['piezas']) {
                    $textoTicket.='<div class="col-md-12">';
                    $textoTicket.='<table class="table ticket" >';
                    $textoTicket.='<thead>';
                    $textoTicket.='<tr style="border:2px solid grey;">';
                    $textoTicket.='<th class="col-md-3">Pza.</th>';
                    $textoTicket.='<th class="col-md-3">I.V.A.</th>';
                    $textoTicket.='<th class="col-md-3">€/Pza</th>';
                    $textoTicket.='<th class="col-md-3">€</th>';
                    $textoTicket.='</tr> ';
                    $textoTicket.='</thead>';
                    $textoTicket.='<tbody>';
                    foreach ($ticket['unidades_pesos'] as $k => $v) {
                        if ($v =="1" || $v=="3" ) { 
                            $textoTicket.='<tr>';
                            $textoTicket.=    '<th colspan="4" class="col-md-12 izquierda">'.$ticket['productos'][$k].'</th>';
                            $textoTicket.='<tr>';
                            
                            $textoTicket.='<tr>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['unidades'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['tiposIva'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['preciosUnitarios'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['precios'][$k].'</td>';
                            $textoTicket.='</tr>';
                            
                            if ($ticket['descuentos'][$k] !=0) {   
                             $textoTicket.='<tr>';
                             $textoTicket.=    '<td colspan="3" class="col-md-3 izquierda">SU ventaja</td>';
                                
                             $textoTicket.=   '<td class="col-md-3">'.$ticket['descuentos'][$k].'</td>';
                            $textoTicket.='</tr>';
                            
                           }
                            
                        }
                    }
                    $textoTicket.='</tbody>';
                    $textoTicket.='</table>';
                    $textoTicket.='</div>';
                }
                if ($ticket['pesados']){
                    $textoTicket.='<div class="col-md-12">';
                    $textoTicket.='<table class="table ticket" >';
                    $textoTicket.='<thead>';
                    $textoTicket.='<tr style="border:2px solid grey;">';
                    $textoTicket.='<th class="col-md-3">Kg</th>';
                    $textoTicket.='<th class="col-md-3">I.V.A.</th>';
                    $textoTicket.='<th class="col-md-3">€/Kg</th>';
                    $textoTicket.='<th class="col-md-3">€</th>';
                    $textoTicket.='</tr> ';
                    $textoTicket.='</thead>';
                    $textoTicket.='<tbody>';
                    foreach ($ticket['unidades_pesos'] as $k => $v) {
                        if ($v =="0" || $v=="4" ) { 
                            $textoTicket.='<tr>';
                            $textoTicket.=    '<th colspan="4" class="col-md-12 izquierda">'.$ticket['productos'][$k].'</th>';
                            $textoTicket.='<tr>';
                            
                            $textoTicket.='<tr>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['unidades'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['tiposIva'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['preciosUnitarios'][$k].'</td>';
                            $textoTicket.='<td class="col-md-3">'.$ticket['precios'][$k].'</td>';
                            $textoTicket.='</tr>';
                            
                            if ($ticket['descuentos'][$k] !=0) {   
                             $textoTicket.='<tr>';
                             $textoTicket.=    '<td colspan="3" class="col-md-3 izquierda">SUS ventaja</td>';
                             $textoTicket.=   '<td class="col-md-3">'.$ticket['descuentos'][$k].'</td>';
                             $textoTicket.='</tr>';
                           }
                            
                        }
                    }
                    $textoTicket.='</tbody>';
                    $textoTicket.='</table>';
                    $textoTicket.='</div>';
                }
                $textoTicket.='</div>'; 
                
                // añadir al final productos las entragas negativas cod 999998   
                if(true){
                $textoTicket.='<div class="row">';
                    $textoTicket.='<div class="col-md-12">';
                        $textoTicket.='<table class="table ticket" >';
                            $textoTicket.='<tbody>';
                                    foreach ($ticket['unidades_pesos'] as $k => $v) { 
                                        if ( $v=='2') { 
                                            $textoTicket.='<tr>';
                                             $textoTicket.=   '<th colspan="4" class="col-md-12 izquierda">'.ucfirst(strtolower($ticket['productos'][$k])).'</th>';
                                            $textoTicket.='</tr>';
                                            $textoTicket.='<tr>';
                                                $textoTicket.='<td class="col-md-3">'.$ticket['tiposIva'][$k].'</td>';
                                                $textoTicket.='<td class="col-md-3">'.$ticket['precios'][$k].'</td>';
                                            $textoTicket.='</tr>';
                                        } 
                                    }
                                $textoTicket.='</tbody>';
                        $textoTicket.='</table> ';   
                    $textoTicket.='</div>';
                   $textoTicket.='</div>';   
                }
                
                $textoTicket.='<div class="row">';
                $textoTicket.='<div class="col-md-12">';
                $textoTicket.='<table class="table ticket" >';
                $textoTicket.='<tbody>';

                $textoTicket.='<tr>';
                $textoTicket.= '<th  class="col-md-3 izquierda ticketTotal">';
                $textoTicket.= $ticket['numPartidasTicket'].' Part';
                $textoTicket.= '</th>';
                $textoTicket.='<th class="col-md-3 centro ticketTotal">';
                $textoTicket.= 'Suma';
                $textoTicket.='</th>';
                $textoTicket.='<th class="col-md-3 centro ticketTotal">';
                $textoTicket.= '€';
                $textoTicket.='</th>';
                $textoTicket.= '<th class="col-md-3" style="font-size: 20px">';
                $textoTicket.= $ticket['totalTicket'];
                $textoTicket.= '</th>';
                $textoTicket.='</tr>';
                
                ksort($ticket['formaPago']);
                
                foreach($ticket['formaPago'] as $k => $v) {
                    $textoTicket.='<tr>';
                    $textoTicket.='<td colspan="2" class="col-md-3 izquierda">';
                    $textoTicket.= $ticket['formaPago'][$k];
                    $textoTicket.='</td>';
                    $textoTicket.='<td class="col-md-3 centro">';
                    $textoTicket.=$ticket['importeFormaPago'][$k]!=""?"€":"" ;
                    $textoTicket.='</td>';
                    $textoTicket.='<td class="col-md-3">';
                    $textoTicket.=$ticket['importeFormaPago'][$k];
                    $textoTicket.='</td>';
                    $textoTicket.='</tr> ';   
                }
                
                $textoTicket.='<tr>';
                $textoTicket.='<td colspan="4" class="col-md-3 izquierda">';
                $textoTicket.='En la suma se incluye';
                $textoTicket.='</td>';
                  
                $textoTicket.='</tr>';
                $textoTicket.='<tr>';
                $textoTicket.='<td class="col-md-3 izquierda">';
                $textoTicket.='</td>';
                $textoTicket.='<td class="col-md-3">';
                $textoTicket.=    'I.V.A.';
                $textoTicket.='</td>';
                $textoTicket.='<td class="col-md-3">';
                $textoTicket.=    'Neto';
                $textoTicket.='</td>';
                $textoTicket.='<td class="col-md-3">';
                $textoTicket.=    'Bruto';
                $textoTicket.='</td>';
                $textoTicket.='</tr>';
                
                foreach ($ticket['tipoIvasSum'] as $k => $v) { 
                    $textoTicket.='<tr>';
                    $textoTicket.='<td colspan="4" class="col-md-3 izquierda">'.$v.'% '.$ticket['textos'][$k] .'</td>';
                    $textoTicket.='</tr>';
                    $textoTicket.='<tr>';
                    $textoTicket.='<td class="col-md-3"></td>';
                    $textoTicket.='<td class="col-md-3">'.$ticket['ivas'][$k].'</td>';
                    $textoTicket.='<td class="col-md-3">'.$ticket['netos'][$k].'</td>';
                    $textoTicket.='<td class="col-md-3">'.$ticket['brutos'][$k].'</td>';
                    $textoTicket.='</tr>';
                }
                
                if ($ticket['sumaIvas']>0){  
                    $textoTicket.='<tr>';
                    $textoTicket.='<td class="col-md-3">Suma</td>';
                    $textoTicket.='<td style="border-top:1px solid black" class="col-md-3">'.$ticket['sumaIvas'].'</td>';
                    $textoTicket.='<td class="col-md-3"></td>';
                    $textoTicket.='<td class="col-md-3"></td>';
                    $textoTicket.='</tr>';
                 } 
                
                $textoTicket.='<tr>';
                $textoTicket.='<td colspan="4" class="col-md-3 izquierda">'.number_format($ticket['fechaCierre'],2).'</td>';
                $textoTicket.='</tr>';
                $textoTicket.='<tr>';
                $textoTicket.='<td colspan="4" class="col-md-3 izquierda">ATES PER '.$ticket['nombreDependiente'].'</td>';
                $textoTicket.='</tr>  ';
                
                $textoTicket.='</tbody>';
                $textoTicket.='</table >';
                $textoTicket.='</div >';
                $textoTicket.='</div >';
                
                $textoTicket.='</div>';
            return $textoTicket;
        }
        
        public function getDatosTicketNumFechaTickets(){
            $num_ticket=$_POST['numTicket'];
            $fecha=$_POST['fecha'];
            $sql="SELECT id FROM pe_tickets WHERE num_ticket='$num_ticket' AND fecha>='$fecha' LIMIT 1";
            log_message('INFO','-------------------------------'.$sql);
            $_POST['id']=$this->db->query($sql)->row()->id;
            return $this->getDatosTicketIdTickets();
        }
         
        public function getNumTicket($id, $base){
            // mensaje('getNumTicket id '.$id);
            $db=$base==1?$this->db:$this->db2;

            $sql="SELECT num_ticket,fecha FROM pe_tickets WHERE id='$id'";
            // mensaje('getNumTicket $sql '.$sql);
            if($this->db->query($sql)->num_rows()==0){
                return 0;
            }
            
            $result=$db->query($sql)->row();
            // mensaje('$result->num_ticket '.$result->num_ticket);
            // mensaje('$result->fecha '.$result->fecha);
            
            $numTicket=$result->num_ticket.' '.$result->fecha;
            return $numTicket;
        }

        public function htmlTicket($id,$base){
            $db=$base==1?$this->db:$this->db2;
            // mensaje('htmlTicket id '.$id);
            // mensaje('htmlTicket base '.$base);
            $numTicket=$this->getNumTicket($id, $base);
            if(!$numTicket) return "No se ha encontrado el ticket";
            
            
            $ticket=$this->getTicket($numTicket,$base);
            
            if(count($ticket)==0) return array('no_existe'=>$numTicket);
            
           $textoTicket="";
           //encabezamiento ticket
           $textoTicket.='<div class="row">'.'<div class="col-md-12">'.'<table class="table ticket" >';
           $textoTicket.='<thead>'.'<tr><th colspan="3" class="col-md-12 izquierda">'.$ticket['modo'].'</th></tr>';
           //datos cliente, si lo hay
           if($ticket['cliente']!==""){
               $textoTicket.='<tr><td colspan="3" class="col-md-12 izquierda ">Núm Cliente: '.$ticket['cliente'].'</td></tr>';
               $textoTicket.='<tr><td colspan="3" class="col-md-12 izquierda ">'.$ticket['nombreCliente'].'</td></tr>';
               $textoTicket.='<tr><td colspan="3" class="col-md-12 izquierda "style=" border-bottom:1px solid black ">'.$ticket['empresa'].'</td></tr>';
           }
               //referencia ticket, fecha, caja 
               $textoTicket.='<tr >';
               $textoTicket.='<td class="col-md-4 izquierda">Spdto '.$ticket['subDepartamento'] .'</td>';
               $textoTicket.='<td class="col-md-4 centro">Caja '.$ticket['numCaja'] .'</td>';
               $textoTicket.='<td class="col-md-4 ">#'.$ticket['referencia'] .'</td>';
               $textoTicket.='</tr >';
               
               $textoTicket.='<tr >';
               $textoTicket.='<td class="col-md-4 izquierda">'.$ticket['fecha'] .'</td>';
               $textoTicket.='<td class="col-md-4 centro">'.$ticket['numero'] .'/'.$ticket['numCaja'].'</td>';
               $textoTicket.='<td class="col-md-4 ">Depe '.$ticket['dependiente'] .'</td>';
               $textoTicket.='</tr >';
               
               $textoTicket.='</thead>';
               $textoTicket.='</table>';
               $textoTicket.='</div>';

               //productos
               $textoTicket.='<div class="row">';
               //ventas a piezas (unidades)
               if ($ticket['piezas']) {
                   $textoTicket.='<div class="col-md-12">';
                   $textoTicket.='<table class="table ticket" >';
                   $textoTicket.='<thead>';
                   $textoTicket.='<tr style="border:2px solid grey;">';
                   $textoTicket.='<th class="col-md-3">Pza.</th>';
                   $textoTicket.='<th class="col-md-3">I.V.A.</th>';
                   $textoTicket.='<th class="col-md-3">€/Pza</th>';
                   $textoTicket.='<th class="col-md-3">€</th>';
                   $textoTicket.='</tr> ';
                   $textoTicket.='</thead>';
                   $textoTicket.='<tbody>';
                   foreach ($ticket['unidades_pesos'] as $k => $v) {
                       if ($v =="1" || $v=="3" ) { 
                           $textoTicket.='<tr>';
                           $textoTicket.=    '<th colspan="4" class="col-md-12 izquierda">'.$ticket['productos'][$k].'</th>';
                           $textoTicket.='<tr>';
                           
                           $textoTicket.='<tr>';
                           $textoTicket.='<td class="col-md-3">'.$ticket['unidades'][$k].'</td>';
                           $textoTicket.='<td class="col-md-3">'.$ticket['tiposIva'][$k].'</td>';
                           $textoTicket.='<td class="col-md-3">'.$ticket['preciosUnitarios'][$k].'</td>';
                           $textoTicket.='<td class="col-md-3">'.$ticket['precios'][$k].'</td>';
                           $textoTicket.='</tr>';
                           

                           if ($ticket['descuentos'][$k] !=0) {   
                            $textoTicket.='<tr>';
                            $textoTicket.=    '<td colspan="3" class="col-md-3 izquierda">Su ventaja</td>';
                               
                            $textoTicket.=   '<td class="col-md-3">'.$ticket['descuentos'][$k].'</td>';
                           $textoTicket.='</tr>';
                           
                          }
                           if ($ticket['otrosDescuentos'][$k] !=0) {   
                            $textoTicket.='<tr>';
                            $textoTicket.=    '<td colspan="3" class="col-md-3 izquierda">Su ventaja BT11</td>';
                               
                            $textoTicket.=   '<td class="col-md-3">'.$ticket['otrosDescuentos'][$k].'</td>';
                           $textoTicket.='</tr>';
                           
                          }

                          /*
                          if ($ticket['anulaciones'][$k] !=0) {   
                            $textoTicket.='<tr>';
                            $textoTicket.=    '<td colspan="3" class="col-md-3 izquierda">Anulación</td>';
                               
                            $textoTicket.=   '<td class="col-md-3">'.$ticket['anulaciones'][$k].'</td>';
                           $textoTicket.='</tr>';
                           
                          }
                          */
                           
                       }
                   }
                   $textoTicket.='</tbody>';
                   $textoTicket.='</table>';
                   $textoTicket.='</div>';
               }
               //ventas a peso 
               if ($ticket['pesados']){
                   $textoTicket.='<div class="col-md-12">';
                   $textoTicket.='<table class="table ticket" >';
                   $textoTicket.='<thead>';
                   $textoTicket.='<tr style="border:2px solid grey;">';
                   $textoTicket.='<th class="col-md-3">Kg</th>';
                   $textoTicket.='<th class="col-md-3">I.V.A.</th>';
                   $textoTicket.='<th class="col-md-3">€/Kg</th>';
                   $textoTicket.='<th class="col-md-3">€</th>';
                   $textoTicket.='</tr> ';
                   $textoTicket.='</thead>';
                   $textoTicket.='<tbody>';
                   foreach ($ticket['unidades_pesos'] as $k => $v) {
                       if ($v =="0" || $v=="4" ) { 
                           $textoTicket.='<tr>';
                           $textoTicket.=    '<th colspan="4" class="col-md-12 izquierda">'.$ticket['productos'][$k].'</th>';
                           $textoTicket.='<tr>';
                           
                           $textoTicket.='<tr>';
                           $textoTicket.='<td class="col-md-3">'.$ticket['unidades'][$k].'</td>';
                           $textoTicket.='<td class="col-md-3">'.$ticket['tiposIva'][$k].'</td>';
                           $textoTicket.='<td class="col-md-3">'.$ticket['preciosUnitarios'][$k].'</td>';
                           $textoTicket.='<td class="col-md-3">'.$ticket['precios'][$k].'</td>';
                           $textoTicket.='</tr>';
                           
                           if ($ticket['descuentos'][$k] !=0) {   
                            $textoTicket.='<tr>';
                            $textoTicket.=    '<td colspan="3" class="col-md-3 izquierda">Su ventaja</td>';
                            $textoTicket.=   '<td class="col-md-3">'.$ticket['descuentos'][$k].'</td>';
                            $textoTicket.='</tr>';
                          }
                          if ($ticket['otrosDescuentos'][$k] !=0) {   
                            $textoTicket.='<tr>';
                            $textoTicket.=    '<td colspan="3" class="col-md-3 izquierda">Su ventaja BT11</td>';
                               
                            $textoTicket.=   '<td class="col-md-3">'.$ticket['otrosDescuentos'][$k].'</td>';
                           $textoTicket.='</tr>';
                           
                          }
                           
                       }
                   }
                   $textoTicket.='</tbody>';
                   $textoTicket.='</table>';
                   $textoTicket.='</div>';
               }
               $textoTicket.='</div>'; 
               
               // añadir al final productos las entragas negativas cod 999998   
               if(true){
               $textoTicket.='<div class="row">';
                   $textoTicket.='<div class="col-md-12">';
                       $textoTicket.='<table class="table ticket" >';
                           $textoTicket.='<tbody>';
                                   foreach ($ticket['unidades_pesos'] as $k => $v) { 
                                       if ( $v=='2') { 
                                           $textoTicket.='<tr>';
                                            $textoTicket.=   '<th colspan="4" class="col-md-12 izquierda">'.ucfirst(strtolower($ticket['productos'][$k])).'</th>';
                                           $textoTicket.='</tr>';
                                           $textoTicket.='<tr>';
                                               $textoTicket.='<td class="col-md-3">'.$ticket['tiposIva'][$k].'</td>';
                                               $textoTicket.='<td class="col-md-3">'.$ticket['precios'][$k].'</td>';
                                           $textoTicket.='</tr>';
                                       } 
                                   }
                               $textoTicket.='</tbody>';
                       $textoTicket.='</table> ';   
                   $textoTicket.='</div>';
                  $textoTicket.='</div>';   
               }
               
               $textoTicket.='<div class="row">';
               $textoTicket.='<div class="col-md-12">';
               $textoTicket.='<table class="table ticket" >';
               $textoTicket.='<tbody>';

               $textoTicket.='<tr>';
               $textoTicket.= '<th  class="col-md-3 izquierda ticketTotal">';
               $textoTicket.= $ticket['numPartidasTicket'].' Part';
               $textoTicket.= '</th>';
               $textoTicket.='<th class="col-md-3 centro ticketTotal">';
               $textoTicket.= 'Suma';
               $textoTicket.='</th>';
               $textoTicket.='<th class="col-md-3 centro ticketTotal">';
               $textoTicket.= '€';
               $textoTicket.='</th>';
               $textoTicket.= '<th class="col-md-3" style="font-size: 20px">';
               $textoTicket.= $ticket['totalTicket'];
               $textoTicket.= '</th>';
               $textoTicket.='</tr>';
               
               ksort($ticket['formaPago']);
               
               foreach($ticket['formaPago'] as $k => $v) {
                   $textoTicket.='<tr>';
                   $textoTicket.='<td colspan="2" class="col-md-3 izquierda">';
                   $textoTicket.= $ticket['formaPago'][$k];
                   $textoTicket.='</td>';
                   $textoTicket.='<td class="col-md-3 centro">';
                   $textoTicket.=$ticket['importeFormaPago'][$k]!=""?"€":"" ;
                   $textoTicket.='</td>';
                   $textoTicket.='<td class="col-md-3">';
                   $textoTicket.=$ticket['importeFormaPago'][$k];
                   $textoTicket.='</td>';
                   $textoTicket.='</tr> ';   
               }
               
               $textoTicket.='<tr>';
               $textoTicket.='<td colspan="4" class="col-md-3 izquierda">';
               $textoTicket.='En la suma se incluye';
               $textoTicket.='</td>';
                 
               $textoTicket.='</tr>';
               $textoTicket.='<tr>';
               $textoTicket.='<td class="col-md-3 izquierda">';
               $textoTicket.='</td>';
               $textoTicket.='<td class="col-md-3">';
               $textoTicket.=    'I.V.A.';
               $textoTicket.='</td>';
               $textoTicket.='<td class="col-md-3">';
               $textoTicket.=    'Neto';
               $textoTicket.='</td>';
               $textoTicket.='<td class="col-md-3">';
               $textoTicket.=    'Bruto';
               $textoTicket.='</td>';
               $textoTicket.='</tr>';
               
               foreach ($ticket['tipoIvasSum'] as $k => $v) { 
                   $textoTicket.='<tr>';
                   $textoTicket.='<td colspan="4" class="col-md-3 izquierda">'.$v.'% '.$ticket['textos'][$k] .'</td>';
                   $textoTicket.='</tr>';
                   $textoTicket.='<tr>';
                   $textoTicket.='<td class="col-md-3"></td>';
                   $textoTicket.='<td class="col-md-3">'.$ticket['ivas'][$k].'</td>';
                   $textoTicket.='<td class="col-md-3">'.$ticket['netos'][$k].'</td>';
                   $textoTicket.='<td class="col-md-3">'.$ticket['brutos'][$k].'</td>';
                   $textoTicket.='</tr>';
               }
               
               if ($ticket['sumaIvas']>0){  
                   $textoTicket.='<tr>';
                   $textoTicket.='<td class="col-md-3">Suma</td>';
                   $textoTicket.='<td style="border-top:1px solid black" class="col-md-3">'.number_format($ticket['sumaIvas'],2).'</td>';
                   $textoTicket.='<td class="col-md-3"></td>';
                   $textoTicket.='<td class="col-md-3"></td>';
                   $textoTicket.='</tr>';
                } 
               
               $textoTicket.='<tr>';
               $textoTicket.='<td colspan="4" class="col-md-3 izquierda">'.$ticket['fechaCierre'].'</td>';
               $textoTicket.='</tr>';
               $textoTicket.='<tr>';
               $textoTicket.='<td colspan="4" class="col-md-3 izquierda">ATES PER '.$ticket['nombreDependiente'].'</td>';
               $textoTicket.='</tr>  ';
               
               $textoTicket.='</tbody>';
               $textoTicket.='</table >';
               $textoTicket.='</div >';
               $textoTicket.='</div >';
               
               $textoTicket.='</div>';
               return $textoTicket;

        }

         public function getDatosTicketIdTickets(){
             $id=$_POST['id'];
             $numTicket=$this->getNumTicket($id,1);
             $textoTicket1=$this->htmlTicket($id,1);

             $salida='<div class="fila">
             <div class="columna">'.$textoTicket1.
             '</div>                
            
           </div>';
           //para ver tambien consersiones 
           //se debe cambiar en teble_tickets.php 
           /* para cambiar el ancho ventana modal 
            .modal-dialog {
                width: 40%;
                margin: 0 auto;
                margin-top: 15px;
            } 
            */
            if($this->session->username=="maba" || $this->session->username=="carlos" || $this->session->username=="carlos2"){
             $textoTicket2=$this->htmlTicket($id,2);
             $salida='<div class="fila">
                <div class="columna">'.$textoTicket1.
                '</div>                
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="columna" style="color:blue">'.$textoTicket2.
                '</div>
              </div>';
            }
        

         return array($numTicket,$salida);
        }
        
        public function crearTicket($post){
            $importeTotal=$post['importeTotal']*100;
            $importe1=$post['importe1']*100;
            $importe2=$post['importe2']*100;
            $importe3=$post['importe3']*100;
            
            $base1=$post['base1']*100;
            $base2=$post['base2']*100;
            $base3=$post['base3']*100;
            
            $iva1=$post['iva1']*100;
            $iva2=$post['iva2']*100;
            $iva3=$post['iva3']*100;
            
            $metalico=$post['metalico']*100;
            $tarjeta=$post['tarjeta']*100;
            $aCuenta=$post['aCuenta']*100;
            $transferencia=$post['transferencia']*100;
            $cheque=$post['cheque']*100;
            
            
            $ivaProd1=$importe1-$base1;
            $ivaProd2=$importe2-$base2;
            $ivaProd3=$importe3-$base3;
            
            
            $fecha=$post['fecha'];
            $sql="SELECT bonu FROM pe_boka ORDER BY bonu DESC LIMIT 1";
            $bonu=$this->db->query($sql)->row()->bonu;
            if($bonu<900000) $bonu=900000; else $bonu=$bonu+1;
            
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='1',"
                    . "`ABNU`='1',"
                    . "`WANU`='3',"
                    . "`BEN1`='10',"
                    . "`BEN2`='4',"
                    . "`SNR1`='40',"
                    . "`GPTY`='1',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='0',"
                    . "`BT12`='0',"
                    . "`BT20`='$importeTotal',"
                    . "`POS1`='3',"
                    . "`POS4`='3',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='0',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='0',"
                    . "`PAR2`='1',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='2',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='0',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='-1',"
                    . "`VART`='2',"
                    . "`BART`='1',"
                    . "`KONU`='0',"
                    . "`RASA`='$bonu',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='0',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='$fecha 00:10:10',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
            $result=$this->db->query($sql);
            
            //STYP = 2 producto 1
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='2',"
                    . "`ABNU`='1',"
                    . "`WANU`='3',"
                    . "`BEN1`='10',"
                    . "`BEN2`='4',"
                    . "`SNR1`='900001',"
                    . "`GPTY`='1',"
                    . "`PNAB`='1',"
                    . "`WGNU`='1',"
                    . "`BT10`='$importe1',"
                    . "`BT12`='$importe1',"
                    . "`BT20`='$importe1',"
                    . "`POS1`='1',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='$ivaProd1',"
                    . "`MWNU`='3',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='1',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='1',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='0',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='1000',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='$iva1',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql);      
            
             //STYP = 2 producto 2
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='2',"
                    . "`ABNU`='1',"
                    . "`WANU`='3',"
                    . "`BEN1`='10',"
                    . "`BEN2`='4',"
                    . "`SNR1`='900002',"
                    . "`GPTY`='1',"
                    . "`PNAB`='1',"
                    . "`WGNU`='1',"
                    . "`BT10`='$importe2',"
                    . "`BT12`='$importe2',"
                    . "`BT20`='$importe2',"
                    . "`POS1`='1',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='$ivaProd2',"
                    . "`MWNU`='1',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='1',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='1',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='0',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='1000',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='$iva2',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql);    
             
             //STYP = 2 producto 3
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='2',"
                    . "`ABNU`='1',"
                    . "`WANU`='3',"
                    . "`BEN1`='10',"
                    . "`BEN2`='4',"
                    . "`SNR1`='900003',"
                    . "`GPTY`='1',"
                    . "`PNAB`='1',"
                    . "`WGNU`='1',"
                    . "`BT10`='$importe3',"
                    . "`BT12`='$importe3',"
                    . "`BT20`='$importe3',"
                    . "`POS1`='1',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='$ivaProd3',"
                    . "`MWNU`='2',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='1',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='1',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='0',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='1000',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='$iva3',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql);      
            
             //STYP = 6 producto 1
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='6',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='$base1',"
                    . "`BT12`='$ivaProd1',"
                    . "`BT20`='$importe1',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='3',"
                    . "`MWTY`='1',"
                    . "`PRUD`='0',"
                    . "`PAR1`='0',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='0',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='$iva1',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql);    
            
              //STYP = 6 producto 2
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='6',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='$base2',"
                    . "`BT12`='$ivaProd2',"
                    . "`BT20`='$importe2',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='1',"
                    . "`MWTY`='1',"
                    . "`PRUD`='0',"
                    . "`PAR1`='0',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='0',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='$iva2',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql);   
             
              //STYP = 6 producto 3
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='6',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='$base3',"
                    . "`BT12`='$ivaProd3',"
                    . "`BT20`='$importe3',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='2',"
                    . "`MWTY`='1',"
                    . "`PRUD`='0',"
                    . "`PAR1`='0',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='0',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='$iva3',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql);   
             
             
               //STYP = 8 efectivo
             if($metalico>0){
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='8',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='$metalico',"
                    . "`BT12`='0',"
                    . "`BT20`='0',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='0',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='1',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='$metalico',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='0',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql);   
             }
              //STYP = 8 cambio
             
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='8',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='0',"
                    . "`BT12`='0',"
                    . "`BT20`='0',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='0',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='20',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='0',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='0',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql);  
             
                    //STYP = 8 tarjeta
              if($tarjeta>0){
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='8',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='$tarjeta',"
                    . "`BT12`='0',"
                    . "`BT20`='0',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='0',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='4',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='$tarjeta',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='0',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql); 
              }
                    //STYP = 8 a Cuenta
               if($aCuenta>0){
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='8',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='$aCuenta',"
                    . "`BT12`='0',"
                    . "`BT20`='0',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='0',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='6',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='$aCuenta',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='0',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql); 
               }
                    //STYP = 8 transferencia
                if($transferencia>0){
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='8',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='$transferencia',"
                    . "`BT12`='0',"
                    . "`BT20`='0',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='0',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='5',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='$transferencia',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='0',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql); 
                }
                    //STYP = 8 cheque
                 if($cheque>0){
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='8',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='$cheque',"
                    . "`BT12`='0',"
                    . "`BT20`='0',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='0',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='2',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='$cheque',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='0',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql); 
                 }
             
              //STYP = 8 vale
                  if($vale>0){
            $sql="INSERT INTO `pe_boka` SET "
                    . "`BONU`='$bonu',"
                    . "`BONU2`='0',"
                    . "`STYP`='8',"
                    . "`ABNU`='0',"
                    . "`WANU`='0',"
                    . "`BEN1`='0',"
                    . "`BEN2`='0',"
                    . "`SNR1`='0',"
                    . "`GPTY`='0',"
                    . "`PNAB`='0',"
                    . "`WGNU`='0',"
                    . "`BT10`='$vale',"
                    . "`BT12`='0',"
                    . "`BT20`='0',"
                    . "`POS1`='0',"
                    . "`POS4`='0',"
                    . "`GEW1`='0',"
                    . "`BT40`='0',"
                    . "`MWNU`='0',"
                    . "`MWTY`='0',"
                    . "`PRUD`='0',"
                    . "`PAR1`='3',"
                    . "`PAR2`='0',"
                    . "`PAR3`='0',"
                    . "`PAR4`='0',"
                    . "`PAR5`='0',"
                    . "`STST`='0',"
                    . "`PAKT`='0',"
                    . "`POS2`='0',"
                    . "`MWUD`='0',"
                    . "`BT13`='0',"
                    . "`RANU`='0',"
                    . "`RATY`='0',"
                    . "`BT30`='0',"
                    . "`BT11`='$vale',"
                    . "`POS3`='0',"
                    . "`GEW2`='0',"
                    . "`SNR2`='0',"
                    . "`SNR3`='0',"
                    . "`VART`='0',"
                    . "`BART`='0',"
                    . "`KONU`='0',"
                    . "`RASA`='0',"
                    . "`ZAPR`='0',"
                    . "`ZAWI`='0',"
                    . "`MWSA`='0',"
                    . "`ZEIS`='$fecha 00:00:10',"
                    . "`ZEIE`='1970-01-01 00:00:00',"
                    . "`ZEIB`='0000-00-00 00:00:00',"
                    . "`TEXT`=''";
             $result=$this->db->query($sql); 
                  }
                  
            return $sql;
        }
        
        public function verTiposIvas(){
            $sql="SELECT valor_iva FROM pe_ivas ";
            $result=$this->db->query($sql);
            $ivas=array();
            foreach($result->result() as $row)
            {
                $ivas[]=$row->valor_iva;
            }
            return $ivas;
        }
        
        public function eliminarTicket($ticket,$fecha){
            $sql="SELECT bonu FROM pe_boka WHERE STYP=1 AND RASA='$ticket' AND ZEIS='$fecha'";
            
            $query=$this->db->query($sql);
            $bonu=$query->row()->bonu;
             $sql="INSERT INTO pe_boka_eliminados SELECT * FROM pe_boka WHERE bonu='$bonu' AND ZEIS='$fecha'";
            $query=$this->db->query($sql);
            $sql="DELETE FROM pe_boka WHERE bonu='$bonu' AND ZEIS='$fecha'";
            $query=$this->db->query($sql);
            return $query;
        }
        
        public function recuperarTicket(){
            $ticket=$_POST['ticket'];
            $bonu=$this->getBONU($ticket);
            //eliminamos registros de pe_boka2 con BONU=$bonu, si existen
            $query = "DELETE FROM pe_boka2 WHERE BONU='$bonu'";
            return $query = $this->db->query($query);
        }
        
        public function buscarDatosTicket(){
            $ticket=$_POST['ticket'];
            $bonu=$this->getBONU($ticket);
            $query1 = "SELECT id,PAR1,BONU FROM pe_boka WHERE BONU='$bonu' AND STYP='8' AND (PAR1='1' OR PAR1='4')";
            $query = $this->db->query($query1);
            $tipoPago=$query?$query->result():array();
            $query2 = "SELECT id, BT20,SNR1,BONU,RASA FROM pe_boka WHERE BONU='$bonu' AND STYP='1' ";
            $query = $this->db->query($query2);
            $clienteTicket=$query?$query->result():array();
            $query = "SELECT * FROM pe_clientes WHERE 1 ORDER BY empresa";
            $query = $this->db->query($query);
            $clientes=$query?$query->result():array();
            return array('tipoPago'=>$tipoPago,'clienteTicket'=>$clienteTicket, 'clientes'=>$clientes);
        }
        
        public function updateRegistroVentas($idBoka,$cliente){
            $row=$this->db->query("SELECT ZEIS, RASA FROM pe_boka WHERE id='$idBoka'")->row();
            $fecha_venta=$row->ZEIS;
            $num_ticket=$row->RASA;
            $this->db->query("UPDATE pe_registro_ventas SET num_cliente='$cliente' WHERE fecha_venta='$fecha_venta' AND num_ticket='$num_ticket' and tipo_tienda=1");
        }

        public function grabarDatosTicket(){
            $id=$_POST['idBokaCliente'];
            //se comprueba si se ha procesado (modificado, es decir si existe en pe_boka2
            $query="SELECT * FROM pe_boka2 WHERE id='$id'";
            $query=$this->db->query($query);
            if($query->num_rows()>0) 
                return array('mensaje'=>'No se ha podido cambiar porque este ticket SE HA PROCESADO');
            $cliente=$_POST['cliente']*10+7;
            $numCliente=$_POST['cliente'];
            $id=$_POST['idBokaCliente'];
            $query="UPDATE pe_boka SET SNR1='$cliente', SNR2='$numCliente' WHERE id='$id' ";
            $query1 = $this->db->query($query);

           // $this->updateRegistroVentas($id,$cliente);

            $formaPago=$_POST['formaPago'];
            $id=$_POST['idBokaFormaPago'];
            $query="UPDATE pe_boka SET PAR1='$formaPago' WHERE id='$id' ";
            $query2 = $this->db->query($query);
            
            
            return array('mensaje'=>'Cambio efectuado correctamente', 'post'=> $_POST, 'queryCliente'=>$query1,'queryFormaPago'=>$query2, 'cliente'=>$cliente, 'formaPago'=>$formaPago);
        }
        
        
        public function cambiarClientePago(){
            $fecha=$_POST['fecha'];
            $referencia=$_POST['referencia'];
            $cliente=$_POST['cliente'];
            
            switch($_POST['formaPago']){
                case 'Entregado':
                    $pago=1;
                    break;
                case 'Cheque':
                    $pago=2;
                    break;
                case 'Vale':
                    $pago=3;
                    break;
                case 'Tarjeta de Crédido':
                    $pago=4;
                    break;
                case 'Transferencia':
                    $pago=5;
                    break;
                case 'A cuenta':
                    $pago=6;
                    break;
                case 'Cambio':
                    $pago=20;
                    break;
                default:
                    $pago=0;
            }
            $sql1="UPDATE pe_boka SET PAR1='$pago' where ZEIS='$fecha' AND BONU='$referencia' and STYP=8 AND PAR1!=20";
            $query = $this->db->query($sql1);

            //actualizar forma pago en pe_tickets
            $formaFormaPagoTicket=$_POST['formaPago'];
            $this->db->query("UPDATE pe_tickets SET id_forma_pago_ticket='$pago' WHERE fecha='$fecha' AND bonu='$referencia'");
            
            //leer importe ticket
            $sql2="SELECT BT20 as importe FROM pe_boka WHERE STYP=1 AND ZEIS='$fecha' AND BONU='$referencia'";        
            $query = $this->db->query($sql2);
            $importe=$query->row()->importe;
            //si $pago = 4 - tarjeta de credito  
            if ($pago==4 || $pago=1){
                $sql3="UPDATE pe_boka SET BT10='0', BT11='0' where ZEIS='$fecha' AND BONU='$referencia' and STYP=8 AND PAR1=20";
                $query=$this->db->query($sql3);
                $sql4="UPDATE pe_boka SET BT10='$importe', BT11='$importe' where ZEIS='$fecha' AND BONU='$referencia' and STYP=8 AND PAR1!=20";
                $query=$this->db->query($sql4);
            }
            $sql="SELECT  SNR1 FROM pe_boka WHERE ZEIS='$fecha' AND BONU=$referencia and STYP=1";
            $query = $this->db->query($sql);
            $row=$query->row();
            //$id=$row->id;
            $clienteCodigo=$row->SNR1;
            $modo=$clienteCodigo%10;
            if($modo!=6 || $modo!=7) $modo=7;
            $clienteCodigo= $cliente*10+$modo;  
            
            $sql="UPDATE pe_boka SET SNR1='$clienteCodigo',SNR2='$cliente' WHERE ZEIS='$fecha' AND BONU=$referencia and STYP=1";
            $query2 = $this->db->query($sql);


           // $this->updateRegistroVentas($id,$cliente);
           // return $sql;
            return array($sql1,$sql2,$sql3,$sql4); //$query && $query2;
        }
        
        public function prepararBoka2(){
            //var_dump($_POST);
            //
            //$_POST['diferenciaImportesPeriodo']="50";
            $this->session->set_userdata('diferenciaPeriodoImportes', $_POST['diferenciaImportesPeriodo']);
            $this->session->set_userdata('diferenciaPeriodoIvas', $_POST['diferenciaIvasPeriodo']);
            // se elimina, si existen campos del 
            $ticket=$_POST['ticket'];
            $bonu=$this->getBONU($ticket);
            //eliminamos registros de pe_boka2 con BONU=$bonu, si existen
            $query = "DELETE FROM pe_boka2 WHERE BONU='$bonu'";
            $query = $this->db->query($query);
            //copiamos información de pe_boka a pe_boka2 con BONU=$bonu
            //$query = "INSERT INTO pe_boka2 SELECT * FROM pe_boka WHERE BONU='$bonu'";
            //$query = $this->db->query($query);
            //obtenemos información para modificar
            $query = "SELECT * FROM pe_boka2 WHERE BONU='$bonu'";
            $query = $this->db->query($query);
            
          
            
            $i2=0;
            $i6=0;
            $i8=0;
            $update="";
            foreach ($query->result() as $k=>$row) {
                 if ($row->STYP == 2) {
                     
                    $GEW1[$i2]=$row->GEW1;
                    $BT20[$i2]=$row->BT20;
                    $POS1[$i2]=$row->POS1;
                    
                    if(isset($_POST['peso'][$i2]))
                        $GEW1[$i2]=$_POST['peso'][$i2]*1000;
                    
                    if(isset($_POST['cantidad'][$i2]))
                        $POS1[$i2]=$_POST['cantidad'][$i2];
                    
                    if(isset($_POST['precio'][$i2]))
                        $BT10[$i2]=$_POST['precio'][$i2]*100;
                   
                    $BT20[$i2]=$_POST['importe'][$i2]*100;
                   
                    $update="UPDATE pe_boka2 SET POS1='$POS1[$i2]', GEW1='$GEW1[$i2]', BT20='$BT20[$i2]', BT10='$BT10[$i2]' WHERE id='$row->id'";
                    //echo $update.'<br />';
                    $updateR = $this->db->query($update);
                    $i2++;
                }
                
                
                if ($row->STYP == 1) {
                    $numPartidasTicket=isset($_POST['numPartidasTicket'])?$_POST['numPartidasTicket']:"0";

                    $totalTicketBoka2=isset($_POST['importeTotal'])?$_POST['importeTotal']*100:"";
                    $update= "UPDATE pe_boka2 SET BT20='$totalTicketBoka2', POS1='$numPartidasTicket'  WHERE id='$row->id'";
                   // echo $update.'<br />';
                    $update = $this->db->query($update);
                }
                if ($row->STYP == 8 && $row->PAR1==1 ) {
                    $importeFormaPago=isset($_POST['importeFormaPago'])?$_POST['importeFormaPago']*100:"";
                    $update= "UPDATE pe_boka2 SET BT10='$importeFormaPago' WHERE id='$row->id'";
                    //echo $update.'<br />';
                    $update = $this->db->query($update);
                    
                }
                if ($row->STYP == 6) {
                    $iva=isset($_POST['iva'][$i6])?$_POST['iva'][$i6]*100:"0";
                    $neto=isset($_POST['neto'][$i6])?$_POST['neto'][$i6]*100:"0";
                    $bruto=isset($_POST['bruto'][$i6])?$_POST['bruto'][$i6]*100:"0";
                    $update= "UPDATE pe_boka2 SET BT12='$iva', BT10='$neto', BT20='$bruto' WHERE id='$row->id'";
                    
                 
                   // echo $update.'<br />';
                    $update = $this->db->query($update);
                    $i6++;
                }
                 
            }
            
            return array($_POST['diferenciaImportesPeriodo'],$_POST); //$update; //array($_POST, $GEW1,$BT20,$POS1);
    }
    
        public function prepararCorrecciones(){
            //var_dump($_POST);
            //
            //$_POST['diferenciaImportesPeriodo']="50";
            
            // se elimina, si existen campos del 
            $ticket=$_POST['ticket'];
            $bonu=$this->getBONU($ticket);
            //eliminamos registros de pe_boka2 con BONU=$bonu, si existen
            //$query = "DELETE FROM pe_boka2 WHERE BONU='$bonu'";
            //$query = $this->db->query($query);
            //copiamos información de pe_boka a pe_boka2 con BONU=$bonu
            //$query = "INSERT INTO pe_boka2 SELECT * FROM pe_boka WHERE BONU='$bonu'";
            //$query = $this->db->query($query);
            //obtenemos información para modificar
            $sql = "SELECT * FROM pe_boka WHERE BONU='$bonu'";
            $query = $this->db->query($sql);
            
            $i2=0;
            $i6=0;
            $i8=0;
            $update="";
            $salida="";
            
            foreach ($query->result() as $k=>$row) {
                 if ($row->STYP == 2) {
                    
                    $GEW1[$i2]=$row->GEW1;
                    $BT20[$i2]=$row->BT20;
                    $POS1[$i2]=$row->POS1;
                    
                    /*
                    $GEW1[$i2]=$_POST['peso'][$i2]*1000 || $row->GEW1;
                    $POS1[$i2]=$_POST['cantidad'][$i2] || $row->POS1;
                    $BT10[$i2]=$_POST['precio'][$i2]*100 || $row->BT10;
                    $BT20[$i2]=$_POST['importe'][$i2]*100 || $row->BT20;
                    */
                    
                    
                    if(isset($_POST['peso'][$i2]))
                        $GEW1[$i2]=$_POST['peso'][$i2]*1000;
                    
                    if(isset($_POST['cantidad'][$i2]))
                        $POS1[$i2]=$_POST['cantidad'][$i2];
                    
                    if(isset($_POST['precio'][$i2]))
                        $BT10[$i2]=$_POST['precio'][$i2]*100;
                   
                    $BT20[$i2]=$_POST['importe'][$i2]*100;
                    
                   
                    $update="UPDATE pe_boka SET POS1='$POS1[$i2]', GEW1='$GEW1[$i2]', BT20='$BT20[$i2]', BT10='$BT10[$i2]' WHERE id='$row->id'";
                    
                    //echo $update.'<br />';
                    $updateR = $this->db->query($update);
                    $i2++;
                    $salida=$salida.' '.$update;
                }
                
                
                if ($row->STYP == 1) {
                    $numPartidasTicket=isset($_POST['numPartidasTicket'])?$_POST['numPartidasTicket']:"0";

                    $totalTicketBoka2=isset($_POST['importeTotal'])?$_POST['importeTotal']*100:"";
                    $update= "UPDATE pe_boka SET BT20='$totalTicketBoka2', POS1='$numPartidasTicket'  WHERE id='$row->id'";
                   // echo $update.'<br />';
                    $update = $this->db->query($update);
                }
                if ($row->STYP == 8 && $row->PAR1==1 ) {
                    $importeFormaPago=isset($_POST['importeFormaPago'])?$_POST['importeFormaPago']*100:"";
                    $update= "UPDATE pe_boka SET BT10='$importeFormaPago' WHERE id='$row->id'";
                    //echo $update.'<br />';
                    $update = $this->db->query($update);
                    
                }
                if ($row->STYP == 6) {
                    $iva=isset($_POST['iva'][$i6])?$_POST['iva'][$i6]*100:"0";
                    $neto=isset($_POST['neto'][$i6])?$_POST['neto'][$i6]*100:"0";
                    $bruto=isset($_POST['bruto'][$i6])?$_POST['bruto'][$i6]*100:"0";
                    $update= "UPDATE pe_boka SET BT12='$iva', BT10='$neto', BT20='$bruto' WHERE id='$row->id'";
                    
                 
                   // echo $update.'<br />';
                    $update = $this->db->query($update);
                    $i6++;
                }
                 
            }
            return ;//array($_POST['diferenciaImportesPeriodo'],$_POST); //$update; //array($_POST, $GEW1,$BT20,$POS1);
    }
        
        public function getTicketsHoy(){
          
            $hoy=date('Y-m-d',now());
            $query="SELECT BONU, RASA, ZEIS from pe_boka WHERE ZEIS>='$hoy' AND ZEIS<='$hoy 23:59:59' AND STYP=1";
            $query=$this->db->query($query);
            $tickets=array();
            foreach ($query->result() as $k => $row) {
                $tickets[]=$row->RASA.' '.$row->ZEIS;
            }
            return $tickets;
        }
        
        public function getTickets($inicio, $final){
            
           
            
            //set_time_limit(0);
            
            $sql="SELECT BONU as bonu, ZEIS as zeis from pe_boka WHERE STYP=1 AND BONU>='900000' AND ZEIS>='$inicio' AND ZEIS<='$final 23:59:59' ORDER BY ZEIS";
            
            $query=$this->db->query($sql);
            
            $bonusManuales=array();
            $diasManuales=array();
            $day_before=array();
            $day_after=array();
            foreach ($query->result() as $k => $row) {
                $bonusManuales[$k]=$row->bonu;
                $time=substr($row->zeis,0,10);
                $diasManuales[$k]=fechaEuropea($time);
                $from_unix_time=mktime(0,0,0,substr($time,5,2), substr($time,8,2),substr($time,0,4) );
                $day_before[$k] = date('Y-m-d',strtotime("yesterday", $from_unix_time));
                $day_after[$k] = date('Y-m-d',strtotime("tomorrow", $from_unix_time));
            }
            
            //no se consideran los tickets con BONU>= 900000 -> introducidos manualmente
            $query="SELECT BONU, RASA, ZEIS from pe_boka WHERE STYP=1 AND BONU<'900000' AND ZEIS>='$inicio' AND ZEIS<='$final 23:59:59' ORDER BY ZEIS";
            $query=$this->db->query($query);
            $num=$query->num_rows();
            $tickets=array();
            $bonus=array();
            unset($_SESSION['ticketsPeriodo']);
            foreach ($query->result() as $k => $row) {
                //$bonus[$k]=$row->BONU;
               // $tickets[$k]=$row->RASA.' '.fechaEuropea($row->ZEIS);
               // $_SESSION['ticketsPeriodo'][$k]=$tickets[$k];
                //echo 'Hola '.$tickets[$k];
               // $ticketsRASA[$k]=$row->RASA;
                //inicializa 
            }
            $queryBalanza=array();
            $numTotalesBalanza=array();
            for($balanza=1;$balanza<4;$balanza++){
                $query="SELECT BONU, RASA, ZEIS from pe_boka WHERE STYP=1 AND BONU<'900000' AND ZEIS>='$inicio' AND ZEIS<='$final 23:59:59' and RANU='$balanza' ORDER BY RASA";
                $queryBalanza[$balanza]=$this->db->query($query);
                $numTotalesBalanza[$balanza]=$this->db->query($query)->num_rows();
            }
            ini_set('max_execution_time', 300);
            for ($balanza = 1; $balanza < 4; $balanza++) {
            $numTickeks = array();
            $ticketsFaltan = array();
            //$num = $queryBalanza[$balanza]->num_rows();
            $primero = $queryBalanza[$balanza]->first_row('array')['RASA'];
            $ultimo = $queryBalanza[$balanza]->last_row('array')['RASA'];
            $faltan = $ultimo - $primero + 1 - $numTotalesBalanza[$balanza];
            foreach ($queryBalanza[$balanza]->result() as $k => $v) {
                $numTickeks[]=$v->RASA;
            }
            
            reset($numTickeks);
            $first = current($numTickeks);
            $anterior = $first - 1;
            foreach ($numTickeks as $k => $v) {
                if ($v != $anterior + 1) {
                    //echo ($anterior + 1) . " - " . ($v - 1) . "<br>";
                    $ticketsFaltan[]=" Del ".($anterior + 1) . " al " . ($v - 1);
                }
                $anterior = $v;
            }


            $balanzas[$balanza] = array('ticketsFaltan'=>$ticketsFaltan,'faltan' => $faltan, 'num' => $queryBalanza[$balanza]->num_rows(), 'primero' => $queryBalanza[$balanza]->first_row(), 'ultimo' => $queryBalanza[$balanza]->last_row(),);
        }

        /* 
            for($balanza=1;$balanza<4;$balanza++){
                $faltan="";
                $n=$queryBalanza[$balanza]->num_rows();
                if($n>0){
                    $v=$queryBalanza[$balanza]->last_row('array');
                    $ultimo=$v['RASA'];
                     $v=$queryBalanza[$balanza]->first_row('array');
                    $primero=$v['RASA'];

                    $diferencia=$ultimo-$primero+1-$n;

                    $noEstan=array();
                    if($diferencia!=0){
                        for($i=$primero;$i<=$ultimo;$i++)
                            if(!in_array($i,$ticketsRASA))
                                $noEstan[]=$i;  
                        $faltan="Faltan los tikets: ".implode(", ",$noEstan);    
                }
            }
                $balanzas[$balanza]=array('faltan'=>$faltan,  'num'=>$queryBalanza[$balanza]->num_rows(),'primero'=>$queryBalanza[$balanza]->first_row(),'ultimo'=>$queryBalanza[$balanza]->last_row(), );

            }
           */
            $textoDiasManuales=count($diasManuales).' ';
            foreach($diasManuales as $k=>$v){
                $v=  substr($v, 0,10);
               $textoDiasManuales.="($v)"; 
            }
            
            return array(
                'num' =>$num,
                'tickets'=>$tickets,
                'num1'=>$balanzas[1]['num'], 'primero1'=>$balanzas[1]['primero'],'ultimo1'=>$balanzas[1]['ultimo'],
                'num2'=>$balanzas[2]['num'], 'primero2'=>$balanzas[2]['primero'],'ultimo2'=>$balanzas[2]['ultimo'],
                'num3'=>$balanzas[3]['num'], 'primero3'=>$balanzas[3]['primero'],'ultimo3'=>$balanzas[3]['ultimo'],
                'faltan1'=>$balanzas[1]['faltan'],'faltan2'=>$balanzas[2]['faltan'],'faltan3'=>$balanzas[3]['faltan'],
                'ticketsFaltan1'=>$balanzas[1]['ticketsFaltan'],
                'ticketsFaltan2'=>$balanzas[2]['ticketsFaltan'],
                'ticketsFaltan3'=>$balanzas[3]['ticketsFaltan'],
                'diasManuales'=>$textoDiasManuales,
                'sql'=>$sql
                );
        }
        
        
        
        public function getNumTicketsAModificar($inicio, $final){
            $query="SELECT b.BONU as BONU, a.RASA as RASA, a.ZEIS as ZEIS from pe_boka a "
                    . " left join pe_boka b on a.BONU=b.BONU and (b.STYP=8 and b.PAR1=1) "
                    . "WHERE (a.STYP=1 AND MOD(a.SNR1,10)<7) AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ORDER BY a.ZEIS";
            $query=$this->db->query($query);
            return $query->num_rows('array'); 
        }
        
        
        public function getTicketsAModificar($inicio, $final){
            // (STYP=1 AND MOD(SNR1,10)<7) filtrado los tickets SIN nomre cliente
            $query="SELECT b.BONU as BONU, a.RASA as RASA, a.ZEIS as ZEIS from pe_boka a "
                    . " left join pe_boka b on a.BONU=b.BONU and (b.STYP=8 and b.PAR1=1) "
                    . "WHERE (a.STYP=1 AND MOD(a.SNR1,10)<7) AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ORDER BY a.ZEIS";
            $query=$this->db->query($query);
            $tickets=array();
            unset($_SESSION['ticketsPeriodo']);
            $i=0;
            foreach ($query->result() as $k => $row) {
                if($row->BONU){
                    $tickets[$i]=$row->RASA.' '.fechaEuropea($row->ZEIS);
                    //$this->session->set_userdata('ticketsPeriodo[$i]',$tickets[$i]);
                    $_SESSION['ticketsPeriodo'][$i]=$tickets[$i];
                    $i++;
                }
                    
            }
            return array('num'=>sizeof($tickets),'tickets'=>$tickets, );
        }
        
        
        public function getBONU($RASA){
            $query = "SELECT BONU FROM pe_boka WHERE RASA='$RASA'";
            $query = $this->db->query($query);
            if($query){
            $row = $query->row();
            $bonu = $row->BONU;
            return $bonu;
            }
            else{
                return false;
            }
        }
        
        public function getTicketsDia($fecha){
            
            $diaSiguiente=strtotime ( '+1 day' , strtotime ( date($fecha) ) ) ;
            $diaSiguiente=date('Y-m-d',$diaSiguiente);        
            $sql="SELECT BONU,RASA, ZEIS FROM pe_boka WHERE STYP=1 AND ZEIS>='$fecha' AND ZEIS<'$diaSiguiente' ";
            $query = $this->db->query($sql);
            $tickets=array();
            foreach($query->result() as $k=>$v){
                $tickets[]=$v->RASA.' '.$v->ZEIS;
            }
            return $tickets;
        }
        
        public function getTicketsDiaClientes($fecha){
            
            $diaSiguiente=strtotime ( '+1 day' , strtotime ( date($fecha) ) ) ;
            $diaSiguiente=date('Y-m-d',$diaSiguiente);        
            $sql="SELECT BONU,RASA, ZEIS FROM pe_boka WHERE STYP=1 AND ZEIS>='$fecha' AND ZEIS<'$diaSiguiente' AND FORMAT(SNR1/10,0)>10";
            $query = $this->db->query($sql);
            $tickets=array();
            foreach($query->result() as $k=>$v){
                $tickets[]=$v->RASA.' '.$v->ZEIS;
            }
            return $tickets;
        }
        
        
        public function getTicketsDiaFecha($fecha){
                  
            $diaSiguiente=strtotime ( '+1 day' , strtotime ( date($fecha) ) ) ;
            $diaSiguiente=date('Y-m-d',$diaSiguiente);        
            $sql="SELECT BONU,RASA, ZEIS FROM pe_boka WHERE STYP=1 AND ZEIS>='$fecha' AND ZEIS<'$diaSiguiente' ORDER BY ZEIS DESC";
            $query = $this->db->query($sql);
            $fechas=array();
            foreach($query->result() as $k=>$v){
                $fechas[]=$v->RASA.' '.$v->ZEIS;
            }
            //return $sql;
            return $fechas;
        }
        
        public function getFechasNumTicket($numTicket){
                  
            $sql="SELECT BONU,RASA, ZEIS FROM pe_boka WHERE STYP=1 AND RASA='$numTicket' ORDER BY ZEIS DESC ";
            $query = $this->db->query($sql);
            $fechas=array();
            foreach($query->result() as $k=>$v){
                $fechas[]=$v->RASA.' '.$v->ZEIS;
            }
            //return $sql;
            return $fechas;
        }
        
        
        public function getTicketsDiaFechaClientes($numTicket){
                  
            $sql="SELECT BONU,RASA, ZEIS FROM pe_boka WHERE STYP=1 AND RASA='$numTicket' AND (SNR1 MOD 10 =7 or SNR1 MOD 10 =6) ORDER BY ZEIS DESC";
            $query = $this->db->query($sql);
            $fechas=array();
            foreach($query->result() as $k=>$v){
                $fechas[]=$numTicket.' '.$v->ZEIS;
            }
            return $fechas;
        }
        
        public function getTicket($ticket,$base){
            $db=$base==1?$this->db:$this->db2;
            //$ticket="RASA fecha hora"   fecha en formato europeo
            $partes=explode(' ',$ticket);
            if(!preg_match ( '/[0-9]{4}-[0-1][0-9]-[0-9]{2}/' , $partes[1])){
                  $partes[1]=substr($partes[1],6,4).'-'.substr($partes[1],3,2).'-'.substr($partes[1],0,2);
            }
            //importante esto se hace para buscar el RASA de una fecha determinada
            $query = "SELECT BONU FROM pe_boka WHERE RASA='$partes[0]' AND ZEIS='$partes[1] $partes[2]'";
            if($db->query($query)->num_rows()==0) return array();
            $query = $db->query($query);
            $row = $query->row();
            $bonu = $row->BONU;
            $query = "SELECT * FROM pe_boka WHERE BONU='$bonu' AND ZEIS='$partes[1] $partes[2]'";
            //$query);
            $query = $db->query($query);

        $importeFormaPago=array();
        $productos=array();
        $codigosProductos=array();
        $precios=array();
        $preciosUnitarios=array();
        $preciosModificados=array();
        $tipoIvas=array();
        $unidades=array();
        $pesos=array();
        $unidades_pesos=array();
        $numUnidades=array();
        $descuentos=array();
        $otrosDescuentos=array();
        $anulaciones=array();
        
        $tipoIvasSum=array();
        $textos=array();
        $bases=array();
        $neto=array();
        $brutos=array();
        $ivas=array();
        $numBascula=array();
        $tiposIva=array();
        $formaPago=array();
        $netos=array();
        $codigosIva=array();
        $codigosIvaSum=array();
        $cliente="";
        $nombreCliente="";
        $empresa="";
        $modo="";
        
        foreach ($query->result() as $row) {
            if ($row->STYP == 1) {
                $numCaja = $row->WANU;
                $subDepartamento = $row->ABNU;
                $referencia = digitos($row->BONU,6);
                $fecha = trim(fechaEuropea($row->ZEIS));
                $fechaCierre=trim(fechaEuropea($row->ZEIE));
                $numero = $row->RASA;
                $dependiente = $row->BEN1;
                $numPartidasTicket=$row->POS1;
                if($row->ZEIS <'2018-02-19'){
                    switch($dependiente){
                        case 1: $nombreDependiente="Carlos";break;
                        case 2: $nombreDependiente="";break;
                        case 3: $nombreDependiente="Joan";break;
                        case 4: $nombreDependiente="Sergi";break;
                        case 10: $nombreDependiente="De Tira";break;
                        default:$nombreDependiente="";
                    }}
                    else{
                        switch($dependiente){
                            case 1: $nombreDependiente="Carlos";break;
                            case 2: $nombreDependiente="";break;
                            case 3: $nombreDependiente="Sergio";break;
                            case 4: $nombreDependiente="Sergi";break;
                            case 10: $nombreDependiente="De Tira";break;
                            default:$nombreDependiente="";
                        }
                }
                $totalTicket=($row->BT20);
                $clienteCodigo=$row->SNR1;
                $modo=$clienteCodigo%10;
                switch ($modo){
                    case 7:
                        $modo='Factura en efectivo';
                        $cliente=intval($clienteCodigo/10);
                        break;
                    case 8:
                        $modo='Reenvío';
                        $cliente=intval($clienteCodigo/10);
                        break;
                    case 9:
                        $modo='Pedido';
                        $cliente=intval($clienteCodigo/10);
                        break;
                    default:
                        $modo="FACTURA SIMPLIFICADA";
                        $cliente="";
                }
                if($cliente!==""){
                    $queryCliente="SELECT * FROM pe_clientes WHERE id_cliente='$cliente'";
                        $queryCliente = $this->db->query($queryCliente);
                        $rowCliente = $queryCliente->row();
                        if(isset($rowCliente->nombre))
                            $nombreCliente=strtoupper($rowCliente->nombre);
                        else 
                            $nombreCliente='CLIENTE SIN NOMBRE';
                        if(isset($rowCliente->empresa))
                            $empresa=$rowCliente->empresa;
                        else 
                            $empresa='';
                }
                
                
            }
            if ($row->STYP == 2) {
                $numBascula = $row->SNR1;
                // mensaje('numBascula '.$numBascula);
             //   $queryProducto="SELECT * FROM pe_productos WHERE id_producto='$row->SNR1'";
                $id_pe_producto=$row->id_pe_producto;
                $queryProducto="SELECT * FROM pe_productos WHERE id='$id_pe_producto'";
                // mensaje('$row->id_pe_producto '.$row->id_pe_producto);

                $queryProducto = $this->db->query($queryProducto);
                $rowProducto = $queryProducto->row();
                //echo $row->SNR1.$rowProducto->nombre.'<br />';
              
                
                if (isset($rowProducto->nombre)  && trim($rowProducto->nombre)=="") $rowProducto->nombre="SIN DENOMINACIÓN//";
                if(isset($rowProducto->nombre) )
                    $productos[]=strtoupper(!$rowProducto->nombre_generico?$rowProducto->nombre:$rowProducto->nombre_generico);
                else 
                    $productos[]="SIN DENOMINACIÓN/";
                
                
                
                
                $preciosModificados[]=formato2decimales($row->BT12/100);
                //$precios[]=formato2decimales($row->BT20/100);
               //$preciosUnitarios[]=formato2decimales($row->BT10/100);
                if($row->GEW1==0){
                    //productos vendidos por unidades
                    //preciosUnitarios o preciosModificados?
                    if($row->BT20*($row->BT10-$row->BT12)==$row->BT30*$row->BT10){
                        $preciosUnitarios[]=formato2decimales($row->BT12/100);
                        $precios[]=formato2decimales(($row->BT20-$row->BT30)/100);
                    }
                else{
                    $preciosUnitarios[]=formato2decimales($row->BT10/100);
                    $precios[]=formato2decimales($row->BT20/100);
                }
                }else{
                    //productos vendidos a peso
                    if(round($row->BT10*$row->GEW1/1000,0)-round($row->BT12*$row->GEW1/1000,0)==$row->BT30){
                        $preciosUnitarios[]=formato2decimales($row->BT12/100);
                        $precios[]=formato2decimales(($row->BT20-$row->BT30)/100);

                    }else{
                        $preciosUnitarios[]=formato2decimales($row->BT10/100);
                        $precios[]=formato2decimales($row->BT20/100);
                    }
                    
                }
                    
                
                
                $iva=$row->MWNU;;
                switch($iva){
                    case 1: $tiposIva[]="10.00%";break;
                    case 2: $tiposIva[]="21.00%";break;
                    case 3: $tiposIva[]="4.00%";break;
                    default: $tiposIva[]="";
                }
                
                
                $codigosProductos[]=$row->SNR1; 
                
                $pesos[]=formato3decimales($row->GEW1/1000);
                $unidades_pesos[]=$row->PAR1;
                $numUnidades[]=$row->POS1;
                
                if($row->GEW1){
                    $unidades[]=formato3decimales($row->GEW1/1000);
                }
                else{
                     $unidades[]=$row->POS1;
                }
                $descuento=0;
                if($row->BT12-$row->BT13){
                   $descuento=$row->BT30/100;
                }
                if($row->GEW1){
                    $descuento=$row->BT30/100;
                    if($row->BT13) $descuento=0;
                }
                $otroDescuento=0;
                if($row->BT11){
                    $otroDescuento=$row->BT11/100;
                 }


                $descuentos[]=  formato2decimales($descuento);
                $otrosDescuentos[]=  formato2decimales($otroDescuento);

                $codigosIva[]=$row->MWNU;
               
               
            }
            if ($row->STYP == 5 ) {
                //$anulaciones[]=  formato2decimales(-$row->BT30/100);
            }
            if ($row->STYP == 8) {
                $formaP=$row->PAR1;
                
                switch($formaP){
                    case 1: $formaP="Entregado";break;
                    case 2: $formaP="Cheque";break;
                    case 3: $formaP="Vale";break;
                    case 4: $formaP="Tarjeta de Crédido";break;
                    case 5: $formaP="Transferencia";break;
                    case 6: $formaP="A cuenta";break;
                    case 20: $formaP="Cambio";break;
                    default: $formaP="Tipo DESCONOCIDO";
                }
                /*
                switch($formaP){
                    case 1: $formaPago[$formaP]="Entregado";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 2: $formaPago[$formaP]="Cheque";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 3: $formaPago[$formaP]="Vale";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 4: $formaPago[$formaP]="Tarjeta de Crédido";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 5: $formaPago[$formaP]="Transferencia";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 6: $formaPago[$formaP]="A cuenta";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 20: $formaPago[$formaP]="Cambio";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    default: $formaPago[$formaP]="Tipo DESCONOCIDO";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                }
                 * 
                 */
                $formaPago[]=$formaP;
                $importeFormaPago[]=  ($row->BT10);
            }
            if ($row->STYP == 6) {
                $tipoIvasSum[]=  formato2decimales($row->MWSA/100);
                $textos[]=$row->TEXT;
                $ivas[]=formato2decimales($row->BT12/100);
                $netos[]=formato2decimales($row->BT10/100);
                $brutos[]=formato2decimales($row->BT20/100);
                $codigosIvaSum[]=$row->MWNU;
            }
        }
            
            //se comprueba si hay pesos /unidades
            $pesados=false;
            $piezas=false;
            $otros=false;
            foreach($unidades_pesos as $k=> $v){
                if($v==0 or $v==4) $pesados=true;
                if($v==1 or $v== 3) $piezas=true;
                if($v==2) $otros=true;
            }
           
            $sumaIvas=0;
            if (sizeof($ivas)>1){
                foreach($ivas as $k=> $v){
                    $sumaIvas+=intval($v);
                }
            }
            
            //chequeamos si en tickets antiguos existen STYP=6 y/o STYP=8 que están registrados en pe_bokaStyp6
            $query = "SELECT * FROM pe_bokaStyp6 WHERE BONU='$bonu' AND ZEIS='$partes[1] $partes[2]'";
            $query = $this->db->query($query);
            foreach ($query->result() as $row) {
                if ($row->STYP == 8) {
                $formaP=$row->PAR1;
                switch($formaP){
                    case 1: $formaP="Entregado";break;
                    case 2: $formaP="Cheque";break;
                    case 3: $formaP="Vale";break;
                    case 4: $formaP="Tarjeta de Crédido";break;
                    case 5: $formaP="Transferencia";break;
                    case 6: $formaP="A cuenta";break;
                    case 20: $formaP="Cambio";break;
                    default: $formaP="Tipo DESCONOCIDO";
                }
                $formaPago[]=$formaP;
                $importeFormaPago[]=  ($row->BT10);
                }
                if ($row->STYP == 6) {
                $tipoIvasSum[]=  formato2decimales($row->MWSA/100);
                $textos[]=$row->TEXT;
                $ivas[]=formato2decimales($row->BT12/100);
                $netos[]=formato2decimales($row->BT10/100);
                $brutos[]=formato2decimales($row->BT20/100);
                $codigosIvaSum[]=$row->MWNU;
                }
            
            }
                
            //calculo saldo -- por si lo pagado NO coinide con el total ticket
            $saldo=$totalTicket;
            foreach($importeFormaPago as $k=>$v){
                if($formaPago[$k]=="Cambio") $saldo+=$v;
                else $saldo-=$v;
            }
            if ($saldo!=0) {
                $importeFormaPago[]=$saldo;
                $formaPago[]='Saldo';
            }
            $totalTicket=formato2decimales($totalTicket/100);
            foreach($importeFormaPago as $k=>$v){
                $importeFormaPago[$k]=formato2decimales($v/100);
            }
            
            return array('numCaja' => $numCaja,
                'subDepartamento' => $subDepartamento,
                'numBascula' => $numBascula,
                'referencia' => $referencia,
                'fecha' => $fecha,
                'numero' => $numero,
                'dependiente' => $dependiente,
                'nombreDependiente'=>$nombreDependiente,
                'fechaCierre'=>$fechaCierre,
                'productos'=>$productos,
                'codigosProductos'=>$codigosProductos,
                'precios'=>$precios,
                'tiposIva'=>$tiposIva,
                'unidades'=> $unidades,
                'pesos'=>$pesos,
                'numUnidades'=>$numUnidades,
                
                'numPartidasTicket'=>$numPartidasTicket,
                'totalTicket'=>$totalTicket,
                'formaPago'=>$formaPago,
                'tipoIvasSum'=>$tipoIvasSum,
                'textos'=>$textos,
                'ivas'=>$ivas,
                'netos'=>$netos,
                'brutos'=>$brutos,
                'importeFormaPago'=>$importeFormaPago,
                'preciosUnitarios'=>$preciosUnitarios,
                'preciosModificados'=>$preciosModificados,
                'unidades_pesos'=>$unidades_pesos,
                'pesados'=>$pesados,
                'piezas' =>$piezas,
                'otros'=>$otros,
                'sumaIvas'=>$sumaIvas,
                'descuentos'=>$descuentos,
                'otrosDescuentos'=>$otrosDescuentos,
                //'anulaciones'=>$anulaciones,
                'codigosIva'=>$codigosIva,
                'codigosIvaSum'=>$codigosIvaSum,
                'cliente'=>$cliente,
                'nombreCliente'=>$nombreCliente,
                'empresa'=>$empresa,
                'modo'=>$modo,
                
            );



        }
      
        //$modo pe_boka // pe_boka2
        public function getTicketPorNumero($ticket,$modo='pe_boka',$db=1) {
           
            //$ticket="RASA fecha hora"   fecha en formato europeo
            $partes=explode(' ',$ticket);
            if(!preg_match ( '/[0-9]{4}-[0-1][0-9]-[0-9]{2}/' , $partes[1])){
                  $partes[1]=substr($partes[1],6,4).'-'.substr($partes[1],3,2).'-'.substr($partes[1],0,2);
            }
            //importante esto se hace para buscar el RASA de una fecha determinada
        $query = "SELECT BONU FROM pe_boka WHERE RASA='$partes[0]' AND ZEIS='$partes[1] $partes[2]'";
        if($db==1) {
             if($this->db->query($query)->num_rows()==0) return array();
        }else{
            if($this->db->query($query)->num_rows()==0) return array();
        }
        //echo $query.'<br />'.$ticket;
        if($db==1)
            $query = $this->db->query($query);
        else
            $query = $this->db2->query($query);

        $row = $query->row();
        $bonu = $row->BONU;
        $query = "SELECT * FROM $modo WHERE BONU='$bonu' AND ZEIS='$partes[1] $partes[2]'";
        
        if($db==1)
            $query = $this->db->query($query);
        else
            $query = $this->db2->query($query);
           
        if($modo=="pe_boka2" && sizeof($query->result())==0){
            $query = "SELECT * FROM pe_boka WHERE BONU='$bonu' AND ZEIS='$partes[1] $partes[2]'";
            $query = $this->db->query($query);
        }
        
        $importeFormaPago=array();
        $productos=array();
        $codigosProductos=array();
        $precios=array();
        $preciosUnitarios=array();
        $preciosModificados=array();
        $tipoIvas=array();
        $unidades=array();
        $pesos=array();
        $unidades_pesos=array();
        $numUnidades=array();
        $descuentos=array();
        
        $tipoIvasSum=array();
        $textos=array();
        $bases=array();
        $neto=array();
        $brutos=array();
        $ivas=array();
        $numBascula=array();
        $tiposIva=array();
        $formaPago=array();
        $netos=array();
        $codigosIva=array();
        $codigosIvaSum=array();
        $cliente="";
        $nombreCliente="";
        $empresa="";
        $modo="";
        
        foreach ($query->result() as $row) {
            if ($row->STYP == 1) {
                $numCaja = $row->WANU;
                $subDepartamento = $row->ABNU;
                $referencia = digitos($row->BONU,6);
                $fecha = trim(fechaEuropea($row->ZEIS));
                $fechaCierre=trim(fechaEuropea($row->ZEIE));
                $numero = $row->RASA;
                $dependiente = $row->BEN1;
                $numPartidasTicket=$row->POS1;
                if($row->ZEIS <'2018-02-19'){
                    switch($dependiente){
                        case 1: $nombreDependiente="Carlos";break;
                        case 2: $nombreDependiente="";break;
                        case 3: $nombreDependiente="Joan";break;
                        case 4: $nombreDependiente="Sergi";break;
                        case 10: $nombreDependiente="De Tira";break;
                        default:$nombreDependiente="";
                    }}
                    else{
                        switch($dependiente){
                            case 1: $nombreDependiente="Carlos";break;
                            case 2: $nombreDependiente="";break;
                            case 3: $nombreDependiente="Sergio";break;
                            case 4: $nombreDependiente="Sergi";break;
                            case 10: $nombreDependiente="De Tira";break;
                            default:$nombreDependiente="";
                        }
                }
                $totalTicket=($row->BT20);
                $clienteCodigo=$row->SNR1;
                $modo=$clienteCodigo%10;
                switch ($modo){
                    case 7:
                        $modo='Factura en efectivo';
                        $cliente=intval($clienteCodigo/10);
                        break;
                    case 8:
                        $modo='Reenvío';
                        $cliente=intval($clienteCodigo/10);
                        break;
                    case 9:
                        $modo='Pedido';
                        $cliente=intval($clienteCodigo/10);
                        break;
                    default:
                        $modo="FACTURA SIMPLIFICADA";
                        $cliente="";
                }
                if($cliente!==""){
                    $queryCliente="SELECT * FROM pe_clientes WHERE id_cliente='$cliente'";
                        $queryCliente = $this->db->query($queryCliente);
                        $rowCliente = $queryCliente->row();
                        if(isset($rowCliente->nombre))
                            $nombreCliente=strtoupper($rowCliente->nombre);
                        else 
                            $nombreCliente='CLIENTE SIN NOMBRE';
                        if(isset($rowCliente->empresa))
                            $empresa=$rowCliente->empresa;
                        else 
                            $empresa='';
                }
                
                
            }
            if ($row->STYP == 2) {
                $numBascula = $row->WANU;
             //   $queryProducto="SELECT * FROM pe_productos WHERE id_producto='$row->SNR1'";
                $queryProducto="SELECT * FROM pe_productos WHERE id='$row->id_pe_producto'";
                // mensaje('$row->id_pe_producto '.$row->id_pe_producto);
                $queryProducto = $this->db->query($queryProducto);
                $rowProducto = $queryProducto->row();
                //echo $row->SNR1.$rowProducto->nombre.'<br />';
              
                
                if (isset($rowProducto->nombre)  && trim($rowProducto->nombre)=="") $rowProducto->nombre="SIN DENOMINACIÓN.";
                if(isset($rowProducto->nombre) ){
                    $productos[]=strtoupper(!$rowProducto->nombre_generico?$rowProducto->nombre:$rowProducto->nombre_generico);
                }else{ 
                    $productos[]="SIN DENOMINACIÓN.";
                }
                
                
                
                $preciosModificados[]=formato2decimales($row->BT12/100);
                //$precios[]=formato2decimales($row->BT20/100);
               //$preciosUnitarios[]=formato2decimales($row->BT10/100);
                if($row->GEW1==0){
                //productos vendidos por unidades
                //preciosUnitarios o preciosModificados?
                if($row->BT20*($row->BT10-$row->BT12)==$row->BT30*$row->BT10){
                    $preciosUnitarios[]=formato2decimales($row->BT12/100);
                    $precios[]=formato2decimales(($row->BT20-$row->BT30)/100);
                }
                else{
                    $preciosUnitarios[]=formato2decimales($row->BT10/100);
                    $precios[]=formato2decimales($row->BT20/100);
                }
                }else{
                    //productos vendidos a peso
                    if(round($row->BT10*$row->GEW1/1000,0)-round($row->BT12*$row->GEW1/1000,0)==$row->BT30){
                        $preciosUnitarios[]=formato2decimales($row->BT12/100);
                        $precios[]=formato2decimales(($row->BT20-$row->BT30)/100);

                    }else{
                        $preciosUnitarios[]=formato2decimales($row->BT10/100);
                        $precios[]=formato2decimales($row->BT20/100);
                    }
                    
                }
                    
                
                
                $iva=$row->MWNU;;
                switch($iva){
                    case 1: $tiposIva[]="10.00%";break;
                    case 2: $tiposIva[]="21.00%";break;
                    case 3: $tiposIva[]="4.00%";break;
                    default: $tiposIva[]="";
                }
                
                
                $codigosProductos[]=$row->SNR1; 
                
                $pesos[]=formato3decimales($row->GEW1/1000);
                $unidades_pesos[]=$row->PAR1;
                $numUnidades[]=$row->POS1;
                
                if($row->GEW1){
                    $unidades[]=formato3decimales($row->GEW1/1000);
                }
                else{
                     $unidades[]=$row->POS1;
                }
                
                $descuentos[]=  formato2decimales($row->BT30/100);
                $codigosIva[]=$row->MWNU;
               
               
            }
            if ($row->STYP == 8) {
                $formaP=$row->PAR1;
                
                switch($formaP){
                    case 1: $formaP="Entregado";break;
                    case 2: $formaP="Cheque";break;
                    case 3: $formaP="Vale";break;
                    case 4: $formaP="Tarjeta de Crédido";break;
                    case 5: $formaP="Transferencia";break;
                    case 6: $formaP="A cuenta";break;
                    case 20: $formaP="Cambio";break;
                    default: $formaP="Tipo DESCONOCIDO";
                }
                /*
                switch($formaP){
                    case 1: $formaPago[$formaP]="Entregado";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 2: $formaPago[$formaP]="Cheque";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 3: $formaPago[$formaP]="Vale";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 4: $formaPago[$formaP]="Tarjeta de Crédido";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 5: $formaPago[$formaP]="Transferencia";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 6: $formaPago[$formaP]="A cuenta";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    case 20: $formaPago[$formaP]="Cambio";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                    default: $formaPago[$formaP]="Tipo DESCONOCIDO";$importeFormaPago[$formaP]=  formato2decimales($row->BT10/100);break;
                }
                 * 
                 */
                $formaPago[]=$formaP;
                $importeFormaPago[]=  ($row->BT10);
            }
            if ($row->STYP == 6) {
                $tipoIvasSum[]=  formato2decimales($row->MWSA/100);
                $textos[]=$row->TEXT;
                $ivas[]=formato2decimales($row->BT12/100);
                $netos[]=formato2decimales($row->BT10/100);
                $brutos[]=formato2decimales($row->BT20/100);
                $codigosIvaSum[]=$row->MWNU;
            }
        }
            
            //se comprueba si hay pesos /unidades
            $pesados=false;
            $piezas=false;
            $otros=false;
            foreach($unidades_pesos as $k=> $v){
                if($v==0 or $v==4) $pesados=true;
                if($v==1 or $v== 3) $piezas=true;
                if($v==2) $otros=true;
            }
           
            $sumaIvas=0;
            if (sizeof($ivas)>1){
                foreach($ivas as $k=> $v){
                    $sumaIvas+=$v;
                }
            }
            
            //chequeamos si en tickets antiguos existen STYP=6 y/o STYP=8 que están registrados en pe_bokaStyp6
            $query = "SELECT * FROM pe_bokaStyp6 WHERE BONU='$bonu' AND ZEIS='$partes[1] $partes[2]'";
            $query = $this->db->query($query);
            foreach ($query->result() as $row) {
                if ($row->STYP == 8) {
                $formaP=$row->PAR1;
                switch($formaP){
                    case 1: $formaP="Entregado";break;
                    case 2: $formaP="Cheque";break;
                    case 3: $formaP="Vale";break;
                    case 4: $formaP="Tarjeta de Crédido";break;
                    case 5: $formaP="Transferencia";break;
                    case 6: $formaP="A cuenta";break;
                    case 20: $formaP="Cambio";break;
                    default: $formaP="Tipo DESCONOCIDO";
                }
                $formaPago[]=$formaP;
                $importeFormaPago[]=  ($row->BT10);
                }
                if ($row->STYP == 6) {
                $tipoIvasSum[]=  formato2decimales($row->MWSA/100);
                $textos[]=$row->TEXT;
                $ivas[]=formato2decimales($row->BT12/100);
                $netos[]=formato2decimales($row->BT10/100);
                $brutos[]=formato2decimales($row->BT20/100);
                $codigosIvaSum[]=$row->MWNU;
                }
            
            }
                
            //calculo saldo -- por si lo pagado NO coinide con el total ticket
            $saldo=$totalTicket;
            foreach($importeFormaPago as $k=>$v){
                if($formaPago[$k]=="Cambio") $saldo+=$v;
                else $saldo-=$v;
            }
            if ($saldo!=0) {
                $importeFormaPago[]=$saldo;
                $formaPago[]='Saldo';
            }
            $totalTicket=formato2decimales($totalTicket/100);
            foreach($importeFormaPago as $k=>$v){
                $importeFormaPago[$k]=formato2decimales($v/100);
            }
            
            return array('numCaja' => $numCaja,
                'subDepartamento' => $subDepartamento,
                'numBascula' => $numBascula,
                'referencia' => $referencia,
                'fecha' => $fecha,
                'numero' => $numero,
                'dependiente' => $dependiente,
                'nombreDependiente'=>$nombreDependiente,
                'fechaCierre'=>$fechaCierre,
                'productos'=>$productos,
                'codigosProductos'=>$codigosProductos,
                'precios'=>$precios,
                'tiposIva'=>$tiposIva,
                'unidades'=> $unidades,
                'pesos'=>$pesos,
                'numUnidades'=>$numUnidades,
                
                'numPartidasTicket'=>$numPartidasTicket,
                'totalTicket'=>$totalTicket,
                'formaPago'=>$formaPago,
                'tipoIvasSum'=>$tipoIvasSum,
                'textos'=>$textos,
                'ivas'=>$ivas,
                'netos'=>$netos,
                'brutos'=>$brutos,
                'importeFormaPago'=>$importeFormaPago,
                'preciosUnitarios'=>$preciosUnitarios,
                'preciosModificados'=>$preciosModificados,
                'unidades_pesos'=>$unidades_pesos,
                'pesados'=>$pesados,
                'piezas' =>$piezas,
                'otros'=>$otros,
                'sumaIvas'=>$sumaIvas,
                'descuentos'=>$descuentos,
                'codigosIva'=>$codigosIva,
                'codigosIvaSum'=>$codigosIvaSum,
                'cliente'=>$cliente,
                'nombreCliente'=>$nombreCliente,
                'empresa'=>$empresa,
                'modo'=>$modo,
                
            );
            
    }
    
    function getImporteCliente($ticket){
        //$ticket="RASA fecha hora"   fecha en formato europeo
            $partes=explode(' ',$ticket);
            //$partes[1]=substr($partes[1],6,4).'-'.substr($partes[1],3,2).'-'.substr($partes[1],0,2);
            //importante esto se hace para buscar el RASA de una fecha determinada
        
        $sql="SELECT b.ZEIS as fecha, b.RASA as RASA, b.BONU as BONU,b.BT20 as BT20,b.SNR1 as SNR1,c.id_cliente as id_cliente,c.nombre as nombre,c.empresa as empresa FROM pe_boka b "
                . " LEFT JOIN  pe_clientes c ON c.id_cliente=b.SNR1 DIV 10 "
                . " WHERE STYP=1 AND RASA='$partes[0]' AND b.SNR1%10>=5 AND ZEIS='$partes[1] $partes[2]'";
       log_message('INFO', $sql);
       
        $query=$this->db->query($sql);
        $row=$query->row();
        $bonu="";
        $rasa="";
        $importe="";
        $cliente="";
        $nombre="";
        $empresa="";
        $id_cliente="";
        $fecha="";
        if($row){
            $fecha=$row->fecha;
            $id_cliente=$row->SNR1;
            $id_cliente=(int)($id_cliente/10);
            $rasa=$row->RASA;
            $bonu=$row->BONU;
            $importe=$row->BT20;
            $cliente=$row->id_cliente;
            $nombre=$row->nombre;
            $empresa=$row->empresa;
        }
         return array('fecha'=>$fecha,'id_cliente'=>$id_cliente,  'rasa'=>$rasa,'bonu'=>$bonu,   'importe'=>$importe,'cliente'=>$cliente,'nombre'=>$nombre,'empresa'=>$empresa, 'sql'=>$sql);       
}
    
    function grabarDatosCaja(){
            extract($_POST);
            $fecha;
            $cambioMañana=(float)$cambioMañana;
            $ventaMetalico=(float)$ventaMetalico;
            $ventaTajeta=(float)$ventaTajeta;
            $ventaACuenta=(float)$ventaACuenta;
            $ventaTransferencia=(float)$ventaTransferencia;
            $ventaVale=(float)$ventaVale;
            $ventaCheque=(float)$ventaCheque;
            $cobroAtrasos=(float)$cobroAtrasos;
            $ventaNoCobrada=(float)$ventaNoCobrada;
            $retiroMetalico=(float)$retiroMetalico;
            $retiroTarjeta=(float)$retiroTarjeta;
            $retiroVale=(float)$retiroVale;
            $retiroCheque=(float)$retiroCheque;
            $cambioNoche=(float)$cambioNoche;
            $saldoBanco=(float)$saldoBanco;
            $notas;
            
            
            $this->load->database();
            $sql="SELECT * FROM pe_caja WHERE fecha='$fecha'";
            $query = $this->db->query($sql);
            
            if ($query->num_rows()>0){
                
             $sql="UPDATE pe_caja SET "
                     . "fecha='$fecha', "
                     . "cambioManana='$cambioMañana', "
                     . "ventaMetalico='$ventasMetalico', "
                     . "ventaTarjeta='$ventasTajeta', "
                     . "ventaACuenta='$ventasACuenta', "
                     . "ventaTransferencia='$ventasTransferencia', "
                     . "ventaVale='$ventasVale', "
                     . "ventaCheque='$ventasCheque', "
                     . "cobroAtrasos='$cobroAtrasos', "
                     . "ventaNoCobrada='$ventaNoCobrada', "
                     . "retiroMetalico='$retiroMetalico', "
                     . "retiroTarjeta='$retiroTarjeta', "
                     . "retiroVale='$retiroVale', "
                     . "retiroCheque='$retiroCheque', "
                     . "cambioNoche='$cambioNoche', "
                     . "saldoBanco='$saldoBanco', "
                     . "notas='$notas'"
                     . " WHERE fecha='$fecha' ";
            
            }else {
            $sql="INSERT INTO pe_caja SET "
                     . "fecha='$fecha', "
                     . "cambioManana='$cambioMañana', "
                     . "ventaMetalico='$ventasMetalico', "
                     . "ventaTarjeta='$ventaTajeta', "
                     . "ventaACuenta='$ventasACuenta', "
                     . "ventaTransferencia='$ventaTransferencia', "
                     . "ventaVale='$ventaVale', "
                     . "ventaCheque='$ventaCheque', "
                     . "cobroAtrasos='$cobroAtrasos', "
                     . "ventaNoCobrada='$ventaNoCobrada', "
                     . "retiroMetalico='$retiroMetalico', "
                     . "retiroTarjeta='$retiroTarjeta', "
                     . "retiroVale='$retiroVale', "
                     . "retiroCheque='$retiroCheque', "
                     . "cambioNoche='$cambioNoche', "
                     . "saldoBanco='$saldoBanco', "
                     . "notas='$notas'";
            }
            $query = $this->db->query($sql);
        
                return $sql;
            }  
            
            
            

}
