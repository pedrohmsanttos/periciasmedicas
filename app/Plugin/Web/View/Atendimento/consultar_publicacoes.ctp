<?php //pr($publicacoes);die; ?>
<div class="col-sm-12">
    <section class="panel">
        <?= $this->element('cabecalho-tabela'); ?>
        <div class="panel-body">
            <div class="adv-table editable-table ">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                        <tr>
                            <th>Número Publicação</th>
                            <th>Data Inicial</th>
                            <th>Data Final</th>
                            <th>Usuário</th>
                            <th>Data Publicação</th>
                            <th>Detalhes</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php


                        if (!empty($publicacoes)):
                            // pr($publicacoes);
                            foreach ($publicacoes as $line) :
                                // pr($line);
                                $id = $line['Publicacao']['id'];
                                $dataIni = Util::toBrDataHora($line['Publicacao']['data_inicial']);
                                $dataFim = Util::toBrDataHora($line['Publicacao']['data_final']);
                                $data = date("d/m/Y H:i:s", strtotime(Util::toDBDataHora($line['Publicacao']['data_publicacao'])));

                                ?>
                                <tr class="">
                                    <td><?= $id ?></td>
                                    <td><?= $dataIni; ?></td>
                                    <td><?= $dataFim; ?></td>
                                    <td><?=$line['UsuarioVersao']['nome'] ?></td>
                                    <td><?= $data; ?></td>
                                   <td style="text-align: center">
                                        <li class="fa fa-search fa-2x detalharPublicacao"
                                           style="cursor: pointer;"
                                           data-url="<?= Router::url(array('controller' => "Atendimento", 'action' => 'visualizarProcessosPublicacao/'.$id )) ?>"
                                           data-id="<?= $id ?>"
                                           data-acao='visualizarProcessosPublicacao'
                                           data-anterior=""></li></td>
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