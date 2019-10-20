<?php 
	
	class Datas{
		private $data;
		private $somar;
		
		function somarMes($data,$meses){
			$aux = $this->getAno($data).'-'.$this->getMes($data).'-20';
			$aux = $this->somarData($aux,30*$meses);
			$aux = $this->getAno($aux).'-'.$this->getMes($aux).'-'.$this->getDia($data);
			while( !checkdate($this->getMes($aux),$this->getDia($aux),$this->getAno($aux)) )
				$aux = $this->subtrairData($aux,1);
			return $aux;
		}
		function subtrairMes($data,$meses){
			$aux = $this->getAno($data).'-'.$this->getMes($data).'-20';
			$aux = $this->somarData($aux,30*$meses);
			$aux = $this->getAno($aux).'-'.$this->getMes($aux).'-'.$this->getDia($data);
			if( !checkdate($this->getMes($aux),$this->getDia($aux),$this->getAno($aux)) )
				$aux = $this->ultimoDiaMes($aux);
			return $aux;
		}
		function diasRestantesMes($data){
			$ultimo_dia_mes = $this->ultimoDiaMes($data);
			$quantidade = 0;
			while ( $this->diferencaData($ultimo_dia_mes,$data) >= 0 ){
				$ultimo_dia_mes = $this->subtrairData($ultimo_dia_mes,1);
				$quantidade = $quantidade + 1;
			}
			return $quantidade;		
		}
		function diasTranscorridosMes($data){
			$primeiro_dia_mes = $this->primeiroDiaMes($data);
			$quantidade = 0;
			while ( $this->diferencaData($primeiro_dia_mes,$data) < 0 ){
				$primeiro_dia_mes = $this->somarData($primeiro_dia_mes,1);
				$quantidade = $quantidade + 1;
			}
			return $quantidade;
		}
		function diasPorMes($data){
			$primeiro_dia_mes = $this->primeiroDiaMes($data);
			$ultimo_dia_mes = $this->ultimoDiaMes($data);
			$quantidade = 1;
			while ( $this->diferencaData($ultimo_dia_mes,$primeiro_dia_mes) > 0 ){
				$primeiro_dia_mes = $this->somarData($primeiro_dia_mes,1);
				$quantidade = $quantidade + 1;
			}
			return $quantidade;
		}
		function ultimoDiaMes($data){
			$aux = $this->getAno($data).'-'.$this->getMes($data).'-20';
			$aux = $this->somarData($aux,30);
			$aux = $this->getAno($aux).'-'.$this->getMes($aux).'-1';
			$aux = $this->subtrairData($aux,1);
			return $aux;
		}
		function primeiroDiaMes($data){
			$aux = $this->getAno($data).'-'.$this->getMes($data).'-1';
			return $aux;
		}
		function getAno($data){
			$aux = explode('-', $data);
			return $aux[0];
		}
		function getMes($data){
			$aux = explode('-', $data);
			return $aux[1];
		}
		function getDia($data){
			$aux = explode('-', $data);
			return $aux[2];
		}
		function diasUteisEntreDatas($dataInicial,$dataFinal){
			if( $this->diferencaData($dataFinal,$dataInicial) <= 0 )
				return 0;
			$dias = 0;
			while( $this->diferencaData($dataFinal,$dataInicial) > 0 ){
				$dataInicial = $this->somarDiasUteis($dataInicial,1);
				$dias = $dias + 1;
			}
			return $dias;
		}
		function somarDiasUteis($data,$dias){
			$i = 0;
			while ( $i < $dias ) {
				$data = $this->somarData($data,1);
				if( $this->ifDiaUtil($data) )
					$i++;
			}
			return $data;
		}
		function ifDiaUtil($data){
			$dia = date('w', strtotime($data));
			if( $dia == 6 || $dia == 0 || $this->isFeriado($data) )
				return false;
			else
				return true;
		}
		function somarDiasUteisExcetoSexta($data,$dias){
			$i = 0;
			while ( $i < $dias ) {
				$data = $this->somarData($data,1);
				if( $this->ifDiaUtilSexta($data) )
					$i++;
			}
			return $data;
		}
		function ifDiaUtilSexta($data){
			$dia = date('w', strtotime($data));
			if( $dia == 5 || $dia == 6 || $dia == 0 || $this->isFeriado($data) )
				return false;
			else
				return true;
		}		
		
		function retornaDiaUtil($data){
			$dia = date('w', strtotime($data));
			switch($dia) {
				case 0:
					return $this->retornaDiaUtil($this->somarData($data,1));
					break;
				case 6:
					return $this->retornaDiaUtil($this->somarData($data,1));
					break;
				default:
					if($this->isFeriado($data)) {
						return $this->retornaDiaUtil($this->somarData($data,1));		
					} else {
						return $data;
					}
					break;	
			}
		}		
		function isFeriado($data) {
			
			$aux = explode("-", $data);
			
			// realiza a requisição do conexoa com o banco.
			require_once('conect.php');

			// pega todos funcionário cadastrado e não demitidos.
			$query = " SELECT * FROM feriado ORDER BY feriadoData DESC";

			$resultado = mysql_query($query) or die (mysql_error());

			if( mysql_num_rows($resultado) > 0 ){
				while($array = mysql_fetch_array($resultado)){
					$feriados [] = $array['feriadoData'];
				}
			}
		
			if($feriados) {
				foreach ($feriados as $value) {
					if( $this->diferencaData($value,$data) == 0 )
						return true;
				}
			}
			return false;
		}
		
		function somarData($data,$dias){
			return date('Y-m-d', strtotime("+".$dias." days",strtotime($data))); 
		}
		function subtrairData($data,$dias){
			return date('Y-m-d', strtotime("-".$dias." days",strtotime($data))); 
		}
		function converterData($data){
			$aux = explode('/', $data);
			return $aux[2].'-'.$aux[1].'-'.$aux[0];
		}
		function desconverterData($data){
			$aux = explode('-', $data);
			return $aux[2].'/'.$aux[1].'/'.$aux[0];
		}
		function geraTimestamp($data) {
			$partes = explode('-', $data);
			return mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
		}
		function diferencaMeses($data_inicio,$data_fim){
			$meses = 0;
			$data_inicio = $this->somarData($data_inicio,30);
			while( $this->diferencaData($data_inicio,$data_fim) < 0 ){
				$data_inicio = $this->somarData($data_inicio,30);
				$meses = $meses + 1;
			}
			return $meses;
		}
		function gerarData($dia,$mes,$ano){
			return $ano.'-'.$mes.'-'.$dia;
		}
		function diferencaData($dataFinal,$dataInicial){
			$dataFinal = $this->geraTimestamp($dataFinal);
			$dataInicial = $this->geraTimestamp($dataInicial);
			$diferenca = $dataFinal - $dataInicial;
			//$dias = (int)ceil( $diferenca / (60 * 60 * 24)); 
			
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // alteracão por Átano.
			return $dias;
		}
	}
	// if( isset($_GET['teste']) ){

	// 	$datas = new Datas();
	// 	// echo $datas->geraTimestamp('2016-09	-14');
	// 	// echo $datas->geraTimestamp('2016-10-15');
	// 	echo $datas->geraTimestamp('2016-10-17')- $datas->geraTimestamp('2016-10-16');echo '<br>';
	// 	echo $datas->geraTimestamp('2016-10-16')- $datas->geraTimestamp('2016-10-15');echo '<br>';
	// 	echo $datas->geraTimestamp('2016-10-15')- $datas->geraTimestamp('2016-10-14');echo '<br>';

	// }

?>