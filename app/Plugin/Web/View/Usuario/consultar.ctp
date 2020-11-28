<?php if (isset($usuarios)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('Usuarios'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 50%"><?= $this->Paginator->sort('nome', __('usuario_label_nome'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 20%"><?= $this->Paginator->sort('cpf', __('usuario_label_cpf'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 15%"><?= $this->Paginator->sort('numero_registro', __('usuario_label_numero_registro'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 5%"><?= $this->Paginator->sort('ativado', __('usuario_label_ativo'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($usuarios)):
                                    foreach ($usuarios as $line) :
                                        ?> 
                                        <tr class="">
                                            <td><?= $line['Usuario']['nome']; ?></td>
                                            <td><?= Util::mask($line['Usuario']['cpf'], "###.###.###-##"); ?></td>
                                            <td><?= $line['Usuario']['numero_registro']; ?></td>
                                            <td><?= ($line['Usuario']['ativado']) ? "<i class='fa green-text fa-2x fa-check'></i>" : "<i class='fa fa-2x red-text fa-times'></i>"; ?></td>
                                            <td><?= $this->element('botoes-default-grid', array('id' => $line['Usuario']['id'], 'model' => 'Usuario')); ?></td>
                                        </tr>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr class="">
                                        <td colspan="7" style="text-align: center;">
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