<?php if (isset($feriados)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('feriado_label_feriado'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 90%"><?= $this->Paginator->sort('nome', __('feriado_label_nome'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 90%"><?= $this->Paginator->sort('data_feriado', __('feriado_label_data'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($feriados)): 
                                    foreach ($feriados as $line) :
                                        ?>
                                        <tr class="">
                                            <td>
                                                <? echo ( $line['Feriado']['feriado_recorrente'] == '1' ) ? $line['Feriado']['nome'] . "<strong> (Feriado Recorrente)</strong>" : $line['Feriado']['nome']; ?>
                                            </td>
                                            <td><?= Util::inverteData($line['Feriado']['data_feriado']); ?></td>
                                            <td><?= $this->element('botoes-default-grid', array('id' => $line['Feriado']['id'], 'model' => 'Feriado')); ?></td>
                                        </tr>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr class="">
                                        <td colspan="5" style="text-align: center;">
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