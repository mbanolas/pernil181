<style>
    td{
        padding-left:10px;
    }
    td:nth-child(2){
        color:blue;
        text-align: left;
    } 
    th{
        color:blue;
    }
    th:nth-child(1), th:nth-child(2){
      text-align: left;   
    }
    .container{
        width:auto;
    }
    td{
        border-bottom: 1px solid lightgrey;
    }
    h4{
        display:inline;
        margin-top:20px;
    }
    #bExcel{
       position: absolute;
       right: 10px;
       width: 100px;
       padding-top:10px;
     
    }
    
    #t{
        margin-top:10px;
         margin-bottom:20px;
    }
    #t0{
        margin-top:20px;
    }
    #t1{
         position: absolute;
       right: 15px;
    }
   
    

</style>
<?php echo form_open('stocks/stocksMinimosExcel', array('class'=>"form-horizontal", 'role'=>"form")); ?>

<input class="hide" type="text" id="lineas" name="lineas" value="" >
<div id="t">
    <span id="t0"><h4>Ventas mensuales de los 3 meses considerados para calcular el promedido mensual y establecer el stock mínimo</h4></span>
    <h4>Establecidos en: <?php echo $mes_stocks_minimos ?></h4>
    <span id="t1"><input class="btn btn-default" type="submit" id="bajarExcel" value="Bajar Excel"></span>
</div>
<?php echo form_close(); ?>


    <?php
    
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
        foreach ($lineas['Código 13'] as $k1 => $v1) {
            echo '<th>' . $v1 . '</th>';
        }
        echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($lineas as $k => $v) {
        if($k==0) continue;
        echo '<tr>';
        foreach ($v as $k1 => $v1) {
            echo '<td>' . $v1 . '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
?>

<script>
    $(document).ready(function () {
       
        $('#bajarExcel').click(function(){
            console.log('#bajarExcel')
            var lineas='<?php echo json_encode($lineas) ?>'
            $('#lineas').val(lineas)
           
        })
        

    })
</script>


    