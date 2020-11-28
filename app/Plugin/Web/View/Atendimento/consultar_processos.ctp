<div class="col-sm-12">
    <section class="panel">
        <?= $this->element('cabecalho-tabela'); ?>
        <div class="panel-body">
            <div class="adv-table editable-table ">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                            <tr>
                                <th style="text-align: center"><?= $this->Form->checkBox('selecionarTodos', array('class' => 'selecionarTodos')) ?></th>
                                <th>Número Atendimento</th>
                                <th style="width:20%">Servidor</th>
                                <th style="width: 30%;"><?= __('processo_label_tipologia'); ?></th>
                                <th>Status Atendimento</th>
                                <th><?= __('processo_label_data_pericia'); ?></th>
                                <th><?= __('processo_label_status'); ?></th>
                                <th><?= __('processo_label_periodo_concedido'); ?></th>
                                <th><?= __('processo_label_detalhes'); ?></th>
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
                                        <td  style="text-align: center"><?= $this->Form->checkBox('processoSelecionado', array('data-id' => $id, 'class' => 'selecionarProcesso processo_'.$id)) ?></td>
                                        <td><?= $id ?></td>
                                        <td><?=$line['Usuario']['nome']; ?></td>
                                        <td><?= $line['Tipologia']['nome']. $recursoTipologia; ?></td>
                                        <td><?= $line['Atendimento']['status_atendimento'] ?></td>
                                        <td><?= $data; ?></td>
                                        <td><?= $line['TipoSituacaoParecerTecnico']['nome'] ?></td>
                                        <td><?= ($line['Atendimento']['duracao'])?$line['Atendimento']['duracao']." dias":"" ?></td>

                                        <td style="text-align: center">
                                            <?php $perfilUsuario = CakeSession::read('perfil');
                                            if($perfilUsuario != PERFIL_ADMINISTRATIVO){
                                                $class = "detalharAtendimento";
                                                $icon = "search";
                                                $url = Router::url(array('controller' => "Atendimento", 'action' => 'detalharAtendimento'));
                                            }else{
                                                $class ="detalharPublicacao";
                                                $icon = "download";
                                                $url = Router::url(['controller' => 'Atendimento', 'action' => 'download_laudo', $id], true);
                                            } ?>
                                            <li class="fa fa-<?=$icon?> fa-2x <?=$class?>"
                                                style="cursor: pointer;"
                                                data-url="<?=$url ?>"
                                                data-id="<?= $id ?>"
                                                data-acao='index_processos'
                                                data-anterior=""></li>
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
                <div class="row">
                    <div class="col-md-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div class="row float-right btn-edit">
                                    <?php
                                    $urlCadastro = Router::url(array('controller' => "Agendamento", 'action' => 'adicionar'));

                                    echo $this->Form->button(__("bt_novo", __('Agendamento')), array(
                                        'class' => 'btn fa fa-calendar-o estiloBotao btn-success',
                                        'type' => 'button',
                                        'onclick' => "location.href = '$urlCadastro'"
                                    ));
                                    ?>
                                    <?php
                                    echo $this->Form->button(__('bt_baixar_processos'), array(
                                        'class' => 'btn fa fa-download estiloBotao btn-success displayNone',
                                        'value' => 'true',
                                        'id' => 'exportar_processos',
                                        'rel' => Router::url('exportar_processos/', true),
                                        'name' => 'salvarButton'
                                    ));
                                    ?>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <?php ?>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
            </div>

        </div>
    </section>
</div>