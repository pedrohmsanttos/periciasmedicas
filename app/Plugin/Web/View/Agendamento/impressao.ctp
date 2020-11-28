<?php

App::import('Vendor', 'xtcpdf');

$tcpdf = new XTCPDF(' ');
$tcpdf->AddPage();


$sexo = ($agendamento['UsuarioServidor']['sexo_id']==SEXO_MASCULINO)?"Masculino":"Feminino";

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


$dtNascimento = $agendamento['UsuarioServidor']['data_nascimento'];
$strDataAno = ($dtNascimento) ? $dtNascimento . ' - ' . Util::calc_idade($dtNascimento) . ' ano(s)' : '';
$html.='<h1 style="text-align: center;">Agendamento</h1>';
$html .= '
<b>Dados servidor</b>
<br/>
<table>
    <tr>
        <td><b>Nome :</b> ' . $agendamento['UsuarioServidor']['nome'] . '</td>
        <td></td>
    </tr>
    <tr>
        <td><b>CPF :</b> ' . Util::mask($agendamento['UsuarioServidor']['cpf'], '###.###.###-##') . '</td>
        <td><b>Sexo :</b> ' .  $sexo . '</td>
    </tr>
    <tr>
        <td colspan="2"><b>Data de Nascimento:</b> ' . $strDataAno . '</td>
    </tr>
</table><br />

<p><b>Tipologia:</b> ' . $agendamento['Tipologia']['nome'] . '</p>
<p><b>Nº Agendamento:</b>' . $agendamento['Agendamento']['id'] . '</p>
<p><b>Data:</b> ' .  substr($agendamento['Agendamento']['data_hora'],0,-6) . '</p>
<br/><br/> ';

$array_tipologias = array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_APOSENTADORIA_ESPECIAL,
 TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO, TIPOLOGIA_INSPECAO);
if($agendamento['Tipologia']['id'] )
if(!in_array($agendamento['Tipologia']['id'], $array_tipologias)){
               
      
$html .= '<span style="color:red">É necessário apresentar-se para o atendimento com 15 minutos de antecedência para confirmar a presença, sob risco de ter que reagendar o atendimento para outra data
</span>
';
      }


$tcpdf->writeHTML($html, true, false, true, false, '');
echo $tcpdf->Output('agendamento_' . str_replace(' ', '_', strtolower($agendamento['Tipologia']['nome'])) . '_' . date('Ymdhis') . '.pdf', 'D');
