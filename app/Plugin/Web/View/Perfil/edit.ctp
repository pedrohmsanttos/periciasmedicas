<?= $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('Perfil')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?php
                        echo
                        $this->Form->input('nome', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('perfil_label_nome_perfil') . ': ' . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
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
                            'legend' => false,
                            'disabled' => $formDisabled));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <?php
                        foreach ($funcionalidades as $funcionalidade):
                            ?>
                            <ul class="tree col-md-4 panel-perfil">
                                <li>
                                    <label> 
                                        <?php
                                        $idFuncionalidadePai = $funcionalidade['Funcionalidade']['id'];

                                        echo $this->Form->checkbox('Funcionalidade.' . $idFuncionalidadePai, array('value' => $idFuncionalidadePai,
                                            'checked' => Util::in_array_object($funcionalidade['Funcionalidade'], $this->request['data']['Funcionalidade']),
                                            'disabled' => $formDisabled));
                                        ?>

                                        <?php echo $funcionalidade['Funcionalidade']['nome']; ?>
                                    </label>
                                    <ul class="tree">
                                        <?php
                                        foreach ($funcionalidade['Funcionalidade']['funcionalidadesFilhas'] as $funcionalidadeFilha):
                                            ?>
                                            <li>
                                                <label> 
                                                    <?php
                                                    $idFuncionalidadeFilha = $funcionalidadeFilha['Funcionalidade']['id'];

                                                    echo $this->Form->checkbox('Funcionalidade.' . $idFuncionalidadeFilha, array('value' => $idFuncionalidadeFilha,
                                                        'checked' => Util::in_array_object($funcionalidadeFilha['Funcionalidade'], $this->request['data']['Funcionalidade']),
                                                        'disabled' => $formDisabled));
                                                    ?>

                                                    <?php echo $funcionalidadeFilha['Funcionalidade']['nome']; ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            </ul>
                        <?php endforeach; ?>

                        <?php
//                        echo $this->Form->input('Funcionalidade', array('multiple' => 'checkbox', 'options' => $funcionalidades));
                        ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-cadastro'); ?>
            </div>
        </section>
    </div>
</div>
<?= $this->Form->end(); ?>