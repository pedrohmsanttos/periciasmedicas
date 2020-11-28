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