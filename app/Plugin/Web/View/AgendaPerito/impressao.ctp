<?php
	setlocale( LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese' );
	date_default_timezone_set( 'America/Sao_Paulo' );

	App::import('Vendor', 'xtcpdf');
	$tcpdf = new XTCPDF('', true);
	$tcpdf->AddPage();
	

$html = "";

$html .= '<h1 style="text-align: center;font-family: Calibri;">Agenda de Atendimentos</h1>';
$html .= '<table style="font-family: Calibri; font-size: 10px; border: 1px solid #ddd;">';
$html .= "<tr><!-- 12 collumns -->
	<td ></td><td ></td><td ></td><td ></td>
</tr>";
	foreach ($todasAgendas as $dia => $agendasDoDia): 
		$html .='<tr style="background-color: #ddd">
					<th ><b>' . utf8_encode( Util::primeiraLetraMaiusculaDia(strftime( '%A', strtotime( $dia ) )) ) . '</b></th>
					<th ></th>
					<th ></th>
					<th style="text-align: right;"><b>'. utf8_encode(strftime('%d de %B de %Y', strtotime( $dia ) )) . '</b></th>
				</tr>';
		foreach ($agendasDoDia as $horario => $itemAgenda): 
			foreach ($itemAgenda as $agendaPerito): 
				$html .='<tr style="border: 1px solid #ddd;">
							<td style=" border: 1px solid #ddd;text-align: center;">' . $horario . '</td>
							<td style=" border: 1px solid #ddd;text-align: center; " >'. $agendaPerito['UnidadeAtendimento'] . '</td>
							<td style=" border: 1px solid #ddd;text-align: center;" > ' . $agendaPerito['Perito'] .'</td>
							<td style=" border: 1px solid #ddd;"> <span>' . $agendaPerito['Tipologias'] . '</span></td>
						</tr>';
			endforeach;
		endforeach;
	endforeach;
$html .="</table>";

// echo $html;die;
$tcpdf->writeHTML($html, true, false, true, false, '');
// ob_end_clean();
echo $tcpdf->Output('agenda_' . date('Ymdhis') . '.pdf', 'D');
die;
