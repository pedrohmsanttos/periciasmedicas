<?php
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

?>

<div class="row" id="allData" style="background-color: #ffffff; width: 1100px" >
    <div class="col-lg-12">
        <fieldset class="scheduler-border" style="margin:15px !important;">
            <legend class="scheduler-border"><?=$titulo ?></legend>
            <?php $total = 0; ?>
            <?php
                $labels = array(); $values = array(); $bgColors = array();
            ?>
            <div class="row">
                <div class="col-lg-6">
                    <? foreach($resultado as $item):
                        $labels[] = $item['Tipologia']['nome'];
                        $values[] = $item['Tipologia']['qtd'];
                        $bgColors[] = "#".random_color();
                        $total += intval($item['Tipologia']['qtd'])
                        ?>
                    <div class="row">
                        <div class="col-lg-8"><?=$item['Tipologia']['nome']?></div>
                        <div class="col-lg-4"><?=intval($item['Tipologia']['qtd'])?></div>
                    </div>
                    <? endforeach; ?>
                    <div class="row"><div class="col-lg-12">&nbsp;</div></div>
                    <div class="row">
                        <div class="col-lg-8"><b>TOTAL</b></div>
                        <div class="col-lg-4"><b><?=$total?></b></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <canvas id="myChart" height="300px"></canvas>
                    <script>
                        var data = {
                            labels: <?=json_encode($labels);?>,
                            datasets: [
                                {
                                    data: <?=json_encode($values);?>,
                                    backgroundColor: <?=json_encode($bgColors)?>
                                }
                            ]
                        };
                        var myPieChart = new Chart($("#myChart"),{
                            type: 'pie',
                            data: data
                        });
                    </script>
                </div>
            </div>
        </fieldset>
    </div>
</div>