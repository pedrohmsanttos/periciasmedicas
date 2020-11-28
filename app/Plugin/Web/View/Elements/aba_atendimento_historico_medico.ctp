<div class="row">
    <div class="col-md-12">
        <p>Caso esteja faltando alguma informação do servidor em atendimento, clique em "Procurar Servidor"</p>
    </div>
</div>
<div class="row">
    <div class="col-md-2" style="padding:10px">
        <button id="btn-procurarServidorModal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#procurarServidorModal">
            Procurar Servidor
        </button>
    </div>
    <div class="col-md-10" style="padding:15px">
        <input type="hidden" id="id_serv_escolhido">
        <span id="serv_escolhido" class="input-sm"><?=$servidorHistAjax?></span>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-hover table-bordered" id="tableHistoricoMedico">
            <thead>
            <tr>

                <th><?= __('atendimento_historico_medico_data'); ?></th>
                <th><?= __('atendimento_historico_medico_procedimento'); ?></th>
                <th><?= __('atendimento_historico_medico_situacao'); ?></th>
                <th><?= __('atendimento_laudo_label_detalhes'); ?></th>

            </tr>
            </thead>
            <tbody>
            <?php if (isset($listHistoricoMedico)):
                if (!empty($listHistoricoMedico)):
                    foreach ($listHistoricoMedico as $key => $line) :
                        $data = date('d/m/Y', strtotime($line['Requerimentos']['datarequerimento']));
                        $id = $line['Requerimentos']['unrequerimento'];
                        ?>
                        <tr class="rowHistorico">

                            <td><?= $data; ?></td>
                            <td><?= $line['Assuntos']['descricao']; // procedimento ?></td>
                            <td><?= $line['Requerimentos']['statusrequerimento']; ?></td>
                            <td style="text-align: center">
                                <?php echo $this->Html->link('', array('action' => 'historicoMedico', $id), array('class' => 'fa fa-search fa-2x ')); ?>

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
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php echo $this->element('procura_servidor'); ?>

