<div class="col-md-8 displayNone" id="divEmpresaAbaProfissao">
    <?php
    echo $this->Form->input('empresa_id', array(
        'type' => 'hidden',
        'id' => 'hiddenEmpresaId',
        'disabled' => true
    ));

    echo $this->Form->input('EmpresaComplete', array('div' => array('class' => 'form-group'),
        'class' => 'form-control',
        'label' => __('usuario_label_empresa') . $isRequerid,
        'data-url' => Router::url('/web/Empresa/getFirm', true),
        'disabled' => $formDisabled));
    ?>
</div>
<div class="col-md-4">
    <?=
    $this->Form->input('data_admissao_pericia', array('label' => __('usuario_label_data_admissao_pericia') . $isRequerid,
        'type' => 'text',
        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
        'onblur' => 'VerificaData(this,this.value)',
        'onmouseout' => 'VerificaData(this,this.value)',
        'disabled' => $formDisabled));
    ?>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="UsuarioNome"><?= __('usuario_label_chefe_perito'); ?></label>
        <br/>
        <?php
        echo $this->Form->checkbox('chefe_perito', array(
            'disabled' => $formDisabled
        ));
        ?>

    </div>
</div>
<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo __('usuario_label_tipologias') . $isRequerid ?></legend>
        <div class="row">
            <div class="col-md-6 form-group" style="width: 55%;">
                <?php echo $this->Form->label(null, __('usuario_label_disponiveis') . ': '); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->label(null, __('usuario_label_atribuidas_perito') . ': '); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <?php
                echo $this->Form->input('Tipologia', array('options' => $tipologia,
                    'multiple' => 'multiple',
                    'class' => 'tipologias_multi_select_agendamento alturaPickList',
                    'disabled' => $formDisabled,
                    'url-data' => Router::url('/web/Usuario/', true),
                    'div' => array('class' => 'form-group multi-select '),
                    'label' => false));
                ?>

            </div>
        </div>
    </fieldset>
</div>
<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo __('usuario_label_agenda_atendimento')  ?></legend>
        <?php
        echo $this->Form->hidden('AgendaAtendimento.id');
        ?>
        <div class="row">
            <div class="col-md-3 form-group">
                <?php
                echo
                $this->Form->input('AgendaAtendimento.dia_semana', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'options' => $diasSemana,
                    'empty' => __('label_selecione'),
                    'label' => __('usuario_label_dia_semana') . $isRequerid,
                    'disabled' => $formDisabled));
                ?>
            </div>
            <div class="col-md-2 form-group">
                <?php
                echo
                $this->Form->input('AgendaAtendimento.hora_inicial', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control hour',
                    'type' => 'text',
                    'id' => 'AgendaAtendimentoHorarioInicial',
                    'label' => __('usuario_label_horario_inicial') . $isRequerid,
                    'disabled' => $formDisabled));
                ?>
            </div>
            <div class="col-md-2 form-group">
                <?php
                echo
                $this->Form->input('AgendaAtendimento.hora_final', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control hour',
                    'id' => 'AgendaAtendimentoHorarioFinal',
                    'type' => 'text',
                    'label' => __('usuario_label_horario_final') . $isRequerid,
                    'disabled' => true));
                ?>
            </div>
            <div class="col-md-3 form-group">
                <?php
                echo
                $this->Form->input('AgendaAtendimento.unidade_atendimento_id', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'options' => $unidadeAtendimento,
                    'empty' => __('label_selecione'),
                    'label' => __('usuario_label_unidade_atendimento') . $isRequerid,
                    'disabled' => $formDisabled));
                ?>
            </div>
             <div class="col-md-2 form-group">
                <label for="AgendaAtendimentoPermitirAgendamento"><?php echo __('label_permitir_agendamento') ?></label>
                <br/>
                <?php
                echo
                $this->Form->input('AgendaAtendimento.permitir_agendamento', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control checkOnOFF',
                    'label' => false,
                    'data-size' => 'small',
                    'data-on-text' => 'Sim',
                    'data-off-text' => 'Não',
                    'checked' => true,
                    'disabled' => ($formDisabled || isset($formAlteracaoDados))
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo __('usuario_label_tipologias') . $isRequerid ?></legend>
                    <div class="row">
                        <div class="col-md-6 form-group" style="width: 55%;">
                            <?php //echo $this->Form->label(null, __('usuario_label_atribuidas_perito') . ': '); ?>
                            <label for="">Atribuídas ao Perito:</label>
                        </div>
                        <div class="form-group">
                            <?php //echo $this->Form->label(null, __('usuario_label_selecionadas') . ': '); ?>
                            <label for="">Selecionadas</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <?php
                            echo $this->Form->input('AgendaAtendimento.Tipologia', array(
                                'multiple' => 'multiple',
                                'id' => 'AgendaAtendimentoTipologia',
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
        <?php if (!$formDisabled): ?>
            <div class="form-group col-md-12">
                <div class="row text-right" id="adicionarAgenda">
                    <div class="col-sm-offset-10">
                        <i class="btn fa fa-plus estiloBotao btn-success" id="adicionarAgendaAtendimento" 
                           data-url="<?php echo Router::url('/web/Usuario/', true); ?>"> <?= __('usuario_label_adicionar'); ?></i>
                    </div>
                </div>
                <div class="row text-right displayNone" id="atualizarAgenda">
                    <div class="col-sm-offset-9">
                        <i class="btn fa fa-retweet btn-info" id="atualizarAgendaAtendimento" 
                           data-url="<?php echo Router::url('/web/Usuario/', true); ?>"> <?= __('usuario_label_atualizar'); ?></i>
                        <i class="btn fa fa-minus-circle btn-danger" id="cancelarAtualizarAgenda"> <?= __('bt_cancelar'); ?></i>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo __('usuario_label_horarios') ?></legend>
                    <div class="adv-table editable-table ">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="tableAgendaAtendimento">
                                <thead>
                                    <tr>
                                        <th style="width: 22%"><?= __('usuario_label_dia_semana'); ?></th>
                                        <th style="width: 22%"><?= __('usuario_label_horario'); ?></th>
                                        <th style="width: 22%"><?= __('usuario_label_unidade'); ?></th>
                                        <th style="width: 22%"><?= __('usuario_label_tipologia'); ?></th>
                                        <th style="width: 22%"><?= __('label_permitir_agendamento'); ?></th>
                                        <th style="width: 5%"></th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($agendaAtendimento) && !empty($agendaAtendimento)):
                                        foreach ($agendaAtendimento as $key => $line) :
                                            ?>
                                            <tr class="linhaRegistro">
                                                <td><?= $line["AgendaAtendimento"]['dia_semana']; ?></td>
                                                <td><?= $line["AgendaAtendimento"]['hora_inicial'] . ' / ' . $line["AgendaAtendimento"]['hora_final']; ?></td>
                                                <td><?= $line["AgendaAtendimento"]['nome_unidade_atendimento']; ?></td>
                                                <td><?= $line["AgendaAtendimento"]['nome_tipologia']; ?></td>
                                                <td><?php echo ($line["AgendaAtendimento"]['permitir_agendamento'] == "1") ? "Sim" : "Não" ; ?></td>
                                                <td>
                                                    <?php if (!$formDisabled): ?>
                                                        <div rel="<?= $key ?>" url-data="<?php echo Router::url('/web/Usuario/', true); ?>" 
                                                             class="btn editarAgendaAtendimento fa btn-info" title="Editar">Alterar</div>
                                                         <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!$formDisabled): ?>
                                                        <div rel="<?= $key ?>" url-data="<?php echo Router::url('/web/Usuario/', true); ?>" 
                                                             class="btn deletarAgendaAtendimento fa btn-danger" title="Excluir">Excluir</div>
                                                         <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                    <tr id="emptyAgendaAtendimento" class="<?= isset($agendaAtendimento) && !empty($agendaAtendimento) ? 'displayNone' : '' ?>">
                                        <td colspan="6" style="text-align: center;">
                                            <?= __('nenhum_registro_encontrado') ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </tbody>
                            </table>
                        </div>
                    </div>
            </div>
    </fieldset>
</div> <!-- END div AGENDAMENTO de ATENDIMENTO -->

<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo __('Agendamento de Atendimento em Domícilio')  ?></legend>
        <?php
        echo $this->Form->hidden('AgendaAtendimentoDomicilio.id');
        ?>
        <div class="row">
            <div class="col-md-2 form-group">
                <?php
                echo
                $this->Form->input('AgendaAtendimentoDomicilio.dia_semana', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'options' => $diasSemana,
                    'empty' => __('label_selecione'),
                    'label' => __('usuario_label_dia_semana') . $isRequerid,
                    'disabled' => $formDisabled));
                ?>
            </div>
            <div class="col-md-2 form-group">
                <?php
                echo
                $this->Form->input('AgendaAtendimentoDomicilio.hora_inicial', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control hour',
                    'type' => 'text',
                    'id' => 'AgendaAtendimentoDomicilioHorarioInicial',
                    'label' => __('usuario_label_horario_inicial') . $isRequerid,
                    'disabled' => $formDisabled));
                ?>
            </div>
            <div class="col-md-2 form-group">
                <?php
                echo
                $this->Form->input('AgendaAtendimentoDomicilio.hora_final', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control hour',
                    'id' => 'AgendaAtendimentoDomicilioHorarioFinal',
                    'type' => 'text',
                    'label' => __('usuario_label_horario_final') . $isRequerid,
                    'disabled' => true));
                ?>
            </div>
            <div class="col-md-3 form-group">
                <?php
                echo
                $this->Form->input('AgendaAtendimentoDomicilio.municipio_id', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'options' => $munipiosAtendimentoDomiciliar,
                    'empty' => __('label_selecione'),
                    'label' => __('Município') . $isRequerid,
                    'disabled' => $formDisabled,
                    'url-data' => Router::url('/web/Usuario/', true)
                    ));
                ?>
            </div>
            <div class="col-md-3 form-group">
                <?php
                echo
                $this->Form->input('AgendaAtendimentoDomicilio.unidade_atendimento_id', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'options' => $unidadeAtendimentoDomicilio,
                    'empty' => __('label_selecione'),
                    'label' => __('usuario_label_unidade_atendimento') . $isRequerid,
                    'disabled' => true,
                    'url-data' => Router::url('/web/Usuario/', true)
                    ));
                ?>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo __('usuario_label_tipologias') . $isRequerid ?></legend>
                    <div class="row">
                        <div class="col-md-6 form-group" style="width: 55%;">
                            <?php echo $this->Form->label(null, __('usuario_label_atribuidas_perito') . ': '); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->label(null, __('usuario_label_selecionadas') . ': '); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <?php
                            echo $this->Form->input('AgendaAtendimentoDomicilio.Tipologia', array(
                                'multiple' => 'multiple',
                                'id' => 'AgendaAtendDomicilioTipologia',
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
        <?php if (!$formDisabled): ?>
            <div class="form-group col-md-12">
                <div class="row text-right" id="adicionarAgendaDomicilio">
                    <div class="col-sm-offset-10">
                        <i class="btn fa fa-plus estiloBotao btn-success" id="adicionarAgendaAtendimentoDomicilio" 
                           data-url="<?php echo Router::url('/web/Usuario/', true); ?>"> <?= __('usuario_label_adicionar'); ?></i>
                    </div>
                </div>
                <div class="row text-right displayNone" id="atualizarAgendaDomicilio">
                    <div class="col-sm-offset-9">
                        <i class="btn fa fa-retweet btn-info" id="atualizarAgendaAtendimentoDomicilio" 
                           data-url="<?php echo Router::url('/web/Usuario/', true); ?>"> <?= __('usuario_label_atualizar'); ?></i>
                        <i class="btn fa fa-minus-circle btn-danger" id="cancelarAtualizarAgendaDomicilio"> <?= __('bt_cancelar'); ?></i>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo __('usuario_label_horarios') ?></legend>
                    <div class="adv-table editable-table ">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="tableAgendaAtendimentoDomicilio">
                                <thead>
                                    <tr>
                                        <th style="width: 18%"><?= __('usuario_label_dia_semana'); ?></th>
                                        <th style="width: 18%"><?= __('usuario_label_horario'); ?></th>
                                        <th style="width: 18%"><?= __('usuario_label_unidade'); ?></th>
                                        <th style="width: 18%"><?= __('usuario_label_municipio'); ?></th>
                                        <th style="width: 18%"><?= __('usuario_label_tipologia'); ?></th>
                                        <th style="width: 5%"></th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($agendaAtendimentoDomicilio) && !empty($agendaAtendimentoDomicilio)):
                                        foreach ($agendaAtendimentoDomicilio as $key => $line) :
                                            ?>
                                            <tr class="linhaRegistro">
                                                <td><?= $line["AgendaAtendimentoDomicilio"]['dia_semana']; ?></td>
                                                <td><?= $line["AgendaAtendimentoDomicilio"]['hora_inicial'] . ' / ' . $line["AgendaAtendimentoDomicilio"]['hora_final']; ?></td>
                                                <td><?= $line["AgendaAtendimentoDomicilio"]['nome_unidade_atendimento']; ?></td>
                                                <td><?= $line["AgendaAtendimentoDomicilio"]['nome_municipio']; ?></td>
                                                <td><?= $line["AgendaAtendimentoDomicilio"]['nome_tipologia']; ?></td>
                                                <td>
                                                    <?php if (!$formDisabled): ?>
                                                        <div rel="<?= $key ?>" url-data="<?php echo Router::url('/web/Usuario/', true); ?>" 
                                                             class="btn editarAgendaAtendimentoDomicilio fa btn-info" title="Editar">Alterar</div>
                                                         <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!$formDisabled): ?>
                                                        <div rel="<?= $key ?>" url-data="<?php echo Router::url('/web/Usuario/', true); ?>" 
                                                             class="btn deletarAgendaAtendimentoDomicilio fa btn-danger" title="Excluir">Excluir</div>
                                                         <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                    <tr id="emptyAgendaAtendimentoDomicilio" class="<?= isset($agendaAtendimentoDomicilio) && !empty($agendaAtendimentoDomicilio) ? 'displayNone' : '' ?>">
                                        <td colspan="7" style="text-align: center;">
                                            <?= __('nenhum_registro_encontrado') ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </tbody>
                            </table>
                        </div>
                    </div>
            </div>
    </fieldset>
</div>
<div class="displayNone" id="dialogHourDiffTwo">

</div>

<!-- Modal informando caso as horas sejam diferentes -->
<div id="dialog-confirm-hour" class="displayNone" title="Horário Diferente">
    <p><?= __('usuario_jornada_diferente_duas_horas')?></p>
</div>