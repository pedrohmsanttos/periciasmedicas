<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
	echo $this->Html->meta ( 'icon' );
	
	echo $this->Html->css ( 'bs3/css/bootstrap.min' );
	echo $this->Html->css ( 'jquery-ui/jquery-ui-1.10.1.custom.min' );
	echo $this->Html->css ( 'bootstrap-reset' );
	echo $this->Html->css ( 'font-awesome/css/font-awesome' );
	echo $this->Html->css ( 'jvector-map/jquery-jvectormap-1.2.2' );
	echo $this->Html->css ( 'clndr' );
	echo $this->Html->css ( 'style' );
	echo $this->Html->css ( 'style-responsive' );
	echo $this->Html->css ( 'spm' );
	
	echo $this->fetch ( 'meta' );
	echo $this->fetch ( 'css' );
	echo $this->fetch ( 'script' );
        echo $this->Html->script('https://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
	?>
</head>
<body>
	<section id="container">
		<!--header start-->
		<header class="header fixed-top clearfix">
			<!--logo start-->
			<div class="brand">
				<a href="<?php echo Router::url('/home/index'); ?>" class="logo"> 
                    <?php echo $this->Html->image('logo-2.png', array('alt' => 'SPM', 'border' => '0')); ?>
				</a>
				<div class="sidebar-toggle-box">
					<div class="fa fa-bars"></div>
				</div>
			</div>
			<!--logo end-->

			<div class="nav notify-row" id="top_menu"></div>
			<div class="top-nav clearfix">
				<!--search & user info start-->
				<ul class="nav pull-right top-menu">
					<li class="dropdown"><a data-toggle="dropdown"
						class="dropdown-toggle" href="#">  <span class="username"><?php echo $userData['nome']?></span>
							<b class="caret"></b>
					</a>
						<ul class="dropdown-menu extended logout">
							<li>
								<?php 
									echo $this->Html->link(__("Sair"),
											array(
													'controller' => 'usuarios',
													'action' => 'logout'
											)
									);
								?>
						</ul></li>
				</ul>
				<!--search & user info end-->
			</div>
		</header>
		<!--header end-->
		<?php
		echo $this->element ( 'menu' );
		?>
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper">
				<!--main content goes here-->
         		 <?php
												echo $this->Session->flash ();
												echo $this->Session->flash ( 'auth', array (
														'element' => 'flash_alert' 
												) );
												echo $this->fetch ( 'content' );
// 												echo $this->element('sql_dump');
												?>
      		</section>
		</section>
		<!--main content end-->
	</section>
	<?php
	echo $this->Html->script ( 'jquery' );
	echo $this->Html->script ( 'jquery-ui/jquery-ui-1.10.1.custom.min' );
	echo $this->Html->script ( 'bs3/bootstrap.min' );
	echo $this->Html->script ( 'jquery.cookie' );
	echo $this->Html->script ( 'jquery.hoverIntent.minified' );
	echo $this->Html->script ( 'jquery.dcjqaccordion.2.7' );
	echo $this->Html->script ( 'jquery.scrollTo.min' );
	echo $this->Html->script ( 'jQuery-slimScroll-1.3.0/jquery.slimscroll' );
	echo $this->Html->script ( 'jquery.nicescroll' );
	echo $this->Html->script ( 'scripts' );
	echo $this->Html->script ( 'jquery.maskedinput.min' );
	echo $this->Html->script ( 'jquery.growl' );
	echo $this->Html->script ( 'periciasmedicas' );
	?>
</body>
</html>
