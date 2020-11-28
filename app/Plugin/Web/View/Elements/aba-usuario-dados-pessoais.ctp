<div class="row col-md-12">
    <div class="col-md-8" style="width: 68%">
        <?php
        echo
        $this->Form->input('nome', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'label' => __('usuario_label_nome_usuario') . $isRequerid,
            'disabled' => ($formDisabled || isset($formAlteracaoDados))));
        ?>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="UsuarioNome"><?= __('usuario_label_ativo'); ?></label>
            <br/>
            <?php
            if ($id == $userData['id']) {
                echo $this->Form->checkbox('ativado', array(
                    'disabled' => true
                ));
            } else {
                echo $this->Form->checkbox('ativado', array(
                    'disabled' => ($formDisabled || isset($formAlteracaoDados))
                ));
            }
            ?>
        </div>
    </div>
</div>
<div class="col-md-2">
    <?php
    echo $this->Form->input('data_nascimento', array('label' => __('usuario_label_data_nascimento'),
        'type' => 'text',
        'class' => 'input-append inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
        'onblur' => 'VerificaData(this,this.value)',
        'onmouseout' => 'VerificaData(this,this.value)',
        'disabled' => ($formDisabled || isset($formAlteracaoDados))));
    ?>
</div>
<div class="col-md-2">
    <?=
    $this->Form->input('data_obito', array('label' => __('usuario_label_data_obito'),
        'type' => 'text',
        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
        'onblur' => 'VerificaData(this,this.value)',
        'onmouseout' => 'VerificaData(this,this.value)',
        'disabled' => ($formDisabled || isset($formAlteracaoDados))));
    ?>
</div>
<div class="col-md-4">
    <?php
    echo
    $this->Form->input('estado_civil_id', array('div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'options' => $estadoCivil,
        'label' => __('usuario_label_estado_civil'),
        'empty' => __('label_selecione'),
        'disabled' => ($formDisabled || isset($formAlteracaoDados))));
    ?>
</div>
<div class="col-md-4">
    <?php
    echo
    $this->Form->input('sexo_id', array('div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'options' => $sexo,
        'label' => __('usuario_label_sexo'),
        'empty' => __('label_selecione'),
        'disabled' => ($formDisabled || isset($formAlteracaoDados))));
    ?>
</div>
<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo __('usuario_label_contatos') ?></legend>
        <div class="row">
            <div class="col-md-2">
                <?php
                echo
                $this->Form->input('telefone', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control telefone',
                    'label' => __('usuario_label_telefone'),
                    'disabled' => $formDisabled));
                ?>
            </div>
            <div class="col-md-2">
                <?php
                echo
                $this->Form->input('telefone_trabalho', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control telefone',
                    'label' => __('usuario_label_telefone_trabalho'),
                    'disabled' => $formDisabled));
                ?>
            </div>
            <div class="col-md-2">
                <?php
                echo
                $this->Form->input('telefone_celular', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control telefone',
                    'label' => __('usuario_label_telefone_celular'),
                    'disabled' => $formDisabled));
                ?>
            </div>
            <div class="col-md-6">
                <?php
                    $emailObrigatorio = false;
                    if(isset($this->request->data) && isset($this->request->data['Usuario'])):
                        $tipoUsuario = $this->request->data["Usuario"]["tipo_usuario_id"];
                        if($tipoUsuario== 1 || $tipoUsuario == 3):
                            $emailObrigatorio = true;
                        endif;
                    endif;
                    $obrigatorio = $emailObrigatorio ? '*' : '';
                echo $this->Form->label(null, __('usuario_label_email'). $obrigatorio, array('id'=>'labelEmail'));
                echo
                $this->Form->input('email', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'label' => false,
                    'disabled' => $formDisabled));
                ?>
            </div>
        </div>
    </fieldset>
</div>
<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo __('usuario_label_documentos') ?></legend>
        <div class="row">
            <div class="col-md-2">
                <?php
                echo
                $this->Form->input('cpf', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control cpf',
                    'label' => __('usuario_label_cpf') . $isRequerid,
                    'readonly' => ($formDisabled || isset($formAlteracaoDados))));
                ?>
            </div>
            <div class="col-md-2">
                <?php
                echo
                $this->Form->input('rg', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'maxlength' => 10, 
                    'label' => __('usuario_label_rg'),
                    'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                ?>
            </div>
            <div class="col-md-2">
                <?php
                echo
                $this->Form->input('orgao_expedidor', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'label' => __('usuario_label_orgao_expedidor'),
                    'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo
                $this->Form->input('numero_registro', array('div' => array('class' => 'form-group displayNone', 'id' => 'numeroRegistroDisplay'),
                    'class' => 'form-control',
                    'label' => __('usuario_label_numero_registro') . $isRequerid,
                    'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                ?>
            </div>
        </div>
    </fieldset>
</div>
<div class="" id="fieldVinculo">
    <div class="col-md-12">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border"><?php echo __('usuario_label_vinculos') ?></legend>
            <div class="row">
                <div class="col-md-4">
                    <?php
                    echo $this->Form->input('Vinculo.orgao_origem_id', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'options' => $orgaoOrigem,
                        'empty' => __('label_selecione'),
                        'data-url' => Router::url('/web/Usuario/', true),
                        'label' => __('usuario_label_orgao_origem') . $isRequerid,
                        'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                    ?>
                </div>
                <div class="col-md-4">
                    <?php
                    echo $this->Form->input('Vinculo.matricula', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'label' => __('usuario_label_matricula') . $isRequerid,
                        'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                    ?>
                </div>
                <div class="col-md-4">
                    <?php
                    echo
                    $this->Form->input('Vinculo.cargo_id', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'options' => $cargo,
                        'empty' => __('label_selecione'),
                        'label' => __('usuario_label_cargo') . $isRequerid,
                        'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?=
                    $this->Form->input('Vinculo.data_admissao_servidor', array('label' => __('usuario_label_data_admissao_servidor') . ': ' . $isRequerid,
                        'type' => 'text',
                        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                        'onblur' => 'VerificaData(this,this.value)',
                        'onmouseout' => 'VerificaData(this,this.value)',
                        'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                    ?>
                </div>

                    <div class="col-md-5 form-group">
                        <?php
                        $arrTipo = array('1' => __('label_sim'), '0' => __('label_nao'));

                        $strAtivo = (isset($this->data['Usuario']['aposentado'])) ? $this->data['Usuario']['aposentado'] : null;
                        $value = null;
                        if ($strAtivo == true):
                            $value = 1;
                        elseif ($strAtivo === false):
                            $value = 0;
                        endif;

                        echo
                        $this->Form->input('aposentado', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $arrTipo,
                            'value' => $value,
                            'label' => __('usuario_label_aposentado'),
                            'empty' => __('label_selecione'),
                            'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                        ?>
                    </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><?php echo __('usuario_label_funcoes') ?></legend>
                        <div class="row">
                            <div class="col-md-4">
                                <?php
                                echo $this->Form->label(__('usuario_label_funcao'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php
                                echo
                                $this->Form->input('funcao', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'options' => $funcao,
                                    'empty' => __('label_selecione'),
                                    'label' => false,
                                    'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php if (!($formDisabled || isset($formAlteracaoDados))): ?>
                                    <div class="col-md-1 form-group" id="divBotoes">
                                        <i class="btn fa fa-plus fa-2x plus" id="adicionarFuncaoUsuario" url-data="<?php echo Router::url('/web/Usuario/', true); ?>"></i>
                                    </div>
                                <?php endif; ?>
                            </div> 
                        </div>
                        <div class="row">
                            <table class="table table-striped table-hover table-bordered" id="tableFuncao">
                                <thead>
                                    <tr>
                                        <th style="width: 93%"><?= __('usuario_label_funcao') ?></th>
                                        <th style="width: 7%"><?= __('') ?></th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyFuncao">
                                    <?php
                                    if (isset($usuarioFuncao)):
                                        if (!empty($usuarioFuncao)):
                                            foreach ($usuarioFuncao as $line) :
                                                ?>
                                                <tr class="">
                                                    <td><?= $line['nome'] ?></td>
                                                    <td>
                                                        <div rel="<?= $line['id'] ?>" url-data="<?php echo Router::url('/web/Usuario/', true); ?>" 
                                                             class="btn deletarFuncao fa btn-danger" title="Excluir">Excluir</div>
                                                    </td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <tr id="emptyFuncao">
                                                <td colspan="2" style="text-align: center;">
                                                    <?= __('nenhum_registro_encontrado') ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <tr id="emptyFuncao">
                                            <td colspan="2" style="text-align: center;">
                                                <?= __('nenhum_registro_encontrado') ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><?php echo __('usuario_label_lotacoes') ?></legend>
                        <div class="row">
                            <div class="col-md-4">
                                <?php
                                echo $this->Form->label(__('usuario_label_lotacao'));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php
                                echo
                                $this->Form->input('lotacao', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'options' => [],
                                    'empty' => __('label_selecione'),
                                    'label' => false,
                                    'disabled' => ($formDisabled || isset($formAlteracaoDados))));
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php if (!($formDisabled || isset($formAlteracaoDados))): ?>
                                    <div class="col-md-1 form-group" id="divBotoes">
                                        <i class="btn fa fa-plus fa-2x plus" id="adicionarLotacaoUsuario" url-data="<?php echo Router::url('/web/Usuario/', true); ?>"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped table-hover table-bordered" id="tableLotacao">
                                <thead>
                                    <tr>
                                        <th style="width: 93%"><?= __('usuario_label_lotacao') ?></th>
                                        <th style="width: 7%"><?= __('') ?></th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyLotacal">
                                    <?php
                                    if (isset($usuarioLotacao)):
                                        if (!empty($usuarioLotacao)):
                                            foreach ($usuarioLotacao as $line) :
                                                ?>
                                                <tr class="">
                                                    <td><?= $line['nome']; ?></td>
                                                    <td>
                                                        <div rel="<?= $line['id'] ?>" url-data="<?php echo Router::url('/web/Usuario/', true); ?>" 
                                                             class="btn deletarLotacao fa btn-danger" title="Excluir">Excluir</div>
                                                    </td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <tr id="emptyLotacao">
                                                <td colspan="2" style="text-align: center;">
                                                    <?= __('nenhum_registro_encontrado') ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <tr id="emptyLotacao">
                                            <td colspan="2" style="text-align: center;">
                                                <?= __('nenhum_registro_encontrado') ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>
            </div>
            <?php if (!($formDisabled || isset($formAlteracaoDados))): ?>
                <div class="form-group col-md-12">
                    <div class="row text-right">
                        <div class="col-sm-offset-10">
                            <i class="btn fa fa-plus btn-success" id="adicionarVinculoUsuario" 
                               url-data="<?php echo Router::url('/web/Usuario/', true); ?>"> <?= __('usuario_label_adicionar_vinculo'); ?></i>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <!-- INICIO -->
            <div class="row">
                <table class="table table-striped table-hover table-bordered" id="tableVinculo">
                    <thead>
                        <tr>
                            <th style="width: 19%"><?= __('usuario_label_orgao'); ?></th>
                            <th style="width: 19%"><?= __('usuario_label_matricula'); ?></th>
                            <th style="width: 19%"><?= __('usuario_label_cargo'); ?></th>
                            <th style="width: 19%"><?= __('usuario_label_funcoes'); ?></th>
                            <th style="width: 19%"><?= __('usuario_label_lotacoes'); ?></th>
                            <th style="width: 5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (isset($usuarioVinculo)): 
                            if (!empty($usuarioVinculo)):
                                foreach ($usuarioVinculo as $key => $line) :
                                    ?>
                                    <tr class="">
                                        <td><?= $line['OrgaoOrigem']['orgao_origem']; ?></td>
                                        <td><?= $line['Vinculo']['matricula']; ?></td>
                                        <td><?= $line['Cargo']['nome']; ?></td>
                                        <td><?= $line['funcoes']; ?></td>
                                        <td><?= $line['lotacoes']; ?></td>
                                        <td>
                                            <?php if (!($formDisabled || isset($formAlteracaoDados))): ?>
                                                <div rel="<?= $key ?>" url-data="<?php echo Router::url('/web/Usuario/', true); ?>" 
                                                     class="btn deletarVinculo fa btn-danger" title="Excluir">Excluir</div>
                                                 <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr id="emptyVinculo">
                                    <td colspan="6" style="text-align: center;">
                                        <?= __('nenhum_registro_encontrado') ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <tr id="emptyVinculo">
                        <td colspan="6" style="text-align: center;">
                            <?= __('nenhum_registro_encontrado') ?>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <!-- FIM TESTE -->
        </fieldset>
    </div>
</div>
<div class="col-md-12">
    <?php


    if(isset($disabledEndereco)){
        $formDisabledEndereco = $disabledEndereco;
    }else{
        $formDisabledEndereco = '';
    }
    
    echo $this->element('componente_endereco', ['model' => 'EnderecoUsuario', 'requerid' => '',
        'municipios' => (isset($municipiosUsuarios)) ? $municipiosUsuarios : [],
        'idComboMunicipio' => 'EnderecoUsuarioMunicipioId', 'formDisabledEndereco' => $formDisabledEndereco])
    ?>
</div>
<?php
if (($this->params['action'] == 'editar' || $this->params['action'] == 'alterarDados') && !$desabilitaTrocaSenha):
    ?>
    <div class="row col-md-12">
        <div class="col-md-12">
            <fieldset class="scheduler-border" id="passAlterUser" class="displayNone">
                <legend class="scheduler-border"><?php echo __('usuario_label_senha') ?></legend>
                <div cl class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('senha_atual', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'type' => 'password',
                        'label' => __('usuario_label_senha_atual') . $isRequerid,
                        'disabled' => true));
                    ?>
                </div>
                <div cl class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('senha', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'UsuarioNovaSenha',
                        'type' => 'password',
                        'label' => __('usuario_label_nova_senha') . $isRequerid,
                        'disabled' => true));
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('confirma_nova_senha', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'label' => __('usuario_label_confirmar_nova_senha') . $isRequerid,
                        'type' => 'password',
                        'disabled' => true));
                    ?>
                </div>
				<div class="col-md-3">
				<p>A senha deve ter no mínimo 6 caracteres e conter número, letra e caractere especial</p>
				</div>
            </fieldset>
        </div>
    </div>

<?php endif; ?>