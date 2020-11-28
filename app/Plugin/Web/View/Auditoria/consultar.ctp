<?php

if (isset($auditoria)) : ?>
    <div class="col-sm-12">
        <section class="panel">
            <?= $this->element('cabecalho-tabela', array('cabecalho' => __('Usuarios'))); ?>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 5%"><?= $this->Paginator->sort('id', __('id'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 50%"><?= $this->Paginator->sort('nome', __('usuario_auditoria'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 20%"><?= $this->Paginator->sort('area_sistema', __('area_sistema'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 15%"><?= $this->Paginator->sort('tipo_operacao', __('tipo_operacao'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 5%"><?= $this->Paginator->sort('ip_auditoria', __('ip_auditoria'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 5%"><?= $this->Paginator->sort('funcao_auditoria', __('funcao_auditoria'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <th style="width: 5%"><?= $this->Paginator->sort('data_auditoria', __('data_auditoria'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    
                                    <?php if($labelAtendimento == 'atendimento' && empty($labelAgendamento) ): ?>
                                        <th style="width: 5%"><?= $this->Paginator->sort('pk_log', __('N° Atendimento'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <?php endif; ?>

                                    <?php if($labelAgendamento == 'agendamento' && empty($labelAtendimento) ): ?>
                                        <th style="width: 5%"><?= $this->Paginator->sort('pk_log', __('N° Agendamento'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <?php endif; ?>

                                    <?php if( empty($labelAgendamento) && empty($labelAtendimento) ||  $labelAgendamento == "agendamento" && $labelAtendimento == "atendimento" ): ?>
                                        <th style="width: 5%"><?= $this->Paginator->sort('pk_log', __('ID Área'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <?php endif; ?>
                                    
                                    <?php if(  $tipo_operacao == "E" && (  ( $labelAtendimento == 'atendimento' ) ||  ( $labelAtendimento == 'atendimento' &&  $labelAgendamento == 'agendamento' ) ) ): ?>
                                        <th style="width: 5%"><?= $this->Paginator->sort('motivo_exclusao', __('Motivo Exclusão'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                        <th style="width: 5%"><?= $this->Paginator->sort('usuario_exclusao', __('Usuário Exclusão'), array('update' => '#grid', 'evalScripts' => true)); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($auditoria)):
                                    foreach ($auditoria as $line) :
                                        ?> 
                                        <tr class="">
                                            <td><?= $line['Auditoria']['id']; ?></td>
                                            <td><?= $line['UsuarioAlteracao']['nome']; ?></td>
                                            <td><?= $line['Auditoria']['area_sistema']; ?></td>
                                            <td><?
                                                $oper = $line['Auditoria']['operacao'];
                                                $descOper = "";
                                                switch($oper){
                                                    case 'I':
                                                        $descOper = "INSER&Ccedil;&Atilde;O";
                                                        break;
                                                    case 'A':
                                                        $descOper = "ALTERA&Ccedil;&Atilde;O";
                                                        break;
                                                    case 'E':
                                                        $descOper = "EXCLUS&Atilde;O";
                                                        break;
													case 'C':
                                                        $descOper = "CONSULTA";
                                                        break;
													case 'V':
														$descOper = "VISUALIZA&Ccedil;&AtildeO";	
														break;
                                                }

                                            echo $descOper;

                                                ?></td>
                                            <td><?= $line['Auditoria']['ip']; ?></td>
                                            <td><?= $line['Auditoria']['nome_funcao']; ?></td>
                                            <td><?= Util::inverteDataComHora($line['Auditoria']['data_inclusao']); ?></td>
                                            
                                            <?php if($labelAgendamento == 'agendamento' && empty($labelAtendimento) ): ?>
                                                <td><a href="Agendamento/visualizar/<?= $line['Auditoria']['pk_log']; ?>" target="_blank" ><?= $line['Auditoria']['pk_log']; ?></a></td>
                                            <?php endif; ?>

                                            <?php if($labelAtendimento == 'atendimento' && empty($labelAgendamento) ): ?>
                                                <td><a href="Atendimento/preVisualizar/<?= $line['Auditoria']['pk_log']; ?>" target="_blank" ><?= $line['Auditoria']['pk_log']; ?></a></td>
                                            <?php endif; ?>

                                            <?php if($labelAtendimento == 'atendimento' && $labelAgendamento == 'agendamento'): ?>
                                            
                                                <?php if($line['Auditoria']['area_sistema'] == "ATENDIMENTO"): ?>
                                                    <td><a href="Atendimento/preVisualizar/<?= $line['Auditoria']['pk_log']; ?>" target="_blank" ><?= $line['Auditoria']['pk_log']; ?></a></td>
                                                <?php endif; ?>

                                                <?php if($line['Auditoria']['area_sistema'] == "AGENDAMENTO"): ?>
                                                    <td><a href="Agendamento/visualizar/<?= $line['Auditoria']['pk_log']; ?>" target="_blank" ><?= $line['Auditoria']['pk_log']; ?></a></td>
                                                <?php endif; ?>

                                                <?php if($line['Auditoria']['area_sistema'] != "AGENDAMENTO" && $line['Auditoria']['area_sistema'] != "ATENDIMENTO"): ?>
                                                    <td><?= $line['Auditoria']['pk_log']; ?></td>
                                                <?php endif; ?>

                                            <?php endif; ?>

                                            <?php if(  $tipo_operacao == "E" && ( $labelAtendimento == 'atendimento' || ( $labelAtendimento == 'atendimento' &&  $labelAgendamento == 'agendamento' ) ) ): ?>
                                                <td><?= $line['Atendimento']['motivo_exclusao']; ?></td>
                                                <td><?= $line['UsuarioExclusao']['nome']; ?></td>
                                            <?php endif; ?>
                                        </tr>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr class="">
                                        <td colspan="8" style="text-align: center;">
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