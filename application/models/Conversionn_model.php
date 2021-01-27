<?php
class Conversionn_model extends CI_Model 
{

       var $db2="";
       
        public function __construct()
        {
                $this->load->database();
                $this->db2=$this->load->database('pernil181bcn',TRUE);
                
        }
        
        
        public function getDatosConversion($inicio, $final){
            $started = microtime(true);
            $ticketsPeriodo=$this->getTicketsPeriodoN($inicio, $final);
            $ticketsPeriodoModificables=$this->getTicketsPeriodoModificablesN($inicio, $final);
            
            
            $end = microtime(true);
            $difference = $end - $started;
            $queryTime = number_format($difference, 10);
            return array('ticketsPeriodo'=>$ticketsPeriodo,'ticketsPeriodoModificables'=>$ticketsPeriodoModificables,'tiempo'=>$queryTime);
        }
        
        public function getTicketsPeriodoN($inicio, $final){
            $sql="SELECT a.id as id,
                        a.bonu as referencia, 
                        b.RASA as ticket,
                        a.zeis as fecha,
                        a.BT20 as pvp,
                        a.bt40 as iva,
                        a.SNR1,c.PAR1,a.POS1 as partidas,a.SNR1 as productoInicial
                        FROM pe_boka a
                        LEFT JOIN pe_boka b ON a.bonu=b.bonu AND a.zeis=b.zeis   
                        LEFT JOIN pe_boka c ON a.bonu=c.bonu AND a.zeis=c.zeis   
                        WHERE a.STYP=2 AND a.zeis>='$inicio' AND a.zeis <='$final 23:59:59'
                            AND a.BT20>0
                            AND (MOD(b.SNR1,10)<7)
                            AND b.STYP=1 AND b.BT20>0 
                            AND c.STYP=8 AND (c.PAR1=1)
                        GROUP BY b.RASA";
            
            $sql="SELECT count(b.RASA) as num,sum(b.BT20) as importe
                    FROM pe_boka b
                    WHERE b.STYP=1 AND b.zeis>='$inicio' AND b.zeis <='$final 23:59:59'";
            log_message('INFO',$sql);
            $row=$this->db->query($sql)->row();
            
            $sql="SELECT count(a.SNR1) as numSNR1 
                    FROM pe_boka a
                    WHERE a.STYP=2 AND a.zeis>='$inicio' AND a.zeis <='$final 23:59:59'";
            log_message('INFO',$sql);
            $rowSNR1=$this->db->query($sql)->row();
            
            return array('num'=>$row->num,'importe'=>$row->importe, 'numSNR1'=>$rowSNR1->numSNR1);
        }
        
        public function getTicketsPeriodoModificablesN($inicio, $final){
            $sql="SELECT a.id as id,
                        a.bonu as referencia, 
                        b.RASA as ticket,
                        a.zeis as fecha,
                        a.BT20 as pvp,
                        a.bt40 as iva,
                        a.SNR1,c.PAR1,a.POS1 as partidas,a.SNR1 as productoInicial
                        FROM pe_boka a
                        LEFT JOIN pe_boka b ON a.bonu=b.bonu AND a.zeis=b.zeis   
                        LEFT JOIN pe_boka c ON a.bonu=c.bonu AND a.zeis=c.zeis   
                        WHERE a.STYP=2 AND a.zeis>='$inicio' AND a.zeis <='$final 23:59:59'
                            AND a.BT20>0
                            AND (MOD(b.SNR1,10)<7)
                            AND b.STYP=1 AND b.BT20>0 
                            AND c.STYP=8 AND (c.PAR1=1)
                        GROUP BY b.RASA";
            
            $sql="SELECT 
                        (b.RASA) as num,
                        (b.BT20) as importe 
                        
                        FROM pe_boka a
                        LEFT JOIN pe_boka b ON a.bonu=b.bonu AND a.zeis=b.zeis   
                        LEFT JOIN pe_boka c ON a.bonu=c.bonu AND a.zeis=c.zeis   
                        WHERE a.STYP=2 AND a.zeis>='$inicio' AND a.zeis <='$final 23:59:59'
                            AND a.BT20>0
                            AND (MOD(b.SNR1,10)<7)
                            AND b.STYP=1 AND b.BT20>0 
                            AND c.STYP=8 AND (c.PAR1=1)
                        GROUP BY b.RASA";
            log_message('INFO',$sql);
            $result=$this->db->query($sql)->result();
            $num=0;
            $importe=0;
            foreach($result as $k=>$v){
                $num++;
                $importe+=$v->importe;
            }
            $sql="SELECT 
                        
                        count(a.SNR1) as numSNR1
                        FROM pe_boka a
                        LEFT JOIN pe_boka b ON a.bonu=b.bonu AND a.zeis=b.zeis   
                        LEFT JOIN pe_boka c ON a.bonu=c.bonu AND a.zeis=c.zeis   
                        WHERE a.STYP=2 AND a.zeis>='$inicio' AND a.zeis <='$final 23:59:59'
                            AND a.BT20>0
                            AND (MOD(b.SNR1,10)<7)
                            AND b.STYP=1 AND b.BT20>0 
                            AND c.STYP=8 AND (c.PAR1=1)";
                        
            log_message('INFO',$sql);
             $rowSNR1=$this->db->query($sql)->row();
            
            return array('num'=>$num,'importe'=>$importe,'numSNR1'=>$rowSNR1->numSNR1);
        }
        
        //numero de tickets periodo ventas en metálico, sin cliente, sin descuentos
        public function getNumTicketsModificablesN($inicio, $final){
            $started = microtime(true);
            $sql="SELECT a.id as id,
                        a.bonu as referencia, 
                        b.RASA as ticket,
                        a.zeis as fecha,
                        a.BT20 as pvp,
                        a.bt40 as iva,
                        a.SNR1,c.PAR1,a.POS1 as partidas,a.SNR1 as productoInicial
                        FROM pe_boka a
                        LEFT JOIN pe_boka b ON a.bonu=b.bonu AND a.zeis=b.zeis   
                        LEFT JOIN pe_boka c ON a.bonu=c.bonu AND a.zeis=c.zeis   
                        WHERE a.STYP=2 AND a.zeis>='$inicio' AND a.zeis <='$final 23:59:59'
                            AND a.BT20>0
                            AND (MOD(b.SNR1,10)<7)
                            AND b.STYP=1 AND b.BT20>0 
                            AND c.STYP=8 AND (c.PAR1=1)
                        GROUP BY b.RASA";
            log_message('INFO',$sql);
            $num=$this->db->query($sql)->num_rows();
            $end = microtime(true);
            $difference = $end - $started;
            $queryTime = number_format($difference, 10);
            return $queryTime;
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
            
            $sql="SELECT c.id, c.id_codigo_inicio as id_codigo_inicio, c.id_codigo_final as id_codigo_final, 
pr1.nombre_web as nombre_inicio, pr2.nombre_web as nombre_final , 
c.activa as activa, pr1.tarifa_venta_unidad, 
pr1.tarifa_venta_peso, pr2.tarifa_venta_unidad, pr2.tarifa_venta_peso,c.peso as peso 
FROM pe_conversiones c 
LEFT  JOIN pe_productos pr1 ON pr1.id_producto=c.id_codigo_inicio 
AND ((pr1.tarifa_venta_unidad IS NOT NULL AND pr1.tarifa_venta_unidad>0)  
OR (pr1.tarifa_venta_peso IS NOT NULL AND pr1.tarifa_venta_peso>0))  
LEFT JOIN  pe_productos pr2 ON pr2.id_producto=c.id_codigo_final 
AND ((pr2.tarifa_venta_unidad IS NOT NULL AND pr2.tarifa_venta_unidad>0) 
OR (pr2.tarifa_venta_peso IS NOT NULL AND pr2.tarifa_venta_peso>0))  
WHERE c.activa=1  GROUP BY c.id_codigo_inicio";
            
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
            return array('pesos'=>$peso,
                         'codigosIniciales'=>$codigoInicio, 
                         'codigosFinales'=>$codigoFinal,
                         'conversiones'=>$conversiones,
                         'nombreInicio'=>$nombreInicio,
                         'nombreFinal'=>$nombreFinal,
                         'activa'=>$activa,
                         'id'=>$id,
                         'sql'=>$sql,
                         );
        }
        
        public function getConversionesTodas(){
            $sql="SET SQL_BIG_SELECTS=1";
            $this->db->query($sql);
            
           
            
            $sql="SELECT c.id, c.id_codigo_inicio as id_codigo_inicio, c.id_codigo_final as id_codigo_final, 
pr1.nombre_web as nombre_inicio, pr2.nombre_web as nombre_final , 
c.activa as activa, pr1.tarifa_venta_unidad, 
pr1.tarifa_venta_peso, pr2.tarifa_venta_unidad, pr2.tarifa_venta_peso,c.peso as peso 
FROM pe_conversiones c 
LEFT  JOIN pe_productos pr1 ON pr1.id_producto=c.id_codigo_inicio 
AND ((pr1.tarifa_venta_unidad IS NOT NULL AND pr1.tarifa_venta_unidad>0)  
OR (pr1.tarifa_venta_peso IS NOT NULL AND pr1.tarifa_venta_peso>0))  
LEFT JOIN  pe_productos pr2 ON pr2.id_producto=c.id_codigo_final 
AND ((pr2.tarifa_venta_unidad IS NOT NULL AND pr2.tarifa_venta_unidad>0) 
OR (pr2.tarifa_venta_peso IS NOT NULL AND pr2.tarifa_venta_peso>0))  
WHERE 1 GROUP BY c.id_codigo_inicio";
            
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
            return array('pesos'=>$peso,
                         'codigosIniciales'=>$codigoInicio, 
                         'codigosFinales'=>$codigoFinal,
                         'conversiones'=>$conversiones,
                         'nombreInicio'=>$nombreInicio,
                         'nombreFinal'=>$nombreFinal,
                         'activa'=>$activa,
                         'id'=>$id,
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
                
                $sql="SELECT count(DISTINCT a.bonu) as tickets, count(a.bonu) as lineas, sum(a.bt20) as pvp, sum(a.bt40) as iva
                    FROM pe_boka as a,
                    (SELECT c.bonu FROM pe_boka as c  WHERE  c.STYP=1 AND MOD(c.SNR1,10)<7 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY c.bonu) as b,
                    (SELECT e.bonu FROM pe_boka as e  WHERE  e.STYP=8 AND e.PAR1=1 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY e.bonu) as d
                    WHERE  
                    NOT EXISTS (SELECT g.bonu FROM pe_boka g WHERE g.STYP=2 AND g.SNR1=999998 AND a.bonu=g.bonu
                    AND g.zeis>='$inicio' AND g.zeis <='$final 23:59:59' GROUP BY g.bonu) AND
                    NOT EXISTS (SELECT h.bonu FROM pe_boka h WHERE h.STYP=2 AND h.BT30<0 AND a.bonu=h.bonu
                    AND h.zeis>='$inicio' AND h.zeis <='$final 23:59:59' GROUP BY h.bonu)     
                    AND (b.bonu=a.bonu AND d.bonu=a.bonu ) AND a.STYP=2 AND a.SNR1='$v' 
                    AND zeis>='$inicio' AND zeis <='$final 23:59:59'";
                
                $query=$this->db->query($sql);
                $numTickets[$k]=$query->row()->lineas;//$query->num_rows();
                $pvp[$k]=$query->row()->pvp;
                $iva[$k]=$query->row()->iva;
            }
            return array('lineas'=>$numTickets,'pvp'=>$pvp,'iva'=>$iva,'sql'=>$sql,);
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
                $tarifa_ventaFinal[]=$v->tarifa_venta/10;
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
                $sql="SELECT a.id, a.zeis as fecha, a.bonu as referencia, b.RASA as ticket,a.POS1 as partidas, a.SNR1 as productoInicial, a.bt20 as pvp, a.bt40 as iva, 
                    $codigoFinal[$k] as codigoFinal, $pesoFinal[$k] as pesoFinal, $valorIvaFinal[$k] as valorIvaFinal,$pvpFinal[$k] as pvpFinal ,$ivaFinal[$k] as ivaFinal, $tarifa_ventaFinal[$k] as tarifa_ventaFinal
                    FROM pe_boka as a,
                    (SELECT c.bonu, c.RASA FROM pe_boka as c  WHERE  c.STYP=1 AND MOD(c.SNR1,10)<7 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY c.bonu) as b,
                    (SELECT e.bonu FROM pe_boka as e  WHERE  e.STYP=8 AND e.PAR1=1 AND zeis>='$inicio' AND zeis <='$final 23:59:59' GROUP BY e.bonu) as d
                    WHERE  
                    NOT EXISTS (SELECT g.bonu FROM pe_boka g WHERE g.STYP=2 AND g.SNR1=999998 AND a.bonu=g.bonu
                    AND g.zeis>='$inicio' AND g.zeis <='$final 23:59:59' GROUP BY g.bonu)
                    AND
                    NOT EXISTS (SELECT h.bonu FROM pe_boka h WHERE h.STYP=2 AND h.BT30<0 AND a.bonu=h.bonu
                    AND h.zeis>='$inicio' AND h.zeis <='$final 23:59:59' GROUP BY h.bonu)
                    AND 
                    NOT EXISTS (SELECT h.bonu FROM pe_boka h WHERE h.STYP=2 AND h.SNR1='$v' AND a.bonu=h.bonu AND h.bt20=-a.bt20
                    AND h.zeis>='$inicio' AND h.zeis <='$final 23:59:59' GROUP BY h.bonu)
                    AND b.bonu=a.bonu AND d.bonu=a.bonu  AND a.STYP=2 AND a.SNR1='$v' AND a.bt20>0
                    AND zeis>='$inicio' AND zeis <='$final 23:59:59'";
   
                //if($k==3) {echo $sql; exit();};
                  
                $query=$this->db->query($sql);
                
                
                
                foreach($query->result() as $k=>$v){
                    $partidas=$v->partidas;
                    //echo $partidas;
                    $p=array_search($v->codigoFinal,$ticketsFinales['productosFinales']);
                    $productoFinal_db=$ticketsFinales['productosFinales'][$p];
                    $pesoFinal_db=$ticketsFinales['pesos'][$p];
                    
                    $pvpFinal_db=$partidas*$ticketsFinales['precios'][$p];
                    $ivaFinal_db=$partidas*$ticketsFinales['precios'][$p]*1000/(10000+$ticketsFinales['porcentajesIvas'][$p]);
                   
                    $lineas[]=array('id'=>$v->id,
                                    'referencia' =>$v->referencia,
                                    'ticket' =>$v->ticket,
                                    'fecha'=>$v->fecha,
                                    'productoInicial' => $v->productoInicial,
                                    'pvp' => $v->pvp,
                                    'iva' => $v->iva,
                                    'productoFinal'=> $productoFinal_db,
                                    'tarifa_ventaFinal'=>$v->tarifa_ventaFinal,
                                    'pesoFinal'=> $pesoFinal_db,
                                    'pvpFinal'=>$pvpFinal_db,
                                    'ivaFinal'=>$ivaFinal_db, 
                                    'valorIvaFinal'=>$v->valorIvaFinal,
                        );
                    
                    
                    $fecha=$v->fecha;
                    $sql="INSERT INTO pe_lineas_convertir (id,referencia, ticket, fecha, productoInicial, pvp, iva, productoFinal, tarifa_venta,pesoFinal,pvpFinal, ivaFinal,valorIvaFinal)"
                            . " VALUES ("
                            . " $v->id, "
                            . " $v->referencia, "
                            . " $v->ticket, "
                            . " '$fecha', "
                            . " $v->productoInicial, "
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
                    $sql="INSERT INTO pe_lineas_convertir_base (id,referencia, ticket, fecha, productoInicial, pvp, iva, productoFinal, tarifa_venta,pesoFinal, pvpFinal, ivaFinal,valorIvaFinal)"
                            . " VALUES ("
                            . " $v->id, "
                            . " $v->referencia, "
                            . " $v->ticket, "
                            . " '$fecha', "
                            . " $v->productoInicial, "
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
        
        public function guardarConversiones(){
            $id=$_POST['id'];
            $activa=$_POST['activa'];
            $sql="UPDATE pe_conversiones SET activa='$activa' WHERE id='$id'";
            return $this->db->query($sql);
        }
        
        public function restaurarConversiones($inicio,$final){
            $sql="SELECT * FROM pe_boka WHERE left(zeis,10)>='$inicio' AND left(zeis,10)<='$final'";
            //log_message('INFO', 'restaurarConversiones ---- '.$sql);
            $result=$this->db->query($sql);
            foreach($result->result() as $k=>$v){
                $id=$v->id;
                $set="";
                foreach($v as $k1=>$v1){
                    $set.=" $k1='$v1', ";
                }
                $set= substr($set, 0, strlen($set)-2);
                $sql="UPDATE pe_boka SET $set WHERE id='$id'";
                //log_message('INFO', $sql);
                $this->db2->query($sql);
            }
            return true;
        }
        
        
        public function makeConversiones(){
            $objetivo=$_POST['objetivo']*100;
            $resultado='';
            $difPVP=0;
            $difIVA=0;
            
            $sql="DELETE FROM pe_lineas_convertir";
            $this->db->query($sql);
            //se eleminan las lineas con diferencia pvp negativo
            $sql="DELETE FROM pe_lineas_convertir_base WHERE pvpFinal>pvp";
            $this->db->query($sql);
            
            $sql="INSERT INTO pe_lineas_convertir SELECT * FROM pe_lineas_convertir_base";
            $this->db->query($sql);
            
            
            
            while ($difPVP<($objetivo-1)){
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
            $sql="SELECT id, ticket,fecha,productoInicial,pvp,iva,productoFinal,pvpFinal,ivaFinal FROM pe_lineas_convertir_base";
            $query=$this->db->query($sql);
            $lineas=array();
            foreach($query->result() as $k=>$v){
                $lineas[]=array('id'=>$v->id,
                                'ticket'=>$v->ticket,
                                'fecha'=>$v->fecha,
                                'productoInicial'=>$v->productoInicial,
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
                //echo $id.'<br>';
                if ($existe) $lineas[$k]['convertido']=false; else $lineas[$k]['convertido']=true;
            }
            $tablaLineas=$this->tablaLineas($lineas);
            
            $productoFinal=$this->registrarConversiones();
            
            return array('productoFinal' =>$productoFinal,'difPVP'=>$difPVP,'difIVA'=>$difIVA,'tablaLineas'=>$tablaLineas);
        }
        
        public function registrarConversiones(){
            
            //seleccion lineas cambiadas
            $sql="SELECT b.referencia as bonu,b.fecha as fecha FROM pe_lineas_convertir_base  b 
                WHERE NOT EXISTS (SELECT referencia FROM  pe_lineas_convertir p WHERE p.id=b.id GROUP BY referencia) 
                GROUP BY b.referencia";
            
            //log_message('INFO', 'registrarConversiones ----- '.$sql);
            
            $query=$this->db->query($sql);
            $productoFinal=array();
            foreach($query->result() as $k=>$v){
                $fecha=$v->fecha;
                $bonu=$v->bonu;
                $query2 = $this->db->query("DELETE FROM pe_boka2 WHERE BONU='$bonu' AND ZEIS='$fecha'");
                //copiamos información de pe_boka a pe_boka2 con BONU=$bonu
                $query3 = $this->db->query("INSERT INTO pe_boka2 SELECT * FROM pe_boka WHERE BONU='$bonu' AND ZEIS='$fecha'");
              
                $sql="SELECT b.id as id, b.fecha as fecha,
                                b.referencia as bonu, 
                                b.productoFinal as productoFinal,
                                b.tarifa_venta as tarifa_venta,
                                b.pvpFinal as pvpFinal,
                                b.ivaFinal as ivaFinal,
                                b.pesoFinal as pesoFinal,
                                b.valorIvaFinal as valorIvaFinal
                    FROM pe_lineas_convertir_base  b 
                    WHERE NOT EXISTS (SELECT referencia FROM  pe_lineas_convertir p WHERE p.id=b.id ) AND b.referencia='$bonu' AND b.fecha='$fecha'
                        ";
                $query4=$this->db->query($sql);
                foreach($query4->result() as $k1=>$v1){
                    $fecha=$v1->fecha;
                    $sql="UPDATE pe_boka2 
                                SET SNR1=$v1->productoFinal, 
                                bt10=$v1->tarifa_venta,
                                bt12=$v1->tarifa_venta,
                                bt20=$v1->pvpFinal,
                                bt40=$v1->ivaFinal,
                                gew1=$v1->pesoFinal,
                                mwsa=$v1->valorIvaFinal,
                                zeis='$fecha'    
                          WHERE pe_boka2.id=$v1->id AND pe_boka2.zeis='$fecha'
                        ";
                    //los productos finales siempre son con tarifa por unidad, luego peso = 0;
                    $sql="UPDATE pe_boka2 
                                SET SNR1=$v1->productoFinal, 
                                bt10=$v1->tarifa_venta,
                                bt12=$v1->tarifa_venta,
                                bt20=$v1->pvpFinal,
                                bt40=$v1->ivaFinal,
                                gew1=0,
                                par1=1,
                                mwsa=$v1->valorIvaFinal,
                                zeis='$fecha'     
                          WHERE pe_boka2.id=$v1->id AND pe_boka2.zeis='$fecha'
                        ";
                    $this->db->query($sql);
                }
                $sql=$this->recomponerTicket($bonu,$fecha);
                
            }
            return $sql;
            
        }
        
        
        public function crearConversiones($inicio,$final){
            $inicios=array(0, 7, 9, 14, 19, 21, 26, 31, 40, 45, 50, 55, 65, 75, 85, 90, 95, 105, 115, 120, 125, 130, 135, 140, 145, 150, 155, 160, 165, 170, 175, 185, 190, 195, 205, 215, 220, 230, 239, 248, 253, 258, 263, 272, 277, 282, 287, 307, 327, 347);
            $finales=array(7, 2, 5,  5,  2,  5,  5,  9,  5,  5,  5, 10, 10, 10,  5,  5, 10,  10,   5,   5,   5,   5,   5,   5, 	 5,   5,   5, 	5,   5,   5,  10,   5, 	 5,  10,  10,   5,  10,   9,   9,   5,   5,   5,   9,   5,   5,   5,  20,  20,  20,  20);

            
            $largo=array(7,    2,    5,     5,   2,    5,    5,    9,    5,    5,    5,   10,    10,   10,    5,    5,   10,   10,   5,    5,     5,   5,     5,   5,    5,    5,    5,    5,    5,    5,    10,   5,    5,    10,  10,    5,    10,   9,    9,    5,    5,    5,    9,    5,    5,    5,    19,   19,   0,   0);
            $this->load->helper('file');
            $this->load->helper('download');
            
            $path='uploads'.DIRECTORY_SEPARATOR;
            $archivo="";
           
            
            
            $sql="SELECT ID, BONU, BONU2, STYP, ABNU, WANU, BEN1, BEN2, SNR1, GPTY, PNAB, WGNU, BT10, BT12, BT20, POS1, POS4, GEW1, BT40, MWNU, MWTY, PRUD, PAR1, PAR2, PAR3, PAR4, PAR5, STST, PAKT, POS2, MWUD, BT13, RANU, RATY, BT30, BT11, POS3, GEW2, SNR2, SNR3, VART, BART, KONU, RASA, ZAPR, ZAWI, MWSA, ZEIS, ZEIE, ZEIB, TEXT FROM pe_boka2 WHERE LEFT(zeis,10)>='$inicio' AND LEFT(zeis,10)<='$final'";
            $result=$this->db->query($sql);
            
            
            $lineasBoka=array();
            $lineas=array();
            $archivoAnterior="";
            $archivosSubidos="";
            $hola="";
            foreach ($result->result() as $k=>$v){
                $lineas=array(
                    $v->BONU, 
                    $v->BONU2, 
                    $v->STYP, 
                    $v->ABNU, 
                    $v->WANU, 
                    $v->BEN1, 
                    $v->BEN2, 
                    $v->SNR1, 
                    $v->GPTY, 
                    $v->PNAB, 
                    $v->WGNU, 
                    $v->BT10, 
                    $v->BT12, 
                    $v->BT20, 
                    $v->POS1, 
                    $v->POS4, 
                    $v->GEW1, 
                    $v->BT40, 
                    $v->MWNU, 
                    $v->MWTY, 
                    $v->PRUD, 
                    $v->PAR1, 
                    $v->PAR2, 
                    $v->PAR3, 
                    $v->PAR4, 
                    $v->PAR5, 
                    $v->STST, 
                    $v->PAKT, 
                    $v->POS2, 
                    $v->MWUD, 
                    $v->BT13, 
                    $v->RANU, 
                    $v->RATY, 
                    $v->BT30, 
                    $v->BT11, 
                    $v->POS3, 
                    $v->GEW2, 
                    $v->SNR2, 
                    $v->SNR3, 
                    $v->VART, 
                    $v->BART, 
                    $v->KONU, 
                    $v->RASA, 
                    $v->ZAPR, 
                    $v->ZAWI, 
                    $v->MWSA, 
                    $v->ZEIS, 
                    $v->ZEIE, 
                    $v->ZEIB, 
                    $v->TEXT
                    );
                $linea="";
                
                foreach($lineas as $k1=>$v1){
                    
                    if (substr($v1, 0, 1) == '-') {
                        $v1 = substr($v1, 1);
                        while (strlen($v1) < $largo[$k1] - 1) {
                            $v1 = "0" . $v1;
                        }
                        $v1 = '-' . $v1;
                    } else {
                        while (strlen($v1) < $largo[$k1]) {
                        $v1 = "0" . $v1;
                    }
                }

                if($k1==46  ) {
                        $time=  strtotime($v1);
                        $v1= date('d.m.Y H:i:s',$time);
                        $v1.=" ";
                        $archivo="BOKA_".date('d_m_y',$time).".TXT";
                        if ($archivo!=$archivoAnterior){
                            if (file_exists($path.$archivoAnterior) && $archivoAnterior!="") {
                                $archivosSubidos[]=$archivoAnterior;
                            }
                            $archivoAnterior=$archivo;
                            if (file_exists($path.$archivo)) unlink($path.$archivo);
                        }
                    }
                    if($k1==47  ) {
                        $time=  strtotime($v1);
                        $v1= date('d.m.Y H:i:s',$time);
                    }
                    
                    if($k1==48) $v1="                     ";
                    
                    $linea.=$v1;
                }
                
                $linea=$linea."\n";
                if(!write_file($path.$archivo,$linea,'at')) return $linea;
                
                
                $lineasBoka[$v->ID]=$linea;
                $this->subirLineasBoka($lineasBoka);
                
                
            }
            
           // $this->subirLineasBoka($lineasBoka);
            
            if (file_exists($path.$archivoAnterior) && $archivoAnterior!="") {
                                $archivosSubidos[]=$archivoAnterior;
                            }
            foreach($archivosSubidos as $k=>$v){
                $name = $path.$v;
                if (file_exists($name)){
                    $datos=file_get_contents($name);
                    $this->zip->add_data($name, $datos);
                    unlink($name);
                }
            }
            
            $file=$path.$inicio."_".$final.".zip";
            
            
            $this->zip->archive($file);
            
                    
            return $file;
            
        }
         function subirLineaBoka($lineas){
            $inicios=array(0, 7, 9, 14, 19, 21, 26, 31, 40, 45, 50, 55, 65, 75, 85, 90, 95, 105, 115, 120, 125, 130, 135, 140, 145, 150, 155, 160, 165, 170, 175, 185, 190, 195, 205, 215, 220, 230, 239, 248, 253, 258, 263, 272, 277, 282, 287, 307, 327, 347);
            $finales=array(7, 2, 5,  5,  2,  5,  5,  9,  5,  5,  5, 10, 10, 10,  5,  5, 10,  10,   5,   5,   5,   5,   5,   5, 	 5,   5,   5, 	5,   5,   5,  10,   5, 	 5,  10,  10,   5,  10,   9,   9,   5,   5,   5,   9,   5,   5,   5,  20,  20,  20,  20);
            
            $valores=array();
            foreach ($lineas as $k => $v) {
                        foreach($inicios as $k2=>$v2){
                        $valores[$k][]=substr($v,$v2,$finales[$k2]);
                        }
                        
                        $valores[$k][46]= date_format(new DateTime($valores[$k][46]), 'Y-m-d H:i:s'); 
                        $valores[$k][47]= date_format(new DateTime($valores[$k][47]), 'Y-m-d H:i:s'); 
                        
                       $this->setBoka($valores[$k],$k);
                    }
        }
        
        function subirLineasBoka($lineas){
            $inicios=array(0, 7, 9, 14, 19, 21, 26, 31, 40, 45, 50, 55, 65, 75, 85, 90, 95, 105, 115, 120, 125, 130, 135, 140, 145, 150, 155, 160, 165, 170, 175, 185, 190, 195, 205, 215, 220, 230, 239, 248, 253, 258, 263, 272, 277, 282, 287, 307, 327, 347);
            $finales=array(7, 2, 5,  5,  2,  5,  5,  9,  5,  5,  5, 10, 10, 10,  5,  5, 10,  10,   5,   5,   5,   5,   5,   5, 	 5,   5,   5, 	5,   5,   5,  10,   5, 	 5,  10,  10,   5,  10,   9,   9,   5,   5,   5,   9,   5,   5,   5,  20,  20,  20,  20);
            
            $valores=array();
                    foreach ($lineas as $k => $v) {
                        foreach($inicios as $k2=>$v2){
                        $valores[$k][]=substr($v,$v2,$finales[$k2]);
                        }
                        
                        $valores[$k][46]= date_format(new DateTime($valores[$k][46]), 'Y-m-d H:i:s'); 
                        $valores[$k][47]= date_format(new DateTime($valores[$k][47]), 'Y-m-d H:i:s'); 
                        
                       $this->setBoka($valores[$k],$k);
                    }
        }
        
        public function setBoka($valores,$k){
                
                $texto=implode("', '",$valores);
                $sql="SELECT ID FROM pe_boka WHERE ID='$k'";
                
                if($this->db2->query($sql)->num_rows()>0) {
                     $sql="DELETE FROM pe_boka WHERE ID='$k'";
                     $this->db2->query($sql);
                }
                    
                $sql="INSERT INTO `pe_boka`(ID, `BONU`, `BONU2`, `STYP`, `ABNU`, `WANU`, `BEN1`, `BEN2`, `SNR1`, `GPTY`, `PNAB`, `WGNU`, `BT10`, `BT12`, `BT20`, `POS1`, `POS4`, `GEW1`, `BT40`, `MWNU`, `MWTY`, `PRUD`, `PAR1`, `PAR2`, `PAR3`, `PAR4`, `PAR5`, `STST`, `PAKT`, `POS2`, `MWUD`, `BT13`, `RANU`, `RATY`, `BT30`, `BT11`, `POS3`, `GEW2`, `SNR2`, `SNR3`, `VART`, `BART`, `KONU`, `RASA`, `ZAPR`, `ZAWI`, `MWSA`, `ZEIS`, `ZEIE`, `ZEIB`, `TEXT`) VALUES "
                        . "('".$k."','".$texto."')";
                $this->db2->query($sql);
               

                return $this->db2->affected_rows();
        }
        
        
        
        function prepararBoka2($inicio,$final){
            $sql="DELETE FROM pe_boka2 Where 1";
            $this->db->query($sql);
            
            $sql="INSERT INTO pe_boka2 SELECT * FROM pe_boka WHERE left(zeis,10)>='$inicio' AND left(zeis,10)<='$final'";
            $this->db->query($sql);
        }
        
        
        function getDatosTabla(){
            
            //conversiones
            $sql="SELECT id_codigo_inicio,id_codigo_final FROM pe_conversiones WHERE activa=1 ORDER BY id_codigo_inicio,id_codigo_final";
            $result=$this->db->query($sql);
            $conversiones=array();
            $lineas=array();
            $pvps=array();
            $ivas=array();
            $pvpFinales=array();
            $ivaFinales=array();
            if ($result){
                foreach($result->result() as $k=>$v){
                    $conversiones[]=$v->id_codigo_inicio.' > '.$v->id_codigo_final;
                    $key=$v->id_codigo_inicio.' > '.$v->id_codigo_final;
                    $lineas[$key]=0;
                    $pvps[$key]=0;
                    $ivas[$key]=0;
                    $pvpFinales[$key]=0;
                    $ivaFinales[$key]=0;
                }
            }
            /*
            //lineas
            $sql="SELECT count(*) as lineas,productoInicial,productoFinal FROM pe_lineas_convertir GROUP BY  productoInicial,productoFinal ORDER BY  productoInicial,productoFinal ";
            $result=$this->db->query($sql);
            $lineas=array();
            if ($result){
                foreach($result->result() as $k=>$v){
                    $key=$v->productoInicial.' > '.$v->productoFinal;
                    $lineas[$key]=$v->lineas;
                }
            }
             * 
             */
            //pvp
            $sql="SELECT count(*) as lineas, sum(pvp) as pvp,sum(iva) as iva,sum(pvpFinal)as pvpFinal,sum(ivaFinal)as ivaFinal, productoInicial,productoFinal FROM pe_lineas_convertir GROUP BY  productoInicial,productoFinal ORDER BY  productoInicial,productoFinal ";
            $result=$this->db->query($sql);
            if ($result){
                foreach($result->result() as $k=>$v){
                    $key=$v->productoInicial.' > '.$v->productoFinal;
                    $lineas[$key]=$v->lineas;
                    $pvps[$key]=$v->pvp;
                    $ivas[$key]=$v->iva;
                    $pvpFinales[$key]=$v->pvpFinal;
                    $ivaFinales[$key]=$v->ivaFinal;
                }
            }
            
            
            
            return array('conversiones'=>$conversiones, 
                'lineas'=>$lineas,
                'pvps'=>$pvps,
                'ivas'=>$ivas,
                'pvpFinales'=>$pvpFinales,
                'ivaFinales'=>$ivaFinales,
                
                );
        }
        
        
        function recomponerTicket($bonu,$fecha){
            //STYP=2  
            $sql="SELECT sum(bt20) as importeTotal ,MWSA as tipoIva ,sum(bt40) as iva
                         FROM pe_boka2 
                         WHERE STYP=2 AND bonu='$bonu' AND zeis='$fecha' GROUP BY MWSA;
                         "; 
            $query=$this->db->query($sql);
            $importeTotalTicket=0;
            $importeTotal=array();
            $iva=array();
            foreach($query->result() as $k=>$v){
                $importeTotal[$v->tipoIva]=$v->importeTotal;
                $iva[$v->tipoIva]=$v->iva;
                $importeTotalTicket+=$v->importeTotal;
            }
            
            $sql="UPDATE pe_boka2 
                    SET bt20='$importeTotalTicket'
                  WHERE STYP=1 AND bonu='$bonu' AND ZEIS='$fecha'     
                    ";
            $query=$this->db->query($sql);
            
            //poner importe ticket como pagado metálico
            $sql="UPDATE pe_boka2 
                    SET bt10='$importeTotalTicket'
                  WHERE STYP=8 AND PAR1=1 AND bonu='$bonu'  AND ZEIS='$fecha'       
                    ";
            $query=$this->db->query($sql);
            
            //poner cambio = 0
            $sql="UPDATE pe_boka2 
                    SET bt10='0'
                  WHERE STYP=8 AND PAR1=20 AND bonu='$bonu' AND ZEIS='$fecha'        
                    ";
            $query=$this->db->query($sql);
            
            //var_dump($importeTotal); exit;
            foreach($importeTotal as $k=>$v){
                
                $sql="SELECT id FROM pe_boka2 WHERE STYP=6 AND bonu='$bonu' AND MWSA='$k' AND ZEIS='$fecha' ";
                $query=$this->db->query($sql);
                if($query->num_rows()>0){
                    $ivaTipo=$iva[$k];
                    $base=$v-$ivaTipo;
                    $id=$query->row()->id;
                    $sql="UPDATE pe_boka2 SET bt20='$v',bt12='$ivaTipo',bt10='$base' WHERE pe_boka2.id=$id";
                    $this->db->query($sql);
                }
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
                            <th class='tconversion'>Diferencia IVA</th>
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
        
        
        public function numProductosBascula($codigo){
            $sql="SELECT id_producto FROM pe_productos WHERE id_producto='$codigo'";
            return $this->db->query($sql)->num_rows();
        }
        
        public function getCodigosBascula(){
            $sql="SELECT id_producto, nombre FROM pe_productos  GROUP BY id_producto ORDER BY id_producto";
            foreach($this->db->query($sql)->result() as $k=>$v){
                $codidosBascula[$v->id_producto]=$v->id_producto.' - '.$v->nombre;
            }
            return $codidosBascula;
        }
        
        public function getUltimasTarifasVenta($codigo){
            //obtener último precio en boka
            $sql="SELECT  bt12 as precio FROM pe_boka WHERE STYP=2 AND SNR1='$codigo' ORDER BY ZEIS DESC LIMIT 1";
            $query=$this->db->query($sql);
            if($query->row()){
                    $precio=$query->row()->precio;
            }
            else{
               $precio=0; 
            }
            
            //no consideramos el último precio boka y
            //se toma el precio de pe_productos
            $precio=0;
            
            if(!$precio){
                //si no existe el precio en boka lo leo de pe_productos
                $sql="SELECT tarifa_venta_unidad,tarifa_venta_peso
                       FROM pe_productos pr 
                       WHERE id_producto='$codigo' 
                    ";
                $query=$this->db->query($sql);
                if($query->row()){
                    if($query->row()->tarifa_venta_unidad) $precio=$query->row()->tarifa_venta_unidad/10;
                    if($query->row()->tarifa_venta_peso) $precio=$query->row()->tarifa_venta_peso/10;
                }
            else{
               $precio=0; 
            }
            }
            return $precio;
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
                
                //forzamos a leer el precio de pe_productos
                $precios[$k]=0;
                
                if($precios[$k]==0){
                //si no existe el precio en boka lo leo de pe_productos
                $sql="SELECT tarifa_venta_unidad, tarifa_venta_peso , iv.valor_iva  as porcentajeIva,peso_real
                       FROM pe_productos pr 
                       INNER JOIN pe_grupos g ON pr.id_grupo=g.id_grupo
                       INNER JOIN pe_ivas iv ON iv.id_iva=g.id_iva
                       WHERE id_producto='$v' GROUP BY id_producto
                    ";
                $query=$this->db->query($sql);
                
               
                if($query->num_rows()>0){
                    if($query->row()->tarifa_venta_unidad) $precios[$k]=$query->row()->tarifa_venta_unidad;
                    else $precios[$k]=$query->row()->tarifa_venta_peso;
                    //$precios[$k]=$precios[$k]/10;
                    $precios[$k]=$precios[$k]/10;
                    $porcentajesIvas[$k]=$query->row()->porcentajeIva;
                    $porcentajesIvas[$k]=$porcentajesIvas[$k]*100;
                    $precios[$k]= $query->row()->tarifa_venta_unidad==0?$precios[$k]*$query->row()->peso_real/1000:$precios[$k];
                
                    //$porcentajesIvas[$k]=$porcentajesIvas[$k]/10;
                }
                else {
                    $precios[$k]=0;
                    $porcentajesIvas[$k]=0;
                }
                
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