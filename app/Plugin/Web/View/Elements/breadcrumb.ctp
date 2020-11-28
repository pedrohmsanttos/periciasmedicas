<div class="row">
    <div class="col-md-12">
		<!--breadcrumbs start -->
		<ul class="breadcrumb">
			<li>
                            <?php
                            echo $this->Html->link(__("menu_home"), array(
                                                'plugin' => 'web',
                                                'controller' => 'dashboard',
                                                'action' => 'index'
                                            ));
                            ?>
                        </li>
			<?php 
				foreach ($menus as $item) {
					
			?>
			<li><a> <?php echo $item?></a></li>
			
			<?php }?>
		</ul>
		<!--breadcrumbs end -->
    </div>
</div>