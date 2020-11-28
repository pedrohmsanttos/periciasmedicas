<?php
//echo $this->Html->script('https://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
echo $this->Html->script('https://www.google.com/recaptcha/api.js');
echo $this->Form->create('Usuario', array('class' => 'user-login', 'novalidate'));
?>

<div id="logo"></div>
<div class="region region-content">
    <p style="color: white;">
        <? if($isColaborador): ?>
            Acesso através das credencias do SPM
        <? else: ?>
            Acesso através das credenciais do contracheque - Portal do Servidor
        <? endif; ?>
    </p>
    <div id="block-system-main" class="block block-system">


        <div class="content">
            <div>
                <div class="logo_irh"></div>

                <div class="form-item form-type-textfield form-item-name">
                    <?php
                    echo $this->Form->input('cpf', array('tabindex' => '1', 'label' => false, 'class' => 'cpf form-text required', 'id' => 'edit-name', 'autocomplete' => 'off', 'placeholder' => 'Login', 'div' => false));
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
                    if (WITH_CAPTCHA) { // && $this->Session->read('validaCaptchaCC'
                        echo '<br/>';
                        $publickey = '6LeXziYTAAAAAER4WUFmTM5uDdJkAH--x-BJ2QHt';

                        if (isset($recaptcha_error)) {
                            echo '<strong>' . $recaptcha_error . '</strong><br/>';
                        }
                        echo "<div class=\"g-recaptcha\" data-sitekey=\"$publickey\"></div>";
                    }

                    ?>
                    <br />
                    <?php if($isColaborador): ?>
                        <div id="wrap-pass">
                            <a tabindex="3" class="recuperarSenha" href="#" data-url=" <?php echo $this->Html->url(array('action' => 'recuperarSenha', 'controller' => 'usuario'), false) ?>/1">
                                Esqueci a senha
                            </a>
                        </div>
                    <?php else: ?>
                        <? if (isset($cpf) && $this->Session->read('showEsqueciSenha')): //ESQUECI SENHA aparece após um erro ?>
                            <div id="wrap-pass" style="color:white; text-align:center">
                                Você esqueceu sua senha? Para cadastrar uma nova senha, acesse o portal do servidor
                                <a tabindex="3" class="recuperarSenha" href="http://www.senhaservidor.sad.pe.gov.br/ControleSenha/index.jsf">clicando aqui</a>
                            </div>

                            <?php
                        endif;
					endif;
                    ?>
                    <br>
                    <?
                    echo $this->Form->button(__('bt_entrar'), array(
                        'tabindex' => '4', 'class' => 'btn form-submit', 'id' => 'edit-submit'
                    ));
                    ?>
                    <div style="text-align:center;color: white;margin-right: 49px;">
                        <? if($isColaborador): ?>
                            <? echo $this->Form->input('colaborador', array( 'type' => 'hidden', 'value' => 1 )); ?>
                            Acesso para servidores,
                            <a href="?c=0" style="color:white; text-decoration: underline; padding-bottom: 10px;">clique aqui</a>
                        <? else : ?>
                            Acesso para colaboradores,
                            <a href="?c=1" style="color:white; text-decoration: underline; padding-bottom: 10px;">clique aqui</a>
                        <? endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Form->end();
?>