<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading"> Cadastro de Usuário </header>
			<div class="panel-body">
				<div class="row">
				<?php
				echo $this->Form->create ( 'Usuario', array (
						'inputDefaults' => array (
								'class' => 'form-control' 
						) ,
						'novalidate'
				) );
				
				echo $this->Form->input ( 'nome', array (
						'div' => array (
								'class' => 'col-md-6 form-group' 
						) 
				) );
				echo $this->Form->input ( 'cpf', array (
						'div' => array (
								'class' => 'col-md-3 form-group' 
						) 
				) );
				echo $this->Form->input ( 'senha', array (
						'div' => array (
								'class' => 'col-md-3 form-group'
						),
						'type' => 'password'
				) );
				?>
			</div>
				<div class="row float-right">
				<?php
				echo $this->Form->button ( 'Salvar', array (
						'class' => 'btn' 
				) );
				echo $this->Form->button ( 'Salvar e Incluir Novo', array (
						'class' => 'btn' 
				) );
				echo $this->Form->button ( 'Ir para consulta', array (
						'class' => 'btn' 
				) );
				echo $this->Form->button ( 'Limpar', array (
						'class' => 'btn' 
				) );
				
				echo $this->Form->end ();
				?>
			</div>


			</div>
		</section>

	</div>
</div>