<div class="">
    <?php
    echo $this->Form->hidden('Dependente.id');
    ?>
    <div class="col-md-6">
        <?php
        echo
        $this->Form->input('Dependente.nome', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'label' => __('usuario_label_dependente_nome') . $isRequerid,
            'disabled' => ($formDisabled)));
        ?>
    </div>
    <div class="col-md-3">
        <?php
        echo
        $this->Form->input('Dependente.cpf', array('div' => array('class' => 'form-group'),
            'class' => 'form-control cpf',
            'maxlength' => '14',
            'label' => __('usuario_label_dependente_cpf'),
            'disabled' => ($formDisabled)));
        ?>
    </div>
    <div class="col-md-3">
        <?php
        echo
        $this->Form->input('Dependente.rg', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'label' => __('usuario_label_dependente_rg'),
            'disabled' => ($formDisabled)));
        ?>
    </div>
</div>
<div class="col-md-3">
    <?=
    $this->Form->input('Dependente.data_nascimento', array('label' => __('usuario_label_dependente_data_nascimento'),
        'type' => 'text',
        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
        'onblur' => 'VerificaData(this,this.value)',
        'onmouseout' => 'VerificaData(this,this.value)',
        'disabled' => ($formDisabled)));
    ?>
</div>
<div class="col-md-3">
    <?php
    echo
    $this->Form->input('Dependente.inscricao_funape', array('div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'label' => __('usuario_label_dependente_inscricao_funape'),
        'disabled' => ($formDisabled)));
    ?>
</div>
<div class="col-md-3">
    <?php
    echo
    $this->Form->input('Dependente.qualidade_id', array('div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'options' => $qualidade,
        'empty' => __('label_selecione'),
        'label' => __('usuario_label_dependente_qualidade') . $isRequerid,
        'disabled' => ($formDisabled)));
    ?>
</div>
<div class=" col-md-8">
    <?php
    echo
    $this->Form->input('Dependente.nome_pai', array('div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'label' => __('usuario_label_dependente_nome_pai'),
        'disabled' => ($formDisabled)));
    ?>
</div>
<div class=" col-md-8">
    <?php
    echo
    $this->Form->input('Dependente.nome_mae', array('div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'label' => __('usuario_label_dependente_nome_mae'),
        'disabled' => ($formDisabled)));
    ?>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="enderecoDependenteServidor"><?= __('usuario_label_dependente_usar_endereco_servidor'); ?></label>
        <br/>
        <?php
        echo $this->Form->checkbox('Dependente.endereco_servidor', array(
            'id' => 'enderecoDependenteServidor',
            'value' => true,
            'disabled' => ($formDisabled)
        ));
        ?>

    </div>
</div>
<div id="enderecoDependente">
    <div class="col-md-12">
        <?php
        echo $this->element('componente_endereco', ['model' => 'EnderecoDependente',
            'requerid' => '',
            'municipios' => isset($municipiosDependentes) ? $municipiosDependentes : array(),
            'idComboMunicipio' => 'EnderecoDependenteMunicipioId'])
        ?>
    </div>
</div>
<?php if (!($formDisabled)): ?>
    <div class="col-md-12">
        <i class="btn fa fa-plus btn-success float-right" id="adicionarDependente" 
           data-url="<?php echo Router::url('/web/Usuario/', true); ?>"> <?= __('usuario_label_adicionar'); ?></i>
    </div>

    <div class="displayNone" id="atualizarDependente">
        <div class="col-md-12 text-right">
            <i class="btn fa fa-retweet  btn-primary" id="atualizarDependenteSession" 
               data-url="<?php echo Router::url('/web/Usuario/', true); ?>"> <?= __('usuario_label_atualizar'); ?></i>

            <i class="btn fa fa-minus-circle estiloBotao btn-primary" id="cancelarAtualizarDependente"> <?= __('bt_cancelar'); ?></i>
        </div>
    </div>
<?php endif; ?>

<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo __('usuario_label_dependente_lista_dependentes') ?></legend>
        <div class="adv-table editable-table ">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="tableDependentes">
                    <thead>
                        <tr>
                            <th style="width: 45%"><?= __('usuario_label_dependente_nome'); ?></th>
                            <th style="width: 45%"><?= __('usuario_label_dependente_cpf'); ?></th>
                            <th style="width: 5%"></th>
                            <th style="width: 5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($usuarioDependentes) && !empty($usuarioDependentes)):
                            foreach ($usuarioDependentes as $key => $line) :
                                ?>
                                <tr class="">
                                    <td><?= $line['Dependente']['nome']; ?></td>
                                    <td><?= Util::mask($line['Dependente']['cpf'], "###.###.###-##"); ?></td>
                                    <td>
                                        <?php if (!($formDisabled)): ?>
                                            <div rel="<?= $key ?>" url-data="<?php echo Router::url('/web/Usuario/', true); ?>" 
                                                 class="btn editarDependente fa btn-info" title="Editar">Editar</div>
                                             <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!($formDisabled)): ?>
                                            <div rel="<?= $key ?>" url-data="<?php echo Router::url('/web/Usuario/', true); ?>" 
                                                 class="btn deletarDependente fa btn-danger" title="Excluir">Excluir</div>
                                             <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <tr id="emptyDependente" class="<?= isset($usuarioDependentes) && !empty($usuarioDependentes) ? 'displayNone' : '' ?>">
                            <td colspan="4" style="text-align: center;">
                                <?= __('nenhum_registro_encontrado') ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </tbody>
                </table>
            </div>
        </div>
    </fieldset>
</div>