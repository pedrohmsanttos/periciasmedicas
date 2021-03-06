<?php if (isset($tipologia)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('Tipologias'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 90%"><?= $this->Paginator->sort('nome', __('tipologia_label_nome'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($tipologia)):
                                    $tipologiaModel = new Tipologia();
                                    foreach ($tipologia as $line) :
                                        $id = $line['Tipologia']['id'];
                                        ?>
                                        <tr class="">
                                            <td><?= $line['Tipologia']['nome']; ?></td>
                                            <td><?= $this->element('botoes-default-grid', array('id' => $id, 'bloquearExclusao' => $tipologiaModel->isTipologiaDefault($id))); ?></td>
                                        </tr>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr class="">
                                        <td colspan="4" style="text-align: center;">
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