<?php
class Catalogo_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        function grabarDescuento(){
            $dto=$_POST['dto']*1000;
            $id=$_POST['id'];
            $idioma=$_POST['idioma'];
            if($idioma=='es')
                return $this->db->query("UPDATE pe_marcas SET descuento='$dto' WHERE id='$id'");
            if($idioma=='en')
                return $this->db->query("UPDATE pe_marcas SET descuento_en='$dto' WHERE id='$id'");
            if($idioma=='fr')
                return $this->db->query("UPDATE pe_marcas SET descuento_fr='$dto' WHERE id='$id'");
        }
}
