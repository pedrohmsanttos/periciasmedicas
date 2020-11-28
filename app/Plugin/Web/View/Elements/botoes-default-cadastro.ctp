<?php if (isset($ajax)): ?>
    <div class="row float-right btn-edit">

        <?php if ($acao == Configure::read('ACAO_INSERIR') || $acao == Configure::read('ACAO_ALTERAR')): ?>
            <i class="btn fa fa-check estiloBotao btn-success" id="ajaxAdd" 
               data-url="<?php
               echo Router::url(array('controller' => $controller,
                   'action' => $this->params['action']), true);
               ?>"><?= __('bt_salvar') ?></i>

            <i class="btn fa fa-check-square-o estiloBotao btn-success" id="ajaxAddAfterNew" 
               data-url="<?= Router::url(array('controller' => $controller), true); ?>" 
               data-retorno="<?= Router::url(array('controller' => $controller, 'action' => 'adicionar'), true); ?>">
                <?= __('bt_salvar_incluir_novo') ?></i>

        <?php endif; ?>

        <?php if ($acao == Configure::read('ACAO_EXCLUIR')): ?>

            <i class="btn fa fa-trash-o estiloBotao btn-danger" id="ajaxDelete" 
               data-url="<?php
               echo Router::url(array('controller' => $controller,
                   'action' => "processarExclusao"));
               ?>"><?= __('bt_excluir') ?></i>

        <?php endif; ?>

        <?php $urlConsulta = Router::url(array('controller' => "$controller", 'action' => 'index')); ?>
        <i class="btn fa fa fa-search estiloBotao btn-info" onclick="location.href = '<?= $urlConsulta; ?>'" id="ajaxConsult" 
           data-url="<?php echo Router::url(array('controller' => "$controller", 'action' => 'index')); ?>"><?= __('bt_ir_consulta') ?></i>
<?php else: ?>
    <div class="row float-right btn-edit">
        <?php
        if ($acao == Configure::read('ACAO_INSERIR') || $acao == Configure::read('ACAO_ALTERAR')) {
            echo $this->Form->button(__('bt_salvar'), array(
                'class' => 'btn fa fa-check estiloBotao btn-success salvarButton',
                'value' => 'true',
                'name' => 'salvarButton'
            ));
            echo $this->Form->button(__('bt_salvar_incluir_novo'), array(
                'class' => 'btn fa fa-check-square-o estiloBotao btn-success salvarButton',
                'value' => 'true',
                'name' => 'salvarContinuarButton'
            ));
        }
        if ($acao == Configure::read('ACAO_EXCLUIR')) {
            echo $this->Form->button(__('bt_excluir'), array(
                'class' => 'btn fa fa-trash-o estiloBotao btn-danger',
            ));
        }


    if($this->request->controller == "TempoConsultaAtendimento"){
        $urlConsulta = Router::url(array('controller'=>"ParametroGeral", 'action'=>'editar', 1));   
    }else{
        $urlConsulta = Router::url(array('controller'=>"$controller", 'action'=>'index'));
    }

        echo $this->Form->button(__('bt_ir_consulta'), array(
            'class' => 'btn fa fa-search estiloBotao btn-info',
            'type' => 'button',
            'onclick' => "location.href = '$urlConsulta'"
        ));
        ?>

    <?php
    endif;
    $urlCadastro = Router::url(array('controller' => "$controller", 'action' => 'adicionar'));

    if ($acao == Configure::read('ACAO_INSERIR')) {
        echo $this->Form->button(__('bt_limpar'), array(
            'class' => 'btn fa fa-eraser estiloBotao btn-danger',
            'type' => 'button',
            'onclick' => "location.href = '$urlCadastro'"
        ));
    }
    ?>
</div>
<?php
if (($this->params['action'] != 'adicionar')):
    echo $this->Form->input('id', array(
        'type' => 'hidden'
    ));
    echo $this->Form->input('data_inclusao', array(
        'type' => 'hidden'
    ));
endif;