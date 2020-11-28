<?php
$formCreate['class'] = ($formDisabled) ? "formVisualizacao" : "";
echo $this->Form->create($controller, $formCreate);
?>

<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('AgendaSistema')))); ?>
            <div class="panel-body">
                <div class="row">
                     <div class="col-md-9 form-group">
                        <?php
                        echo
                        $this->Form->input('AgendaSistema.descricao', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'empty' => __('Descrição'),
                            'label' => __('Descrição') . $isRequerid,
                            'disabled' => $formDisabled));
                        ?>
                     </div>

                </div>


                <div class="row">
                    <div class="col-md-3">
                        <?php
                        echo
                        $this->Form->input('prazo_inicial', array('label' => __('Validade Inicial'),
                            'type' => 'text',
                            'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                            'onblur' => 'VerificaData(this,this.value)',
                            'onmouseout' => 'VerificaData(this,this.value)',
                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                        ?>
                    </div>

                    <div class="col-md-3">
                        <?php
                        echo
                        $this->Form->input('prazo_final', array('label' => __('Validade Final'),
                            'type' => 'text',
                            'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                            'onblur' => 'VerificaData(this,this.value)',
                            'onmouseout' => 'VerificaData(this,this.value)',
                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                        ?>
                    </div>
                    <div class="col-md-3">
                        <style>
                            .thisCheckbox{
                                float: left;
                                width: 15px;
                                height: 15px;
                                margin: 2px !important;
                            }
                            .checkLabel label{
                                float: left;
                                margin-left: 5px;
                                font-size: 13px;
                                font-weight: 700;
                            }
                        </style>
                        <div class="checkLabel">
                        <?php
                        echo
                        $this->Form->input('habilitada', array('label' => __('Agenda Habilitada'),
                            'type' => 'checkbox',
                            'class' => 'form-control thisCheckbox',
                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo __('Tipologias') . $isRequerid ?></legend>
                    <div class="row">
                        <div class="col-md-6 form-group" style="width: 55%;">
                            <?php echo $this->Form->label(null, __('Disponíveis') . ': '); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->label(null, __('Atribuídas') . ': '); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <?php
                            echo $this->Form->input('Tipologia', array('options' => $tipologia,
                                'multiple' => 'multiple',
                                'class' => 'tipologias_multi_select_agendamento alturaPickList tipologiasDisponiveis',
                                'disabled' => $formDisabled,
                                'url-data' => Router::url('/web/AgendaSistema/', true),
                                'div' => array('class' => 'form-group multi-select '),
                                'label' => false));
                            ?>

                        </div>
                    </div>
                </fieldset>
            </div>
                <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo __('Agenda de Atendimento')  ?></legend>
                    <?php
                    echo $this->Form->hidden('AgendaSistemaItem.id');
                    ?>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <?php
                            echo
                            $this->Form->input('AgendaSistemaItem.dia_semana', array('div' => array('class' => 'form-group'),
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
                            $this->Form->input('AgendaSistemaItem.hora_inicial', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control hour',
                                'type' => 'text',
                                'id' => 'AgendaSistemaItemHorarioInicial',
                                'label' => __('usuario_label_horario_inicial') . $isRequerid,
                                'disabled' => $formDisabled));
                            ?>
                        </div>
                        <div class="col-md-2 form-group">
                            <?php
                            echo
                            $this->Form->input('AgendaSistemaItem.hora_final', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control hour',
                                'id' => 'AgendaSistemaItemHorarioFinal',
                                'type' => 'text',
                                'label' => __('usuario_label_horario_final') . $isRequerid,
                                'disabled' => true));
                            ?>
                        </div>
                        <div class="col-md-4 form-group">
                            <?php
                            echo
                            $this->Form->input('AgendaSistemaItem.unidade_atendimento_id', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => $unidadeAtendimento,
                                'empty' => __('label_selecione'),
                                'label' => __('usuario_label_unidade_atendimento') . $isRequerid,
                                'disabled' => $formDisabled));
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo __('Tipologias') . $isRequerid ?></legend>
                                <div class="row">
                                    <div class="col-md-6 form-group" style="width: 55%;">
                                        <?php echo $this->Form->label(null, __('Atribuídas à agenda') . ': '); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo $this->Form->label(null, __('Selecionadas') . ': '); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <?php
                                        echo $this->Form->input('AgendaSistemaItem.Tipologia', array(
                                            'multiple' => 'multiple',
                                            'id' => 'AgendaSistemaItemTipologia',
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
                                    <i class="btn fa fa-plus estiloBotao btn-success" id="adicionarAgendaSistemaItem"
                                       data-url="<?php echo Router::url('/web/AgendaSistema/', true); ?>"> <?= __('usuario_label_adicionar'); ?></i>
                                </div>
                            </div>
                            <div class="row text-right displayNone" id="atualizarAgenda">
                                <div class="col-sm-offset-9">
                                    <i class="btn fa fa-retweet btn-info" id="atualizarAgendaSistemaItem"
                                       data-url="<?php echo Router::url('/web/AgendaSistema/', true); ?>"> <?= __('usuario_label_atualizar'); ?></i>
                                    <i class="btn fa fa-minus-circle btn-danger" id="cancelarAtualizarAgenda"> <?= __('bt_cancelar'); ?></i>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo __('usuario_label_horarios') ?></legend>
                                <span>* Ao salvar a agenda os itens da agenda serão validados</span>
                                <div class="adv-table editable-table ">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered" id="tableAgendaSistemaItem">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%">Validado</th>
                                                <th style="width: 20%"><?= __('usuario_label_dia_semana'); ?></th>
                                                <th style="width: 20%"><?= __('usuario_label_horario'); ?></th>
                                                <th style="width: 20%"><?= __('usuario_label_unidade'); ?></th>
                                                <th style="width: 20%"><?= __('usuario_label_tipologia'); ?></th>
                                                <th style="width: 5%"></th>
                                                <th style="width: 5%"></th>
                                            </tr>
                                            </thead>
                                            <style>.red { color:red; } .green{ color: green;}</style>
                                            <tbody>
                                            <?php
                                            if (isset($AgendaSistemaItem) && !empty($AgendaSistemaItem)):
                                                foreach ($AgendaSistemaItem as $key => $line):
                                                    $validado = ($line["AgendaSistemaItem"]['validado']==true);
                                                    ?>
                                                    <tr class="linhaRegistro">
                                                        <td style="text-align: center"><span class="icon-status glyphicon glyphicon-<?=$validado?"ok":"ban-circle";?> <?=$validado?"green":"red" ?>"></span></td>
                                                        <td><?= $line["AgendaSistemaItem"]['dia_semana']; ?></td>
                                                        <td><?= $line["AgendaSistemaItem"]['hora_inicial'] . ' / ' . $line["AgendaSistemaItem"]['hora_final']; ?></td>
                                                        <td><?= $line["AgendaSistemaItem"]['nome_unidade_atendimento']; ?></td>
                                                        <td><?= $line["AgendaSistemaItem"]['nome_tipologia']; ?></td>
                                                        <td>
                                                            <?php if (!$formDisabled): ?>
                                                                <div rel="<?= $key ?>" url-data="<?php echo Router::url('/web/AgendaSistema/', true); ?>"
                                                                     class="btn editarAgendaSistemaItem fa btn-info" title="Editar">Alterar</div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!$formDisabled): ?>
                                                                <div rel="<?= $key ?>" url-data="<?php echo Router::url('/web/AgendaSistema/', true); ?>"
                                                                     class="btn deletarAgendaSistemaItem fa btn-danger" title="Excluir">Excluir</div>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                            <tr id="emptyAgendaSistemaItem" class="<?= isset($AgendaSistemaItem) && !empty($AgendaSistemaItem) ? 'displayNone' : '' ?>">
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
        </div>


</div>
    </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <div class="panel-body">
                <?php if ($acao == Configure::read('ACAO_ALTERAR')): ?>
                    <div class="row float-right btn-edit">
                        <i class="btn fa fa-check estiloBotao btn-warning" id="ajaxDuplicar"
                           data-url="<?php
                           echo Router::url(array('controller' => 'AgendaSistema',
                                   'action' => $this->params['action']), true)."/$agendaSistemaId/1";
                           ?>">Duplicar</i>
                        <i class="btn fa fa-check estiloBotao btn-success" id="ajaxAdd" 
                           data-url="<?php
                           echo Router::url(array('controller' => 'AgendaSistema',
                               'action' => $this->params['action']), true)."/$agendaSistemaId";
                           ?>"><?= __('bt_salvar') ?></i>
                        <i class="btn fa fa fa-search estiloBotao btn-info"
                           onclick="location.href = '<?= Router::url(array('controller' => 'AgendaSistema', 'action'=>'index'));?>'"
                           id="ajaxConsult" data-url="<?= Router::url(array('controller' => 'AgendaSistema', 'action'=>'index'));?>"> Ir para consulta</i>
                    </div>
                <?php else: ?>
                    <?= $this->element('botoes-default-cadastro', ['ajax' => true]); ?>

                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<style>
    .bgDanger { background-color: #F33; }
    .bgOk { background-color: #3B3; }
</style>
<div class="modal fade modalMsg" tabindex="-1" role="dialog" style="background: none">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bgDanger" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="msgTitle">Atenção</h4>
            </div>
            <div class="modal-body">
                <p id="msgTxt">A agenda não foi validada, pois existem horários que não estão disponíveis na agenda dos peritos. Favor, verificar a agenda dos peritos.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>




<script>
    console.log(<?=json_encode($acao);?>);
    console.log(<?=json_encode(Configure::read('ACAO_ALTERAR'));?>);
$(function(argument) {

  $('.checkOnOFF').bootstrapSwitch();
})
</script>


<?= $this->Form->end(); ?>
<?php
        echo $this->Html->script('Web.periciasmedicas', array('block' => 'script'));
        echo $this->Html->script('Web.agendaSistema', array('block' => 'script'));
?>

<!-- Modal de alerta -->
<div class="modal fade modalMsgAlert" role="dialog" style="background: none">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header " >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="msgTitle">Aviso</h4>
            </div>
            <div class="modal-body">
                <p id="msgTxt" style="text-align: center;font-size: 16px;"><?=$this->Session->flash('msgAlert')?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<script>
    jQuery(function ($) {
        if($(".modalMsgAlert #msgTxt").text() !== ''){
            $('.modalMsgAlert').modal();
        }
    });
</script>
