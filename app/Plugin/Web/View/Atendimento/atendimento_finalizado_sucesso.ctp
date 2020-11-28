<div class="error-head divHead">
    <div class="error-desk divMensagem">
        <p class="nrml-txt texto"><?= __('info_atendimento_finalizado_sucesso') ?></p>
        <?php
        if ($situacaoAtendimento == TipoSituacaoParecerTecnico::EM_EXIGENCIA):
            $urlExigencias = Router::url(['controller' => 'Atendimento', 'action' => 'download_exigencias', $id], true);
            ?>
            <p class="nrml-txt textoInfo"><?= __('info_atendimento_finalizado_sucesso_impressao_exigencias') ?> <a href="<?= $urlExigencias ?>">aqui</a></p>
            <?php
        else:
            $urlLaudo = Router::url(['controller' => 'Atendimento', 'action' => 'download_laudo', $id], true);
            ?>
            <p class="nrml-txt textoInfo"><?= __('info_atendimento_finalizado_sucesso_geracao_laudo') ?> <a href="<?= $urlLaudo ?>">aqui</a></p>
        <?php
        endif;
        ?>
    </div>
</div>