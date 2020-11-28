<!DOCTYPE html>
<html lang="pt">

<head>
<?php echo $this->Html->charset(); ?>
<title><?= $title_for_layout?></title>
    <?php
    if(SHOW_ERROR){
        echo $this->fetch('content');
    }else{
       echo $this->Html->css ( BS_PLUGIN_CSS.'style-erro' );
    }
    ?>
</head>




<body>


</body>
</html>
