<?php
class Mostrar_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function getTickets($inicio, $final){
            $query="SELECT a.BONU as BONU, a.RASA as RASA, a.ZEIS as ZEIS from pe_boka a "
                   // . " left join pe_boka b on a.BONU=b.BONU and (b.STYP=8 and b.PAR1=1) "
                    . "WHERE a.STYP=1  AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ORDER BY a.ZEIS DESC";
            $query=$this->db->query($query);
            $num=$query->num_rows();
            
            $query="SELECT a.BONU as BONU, a.RASA as RASA, a.ZEIS as ZEIS, a.BT20 FROM pe_boka a "
                   // . " left join pe_boka b on a.BONU=b.BONU and (b.STYP=8 and b.PAR1=1) "
                    . "WHERE a.STYP=1  AND a.ZEIS>='$inicio' AND a.ZEIS<='$final 23:59:59' ORDER BY a.ZEIS DESC";
            $query=$this->db->query($query);
            $tickets=array();
            $i=1;
            $this->db->query("DELETE FROM `pe_temporal_mostrar` WHERE 1");
            foreach ($query->result() as $k => $row) {
                if($row->BONU){
                    $importe=  number_format($row->BT20/100,2);
                    $ticket=$row->RASA.' '.fechaEuropea($row->ZEIS).' - Total: '.$importe.' €';
                    $tickets[$i]=$ticket;
                    $this->db->query("INSERT INTO `pe_temporal_mostrar` SET id='$i', ticket='$ticket'");
                    $i++;
                }
                    
            }
            return array('num'=>sizeof($tickets),'tickets'=>$tickets, );
            
        }
        
        public function getTicketsPeriodo(){
            $query=$this->db->query("SELECT * FROM pe_temporal_mostrar");
            $ticketsPeriodo=array();
            foreach($query->result() as $k=>$v){
                $ticketsPeriodo[$v->id]=$v->ticket;
            }
            return $ticketsPeriodo;
        }
        /*
        public function getTicketsPeriodoAjax(){
            $query=$this->db->query("SELECT * FROM pe_temporal_mostrar");
            $ticketsPeriodo=array();
            foreach($query->result() as $k=>$v){
                $ticketsPeriodo[$v->id]=$v->ticket;
            }
            
            echo json_encode(utf8_encode($ticketsPeriodo));
            //return $ticketsPeriodo;
        }
        */
        
        
        public function getDatosTicket($ticket){
            $query=$this->db->query("SELECT ticket FROM pe_temporal_mostrar WHERE id='$ticket'");
            $numTicket= $query->row()->ticket;
            $numTicket=substr($numTicket,0,strpos($numTicket,' - Total:'));
            $ticket=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka");
            //var_dump($ticket);
            //exit();
            $ticket2=$this->tickets_->getTicketPorNumero($numTicket,"pe_boka2");
            return array('ticket'=> $ticket,'ticket2'=>$ticket2);
        }
        
        public function getFormasPago(){
            $formasPago=array(
                                'Entregado'=>'Entregado',
                                'Cheque'=>'Cheque',
                                'Vale'=>'Vale',
                                'Tarjeta de Crédido'=>'Tarjeta de Crédido',
                                'Transferencia'=>'Transferencia',
                                'A cuenta'=>'A cuenta',
                                'Cambio'=>'Cambio',
                                );
            return $formasPago;
        }
        
}