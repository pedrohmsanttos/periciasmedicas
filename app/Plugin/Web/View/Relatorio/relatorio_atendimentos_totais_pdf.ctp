<?php

ob_start();
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

App::import('Vendor', 'xtcpdf');
$tcpdf = new XTCPDF('', false);
$tcpdf->AddPage();


$html = "";
$html .= "<h1 style=\"text-align: center;font-family: Calibri;\">$titulo</h1>";

$total = 0;
$labels = array();
$values = array();
$bgColors = array();

$totalItens = array();

$html .= "<table style=\"width: 100%\">";
$html .= "<tr><!-- 12 collumns -->    
	<td ></td><td ></td>
</tr>";

foreach ($resultado as $item):
    $labels[] = $item['Tipologia']['nome'];
    $values[] = $item['Tipologia']['qtd'];
    $tipologia = $item['Tipologia']['nome'];

    $totalItens[$tipologia]['total'] = (!empty($totalItens[$tipologia]['total'])) ? intval($totalItens[$tipologia]['total']) + intval($item['Tipologia']['qtd']) : intval($item['Tipologia']['qtd']);
    $totalItens[$tipologia]['red'] = $red = (empty($totalItens[$tipologia]['red'])) ? rand(0, 255) : $totalItens[$tipologia]['red'];
    $totalItens[$tipologia]['blue'] = $blue = (empty($totalItens[$tipologia]['blue'])) ? rand(0, 255) : $totalItens[$tipologia]['blue'];
    $totalItens[$tipologia]['green'] = $green = (empty($totalItens[$tipologia]['green'])) ? rand(0, 255) : $totalItens[$tipologia]['green'];

    asort($totalItens);

    $total += intval($item['Tipologia']['qtd']);

    $html .= "<tr>
                <td style=\"background-color: RGB( $red, $green, $blue);float: left;width: 8px;\"></td>
                <td style=\"font-size: 10px;\">&nbsp;" . $item['Tipologia']['nome'] . "</td>
                <td>" . intval($item['Tipologia']['qtd']) . "</td>
            </tr>";
endforeach;
$html .= "<tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td style=\"font-size: 10px;\"><b>TOTAL</b></td>
            <td style=\"font-size: 10px;\"><b>$total</b></td>
        </tr>";

$html .= "</table>";

$html .= "<div class='col-lg-6'>
        <canvas id='myChart' height='300px'></canvas>";

$xc = 160;
$yc = 100;
$r = 40;


asort($totalItens);


$lastAngle = 0;
$i = 1;

$html_teste = "";
foreach ($totalItens as $item) {

    $value = $item['total'];
    $red = $item['red'];
    $green = $item['green'];
    $blue = $item['blue'];

    $x = number_format(round(floatval(($value * 360) / $total), 2), 2, ".", "");

    $xFinal = (floatval($x) + floatval($lastAngle));

    if ($i == count($values)) {
        $xFinal = 0;
    }

    if(count($values) == 1){
        $xFinal = 360;
    }

    $tcpdf->SetFillColor($red, $green, $blue);
    $tcpdf->PieSector($xc, $yc, $r, $lastAngle, $xFinal, 'FD', false, 0, 2);

    $lastAngle += $x;


    $i++;
}


$html .= "</div>";

//echo $html;die;

$tcpdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
echo $tcpdf->Output('relatorio_' . date('Ymdhis') . '.pdf', 'D');
die;


?>



