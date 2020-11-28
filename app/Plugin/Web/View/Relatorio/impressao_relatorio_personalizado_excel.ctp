<?php

App::import('Vendor', 'PHPExcel');

$html = "";


if(!empty($resultado_relatorio)){
    if(isset($resultado_relatorio['titulo_relatorio'])){
        $html .= '<h2 style="text-align: center;font-family: Calibri;">'. $resultado_relatorio['titulo_relatorio'] .'</h2>';
    }else{
        $html .= '<h1 style="text-align: center;font-family: Calibri;">Relatório Personalizado</h1>';
    }

    if(isset($resultado_relatorio['label_orgao'])){
        $html .= '<p style="text-align: center;font-family: Calibri;">Orgão(s): <strong>'. $resultado_relatorio['label_orgao'] . '</strong></p>';
    }
}else{
    $html .= '<h1 style="text-align: center;font-family: Calibri;">Relatório Personalizado</h1>';
}

$html .= '<table style="font-family: Calibri; font-size: 10px; border: 1px solid #ddd;">';
            
            $header = array(
                "id"                        => "N° Atendimento",
                "id_atendimento"            => "N° Atendimento",
                "id_agendamento"            => "N° Agendamento",
                "data_atendimento"          => "Data do Atendimento",
                "data_agendamento"          => "Data do Agendamento",
                "nome"                      => "Nome",
                "nome_und"                  => "Nome da Unidade",
                "bairro"                    => "Bairro",
                "cpf_usuario"               => "CPF",
                "qtd_licencas"              => "Qtd de Tipologias",
                "nome_funcao"               => "Função",
                "data_admissao_servidor"    => "Data Admissão",
                "sexo_desc"                 => "Sexo",
                "duracao"                   => "Período da Licença",
                "orgao_origem"              => "Orgão de Origem" 
            );

            if(!empty($resultado_relatorio)){
                
                if(isset($resultado_relatorio['agrupamento'])){

                    $agrupRelatorio = $resultado_relatorio['agrupamento'];

                    if(trim($agrupRelatorio) == "grupo_tipologia_cid"){

                        $resultado_agrupado = array();
                        foreach ($resultado_relatorio['resultado'] as $key => $resultado) {
                            foreach ($resultado as $key => $value) {
                                
                                $agrupamentoTipologia = $value['grupo_tipologia'];
								$chave_agrup = Util::returnArrayByAgrupamento($agrupamentoTipologia);

								$agrupamentoCID = $value['grupo_cid'];
								$chave_agrup_cid = Util::returnArrayByAgrupamento($agrupamentoCID);

                                $replace = '"';
                                foreach ($chave_agrup as $chave) {
                                    $chave = str_replace($replace, "", $chave);
                                    if($chave == "NULL"){
                                        $chave = "Não Possui";
                                    }


                                    foreach ($chave_agrup_cid as $chaveTipo) {

                                        if($chaveTipo == "NULL"){
											$chaveTipo = "Não Possui";
                                        }
                                        
                                        $resultado_agrupado[$chave][$chaveTipo][] = $value;
                                    }

                                }
                            }
                        }

                        $html .='<tr style="background-color: #ddd"><thead>';
                        $aux = 0;

                        foreach ($resultado_agrupado as $chave_resu_pai => $resuPai) {

                            foreach ($resuPai as $chave_resu => $resu) {
                                foreach ($resu as $chave_resu_deta => $resu_deta) {
                                    foreach ($resu_deta as $chave_head => $value) {
                                        $mystring = $chave_head;
                                        $findme   = 'grupo_';
                                        $pos = strpos($mystring, $findme);
                                        if ($pos === false) {
                                            $aux++;
                                           $html .="<th>" .$header[trim($chave_head)] . "</th>";
                                        }
                                    }
                                    break;
                                }
                                break;
                            }
                            break;
                        }

                         $html .= "</thead></tr>";
                        // die;
                        
                         $html .= "<tbody>";

                        foreach ($resultado_agrupado as $chave_resu_pai => $resu_pai) {
                            $html .= "<tr><!-- 12 collumns -->
                                        <td ></td><td ></td><td ></td><td ></td>
                                    </tr>";
                             $html .= '<tr style="background-color: #f5f5f5"><td colspan=' .$aux .'><strong>' . $chave_resu_pai . '</strong></td></tr>';

                            foreach ($resu_pai as $chave_resu => $resu) {
                                 $html .= '<tr style="background-color: #f5f5f5"><td colspan=' .$aux .'><strong>' . $chave_resu . '</strong></td></tr>';
                                // pr($chave_resu);
                                foreach ($resu as $chave_resu_deta => $resu_deta) {
                                    // pr($resu_deta);
                                     $html .= '<tr style="border: 1px solid #ddd;">';
                                    foreach ($resu_deta as $chave_head => $value) {
                                        $mystring = $chave_head;
                                        $findme   = 'grupo_';
                                        $pos = strpos($mystring, $findme);
                                        if ($pos === false) {
                                             $html .= '<td style=" border: 1px solid #ddd;text-align: center;">'. $value . '</td>';
                                        }
                                    }
                                     $html .= "</tr>";
                                }
                            }
                        }

                         $html .= "</tbody>";
                        // die;


                    }else{


                        $resultado_agrupado = array();
                        foreach ($resultado_relatorio['resultado'] as $key => $resultado) {
                            foreach ($resultado as $key => $value) {
                                
                                $agrupamento = $value[$resultado_relatorio['agrupamento']];
                                $chave_agrup = Util::returnArrayByAgrupamento($agrupamento);

                                $replace = '"';
                                foreach ($chave_agrup as $chave) {
                                    $chave = str_replace($replace, "", $chave);
                                    if($chave == "NULL"){
                                        $chave = "Não Possui";
                                    }
                                    $resultado_agrupado[$chave][] = $value;
                                }
                            }
                        }
                        // pr($resultado_agrupado);

                        $html .='<tr style="background-color: #ddd"><thead>';
                        $aux = 0;
                        foreach ($resultado_agrupado as $chave_resu => $resu) {
                            foreach ($resu as $chave_resu_deta => $resu_deta) {
                                foreach ($resu_deta as $chave_head => $value) {
                                    $mystring = $chave_head;
                                    $findme   = 'grupo_';
                                    $pos = strpos($mystring, $findme);
                                    if ($pos === false) {
                                        $aux++;
                                       $html .="<th>" .$header[trim($chave_head)] . "</th>";
                                    }
                                }
                                break;
                            }
                            break;
                        }
                         $html .= "</thead></tr>";
                        // die;
                        
                         $html .= "<tbody>";
                        foreach ($resultado_agrupado as $chave_resu => $resu) {
                            $html .= "<tr><!-- 12 collumns -->
                                        <td ></td><td ></td><td ></td><td ></td>
                                    </tr>";
                             $html .= '<tr style="background-color: #f5f5f5"><td colspan=' .$aux .'><strong>' . $chave_resu . '</strong></td></tr>';
                            // pr($chave_resu);
                            foreach ($resu as $chave_resu_deta => $resu_deta) {
                                // pr($resu_deta);
                                 $html .= '<tr style="border: 1px solid #ddd;">';
                                foreach ($resu_deta as $chave_head => $value) {
                                    $mystring = $chave_head;
                                    $findme   = 'grupo_';
                                    $pos = strpos($mystring, $findme);
                                    if ($pos === false) {
                                         $html .= '<td style=" border: 1px solid #ddd;text-align: center;">'. $value . '</td>';
                                    }
                                }
                                 $html .= "</tr>";
                            }
                        }
                         $html .= "</tbody>";
                        // die;
                        
                    }

                }else{
                    $primeiro = $resultado_relatorio[0][0];

                     $html .= '<tr style="background-color: #ddd"><thead>';

                    // foreach ($primeiro as $key => $value) {
                    //      $html .= "<th>" . $key . "</th>";
                    // }

                    $contColspan = 0;
					foreach ($primeiro as $key => $value) {
						// echo "<th>" . $key . "</th>";
						$mystring = $key;
						$findme   = 'grupo_';
						$pos = strpos($mystring, $findme);
						if ($pos === false) {
						    $html .= "<td>" . $header[trim($key)] . "</td>";
						    $contColspan++;
						}
                    }
                    
                     $html .= "</thead></tr>";

                     $html .= "<tbody>";

                    // foreach ($resultado_relatorio as $key => $resultado) {
                    //          $html .= "<tr>";
                    //         foreach ($resultado as $key2 => $valor) {
                    //             foreach ($valor as $v => $aux) {
                    //                  $html .= "<td>" . $aux . "</td>";       
                    //             }
                    //             // pr($valor);
                    //         }
                    //          $html .= "</tr>";
                    // }

                    $totalGeral = 0;
					foreach ($resultado_relatorio as $key => $resultado) {
                        $html .= "<tr>";
							$totalGeral++;
							foreach ($resultado as $key2 => $valor) {
								foreach ($valor as $v => $aux) {
									// echo "<td>" . $aux . "</td>";

									$mystring = $v;
									$findme   = 'grupo_';
									$pos = strpos($mystring, $findme);
									if ($pos === false) {
                                        $html .= '<td style=" border: 1px solid #ddd;text-align: center;">' . $aux . '</td>';
									}	

								}
								// pr($valor);
							}
                            $html .= "</tr>";
					}
                    $html .= "<tr>";
                        $html .= "<td colspan='$contColspan' style='text-align: center;background-color: #176090;color: white;'>";
                            $html .= "<strong>Total:</strong>" . $totalGeral . " registro(s)";
                        $html .= "</td>";
                    $html .= "</tr>";

                     $html .= "</tbody>";
                    
                }
            }else{
                $html .="<tr>
                    <td>Não foram encontrados registos.</td>
                </tr>";   
            }

     $html .="</table>";
     // echo $html;die;

$diri = WWW_ROOT. '/' .  'relatorio_personalizado/excel/';
if(!is_dir($diri)) {
    mkdir($diri);
}
$nomefile = '/relatorio_' . date('Ymdhis');

$tmpfile = $nomefile.'.html';
file_put_contents($diri."/".$tmpfile, $html);

$reader = new PHPExcel_Reader_HTML;
$content = $reader->load($diri .$tmpfile);

// Pass to writer and output as needed
$objWriter = PHPExcel_IOFactory::createWriter($content, 'Excel2007');
$objWriter->save($diri.$nomefile.'.xlsx');

$url = Router::url('/', true).'relatorio_personalizado/excel' . $nomefile . ".xlsx";

$retorno = array();
$retorno['tipo'] = "ok";
$retorno['url'] = $url;

echo json_encode($retorno);

die;
