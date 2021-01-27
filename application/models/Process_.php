<?php
class Process_ extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        
        public function altaProducto($datos){
            
            extract($datos);
            
            //compreba campos vacios
           if(trim($id_producto)=="" || trim($producto)=="" || trim($familia)=="0" || trim($proveedor)=="0" || trim($precio)=="")
               return false;
           
           $sql="SELECT * FROM pe_productos WHERE id_producto='$id_producto'";
           
           $query=$this->db->query($sql);
            
           $row = $query->row();
           
           //comprueba si ya existe
           if ($this->db->affected_rows()>0)
               return false;
           
           $sql2="INSERT INTO pe_productos "
                    . "SET id_producto='$id_producto',"
                    . " id_familia='$familia',"
                    . "  id_proveedor='$proveedor',"
                    . " nombre='$producto', "
                    . " precio='$precio'";
                    
            $query=$this->db->query($sql2);
            return true;      
           
        }
        
      
        
        
        public function altaProveedor($datos){
            $set="";
           foreach($datos as $k=>$v){
               $set.="$k='$v',";
           }
           $set=substr($set,0,-1);

           extract($datos); //para obtener $id_proveedor
           
           //compreba campos vacios
            if(trim($id_proveedor)=="" || trim($nombre)=="" )
               return false;
           
           $sql="SELECT * FROM pe_proveedores WHERE id_proveedor='$id_proveedor' OR nombre='$nombre'";
           //return $sql;
           $query=$this->db->query($sql);
            
           $row = $query->row();
           
           //comprueba si ya existe
           if ($this->db->affected_rows()>0)
               return false;
           
           
            $sql2="INSERT INTO pe_proveedores SET $set";
                    
                    
            $query=$this->db->query($sql2);
            return $query;      
        }
        
        public function altaAcreedor($datos){
            $set="";
           foreach($datos as $k=>$v){
               $set.="$k='$v',";
           }
           $set=substr($set,0,-1);

           extract($datos); //para obtener $id_proveedor
           
           //compreba campos vacios
            if(trim($id_proveedor)=="" || trim($nombre)=="" )
               return false;
           
           $sql="SELECT * FROM pe_acreedores WHERE id_proveedor='$id_proveedor' OR nombre_proveedor='$nombre'";
           //return $sql;
           $query=$this->db->query($sql);
            
           $row = $query->row();
           
           //comprueba si ya existe
           if ($this->db->affected_rows()>0)
               return false;
           
           
            $sql2="INSERT INTO pe_acreedores SET $set";
                    
                    
            $query=$this->db->query($sql2);
            return $query;      
        }
        
        public function altaFamilia($datos){
            
            
            extract($datos);
          
            //compreba campos vacios
            if(trim($id_familia)=="" || trim($familia)=="" )
               return false;
           
           $sql="SELECT * FROM pe_familias WHERE id_familia='$id_familia' OR nombre='$familia'";
           //return $sql;
           $query=$this->db->query($sql);
            
           $row = $query->row();
           
           //comprueba si ya existe
           if ($this->db->affected_rows()>0)
               return false;
           
           $sql2="INSERT INTO pe_familias "
                    . "SET id_familia='$id_familia',"
                   
                    . " nombre='$familia' ";
                 
                    
            $query=$this->db->query($sql2);
            return true;      
           
        }
        
        public function altaCliente($datos){
            
            
           $id_cliente=$datos['id_cliente'];
          
            
           
           
           $sql="SELECT * FROM pe_clientes WHERE id_cliente='$id_cliente'";
           //return $sql;
           $query=$this->db->query($sql);
            
           $row = $query->row();
           
           //comprueba si ya existe
           if ($this->db->affected_rows()>0)
               return false;
           
          
           $set="SET ";
           foreach($datos as $k => $v){
               $set.=" $k = '".$v."',";
           }
           $set=trim($set,',');  //elimina la última coma
           
            $sql="INSERT INTO pe_clientes "
                    . "$set ";
                   
            
            $query=$this->db->query(html_entity_decode(htmlentities($sql)));
            return true;      
           
        }
        
        
        public function upDateProducto($datos){
            
           extract($datos);
            
            $sql="UPDATE pe_productos "
                    . "SET id_familia='$familia',"
                    . "  id_proveedor='$proveedor',"
                    . " nombre='$producto', "
                    . " precio='$precio'"
                    . " WHERE id_producto='$id_producto'";
                  
             //return $sql;           
                      
            $query=$this->db->query($sql);
            
        }
        
        public function upDateProveedor($datos){
           $set="";
           foreach($datos as $k=>$v){
               $set.="$k='$v',";
           }
           extract($datos); //para obtener $id_proveedor
           $set=substr($set,0,-1);
            $sql="UPDATE pe_proveedores "
                    . "SET "
                    . " $set "
                    . " WHERE id_proveedor='$id_proveedor'";

            $query=$this->db->query($sql);
            return $sql;
            
        }
        
        public function upDateAcreedor($datos){
           $set="";
           foreach($datos as $k=>$v){
               $set.="$k='$v',";
           }
           extract($datos); //para obtener $id_proveedor
           $set=substr($set,0,-1);
            $sql="UPDATE pe_acreedores "
                    . "SET "
                    . " $set "
                    . " WHERE id_proveedor='$id_proveedor'";

            $query=$this->db->query($sql);
            return $sql;
            
        }
        
        public function upDateFamilia($datos){
            
           extract($datos);
            
            $sql="UPDATE pe_familias "
                    . "SET "
                    . " nombre='$familia' "
                    . " WHERE id_familia='$id_familia'";
                  
             //return $sql;           
                      
            $query=$this->db->query($sql);
            
        }
        
        public function upDateCliente($datos){
           $id_cliente=$datos['id_cliente'];
           $set="SET ";
           foreach($datos as $k => $v){
               $set.=" $k = '".$v."',";
           }
           $set=trim($set,',');  //elimina la última coma
           
            $sql="UPDATE pe_clientes "
                    . "$set "
                    . " WHERE id_cliente='$id_cliente'";
            
            $query=$this->db->query(html_entity_decode(htmlentities($sql)));
        }
        
        public function eliminarProducto($datos){
            
           extract($datos);
            
            $sql="DELETE FROM pe_productos "
                    . " WHERE id_producto='$id_producto'";
              
            $query=$this->db->query($sql);
            
        }
        
        public function eliminarFamilia($datos){
            
           extract($datos);
            
            $sql="DELETE FROM pe_familias "
                    . " WHERE id_familia='$id_familia'";
              
            $query=$this->db->query($sql);
            
        }
        
        public function eliminarProveedor($datos){
            
           extract($datos);
            
            $sql="DELETE FROM pe_proveedores "
                    . " WHERE id_proveedor='$id_proveedor'";
              
            $query=$this->db->query($sql);
            
        }
        
        public function eliminarAcreedor($datos){
            
           extract($datos);
            
            $sql="DELETE FROM pe_acreedores "
                    . " WHERE id_proveedor='$id_proveedor'";
              
            $query=$this->db->query($sql);
            
        }
        
        public function eliminarCliente($id_cliente){
            
            $sql="DELETE FROM pe_clientes "
                    . " WHERE id_cliente='$id_cliente'";
              
            
            $query=$this->db->query($sql);
            
        }
        
        
        
        public function getNombreFamilia($id_familia){
            $sql="SELECT nombre FROM pe_familias "
                    . " WHERE id_familia='$id_familia'";
            $query=$this->db->query($sql);
            
            $row = $query->row();
            return $row->nombre;
            
        }
        
        public function getNombreProveedor($id_proveedor){
            $sql="SELECT nombre FROM pe_proveedores "
                    . " WHERE id_proveedor='$id_proveedor'";
            $query=$this->db->query($sql);
            
            $row = $query->row();
            return $row->nombre;
            
        }
        
        
}
        
