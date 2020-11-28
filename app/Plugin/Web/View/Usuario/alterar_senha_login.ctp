
<?php echo $this->Form->create('Usuario', array('class' => 'user-login')); ?>

<div id="logo"></div>
<br>
<div class="region region-content">
    <div id="block-system-main" class="block block-system">


        <div class="content">
            <h3 style="color: white;margin-left: 46px">Alteração de Senha</h3>
            <div>
                <div class="logo_irh"></div>
                <div class="form-item form-type-textfield form-item-name">
                    <?php
                    echo $this->Form->input('rg', array(
                        'id' => 'edit-rg',
                        'label' => false,
                        'div' => false,
                        'type' => 'text',
                        'class' => 'form-text',
                        'maxlength' => 10,
                        'placeholder' => 'RG'
                    ));
                    ?>
                </div>
                <br>
                <div class="form-item form-type-textfield form-item-name">
                    <?php
                    echo $this->Form->input('data_nascimento', array(
                            'tabindex' => '2',
                            'id' => 'edit-data-nascimento',
                            'label' => false,
                            'type' => 'text',
                            'class' => 'form-text input-data required',
                            'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                            'onblur' => 'VerificaData(this,this.value)',
                            'onmouseout' => 'VerificaData(this,this.value)',
                            'placeholder' => 'Data de Nascimento',
                            'div' => false,
                            'autocomplete' => 'off'
                        )
                    );
                    ?>
                </div>
                <br>
                <div class="form-item form-type-textfield form-item-name">
                     <?php
                    echo $this->Form->input('senha', array('tabindex' => '2', 'label' => false, 'id' => 'edit-pass', 'class' => 'form-text required', 'autocomplete' => 'off', 'placeholder' => 'Nova Senha', 'div' => false, 'type' => 'password'));
                    ?>
                </div>
                <br>
                <div class="form-item form-type-password form-item-pass">
                    <?php
                    echo $this->Form->input('confirma_nova_senha', array('tabindex' => '2', 'label' => false, 'id' => 'edit-confirm-pass', 'class' => 'form-text required', 'autocomplete' => 'off', 'placeholder' => 'Confirmação de Senha', 'div' => false, 'type' => 'password'));
                    ?>
                </div>
                <div class="form-actions form-wrapper" id="edit-actions">
                    <br />
                    <div id="wrap-pass">
                        <a tabindex="3" class="recuperarSenha" href="<?php echo $this->Html->url(array('action' => 'logout', 'controller' => 'usuario'), false) ?>" data-url="<?php echo $this->Html->url(array('action' => 'logout', 'controller' => 'usuario'), false) ?>">Sair</a>
                    </div>
                    <br>
                    <?php
                    echo $this->Form->button(__('bt_senha'), array(
                        'tabindex' => '4', 'class' => 'btn form-submit', 'id' => 'edit-submit'
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Form->end();


echo $this->Html->css(BS_PLUGIN_CSS . 'datepicker');
echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-datepicker');
echo $this->Html->script(BS_PLUGIN_JS . 'bootstrap-datepicker.pt-BR');

echo $this->Html->script(BS_PLUGIN_JS . 'alterar-senha');


?>