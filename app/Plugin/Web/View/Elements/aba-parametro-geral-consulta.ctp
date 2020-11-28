<div class="row col-lg-12">
    <div class="col-md-5">
        <?php
        echo $this->Form->input('tempo_consulta', array('div' => array('class' => 'form-group'),
            'class' => 'form-control soNumero',
            'size' => '4',
            'label' => __('parametro_geral_tempo_consulta_medica')));
        ?>
    </div>
</div> 
    
    <div class="row col-sm-12">
        <section class="panel">
            <header class="panel-heading"> 
			    <div class="row">
			        <div class="col-md-10">
			            <div class="panel-title">Tempo de Consulta do Atendimento (demais Tipologias)</div>
			        </div>
			        <div class = "col-md-2">
			            <?php

			            $urlCadastro = Router::url(array('controller' => "TempoConsultaAtendimento", 'action' => 'adicionar'));

					    echo $this->Form->button(' Adicionar Tempo de Consulta', array(
					        'class' => 'btn fa fa-file-text estiloBotao btn-success float-right',
					        'type' => 'button',
					        'onclick' => "jQuery('body').addClass('loading');location.href = '$urlCadastro'"
					    ));

			            ?>
			        </div>
			        
			    </div>
			</header>
            
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th style="width: 20%"><?=  __('cid_label_tempo_consulta'); ?></th>
                                    <th style="width: 60%"><?=  __('cid_label_tempo_tipologia'); ?></th>
                                    <!-- <th style="width: 20%"></th> -->
                                    <?= $this->element('botoes-default-grid-title'); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($consultas)):
                                    foreach ($consultas as $line) :
                                        ?>
                                        <tr class="">
                                            <td><?= $line['TempoConsultaAtendimento']['tempo_consulta']; ?></td>
                                            <td>
                                            	<?php $aux = 0; ?>
	                                            <?php foreach($line['Tipologia'] as $tipologia): ?>
	                                            	<?php 
	                                            		echo $tipologia['nome']; 
		                                            	$aux++;
											            if($aux != count($line['Tipologia']) ){
											                echo ", ";
											            }

	                                            	?>

	                                            <?php endforeach; ?>
                                            </td>
                                            <td style="text-align: center;"><?= $this->element('botoes-default-grid-tempo-consulta', array('id' => $line['TempoConsultaAtendimento']['id'], 'model' => 'TempoConsultaAtendimento')); ?></td>
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
                    <? //echo $this->element('paginator'); ?>
                </div>
            </div>
        </section>
    </div>
