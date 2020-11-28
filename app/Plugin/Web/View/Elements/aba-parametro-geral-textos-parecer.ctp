<div class="row col-lg-12">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('maior_invalido_anterior', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            'maxlength' => '8000',
            'label' => __('parametro_geral_maior_invalido_anterior')));
        ?>
    </div>
    <div class="col-md-12">
        <?php
        echo $this->Form->input('maior_invalido_partir', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
            'maxlength' => '8000',
            'label' => __('parametro_geral_maior_invalido_partir')));
        ?>
    </div>
    <div class="col-md-12">
        <?php
        echo $this->Form->input('aposentadoria_invalidez_integral', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
            'maxlength' => '8000',
            'label' => __('parametro_geral_aposentadoria_invalidez_integral')));
        ?>
    </div>
    <div class="col-md-12">
        <?php
        echo $this->Form->input('aposentadoria_invalidez_proporcional', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
            'maxlength' => '8000',
            'label' => __('parametro_geral_aposentadoria_invalidez_proporcional')));
        ?>
    </div>
    <div class="col-md-12">
        <?php
        echo $this->Form->input('isencao_imposto_renda_temporaria', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
            'maxlength' => '8000',
            'label' => __('parametro_geral_isencao_imposto_renda_temporaria')));
        ?>
    </div>
    <div class="col-md-12">
        <?php
        echo $this->Form->input('isencao_imposto_renda_definitiva', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
            'maxlength' => '8000',
            'label' => __('parametro_geral_isencao_imposto_renda_definitiva')));
        ?>
    </div>
    <div class="col-md-12">
        <?php
        echo $this->Form->input('isencao_contribuicao_previdenciaria_temporaria', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
            'maxlength' => '8000',
            'label' => __('parametro_geral_isencao_contribuicao_previdenciaria_temporaria')));
        ?>
    </div>
    <div class="col-md-12">
        <?php
        echo $this->Form->input('isencao_contribuicao_previdenciaria_definitiva', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
            'maxlength' => '8000',
            'label' => __('parametro_geral_isencao_contribuicao_previdenciaria_definitiva')));
        ?>
    </div>
</div>