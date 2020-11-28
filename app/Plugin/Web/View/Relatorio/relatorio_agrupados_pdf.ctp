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
$tipologias = array();
$first = true;
$lastTipologia = '';
$labels = array();
$values = array();
$bgColors = array();

$totalItens = array();

$html .= "<div class='row'>
                <div class='col-lg-6'>";
foreach ($resultado as $item):
    $item = $item[0];
    $ag_id = $item['agrupamento_id'];
    if (!isset($tipologias[$item['tipologia']])) {
        $tipologias[$item['tipologia']] = array('tipologia' => $item['tipologia']);
        $tipologias[$item['tipologia']]['agrupamentos'] = array();

        if (!$first) {
            $parcial = array_sum($tipologias[$lastTipologia]['agrupamentos_qtd']);
            $html .= "<div class='col-lg-10 padleft30'><b>Total Parcial - $parcial </b></div><br><br><br><br>";

        }
        $html .= "<div class='row' style\"background-color: red\"><b>" . $item['tipologia'] . "</b></div>";
    }

    if (!$ag_id) {
        $ag_id = '0';
        $cidade = ' ------------- ';
    } else {
        $cidade = $item['agrupamento'];
    }

    if (!isset($tipologias[$item['tipologia']]['agrupamentos_qtd'][$ag_id])) {
        $tipologias[$item['tipologia']]['agrupamentos_qtd'][$ag_id] = intval($item['qtd']);

        $totalItens[$cidade]['total'] = (!empty($totalItens[$cidade]['total'])) ? intval($totalItens[$cidade]['total']) + intval($item['qtd']) : intval($item['qtd']);

        $totalItens[$cidade]['red'] = $red = (empty($totalItens[$cidade]['red'])) ? rand(0, 255) : $totalItens[$cidade]['red'];
        $totalItens[$cidade]['blue'] = $blue = (empty($totalItens[$cidade]['blue'])) ? rand(0, 255) : $totalItens[$cidade]['blue'];
        $totalItens[$cidade]['green'] = $green = (empty($totalItens[$cidade]['green'])) ? rand(0, 255) : $totalItens[$cidade]['green'];

        $html .= "<div class='row'>
                <table>
                    <tr>
                        <td style=\"background-color: RGB($red,$green,$blue);float: left;width: 8px;\"></td>
                        <td>&nbsp;$cidade - " . intval($item['qtd']) . " </td>
                    </tr>
                    
                </table>
                ";


    }

    $labels[$ag_id] = $cidade;
    if (!isset($values[$ag_id])) $values[$ag_id] = 0;
    $values[$ag_id] += $item['qtd'];

    $total += intval($item['qtd']);
    $lastTipologia = $item['tipologia'];
    $first = false;

endforeach;
asort($totalItens);

$html .= "<div class='row'><div class='col-lg-12'>&nbsp;</div></div>
                    <div class='row'><div class='col-lg-12'>&nbsp;</div></div>
                    <div class='row'>
                    <table>";

            foreach ($totalItens as $key => $item):
            $html .= "<tr>
                        <td>" . $key . "</td>
                        <td>" . $item['total'] . "</td>
                     </tr>";
            endforeach;
            $html .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
            $html .= "<tr>
                    <td style=\"font-weight: bold\">TOTAL</td>
                    <td style=\"font-weight: bold\">$total</td>
                    </tr>
                    </table>
                       
                    </div>
                </div>
                <div class='col-lg-6'>
                    <canvas id='myChart' width='400' height='400'></canvas>";

$xc = 150;
$yc = 100;
$r = 50;
$total = array_sum(array_values($values));

asort($values);


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
$html .= "</div>
            </div>";

$tcpdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
echo $tcpdf->Output('relatorio_' . date('Ymdhis') . '.pdf', 'D');
die;


?>
