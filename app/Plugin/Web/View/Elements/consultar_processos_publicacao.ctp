<?php

$formCreate['id'] = "form-processos";
$url = Router::url(['controller' => 'Atendimento', 'action' => 'enviarPublicacao'], true);
echo $this->Form->create(array('id'=>'form-processos', 'data-url'=>$url));


echo $this->Form->hidden('Publicacao.data_inicial', array('id' => 'data_inicial', 'value' => $data_inicial));
echo $this->Form->hidden('Publicacao.data_final', array('id' => 'data_final', 'value' => $data_final));

	
	$nome = $cpf = "";
	
	if(!empty($nome_usuario) && isset($nome_usuario)){
		$nome = $nome_usuario;
	}
	
	if(!empty($cpf_usuario) && isset($cpf_usuario)){
		$cpf = $cpf_usuario;
	}

echo $this->Form->hidden('Usuario.nome', array('id' => 'nome_usuario', 'value' => $nome));
echo $this->Form->hidden('Usuario.cpf', array('id' => 'cpf_usuario', 'value' => $cpf));
?>
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-body">
                <div class="col-sm-2"><b>Data Inicial:</b> <?=$data_inicial?></div>
                <div class="col-sm-2"><b>Data Final:</b> <?=$data_final?></div>
            </div>
        </section>
    </div>
    <div class="col-sm-12">
        <section class="panel">
            <?//= $this->element('cabecalho-tabela'); ?>

            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                            <tr>
                                <th>Nº Atendimento</th>
                                <th>&Oacute;rg&atilde;o</th>
                                <th style="width:20%">Situa&ccedil;&atilde;o</th>
                                <th style="width: 35%;">Tipologia</th>
                                <th>Servidor</th>
                                <th>Matr&iacute;cula</th>
                                <th>Dias</th>
                                <th>A partir de</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $qtd = 0;
                            $haPublicacoes = false;
                            if (!empty($publicacoes)):

                                $haPublicacoes = true;

                                foreach ($publicacoes as $line):

                                    $qtd++;
                                    $data = date("d/m/Y", strtotime($line['Atendimento']['data_parecer']));

                                    ?>
                                    <tr class="">
                                        <?php echo $this->Form->input('Atendimento.Atendimento.', array(
                                            'type' => 'hidden',
                                            'id' => 'processos',
                                            'value' => $line['Atendimento']['id']
                                        ));
                                        $recursoTipologia = '';
                                        if(!empty($line['TipologiaRecurso']['nome'])){
                                            $recursoTipologia = ' ('.$line['TipologiaRecurso']['nome'] . ')';
                                        }

                                        ?>
                                        <td><?=$line['Atendimento']['id'] ?></td>
                                        <td><?=$line['OrgaoOrigem']['orgao_origem'] ?></td>
                                        <td><?=$line['ParecerSituacao']['nome'] ?></td>
                                        <td><?= $line['Tipologia']['nome'] . $recursoTipologia ?></td>
                                        <td><?= $line['Usuario']['nome'] ?></td>
                                        <td><?= $line['Vinculo']['matricula'] ?></td>
                                        <td><?= $line['Atendimento']['duracao'] ?></td>
                                        <td><?= $data //$line['Atendimento']['data_parecer']; ?></td>

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
                    <?//= $this->element('paginator'); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?php if($qtd > 0): ?>
                                &nbsp;<b>Quandidade total:</b> <?=$qtd?>
                            <?php endif; ?>
                            <section class="panel">
                                <div class="panel-body">





                                                <?php
                                                if(!isset($modoVisualizar) || !$modoVisualizar  ){?>

                                                <?php if($haPublicacoes): ?>
                                                <div class="row float-right btn-edit">
                                                    <?
                                                    echo $this->Form->button( ' Publicar', array(
                                                    'class' => 'btn fa fa-files-o estiloBotao btn-primary',
                                                    'type' => 'button',
                                                    'id' => "btn-publicar"
                                                    ));
                                                    ?>
                                                 </div>
                                             <?php endif; ?>

                                                <?
                                                }else{?>
                                                <div class="row float-right btn-edit">
                                                <?
                                                    echo $this->Form->button( ' Voltar', array(
                                                        'class' => 'btn fa fa-arrow-left estiloBotao btn-danger',
                                                        'type' => 'button',
                                                        'id' => "btn-voltar"
                                                    ));

                                                    ?>

                                                    <button data-toggle="dropdown" class="btn fa  dropdown-toggle btn-primary" type="button"><?php echo __('label_acoes') ?> <span class="caret"></span></button>
                                                        <ul role="menu" class="dropdown-menu listaAcoes">
                                                            <li><?php echo $this->Html->link(' Reenviar e-mail', 'javascript:void(0)', array('class' => 'btn fa fa-envelope-o','id'=>'btn-reenviar-email', 'title' => ' Reenviar e-mail')); ?></li>
                                                            <li><?php echo $this->Html->link(' Download', 'javascript:void(0)', array('class' => 'btn fa fa-files-o','id' => 'btn-download', 'title' => ' Download')); ?></li>
                                                        </ul>
                                                </div>
                                                    <?}?>




                                    <!--<div class="row float-right btn-edit">-->
                                        <?php
                                      /*  if(!isset($modoVisualizar) || !$modoVisualizar  ){
                                            $urlCadastro = Router::url(array('controller' => "Atendimento", 'action' => 'enviarPublicacao'));

                                            echo $this->Form->button( ' Publicar', array(
                                                'class' => 'btn fa fa-files-o estiloBotao btn-primary',
                                                'type' => 'button',
                                                'id' => "btn-publicar"
                                            ));
                                        }else{

                                            echo $this->Form->button( ' Voltar', array(
                                                'class' => 'btn fa fa-arrow-left estiloBotao btn-danger',
                                                'type' => 'button',
                                                'id' => "btn-voltar"
                                            ));


                                            $urlCadastro = Router::url(array('controller' => "Atendimento", 'action' => 'enviarPublicacao'));

                                            echo $this->Form->button( ' Download', array(
                                                'class' => 'btn fa fa-files-o estiloBotao btn-primary',
                                                'type' => 'button',
                                                'id' => "btn-download"
                                            ));
                                        }*/
                                        ?>
                                    <!--</div>-->
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    <?
if(!empty($publicacaoId)){
    $urlReenvio = Router::url(array('controller' => "Atendimento", 'action' => 'reenviarPublicacao'));
    echo $this->Form->hidden('url_reenvio', array('id' => 'url_reenvio', 'data-url' => $urlReenvio.'/'.$publicacaoId, 'value' => "Atendimento/reenviar_publicacao/".$publicacaoId ));
}
    ?>
    <script>

        <?php  if(!isset($modoVisualizar) || !$modoVisualizar  ): ?>
            <?php if($haPublicacoes): ?>
                document.getElementById('btn-publicar').addEventListener('click', function(e){
                    if(confirm('Deseja realmente publicar esse(s) processo(s)?')){
                        var url = $('#form-processos').data('url');
                        $('#form-processos')[0].action = url;
                        $('#form-processos').submit();
                    }
                });
            <?php endif; ?>
        <?php else: ?>
        document.getElementById('btn-download').addEventListener('click', function(e){
            var url = $('#')
            window.location = '<?= Router::url('/', true).'publicacoes/publicacao_'.$publicacaoId.'.odt' ?>';
        });
        document.getElementById('btn-voltar').addEventListener('click', function(e){
            window.history.back();
        });
        document.getElementById('btn-reenviar-email').addEventListener('click', function(e){

            var url = $('#url_reenvio').data('url');
            window.location = url;
            // console.log('chegou aqui', url);
            // $("#formularioConsulta").attr("method", "post");
            // $('#formularioConsulta')[0].action = url;
            // $('#formularioConsulta').submit();
        });


        <?php endif; ?>
    </script>


    <?php
        // $url = Router::url('/', true).'publicacoes/publicacao_'.$publicacaoId.'.odt';
        // pr($url);die;
        // pr($this->webroot);die;
        // pr(file_get_contents($url));die;

        // print_r(  'C:\wamp64\www\spm\fontes\trunk\app\webroot\publicacoes' );die;
    ?>

<?= $this->Form->end(); ?>