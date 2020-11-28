<?php if (isset($perfis)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('perfil_label_perfis'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 90%"><?= $this->Paginator->sort('nome', __('perfil_label_nome'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 5%"><?= $this->Paginator->sort('ativado', __('perfil_label_ativo'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
            						<?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($perfis)):
                                    foreach ($perfis as $line) :
                                        ?>
                                        <tr class="">
                                            <td><?= $line['Perfil']['nome']; ?></td>
                                            <td><?= ($line['Perfil']['ativado']) ? "<i class='fa green-text fa-2x fa-check'></i>" : "<i class='fa fa-2x red-text fa-times'></i>"; ?></td>
                                            <td><?= $this->element('botoes-default-grid', array('id' => $line['Perfil']['id'], 'model' => 'Perfil')); ?></td>
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