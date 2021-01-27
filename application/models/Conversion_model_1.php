<?php
class Conversion_model extends CI_Model 
{

        public function __construct()
        {
                $this->load->database();
        }
        
        //numero de tickets periodo ventas en metálico, sin cliente, sin descuentos
        public function getNumTicketsModificables($inicio, $final){
            $query="SELECT DISTINCT a.BONU
                    FROM pe_boka a  
                    INNER JOIN pe_boka b ON a.BONU=b.BONU AND (b.STYP=8 and b.PAR1=1) 
                    INNER JOIN pe_boka c ON a.BONU=c.BONU AND b.BONU=c.BONU AND c.STYP=2 AND c.BT30=0
                    WHERE a.STYP=1 AND MOD(a.SNR1,10)<7
                    AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59'" ;
                    
            $query=$this->db->query($query);
            $num=$query->num_rows();
            return $num;
        }
        //importe bruto tickets periodo ventas en metálico, sin cliente, sin descuentos
        public function getImporteBrutoModificables($inicio, $final){
           $sql="SELECT DISTINCT sum(c.bt20)+sum(c.bt30) as bruto
                    FROM pe_boka a  
                    INNER JOIN pe_boka b ON a.BONU=b.BONU AND (b.STYP=8 and b.PAR1=1) 
                    INNER JOIN pe_boka c ON a.BONU=c.BONU AND b.BONU=c.BONU AND c.STYP=2 AND c.BT30=0
                    WHERE a.STYP=1 AND MOD(a.SNR1,10)<7
                    AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ";
                    
           $query=$this->db->query($sql);
           $bruto=$query->row()->bruto;
           return $bruto/100; //formato2decimales($bruto/100);
        }
        
        public function getFormatedImporteBrutoModificables($inicio, $final){
            return formato2decimales($this->getImporteBrutoModificables($inicio, $final));
        }
        //iva bruto tickets periodo ventas en metálico, sin cliente, sin descuentos
        public function getIvaModificables($inicio, $final){
           $sql="SELECT DISTINCT sum(c.bt40) as iva
                    FROM pe_boka a  
                    INNER JOIN pe_boka b ON a.BONU=b.BONU AND (b.STYP=8 and b.PAR1=1) 
                    INNER JOIN pe_boka c ON a.BONU=c.BONU AND b.BONU=c.BONU AND c.STYP=2 AND c.BT30=0
                    WHERE a.STYP=1 AND MOD(a.SNR1,10)<7
                    AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ";
           $query=$this->db->query($sql);
           $iva=$query->row()->iva;
           return $iva/100; //formato2decimales($iva/100);
        }
        public function getFormatedIvaModificables($inicio, $final){
            return formato2decimales($this->getIvaModificables($inicio, $final));
        }
        
        //num tickets periodo 
        public function getNumTicketsTodos($inicio, $final){
            $query="SELECT DISTINCT a.BONU
                    FROM pe_boka a  
                    WHERE a.STYP=1 
                    AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59'" ;
                    
            $query=$this->db->query($query);
            $num=$query->num_rows();
            return $num;
        }
        //importe bruto tickets periodo 
        public function getImporteBrutoTodos($inicio, $final){
           $sql="SELECT DISTINCT sum(a.bt20) as bruto
                    FROM pe_boka a  
                    WHERE a.STYP=1 
                    AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ";
                    
           $query=$this->db->query($sql);
           $bruto=$query->row()->bruto;
           return $bruto/100; //formato2decimales($bruto/100);
        }
        public function getFormatedImporteBrutoTodos($inicio, $final){
            return formato2decimales($this->getImporteBrutoTodos($inicio, $final));
        }
        
        //iva tickets periodo 
        public function getIvaTodos($inicio, $final){
           $sql="SELECT DISTINCT sum(a.bt40) as iva
                    FROM pe_boka a  
                    WHERE a.STYP=2  
                    AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ";
           $query=$this->db->query($sql);
           $iva=$query->row()->iva;
           return $iva/100;//formato2decimales($iva/100);
        }
        public function getFormatedIvaTodos($inicio, $final){
            return formato2decimales($this->getIvaTodos($inicio, $final));
        }
        /*
         * $numPorCiento=  number_format($numModificables/$numTodos*100,2);
            $brutoPorCiento=  number_format($brutoModificables/$brutoTodos*100,2);
            $ivaPorCiento=  $ivaModificables/$ivaTodos; //number_format($ivaTodos,2);
         */
        
        
        
        public function getFormatedNumPorCiento($inicio, $final){
            
            return $this->getNumTicketsTodos($inicio, $final)>0?formato2decimales($this->getNumTicketsModificables($inicio, $final)/$this->getNumTicketsTodos($inicio, $final)*100):0;
        }
        public function getFormatedBrutoPorCiento($inicio, $final){
            return $this->getImporteBrutoTodos($inicio, $final)>0?formato2decimales($this->getImporteBrutoModificables($inicio, $final)/$this->getImporteBrutoTodos($inicio, $final)*100):0;
        }
        public function getFormatedIvaPorCiento($inicio, $final){
            return $this->getIvaTodos($inicio, $final)>0?formato2decimales($this->getIvaModificables($inicio, $final)/$this->getIvaTodos($inicio, $final)*100):0;
        }
       
        //obtener tickets periodo ventas en metálico, sin cliente, sin descuentos
        public function getTickets($inicio, $final){
            $sql="SELECT DISTINCT a.BONU as BONU, a.RASA as RASA, a.ZEIS as ZEIS
                    FROM pe_boka a  
                    INNER JOIN pe_boka b ON a.BONU=b.BONU AND (b.STYP=8 and b.PAR1=1) 
                    INNER JOIN pe_boka c ON a.BONU=c.BONU AND b.BONU=c.BONU AND c.STYP=2 AND c.BT30=0
                    WHERE a.STYP=1 AND MOD(a.SNR1,10)<7
                    AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ";
            
            $sql="SELECT DISTINCT a.BONU as BONU, a.RASA as RASA, a.ZEIS as ZEIS
                    FROM pe_boka as a,
                    (SELECT c.bonu FROM pe_boka as c  WHERE  c.STYP=1 AND MOD(c.SNR1,10)<7 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY c.bonu) as b,
                    (SELECT e.bonu FROM pe_boka as e  WHERE  e.STYP=8 AND e.PAR1=1 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY e.bonu) as d
                    WHERE  
                    NOT EXISTS (SELECT g.bonu FROM pe_boka g WHERE g.STYP=2 AND g.SNR1=999998 AND a.bonu=g.bonu
                    AND g.zeis>='$inicio' AND g.zeis <='$final 23:59:59' GROUP BY g.bonu) AND
                    (b.bonu=a.bonu AND d.bonu=a.bonu ) 
                    AND zeis>='$inicio' AND zeis <='$final 23:59:59'";
            
            $sql="SELECT DISTINCT a.BONU as BONU, a.RASA as RASA, a.ZEIS as ZEIS,a.BT20 
                    FROM pe_boka a  
                    INNER JOIN pe_boka b ON a.BONU=b.BONU AND (b.STYP=8 and b.PAR1=1) 
                    INNER JOIN pe_boka c ON a.BONU=c.BONU AND b.BONU=c.BONU AND c.STYP=2 AND c.BT30=0
                    WHERE a.STYP=1 AND MOD(a.SNR1,10)<7
                    AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ";
            
            
            $query=$this->db->query($sql);
            $num=$query->num_rows();
            
            $tickets=array();
            $i=1;
            $this->db->query("DELETE FROM `pe_temporal` WHERE 1");
            foreach ($query->result() as $k => $row) {
                if($row->BONU){
                    $importe=  number_format($row->BT20/100,2);
                    $ticket=$row->RASA.' '.fechaEuropea($row->ZEIS).' - Total: '.$importe.' €';
                    $tickets[$i]=$ticket;
                    $this->db->query("INSERT INTO `pe_temporal` SET id='$i', ticket='$ticket'");
                    $i++;
                }
                    
            }
            //return array('num'=>$num,'tickets'=>$tickets, );
            return array('sql'=>$sql, 'num'=>sizeof($tickets), 'num2'=> $num, 'tickets'=>$tickets,);
            
        }
        
        public function getTicketsPeriodo(){
            $query=$this->db->query("SELECT * FROM pe_temporal");
            $ticketsPeriodo=array();
            foreach($query->result() as $k=>$v){
                $ticketsPeriodo[$v->id]=$v->ticket;
            }
            return $ticketsPeriodo;
        }
        
        public function getDatosTicket($ticket){
            $query=$this->db->query("SELECT ticket FROM pe_temporal WHERE id='$ticket'");
            $numTicket= $query->row()->ticket;
            $ticket=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
            $ticket2=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka2");
            return array('ticket'=> $ticket,'ticket2'=>$ticket2);
        }
        
        public function getConversiones(){
            $sql="SET SQL_BIG_SELECTS=1";
            $this->db->query($sql);
            
            $sql="SELECT c.peso as peso, "
                    . " c.id_codigo_inicio as id_codigo_inicio, "
                    . " c.id_codigo_final as id_codigo_final, "
                    . " pr1.nombre_web as nombre_inicio, "
                    . " pr2.nombre_web as nombre_final , "
                    . " c.activa as activa, "
                    . " pr1.precio_ultimo_unidad,"
                    . " pr1.precio_ultimo_peso, "
                    . " pr2.precio_ultimo_unidad,"
                    . " pr2.precio_ultimo_peso "
                    . " FROM pe_conversiones c"
                    . " LEFT JOIN pe_productos pr1 ON pr1.id_producto=c.id_codigo_inicio AND (pr1.precio_ultimo_unidad IS NOT NULL OR pr1.precio_ultimo_peso IS NOT NULL) AND (pr1.precio_ultimo_unidad>0 OR pr1.precio_ultimo_peso>0 )"
                    . " LEFT JOIN pe_productos pr2 ON pr2.id_producto=c.id_codigo_final AND (pr2.precio_ultimo_unidad IS NOT NULL OR pr2.precio_ultimo_peso IS NOT NULL) AND (pr2.precio_ultimo_unidad>0  OR pr2.precio_ultimo_peso>0 )"
                    . " WHERE c.activa=1"
                    . " GROUP BY c.id_codigo_inicio";
            
            $sql="SELECT  c.id_codigo_inicio as id_codigo_inicio, 
                          c.id_codigo_final as id_codigo_final, 
                          pr1.nombre_web as nombre_inicio, 
                          pr2.nombre_web as nombre_final , 
                          c.activa as activa, 
                          pr1.tarifa_venta_unidad, 
                          pr1.tarifa_venta_peso, 
                          pr2.tarifa_venta_unidad, 
                          pr2.tarifa_venta_peso,
                          c.peso as peso 
                   FROM pe_conversiones c 
                   LEFT  JOIN pe_productos pr1 ON pr1.id_producto=c.id_codigo_inicio 
                         AND ((pr1.tarifa_venta_unidad IS NOT NULL AND pr1.tarifa_venta_unidad>0)  
                         OR (pr1.tarifa_venta_peso IS NOT NULL AND pr1.tarifa_venta_peso>0))  
                   LEFT JOIN  pe_productos pr2 ON pr2.id_producto=c.id_codigo_final 
                         AND ((pr2.tarifa_venta_unidad IS NOT NULL AND pr2.tarifa_venta_unidad>0) 
                         OR (pr2.tarifa_venta_peso IS NOT NULL AND pr2.tarifa_venta_peso>0))  
                   WHERE c.activa=1  
                   GROUP BY c.id_codigo_inicio";
            
            $query=$this->db->query($sql); 
            $converiones=array();
            $codigoInicio=array();
            foreach($query->result() as $k => $row){
                if($row->id_codigo_inicio!=0){
                $conversiones[$k]="$row->id_codigo_inicio"." > "."$row->id_codigo_final";
                $codigoInicio[$k]=$row->id_codigo_inicio;
                $codigoFinal[$k]=$row->id_codigo_final;
                $nombreInicio[$k]=$row->nombre_inicio;
                $nombreFinal[$k]=$row->nombre_final;
                $activa[$k]=$row->activa;
                $peso[$k]=$row->peso;
                }
            }
            return array('pesos'=>$peso,
                         'codigosIniciales'=>$codigoInicio, 
                         'codigosFinales'=>$codigoFinal,
                         'conversiones'=>$conversiones,
                         'nombreInicio'=>$nombreInicio,
                         'nombreFinal'=>$nombreFinal,
                         'activa'=>$activa,
                         'sql'=>$sql,
                         );
        }
        
         public function getConversionesTodas(){
            
            
            $sql="SELECT  c.id,c.id_codigo_inicio as id_codigo_inicio, 
                          c.id_codigo_final as id_codigo_final, 
                          pr1.nombre_web as nombre_inicio, 
                          pr2.nombre_web as nombre_final , 
                          c.activa as activa, 
                          pr1.tarifa_venta_unidad, 
                          pr1.tarifa_venta_peso, 
                          pr2.tarifa_venta_unidad, 
                          pr2.tarifa_venta_peso,
                          c.peso as peso 
                   FROM pe_conversiones c 
                   LEFT  JOIN pe_productos pr1 ON pr1.id_producto=c.id_codigo_inicio 
                         AND ((pr1.tarifa_venta_unidad IS NOT NULL AND pr1.tarifa_venta_unidad>0)  
                         OR (pr1.tarifa_venta_peso IS NOT NULL AND pr1.tarifa_venta_peso>0))  
                   LEFT JOIN  pe_productos pr2 ON pr2.id_producto=c.id_codigo_final 
                         AND ((pr2.tarifa_venta_unidad IS NOT NULL AND pr2.tarifa_venta_unidad>0) 
                         OR (pr2.tarifa_venta_peso IS NOT NULL AND pr2.tarifa_venta_peso>0))  
                   WHERE 1  
                   GROUP BY c.id_codigo_inicio";
            
            $query=$this->db->query($sql); 
            $converiones=array();
            $codigoInicio=array();
            foreach($query->result() as $k => $row){
                if($row->id_codigo_inicio!=0){
                $id[$k]=$row->id;    
                $conversiones[$k]="$row->id_codigo_inicio"." > "."$row->id_codigo_final";
                
                $codigoInicio[$k]=$row->id_codigo_inicio;
                $codigoFinal[$k]=$row->id_codigo_final;
                $nombreInicio[$k]=$row->nombre_inicio;
                $nombreFinal[$k]=$row->nombre_final;
                $activa[$k]=$row->activa;
                $peso[$k]=$row->peso;
                }
            }
            return array('id'=>$id,
                        'pesos'=>$peso,
                         'codigosIniciales'=>$codigoInicio, 
                         'codigosFinales'=>$codigoFinal,
                         'conversiones'=>$conversiones,
                         'nombreInicio'=>$nombreInicio,
                         'nombreFinal'=>$nombreFinal,
                         'activa'=>$activa,
                         'sql'=>$sql,
                         );
        }
        
        //obtener tickets periodo ventas en metálico, sin cliente, sin descuentos
        public function getTicketsConvertir($inicio,$final,$codigosIniciales){
            $numTickets=array();
            foreach($codigosIniciales as $k=>$v){
                //para probar directamente en phpMyAdmin
                $sql="
                    SELECT g.bonu FROM pe_boka g WHERE g.STYP=2 AND g.SNR1=999998 
                    AND g.zeis>='2015-11-01' AND g.zeis <='2015-11-31 23:59:59' GROUP BY g.bonu;
                    SELECT a.bonu
                    FROM pe_boka as a,
                    (SELECT c.bonu FROM pe_boka as c  WHERE  c.STYP=1 AND MOD(c.SNR1,10)<7 AND c.zeis>='2015-11-01' AND g.zeis <='2015-11-31 23:59:59' GROUP BY c.bonu) as b,
                    (SELECT e.bonu FROM pe_boka as e  WHERE  e.STYP=8 AND e.PAR1=1 AND e.zeis>='2015-11-01' AND g.zeis <='2015-11-31 23:59:59' GROUP BY e.bonu) as d
                    WHERE NOT EXISTS (SELECT g.bonu FROM pe_boka g WHERE g.STYP=2 AND g.SNR1=999998 AND a.bonu=g.bonu
                    AND g.zeis>='2015-11-01' AND g.zeis <='2015-11-31 23:59:59' GROUP BY g.bonu) AND
                    (b.bonu=a.bonu AND d.bonu=a.bonu ) AND a.STYP=2 AND a.SNR1='7' 
                    AND a.zeis>='2015-11-01' AND g.zeis <='2015-11-31 23:59:59'";
                
                $sql="SELECT count(DISTINCT a.bonu) as tickets, count(a.bonu) as lineas, sum(a.pos1) as partidas,sum(a.bt20) as pvp, sum(a.bt40) as iva
                    FROM pe_boka as a,
                    (SELECT c.bonu FROM pe_boka as c  WHERE  c.STYP=1 AND MOD(c.SNR1,10)<7 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY c.bonu) as b,
                    (SELECT e.bonu FROM pe_boka as e  WHERE  e.STYP=8 AND e.PAR1=1 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY e.bonu) as d
                    WHERE  
                    NOT EXISTS (SELECT g.bonu FROM pe_boka g WHERE g.STYP=2 AND g.SNR1=999998 AND a.bonu=g.bonu
                    AND g.zeis>='$inicio' AND g.zeis <='$final 23:59:59' GROUP BY g.bonu) AND
                    (b.bonu=a.bonu AND d.bonu=a.bonu ) AND a.STYP=2 AND a.SNR1='$v' 
                    AND zeis>='$inicio' AND zeis <='$final 23:59:59'";
                
                $query=$this->db->query($sql);
                $numTickets[$k]=$query->row()->lineas;//$query->num_rows();
                $pvp[$k]=$query->row()->pvp;
                $iva[$k]=$query->row()->iva;
                $partidas[$k]=$query->row()->partidas;
            }
            return array('partidas'=>$partidas,'lineas'=>$numTickets,'pvp'=>$pvp,'iva'=>$iva,'sql'=>$sql,);
        }
        
        public function getLineasConvertir($inicio,$final,$ticketsFinales){
            //var_dump($ticketsFinales['productosFinales']);
            //var_dump($ticketsFinales['precios']);
            //var_dump($ticketsFinales['pesos']);
            //exit();
            $sql="DELETE FROM pe_lineas_convertir WHERE 1";
            $query=$this->db->query($sql);
            $sql="DELETE FROM pe_lineas_convertir_base WHERE 1";
            $query=$this->db->query($sql);
            
            
            $sql="SELECT id_codigo_inicio as codigoInicial,  
                         id_codigo_final as codigoFinal,  
                         c.peso as pesoFinal, 
                         if(pr.tarifa_venta_peso!=0, pr.tarifa_venta_peso,pr.tarifa_venta_unidad) as tarifa_venta,
                         if(pr.tarifa_venta_peso!=0, c.peso*pr.tarifa_venta_peso,pr.tarifa_venta_unidad) as pvpFinal,
                         iva.valor_iva*100 as valor_iva 
                    FROM pe_conversiones c 
                    LEFT JOIN pe_productos pr ON  pr.id_producto=c.id_codigo_final  
                    LEFT JOIN pe_grupos g ON g.id_grupo=pr.id_grupo  
                    LEFT JOIN pe_ivas iva ON iva.id_iva=g.id_iva  
                    WHERE activa=1 GROUP BY id_codigo_inicio";
            $query=$this->db->query($sql);
            
            $codigosActivos=array();
            $codigoFinal=array();
            $pesoFinal=array();
            $tarifa_ventaFinal=array();
            $pvpFinal=array();
            $valorIvaFinal=array();
            $ivaFinal=array();
            foreach($query->result() as $k=>$v){
                
                $codigosActivos[]=$v->codigoInicial;
                $codigoFinal[]=$v->codigoFinal;
                $pesoFinal[]=$v->pesoFinal;
                $pvpFinal[]=$v->pvpFinal;
                $tarifa_ventaFinal[]=$v->tarifa_venta;
                $valorIvaFinal[]=$v->valor_iva;
                $ivaFinal[]=$v->pvpFinal/(10000+$v->valor_iva)*10000;
                
                
            }
            
            //var_dump($tarifa_ventaFinal); exit();
            
            $lineas=array();
            foreach($codigosActivos as $k=>$v){
                /* Se seleccionan las lineas que
                 * - estén dentro de periodo
                 * - que tengan una lineaa con el producto a convertir y NO sea con importe negativo
                 * - que NO tenga, en el mismo ticket el producto devuelto (producto con importe positivo y mismo producto con mismo importe negativo
                 * - que el ticket no tenga entrada negativa (código producto 999998, es decir se haya realizado un descuento de ajuste total ticket
                 * - que no tenga asignado cliente
                 * - que se haya pagado en metálico
                 */
                $valorIvaFinal[$k]=$valorIvaFinal[$k]==""?1000:$valorIvaFinal[$k];
                $sql="SELECT a.id, a.bonu as referencia, b.RASA as ticket,a.SNR1 as productoInicial, a.PAR1 as partidas, a.bt20 as pvp, a.bt40 as iva, 
                    $codigoFinal[$k] as codigoFinal, $pesoFinal[$k] as pesoFinal, $valorIvaFinal[$k] as valorIvaFinal,$pvpFinal[$k] as pvpFinal ,$ivaFinal[$k] as ivaFinal, $tarifa_ventaFinal[$k] as tarifa_ventaFinal
                    FROM pe_boka as a,
                    (SELECT c.bonu, c.RASA FROM pe_boka as c  WHERE  c.STYP=1 AND MOD(c.SNR1,10)<7 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY c.bonu) as b,
                    (SELECT e.bonu FROM pe_boka as e  WHERE  e.STYP=8 AND e.PAR1=1 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY e.bonu) as d
                    WHERE  
                    NOT EXISTS (SELECT g.bonu FROM pe_boka g WHERE g.STYP=2 AND g.SNR1=999998 AND a.bonu=g.bonu
                    AND g.zeis>='$inicio' AND g.zeis <='$final 23:59:59' GROUP BY g.bonu)
                    AND 
                    NOT EXISTS (SELECT h.bonu FROM pe_boka h WHERE h.STYP=2 AND h.SNR1='$v' AND a.bonu=h.bonu AND h.bt20=-a.bt20
                    AND h.zeis>='$inicio' AND h.zeis <='$final 23:59:59' GROUP BY h.bonu)
                    AND b.bonu=a.bonu AND d.bonu=a.bonu  AND a.STYP=2 AND a.SNR1='$v' AND a.bt20>0
                    AND zeis>='$inicio' AND zeis <='$final 23:59:59'";
   
                //if($k==3) {echo $sql; exit();};
                  
                $query=$this->db->query($sql);
                
                
                
                foreach($query->result() as $k=>$v){
                    $p=array_search($v->codigoFinal,$ticketsFinales['productosFinales']);
                    $productoFinal_db=$ticketsFinales['productosFinales'][$p];
                    $pesoFinal_db=$ticketsFinales['pesos'][$p];
                    
                    $pvpFinal_db=$ticketsFinales['precios'][$p];
                    $ivaFinal_db=$ticketsFinales['precios'][$p]*1000/(10000+$ticketsFinales['porcentajesIvas'][$p]);
                   
                    $lineas[]=array('id'=>$v->id,
                                    'referencia' =>$v->referencia,
                                    'ticket' =>$v->ticket,
                                    'productoInicial' => $v->productoInicial,
                                    'partidas'=>$v->partidas,
                                    'pvp' => $v->pvp,
                                    'iva' => $v->iva,
                                    'productoFinal'=> $productoFinal_db,
                                    'tarifa_ventaFinal'=>$v->tarifa_ventaFinal,
                                    'pesoFinal'=> $pesoFinal_db,
                                    'pvpFinal'=>$pvpFinal_db,
                                    'ivaFinal'=>$ivaFinal_db, 
                                    'valorIvaFinal'=>$v->valorIvaFinal,
                        );
                    
                    
                    
                    $sql="INSERT INTO pe_lineas_convertir (id,referencia, ticket, productoInicial, partidas, pvp, iva, productoFinal, tarifa_venta,pesoFinal,pvpFinal, ivaFinal,valorIvaFinal)"
                            . " VALUES ("
                            . " $v->id, "
                            . " $v->referencia, "
                            . " $v->ticket, "
                            . " $v->productoInicial, "
                            . " $v->partidas, "
                            . " $v->pvp, "
                            . " $v->iva, "
                            . " $productoFinal_db, "
                            . " $v->tarifa_ventaFinal, "
                            . " $pesoFinal_db, " 
                            . " $pvpFinal_db, "
                            . " $ivaFinal_db, "
                            . " $v->valorIvaFinal "
                            . " )";
                    
                   
                    
                    $query2=$this->db->query($sql);
                    $sql="INSERT INTO pe_lineas_convertir_base (id,referencia, ticket, productoInicial,partidas, pvp, iva, productoFinal, tarifa_venta,pesoFinal, pvpFinal, ivaFinal,valorIvaFinal)"
                            . " VALUES ("
                            . " $v->id, "
                            . " $v->referencia, "
                            . " $v->ticket, "
                            . " $v->productoInicial, "
                            . " $v->partidas, "
                            . " $v->pvp, "
                            . " $v->iva, "
                            . " $productoFinal_db, "
                           . " $v->tarifa_ventaFinal, "
                            . " $pesoFinal_db, "
                            . " $pvpFinal_db, "
                            . " $ivaFinal_db, "
                           . " $v->valorIvaFinal "
                            . " )";
                    $query2=$this->db->query($sql);
                    
                    
                     
                }
            }
            
            return $lineas;
        }
        
        public function makeConversiones(){
            $objetivo=$_POST['objetivo']*100;
            $resultado='';
            $difPVP=0;
            $difIVA=0;
            
            $sql="DELETE FROM pe_lineas_convertir";
            $this->db->query($sql);
            
            $sql="INSERT INTO pe_lineas_convertir SELECT * FROM pe_lineas_convertir_base";
            $this->db->query($sql);
            
            while ($difIVA<$objetivo){
            $sql="SELECT FLOOR(RAND()*COUNT(*)) as offset FROM pe_lineas_convertir";
                $query=$this->db->query($sql);
                $offset=$query->row()->offset;
                
            $sql="SELECT id,pvp,iva,pvpFinal,ivaFinal FROM pe_lineas_convertir LIMIT $offset,1";
                $query=$this->db->query($sql);
                $difPVP=$difPVP+$query->row()->pvp-$query->row()->pvpFinal;
                $difIVA=$difIVA+$query->row()->iva-$query->row()->ivaFinal;
                $id=$query->row()->id;
             $sql="DELETE  FROM pe_lineas_convertir WHERE id='$id'";  
                $this->db->query($sql);
            }
            //preparar salida con info conversiones
            $sql="SELECT id, ticket,productoInicial,partidas,pvp,iva,productoFinal,pvpFinal,ivaFinal FROM pe_lineas_convertir_base";
            $query=$this->db->query($sql);
            $lineas=array();
            foreach($query->result() as $k=>$v){
                $lineas[]=array('id'=>$v->id,
                                'ticket'=>$v->ticket,
                                'productoInicial'=>$v->productoInicial,
                                'partidas'=>$v->partidas,
                                'pvp'=>$v->pvp,
                                'iva'=>$v->iva,
                                'productoFinal'=>$v->productoFinal,
                                'pvpFinal'=>$v->pvpFinal,
                                'ivaFinal'=>$v->ivaFinal);
            }
            foreach($lineas as $k=>$v){
                $id=$v['id'];
                $sql="SELECT id FROM pe_lineas_convertir WHERE id=$id";
                $existe=$this->db->query($sql)->num_rows();
                if ($existe) $lineas[$k]['convertido']=false; else $lineas[$k]['convertido']=true;
            }
            $tablaLineas=$this->tablaLineas($lineas);
            
            $productoFinal=$this->registrarConversiones();
            
            return array('productoFinal'=>$productoFinal,'difIVA'=>$difIVA,'tablaLineas'=>$tablaLineas);
        }
        
        public function registrarConversiones(){
            
            //seleccion lineas cambiadas
            $sql="SELECT b.referencia as bonu FROM pe_lineas_convertir_base  b 
                WHERE NOT EXISTS (SELECT referencia FROM  pe_lineas_convertir p WHERE p.id=b.id GROUP BY referencia) 
                GROUP BY b.referencia";
            $query=$this->db->query($sql);
            $productoFinal=array();
            foreach($query->result() as $k=>$v){
                $v=$v->bonu;
                $query2 = $this->db->query("DELETE FROM pe_boka2 WHERE BONU='$v'");
                //copiamos información de pe_boka a pe_boka2 con BONU=$bonu
                $query3 = $this->db->query("INSERT INTO pe_boka2 SELECT * FROM pe_boka WHERE BONU='$v'");
              
                $sql="SELECT b.id, 
                                b.referencia as bonu, 
                                b.productoFinal as productoFinal,
                                b.tarifa_venta as tarifa_venta,
                                b.pvpFinal as pvpFinal,
                                b.ivaFinal as ivaFinal,
                                b.pesoFinal as pesoFinal,
                                b.valorIvaFinal as valorIvaFinal
                    FROM pe_lineas_convertir_base  b 
                    WHERE NOT EXISTS (SELECT referencia FROM  pe_lineas_convertir p WHERE p.id=b.id ) AND b.referencia='$v'
                        ";
                $query4=$this->db->query($sql);
                foreach($query4->result() as $k1=>$v1){
                    $sql="UPDATE pe_boka2 
                                SET SNR1=$v1->productoFinal, 
                                bt10=$v1->tarifa_venta,
                                bt12=$v1->tarifa_venta,
                                bt20=$v1->pvpFinal,
                                bt40=$v1->ivaFinal,
                                gew1=$v1->pesoFinal,
                                mwsa=$v1->valorIvaFinal    
                          WHERE pe_boka2.id=$v1->id
                        ";
                    $this->db->query($sql);
                }
                $sql=$this->recomponerTicket($v);
                
            }
            return $sql;
            
        }
        
        function recomponerTicket($bonu){
            //STYP=2  
            $sql="SELECT sum(bt20) as importeTotal ,MWSA as tipoIva ,sum(bt40) as iva
                         FROM pe_boka2 
                         WHERE STYP=2 AND bonu='$bonu' GROUP BY MWSA;
                         "; 
            $query=$this->db->query($sql);
            $importeTotalTicket=0;
            foreach($query->result() as $k=>$v){
                $importeTotal[$v->tipoIva]=$v->importeTotal;
                $iva[$v->tipoIva]=$v->iva;
                $importeTotalTicket+=$v->importeTotal;
            }
            
            $sql="UPDATE pe_boka2 
                    SET bt20='$importeTotalTicket'
                  WHERE STYP=1 AND bonu='$bonu'      
                    ";
            $query=$this->db->query($sql);
            $sql="UPDATE pe_boka2 
                    SET bt10='$importeTotalTicket'
                  WHERE STYP=8 AND PAR1=1 AND bonu='$bonu'      
                    ";
            $query=$this->db->query($sql);
            //var_dump($importeTotal); exit;
            foreach($importeTotal as $k=>$v){
                $sql="SELECT id FROM pe_boka2 WHERE STYP=6 AND bonu='$bonu' AND MWSA='$k'
                    ";
                //echo $sql;
                $query=$this->db->query($sql);
                $ivaTipo=$iva[$k];
                $base=$v-$ivaTipo;
                $id=$query->row()->id;
                
              //  if($query->num_rows){
                    $sql="UPDATE pe_boka2 SET bt20='$v',bt12='$ivaTipo',bt10='$base' WHERE pe_boka2.id=$id";
                    //echo $sql;
                    $this->db->query($sql);
               // }
                
            }
            
            
        }
        
        function tablaLineas($lineas){
             $tablaLineasEncabezado="<table>
                        <thead>
                        <tr>
                            <th class='tconversion'>Ticket</th>
                            <th class='tconversion'>Producto inicial</th>
                            <th class='tconversion'>PVP inicial</th>
                            <th class='tconversion'>IVA inicial</th>
                            <th class='tconversion'><span style='color:#f2f2f2'>---</span></th>
                            <th class='tconversion'>Producto final</th>
                            <th class='tconversion'>PVP final</th>
                            <th class='tconversion'>IVA final</th>
                            <th class='tconversion'><span style='color:#f2f2f2'>---</span></th>
                            <th class='tconversion'>Diferencia PVP</th>
                            <th class='tconversion'>Diferencia IVA__</th>
                            <th class='tconversion'><span style='color:#f2f2f2'>---</span></th>
                            <th class='tconversion'>Convertido</th>
                        </tr>
                        </thead>
                        ";
            $tablaLineasCuerpo="<tbody>";
                  foreach($lineas as $k=>$v){  
                       $ticket=$v['ticket'];
                       $productoInicial=$v['productoInicial'];
                       $pvp=  number_format($v['pvp']/100,2,".",",");
                       $iva=number_format($v['iva']/100,2,".",",");
                       $productoFinal=$v['productoFinal'];
                       
                       $pvpFinal=number_format($v['pvpFinal']/100,2,".",",");
                       $ivaFinal=number_format($v['ivaFinal']/100,2,".",",");
                       
                       $pvpDiferencia=number_format(($v['pvp']-$v['pvpFinal'])/100,2,".",",");
                       $ivaDiferencia=number_format(($v['iva']-$v['ivaFinal'])/100,2,".",",");       
                       $convertido=$v['convertido']?'convertido':'';
                       
                        $tablaLineasCuerpo.="<tr>
                            <td class='tconversion $convertido'><span '>$ticket</span></td>
                            <td class='tconversion $convertido'>$productoInicial</td>
                            <td class='tconversion $convertido'>$pvp</td>
                            <td class='tconversion $convertido'>$iva</td>
                            <td class='tconversion $convertido'></td>
                            <td class='tconversion $convertido'>$productoFinal</td>
                            <td class='tconversion $convertido'>$pvpFinal</td>
                            <td class='tconversion $convertido'>$ivaFinal</td>
                            <td class='tconversion $convertido'></td>
                            <td class='tconversion $convertido'>$pvpDiferencia</td>
                            <td class='tconversion $convertido'>$ivaDiferencia</td>
                            <td class='tconversion $convertido'></td>
                            <td class='tconversion $convertido'>$convertido</td>
                                
                        </tr>";
                  };
            $tablaLineasCuerpo.="</tbody>";
            $tablaLineasPie="
                        <tfoot>
                        <tr>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                            <th class='tconversion'></th>
                        </tr>
                        </tfoot>
                        ";
            $tablaLineas=$tablaLineasEncabezado.$tablaLineasCuerpo.$tablaLineasPie;
            return $tablaLineas;
        }
        
        public function getTicketsConvertirFinal($pesos,$codigosFinales){
            //obtener último precio de boka para cada producto final
            foreach($codigosFinales as $k=>$v){
                $sql="SELECT  bt12 as precio,gew1 as peso, mwsa as porcentajeIva FROM pe_boka WHERE STYP=2 AND SNR1='$v' ORDER BY ZEIS DESC LIMIT 1";
                $query=$this->db->query($sql);
                $productosFinales[$k]=$v;
                if($query->row()){
                    $precios[$k]=$query->row()->precio;
                    $porcentajesIvas[$k]=$query->row()->porcentajeIva;
                    $pesoBoka[$k]=$query->row()->peso;
                }
                else {
                    $precios[$k]=0;
                    $porcentajesIvas[$k]=0;
                    $pesoBoka[$k]=0;
                }
                
                //si $pesos[$k]=0 se trata de un producto por unidades
                $precios[$k]= $pesoBoka[$k]==0?$precios[$k]:$precios[$k]*$pesos[$k]/1000;
                
                if($precios[$k]==0){
                    //echo $v.' '.$precios[$k].' '.$pesos[$k].' - ';
                //si no existe el precio en boka lo leo de pe_productos
                $sql="SELECT peso_real, tarifa_venta_unidad, tarifa_venta_peso , iv.valor_iva  as porcentajeIva
                       FROM pe_productos pr 
                       INNER JOIN pe_grupos g ON pr.id_grupo=g.id_grupo
                       INNER JOIN pe_ivas iv ON iv.id_iva=g.id_iva
                       WHERE id_producto='$v' GROUP BY id_producto
                    ";
                $query=$this->db->query($sql);
                
                if($query->row()){
                    
                    $precios[$k]=$query->row()->tarifa_venta_unidad==0?$query->row()->tarifa_venta_peso*$query->row()->peso_real/1000:$query->row()->tarifa_venta_unidad;
                    //if($peso==0) $precios[$k]=$query->row()->tarifa_venta_unidad; else $precios[$k]=$query->row()->tarifa_venta_peso;
                    $porcentajesIvas[$k]=$query->row()->porcentajeIva;
                }
                else {
                    $precios[$k]=0;
                    $porcentajesIvas[$k]=0;
                }
                //$precios[$k]= $pesos[$k]==0?$precios[$k]:$precios[$k]*$pesos[$k]/1000;
                
                }
                
            }
            
            return array('productosFinales'=>$productosFinales,'precios'=>$precios,'pesos'=>$pesos,'porcentajesIvas'=>$porcentajesIvas);
        }
        
        
        
        
        public function getNumTicketsCodigoInicio($inicio,$final){
            $sql="SELECT id_codigo_inicio,activa, b.BONU FROM pe_conversiones c "
                    . " LEFT JOIN pe_boka b ON b.STYP=2 AND b.SNR1=c.id_codigo_inicio AND b.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' "
                    . " WHERE c.activa=1 ";
        }
        
        //obtener RESUMEN periodo ventas en metálico, sin cliente, sin descuentos. PRODUCTOS VENTA CON PESO
        public function getResumenTicketsPeso($inicio, $final){
            $sql="
        SELECT DISTINCT
	( s.RASA) , 
	pr.nombre, 
	(s.peso) as peso, 
	s.precio, 
	(s.importe) as importe, 
	(s.iva) as iva, 
	
	(Round(s.peso * s.precio / 1000)) as importe_calculado,

	
	(Round(round(s.peso * s.precio / 1000) * iv.valor_iva / (10000+iv.valor_iva))) as iva_calculado,
	
	(round(s.peso * 0.90)) as peso_nuevo,
	
	(Round(round(s.peso * 0.90) * s.precio / 1000)) as importe_nuevo,
	
	(Round(round(s.peso * 0.90 * s.precio / 1000) * iv.valor_iva / (10000+iv.valor_iva))) as iva_nuevo,
	
	(s.iva) - (Round(round(s.peso * 0.90 * s.precio / 1000) * iv.valor_iva / (10000+iv.valor_iva)))  as dif_iva
	
	
	
FROM 
	(
		SELECT 
			a.RASA, 
			c.STYP, 
			c.SNR1 as producto, 
			c.POS1 as unidades, 
			c.gew1 as peso, 
			c.bt10 as precio, 
			c.bt20 as importe, 
			c.bt40 as iva, 
			c.BT30 as descuento, 
			a.SNR1, 
			b.PAR1 
		FROM 
			pe_boka a 
			INNER JOIN pe_boka b ON a.BONU = b.BONU 
			AND (
				b.STYP = 8 
				and b.PAR1 = 1
			) 
			INNER JOIN pe_boka c ON a.BONU = c.BONU 
			AND c.STYP = 2 
			and b.STYP = 8 
			and c.GEW1 > 0 
		WHERE 
			a.STYP = 1 
			AND MOD(a.SNR1, 10)< 7 
			AND a.ZEIS >= '2015-10-01' 
			AND a.ZEIS <= '2015-10-01 23:59:59'
	) as s, 
	pe_productos as pr, 
	pe_grupos as gr, 
	pe_ivas as iv 
WHERE 
	s.producto = pr.id_producto 
	and pr.id_grupo = gr.id_grupo 
	and gr.id_iva = iv.id_iva 
ORDER BY 
	s.RASA";
        $query=$this->db->query($sql); 
        $numProductos=$query->num_rows();
        $importe=0; $iva=0;
        foreach($query->result() as $k => $row){
            $importe+=$row->importe;
            $iva+=$row->iva;
        }
        return array('numProductos'=>$numProductos, 'importe'=>formato2decimales($importe/100), 'iva'=>formato2decimales($iva/100));
        }
}