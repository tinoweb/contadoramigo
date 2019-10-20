<?php include 'header_restrita.php' ?>
<?

$arrUrl_origem = explode('/',$_SERVER['PHP_SELF']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];

$_SESSION['paginaOrigemSocios'] = $pagina_origem;

?>



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
			  url:'pro_labore_checa.php?id=<?=$_SESSION["id_userSecao"]?>&idSocio=' + $('#Nome').val() + '&data=' + $('#DataPgto').val(),
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
//					$('#formGeraRecibo').submit();
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

		// transforma a data de pagamento preenchida em timestamp para fazer uma comparação mais prática entre datas
		var dataPagto = $('#DataPgto').val().substr(3,2)+"/"+$('#DataPgto').val().substr(0,2)+"/"+$('#DataPgto').val().substr(6,4);
		dataPagto = new Date(dataPagto).getTime()/1000;

		var anoPagto = $('#DataPgto').val().substr(6,4);
		var mesPagto = $('#DataPgto').val().substr(3,2);

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

<div style="width:740px">
<div class="titulo" style="margin-bottom:20px;">Pagamentos</div>

<div class="tituloVermelho" style="float: left; margin-bottom:10px">Pró-Labore</div>
<div class="btReAbrirBox" div="video" style="display: none; padding-top: 3px; line-height: 18px; float: right;"><a href="">Vídeo de orientações</a></div>
<div style="clear:both;"></div>

<div id="video" class="box_visualizacao check_visualizacao x_visualizacao" style="border-style:solid; border-width:1px; border-color:#CCCCCC; position:absolute; left:50%; margin-left:-340px; top:148px; background-color:#fff; width:680px; display: none;">
<!-- style="border-style:solid; border-width:1px; width:680px; border-color:#CCCCCC; position:relative; left:50%; margin-left:-340px; background-color:#fff">-->
    <div style="padding:20px">
       <div class="titulo" style="text-align:left; margin-bottom:10px">Orientações Gerais</div>
       <video id="video1" width="640" height="360" controls>
        <source src="videos/prolabore.mp4" type='video/mp4'> 
        <source src="videos/prolabore.ogv" type='video/ogg'>
      <object id="video2" width="640" height="360" type="application/x-shockwave-flash" data="video_fac.swf"> 
        <param name="movie" value="video_fac.swf" />
        <param name="play" value="false" />
        <param name="flashvars" value="file=videos/prolabore.mp4" />
        </object>
        </video>
    </div>
</div>


<div style="clear:both; height:10px;"></div>

<div style="margin-bottom:20px">Utilize esta página para emitir seus recibos de pró-labore, calcular o recolhimento ao <strong>INSS</strong> e eventuais retenções de <strong>Imposto de Renda Pessoa Física</strong>. Sua empresa deverá depositar na sua conta pessoa física o valor líquido do pró-labore, demonstrado no recibo.<br />
  <ul>
  <li>O valor de seu pró-labore pode ser estipulado livremente por você, porém recomendamos observar para os seguintes pontos:</li>
  
  <li>O recolhimento ao ao INSS corresponde a 11% do seu pró-labore, mas nunca será inferior a R$ <?= $Contribuicao_Minima ?>, ou seja, não adianta estipular um pró-labore menor que R$ <?= $Salario_Minimo ?>, pois você pagará o mesmo valor de INSS. De outro lado, se o se o seu pró-labore for superior a R$ <?= $Teto_Previdenciario ?> o recolhimento não passará de R$ <?= $Contribuicao_Maxima ?>.</li>
  <li>Se o valor líquido  (descontado o INSS) for superior a R$ <?= $Limite_Insencao ?>, você precisará fazer a retenção de Imposto de Renda.</li>
  </ul>
</div>

<div class="tituloVermelho" style="margin-bottom:10px">Emissão de recibo</div>

<form id="formGeraRecibo" action="Recibo_download.php" method="post">
<input type="hidden" name="hddDependentes" id="hddDependentes" value="" />
<input type="hidden" name="hddAliquotaISS" id="hddAliquotaISS" value="" />
<input type="hidden" name="hddPercPensao" id="hddPercPensao" value="" />
<input type="hidden" name="hddCategoria_livro_caixa" value="Pró-Labore">

<div>

<!--nome -->
<label for="Nome" style="margin-right:10px">Sócio: </label> 
<select name="Nome" id="Nome">
	<option value="">Selecione </option>
<?
$query = mysql_query('SELECT idSocio, nome, dependentes, perc_pensao FROM dados_do_responsavel WHERE id = '.$_SESSION["id_userSecao"].' ORDER BY nome');
while($dados = mysql_fetch_array($query)){
	//echo "<OPTION value=\"".$dados['idSocio']."|".$dados['dependentes']."|0|".$dados['perc_pensao']."\">".$dados['nome']."</OPTION>";
	echo "<OPTION value=\"".$dados['idSocio']."\">".$dados['nome']."</OPTION>";
}
?>
</select>
&nbsp;&nbsp;&nbsp;<a class="link_atualiza" href="#">Atualizar dados dos sócios</a> <span style="font-size:11px">(inclusive número de dependentes)</span>
<div style="clear:both; height:10px"></div>

<!--data -->
<label for="DataPgto" style="margin-right:10px">Data de retirada do pró-labore (geralmente no começo do mês):</label> <input name="DataPgto" id="DataPgto" type="text" size="12" maxlength="50" class="campoData" value="" />
 (dd/mm/aaaa) <br />
<div style="clear:both; height:10px"></div>

<label for="OutraFontePagadora" style="margin-right:10px">O sócio terá outra fonte pagadora neste mês?</label><input name="OutraFontePagadora" id="OutraFontePagadora" type="checkbox" value="1" /> 
Sim 
<div style="clear:both; height:10px"></div>

<!--outra fonte pagadora -->
<div style="display:none; margin-bottom:20px" id="OutraFonte"><label for="INSSOutraFonte" style="margin-right:10px; margin-left:30px">Qual valor do INSS recolhido pela outra fonte pagadora? (R$)</label>
 <input name="INSSOutraFonte" id="INSSOutraFonte" type="text" size="30" maxlength="12" class="current" />
</div>

<!--valor bruto -->
<label for="ValorBruto" style="margin-right:10px">Valor bruto do pró-labore (R$)</label> <input name="ValorBruto" id="ValorBruto" type="text" size="30" maxlength="12" class="current" /> 
<div style="clear:both; height:10px"></div>


<!--botao calculo -->
<div style="margin-bottom:20px"><input name="btnCalculaRetencoes" id="btnCalculaRetencoes" type="button" value="Calcular retenções" onClick="javascript:calculaRetencoes();"/></div>
</div>

<div class="destaqueAzul" style="margin-bottom:20px">Retenções devidas:</div>

<div style="float:left; width:40px">INSS:</div> <input name="RetencaoINSS" id="RetencaoINSS" type="text" size="21" maxlength="50"  readonly="readonly" />
<div style="clear:both; height:5px"></div>
<div style="float:left; width:40px">IR:</div> <input name="RetencaoIR" id="RetencaoIR" type="text" size="21" maxlength="50"  readonly="readonly" />
<div style="clear:both; height:20px"></div>
<div style="margin-bottom:20px">Valor líquido a ser pago: <strong>R$</strong> <input name="ValorLiquido" id="ValorLiquido" type="text" size="21" maxlength="50" style="font-weight:bold" readonly /></div>

<div style="position: relative;">
  <input name="btnGerarRecibo" id="btnGerarRecibo" type="button" value="Gerar Recibo" />
  
  <input name="btnLimparCampos" id="btnLimparCampos" type="button" value="Limpar" style="position: relative; margin-left: 10px; display: inline; " />
  
  
   <!--BALLOM Livro Caixa -->
  <div class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; left:120px;" id="aviso-livro-caixa">
      <div style="padding:20px; font-size:12px;">
        Deseja cadastrar este pagamento no seu livro caixa?<br><br>            
        <div style="clear: both; margin: 0 auto; display: inline;">
        	<center>
          	<button id="btSIMAvisoLivroCaixa" type="button" idPagto="">Sim</button>
            <button id="btNAOAvisoLivroCaixa" type="button">Não</button>
          </center>
        </div>
        <div style="clear: both;"></div>
      </div>
    </div>
  <!--FIM DO BALLOOM Livro Caixa -->

</div>
</form>

  <div class="tituloVermelho" style="margin-top:20px; margin-bottom:10px">Como recolher os impostos</div>
  <ul>
  <li>O <strong>INSS</strong> referente ao pró-labore já estará automaticamente incluído na <strong>Gfip</strong> que sua empresa deve pagar todo mês.</li>
  <li>O recolhimento do <strong>IR</strong>, se houver, deverá ser efetuado por meio do DARF, até o dia 20 do mês seguinte aos pagamentos. O calendário fiscal do Contador Amigo o lembrará deste pagamento e um tutorial o guiará na geração da guia.</li>
  </ul>



<?
$_SESSION['categoria'] = 'pró-labore';

$categoria = $_SESSION['categoria'];


$categoria = ($categoria != "" ? strtolower($categoria) : '');

$tipoFiltro = "mes";



function get_nome_mes($numero_mes){
	$arrMonth = array(
		1 => 'janeiro',
		2 => 'fevereiro',
		3 => 'março',
		4 => 'abril',
		5 => 'maio',
		6 => 'junho',
		7 => 'julho',
		8 => 'agosto',
		9 => 'setembro',
		10 => 'outubro',
		11 => 'novembro',
		12 => 'dezembro'
		);
	return $arrMonth[(int)$numero_mes];
}

function get_ultimo_dia_mes($mes, $ano){
	switch($mes){
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			return '31';
		break;
		case 4:
		case 6:
		case 9:
		case 11:
			return '30';
		break;
		case 2:
			if($ano % 4 == 0){
				return '29';
			}else{
				return '28';
			}
		break;
	}
}


	$sql = "SELECT 
				MIN(YEAR(pgto.data_pagto)) ano
			FROM 
				dados_pagamentos pgto
			WHERE 
				pgto.id_login='" . $_SESSION["id_userSecao"] . "'
			LIMIT 0,1";
	$rsAnoInicial = mysql_fetch_array(mysql_query($sql));
	$anoInicioPagamentos = $rsAnoInicial['ano'];
?>


<script>

	

	$(document).ready(function(e) {
		
		$('#link_outro_periodo').bind('click',function(e){
			e.preventDefault();
			if($(this).html() == 'definir período maior'){
				$(this).html('definir período por mês');
				$('#hddTipoFiltro').val('periodo');
				$('#form_mes_ano').css('display','none');
				$('#form_outro_periodo').css('display','inline');
				$('#form_mes_ano').find('select').val('<?=date('Y')?>');
			}else{
				$(this).html('definir período maior');
				$('#form_mes_ano').css('display','inline');
				$('#form_mes_ano').find('select').val('<?=date('Y')?>');
				$('#form_outro_periodo').css('display','none');
				$('#hddTipoFiltro').val('mes');
				$('#dataInicio').val('');
				$('#dataFim').val('');
			}
		});
		
		// MONTA A COMBO COM OS NOMES  é passado o id do request para deixar o ultimo
		<?
		if(isset($_REQUEST["nome"])){
			$arrDadosNome = explode("|",$_REQUEST["nome"]);
			$idNome = $arrDadosNome[0];
			$filtro_categoria = $arrDadosNome[1];
		}
		?>
	
		// MONTA A COMBO COM OS NOMES  é passado o id do request para deixar o ultimo
		// FOI SOLICITADA MUDANÇA PARA EXECUTAR O FILTRO A CADA CHANGE
		$('#categoria').change(function(){
			$('#nome').val(''); // PARA QUE O FILTRO FUNCIONE CORRETAMENTE É NECESSÁRIO ZERAR A COMBO DE NOMES
			$('#form_filtro').attr('action','<?=$_SERVER['PHP_SELF']?>');
			$('#form_filtro').submit();
		});

		if($('#categoria').val() == "" || $('#categoria').val() == "sefip"){
			$('#nome').css('display','none');
		}else{
			$('#nome').css('display','inline');
			montaCombo('populaCombo','area=folha_pagto&tipo=pró-labore&id=<?=$_REQUEST["nome"]?>','nome');
		}

		// FOI SOLICITADA MUDANÇA PARA EXECUTAR O FILTRO A CADA CHANGE
		$('#nome').change(function(){
			$('#form_filtro').submit();
		});
		
		$('#cad_pagamento').bind('click',function(e){
			location.href=$('#opt_pagamento').val() + ".php";
		});
			
		$('.excluirPagamento').bind('click',function(e){
			e.preventDefault();
			var idPagto = $(this).attr("idpg");
			var idLivroCaixa = $(this).attr("idLC");
			var queryString = "", queryString2 = "";
			var mensagem = "Você tem certeza que deseja excluir este Pagamento?";


			$('#aviso-delete-livro-caixa').fadeOut(100);

			queryString = "excluir=" + idPagto;

			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				mensagem = "Deseja excluir este lançamento do Livro Caixa?";
				queryString2 = "&idLivroCaixa=" + idLivroCaixa;
			}

			$('#aviso-delete-livro-caixa').find('#mensagemDELETEPagamento').html(mensagem);

			$('#btSIMDeletePagamentoLivroCaixa').attr("idpg",idPagto);
			$('#btSIMDeletePagamentoLivroCaixa').attr("idLC",idLivroCaixa);

			$('#btNAODeletePagamentoLivroCaixa').attr("idpg",idPagto);
			$('#btNAODeletePagamentoLivroCaixa').attr("idLC",idLivroCaixa);

			$('#aviso-delete-livro-caixa').css('top',($(this).offset().top - 200) + 'px').fadeIn(100);
/*			//btSIMDeletePagamentoLivroCaixa
			
			if (confirm(mensagem)){
				location.href='folha_pagamentos_excluir.php?' + queryString + queryString2;
			}else{
				if(idLivroCaixa != 0 && idLivroCaixa != ''){
					location.href='folha_pagamentos_excluir.php?' + queryString;
				}
			}*/

		});
		
		
		$('#btSIMDeletePagamentoLivroCaixa').bind('click',function(){
			var idPagto = $(this).attr("idpg");
			var idLivroCaixa = $(this).attr("idLC");
			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				location.href='folha_pagamentos_excluir.php?' + "excluir=" + idPagto + "&idLivroCaixa=" + idLivroCaixa;
			}else{
				location.href='folha_pagamentos_excluir.php?' + "excluir=" + idPagto;
			}
		});

		$('#btNAODeletePagamentoLivroCaixa').bind('click',function(){
			var idPagto = $(this).attr("idpg");
			var idLivroCaixa = $(this).attr("idLC");
			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				location.href='folha_pagamentos_excluir.php?' + "excluir=" + idPagto;
			}else{
				$('#aviso-delete-livro-caixa').fadeOut(100);
			}
		});
		
		
	});


	// MONTAR COMBO
	function montaCombo(codigo, parametros, idCampoDestino){
		$.ajax({
			url: codigo+'.php',
			data: parametros,
			type: 'POST',
			async: false ,
			cache: false,
			success: function(result){
//					$('#resultado').html(result);
				$('#'+idCampoDestino).html(result);//arrResult[1]);
			}
			
		});
	}



	
	
	function ultimoDiaMes(mes,ano){
		switch(mes){
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
				return '31';
			break;
			case 4:
			case 6:
			case 9:
			case 11:
				return '30';
			break;
			case 2:
				if(ano % 4 == 0){
					return '29';
				}else{
					return '28';
				}
			break;
		}
	}
	
	function alterarPeriodo() {
		
		if(document.getElementById('dataInicio').value != '' && document.getElementById('dataFim').value != ''){
			
			dataInicio = document.getElementById('dataInicio').value;
			anoInicio = dataInicio.substr(6,4);
			mesInicio = dataInicio.substr(3,2);
			diaInicio = dataInicio.substr(0,2);
			dataFim = document.getElementById('dataFim').value;
			anoFim = dataFim.substr(6,4);
			mesFim = dataFim.substr(3,2);
			diaFim = dataFim.substr(0,2);
	
		}else{
			if(document.getElementById('periodoMes').value != '' && document.getElementById('periodoAno').value != ''){
				anoInicio = anoFim = document.getElementById('periodoAno').value;
				mesInicio = mesFim = document.getElementById('periodoMes').value;
				diaInicio = '01';
				diaFim = ultimoDiaMes(mesInicio,anoInicio);
				alert(diaFim);
			}
		}
		
		
		window.location='<?=$_SERVER['PHP_SELF']?>?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&busca=<?=$_REQUEST["busca"]?>&coluna=<?=$_REQUEST["coluna"]?>';
	}
	
</script>

<a name="pagamentos"></a>
  <div class="tituloVermelho" style="margin-top:20px; margin-bottom:20px;">Pagamentos efetuados</div>

    <form id="form_filtro" method="post" action="<?=$_SERVER['PHP_SELF']?>">
      
<?
		//Valores pré-definidos para a busca.
		if($_POST || $_GET){
		
			$tipoFiltro = $_REQUEST['hddTipoFiltro'];
			
			if($_REQUEST["periodoMes"] != ""){ // selecionou mes/ano
				$dataInicio = date('Y-m-d',mktime(0,0,0,$_REQUEST["periodoMes"],'01',$_REQUEST["periodoAno"]));
				$dataFim = date('Y-m-d',mktime(0,0,0,$_REQUEST["periodoMes"],get_ultimo_dia_mes($_REQUEST["periodoMes"],$_REQUEST["periodoAno"]),$_REQUEST["periodoAno"]));
				$comparaMes = $_REQUEST["periodoMes"];
				$comparaAno = $_REQUEST["periodoAno"];
			}else{
				if($_REQUEST["dataInicio"] != "" && $_REQUEST["dataFim"] != ""){ // selecionou periodo de data
					if($_REQUEST["dataFim"] != ""){
						$dataFim = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataFim"],3,2),substr($_REQUEST["dataFim"],0,2),substr($_REQUEST["dataFim"],6,4)));
					}
					if($_REQUEST["dataInicio"] != ""){
						$dataInicio = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataInicio"],3,2),substr($_REQUEST["dataInicio"],0,2),substr($_REQUEST["dataInicio"],6,4)));
					}
				}else{ // mostrar todos os meses
					$dataInicio = date('Y-m-d',mktime(0,0,0,'01','01',$_REQUEST["periodoAno"]));
					$dataFim = date('Y-m-d',mktime(0,0,0,'12','31',$_REQUEST["periodoAno"]));
					$comparaMes = $_REQUEST["periodoMes"];
					$comparaAno = $_REQUEST["periodoAno"];
				}
			}
		}
		
		if ($dataInicio == "") {
/*
			$dataInicio = date('Y-m-d',mktime(0,0,0,(date('m') == 1 ? '12' : date('m')-1) ,'01',(date('m') == 1 ? date('Y')-1 : date('Y'))));
			$comparaMes = (date('m') == 1 ? '12' : date('m')-1) ;
			$comparaAno = (date('m') == 1 ? date('Y')-1 : date('Y')) ;
*/
			$dataInicio = date('Y-m-d',mktime(0,0,0,date('m') ,'01',date('Y')));
			$comparaMes = date('m');
			$comparaAno = date('Y');
		}
		
		if ($dataFim == "") {
//			$dataFim = date('Y-m-d',strtotime("-1 days",strtotime('01-'.(date('m')).'-'.date('Y'))));
			$dataFim = date('Y-m-d',strtotime("-1 days",strtotime('01-'.(date('m')+1).'-'.date('Y'))));
		}
?>
    <div style="display:inline;float:left;margin-right:5px;">
      <select name="nome" id="nome">
      
      </select>
    </div>
    
    <div id="form_mes_ano" style="display:<?=$tipoFiltro == "mes" ? 'inline' : 'none' ?>;float:left;margin-right:5px;">
      No mês de 
      <select name="periodoMes" id="periodoMes">
        <option value="">Todos</option>
        <? for($i = 1; $i <= 12; $i++) {?>
        <option value="<?=$i?>"<?=(($comparaMes == $i) ? " selected" : "")?>><?=ucfirst(get_nome_mes($i))?></option>
        <? } ?>
      </select>
      de 
      <select name="periodoAno" id="periodoAno">
        <option value=""></option>
        <? for($i = $anoInicioPagamentos; $i <= date('Y'); $i++) {?>
        <option value="<?=$i?>"<?=($comparaAno == $i ? " selected" : "")?>><?=$i?></option>
        <? } ?>
      </select>
    </div>
    
    <div id="form_outro_periodo" style="display:<?=$tipoFiltro == "mes" ? 'none' : 'inline' ?>;float:left;margin-right:5px;">
      Período de: <input name="dataInicio" id="dataInicio" type="text" value="<?=$_REQUEST['dataInicio'] != "" ? date('d/m/Y',strtotime($dataInicio)) : ""?>" maxlength="10"  style="width:80px" class="campoData" /> 
      até: <input name="dataFim" id="dataFim" type="text" value="<?=$_REQUEST['dataFim'] != "" ? date('d/m/Y',strtotime($dataFim)) : ""?>" maxlength="10"  style="width:80px" class="campoData" /> 
    </div>
    
    <div style="display:inline;float:left;margin-right:5px;">
      <input type="hidden" name="hddTipoFiltro" id="hddTipoFiltro" value="<?=$tipoFiltro?>" />
      <input type="submit" value="Pesquisar" />
    </div>
    
    <div style="display:inline;float:left;padding-top:5px;margin-right:5px;">
      ou <a href="#" id="link_outro_periodo"><?=(($_REQUEST['dataInicio'] != "") || ($_REQUEST['dataFim'] != "") ? 'definir período por mês' : 'definir período maior' )?></a>
    </div>
     
    </form>
    <div style="clear: both; margin-bottom: 20px;"></div>
<?
	//PRO-LABORE
		// MONTAGEM DA LISTAGEM DOS PAGAMENTOS
		$sql = "SELECT 
					pgto.id_pagto
					, pgto.valor_bruto
					, pgto.INSS
					, pgto.IR
					, pgto.ISS
					, pgto.valor_liquido
					, pgto.data_pagto  
					, pgto.desconto_dependentes
					, socio.dependentes dependentes
					, socio.idSocio id
					, socio.nome nome
					, socio.cpf cpf
					, pgto.idLivroCaixa
				FROM 
					dados_pagamentos pgto
					inner join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
				WHERE 
					pgto.id_login='" . $_SESSION["id_userSecao"] . "'
				";	
										
		$resDatas = "";
		if($dataInicio != ''){
			$resDatas .= " AND pgto.data_pagto >= '" . $dataInicio . "'"; 
		}
		if($dataFim != ''){
			$resDatas .= " AND pgto.data_pagto <= '" . $dataFim . "'"; 
		}
	
		if ($_REQUEST["nome"] != ""){
			$resColuna = " HAVING 1=1 AND id = ". $idNome . "";
		}
				
		$resOrdem = "
				ORDER BY data_pagto DESC
				LIMIT 0,12
				";
		
	//	echo $sql . $resDatas . $resColuna . $resOrdem;
		
	
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
	
		$categoria = 'pró-labore';
			
	?>

      <table width="900" cellpadding="5" style="margin-bottom:25px;">
          <tr>
              <th width="7%">Ação</th>
              <th width="30%">Nome</th>
              <th width="9%">Data</th>
              <th width="9%">Valor Bruto</th>
              <th width="9%">INSS</th>
              <th width="10%">Desconto<br />Dependentes</th>
              <th width="9%">IR</th>
              <th width="8%">ISS</th>
              <th width="9%">Valor Líquido</th>
          </tr>
        
	<?	
		if(mysql_num_rows($resultado) > 0){
		
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 				= $linha["id_pagto"];
					$id 						= $linha["id"];
					$nome 					= $linha["nome"];
		
					$valor_bruto 		= $linha["valor_bruto"];
					$INSS		 				= $linha["INSS"];
					$dependentes		= $linha["dependentes"];
					$desc_dep 			= $linha["desconto_dependentes"];
					$IR			 				= $linha["IR"];
					$ISS		 				= $linha["ISS"];
					$valor_liquido	= $linha["valor_liquido"];
					$idLivroCaixa		= $linha["idLivroCaixa"];
					$data_pagto 		= (date("d/m/Y",strtotime($linha['data_pagto'])));
					
	?>
                    <tr>
                        <td class="td_calendario" align="center">
                            <a href="#" class="excluirPagamento" idpg="<?=$idPagto?>" idLC="<?=$idLivroCaixa?>" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
                            <a href="Recibo_download.php?id_pagto=<?=$idPagto?>" title="Salvar"><i class="fa fa-floppy-o iconesAzul iconesGrd"></i></a>
                        </td>
                        <td class="td_calendario"><a href="meus_dados_socio.php?editar=<?=$id?>"><?=$nome?></a></td>
                        <td class="td_calendario" align="right"><?=$data_pagto?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($INSS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($desc_dep,2,',','.') . " (" . $dependentes . ")"?></td>
                        <td class="td_calendario" align="right"><?=number_format($IR,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($ISS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_liquido,2,',','.')?></td>
                    </tr>
	<?
			
					$total_desc_dep += $desc_dep;
					$total_INSS += $INSS;
		
					$total_valor_bruto += $valor_bruto;
					$total_IR += $IR;
					$total_ISS += $ISS;
					$total_valor_liquido += $valor_liquido;	
		
					// FIM DO LOOP
				}
	
	?>
				<tr>
					<th style="background-color: #999; font-weight: normal" colspan="3" align="right">Totais:&nbsp;</th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_INSS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_desc_dep,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_IR,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_ISS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_liquido,2,',','.')?></th>
				</tr>
		<?
				$total_INSS = 0;
				$total_desc_dep = 0;
				$total_valor_bruto = 0;
				$total_IR = 0;
				$total_ISS = 0;
				$total_valor_liquido = 0;	
	
	
			}else{
	?>
                <tr>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                </tr>
	<?		
		}
		
	?>
	
		</table>






<!-- ************************************--BALLOM CBO **********************************-->
<div style="width:310px; position:absolute; top:210px; margin-left:450px; display:none; z-index:3" id="balloon_cbo">
<div style="width:8px; position:absolute; margin-left:280px; margin-top:12px"><a href="javascript:fechaDiv('balloon_cbo')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td>
    </tr>
    <tr>
      <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
      <td width="285" bgcolor="#ffff99" valign="top"><div style="width:245px; margin-left:20px; font-size:12px">
      <strong>CBO</strong> - significa Código Brasileiro de Ocupações. Informe o código da atividade desenvolvida pelo autônomo. Para saber o número, consulte <a href="http://www.mtecbo.gov.br/cbosite/pages/home.jsf" target="_blank">este site</a>. </div></td>
      <td background="images/balloon_fundo_dir.png" width="7"></td>
    </tr>
    <tr>
      <td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td>
    </tr>
  </table>
</div>
<!-- ***********************************FIM DO BALLOOM CBO***************************** -->


</div>
</div>

<?php include 'rodape.php' ?>
