<div class="row float-right">
	<?php
	
	if($acao == Configure::read('ACAO_INSERIR') || $acao == Configure::read('ACAO_ALTERAR')){
		echo $this->Form->button ( __('Salvar'), array (
				'class' => 'btn fa fa-save estiloBotao btn-primary',
				'value' => 'true',
				'name' => 'salvarButton' 
		) );
		echo $this->Form->button ( __('Salvar e Incluir Novo'), array (
				'class' => 'btn fa fa-save estiloBotao btn-primary',
				'value' => 'true',
				'name' => 'salvarContinuarButton' 
		) );
	}
	if($acao == Configure::read('ACAO_EXCLUIR')){
		echo $this->Form->button ( __('Excluir'), array (
				'class' => 'btn fa fa-trash-o estiloBotao btn-primary',
		) );
	}
	
	$urlConsulta = Router::url(array('controller'=>"$controller", 'action'=>'index'));
	
	echo $this->Form->button ( __('Ir para consulta'), array (
			'class' => 'btn fa fa-search estiloBotao btn-primary',
			'type'  => 'button',
			'onclick' => "location.href = '$urlConsulta'"
	) );
	
	$urlCadastro = Router::url(array('controller'=>"$controller", 'action'=>'adicionar'));
	
	if($acao == Configure::read('ACAO_INSERIR')){
		echo $this->Form->button ( __('Limpar'), array (
				'class' => 'btn fa fa-eraser estiloBotao btn-primary',
				'type'  => 'button',
				'onclick' => "location.href = '$urlCadastro'"
		) );
	}
	?>
</div>