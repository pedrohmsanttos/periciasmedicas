<?php if (isset($orgaosOrigem)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('orgao_origem_label_orgaos'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 60%"><?= $this->Paginator->sort('nome', __('orgao_origem_label_orgaos'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 15%"><?= $this->Paginator->sort('sigla', __('orgao_origem_label_sigla'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 15%"><?= $this->Paginator->sort('cnpj', __('orgao_origem_label_cnpj'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($orgaosOrigem)):
                                    foreach ($orgaosOrigem as $line) :
                                        ?>
                                        <tr class="">
                                            <td><?= $line['OrgaoOrigem']['orgao_origem']; ?></td>
                                            <td><?= $line['OrgaoOrigem']['sigla']; ?></td>
                                            <td><?= Util::mask($line['OrgaoOrigem']['cnpj'],'##.###.###/####-##'); ?></td>
                                            <td><?= $this->element('botoes-default-grid', array('id' => $line['OrgaoOrigem']['id'])); ?></td>
                                        </tr>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr class="">
                                        <td colspan="6" style="text-align: center;">
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