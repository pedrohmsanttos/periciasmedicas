<?php
if (!$formDisabled):
    ?>
    <div class="row">
        <div class="col-md-6 form-group" style="width: 55%;">
            <i class="btn fa estiloBotao btn-info select-all" data-target="<?= $target ?>"><?= __('label_selecionar_todas') ?></i>
        </div>
        <div class="form-group">
            <i class="btn fa estiloBotao btn-info deselect-all" data-target="<?= $target ?>"><?= __('label_desmarcar_todas') ?></i>
        </div>
    </div>
    <?php
endif;
?>