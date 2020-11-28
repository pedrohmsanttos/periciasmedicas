<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading"> <?php echo __('cabecalho_grid', __('Agendamentos')) ?>
        </header>
        <div class="panel-body">
            <div class="adv-table editable-table ">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                            <tr>
                                <th>Nome do Servidor</th>
                                <th><?= __('agendamento_input_dia_hora_consulta_servidor'); ?></th>
                                <th>Número Agendamento</th>
                                <th>Número Atendimento</th>
                                <th><?= __('agendamento_input_tipologia'); ?></th>
                                <th><?= __('agendamento_input_unidade_atendimento'); ?></th>
                                <th>Status</th>
                                <?= $this->element('botoes-default-grid-title'); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($agendamentos)):
                                $idUsuario = CakeSession::read('Auth.User.id');


                                foreach ($agendamentos as $line) :
                                    $id = $line['Agendamento']['id'];
                                    $data = Util::toDBDataHora($line['Agendamento']['data_hora']);
                                    $data = date("d/m/Y H:i", strtotime($data));
                                    if(in_array($line['Tipologia']['id'],array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO))){
                                        $data="";
                                    }

                                    $chefiaMediata1 = $line['Agendamento']['chefe_imediato_um_id'];
                                    $chefiaMediata2 = $line['Agendamento']['chefe_imediato_dois_id'];
                                    $chefiaMediata3 = $line['Agendamento']['chefe_imediato_tres_id'];
                                    $arrChefiaMediata = array($chefiaMediata1,$chefiaMediata2,$chefiaMediata3);

                                    //$permitirHomologar = in_array($idUsuario,$arrChefiaMediata);
                                    $permitirHomologar = (in_array($idUsuario,$arrChefiaMediata))&&(in_array($line['Tipologia']['id'], array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE,TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO)));

                                    unset($arrChefiaMediata);


                                    $idAtendimento = isset($line['Atendido']['id'])?$line['Atendido']['id']:'';
                                    ?>
                                    <tr class="">
                                        <td><?=$line['UsuarioServidor']['nome'] ?></td>
                                        <td><?= $data; ?></td>
                                        <td><?= $line['Agendamento']['protocolo'] ?></td>
                                        <td><?=$idAtendimento?></td>
                                        <td><?= $line['Tipologia']['nome']; ?></td>
                                        <td><?= $line['UnidadeAtendimento']['nome']; ?></td>
                                        <td><?= ((!empty($line['Agendamento']['homologa']))?'<b>Homologado </b>/ ':'').$line['Agendamento']['status_agendamento'] ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_acoes') ?> <span class="caret"></span></button>
                                                <ul role="menu" class="dropdown-menu listaAcoes">
                                                    <li><?php echo $this->Html->link("Imprimir", array('action' => 'impressao', $id), array('class' => 'fa fa-print', 'title' =>"Imprimir")); ?></li>
                                                    <?php if (Util::temPermissao($nameModel . ".editar") && !$permitirHomologar): ?>
                                                        <li><?php echo $this->Html->link(__('bt_reagendar'), array('action' => 'editar', $id), array('class' => 'fa fa-calendar', 'title' => __('bt_reagendar'))); ?></li>
                                                    <?php endif; ?>
                                                    <?php if (Util::temPermissao($nameModel . ".deletar") && !$permitirHomologar): ?>
                                                        <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deletar', $id), array('class' => 'fa fa-trash-o', 'title' => __('bt_excluir'))); ?></li>
                                                    <?php endif; ?>
                                                    <?php if ($permitirHomologar): ?>
                                                        <li><?php echo $this->Html->link(__('bt_homologar'), array('action' => 'homologar', $id), array('class' => 'fa fa-check-square', 'title' => __('bt_homologar'))); ?></li>
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
                                    <td colspan="6" style="text-align: center;">
                                        <?= __('nenhum_registro_encontrado') ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?php
                    echo $this->element('botao_novo');
                    ?>
                </div>
            </div>

        </div>
    </section>
</div>