<?= $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('Tempo de Consulta do Atendimento')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?php
                        echo $this->Form->input('tempo_consulta', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control soNumero',
                            'label' => __('cid_label_tempo_consulta') . ': ' . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 form-group" style="width: 55%;">
                                <?php echo $this->Form->label(null, 'Tipologias'); ?>
                            </div>
                             <div class="form-group">
                                <?php echo $this->Form->label(null, 'Tipologias Selecionadas'); ?>
                            </div>
                        </div>
                        <?= $this->element('componente_acoes_pickList', array("target" => 'sigas_multi_select', 'formDisabled' => $formDisabled))?>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <?php
                                echo $this->Form->input('Tipologia', array('options' => $tipologias,
                                    'id' => 'sigas_multi_select',
                                    'multiple' => 'multiple',
                                    'disabled' => $formDisabled,
                                    'class' => 'sigas_multi_select alturaPickList',
                                    'div' => array('class' => 'form-group multi-select '),
                                    'label' => false));
                                ?>

                            </div>
                        </div>
                        
                    </div>
                </div>
               
               
                <div class="row col-md-12">
                    <?php
                    if ($acao == Configure::read('ACAO_EXCLUIR')):
                        $this->Form->unlockField('Tipologia.Tipologia');
                    endif;
                    ?>
                    <?= $this->element('botoes-default-cadastro'); ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?= $this->Form->end(); ?>

<script type="text/javascript">
    
    $(".soNumero").on("keyup", function (event) {
        soNumero($(this));
    });
    $(".soNumero").on("change", function (event) {
        soNumero($(this));
    });
    $(".soNumero").on("focus", function (event) {
        soNumero($(this));
    });
    $(".soNumero").on("hover", function () {
        soNumero($(this));
    });
    $(".soNumero").on("mouseout", function () {
        soNumero($(this));
    });

    function soNumero(element) {
        $(element).val($(element).val().replace(/[^0-9]/gi, ''));
    }

    $(".salvarButton").click(function(e) {
        var X = 5;

        var numero = parseInt($("#TempoConsultaAtendimentoTempoConsulta").val());
        if (!isNaN(numero)) {
            if (numero % X === 0) {
                $("#formBody").submit();
            } else {
                e.preventDefault();
                gerarMensagem('O Tempo da consulta só pode ser múltiplo de 5.', 'danger');
            }
        } else {
                e.preventDefault();
                gerarMensagem('Entrada não é um número.', 'danger');
            }
    });

</script>