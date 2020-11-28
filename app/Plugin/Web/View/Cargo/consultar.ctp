<?php if (isset($cargos)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('cargo_label_cargos'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 90%"><?= $this->Paginator->sort('nome', __('cargo_label_nome'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 90%"><?= $this->Paginator->sort('sigla', __('cargo_label_sigla'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($cargos)): 
                                    foreach ($cargos as $line) :
                                        ?>
                                        <tr class="">
                                            <td><?= $line['Cargo']['nome']; ?></td>
                                            <td><?= $line['Cargo']['sigla']; ?></td>
                                            <td><?= $this->element('botoes-default-grid', array('id' => $line['Cargo']['id'], 'model' => 'Cargo')); ?></td>
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