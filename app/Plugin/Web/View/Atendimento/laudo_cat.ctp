<?php

App::import('Vendor', 'xtcpdf');

$tcpdf = new XTCPDF(' ');
$tcpdf->AddPage();
$conteudoTable = '';
$html = '
<table style="font-family: Calibri; font-size: 10px;">
<tr><!-- 12 collumns -->
	<td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td>
</tr>
<tr>
	<td colspan="4" align="center" >
		<img border=0 src="' . Router::url('/', true) . '/img/image003.jpg">
	</td>
	<td colspan="4" align="center">
		<img border=0 src="' . Router::url('/', true) . '/img/SPM.png">
	</td>
	<td colspan="4" align="center">
		<p align="center" ><img  border=0  width=189 height=104 id="Imagem 4" src="' . Router::url('/', true) . '/img/image004.png" ></p>
	</td>
</tr>
</table>';


$dtNascimento = Util::inverteData($atendimento['Servidor']['data_nascimento']);
$strDataAno = ($dtNascimento) ? $dtNascimento . ' - ' . Util::calc_idade($dtNascimento) . ' ano(s)' : '';
$html .= '<h1 style="text-align: center;">' . $tituloLaudo . '</h1>';
if(isset($recursoAdm) && !empty($recursoAdm)){
    $html .= '<h2 style="text-align: center;">' . $recursoAdm . '</h2>';
}
$html .= '<h1 style="text-align: center;font-size: 13px;">Declaração</h1>';

$numero_registro = "";
if(isset($atendimento['Perito']['numero_registro']) && !empty($atendimento['Perito']['numero_registro'])){
    $numero_registro = $atendimento['Perito']['numero_registro'];
}

$html .= '
<b>Dados servidor</b>
<br/>
<table>
    <tr>
        <td><b>Nome :</b> ' . $atendimento['Servidor']['nome'] . '</td>
        <td></td>
    </tr>
    <tr>
        <td><b>CPF :</b> ' . Util::mask($atendimento['Servidor']['cpf'], '###.###.###-##') . '</td>
        <td><b>Sexo :</b> ' . $atendimento['Sexo']['nome'] . '</td>
    </tr>
    <tr>
        <td colspan="2"><b>Data de Nascimento:</b> ' . $strDataAno . '</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
        <td><b>Perito:</b> ' . $atendimento['Perito']['nome'] . " / " . $numero_registro . '</td>';
if (isset($atendimento['Agendamento']['sala']) && !empty($atendimento['Agendamento']['sala'])) {
    $html .= '<td><b>Sala:</b> ' . $atendimento['Agendamento']['sala'] . '</td>';
} else {
    $html .= '<td></td>';
}
$html .= '</tr>';
if(count($juntaPeritos)>0){
    $html .= "<tr><td colspan=\"2\"><b>Junta de Peritos:</b> "; $s = "";
    foreach ($juntaPeritos as $perito){
        $p = $perito['Perito'];
        $html .= $s .$p['numero_registro']." - ".$p['nome']; $s = ', ';
    }
    $html .= "</td></tr>";
}

$html .= '</table>';
$idLaudo = "";

if (isset($idOrig)){

    $idLaudo = $idOrig;

    // $html .= '<p>Laudo médico nº:' . $idOrig . '</p>';
    // $html .= '<p>Data despacho: ' . Util::toBrData($dataParecer) . '</p>';
}elseif(isset($idExig)){

    $idLaudo = $idExig;

    // $html .= '<p>Laudo médico nº:' . $idExig . '</p>';
    // $html .= '<p>Data despacho: ' . Util::toBrData($dataParecer) . '</p>';
}else{
    $idLaudo = $atendimento['Atendimento']['id'];
    // $html .= '<p>Laudo médico nº:' . $atendimento['Atendimento']['id'] . '</p>';
    // $html .= '<p>Data despacho: ' . Util::toBrData($dataParecer) . '</p>';
}

if(isset($recursoAdm) && !empty($recursoAdm)){
    $html .= '<p>CAT nº:' . $idLaudo . ' | Data despacho: ' . Util::toBrData($dataAtendimentoOrg).'</p>';
    $html .= '<br><p>Data do Atendimento: ' . Util::toBrDataHora($atendimento['Atendimento']['data_inclusao']) . '</p>';
}else{
    $html .= '<p>CAT nº:' . $idLaudo . '</p>';
    $html .= '<p>Data despacho: ' . Util::toBrData($dataParecer) . '</p>';

    if(isset($dataAgendamentoOrg) || !empty($dataAgendamentoOrg)){
        $html .= '<br><p>Data do Agendamento: ' . Util::toBrDataHora($dataAgendamentoOrg) . '</p>';
    }
}

$html .= '<br/>';

if ($atendimento['TipoSituacaoParecerTecnico']['id'] == TipoSituacaoParecerTecnico::DEFERIDO && $atendimento['Atendimento']['modo'] == Atendimento::MODO_INICIAL) {

    // $html .= 'Do ponto de vista médico pericial, faz jus à concessão de licença médica inicial por um período de ' . $atendimento['Atendimento']['duracao'] . ' dias a partir de ' . Util::inverteData($atendimento['Atendimento']['data_parecer']) . ', de acordo com o artigo 115 da Lei Estadual 6.123 de 20/07/1968 (EFP/PE).';

    $html.= 'Do ponto de vista médico pericial, justifica-se licença para acompanhar pessoa da família num período de '.$atendimento['Atendimento']['duracao']. ' dias, com base no artigo 125 da Lei Estadual 6.123 de 20/07/1968 (EFP/PE), a partir de '. Util::inverteData($atendimento['Atendimento']['data_parecer']). 'devendo assumir ao término do período, suas atividades laborativas.';

} else if ($atendimento['TipoSituacaoParecerTecnico']['id'] == TipoSituacaoParecerTecnico::DEFERIDO && $atendimento['Atendimento']['modo'] == Atendimento::MODO_PRORROGACAO) {

    // $html .= 'Do ponto de vista médico pericial, faz jus à concessão de licença médica inicial por um período de ' . $atendimento['Atendimento']['duracao'] . ' dias a partir de ' . Util::inverteData($atendimento['Atendimento']['data_parecer']) . ', de acordo com o artigo 110 da Lei Estadual 6.123 de 20/07/1968 (EFP/PE).';

    $html.= ' Do ponto de vista médico pericial, justifica-se licença para acompanhar pessoa da família num período de '.$atendimento['Atendimento']['duracao']. ' dias, com base nos artigos 110, 125 da Lei Estadual 6.123 de 20/07/1968 (EFP/PE), a partir de '. Util::inverteData($atendimento['Atendimento']['data_parecer']). ', devendo assumir ao término do período, suas atividades laborativas.';


} else if ($atendimento['TipoSituacaoParecerTecnico']['id'] == TipoSituacaoParecerTecnico::INDEFERIDO) {
    //#13375 texto de negativa é somente o parecer
    //$html.= 'Do ponto de vista médico pericial, NÃO existe, no momento, incapacidade para o trabalho, sendo orientado a permanecer exercendo suas atividades laborativas.';
}
$html .= '<br><br>Conclusão:<p style="font-size: small;"><i>' . $atendimento['Atendimento']['parecer'] . '</i></p>';

$tcpdf->writeHTML($html, true, false, true, false, '');
echo $tcpdf->Output('laudo_' . str_replace(' ', '_', strtolower($nomeTipologia)) . '_' . date('Ymdhis') . '.pdf', 'D');
