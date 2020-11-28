<?php
App::import("Lib", "Util");
Util::temPermissao('Usuario.consultar');
?>
<section class="col-lg-12">
    <?= $this->Html->image('Web.marca-dashboard.png',
        array('alt' => 'SPM', 'border' => '0', 'class'=>'marca-dagua')); 
    ?>
</section>
<?php if( in_array($_SERVER['SERVER_ADDR'], array('200.196.163.126', '200.196.163.122'))): ?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-82326704-1', 'auto');
    ga('send', 'pageview');
</script>
<?php endif; ?>