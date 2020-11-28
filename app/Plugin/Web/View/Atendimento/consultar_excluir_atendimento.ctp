<div class="col-sm-12">
    <section class="panel">
        <?= $this->element('cabecalho-tabela'); ?>
        <div class="panel-body">
            <div class="adv-table editable-table ">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                        <tr>
                            <!-- <th style="text-align: center"><?= $this->Form->checkBox('selecionarTodos', array('class' => 'selecionarTodos')) ?></th>-->
                            <th>Número Atendimento</th>
                            <th style="width:20%">Servidor</th>
                            <th style="width: 30%;"><?= __('processo_label_tipologia'); ?></th>
                            <th>Status Atendimento</th>
                            <th><?= __('processo_label_data_pericia'); ?></th>
                            <th><?= __('processo_label_status'); ?></th>
                            <th><?= __('Excluir'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($processos)):

                            foreach ($processos as $line) :

                                $id = $line['Atendimento']['id'];
                                $data = date("d/m/Y", strtotime($line['Atendimento']['data_inclusao']));
                                $recursoTipologia = '';
                                if(!empty($line['RecursoTipologia']['nome'])){
                                    $recursoTipologia = ' ('.$line['RecursoTipologia']['nome'] . ')';
                                }
                                ?>
                                <tr class="">
                                    <!--<td  style="text-align: center"><?= $this->Form->checkBox('processoSelecionado', array('data-id' => $id, 'class' => 'selecionarProcesso processo_'.$id)) ?></td>-->
                                    <td><?= $id ?></td>
                                    <td><?=$line['Servidor']['nome']; ?></td>
                                    <td><?= $line['Tipologia']['nome']. $recursoTipologia; ?></td>
                                    <td><?= $line['Atendimento']['status_atendimento'] ?></td>
                                    <td><?= $data; ?></td>
                                    <td><?= $line['TipoSituacaoParecerTecnico']['nome'] ?></td>
                                    <td style="text-align: center">
                                        <li class="fa fa-minus-circle fa-2x excluirAtendimento"
                                            style="cursor: pointer;"
                                            data-url="<?= Router::url(array('controller' => "Atendimento", 'action' => 'deletar', $id) ) ?>"
                                            data-id="<?=$id?>"
                                            data-acao='deletar'></li>
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
                <?= $this->element('paginator'); ?>
            </div>

        </div>
    </section>
</div>
