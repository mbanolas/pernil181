<?php
class Directas_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
       public function getPedidosVD($inicio, $final){
           $sql="SELECT count(id) as numPedidos, min(id) as primerPedido, max(id) as ultimoPedido FROM pe_ventas_directas WHERE fecha>='$inicio' AND fecha<='$final'";
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
           $fechaPrimerPedido=$this->db->query("SELECT fecha FROM pe_ventas_directas WHERE id='$primerPedido'")->row()->fecha;
           $ultimoPedido=$row->ultimoPedido;
           $fechaUltimoPedido=$this->db->query("SELECT fecha FROM pe_ventas_directas WHERE id='$ultimoPedido'")->row()->fecha;
           return array('numPedidos'=>$numPedidos,
                           'primerPedido'=>$primerPedido,
                           'ultimoPedido'=>$ultimoPedido,
                           'fechaPrimerPedido'=>$fechaPrimerPedido,
                           'fechaUltimoPedido'=>$fechaUltimoPedido
                           );
           
       }
       
       public function getVentasPorProducto($desde, $hasta){
           //vemos distintos tipos iva
           $periodo="id_venta_directa>='$desde' AND id_venta_directa<='$hasta'";
           $sql="SELECT tipo_iva FROM pe_lineas_ventas_directas  WHERE $periodo GROUP BY tipo_iva ORDER BY tipo_iva";
           $ivas=$this->db->query($sql)->result();
           //var_dump($ivas);
           $resultados=array();
           foreach($ivas as $k=>$v){
               $tipo_iva=$v->tipo_iva;
               $sql="SELECT tipo_iva, sum(importe) as importe, sum(iva) as iva FROM pe_lineas_ventas_directas WHERE $periodo AND tipo_iva='$tipo_iva'";
               $row=$this->db->query($sql)->row();
               $resultados[]=array('tipo_iva'=>$row->tipo_iva, 'base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );
           }
           //totales
               $sql="SELECT  sum(importe) as importe, sum(iva) as iva FROM pe_lineas_ventas_directas WHERE $periodo ";
                $row=$this->db->query($sql)->row();
               $resultadosTotal=array('base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );

           
          // var_dump($resultados);
           return array('resultados'=> $resultados, 'resultadosTotal'=>$resultadosTotal);
       }
       
       
       public function getProductos($desde,$hasta, $agrupar=false){
           
           $periodo="id_venta_directa>='$desde' AND id_venta_directa<='$hasta'";
           $sql="SELECT p.id_producto as id_producto, count(l.id_venta_directa) as pedidos, p.codigo_producto as codigo, p.nombre as nombre, sum(l.cantidad) as unidades, sum(l.importe) as importe FROM pe_lineas_ventas_directas l 
                 LEFT JOIN pe_productos p ON p.id=l.id_pe_producto
                 WHERE $periodo  GROUP BY codigo_producto ORDER BY p.codigo_producto";
           if($agrupar)
           $sql="SELECT p.id_producto as id_producto, count(l.id_venta_directa) as pedidos, p.id_producto as codigo, p.nombre as nombre, sum(l.cantidad) as unidades, sum(l.importe) as importe FROM pe_lineas_ventas_directas l 
                 LEFT JOIN pe_productos p ON p.id=l.id_pe_producto
                 WHERE $periodo  GROUP BY id_pe_producto ORDER BY p.id_producto";
            
           $result=$this->db->query($sql)->result();
          
           $productos=array();
           foreach ($result as $k=>$v){
               if(!$agrupar)
                    $productos[]=array('id_producto'=>$v->id_producto,'pedidos'=>$v->pedidos,'nombre'=>$v->nombre, 'codigo'=>$v->codigo,'unidades'=>$v->unidades,'importe'=>$v->importe);
               else {
                   $nombre=$v->nombre;
                   if(!$v->id_producto) $nombre="Sin código báscula";
                    $productos[]=array('id_producto'=>$v->id_producto,'pedidos'=>$v->pedidos,'nombre'=>$nombre, 'codigo'=>$v->id_producto,'unidades'=>$v->unidades,'importe'=>$v->importe);
               }
           }
           
            return $productos;
            
        }
       
        
        
        public function getProductosTotales($desde,$hasta){
           $periodo="id_venta_directa>='$desde' AND id_venta_directa<='$hasta'";
           $sql="SELECT count(id_venta_directa) as pedidos,sum(l.cantidad) as unidades, sum(l.importe) as importe FROM pe_lineas_ventas_directas l 
                 WHERE $periodo ";
           $row=$this->db->query($sql)->row();
            return array('pedidos'=>$row->pedidos,'unidades'=>$row->unidades,'importe'=>$row->importe);
        }
       
       
       public function getVentasPorTiposIva($desde, $hasta){
           //vemos distintos tipos iva
           $periodo="id_venta_directa>='$desde' AND id_venta_directa<='$hasta'";
           $sql="SELECT tipo_iva FROM pe_lineas_ventas_directas  WHERE $periodo GROUP BY tipo_iva ORDER BY tipo_iva";
           $ivas=$this->db->query($sql)->result();
           //var_dump($ivas);
           $resultados=array();
           foreach($ivas as $k=>$v){
               $tipo_iva=$v->tipo_iva;
               $sql="SELECT tipo_iva, sum(importe) as importe, sum(iva) as iva FROM pe_lineas_ventas_directas WHERE $periodo AND tipo_iva='$tipo_iva'";
               $row=$this->db->query($sql)->row();
               $resultados[]=array('tipo_iva'=>$row->tipo_iva, 'base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );
           }
           //totales
               $sql="SELECT  sum(importe) as importe, sum(iva) as iva FROM pe_lineas_ventas_directas WHERE $periodo ";
                $row=$this->db->query($sql)->row();
               $resultadosTotal=array('base'=>$row->importe-$row->iva, 'iva'=>$row->iva,'total'=>$row->importe );

           
          // var_dump($resultados);
           return array('resultados'=> $resultados, 'resultadosTotal'=>$resultadosTotal);
       }
}
        
