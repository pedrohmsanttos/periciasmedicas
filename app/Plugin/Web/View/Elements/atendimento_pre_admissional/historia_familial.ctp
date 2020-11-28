
<div style="margin-bottom: 15px;"><?php echo __('História Familial (parentes até 2º grau) - Especificar doença e relação de parentesco') ?> </div>
<?php

echo $this->PForm->radioYND(array(
    'title' => 'Câncer (próstata < 60 anos, mama, cólon, reto, ovário)',
    'name' => 'PreAdmissional.hf_cancer',
    'disabled' => $formDisabled
));

echo $this->PForm->radioYND(array(
    'title' => 'Outros cânceres',
    'name' => 'PreAdmissional.hf_cancer_outros',
    'disabled' => $formDisabled
));


echo $this->PForm->radioYND(array(
    'title' => 'Infarto ou AVC (“derrame”) (homens antes dos 55 e mulheres antes dos 65)',
    'name' => 'PreAdmissional.hf_infarto',
    'disabled' => $formDisabled
));

echo $this->PForm->radioYND(array(
    'title' => 'Doenças neurológicas, Mentais ou Psiquiátricas (incluir quadros demências)',
    'name' => 'PreAdmissional.hf_psico_neurologica',
    'disabled' => $formDisabled
));

echo $this->PForm->radioYND(array(
    'title' => 'Glaucoma',
    'name' => 'PreAdmissional.hf_glaucoma',
    'disabled' => $formDisabled
));

echo $this->PForm->radioYND(array(
    'title' => 'Diabetes mellitus',
    'name' => 'PreAdmissional.hf_diabetes_mellitus',
    'disabled' => $formDisabled
));

echo $this->PForm->radioYND(array(
    'title' => 'Doença renal (diálise)',
    'name' => 'PreAdmissional.hf_dialise',
    'disabled' => $formDisabled
));

echo $this->PForm->radioYND(array(
    'title' => 'Outras doenças recorrentes na família',
    'name' => 'PreAdmissional.hf_outras',
    'disabled' => $formDisabled
));

?>