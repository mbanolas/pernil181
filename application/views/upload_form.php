



<h4>Archivos Boka</h4><br>	
	

<h4 id="error" style="color:red"><?php echo $error;?></h4>


<h4>Seleccionar un archivo Boka para subir</h4>

<?php echo form_open_multipart('upload/do_upload');?>
<div class="container">
   
 <input  class="btn btn-success btn-mini " type="file" name="userfile" style="width: 540px" />



<br /><br />
    

<input class="btn btn-primary btn-mini " id="descargar" type="submit" value="Descargar" />
<div id="esperar" class="hide">
<br /><br /><h4>Esperar</h4>
<img class="img-responsive ajax-loader"   src="<?php echo base_url('images/ajax-loader-w.gif') ?>">
</div>

</div>
</form>

<script>
$(document).ready(function () {

$('#descargar').click(function(){
    $('#esperar').removeClass('hide')
    $('#error').addClass('hide')

})
})
</script>




