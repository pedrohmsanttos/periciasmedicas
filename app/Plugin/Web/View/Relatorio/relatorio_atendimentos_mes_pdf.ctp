<?php

ob_start();
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

App::import('Vendor', 'xtcpdf');
$tcpdf = new XTCPDF('', false);

//pr($resultados);
//die;

$html = "";

$i = 1;

foreach ($resultados as $itemResultado):
    $tcpdf->AddPage();
    $html .= ($i == 1) ? "<h1 style=\"text-align: center;font-family: Calibri;\">$titulo</h1>" : "";


    $resultado = $itemResultado['resultado'];
    $total = 0;
    $tipologias = array();
    $first = true;
    $lastTipologia = '';
    $totalItens = array();

    $labels = array();
    $values = array();
    $bgColors = array();

    $html .= "<h2 style=\"text-align: center;font-family: Calibri;\">" . $itemResultado['nome'] . "</h2>";

    foreach ($resultado as $item):
        $item = $item[0];
        $ag_id = $item['agrupamento_id'];
        if (!isset($tipologias[$item['tipologia']])) {
            $tipologias[$item['tipologia']] = array('tipologia' => $item['tipologia']);
            $tipologias[$item['tipologia']]['agrupamentos'] = array();

            if (!$first) {
                $parcial = array_sum($tipologias[$lastTipologia]['agrupamentos_qtd']);

                $html .= "<div class='col-lg-10 padleft30'><b>Total Parcial - $parcial </b></div><br><br>";
            }

            $html.="
            <div class='col-lg-10'><b>" . $item['tipologia'] . "</b></div>";
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

//    pr($totalItens);

$html .="<br><br><div class='row'>
                <div class='col-lg-10'><b>TOTAL - $total</b></div>
</div>
<div class='col-lg-6'>";


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
$html.="</div>";

$i++;

$tcpdf->writeHTML($html, true, false, true, false, '');
$html = "";
endforeach;

//die;

//echo $html;die;

ob_end_clean();
echo $tcpdf->Output('relatorio_' . date('Ymdhis') . '.pdf', 'D');
die;



?>


<legend class='scheduler-border'><?= $titulo ?></legend>
<?php
foreach ($resultados as $itemResutado):
    $resultado = $itemResutado['resultado'];
    $total = 0;
    $tipologias = array();
    $first = true;
    $lastTipologia = '';

    $labels = array();
    $values = array();
    $bgColors = array();
    ?>

    <legend class='scheduler-border'><?= $itemResutado['nome'] ?></legend>
    <div class='row'>
        <div class='col-lg-6'>
            <? foreach ($resultado as $item):
                $item = $item[0];
                $ag_id = $item['agrupamento_id'];
                if (!isset($tipologias[$item['tipologia']])) {
                    $tipologias[$item['tipologia']] = array('tipologia' => $item['tipologia']);
                    $tipologias[$item['tipologia']]['agrupamentos'] = array();

                    if (!$first) {
                        $parcial = array_sum($tipologias[$lastTipologia]['agrupamentos_qtd']);
                        ?>
                        <div class='row'>
                            <div class='col-lg-12'>&nbsp;</div>
                        </div>
                        <div class='row'>
                            <div class='col-lg-10 padleft30'><b>Total Parcial</b></div>
                            <div class='col-lg-2'><b><?= $parcial ?></b></div>
                        </div>
                        <div class='row'>
                            <div class='col-lg-12'>&nbsp;</div>
                        </div>
                        <div class='row'>
                            <div class='col-lg-12'>&nbsp;</div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class='row'>
                    <div class='col-lg-10'><b><?= $item['tipologia'] ?></b></div></div><?php
                }

                if (!$ag_id) {
                    $ag_id = '0';
                    $cidade = ' ------------- ';
                } else {
                    $cidade = $item['agrupamento'];
                }

                if (!isset($tipologias[$item['tipologia']]['agrupamentos_qtd'][$ag_id])) {
                    $tipologias[$item['tipologia']]['agrupamentos_qtd'][$ag_id] = intval($item['qtd']);
                    ?>
                    <div class='row'>
                        <div class='col-lg-10 padleft30'><?= $cidade ?></div>
                        <div class='col-lg-2'><?= intval($item['qtd']) ?></div>
                    </div>
                    <?php

                }

                $labels[$ag_id] = $cidade;
                if (!isset($values[$ag_id])) $values[$ag_id] = 0;
                $values[$ag_id] += $item['qtd'];
                $bgColors[$ag_id] = '#' . random_color();

                $total += intval($item['qtd']);
                $lastTipologia = $item['tipologia'];
                $first = false;
                ?>
            <? endforeach; ?>
            <div class='row'>
                <div class='col-lg-12'>&nbsp;</div>
            </div>
            <div class='row'>
                <div class='col-lg-12'>&nbsp;</div>
            </div>
            <div class='row'>
                <div class='col-lg-10'><b>TOTAL</b></div>
                <div class='col-lg-2'><b><?= $total ?></b></div>
            </div>
        </div>
        <div class='col-lg-6'>
            <canvas id='myChart<?= $itemResutado['id'] ?>' width='200' height='200'></canvas>

        </div>
    </div>

<?php endforeach; ?>


