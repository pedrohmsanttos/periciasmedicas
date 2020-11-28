<div class="row">
    <div class="col-md-6">
        <?php
        echo $this->Form->input('usuario_perito_id', array(
            'type' => 'hidden',
            'id' => 'hidPeritoId'
        ));
        $urlAutoCompleteNome = Router::url(array('controller' => "Atendimento", 'action' => 'getNomeJuntaPerito'), true);

        echo
        $this->Form->input('nome_perito', array('label' => __('atendimento_laudo_label_nome_perito') . $isRequerid,
            'type' => 'text',
            'maxlength' => 255,
            'id' => 'inputNomeJuntaPerito',
            'data-url' => $urlAutoCompleteNome,
            'class' => 'form-control',
            'disabled' => $formDisabled));
        ?>
    </div>
    <div class="col-md-3">
        <?php
        $urlAutoCompleteMatricula = Router::url(array('controller' => "Atendimento", 'action' => 'getNumeroRegistroPerito'), true);

        echo
        $this->Form->input('numero_registro_perito', array('label' => __('atendimento_laudo_label_numero_registro') . $isRequerid,
            'type' => 'text',
            'id' => 'inputNumeroRegistroPerito',
            'maxlength' => 10,
            'data-url' => $urlAutoCompleteMatricula,
            'class' => 'form-control',
            'disabled' => $formDisabled));
        ?>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 ">
        <?php
        if (!$formDisabled):
            ?>
            <i class="btn fa fa-chain btn-success float-right" id="adicionarPerito" data-url="<?php echo Router::url('/web/Atendimento/', true); ?>"> <?= __('bt_adicionar'); ?></i>
            <?php
        endif;
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-hover table-bordered" id="tablePeritos">
            <thead>
                <tr>
                    <th style="width: 70%;"><?= __('atendimento_laudo_label_nome'); ?></th>
                    <th><?= __('atendimento_laudo_label_numero_registro'); ?></th>
                    <?php
                    if (!$formDisabled):
                        ?>
                        <th style="width: 5%;"></th>
                        <?php
                    endif;
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($peritos) && !empty($peritos)):
                    foreach ($peritos as $key => $line):
                        ?>
                        <tr class="">
                            <td style="width: 70%;">
                                <input type="hidden" name="data[Perito][Perito][]" value="<?=$line['Perito']['id'] ?>" >
                                <?= $line['Perito']['nome'] ?></td>
                            <td><?= $line['Perito']['numero_registro'] ?></td>
                            <?php
                            if (!$formDisabled):
                                ?>
                                <td style="width: 5%;"><div rel="<?= $line['Perito']['id'] ?>"
                                    data-url="<?php echo Router::url('/web/Atendimento/', true); ?>"
                                    class="btn deletarPerito fa btn-danger" title="Excluir">Excluir</div></td>
                                    <?php
                                endif;
                                ?>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
                <tr id="emptyPerito" class="<?= isset($peritos) && !empty($peritos) ? 'displayNone' : ''?>">
                    <td colspan="3" style="text-align: center;">
                        <?= __('nenhum_registro_encontrado') ?>
                    </td>
                </tr>
            </tbody>
        </table>
        </tbody>
        </table>
    </div>
</div>