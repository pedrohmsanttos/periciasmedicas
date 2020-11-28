<!--sidebar start-->
<aside>
	<div id="sidebar" class="nav-collapse">
		<!-- sidebar menu start-->
		<div class="leftside-navigation">
			<ul class="sidebar-menu" id="nav-accordion">
				<li><a class="active"
				       href="<?php echo Router::url('/home/index'); ?>"> <i
							class="fa fa-dashboard"></i>Início
					</a></li>
				<li><a href="javascript:;"> <i class="fa fa-laptop"></i>
						<span><?php echo __('Administrativo'); ?></span>
					</a>
					<ul>
						<li><a href="#"><?php echo __('Controle de Acesso'); ?></a>
							<ul>
								<li><a href="#"><?php echo __('Usuários'); ?></a>
									<ul>
										<li>
											<?php
											echo $this->Html->link(__("Consultar"), array(
												'controller' => 'usuarios',
												'action' => 'index'
											));
											?>
										</li>
									</ul>
								</li>
								<li><a href="#"><?php echo __('Perfis'); ?></a>
									<ul>
										<li>
											<?php
											echo $this->Html->link(__("Consutlar"), array(
												'controller' => 'perfil',
												'action' => 'index'
											));
											?>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>

				<li><a href="javascript:;"> <i class="fa fa-laptop"></i>
						<span><?php echo __('Configurações do Sistema'); ?></span>
					</a>
					<ul>
						<li><a href="#"><?php echo __('Cargo'); ?></a>
							<ul>
								<li>
									<?php
									echo $this->Html->link(__("Consultar"), array(
										'controller' => 'cargos',
										'action' => 'index'
									));
									?>
								</li>
							</ul>
						</li>
						<li><a href="#"><?php echo __('Função'); ?></a>
							<ul>
								<li>
									<?php
									echo $this->Html->link(__("Consultar"), array(
										'controller' => 'funcao',
										'action' => 'index'
									));
									?>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<!-- sidebar menu end-->
	</div>
</aside>
<!--sidebar end-->