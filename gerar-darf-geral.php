<script type="text/javascript" src="./scripts/jquery.min.js"></script>
<?php include 'conect.php'; ?>
<?php 

	session_start();	

	function calcularDataVencimentoAux($data){
		$datas = new Datas();
		$data_aux = explode('-', $data);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';
		$aux = $datas->somarData($data_aux,30);
		$data_aux = explode('-',$aux);
		$mes = $data_aux[1];
		$ano = $data_aux[0];
		$data_vencimento = $data_aux[0].'-'.$data_aux[1].'-20';
		return $data_vencimento;
	}

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
			return $_GET['data2'];
		return $data_vencimento;
	}
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
	function calcularMultaMora($valor,$data){
		$datas = new Datas();
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
		
		$dias = $datas->diferencaData($_GET['data2'],$data_vencimento);
		
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
	function calcularJurosMora($valor,$data){
		$datas = new Datas();
		$data_aux = explode('-', $data);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';
		$aux = $datas->somarData($data_aux,30);
		$data_aux = explode('-',$aux);
		$mes = intval($data_aux[1]) + 1;
		$ano = $data_aux[0];
		$data_vencimento = $data_aux[0].'-'.$data_aux[1].'-20';
		$dias = $datas->diferencaData($_GET['data2'],$data_vencimento);
		$mes_final = explode('-', $_GET['data2']);
		$mes_final = intval($mes_final[1]) - 1;
		$juros = 0;
		$valor_selic = 0;
		$periodo = array();
		if( $dias > 0 ){
			//Pega cada mes entre o inicial e o final - 1 
			while ($datas->diferencaData($data_vencimento,$_GET['data2']) < - 30 ){
				$aux1 = explode('-', $data_vencimento);
				$data_vencimento = $aux1[0].'-'.$aux1[1].'-20';
				$data_vencimento = $datas->somarData($data_vencimento,30);
				$aux = explode('-', $data_vencimento);
				$meses = $aux[1];
				$ano = $aux[0];
				if( $periodo[$meses.$ano] != 'usado' ){
					$consulta = mysql_query("SELECT * FROM selic WHERE ano = '".$ano."' AND mes = '".$meses."' ");
					$periodo[$meses.$ano] = 'usado';
					$objeto=mysql_fetch_array($consulta);
					$valor_selic = $objeto['valor'];
					$juros = $juros + floatval(str_replace(',','.',$valor_selic))/100 * $valor;
					// echo $valor_selic.'<br>';
					// echo $valor.'<br>';
				}
			}
			$juros = $juros + 0.01 * $valor;
		}
		return $juros;
	}
	function calcularTotal($valor,$juros,$multa){
		
		return floatval($valor) + $juros + $multa;
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

	include 'datas.class.php';
	$datas = new Datas();
	$codigo_receita = $_GET['codigo_receita'];
	$data_apuracao = $datas->desconverterData(calcularDataApuracao($_GET['data']));
	$cpf_cnpj = '';
	$numero_referencia = '';
	$aux_data_emissao = $_GET['data'];
	$data_vencimentoAux = $datas->desconverterData(calcularDataVencimentoAux($aux_data_emissao));
	$data_vencimento = $datas->desconverterData(calcularDataVencimento($_GET['data']));
	$valor_principal = number_format($_GET['valor'],2,',','.');
	$valor_multa = calcularMultaMora($_GET['valor'],$_GET['data']);
	$valor_multa = floor(($valor_multa * 100))/100;

	$vencimentoMesAno = date('Ym', strtotime(calcularDataVencimentoAux($_GET['data'])));
	
	//echo $vencimentoMesAno." - ".date('Ym');

	// O juros só pode ser apliicado após o primeiro mês 
	if($vencimentoMesAno < date('Ym') ){
		
		$valor_juros = calcularJurosMora($_GET['valor'],$_GET['data']);
		//$valor_juros = floor(($valor_juros	 * 100))/100;
		$valor_juros = DecimalSemArredondar($valor_juros, 2);
		
	} else {
		$valor_juros = 0;
	}

	$valor_total = calcularTotal($_GET['valor'],$valor_juros,$valor_multa);
	$autenticacao = '';
			
	$consulta = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$_SESSION['id_empresaSecao']."' ");
	$objeto=mysql_fetch_array($consulta);

	$nome = $objeto['razao_social'];

	$cidade = $objeto['cidade'];
	$estado = $objeto['estado'];

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
					<table border="0" style="font-size: 12px; border: 0mm solid #000;" width="100%">
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
					<p><?php echo ( $datas->diferencaData(date("Y-m-d"), calcularDataVencimentoAux($aux_data_emissao)) > 0 ? $data_vencimentoAux : $data_vencimento ); ?></p>
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
					<?php if( $datas->diferencaData(date("Y-m-d"), calcularDataVencimentoAux($aux_data_emissao)) > 0 ):?>
						<b>DARF válido para pagamento até <?php echo $data_vencimento; ?></b><br/>
						Domicílio tributário informado: <?php echo $cidade.' - '.$estado; ?> <br/>
						<b>NÃO RECEBER COM RASURAS</b>
					<?php endif;?>
				</td>
				<td valign="top">
					<p><strong>08</strong> <span class="titulo">VALOR DA MULTA</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo number_format($valor_multa,2,',','.'); ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>09</strong> <span class="titulo">VALOR DOS JUROS</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo number_format($valor_juros,2,',','.'); ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>10</strong> <span class="titulo">VALOR TOTAL</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo number_format($valor_total,2,',','.'); ?></p>
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
					<p><?php echo ( $datas->diferencaData(date("Y-m-d"), calcularDataVencimentoAux($aux_data_emissao)) > 0 ? $data_vencimentoAux : $data_vencimento ); ?></p>
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
					<?php if( $datas->diferencaData(date("Y-m-d"), calcularDataVencimentoAux($aux_data_emissao)) > 0 ):?>
						<b>DARF válido para pagamento até <?php echo $data_vencimento; ?></b><br/>
						Domicílio tributário informado: SÃO PAULO - SP <br/>
						<b>NÃO RECEBER COM RASURAS</b>
					<?php endif;?>
				</td>
				<td valign="top">
					<p><strong>08</strong> <span class="titulo">VALOR DA MULTA</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo number_format($valor_multa,2,',','.'); ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>09</strong> <span class="titulo">VALOR DOS JUROS</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo number_format($valor_juros,2,',','.'); ?></p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p><strong>10</strong> <span class="titulo">VALOR TOTAL</span></p>
				</td>
				<td valign="top" align="right">
					<p><?php echo number_format($valor_total,2,',','.'); ?></p>
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