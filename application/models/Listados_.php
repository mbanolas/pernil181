<?php
class Listados_ extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->db2=$this->load->database('pernil181bcn',TRUE);
                
                ini_set('memory_limit','512M');
        }
        
        //obtener las ventas (totales) por los diferentes conceptos
        // metálico
        // tarjeta
        // a cuenta
        // transferencia
        // vale
        public function getDatosVentasBokaCaja($inicio,$final){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            //ventas total
            $sql="SELECT sum(BT20) as ventas FROM pe_boka WHERE STYP='1' AND $where";
            $query=$this->db->query($sql);
            
            $totales[0]=$query->row()->ventas;
            
            // totales METALICO
            $sqlM="SELECT sum(BT10) as totales "
                    . " FROM pe_boka as a WHERE STYP=8 AND PAR1=1 AND $where "
                    . " AND "
                    . " EXISTS(SELECT * FROM pe_boka WHERE BONU=a.BONU AND STYP='1' AND $where ) "
                    . " ";
            $query=$this->db->query($sqlM);
            $totales[1]=$query->row()->totales;
            
            
            // totales devoluciones METALICO
            $sqlDV="SELECT sum(BT10) as totales "
                    . " FROM pe_boka as a WHERE STYP=8 AND PAR1=20 AND $where "
                    . " AND "
                    . " EXISTS(SELECT * FROM pe_boka WHERE BONU=a.BONU AND STYP='1' AND $where ) "
                    . " ";
            
            $query=$this->db->query($sqlDV);
            $totales[20]=$query->row()->totales;
            
            // totales CHEQUE
            $sqlCheque="SELECT sum(BT10) as totales "
                    . " FROM pe_boka as a WHERE STYP=8 AND PAR1=2 AND $where "
                    . " AND "
                    . " EXISTS(SELECT * FROM pe_boka WHERE BONU=a.BONU AND STYP='1' AND $where ) "
                    . " ";
            $query=$this->db->query($sqlCheque);
            $totales[2]=$query->row()->totales;
            
            // totales Vale
            $sqlVale="SELECT sum(BT10) as totales "
                    . " FROM pe_boka as a WHERE STYP=8 AND PAR1=3 AND $where "
                    . " AND "
                    . " EXISTS(SELECT * FROM pe_boka WHERE BONU=a.BONU AND STYP='1' AND $where ) "
                    . " ";
            $query=$this->db->query($sqlVale);
            $totales[3]=$query->row()->totales;
            
            // totales Tarjeta crédito
            $sqlTC="SELECT sum(BT10) as totales "
                    . " FROM pe_boka as a WHERE STYP=8 AND PAR1=4 AND $where "
                    . " AND "
                    . " EXISTS(SELECT * FROM pe_boka WHERE BONU=a.BONU AND STYP='1' AND $where ) "
                    . " ";
            $query=$this->db->query($sqlTC);
            $totales[4]=$query->row()->totales;
            
            // totales Transferencias
            $sqlTR="SELECT sum(BT10) as totales "
                    . " FROM pe_boka as a WHERE STYP=8 AND PAR1=5 AND $where "
                    . " AND "
                    . " EXISTS(SELECT * FROM pe_boka WHERE BONU=a.BONU AND STYP='1' AND $where ) "
                    . " ";
            $query=$this->db->query($sqlTR);
            $totales[5]=$query->row()->totales;
            
            
            // totales A cuenta
            $sqlAC="SELECT sum(BT10) as totales "
                    . " FROM pe_boka as a WHERE STYP=8 AND PAR1=6 AND $where "
                    . " AND "
                    . " EXISTS(SELECT * FROM pe_boka WHERE BONU=a.BONU AND STYP='1' AND $where ) "
                    . " ";
            $query=$this->db->query($sqlAC);
            $totales[6]=$query->row()->totales;
            
            
            //buscalos row pe_caja anterior al de la fecha
            $fecha=$_POST['fecha'];
            $sql="SELECT * FROM pe_caja WHERE fecha<'$fecha' ORDER BY fecha DESC LIMIT 1";
            $query=$this->db->query($sql);  
            $cajaAnterior=$query->row();
            
            return array('totales'=>  $totales,'cajaAnterior'=> $cajaAnterior,'sqlDV'=>$sqlDV, );
        }
        
       
        
        
        public function getDatosVentasBoka_1($inicio,$final){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            //ventas total
            $sql="SELECT sum(BT20) as ventas FROM pe_boka WHERE STYP='1' AND $where";
            $query=$this->db->query($sql);
            $ventas=$query->row()->ventas;
            /*
            $sql="SELECT BONU as bonu FROM pe_boka WHERE STYP='1' AND $where";
            $query=$this->db->query($sql);
            $bonus=array();
            foreach($query->result() as $k=>$v)
                $bonus[]=$v->bonu;
            
            $bonusOR=implode("' OR BONU='",$bonus);
            $bonusOR="BONU='".$bonusOR."'";
            */
            $tipos=array();
            $ivas=array();
            $bases=array();
            $totales=array();
            
            $tiposM=array();
            $ivasM=array();
            $basesM=array();
            $totalesM=array();
            
            $tiposT=array();
            $ivasT=array();
            $basesT=array();
            $totalesT=array();
            
            $tiposO=array();
            $ivasO=array();
            $basesO=array();
            $totalesO=array();
            
            
            //tipos IVA , bases, ivas, totales TODOS
           // $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 GROUP BY MWSA";
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND (STYP=8 AND PAR1!=20)) "
                    . " GROUP BY MWSA";
            
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND (STYP=8 )) "
                    . " GROUP BY MWSA";
            
            $query=$this->db->query($sql);
            foreach($query->result() as $k =>$v){
                $tipos[$v->tipos]=0;
                $tiposM[$v->tipos]=0;
                $tiposT[$v->tipos]=0;
                $tiposO[$v->tipos]=0;
                
                $ivas[$v->tipos]=0;
                $bases[$v->tipos]=0;
                $totales[$v->tipos]=0;
                
                $ivasM[$v->tipos]=0;
                $basesM[$v->tipos]=0;
                $totalesM[$v->tipos]=0;
                
                $ivasT[$v->tipos]=0;
                $basesT[$v->tipos]=0;
                $totalesT[$v->tipos]=0;
                
                $ivasO[$v->tipos]=0;
                $basesO[$v->tipos]=0;
                $totalesO[$v->tipos]=0;
                
            }   
            
            foreach($query->result() as $k =>$v){
                $tipos[$v->tipos]=$v->tipos/100;
                $ivas[$v->tipos]=$v->ivas/100;
                 $bases[$v->tipos]=$v->bases/100;
                 $totales[$v->tipos]=$v->totales/100;
            }
            
            //tipos IVA , bases, ivas, totales TARJETA CREDITO
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND (STYP=8 AND PAR1=4)) "
                    . " GROUP BY MWSA";
            $query=$this->db->query($sql);

            foreach($query->result() as $k =>$v){
                $tiposT[$v->tipos]=$v->tipos/100;
                $ivasT[$v->tipos]=$v->ivas/100;
                 $basesT[$v->tipos]=$v->bases/100;
                 $totalesT[$v->tipos]=$v->totales/100;
            }
             //tipos IVA , bases, ivas, totales METALICO
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " ("
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND STYP=8 AND PAR1=20 AND BT10!=0 ) "
                    . " OR"
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND STYP=8 AND PAR1=1 ) "
                    . " )"
                    . " GROUP BY MWSA";
            
            
            $query=$this->db->query($sql);
            
            foreach($query->result() as $k =>$v){
                $tiposM[$v->tipos]=$v->tipos/100;
                $ivasM[$v->tipos]=$v->ivas/100;
                 $basesM[$v->tipos]=$v->bases/100;
                 $totalesM[$v->tipos]=$v->totales/100;
            }
             //tipos IVA , bases, ivas, totales OTROS   //AND BT10!=0
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND (STYP=8 AND PAR1!=1  AND PAR1!=4 AND PAR1!=20 )) "
                    . " GROUP BY MWSA";
            $query=$this->db->query($sql);
            
            foreach($query->result() as $k =>$v){
                $tiposO[$v->tipos]=$v->tipos/100;
                $ivasO[$v->tipos]=$v->ivas/100;
                 $basesO[$v->tipos]=$v->bases/100;
                 $totalesO[$v->tipos]=$v->totales/100;
            }
            

            return array(
                'periodoInicio'=>$periodoInicio,
                'periodoFinal'=>$periodoFinal,
                
                'ventas'=>$ventas,
                'tipos'=>$tipos,
                'ivas'=>$ivas,
                'bases'=>$bases,
                'totales'=>$totales,
                
                'tiposT'=>$tiposT,
                'ivasT'=>$ivasT,
                'basesT'=>$basesT,
                'totalesT'=>$totalesT,
                
                'tiposM'=>$tiposM,
                'ivasM'=>$ivasM,
                'basesM'=>$basesM,
                'totalesM'=>$totalesM,
                
                'tiposO'=>$tiposO,
                'ivasO'=>$ivasO,
                'basesO'=>$basesO,
                'totalesO'=>$totalesO,
                
               // 'sql'=>$sql,
               // 'bonus'=>$bonus,
               // 'bonusOR'=>$bonusOR,
                );
        }
        
        public function getDatosVentasBoka_2($inicio,$final){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            //ventas total
            $sql="SELECT sum(BT20) as ventas FROM pe_boka WHERE STYP='1' AND $where";
            $query=$this->db->query($sql);
            $ventas=$query->row()->ventas;
            /*
            $sql="SELECT BONU as bonu FROM pe_boka WHERE STYP='1' AND $where";
            $query=$this->db->query($sql);
            $bonus=array();
            foreach($query->result() as $k=>$v)
                $bonus[]=$v->bonu;
            
            $bonusOR=implode("' OR BONU='",$bonus);
            $bonusOR="BONU='".$bonusOR."'";
            */
            $tipos=array();
            $ivas=array();
            $bases=array();
            $totales=array();
            
            $tiposM=array();
            $ivasM=array();
            $basesM=array();
            $totalesM=array();
            
            $tiposT=array();
            $ivasT=array();
            $basesT=array();
            $totalesT=array();
            
            $tiposO=array();
            $ivasO=array();
            $basesO=array();
            $totalesO=array();
            
            
            //tipos IVA , bases, ivas, totales TODOS
           // $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 GROUP BY MWSA";
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND (STYP=8 AND PAR1!=20)) "
                    . " GROUP BY MWSA";
            
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND (STYP=8 )) "
                    . " GROUP BY MWSA";
            
            $sql="SELECT b.MWSA as tipos, sum(b.BT10) as bases, sum(b.BT12) as ivas, sum(b.BT20) as totales FROM pe_boka a RIGHT JOIN pe_boka b ON a.BONU=b.BONU AND b.STYP=6 AND b.ZEIS>='2015-06-08 00:00' AND b.ZEIS<='2015-06-08 23:59' WHERE a.STYP=1 AND a.ZEIS>='2015-06-08 00:00' AND a.ZEIS<='2015-06-08 23:59' GROUP BY b.MWSA";
            
            return $sql;
            
            
            $query=$this->db->query($sql);
            foreach($query->result() as $k =>$v){
                $tipos[$v->tipos]=0;
                $tiposM[$v->tipos]=0;
                $tiposT[$v->tipos]=0;
                $tiposO[$v->tipos]=0;
                
                $ivas[$v->tipos]=0;
                $bases[$v->tipos]=0;
                $totales[$v->tipos]=0;
                
                $ivasM[$v->tipos]=0;
                $basesM[$v->tipos]=0;
                $totalesM[$v->tipos]=0;
                
                $ivasT[$v->tipos]=0;
                $basesT[$v->tipos]=0;
                $totalesT[$v->tipos]=0;
                
                $ivasO[$v->tipos]=0;
                $basesO[$v->tipos]=0;
                $totalesO[$v->tipos]=0;
                
            }   
            
            foreach($query->result() as $k =>$v){
                $tipos[$v->tipos]=$v->tipos/100;
                $ivas[$v->tipos]=$v->ivas/100;
                 $bases[$v->tipos]=$v->bases/100;
                 $totales[$v->tipos]=$v->totales/100;
            }
            
            //tipos IVA , bases, ivas, totales TARJETA CREDITO
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND (STYP=8 AND PAR1=4)) "
                    . " GROUP BY MWSA";
            $query=$this->db->query($sql);

            foreach($query->result() as $k =>$v){
                $tiposT[$v->tipos]=$v->tipos/100;
                $ivasT[$v->tipos]=$v->ivas/100;
                 $basesT[$v->tipos]=$v->bases/100;
                 $totalesT[$v->tipos]=$v->totales/100;
            }
             //tipos IVA , bases, ivas, totales METALICO
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " ("
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND STYP=8 AND PAR1=20 AND BT10!=0 ) "
                    . " OR"
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND STYP=8 AND PAR1=1 ) "
                    . " )"
                    . " GROUP BY MWSA";
            
            
            $query=$this->db->query($sql);
            
            foreach($query->result() as $k =>$v){
                $tiposM[$v->tipos]=$v->tipos/100;
                $ivasM[$v->tipos]=$v->ivas/100;
                 $basesM[$v->tipos]=$v->bases/100;
                 $totalesM[$v->tipos]=$v->totales/100;
            }
             //tipos IVA , bases, ivas, totales OTROS   //AND BT10!=0
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_boka WHERE  BONU=pe_boka2.BONU AND (STYP=8 AND PAR1!=1  AND PAR1!=4 AND PAR1!=20 )) "
                    . " GROUP BY MWSA";
            $query=$this->db->query($sql);
            
            foreach($query->result() as $k =>$v){
                $tiposO[$v->tipos]=$v->tipos/100;
                $ivasO[$v->tipos]=$v->ivas/100;
                 $basesO[$v->tipos]=$v->bases/100;
                 $totalesO[$v->tipos]=$v->totales/100;
            }
            

            return array(
                'periodoInicio'=>$periodoInicio,
                'periodoFinal'=>$periodoFinal,
                
                'ventas'=>$ventas,
                'tipos'=>$tipos,
                'ivas'=>$ivas,
                'bases'=>$bases,
                'totales'=>$totales,
                
                'tiposT'=>$tiposT,
                'ivasT'=>$ivasT,
                'basesT'=>$basesT,
                'totalesT'=>$totalesT,
                
                'tiposM'=>$tiposM,
                'ivasM'=>$ivasM,
                'basesM'=>$basesM,
                'totalesM'=>$totalesM,
                
                'tiposO'=>$tiposO,
                'ivasO'=>$ivasO,
                'basesO'=>$basesO,
                'totalesO'=>$totalesO,
                
               // 'sql'=>$sql,
               // 'bonus'=>$bonus,
               // 'bonusOR'=>$bonusOR,
                );
        }
        
        
        public function getDatosVentasBokaTipoBaseIvaTotal($inicio,$final,$boka,$db=0){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=1 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . "GROUP BY b.MWSA ORDER BY b.MWSA";
            
            //log_message('INFO', $sql);
            
            if($db==0){
                $this->db->query("SET SQL_BIG_SELECTS=1");
                $query=$this->db->query($sql);
            } else{
                $this->db2->query("SET SQL_BIG_SELECTS=1");
                $query=$this->db2->query($sql);
            }
            
            
            return $query->result();
        }
        
        public function getDatosVentasBokaTipoBaseIvaTotalSTYP6($inicio,$final,$boka,$db=0){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT MWSA as tipos, "
                    . "sum(BT10) as bases, "
                    . "sum(BT12) as ivas, "
                    . "sum(BT20) as totales "
                    . "FROM pe_bokaStyp6 a "
                    . " WHERE STYP=6 AND "
                            . "ZEIS>='$inicio' AND "
                            . "ZEIS<='$final' "
                    . "GROUP BY MWSA ORDER BY MWSA";
            if($db==0){
                $this->db->query("SET SQL_BIG_SELECTS=1");
                $query=$this->db->query($sql);
            }else{
                $this->db2->query("SET SQL_BIG_SELECTS=1");
                $query=$this->db2->query($sql);
            }
            
            return $query->result();
        }
        
        public function getDatosVentasBokaTipoBaseIvaTotalTotales($inicio,$final,$boka,$db=0){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=1 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . " "; 
            if($db==0){
            $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            return $query->row();
        }
        public function getDatosVentasBokaTipoBaseIvaTotalTotalesSTYP6($inicio,$final,$boka,$db=0){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT  "
                    . "sum(BT10) as bases, "
                    . "sum(BT12) as ivas, "
                    . "sum(BT20) as totales "
                    . "FROM pe_bokaStyp6 a "
                    . " WHERE STYP=6 AND "
                            . "ZEIS>='$inicio' AND "
                            . "ZEIS<='$final' "
                    . " ";    
            if($db==0){
            $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            return $query->row();
        }
         
        public function getDatosVentasBokaTipoBaseIvaTotalNoMetalico($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND (a.PAR1=2 OR a.PAR1=3 OR a.PAR1=4 OR a.PAR1=5 OR a.PAR1=6) AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . "GROUP BY b.MWSA";
            
            $query=$this->db->query($sql);
            return $query->result();
        }
        
         public function getDatosVentasBokaTotalesFormaPago($inicio,$final,$boka,$db=0){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT sum(a.BT10) as total FROM $boka a WHERE a.STYP=8 AND PAR1=1 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            
            if($db==0){
            $query=$this->db->query($sql);
            }else{
               $query=$this->db2->query($sql); 
            }
            
            if($query->num_rows()>0) $total=$query->row()->total; else $total=0;
            $sql="SELECT sum(a.BT10) as total FROM $boka a WHERE a.STYP=8 AND PAR1=20 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $cambio=$query->row()->total; else $cambio=0;
            $metalico=$total-$cambio;
            
            $sql="SELECT sum(a.BT10) as total FROM $boka a WHERE a.STYP=8 AND PAR1=2 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $cheque=$query->row()->total; else $cheque=0;
            
            $sql="SELECT sum(a.BT10) as total FROM $boka a WHERE a.STYP=8 AND PAR1=3 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $vale=$query->row()->total; else $vale=0;
            
            $sql="SELECT sum(a.BT10) as total FROM $boka a WHERE a.STYP=8 AND PAR1=4 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $tarjeta=$query->row()->total; else $tarjeta=0;
            
            $sql="SELECT sum(a.BT10) as total FROM $boka a WHERE a.STYP=8 AND PAR1=5 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $transferencia=$query->row()->total; else $transferencia=0;
            
            $sql="SELECT sum(a.BT10) as total FROM $boka a WHERE a.STYP=8 AND PAR1=6 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $aCuenta=$query->row()->total; else $aCuenta=0;
            
            
            /// para tickets antiguos los datos están acumulados en pe_bokaStyp6
            $sql="SELECT sum(a.BT10) as total FROM pe_bokaStyp6 a WHERE a.STYP=8 AND PAR1=1 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            $sql1=$sql;
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $total+=$query->row()->total; 
            $sql="SELECT sum(a.BT10) as total FROM pe_bokaStyp6 a WHERE a.STYP=8 AND PAR1=20 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $cambio+=$query->row()->total; 
            $metalico=$total-$cambio;
            
            $sql="SELECT sum(a.BT10) as total FROM pe_bokaStyp6 a WHERE a.STYP=8 AND PAR1=2 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $cheque+=$query->row()->total; 
            
            $sql="SELECT sum(a.BT10) as total FROM pe_bokaStyp6 a WHERE a.STYP=8 AND PAR1=3 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            };
            if($query->num_rows()>0) $vale+=$query->row()->total; 
            
            $sql="SELECT sum(a.BT10) as total FROM pe_bokaStyp6 a WHERE a.STYP=8 AND PAR1=4 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $tarjeta+=$query->row()->total;
            
            $sql="SELECT sum(a.BT10) as total FROM pe_bokaStyp6 a WHERE a.STYP=8 AND PAR1=5 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $transferencia+=$query->row()->total; 
            
            $sql="SELECT sum(a.BT10) as total FROM pe_bokaStyp6 a WHERE a.STYP=8 AND PAR1=6 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' ";
            if($db==0){
                $query=$this->db->query($sql);
            }else{
                $query=$this->db2->query($sql);
            }
            if($query->num_rows()>0) $aCuenta+=$query->row()->total;
            
            
            
            return array('metalico'=>$metalico,
                          'cheque'=>$cheque,
                          'vale'=>$vale,
                          'tarjeta'=>$tarjeta,
                           'transferencia'=>$transferencia,
                           'aCuenta'=>$aCuenta,
                            'sql'=>$sql1);
        }
      
        
        public function getDatosVentasBokaTipoBaseIvaTotalTotalesNoMetalico($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND (a.PAR1=2 OR a.PAR1=3 OR a.PAR1=4 OR a.PAR1=5 OR a.PAR1=6) AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . " ";           
            $query=$this->db->query($sql);
            return $query->row();
        }
        public function getDatosVentasBokaTipoBaseIvaTotalTransferencias($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND a.PAR1=5 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . "GROUP BY b.MWSA";
            
            $query=$this->db->query($sql);
            return $query->result();
        }
        public function getDatosVentasBokaTipoBaseIvaTotalTotalesTransferencias($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND a.PAR1=5 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . " ";            
            $query=$this->db->query($sql);
            return $query->row();
        }
       
        public function getDatosVentasBokaTipoBaseIvaTotalVales($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND a.PAR1=3 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . "GROUP BY b.MWSA";
            
            $query=$this->db->query($sql);
            return $query->result();
        }
        public function getDatosVentasBokaTipoBaseIvaTotalTotalesVales($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND a.PAR1=3 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . " ";            
            $query=$this->db->query($sql);
            return $query->row();
        }
       
        public function getDatosVentasBokaTipoBaseIvaTotalTotalesACuenta($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND a.PAR1=6 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . " ";            
            $query=$this->db->query($sql);
            return $query->row();
        }
        public function getDatosVentasBokaTipoBaseIvaTotalACuenta($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND a.PAR1=6 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . "GROUP BY b.MWSA";
            
            $query=$this->db->query($sql);
            return $query->result();
        }
       
        public function getDatosVentasBokaTipoBaseIvaTotalTarjetas($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND a.PAR1=4 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . "GROUP BY b.MWSA";
            
            $query=$this->db->query($sql);
            return $query->result();
        }
        public function getDatosVentasBokaTipoBaseIvaTotalTotalesTarjetas($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="SELECT b.MWSA as tipos, "
                    . "sum(b.BT10) as bases, "
                    . "sum(b.BT12) as ivas, "
                    . "sum(b.BT20) as totales "
                    . "FROM $boka a "
                    . "RIGHT JOIN $boka b ON a.BONU=b.BONU AND "
                                            . "b.STYP=6 AND "
                                            . "b.ZEIS>='$inicio' AND "
                                            . "b.ZEIS<='$final'"
                    . " WHERE a.STYP=8 AND a.PAR1=4 AND "
                            . "a.ZEIS>='$inicio' AND "
                            . "a.ZEIS<='$final' "
                    . " ";            
            $query=$this->db->query($sql);
            return $query->row();
        }
        
        public function getDatosTickets($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            
            $sql="
                SELECT a.RASA as ticket, a.ZEIS as fecha, '---' as formaPago, 
                        sum(b.BT10) as bases, 
                        sum(b.BT12) as ivas, 
                        a.BT20 as totales FROM $boka a 
                        LEFT JOIN $boka b ON a.BONU=b.BONU AND b.STYP=6 and a.ZEIS=b.ZEIS 
                        WHERE a.STYP=1 
                        AND a.ZEIS>='$inicio' 
                        AND a.ZEIS<='$final' "
                    . "GROUP BY a.RASA";
            
            $this->db->query("SET SQL_BIG_SELECTS=1");
            
            $query=$this->db->query($sql);
            
            $sql="
                SELECT a.RASA as ticket, a.ZEIS as fecha, min(c.PAR1) as formaPago 
                        FROM $boka a 
                        LEFT JOIN $boka c ON a.BONU=c.BONU AND c.STYP=8 AND a.ZEIS=c.ZEIS 
                        WHERE a.STYP=1 
                        AND a.ZEIS>='$inicio' 
                        AND a.ZEIS<='$final' "
                    . "GROUP BY a.RASA";
            $query2=$this->db->query($sql);
            
            return array('importes'=>$query->result(),'formaPago'=>$query2->result());
        }
 
         public function getDatosTicketsTotales($inicio,$final,$boka){
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            
            $sql="
                SELECT a.RASA as ticket, a.ZEIS as fecha, '---' as formaPago, 
                        sum(b.BT10) as bases, 
                        sum(b.BT12) as ivas, 
                        sum(b.BT20) as totales FROM $boka a 
                        LEFT JOIN $boka b ON a.BONU=b.BONU and  b.STYP=6 AND a.ZEIS=b.ZEIS  
                        WHERE a.STYP=1 
                        AND a.ZEIS>='$inicio' 
                        AND a.ZEIS<='$final' "
                    . " ";
            
            $query=$this->db->query($sql);
            
            $query=$this->db->query($sql);
            
            
            
            return $query->row();
        }
        
        
        
        public function prepararBokaMerge($inicio,$final){
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            $sql="DROP TABLE IF EXISTS pe_bokaMerged ";
            $query=$this->db->query($sql);
            $sql="CREATE TABLE IF NOT EXISTS pe_bokaMerged AS (SELECT * FROM pe_boka as a WHERE EXISTS(SELECT * FROM pe_boka where a.bonu=bonu and STYP='1' AND $where))";
            $query=$this->db->query($sql);
            $sql="ALTER TABLE pe_bokaMerged ADD PRIMARY KEY (id)";
            $query=$this->db->query($sql);
            $sql="REPLACE INTO pe_bokaMerged SELECT * FROM pe_boka2";
            $query=$this->db->query($sql);
        }
        
        public function getNombresClientes(){
                $query="SELECT  "
                        . "c.id_cliente as id_cliente, c.nombre as cliente "
                        . " FROM pe_clientes c GROUP BY c.id_cliente ORDER BY c.nombre"
                       ;
                $query=$this->db->query($query);
                $clientes=array();
                foreach($query->result() as $k=>$v){
                    $clientes[$v->id_cliente]=$v->cliente.' ('.$v->id_cliente.')';
                }
                return $clientes;
        }
        
        public function getEmpresasClientes(){
                $query="SELECT  "
                        . "c.id_cliente as id_cliente, c.nombre as cliente,  c.empresa as empresa"
                        . " FROM pe_clientes c GROUP BY c.id_cliente ORDER BY c.id_cliente"
                       ;
                $query=$this->db->query($query);
                $clientes=array();
                foreach($query->result() as $k=>$v){
                    $clientes[$v->id_cliente]=$v->empresa.' ('.$v->cliente.') ('.$v->id_cliente.')';
                }
                return $clientes;
        }
        
        public function getNumerosClientes(){
                $query="SELECT  "
                        . "c.id_cliente as id_cliente, c.nombre as cliente "
                        . " FROM pe_clientes c GROUP BY c.id_cliente"
                       ;
                $query=$this->db->query($query);
                $clientes=array();
                foreach($query->result() as $k=>$v){
                    $clientes[$v->id_cliente]=$v->id_cliente.' ('.$v->cliente.')';
                }
                return $clientes;
        }
        
        public function getDatosVentasBoka2($inicio,$final){
            $this->prepararBokaMerge($inicio,$final);
            $periodoInicio=  fechaEuropea($inicio);
            $periodoFinal=  fechaEuropea($final);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            //ventas total
            $sql="SELECT sum(BT20) as ventas FROM pe_bokaMerged WHERE STYP='1' AND $where";
            $query=$this->db->query($sql);
            $ventas=$query->row()->ventas;
            /*
            $sql="SELECT BONU as bonu FROM pe_boka WHERE STYP='1' AND $where";
            $query=$this->db->query($sql);
            $bonus=array();
            foreach($query->result() as $k=>$v)
                $bonus[]=$v->bonu;
            
            $bonusOR=implode("' OR BONU='",$bonus);
            $bonusOR="BONU='".$bonusOR."'";
            */
            $tipos=array();
            $ivas=array();
            $bases=array();
            $totales=array();
            
            $tiposM=array();
            $ivasM=array();
            $basesM=array();
            $totalesM=array();
            
            $tiposT=array();
            $ivasT=array();
            $basesT=array();
            $totalesT=array();
            
            $tiposO=array();
            $ivasO=array();
            $basesO=array();
            $totalesO=array();
            
            
            //tipos IVA , bases, ivas, totales TODOS
           // $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases FROM pe_boka as pe_boka1,(SELECT BONU  FROM pe_boka WHERE STYP='1' AND $where) as pe_boka2  WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 GROUP BY MWSA";
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_bokaMerged as pe_boka1,(SELECT BONU  FROM pe_bokaMerged WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_bokaMerged WHERE  BONU=pe_boka2.BONU AND (STYP=8 AND PAR1!=20)) "
                    . " GROUP BY MWSA";
            
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_bokaMerged as pe_boka1,(SELECT BONU  FROM pe_bokaMerged WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_bokaMerged WHERE  BONU=pe_boka2.BONU AND (STYP=8 )) "
                    . " GROUP BY MWSA";
            
            $query=$this->db->query($sql);
            foreach($query->result() as $k =>$v){
                $tipos[$v->tipos]=0;
                $tiposM[$v->tipos]=0;
                $tiposT[$v->tipos]=0;
                $tiposO[$v->tipos]=0;
                
                $ivas[$v->tipos]=0;
                $bases[$v->tipos]=0;
                $totales[$v->tipos]=0;
                
                $ivasM[$v->tipos]=0;
                $basesM[$v->tipos]=0;
                $totalesM[$v->tipos]=0;
                
                $ivasT[$v->tipos]=0;
                $basesT[$v->tipos]=0;
                $totalesT[$v->tipos]=0;
                
                $ivasO[$v->tipos]=0;
                $basesO[$v->tipos]=0;
                $totalesO[$v->tipos]=0;
                
            }   
            
            foreach($query->result() as $k =>$v){
                $tipos[$v->tipos]=$v->tipos/100;
                $ivas[$v->tipos]=$v->ivas/100;
                 $bases[$v->tipos]=$v->bases/100;
                 $totales[$v->tipos]=$v->totales/100;
            }
            
            //tipos IVA , bases, ivas, totales TARJETA CREDITO
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_bokaMerged as pe_boka1,(SELECT BONU  FROM pe_bokaMerged WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_bokaMerged WHERE  BONU=pe_boka2.BONU AND (STYP=8 AND PAR1=4)) "
                    . " GROUP BY MWSA";
            $query=$this->db->query($sql);

            foreach($query->result() as $k =>$v){
                $tiposT[$v->tipos]=$v->tipos/100;
                $ivasT[$v->tipos]=$v->ivas/100;
                 $basesT[$v->tipos]=$v->bases/100;
                 $totalesT[$v->tipos]=$v->totales/100;
            }
             //tipos IVA , bases, ivas, totales METALICO
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_bokaMerged as pe_boka1,(SELECT BONU  FROM pe_bokaMerged WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " ("
                    . " EXISTS(SELECT PAR1 FROM pe_bokaMerged WHERE  BONU=pe_boka2.BONU AND STYP=8 AND PAR1=20 AND BT10!=0 ) "
                    . " OR"
                    . " EXISTS(SELECT PAR1 FROM pe_bokaMerged WHERE  BONU=pe_boka2.BONU AND STYP=8 AND PAR1=1 ) "
                    . " )"
                    . " GROUP BY MWSA";
            
            
            $query=$this->db->query($sql);
            
            foreach($query->result() as $k =>$v){
                $tiposM[$v->tipos]=$v->tipos/100;
                $ivasM[$v->tipos]=$v->ivas/100;
                 $basesM[$v->tipos]=$v->bases/100;
                 $totalesM[$v->tipos]=$v->totales/100;
            }
             //tipos IVA , bases, ivas, totales OTROS   //AND BT10!=0
            $sql="SELECT MWSA as tipos, sum(BT12) as ivas, sum(BT20) as totales, sum(BT10) as bases "
                    . " FROM pe_bokaMerged as pe_boka1,(SELECT BONU  FROM pe_bokaMerged WHERE STYP='1' AND $where) as pe_boka2  "
                    . " WHERE pe_boka2.BONU=pe_boka1.BONU AND STYP=6 AND "
                    . " EXISTS(SELECT PAR1 FROM pe_bokaMerged WHERE  BONU=pe_boka2.BONU AND (STYP=8 AND PAR1!=1  AND PAR1!=4 AND PAR1!=20 )) "
                    . " GROUP BY MWSA";
            $query=$this->db->query($sql);
            
            foreach($query->result() as $k =>$v){
                $tiposO[$v->tipos]=$v->tipos/100;
                $ivasO[$v->tipos]=$v->ivas/100;
                 $basesO[$v->tipos]=$v->bases/100;
                 $totalesO[$v->tipos]=$v->totales/100;
            }
            

            return array(
                'periodoInicio'=>$periodoInicio,
                'periodoFinal'=>$periodoFinal,
                
                'ventas'=>$ventas,
                'tipos'=>$tipos,
                'ivas'=>$ivas,
                'bases'=>$bases,
                'totales'=>$totales,
                
                'tiposT'=>$tiposT,
                'ivasT'=>$ivasT,
                'basesT'=>$basesT,
                'totalesT'=>$totalesT,
                
                'tiposM'=>$tiposM,
                'ivasM'=>$ivasM,
                'basesM'=>$basesM,
                'totalesM'=>$totalesM,
                
                'tiposO'=>$tiposO,
                'ivasO'=>$ivasO,
                'basesO'=>$basesO,
                'totalesO'=>$totalesO,
                
               // 'sql'=>$sql,
               // 'bonus'=>$bonus,
               // 'bonusOR'=>$bonusOR,
                );
        }
        
        public function getAll($table){
            $sql="SELECT * FROM $table";
            $query=$this->db->query($sql);
            
            return $query->result();
        }
        
        
        public function getSelect($sql){
            $query=$this->db->query($sql);
            
            return $query->result();
        }
        
        public function getBoka2($campos,$inicio,$final){
            $campos=implode(",",$campos);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            $query="SELECT BONU FROM pe_boka WHERE STYP='1' AND $where";
            
            $query=$this->db->query($query);
            $codigosTickets=array();
            foreach ($query->result() as $k => $row) {
                $codigosTickets[]=$row->BONU;
            }
            
            $query="SELECT BONU FROM pe_boka2 WHERE STYP='1' AND $where";
            
            $query=$this->db->query($query);
            $codigosTickets2=array();
            foreach ($query->result() as $k => $row) {
                $codigosTickets2[]=$row->BONU;
            }
            
            //  $codigosTickets -> códigos del periodo
            //  $codigosTickets2 -> códigos presentes en pe_boka2
            // comprobar si un ticket está en pe_boka2, si sí eliminarlo de $codigosTickets para pe_boka
            // y se tomará los datos de pe_boka2
            foreach($codigosTickets as $k=>$v){
                if (in_array($v, $codigosTickets2))
                        unset($codigosTickets[$k]);
            }
            
             $salida=array();
            //var_dump($codigosTickets);
            // tomamos los datos de pe_boka y los registramos en salida
            if (sizeof( $codigosTickets)>0) {
                $where=implode(" OR BONU=",$codigosTickets);
                $where="BONU=$where";
            }
            $query="SELECT $campos FROM pe_boka WHERE $where";
            $query=$this->db->query($query);
            foreach($query->result() as $k=>$v)
                $salida[]=$v;
            
            // tomamos los datos de pe_boka2 y los seguimos registrando en salida
            if (sizeof( $codigosTickets2)>0) {
                $where=implode(" OR BONU=",$codigosTickets2);
                $where="BONU=$where";
            }
            $query="SELECT $campos FROM pe_boka2 WHERE $where";
            $query=$this->db->query($query);
            foreach($query->result() as $k=>$v)
                $salida[]=$v;
            
            
            
            return $salida;
        }
        
             
               
        public function getBoka($campos,$inicio,$final){
            $campos=implode(",",$campos);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            $query="SELECT BONU FROM pe_boka WHERE STYP='1' AND $where";
            
            $query=$this->db->query($query);
            $codigosTickets=array();
            foreach ($query->result() as $k => $row) {
                $codigosTickets[]=$row->BONU;
            }
            //var_dump($codigosTickets); echo min($codigosTickets);echo max($codigosTickets);
            //$where=1;
            if (sizeof($codigosTickets)>0) {
                $where=implode(" OR BONU=",$codigosTickets);
                $where="BONU=$where";
                //$minCodigoTickets=min($codigosTickets);
                //$maxCodigoTickets=max($codigosTickets);
                //$where="BONU>='$minCodigoTickets' AND BONU<='$maxCodigoTickets'";
            }
            $query="SELECT $campos FROM pe_boka WHERE $where";
            //echo $query;
           
            $query=$this->db->query($query);
            
            return $query->result();
        }
        
        public function getResumenTodos($results){
            //var_dump($results);
                    $tickets=0;
                    $base[1]=0;
                    $base[2]=0;
                    $base[3]=0;
                    $base[4]=0;
                    $iva[1]=0;
                    $iva[2]=0;
                    $iva[3]=0;
                    $iva[4]=0;
                    $total[1]=0;
                    $total[2]=0;
                    $total[3]=0;
                    $total[4]=0;
                    $codigosSubidos=array();
                    foreach ($results as $k => $v) {
                        if ($v->STYP==1)
                        {
                            $tickets++;
                            
                            $codigosSubidos[]=  $v->ZEIS.' '.$v->RASA ;
                        }
                        if ($v->STYP==6)
                        {
                            $tipo=intval($v->MWNU);
                            $base[$tipo]+=intval($v->BT10);
                            $iva[$tipo]+=intval($v->BT12);
                            $total[$tipo]+=intval($v->BT20);
                            
                        }
                    }
                    
                    if(sizeof($codigosSubidos)>0){
                    $inicio=min($codigosSubidos);
                    $inicioTicket=substr($inicio,20);
                    $inicioFecha=fechaEuropea(substr($inicio,0,19));
                    
                    $final=max($codigosSubidos);
                    $finalTicket=substr($final,20);
                    $finalFecha=fechaEuropea(substr($final,0,19));

                    $codigosSubidos="Desde el $inicioFecha ($inicioTicket)  hasta el $finalFecha ($finalTicket)";
                    }
                    else $codigosSubidos="---";
                    
                    
              return array('codigosSubidos'=>$codigosSubidos,
                      'tickets' => $tickets,  
                      'base' => $base,
                      'iva' => $iva,
                      'total' => $total
                   );   
            
            }      
            
        public function getResumenMetalico($results){
            //var_dump($results);
                    $tickets=0;
                    $base[1]=0;
                    $base[2]=0;
                    $base[3]=0;
                    $iva[1]=0;
                    $iva[2]=0;
                    $iva[3]=0;
                    $total[1]=0;
                    $total[2]=0;
                    $total[3]=0;
                    $codigosSubidos=array();
                    $metalico=array();
                    foreach ($results as $k=> $v){
                        if (($v->STYP==1) )
                            $metalico[]=$v->BONU;
                    }
                    
                    foreach ($results as $k=> $v){
                        if (($v->STYP==8) AND ($v->PAR1==4) ){
                            $r=array_search($v->BONU,$metalico);
                            if ($r) unset($metalico[$r]);
                        }
                            
                    }
                    foreach ($results as $k => $v) {
                        if(in_array($v->BONU, $metalico)){
                        if ($v->STYP==1)
                        {
                            $tickets++;
                            
                            $codigosSubidos[]=  $v->ZEIS.' '.$v->RASA ;
                        }
                        if ($v->STYP==6)
                        {
                            $tipo=intval($v->MWNU);
                            $base[$tipo]+=intval($v->BT10);
                            $iva[$tipo]+=intval($v->BT12);
                            $total[$tipo]+=intval($v->BT20);
                            
                        }
                        }
                    }
                   
                    if(sizeof($codigosSubidos)>0){
                    $inicio=min($codigosSubidos);
                    $inicioTicket=substr($inicio,20);
                    $inicioFecha=fechaEuropea(substr($inicio,0,19));
                    
                    $final=max($codigosSubidos);
                    $finalTicket=substr($final,20);
                    $finalFecha=fechaEuropea(substr($final,0,19));

                    $codigosSubidos="Desde el $inicioFecha ($inicioTicket)  hasta el $finalFecha ($finalTicket)";
                    }
                    else $codigosSubidos="---";
                    
                    
              return array('codigosSubidos'=>$codigosSubidos,
                      'tickets' => $tickets,  
                      'base' => $base,
                      'iva' => $iva,
                      'total' => $total
                   );   
            
            }        
            
        public function getResumenTarjetas($results){
                  //controlamos num registros
                  
                
             
            //var_dump($results);
                    $tickets=0;
                    $base[1]=0;
                    $base[2]=0;
                    $base[3]=0;
                    $iva[1]=0;
                    $iva[2]=0;
                    $iva[3]=0;
                    $total[1]=0;
                    $total[2]=0;
                    $total[3]=0;
                    $codigosSubidos=array();
                    $tarjetas=array();
                    foreach ($results as $k=> $v){
                        if (($v->STYP==8) AND ($v->PAR1==4) )
                            $tarjetas[]=$v->BONU;
                    }
                    foreach ($results as $k => $v) {
                        if(in_array($v->BONU, $tarjetas)){
                        if ($v->STYP==1)
                        {
                            $tickets++;
                            
                            $codigosSubidos[]=  $v->ZEIS.' '.$v->RASA ;
                        }
                        if ($v->STYP==6)
                        {
                            $tipo=intval($v->MWNU);
                            $base[$tipo]+=intval($v->BT10);
                            $iva[$tipo]+=intval($v->BT12);
                            $total[$tipo]+=intval($v->BT20);
                            
                        }
                        }
                    }
                    
                    if(sizeof($codigosSubidos)>0){
                    $inicio=min($codigosSubidos);
                    $inicioTicket=substr($inicio,20);
                    $inicioFecha=fechaEuropea(substr($inicio,0,19));
                    
                    $final=max($codigosSubidos);
                    $finalTicket=substr($final,20);
                    $finalFecha=fechaEuropea(substr($final,0,19));

                    $codigosSubidos="Desde el $inicioFecha ($inicioTicket)  hasta el $finalFecha ($finalTicket)";
                    }
                    else $codigosSubidos="---";
                    
                    
              return array('codigosSubidos'=>$codigosSubidos,
                      'tickets' => $tickets,  
                      'base' => $base,
                      'iva' => $iva,
                      'total' => $total
                   );   
            
            }            
            
        public function getProductosBoka($campos,$inicio,$final){
            $campos=implode(",",$campos);
            $inicio.=" 00:00";
            $final.=" 23:59";
            $where="ZEIS>='$inicio' AND ZEIS<='$final'";
            $query="SELECT BONU FROM pe_boka WHERE STYP='1' AND $where";
           
            
            $query=$this->db->query($query);
            $codigosTickets=array();
            foreach ($query->result() as $k => $row) {
                $codigosTickets[]=$row->BONU;
            }
            //var_dump($codigosTickets); echo min($codigosTickets);echo max($codigosTickets);
            //$where=1;
            if (sizeof($codigosTickets)>0) {
                $where=implode(" OR BONU=",$codigosTickets);
                $where="BONU=$where";
                //$minCodigoTickets=min($codigosTickets);
                //$maxCodigoTickets=max($codigosTickets);
                //$where="BONU>='$minCodigoTickets' AND BONU<='$maxCodigoTickets'";
            }
            $query="SELECT BONU,BONU2,STYP,SNR1,sum(BT20) as importe, sum(BT30) as descuento, sum(BT40) as iva, avg(b.MWSA) as ivaPorcentaje, avg(MWNU) as tipo, sum(POS1) as unidades,sum(GEW1) as peso,PAR1,STST,POS2,  p.id_producto as id_producto, p.nombre as producto FROM pe_boka b"
                    . " LEFT JOIN pe_productos p ON (STYP=2 AND SNR1=p.id_producto) "
                    . " WHERE STYP=2 AND ($where) "
                    . " GROUP BY SNR1";
           
            $query=$this->db->query($query);
            
            return $query->result();
        }    
            
        public function getProductos($inicio,$final,$agrupar=false){
             $this->load->model('productos_');
            $sql="SELECT SNR1 as producto, 
                         id_pe_producto as id_pe_producto,
                         pr.codigo_producto as codigo_producto,
                         sum(pos1) as unidades,
                         sum(gew1) as peso, 
                         sum((bt20)-(bt40)+(bt30)) as base,  
                         (bt40) as iva, 
                         sum((bt20)+(bt30))  as importe , 
                         (mwsa) as ivaPorcentaje 
                   FROM `pe_boka` b 
                   LEFT JOIN pe_productos pr ON pr.id=b.id_pe_producto 
                   WHERE STYP=2 AND 
                         zeis>='$inicio' AND
                         zeis<='$final 23:59:59'
                         GROUP BY codigo_producto 
                       ORDER BY codigo_producto    
                   ";
            if($agrupar)
                $sql="SELECT SNR1 as producto, 
                             id_pe_producto,
                             sum(pos1) as unidades,
                             sum(gew1) as peso, 
                             sum((bt20)-(bt40)+(bt30)) as base,  
                             (bt40) as iva, 
                             sum((bt20)+(bt30))  as importe , 
                             (mwsa) as ivaPorcentaje 
                       FROM `pe_boka` b 
                       WHERE STYP=2 AND 
                             zeis>='$inicio' AND
                             zeis<='$final 23:59:59'
                       GROUP BY SNR1 
                       ORDER BY SNR1
                       ";

            
            $lineasProducto=array();
            $result=$this->db->query($sql)->result();
            foreach($result as $k=>$v){
                
                //si agrupar por código báscula
                if($agrupar)
                    $codigo_producto=$v->producto;
                else {
                    $codigo_producto= $v->codigo_producto;//$this->productos_->codigoBasculaToCodigo13($v->producto,$v->peso);
                    if(!$codigo_producto) $codigo_producto= $v->producto;
                }
                
                if(!isset($lineasProducto[$codigo_producto])){
                    $lineasProducto[$codigo_producto]=array(
                       
                    'unidades'=>$v->unidades,
                    'peso'=>$v->peso,
                    'base'=>$v->base,
                    'iva'=>$v->iva,
                    'importe'=>$v->importe,
                    'ivaPorcentaje'=>$v->ivaPorcentaje
                     );
                }else{
                    $lineasProducto[$codigo_producto]['unidades']+=$v->unidades;
                    $lineasProducto[$codigo_producto]['peso']+=$v->peso;
                    $lineasProducto[$codigo_producto]['base']+=$v->base;
                    $lineasProducto[$codigo_producto]['iva']+=$v->iva;
                    $lineasProducto[$codigo_producto]['importe']+=$v->importe;
                    $lineasProducto[$codigo_producto]['ivaPorcentaje']=ceil(($lineasProducto[$codigo_producto]['ivaPorcentaje']+$v->ivaPorcentaje)/2);
                }
            }
            
            $productos=array();
            foreach($lineasProducto as $k=>$v){
               
                 $sql="SELECT nombre FROM pe_productos WHERE codigo_producto='$k'";
                 
                 //si agrupar por código báscula
                 if($agrupar)
                    $sql="SELECT nombre FROM pe_productos WHERE id_producto='$k'";
                
                 if($this->db->query($sql)->num_rows()>0)
                    $nombre=$this->db->query($sql)->row()->nombre;
                 else $nombre='Codigo báscula. No en base datos productos';
                 $productos[]=array('nombre'=>$nombre, 'codigo'=>$k,'unidades'=>$v['unidades'],'peso'=>$v['peso'],'importe'=>$v['importe'],'base'=>$v['base'],'iva'=>$v['iva'],'ivaPorcentaje'=>$v['ivaPorcentaje']);
            }

            return $productos;
        }
        
        //para la version php 7.2
        public function getProductos72($inicio,$final,$agrupar=false){
            $sql="SET SQL_BIG_SELECTS=1";
                $this->db->query($sql);
                ini_set('memory_limit', '-1');
                //ini_set('max_execution_time', 0);
                set_time_limit(300);
            $this->load->model('productos_');
            $sql="SELECT id_pe_producto,codigo_producto,nombre
                    FROM `pe_boka` b  
                    LEFT JOIN pe_productos p ON p.id=b.id_pe_producto            
                    WHERE STYP=2 AND 
                            zeis>='$inicio' AND
                            zeis<='$final 23:59:59'
                            GROUP BY id_pe_producto,p.nombre
            ";
            
            $this->db->query("DELETE FROM pe_productos_excel WHERE 1");
           $lineasProducto=array();
           $result=$this->db->query($sql)->result();
            $contador=0;
           foreach($result as $k=>$v){
               $id_pe_producto=$v->id_pe_producto;
               $codigo_producto=$v->codigo_producto;  

               $sql="SELECT     sum(pos1) as unidades,
                                sum(gew1) as peso,                           
                                sum(if(STYP=2,bt20,bt30))  as importe         
                    FROM `pe_boka` b 
                    WHERE (STYP=2 OR STYP=202) AND 
                        zeis>='$inicio' AND
                        zeis<='$final 23:59:59' AND 
                        id_pe_producto='$id_pe_producto'
                        ";
                $row=$this->db->query($sql)->row(); 
               if(!isset($lineasProducto[$codigo_producto])){
                   $lineasProducto[$codigo_producto]=array(
                                                        'unidades'=>$row->unidades,
                                                        'peso'=>$row->peso,
                                                        'importe'=>$row->importe,
                                                        'codigo_producto'=>$v->codigo_producto,
                                                        'nombre'=>$v->nombre,
                                                    );
               }else{
                   $lineasProducto[$codigo_producto]['unidades']+=$row->unidades;
                   $lineasProducto[$codigo_producto]['peso']+=$row->peso;
                   $lineasProducto[$codigo_producto]['importe']+=$row->importe;
               }
               $this->db->query("INSERT INTO pe_productos_excel SET 
                                             codigo_producto='".$v->codigo_producto."',
                                             nombre='".$v->nombre."',
                                             cantidad='".$row->unidades."',
                                             peso='".$row->peso."',
                                             importe='".$row->importe."'
                                             ");

           }
           return array('lineasProducto'=>$lineasProducto);
       }
        
        
        public function getProductosTotales($inicio,$final){
            $sql="SELECT count(snr1) as producto,
                         sum(pos1) as unidades,
                         sum(gew1) as peso, 
                         SUM(bt20)-SUM(bt40) as base, 
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

        public function getProductosTotales72($inicio,$final){
            $sql="SELECT count(snr1) as producto,
                         sum(pos1) as unidades,
                         sum(gew1) as peso, 
                         SUM(bt20)-SUM(bt40) as base, 
                         SUM(bt40) as iva, 
                         sum(if(STYP=2,bt20,bt30))  as importe,   
                         FLOOR(avg(mwsa)) as ivaPorcentaje
                   FROM `pe_boka` b
                   WHERE (STYP=2 OR STYP=202) AND 
                         zeis>='$inicio' AND
                         zeis<='$final 23:59:59'
                   ";    
                   
            $row=$this->db->query($sql)->row();

            $salida=array('producto'=>$row->producto,
                            'unidades'=>$row->unidades,
                            'peso'=>$row->peso,
                            'base'=>$row->base,
                            'iva'=>$row->iva,
                            'importe'=>$row->importe,
                            'ivaPorcentaje'=>$row->ivaPorcentaje,
                        );
            return $salida;            
        }
        
        public function getResumenProductos($results){
             $tickets=0;
             $codigosSubidos=array();
             foreach ($results as $k => $v) {
                
                    if ($v->STYP == 1) {
                        
                        $tickets++;
                        $codigosSubidos[] = $v->ZEIS . ' ' . $v->RASA;
                    }
                
            }
            if(sizeof($codigosSubidos)>0){
                    $inicio=min($codigosSubidos);
                    $inicioTicket=substr($inicio,20);
                    $inicioFecha=fechaEuropea(substr($inicio,0,19));
                    
                    $final=max($codigosSubidos);
                    $finalTicket=substr($final,20);
                    $finalFecha=fechaEuropea(substr($final,0,19));

                    $codigosSubidos="Desde el $inicioFecha ($inicioTicket)  hasta el $finalFecha ($finalTicket)";
                    }
                    else $codigosSubidos="---";
                    
                    return array('codigosSubidos'=>$codigosSubidos,
                      'tickets' => $tickets,  
                   );  
    }
    
    public function leerCliente($id_cliente){
        $sql="SELECT * FROM pe_clientes WHERE id_cliente='$id_cliente'";
        $query=$this->db->query($sql);
            
            return $query->row();
        
        
    }
    
    public function leerClienteNuevo(){
        $sql="SELECT * FROM pe_clientes LIMIT 1";
        $query=$this->db->query($sql);
            
            return $query->row();
        
        
    }

}  
        
        
