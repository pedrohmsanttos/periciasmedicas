<?= $this->Form->create($controller, $formCreate);

echo $this->Form->input('baseUrlDefault', array(
    'type' => 'hidden',
    'id' => 'baseUrlDefault',
    'data-url' => Router::url('/web/UnidadeAtendimento/', true),
    'disabled' => true
));



?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('UnidadeAtendimento')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 form-group">
                        <?php
                        echo $this->Form->input('nome', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('unidade_atendimento_label_nome') . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?=
                        $this->Form->input('cnpj', array('maxlength' => 18,
                            'label' => __('unidade_atendimento_label_cnpj'). $isRequerid,
                            'class' => 'form-control cnpj',
                            'disabled' => $formDisabled));
                        ?>
                    </div>
                </div>
                <?php
                echo $this->element('componente_endereco', array('idComboMunicipio' => 'idUnidadeAtendimentoMunicipio',
                    'municipios' => (isset($municipios)) ? $municipios : []));
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('unidade_atendimento_label_municipios_proximos') ?></legend>
                            <div class="row">
                                <div class="col-md-6 form-group" style="width: 55%;">
                                    <?php echo $this->Form->label(null, __('unidade_atendimento_label_municipios') . $isRequerid); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $this->Form->label(null, __('unidade_atendimento_label_escolhidos') . $isRequerid); ?>
                                </div>
                            </div>
                            <?= $this->element('componente_acoes_pickList', array("target" => 'picklistMunicipios'))?>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('MunicipioProximo', array('options' => $municipiosUnidade,
                                        'multiple' => 'multiple',
                                        'id' => 'picklistMunicipios',
                                        'class' => 'sigas_multi_select alturaPickList',
                                        'disabled' => $formDisabled,
                                        'div' => array('class' => 'form-group multi-select '),
                                        'label' => false));
                                    ?>

                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-8">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('unidade_atendimento_label_associar_cids') . $isRequerid ?></legend> -->
                           <!-- <div class="row">
                                <div class="col-md-6 form-group" style="width: 55%;">
                                    <?php echo $this->Form->label(null, __('unidade_atendimento_label_cids_disponiveis') . $isRequerid); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $this->Form->label(null, __('unidade_atendimento_label_cids_selecionados')); ?>
                                </div>
                            </div>
                            <?= $this->element('componente_acoes_pickList', array("target" => 'pickListCid'))?>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('Cid', array('options' => $cids,
                                        'multiple' => 'multiple',
                                        'id' => 'pickListCid',
                                        'class' => 'sigas_multi_select alturaPickList',
                                        'disabled' => $formDisabled,
                                        'div' => array('class' => 'form-group multi-select '),
                                        'label' => false));
                                    ?>

                                </div>
                            </div>-->
                            <!-- <div class="row">
                                <div class="col-md-15 form-group">
                                    <?
                                    if(!isset($cidsSelecionados)){
                                        $cidsSelecionados = '';
                                    }
                                   echo  $this->Form->input('Cid', array('div' => array('class' => 'form-group'),
                                        'type' => 'select',
                                        'class' => 'tokenize-callable-cid',
                                        'multiple' => true,
										'requerid' => true,
                                        'options' => $cidsSelecionados));
                                    ?>

                                </div>
                            </div> -->
                        <!-- </fieldset>
                    </div>
                </div> -->




                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('unidade_atendimento_label_responsavel') ?></legend>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <?php
                                    $responsavel = '';
                                    if (isset($this->request->data) && isset($this->request->data['UnidadeAtendimento']['responsavel_id'])) :
                                        $responsavel = $this->request->data['UnidadeAtendimento']['responsavel_id'];
                                    endif;

                                    $urlAutoCompleteNome = Router::url(array('controller' => "UnidadeAtendimento", 'action' => 'getNomeResponsavel'), true);

                                    echo $this->Form->input('responsavel_id', array(
                                        'type' => 'hidden',
                                        'id' => 'hiddenResponsavelId',
                                        'value' => $responsavel,
                                        'disabled' => $formDisabled
                                    ));
                                    echo $this->Form->input('nome_responsavel', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'id' => 'inputNomeResponsavel',
                                        'maxlength' => 255,
                                        'data-url' => $urlAutoCompleteNome,
                                        'data-telefone' => true,
                                        'class' => 'form-control',
                                        'label' => __('unidade_atendimento_label_responsavel_nome'),
                                        'disabled' => $formDisabled
                                    ));
                                    ?>

                                </div>
                                <div class="col-md-6 form-group">
                                    <?php
                                    echo $this->Form->input('telefone_trabalho', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'id' => 'inputTelefoneResponsavel',
                                        'class' => 'form-control telefone',
                                        'label' => __('unidade_atendimento_label_responsavel_telefone_trabalho'),
                                        'disabled' => $formDisabled
                                    ));
                                    ?>

                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div> <!-- end row RESPONSAVEL -->

                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('CIDs') ?></legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Deseja associar todos os CIDs?</label>
                                        <?php
                                        echo $this->Form->input('associar_cids', array(
                                            'type' => 'checkbox',
                                            'label' => false,
                                            'div' => array('class' => 'form-group'),
                                            'class' =>false,
                                            'disabled' => $formDisabled
                                        ));
                                        if( isset($hasAllCids) && !empty($hasAllCids) ) { ?><script>$('#UnidadeAtendimentoAssociarCids').attr('checked', true);</script><?php } ?>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('Atendimento domiciliar') ?></legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Unidade de Atendimento permite atendimento domiciliar?</label>
                                        <?php
                                        echo $this->Form->input('atendimento_domicilio', array(
                                            // 'type' => 'checkbox',
                                            'label' => false,
                                            'div' => array('class' => 'form-group'),
                                            'class' =>false,
                                            'disabled' => $formDisabled
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div> <!-- end row ATENDIMENTO DOMICILIAR -->
                <div class="row col-md-12">
                    <?= $this->Form->input('cids', array('type' => 'text', 'style' => 'display:none', 'label' => false)); ?>
                    <?= $this->Form->input('municipiosProximos', array('type' => 'text', 'style' => 'display:none', 'label' => false)); ?>
                    <?php
                    if ($acao == Configure::read('ACAO_EXCLUIR')):
                        $this->Form->unlockField('Especialidade.Especialidade');
                        $this->Form->unlockField('MunicipioProximo.MunicipioProximo');
                    endif;
                    ?>
                    <?= $this->element('botoes-default-cadastro'); ?>
                </div>


            </div>
        </section>
    </div>

</div>

<?php 
        // echo $this->Html->css('Admin.tokenize2', array('block' => 'script'));
        // echo $this->Html->script('Admin.tokenize2', array('block' => 'script')); 
        // echo $this->Html->script('Admin.loadcid', array('block' => 'script')); 
        
        echo $this->Html->css('Web.tokenize2', array('block' => 'script'));
        echo $this->Html->script('Web.tokenize2', array('block' => 'script')); 
        echo $this->Html->script('Web.loadcid', array('block' => 'script')); 
?>
<?= $this->Form->end(); ?>
<?= $this->Html->script('Web.unidade', array('block' => 'script')); ?>



