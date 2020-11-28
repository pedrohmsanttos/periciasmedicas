<div id="div-btn-actions" class="btn-group">
    <button id="btn-actions" data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_acoes') ?> <span class="caret"></span></button>
    <ul role="menu" class="dropdown-menu listaAcoes">
        <?php if ((isset($acao) && $acao != Configure::read('ACAO_VISUALIZAR')) || !isset($acao)): ?>
            <?php if (Util::temPermissao("TempoConsultaAtendimento.visualizar")): ?>
                <?php $hasAction =true; ?>
                <li><?php echo $this->Html->link(__('bt_visualizar'), array('controller' => 'TempoConsultaAtendimento', 'action' => 'visualizar', $id), array('class' => 'fa fa-search', 'title' => __('bt_visualizar'))); ?></li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Util::temPermissao("TempoConsultaAtendimento.editar")):
            if ((!isset($bloquearEdicao) || $bloquearEdicao == false)): ?>
            <li><?php echo $this->Html->link(__('bt_alterar'), array('controller' => 'TempoConsultaAtendimento', 'action' => 'editar', $id), array('class' => 'fa fa-edit', 'title' => __('bt_alterar'))); ?></li>
        <?php $hasAction =true;
            endif;
        endif; ?>
        <?php if (Util::temPermissao("TempoConsultaAtendimento.deletar")):
            if ((!isset($bloquearExclusao) || $bloquearExclusao == false)):
                ?>
                <li><?php echo $this->Html->link(__('bt_excluir'), array('controller' => 'TempoConsultaAtendimento','action' => 'deletar', $id), array('id'=>'btn-excluir-'. $id,   'class' => 'fa fa-trash-o', 'title' => __('bt_excluir'))); ?></li>
                <script>
                    document.getElementById('btn-excluir-<?=$id?>').addEventListener('click', function(e){
                        if(!confirm('Deseja realmente excluir o item?')){e.preventDefault();document.getElementById('btn-excluir-<?=$id?>').setAttribute('ok', 0);}else{document.getElementById('btn-excluir-<?=$id?>').setAttribute('ok', 1);}
                    });</script>
                <?php 
            endif;
        endif; ?>
    </ul>
</div>