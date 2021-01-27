<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Pernil181 </title>
       <!--<link href="<?php //echo base_url('images/favicon_nuevo_.jpg') ?>" rel="shortcut icon" type="image/x-icon" /> -->
       <?php if (strpos(host(),'localhost:8888')===0) { ?>
       <link href="<?php echo base_url('images/favicon.jpg') ?>" rel="shortcut icon" type="image/x-icon" /> 
    <?php  }else{ ?> 
        <link href="<?php echo base_url('images/favicon_nuevo.jpg') ?>" rel="shortcut icon" type="image/x-icon" /> 
     <?php } ?> 
     
     <link rel="manifest" href="<?php echo base_url('manifest.json') ?>"> 
  
       <link href="<?php echo base_url('css/bootstrap.css') ?>" media="screen" rel="stylesheet" type="text/css">
       <link href="<?php echo base_url('css/jquery.dataTables.css') ?>" media="screen" rel="stylesheet" type="text/css">
       <link href="<?php echo base_url('css/dataTables.tableTools.css') ?>" media="screen" rel="stylesheet" type="text/css">
       <link href="<?php echo base_url('css/bootstrap-theme.min.css') ?>" media="screen" rel="stylesheet" type="text/css">
   
       <!--
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
       <link rel="stylesheet" href="<?php echo base_url('css/bootstrap-submenu.min.css') ?>">
       -->
       
       <link href="<?php echo base_url('css/bootstrap-select.min.css') ?>" media="screen" rel="stylesheet" type="text/css">


       <link href="<?php echo base_url('css/maba.css') ?>" rel="stylesheet" media="screen">


       
       <script type="text/javascript" src="<?php echo base_url('js/jquery.min.js') ?>"></script>
       <script type="text/javascript" src="<?php echo base_url('js/jquery.dataTables.min.js') ?>"></script>
       <script type="text/javascript" src="<?php echo base_url('js/dataTables.tableTools.min.js') ?>"></script>
       <script type="text/javascript" src="<?php echo base_url('js/bootstrap.min.js') ?>"></script>  
       
       <script type="text/javascript" src="<?php echo base_url('js/java-jquery-ui.js') ?>"></script>
       <script type="text/javascript" src="<?php echo base_url('js/bootstrap-select.min.js') ?>"></script>

       <script type="text/javascript" src="<?php echo base_url('js/numeral.min.js') ?>"></script> 
       <script type="text/javascript" src="<?php echo base_url('js/maba.js') ?>"></script>
       <script type="text/javascript" src="<?php echo base_url('js/mabaTables.js') ?>"></script>
      
       <!--
       <script src="https://code.jquery.com/jquery-2.2.4.min.js" defer></script>
       <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" defer></script>
       <script src=""<?php echo base_url('js/bootstrap-submenu.min.js') ?> defer></script>
       -->

        <?php foreach ($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

        <?php endforeach; ?>
        <?php foreach ($js_files as $file): ?>

            <script src="<?php echo $file; ?>"></script>
        <?php endforeach; ?>
        
    </head>
    
    <body>