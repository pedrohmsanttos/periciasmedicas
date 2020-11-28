<?php

if (isset($agendamentos)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('Agendamentos'), 'idLimiteConsulta' => 'registros_pagina_agendamento')); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th>Número Agendamento</th>
                                    <th>Número Atendimento</th>
                                    <th><?= __('agendamento_input_dia_hora'); ?></th>
                                    <th><?= __('agendamento_input_servidor'); ?></th>
                                    <th><?= __('agendamento_input_cpf_servidor'); ?></th>
                                    <th><?= __('agendamento_input_tipologia'); ?></th>
                                    <th><?= __('agendamento_input_unidade_atendimento'); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($agendamentos)):
                                    $idUsuario = CakeSession::read('Auth.User.id');

                                    foreach ($agendamentos as $line) :

                                        $id = $line['Agendamento']['id'];
                                        $data = "";
                                        if(isset($line['Agendamento']['data_hora']) && !empty($line['Agendamento']['data_hora'])){
                                            $data = Util::toDBDataHora($line['Agendamento']['data_hora'], true);
                                            $data = date("d/m/Y H:i", strtotime($data));
                                        }
                                        
                                        if(in_array($line['Tipologia']['id'],array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO))){
                                            $data="";
                                        }

                                        $statusAgendamento = $line['Agendamento']['status_agendamento'];
                                        $statusDeAtendimento = ($statusAgendamento == "Em Atendimento" || $statusAgendamento == "Atendido");
                                        $permitirDeletar = (Util::temPermissao($nameModel . ".deletar") && !$statusDeAtendimento);
                                        $permitirEditar = (Util::temPermissao($nameModel . ".editar") && !$statusDeAtendimento);


                                        $chefiaMediata1 = $line['Agendamento']['chefe_imediato_um_id'];
                                        $chefiaMediata2 = $line['Agendamento']['chefe_imediato_dois_id'];
                                        $chefiaMediata3 = $line['Agendamento']['chefe_imediato_tres_id'];
                                        $arrChefiaMediata = array($chefiaMediata1,$chefiaMediata2,$chefiaMediata3);

                                        $permitirHomologar = (in_array($idUsuario,$arrChefiaMediata))&&(in_array($line['Tipologia']['id'], array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE,TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO)));


                                        $exibirAcoes = ($permitirEditar || $permitirDeletar ||  $permitirHomologar);

                                        ?>
                                        <tr class="">
                                            <td><?=$line['Agendamento']['id']?></td>
                                            <td><?=!empty($line['Atendido']['id'])?$line['Atendido']['id']:''?></td>
                                            <td><?= $data; ?><!-- <?=$line['Agendamento']['id'] ?> --></td>
                                            <td><?= $line['UsuarioServidor']['nome'] ?></td>
                                            <td><?= Util::mask($line['UsuarioServidor']['cpf'], '###.###.###-##'); ?></td>
                                            <?php $tipologia_processo = isset($line['Agendamento']['tipologia_processo'])?$line['Agendamento']['tipologia_processo']:""; ?>
                                            <td><?= $line['Tipologia']['nome']. (!empty($tipologia_processo)?"<b> ($tipologia_processo)</b>":""); ?></td>
                                            <td><?= $line['UnidadeAtendimento']['nome']; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button data-toggle="dropdown" class="btn <?= $exibirAcoes ? 'btn-info' : 'btn-default'?> dropdown-toggle btn-sm" type="button"><?php echo __('label_acoes') ?> <span class="caret"></span></button>
                                                    <?php
                                                    if ($exibirAcoes):
                                                        ?>
                                                        <ul role="menu" class="dropdown-menu listaAcoes">
                                                            <li><?php echo $this->Html->link("Imprimir", array('action' => 'impressao', $id), array('class' => 'fa fa-print', 'title' => "Imprimir")); ?></li>
                                                            <?php if ($permitirEditar): ?>
                                                                <li><?php echo $this->Html->link(__('bt_reagendar'), array('action' => 'editar', $id), array('class' => 'fa fa-calendar', 'title' => __('bt_reagendar'))); ?></li>
                                                            <?php endif; ?>
                                                            <?php if ($permitirDeletar): ?>
                                                                <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deletar', $id), array('class' => 'fa fa-trash-o', 'title' => __('bt_excluir'))); ?></li>
                                                            <?php endif; ?>
                                                            <?php if ($permitirHomologar): ?>
                                                                <li><?php echo $this->Html->link(__('bt_homologar'), array('action' => 'homologar', $id), array('class' => 'fa fa-check-square', 'title' => __('bt_homologar'))); ?></li>
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
                                        <td colspan="6" style="text-align: center;">
                                            <?= __('nenhum_registro_encontrado') ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?= $this->element('paginator'); ?>
                </div>
            </div>
        </section>
    </div>
<?php endif; ?>