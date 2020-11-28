<?php
App::import('Vendor', 'xtcpdf');

$tcpdf = new XTCPDF(' ');
$tcpdf->AddPage();
$conteudoTable = '';

$numero_registro = "";
if(isset($atendimento['Perito']['numero_registro']) && !empty($atendimento['Perito']['numero_registro'])){
    $numero_registro = $atendimento['Perito']['numero_registro'];
}

$html = '<html>
<body>
<table style="font-family: Calibri; font-size: 10px;">
<tr><!-- 12 collumns -->
	<td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td>
</tr>
<tr>
	<td colspan="4" align="center">
		<img border=0 src="'.Router::url('/', true).'/img/image003.jpg">
	</td>
	<td colspan="4" align="center">
	    <br />
		<img border=0 src="'.Router::url('/', true).'/img/SPM.png">
	</td>
	<td colspan="4" align="center">
		<p align="center" ><img  border=0  width=189 height=104 id="Imagem 4" src="'.Router::url('/', true).'/img/image004.png" ></p>
	</td>
</tr>
<tr><td colspan="12" align="center"> EXAME PRÉ ADMISSIONAL</td></tr>
<tr><td colspan="12" align="center"> &nbsp;</td></tr>

<tr>
    <td colspan="6" align="left">Nome: '.$atendimento['Servidor']['nome'].'</td>
    <td colspan="6" align="left">CPF:  '.$atendimento['Servidor']['cpf'].'</td>
</tr>
<tr>
    <td colspan="6" align="left">Cargo: '.$cargoOrgao['cargo'].'</td>
    <td colspan="6" align="left"> Secretaria/Órgão: '.$cargoOrgao['orgao'].'</td>
</tr>

<tr><td colspan="12" align="left">Endereço: '.$endereco.'</td></tr>
<tr>
    <td colspan="6" align="left">E mail: '.$atendimento['Servidor']['email'].'</td>
    <td colspan="6" align="left">Telefone: '.$atendimento['Servidor']['telefone'].'</td>
</tr>
<tr>
    <td colspan="12">&nbsp;</td>
</tr>
<tr> 
    <td colspan="6"><b>Perito:</b> ' . $atendimento['Perito']['nome'] . " / " . $numero_registro . '</td>';
if(isset( $atendimento['Agendamento']['sala']) && !empty($atendimento['Agendamento']['sala'])){
    $html .= '<td colspan="6"><b>Sala:</b> ' . $atendimento['Agendamento']['sala'] . '</td>';
}else{
    $html .= '<td colspan="6"></td>';
}
$html .= '</tr>';
if(count($juntaPeritos)>0){
    $html .= "<tr><td colspan=\"12\"><b>Junta de Peritos:</b> "; $s = "";
    foreach ($juntaPeritos as $perito){
        $p = $perito['Perito'];
        $html .= $s .$p['numero_registro']." - ".$p['nome']; $s = ', ';
    }
    $html .= "</td></tr>";
}
$html .= '
<tr><td colspan="12" align="center">&nbsp;</td></tr>
<tr><td colspan="12" align="center">RESULTADO</td></tr>';
if(!$pne){
    $html.='<tr><td colspan="12" align="center">'.$apto.'</td></tr>';
}else{
    $html.='<tr><td colspan="12" align="center">'.$pne_resultado.'
    </td></tr>';
}
$dataAtendimento = Util::toDBData($atendimento['Atendimento']['data_inclusao']);
$html .= '<tr><td colspan="12" align="center">&nbsp;</td></tr>
<tr><td colspan="12" align="center"> Recife,  '.date('d', strtotime($dataAtendimento)).', '.__(date('F', strtotime($dataAtendimento))).' de '.date('Y', strtotime($dataAtendimento)).'</td></tr>
<tr><td colspan="12" align="center">&nbsp;</td></tr>
<tr>
	<td colspan="6" align="center">_________________________________</td>
	<td colspan="6" align="center">_________________________________</td>
</tr>
<tr>
	<td colspan="6" align="center">Assinatura do candidato</td>
	<td colspan="6" align="center">Assinatura do médico </td>
</tr>

<tr><td colspan="12" align="center">&nbsp;</td></tr>
<tr><td colspan="12" align="center"> &nbsp;</td></tr>
<tr><td colspan="12" align="center"> &nbsp;</td></tr>
<tr><td colspan="12" align="right"></td></tr>

</table>
</body>

</html>

';
//echo $html;
$tcpdf->writeHTML($html, true, false, true, false, '');
echo $tcpdf->Output('laudo_'. str_replace(' ', '_', strtolower($nomeTipologia)) . date('Ymdhis') . '.pdf', 'D');
