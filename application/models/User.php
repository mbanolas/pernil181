<?php
Class User extends CI_Model
{
    
 public function __construct(){   
     $this->load->database();
 }
 
 function login($username, $password)
 {
   $this -> db -> select('id, username, password');
   $this -> db -> from('pe_users');
   $this -> db -> where('username', $username);
   $this -> db -> where('password', MD5($password));
   $this -> db -> limit(1);

   $query = $this -> db -> get();
   $md5=MD5($password);
   $sql="SELECT id, username, password, nombre, tipoUsuario from pe_users WHERE username='$username'  AND password='$md5'";
   mensaje($sql);
   $query=$this->db->query($sql);
   
   if($query -> num_rows() == 1)
   {
     $row=  $query->row();
     //$fecha=date('Y-m-d H:i:s');
     //$ip=$this->get_client_ip_env();
    // $registro="INSERT INTO pe_movimientosWeb ( usuario, fecha, IP) values($row->id, '$fecha','$ip')";
    // $this->db->query($registro);
     
     return $query->result();
   }
   else
   {
     return false;
   }
 }
 
 // Function to get the client ip address
public function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
 
function fechaMovimientoWeb($fecha,$nombre,$anchoPantalla){
    $ip=$this->get_client_ip_env();    
    $sql="INSERT INTO pe_movimientosWeb SET accion='entrada', usuario='$nombre',fecha='$fecha',IP='$ip',ancho_pantalla=$anchoPantalla ";
    $this->db->query($sql);
    return $fecha;
}
 
 
}
?>