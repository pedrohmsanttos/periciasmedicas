<?php


$formCreate['class'] = ($formDisabled) ? "formVisualizacao" : "";
echo $this->Form->create($controller, $formCreate);
?>

<?php
echo $this->Form->input('usuario_perito_credenciado', array(
    'type' => 'hidden',
    'id' => 'usuario_perito_credenciado',
    'value' => USUARIO_PERITO_CREDENCIADO,
    'disabled' => true
));

echo $this->Form->input('usuario_perito_servidor', array(
    'type' => 'hidden',
    'id' => 'usuario_perito_servidor',
    'value' => USUARIO_PERITO_SERVIDOR,
    'disabled' => true
));

echo $this->Form->input('usuario_interno', array(
    'type' => 'hidden',
    'id' => 'usuario_interno',
    'value' => USUARIO_INTERNO,
    'disabled' => true
));

echo $this->Form->input('usuario_servidor', array(
    'type' => 'hidden',
    'id' => 'usuario_servidor',
    'value' => USUARIO_SERVIDOR,
    'disabled' => true
));
?>

<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('Usuario')))); ?>
            <div class="panel-body">
                <div class="row">
                <div class="col-md-9">
                    <div class="col-md-5 form-group">
                        <?php
                        echo
                        $this->Form->input('tipo_usuario_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'id' => 'UsuarioTipoUsuario',
                            'options' => $tipoUsuario,
                            'label' => __('usuario_label_tipo_usuario') . $isRequerid,
                            'empty' => __('label_selecione'),
                            'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                        ?>
                    </div>

                    <div class="col-md-5 form-group">
                        <label for="UsuarioHabilitarAlteracaoSenha"><?php echo __('label_habilitar_senha') ?></label>
                        <br/>
                        <?php
                        echo
                        $this->Form->input('habilitar_alteracao_senha', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control checkOnOFF',
                            'label' => false,
                            'data-size' => 'small',
                            'data-on-text' => 'Sim',
                            'data-off-text' => 'Não',
                            'checked' => $checkedHabilitarSenha,
                            'disabled' => ($formDisabled || isset($formAlteracaoDados))
                            )
                        );
                        ?>
                    </div>
                    
                </div>
                    <div class="col-md-5 form-group displayNone" id="unidadeAtendimentoUsuario">
                        <?php
                        echo
                        $this->Form->input('unidade_atendimento_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'id' => 'UsuarioUnidadeAtendimento',
                            'options' => $unidadeAtendimento,
                            'label' => __('usuario_label_unidade_atendimento'),
                            'empty' => __('label_selecione'),
                            'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('usuario_label_perfis') ?> *</legend>
                            <?php
                            echo $this->Form->select('Perfil', $perfis, array(
                                'multiple' => 'checkbox',
                                'disabled' => ($formDisabled || isset($formAlteracaoDados))
                            ));
                            ?>
                        </fieldset>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading tab-bg-dark-navy-blue">
                <ul class="nav nav-tabs nav-justified ">
                    <li class="active">
                        <a data-toggle="tab" href="#dados-pessoais">
                            <?php echo __('usuario_label_dados_pessoais') ?>
                        </a>
                    </li>
                    <li id="abaDadosProfissionais" class="displayNone">
                        <a data-toggle="tab" href="#dados-profissionais">
                            <?php echo __('usuario_label_dados_profissionais') ?>
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#dependentes" class="contact-map">
                            <?php echo __('usuario_label_dependentes') ?>
                        </a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content tasi-tab">
                    <div id="dados-pessoais" class="tab-pane active">
                        <div class="row">
                            <? echo $this->element('aba-usuario-dados-pessoais'); ?>
                        </div>
                    </div>
                    <div id="dados-profissionais" class="tab-pane displayNone">
                        <div class="row">
                            <?= $this->element('aba-usuario-dados-profissionais'); ?>
                        </div>
                    </div>
                    <div id="dependentes" class="tab-pane ">
                        <div class="row">
                            <?= $this->element('aba-usuario-dependentes'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <div class="panel-body">
                <?php if ($acao == Configure::read('ACAO_ALTERAR_DADOS')): ?>
                    <div class="row float-right btn-edit">
                        <i class="btn fa fa-check estiloBotao btn-success" id="ajaxAdd" 
                           data-url="<?php
                           echo Router::url(array('controller' => $controller,
                               'action' => $this->params['action']), true);
                           ?>"><?= __('bt_salvar') ?></i>
                    </div>
                <?php else: ?>
                    <?= $this->element('botoes-default-cadastro', ['ajax' => true]); ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>



<script>
$(function(argument) {
  $('.checkOnOFF').bootstrapSwitch();
})
</script>


<?= $this->Form->end(); ?>
<?php
        //echo $this->Html->script('Admin.usuario', array('block' => 'script'));
        echo $this->Html->script('Web.usuario', array('block' => 'script'));
?>