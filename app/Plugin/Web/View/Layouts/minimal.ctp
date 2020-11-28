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
?> <style>.vexContentW{ width:90% !important;} .padtb10{ padding-top:10px !important; padding-bottom: 10px !important;}</style><?php
/*
 * Css do sistema
 */
echo $this->Html->css(BS_PLUGIN_CSS . 'spm');

echo $this->fetch('meta');
echo $this->fetch('css');

echo $this->Html->script(BS_PLUGIN_JS . 'jquery');
        

echo $this->fetch('content');

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
echo $this->Html->script(BS_PLUGIN_JS . 'jquery.multi-select');
echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-datepicker');
echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-datepicker.pt-BR');
echo $this->Html->script(BS_PLUGIN_JS . 'periciasmedicas');
echo $this->Html->script(BS_PLUGIN_JS . 'admin');
echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-switch/bootstrap-switch');
echo $this->Html->script(BS_PLUGIN_JS . 'vex/vex.combined');?>
<script>vex.defaultOptions.className = 'vex-theme-os'</script>
<?php
echo $this->fetch('script');
?>