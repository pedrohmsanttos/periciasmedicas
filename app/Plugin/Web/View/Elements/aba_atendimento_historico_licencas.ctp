<div class="row">
    <div class="col-md-12">
    <div class="displayNone">
        <?php
        echo $this->Form->select("procesos_selecionados", array(), array('multiple' => true, 'id' => 'procesos_selecionados'));
        ?>
    </div>
        </div>
    <div class="col-md-12">
        <table class="table table-striped table-hover table-bordered " id="tableAtendimentosAssociados">
            <thead>
                <tr>
                    <th style="text-align: center"><?= $this->Form->checkBox('selecionarTodos', array('class' => 'selecionarTodos')) ?></th>
                    <th><?= __('atendimento_laudo_label_numero'); ?></th>
                    <th><?= __('atendimento_laudo_label_tipologia'); ?></th>
                    <th>Status Atendimento</th>
                    <th><?= __('atendimento_laudo_label_data_pericia'); ?></th>
                    <th><?= __('atendimento_laudo_label_status'); ?></th>
                    <th><?= __('atendimento_laudo_label_periodo_concedido'); ?></th>
                    <th style="text-align: center"><?= __('atendimento_laudo_label_detalhes'); ?></th>
                    <th style="text-align: center"><?= __('atendimento_laudo_label_associar'); ?></th>
                    <th style="text-align: center"><?= __('atendimento_laudo_label_laudo'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($historicoLicencas)): ?>
                    <tr id="emptyVinculo" class="displayNone">
                        <td colspan="7" style="text-align: center;">
                            <?= __('nenhum_registro_encontrado') ?>
                        </td>
                    </tr>
                    <?php
                    if (!empty($historicoLicencas)):
                        foreach ($historicoLicencas as $key => $line) :
                            $data = date('d/m/Y', strtotime($line['Atendimento']['data_inclusao']));
                            $id = $line['Atendimento']['id'];
                            $associado = $id == $this->request->data['Atendimento']['atedimento_pai_id'] ? true : false;
                            $action = $this->params['action'];
                            ?>
                            <tr class="rowHistorico">
                                <td  style="text-align: center"><?= $this->Form->checkBox('processoSelecionado', array('data-id'=>$id,'class'=>'selecionarProcesso processo_'.$id)) ?></td>
                                <td><a class="fa fa-search <?=(!isset($isModal))?"modalLink":'' ?>" href="#" data-id="<?= $id; ?>">&nbsp;<?= $id; ?></a></td>
                                <td><?= $line['Tipologia']['nome']; ?></td>
                                <td><?= $line['Atendimento']['status_atendimento'] ?></td>
                                <td><?= $data; ?></td>
                                <td><?= $line['TipoSituacaoParecerTecnico']['nome']; ?></td>
                                <td><?= isset($line['Atendimento']['duracao'])?$line['Atendimento']['duracao']." dias":"" ?></td>
                                <td style="text-align: center">
                                    <?php
                                    $idAtendimentoPai = $line['Atendimento']['atedimento_pai_id'];
                                    if (!is_null($idAtendimentoPai)):
                                        ?>
                                    <li class="fa fa-search fa-2x detalharAtendimento"
                                        style="cursor: pointer;"
                                        data-url="<?= Router::url(array('controller' => "Atendimento", 'action' => 'detalharAtendimento')) ?>"
                                        data-id="<?= $id ?>"
                                        data-acao='<?= $currentAction ?>'
                                        data-anterior="<?= $this->request->data['Atendimento']['id'] ?>"></li>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center">
                                    <?php
                                    $idSituacao = $line['TipoSituacaoParecerTecnico']['id'];
                                    if ($idSituacao == TipoSituacaoParecerTecnico::EM_EXIGENCIA):
                                        echo $this->Form->checkBox('associar', array('class' => 'checkAssociar', 'data-id' => $id, 'checked' => $associado, 'disabled' => !($action === 'editar')));
                                    endif;
                                    ?>
                                </td>
                                <td style = "text-align: center">
                                    <?php
                                    $tipologia = $line['Tipologia']['id'];
                                    if($line['Atendimento']['status_atendimento'] == 'Finalizado'): ?>
                                        <?php $urlLaudo = Router::url(['controller' => 'Atendimento', 'action' => 'download_laudo', $id], true); ?>
                                        <i class = "btn fa fa-2x fa-download downloadLaudo" data-url="<?php echo $urlLaudo; ?>" data-id="<?php echo $id ?>"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">
                            <?= __('nenhum_registro_encontrado') ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        <?php else: ?>
            <tr>
                <td colspan="9" style="text-align: center;">
                    <?= __('nenhum_registro_encontrado') ?>
                </td>
            </tr>
            </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php
if (!isset($complementoIdTabs)):
    ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo $this->Form->button(__('bt_baixar_processos'), array(
                'class' => 'btn fa fa-download estiloBotao btn-success float-right displayNone',
                'value' => 'true',
                'type' => 'button',
                'id' => 'exportar_processos',
                'rel' => Router::url(array('controller'=>'Atendimento','action'=>'exportar_processos'), true),
                'name' => 'salvarButton'
            ));
            ?>
        </div>
    </div>
    <?php
endif;
?>
<div id="dialog-detalhamento_exigencia" data-index="1" class="displayNone panel">
    <div class="conteudoDetalhamento" style="height: 100%"></div>
</div>
<div id="modalTestId" class="modal fade modalHistorico" tabindex="-1" role="dialog" style="background: none">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <p>TESTE!!</p>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    $('#tableAtendimentosAssociados').on('click', ".modalLink", function(e){
        e.preventDefault();
        $.ajax({
            url:"visualizarAtendimento/"+$(this).data('id'),
            success: function (d) {
                window.ajaxContent = d;
                vex.open({
                    className: "vex-theme-os padtb10",
                    unsafeContent: d,
                    contentClassName:"vexContentW"
                });
            }
        });
    });
</script>