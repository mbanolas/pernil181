



<h4>Archivos PrestaShop</h4>	

<h4 style="color:red" ><?php echo $data['orig_name'] ?><br> <span id="error"><?php echo $error;?></span></h4>
<br>

<?php echo form_open_multipart('upload/do_upload_transportes');?>

<div class="container" >
<input  class="btn btn-success btn-mini " type="file" name="userfile" style="width: 540px" />

<br /><br />

<input class="btn btn-primary btn-mini " id="descargar" type="submit" value="Descargar Transportes" />
<div id="esperar" class="hide">
<br /><br /><h4>Esperar</h4>
<img class="img-responsive ajax-loader"   src="<?php echo base_url('images/ajax-loader-w.gif') ?>">
</div>


</div>
</form>


<script>
$(document).ready(function(){
  
  
  $('#descargar').click(function(){
        $('#esperar').removeClass('hide')
  })

  var error=$('#error').html()
  if(error=="<p>No seleccionaste un archivo para subir.</p>"){
      $('#error').addClass('hide')
  }
  else{
      $('#error').removeClass('hide')
  }
  
  if(error!="<p>No seleccionaste un archivo para subir.</p>"){
        $('.modal-title').html('Información importante')
        $('.modal-body>p').html(error)
        $("#myModal").css('color','red')
        $("#myModal").modal()  
  }
    
})
</script>



