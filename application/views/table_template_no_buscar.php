<style>
    
h1 {
		color: #444;
		background-color: transparent;
		
		font-size: 30px;
		font-weight: normal;
		margin: 0 0 14px 0;
		
	}
        
    div.title_top{
                color: #444;
		background-color: transparent;
		border-bottom: 2px solid #D0D0D0;
		/*font-size: 19px; */
		font-weight: normal;
		margin: 15px 0 14px 0;
		
            
            
            color:red;
        }
        
        div.title_2{
                color: #444;
		background-color: transparent;
		border-bottom: 2px solid #D0D0D0;
		/*font-size: 24px;*/
		font-weight: normal;
		margin: 46px 0 14px 0;
		
            
            
            color:red;
        }
        
        nav#mainNav ul {
    padding: 0 /*0 0 0 1.5rem*/;
    background: #BD236C;
    float: right;
    margin: 0;
    width: 100%;
}
ol, ul, dl, address {
    margin-bottom: 1em;
    font-size: 1em;
}
nav#mainNav {
    max-width: 100%;
}
article, aside, aside2, details, figcaption, figure, footer, header, hgroup, nav, section {
    display: block;
}

nav#mainNav ul li, #tools ul li {
    float: left;
    list-style-type: none;
    padding: 1em .5em 1em .5em;
}
 
}

nav#mainNav ul li a {
    padding: 0 1.5em;
}
 
 a:hover {
    color:white;
}

div.flexigrid{
    font-size: 14px;
}
.ftitle{
    font-size: 20px;
   
}
.table-label {
    background: #DDD;
    width: 100%;
    padding: 5px;
    text-align: left;
    font-size:24px;
    font-weight:bold;
}


#gcrud-search-form > table > tbody > tr > td{
        padding-left:5px;
        padding-right:5px;
        padding-top:7px;
}
#gcrud-search-form > table > tbody > tr > td:nth-child(1){
    padding:0px;
    padding-right:5px;
}
#gcrud-search-form > table > thead{
    
    border-top: 2px solid black;
    border-bottom: 3px solid black !important;
}

</style>


<?php //para incluir título en cabecera tabla
    $titulo=isset($titulo)?$titulo:'Sin Título' ;
    $col_bootstrap=isset($col_bootstrap)?$col_bootstrap:10;  ?>
    <input type="hidden" id="titulo" value="<?php echo $titulo ?>">
         
    <div style='height:20px;'></div>  
    <div class="container">
        <div class="row">
            <div class="col-xs-<?php echo $col_bootstrap ?>">
                <div>
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        
$(document).ready(function(){
    //oculta fila con buscar
    $('#gcrud-search-form > table > thead > tr.filter-row.gc-search-row').addClass('hide')

    $('table').addClass('table-striped')

    $('.header-tools > div:first').addClass('hide')

    $('div.container').addClass('container-fluid')
    $('div.container-fluid').removeClass('container') 
    $('div.container-fluid').css('margin', '3px 10px') 
        
 $('#quickSearchBox').attr('hidden','hidden')      
   
 $('[rel="peso_real"]').removeClass('text-left')
   $('[rel="peso_real"]').addClass('text-right') 
   $('[rel="tarifa_venta"]').removeClass('text-left')
   $('[rel="tarifa_venta"]').addClass('text-right') 
   $('[rel="precio_ultimo"]').removeClass('text-left')
   $('[rel="precio_ultimo"]').addClass('text-right') 
   $('[rel="descuento_1_compra"]').addClass('text-right')      
   $('[rel="margen_real_producto"]').addClass('text-right')      
         
      function load() {
        var titulo=$('#titulo').val()
        $('div.ftitle').html(titulo)
        };
        
    window.onload = load;
    })
    </script>