<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 07/07/2017
 */

// Realiza a requisição do arquivo que retorna o objeto com os dados do funcionário.
require_once('Model/DadosFuncionarios/DadosFuncionariosData.php');

// Realiza a requisição do arquivo que retorna o objeto com os dados de pagamento do funcionário.
require_once('Model/PagamentoFuncionario/PagamentoFuncionarioData.php');

// Realiza a requisição do arquivo com os pagamentos de férias.
require_once('Model/PagamentoFerias/PagamentoFeriasData.php');

// Realiza a requisição do arquivo que retorna o objeto com os Lista de porcentagem do inss.
require_once('Model/TabelaINSS/TabelaINSSData.php');

//Classe criada para manipular os dados que deverão ser apresentado.
class PagamentoFuncionarioController {
	
	public function PegaDadosFuncionarios($empresaId){
		
		// Instância da classe que manipula os dados do funcionário.
		$dadosFuncionariosData = new DadosFuncionariosData();
		
		// Pega a lista de funcionarios com os dados do funcionário.
		$listaObjeto = $dadosFuncionariosData->PegaListaObjetoFuncionario($empresaId);
		
		// Monta o select com a lista de funcionário.
		$tagSelect = ' <select name="funcionarioId" id="funcionarioId"><option value="">Selecione o funcionário</option>';
 
		// Verifica se existe dados do funcionário e monta os option. 
		if($listaObjeto) {
			
			// Percorre a lista.
			foreach($listaObjeto as $val){
				$tagSelect .= " <option value=".$val->getFuncionarioId().">".$val->getNome()."</option> ";
			}
		
		}
		
		$tagSelect .= '</select>'; 
 
		return $tagSelect;	
	}
	
	public function ExisteFuncionario($empresaId, $funcionariId = 0){
		
		// Instância da classe que manipula os dados do funcionário.
		$dadosFuncionariosData = new DadosFuncionariosData();
		
		// Pega a lista de funcionarios com os dados do funcionário.
		$listaObjeto = $dadosFuncionariosData->PegaListaObjetoFuncionario($empresaId);
		
		if($listaObjeto) {
			return true;
		}
				
		return false;
	}	
	
	public function PegaDadosFuncionariosFiltro($empresaId, $funcionariId = 0){
		
		// Instância da classe que manipula os dados do funcionário.
		$dadosFuncionariosData = new DadosFuncionariosData();
		
		// Pega a lista de funcionarios com os dados do funcionário.
		$listaObjeto = $dadosFuncionariosData->PegaListaObjetoFuncionario($empresaId);
		
		// Monta o select com a lista de funcionário.
		$tagSelect = ' <select name="funcionarioId" id="nome" style="display: inline;"><option value="">Todos</option>';
 		
		// Verifica se existe dados do funcionário e monta os option. 
		if($listaObjeto) {
			
			// Percorre a lista.
			foreach($listaObjeto as $val){
				
				if($funcionariId == $val->getFuncionarioId()) {
					$tagSelect .= " <option value=".$val->getFuncionarioId()." selected>".$val->getNome()."</option> ";
				} else {
					$tagSelect .= " <option value=".$val->getFuncionarioId().">".$val->getNome()."</option> ";
				}
			}
		}
		
		$tagSelect .= '</select>'; 
 
		return $tagSelect;	
	}	
	
	public function PegaValorINSS(){
		
		// Instância a classe para Responsavel por pegar os dados do inss.
		$inssDados = new TabelaINSSData();
		
		$inss = $inssDados->PegaINSSPorAno(date('Y'));

		$count = 0;
		$tag = '';
		
		// Monta os imputs hidden com os valores e porcentagens.
		if($inss) {
			
			// Percorre a lista de dados para montar a lista de input com os dados do inss.
			foreach($inss as $val) {
				
				$count += 1;
				
				// Monta input com os dados do inss.
				$tag .= ' <input id="inss_'.$count.'" class="inss" type="hidden" value="'.$val->getValor().'" data-porcentagem="'.$val->getPorcentagem().'"> ';
			}
		}
		
		return $tag;
	}
	
	// Método criado para criar a lista com os anos.
	public function FiltroAno($empresaId){
		
		// Instancia da classe que manipula os pagamentos do funcionario.
		$pagamentoFuncionario = new PagamentoFuncionarioData();
		
		$ano = $pagamentoFuncionario->PegaMenorAnoIncluido($empresaId);
		
		if(empty($ano)){
			$ano = date('Y');
		} 		
		
		$dataUltimoPagto = $pagamentoFuncionario->PegaDataUltimoPagamento($empresaId);
		
		// Pega o ano atual para poder pegar as datas de pagamento. 
		$anoAux = date('Y');
		
		if(!empty($dataUltimoPagto)){
			
			$anoAux = date('Y', strtotime($dataUltimoPagto));			
		}
		
		// Verifica qual o Ano.
		if(isset($_GET['periodoAno']) && !empty($_GET['periodoAno'])) {
			
			// Pega o ano que esta na busca.
			$anoAux = $_GET['periodoAno'];
		}
		
		$tag = '<select name="periodoAno" id="periodoAno">';

		for($i = date('Y'); $i >= $ano; $i--) {
			
			if($anoAux == $i) {
				$tag .=	'<option value="'.$i.'" selected="">'.$i.'</option>';
			} else {
				$tag .=	'<option value="'.$i.'">'.$i.'</option>';
			}
		}
		
		$tag .= '</select>';
		
		return $tag;
	}	
	
	// Método criado para criar a lista com os meses.
	public function FiltroMeses($empresaId){
		
		// Instancia da classe que manipula os pagamentos do funcionario.
		$pagamentoFuncionario = new PagamentoFuncionarioData();			
		
		// Pega o numero do mês atual
		$mesNumber = date('n');
		
		$dataUltimoPagto = $pagamentoFuncionario->PegaDataUltimoPagamento($empresaId);
		
		if(!empty($dataUltimoPagto)){
			$mesNumber = date('m', strtotime($dataUltimoPagto));
		}
	
		// Verifica qual o mes.
		if(isset($_GET['periodoMes'])) {
			// Pega o numero do mês
			$mesNumber = $_GET['periodoMes'];
		}
		
		// Monta array com os meses do ano.
		$meses = array(1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11 =>'Novembro',12=>'Dezembro');
		
		// Inicia a criação do select.
		$tag = "<select name='periodoMes' id='periodoMes'><option value=''>Todos</option>";
		
		// Caso não exista a data ele pega a data atual
		if(!$meses){
			$meses = date('Y');
		}
		
		// Monta a lista de opções do select.
		foreach($meses as $key=>$val){
			
			if($mesNumber == $key) {
				$tag .= "<option value='".$key."' selected=''>".$val."</option>";
			} else {
				$tag .= "<option value='".$key."'>".$val."</option>";
			}
		}
				
		$tag .="</select>";
		
		return $tag;
	}
	
	// Método criado para criar a tabela 
	public function CriaTabelaComPagamentos(){
		
		$pagamentoFuncionario = new PagamentoFuncionarioData();
		
		$empresaId = $_SESSION['id_empresaSecao'];
		
		// Pega o código do funcionario.
		$funcionarioId = (isset($_GET['funcionarioId']) && is_numeric($_GET['funcionarioId']) ? $_GET['funcionarioId'] : '');
		
		$data1 = date('Y-m').'-01';
		$data2 = date('Y-m').'-31';
		
		$dataUltimoPagto = $pagamentoFuncionario->PegaDataUltimoPagamento($empresaId);
		
		if(!empty($dataUltimoPagto)){
			
			$data1 = date('Y-m', strtotime($dataUltimoPagto)).'-01';
			$data2 = date('Y-m', strtotime($dataUltimoPagto)).'-31';
		}		
		
		// Verifica qual foi o tipo de filtro definido.
		if(isset($_GET['dataInicio']) && !empty($_GET['dataInicio'])) {
			$data1 = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['dataInicio'])));
		}
		
		// Verifica se a data fim foi definida
		if(isset($_GET['dataFim']) && !empty($_GET['dataFim'])) {
			$data2 = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['dataFim'])));
		}
		
		if(isset($_GET['periodoMes']) && isset($_GET['periodoAno'])) {
			
			if($_GET['periodoMes'] == '') {
				$data1 = $_GET['periodoAno'].'-01-01';
				$data2 = $_GET['periodoAno'].'-12-31';
			} else {
				$data1 = $_GET['periodoAno'].'-'.$_GET['periodoMes'].'-01';
				$data2 = $_GET['periodoAno'].'-'.$_GET['periodoMes'].'-31';
			}
		}
		
		$tag = '';
		
		// Pega a lista de pagamentos de salario
		//$pagamentoFuncionario = new PagamentoFuncionarioData();
		$lista = $pagamentoFuncionario->PegaListaPagamento($empresaId, $data1, $data2, $funcionarioId);
		
		// Pega a lista de pagamento de Ferias.
		$pagamentoFerias = new PagamentoFeriasData();
		$listaFerias = $pagamentoFerias->PegaListaPagamentoFerias($empresaId, $data1, $data2, $funcionarioId);
		
		$listArray = array();
		
		// For criado para mesclar os meses.
		for($i=12; $i > 0; $i--) {
			
			// Verifica se existe lista
			if($listaFerias) {
				// Percorre a lista e pega os pagamentos de ferias de acordo com o mes informado pelo for.
				foreach($listaFerias as $val) {

					$mesPagtoFerias = date('m', strtotime($val->getDataPagto()));	

					// Pega o total de vencimento.
					$valorBruto = $val->getValorFerias() + $val->getValorUmTercoFerias() + $val->getValorFeriasVendida() + $val->getValorUmTercoFeriasVendida();

					// Pega do array as linhas do array que o mês 
					if($i == $mesPagtoFerias) {				
						$listArray[] = array( 'feriasId'=>$val->getFeriasId()
											 , 'funcionarioNome'=>$val->getFuncionarioNome()
											 , 'tipoPagto'=>'reciboFerias'
											 , 'dataPagto'=>date('d/m/Y', strtotime($val->getDataPagto()))
											 , 'vencimentos'=>number_format($valorBruto, 2, ',', '.')
											 , 'valorINSS'=>number_format(($val->getValorSecundarioINSS() > 0 ? $val->getValorSecundarioINSS() : $val->getValorINSS()), 2, ',', '.')
											 , 'valorIR'=>number_format($val->getValorIR(), 2, ',', '.')
											 , 'descontos' => number_format(0, 2, ',', '.')
											 , 'valorLiquido'=>number_format($val->getValorliquido(), 2, ',', '.'));
					}
				}
			}

			// Verifica se existe lista
			if($lista) {
				// Percorre a lista e pega os pagamentos de acordo com o mes informado pelo for.
				foreach($lista as $val){ 

					$mesPagto = date('m', strtotime($val->getDataPagto()));				

					// Pega do array as linhas do array que o mês 
					if($i == $mesPagto) {
						
						$descPrimeiraParcela = 0;
						
						// Se a segunda parcela do decimo inclui o desconto da primeira parcela do decimo.
						if($val->getTipoPagto() == 'decimoTerceiro' && $val->getParcelaDecimo() == 'segunda') {
							$descPrimeiraParcela = $val->getValorBruto() / 2;
						}
						

						$desconto = $val->getValorVR() + $val->getValorVT() + $val->getValorFaltas() + $val->getValorIRFerias() + $val->getValorPensao() + $val->getLiquidoFerias() + $descPrimeiraParcela;

						$listArray[] = array( 'pagtoId'=>$val->getPagtoId()
											 ,'funcionarioNome'=>$val->getFuncionarioNome()
											 ,'tipoPagto'=>$val->getTipoPagto()
											 ,'dataPagto'=>date('d/m/Y', strtotime($val->getDataPagto()))
											 ,'vencimentos'=>number_format($val->getValorBruto(), 2, ',', '.')
											 ,'valorINSS'=>number_format($val->getValorINSS(), 2, ',', '.')
											 ,'valorIR'=>number_format($val->getValorIR(), 2, ',', '.')
											 ,'descontos' => number_format($desconto, 2, ',', '.')
											 ,'valorLiquido'=>number_format($val->getValorliquido(), 2, ',', '.')
											 ,'parcelaDecimo'=>$val->getParcelaDecimo());
					}
				}
			}
		}
				
		// Monata um array misturando os pagamento Salario e decimo mais férias.
		if($listArray) {
			
			foreach($listArray as $val){
				
				// Define o tipo de documento pagamento.
				if($val['tipoPagto'] == 'salario') {
					$tipoPagto = 'Salário';
				} elseif($val['tipoPagto'] == 'reciboFerias') {
					$tipoPagto = 'Recibo de Férias';
				} elseif($val['tipoPagto'] == 'decimoTerceiro') {
					
					if($val['parcelaDecimo'] == 'primeira') {
						$tipoPagto = '13° - Primeira parcela';
					} elseif($val['parcelaDecimo'] == 'segunda') {
						$tipoPagto = '13° - Segunda parcela';
					} else {
						$tipoPagto = '13° - Parcela única';
					}
				}
			
				if($val['tipoPagto'] == 'reciboFerias') {
				
					$tag .= "<tr>
						<td class='td_calendario'>
							<a style='cursor: pointer; text-decoration: none;' class='excluirPagtoFerias' data-feriasId='".$val['feriasId']."'>
								<i class='fa fa-trash-o iconesAzul iconesGrd'></i>
							</a>
							<a href='gerar_recibo_ferias.php?feriasId=".$val['feriasId']."' title='Salvar'>
								<i class='fa fa-cloud-download' aria-hidden='true' style='font-size: 20px;line-height: 20px;'></i>
							</a>
						</td>
						<td class='td_calendario'>".$val['funcionarioNome']."</td>
						<td class='td_calendario'>".$tipoPagto."</td>
						<td class='td_calendario' align='center'>".$val['dataPagto']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['vencimentos']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['valorINSS']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['valorIR']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['descontos']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['valorLiquido']."</td>
					</tr>";
					
				} else {
					
					$tag .= "<tr>
						<td class='td_calendario'>
							<a style='cursor: pointer; text-decoration: none;' class='excluirPagamento' data-pagtoId='".$val['pagtoId']."'>
								<i class='fa fa-trash-o iconesAzul iconesGrd'></i>
							</a>
							<a href='gerar_holerite_funcionario_pf.php?pagtoId=".$val['pagtoId']."' title='Salvar'>
								<i class='fa fa-cloud-download' aria-hidden='true' style='font-size: 20px;line-height: 20px;'></i>
							</a>
						</td>
						<td class='td_calendario'>".$val['funcionarioNome']."</td>
						<td class='td_calendario'>".$tipoPagto."</td>
						<td class='td_calendario' align='center'>".$val['dataPagto']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['vencimentos']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['valorINSS']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['valorIR']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['descontos']."</td>
						<td class='td_calendario' align='right'>R$ ".$val['valorLiquido']."</td>
					</tr>";
					
				}	
			}
		} else {
			$tag .= "<tr>
				<td class='td_calendario'>&nbsp;</td>
				<td class='td_calendario'>&nbsp;</td>
				<td class='td_calendario'>&nbsp;</td>
				<td class='td_calendario'>&nbsp;</td>
				<td class='td_calendario'>&nbsp;</td>
				<td class='td_calendario'>&nbsp;</td>
				<td class='td_calendario'>&nbsp;</td>
				<td class='td_calendario'>&nbsp;</td>
				<td class='td_calendario'>&nbsp;</td>
			</tr>";
		}
		
		return $tag;
	}
		
	public function ExcluiPagamento($empregadoId, $pagtoId, $tipoPagto) {

		switch($tipoPagto) {
			case 'holerite':
				
				// Instâcina a classe responsavel por manipular os dados do pagamento do funcionario. 
				$pagamentoFuncionario = new PagamentoFuncionarioData();
				
				// Chama o metodo para realizar a exclusão do pagamento.
				$pagamentoFuncionario->ExcluiDadosPagamentoFuncionario($empregadoId, $pagtoId);
				
				break;
			case 'ferias':	

				// Pega a lista de pagamento de Ferias.
				$pagamentoFerias = new PagamentoFeriasData();
				
				// Chama o método para realizar a exclusão do pagamento de Ferias.
				$pagamentoFerias->ExcluiDadosPagamentoFuncionario($empregadoId, $pagtoId);				
				break;	
		}

		//Realiza o redirecionamento da página.
		header('location: '.$_SERVER['REQUEST_URI']);
			
		die();
	}
}