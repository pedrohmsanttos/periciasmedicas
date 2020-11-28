<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Tabagismo') ?> </legend>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Fuma?</label><br>
                <?php
                $options = array('0' => 'Não', '1' => 'Sim', '2' => 'Já fez uso');
                $attribute = array('legend'=>false, 'separator'=> " &nbsp; ", 'disabled' => $formDisabled);
                echo $this->Form->radio('PreAdmissional.rh_tabagismo',$options, $attribute);
                ?>
            </div>
        </div>
        <div class="col-md-3">
            <?php
            echo
            $this->Form->input('PreAdmissional.rh_tabagismo_fez_uso', array('div' => array('class' => 'form-group'),
                'class' => 'form-control tabagismo_fez_uso',
                'type' => 'text',
                'label' => __('A quanto tempo fez uso?'),
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo
            $this->Form->input('PreAdmissional.rh_tabagismo_quantidade', array('div' => array('class' => 'form-group'),
                'class' => 'form-control tabagismo_quantidade',
                'type' => 'text',
                'maxlength' => '4',
                'label' => __('Cigarros/dia'),
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo
            $this->Form->input('PreAdmissional.rh_tabagismo_periodo', array('div' => array('class' => 'form-group'),
                'class' => 'form-control tabagismo_periodo',
                'type' => 'text',
                'maxlength' => '4',
                'label' => __('Por quantos anos?'),
                'disabled' => $formDisabled));
            ?>
        </div>
    </div>
</fieldset>
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Exercício Físico') ?> </legend>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Pratica exercício físico?</label><br>
                <?php
                $options = array('0' => 'Não', '1' => 'Sim', '2' => 'Irregularmente', '3'=> 'Regularmente < 150 min / semana', '4'=> 'Regularmente > 150 min / semana');
                $attribute = array('legend'=>false, 'separator'=> " &nbsp; ", 'disabled' => $formDisabled);
                echo $this->Form->radio('PreAdmissional.rh_exercicio',$options, $attribute);
                ?>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo $this->Form->input('PreAdmissional.rh_exercicio_restricao', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'disabled' => $formDisabled,
                'type' => 'textarea',
                'onkeyup' => "limitarTamanho(this,8000);",
                'onblur' => "limitarTamanho(this,8000);",
                'label' => __('Alguma restrição medica a exercícios?')));
            ?>
        </div>
    </div>
</fieldset>
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Bebida Alcoólica') ?> </legend>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Ingere bebida alcoólica?</label><br>
                <?php
                $options = array('0' => 'Não', '1' => 'Sim');
                $attribute = array('legend'=>false, 'separator'=> " &nbsp; ", 'disabled' => $formDisabled);
                echo $this->Form->radio('PreAdmissional.rh_bebida',$options, $attribute);
                ?>
            </div>
        </div>
        <div class="col-md-3">
            <?php
            echo
            $this->Form->input('PreAdmissional.rh_bebida_quantidade', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'label' => __('Dose /semana'),
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo
            $this->Form->input('PreAdmissional.rh_bebida_tempo', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'label' => __('Por quantos anos?'),
                'disabled' => $formDisabled));
            ?>
        </div>
    </div>
</fieldset>
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Outro') ?> </legend>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo $this->Form->input('PreAdmissional.rh_habitos_outros', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'disabled' => $formDisabled,
                'type' => 'textarea',
                'onkeyup' => "limitarTamanho(this,8000);",
                'onblur' => "limitarTamanho(this,8000);",
                'label' => __('Hábitos / Faz uso de drogas')));
            ?>
        </div>
    </div>
</fieldset>