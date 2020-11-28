<?php if (isset($cids)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('cid_label_cid'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 20%"><?= $this->Paginator->sort('cid', __('cid_label_cid'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 20%"><?=  __('cid_label_nome_doenca'); ?></th>
                                    <th style="width: 60%"><?=  __('cid_label_especialidades_associadas'); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($cids)):
                                    foreach ($cids as $line) :
                                        ?>
                                        <tr class="">
                                            <td><?= $line['Cid']['nome']; ?></td>
                                            <td><?= $line['Cid']['nome_doenca']; ?></td>
                                            <td><?= $line[0]['especialidade_nome']; ?></td>
                                            <td><?= $this->element('botoes-default-grid', array('id' => $line['Cid']['id'], 'model' => 'Cid')); ?></td>
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