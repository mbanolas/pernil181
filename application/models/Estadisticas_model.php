<?php
class Estadisticas_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->load->library('excel');
                $this->load->library('exceldrawing');
                $this->load->model('tickets_');
                $this->load->model('compras_model');
        }
        
        function getDatosVentas($id_pe_producto,$fechaDesde, $fechaHasta){
            $sql="SELECT id_producto,nombre_generico,codigo_producto FROM pe_productos WHERE id='$id_pe_producto'";
            $row=$this->db->query($sql)->row();
            $nombre_generico=$row->nombre_generico;
            $SNR1=$row->id_producto;
            
            $sql="SELECT codigo_producto FROM pe_productos WHERE id_producto='$SNR1' ORDER by codigo_producto LIMIT 1";
            $primer_codigo_producto=$this->db->query($sql)->row()->codigo_producto;
            $sql="SELECT codigo_producto FROM pe_productos WHERE id_producto='$SNR1' ORDER by codigo_producto DESC LIMIT 1";
            $ultimo_codigo_producto=$this->db->query($sql)->row()->codigo_producto;
            $codigoProducto=$primer_codigo_producto.($ultimo_codigo_producto!=$primer_codigo_producto?" -> ".$ultimo_codigo_producto:"");

            // $sql="SELECT codigo_producto FROM pe_productos WHERE id_producto='$SNR1' ORDER by codigo_producto";
            // $result=$this->db->query($sql)->result();
            // foreach($result as $k=>$v){
            //     $codigoProductoArray[]=$v->codigo_producto;
            // }
            // $codigoProducto=implode(", ",$codigoProductoArray);
            $nombre=$SNR1.' '.$nombre_generico.' ('.$codigoProducto.')';
            $sql="SELECT BT10 as pvp, left(zeis,10) as fecha FROM pe_boka WHERE STYP=2 AND SNR1='$SNR1' AND LEFT(ZEIS,10)>='$fechaDesde' AND LEFT(ZEIS,10)<='$fechaHasta' ORDER BY zeis DESC";
            
            $result=$this->db->query($sql)->result();
            $pvp=array();
            foreach($result as $k=>$v){
                $fecha= fechaEuropeaSinHora($v->fecha);
                $pvp[]=array('fecha'=>$fecha, 'pvp'=>$v->pvp/100);
            }
            return array('pvp'=>$pvp,'nombre'=>$nombre);
            
        }
        
        function getCantidadesVentas($id_pe_producto,$fechaDesde, $fechaHasta){
            $sql="SELECT id_producto,nombre,codigo_producto FROM pe_productos WHERE id='$id_pe_producto'";
            $row=$this->db->query($sql)->row();
            $SNR1=$row->id_producto;
            $comentario="";
            if($SNR1){
                $num=$this->db->query("SELECT *  FROM pe_productos WHERE id_producto='$SNR1'")->num_rows();
                log_message('INFO', "SELECT *  FROM pe_productos WHERE id_producto='$SNR1'");
                
                if($num>0) $comentario=" ($num referencias)";
                if($num==1) $comentario=" ($num referencia)";
                //$nombre=$row->id_producto.' '.$row->nombre.' ('.$row->codigo_producto.') '.$comentario;
                $nombre=$row->id_producto.' '.$row->nombre.' '.$comentario;
            }
            else{
                $comentario=" (1 referencia)";
                $nombre='NT '.$row->nombre.' '.$comentario;
            }
            
            $where="";
            if ($SNR1) {
            $result = $this->db->query("SELECT id  FROM pe_productos WHERE id_producto='$SNR1'")->result();
            $where = " (";
            foreach ($result as $k => $v) {
                    $codigo = $v->id;
                    $where .= "lp.id_pe_producto='$codigo' OR ";
                }
            $where = substr($where, 0, strlen($where) - 4) . ") ";
            }
            
            
            

            $fechaDesde=substr($fechaDesde,0,8).'01';
            $fechaHasta=substr($fechaHasta,0,8).'01';
            $fechaHasta=date('Y-m-d',strtotime('+1 month', strtotime($fechaHasta)));
            
            $und=array();
            $importe=array();
            $totalUnidades=0;
            $totalImportes=0;
            $totalUnidadesT=0;
            $totalImportesT=0;
            $totalUnidadesP=0;
            $totalImportesP=0;
            $totalUnidadesVD=0;
            $totalImportesVD=0;
            
            $fecha=$fechaDesde;
            while($fecha<$fechaHasta){
                $fechaMesSiguiente=date('Y-m-d',strtotime('+1 month', strtotime($fecha)));
                $whereP=$where;
                if ($whereP=="") $whereP = " lp.id_pe_producto='$id_pe_producto' ";
                $sqlP="SELECT  sum(lp.cantidad) as und, sum(lp.importe) as importe
                      FROM pe_orders_prestashop op
                      LEFT JOIN pe_lineas_orders_prestashop lp ON lp.id_order = op.id 
                      WHERE op.fecha>='$fecha' AND op.fecha<'$fechaMesSiguiente' AND $whereP ";
                $rowP=$this->db->query($sqlP)->row();
                if(!$SNR1) $SNR1="";
                $sql="SELECT sum(POS1) as und, sum(BT20) as importe FROM pe_boka WHERE STYP=2 AND SNR1='$SNR1' AND LEFT(ZEIS,10)>='$fecha' AND LEFT(ZEIS,10)<'$fechaMesSiguiente' ";
                $row=$this->db->query($sql)->row();
                
                $sqlVD="SELECT  sum(lp.cantidad) as und, sum(lp.importe) as importe
                      FROM pe_ventas_directas vd
                      LEFT JOIN pe_lineas_ventas_directas lp ON lp.id_venta_directa = vd.id 
                      WHERE vd.fecha>='$fecha' AND vd.fecha<'$fechaMesSiguiente' AND $whereP ";
                //return $sqlVD;
                $rowVD=$this->db->query($sqlVD)->row();
                $und[]=array('fecha'=>substr(fechaEuropeaSinHora($fecha),3), 'und'=>$row->und/1, 'undP'=>$rowP->und/1,'undVD'=>$rowVD->und/1);
                $importe[]=array('fecha'=>substr(fechaEuropeaSinHora($fecha),3), 'importe'=>$row->importe/100, 'importeP'=>$rowP->importe/100,'importeVD'=>$rowVD->importe/100);
                
                $fecha=$fechaMesSiguiente;
                $totalUnidades+=$row->und/1+$rowP->und/1+$rowVD->und/1;
                $totalImportes+=$row->importe/100+$rowP->importe/100+$rowVD->importe/100;
                $totalUnidadesT+=$row->und/1;
                $totalImportesT+=$row->importe/100;
                $totalUnidadesP+=$rowP->und/1;
                $totalImportesP+=$rowP->importe/100;
                $totalUnidadesVD+=$rowVD->und/1;
                $totalImportesVD+=$rowVD->importe/100;
                
            }
            
            $totalImportes= number_format($totalImportes,2);
            return array('sqlP'=>$sqlP,'und'=>$und,'importe'=>$importe,'nombre'=>$nombre, 'totalUnidades'=>$totalUnidades, 'totalImportes'=>$totalImportes,
                'totalUnidadesT'=>$totalUnidadesT, 'totalImportesT'=>$totalImportesT,
                'totalUnidadesP'=>$totalUnidadesP, 'totalImportesP'=>$totalImportesP,
                'totalUnidadesVD'=>$totalUnidadesVD, 'totalImportesVD'=>$totalImportesVD
                );
            
        }
        
        function getCantidadesStocks($id_pe_producto,$fechaDesde, $fechaHasta){
            $sql="SELECT id_producto,nombre,codigo_producto,stock_minimo, precio_ultimo_peso,precio_ultimo_unidad FROM pe_productos WHERE id='$id_pe_producto'";
            $row=$this->db->query($sql)->row();
            $SNR1=$row->id_producto;
            $precio=$row->precio_ultimo_unidad==0?$row->precio_ultimo_peso:$row->precio_ultimo_unidad;
            $precio/=1000;
            $stock_minimo=$row->stock_minimo;
            $comentario="";
            $num=$this->db->query("SELECT *  FROM pe_productos WHERE id_producto='$SNR1'")->num_rows();
            if($num>1) $comentario=" - En Tienda TODOS los ".$SNR1." (".$num." referencias)";
            $nombre=$row->id_producto.' '.$row->nombre.' ('.$row->codigo_producto.') '.$comentario;
            $fecha='2016-12-23';
            $fecha=substr($fecha,0,8).'01';
            $fechaSiguienteMes=date('Y-m-d',strtotime('+1 month', strtotime($fecha)));
        
        
            //$fechaDesde=substr($fechaDesde,0,8).'01';
            //$fechaHasta=substr($fechaHasta,0,8).'01';
            $fechaHasta=date('Y-m-d',strtotime('+1 day', strtotime($fechaHasta)));
            
            $und=array();
            $importe=array();
            $fecha=$fechaDesde;
            $cantidad=0;
            $valor=0;
            while($fecha<$fechaHasta){
                $fechaDiaSiguiente=date('Y-m-d',strtotime('+1 day', strtotime($fecha)));
                $sql="SELECT  sum(sh.cantidad) as und, sum(sh.cantidad)*$precio as importe
                      FROM pe_stocks_historico sh
                      WHERE sh.fecha_registro='$fecha'  AND sh.id_pe_producto='$id_pe_producto'";
                if(!is_null($this->db->query($sql)->row()->und)){
                    $row=$this->db->query($sql)->row();
                    $cantidad=$row->und/1000;
                    $valor=$row->importe/1000;
                }
                $und[]=array('fecha'=>fechaEuropeaSinHora($fecha), 'und'=>$cantidad);
                $importe[]=array('fecha'=>fechaEuropeaSinHora($fecha), 'importe'=>$valor);
                $fecha=$fechaDiaSiguiente;
            }
               
            return array('und'=>$und,'importe'=>$importe,'nombre'=>$nombre,'stock_minimo'=>$stock_minimo);
            
        }
        
        function getVentasUltimoDia(){
            $ultimoDiaTickets=$this->db->query("SELECT left(fecha,10) as fecha FROM pe_tickets ORDER BY fecha DESC LIMIT 1")->row()->fecha;
            $ultimoDiaPrestaShop=$this->db->query("SELECT fecha FROM pe_orders_prestashop ORDER BY fecha DESC LIMIT 1")->row()->fecha;
            $ultimoDiaVentaDirecta=$this->db->query("SELECT fecha FROM pe_ventas_directas ORDER BY fecha DESC LIMIT 1")->row()->fecha;
            
            $totalTickets=$this->db->query("SELECT sum(total)/100 as total FROM pe_tickets WHERE left(fecha,10)='$ultimoDiaTickets'")->row()->total;
            $totalPrestaShop=$this->db->query("SELECT sum(total)/100 as total FROM pe_orders_prestashop WHERE fecha='$ultimoDiaPrestaShop'")->row()->total;
            $totalVentasDirectas=$this->db->query("SELECT sum(importe_total)/100 as total FROM pe_ventas_directas WHERE fecha='$ultimoDiaVentaDirecta'")->row()->total;
            
            return array('ultimoDiaTickets'=>fechaEuropeaSinHora($ultimoDiaTickets),
                'ultimoDiaPrestaShop'=> fechaEuropeaSinHora($ultimoDiaPrestaShop),
                'ultimoDiaVentaDirecta'=>fechaEuropeaSinHora($ultimoDiaVentaDirecta),
                'totalTickets'=>$totalTickets,
                'totalPrestaShop'=>$totalPrestaShop,
                'totalVentasDirectas'=>$totalVentasDirectas
                 );
            
            
        }
}
