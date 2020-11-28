    <?php  setlocale( LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese' );
	date_default_timezone_set( 'America/Sao_Paulo' ); ?>
    <div class="col-sm-12">
 
        <section class="panel">
            <header class="panel-heading"> Agenda do Perito </header>
			<div class="panel-body">
                <div class="adv-table editable-table ">
                	<div class="row float-right btn-edit">
						<!-- <button class="btn fa fa-print estiloBotao btn-primary" id="btn-imprimir" evalscripts="1" type="button"> Imprimir</button> -->
    				</div>
    				
    				<table class="table table-bordered">
					<?php if(isset($todasAgendas) && !empty($todasAgendas)): ?>	
						<?php foreach ($todasAgendas as $dia => $agendasDoDia): ?>
								<tr style="background-color: #ddd">
									<!-- <th ><b><?php //echo utf8_encode( Util::primeiraLetraMaiusculaDia(strftime( '%A', strtotime( $dia ) )) ) ?> </b></th> -->
									<th ><b><?=  Util::primeiraLetraMaiusculaDia(strftime( '%A', strtotime( $dia ) ))  ?> </b></th>
									<th ></th>
									<th ></th>
									<!-- <th style="text-align: right;"><b><?php //echo  utf8_encode(strftime('%d de %B de %Y', strtotime( $dia ) )) ?> </b></th> -->
									<th style="text-align: right;"><b><?=  strftime('%d de %B de %Y', strtotime( $dia ) ) ?> </b></th>
								</tr>
							<?php foreach ($agendasDoDia as $horario => $itemAgenda): ?>
								<?php foreach ($itemAgenda as $agendaPerito): ?>
								<tr style="border: 1px solid #ddd;">
									<td style=" width: 200px; border: 1px solid #ddd;text-align: center;"><?= $horario ?> </td>
									<td style=" width: 200px; border: 1px solid #ddd;text-align: center; " ><?= $agendaPerito['UnidadeAtendimento'] ?> </td>
									<td style=" width: 200px; border: 1px solid #ddd;text-align: center;" > <?= $agendaPerito['Perito'] ?> </td>
									<td style=" border: 1px solid #ddd;"> <span><?= $agendaPerito['Tipologias'] ?> </span></td>
								</tr>
								<?php endforeach; ?>
							<?php endforeach; ?>
						<?php endforeach; ?>
					<?php else: ?>
						<p style="text-align: center;">Não há agendas cadastradas</p>	
					<?php endif;?>
					</table>

            </div>
        </section>
    </div>
   