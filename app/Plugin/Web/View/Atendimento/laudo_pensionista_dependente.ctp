<?php
$dataObito = empty($atendimento['Agendamento']['data_obito']) ? $atendimento['Servidor']['data_obito'] : $atendimento['Agendamento']['data_obito'];


try {
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

     $dataObito = empty($atendimento['Agendamento']['data_obito']) ? $atendimento['Servidor']['data_obito'] : $atendimento['Agendamento']['data_obito'];

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
        <td colspan=""><b>Data de Nascimento:</b> ' . $strDataAno . '</td>
        <td colspan=""><b>Data de Óbito:</b> ' . Util::inverteData($dataObito) . '</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>';


if( isset($atendimento['Agendamento']['nome_pretenso']) && !empty(isset($atendimento['Agendamento']['nome_pretenso'])) ){
       $dtNascimentoPretenso = Util::inverteData($atendimento['Agendamento']['data_nascimento_pretenso']);
        $strDataAnoPretenso = ($dtNascimentoPretenso) ? $dtNascimentoPretenso . ' - ' . Util::calc_idade($dtNascimentoPretenso) . ' ano(s)' : '';

        $sexoPretenso = ($atendimento['Agendamento']['sexo_id_pretenso'] == "1") ? "Masculino" : "Feminino";

       $html .= '
        <b>Dados do Pretenso Pensionista</b>
        <br/>
        <table>
            <tr>
                <td><b>Nome :</b> ' . $atendimento['Agendamento']['nome_pretenso'] . '</td>
                <td></td>
            </tr>
            <tr>
                <td><b>CPF :</b> ' . Util::mask($atendimento['Agendamento']['cpf_pretenso'], '###.###.###-##') . '</td>
                <td><b>Sexo :</b> ' . $sexoPretenso . '</td>
            </tr>
            <tr>
                <td colspan="2"><b>Data de Nascimento:</b> ' . $strDataAnoPretenso . '</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>';
}
    
    $html .='<table><tr> 
        <td><b>Perito:</b> ' . $atendimento['Perito']['nome'] . " / " . $numero_registro .  '</td>';
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

   
    $dataObitoUT = empty($dataObito) ? 0 : strtotime($dataObito);
    $dataLimiteUT = strtotime("2000-01-15");

    $dataInvalido = empty($atendimento['Atendimento']['data_dependente_invalido']) ? "--/--/----" : date("d/m/Y", strtotime($atendimento['Atendimento']['data_dependente_invalido']));
    $dataIncapaz = empty($atendimento['Atendimento']['data_dependente_inc_atos_vida']) ? "--/--/----" : date("d/m/Y", strtotime($atendimento['Atendimento']['data_dependente_inc_atos_vida']));

    $textoInvalidez = "";
    if ($dataObitoUT < $dataLimiteUT) {
        $opt = isset($atendimento['Atendimento']['invalidez_fisica_id']) ? $atendimento['Atendimento']['invalidez_fisica_id'] : 0;
  
        if ($atendimento['TipoSituacaoParecerTecnico']['id'] == TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA) {
            $textoInvalidez = "O Periciado não enquadra-se na Lei Estadual nº 7551/1977 e suas alterações.";
        } else {
            switch ($opt) {
                case TipoInvalidezFisica::$DEFINITIVA:
                    $textoInvalidez = " O Periciado enquadra-se na Lei Estadual nº 7551/1977 e suas alterações. É definitivamente inválido físico e para atividade laborativa. A invalidez remonta à data do óbito, ou antes, da menoridade previdenciária.";
                    break;
                case TipoInvalidezFisica::$TEMPORARIA:
                    $textoInvalidez = "O Periciado enquadra-se na Lei Estadual nº 7551/1977 e suas alterações. É temporariamente inválido físico e para atividade laborativa até $dataInvalido. A invalidez remonta à data do óbito, ou antes, da menoridade previdenciária.";
                    break;
                case TipoInvalidezFisica::$REMONTA_DATA_OBITO:
                    $textoInvalidez = "";
                    break;
                case TipoInvalidezFisica::$REMONTA_MENORIDADE:
                    $textoInvalidez = "O periciado enquadra-se no texto da Lei Complementar 7551/1977 e suas alterações, em caráter definitivo, pois sua doença remonta à menoridade.";
                    break;
                default:
                    $textoInvalidez = "";
                    break;
            }
        }
    } else {
        //datas maiores que 15/01/2000
        if ($atendimento['TipoSituacaoParecerTecnico']['id'] == TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA) {
            $textoInvalidez = "O Periciado não enquadra-se na Lei Complementar Estadual nº 28/2000 e suas alterações.";
        } else {
            switch (intval($atendimento['Atendimento']['invalidez_fisica_id'])) {
                case TipoInvalidezFisica::$DEFINITIVA:
                    $textoInvalidez = "O Periciado enquadra-se na Lei Complementar Estadual nº 28/2000 e suas alterações. É definitivamente inválido físico e para atividade laborativa. A invalidez ter sido caracterizada antes do óbito do segurado e ter sido determinada antes de o inválido completar 21 anos de idade.";
                    break;
                case TipoInvalidezFisica::$TEMPORARIA:
                    $textoInvalidez = "O Periciado enquadra-se na Lei Complementar Estadual nº 28/2000 e suas alterações. É temporariamente inválido físico e para atividade laborativa até $dataInvalido. A invalidez ter sido caracterizada antes do óbito do segurado e ter sido determinada antes de o inválido completar 21 anos de idade.";
                    break;
                case TipoInvalidezFisica::$REMONTA_DATA_OBITO:
                    $textoInvalidez = "";
                    break;
                case TipoInvalidezFisica::$REMONTA_MENORIDADE:
                    $textoInvalidez = "O periciado enquadra-se no texto da Lei Complementar 28/2000 e suas alterações, em caráter definitivo, pois sua doença remonta à menoridade.";
                    break;
            }
        }
    }

    $textoIncapacidade = "";
    if ($dataObitoUT < $dataLimiteUT) {
        if ($atendimento['TipoSituacaoParecerTecnico']['id'] == TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA) {
            //Texto de negativa se encontra em invalidez física
        } else {
            //ps.: tanto incapacidade de atos quanto invalidez física possuem os mesmos status
            switch (intval($atendimento['Atendimento']['incap_atos_vida_civil_id'])) {
                case TipoInvalidezFisica::$DEFINITIVA:
                    $textoIncapacidade = "O Periciado enquadra-se na Lei Estadual nº 7551/1977 e suas alterações. É definitivamente inválido para os atos da vida civil e laborativa. A invalidez remonta à data do óbito, ou antes, da menoridade previdenciária.";
                    break;
                case TipoInvalidezFisica::$TEMPORARIA:
                    $textoIncapacidade = "O Periciado enquadra-se na Lei Estadual nº 7551/1977 e suas alterações. É temporariamente inválido para os atos da vida civil e laborativa até $dataIncapaz. A invalidez remonta à data do óbito, ou antes, da menoridade previdenciária.";
                    break;
                case TipoInvalidezFisica::$REMONTA_DATA_OBITO:
                    $textoIncapacidade = "";
                    break;
                case TipoInvalidezFisica::$REMONTA_MENORIDADE:
                    $textoIncapacidade = "O periciado enquadra-se no texto da Lei Complementar 7551/1977 e suas alterações, em caráter definitivo, pois sua doença remonta à menoridade.";
                    break;
            }
        }
    } else {
        //datas maiores que 15/01/2000  ps.: tanto incapacidade de atos quanto invalidez física possuem os mesmos status
        if ($atendimento['TipoSituacaoParecerTecnico']['id'] == TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA) {
            //Texto de negativa se encontra em invalidez física
        } else {
            switch (intval($atendimento['Atendimento']['incap_atos_vida_civil_id'])) {
                case TipoInvalidezFisica::$DEFINITIVA:
                    $textoIncapacidade = "O Periciado enquadra-se na Lei Complementar Estadual nº 28/2000 e suas alterações. É definitivamente inválido para os atos da vida civil e laborativa. A invalidez ter sido caracterizada antes do óbito do segurado e ter sido determinada antes de o inválido completar 21 anos de idade.";
                    break;
                case TipoInvalidezFisica::$TEMPORARIA:
                    $textoIncapacidade = "O Periciado enquadra-se na Lei Complementar Estadual nº 28/2000 e suas alterações. É temporariamente inválido para os atos da vida civil e laborativa até $dataIncapaz. A invalidez ter sido caracterizada antes do óbito do segurado e ter sido determinada antes de o inválido completar 21 anos de idade.";
                    break;
                case TipoInvalidezFisica::$REMONTA_DATA_OBITO:
                    $textoIncapacidade = "";
                    break;
                case TipoInvalidezFisica::$REMONTA_MENORIDADE:
                    $textoIncapacidade = "O periciado enquadra-se no texto da Lei Complementar 28/2000 e suas alterações, em caráter definitivo, pois sua doença remonta à menoridade.";
                    break;
            }
        }
    }

    $html .= !empty($textoInvalidez) ? "<p>$textoInvalidez</p>" : "";
    if ($textoInvalidez != $textoIncapacidade) {
        $html .= !empty($textoIncapacidade) ? "<p>$textoIncapacidade</p>" : "";
    }

    $html .= '<br><br>';

    // echo $html;die;

    $tcpdf->writeHTML($html, true, false, true, false, '');
    echo $tcpdf->Output('laudo_' . str_replace(' ', '_', strtolower($nomeTipologia)) . '_' . date('Ymdhis') . '.pdf', 'D');
} catch (Exception $e) {
    pr($e->getMessage());
}