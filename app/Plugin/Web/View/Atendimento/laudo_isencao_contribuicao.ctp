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


$numero_registro = "";
if(isset($atendimento['Perito']['numero_registro']) && !empty($atendimento['Perito']['numero_registro'])){
    $numero_registro = $atendimento['Perito']['numero_registro'];
}

$dtNascimento = Util::inverteData($atendimento['Servidor']['data_nascimento']);
$strDataAno = ($dtNascimento) ? $dtNascimento . ' - ' . Util::calc_idade($dtNascimento) . ' ano(s)' : '';
$html .= '<h1 style="text-align: center;">' . $tituloLaudo . '</h1>';
if(isset($recursoAdm) && !empty($recursoAdm)){
    $html .= '<h2 style="text-align: center;">' . $recursoAdm . '</h2>';
}
$html .= '<h1 style="text-align: center;font-size: 13px;">Declaração</h1>';
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
    $html .= '<p>Laudo médico nº:' . $idLaudo . ' | Data despacho: ' . Util::toBrData($dataAtendimentoOrg).'</p>';
    $html .= '<br><p>Data do Atendimento: ' . Util::toBrDataHora($atendimento['Atendimento']['data_inclusao']) . '</p>';
}else{
    $html .= '<p>Laudo médico nº:' . $idLaudo . '</p>';
    $html .= '<p>Data despacho: ' . Util::toBrData($dataParecer) . '</p>';

    if(isset($dataAgendamentoOrg) || !empty($dataAgendamentoOrg)){
        $html .= '<br><p>Data do Agendamento: ' . Util::toBrDataHora($dataAgendamentoOrg) . '</p>';
    }
}

$html .= '<br/>';

if ($atendimento['TipoSituacaoParecerTecnico']['id'] == TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA) {
    $html .= "<h4>ISENÇÃO DA CONTRIBUIÇÃO DE IMPOSTO DE RENDA</h4>";
    $html .= "<p>O Periciado não enquadra-se na Lei Federal Nº 7713, de 22.12.1988, e suas alterações.</p>";

    $html .= "<h4>ISENÇÃO DA CONTRIBUIÇÃO  PREVIDENCIÁRIA</h4>";
    $html .= "<p>O periciado não enquadra-se nos artigos 34 e 71 da  Lei Complementar Estadual nº 28/2000 e suas alterações.</p>";
} else {
    $dataIsencao = date("d/m/Y", strtotime($atendimento['Atendimento']['data_parecer']));
    $years = '';
    //if (!empty($atendimento['Atendimento']['data_insencao_temporaria'])) 
    if (empty($atendimento['Atendimento']['data_insencao_temporaria'])) {
        $d1 = new DateTime($atendimento['Atendimento']['data_parecer']);
        $d2 = new DateTime($atendimento['Atendimento']['data_insencao_temporaria']);
        $diff = $d2->diff($d1);

        $anos = (($years = $diff->y) == 1) ? 'ano' : 'anos';
        $meses = (($months = $diff->m) == 1) ? 'mês' : 'meses';
        $dias = (($days = $diff->d) == 1) ? 'dia' : 'dias';
    }
    $dias_amais = $atendimento['Atendimento']['duracao'];
    $data_atual = $atendimento['Atendimento']['data_parecer'];
    $data_somada = date_create($data_atual);
    date_add($data_somada, date_interval_create_from_date_string("'".$dias_amais." days'"));
    $data_arrumada = date_format($data_somada, 'd/m/Y');
    //pr($data_arrumada);die;
   // $data_somada = date('D/m/y', strtotime($data_atual. ' + $dias_amais days'));
    $textoIsencao = "";
    $textoIsencaoImposto = '';
    $textoIsencaoPrevidencia = '';
    //pr($atendimento['Atendimento']['situacao_id']);die;
    switch ($atendimento['Atendimento']['situacao_id']) {
        case SITUACAO_TEMPORARIO:
            $textoIsencaoImposto = "O Periciado enquadra-se na Lei Federal Nº 7713, de 22.12.1988 e suas alterações de $dataIsencao até $data_arrumada.";
            $textoIsencaoPrevidencia = "O Periciado enquadra-se no §3º art. 34 e no §3º art. 71 da LCE Nº 28/00 e suas alterações, para concessão de isenção de contribuição previdenciária de $dataIsencao
                até $data_arrumada.";
            break;
        case SITUACAO_DEFINITIVO:
            $textoIsencaoImposto = "O Periciado enquadra-se na Lei Federal Nº 7713, de 22.12.1988, e suas alterações de $dataIsencao.";
            $textoIsencaoPrevidencia = "O Periciado enquadra-se no §3º art. 34 e no §3º art. 71 da LCE Nº 28/00 e suas alterações, para concessão de isenção de contribuição previdenciária de $dataIsencao.";
            break;
    }
    if ($textoIsencaoImposto != '') {
        $textoIsencao .= "<h4>ISENÇÃO DA CONTRIBUIÇÃO DE IMPOSTO DE RENDA</h4>";
        $textoIsencao .= "<p>$textoIsencaoImposto</p>";

        $textoIsencao .= "<h4>ISENÇÃO DA CONTRIBUIÇÃO  PREVIDENCIÁRIA</h4>";
        $textoIsencao .= "<p>$textoIsencaoPrevidencia</p>";
    }
    $html .= $textoIsencao;
}

$html .= '<br><br>';

$tcpdf->writeHTML($html, true, false, true, false, '');
echo $tcpdf->Output('laudo_' . str_replace(' ', '_', strtolower($nomeTipologia)) . '_' . date('Ymdhis') . '.pdf', 'D');