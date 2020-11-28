<?php if (isset($gerenciarSalas)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('gerenciar_sala_salas'), 'idLimiteConsulta' => 'registros_pagina_sala')); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= __('gerenciar_sala_unidade')?></th>
                                    <th><?= __('gerenciar_sala_sala'); ?></th>
                                    <th><?= __('gerenciar_sala_perito'); ?></th>
                                    <th><?= __('gerenciar_sala_tipologias'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($gerenciarSalas)):
                                    foreach ($gerenciarSalas as $line) :
                                        $tipologias = "";
                                        foreach ($line['Tipologia'] as $key => $tipologia) :
                                            if($key > 0):
                                                $tipologias = $tipologias . ", ";
                                            endif;
                                            $tipologias = $tipologias . $tipologia['nome'];
                                        endforeach;
                                        ?>
                                        <tr class="">
                                            <td><?php echo $line['UnidadeAtendimento']['nome'] ?></td>
                                            <td><?php echo $line['GerenciamentoSala']['sala'] ?></td>
                                            <td><?php echo $line['UsuarioPerito']['nome'] ?></td>
                                            <td><?php echo $tipologias ?></td>
                                            <td style="width: 10%;">
                                                <div class="btn-group">
                                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_acoes') ?> <span class="caret"></span></button>
                                                    <ul role="menu" class="dropdown-menu listaAcoes">
                                                        <?php if (Util::temPermissao("GerenciamentoSala.deletar")): ?>
                                                            <li>
                                                                <a class="fa fa-times deleteSala"
                                                                   data-sala="<?php echo $line['GerenciamentoSala']['sala'];  ?>"
                                                                   data-url="<?= Router::url('/web/GerenciamentoSala/deletar/' . $line['GerenciamentoSala']['id'], true); ?>" 
                                                                   title=" <?= __('bt_limpar') ?>">
                                                                    <?= __('bt_limpar') ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
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
                    <?= $this->element('paginator', ['grid' => 'gridGerenciarSalas']); ?>
                </div>
            </div>
        </section>
    </div>
<?php endif; ?>