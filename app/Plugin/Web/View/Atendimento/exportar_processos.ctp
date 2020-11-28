<?php

App::import('Vendor', 'xtcpdf');

$tcpdf = new XTCPDF();
$tcpdf->AddPage();
$conteudoTable = '';
$html = '';
$i = 1;
foreach ($processos as $line) {
    $data_parecer = $line['Atendimento']['data_parecer'];
    $periodoConcedido = Util::inverteData($data_parecer);
    $duracao = $line['Atendimento']['duracao'];
    if($line['Atendimento']['duracao']):
        $periodoConcedido = $periodoConcedido . " até " . date('d/m/Y', strtotime($data_parecer . ' + '.$duracao.' days'));
    endif;
    $conteudoTable .= '
        <tr>
            <td colspan="2" style="background-color: #DCDCDC;text-align: right;white-space: nowrap; border-top:1pt solid black; border-left:1pt solid black;">' . __('processo_label_nome') . ':</td>
            <td colspan="4" style="text-align: left; border-top:1pt solid black;border-right:1pt solid black;">' . $line['Servidor']['nome'] . '</td>
        </tr>
        <tr  >
            <td colspan="2" style="background-color: #DCDCDC;text-align: right;white-space: nowrap;border-left:1pt solid black;">' . __('processo_label_numero') . ':</td>
            <td colspan="4" style="text-align: left;border-right:1pt solid black;">' . $line['Atendimento']['id'] . '</td>
        </tr>
        <tr >
            <td colspan="2" style="background-color: #DCDCDC;text-align: right;white-space: nowrap;border-left:1pt solid black;">' . __('processo_label_tipologia') . ':</td>
            <td colspan="4" style="text-align: left;border-right:1pt solid black;">' . $line['Tipologia']['nome'] . '</td>
        </tr>
        <tr >
            <td colspan="2"  style="background-color: #DCDCDC;text-align: right;white-space: nowrap;border-left:1pt solid black;">' . __('processo_label_data_pericia') . ':</td>
            <td style="text-align: left;">' . date('d/m/Y', strtotime($line['Atendimento']['data_inclusao'])) . '</td>
            <td style="background-color: #DCDCDC;text-align: right;">' . __('processo_label_status') . ':</td>
            <td colspan="2" style="text-align: left;border-right:1pt solid black;">' . $line['TipoSituacaoParecerTecnico']['nome'] . '</td>
        </tr>
        <tr  >
            <td colspan="2" style="background-color: #DCDCDC;text-align: right;border-left:1pt solid black; border-bottom:1pt solid black;">' . __('processo_label_periodo_concedido') . ':</td>
            <td colspan="4" style="text-align: left; border-bottom:1pt solid black; border-right:1pt solid black;">' . $periodoConcedido . '</td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        ';
    
    $iIncremente = $i+1;
    if ($i % 6 == 0 && $iIncremente < $totalProcessos) {
        $conteudoTable.= '<br pagebreak="true" />';
    }
    $i++;
}

$html .= '
        <div style="text-align: center;">
            <h1 style="">' . __('processo_label_processos_servidor') . '</h1>
            <p align="rigth"><span >Total de processos no relatório: ' . $totalProcessos . '</span></p>
        </div>
        <br/>
        <table border="0" style="padding-left: 12px;">
            <tbody>
                ' . $conteudoTable . '
            </tbody>
        </table>
';
//echo $html;
//exit;
$tcpdf->writeHTML($html, true, false, true, false, '');
echo $tcpdf->Output('processos_' . date('Ymdhis') . '.pdf', 'D');