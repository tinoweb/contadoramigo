<?php include 'header_restrita.php' ?>
<?

$arrUrl_origem = explode('/',$_SERVER['PHP_SELF']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];

$_SESSION['paginaOrigemSocios'] = $pagina_origem;

?>

<script src="jquery.maskMoney.js" type="text/javascript"></script>

<script>

	var Arr_Salarios = new Array();
	var Arr_TetoPrev = new Array();
	var Arr_IR = new Array();
	var contrMinima, contrMaxima, valor1, valor2, valor3, valor4, aliquota1, aliquota2, aliquota3, aliquota4, aliquota5, desconto1, desconto2, desconto3, desconto4, desconto5, descontoDep, ValorLiquido;

	<?
	// MONTANDO ARRAY COM OS VALORES DOS SALARIOS MINIMOS E SUAS DATAS
	$sql_sms = "SELECT * FROM salario_minimo";
	$resultado_sms = mysql_query($sql_sms)
	or die (mysql_error());
	// EXECUTA CONSULTA
	$arr_sms = array(); // CRIANDO UM ARRAY PHP
	while($linha_sms = mysql_fetch_array($resultado_sms)){ // LOOP NO RESULTADO
		array_push($arr_sms,array('valor'=>(float)str_replace(',','.',str_replace('.','',$linha_sms['valor'])),'inicio'=>strtotime($linha_sms['inicio_vigencia']),'fim'=>strtotime($linha_sms['fim_vigencia'] == 0 ? date('Y') . '-12-31' : $linha_sms['fim_vigencia'])));// INSERINDO NO ARRAY PHP DE CHAVES "VALOR", "INICIO" E "FIM" OS RESPECTIVOS VALORES
	}

	foreach($arr_sms as $chave => $salarios_minimos){ // PERCORRENDO A ARRAY PHP PARA MONTAR A ARRAY JAVASCRIPT
	//	if(strtotime('2014-01-01') >= $salarios_minimos['inicio'] && strtotime('2014-01-01') <= $salarios_minimos['fim']){
	//		echo $salarios_minimos['valor'] . "<BR>";
	//	}
		echo 'Arr_Salarios.push(Array("'.$salarios_minimos['valor'].'","'.$salarios_minimos['inicio'].'","'.$salarios_minimos['fim'].'"));' . "\n"; // ARRAY MULTIPLA DE 3 POSICOES

	}


	// MONTANDO ARRAY COM OS VALORES DOS TETOS PREVIDENCIARIOS E SUAS DATAS
	$sql_tps = "SELECT * FROM teto_previdenciario";
	$resultado_tps = mysql_query($sql_tps)
	or die (mysql_error());
	$arr_tps = array();
	while($linha_tps = mysql_fetch_array($resultado_tps)){
		array_push($arr_tps,array('valor'=>(float)str_replace(',','.',str_replace('.','',$linha_tps['valor'])),'inicio'=>strtotime($linha_tps['inicio_vigencia']),'fim'=>strtotime($linha_tps['fim_vigencia'] == 0 ? date('Y') . '-12-31 23:59:59' : $linha_tps['fim_vigencia'])));
	}

	foreach($arr_tps as $chave => $tetos){
	//	if(strtotime('2014-01-01') >= $salarios_minimos['inicio'] && strtotime('2014-01-01') <= $salarios_minimos['fim']){
	//		echo $salarios_minimos['valor'] . "<BR>";
	//	}
		echo 'Arr_TetoPrev.push(Array("'.$tetos['valor'].'","'.$tetos['inicio'].'","'.$tetos['fim'].'"));' . "\n";

	}
	echo "\r";

	// MONTANDO ARRAY COM OS VALORES DOS IR , ALIQUOTAS E SUAS DATAS
	$sql_ir = "SELECT * FROM tabelas";
	$resultado_ir = mysql_query($sql_ir)
	or die (mysql_error());
	$arr_ir = array();
	while($linha_ir = mysql_fetch_array($resultado_ir)){
		array_push($arr_ir,array('ano'=>$linha_ir['ano_calendario']
									,'valor1'=>(float)$linha_ir['ValorBruto1']
									,'valor2'=>(float)$linha_ir['ValorBruto2']
									,'valor3'=>(float)$linha_ir['ValorBruto3']
									,'valor4'=>(float)$linha_ir['ValorBruto4']
									,'aliquota1'=>(float)$linha_ir['Aliquota1']
									,'aliquota2'=>(float)$linha_ir['Aliquota2']
									,'aliquota3'=>(float)$linha_ir['Aliquota3']
									,'aliquota4'=>(float)$linha_ir['Aliquota4']
									,'aliquota5'=>(float)$linha_ir['Aliquota5']
									,'desconto1'=>(float)$linha_ir['Desconto1']
									,'desconto2'=>(float)$linha_ir['Desconto2']
									,'desconto3'=>(float)$linha_ir['Desconto3']
									,'desconto4'=>(float)$linha_ir['Desconto4']
									,'desconto5'=>(float)$linha_ir['Desconto5']
									,'descontoDep'=>(float)$linha_ir['Desconto_Ir_Dependentes'])
							);
	}

	foreach($arr_ir as $chave => $irs){
	//	if(strtotime('2014-01-01') >= $salarios_minimos['inicio'] && strtotime('2014-01-01') <= $salarios_minimos['fim']){
	//		echo $salarios_minimos['valor'] . "<BR>";
	//	}
		echo 'Arr_IR.push(Array("'.$irs['ano'].'","'.$irs['valor1'].'","'.$irs['valor2'].'","'.$irs['valor3'].'","'.$irs['valor4'].'","'.$irs['aliquota1'].'","'.$irs['aliquota2'].'","'.$irs['aliquota3'].'","'.$irs['aliquota4'].'","'.$irs['aliquota5'].'","'.$irs['desconto1'].'","'.$irs['desconto2'].'","'.$irs['desconto3'].'","'.$irs['desconto4'].'","'.$irs['desconto5'].'","'.$irs['descontoDep'].'"));' . "\n";

	}


	?>


	$(document).ready(function(e) {
		
		$.formataDataEn = function(data){
			var dia = 0;
			var mes = 0;
			var ano = 0;
			dia = data.substr(0,2);
			mes = data.substr(3,2);
			ano = data.substr(6,4);
			return ano + '-' + mes + '-' + dia;
		}
		
		$('.link_atualiza').bind('click',function(e){
			e.preventDefault();
			var socio_selecionado = $('#Nome').val();
			if(socio_selecionado > 0){
				location.href='meus_dados_socio.php?editar=' + socio_selecionado;
			}else{
				alert("Selecione um sócio.");
	//				location.href='meus_dados_socio.php';
			}

		});
		
		//CHANGE DA COMBO DE NOME QUE RETORNA AS VARIAVEIS RELATIVAS AO CALCULO DAS RETENCOES
		$('#Nome').change(function(){
			if($(this).val() != ''){
				//url:'pagamento_autonomos_retorna_dados_autonomo.php?aut=' + $(this).val().split('|')[0],
				$.ajax({
				  url:'pro_labore_retorna_dados_socio.php?soc=' + $(this).val(),
				  type: 'get',
				  cache: false,
				  async: true,
				  beforeSend: function(){
					$("body").css("cursor", "wait");
				  },
				  success: function(retorno){
					$("body").css("cursor", "auto");
					if(retorno != '0'){
						// QUEBRANDO O RETORNO PARA POPULAR OS CAMPOS RESPECTIVOS
						ArrRetorno = retorno.split("|");
						$('#hddDependentes').val(ArrRetorno[2]);
						$('#hddAliquotaISS').val(ArrRetorno[3]);
						$('#hddPercPensao').val(ArrRetorno[4]);

					}
				  }
				});
			}
		});
	
		// DITA A AÇÃO DO BOTAO LIMPAR
		$('#btnLimparCampos').click(function(){
			history.go(0);
		});
	
		// TRATA A OPÇÃO DE HAVER OUTRA FONTE PAGADORA DE INSS
        $('#OutraFontePagadora').click(function(){
			if($('#Nome').val() == ''){
				alert('Selecione um sócio');
				$('#OutraFonte').css('display','none');
				$('#INSSOutraFonte').val('');
				$('#OutraFontePagadora').attr('checked',false);
			}else{
				
				if($(this).attr('checked')==true){
					$('#OutraFonte').css('display','block');
				}else{
					$('#OutraFonte').css('display','none');
					$('#INSSOutraFonte').val('');
				}
			}
		});
		
		// BOTAO RESPONSAVEL POR CADASTRAR OS DADOS DO PAGAMENTO NO BANCO DE DADOS E EFETUAR A GERAÇÃO DO RECIBO
		$('#btnGerarRecibo').click(function(){
		
			if($('#Nome').val() == ''){
				alert('Selecione um sócio');
				$('#Nome').focus();
				return false;
			}

			if($('#DataPgto').val() == ''){
				alert('Preencha a data de pagamento.');
				$('#DataPgto').focus();
				return false;
			}
		
			if($('#OutraFontePagadora').attr('checked') == true && $('#INSSOutraFonte').val() == ''){			
				alert('Preencha o valor do INSS recolhido pela outra fonte pagadora.');
				$('#INSSOutraFonte').focus();
				return false;
			}
			
			if($('#ValorBruto').val() == ''){
				alert('Preencha o valor bruto.');
				$('#ValorBruto').focus();
				return false;
			}
			
			if($('#ValorLiquido').val() == ''){
				calculaRetencoes();
			}

			if(ValorLiquido < 0){
				alert('Não é possível gerar um Recibo com Valor Líquido menor que o INSS.');
				$('#ValorBruto').focus();
				return false;
			}
			
			var 
				$currURL = location.href
				, $Date = new Date($.formataDataEn($('#DataPgto').val()))
				, $DateHoje = new Date()
				, $mesHoje = ($DateHoje.getMonth() + 1)
				, $anoHoje = ($DateHoje.getFullYear())
			;
			
			$.ajax({
			  url:'pro_labore_checa.php?id=<?=$_SESSION["id_empresaSecao"]?>&idSocio=' + $('#Nome').val() + '&data=' + $('#DataPgto').val(),
			  type: 'get',
			  cache: false,
			  async: false,
			  beforeSend: function(){
				$("body").css("cursor", "wait");
			  },
			  success: function(retorno){
				$("body").css("cursor", "auto");
				// SE HOUVER PAGAMENTO PARA O MESMO SOCIO NO MES, É EXIBIDA A ALERTA E O ENVIO É CANCELADO
				if(retorno == '1'){
					alert('Não foi possível gerar o pagamento, pois já existe outro pró-labore efetuado no mesmo mês para este sócio.');
					return false;
					prosseguir = false;
				}else{
					if(retorno == '0'){
						prosseguir = true;
						$('#formGeraRecibo').attr('action','Recibo_download.php?acao=ins');
					}else{
						alert('Não foi possível gerar o Recibo.\nPreencha o campo ' + retorno.split('|')[1] + ' na página Meus Dados/Dados do Sócio e tente novamente.');
						prosseguir = false;
					}
				}
				
				if(prosseguir){

					var $data = $('#formGeraRecibo').serialize();
					$.ajax({
						url:'Recibo_download.php?acao=ins',
						type: 'post',
						data: $data,
						cache: false,
						async: true,
						beforeSend: function(){
							$("body").css("cursor", "wait");
						},
						success: function(retorno){

							$("body").css("cursor", "auto");

							if(retorno > 0){
								
								$('#btSIMAvisoLivroCaixa').attr('idPagto',retorno);
								$('#aviso-livro-caixa').fadeIn(100);

							}
						}
					});
		
					//alert($data);
						// $('#formGeraRecibo').submit();
					}
			  }
			});
			
		});
		
		$('#btSIMAvisoLivroCaixa').bind('click',function(){
			
			var 
				$currURL = location.href
				, $Date = new Date($.formataDataEn("10/" + $('#DataPgto').val().substr(3,2) + "/" + $('#DataPgto').val().substr(6,4)))
				, $DateHoje = new Date()
				, $mesHoje = ($DateHoje.getMonth() + 1)
				, $anoHoje = ($DateHoje.getFullYear())
				, $this = $(this)
			;
		//			alert($('#DataPgto').val());
		//			alert($.formataDataEn($('#DataPgto').val()));
		//			alert($Date);
		//			return false;
			var $data = $('#formGeraRecibo').serialize();
			$data += "&nome=" + $('#Nome option:selected').text() + "&idPagto=" + $this.attr('idPagto');

			$.ajax({
				url:'atualiza_livros_caixa.php',
				type: 'post',
				data: $data,
				cache: false,
				async: true,
				beforeSend: function(){
					$("body").css("cursor", "wait");
				},
				success: function(retorno){
					$("body").css("cursor", "default");
					if(retorno == 1){
						// faz o post do formulário de pesquisa para listar os do mes do pagamento recem cadastrado
						$('#periodoAno').val($Date.getFullYear());
						$('#periodoMes').val($Date.getMonth()+1);
						$('#hddTipoFiltro').val('mes');

						$('#form_filtro').submit();			
			//												location.href = $currURL;
					}
				}
			});
			
		});
		
		
		$('#btNAOAvisoLivroCaixa').bind('click',function(){										
			// faz o post do formulário de pesquisa para listar os do mes do pagamento recem cadastrado
			var 
				$Date = new Date($.formataDataEn("10/" + $('#DataPgto').val().substr(3,2) + "/" + $('#DataPgto').val().substr(6,4)))
			;
			
			$('#periodoAno').val($Date.getFullYear());
			$('#periodoMes').val($Date.getMonth()+1);
			$('#hddTipoFiltro').val('mes');

			$('#form_filtro').submit();			

		//									location.href = $currURL;
		});
								

		
		
	  });





	function calculaRetencoes(){
		var aliquotaIR = 0;
		var descontoIR = 0;
		if($('#Nome').val() == ''){
			alert('Selecione um sócio');
			$('#Nome').focus();
			return false;
		}

		if($('#DataPgto').val() == ''){
			alert('Preencha a data de pagamento.');
			$('#DataPgto').focus();
			return false;
		}

		var dataHoje = new Date();
		dataHoje = new Date(dataHoje).getTime()/1000;
		
		// transforma a data de pagamento preenchida em timestamp para fazer uma comparação mais prática entre datas
		var dataPagto = $('#DataPgto').val().substr(3,2)+"/"+$('#DataPgto').val().substr(0,2)+"/"+$('#DataPgto').val().substr(6,4);
		dataPagto = new Date(dataPagto).getTime()/1000;

		var anoPagto = $('#DataPgto').val().substr(6,4);
		var mesPagto = $('#DataPgto').val().substr(3,2);

	//alert(dataHoje);
	//alert(dataPagto);
	//alert(dataHoje < dataPagto);
		if(dataHoje < dataPagto){
			alert('A data da retirada do pró-labore não pode ser superior a data atual.');
			$('#DataPgto').focus();
			return false;			
		}
		


		//		alert(anoPagto);
		if(anoPagto < 2011){
			alert('Não é possível realizar os cálculos pois o pagamento é muito antigo!');
 			document.getElementById('RetencaoIR').value		=	"";
			document.getElementById('RetencaoINSS').value	=	"";
			document.getElementById('ValorLiquido').value	=	"";
			return false;
		}


		// percorre a array com os valores dos salarios minimos para localizar a faixa correnpondente à data de pagamento preenchida
		for(var i = 0; i < Arr_Salarios.length; i++){
			if(dataPagto >= Arr_Salarios[i][1] && dataPagto <= Arr_Salarios[i][2]){
				//alert(Arr_Salarios[i]);
				contrMinima = parseFloat((Arr_Salarios[i][0]) * 0.11).toFixed(2);
			}
		}
		//alert(contrMinima);
		//		alert(Arr_TetoPrev[i][1]);
		// percorre a array com os valores dos tetos previdenciarios para localizar a faixa correnpondente à data de pagamento preenchida
		for(var i = 0; i < Arr_TetoPrev.length; i++){
		//			alert(dataPagto);
		//			alert(Arr_TetoPrev[i][1]);
		//			alert(Arr_TetoPrev[i][2]);
			if(dataPagto >= Arr_TetoPrev[i][1] && dataPagto <= Arr_TetoPrev[i][2]){
				//alert(Arr_TetoPrev[i]);
				contrMaxima = parseFloat((Arr_TetoPrev[i][0]) * 0.11).toFixed(2);
 			}
		}
		//alert(contrMaxima);

		// percorre a array com os valores dos tetos previdenciarios para localizar a faixa correnpondente à data de pagamento preenchida
		for(var i = 0; i < Arr_IR.length; i++){
			if(anoPagto == '2015' && mesPagto <= 3){
				if('2014' == Arr_IR[i][0]){
					valor1 = parseFloat(Arr_IR[i][1]);
					valor2 = parseFloat(Arr_IR[i][2]);
					valor3 = parseFloat(Arr_IR[i][3]);
					valor4 = parseFloat(Arr_IR[i][4]);
					aliquota1 = parseFloat(Arr_IR[i][5]);
					aliquota2 = parseFloat(Arr_IR[i][6]);
					aliquota3 = parseFloat(Arr_IR[i][7]);
					aliquota4 = parseFloat(Arr_IR[i][8]);
					aliquota5 = parseFloat(Arr_IR[i][9]);
					desconto1 = parseFloat(Arr_IR[i][10]);
					desconto2 = parseFloat(Arr_IR[i][11]);
					desconto3 = parseFloat(Arr_IR[i][12]);
					desconto4 = parseFloat(Arr_IR[i][13]);
					desconto5 = parseFloat(Arr_IR[i][14]);
					descontoDep = parseFloat(Arr_IR[i][15]);
				}
			}else{
				if(anoPagto == Arr_IR[i][0]){
					//alert(Arr_IR[i]);
					valor1 = parseFloat(Arr_IR[i][1]);
					valor2 = parseFloat(Arr_IR[i][2]);
					valor3 = parseFloat(Arr_IR[i][3]);
					valor4 = parseFloat(Arr_IR[i][4]);
					aliquota1 = parseFloat(Arr_IR[i][5]);
					aliquota2 = parseFloat(Arr_IR[i][6]);
					aliquota3 = parseFloat(Arr_IR[i][7]);
					aliquota4 = parseFloat(Arr_IR[i][8]);
					aliquota5 = parseFloat(Arr_IR[i][9]);
					desconto1 = parseFloat(Arr_IR[i][10]);
					desconto2 = parseFloat(Arr_IR[i][11]);
					desconto3 = parseFloat(Arr_IR[i][12]);
					desconto4 = parseFloat(Arr_IR[i][13]);
					desconto5 = parseFloat(Arr_IR[i][14]);
					descontoDep = parseFloat(Arr_IR[i][15]);
				}
			}/*
			if(anoPagto == Arr_IR[i][0]){
				//alert(Arr_IR[i]);
				valor1 = parseFloat(Arr_IR[i][1]);
				valor2 = parseFloat(Arr_IR[i][2]);
				valor3 = parseFloat(Arr_IR[i][3]);
				valor4 = parseFloat(Arr_IR[i][4]);
				aliquota1 = parseFloat(Arr_IR[i][5]);
				aliquota2 = parseFloat(Arr_IR[i][6]);
				aliquota3 = parseFloat(Arr_IR[i][7]);
				aliquota4 = parseFloat(Arr_IR[i][8]);
				aliquota5 = parseFloat(Arr_IR[i][9]);
				desconto1 = parseFloat(Arr_IR[i][10]);
				desconto2 = parseFloat(Arr_IR[i][11]);
				desconto3 = parseFloat(Arr_IR[i][12]);
				desconto4 = parseFloat(Arr_IR[i][13]);
				desconto5 = parseFloat(Arr_IR[i][14]);
				descontoDep = parseFloat(Arr_IR[i][15]);
			}*/
		}
		/*
		alert('valor1 = ' + valor1);
		alert('valor2 = ' + valor2);
		alert('valor3 = ' + valor3);
		alert('valor4 = ' + valor4);
		alert('aliquota1 = ' + aliquota1);
		alert('aliquota2 = ' + aliquota2);
		alert('aliquota3 = ' + aliquota3);
		alert('aliquota4 = ' + aliquota4);
		alert('aliquota5 = ' + aliquota5);
		alert('desconto1 = ' + desconto1);
		alert('desconto2 = ' + desconto2);
		alert('desconto3 = ' + desconto3);
		alert('desconto4 = ' + desconto4);
		alert('desconto5 = ' + desconto5);
		*/

		//pega o INSSOutra, transforma em float e põe no padrão americano para efeito de cálculo
		INSSOutra = document.getElementById('INSSOutraFonte').value;
		if(document.getElementById('OutraFontePagadora').checked && INSSOutra == ''){			
			alert('Preencha o valor do INSS recolhido pela outra fonte pagadora.');
			document.getElementById('INSSOutraFonte').focus();
			return false;
		}
		if(INSSOutra != ''){
			INSSOutra = INSSOutra.replace(".","");
			INSSOutra = INSSOutra.replace(",",".");
			INSSOutra = parseFloat(INSSOutra);
			//if(INSSOutra > <?= $Contribuicao_Maxima_n ?>){
			if(INSSOutra > parseFloat(contrMaxima).toFixed(2)){
				//alert('O valor do INSS pago pela outra fonte não pode ser maior que <?= $Contribuicao_Maxima_n ?>');
				alert('O valor do INSS pago pela outra fonte não pode ser maior que ' + parseFloat(contrMaxima).toFixed(2));
				document.getElementById('INSSOutraFonte').value = '';
				document.getElementById('INSSOutraFonte').focus();
				return false;
			}
		}

		//pega o ValorBruto, transforma em float e põe no padrão americano para efeito de cálculo
		ValorBruto = document.getElementById('ValorBruto').value;
		if(ValorBruto == ''){
			alert('Preencha o valor bruto.');
			document.getElementById('ValorBruto').focus();
			return false;
		}
		ValorBruto = ValorBruto.replace(".","");
		ValorBruto = ValorBruto.replace(",",".");
		ValorBruto = parseFloat(ValorBruto);
	
		// calcula retenção do INSS
		INSS = (ValorBruto * 11)/100;
	
		SOMA = parseFloat(INSS + INSSOutra);
		
		// SE A SOMA 
		/*	
				if (SOMA > <?= $Contribuicao_Maxima_n ?>) {
					INSS = <?= $Contribuicao_Maxima_n ?> - INSSOutra;
				}
		*/
		if (SOMA > contrMaxima) {
			INSS = contrMaxima - INSSOutra;
		}

		/*		
				if (SOMA < <?= $Contribuicao_Minima_n ?>) {
					INSS = <?= $Contribuicao_Minima_n ?>; 
				}
		*/
		if (SOMA < contrMinima) {
			INSS = contrMinima; 
		}

		// ARREDONDANDO OS DECIMAIS PARA RESOLVER A QUESTÃO DO INSS
		//INSS = Math.floor(INSS * 100) / 100;
	
		//	INSS = 205.63;//****************
		
		// calcula o desconto por quantidade de dependentes
		// PEGANDO DO VALUE DO COMBO A SEGUNDA POSIÇÃO DO VALUE = DEPENDENTES
	
		//arrValue = document.getElementById('Nome').value.split('|');
		//NumeroDep = arrValue[1];
	
		NumeroDep = document.getElementById('hddDependentes').value;
		DescontoDep = NumeroDep * descontoDep;
		document.getElementById('hddValorDependentes').value = DescontoDep;
		//	DescontoDep = 106//*****************
	
		// calcula alíquota e desconto do IR com base no ValorBruto para cálculo da pensão
		if (ValorBruto <= (valor1)) {aliquotaIR = aliquota1; descontoIR = desconto1}
		if (ValorBruto >  (valor1) && ValorBruto <= (valor2)){ aliquotaIR = aliquota2; descontoIR = desconto2}
		if (ValorBruto >  (valor2) && ValorBruto <= (valor3)){ aliquotaIR = aliquota3; descontoIR = desconto3}
		if (ValorBruto >  (valor3) && ValorBruto <= (valor4)){ aliquotaIR = aliquota4; descontoIR = desconto4}
		if (ValorBruto >  (valor4)){ aliquotaIR = aliquota5; descontoIR = desconto5}
		/*
				if (ValorBruto <= <?=$ValorBruto1?>) {aliquotaIR = <?=$Aliquota1?>; descontoIR = <?=$Desconto1?>}
				if (ValorBruto >  <?=$ValorBruto1?> && ValorBruto <= <?=$ValorBruto2?>){ aliquotaIR = <?=$Aliquota2?>; descontoIR = <?=$Desconto2?>}
				if (ValorBruto >  <?=$ValorBruto2?> && ValorBruto <= <?=$ValorBruto3?>){ aliquotaIR = <?=$Aliquota3?>; descontoIR = <?=$Desconto3?>}
				if (ValorBruto >  <?=$ValorBruto3?> && ValorBruto <= <?=$ValorBruto4?>){ aliquotaIR = <?=$Aliquota4?>; descontoIR = <?=$Desconto4?>}
				if (ValorBruto >  <?=$ValorBruto4?> ){ aliquotaIR = <?=$Aliquota5?>; descontoIR = <?=$Desconto5?>}
		*/
		//	aliquotaIR = 27.5//*****************
		//	descontoIR = 423.08//*****************
		
		// obtem o valor da pensao 
		
		//Pega percentual da pensao, transforma em float, põe no padrão americano
		//	PercentPensao = arrValue[3];
		PercentPensao = document.getElementById('hddPercPensao').value;
		if (PercentPensao != "") {
			PercentPensao = PercentPensao.replace(".","");
			PercentPensao = PercentPensao.replace(",",".");
			PercentPensao = parseFloat(PercentPensao);
			
			//faz conta para descobrir o valor da pensao
			a = ValorBruto - INSS; 
			b = aliquotaIR/100; 
			c = ValorBruto - INSS - DescontoDep; 
			d = descontoIR; 
			e = PercentPensao/100; 
			f = b * c; 
			x = a - f + d; 
			y = e*x; 
			w = e*b; 
			Pensao = y/(1-w);
			
		} else {
			Pensao = 0;
		}
		
		// Veja como cheguei nesse calculo: 
		// Pensao = [ValorBruto - INSS - ((aliquotaIR/100) * (ValorBruto - INSS - DescontoDep - Pensao)) + descontoIR] * (percentPensao/100)
		// Pensao = (a - (b * (c-Pensao)) + d) * e	
		// Pensao = (a - (b * c - b * Pensao) + d) * e	
		// Pensao = (a - (f - b * Pensao) + d) * e	
		// Pensao = (a - f + b*Pensao + d) * e	
		// Pensao = (x + b*Pensao) * e	
		// Pensao = e*x + e*b*Pensao	
		// Pensao = y + w*Pensao
		// Pensao/Pensao = y/Pensao + w
		// 1 = y/Pensao + w
		// 1 - w = y/Pensao
		// (1-w)/y = 1/Pensao
		// y/(1-w) = Pensao
		
		
	
		//Obtém a base de calculo
		BaseCalculoIR = ValorBruto - INSS - DescontoDep - Pensao;

		if (BaseCalculoIR <= (valor1)) {aliquotaIR = aliquota1; descontoIR = desconto1}
		if (BaseCalculoIR >  (valor1) && BaseCalculoIR <= (valor2)){ aliquotaIR = aliquota2; descontoIR = desconto2}
		if (BaseCalculoIR >  (valor2) && BaseCalculoIR <= (valor3)){ aliquotaIR = aliquota3; descontoIR = desconto3}
		if (BaseCalculoIR >  (valor3) && BaseCalculoIR <= (valor4)){ aliquotaIR = aliquota4; descontoIR = desconto4}
		if (BaseCalculoIR >  (valor4)){ aliquotaIR = aliquota5; descontoIR = desconto5}
		/*		
				//Calcula a alíquota e o desconto do IR
				if (BaseCalculoIR <= <?=$ValorBruto1?>) {aliquotaIR = <?=$Aliquota1?>; descontoIR = <?=$Desconto1?>}
				if (BaseCalculoIR >  <?=$ValorBruto1?> && BaseCalculoIR <= <?=$ValorBruto2?>){ aliquotaIR = <?=$Aliquota2?>; descontoIR = <?=$Desconto2?>}
				if (BaseCalculoIR >  <?=$ValorBruto2?> && BaseCalculoIR <= <?=$ValorBruto3?>){ aliquotaIR = <?=$Aliquota3?>; descontoIR = <?=$Desconto3?>}
				if (BaseCalculoIR >  <?=$ValorBruto3?> && BaseCalculoIR <= <?=$ValorBruto4?>){ aliquotaIR = <?=$Aliquota4?>; descontoIR = <?=$Desconto4?>}
				if (BaseCalculoIR >  <?=$ValorBruto4?> ){ aliquotaIR = <?=$Aliquota5?>; descontoIR = <?=$Desconto5?>}
		*/
		//Calcula o valor do IR incluindo a pensao
		IR = [(BaseCalculoIR * (aliquotaIR))/100 ] - (descontoIR);
			
		//valor liquido	
		ValorLiquido = ValorBruto - INSS - IR;
	
		document.getElementById('RetencaoIR').value		=	formataCampoMoeda(limpaCaracteres(parseFloat(IR).toFixed(2)));
		document.getElementById('RetencaoINSS').value	=	formataCampoMoeda(limpaCaracteres(parseFloat(INSS).toFixed(2)));
		document.getElementById('ValorLiquido').value	=	formataCampoMoeda(limpaCaracteres(parseFloat(ValorLiquido).toFixed(2)));
		if(ValorLiquido < 0){
			//$('#ValorLiquido').val("-" + $('#ValorLiquido').val());
			alert('INSS maior que a retirada. Estipule um valor maior para seu pró-labore.');
		}
		
	}
	
	
	
	function formataCampoMoeda(varValor){
		switch(varValor.length){
			case 2:
				varResult = ('0,' + varValor.substr(0,1) + varValor.substr(1,1));
			break;
			case 3:
				varResult = (varValor.substr(0,1) + ',' + varValor.substr(1,1) + varValor.substr(2,1));
			break;
			case 4:
				varResult = (varValor.substr(0,1) + varValor.substr(1,1) + ',' + varValor.substr(2,1) + varValor.substr(3,1));
			break;
			case 5:
				varResult = (varValor.substr(0,1) + varValor.substr(1,1) + varValor.substr(2,1) + ',' + varValor.substr(3,1) + varValor.substr(4,1));
			break;
			case 6:
				varResult = (varValor.substr(0,1) + '.' + varValor.substr(1,1) + varValor.substr(2,1) + varValor.substr(3,1) + ',' + varValor.substr(4,1) + varValor.substr(5,1));
			break;
			case 7:
				varResult = (varValor.substr(0,1) + varValor.substr(1,1) + '.' + varValor.substr(2,1) + varValor.substr(3,1) + varValor.substr(4,1) + ',' + varValor.substr(5,1) + varValor.substr(6,1));
			break;
			case 8:
				varResult = (varValor.substr(0,1) + varValor.substr(1,1) + varValor.substr(2,1) + '.' + varValor.substr(3,1) + varValor.substr(4,1) + varValor.substr(5,1) + ',' + varValor.substr(6,1) + varValor.substr(7,1));
			break;
			case 9:
				varResult = (varValor.substr(0,1) + '.' + varValor.substr(1,1) + varValor.substr(2,1) + varValor.substr(3,1) + '.' + varValor.substr(4,1) + varValor.substr(5,1) + varValor.substr(6,1) + ',' + varValor.substr(7,1) + varValor.substr(8,1));
			case 10:
				varResult = (varValor.substr(0,1) + varValor.substr(1,1) + '.' + varValor.substr(2,1) + varValor.substr(3,1) + varValor.substr(4,1) + '.' + varValor.substr(5,1) + varValor.substr(6,1) + varValor.substr(7,1) + ',' + varValor.substr(8,1) + varValor.substr(9,1));
			break;
		}
		return varResult;
	}

</script>


	<div style="position: relative;">
    
      <!--BALLOM excluir pagamento-->
      <div class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; left:50%; margin-left: -400px;" id="aviso-delete-livro-caixa">
          <div style="padding:20px; font-size:12px;">
              <div id="mensagemDELETEPagamento"></div><br>
              <div style="clear: both; margin: 0 auto; display: inline;">
                <center>
                  <button id="btSIMDeletePagamentoLivroCaixa" type="button" idpg="" idLC="">Sim</button>
                  <button id="btNAODeletePagamentoLivroCaixa" type="button" idpg="" idLC="">Não</button>
                </center>
              </div>
              <div style="clear: both;"></div>
          </div>
        </div>
      <!--FIM DO BALLOOM excluir pagamento -->
      

    </div>



<div class="principal">

	<div style="width:100%">
		<div class="titulo">Empréstimos</div>

		<!-- <div style="clear:both;"></div> -->

		<!-- <div style="clear:both; height:10px;"></div> -->

		<?php 

			$mes_atual_selic = (intval(date("m"))-1)%12;
			$ano = date("Y");
			if( $mes_atual_selic == 0 ){
				$mes_atual_selic = 12;
				$ano = $ano - 1;
			}

			$consulta = mysql_query("SELECT * FROM selic WHERE ano = '".$ano."' AND mes = '".$mes_atual_selic."' ");
			$selic = mysql_fetch_array( $consulta );

			$taxa_selic = number_format(str_replace(',', '.',$selic['valor']),2,',','.')."%";

		?>

		<div style="margin-bottom:20px">Se você já retirou o pró-labore e fez a distribuição de lucros no valor máximo permitido e, ainda assim, precisa de um reforço na sua conta pessoa física, sua empresa pode lhe conceder um empréstimo. Da mesma forma, se a sua empresa estiver precisando, você pode emprestar um dinheiro para ela. É importante, porém, que esse empréstimo seja documentado na forma de um Contrato de Mútuo, caso contrário você terá problemas em uma eventual fiscalização. <br>
		  <br>
Nesta página você poderá gerar automaticamente o Contrato de Mútuo e calcular os impostos a ele relacionados. Se o mutuante (aquele que concede o empréstimo) for a empresa, haverá recolhimento de IOF. Se o mutuante for a Pessoa Física, não haverá IOF. Na hora de devolver dinheiro, independente de quem emprestou, haverá recolhimento de IR na fonte sobre os juros ganhos na operação. A alíquota do IR varia de acordo com o prazo do empréstimo, conforme tabela a seguir:<br>
<br>
22,5%, em operações com prazo de até 180 dias;<br>
20%, em operações com prazo de 181 dias até 360 dias; <br>
17,5%, em operações com prazo de 361 dias até 720 dias;<br>
15%, em operações com prazo acima de 720 dias. <br>
<br>
Para que o contrato tenha validade, você deve estipular uma taxa de juros para o empréstimo. Nosso sistema sugere uma taxa de 1% ao mês. Você poderá alterar para mais, desde que não ultrapasse a Selic (que hoje está em <?php echo $taxa_selic; ?> ) ou para menos, desde que não fique muito abaixo da média do mercado, para não descaracterizar o empréstimo. </div>

		<div style="margin-bottom: 20px;">
			<input type="radio" name="tipo_emprestimo" id="tipo_emprestimo1" class="tipo_emprestimo" value="1" style="margin-right: 5px;">Empréstimo do sócio/proprietário para a empresa
			<input type="radio" name="tipo_emprestimo" id="tipo_emprestimo2" class="tipo_emprestimo" value="2" style="margin-left: 10px;margin-right: 5px;">Empréstimo da empresa para o sócio/proprietário	
		</div>
		<form id="formGerarReciboEmprestimo" action="recibo_emprestimo.php" method="post">
			<div class="socio_emprestimo" style="display:none">
				<span id="frase">Selecione o sócio que fará o empréstimo</span>
				<select name="cedente_emprestimo" id="cedente_emprestimo" style="">
				<?php 
					$consulta = mysql_query("SELECT * FROM dados_do_responsavel WHERE id = '".$_SESSION["id_empresaSecao"]."' ");
					
					$checkSocio = 0;
					
					while( $objeto=mysql_fetch_array($consulta) ){
						
					$checkSocio += 1;	
				?>
					<option value="<?php echo $objeto['idSocio'] ?>"><?php echo $objeto['nome'] ?></option>	
				<?php }	?>
                <?php echo ($checkSocio == 0 ? "<option>Não há sócio cadastrado</option>" : "" )?></a>
			</select> <a href="/meus_dados_socio.php?act=new" style="margin-left:10px;"><?php echo ($checkSocio == 0 ? "Cadastrar Sócio" : "Cadastrar novo Sócio" )?></a>
			<br>
			<br>
			</div>
			
			<div class="tituloVermelho" style="margin-bottom:10px">Dados do Empréstimo</div>

			<!-- <form id="formGerarReciboEmprestimo" action="recibo_emprestimo.php" method="post"> -->
			<table width="900" cellpadding="3" style="margin-bottom:10px;">			
				<tbody style="float:left">
					<tr>
						<td align="right" valign="top" class="formTabela">Nome:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="nome_emprestimo" id="nome_emprestimo" type="text">
						</td>
					</tr>
					 
					<tr>
						<td align="right" valign="top" class="formTabela">Rua:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="rua_emprestimo" id="rua_emprestimo" type="text" value="">
						</td>
					</tr>

					<tr>
						<td align="right" valign="top" class="formTabela">Bairro:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="bairro_emprestimo" id="bairro_emprestimo" type="text" value="">
						</td>
					</tr>

					<?php 
				  		$arrEstados = array();
						$sql = "SELECT * FROM estados ORDER BY sigla";
						$result = mysql_query($sql) or die(mysql_error());
						while($estados = mysql_fetch_array($result)){
							array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
						}

				  	 ?>
					<tr>
						<td align="right" valign="top" class="formTabela">Estado:</td>
						<td align="left" valign="top" class="formTabela">
							<select name="estado_emprestimo" id="estado_emprestimo">
								<option value="">UF</option>
								 <?
						            foreach($arrEstados as $dadosEstado){
										echo "<option class=\"escolher_estado\" id-uf=\"".$dadosEstado['id']."\" value=\"".$dadosEstado['sigla']."\" >".$dadosEstado['sigla']."</option>";
						            }
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right" valign="top" class="formTabela">Cidade:</td>
						<td align="left" valign="top" class="formTabela">
							<select name="cidade_emprestimo" id="cidade_emprestimo" style="width:300px" class="comboM" alt="Cidade"></select>
						</td>
					</tr>
					<tr>
						<td align="right" valign="top" class="formTabela">RG:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="rg_emprestimo" class="campoRG2" id="rg_emprestimo" type="text" value="">
						</td>
					</tr>
					<tr>
						<td align="right" valign="top" class="formTabela">CPF:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="cpf_emprestimo" id="cpf_emprestimo" type="text" value="">
						</td>
					</tr>

					<tr>
						<td align="right" valign="top" class="formTabela">Nacionalidade:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="nacionalidade_emprestimo" id="nacionalidade_emprestimo" type="text" value="">
						</td>
					</tr>

					<tr>
						<td align="right" valign="top" class="formTabela">Profissão:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="profissao_emprestimo" id="profissao_emprestimo" type="text" value="">
						</td>
					</tr>

					<tr>
						<td align="right" valign="top" class="formTabela">Estado Civil:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="estado_civil_emprestimo" id="estado_civil_emprestimo" type="text" value="">
						</td>
					</tr>

					<tr>
						<td align="right" valign="top" class="formTabela">Valor:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="valor_emprestimo" id="valor_emprestimo" class="currency" type="text" size="13">
                            <input name="valor_liquido_emprestar" id="valor_liquido_emprestar" type="hidden" />
						</td>
					</tr>

					<tr>
						<td align="right" valign="top" class="formTabela">Data do empréstimo:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="data_emprestimo" id="data_emprestimo" class="campoData" type="text" size="10">
						</td>
					</tr>
					<tr>
						<td align="right" valign="top" class="formTabela">Prazo (em dias):</td>
						<td align="left" valign="top" class="formTabela">
							<input name="devolucao_emprestimo_dias" id="devolucao_emprestimo_dias" class="" type="text" size="7">
						</td>
					</tr>
					<!--<tr>
						<td align="right" valign="top" class="formTabela">Data da devolução:</td>
						<td align="left" valign="top" class="formTabela"-->
							<input name="devolucao_emprestimo" id="devolucao_emprestimo" class="campoData" type="hidden" size="10" disabled="disabled">
						<!--</td>
					</tr>-->

					<tr>
						<td align="right" valign="top" class="formTabela">Juros Mensal:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="juros_emprestimo" id="juros_emprestimo" type="text" value="<?php echo str_replace("%","",$taxa_selic); ?>" size="8" > 
                            <span style="margin-left: 6px;">Adotamos como taxa de juros o valor da Selic(<?php echo $taxa_selic; ?> ), mas você pode alterar para mais, se quiser. Nunca para menos.</span>
							<!-- % (<span style="margin-bottom:20px">Você poderá alterar para mais, desde que não ultrapasse a Selic ( ). !--ou para menos.-- </span>-->
                       	</td>
					</tr>
					<!--<tr>
						<td><br></td>
						<td><br></td>
					</tr>-->

					<!--tr>
						<td align="right" valign="top" class="formTabela"><strong>Testemunhas</strong></td>
						<td align="left" valign="top" class="formTabela">
						</td>
					</tr>

					<tr>
						<td align="right" valign="top" class="formTabela">Nome:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="nome1_testemunha" id="nome1_testemunha" type="text" class="" size="50">
						</td>
					</tr>
					<tr>
						<td align="right" valign="top" class="formTabela">RG:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="rg1_testemunha" id="rg1_testemunha" class="" type="text" class="" size="11">
						</td>
					</tr>

					<tr>
						<td align="right" valign="top" class="formTabela">Nome:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="nome2_testemunha" id="nome2_testemunha" type="text" class="" size="50">
						</td>
					</tr>
					<tr>
						<td align="right" valign="top" class="formTabela">RG:</td>
						<td align="left" valign="top" class="formTabela">
							<input name="rg2_testemunha" id="rg2_testemunha" class="" type="text" class="" size="11">
						</td>
					</tr>-->

				</tbody>
			</table>

			<input name="gerarEmprestimo" id="calcularIOF" type="button" value="Calcular Impostos" ><br/>
            <div id="carregandoDados" style="text-align:left; display:none">
            	<img src="images/loading.gif" width="16" height="16" /> processando...
            </div>

			<div style="margin-bottom:20px;margin-top:30px;position: relative;">

				<div style="margin-bottom: 5px;width:100%;float:left">
					
					<div style="float: left;width: 40px;">IOF: </div>
					<input type="text" id="iof" class="currency" value="" size="15" disabled style="margin-right:10px;float: left;">
					<div style="float: left;">a ser recolhido no ato do empréstimo <strong>por quem tomou o dinheiro</strong>.</div> 

				</div>
				<div style="margin-bottom: 5px;width:100%;float:left">
					<div style="float: left;width: 40px;">IRRF: </div>
					<input type="text" id="IRRF" class="currency" value="" size="15" disabled style="margin-right:10px;float: left;">
					<div style="float: left;"><div style="float: left;">a ser pago <strong>por quem cedeu o empréstimo</strong></div><div style="float:left;display:none;margin-left: 4px;" class="data_IRRF"> em <span id="data_pagamemto">20/MM/AAAA</span></div>.</div>
				</div>
				<div style="display:none;margin-bottom: 5px;width:100%;float:left;margin-top: 15px;" class="valor_total_repassar">
					Valor liquído a emprestar <span id="txtIOF">(descontado o IOF)</span>: R$ <span id="valor_total_repassar" class="destaque">XX,XX</span>
				</div>
				<div style="display:none;margin-bottom: 15px;width:100%;float:left" class="valor_a_restituir">
					Valor total a restituir em <span id="data_pagamemto_emprestimo">DD/MM/AAAA</span>: R$ <span id="valor_total" class="destaque">XX,XX</span>
				</div><br>

			</div>

			<br>

			<div style="margin-bottom:20px;margin-top:10px;position: relative;">
                <table style="margin-bottom:20px;">
                    <tbody>
                        <tr>
                            <td align="right" valign="top" class="formTabela">
                            	<span class="tituloVermelho">Testemunhas</span>
                            </td>
                            <td align="left" valign="top" class="formTabela">
                            </td>
                        </tr>
    
                        <tr>
                            <td align="right" valign="top" class="formTabela">Nome:</td>
                            <td align="left" valign="top" class="formTabela">
                                <input name="nome1_testemunha" id="nome1_testemunha" type="text" class="" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top" class="formTabela">RG:</td>
                            <td align="left" valign="top" class="formTabela">
                                <input name="rg1_testemunha" id="rg1_testemunha" class="" type="text" class="" size="11">
                            </td>
                        </tr>
    
                        <tr>
                            <td align="right" valign="top" class="formTabela">Nome:</td>
                            <td align="left" valign="top" class="formTabela">
                                <input name="nome2_testemunha" id="nome2_testemunha" type="text" class="" size="50">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top" class="formTabela">RG:</td>
                            <td align="left" valign="top" class="formTabela">
                                <input name="rg2_testemunha" id="rg2_testemunha" class="" type="text" class="" size="11">
                            </td>
                        </tr>
    
                    </tbody>
                </table>
  
				<input type="hidden" name="gerarEmprestimo" value="">
				<input type="hidden" name="tipo" id="tipo_emprestimo" value="1">
				<input name="gerarEmprestimo" id="enviarEmprestimo" type="button" value="Baixar contrato">
				<input type="hidden" name="id_user" value="<?php echo $_SESSION['id_empresaSecao']; ?>">
				<input type="hidden" name="livro_caixa" class="livro_caixa" value="">
				<!--BALLOM Livro Caixa -->
			  	<div class="bubble only-box" style="display: none;padding: 0px;position: absolute;top: -110px;right: 319px;" id="aviso-livro-caixa">
			      <div style="padding:20px; font-size:12px;">
			        Deseja cadastrar este pagamento no seu livro caixa?<br><br>            
			        <div style="clear: both; margin: 0 auto; display: inline;">
			        	<center>
			          		<button class="enviarForm" value="sim" type="button" idpagto="">Sim</button>
			            	<button class="enviarForm" value="nao" type="button">Não</button>
			          	</center>
			        </div>
			        <div style="clear: both;"></div>
			      </div>
			    </div>
			    <!--FIM DO BALLOOM Livro Caixa -->
			</div>

		</form>

	<!--BALLOM Livro Caixa -->
  	<div class="bubble only-box" style="display: none;padding: 0px;position: absolute;top: -50px;left: 520px;" id="aviso-excluir">
      <div style="padding:20px; font-size:12px;">
        <!--Deseja cadastrar este pagamento no seu livro caixa?-->
        Deseja realmente excluir este contrato de empréstimos?<br><br>            
        <div style="clear: both; margin: 0 auto; display: inline;">
        	<center>
          		<button class="respostaExcluir" value="sim" type="button" idpagto="">Sim</button>
            	<button class="respostaExcluir" value="nao" type="button">Não</button>
            	<input type="hidden" id="link_excluir" value="">
          	</center>
        </div>
        <div style="clear: both;"></div>
      </div>
    </div>
    <!--FIM DO BALLOOM Livro Caixa -->	

	<?php 
	
		$parametros = array("%", ",");
		$alterar = array("", ".");
		
		$taxaSelic = str_replace($parametros, $alterar,$taxa_selic); 
	?>

		<script>
			
			$( document ).ready(function() {

				var juros_selecionado = 0;
				
				var checkSocio = <?php echo $checkSocio; ?>;
				var taxaSelic = parseFloat(<?php echo $taxaSelic; ?>);

				function zerarCamposImpostos(){
					// $("#iof").empty();
				    $("#iof").val("");
				    $("#valor_total").empty();
				    // $("#valor_total").append(obj[1]);
				    $("#data_pagamemto").empty();
				    // $("#data_pagamemto").append(obj[2]);
				    $("#data_pagamemto_emprestimo").empty();
				    // $("#data_pagamemto_emprestimo").append(obj[3]);
				    $("#IRRF").val("");
				    $("#valor_total_repassar").empty();
					$("#valor_liquido_emprestar").empty();
				    // $("#valor_total_repassar").append(obj[5]);
				    
				    $(".valor_total_repassar").css("display","none");						    
				    $(".valor_a_restituir").css("display","none");
				    $(".data_IRRF").css("display","none");

				}


				$(".tipo_emprestimo").click(function() {
					$(".socio_emprestimo").css("display","block");
				});

				$("#juros_emprestimo").change(function() {
					
					/*zerarCamposImpostos();

					var data_emprestimo = $("#data_emprestimo").val();
					var devolucao_emprestimo = $("#devolucao_emprestimo").val();

					var juros_emprestimo = $("#juros_emprestimo").val();					

					if( devolucao_emprestimo != '' && data_emprestimo != '' ){

						$.ajax({
							url:'ajax.php'
							, data: 'calcularValidadeJuros=calcularValidadeJuros&data_emprestimo='+data_emprestimo+'&devolucao_emprestimo='+devolucao_emprestimo+'&juros_emprestimo='+juros_emprestimo
							, type: 'post'
							, async: true
							, cache:false
							, success: function(retorno){
							    // console.log(retorno);
							    obj = JSON.parse(retorno);
								
							    if( obj[0] === 'erro' ){
							    	alert("Taxa de juros não pode ser maior que a Selic");
							    	// $("#juros_emprestimo").val("");
							    	$("#juros_emprestimo").focus();
							    	$("#juros_emprestimo").val(juros_selecionado);
							    }
							    else{
							    	juros_selecionado = $("#juros_emprestimo").val();
							    }
							}
						});
					}*/

				});
				
//				$("#data_emprestimo").focusin(function() {	
//					$("#juros_emprestimo").attr("disabled","disabled");	
//				});
			
//				$("#data_emprestimo").focusout(function() {
//					if($("#data_emprestimo" || "#devolucao_emprestimo_dias").val()){
//						$("#juros_emprestimo").attr("disabled","");
//					}
//				});
				
//				$("#devolucao_emprestimo_dias").focusin(function() {	
//					$("#juros_emprestimo").attr("disabled","disabled");					
//				});
					
//				$("#devolucao_emprestimo_dias").focusout(function() {
//					if($("#data_emprestimo" || "#devolucao_emprestimo_dias").val()){
//						$("#juros_emprestimo").attr("disabled","");
//					}			
//				});
				
				$("#data_emprestimo").change(function() {
					
					zerarCamposImpostos();
					
					/*if($("#data_emprestimo").val()) {
						if($("#devolucao_emprestimo_dias").val()){
							apresentaDadosDevolucao();
						}	
					} else {
						$("#devolucao_emprestimo").val("");
						$("#juros_emprestimo").val("");
					}*/
					

/*					zerarCamposImpostos();
					var data_emprestimo = $("#data_emprestimo").val();
					var devolucao_emprestimo = $("#devolucao_emprestimo").val();
					if( devolucao_emprestimo != '' && data_emprestimo != '' ){
						$.ajax({
							url:'ajax.php'
							, data: 'calcularJurosInicial=calcularJurosInicial&data_emprestimo='+data_emprestimo+'&devolucao_emprestimo='+devolucao_emprestimo
							, type: 'post'
							, async: true
							, cache:false
							, success: function(retorno){
							    // console.log(retorno);
							    obj = JSON.parse(retorno);
							    $("#juros_emprestimo").val(obj[0]);
							    juros_selecionado = obj[0];
							}
						});
					}*/

				});

				$("#tipo_emprestimo1").change(function() {
					zerarCamposImpostos();					
				});

				$("#tipo_emprestimo2").change(function() {
					zerarCamposImpostos();					
				});

				$("#valor_emprestimo").change(function() {
					zerarCamposImpostos();	
				});

				$("#devolucao_emprestimo_dias").change(function() {
					zerarCamposImpostos();	
				/*	var prazo = $("#devolucao_emprestimo_dias").val();					
					var data_emprestimo = $("#data_emprestimo").val();
					if( data_emprestimo === '' ){
						alert("Informe a data do empréstimo.")
						$("#data_emprestimo").focus();
					}
					$.ajax({
						url:'ajax.php'
						, data: 'calcularDataDevolucaoPraz=calcularDataDevolucaoPraz&prazo='+prazo+'&data_emprestimo='+data_emprestimo
						, type: 'post'
						, async: true
						, cache:false
						, success: function(retorno){
							// console.log(retorno);
						    obj = JSON.parse(retorno);
						    $("#devolucao_emprestimo").val(obj[0]);
						    calcularDevolucaoPrazo();
						},
						beforeSend: function() {	
							$( "body" ).unbind( "realizacalculo");
							
							$("#calcularIOF").hide();
							$("#carregandoDados").show();					
						},
						complete: function() {
							$( "body" ).bind( "realizacalculo", function() {
								realizacalculo();
							});
							
							$("#calcularIOF").show();
							$("#carregandoDados").hide();	
						}
					});*/
					
				});
				
				function apresentaDadosDevolucao(){
					
					zerarCamposImpostos();	
					var prazo = $("#devolucao_emprestimo_dias").val();					
					var data_emprestimo = $("#data_emprestimo").val();
					if($("#data_emprestimo").val()) {
						$.ajax({
							url:'ajax.php'
							, data: 'calcularDataDevolucaoPraz=calcularDataDevolucaoPraz&prazo='+prazo+'&data_emprestimo='+data_emprestimo
							, type: 'post'
							, async: true
							, cache:false
							, success: function(retorno){
								// console.log(retorno);
								obj = JSON.parse(retorno);
								$("#devolucao_emprestimo").val(obj[0]);
								calcularDevolucaoPrazo();
							},
							beforeSend: function() {	
								$( "body" ).unbind( "realizacalculo");
								
								$("#calcularIOF").hide();
								$("#carregandoDados").show();					
							},
							complete: function() {
								$( "body" ).bind( "realizacalculo", function() {
									realizacalculo();
								});
								
								$("#calcularIOF").show();
								$("#carregandoDados").hide();
							}
						});
					}
				} 

				function calcularDevolucaoPrazo(){
					zerarCamposImpostos();

					var data_emprestimo = $("#data_emprestimo").val();
					var devolucao_emprestimo = $("#devolucao_emprestimo").val();
					if( devolucao_emprestimo != '' && data_emprestimo != '' ){
						$.ajax({
							url:'ajax.php'
							, data: 'calcularJurosInicial=calcularJurosInicial&data_emprestimo='+data_emprestimo+'&devolucao_emprestimo='+devolucao_emprestimo
							, type: 'post'
							, async: true
							, cache:false
							, success: function(retorno){
							    // console.log(retorno);
							    obj = JSON.parse(retorno);
							    $("#juros_emprestimo").val(obj[0]);
							    juros_selecionado = obj[0];
							},
							beforeSend: function() {	
								$("#juros_emprestimo").attr("disabled","disabled");						
							},
							complete: function() {
								$("#juros_emprestimo").attr("disabled","");
							}
						});
					}
				}

				$('.campoRG2').mask('99.999.999-9');

				var tipo = $("#cedente_emprestimo").val();

				if( tipo === "outro" ){
					abreInserirDados();
					habilitarCampos()
				}
				else{
					abreDadosSocio(tipo);
					desabilitarCampos();
				}
	
				$( "body" ).bind( "realizacalculo", function() {
					realizacalculo();
				});
	
	
				$("#calcularIOF").click(function() {
					
					$("body").trigger("realizacalculo");

					/*if( $("#tipo_emprestimo1").attr("checked") === false && $("#tipo_emprestimo2").attr("checked") === false ){
						alert("Selecione quem irá emprestar o dinheiro e quem irá receber");
						$("#tipo_emprestimo1").focus();
						return;
					}
					
					if(checkSocio == 0){
						alert("Por favor, cadastre primeiro o sócio que receberá o empréstimo.");
						$("#nome_emprestimo").focus();
						return;
					}
					
					//Valida o preenchimento do campo
					var nome = $("#nome_emprestimo").val();
					if( nome === '' ){
						alert("Informe o Sócio");
						 $("#nome_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var rua = $("#rua_emprestimo").val();
					if( rua === '' ){
						alert("Informe a Rua");
						 $("#rua_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var bairro = $("#bairro_emprestimo").val();
					if( bairro === '' ){
						alert("Informe o Bairro");
						 $("#bairro_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var estado = $("#estado_emprestimo").val();
					if( estado === '' ){
						alert("Informe o Estado");
						 $("#estado_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var cidade = $("#cidade_emprestimo").val();
					if( cidade === '' ){
						alert("Informe a Cidade");
						 $("#cidade_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var rg = $("#rg_emprestimo").val();
					if( rg === '' ){
						alert("Informe o Rg");
						 $("#rg_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var cpf = $("#cpf_emprestimo").val();
					if( cpf === '' ){
						alert("Informe o Cpf");
						 $("#cpf_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var nacionalidade = $("#nacionalidade_emprestimo").val();
					if( nacionalidade === '' ){
						alert("Informe a Nacionalidade");
						 $("#nacionalidade_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var profissao = $("#profissao_emprestimo").val();
					if( profissao === '' ){
						alert("Informe a Profissao");
						 $("#profissao_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var estado_civil = $("#estado_civil_emprestimo").val();
					if( estado_civil === '' ){
						alert("Informe o Estado civil");
						 $("#estado_civil_emprestimo").focus();
						return;
					}
					
					//Valida o preenchimento do campo
					var valor = $("#valor_emprestimo").val();
					if( valor === '' ){
						alert("Informe o Valor");
						 $("#valor_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var data = $("#data_emprestimo").val();
					if( data === '' ){
						alert("Informe a data do empréstimo");
						 $("#data_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var devolucao = $("#devolucao_emprestimo_dias").val();
					if( devolucao === '' ){
						alert("Informe o prazo para devolução");
						 $("#devolucao_emprestimo_dias").focus();
						return;
					}
					//Valida o preenchimento do campo
					var devolucao = $("#devolucao_emprestimo").val();
					if( devolucao === '' ){
						alert("Informe a data de devolução");
						 $("#devolucao_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo					
					var juros = $("#juros_emprestimo").val();
					if( juros === '' ){
						alert("Informe o Juros");
						 $("#juros_emprestimo").focus();
						return;
					}
					var tipo = $(".tipo_emprestimo");
					//Define o tipo de emprestimo, PJ->PF ou PF->PJ
					if( $(tipo[0]).attr("checked") === true )
						var tipo = $(tipo[0]).val();
					else 
						var tipo = $(tipo[1]).val();
	
					//Envia os dados preencido via ajax para calculo parcial do emprestimo
					//realizacalculo(valor, juros, data, devolucao, tipo);
					
					$.ajax({
						url:'ajax.php'
						, data: 'calcularIOF=calcularIOF&valor='+valor+'&juros='+juros+'&data1='+data+'&data2='+devolucao+'&tipo='+tipo
						, type: 'post'
						, async: true
						, cache:false
						, success: function(retorno){
							// console.log(retorno);
						    obj = JSON.parse(retorno);
						    console.log(obj);

						    //Informa erro quando a data informada ão é valida para o esmprestimo
						    if( obj[6] === 1 ){
						    	alert("A data de devolução deve ser posterior à data do empréstimo.")
						    	return;
						    }
						    	
						    //Preenche os campos com os dados recebidos via ajax
						    $("#iof").empty();
						    $("#iof").val(obj[0]);
						    $("#valor_total").empty();
						    $("#valor_total").append(obj[1]);
						    $("#data_pagamemto").empty();
						    $("#data_pagamemto").append(obj[2]);
						    $("#data_pagamemto_emprestimo").empty();
						    $("#data_pagamemto_emprestimo").append(obj[3]);
						    $("#IRRF").empty();
						    $("#IRRF").val(obj[4]);
						    $("#valor_total_repassar").empty();
							
							$("#txtIOF").show();
							
							if(obj[0] == "Isento") {
								$("#txtIOF").hide();
							}
							
						    $("#valor_total_repassar").append(obj[7]);
						    
						    $(".valor_total_repassar").css("display","block");						    
						    $(".valor_a_restituir").css("display","block");
						    $(".data_IRRF").css("display","block");

						    // console.log(obj[7]);
						}
					});*/

				});
	
				function realizacalculo(){
					
					if( $("#tipo_emprestimo1").attr("checked") === false && $("#tipo_emprestimo2").attr("checked") === false ){
						alert("Selecione quem irá emprestar o dinheiro e quem irá receber");
						$("#tipo_emprestimo1").focus();
						return;
					}
					
					if(checkSocio == 0){
						alert("Por favor, cadastre primeiro o sócio que receberá o empréstimo.");
						$("#nome_emprestimo").focus();
						return;
					}
					
					//Valida o preenchimento do campo
					var nome = $("#nome_emprestimo").val();
					if( nome === '' ){
						alert("Informe o Sócio");
						 $("#nome_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var rua = $("#rua_emprestimo").val();
					if( rua === '' ){
						alert("Informe a Rua");
						 $("#rua_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var bairro = $("#bairro_emprestimo").val();
					if( bairro === '' ){
						alert("Informe o Bairro");
						 $("#bairro_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var estado = $("#estado_emprestimo").val();
					if( estado === '' ){
						alert("Informe o Estado");
						 $("#estado_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var cidade = $("#cidade_emprestimo").val();
					if( cidade === '' ){
						alert("Informe a Cidade");
						 $("#cidade_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var rg = $("#rg_emprestimo").val();
					if( rg === '' ){
						alert("Informe o Rg");
						 $("#rg_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var cpf = $("#cpf_emprestimo").val();
					if( cpf === '' ){
						alert("Informe o Cpf");
						 $("#cpf_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var nacionalidade = $("#nacionalidade_emprestimo").val();
					if( nacionalidade === '' ){
						alert("Informe a Nacionalidade");
						 $("#nacionalidade_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var profissao = $("#profissao_emprestimo").val();
					if( profissao === '' ){
						alert("Informe a Profissao");
						 $("#profissao_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var estado_civil = $("#estado_civil_emprestimo").val();
					if( estado_civil === '' ){
						alert("Informe o Estado civil");
						 $("#estado_civil_emprestimo").focus();
						return;
					}
					
					//Valida o preenchimento do campo
					var valor = $("#valor_emprestimo").val();
					if( valor === '' ){
						alert("Informe o Valor");
						 $("#valor_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var data = $("#data_emprestimo").val();
					if( data === '' ){
						alert("Informe a data do empréstimo");
						 $("#data_emprestimo").focus();
						return;
					}
					//Valida o preenchimento do campo
					var devolucaoDias = $("#devolucao_emprestimo_dias").val();
					if( devolucaoDias === '' ){
						alert("Informe o prazo para devolução");
						 $("#devolucao_emprestimo_dias").focus();
						return;
					}
					//Valida o preenchimento do campo
					/*var devolucao = $("#devolucao_emprestimo").val();
					if( devolucao === '' ){
						alert("Informe a data de devolução");
						 $("#devolucao_emprestimo").focus();
						return;
					}*/
					//Valida o preenchimento do campo					
					var juros = $("#juros_emprestimo").val();
					if( juros === '' ){
						alert("Informe o Juros");
						 $("#juros_emprestimo").focus();
						return;
					}
					
					//Valida o preenchimento do campo					
					var jurosAux = $("#juros_emprestimo").val();
					if( parseFloat(jurosAux.replace(",", ".")) < taxaSelic ){
						alert("O juros informado não pode ser menor que o da taxa selic.");
						 $("#juros_emprestimo").focus();
						return;
					}					
					
					
					var tipo = $(".tipo_emprestimo");
					//Define o tipo de emprestimo, PJ->PF ou PF->PJ
					if( $(tipo[0]).attr("checked") === true )
						var tipo = $(tipo[0]).val();
					else 
						var tipo = $(tipo[1]).val();
	
					//Envia os dados preencido via ajax para calculo parcial do emprestimo
					//realizacalculo(valor, juros, data, devolucao, tipo);
					
					/*$("body").bind( "nextAjax", function() {
					
						realizacalculo(valor, juros, data, devolucao, tipo);		
						
					});*/
					
					$.ajax({
						url:'ajax.php'
						, data: 'calcularIOF2=calcularIOF2&valor='+valor+'&juros='+juros+'&data1='+data+'&dias='+devolucaoDias+'&tipo='+tipo
						, type: 'post'
						, async: true
						, cache:false
						, success: function(retorno){
							// console.log(retorno);
						    obj = JSON.parse(retorno);
						    console.log(obj);

						    //Informa erro quando a data informada ão é valida para o esmprestimo
						    if( obj[6] === 1 ){
						    	alert("A data de devolução deve ser posterior à data do empréstimo.")
						    	return;
						    }
						    	
						    //Preenche os campos com os dados recebidos via ajax
						    $("#iof").empty();
						    $("#iof").val(obj[0]);
						    $("#valor_total").empty();
						    $("#valor_total").append(obj[1]);
						    $("#data_pagamemto").empty();
						    $("#data_pagamemto").append(obj[2]);
						    $("#data_pagamemto_emprestimo").empty();
						    $("#data_pagamemto_emprestimo").append(obj[3]);
						    $("#IRRF").empty();
						    $("#IRRF").val(obj[4]);
						    $("#valor_total_repassar").empty();
							$("#valor_liquido_emprestar").empty();
	
							$("#devolucao_emprestimo").val(obj[3]);
							
							$("#txtIOF").show();
							
							if(obj[0] == "Isento") {
								$("#txtIOF").hide();
							}
							
						    $("#valor_total_repassar").append(obj[7]);
							$("#valor_liquido_emprestar").append(obj[7]);
							$("#valor_liquido_emprestar").val(obj[7]);
							
							//alert(obj[8]);
							
						    
						    $(".valor_total_repassar").css("display","block");						    
						    $(".valor_a_restituir").css("display","block");
						    $(".data_IRRF").css("display","block");
						},
						beforeSend: function() {	
							$("#calcularIOF").hide();
							$("#carregandoDados").show();					
						},
						complete: function() {
							$("#calcularIOF").show();
							$("#carregandoDados").hide();
						}
					});
				}

				//Busca os dados do socio de acordo com a escolha do usuario
				$("#cedente_emprestimo").change(function() {
					var tipo = $(this).val();
					if( tipo === "outro" ){
						abreInserirDados();
						habilitarCampos()
					}
					else{
						abreDadosSocio(tipo);
						desabilitarCampos();
					}

				});
				//Busca no banco os dados do socio via ajax
				function abreDadosSocio(id){

					$.ajax({
						url:'ajax.php'
						, data: 'getDadosSocio=getDadosSocio&id='+id
						, type: 'post'
						, async: true
						, cache:false
						, success: function(retorno){
				  	    	socio = JSON.parse(retorno);
				  	    	setaDados(socio);
					  }
					});
				}
				//Seta nos inputs os dados do socio, esses dados ficam em modo disabled e ocultos até o envio do form
				function setaDados(socio){
					// console.log(socio);
					// mostraCampos();
					var nome 			= socio[0];
					var rua 			= socio[1];
					var bairro 			= socio[2];
					var estado 			= socio[3];
					var cidade 			= socio[4];
					var rg 				= socio[5];
					var cpf 			= socio[6];	 
					var nacionalidade 	= socio[7];
					var profissao 		= socio[8];
					var estado_civil 	= socio[9];

					$("#nome_emprestimo").val(nome);
					$("#rua_emprestimo").val(rua);
					$("#bairro_emprestimo").val(bairro);
					$("#estado_emprestimo").val(estado);
					$("#cidade_emprestimo").append('<option value="'+cidade+'">'+cidade+'</option>');
					$("#rg_emprestimo").val(rg);
					$("#cpf_emprestimo").val(cpf);
					$("#nacionalidade_emprestimo").val(nacionalidade);
					$("#profissao_emprestimo").val(profissao);
					$("#estado_civil_emprestimo").val(estado_civil);

				}
				//Define os campos com os dados do socio como disabled
				function desabilitarCampos(){
					$("#nome_emprestimo").attr('disabled','disabled');
					$("#rua_emprestimo").attr('disabled','disabled');
					$("#bairro_emprestimo").attr('disabled','disabled');
					$("#estado_emprestimo").attr('disabled','disabled');
					$("#cidade_emprestimo").attr('disabled','disabled');
					$("#rg_emprestimo").attr('disabled','disabled');
					$("#cpf_emprestimo").attr('disabled','disabled');
					$("#nacionalidade_emprestimo").attr('disabled','disabled');
					$("#profissao_emprestimo").attr('disabled','disabled');
					$("#estado_civil_emprestimo").attr('disabled','disabled');
				}
				//Define os campos com os dados do socio como habilidatos
				function habilitarCampos(){
					$("#nome_emprestimo").removeAttr('disabled');
					$("#rua_emprestimo").removeAttr('disabled');
					$("#bairro_emprestimo").removeAttr('disabled');
					$("#estado_emprestimo").removeAttr('disabled');
					$("#cidade_emprestimo").removeAttr('disabled');
					$("#rg_emprestimo").removeAttr('disabled');
					$("#cpf_emprestimo").removeAttr('disabled');
					$("#nacionalidade_emprestimo").removeAttr('disabled');
					$("#profissao_emprestimo").removeAttr('disabled');
					$("#estado_civil_emprestimo").removeAttr('disabled');
					$("#devolucao_emprestimo").removeAttr('disabled');
					
				}
				//Função que não é mais utilizada
				function abreInserirDados(){
					$("#nome_emprestimo").val("");
					$("#nome_emprestimo").parent().parent().fadeIn(0);

					$("#rua_emprestimo").val("");
					$("#rua_emprestimo").parent().parent().fadeIn(0);

					$("#bairro_emprestimo").val("");
					$("#bairro_emprestimo").parent().parent().fadeIn(0);

					$("#estado_emprestimo").val("");
					$("#estado_emprestimo").parent().parent().fadeIn(0);

					$("#cidade_emprestimo").val("");
					$("#cidade_emprestimo").parent().parent().fadeIn(0);

					$("#rg_emprestimo").val("");
					$("#rg_emprestimo").parent().parent().fadeIn(0);

					$("#cpf_emprestimo").val("");
					$("#cpf_emprestimo").parent().parent().fadeIn(0);

					$("#nacionalidade_emprestimo").val("");
					$("#nacionalidade_emprestimo").parent().parent().fadeIn(0);

					$("#profissao_emprestimo").val("");
					$("#profissao_emprestimo").parent().parent().fadeIn(0);

					$("#estado_civil_emprestimo").val("");
					$("#estado_civil_emprestimo").parent().parent().fadeIn(0);

				}

				function esconderCampos(){
					
					$("#nome_emprestimo").parent().parent().fadeOut(0);
					$("#rua_emprestimo").parent().parent().fadeOut(0);
					$("#bairro_emprestimo").parent().parent().fadeOut(0);
					$("#estado_emprestimo").parent().parent().fadeOut(0);
					$("#cidade_emprestimo").parent().parent().fadeOut(0);
					$("#rg_emprestimo").parent().parent().fadeOut(0);
					$("#cpf_emprestimo").parent().parent().fadeOut(0);
					$("#nacionalidade_emprestimo").parent().parent().fadeOut(0);
					$("#profissao_emprestimo").parent().parent().fadeOut(0);
					$("#estado_civil_emprestimo").parent().parent().fadeOut(0);

				}

				function mostraCampos(){
					
					$("#nome_emprestimo").parent().parent().fadeIn(0);
					$("#rua_emprestimo").parent().parent().fadeIn(0);
					$("#bairro_emprestimo").parent().parent().fadeIn(0);
					$("#estado_emprestimo").parent().parent().fadeIn(0);
					$("#cidade_emprestimo").parent().parent().fadeIn(0);
					$("#rg_emprestimo").parent().parent().fadeIn(0);
					$("#cpf_emprestimo").parent().parent().fadeIn(0);
					$("#nacionalidade_emprestimo").parent().parent().fadeIn(0);
					$("#profissao_emprestimo").parent().parent().fadeIn(0);
					$("#estado_civil_emprestimo").parent().parent().fadeIn(0);

				}
				
				esconderCampos();
			    
				$("#estado_emprestimo").change(function(event) {
					var uf = $(this).val();
					$.ajax({
						url: 'SEFIP_config.php',
						data: 'uf='+uf,
						dataType:"text",
						type:"POST",
						cache: false,
						success: function(response){
							$("#cidade_emprestimo").empty();
							$("#cidade_emprestimo").append(response);
						}
					});
				});

				$(function() {
				    $('.currency').maskMoney();
				})

				$("#enviarEmprestimo").click(function() {
					
					var nome = $("#nome_emprestimo").val();
					if( nome === '' ){
						alert("Informe o Sócio");
						 $("#nome_emprestimo").focus();
						return;
					}
					var rua = $("#rua_emprestimo").val();
					if( rua === '' ){
						alert("Informe a Rua");
						 $("#rua_emprestimo").focus();
						return;
					}
					var bairro = $("#bairro_emprestimo").val();
					if( bairro === '' ){
						alert("Informe o Bairro");
						 $("#bairro_emprestimo").focus();
						return;
					}
					var estado = $("#estado_emprestimo").val();
					if( estado === '' ){
						alert("Informe o Estado");
						 $("#estado_emprestimo").focus();
						return;
					}
					var cidade = $("#cidade_emprestimo").val();
					if( cidade === '' ){
						alert("Informe a Cidade");
						 $("#cidade_emprestimo").focus();
						return;
					}
					var rg = $("#rg_emprestimo").val();
					if( rg === '' ){
						alert("Informe o Rg");
						 $("#rg_emprestimo").focus();
						return;
					}
					var cpf = $("#cpf_emprestimo").val();
					if( cpf === '' ){
						alert("Informe o Cpf");
						 $("#cpf_emprestimo").focus();
						return;
					}
					var nacionalidade = $("#nacionalidade_emprestimo").val();
					if( nacionalidade === '' ){
						alert("Informe a Nacionalidade");
						 $("#nacionalidade_emprestimo").focus();
						return;
					}
					var profissao = $("#profissao_emprestimo").val();
					if( profissao === '' ){
						alert("Informe a Profissao");
						 $("#profissao_emprestimo").focus();
						return;
					}
					var estado_civil = $("#estado_civil_emprestimo").val();
					if( estado_civil === '' ){
						alert("Informe o Estado civil");
						 $("#estado_civil_emprestimo").focus();
						return;
					}
					var valor = $("#valor_emprestimo").val();
					if( valor === '' ){
						alert("Informe o Valor");
						 $("#valor_emprestimo").focus();
						return;
					}
					var juros = $("#juros_emprestimo").val();
					if( juros === '' ){
						alert("Informe o Juros");
						 $("#juros_emprestimo").focus();
						return;
					}

					var data = $("#data_emprestimo").val();
					if( data === '' ){
						alert("Informe a data do empréstimo");
						 $("#data_emprestimo").focus();
						return;
					}

					var devolucao = $("#devolucao_emprestimo").val();
					if( devolucao === '' ){
						alert("Informe a data de devolução");
						 $("#devolucao_emprestimo").focus();
						return;
					}

					var nome1_testemunha = $("#nome1_testemunha").val();
					if( nome1_testemunha === '' ){
						alert("Informe o nome da testemunha");
						 $("#nome1_testemunha").focus();
						return;
					}
					var rg1_testemunha = $("#rg1_testemunha").val();
					if( rg1_testemunha === '' ){
						alert("Informe o RG da testemunha");
						 $("#rg1_testemunha").focus();
						return;
					}

					var nome2_testemunha = $("#nome2_testemunha").val();
					if( nome2_testemunha === '' ){
						alert("Informe o nome da testemunha");
						 $("#nome2_testemunha").focus();
						return;
					}
					var rg2_testemunha = $("#rg2_testemunha").val();
					if( rg2_testemunha === '' ){
						alert("Informe o RG da testemunha");
						 $("#rg2_testemunha").focus();
						return;
					}




					$("#aviso-livro-caixa").css("display","block")

				});

				$(".enviarForm").click(function(event) {
					if( $(this).val() === 'sim' )
						$(".livro_caixa").val("sim");
					else
						$(".livro_caixa").val("nao");
					habilitarCampos();

					// var aux = $('.campoRG2').val();
					// $('.campoRG2').mask('99.999.999-9');
					// $('.campoRG2').focus();
					// $('.campoRG2').val(aux);

					$("#formGerarReciboEmprestimo").submit();

				});

				$(".tipo_emprestimo").click(function() {
					
					$("#tipo_emprestimo").val($(this).val());

					if( $(this).val() == '1'){
						$("#frase").empty();
						$("#frase").append("Selecione o sócio que fará o empréstimo");
					}
					else{
						$("#frase").empty();
						$("#frase").append("Selecione o sócio que receberá o empréstimo");
					}


				});

				$(".excluirItem").click(function() {
					$('#aviso-excluir').css('top',($(this).offset().top - 40 ) + 'px').fadeIn(100);
					$("#link_excluir").val($(this).attr("link"));
				});
				$(".respostaExcluir").click(function() {
					$('#aviso-excluir').css('display','none');
					if($(this).val() === 'sim')
						location.href = $("#link_excluir").val();

				});

			});
		
		</script>	

		<div class="tituloVermelho" style="margin-top:35px; margin-bottom:10px;">Como recolher os impostos</div>
			
		<ul>
		  	<li>Gere a guia dos impostos clicando no item correspondente na tabela abaixo.</li>
		</ul>
		
		<div class="tituloVermelho" style="margin-top:35px; margin-bottom:20px">Contrato de empréstimos cadastrados</div>
		<form action="emprestimos.php" method="get" accept-charset="utf-8">	
			<div style="float: left;margin-right: 10px;">	
				<select name="periodoAno">
		        	<option value="">Todos</option>
				    <option value="2015" <?php if( isset($_GET['periodoAno']) && $_GET['periodoAno'] == 2015 ) echo 'selected=""' ?>>2015</option>
				    <option value="2016" <?php if( isset($_GET['periodoAno']) && $_GET['periodoAno'] == 2016 ) echo 'selected=""'?>>2016</option>
				</select>

			</div>
			<div style="display:inline;float:left;margin-right:5px;">
		      	<input type="submit" value="Pesquisar">
		    </div>
	    </form>
		<br /><br />
		<table id="tabela_itens" width="960" cellpadding="5">
          	<tbody>
				<tr>
					<th width="7%">Ação</th>
					<th width="28%">De/Para</th>
					<!-- <th width="13%">Data do Empréstimo</th> -->
					<th width="10%">Valor original</th>
                    <th width="8%">IOF</th>
                    <th width="10%">Valor líquido</th>
					<th width="5%">Juros</th>
					<th width="11%">Valor a restituir</th>
					<th width="8%">Restituição</th>
					<th width="5%">IRRF</th>
					<th width="8%" colspan="2">Gerar Darf</th>


				</tr>
				<?php 
					
					// include 'datas.class.php';	
					$data = new Datas();
					function calcularIOF($item,$cifrao=true){
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

						if( !$cifrao )
							$aux = 'R$ ';
						else
							$aux = '';

						if( $item['tipo'] == 1 )
							return "Isento";
						return $aux.number_format( $valor , 2 , ',' , '.');

					}
					function nomeEmprestimo($item){
						if( $item['tipo']  == 1 ){
							$aux = explode(' ',$item['nome']);
							echo $aux[0];
							echo ' para ';
							$aux = explode(' ',$_SESSION['nome_userSecao']);
							echo $aux[0];
						}
						else{
							$aux = explode(' ',$_SESSION['nome_userSecao']);
							echo $aux[0];
							echo ' para ';
							$aux = explode(' ',$item['nome']);
							echo $aux[0];
						}
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
							$valorIR = number_format($valorIR,2,',','.');

						if( $cifrao )
							$aux = 'R$ ';
						else
							$aux = '';

						return $aux.$valorIR;

					}
					$filtro = '';
					if( isset( $_GET['periodoAno'] ) ):
						
						if( $_GET['periodoAno'] != 'todos' )
							$filtro = "AND YEAR(data) = ".$_GET['periodoAno'];
					
					endif;

					$emprestimos = mysql_query("SELECT * FROM emprestimos WHERE id_user = '".$_SESSION['id_empresaSecao']."' ".$filtro." ORDER BY data ASC ");
					while( $item = mysql_fetch_array($emprestimos) ){

				?>
                <tr>
        	        <?php $aux_data_emprestimo = explode(' ', $item['data']); ?>
                    <?php $aux_data_devolucao = explode(' ', $item['data_pagamento']); ?>
        	        <td class="td_calendario" align="center">
            			<a class="excluirItem" href="#tabela_itens" link="recibo_emprestimo.php?deletar&id=<?php echo $item['id']; ?>&user=<?php echo $_SESSION['id_empresaSecao']; ?>" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
                        <a target="_blank" href="download_contrato_mutuo.php?id=<?php echo $item['id']; ?>&user=<?php echo $_SESSION['id_empresaSecao']; ?>"  title="Salvar"> <i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 20px;line-height: 20px;"></i></a>
                    </td>
                    <td class="td_calendario"><?php echo nomeEmprestimo($item); ?></td>
                    <!-- <td class="td_calendario" align="left"><?php echo $data->desconverterData($aux_data_emprestimo[0]); ?></td> -->
                    <td class="td_calendario" align="left">R$ <?php echo number_format($item['valor'],2,',','.'); ?></td>
                    <td class="td_calendario" align="left"><?php echo calcularIOF($item,false); ?></td>
                   	<td class="td_calendario" align="left">R$ <?php echo number_format($item['valor']-calcularIOF($item),2,',','.'); ?></td>
                    <td class="td_calendario" align="left"><?php echo $item['juros']*100; ?>%</td>
               		<td class="td_calendario" align="left">R$ <?php echo number_format($item['valor']+$item['valor']*$item['juros'],2,',','.'); ?></td>
                    <td class="td_calendario" align="left"><?php echo $data->desconverterData($aux_data_devolucao[0]); ?></td>
                    <td class="td_calendario" align="left"><?php echo calcularIRRF($item,false); ?></td>
                    <td class="td_calendario" align="center">
                    	<?php if( floatval(str_replace('.','',calcularIOF($item))) > 0 ){ ?>
                    	<a href="#tabela_itens" onClick="abreJanela('impressao-darf.php?id=<?php echo $item['id'] ?>&id_user=<?php echo $item['id_user'] ?>','_blank','width=700, height=400, top=150, center=150, scrollbars=yes, resizable=yes');" title="Imprimir dados do IOF">IOF</a>
                    	<?php }else echo '-'; ?>
                    </td>
                    <td class="td_calendario" align="center">
                    	<?php if( floatval(str_replace('.', '', calcularIRRF($item))) > 0 ){ ?>
                    	<a href="#tabela_itens" onClick="abreJanela('impressao-irrf.php?id=<?php echo $item['id'] ?>&id_user=<?php echo $item['id_user'] ?>','_blank','width=700, height=400, top=150, left=150, scrollbars=yes, resizable=yes');" title="Imprimir dados do IRRF">IRRF</a>
                    	<?php }else echo '-'; ?>
                    </td>

				</tr>

				<?php 
					} 
				?>
			</tbody>
		</table>
		<!-- <table width="900" cellpadding="5" style="margin-bottom:25px;">
          	<tbody>
				<tr>
					<th width="70%">Recolhimento do IOF</th>
					<th width="30%"></th>
				</tr>
                <tr>
                    <td class="td_calendario" align="left">Código de Recolhimento 1150 - IOF</td>
                    <td class="td_calendario" align="left">R$ 10,00</td>
				</tr>
			</tbody>
		</table>
 -->
	</div>
</div>


<?php include 'rodape.php' ?>
