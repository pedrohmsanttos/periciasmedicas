<?php

class Util
{

	const XLS = 'xls';
	const CSV = 'csv';

	/**
	 * Valida CNPJ
	 *
	 * @author Luiz Otávio Miranda <contato@tutsup.com>
	 * @param string $cnpj
	 * @return bool true para CNPJ correto
	 *
	 */
	public static function valida_cnpj($cnpj)
	{
		// Deixa o CNPJ com apenas números
		$cnpj = preg_replace('/[^0-9]/', '', $cnpj);

		// Garante que o CNPJ é uma string
		$cnpj = (string)$cnpj;

		// O valor original
		$cnpj_original = $cnpj;

		// Captura os primeiros 12 números do CNPJ
		$primeiros_numeros_cnpj = substr($cnpj, 0, 12);

		/**
		 * Multiplicação do CNPJ
		 *
		 * @param string $cnpj Os digitos do CNPJ
		 * @param int $posicoes A posição que vai iniciar a regressão
		 * @return int O
		 *
		 */
		function multiplica_cnpj($cnpj, $posicao = 5)
		{
			// Variável para o cálculo
			$calculo = 0;

			// Laço para percorrer os item do cnpj
			for ($i = 0; $i < strlen($cnpj); $i++) {
				// Cálculo mais posição do CNPJ * a posição
				$calculo = $calculo + ($cnpj[$i] * $posicao);

				// Decrementa a posição a cada volta do laço
				$posicao--;

				// Se a posição for menor que 2, ela se torna 9
				if ($posicao < 2) {
					$posicao = 9;
				}
			}
			// Retorna o cálculo
			return $calculo;
		}

		// Faz o primeiro cálculo
		$primeiro_calculo = multiplica_cnpj($primeiros_numeros_cnpj);

		// Se o resto da divisão entre o primeiro cálculo e 11 for menor que 2, o primeiro
		// Dígito é zero (0), caso contrário é 11 - o resto da divisão entre o cálculo e 11
		$primeiro_digito = ($primeiro_calculo % 11) < 2 ? 0 : 11 - ($primeiro_calculo % 11);

		// Concatena o primeiro dígito nos 12 primeiros números do CNPJ
		// Agora temos 13 números aqui
		$primeiros_numeros_cnpj .= $primeiro_digito;

		// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
		$segundo_calculo = multiplica_cnpj($primeiros_numeros_cnpj, 6);
		$segundo_digito = ($segundo_calculo % 11) < 2 ? 0 : 11 - ($segundo_calculo % 11);

		// Concatena o segundo dígito ao CNPJ
		$cnpj = $primeiros_numeros_cnpj . $segundo_digito;

		// Verifica se o CNPJ gerado é idêntico ao enviado
		if ($cnpj === $cnpj_original) {
			return true;
		}
	}

	/**
	 * Método para validar o CPF
	 * @param string $cpf
	 * @return boolean
	 */
	public static function validaCPF($cpf = null)
	{

		// Verifica se um número foi informado
		if (empty($cpf)) {
			return false;
		}

		// Elimina possivel mascara
		// $cpf = ereg_replace('[^0-9]', '', $cpf);
		// $cpf = preg_replace('[^0-9]', ' ', $cpf);
		$cpf = str_replace(".", "", $cpf);
		$cpf = str_replace("-", "", $cpf);
		
		$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

		// Verifica se o numero de digitos informados é igual a 11
		if (strlen($cpf) != 11) {
			return false;
		}
		// Verifica se nenhuma das sequências invalidas abaixo
		// foi digitada. Caso afirmativo, retorna falso
		else if ($cpf == '00000000000' ||
			$cpf == '11111111111' ||
			$cpf == '22222222222' ||
			$cpf == '33333333333' ||
			$cpf == '44444444444' ||
			$cpf == '55555555555' ||
			$cpf == '66666666666' ||
			$cpf == '77777777777' ||
			$cpf == '88888888888' ||
			$cpf == '99999999999'
		) {
			return false;
			// Calcula os digitos verificadores para verificar se o
			// CPF é válido
		} else {

			for ($t = 9; $t < 11; $t++) {

				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf{$c} * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf{$c} != $d) {
					return false;
				}
			}

			return true;
		}
	}

	/**
	 * Método para remover os espaços extras presentes entre as palavras de uma String
	 * @param unknown $string
	 * @return mixed
	 */
	public static function removerEspacosExtras($string)
	{
		return preg_replace("/\s+/", " ", $string);
	}

	/**
	 * Método para gerar um arquivo xls ou csv
	 * @param unknown $data
	 * @param String $tipo Tipo da exportação (xls ou csv)
	 */
	public static function exportar_xls_csv($data = array(), $tipo = null)
	{
		header("Content-Disposition: attachment; filename=\"instancias_controle_social_" . date('Ymdhis') . ".$tipo\"");

		if ($tipo == self::XLS) {
			header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		} else {
			header("Content-Type: application/text/csv; charset=utf-8");
		}

		$flag = false;
		foreach ($data as $inputOut) {

			//Caso seja do tipo xls  esteja na primeira iteração, é incluído na primeira linha os títulos das colunas
			if (!$flag && $tipo == self::XLS) {
				echo implode("\t", array_keys($inputOut)) . "\r\n";
				$flag = true;
			}
			if ($tipo == self::XLS) {
				echo implode("\t", array_values($inputOut)) . "\r\n";
			} else {
				$out = fopen("php://output", 'w');
				fputcsv($out, array_values($inputOut), ',', '"');
				fclose($out);
			}
		}
		die();
	}

	/**
	 *
	 * Método para verificar se um objeto existe em um array
	 * @param unknown_type $objeto
	 * @param unknown_type $array_objetos
	 */
	public static function in_array_object($objeto, $array_objetos)
	{


		foreach ($array_objetos as $obj) {
			if (is_array($obj) && is_array($objeto)) {
				if ($obj['id'] == $objeto['id']) {
					return TRUE;
				}
			} else if (is_array($obj) && is_object($objeto)) {
				if ($obj['id'] == $objeto->id) {
					return TRUE;
				}
			} else if (is_object($obj) && is_object($objeto)) {
				if ($obj->id == $objeto->id) {
					return TRUE;
				}
			} else {
				if (is_object($objeto) && !is_array($obj)) {
					if ($obj->id == $obj) {
						return TRUE;
					}
				} else if (is_array($objeto) && !is_object($obj)) {
					if ($objeto['id'] == $obj) {
						return TRUE;
					}
				} else {
					if ($objeto == $obj) {
						return TRUE;
					}
				}
			}
		}
		return false;
	}

	/**
	 * * Metodo para retornar uma instancia de filtro para montar uma consulta
	 * @param string $limitarResultadosAtivos Flag que indica se a consulta deve considerar apenas registro ativos. Default é true
	 * @param string $model Nome do Model
	 * @return multitype:boolean
	 */
	public static function criarFiltrosConsulta($model = null, $limitarResultadosAtivos = true)
	{
		$filtro = array();
		if ($limitarResultadosAtivos) {
			$filtro["$model.ativo = "] = true;
		}
		return $filtro;
	}

	/**
	 * Método para remover os caracteres especiais de um documento
	 * @param unknown $valor
	 * @return mixed
	 */
	public static function limpaDocumentos($valor)
	{
		$valor = trim($valor);
		$valor = str_replace(".", "", $valor);
		$valor = str_replace(",", "", $valor);
		$valor = str_replace("-", "", $valor);
		$valor = str_replace("/", "", $valor);
		$valor = str_replace("_", "", $valor);
		return $valor;
	}

	/**
	 * Método para remover a mascara do telefone
	 * @param unknown $valor
	 * @return mixed
	 */
	public static function removerMascaraTelefone($valor)
	{
		$valor = trim($valor);
		$valor = str_replace("(", "", $valor);
		$valor = str_replace(")", "", $valor);
		$valor = str_replace("-", "", $valor);
		$valor = str_replace(" ", "", $valor);
		return $valor;
	}

	/**
	 * Método para remover a mascara do CEP
	 * @param unknown $valor
	 * @return mixed
	 */
	public static function removerMascaraCEP($valor)
	{
		$valor = trim($valor);
		$valor = str_replace("-", "", $valor);
		return $valor;
	}

	public static function temPermissao($permissao = null)
	{
		return in_array($permissao, $_SESSION['permissoes']);
	}

	/**
	 * Metodo que tem a função de inverter o formato de data original
	 * Caso a data venha no formato ptbr, é invertido para o fotmato usa
	 * Caso venha no USA, inverte para o ptbr
	 * Caso venha hora, retorna com hora, sem os segundos
	 * Caso não venha com hora, retorna sem hora
	 * @param string $strData
	 * @static
	 * @access public
	 * @return string
	 */
	public static function inverteData($strData)
	{
		$strData = trim($strData);
		$arrayData = explode(" ", $strData);

		$data = $arrayData[0];
		$hora = (isset($arrayData[1])) ? " " . substr($arrayData[1], 0, 5) : "";

		/* ptbr to usa */
		if (strpos($data, "/")) {
			list($dia, $mes, $ano) = explode("/", $data);
			return $ano . "-" . $mes . "-" . $dia . $hora;
		} /* usa to ptbr */ else if (strpos($data, "-")) {
			list($ano, $mes, $dia) = explode("-", $data);
			return $dia . "/" . $mes . "/" . $ano . $hora;
		}
	}

	public static function inverteDataComHora($strData)
	{
		$strData = trim($strData);
		$arrayData = explode(" ", $strData);

		$data = $arrayData[0];
		$hora = (isset($arrayData[1])) ? " " . substr($arrayData[1], 0, 8) : " 00:00:00";

		/* ptbr to usa */
		if (strpos($data, "/")) {
			list($dia, $mes, $ano) = explode("/", $data);
			return $ano . "-" . $mes . "-" . $dia . $hora;
		} /* usa to ptbr */ else if (strpos($data, "-")) {
			list($ano, $mes, $dia) = explode("-", $data);
			return $dia . "/" . $mes . "/" . $ano . $hora;
		}
	}

	public static function array2csv(array &$array)
	{
		if (count($array) == 0) {
			return null;
		}
		ob_start();
		$df = fopen("php://output", 'w');
		//        fputcsv($df, array_keys($array[0]),$delimiter = "," ,$enclosure = '1');

		foreach ($array as $row) {
			fputcsv($df, $row, ",", "\\");
		}

		fclose($df);
		return ob_get_clean();
	}

	/**
	 * Método para criar uma lista contendo os IDs dos objetos passados
	 * @param unknown $lista_objetos
	 */
	public static function criarListaIds($lista_objetos)
	{
		$ids = array();
		foreach ($lista_objetos as $item) {
			$ids[] = $item['id'];
		}
		return $ids;
	}

	public static function download_send_headers($filename)
	{
		// disable caching
		$now = gmdate("D, d M Y H:i:s");
		header('Content-Type: text/csv; charset=utf-8');
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");


		// force download
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");

		// disposition / encoding on response body
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
	}

	public static function diffDates($dtStart = null, $dtEnd = null)
	{
		if ($dtStart && $dtEnd) {
			$dtStart = new DateTime($dtStart);
			$dtEnd = new DateTime($dtEnd);
			return $dtStart->diff($dtEnd);
		}
	}

	public static function mask($val, $mask)
	{
		if (!empty($val)) {
			$maskared = '';
			$k = 0;
			for ($i = 0; $i <= strlen($mask) - 1; $i++) {
				if ($mask[$i] == '#') {
					if (isset($val[$k]))
						$maskared .= $val[$k++];
				} else {
					if (isset($mask[$i]))
						$maskared .= $mask[$i];
				}
			}
			return $maskared;
		}
	}

	public static function gerar_senha($tamanho)
	{
		$ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiusculas
		$mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
		$nu = "0123456789"; // $nu contem os numeros
		$senha = "";
		$senha .= str_shuffle($ma);

		$senha .= str_shuffle($mi);

		$senha .= str_shuffle($nu);


		// retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variavel $tamanho
		return substr(str_shuffle($senha), 0, $tamanho) . "a1!";
	}

	/**
	 * Verifica se a hora enviada é uma hora valida
	 *
	 * @param string $hourString
	 * @example $hourString description :  24:59:59
	 *
	 * @return boolean
	 */
	public static function hourValid($hourString)
	{
		$return = true;
		//Verifica se realmente existe a variavel
		if ($hourString) {
			$arrHour = explode(':', $hourString);
			if (count($arrHour) == 3) {
				$hour = is_numeric($arrHour[0]) ? $arrHour[0] : false;
				$minute = is_numeric($arrHour[1]) ? $arrHour[1] : false;
				$seconds = is_numeric($arrHour[2]) ? $arrHour[2] : false;

				$hourValide = false;
				$minuteValide = false;
				$secondValide = false;

				//Verifica se a hora do tipo é numerico
				if ($hour) {
					if ((int)$hour <= 24) {
						$hourValide = true;
					}
				}

				//Verifica se o minuto do tipo é numerico
				if ($minute) {
					if ((int)$minute <= 59) {
						$minuteValide = true;
					}
				}
				//Verifica se o segundo do tipo é numerico
				if ($seconds) {
					if ((int)$seconds <= 59) {
						$secondValide = true;
					}
				}

				if ((!$hourValide) || (!$minuteValide) || (!$secondValide)) {
					$return = false;
				}
			} elseif (count($arrHour) == 2) {
				$hour = is_numeric($arrHour[0]) ? $arrHour[0] : false;
				$minute = is_numeric($arrHour[1]) ? $arrHour[1] : false;

				$hourValide = false;
				$minuteValide = false;

				//Verifica se a hora do tipo é numerico
				if ($hour) {
					if ((int)$hour <= 24) {
						$hourValide = true;
					}
				}

				//Verifica se o minuto do tipo é numerico
				if ($minute) {
					if ((int)$minute <= 59) {
						$minuteValide = true;
					}
				}
				if ((!$hourValide) || (!$minuteValide)) {
					$return = false;
				}
			}
		}
		return $return;
	}

	public static function calc_idade($data_nasc)
	{
		if ($data_nasc) {
			$data_nasc = self::toBrData($data_nasc);
			$data_nasc = explode('/', $data_nasc);

			$data = date('d/m/Y');

			$data = explode('/', $data);

			$anos = $data[2] - $data_nasc[2];

			if ($data_nasc[1] > $data[1])
				return $anos - 1;

			if ($data_nasc[1] == $data[1])
				if ($data_nasc[0] <= $data[0]) {
					return $anos;
				} else {
					return $anos - 1;
				}

			if ($data_nasc[1] < $data[1])
				return $anos;
		}
		return "";
	}

	public static function trataString($string)
	{
		$retorno = trim(mb_strtolower(self::removerEspacosExtras($string)));
		return $retorno;
	}

	public static function convertToBytes($mb)
	{
		$mb = floatval($mb);
		return (($mb * 1024) * 1024);
	}


	// Soma de uma data com horas e minutos - $horaParaSomar => "hh:mm"
	public static function opeHorasMinutos($horaInicial, $horaParaSomar, $tipo)
	{
		$horaFinal = "";
		$aux = explode(':', $horaParaSomar);


		$horas = intval($aux['0']);
		$minutos = intval($aux['1']);

		if ($tipo) {
			$horaFinal = date('Y-m-d H:i:s', strtotime("+$horas hour", strtotime($horaInicial)));
			$horaFinal = date('Y-m-d H:i:s', strtotime("+$minutos minute", strtotime($horaFinal)));

		} else {
			$horaFinal = date('Y-m-d H:i:s', strtotime("-$horas hour", strtotime($horaInicial)));
			$horaFinal = date('Y-m-d H:i:s', strtotime("-$minutos minute", strtotime($horaFinal)));
		}

		return $horaFinal;
	}


	public static function isDBDataHora($dataHora)
	{
		return preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}.*/', $dataHora);
	}
	public static function isDBData($data){
		return preg_match('/\d{4}-\d{2}-\d{2}.*/', $data);
	}

	public static function isBrDataHora($dataHora)
	{
		return preg_match('/\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}.*/', $dataHora);
	}
	public static function isBrData($data){
		return preg_match('/\d{2}\/\d{2}\/\d{4}.*/', $data);
	}

	public static function toBrDataHora($dataHora, $full = false)
	{
		$dataHora = trim($dataHora);
		$size = $full ? 19 : 16;
		if (self::isDBDataHora($dataHora)) {
			return self::inverteDataComHora(substr($dataHora, 0, $size));
		} else if (self::isBrDataHora($dataHora)) {
			return substr($dataHora, 0, $size);
		} else {
			return self::toBrData($dataHora);
		}
	}

	public static function toBrData($data){
		$data = trim($data);
		if (self::isDBData($data)) {
			return self::inverteData(substr($data, 0, 10));
		} else if (self::isBrData($data)) {
			return substr($data, 0, 10);
		} else {
			return null;
		}
	}

	public static function toDBDataHora($dataHora, $full = false)
	{
		$dataHora = trim($dataHora);
		$size = $full ? 19 : 16;
		if (self::isDBDataHora($dataHora)) {
			return substr($dataHora, 0, $size);
		} else if (self::isBrDataHora($dataHora)) {
			return self::inverteDataComHora(substr($dataHora, 0, $size));
		} else {
			return self::toDBData($dataHora);
		}
	}

	public static function toDBData($data){
		$data = trim($data);
		if (self::isDBData($data)) {
			return substr($data, 0, 10);
		} else if (self::isBrData($data)) {
			return self::inverteData(substr($data, 0, 10));
		} else {
			return null;
		}
	}

	public static function getAllDatasEntrePeriodo($dataInicial, $dataFinal){
		$datas = array();
		if((!is_null($dataInicial) && $dataInicial != "") && (!is_null($dataFinal) && $dataFinal != "") ){

			$date_ini = "";
			$date_fim = "";

			$date_ini = self::toDBData($dataInicial); //Data inicial
			$date_fim = self::toDBData($dataFinal); //Data final

			  
			while (strtotime($date_ini) <= strtotime($date_fim)) {
				
				
					array_push($datas, $date_ini);
				

				$date_ini = date ("Y-m-d", strtotime("+1 day", strtotime($date_ini)));

			}//end - while

		}

		return $datas;   
	}

	public static function getDiaDaSemanaByData($data){
		$dias_semana = Configure::read('DIAS_SEMANA');
		return array_search (date("w", strtotime($data)), $dias_semana);
	}

	// Função pra retornar todos as datas de um dia em específico, por um período determinado
	public static function retornaDatasPorDia($diaSemana, $dataInicial, $dataFinal){
        $dias = array(0 => 'DOMINGO',1 => 'SEGUNDA-FEIRA',2 => 'TERÇA-FEIRA',3 => 'QUARTA-FEIRA',4 => 'QUINTA-FEIRA',5 => 'SEXTA-FEIRA',6 => 'SÁBADO');
        $datas = array();

        // pr("DIA DA SEMANA : " . $diaSemana);
        // pr("DATA INICIAL : " . $dataInicial);
        // pr("DATA FINAL : " . $dataFinal);

        if(!is_null($diaSemana) && $diaSemana != ""){
            if((!is_null($dataInicial) && $dataInicial != "") && (!is_null($dataFinal) && $dataFinal != "") ){

            	$date_ini = "";
            	$date_fim = "";

                $date_ini = self::toDBData($dataInicial); //Data inicial
                $date_fim = self::toDBData($dataFinal); //Data final

              	
                while (strtotime($date_ini) <= strtotime($date_fim)) {
                    
                    if( $dias[date("w", strtotime($date_ini))] == strtoupper($diaSemana) ){
                        array_push($datas, $date_ini);
                    }

                    $date_ini = date ("Y-m-d", strtotime("+1 day", strtotime($date_ini)));

                }//end - while

            }

        }
         return $datas;       

    }

   public static function primeiraLetraMaiusculaDia($string) { 
	    $sentences = preg_split('/([-]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE); 
	    $new_string = ''; 
	    foreach ($sentences as $key => $sentence) { 
	        $new_string .= ($key & 1) == 0? 
	            ucfirst(strtolower(trim($sentence))) : 
	            $sentence; 
	    } 
	    return trim($new_string); 
	}


	public static function returnLogSQL($model){
        $log = $model->getDataSource()->getLog(false, false);
	    pr($log);
	    die;
	} 

	// Interseção de arrays multidimensionais
	public static function array_intersect_recursive() {

        foreach(func_get_args() as $arg) {
            $args[] = array_map('serialize', $arg);
        }
        $result = call_user_func_array('array_intersect', $args);

        return array_map('unserialize', $result);
    }

    public static function returnArrayByAgrupamento($agrupamentoConcat){
    	$separate = '",';
		
		$agrupamentoConcat = str_replace("{", "", $agrupamentoConcat);
		$agrupamentoConcat = str_replace("}", "", $agrupamentoConcat);

		return explode($separate, $agrupamentoConcat);
    }
}
