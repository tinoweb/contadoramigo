<?php 
/**
 * Data: 20/07/2017
 * Auntor: Átano de FariasJacinto
 */
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// Realiza a requisição do arquivo que retorna o objeto com os Lista de porcentagem do inss.
require_once('../Model/TabelaINSS/TabelaINSSData.php');

// Classe responsavel por realizar o controle do inss.
class RetencaoINSSController {
	
	// Método criado para criar as linas da tabela.
	public function GeraLinhaTabelaINSS() {
		
		// Pega a data atual.
		$ano = date('Y');
		
		$tagLinha = false;

		// Verifica se o ano foi definido.
		if(isset($_GET['ano']) && !empty($_GET['ano'])) {
			$ano = $_GET['ano'];
		}

		// Instância da classe do INSS.
		$tabelaINSSData = new TabelaINSSData();

		// Pega os dados do INSS.
		$dadosINSS = $tabelaINSSData->PegaINSSPorAno($ano);

		if($dadosINSS) {
			
			$count = 1;
			
			foreach($dadosINSS as $val) {
				
				if($tagLinha) {

					$tagLinha .= '<tr>
									<td align="right">De R$ '.$valorAux.' até</td>
									<td align="center">
										<input type="hidden" id="inssId_'.$count.'" name="inssId_'.$count.'" value="'.$val->getInssId().'" />
										R$ <input type="text" id="valor_'.$count.'" name="valor_'.$count.'" value="'.number_format($val->getValor(),2,",",".").'" class="current" size="7" maxlength="10" />
									</td>
									<td align="center">
										<input type="text" id="porcentagem_'.$count.'" name="porc_'.$count.'" value="'.str_replace('.',',',$val->getPorcentagem()).'" class="inteiro" size="2" maxlength="10" /> %
									</td>
								</tr>';	
					
					$count += 1;
					
				} else {
					
					$tagLinha .= '<tr>
						<td align="right">Até</td>
						<td align="center">
							<input name="atualizarINSS" type="hidden" />
							<input type="hidden" id="inssId_'.$count.'" name="inssId_'.$count.'" value="'.$val->getInssId().'" />
							R$ <input type="text" id="valor_'.$count.'" name="valor_'.$count.'" value="'.number_format($val->getValor(),2,",",".").'" class="current" size="7" maxlength="10" />
						</td>
						<td align="center">
							<input type="text" id="porcentagem_'.$count.'" name="porc_'.$count.'" value="'.str_replace('.',',',$val->getPorcentagem()).'" class="inteiro" size="2" maxlength="10" /> %
						</td>
					</tr>';
					
					$count += 1;
				}
				
				$valorAux = $val->getValor() + 0.01;
			}
		} else {
			$tagLinha =	'<tr>
						<td align="right">Até</td>
						<td align="center">
							<input name="IncluirINSS" type="hidden" />
							<input id="ano_1" name="ano_1" value="'.$ano.'" type="hidden">
							R$ <input id="valor_1" name="valor_1" value="0" class="current" size="7" maxlength="10" type="text">
						</td>
						<td align="center">
							<input id="porcentagem_1" name="porc_1" value="0" class="inteiro" size="2" maxlength="10" type="text"> %
						</td>
					</tr>
					<tr>
						<td align="right">De R$ 0,00 até</td>
						<td align="center">
							<input id="ano_2" name="ano_2" value="'.$ano.'" type="hidden">
							R$ <input id="valor_2" name="valor_2" value="0" class="current" size="7" maxlength="10" type="text">
						</td>
						<td align="center">
							<input id="porcentagem_2" name="porc_2" value="0" class="inteiro" size="2" maxlength="10" type="text"> %
						</td>
					</tr>
					<tr>
						<td align="right">De R$ 0,00 até</td>
						<td align="center">
							<input id="ano_3" name="ano_3" value="'.$ano.'" type="hidden">
							R$ <input id="valor_3" name="valor_3" value="0" class="current" size="7" maxlength="10" type="text">
						</td>
						<td align="center">
							<input id="porcentagem_3" name="porc_3" value="0" class="inteiro" size="2" maxlength="10" type="text"> %
						</td>
					</tr>';
		}
		
		return $tagLinha;
		
	} 
	
	// Método criado para montar o select com os anos dos inss
	public function SelectAnoFiltro() {
		
		// Instância da classe do INSS.
		$tabelaINSSData = new TabelaINSSData();

		// Pega os dados do INSS.
		$ano = $tabelaINSSData->PegaMenorAno();
		
		$anoAux = date('Y');		
		$proxAno = date('Y');
		$proxAno += 1; 
		
		if(isset($_GET['ano']) && !empty($_GET['ano'])){ 
			$anoAux = $_GET['ano'];
		}		
		
		// Verifica se existe o ano
		if(!$ano) {
			$ano = date('Y');
		}
		
		$tagOption = '';
		
		// Loop para montar os itens do select.
		for($i=$ano;$i<=date('Y');$i++) {
			
			if($i == $anoAux){
				$tagOption .= "<option value='".$i."' selected=''>".$i."</option>";	
			} else {
				$tagOption .= "<option value='".$i."'>".$i."</option>";
			}
		}
		
		$tagOption .= "<option value='".$proxAno."'>".$proxAno."</option>";
		
		return $tagOption;			
	}
}


