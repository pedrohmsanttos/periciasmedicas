<div class="row col-lg-12">
    <div class="col-md-5">
        <?php
        echo $this->Form->input('email_senha_copia', array('type'=>'email', 'div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'label' => __('parametro_geral_interno_email_copia')));

        ?>
    </div>
    <div class="col-md-5">
        <?php
        echo $this->Form->input('diretor_presidente', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'label' => 'Diretor / Presidente'));
        ?>
    </div>

    <div class="col-md-5">
        <?php
        echo $this->Form->input('email_publicacao', array('type'=>'email', 'div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'label' => 'Email Publicação'));
        ?>
    </div>

    <div class="col-md-5">
        <?php
        echo $this->Form->input('numero_tentativas_login', array('type'=>'text', 'div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'label' => 'Número de tentativas de login antes de bloquear'));
        ?>
    </div>

    <div class="col-md-5">
        <?php
        echo $this->Form->input('limite_tam_arquivo_upload', array('type'=>'text', 'div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'label' => 'UPLOAD - Limite de tamanho do arquivo (mb)'));
        ?>
    </div>

    <div class="col-md-5">
        <?php
        echo $this->Form->input('limite_qtd_arquivo_upload', array('type'=>'text', 'div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'label' => 'UPLOAD - Limite de quantidade de arquivos por usuário'));
        ?>
    </div>

    <div class="col-md-5">
        <?php
        echo $this->Form->input('limite_tempo_arquivo_upload', array('type'=>'text', 'div' => array('class' => 'form-group'),
            'class' => 'form-control hour',
            'label' => 'UPLOAD - Limite de tempo por usuário (hh:mm)'));
        ?>
    </div>
    <div class="col-md-5">
        <?php
        echo $this->Form->input('dias_expiracao_senha', array('type'=>'text', 'div' => array('class' => 'form-group'),
            'class' => 'form-control soNumero',
            'label' => 'Quantidade de dias até senha expirar'));
        ?>
    </div>
    <div class="col-md-5">
        <?php
        echo $this->Form->input('quantidade_historico_senha', array('type'=>'text', 'div' => array('class' => 'form-group'),
            'class' => 'form-control soNumero',
            'label' => 'Tamanho do histórico de senha'));
        ?>
    </div>
</div>