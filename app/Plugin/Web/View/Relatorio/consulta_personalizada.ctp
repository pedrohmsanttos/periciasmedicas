<div id="resultadoRelatorio">
	<div class="row float-right btn-edit">
	    <div id="loading-consultar" class="displayNone" style="
	        float:left;
	        background: rgba( 255, 255, 255, .8 ) url('img/l6BEEsW.gif') 50% 50% no-repeat;
	        width: 30px;
	        height: 30px;
	        margin-right: 5px" >
	    </div>
	    <button class="btn fa fa-arrow-left estiloBotao btn-info" type="button" id="back-relatorio"> Voltar</button>
	    
	</div>

	<?php if(!empty($resultado_relatorio)): ?>
		<?php if(isset($resultado_relatorio['titulo_relatorio'])): ?>
			<div class="row">
				<div class="col-md-12">
					<h3><strong><?php echo $resultado_relatorio['titulo_relatorio']; ?></strong></h3>
				</div>
			</div>
		<?php endif; ?>

		<?php if(isset($resultado_relatorio['label_orgao'])): ?>
			<div class="row">
				<div class="col-md-12">
					<p>Orgão(s): <strong><?php echo $resultado_relatorio['label_orgao']; ?></strong></p>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<table class="table table-striped table-hover table-bordered " id="editable-sample">
		<?php
			
			// pr($resultado_relatorio);die;

			$header = array(
				"id" 						=> "N° Atendimento",
				"id_atendimento" 			=> "N° Atendimento",
				"id_agendamento" 			=> "N° Agendamento",
				"data_atendimento" 			=> "Data do Atendimento",
				"data_agendamento" 			=> "Data do Agendamento",
				"nome" 						=> "Nome",
				"nome_und" 					=> "Nome da Unidade",
				"bairro" 					=> "Bairro",
				"cpf_usuario" 				=> "CPF",
				"qtd_licencas" 				=> "Qtd de Tipologias",
				"nome_funcao" 				=> "Função",
				"data_admissao_servidor" 	=> "Data Admissão",
				"sexo_desc" 				=> "Sexo",
				"duracao"					=> "Período da Licença",
				"orgao_origem"				=> "Orgão de Origem" 
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

						
						
						$aux = 0;

						foreach ($resultado_agrupado as $chave_resu_pai => $resuPai) {

							foreach ($resuPai as $chave_resu => $resu) {
								foreach ($resu as $chave_resu_deta => $resu_deta) {
									echo "<tr><thead>";
									foreach ($resu_deta as $chave_head => $value) {
										// pr($chave_head);
										$mystring = $chave_head;
										$findme   = 'grupo_';
										$pos = strpos($mystring, $findme);
										if ($pos === false) {
											$aux++;
											echo "<th>" . $header[trim($chave_head)] . "</th>";
										}
									}
									break;
									echo "</thead></tr>";
								}
								break;
							}
							break;

						}
						// die;

						
						// die;
						$totalGeral = 0;
						
						echo "<tbody>";

						foreach ($resultado_agrupado as $chave_resu_pai => $resu_pai) {
							echo "<tr><td colspan='$aux'>Tipologia: <strong>" . $chave_resu_pai . "</strong></td></tr>";

							foreach ($resu_pai as $chave_resu => $resu) {
								echo "<tr><td colspan='$aux'>CID: <strong>" . $chave_resu . "</strong></td></tr>";
								// pr($chave_resu);
								$totalGrupo = 0;
								
								foreach ($resu as $chave_resu_deta => $resu_deta) {
									$totalGrupo++;
									// pr($resu_deta);
									$totalGeral++;
									echo "<tr>";
									foreach ($resu_deta as $chave_head => $value) {
										$mystring = $chave_head;
										$findme   = 'grupo_';
										$pos = strpos($mystring, $findme);
										if ($pos === false) {
										    echo "<td>" . $value . "</td>";
										    
										}
									}
									echo "</tr>";
									// echo "TOTAL GRUPO >>" . $totalGrupo . "<br>";
								}
								echo "<tr>";
									echo "<td colspan='$aux' style='text-align: center;'>";
										echo "<strong>Total Grupo:</strong>" . $totalGrupo . " registro(s)";
									echo "</td>";
								echo "</tr>";
							}
						}

						
						echo "<tr>";
							echo "<td colspan='$aux' style='text-align: center;background-color: #176090;color: white;'>";
								echo "<strong>Total:</strong>" . $totalGeral . " registro(s)";
							echo "</td>";
						echo "</tr>";
						echo "</tbody>";


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
						
						echo "<tr><thead>";
						$aux = 0;
						foreach ($resultado_agrupado as $chave_resu => $resu) {
							foreach ($resu as $chave_resu_deta => $resu_deta) {
								foreach ($resu_deta as $chave_head => $value) {
									$mystring = $chave_head;
									$findme   = 'grupo_';
									$pos = strpos($mystring, $findme);
									if ($pos === false) {
										$aux++;
									    echo "<th>" . $header[trim($chave_head)] . "</th>";
									}
								}
								break;
							}
							break;
						}
						
						echo "</thead></tr>";
						// die;
						$totalGeral = 0;
						
						echo "<tbody>";
						foreach ($resultado_agrupado as $chave_resu => $resu) {
							echo "<tr><td colspan='$aux'><strong>" . $chave_resu . "</strong></td></tr>";
							// pr($chave_resu);
							$totalGrupo = 0;
							
							foreach ($resu as $chave_resu_deta => $resu_deta) {
								$totalGrupo++;
								// pr($resu_deta);
								$totalGeral++;
								echo "<tr>";
								foreach ($resu_deta as $chave_head => $value) {
									$mystring = $chave_head;
									$findme   = 'grupo_';
									$pos = strpos($mystring, $findme);
									if ($pos === false) {
									    echo "<td>" . $value . "</td>";
									    
									}
								}
								echo "</tr>";
								// echo "TOTAL GRUPO >>" . $totalGrupo . "<br>";
							}
							echo "<tr>";
								echo "<td colspan='$aux' style='text-align: center;'>";
									echo "<strong>Total Grupo:</strong>" . $totalGrupo . " registro(s)";
								echo "</td>";
							echo "</tr>";
						}
						echo "<tr>";
							echo "<td colspan='$aux' style='text-align: center;background-color: #176090;color: white;'>";
								echo "<strong>Total:</strong>" . $totalGeral . " registro(s)";
							echo "</td>";
						echo "</tr>";
						echo "</tbody>";
						// die;
					}

					

				}else{
					$primeiro = $resultado_relatorio[0][0];

					echo "<tr><thead>";
					$contColspan = 0;
					foreach ($primeiro as $key => $value) {
						// echo "<th>" . $key . "</th>";
						$mystring = $key;
						$findme   = 'grupo_';
						$pos = strpos($mystring, $findme);
						if ($pos === false) {
						    echo "<td>" . $header[trim($key)] . "</td>";
						    $contColspan++;
						}
					}
					echo "</thead></tr>";

					echo "<tbody>";
					$totalGeral = 0;
					foreach ($resultado_relatorio as $key => $resultado) {
							echo "<tr>";
							$totalGeral++;
							foreach ($resultado as $key2 => $valor) {
								foreach ($valor as $v => $aux) {
									// echo "<td>" . $aux . "</td>";

									$mystring = $v;
									$findme   = 'grupo_';
									$pos = strpos($mystring, $findme);
									if ($pos === false) {
									    echo "<td>" . $aux . "</td>";
									}	

								}
								// pr($valor);
							}
							echo "</tr>";
					}
					echo "<tr>";
						echo "<td colspan='$contColspan' style='text-align: center;background-color: #176090;color: white;'>";
							echo "<strong>Total:</strong>" . $totalGeral . " registro(s)";
						echo "</td>";
					echo "</tr>";
					echo "</tbody>";
					
				}
			}else{
				?>
				<tr>
					<td>Não foram encontrados registros.</td>
				</tr>	
				<?php
			}

			// pr($primeiro);
		?>
	</table>
</div>


<script type="text/javascript">
	$("#back-relatorio").click(function(){
        $(".panel-body").show();
        $("#resultadoRelatorio").hide();
    });
</script>