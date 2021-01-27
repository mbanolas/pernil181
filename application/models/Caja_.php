<?php
class Caja_ extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function contarDias($inicio,$final){
            $fecha1=min(array($inicio,$final));
            $fecha2=max(array($inicio,$final));
            $sql="SELECT count(*) as numCierres FROM pe_caja WHERE fecha>='$fecha1' AND fecha<='$fecha2'";
           //return $sql;
            $query=$this->db->query($sql);
           $row = $query->row();
           return $row->numCierres;
        }
        
        public function getCierres($inicio,$final){
            $fecha1=min(array($inicio,$final));
            $fecha2=max(array($inicio,$final));
            $sql="SELECT *  FROM pe_caja WHERE fecha>='$fecha1' AND fecha<='$fecha2' ORDER BY fecha";
           //return $sql;
            $query=$this->db->query($sql);
           
           return $query->result();
        }
        
        public function getSumaCierres($inicio,$final){
            $fecha1=min(array($inicio,$final));
            $fecha2=max(array($inicio,$final));
            $sql="SELECT sum(cambioManana) as cambioManana, "
                    . " sum(ventaMetalico) as ventaMetalico,"
                    . " sum(ventaTarjeta) as ventaTarjeta,"
                    . " sum(ventaACuenta) as ventaACuenta,"
                    . " sum(ventaTransferencia) as  ventaTransferencia, "
                    . " sum(ventaVale) as ventaVale, "
                    . " sum(ventaCheque) as ventaCheque,"
                    . " sum(cobroAtrasosMetalico) as cobroAtrasosMetalico,  "
                    . " sum(cobroAtrasosTarjeta) as cobroAtrasosTarjeta,  "
                    . " sum(ventaNoCobrada) as ventaNoCobrada, "
                    . " sum(retiroMetalico) as retiroMetalico, "
                    . " sum(retiroTarjeta) as retiroTarjeta, "
                    . " sum(retiroVale) as retiroVale, "
                    . " sum(retiroCheque) as retiroCheque, "
                    . " sum(retiroTransferencia) as retiroTransferencia, "
                    . " sum(cambioNoche) as cambioNoche, "
                    . " sum(saldoBanco) as saldoBanco, "
                   
                    . " sum(ventaDia) as ventaDia, "
                    . " sum(diferenciaMetalico) as diferenciaMetalico, "
                    . " sum(diferenciaTarjeta) as diferenciaTarjeta, "
                    . " sum(diferenciaTransferencia) as diferenciaTransferencia, "
                    . " sum(diferenciaVale) as diferenciaVale, "
                    . " sum(diferenciaCheque) as diferenciaCheque, "
                    . " sum(diferenciaCajaAcumulada) as diferenciaCajaAcumulada, "
                    . " sum(diferenciaCaja) as diferenciaCaja "
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    . "FROM pe_caja WHERE fecha>='$fecha1' AND fecha<='$fecha2'";
           //return $sql;
            $query=$this->db->query($sql);
           
           return $query->result();
        }
        
        
        public function saveDatosInicio($fecha,$cambioNoche,$saldoBanco, $desviacionCajaAcumulada, $notas){
            
           $sql="DELETE FROM `pe_caja` WHERE fecha>='$fecha'";
           $query=$this->db->query($sql);
           $cambioNoche*=100;
           $saldoBanco*=100;
           $desviacionCajaAcumulada*=100;
           
           
           $set=  "SET `fecha`='$fecha',"
                     . " `cambioManana`=0,"
                   . " `ventaDia`=0,"
                   . " `ventaMetalico`=0,"
                    . " `ventaTarjeta`=0,"
                    . " `ventaACuenta`=0,"
                    . " `ventaTransferencia`=0,"
                    . " `ventaVale`=0,"
                    . " `ventaCheque`=0,"
                    . " `retiroMetalico`=0,"
                    . " `retiroTarjeta`=0,"
                    . " `retiroVale`=0,"
                    . " `retiroCheque`=0,"
                    . " `retiroTransferencia`=0,"
                    . " `cambioNoche`='$cambioNoche',"
                    . " `cobroAtrasosMetalico`=0,"
                    . " `cobroAtrasosTarjeta`=0,"
                    . " `ventaNoCobrada`=0, "
                    . " `saldoBanco`='$saldoBanco' ,"
                    . " `diferenciaCajaAcumulada`='$desviacionCajaAcumulada' ,"
                    . " `notas`='$notas' ";
            
             $sql="INSERT INTO pe_caja $set";
             $query=$this->db->query($sql);
            return $query;
        }
        
        function grabarDatosCaja2(){
             
             //return $_POST;
             
            extract($_POST);
            $fecha;
            $cambioMañana=(float)$cambioMañana;
            $ventasMetalico=(float)$ventaMetalico;
            $ventasTajeta=(float)$ventaTajeta;
            $ventasACuenta=(float)$ventaACuenta;
            $ventasTransferencia=(float)$ventaTransferencia;
            $ventasVale=(float)$ventaVale;
            $ventasCheque=(float)$ventaCheque;
            $cobroAtrasos=(float)$cobroAtrasos;
            $ventaNoCobrada=(float)$ventaNoCobrada;
            $retiroMetalico=(float)$retiroMetalico;
            $retiroTarjeta=(float)$retiroTarjeta;
            $retiroVale=(float)$retiroVale;
            $retiroCheque=(float)$retiroCheque;
            $retiroTransferencia=(float)$retiroTransferencia;
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
                     . "retiroTransferencia='$retiroTransferencia', "
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
                     . "retiroTransferencia='$retiroTransferencia', "
                     . "cambioNoche='$cambioNoche', "
                     . "saldoBanco='$saldoBanco', "
                     . "notas='$notas'";
            }
            
             //return $sql;
            $query = $this->db->query($sql);
        
            return $sql;
            }   
        
        
         function grabarDatosCaja(){
             
            // return $_POST;
             
            extract($_POST);
            $fecha;
            $set;
            
            
            $this->load->database();
            $sql="SELECT * FROM pe_caja WHERE fecha='$fecha'";
            $query = $this->db->query($sql);
            
            if ($query->num_rows()>0){
                
             $sql="UPDATE pe_caja SET $set WHERE fecha='$fecha' ";
                    
            
            }else {
            $sql="INSERT INTO pe_caja SET $set  ";
                    
            }
            
          
            $query = $this->db->query($sql);
        
            return $sql;
            }   
            
            function leerDatosAnterioresCaja($fecha){
                $sql="SELECT cambioNoche,saldoBanco,diferenciaCajaAcumulada FROM pe_caja WHERE fecha<'$fecha' ORDER BY fecha DESC LIMIT 1";
                $query = $this->db->query($sql);
                //return $sql;
                return is_null($query->row())?0:array('cambioNocheAnterior'=>$query->row()->cambioNoche,
                       'saldoBanco'=>$query->row()->saldoBanco,
                        'diferenciaCajaAcumulada'=>$query->row()->diferenciaCajaAcumulada);
            }
            function leerDatosCierreCaja($fecha){
                $sql="SELECT * FROM pe_caja WHERE fecha='$fecha' ORDER BY fecha DESC LIMIT 1";
                $query = $this->db->query($sql);
                
                return is_null($query->row())?0:array(
                    'retiroMetalico'=>$query->row()->retiroMetalico,
                     'retiroTarjeta'=>$query->row()->retiroTarjeta,
                     'retiroVale'=>$query->row()->retiroVale,
                     'retiroCheque'=>$query->row()->retiroCheque,
                    'retiroTransferencia'=>$query->row()->retiroTransferencia,
                    'cambioNoche'=>$query->row()->cambioNoche,
                    'cobroAtrasosMetalico'=>$query->row()->cobroAtrasosMetalico,
                    'cobroAtrasosTarjeta'=>$query->row()->cobroAtrasosTarjeta,
                    'ventaNoCobrada'=>$query->row()->ventaNoCobrada,
                    'saldoBanco'=>$query->row()->saldoBanco,
                    'diferenciaCajaAcumulada'=>$query->row()->diferenciaCajaAcumulada,
                    );
            }
            
        
}
        
