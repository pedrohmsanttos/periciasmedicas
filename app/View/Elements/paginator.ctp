<div class="col-md-3 col-md-offset-4">
	<div class="span6">
		<div class="dataTables_paginate paging_bootstrap pagination">
			<ul>
				<?php
				$this->Paginator->options ( array (
						'update' => '#grid',
						'evalScripts' => true 
				) );
				echo $this->Paginator->prev ( __ ( '←' ), array (
						'tag' => 'li' 
				), null, array (
						'tag' => 'li',
						'class' => 'prev',
						'disabledTag' => 'a',
						'class' => 'disabled' 
				) );
				echo $this->Paginator->numbers ( array (
						'separator' => '',
						'currentTag' => 'a',
						'currentClass' => 'active',
						'tag' => 'li',
						'first' => 1,
						'modulus' => 4,
						'ellipsis' => '',
						'href' => '#' 
				) );
				echo $this->Paginator->next ( __ ( '→' ), array (
						'tag' => 'li',
						'currentClass' => 'disabled' 
				), null, array (
						'tag' => 'li',
						'class' => 'disabled',
						'disabledTag' => 'a' 
				) );
				?>
			</ul>
		</div>
	</div>
</div>
<div class="col-md-1">
	<div class="span6">
	<?php
	$arrTipo = array (
			'10' => '10',
			'25' => '25',
			'50' => '50' 
	);
	
	echo $this->Form->input ( 'limiteConsultaSelecionado', array (
			'options' => $arrTipo,
			'id' => 'registros_pagina',
			'label' => false,
			'class' => 'form-control small estiloTamanhoPaginacao',
			'aria-controls' => 'editable-sample',
			'value' => $limiteConsultaSelecionado 
	) );
	?>
	</div>
</div>
<?php echo $this->Js->writeBuffer();?>