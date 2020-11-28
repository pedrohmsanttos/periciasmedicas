<fieldset class="scheduler-border">
    <legend class="scheduler-border">Sub-filtros</legend>

    <div class="row">
    	<div class="col-md-12 form-group" id="unidades" style="display: none">
    		<?=
            $this->Form->input('unidades', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_unidades',
            'class' => 'form-control',
            'options' => $unidades_atendimento,
            'multiple' => 'multiple',
            'label' => __('Unidades de Atendimento'),
            'required'=>false));
            ?>
    	</div>		
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="sexo" style="display: none">
            <?=
            $this->Form->input('sexo', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_sexo',
            'class' => 'form-control',
            'options' => $sexo,
            'multiple' => 'multiple',
            'label' => __('Sexo'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="tipologia" style="display: none">
            <?=
            $this->Form->input('tipologia', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_tipologia',
            'class' => 'form-control',
            'options' => $tipologias,
            'multiple' => 'multiple',
            'label' => __('Tipologia'),
            // 'disabled' => true,
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="status_agendamento" style="display: none">
            <?=
            $this->Form->input('status_agendamento', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_status_agendamento',
            'class' => 'form-control',
            'options' => $status_agendamento,
            'multiple' => 'multiple',
            'label' => __('Status do Agendamento'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="status_atendimento" style="display: none">
            <?=
            $this->Form->input('status_atendimento', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_status_atendimento',
            'class' => 'form-control',
            'options' => $status_atendimento,
            'multiple' => 'multiple',
            'label' => __('Status do Atendimento'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="situacao_atendimento" style="display: none">
            <?=
            $this->Form->input('situacao_atendimento', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_situacao_atendimento',
            'class' => 'form-control',
            'options' => $situacao_atendimento,
            'multiple' => 'multiple',
            'label' => __('Situação do Atendimento'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="orgao_origem" style="display: none">
            <?=
            $this->Form->input('orgao_origem', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_orgao',
            'class' => 'form-control',
            'options' => $orgao_origem,
            'multiple' => 'multiple',
            'label' => __('Orgão de Origem'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="cargo" style="display: none">
            <?=
            $this->Form->input('cargo', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_cargo',
            'class' => 'form-control', 
            'options' => $cargo,
            'multiple' => 'multiple',
            'label' => __('Cargo'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="lotacao" style="display: none">
            <?=
            $this->Form->input('lotacao', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_lotacao',
            'class' => 'form-control',
            'options' => $lotacao,
            'multiple' => 'multiple',
            'label' => __('Lotação'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="funcao" style="display: none">
            <?=
            $this->Form->input('funcao', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_funcao',
            'class' => 'form-control',
            'options' => $funcao,
            'multiple' => 'multiple',
            'label' => __('Função'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="publicacao" style="display: none">
            <?=
            $this->Form->input('publicacao', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_publicacao',
            'class' => 'form-control',
            'options' => $publicacoes,
            'multiple' => 'multiple',
            'label' => __('Publicação'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
        <div class="col-md-12 form-group" id="tipo_usuario" style="display: none">
            <?=
            $this->Form->input('tipo_usuario', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_tipo_usuario',
            'class' => 'form-control',
            'options' => $tipo_usuario,
            'multiple' => 'multiple',
            'label' => __('Tipo de Usuário'),
            'required'=>false));
            ?>
        </div>      
    </div>

    <div class="row">
    	<div class="col-md-4 form-group">
    		<?=
            $this->Form->input('numero_laudo_opcao', array('div' => array('class' => 'form-group'),
            'id' => 'numero_laudo_opcao',
            'class' => 'form-control',
            'options' => $conteudo,
            'label' => __('N° Laudo (Opção)'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
    	</div>
    	<div class="col-md-4 form-group">
    		<?=
            $this->Form->input('numero_laudo', array('div' => array('class' => 'form-group'),
            'id' => 'numero_laudo',
            'class' => 'form-control',
            'label' => __('N° Laudo'),
            'required'=>false));
            ?>
    	</div>
    </div>

    <div class="row">
    	<div class="col-md-4 form-group">
    		<?=
            $this->Form->input('endereco_opcao', array('div' => array('class' => 'form-group'),
            'id' => 'endereco_opcao',
            'class' => 'form-control',
            'options' => $conteudo,
            'label' => __('Cidade (Opção)'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
    	</div>
    	<div class="col-md-4 form-group">
    		<?=
            $this->Form->input('endereco', array('div' => array('class' => 'form-group'),
            'id' => 'endereco',
            'class' => 'form-control',
            'label' => __('Cidade'),
            'required'=>false));
            ?>
    	</div>
    </div>

    <div class="row">
    	<div class="col-md-4 form-group">
    		<?=
            $this->Form->input('cid_opcao', array('div' => array('class' => 'form-group'),
            'id' => 'cid_opcao',
            'class' => 'form-control',
            'options' => $conteudo,
            'label' => __('CID (Opção)'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
    	</div>
    	<div class="col-md-4 form-group">
    		<?=
            $this->Form->input('cid', array('div' => array('class' => 'form-group'),
            'id' => 'cid',
            'class' => 'form-control',
            'label' => __('CID'),
            'required'=>false));
            ?>
    	</div>
    </div>

    <div class="row">
     	<div class="col-md-4 form-group">
    		<?=
            $this->Form->input('demais_campos', array('div' => array('class' => 'form-group'),
            'id' => 'demais_campos',
            'class' => 'form-control',
            'options' => $demais_campos,
            'label' => __('Demais Campos'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
    	</div>
    	<div class="col-md-4 form-group">
    		<?=
            $this->Form->input('demais_campos_opcao', array('div' => array('class' => 'form-group'),
            'id' => 'demais_campos_opcao',
            'class' => 'form-control',
            'options' => $conteudo,
            'label' => __('Demais Campos (Opção)'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
    	</div>
    	<div class="col-md-4 form-group">
    		<?=
            $this->Form->input('conteudo', array('div' => array('class' => 'form-group'),
            'id' => 'conteudo',
            'class' => 'form-control',
            'label' => __('Conteúdo'),
            'required'=>false));
            ?>
    	</div>
    </div>

    <div class="row">
     	<div class="col-md-3 form-group">
    		<?=
            $this->Form->input('periodo_inicio', array('div' => array('class' => 'form-group'),
            'id' => 'periodo_inicio',
            'class' => 'form-control',
            'options' => $periodo_inicio,
            'label' => __('Período Início'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
    	</div>
    	<div class="col-md-3 form-group">
    		<?=
            $this->Form->input('periodo_inicio_opcoes', array('div' => array('class' => 'form-group'),
            'id' => 'periodo_inicio_opcoes',
            'class' => 'form-control',
            'options' => $opcoes_periodo,
            'label' => __('Período (Opção)'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
    	</div>
    	<div class="col-md-3 form-group" id="periodoInicioDtInicio" style="display: none;">
    		<?=
            $this->Form->input('periodo_inic_dt_inic', array('maxlength' => 150, 'label' => __('De'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                'onblur' => 'VerificaData(this,this.value)',
                'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData',
                'required' => false));
            ?>
    	</div>

    	<div class="col-md-3 form-group" id="periodoInicioDtFim" style="display: none;">
    		<?=
            $this->Form->input('periodo_inic_dt_fim', array('maxlength' => 150, 'label' => __('Para'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                'onblur' => 'VerificaData(this,this.value)',
                'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData',
                'required' => false));
            ?>
    	</div>
    </div>

<!--
    <div class="row">
     	<div class="col-md-3 form-group">
    		<?=
            $this->Form->input('periodo_fim', array('div' => array('class' => 'form-group'),
            'id' => 'periodo_fim',
            'class' => 'form-control',
            'options' => $periodo_fim,
            'label' => __('Período Final'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
    	</div>
    	<div class="col-md-3 form-group">
    		<?=
            $this->Form->input('periodo_final_opcoes', array('div' => array('class' => 'form-group'),
            'id' => 'periodo_final_opcoes',
            'class' => 'form-control',
            'options' => $opcoes_periodo,
            'label' => __('Período (Opção)'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
    	</div>
    	<div class="col-md-3 form-group">
    		<?=
            $this->Form->input('periodo_fim_dt_inic', array('maxlength' => 150, 'label' => __('De'),   'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                'onblur' => 'VerificaData(this,this.value)',
                'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData',
                'required' => false));
            ?>
    	</div>

    	<div class="col-md-3 form-group">
    		<?=
            $this->Form->input('periodo_fim_dt_fim', array('maxlength' => 150, 'label' => __('Para'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                'onblur' => 'VerificaData(this,this.value)',
                'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData',
                'required' => false));
            ?>
    	</div>
    </div> -->

    <div class="row float-right btn-edit">
        <button class="btn fa fa-arrow-left estiloBotao btn-info" type="button" id="back-2"> Voltar</button>
        <button class="btn fa fa-arrow-right estiloBotao btn-info" type="button" id="next-2"> Próximo</button>
    </div>
</fieldset>