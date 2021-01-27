<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pernil 181</title>
        <!--
        <link href="<?php echo base_url('images/favicon.jpg') ?>" rel="shortcut icon" type="image/x-icon" /> 
        -->
        <?php if (strpos(host(),'localhost:8888')===0) { ?>
       <link href="<?php echo base_url('images/favicon.jpg') ?>" rel="shortcut icon" type="image/x-icon" /> 
    <?php  }else{ ?> 
        <link href="<?php echo base_url('images/favicon_nuevo.jpg') ?>" rel="shortcut icon" type="image/x-icon" /> 
     <?php } ?> 
       <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.css') ?>" media="screen" rel="stylesheet" type="text/css">
       <link href="<?php echo base_url('css/maba.css') ?>" rel="stylesheet" media="screen">
       <link href="<?php echo base_url('css/jquery-ui.min.css') ?>" rel="stylesheet" media="screen">
       
       <script type="text/javascript" src="<?php echo base_url('js/jquery-2.2.3.min.js') ?>"></script>
       <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>  
       <script type="text/javascript" src="<?php echo base_url('js/jquery-ui.min.js') ?>"></script>
       <script type="text/javascript" src="<?php echo base_url('js/maba.js') ?>"></script>
       <script type="text/javascript" src="<?php echo base_url('js/mabaTables.js') ?>"></script>

    
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
       
       
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>

</head>
<body>
	
	