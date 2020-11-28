<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar',__('Perfil')), 'exibirBotaoNovo' => true)); ?>

            <div class="panel-body">
                <?= $this->Form->create('Perfil', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required'=> false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => 'Perfil', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?= $this->Form->input('nome', array('label' => __('perfil_label_nome_perfil'))); ?>
                    </div>
                    <div class="col-md-4 form-group statusPerfil">
                        <?php
                        $arrTipo = array('1' => __('perfil_label_ativo'), '0' => __('perfil_label_inativo'));

                        $value = null;

                        if (isset($this->data['Perfil'])):
                            $strAtivo = $this->data['Perfil']['ativado'];
                            if ($strAtivo == true):
                                $value = 1;
                            elseif ($strAtivo === false):
                                $value = 0;
                            endif;
                        else:
                            $value = 1;
                        endif;


                        echo $this->Form->radio('ativado', $arrTipo, array('default' => 1,
                            'legend' => false));
                        ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-consulta'); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>