<?php if (isset($lotacao)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('lotacao_label_lotacao'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 60%"><?= __('lotacao_label_nome') ;?></th>
                                    <th style="width: 20%"><?= __('lotacao_label_orgao'); ?></th>
                                    <th style="width: 20%"><?= __('lotacao_label_municipio'); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($lotacao)): 
                                    foreach ($lotacao as $line) :
                                        ?>
                                        <tr class="">
                                            <td><?= $line['Lotacao']['nome']; ?></td>
                                            <td><?= $line['OrgaoOrigem']['orgao_origem']; ?></td>
                                            <td><?= $line['Municipio']['nome']; ?></td>
                                            <td><?= $this->element('botoes-default-grid', array('id' => $line['Lotacao']['id'])); ?></td>
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