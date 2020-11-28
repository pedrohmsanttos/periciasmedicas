<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="ThemeBucket">
        <link rel="shortcut icon" href="<?php echo Router::url('/', true) ?>img/favicon.png">
        <title><?php echo ($this->request->action == "alterarSenhaLogin") ? "Alteração de Senha" : "Login" ?></title>
        <?php
        echo $this->Html->css(BS_PLUGIN_CSS . 'bs3/css/bootstrap.min');
        echo $this->Html->css(BS_PLUGIN_CSS . 'bootstrap-reset');
        echo $this->Html->css(BS_PLUGIN_CSS . 'font-awesome/css/font-awesome');
        echo $this->Html->css(BS_PLUGIN_CSS . 'style');
        echo $this->Html->css(BS_PLUGIN_CSS . 'style-login');
        echo $this->Html->css(BS_PLUGIN_CSS . 'style-responsive');
        echo $this->Html->css(BS_PLUGIN_CSS . 'spm');
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery');
        ?>
    </head>

    <body>
    <div class="modal" style="justify-content: center"><!-- Place at bottom of page -->
        <div style="display: flex; flex-direction: column; justify-content: center">
                <span style="margin-top: 65px; font-size: 15px; font-weight: bold; ">
                    Aguarde processando informações
                </span>
        </div>
    </div>
        <div class="container">

            <?php
            echo $this->Session->flash();
            echo $this->Session->flash('auth', array(
                'element' => 'flash_alert'
            ));
            echo $this->fetch('content');
            ?>

        </div>
        <?php
        
        echo $this->Html->script(BS_PLUGIN_JS . 'bs3/bootstrap.min');
        echo $this->Html->script(BS_PLUGIN_JS . 'jquery.maskedinput.min');
        echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-growl/bootstrap-growl.min');
        echo $this->Html->script(BS_PLUGIN_JS . 'admin');
        echo $this->Html->script(BS_PLUGIN_JS . 'periciasmedicas');
        echo $this->fetch('script');
        ?>

    </body>
</html>
