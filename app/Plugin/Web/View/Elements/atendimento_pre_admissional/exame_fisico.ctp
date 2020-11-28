<div class="row">
    <div class="col-md-2">
        <?php
        echo
        $this->Form->input('PreAdmissional.ef_altura', array('div' => array('class' => 'form-group'),
            'class' => 'form-control altura',
            'type' => 'text',
            'label' => __('atendimento_laudo_label_altura'),
            'disabled' => $formDisabled));
        ?>
    </div>
    <div class="col-md-2">
        <?php
        echo
        $this->Form->input('PreAdmissional.ef_peso', array('div' => array('class' => 'form-group'),
            'class' => 'form-control typeNumber typeNumber_+float2',
            'type' => 'text',
            'maxlength' => '5',
            'label' => __('atendimento_laudo_label_peso'),
            'disabled' => $formDisabled));
        ?>
    </div>
    <div class="col-md-2">
        <?php
        echo
        $this->Form->input('PreAdmissional.ef_pressao', array('div' => array('class' => 'form-group'),
            'class' => 'form-control pressao',
            'type' => 'text',
            'maxlength' => '5',
            'label' => __('Pressão Arterial'),
            'disabled' => $formDisabled));
        ?>
    </div>
    <div class="col-md-2">
        <?php
        echo
        $this->Form->input('PreAdmissional.ef_temperatura', array('div' => array('class' => 'form-group'),
            'class' => 'form-control typeNumber typeNumber_+float1',
            'type' => 'text',
            'maxlength' => '4',
            'label' => __('atendimento_laudo_label_temperatura'),
            'disabled' => $formDisabled));
        ?>
    </div>
</div>

<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Pele e fâneros',
        'name' => 'PreAdmissional.ef_pele_faneros',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas<br>', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_pele_faneros_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>

<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Gânglios',
        'name' => 'PreAdmissional.ef_ganglios',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_ganglios_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>
<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Orelhas/Nariz/Boca/Orofaringe',
        'name' => 'PreAdmissional.ef_orelhas_nariz_boca_orofaringe',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_orelhas_nariz_boca_orofaringe_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>
<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Pescoço',
        'name' => 'PreAdmissional.ef_pescoco',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_pescoco_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>

<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Sist. Respiratório',
        'name' => 'PreAdmissional.ef_respiratorio',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_respiratorio_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>
<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'S. Cardiovascular',
        'name' => 'PreAdmissional.ef_cardiovascular',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_cardiovascular_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>
<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Abdome',
        'name' => 'PreAdmissional.ef_abdome',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_abdome_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>
<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Sist. Locomotor',
        'name' => 'PreAdmissional.ef_locomotor',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_locomotor_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>
<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Sist. Nervoso',
        'name' => 'PreAdmissional.ef_nervoso',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_nervoso_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>

<div class="row row-item" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Ap. Gênito urinário',
        'name' => 'PreAdmissional.ef_ap_genito_urinario',
        'disabled' => $formDisabled,
        'options' => array('0'=> 'Não examinado', '1'=> 'Sem alterações significativas', '2'=> 'Houve alterações'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.ef_ap_genito_urinario_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
</div>

<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('PreAdmissional.ef_outras_alteracoes', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'textarea',
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('Outras Alterações')));
        ?>
    </div>
</div>