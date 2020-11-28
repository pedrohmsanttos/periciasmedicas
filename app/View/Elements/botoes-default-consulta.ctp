<div class="row float-right">
<?php 
	echo $this->Form->button ( __('Consultar'), array (
			'class' => 'btn fa fa-search estiloBotao btn-primary',
			'update' => '#main-content','evalScripts' => true
	) );
	
	$urlConsulta = Router::url(array('controller'=>"$controller", 'action'=>'index'));
	
	echo $this->Form->button ( __('Limpar'), array (
			'class' => 'btn fa fa-eraser estiloBotao btn-primary',
			'type'  => 'button',
			'onclick' => "location.href = '$urlConsulta'"
	) );
	
	$urlCadastro = Router::url(array('controller'=>"$controller", 'action'=>'adicionar'));
	
	echo $this->Form->button ( __($label_botao_adicionar), array (
			'class' => 'btn fa fa-file-text-o estiloBotao btn-primary',
			'type' => 'button',
			'onclick' => "location.href = '$urlCadastro'"
	) );
	
	echo $this->Form->input('limitConsulta', array('type' => 'hidden', 	'id' => 'limitConsulta'));
?>
</div>