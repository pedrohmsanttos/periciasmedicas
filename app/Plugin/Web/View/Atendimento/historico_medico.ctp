
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => "LAUDO DE ".$dadosUsuarioLaudoParecer[0]['assuntosP']['descricao'])); ?>
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('historico_medico_label_dados_pessoais') ?></legend>


                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('nome', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'value' => $dadosUsuarioLaudoParecer[0]['pessoas']['nome'],
                                        'class' => 'form-control',
                                        'label' => __('hm_dados_label_nome') . ': ' ,
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('rg', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'class' => 'form-control',
                                        'value' => $dadosUsuarioLaudoParecer[0]['pessoas']['identidade'],
                                        'label' => __('hm_dados_label_rg') . ': ' ,
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <?php
                                    echo $this->Form->input('matricula', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'value' => $dadosUsuarioLaudoParecer[0]['servidorpessoa']['matricula'],
                                        'class' => 'form-control',
                                        'label' => __('hm_dados_label_matricula') . ': ' ,
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                                <div class="col-md-8 form-group">
                                    <?php
                                    echo $this->Form->input('orgao', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'value' => $dadosUsuarioLaudoParecer[0]['orgaospessoa']['nome'],
                                        'class' => 'form-control',
                                        'label' => __('hm_dados_label_orgao') . ': ' ,
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <?php
                                    echo $this->Form->input('data', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'value' =>date("d/m/Y", strtotime($dadosUsuarioLaudoParecer[0]['laudosP']['datadespacho'])),
                                        'class' => 'form-control',
                                        'label' => __('hm_dados_label_data') . ': ' ,
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                                <div class="col-md-4 form-group">
                                    <?php
                                    echo $this->Form->input('criadoem', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'value' => date("d/m/Y", strtotime($dadosUsuarioLaudoParecer[0]['Requerimentos']['datarequerimento'])),
                                        'class' => 'form-control',
                                        'label' => __('hm_dados_label_criadoem')  ,
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                            </div>


                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('historico_medico_label_laudo') ?></legend>


                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('num_laudo', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'class' => 'form-control',
                                        'value' => $dadosUsuarioLaudoParecer[0]['laudosP']['laudono'],
                                        'label' => __('hm_laudo_label_num')  ,
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php


                                    $situacaoLaudo = $dadosUsuarioLaudoParecer[0]['laudosP']['deferido'];
                                    $situacaoVal = "";
                                    if($situacaoLaudo ==0){
                                        $situacaoVal ="Indeferido";
                                    }else if($situacaoLaudo ==1){
                                        $situacaoVal ="Deferido" ;
                                    }else if($situacaoLaudo ==2){
                                        $situacaoVal ="Em exigência";
                                    }



                                    echo $this->Form->input('situacao', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'value' => $situacaoVal,
                                        'class' => 'form-control',
                                        'label' => __('hm_laudo_label_situacao') ,
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('medico', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'value' => $dadosUsuarioLaudoParecer[0]['pessoamedico']['nome']. " - CRM:".$dadosUsuarioLaudoParecer[0]['medicos']['crm'],
                                        'class' => 'form-control',
                                        'label' => __('hm_laudo_label_medico'),
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                            </div>
                         </fieldset>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('historico_medico_label_cid') ?></legend>


                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <table class="table table-striped table-hover table-bordered" id="tableHistoricoMedico">
                                        <thead>
                                        <tr>

                                            <th><?= __('historico_medico_cid_ado_epidemia'); ?></th>
                                            <th><?= __('historico_medico_cid_cid'); ?></th>
                                            <th><?= __('historico_medico_cid_efermidade'); ?></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        if (isset($listDadosCid)):
                                                    if (!empty($listDadosCid)):
                                                        foreach ($listDadosCid as $key => $line) :

                                                            $adquiridoporepidemia = $line['laudoscids']['adquiridoporepidemia'];

                                                            ?>
                                                            <tr class="rowHistorico">


                                                                <td><?=($adquiridoporepidemia == 1 ? "SIM" : "N&Atilde;O") ?></td>
                                                                <td><?= $line['cids']['cid']; ?></td>
                                                                <td><?= $line['cids']['enfermidade']; ?></td>

                                                            </tr>
                                                            <?php
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <tr>
                                                            <td colspan="9" style="text-align: center;">
                                                                <?= __('nenhum_registro_encontrado') ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif;
                                                endif;
                                                $diagnosticoLaudo = $dadosUsuarioLaudoParecer[0]['laudosP']['diagnostico'];
                                                if(!empty( $diagnosticoLaudo)){
                                                ?>

                                                <tr>
                                                    <td colspan="9" style="text-align: center;">
                                                        <?="Diagn&oacute;stico: ".$diagnosticoLaudo ?>
                                                    </td>
                                                </tr>
                                            <?}?>

                                        </tbody>
                                    </table>

                                 </div>
                            </div>


                        </fieldset>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('historico_medico_label_exigencia_medica') ?></legend>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <table class="table table-striped table-hover table-bordered" id="tableHistoricoMedico">
                                        <tbody>

                                      <?php

                                    if (isset($listDadosExigencia)) {
                                        if (!empty($listDadosExigencia)) {


                                            foreach ($listDadosExigencia as $key => $line) {
                                                ?>
                                                <tr>
                                                    <td colspan="9" style="text-align: center;">
                                                <?
                                                $descricao = "";
                                                if (($line['exigenciasmedicas']['laudonormalid']) != "0") {
                                                    $dataCumprimento = $line['exigenciaslaudos']['datacumprimento'];
                                                    if (empty($dataCumprimento)) {
                                                        $dataCumprimento = "N&;atildeo cumprida";
                                                    }
                                                    echo $line['exigenciasmedicas']['exigencia']." ".date("d/m/Y", strtotime(Util::toDBDataHora($dataCumprimento)));
                                                }
                                                ?>
                                                    </td>
                                                </tr>

                                                <?
                                            }
                                        }
                                    }


                                    ?>
                                        </tbody>
                                        </table>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('historico_medico_label_licenca_medica_parecer') ?></legend>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php


                                    $tipoLicenca = "";
                                    if($dadosUsuarioLaudoParecer[0]['licencasmedicas']['renovacao'] == "0"){
                                        $tipoLicenca= "Licença Inicial";
                                    }else if($dadosUsuarioLaudoParecer[0]['licencasmedicas']['renovacao'] == "1"){
                                        $tipoLicenca= "Licença em prorrogação";
                                    }
                                    $assuntoId = $dadosUsuarioLaudoParecer[0]['assuntosP']['unassunto'];

                                    $descricaoParecer ="";
                                    if($assuntoId == "4"){
                                        $descricaoParecer ="Concedemos " . ($dadosUsuarioLaudoParecer[0]['licencasmedicas']['dias']) . " dia(s) de licença pelo artigo 7º, Item XVIII, capitulo II da C.F. a partir de  " .date("d/m/Y", strtotime(Util::toDBDataHora(($dadosUsuarioLaudoParecer[0]['licencasmedicas']['datainicial']))))  . ".";
                                    }else if($assuntoId == "40"){
                                        $descricaoParecer ="Concedemos " . ($dadosUsuarioLaudoParecer[0]['licencasmedicas']['dias']) . "  dia(s) de licença pelo Artigo 126 da lei 6123 de 20/07/68, alterada pela Lei Complementar Nº91 de 21/06/07 a parti de " .date("d/m/Y", strtotime(Util::toDBDataHora(($dadosUsuarioLaudoParecer[0]['licencasmedicas']['datainicial']))))  . ".";
                                    }else if($assuntoId == "41"){
                                        $descricaoParecer ="Concedemos 30 (trinta) dias de licença pelo Artigo 126 da lei 6123 de 20/07/68, alterada pela Lei Complementar Nº91 de 21/06/07, no seu Paragrafo 3º, a parti de " .date("d/m/Y", strtotime(Util::toDBDataHora(($dadosUsuarioLaudoParecer[0]['licencasmedicas']['datainicial']))))  . ".";
                                    }else if($assuntoId == "42"){
                                        $descricaoParecer ="Concedemos 30 (trinta) dias de licença pelo Artigo 126 da lei 6123 de 20/07/68, alterada pela Lei Complementar Nº91 de 21/06/07, no seu Paragrafo 4º a parti de" .date("d/m/Y", strtotime(Util::toDBDataHora(($dadosUsuarioLaudoParecer[0]['licencasmedicas']['datainicial']))))  . ".";
                                    }else if($assuntoId == "43"){
                                        $descricaoParecer ="Concedemos 60 (sessenta) dias de licença pelo Artigo 126 da Lei 6123 de 20/07/68, alterada pela Lei Complementar Nº91 de 21/06/07, no seu artigo 3º, a parti de  " .date("d/m/Y", strtotime(Util::toDBDataHora(($dadosUsuarioLaudoParecer[0]['licencasmedicas']['datainicial']))))  . ".";
                                    }else{
                                        if(empty($dadosUsuarioLaudoParecer[0]['licencasmedicas']['artigos'])){
                                            $descricaoParecer ="Concedemos  ".($dadosUsuarioLaudoParecer[0]['licencasmedicas']['dias'])."  dia(s) de " .$tipoLicenca." partir de ".($dadosUsuarioLaudoParecer[0]['licencasmedicas']['datainicial'])  .".";
                                        }else{
                                            $descricaoParecer ="Concedemos  ".($dadosUsuarioLaudoParecer[0]['licencasmedicas']['dias'])."  dia(s) de ". $tipoLicenca. " pelo(s) artigo(s) ".($dadosUsuarioLaudoParecer[0]['licencasmedicas']['artigos'])  . " do Estatuto dos Funcionários Públicos do Estado a partir de ".date("d/m/Y", strtotime(Util::toDBDataHora(($dadosUsuarioLaudoParecer[0]['licencasmedicas']['datainicial']))))  .".";
                                        }
                                    }
                                    echo $this->Form->input('licenca', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'type' =>'textarea',
                                        'label' => __('hm_laudo_label_licenca_medica_parecer_licenca')  ,
                                        'value' => ($descricaoParecer),
                                        'class' => 'form-control',
                                        'disabled' => true
                                    ));
                                    ?>
                                </div>
                            </div>
                            <?
                            if(!(empty($dadosUsuarioLaudoParecer[0]['laudosP']['conclusao']))){
                            ?>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?
                                    $valueDados = utf8_encode("Observação: ").$dadosUsuarioLaudoParecer[0]['laudosP']['conclusao'];
                                    echo $this->Form->input('exigencia_medica', array(
                                        'div' => array(
                                            'class' => 'form-group'
                                        ),
                                        'label' => __('hm_laudo_label_licenca_medica_parecer_licenca')  ,
                                        'value' => $valueDados,
                                        'class' => 'form-control',
                                        'disabled' => true
                                    ));

                                    ?>
                                </div>
                            </div>
                            <?}?>
                        </fieldset>
                    </div>
                </div>



                <?php echo $this->Html->link(__('bt_voltar') , $referer,  array('class' => 'btn fa fa-arrow-left estiloBotao btn-danger botaoVoltarAtendimento')); ?>



            </div>
        </section>
    </div>
</div>

