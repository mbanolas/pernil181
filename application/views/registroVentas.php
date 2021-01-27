<?php 

?>
<script>
$(document).ready(function () {

<?php if($error) { ?>
    $('#myModal').css('color','red')
<?php } else { ?>
    $('#myModal').css('color','black')
<?php } ?>
$('.modal-title').html('Información')
$('.modal-body>p').html("<?php echo $mensaje ?>")
$("#myModal").modal()  
})
</script>