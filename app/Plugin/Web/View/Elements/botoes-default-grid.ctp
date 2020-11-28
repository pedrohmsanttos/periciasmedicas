<?php
$classView = "";
if (isset($acao) && $acao == Configure::read('ACAO_VISUALIZAR')):
    $classView = "float-right btn-edit2";
endif;
$hasAction = false;

?>

<div id="div-btn-actions" class="btn-group <?= $classView ?>">
    <button id="btn-actions" data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_acoes') ?> <span class="caret"></span></button>
    <ul role="menu" class="dropdown-menu listaAcoes">
        <?php if ((isset($acao) && $acao != Configure::read('ACAO_VISUALIZAR')) || !isset($acao)): ?>
            <?php if (Util::temPermissao($nameModel . ".visualizar")): ?>
                <?php $hasAction =true; ?>
                <li><?php echo $this->Html->link(__('bt_visualizar'), array('action' => 'visualizar', $id), array('class' => 'fa fa-search', 'title' => __('bt_visualizar'))); ?></li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Util::temPermissao($nameModel . ".editar")):
            if ((!isset($bloquearEdicao) || $bloquearEdicao == false)): ?>
            <li><?php echo $this->Html->link(__('bt_alterar'), array('action' => 'editar', $id), array('class' => 'fa fa-edit', 'title' => __('bt_alterar'))); ?></li>
        <?php $hasAction =true;
            endif;
        endif; ?>
        <?php if (Util::temPermissao($nameModel . ".deletar")):
            if ((!isset($bloquearExclusao) || $bloquearExclusao == false)):
                ?>
                <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deletar', $id), array('id'=>'btn-excluir-'. $id,   'class' => 'fa fa-trash-o', 'title' => __('bt_excluir'))); ?></li>
                <script>
                    document.getElementById('btn-excluir-<?=$id?>').addEventListener('click', function(e){
                        if(!confirm('Deseja realmente excluir o item?')){e.preventDefault();document.getElementById('btn-excluir-<?=$id?>').setAttribute('ok', 0);}else{document.getElementById('btn-excluir-<?=$id?>').setAttribute('ok', 1);}
                    });</script>
                <?php $hasAction =true;
            endif;
        endif; ?>
        <?php if (isset($download_laudo) && $download_laudo > 0){
            $hasAction =true;
            $urlLaudo = Router::url(['controller' => 'Atendimento', 'action' => 'download_laudo', $download_laudo], true);
            ?><li><?php echo $this->Html->link('Download Laudo', $urlLaudo, array('id'=>'btn-download',   'class' => 'fa', 'title' => 'Download Laudo')); ?></li><?php
        } ?>
    </ul>
</div>
<?php if(!$hasAction){ ?>
    <script>
        (function(){
            var divbtn;
            divbtn = document.getElementById('div-btn-actions').onclick = function(e){
                e.preventDefault();
                e.stopPropagation();
                return false;
            };
            document.getElementById('btn-actions').style.background= '#CCC';
        })();
    </script>
<?php } ?>
