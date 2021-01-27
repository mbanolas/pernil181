
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 echo "Saludos";
 echo "<button id='boton'>Enviar notificación</button>";

 ?>
 <script>
    var boton=document.getElementById("boton");
    boton.addEventListener('click',function(){
        // alert('pulsado envio notificacion')
        notify()
    })

    function notify(){
        if(!("Notification" in window)){
            alert ('Tu navegador no permite notificaciones')
        }else if(Notification.permission === 'granted'){
            var notification= new Notification('Mi primera notificación')
        }else if(Notification.permission !=="denied"){
            Notification.requestPermission(function(permision){
                if(Notification.permission === "granted"){
                    var notification = new Notofication("Hola mundo !")
                }
            })
        }
    }
 </script>

