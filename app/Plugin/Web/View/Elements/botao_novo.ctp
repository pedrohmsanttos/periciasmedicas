
    <?php
    $urlCadastro = Router::url(array('controller' => "$controller", 'action' => 'adicionar'));

    $btGenero = (isset($feminino) && $feminino == true) ? 'bt_nova' : 'bt_novo';

    echo $this->Form->button(__($btGenero, __($controller)), array(
        'class' => 'btn fa fa-file-text estiloBotao btn-success float-right',
        'type' => 'button',
        'onclick' => "jQuery('body').addClass('loading');location.href = '$urlCadastro'"
    ));
    ?>
