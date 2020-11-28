<?php

if (isset($agendaSistema)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => 'Agenda do Sistema')); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 5%"><?= $this->Paginator->sort('id', 'Id', array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 50%"><?= $this->Paginator->sort('descricao', 'Descrição', array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 20%"><?= $this->Paginator->sort('prazo_inicial', 'Validade Incial', array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 15%"><?= $this->Paginator->sort('prazo_final', 'Validade Final', array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 5%"><span style="color:#223">Validada</span></th>
                                    <th style="width: 5%"><span style="color:#223">Habilitada</span></th>
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($agendaSistema)):
                                    foreach ($agendaSistema as $line) :
                                        $validado = $line['AgendaSistema']['validada'];
                                        $habilitada = $line['AgendaSistema']['habilitada'];
                                        ?> 
                                        <tr class="">
                                            <td><?= $line['AgendaSistema']['id']; ?></td>
                                            <td><?= $line['AgendaSistema']['descricao']; ?></td>
                                            <td><?= Util::toBrData($line['AgendaSistema']['prazo_inicial']); ?></td>
                                            <td><?= Util::toBrData($line['AgendaSistema']['prazo_final']); ?></td>
                                            <td style="text-align: center"><span class="glyphicon glyphicon-<?=$validado?"ok":"ban-circle";?> <?=$validado?"green":"red" ?>"></span></td>
                                            <td style="text-align: center"><span class="glyphicon glyphicon-<?=$habilitada?"ok":"ban-circle";?> <?=$habilitada?"green":"red" ?>"></span></td>
                                            <td><?= $this->element('botoes-default-grid', array('id' => $line['AgendaSistema']['id'], 'model' => 'AgendaSistema')); ?></td>
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
    <script>
        $('body .listaAcoes [id^=btn-excluir]').each(function(){
            $(this).on('click', function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                if($(this).attr('ok') =="1"){
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "html",
                        success: function (response) {
                            $('#formularioConsulta button:submit').click();
                        },
                        error: function (response) {
                        }
                    });
                }
            });
        });
    </script>
<?php endif; ?>