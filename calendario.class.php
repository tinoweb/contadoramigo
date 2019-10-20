<?php
	class Itens_agenda{
		private $id;
		private $mes;
		private $dia;
		private $tipo;
		function __construct($dado){
			$this->setid($dado['id']);
			$this->setmes($dado['mes']);
			$this->setdia($dado['dia']);
			$this->settipo($dado['tipo']);
		}
		function getNomeItem(){
			$consulta = mysql_query("SELECT * FROM itens_agenda WHERE id = '".$this->gettipo()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta;
		}
		function getid(){
			return $this->id;
		}
		function setid($string){
			$this->id = $string;
		}
		function getmes(){
			return $this->mes;
		}
		function setmes($string){
			$this->mes = $string;
		}
		function getdia(){
			if( $this->dia < 10 )
				return '0'.$this->dia;
			return $this->dia;
		}
		function setdia($string){
			$this->dia = $string;
		}
		function gettipo(){
			return $this->tipo;
		}
		function settipo($string){
			$this->tipo = $string;
		}
	}

	class Calendario{
		//Função que escreve os dias da semana
		function mostrarSemanas(){
			$semanas = "DSTQQSS";
			for( $i = 0; $i < 7; $i++ )
				echo '<th width="27" align="center">'.$semanas{$i}.'</th>';
		}
		//Função que retorna o numero de dias de um determinado mês
		function getNumeroDiasMes( $mes ){
			$numero_dias = array( 
					'1' => 31, '2' => 28, '3' => 31, '4' =>30, '5' => 31, '6' => 30,
					'7' => 31, '8' =>31, '9' => 30, '10' => 31, '11' => 30, '12' => 31
			);
			if ((($ano % 4) == 0 and ($ano % 100)!=0) or ($ano % 400)==0){
			    $numero_dias['2'] = 29;	// altera o numero de dias de fevereiro se o ano for bissexto
			}
			return $numero_dias[$mes];
		}
		//Função que retorna o nome por extenso do mes
		function getNomeMes( $mes ){
			$meses = array( '1' => "Janeiro", '2' => "Fevereiro", '3' => "Março",
			             '4' => "Abril",   '5' => "Maio",      '6' => "Junho",
			             '7' => "Julho",   '8' => "Agosto",    '9' => "Setembro",
			             '10' => "Outubro", '11' => "Novembro",  '12' => "Dezembro"
			             );
			if( $mes >= 01 && $mes <= 12)
				return $meses[$mes];
			return "Mês deconhecido";
		}
		//função que mostra o calendário para um determinado Mes
		function mostrarCalendario( $mes , $ano  ){
			$datas = new Datas();
			$cont = 0;
			$numero_dias = $this->getNumeroDiasMes( $mes );	// retorna o número de dias que tem o mês desejado
			$nome_mes = $this->getNomeMes( $mes );
			$diacorrente = 0;	
			$diasemana = jddayofweek( cal_to_jd(CAL_GREGORIAN, $mes,"01",$ano) , 0 );	// função que descobre o dia da semana
			$dias_envios = $this->getDiasItensMes($mes);
			$dias_feriado = $this->getDiasFeriadoMes($mes);
		    echo '<div class="tituloVermelho">'.$nome_mes.'</div>';
		    echo '<table border="0" style="margin-bottom:5px">';
		    echo '<tbody>';
		    echo '<tr>';
		    $this->mostrarSemanas();	// função que mostra as semanas aqui
			echo "</tr>";
			for( $linha = 0; $linha < 6; $linha++ ){
			   	echo "<tr>";
		 	   	for( $coluna = 0; $coluna < 7; $coluna++ ){
				echo '<td width="27" align="center" ';
					if( in_array( ($diacorrente+1) , $dias_envios ) ){
					   	echo ' class="dataCompromisso" ';
					}
					else if( ($coluna == 0 || $coluna == 6 ) && $diacorrente > 0 && $diacorrente <= $numero_dias  ) {	
						echo 'class="fimDeSemana"';
					}
					else if( ( in_array(($diacorrente+1), $dias_feriado)  && ($diacorrente+1) > 0 && ($diacorrente+1) <= $numero_dias  ) ){
						echo 'class="fimDeSemana"';
					}
					else{
						echo 'class="td_calendario"';
					}
					echo $ano.'-'.$mes.'-'.($diacorrente+1);
				    if(($diacorrente + 1) <= $numero_dias ){
				        if( $coluna < $diasemana && $linha == 0){
							echo " id = 'dia_branco' ";
					 	}
					 	else{
					  		echo " id = 'dia_comum' ";
					  	}
				    }
				    else{
						echo " ";
				    }
					echo ' align="center" valign="center">';
			 	 	/* TRECHO IMPORTANTE: A PARTIR DESTE TRECHO É MOSTRADO UM DIA DO CALENDÁRIO (MUITA ATENÇÃO NA HORA DA MANUTENÇÃO) */
			        if( $diacorrente + 1 <= $numero_dias ){
						 if( $coluna < $diasemana && $linha == 0){
						  	 echo " ";
						 }
						 else{
						  	// echo "<input type = 'button' id = 'dia_comum' name = 'dia".($diacorrente+1)."'  value = '".++$diacorrente."' onclick = "acao(this.value)">";
						  	$cont = ($cont + 1) % 4;
							echo ++$diacorrente;
						}
					}
					else{
						echo "&nbsp;";
						++$diacorrente;
				    }
			 	    /* FIM DO TRECHO MUITO IMPORTANTE */
			 		echo "</td>";
				}
				echo "</tr>";
			}
		 	echo "</table>";
		}
		function getDiasItensMes($mes){
			$array =  array();
			$itens = $this->getSqlItensMes($mes);
			while( $item = mysql_fetch_array($itens) ){
				$item = new Itens_agenda($item);
				$array[] = intval($item->getdia());
			}
		   	return $array;
		}
		function getDiasFeriadoMes($mes){
			$array =  array();
			$itens = $this->getSqlFeriadosMes($mes);
			$item = mysql_fetch_array($itens);
			$item = explode(',',$item['string']);
			foreach ($item as $value) {
				$array[] = $value;
			}
		   	return $array;
		}
		function getSqlFeriadosMes($mes){
			return mysql_query("SELECT * FROM agenda_index_feriados_publicar WHERE mes = '".$mes."'");
		}
		function getSqlItensMes($mes){
			return mysql_query("SELECT * FROM agenda_index_publicar WHERE mes = '".$mes."' ORDER BY dia ASC");
		}
		function getItensMes($mes){
			echo '<span class="legenda">';
			$itens = $this->getSqlItensMes($mes);			
			while( $item = mysql_fetch_array($itens) ){
				$item = new Itens_agenda($item);
				$infoNomeLinkItem = $item->getNomeItem();
				echo '<strong>Dia '.$item->getdia().': <a href="'.$infoNomeLinkItem['pagina'].'"</strong>'.$infoNomeLinkItem['frase'].'</a><br>';
		   	}
		   	echo '</span>' ;
		}
		function __construct(){

		}
	}
?>