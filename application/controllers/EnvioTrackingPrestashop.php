<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($GLOBALS['_SERVER']['HTTP_REFERER'])) exit("<h2>No está permitido el acceso directo a esta URL</h2>");


class EnvioTrackingPrestashop extends CI_Controller {
	 
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
                    
        }

    function listaPendientes(){
        $dato['pendientes']=$this->db->query("SELECT * FROM pe_email_tracking WHERE fecha_envio IS NULL")->result_array();

            $dato['autor'] = 'Miguel Angel Bañolas';
            $this->load->view('templates/header.html', $dato);
            $this->load->view('templates/top.php', $dato);
            $this->load->view('envioTrackingPrestashop', $dato);
            $this->load->view('templates/footer.html', $dato);
    }   

    function enviar(){
        $this->load->library('email');


        $dato['pedidos']=array();
        $datos['fallosEnvios']=array();
        $this->db->query("DELETE FROM pe_datos_email_tracking");
        foreach($_POST as $k=>$v) {
            $dato['pedidos'][]=$v;
            $datosPedido=$this->db->query("SELECT * FROM pe_orders_prestashop o
                                            LEFT JOIN pe_traducciones_paises t ON o.delivery_country=t.esp WHERE id='$v'")->result_array();
            
            foreach($datosPedido as $k1=>$v1){
                $shop_name=$v1['shop_name'];
                $datosShop=$this->db->query("SELECT * FROM pe_shops_web  WHERE nombre_web='$shop_name'")->row_array();
                $shop=array();
               
                $idioma=$v1['customer_id_language'];
                $asunto="";
                switch($idioma){
                    case 1:
                        $v1['delivery_country']=$v1['eng'];
                        $mensajeEmail=$this->eng($v1,$datosShop);
                        $asunto="Jamonarium - Tracking Order ".$v1['reference'];
                        break;
                    case 2:
                        $v1['delivery_country']=!$v1['fra']?$v1['delivery_country']:$v1['fra'];
                        $mensajeEmail=$this->fra($v1,$datosShop); 
                        $asunto="Jamonarium - Suivi de commande ".$v1['reference'];
                        break;
                    case 3:
                        $v1['delivery_country']=$v1['esp'];
                        $mensajeEmail=$this->esp($v1,$datosShop);
                        $asunto="Jamonarium - Seguimiento Pedido ".$v1['reference'];
                        break;
                    case 4:
                        $v1['delivery_country']=$v1['eng'];
                        $mensajeEmail=$this->eng($v1,$datosShop);
                        $asunto="Jamonarium - Tracking Order ".$v1['reference'];
                        break;
                    case 5:
                        $v1['delivery_country']=$v1['ita'];
                        $mensajeEmail=$this->ita($v1,$datosShop);
                        $asunto="Jamonarium - Tracciamento dell´ordine ".$v1['reference'];
                        break;
                    case 6:
                        $v1['delivery_country']=$v1['cat'];
                        $mensajeEmail=$this->cat($v1,$datosShop);
                        $asunto="Jamonarium - Seguiment de la comanda ".$v1['reference'];
                        break;
                    default: 
                        $v1['delivery_country']=$v1['esp'];
                        $mensajeEmail=$this->eng($v1,$datosShop);
                        $asunto="Jamonarium - Seguimiento Pedido ".$v1['reference'];   
                }

                $nombreEmpresa=$datosShop['nombre_empresa']; 
                $email_info=$datosShop['email_info']; 
                $telefono_contacto=$datosShop['telefono_contacto']; 
                $web_chat=$datosShop['web_chat']; 
                $facebook_url=$datosShop['facebook_url']; 
                $facebook_nombre=$datosShop['facebook_nombre']; 
                $twitter_nombre=$datosShop['twitter_nombre']; 
                $instagram_url=$datosShop['instagram_url']; 
                $instagram_nombre=$datosShop['instagram_nombre']; 
                $youtube_url=$datosShop['youtube_url']; 
                $youtube_nombre=$datosShop['youtube_nombre']; 
                $blog_url=$datosShop['blog_url']; 
                $twitter_url=$datosShop['twitter_url']; 
                $blog_nombre=$datosShop['blog_nombre']; 
                
                
                //correo desde info@lolivaret.com
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = $datosShop['smtp_host'];         //'ssl://lin116.loading.es'; 
                $config['smtp_port'] = $datosShop['smtp_port'];  
                $config['smtp_user'] = $datosShop['smtp_user'];  
                $config['smtp_pass'] = $datosShop['smtp_pass'];  
                // mensaje($config['smtp_host']);
                // mensaje($config['smtp_port']);
                // mensaje($config['smtp_user']);
                // mensaje($config['smtp_pass']);
/*
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'ssl://lin116.loading.es'; 
                $config['smtp_port'] = '465';
                $config['smtp_user'] = 'tracking@jamonarium.com'; 
                $config['smtp_pass'] = 'D0h8%hv6'; 
*/
                $config['mailtype'] = 'html';
                $config['charset'] = 'utf-8';
                $config['wordwrap'] = TRUE;
                $config['newline'] = "\r\n"; //use double quotes to comply with RFC 822 standard

                $config['mailtype'] = 'html';
                $config['charset'] = 'utf-8';
                $config['wordwrap'] = TRUE;
                $config['newline'] = "\r\n"; //use double quotes to comply with RFC 822 standard

                $this->email->clear();
                $this->email->initialize($config);
/*
                $config['smtp_host'] = 'ssl://send.one.com'; 
                $config['smtp_port'] = '465';
                $config['smtp_user'] = 'info@lolivaret.com'; 
                $config['smtp_pass'] = 'fgfrg$%Yy46bfdg$%Gg5g5'; 


*/
                $from_email=$config['smtp_user'];

                $smtp_user=$config['smtp_user'];
                $smtp_host=$config['smtp_host'];
                $smtp_port=$config['smtp_port'];
                $smtp_pass=$config['smtp_pass'];
                

                //$v1['customer_email']='alex@jamonarium.com';
                
                $customer_email=$v1['customer_email'];
                $reference=$v1['reference'];
                $delivery_firstname=$v1["delivery_firstname"];
                $delivery_lastname=$v1["delivery_lastname"];
                $delivery_country=$v1["delivery_country"];
                $customer_id_language=$v1['customer_id_language'];

                /*
                para incluir cc y bcc (copia y copia oculta)
                cc='tracking@jamonarium.com',
                bcc='mbanolas@gmail.com',
                */
                $this->db->query("INSERT INTO pe_datos_email_tracking SET 
                                    from_email='$from_email',
                                    nombre_empresa='$nombreEmpresa',
                                    to_email='$customer_email',
                                    asunto='$asunto',
                                    reference='$reference',
                                    delivery_firstname='$delivery_firstname',
                                    delivery_lastname='$delivery_lastname',
                                    delivery_country='$delivery_country',
                                    customer_id_language='$customer_id_language',
                                    email_info='$email_info',
                                    telefono_contacto='$telefono_contacto',
                                    web_chat='$web_chat',
                                    facebook_url='$facebook_url',
                                    facebook_nombre='$facebook_nombre',
                                    twitter_nombre='$twitter_nombre',
                                    instagram_url='$instagram_url',
                                    instagram_nombre='$instagram_nombre',
                                    youtube_url='$youtube_url',
                                    youtube_nombre='$youtube_nombre',
                                    blog_url='$blog_url',
                                    twitter_url='$twitter_url',
                                    smtp_user='$smtp_user',
                                    smtp_host='$smtp_host',
                                    smtp_port='$smtp_port',
                                    smtp_pass='$smtp_pass'
                                   
                                    

                                     ");

                $this->email->from($from_email, $nombreEmpresa);
                
                $this->email->to($v1['customer_email']);
                //$this->email->cc('mbanolas@gmail.com');
                //$this->email->bcc('mbanolas@gmail.com');

                $this->email->subject($asunto);
                $this->email->message($mensajeEmail);

                $hoy=date("Y-m-d");
                $this->db->query("UPDATE pe_email_tracking SET fecha_envio='$hoy' WHERE num_pedido='$v'");
/*
                if($this->email->send()){
                    $hoy=date("Y-m-d");
                    $this->db->query("UPDATE pe_email_tracking SET fecha_envio='$hoy' WHERE num_pedido='$v'");
                }else{
                    $this->email->print_debugger(array('headers','subject','body'));
                };
*/
            }

        }

        $dato['autor'] = 'Miguel Angel Bañolas';
        $this->load->view('templates/header.html', $dato);
        $this->load->view('templates/top.php', $dato);
        $this->load->view('enviosTracking.php', $dato);
        $this->load->view('templates/footer.html', $dato);
        
    }

    function cat($datos,$shop){
        $link='https://www.tnt.com/express/es_es/site/home/aplicaciones/tracking.html?cons='.$datos["reference"].'&searchType=REF&source=home_widget';
        $texto='Hola!'.'<br><br>'.
        'Gràcies per confiar en Jamonarium.'.'<br><br>'.
        'Escrivim en referència a la teva comanda <strong>'.$datos["reference"].'</strong>, dirigida a <strong>'.$datos["delivery_firstname"].' '.$datos["delivery_lastname"].'</strong> i amb destí <strong>'.$datos["delivery_country"].'</strong>.<br><br>
        A continuació et proporcionem la pàgina web per a saber on es troba la teva comanda.<br> 
        
        <strong><a href="'.$link.'" target="_blank">'.$link.'</a> </strong><br><br>
        Tingues en compte que la informació del seguiment a vegades s’actualitza al dia següent.<br><br>
        
        Si tens qualsevol dubte o problema relacionat amb l’enviament contacta amb nosaltres:<br><br>
        Escriu-nos un mail: <strong><a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a></strong><br><br>
        Truca’ns per Telèfon (9-17h): <strong>'.$shop["telefono_contacto"].'</strong><br><br> 
        Xateja amb nosaltres des de la web (9-17h): <strong><a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a></strong><br><br>
        
        Desitgem que rebis aviat els productes i els gaudeixis en bona companyia.<br><br>
        Salutacions cordials de tot l’equip de '.$shop["nombre_empresa"].'!<br><br>
        <strong>Dept. Servei Client</strong>, <i>"Estem aquí para ajudar-te"</i>
        '.$shop["telefono_contacto"].'</strong><br>
        <a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a><br>
        <a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a><br><br>
        Segueix-nos:<br>
        <a href="'.$shop["facebook_url"].'">'.$shop["facebook_nombre"].'</a><br> 
        <a href="'.$shop["twitter_url"].'">'.$shop["twitter_nombre"].'</a><br> 
        <a href="'.$shop["instagram_url"].'">'.$shop["instagram_nombre"].'</a><br> 
        <a href="'.$shop["youtube_url"].'">'.$shop["youtube_nombre"].'</a><br> 
        <a href="'.$shop["blog_url"].'">'.$shop["blog_nombre"].'</a><br> 
        ';
        return $texto;
    }

    function esp($datos,$shop){
        $link='https://www.tnt.com/express/es_es/site/home/aplicaciones/tracking.html?cons='.$datos["reference"].'&searchType=REF&source=home_widget';
        $texto='Hola!'.'<br><br>'.
        'Gracias por confiar en Jamonarium.'.'<br><br>'.
        'Te escribimos en referencia a tu pedido <strong>'.$datos["reference"].'</strong>, dirigido a <strong>'.$datos["delivery_firstname"].' '.$datos["delivery_lastname"].'</strong> y con destino <strong>'.$datos["delivery_country"].'</strong>.<br><br>
        A continuación te proporcionamos la página web para saber dónde se encuentra tu pedido.<br> 
        
        <strong><a href="'.$link.'" target="_blank">'.$link.'</a> </strong><br><br>
        Ten en cuenta que la información del seguimiento se actualiza a veces al día siguiente.<br><br>
        
        Si tienes cualquier duda o problema relacionado con el envío contacta con nosotros:<br><br>
        Escríbenos un mail: <strong><a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a></strong><br><br>
        Llámanos por Teléfono (9-17h): <strong>'.$shop["telefono_contacto"].'</strong><br><br>
        Chatea con nosotros desde la web (9-17h): <strong><a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a></strong><br><br>
        
        Esperamos recibas muy pronto los productos y los disfrutes en buena compañía.<br><br>
        ¡Saludos cordiales de todo el equipo de '.$shop["nombre_empresa"].'!<br><br>
        <strong>Dept. Servicio Cliente</strong>, <i>"Estamos aquí para ayudarte"</i>
        '.$shop["telefono_contacto"].'</strong><br>
        <a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a><br>
        <a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a><br><br>
        Síguenos:<br>
        <a href="'.$shop["facebook_url"].'">'.$shop["facebook_nombre"].'</a><br> 
        <a href="'.$shop["twitter_url"].'">'.$shop["twitter_nombre"].'</a><br> 
        <a href="'.$shop["instagram_url"].'">'.$shop["instagram_nombre"].'</a><br> 
        <a href="'.$shop["youtube_url"].'">'.$shop["youtube_nombre"].'</a><br> 
        <a href="'.$shop["blog_url"].'">'.$shop["blog_nombre"].'</a><br> 
        ';
       
        return $texto;
    }

    function fra($datos,$shop){
        $link='https://www.tnt.com/express/es_es/site/home/aplicaciones/tracking.html?cons='.$datos["reference"].'&searchType=REF&source=home_widget';
        $texto='Bonjour!'.'<br><br>'.
        'Merci pour votre confiance en Jamonarium.'.'<br><br>'.
        'Nous vous écrivons en référence à votre commande <strong>'.$datos["reference"].'</strong>, adressée à <strong>'.$datos["delivery_firstname"].' '.$datos["delivery_lastname"].'</strong> et vers <strong>'.$datos["delivery_country"].'</strong>.<br><br>
        Ci-dessous, nous fournissons le site pour savoir où est votre commande.<br> 
        
        <strong><a href="'.$link.'" target="_blank">'.$link.'</a> </strong><br><br>
        A noter que l´information de suivi est mise à jour, parfois le lendemain.<br><br>
        Si vous avez des questions ou des problèmes liés à l´expédition, contactez-nous:<br><br>
        Envoyez-nous un e-mail: <strong><a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a></strong><br><br>
        Appelez-nous par téléphone (9-17h): <strong>'.$shop["telefono_contacto"].'</strong><br><br>
        Chattez avec nous sur le web (9-17h): <strong><a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a></strong><br><br>
        
        Nous espérons recevoir bientôt les produits et profiter de la bonne compagnie.<br><br>
        Salutations de toute l´équipe '.$shop["nombre_empresa"].'!<br><br>
        <strong>Dept. Servicio Cliente</strong>, <i>"Nous sommes là pour vous aider"</i>
        '.$shop["telefono_contacto"].'</strong><br>
        <a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a><br>
        <a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a><br><br>
        Suivez-nous:<br>
        <a href="'.$shop["facebook_url"].'">'.$shop["facebook_nombre"].'</a><br> 
        <a href="'.$shop["twitter_url"].'">'.$shop["twitter_nombre"].'</a><br> 
        <a href="'.$shop["instagram_url"].'">'.$shop["instagram_nombre"].'</a><br> 
        <a href="'.$shop["youtube_url"].'">'.$shop["youtube_nombre"].'</a><br> 
        <a href="'.$shop["blog_url"].'">'.$shop["blog_nombre"].'</a><br> 
        ';
        return $texto;
    }

    function eng($datos,$shop){
        $link='https://www.tnt.com/express/es_es/site/home/aplicaciones/tracking.html?cons='.$datos["reference"].'&searchType=REF&source=home_widget';
        $texto='Hello!'.'<br><br>'.
        'Thank you for trusting Jamonarium.'.'<br><br>'.
        'We write you about your order <strong>'.$datos["reference"].'</strong>, for <strong>'.$datos["delivery_firstname"].' '.$datos["delivery_lastname"].'</strong> shipped to <strong>'.$datos["delivery_country"].'</strong>.<br><br>
        Here the tracking link to follow up your parcel:<br><br>  
        
        <strong><a href="'.$link.'" target="_blank">'.$link.'</a> </strong><br><br>
        Be aware that the information could be refreshed the next day.<br><br> 
        
        In case you have any doubt or problem related to the shipment, do not hesitate to contact us:<br><br>
        Write us an email to: <strong><a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a></strong><br><br>
        Call us (9-17h): <strong>'.$shop["telefono_contacto"].'</strong><br><br>
        Chat with us through our chat in (9-17h): <strong><a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a></strong><br><br>
        
        We hope you receive soon the products and enjoy them in good company.<br><br>
        Greetings from all '.$shop["nombre_empresa"].' team!<br><br>
        <strong>Dept. Customer Service</strong>, <i>"We are here to help you"</i> '.$shop["telefono_contacto"].'</strong><br>
        <a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a><br>
        <a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a><br><br>
        Follow us:<br>
        <a href="'.$shop["facebook_url"].'">'.$shop["facebook_nombre"].'</a><br> 
        <a href="'.$shop["twitter_url"].'">'.$shop["twitter_nombre"].'</a><br> 
        <a href="'.$shop["instagram_url"].'">'.$shop["instagram_nombre"].'</a><br> 
        <a href="'.$shop["youtube_url"].'">'.$shop["youtube_nombre"].'</a><br> 
        <a href="'.$shop["blog_url"].'">'.$shop["blog_nombre"].'</a><br> 
        ';
        return $texto;
    }

    function ita($datos,$shop){
        $link='https://www.tnt.com/express/es_es/site/home/aplicaciones/tracking.html?cons='.$datos["reference"].'&searchType=REF&source=home_widget';
        $texto='Ciao!'.'<br><br>'.
        'Grazie per avere fiducia in Jamonarium.'.'<br><br>'.
        'Ti scriviamo in riferimento al vostro ordine <strong>'.$datos["reference"].'</strong>, indirizzata a <strong>'.$datos["delivery_firstname"].' '.$datos["delivery_lastname"].'</strong> e di destinazione <strong>'.$datos["delivery_country"].'</strong>.<br><br>
        Di seguito forniamo il sito web per scoprire dove il vostro ordine.<br> 
        
        <strong><a href="'.$link.'" target="_blank">'.$link.'</a> </strong><br><br>
        Da notare che la informazioni di monitoraggio è a volte aggiornata il giorno successivo.<br><br>
        Se hai domande o problemi relativi al trasporto, contattaci:<br><br>
        Inviaci una e-mail: <strong><a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a></strong><br><br>
        Chiamaci per telefono (9-17h): <strong>'.$shop["telefono_contacto"].'</strong><br><br>
        Chatta con noi dal web (9-17h): <strong><a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a></strong><br><br>
        
        Speriamo di ricevere presto i prodotti e godere di buona compagnia.<br><br>
        Cordiali saluti da tutto il team '.$shop["nombre_empresa"].'!<br><br>
        <strong>Dept. Servicio Cliente</strong>, <i>"Siamo qui per aiutarti"</i>
        '.$shop["telefono_contacto"].'</strong><br>
        <a href="mailto:'.$shop["email_info"].'">'.$shop["email_info"].'</a><br>
        <a href="'.$shop["web_chat"].'" target="_blank">'.$shop["web_chat"].'</a><br><br>
        Seguici su:<br>
        <a href="'.$shop["facebook_url"].'">'.$shop["facebook_nombre"].'</a><br> 
        <a href="'.$shop["twitter_url"].'">'.$shop["twitter_nombre"].'</a><br> 
        <a href="'.$shop["instagram_url"].'">'.$shop["instagram_nombre"].'</a><br> 
        <a href="'.$shop["youtube_url"].'">'.$shop["youtube_nombre"].'</a><br> 
        <a href="'.$shop["blog_url"].'">'.$shop["blog_nombre"].'</a><br> 
        ';
        return $texto;
    }




    }
        