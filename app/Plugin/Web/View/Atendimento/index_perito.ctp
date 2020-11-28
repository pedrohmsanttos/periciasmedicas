<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('atendimento_perito_titulo'))); ?>

            <div class="panel-body">
                <?=
                $this->Form->create('Agendamento', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => 'Agendamento', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?= $this->Form->label(null, __('atendimento_perito_label_unidade_atendimento') . ': ' . $unidadeAtendimento); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?= $this->Form->label(null, __('atendimento_perito_label_tipologia') . ': ' . $tipologia); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $urlChamarProximo = Router::url(array('controller' => "$controller", 'action' => 'atenderProximo'));

                        echo $this->Form->button(__('bt_atender_proximo'), array(
                            'class' => 'btn fa fa-person estiloBotao btn-success float-right',
                            'type' => 'button',
                            'onclick' => "location.href = '$urlChamarProximo'"
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading"> <?php echo __('cabecalho_grid', __('atendimento_perito_label_servidores')) ?>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 46%;"><?= __('atendimento_perito_label_nome'); ?></th>
                                    <th style="width: 24%;"><?= __('atendimento_perito_label_tipologia'); ?></th>
                                    <th style="width: 18%;">Status</th>
                                    <th style="width: 6%;text-align: center;"><?= __('atendimento_perito_label_horario'); ?></th>
                                    <th style="width: 6%;text-align: center;"><?= __('atendimento_perito_label_presente'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($agendamentos)):
                                    foreach ($agendamentos as $line) :
                                        // pr($line);
                                        $id = $line['Agendamento']['id'];
                                        $hora = Util::toDBDataHora($line['Agendamento']['data_hora']);
                                        if(!empty($hora)){
                                            $hora = date("H:i", strtotime($hora));
                                        }else{
                                            $hora = '';
                                        }
                                        if($line['Agendamento']['tempo_consulta']>=1)$hora = 'Prior.';
                                        $status = '';
                                        if( in_array($line['Agendamento']['tipologia_id'], array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO)) ) {
                                            $status = 'Aguardando homologação da chefia imediata';
                                            if (isset($line['Agendamento']['homologa'])){
                                                $homologa = $line['Agendamento']['homologa'];
                                                if($homologa === 1){
                                                    $status = 'Homologação aceita';
                                                }else if($homologa === 0){
                                                    $status = 'Homologação recusada';
                                                }
                                            }
                                        }
                                        $tipologiaRecurso = '';
                                        if($line['Agendamento']['tipologia_id'] == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
                                            $tipologiaRecurso = ' ('.$line['TipologiaRecurso']['nome'].')';
                                        }


                                        $labelEncaixe = "";
                                        if(!is_null($line['Agendamento']['encaixe']) && $line['Agendamento']['encaixe'] == "1"){
                                            $labelEncaixe = " (ENCAIXE)";
                                        } 

                                        ?>

                                        
                                        <tr class="">
                                            <td><?= $line['UsuarioServidor']['nome'] . $labelEncaixe; ?></td>
                                            <td><?= $line['Tipologia']['nome'].$tipologiaRecurso ?></td>
                                            <td><?= $status?></td>
                                            <td style="text-align: center;"><?= $hora; ?></td>
                                            <td style="text-align: center;"><?= ($line['Agendamento']['agendamento_confirmado']) ? "<i class='fa green-text fa-2x fa-check'></i>" : "<i class='fa fa-2x red-text fa-times'></i>"; ?></td>
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
                </div>

            </div>
        </section>
    </div>
</div>
<? //echo $this->Html->script('Admin.atendimentoPerito', array('block' => 'script')); ?>
<? echo $this->Html->script('Web.atendimentoPerito', array('block' => 'script')); ?>