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
            <?php echo 'SPM - ' . __($title_for_layout); ?>
        </title>
        <?php
        echo $this->Html->meta('icon');
        /*
         * css obrigatórios para funcionamento do tema 
         */
        echo $this->Html->css(BS_PLUGIN_CSS . 'bs3/css/bootstrap.min');
        echo $this->Html->css(BS_PLUGIN_CSS . 'bootstrap-reset');
		echo $this->Html->css(BS_PLUGIN_CSS . 'jquery-ui/jquery-ui-1.10.1.custom.min');
        echo $this->Html->css(BS_PLUGIN_CSS . 'font-awesome/css/font-awesome');
        echo $this->Html->css(BS_PLUGIN_CSS . 'jvector-map/jquery-jvectormap-1.2.2');
        echo $this->Html->css(BS_PLUGIN_CSS . 'clndr');
        echo $this->Html->css(BS_PLUGIN_CSS . 'style');
        echo $this->Html->css(BS_PLUGIN_CSS . 'style-responsive');

        /*
         * Css dos plugins
         */
        echo $this->Html->css(BS_PLUGIN_CSS . 'jquery-checktree');
        echo $this->Html->css(BS_PLUGIN_CSS . 'multi-select');
        echo $this->Html->css(BS_PLUGIN_CSS . 'datepicker');
        echo $this->Html->css(BS_PLUGIN_CSS . 'bootstrap-switch/bootstrap-switch');
        echo $this->Html->css(BS_PLUGIN_CSS . 'vex/vex');
        echo $this->Html->css(BS_PLUGIN_CSS . 'vex/vex-theme-os');
        // echo $this->Html->css(BS_PLUGIN_CSS . 'rateyo/jquery.rateyo.min');



        /*
         * Css do sistema
         */
        echo $this->Html->css(BS_PLUGIN_CSS . 'spm');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery');

        echo $this->Html->script(BS_PLUGIN_JS . 'jquery.quicksearch');
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery.multi-select');
        
        ?>
        <style>.vexContentW{ width:90% !important;} .padtb10{ padding-top:10px !important; padding-bottom: 10px !important;}</style>
    </head>
    <body>
        <div class="modal" style="justify-content: center"><!-- Place at bottom of page -->
            <div style="display: flex; flex-direction: column; justify-content: center">
                <span style="margin-top: 65px; font-size: 15px; font-weight: bold; ">
                    Aguarde processando informações
                </span>
            </div>
        </div>
        <section id="container">
            <!--header start-->
            <header class="header fixed-top clearfix">
                <!--logo start-->
                <div class="brand">

                    <a href="<?php echo Router::url('/web'); ?>" class="logo"> 
                        <?php //echo $this->Html->image('Admin.logo-2.png', array('alt' => 'SPM', 'border' => '0')); ?>
                        <?php echo $this->Html->image('Web.logo-2.png', array('alt' => 'SPM', 'border' => '0')); ?>
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
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <?php //echo $this->Html->image('Admin.foto-usuario.jpg', array('alt' => '', 'border' => '0')); ?>
                                <?php echo $this->Html->image('Web.foto-usuario.jpg', array('alt' => '', 'border' => '0')); ?>

                                <span class="username" <?=(strlen($userData['nome']) > 60) ? "title='".$userData['nome']."'" : ""?>>
                                    <?php echo (strlen($userData['nome']) > 60) ? substr($userData['nome'], 0,60).'...' : $userData['nome']; ?>
                                    <?php // echo $userData['nome'] ?>
                                </span>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu extended logout">
                                <li>
                                    <?php
                                    echo $this->Html->link(__("menu_alterar_dados"), array(
                                        'controller' => 'usuario',
                                        'action' => 'alterarDados',
                                        $userData['id']
                                            )
                                    );
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    echo $this->Html->link(__("menu_sair"), array(
                                        'controller' => 'usuario',
                                        'action' => 'logout'
                                            )
                                    );
                                    ?>
                                </li>
                            </ul></li>
                    </ul>
                    <!--search & user info end-->
                </div>
            </header>
            <!--header end-->
            <?php
            echo $this->element('menu');
            ?>
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">
                    <!--main content goes here-->
                    <?php
                    echo $this->Session->flash();
                    echo $this->Session->flash('auth', array(
                        'element' => 'flash_alert'
                    ));
                    echo $this->fetch('content');
// 												echo $this->element('sql_dump');
                    ?>
                </section>
            </section>
            <!--main content end-->
        </section>
        <?php
        /**
         * Scripts necessários para funcionamento de template
         */
        // echo $this->Html->script(BS_PLUGIN_JS . 'jquery');
        echo $this->Html->script(BS_PLUGIN_JS . 'bs3/bootstrap.min');
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery-ui/jquery-ui-1.10.1.custom.min');
		echo $this->Html->script(BS_PLUGIN_JS . 'jquery.cookie');
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery.hoverIntent.minified');
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery.dcjqaccordion.2.7');
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery.scrollTo.min');
        echo $this->Html->script(BS_PLUGIN_JS . 'jQuery-slimScroll-1.3.0/jquery.slimscroll');
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery.nicescroll');
        echo $this->Html->script(BS_PLUGIN_JS . 'scripts');

        /**
         * Scripts necessários para funcionamento de plugins
         */
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery.maskedinput.min');
        echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-growl/bootstrap-growl.min.js');
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery-checktree');
        
        echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-datepicker');
        echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-datepicker.pt-BR');
        echo $this->Html->script(BS_PLUGIN_JS . 'periciasmedicas');
        echo $this->Html->script(BS_PLUGIN_JS . 'admin');
        echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-switch/bootstrap-switch');
        echo $this->Html->script(BS_PLUGIN_JS . 'vex/vex.combined');
        // echo $this->Html->script(BS_PLUGIN_JS . 'rateyo/jquery.rateyo');
        ?>
        <script>vex.defaultOptions.className = 'vex-theme-os'</script>
        <?php
        echo $this->fetch('script');

        $this->Html
        ?>
        <script>
            var linkVerify = '<?=Router::url(array('action' => 'verify', 'controller' => 'Usuario' , 'plugin' => 'web'));?>';
            setInterval(function(){
                $.ajax({
                    global:false,
                    method:'get',
                    url:linkVerify,
                    error:function(){
                        location.reload();
                    }
            }) }, 90000); //a cada 1 min
        </script>
    </body>
</html>
