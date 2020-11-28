<div class="row">
    <div class="col-md-12">
        <div data-collapsed="0" class="panel">
            <header class="panel-heading">
                <div class="row">
                    <div class="col-md-10">
                        <div class="panel-title">Relatório Personalizado</div>
                    </div>
                </div>
            </header>
            <div class="panel-body">
            	<?=
                $this->Form->create('Relatorio', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'type' => 'post',
                    'data-acao' => 'consultaPersonalizada',
                    'id' => 'formularioRelatorio',
                    'url' => array('controller' => 'Relatorio', 'action' => 'index')
                ));
                ?>

                <div class="row" id="parte1">
                    <div class="col-md-12 form-group">
                        <?php echo $this->element('relatorio_personalizado/parte1'); ?>
                    </div>
                </div>

              	<div class="row" id="parte2" style="display: none">
                    <div class="col-md-12 form-group">
                        <?php echo $this->element('relatorio_personalizado/parte2'); ?>
                    </div>
                </div>

                <div class="row" id="parte3" style="display: none">
                    <div class="col-md-12 form-group">
                        <?php echo $this->element('relatorio_personalizado/parte3'); ?>
                    </div>
                </div>

                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel" id="grid">
        </section>
    </div>
</div>

<script type="text/javascript">
    
</script>

<?php  echo $this->Html->script('Web.relatorio_personalizado', array('block' => 'script'));  ?>