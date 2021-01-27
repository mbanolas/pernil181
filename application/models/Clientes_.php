<?php
class Clientes_ extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function id_clienteUltimoCliente(){
            $sql="SELECT MAX(id_cliente) as ultimo_id_cliente FROM pe_clientes";
            $query=$this->db->query($sql);
            return $query->row()->ultimo_id_cliente;
        }
        
        public function getClientes(){
            $sql="SELECT id_cliente, nombre FROM pe_clientes ORDER BY nombre";
            $result=$this->db->query($sql)->result();
            $clientes[0]='_Seleccionar un cliente';
            foreach($result as $k=>$v){
                $clientes[$v->id_cliente]=$v->nombre.' ('.$v->id_cliente.')';
            }
            return $clientes;
        }
        
        public function getClientesFiltro($filtro){
        $optionsClientes[0]="Seleccionar un cliente";
        $palabras=explode(" ",trim($filtro));
         $like="";
         $resultado=array();
         $resultado2=array();
            foreach($palabras as $k=>$v){
                $resultado[]="concat(nombre,id_cliente) LIKE '%$v%'";
            }
          $like=implode(' AND ',$resultado);
         $clientes=array();
         $sql="SELECT id,id_cliente,nombre FROM pe_clientes WHERE $like ORDER BY nombre";
         $result=$this->db->query($sql)->result();
         foreach($result as $k=>$v){
             $cliente=array(
                 'id'=>$v->id,
                 'id_cliente'=>$v->id_cliente,
                 'nombre'=>$v->nombre,
             );
             $clientes[]=$cliente;
         }
         
        
        foreach($clientes as $k=>$v){
            $id=$v['id']; 
            $id_cliente=$v['id_cliente']; 
            $nombre=$v['nombre'];
            $optionsClientes[$id_cliente]="$nombre ($id_cliente)";
        }
         
         return array( 'optionsClientes'=>$optionsClientes,'sql'=>$sql);
        }
        
        public function getDatosCliente($cliente){
            $datosCliente=array('nombre' => '',
               'poblacion' => '',
               'provincia' => '',
               'pais' => '',
               'nif' => '',
               'codigoPostal' => '',
               'direccion' => '',
                'correo1' => '',
                'correo2' => '',
               );
            $sql="SELECT nombre,empresa,direccion,poblacion,provincia,pais,nif,codigoPostal,correo1,correo2  FROM pe_clientes WHERE id_cliente='$cliente'";
            // mensaje('getDatosCliente '.$sql);
            $query=$this->db->query($sql);
            $row = $query->row();
            if($row){
                if($row->correo1=="") $row->correo1="sin información email";
                $datosCliente=array(
               'empresa'=>$row->empresa,
               'nombre' => $row->nombre,
               'poblacion' => $row->poblacion,
               'provincia' => $row->provincia,
               'pais' => $row->pais,
               'nif' => $row->nif,
               'codigoPostal' => $row->codigoPostal,
               'direccion' => $row->direccion,
               'correo1' => $row->correo1,
               'correo2' => $row->correo2,     
               );
            }
           return $datosCliente;
                 
        }

        public function getDatosClientePrestashop($cliente){
            $datosCliente=array(
                'num_cliente'=>$cliente,
                'company' => '',
                'firstname' => '',
                'lastname' => '',
                'email' => '',
                'address_postcode' => '',
                'address_city' => '',
                'address_phone' => '',
                'address_phone_mobile' => '',
                'id_lang' => '',
                'shop_name' => '',
                'country' => '',
               );
            $sql="SELECT *  FROM pe_clientes_jamonarium WHERE id='$cliente'";
            
            $query=$this->db->query($sql);
            $row = $query->row();
            if($row){
                switch($row->id_lang){
                    case 1: $idioma='Inglés'; break;
                    case 2: $idioma='Francés'; break;
                    case 3: $idioma='Español'; break;
                    case 4: $idioma='Inglés'; break;
                    case 5: $idioma='Italiano'; break;
                    case 6: $idioma='Catalán'; break;
                    default: $idioma=$row->id_lang;

                }
                $datosCliente=array(
                    'num_cliente'=>$row->id,
                    'company' => $row->company,
                    'firstname' => $row->firstname,
                    'lastname' => $row->lastname,
                    'email' => $row->email,
                    'address_postcode' => $row->address_postcode,
                    'address_city' => $row->address_city,
                    'address_phone' => $row->address_phone,
                    'address_phone_mobile' => $row->address_phone_mobile,
                    'id_lang' => $idioma,
                    'shop_name' => $row->shop_name,
                    'country' => $row->country,    
               );
            }
           return $datosCliente;
                 
        }
        
}
        
