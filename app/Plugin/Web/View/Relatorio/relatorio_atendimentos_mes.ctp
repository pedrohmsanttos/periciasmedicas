<?php
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

?>
<style>
    .padleft30{
        padding-left: 30px;
    }
</style>
<div class="row" id="allData" style="background-color: #ffffff; width: 1100px">
    <div class="col-lg-12">
        <fieldset class="scheduler-border" style="margin:15px !important;">
            <legend class="scheduler-border"><?=$titulo ?></legend>
            <?php
            foreach($resultados as $itemResutado):
                $resultado = $itemResutado['resultado'];
                $total = 0;
                $tipologias = array();
                $first = true;
                $lastTipologia = '';

                $labels = array(); $values = array(); $bgColors = array();
                ?>
                <fieldset class="scheduler-border" style="margin:15px !important;">
                    <legend class="scheduler-border"><?=$itemResutado['nome'] ?></legend>
                    <div class="row">
                        <div class="col-lg-6">
                            <? foreach($resultado as $item):
                                $item = $item[0];
                                $ag_id =  $item['agrupamento_id'];
                                if(!isset($tipologias[$item['tipologia']])){
                                    $tipologias[$item['tipologia']] = array('tipologia' => $item['tipologia']);
                                    $tipologias[$item['tipologia']]['agrupamentos'] = array();

                                    if(!$first){
                                        $parcial = array_sum($tipologias[$lastTipologia]['agrupamentos_qtd']);
                                        ?>
                                        <div class="row"><div class="col-lg-12">&nbsp;</div></div>
                                        <div class="row">
                                            <div class="col-lg-10 padleft30"><b>Total Parcial</b></div>
                                            <div class="col-lg-2"><b><?=$parcial?></b></div>
                                        </div>
                                        <div class="row"><div class="col-lg-12">&nbsp;</div></div>
                                        <div class="row"><div class="col-lg-12">&nbsp;</div></div>
                                        <?php
                                    }
                                    ?><div class="row"><div class="col-lg-10"><b><?=$item['tipologia']?></b></div></div><?php
                                }

                                if(!$ag_id){
                                    $ag_id= '0';
                                    $cidade = ' ------------- ';
                                }else{
                                    $cidade = $item['agrupamento'];
                                }

                                if(!isset($tipologias[$item['tipologia']]['agrupamentos_qtd'][$ag_id])){
                                    $tipologias[$item['tipologia']]['agrupamentos_qtd'][$ag_id] = intval($item['qtd']);
                                    ?><div class="row">
                                    <div class="col-lg-10 padleft30"><?=$cidade?></div>
                                    <div class="col-lg-2"><?=intval($item['qtd'])?></div>
                                    </div>
                                    <?php

                                }

                                $labels[$ag_id] = $cidade;
                                if(!isset($values[$ag_id]))$values[$ag_id] = 0;
                                $values[$ag_id] += $item['qtd'];
                                $bgColors[$ag_id] = "#".random_color();

                                $total += intval($item['qtd']);
                                $lastTipologia = $item['tipologia'];
                                $first = false;
                                ?>
                            <? endforeach; ?>
                            <div class="row"><div class="col-lg-12">&nbsp;</div></div>
                            <div class="row"><div class="col-lg-12">&nbsp;</div></div>
                            <div class="row">
                                <div class="col-lg-10"><b>TOTAL</b></div>
                                <div class="col-lg-2"><b><?=$total?></b></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <canvas id="myChart<?=$itemResutado['id']?>" width="200" height="200"></canvas>
                            <script>
                                var data = {
                                    labels: <?=json_encode(array_values($labels));?>,
                                    datasets: [
                                        {
                                            data: <?=json_encode(array_values($values));?>,
                                            backgroundColor: <?=json_encode(array_values($bgColors))?>
                                        }
                                    ]
                                };
                                var myPieChart = new Chart($("#myChart<?=$itemResutado['id']?>"),{
                                    type: 'pie',
                                    data: data
                                });
                            </script>
                        </div>
                    </div>
                </fieldset>
            <?php endforeach; ?>
        </fieldset>
    </div>
</div>