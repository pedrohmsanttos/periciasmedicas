
<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">
            <header class="panel-heading"> 
                <div class="row">
                    <div class="col-md-10">
                        <div class="panel-title">Publicação <?=$publicacaoId ?></div>
                    </div>
                </div>

            </header>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6"><b>Diretor/Presidente:</b> <?=$diretor_presidente?></div>
                </div>
                <?=
                $this->Form->create('Atendimento', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'type' => 'get',
                    'id' => 'formularioConsulta',
                  //  'data-acao' => 'consultarPublicacao',
                    'url' => array('controller' => 'Atendimento', 'action' => 'consultarProcessosPublicacao')
                ));
                ?>
            </div>
        </div>
    </div>
    <div id="grid">
        <?php echo $this->element('consultar_processos_publicacao') ?>
    </div>
</div>
<? //echo $this->Html->script('Admin.processos', array('block' => 'script')); ?>
<? echo $this->Html->script('Web.processos', array('block' => 'script')); ?>
