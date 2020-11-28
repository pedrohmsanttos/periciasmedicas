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
		<img border=0 src="'.Router::url('/', true).'/img/image003.jpg">
	</td>
	<td colspan="4" align="center">
		<img border=0 src="'.Router::url('/', true).'/img/SPM.png">
	</td>
	<td colspan="4" align="center">
		<p align="center" ><img  border=0  width=189 height=104 id="Imagem 4" src="'.Router::url('/', true).'/img/image004.png" ></p>
	</td>
</tr>
</table>';
$html .= '<style>   
    .assinatura {
        width: 350px;
    }
</style>';
$dtNascimento = Util::inverteData($atendimento['Agendamento']['UsuarioServidor']['data_nascimento']);
$strDataAno = ($dtNascimento) ? $dtNascimento . ' - ' . Util::calc_idade($dtNascimento) . ' ano(s)' : '';
$html.='<h1 style="text-align: center;">' . $tituloExigencias . '</h1>';
$html.='<h1 style="text-align: center;font-size: 13px;">Exigência</h1>';
$html .= '
<br/>
<table>
    <tr>
        <td><b>Nome :</b> ' . $atendimento['Agendamento']['UsuarioServidor']['nome'] . '</td>
        <td></td>
    </tr>
    <tr>
        <td><b>CPF :</b> ' . Util::mask($atendimento['Agendamento']['UsuarioServidor']['cpf'], '###.###.###-##') . '</td>
        <td><b>Sexo :</b> ' . (isset($atendimento['Agendamento']['UsuarioServidor']['Sexo']['nome']) ? $atendimento['Agendamento']['UsuarioServidor']['Sexo']['nome'] : ''). '</td>
    </tr>
    <tr>
        <td colspan="2"><b>Data de Nascimento:</b> ' . $strDataAno . '</td>
    </tr>
    <tr>
        <td colspan="2"><b>Data Limite :</b> ' . Util::inverteData($atendimento['Atendimento']['data_limite_exigencia']) . '</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
        <td><b>Perito:</b> ' . $atendimento['Perito']['nome'] . '</td>';



if(isset( $atendimento['Agendamento']['sala']) && !empty($atendimento['Agendamento']['sala'])){
    $html .= '<td><b>Sala:</b> ' . $atendimento['Agendamento']['sala'] . '</td>';
}else{
    $html .= '<td></td>';
}

$html .= '</tr> 
</table>';
if(isset($idExig)){
    $html .= '<p><b>Laudo médico nº:</b>' . $idExig . '</p>';
}else{
    $html .= '<p><b>Laudo médico nº:</b>' . $atendimento['Atendimento']['id'] . '</p>';
}

$html .= '
<p>Requisições (Documentos Solicitados):</p>';

foreach ($atendimento["RequisicaoDisponivel"] as $key => $exigencia) :
$html .= ($key +1 ) . '. '.$exigencia["nome"].'<br/>';
endforeach;
$html .= '<br/>';
$html .= '<br/>';


$html .= "<p><strong>Outros:</strong>" . $atendimento['Atendimento']['observacoes_exigencias'] . "</p>";

/*
$html .= '<hr class="assinatura" align="center">';
$html .= '<br/>';
$html .= 'Nome do Servidor';*/
$tcpdf->writeHTML($html, true, false, true, false, '');
echo $tcpdf->Output('exigencias_' . date('Ymdhis') . '.pdf', 'D');
