<?php if (isset($atendimentosPendentes)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('AtendimentosPendentes'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <style>
                            .tr-w td:nth-child(1){ width:100px; }
                            .tr-w td:nth-child(2){ width:100px; }
                            .tr-w th:nth-child(1){ width:100px; }
                            .tr-w th:nth-child(2){ width:100px; }
                        </style>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr class="tr-w">
                                    <th>Número Agendamento</th>
                                    <th>Número Atendimento</th>
                                    <th><?= __('atendimentos_pendentes_label_nome_servidor'); ?></th>
                                    <th><?= __('atendimentos_pendentes_label_tipologia'); ?></th>
                                    <th><?= __('atendimentos_pendentes_label_situacao'); ?></th>
                                    <th><?= __('atendimentos_pendentes_label_data_pericia'); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($atendimentosPendentes)):

                                    foreach ($atendimentosPendentes as $line) :
                                        $data = date('d/m/Y', strtotime($line['Atendimento']['data_inclusao']));
                                        $id = $line['Atendimento']['id'];
                                        $status = ($line['SituacaoParecer']['nome']) ? $line['SituacaoParecer']['nome'] : "";
                                        $tipologia_processo = isset($line['Agendamento']['tipologia_processo'])?$line['Agendamento']['tipologia_processo']:"";

                                        ?>
                                        <tr class="tr-w">
                                            <td><?=$line['Agendamento']['id'];  ?></td>
                                            <td><?= $id; ?></td>
                                            <td><?= $line['Servidor']['nome']; ?></td>
                                            <td><?= $line['Tipologia']['nome']. (!empty($tipologia_processo)?"<b> ($tipologia_processo)</b>":""); ?></td>
                                            <td><?= $status; ?></td>
                                            <td><?= $data; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_acoes') ?> <span class="caret"></span></button>
                                                    <ul role="menu" class="dropdown-menu listaAcoes">
                                                        <?php if ((isset($acao) && $acao != Configure::read('ACAO_VISUALIZAR')) || !isset($acao)): ?>
                                                            <?php if (Util::temPermissao($nameModel . ".visualizar")): ?>
                                                                <li><?php echo $this->Html->link(__('bt_visualizar'), array('action' => 'preVisualizar', $id), array('class' => 'fa fa-search', 'title' => __('bt_visualizar'))); ?></li>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php if (Util::temPermissao($nameModel . ".editar") && $status != "Finalizado"): ?>
                                                            <li><?php echo $this->Html->link(__('bt_alterar'), array('action' => 'preEditarAtendimento', $id), array('class' => 'fa fa-edit', 'title' => __('bt_alterar'))); ?></li>
                                                        <?php endif; ?>
                                                    </ul>
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