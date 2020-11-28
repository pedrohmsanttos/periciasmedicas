<?php if (isset($agendamentos)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <?php $urlController = Router::url(array('controller' => "Atendimento", "action" => "/"), true); ?>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample" data-url="<?= $urlController ?>">
                            <thead>
                                <tr>
                                    <th><?= __('atendimento_label_status_atendimento'); ?></th>
                                    <th><?= __('atendimento_label_sala_atendimento'); ?></th>
                                    <th><?= __('atendimento_label_hora'); ?></th>
                                    <th><?= __('atendimento_label_tipologia'); ?></th>
                                    <th><?= __('atendimento_label_servidor'); ?></th>
                                    <th><?= __('atendimento_label_cpf'); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($agendamentos)):
                                    foreach ($agendamentos as $line) :
                                        // pr($line);
                                        if(!empty($line['Agendamento']['data_hora'])){
                                            $data = Util::toDBDataHora($line['Agendamento']['data_hora']);
                                            $hora = date("H:i", strtotime($data));
                                        }else{
                                            $data = '';
                                            $hora = '';
                                        }
                                        if($line['Agendamento']['tempo_consulta']>=1)$hora = 'Prior.';

                                        $id = $line['Agendamento']['id'];
                                        $statusAgendamento = $line['Agendamento']['status_agendamento'];
                                        $agendamentoConfirmado = $line['Agendamento']['agendamento_confirmado'];
                                        ?>
                                        <tr class="<?php
                                        if ($statusAgendamento == "Em Atendimento" && $line['Agendamento']['agendamento_encaminhado_sala'] != true):
                                            echo "solicitarPresenca";
                                        endif;
                                        ?>">
                                            <?php  
                                                $labelEncaixe = "";
                                                if(!is_null($line['Agendamento']['encaixe']) && $line['Agendamento']['encaixe'] == "1"){
                                                    $labelEncaixe = " (ENCAIXE)";
                                                } 
                                            ?>
                                            <td>
                                                <?= $this->Form->hidden('idAgendamento', array('class' => 'idAgendamento', 'value' => $id)) ?>
                                                <?= $line['Agendamento']['status_agendamento'] .  $labelEncaixe ?></td>
                                            <td class="colunaSala"><?php
                                                if (isset($line['Agendamento']['sala']) && !empty($line['Agendamento']['sala'])):
                                                    echo $line['Agendamento']['sala'];
                                                endif;
                                                ?>
                                            </td>
                                            <td class="colunaHora"><?= $hora ?></td>
                                            <?php $tipologia_processo = isset($line['Agendamento']['tipologia_processo'])?$line['Agendamento']['tipologia_processo']:""; ?>
                                            <td><?= $line['Tipologia']['nome']. (!empty($tipologia_processo)?"<b> ($tipologia_processo)</b>":""); ?></td>
                                            <td class="colunaNome"><?= $line['UsuarioServidor']['nome']; ?></td>
                                            <td class="colunaCpf"><?= Util::mask($line['UsuarioServidor']['cpf'], "###.###.###-##") ?></td>
                                            <td>
                                                <div class="btn-group">


                                                    <?php

                                                    $exibirBotaoConfirmacao = Util::toDBDataHora($data) >= date("Y-m-d H:i");
                                                    $statusDeAtendimento = ($statusAgendamento == "Em Atendimento" || $statusAgendamento == "Atendido");
                                                    $permitirConfirmar = (Util::temPermissao("Atendimento.confirmar") && !$statusDeAtendimento);
                                                    $permitirRemarcar = (Util::temPermissao("Atendimento.remarcar") && !$statusDeAtendimento);
                                                    $permitirDeletar = (Util::temPermissao("Atendimento.deletar") && !$statusDeAtendimento);
                                                    $exibirAcoes = ($permitirConfirmar || $permitirRemarcar || $permitirDeletar);
                                                    ?>

                                                    <button data-toggle="dropdown" class="btn <?= $exibirAcoes ? 'btn-info' : 'btn-default'?> dropdown-toggle btn-sm" type="button"><?php echo __('label_acoes') ?> <span class="caret"></span></button>

                                                    <?php
                                                    if ($exibirAcoes):
                                                        ?>
                                                        <ul role="menu" class="dropdown-menu listaAcoes">
                                                            <?php if ($permitirConfirmar): ?>
                                                                <li><a class="fa <?= $agendamentoConfirmado === true ? 'fa-times' : 'fa-clock-o' ?> beforeAcao" data-acao="<?= $agendamentoConfirmado ? "desconfirmar" : "confirmar" ?>" data-url="<?= Router::url('/web/Atendimento/confirmarAgendamento/' . $id . '/' . !$agendamentoConfirmado); ?>" title=" <?= __('bt_confirmar'); ?>"> <?= $agendamentoConfirmado == true ? __('bt_desconfirmar_presenca') : __('bt_confirmar_presenca'); ?></a></li>
                                                            <?php endif; ?>
                                                            <?php if ($permitirRemarcar): ?>
                                                                <li>
                                                                    <a class="fa fa-calendar beforeAcao" title=" <?= __('bt_reagendar') ?>" data-acao="reagendar" data-url="<?= Router::url('/web/Agendamento/editar/' . $id . '/Atendimento', true); ?>"> <?= __('bt_reagendar') ?></a>
                                                                <?php endif; ?>
                                                                <?php if ($permitirDeletar): ?>
                                                                <li>
                                                                    <a class="fa fa-trash-o beforeAcao" data-acao="excluir" data-url="<?= Router::url('/web/Agendamento/deletar/' . $id . '/Atendimento', true); ?>" title=" <?= __('bt_excluir') ?>"> <?= __('bt_excluir') ?></a>
                                                                <?php endif; ?>
                                                        </ul>
                                                        <?php
                                                    endif;
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr class="">
                                        <td colspan="7" style="text-align: center;">
                                            <?= __('nenhum_registro_encontrado') ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group" style="width: 15%; text-align: center;" >
                            <i class="fa fa-clock-o fa-2x"></i>
                            <?= $this->Form->label(null, __('atendimento_label_confirmar_presenca')) ?>
                        </div>
                        <div class="col-md-2 form-group" style="width: 16%; text-align: center;">
                            <i class="fa fa-times fa-2x"></i>
                            <?= $this->Form->label(null, __('atendimento_label_desconfirmar_presenca')) ?>
                        </div>
                        <div class="col-md-1 form-group" style="width: 10%; text-align: center;">
                            <i class="fa fa-calendar fa-2x"></i>
                            <?= $this->Form->label(null, __('atendimento_label_reagendar')) ?>
                        </div>
                        <div class="col-md-2 form-group" style="width: 10%; text-align: center;">
                            <i class="fa fa-trash-o fa-2x"></i><br>
                            <?= $this->Form->label(null, __('atendimento_label_cancelar')) ?>
                        </div>  
                        <div class="col-md-5 " style="width: 49%;">
                            <?php
                            $urlConsulta = Router::url(array('controller' => "Agendamento", 'action' => 'adicionar'));

                            echo $this->Form->button(__('bt_novo_agendamento'), array(
                                'class' => 'btn fa fa-calendar-o estiloBotao btn-success float-right',
                                'type' => 'button',
                                'onclick' => "location.href = '$urlConsulta'"
                            ));
                            ?>
                            <?php
                            $urlConsultaSalas = Router::url(array('controller' => "GerenciamentoSala", 'action' => 'consultar'), true);
                            echo $this->Form->button(__('bt_gerenciar_salas'), array(
                                'class' => 'btn fa fa-building-o estiloBotao btn-info float-right',
                                'type' => 'button',
                                'id' => 'gerenciarSalas',
                                'data-gerenciar-sala' => $urlConsultaSalas,
                                'href' => 'javascript:void(0)'
                            ));
                            ?>
                        </div>
                    </div>   

                </div>
            </div>
        </section>
    </div>

    <div id="dialog-chamada_para_sala" class="displayNone">
        <p><?php echo __('atendimento_dialog_lembrar_servidor'); ?> <span id="parametroNomeServidor"></span>
            <?php echo __('atendimento_dialog_comparecer_sala'); ?> <span id="parametroSala"></span>.
        </p>
    </div>
<?php endif; ?>