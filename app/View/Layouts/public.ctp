<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="<?php echo Router::url('/', true) ?>img/favicon.png">
    <title>Login</title>
    <?php 
    	echo $this->Html->css ( 'bs3/css/bootstrap.min' );
    	echo $this->Html->css ( 'bootstrap-reset' );
    	echo $this->Html->css ( 'font-awesome/css/font-awesome' );
    	echo $this->Html->css ( 'style' );
        echo $this->Html->css ( 'style-login' );
    	echo $this->Html->css ( 'style-responsive' );
    	echo $this->Html->css ( 'spm' );
    ?>
</head>

  <body class="login-body">

    <div class="container">

      	 <?php
			echo $this->Session->flash ();
			echo $this->Session->flash ( 'auth', array (
					'element' => 'flash_alert' 
			) );
			echo $this->fetch ( 'content' );
		?>

    </div>
    <?php 
    	echo $this->Html->script ( 'jquery' );
    	echo $this->Html->script ( 'bs3/bootstrap.min' );
    	echo $this->Html->script ( 'jquery.maskedinput.min' );
    	echo $this->Html->script ( 'jquery.growl' );
    	echo $this->Html->script ( 'periciasmedicas' );
    ?>

  </body>
</html>
