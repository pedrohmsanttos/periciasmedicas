<?php
$MENSAGEM_PADRAO_PAGINACAO = 'Página {:page} de {:pages}, exibindo {:current} registro(s) de {:count} no total.';
if (!isset($grid)){
    $grid = 'grid';
}
?>
<div class="row">
    <div class="col-md-4">
        <div class="dataTables_info" id="dynamic-table_info">
            <?php echo $this->Paginator->counter(array('format' => __($MENSAGEM_PADRAO_PAGINACAO))); ?>		
        </div>
    </div>

    <div class="col-md-4 text-center">
        <?php if ($this->Paginator->params()['pageCount'] > 1) { ?>
            <div class="dataTables_paginate paging_bootstrap pagination">
                <ul>
                    <?php
                    $this->Paginator->options(array(
                        'update' => '#'.$grid,
                        'evalScripts' => true
                    ));
                    echo $this->Paginator->prev(__('←'), array(
                        'tag' => 'li'
                            ), null, array(
                        'tag' => 'li',
                        'class' => 'prev',
                        'disabledTag' => 'a',
                        'class' => 'disabled'
                    ));
                    echo $this->Paginator->numbers(array(
                        'separator' => '',
                        'currentTag' => 'a',
                        'currentClass' => 'active',
                        'tag' => 'li',
                        'first' => 1,
                        'modulus' => 4,
                        'ellipsis' => '',
                        'href' => '#'
                    ));
                    echo $this->Paginator->next(__('→'), array(
                        'tag' => 'li',
                        'currentClass' => 'disabled'
                            ), null, array(
                        'tag' => 'li',
                        'class' => 'disabled',
                        'disabledTag' => 'a'
                    ));
                    ?>
                </ul>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-4">
    </div>




</div>

<?php echo $this->Js->writeBuffer(); ?>