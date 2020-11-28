<div style="margin-bottom: 15px;"><?php echo __(' - Especificar doenças ativas e já resolvidas - ') ?></div>

<?
echo $this->PForm->radioYND(array(
    'title' => 'Internações hospitalares',
    'name' => 'PreAdmissional.hpis_internacoes',
    'disabled' => $formDisabled
));

echo $this->PForm->radioYND(array(
    'title' => 'Cirurgias',
    'name' => 'PreAdmissional.hpis_cirurgias',
    'disabled' => $formDisabled
));

echo $this->PForm->radioYND(array(
    'title' => 'Transfusão sanguínea',
    'name' => 'PreAdmissional.hpis_transfusao_sanguinea',
    'disabled' => $formDisabled
));

?>
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Doenças / Tratamentos') ?> </legend>
    <?php
    echo $this->PForm->radioYND(array(
        'title' => 'Neoplasias (Câncer)',
        'name' => 'PreAdmissional.hpis_neoplasias',
        'disabled' => $formDisabled
    ));
    ?>
</fieldset>

<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Sistema Circulatório (Cardiovascular)') ?> </legend>
    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Hipertensão Arterial (HAS)',
        'name' => 'PreAdmissional.hpis_sc_hipertensao',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sc_hipertensao_tratamento_reg',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYND(array(
        'title' => 'Isquemia/ Infarto/ Angina pectoris',
        'name' => 'PreAdmissional.hpis_sc_isquemia_infarto_angina',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDT(array(
        'title' => 'Trombose / Embolia',
        'name' => 'PreAdmissional.hpis_sc_trombose_embolia',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sc_trombose_embolia_tratamento_reg',
        'disabled' => $formDisabled
    ));

    ?>

    <div class="row row-item-top">
        <?php
        echo $this->PForm->radioYN(array(
            'title' => 'Outras doenças cardiovasculares',
            'name' => 'PreAdmissional.hpis_sc_outras',
            'disabled' => $formDisabled,
            'column' => 3
        ));

        echo $this->PForm->radio(array(
            'options'=> array('0' => 'Passado', '1' => 'Ativa'),
            'title' => '',
            'name' => 'PreAdmissional.hpis_sc_outras_tempo',
            'disabled' => $formDisabled,
            'column' => 3
        ));

        ?>
    </div>
    <div class="row row-item-mid"  >
        <?php
        echo $this->PForm->text(array(
            'title' => 'Quando?',
            'name' => 'PreAdmissional.hpis_sc_outras_quando',
            'disabled' => $formDisabled,
            'column' => 6
        ));

        echo $this->PForm->text(array(
            'title' => 'Detalhes',
            'name' => 'PreAdmissional.hpis_sc_outras_desc',
            'disabled' => $formDisabled,
            'column' => 6
        ));
        ?>
    </div>
    <?php
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sc_outras_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>
</fieldset>


<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Sistema Digestório') ?> </legend>
    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Esôfago/Estômago/Fígado',
        'name' => 'PreAdmissional.hpis_sd_esofago_estomago_figado',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sd_esofago_estomago_figado_tratamento_reg',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDT(array(
        'title' => 'Cólon/Reto/Ânus',
        'name' => 'PreAdmissional.hpis_sd_colon_reto_anus',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sd_colon_reto_anus_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>
    <div class="row row-item">
        <?
        echo $this->PForm->radio(array(
            'title' => 'Última consulta ao dentista',
            'name' => 'PreAdmissional.hpis_sd_ultima_consulta_dentista',
            'options' => array('0' => 'Há menos de um ano', '1' => 'Há mais de um ano'),
            'disabled' => $formDisabled,
            'column' => 5
        ));
        ?>
    </div>
</fieldset>

<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Sistema Endócrino (hormonal)') ?> </legend>
    <?
    echo $this->PForm->radioYNDT(array(
        'title' => 'Diabetes Mellitus',
        'name' => 'PreAdmissional.hpis_se_diabetes_mellitus',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_se_diabetes_mellitus_tratamento_reg',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDT(array(
        'title' => 'Alterações de Tireoide',
        'name' => 'PreAdmissional.hpis_se_tireoide',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_se_tireoide_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>
</fieldset>

<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Sistema Imunitário/ Doenças infecciosas') ?> </legend>
    <?
    echo $this->PForm->radioYNDT(array(
        'title' => 'Alergias (a medicamento, alimento, contato, respiratória)',
        'name' => 'PreAdmissional.hpis_sidi_alergias',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sidi_alergias_tratamento_reg',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDT(array(
        'title' => 'Doenças Autoimunes',
        'name' => 'PreAdmissional.hpis_sidi_autoimunes',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sidi_autoimunes_tratamento_reg',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDT(array(
        'title' => 'Doenças infecciosas crônicas (Hepatites, HPV, VIH, Sífilis, Tuberculose, Outras)',
        'name' => 'PreAdmissional.hpis_sidi_infecciosas_cronicas',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sidi_infecciosas_cronicas_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>
    <div class="row row-item" >
        <?
        echo $this->PForm->radio(array(
            'title' => 'Imunizações (vacinações): Vacinação completa, segundo calendário vacinal para adulto',
            'name' => 'PreAdmissional.hpis_sidi_imunizacoes',
            'disabled' => $formDisabled,
            'options' => array('1'=> 'Sim', '0'=> 'Não', '2'=> 'Não sei'),
            'column' => 5
        ));
        echo $this->PForm->text(array(
            'title' => 'Detalhe',
            'name' => 'PreAdmissional.hpis_sidi_imunizacoes_desc',
            'disabled' => $formDisabled,
            'column' => 7
        ));
        ?>
    </div>
</fieldset>
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Sistema Locomotor') ?> </legend>
    <?
    echo $this->PForm->radioYNDT(array(
        'title' => 'Músculos, tendões, ossos, articulações, coluna',
        'name' => 'PreAdmissional.hpis_sl_sistema_locomotor',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sl_sistema_locomotor_tratamento_reg',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDT(array(
        'title' => 'Dor crônica',
        'name' => 'PreAdmissional.hpis_sl_dor_cronica',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sl_dor_cronica_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>
</fieldset>

<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Sistema Nervoso') ?> </legend>
    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Cérebro, tronco cerebral, pares cranianos, medula neural, nervos periféricos',
        'name' => 'PreAdmissional.hpis_sn_sistema_nervoso',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sn_sistema_nervoso_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>
</fieldset>

<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Condições Psicológicas ou Psiquiátricas') ?> </legend>
    <div>
        Já fez tratamentos psicológicos ou psiquiátricos, já foi encaminhado por profissionais de saúde para tratamento
        psicológico ou psiquiátrico, já sentiu a necessidade de fazer tratamentos psicológicos ou psiquiátricos?
    </div>
    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Psicológico',
        'name' => 'PreAdmissional.hpis_cpp_psicologio',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_cpp_psicologio_tratamento_reg',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYND(array(
        'title' => 'Psiquiátrico',
        'name' => 'PreAdmissional.hpis_cpp_psiquiatrico',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDT(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_cpp_psiquiatrico_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>
</fieldset>

<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Sistema Tegumentar') ?> </legend>
    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Pele, cabelos, pelos e unhas',
        'name' => 'PreAdmissional.hpis_st_sistema_tegumentar',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_st_sistema_tegumentar_tratamento_reg',
        'disabled' => $formDisabled
    ));

    ?>
</fieldset>

<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Sistema Urinário e Reprodutivo') ?> </legend>
    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Rins/ Vias urinárias (ureteres, bexiga, uretra)',
        'name' => 'PreAdmissional.hpis_sur_rins_vias_urinarias',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sur_rins_vias_urinarias_tratamento_reg',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDT(array(
        'title' => 'Ginecológico',
        'name' => 'PreAdmissional.hpis_sur_ginecologico',
        'disabled' => $formDisabled
    ));
    ?>
    <div class="row row-item-bottom" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sur_ginecologico_tratamento_reg',
        'disabled' => $formDisabled,
        'options' => array('1'=> 'Sim', '0'=> 'Não', '2'=> 'Prefiro não especificar'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.hpis_sur_ginecologico_tratamento_reg_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
    </div>
    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Aparelho reprodutivo masculino',
        'name' => 'PreAdmissional.hpis_sur_reprodutivo_masculino',
        'disabled' => $formDisabled
    ));
    ?>
    <div class="row row-item-bottom" >
    <?php
    echo $this->PForm->radio(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_sur_reprodutivo_masculino_tratamento_reg',
        'disabled' => $formDisabled,
        'options' => array('1'=> 'Sim', '0'=> 'Não', '2'=> 'Prefiro não especificar'),
        'column' => 5
    ));

    echo $this->PForm->text(array(
        'title' => 'Detalhes',
        'name' => 'PreAdmissional.hpis_sur_reprodutivo_masculino_tratamento_reg_desc',
        'disabled' => $formDisabled,
        'column' => 7
    ));
    ?>
    </div>
</fieldset>
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Visão/Audição') ?> </legend>
    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Algum problema de visão? ',
        'name' => 'PreAdmissional.hpis_va_visao',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDM(array(
        'title' => 'Usa Correção visual?',
        'name' => 'PreAdmissional.hpis_va_visao_correcao_visual',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular? ',
        'name' => 'PreAdmissional.hpis_va_visao_tratamento_reg',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDT(array(
        'title' => 'Algum problema de audição?  ',
        'name' => 'PreAdmissional.hpis_va_audicao',
        'disabled' => $formDisabled
    ));
    echo $this->PForm->radioYNDM(array(
        'title' => 'Usa aparelho?',
        'name' => 'PreAdmissional.hpis_va_audicao_aparelho',
        'disabled' => $formDisabled
    ));

    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular? ',
        'name' => 'PreAdmissional.hpis_va_audicao_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>
</fieldset>

<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Sistema Respiratório, nariz, orelhas e garganta') ?> </legend>
    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Pulmões / Brônquios',
        'name' => 'PreAdmissional.hpis_srnog_pulmoes_bronquios',
        'disabled' => $formDisabled
    ));
    ?>
    <?php
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_srnog_pulmoes_bronquios_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>

    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Nariz, orelhas, gargantas, seios nasais',
        'name' => 'PreAdmissional.hpis_srnog_nariz_orelhas_gargantas',
        'disabled' => $formDisabled
    ));
    ?>
    <?php
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_srnog_nariz_orelhas_gargantas_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>

    <?php
    echo $this->PForm->radioYNDT(array(
        'title' => 'Outros problemas',
        'name' => 'PreAdmissional.hpis_srnog_sistema_respiratorio_outros',
        'disabled' => $formDisabled
    ));
    ?>
    <?php
    echo $this->PForm->radioYNDB(array(
        'title' => 'Em tratamento regular?',
        'name' => 'PreAdmissional.hpis_srnog_sistema_respiratorio_outros_tratamento_reg',
        'disabled' => $formDisabled
    ));
    ?>
</fieldset>

<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo __('Uso de Medicamentos') ?> </legend>
    <?php
    echo $this->PForm->radioYND(array(
        'title' => 'Uso Regular ou frequente de Medicamentos',
        'name' => 'PreAdmissional.hpis_medicamentos',
        'disabled' => $formDisabled
    ));
    ?>
</fieldset>

