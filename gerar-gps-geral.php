<script type="text/javascript" src="./scripts/jquery.min.js"></script>
<?php include 'conect.php'; ?>
<?php 
	
	session_start();	
	//Função que calcula a data de vencimento da GUIA
	function calcularDataVencimento($data){
		$datas = new Datas();			
		$data_aux = explode('-', $data);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';
		$aux = $datas->somarData($data_aux,30);
		$data_aux = explode('-',$aux);
		$mes = $data_aux[1];
		$ano = $data_aux[0];
		$data_vencimento = $data_aux[0].'-'.$data_aux[1].'-20';
		if( isset($_GET['converter']) )
			return $datas->converterData($_GET['data2']);
		return $data_vencimento;
	}
	//Função que calcula a data de apuração da GUIA
	function calcularDataApuracao($data){
		$datas = new Datas();
		$data_aux = explode('-', $data);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';
		$aux = $datas->somarData($data_aux,30);
		$data_aux = explode('-', $aux);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-01';
		$aux = $datas->subtrairData($data_aux,1);
		return $aux;
	}
	//Função que calcula a multa de acordo com a data de vencimento e a data que será realizado o pagamento e retorna o valor
	function calcularMultaMora($valor,$data){
		$datas = new Datas();
		//Define a data de vencimento original como dia 20 do mes seguinte à data de apuração, o calculo doas dias é feito assim: nova data de vencimento - Data de vencimento original(iniciando sempre em dia util)
		$data_aux = explode('-', $data);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';
		$aux = $datas->somarData($data_aux,30);
		$data_aux = explode('-',$aux);
		$mes = $data_aux[1];
		$ano = $data_aux[0];
		$data_vencimento = $data_aux[0].'-'.$data_aux[1].'-20';
		$fim_de_semana = 0;
		
		//Se dia 20 cai em feriado ou fim de semana, leva até o primeiro dia util anterior 
		while( !$datas->ifDiaUtil($data_vencimento) ){
			$data_vencimento = $datas->subtrairData($data_vencimento,1);
		}
		
		// Soma mais um dia para descobrir quando começa a contar a multa.
		$data_vencimento = $datas->somarData($data_vencimento,1);

		// Se a multa cair em ferado ou fina de sema leva ate primeiro dia util sub sequente.
		while( !$datas->ifDiaUtil($data_vencimento) ) {
			$data_vencimento = $datas->somarData($data_vencimento,1);
		}

		// Subtrai um dia para fazer a contagem correta dos dias de mora.
		$data_vencimento = $datas->subtrairData($data_vencimento,1);
		
		//Pega os dias entre as datas para calculo do juros
		$dias = $datas->diferencaData($datas->converterData($_GET['data2']),$data_vencimento);

		$mora = 0;
		if( $dias > 0 ){	
			$juros = $dias * 0.0033;
			if( $juros > 0.2 ){
				$mora = floatval($valor) * 0.2;
			}
			else{
				$mora = $juros * floatval($valor);
			}
		}
		return $mora;
	}

	// Método criado para presevar qunatidade de casa sem arredondar. 
	function DecimalSemArredondar($number, $qtdCasa) {
		
		$valorAux = "";
		$valor = $number;
		
		$numberTmp = explode('.', $number);
		
		if(isset($numberTmp[0])){
			$valorAux = $numberTmp[0];
		}
			
		if(isset($numberTmp[1])){
			$valorAux = floatval($valorAux.'.'.substr($numberTmp[1], 0, 2));
		}		
		
		if($valorAux) {
			$valor = $valorAux;
		}
						
		return $valor;
	}  

	function PegaDataVencimentoReal($dataVencimento){
	
		$datas = new Datas();
		
		//Se dia 20 cai em feriado ou fim de semana, leva até o primeiro dia util anterior 
		while( !$datas->ifDiaUtil($dataVencimento) ){
			$dataVencimento = $datas->subtrairData($dataVencimento,1);
		}
		
		return $dataVencimento;
	}

	//Função que calcula a mora de acordo com a data de vencimento e a data que será realizado o pagamento e retorna o valor
	function calcularJurosMora($valor,$data,$data2){
		$datas = new Datas();
		//Vai para o dia original de vencimento baseado na data de apuração. O dia e sempre 20/mes+1/ano, -1dia se dia nao for dia util
		$data_aux = explode('-', $data);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';
		$aux = $datas->somarData($data_aux,30);
		$data_aux = explode('-',$aux);
		$mes = intval($data_aux[1]) + 1;
		$ano = $data_aux[0];
		$data_vencimento = $data_aux[0].'-'.$data_aux[1].'-20';
				
		$dias = $datas->diferencaData($data2,$data_vencimento);
		
		// Pega data do vencimento.
		$Vencimento = PegaDataVencimentoReal($data_vencimento);

		// Pega data do pagamento.
		$Pagamento = date('Y-m-d',strtotime($data2));		
		
		//Pega o mes do pagamento
		$mes_final = explode('-', $data2);
		//Define o ultimo mes de cobrancade  juros como mes -1
		$mes_final = intval($mes_final[1]) - 1;
		$juros = 0;
		$valor_selic = 0;
		$periodo = array();
		
		// O Juros só devera ser aplicado apos no proximo mês apos o vencimento.
		// Verifica se o mes de pagamento e maior que o mês de vencimento.
		if( $Pagamento > $Vencimento ){
			
			//Pega cada mes entre o inicial e o final - 1 
			while ($datas->diferencaData($data_vencimento,$datas->converterData($_GET['data2'])) < - 30 ){
				//Percore de 30 em 30 dias para pegar cada mes entre os meses de pagamento original e o mes -1 do novo pagamento
				$aux1 = explode('-', $data_vencimento);
				$data_vencimento = $aux1[0].'-'.$aux1[1].'-20';
				$data_vencimento = $datas->somarData($data_vencimento,30);
				$aux = explode('-', $data_vencimento);
				$meses = $aux[1];
				$ano = $aux[0];
				//Caso nao tenhamos aplicado juros ao mes corrente, aplica e marca como aplicado para evitar duplicidade
				if( $periodo[$meses.$ano] != 'usado' ){
					//Busca a taxa selic no mes e ano
					$consulta = mysql_query("SELECT * FROM selic WHERE ano = '".$ano."' AND mes = '".$meses."' ");
					//Marc o mes/ano como contabilizado
					$periodo[$meses.$ano] = 'usado';
					$objeto=mysql_fetch_array($consulta);
					$valor_selic = $objeto['valor'];
					//Acumula o juros
					$juros = $juros + floatval(str_replace(',','.',$valor_selic))/100 * $valor;
				}
			}
			if( $mes < $mes_final + 2 )
				$juros = ($juros) + 0.01 * $valor;//Soma o juros (selic + 10%) do valor total
			else
				$juros = ($juros) + 0.01 * $valor;//Soma o juros (selic + 10%) do valor total
		}

		// Preserva 2 casas sem arredondar.
		$juros = DecimalSemArredondar($juros, 2);
		
		return $juros;
	}
	//Calcula o valor total da guia, somando valor original + multa + mora
	function calcularTotal($valor,$juros,$multa){
		
		return floatval($valor) + $juros + $multa;
	}

	include 'datas.class.php';
	$datas = new Datas();
	$codigo_receita = $_GET['codigo_receita'];
	//Pega a data da apuração do pagamento
	$data_apuracao = $datas->desconverterData(calcularDataApuracao($datas->converterData($_GET['data'])));
	$cpf_cnpj = '';
	//Pega a data em que o usuario pretende realizar o pagamento
	$data_vencimento = $datas->desconverterData(calcularDataVencimento($datas->converterData($_GET['data'])));
	//Pega o valor original da guia
	$valor_principal = number_format(str_replace(',', '.', str_replace('.', '', str_replace(',', '.', str_replace('.', '', $_GET['valor'])))),2,',','.');
	//Calcula o valor da multa
	$valor_multa = calcularMultaMora(str_replace(',', '.', str_replace('.', '', $_GET['valor'])),$datas->converterData($_GET['data']));
	//Normaliza a multa para : arredondamento para baixo com duas casas decimais
	$valor_multa = floor(($valor_multa * 100))/100;

	//Calcula o valor do juros
	$valor_juros = calcularJurosMora(str_replace(',', '.', str_replace('.', '', $_GET['valor'])),$datas->converterData($_GET['data']),$datas->converterData($_GET['data2']));

	//Normaliza a multa para : arredondamento para baixo com duas casas decimais
	//$valor_juros = floor(($valor_juros	 * 100))/100;

	//calcula o valor total
	$valor_total = number_format(str_replace(',', '.', str_replace('.', '', $_GET['valor'])),2,',','.');
	//Busca os dadosda empresa para preenchimento ad guia
	$consulta = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$_SESSION['id_empresaSecao']."' ");
	$objeto=mysql_fetch_array($consulta);

	//Define o Identificador = CNPJ
	$identificador = $objeto['cnpj'];
	//Define a Razão Social
	$nome = $objeto['razao_social'];
	//Define o telefone
	$telefone = '('.$objeto['pref_telefone'].') '.$objeto['telefone'];
	//Define o CNPJ
	$cpf_cnpj = $objeto['cnpj'];
	//Define o endereço
	$endereco = $objeto['endereco'];
	//Define a cidade
	$cidade = $objeto['cidade'];
	//Define o estado: UF
	$estado = $objeto['estado'];
	//Define o CEP
	$cep = $objeto['cep'];

	// }
	

?>

<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<title>GPS - Guia da Previdência Social</title>
	<link href="/PortalSalInternet/css/gps.css" rel="stylesheet" type="text/css">
	<link href="/PortalSalInternet/css/gpsImpressao.css" rel="stylesheet" type="text/css" media="print">
</head>

<body>
	<img src="https://comercio.locaweb.com.br/LocaWebCE/boleto/images/bt_imprimir.gif" class="imprimir" style="cursor:pointer;float: right;">
	<div align="center">
		<div class="gps">
			<table class="gpsTableInterna" cellspacing="1" cellpadding="3">
				<tbody>
					<tr>
						<td colspan="2" rowspan="3">
							<img class="gpsLogoPrevidencia" src="http://www2.dataprev.gov.br/PortalSalInternet/images/gps/MpasLogo.gif">
							<div class="gpsTitulo center">MINISTÉRIO DA PREVIDÊNCIA SOCIAL - MPS<br>INSTITUTO NACIONAL DO SEGURO SOCIAL - INSS<br>SECRETARIA DA RECEITA PREVIDENCIÁRIA - SRP<br>
								<br><span class="gpsTitulo negrito">GUIA DA PREVIDÊNCIA SOCIAL - GPS</span>
							</div>
						</td>
						<td class="gpsCelulaLabelValor">3 - CÓDIGO DE PAGAMENTO
						</td>
						<td class="gpsCelulaValor gpsValor center"><?php echo $codigo_receita; ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">4 - COMPETÊNCIA
						</td>
						<td class="gpsCelulaValor gpsValor center"><?php echo $datas->getMes($datas->converterData($_GET['data'])).'/'.$datas->getAno($datas->converterData($_GET['data'])); ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">5 - IDENTIFICADOR
						</td>
						<td class="gpsCelulaValor gpsValor center"><?php echo $identificador; ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaDadosContribuinte" colspan="2" rowspan="3">1 - NOME OU RAZÃO SOCIAL / FONE / ENDEREÇO<br>
							<span class="gpsDadosContribuinte left">
								<span class="gpsDadosContribuinte negrito">CNPJ <?php echo $cpf_cnpj; ?></span>
								<br><?php echo $nome; ?>
								<br><?php echo $endereco; ?>
								<br><?php echo $cidade; ?> - <?php echo $estado; ?>
								<br><?php echo $cep; ?>
							</span>
						</td>
						<td class="gpsCelulaLabelValor">6 - VALOR DO INSS
						</td>
						<td class="gpsCelulaValor gpsValor right"><?php echo $valor_total; ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">7 -
						</td>
						<td>&nbsp;
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">8 -
						</td>
						<td>&nbsp;
						</td>
					</tr>
					<tr>
						<td calss="left" width="20%">2 - VENCIMENTO<br>(Uso exclusivo INSS)
						</td>
						<td class="gpsValor center"><?php echo $data_vencimento; ?>
						</td>
						<td class="gpsCelulaLabelValor">9 - VALOR OUTRAS ENTIDADES
						</td>
						<td class="gpsCelulaValor gpsValor right">0,00
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="2"><span class="negrito">ATENÇÃO:</span>É vedada a utilização de GPS para recolhimento de receita de valor inferior ao estipulado em resolução publicada pelo INSS. A receita que resultar valor inferior deverá ser adicionada à contribuição ou importância correspondente nos meses subsequentes, até que o total seja igual ou superior ao valor mínimo fixado.
						</td>
						<td class="gpsCelulaLabelValor">10 - ATM/MULTA E JUROS
						</td>
						<td class="gpsCelulaValor gpsValor right"><?php echo number_format( $valor_multa + $valor_juros , 2 , ',' , '.' ); ; ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">11 - TOTAL
						</td>
						<td class="gpsCelulaValor gpsValor right"><?php echo number_format(calcularTotal(str_replace(',', '.', str_replace('.', '', $_GET['valor'])),$valor_juros,$valor_multa),2,',','.'); ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaAutenticacaoBancaria right" colspan="4" rowspan="2">AUTENTICAÇÃO BANCÁRIA
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="gps">
			<table class="gpsTableInterna" cellspacing="1" cellpadding="3">
				<tbody>
					<tr>
						<td colspan="2" rowspan="3">
							<img class="gpsLogoPrevidencia" src="http://www2.dataprev.gov.br/PortalSalInternet/images/gps/MpasLogo.gif">
							<div class="gpsTitulo center">MINISTÉRIO DA PREVIDÊNCIA SOCIAL - MPS<br>INSTITUTO NACIONAL DO SEGURO SOCIAL - INSS<br>SECRETARIA DA RECEITA PREVIDENCIÁRIA - SRP<br>
								<br><span class="gpsTitulo negrito">GUIA DA PREVIDÊNCIA SOCIAL - GPS</span>
							</div>
						</td>
						<td class="gpsCelulaLabelValor">3 - CÓDIGO DE PAGAMENTO
						</td>
						<td class="gpsCelulaValor gpsValor center"><?php echo $codigo_receita; ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">4 - COMPETÊNCIA
						</td>
						<td class="gpsCelulaValor gpsValor center"><?php echo $datas->getMes($datas->converterData($_GET['data'])).'/'.$datas->getAno($datas->converterData($_GET['data'])); ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">5 - IDENTIFICADOR
						</td>
						<td class="gpsCelulaValor gpsValor center"><?php echo $identificador; ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaDadosContribuinte" colspan="2" rowspan="3">1 - NOME OU RAZÃO SOCIAL / FONE / ENDEREÇO<br>
							<span class="gpsDadosContribuinte left">
								<span class="gpsDadosContribuinte negrito">CNPJ <?php echo $cpf_cnpj; ?></span>
								<br><?php echo $nome; ?>
								<br><?php echo $endereco; ?>
								<br><?php echo $cidade; ?> - <?php echo $estado; ?>
								<br><?php echo $cep; ?>
							</span>
						</td>
						<td class="gpsCelulaLabelValor">6 - VALOR DO INSS
						</td>
						<td class="gpsCelulaValor gpsValor right"><?php echo $valor_total; ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">7 -
						</td>
						<td>&nbsp;
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">8 -
						</td>
						<td>&nbsp;
						</td>
					</tr>
					<tr>
						<td calss="left" width="20%">2 - VENCIMENTO<br>(Uso exclusivo INSS)
						</td>
						<td class="gpsValor center"><?php echo $data_vencimento; ?>
						</td>
						<td class="gpsCelulaLabelValor">9 - VALOR OUTRAS ENTIDADES
						</td>
						<td class="gpsCelulaValor gpsValor right">0,00
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="2"><span class="negrito">ATENÇÃO:</span>É vedada a utilização de GPS para recolhimento de receita de valor inferior ao estipulado em resolução publicada pelo INSS. A receita que resultar valor inferior deverá ser adicionada à contribuição ou importância correspondente nos meses subsequentes, até que o total seja igual ou superior ao valor mínimo fixado.
						</td>
						<td class="gpsCelulaLabelValor">10 - ATM/MULTA E JUROS
						</td>
						<td class="gpsCelulaValor gpsValor right"><?php echo number_format( $valor_multa + $valor_juros , 2 , ',' , '.' ); ; ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaLabelValor">11 - TOTAL
						</td>
						<td class="gpsCelulaValor gpsValor right"><?php echo number_format(calcularTotal(str_replace(',', '.', str_replace('.', '', $_GET['valor'])),$valor_juros,$valor_multa),2,',','.'); ?>
						</td>
					</tr>
					<tr>
						<td class="gpsCelulaAutenticacaoBancaria right" colspan="4" rowspan="2">AUTENTICAÇÃO BANCÁRIA
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
<form id="formBotoes" name="formBotoes" method="post" action="/PortalSalInternet/faces/pages/calcContribuicoesEmpresasEOrgaosPublicos/emissaoGps.xhtml" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="formBotoes" value="formBotoes">
<input type="hidden" name="DTPINFRA_TOKEN" value="1475784525388"><input type="hidden" value="r9f3t7n7" id="captcha413x" name="captcha413x">
	  	
		<div class="boxBotoes"><input id="formBotoes:botaoSecundarioVoltar" type="submit" name="formBotoes:botaoSecundarioVoltar" value="Voltar" title="Voltar à tela anterior" class="botaoSecundario"><input id="formBotoes:botaoSecundarioInicio" type="submit" name="formBotoes:botaoSecundarioInicio" value="Retornar ao início" title="Retornar à tela inicial do cálculo de contribuições de empresa e órgão público" class="botaoSecundario">
		</div><input type="hidden" name="javax.faces.ViewState" id="javax.faces.ViewState" value="j_id42:j_id52">
</form>

</body>
</html>

<!-- <img src="https://comercio.locaweb.com.br/LocaWebCE/boleto/images/bt_imprimir.gif" class="imprimir" style="cursor:pointer;float: right;"> -->
<style type="text/css" media="screen">
	/* Folha de estilos responsável pela formatação dos itens na página da GPS comum, tanto para impressão como para tela */

img.imgRecorte {
	margin-top: 40px;
	width: 640px;
	height: 18px;
}

div.gps {
	/*margin-top: 40px;*/
	/*width: 640px;*/
	margin-top: 20px;
	float: left;
}

div.gps * {
	font-family: "ERIE", "TIMES NEW ROMAN";
	font-size: 10px;
}

img.gpsLogoPrevidencia {
	float: left;
}

img.viasGps {
	float: left;
	margin-top: 185px;
}

div.gpsTitulo {
	float: right;
	padding-right: 5px;
}

span.gpsTitulo {
	font-size: 13px;
}

table.gpsTableInterna {
	border: 1px solid;
	width: 700px;
}

table.gpsTableInterna td {
	border: 1px solid;
}

td.gpsCelulaLabelValor {
	height: 30px;
	width: 16%;
}

td.gpsCelulaValor {
	width: 23%;
}

td.gpsCelulaAutenticacaoBancaria {
	padding-bottom: 60px;
}

td.gpsCelulaDadosContribuinte {
	vertical-align: top;
}

td.gpsValor {
	font-family: sans-serif;
	font-size: 14px;
}

span.gpsDadosContribuinte {
	font-family: sans-serif;
}

.negrito {
	font-weight: bold !important;
}

/* Os estilos 'left', 'right' e 'center' são usados para o alinhamento do texto */
.left {
	text-align: left;
}
.right {
	text-align: right;
}
.center {
	text-align: center;
}

/* 
 * botaoPrincipal: usado para botões que realizam a operação principal da página 
 * botaoSecundario: usado para botões com operações alternativas
 */
.botaoPrincipal, .botaoSecundario, .botaoDesabilitado { 
	height: 21px;
	color: #FFFFFF;
	cursor: pointer;
	font-size: 0.9em;
	font-weight: bold;
	margin: 0 2px 0 2px;
	padding: 0 5px 0 5px;
	vertical-align: middle;
	background-color: #00008B;
	border: 1px solid #216d91;
}
.botaoPrincipal:hover {
	background-color: #87CEEB;
}
.botaoSecundario {
	color: #00008B;
	background-color: #FFFFFF;
}
.botaoSecundario:hover {
	background-color: #EFEFEF;
}
.botaoDesabilitado {
    color: #a7a79b;
    cursor: default;
    background-color: #f4f4e8;
    border: 1px solid #a7a79b;
}
div.boxBotoes {
	margin: 10px;
	text-align: center;
	font-family: Arial,Helvetica,sans-serif;
    font-size: 11px;
}
/* Folha de estilos responsável pela formatação dos itens na página da GPS impressa */

.quebra {
	page-break-before: always;
}

div.boxBotoes {
	display: none;
}

</style>
<style type="text/css" media="print">
	.imprimir{
		display: none;
	}
	<style type="text/css" media="screen">
	/* Folha de estilos responsável pela formatação dos itens na página da GPS comum, tanto para impressão como para tela */

img.imgRecorte {
	margin-top: 40px;
	width: 640px;
	height: 18px;
}

div.gps {
	/*margin-top: 40px;*/
	/*width: 640px;*/
}

div.gps * {
	font-family: "ERIE", "TIMES NEW ROMAN";
	font-size: 10px;
}

img.gpsLogoPrevidencia {
	float: left;
}

img.viasGps {
	float: left;
	margin-top: 185px;
}

div.gpsTitulo {
	float: right;
	padding-right: 5px;
}

span.gpsTitulo {
	font-size: 13px;
}

table.gpsTableInterna {
	border: 1px solid;
	width: 670px;
}

table.gpsTableInterna td {
	border: 1px solid;
}

td.gpsCelulaLabelValor {
	height: 30px;
	width: 16%;
}

td.gpsCelulaValor {
	width: 23%;
}

td.gpsCelulaAutenticacaoBancaria {
	padding-bottom: 60px;
}

td.gpsCelulaDadosContribuinte {
	vertical-align: top;
}

td.gpsValor {
	font-family: sans-serif;
	font-size: 14px;
}

span.gpsDadosContribuinte {
	font-family: sans-serif;
}

.negrito {
	font-weight: bold !important;
}

/* Os estilos 'left', 'right' e 'center' são usados para o alinhamento do texto */
.left {
	text-align: left;
}
.right {
	text-align: right;
}
.center {
	text-align: center;
}

/* 
 * botaoPrincipal: usado para botões que realizam a operação principal da página 
 * botaoSecundario: usado para botões com operações alternativas
 */
.botaoPrincipal, .botaoSecundario, .botaoDesabilitado { 
	height: 21px;
	color: #FFFFFF;
	cursor: pointer;
	font-size: 0.9em;
	font-weight: bold;
	margin: 0 2px 0 2px;
	padding: 0 5px 0 5px;
	vertical-align: middle;
	background-color: #00008B;
	border: 1px solid #216d91;
}
.botaoPrincipal:hover {
	background-color: #87CEEB;
}
.botaoSecundario {
	color: #00008B;
	background-color: #FFFFFF;
}
.botaoSecundario:hover {
	background-color: #EFEFEF;
}
.botaoDesabilitado {
    color: #a7a79b;
    cursor: default;
    background-color: #f4f4e8;
    border: 1px solid #a7a79b;
}
div.boxBotoes {
	margin: 10px;
	text-align: center;
	font-family: Arial,Helvetica,sans-serif;
    font-size: 11px;
}
/* Folha de estilos responsável pela formatação dos itens na página da GPS impressa */

.quebra {
	page-break-before: always;
}

div.boxBotoes {
	display: none;
}
</style>
<script>

	
	$( ".imprimir" ).click(function() {
	    
		window.print();
	    
	});

</script>