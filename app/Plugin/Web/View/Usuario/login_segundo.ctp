<?php
//echo $this->Html->script('https://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
echo $this->Html->script('https://www.google.com/recaptcha/api.js');
?>
<?php echo $this->Form->create('Usuario', array('class' => 'user-login', 'novalidate')); ?>

<div id="logo"></div>
<br>
<div class="region region-content">
    <p style="color: white;">
        Acesso através das credencias do SPM
    </p>
    <div id="block-system-main" class="block block-system">


        <div class="content">
            <div>
                <div class="logo_irh"></div>
                <?php  if (isset($perfis)): ?>
                <style>
                    #perfil_radio .form-group label {
                        font-weight: normal;
                    }
                    #perfil_radio .form-group>label {
                        margin-left: -10px;
                        font-size: 15px;
                        font-weight: bold;
                    }
                    .radio-item{
                        width: 100%;
                    }

                </style>
                <div id="perfil_radio" style="color:white; margin-bottom: 10px; float: left">
                    <?php
                    echo $this->PForm->radio(array(
                        'options'=>$perfis,
                        'title' => 'Perfil',
                        'name' => 'Perfil.id',
                        'column' => 12,
                        'required' => true
                    ));
                    ?>
                </div>
                <?php endif; ?>
                <div class="form-item form-type-textfield form-item-name">
                    <?php

                    $readonly = false;
                    if(isset($cpf)){
                        $readonly = true;
                    }else{
                        $cpf = '';
                    }
                    echo $this->Form->input('cpf', array(
                        'tabindex' => '1',
                        'label' => false,
                        'class' => 'cpf form-text required',
                        'id' => 'edit-name',
                        'autocomplete' => 'off',
                        'placeholder' => 'Login',
                            'readonly' =>$readonly,
                            'value' => $cpf,
                        'div' => false)
                    );
                    ?>
                </div>
                <br>
                <div class="form-item form-type-password form-item-pass">
                    <?php
                    echo $this->Form->input('senha', array('tabindex' => '2', 'label' => false, 'id' => 'edit-pass', 'class' => 'form-text required', 'autocomplete' => 'off', 'placeholder' => 'Senha', 'div' => false, 'type' => 'password'));
                    ?>
                </div>
                <div class="form-actions form-wrapper" id="edit-actions">
                    <!-- <div class="logo_bsLogin"></div> -->

                    <?php
                    if (WITH_CAPTCHA) {//  && $this->Session->read('validaCaptcha')
                        echo '<br/>';
                        $publickey = '6LeXziYTAAAAAER4WUFmTM5uDdJkAH--x-BJ2QHt';

                        if (isset($recaptcha_error)) {
                            echo '<strong>' . $recaptcha_error . '</strong><br/>';
                        }
                        echo "<div class=\"g-recaptcha\" data-sitekey=\"$publickey\"></div>";
                    }
                    ?>

                    <br />
                    <div id="wrap-pass">
                        <a tabindex="3" class="recuperarSenha" href="#" data-url=" <?php echo $this->Html->url(array('action' => 'recuperarSenha', 'controller' => 'usuario'), false) ?>/1">
                            Esqueci a senha</a>
                    </div>
                    <br>
                    <?php
                    echo $this->Form->button(__('bt_entrar'), array(
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
?>