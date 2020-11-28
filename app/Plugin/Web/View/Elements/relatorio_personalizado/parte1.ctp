<fieldset class="scheduler-border">
    <legend class="scheduler-border">Filtros Geral</legend>

    <div class="row">
        <div class="col-md-4 form-group" >
            <?=
            $this->Form->input('controle', array('div' => array('class' => 'form-group'),
            'id' => 'controle',
            'class' => 'form-control',
            'options' => $controle,
            'label' => __('Controle*'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
        </div>
        <div class="col-md-4 form-group" >
            <?=
            $this->Form->input('ano_exercicio', array('div' => array('class' => 'form-group'),
            'id' => 'ano_exercicio',
            'class' => 'form-control',
            'options' => $ano_exercicio,
            'label' => __('Exercício*'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
        </div>
        <!--<div class="col-md-4 form-group" >
            <?=
            $this->Form->input('agrupado', array('div' => array('class' => 'form-group'),
            'id' => 'ano_exercicio',
            'class' => 'form-control',
            'options' => $agrupado,
            'label' => __('Agrupado Por*'),
            'empty' => __('label_selecione'),
            'required'=>false));
            ?>
        </div> -->
    </div>

    <div class="row">
    	<div class="col-md-6 form-group">
    		<?=
            $this->Form->input('filtros_licenca', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_licenca',
            'class' => 'form-control',
            'options' => $filtros_licenca,
            'multiple' => 'multiple',
            'label' => __('Filtros Licença'),
            'required'=>false));
            ?>
    	</div>		
    </div>

    <div class="row float-right btn-edit">
        <button class="btn fa fa-arrow-right estiloBotao btn-info" type="button" id="next-1"> Próximo</button>
        <!-- <button class="btn fa fa-eraser estiloBotao btn-danger" type="button" onclick="location.href = '/spm/fontes/trunk/web/Relatorio/personalizado'"> Limpar</button>                 -->
    </div>

</fieldset>