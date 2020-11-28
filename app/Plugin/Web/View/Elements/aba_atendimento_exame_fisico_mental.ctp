<div class="row">
    <div class="col-md-2">
        <?php
        echo
        $this->Form->input('altura', array('div' => array('class' => 'form-group'),
            'class' => 'form-control altura',
            'type' => 'text',
            
            'label' => __('atendimento_laudo_label_altura'),
            'disabled' => $formDisabled));
        ?>
    </div> 
    <div class="col-md-2">
        <?php
        echo
        $this->Form->input('peso', array('div' => array('class' => 'form-group'),
            'class' => 'form-control peso',
            'type' => 'text',
            
            'maxlength' => '5',
            'label' => __('atendimento_laudo_label_peso'),
            'disabled' => $formDisabled));
        ?>
    </div>
    <div class="col-md-2">
        <?php
        echo
        $this->Form->input('temperatura', array('div' => array('class' => 'form-group'),
            'class' => 'form-control temperatura',
            'type' => 'text',
            
            'maxlength' => '4',
            'label' => __('atendimento_laudo_label_temperatura'),
            'disabled' => $formDisabled));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php
        echo
        $this->Form->input('faceis', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            
            'label' => __('atendimento_laudo_label_faceis'),
            'disabled' => $formDisabled));
        ?>
    </div>
    <div class="col-md-6">
        <?php
        echo
        $this->Form->input('estado_nutricao', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            
            'label' => __('atendimento_laudo_label_estado_nutricao'),
            'disabled' => $formDisabled));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php
        echo
        $this->Form->input('mucoses_visiveis', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            
            'label' => __('atendimento_laudo_label_mucoses_visiveis'),
            'disabled' => $formDisabled));
        ?>
    </div>
    <div class="col-md-6">
        <?php
        echo
        $this->Form->input('atitude', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            
            'label' => __('atendimento_laudo_label_atitude'),
            'disabled' => $formDisabled));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php
        echo
        $this->Form->input('tecido_celular_subcutaneo', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            
            'label' => __('atendimento_laudo_label_tecido_celular_subcutaneo'),
            'disabled' => $formDisabled));
        ?>
    </div>
    <div class="col-md-6">
        <?php
        echo
        $this->Form->input('pele_faneros', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            
            'label' => __('atendimento_laudo_label_pele_faneros'),
            'disabled' => $formDisabled));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('defeitos_fisicos', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            
            'disabled' => $formDisabled,
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_defeitos_fisicos')));
        ?>
    </div>
</div>
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('atendimento_laudo_label_aparelho_circulatorio') ?> </legend>
    <div class="row">
        <div class="col-md-2">
            <?php
            echo
            $this->Form->input('tensao_arterial', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                
                'label' => __('atendimento_laudo_label_tensao_arterial'),
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-2">
            <?php
            echo
            $this->Form->input('pulso', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                
                'label' => __('atendimento_laudo_label_pulso'),
                'disabled' => $formDisabled));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo $this->Form->input('observacoes', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'type' => 'textarea',
                
                'disabled' => $formDisabled,
                'onkeyup' => "limitarTamanho(this,4000);",
                'onblur' => "limitarTamanho(this,4000);",
                'label' => __('atendimento_laudo_label_observacoes')));
            ?>
        </div>
    </div>
</fieldset>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('aparelho_respiratorio', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            
            'disabled' => $formDisabled,
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_aparelho_respiratorio')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('aparelho_digestivo', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'textarea',
            
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_aparelho_digestivo')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('aparelho_linfo_hemopoetico', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            
            'disabled' => $formDisabled,
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_aparelho_linfo_hemopoetico')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('aparelho_genitor_urinario', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'textarea',
            
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_aparelho_genito_urinario')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('aparelho_osteo_articular', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            
            'disabled' => $formDisabled,
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_aparelho_osteo_articular')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('exame_neuro_psiquiatrico', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'textarea',
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_exame_neuro_psiquiatrico')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('sensibilidade_geral_especial', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            
            'disabled' => $formDisabled,
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_sensibilidade_geral_especial')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('exames_complementares', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'textarea',
            
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_exames_complementares')));
        ?>
    </div>
</div>
<? echo  $this->Html->script('inputmask', array('block' => 'script')); ?>
<? echo  $this->Html->script('inputmask-regex-extensions', array('block' => 'script')); ?>
<? echo  $this->Html->script('jquery-inputmask', array('block' => 'script')); ?>
<? echo  $this->Html->script('Web.atendimento-exame-fisico', array('block' => 'script')); ?>

