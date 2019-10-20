<script type="text/javascript" src="./scripts/jquery.min.js"></script>
<?php include 'conect.php'; ?>
<?php 
	
	function calcularDataApuracao($data){
		$datas = new Datas();

		$aux_data_vencimento = explode(' ', $data);
		$aux_data_vencimento = explode('-',$aux_data_vencimento[0]);

		$dia = $aux_data_vencimento[2]; 

		$intervalo = '';

		if( $dia >= 1 && $dia <= 10 ){
			return $datas->getAno($data).'-'.$datas->getMes($data).'-10';
		}
		if( $dia >= 11 && $dia <= 20 ){
			return $datas->getAno($data).'-'.$datas->getMes($data).'-20';
		}
		if( $dia >= 21 ){
			return $datas->ultimoDiaMes($data);
		}

	}

	function calcularDataVencimento($data){

		$datas = new Datas();

		$aux_data_vencimento = explode(' ', $data);
		$aux_data_vencimento = explode('-',$aux_data_vencimento[0]);

		$dia = $aux_data_vencimento[2]; 

		$intervalo = '';

		if( $dia >= 1 && $dia <= 10 ){
			$intervalo = '10';

		}
		if( $dia >= 11 && $dia <= 20 ){
			$intervalo = '20';

		}
		if( $dia >= 21 ){
			$intervalo = '1';
			$data_aux = $aux_data_vencimento[0].'-'.$aux_data_vencimento[1].'-20';
			$data = $datas->somarData($data_aux,30);
			$aux_data_vencimento = explode(' ', $data);
			$aux_data_vencimento = explode('-',$aux_data_vencimento[0]);
		}

		

		$data_aux = $aux_data_vencimento[0].'-'.$aux_data_vencimento[1].'-'.$intervalo;

		return $datas->desconverterData($datas->somarDiasUteis($data_aux,3));

	}
	function calcularIRRF($item){

		$datas = new Datas();

		$data = explode(' ', $item['data']);
		$data = explode('-', $data[0]);
		$ano = $data[0];

		$valorIR = floatval($item['juros']) * floatval($item['valor']);

		$consulta = mysql_query("SELECT * FROM tabelas WHERE ano_calendario = '".$ano."' ");
		$objeto=mysql_fetch_array($consulta);

		$dias = $datas->diferencaData($item['data_pagamento'],$item['data']);

		if( $dias <= 180 ){

			$aliquota = 22.5;
			$desconto = 0;

		}
		else if( $dias <= 360 ){
			
			$aliquota = 20;
			$desconto = 0;

		}
		else if( $dias <= 720 ){
			
			$aliquota = 17.5;
			$desconto = 0;

		}
		else{
			
			$aliquota = 15;
			$desconto = 0;

		} 

		$valorIR = $valorIR * $aliquota/100 - $desconto;
		// $valorIR = number_format($valorIR,2,',','.');


		// $valorIR = $valorIR * $aliquota/100 - $desconto;
		if( $valorIR == 0 )
			$valorIR = "Isento";
		else
			$valorIR = 'R$ '.number_format($valorIR,2,',','.');

		return $valorIR;

	}
	function calcularIOF($item){
		// echo $item['tipo'];
		$data = new Datas();
		$dataInicio = explode(' ', $item['data']);
		$dataInicio = $dataInicio[0];
		$dataFim = explode(' ', $item['data_pagamento']);
		$dataFim = $dataFim[0];
		$dias = $data->diferencaData($dataFim,$dataInicio);

		$valor = $item['valor'];

		//Define a aliquota em relação ao valor e tipo de emprestimo(tipo = PF->PJ ou PJ->PF)
		if( $item['tipo'] == 1 && $valor > 30000 ){
			$aliquota_dia_iof = 0.000041;
		}
		else if( $item['tipo'] == 2 && $valor > 30000 ){
			$aliquota_dia_iof = 0.000082;
		}
		else if( $item['tipo'] == 2 && $valor <= 30000 ){
			$aliquota_dia_iof = 0.000082;	
		}
		else if( $item['tipo'] == 1 && $valor <= 30000 ){
			$aliquota_dia_iof = 0.0000137;	
		}
		//multiplica o numero de dias do emprestimo pela aliquota, determinada pelo valor e tipo de emprestimo(tipo = PF->PJ ou PJ->PF)

		$aux = floatval($dias * $aliquota_dia_iof);
		if( $aux > 0.015 )
			$aux = 0.015;

		//Define o valor do IFO -> // [(dias * aliquota) + 0.38%] * valor
		$valor = (0.0038 + $aux) * $valor;

		if( $item['tipo'] == 1 )
			return number_format( 0 , 2 , ',' , '.');
		return number_format( $valor , 2 , ',' , '.');

	}
	session_start();
	include 'datas.class.php';	
	$data = new Datas();		

	$valor = floatval(str_replace(",",".",str_replace(".","",$_POST['valor'])));
	$data1 = $data->converterData($_POST['data1']);

	$id = $_GET['id'];
	$id_user = $_GET['id_user'];

	$consulta = mysql_query("SELECT * FROM emprestimos WHERE id = '".$id."' AND id_user = '".$id_user."' ");
	$objeto=mysql_fetch_array($consulta);

	$aux_data = explode(' ', $objeto['data']);

	$data_apuracao = $data->desconverterData(calcularDataApuracao($objeto['data']));
	$cpf_cnpj = '';
	if( $objeto['tipo'] == 1 )
		$codigo_receita = '1150';	
	else
		$codigo_receita = '7893';	
	$numero_referencia = '';
	$data_vencimento = calcularDataVencimento($objeto['data']);
	$valor_principal = number_format($objeto['valor'],2,',','.');
	$valor_principal = calcularIOF($objeto);
	$valor_multa = '';
	$valor_juros = '';
	$valor_total = $valor_principal;
	$autenticacao = '';

	// if( $objeto['tipo'] == 2 ){

	// 	$nome = $objeto['nome'];
	// 	$telefone = '';
	// 	$cpf_cnpj = $objeto['cpf'];

	// }
	// else{
		
	$consulta = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$_SESSION['id_empresaSecao']."' ");
	$objeto=mysql_fetch_array($consulta);

	$nome = $objeto['razao_social'];
	$telefone = '('.$objeto['pref_telefone'].') '.$objeto['telefone'];

	$cpf_cnpj = $objeto['cnpj'];

	// }

	

?>
<style type="text/css">
	body {
		margin: 10;
		padding: 0;
		font-size: 11px;
		font-family: Arial;
	}
	p {
		margin: 0;
	}
	table.darf {
		border-collapse: collapse;
		border: 0.1mm solid #000;
		max-width: 800px;
	}
	table.darf tr {
		border: 0.1mm solid #000;
	}
	table.darf tr td {
		border: 0.1mm solid #000;
	}
	strong {
		font-size: 14px;
	}
	span.titulo {
		font-size: 9px;
	}
</style>
<title>Contador Amigo - DARF</title>	
<center>
	<img src="https://comercio.locaweb.com.br/LocaWebCE/boleto/images/bt_imprimir.gif" class="imprimir" style="cursor:pointer;float: right;">
	<br><br>
	<table class="darf" width="100%" style="font-size: 12px;">
		<tbody>
			<tr>
				<td width="48%" rowspan="3" valign="top">
					<table border="0" style="font-size: 12px;;border: 0mm solid #000;" width="100%">
						<tbody>
							<tr>
								<td style="border:0;">
									<img src="images/brasao-brasil.gif" alt="Brasão Brasil" style="float: left;">
								</td>
								<td style="border:0;">
									<p>MINISTÉRIO DA FAZENDA</p>
									<p>Secretaria da Receita Federal do Brasil</p>
									<p>Documento de Arrecadação de Receitas Federais</p>
									<p><strong>DARF</strong></p>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="22%" valign="top">
					<p><strong>02</strong> <span class="titulo">PERÍODO DE APURAÇÃO</span></p>
				</td>
				<td width="30%" valign="top" align="right">
					<p><?php echo $data_apuracao; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>03</strong> <span class="titulo">NÚMERO DO CPF OU CNPJ</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $cpf_cnpj; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>04</strong> <span class="titulo">CÓDIGO DA RECEITA</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $codigo_receita; ?></p>
				</td>
			</tr>
			<tr>
				<td rowspan="3" valign="top">
					<p><strong>01</strong> <span class="titulo">NOME / TELEFONE</span></p>
					<br>
					<p><?php echo $nome ?></p>
					<p><?php echo $telefone ?></p>
				</td>
				<td valign="top">
					<p><strong>05</strong> <span class="titulo">NÚMERO DE REFERÊNCIA</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $numero_referencia; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>06</strong> <span class="titulo">DATA DE VENCIMENTO</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $data_vencimento; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>07</strong> <span class="titulo">VALOR DO PRINCIPAL</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $valor_principal; ?></p>
				</td>
			</tr>
			<tr>
				<td rowspan="3" valign="top">
					<p></p>
				</td>
				<td valign="top">
					<p><strong>08</strong> <span class="titulo">VALOR DA MULTA</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $valor_multa; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>09</strong> <span class="titulo">VALOR DOS JUROS</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $valor_juros; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>10</strong> <span class="titulo">VALOR TOTAL</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $valor_total; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top" style="padding: 10px;">
					<p></p><center><strong>ATENÇÃO</strong></center><p></p>
					<p>É vedado o recolhimento de tributos Contribuições administrados pela Secretaria da Receita Federal cujo valor total seja inferior a R$ 10,00. Ocorrendo tal situação adicione tal valor ao tributo/contribuição de mesmo código de períodos subsequentes, até que o total seja igual ou superior a R$ 10,00.</p>
				</td>
				<td valign="top" colspan="2">
					<p><strong>11</strong> <span class="titulo">AUTENTICAÇÃO</span></p>
					<p><?php echo $autenticacao; ?></p>
				</td>
			</tr>
		</tbody>
	</table>
		
	<br><br><br><br>
		
	<table class="darf" width="100%" style="font-size: 11px;">
		<tbody>
			<tr>
				<td width="48%" rowspan="3" valign="top">
					<table border="0" style="font-size: 11px;;border: 0mm solid #000;" width="100%">
						<tbody>
							<tr>
								<td style="border:0;">
									<img src="images/brasao-brasil.gif" alt="Brasão Brasil" style="float: left;">
								</td>
								<td style="border:0;">
									<p>MINISTÉRIO DA FAZENDA</p>
									<p>Secretaria da Receita Federal do Brasil</p>
									<p>Documento de Arrecadação de Receitas Federais</p>
									<p><strong>DARF</strong></p>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="22%" valign="top">
					<p><strong>02</strong> <span class="titulo">PERÍODO DE APURAÇÃO</span></p>
				</td>
				<td width="30%" valign="top" align="right">
					<p><?php echo $data_apuracao; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>03</strong> <span class="titulo">NÚMERO DO CPF OU CNPJ</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $cpf_cnpj; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>04</strong> <span class="titulo">CÓDIGO DA RECEITA</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $codigo_receita; ?></p>
				</td>
			</tr>
			<tr>
				<td rowspan="3" valign="top">
					<p><strong>01</strong> <span class="titulo">NOME / TELEFONE</span></p>
					<br>
					<p><?php echo $nome ?></p>
					<p><?php echo $telefone ?></p>
				</td>
				<td valign="top">
					<p><strong>05</strong> <span class="titulo">NÚMERO DE REFERÊNCIA</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $numero_referencia; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>06</strong> <span class="titulo">DATA DE VENCIMENTO</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $data_vencimento; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>07</strong> <span class="titulo">VALOR DO PRINCIPAL</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $valor_principal; ?></p>
				</td>
			</tr>
			<tr>
				<td rowspan="3" valign="top">
					<p></p>
				</td>
				<td valign="top">
					<p><strong>08</strong> <span class="titulo">VALOR DA MULTA</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $valor_multa; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>09</strong> <span class="titulo">VALOR DOS JUROS</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $valor_juros; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>10</strong> <span class="titulo">VALOR TOTAL</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo $valor_total; ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top" style="padding: 10px;">
					<p></p><center><strong>ATENÇÃO</strong></center><p></p>
					<p>É vedado o recolhimento de tributos Contribuições administrados pela Secretaria da Receita Federal cujo valor total seja inferior a R$ 10,00. Ocorrendo tal situação adicione tal valor ao tributo/contribuição de mesmo código de períodos subsequentes, até que o total seja igual ou superior a R$ 10,00.</p>
				</td>
				<td valign="top" colspan="2">
					<p><strong>11</strong> <span class="titulo">AUTENTICAÇÃO</span></p>
					<p><?php echo $autenticacao; ?></p>
				</td>
			</tr>
		</tbody>
	</table>
</center>
<style type="text/css" media="print">
	.imprimir{
		display: none;
	}
</style>
<script>

	
	$( ".imprimir" ).click(function() {
	    
		window.print();
	    
	});

</script>