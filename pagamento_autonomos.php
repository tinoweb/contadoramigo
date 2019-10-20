<?php include 'header_restrita.php' ?>


<?
$_SESSION["categoria"] = "autônomos";

$arrUrl_origem = explode('/',$_SERVER['PHP_SELF']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];
//$_SESSION['paginaOrigem'] = $pagina_origem;

$_SESSION['paginaOrigemAutonomos'] = $pagina_origem;
//echo " var PaginaOrigem = '" . $_SESSION['paginaOrigemAutonomos'] . "';";


$mostra_tomadores = false;
// checa se a empresa possui atividades ligadas à construção civil para determinar se vai ou não mostrar a combo para escolha dos tomadores
$verifica_construcao_civil = mysql_fetch_array(mysql_query("SELECT count(*) total FROM cnae c INNER JOIN dados_da_empresa_codigos cod ON c.cnae = cod.cnae WHERE cod.id='" . $_SESSION["id_empresaSecao"] . "' AND (c.cnae LIKE '41%' OR c.cnae LIKE '42%' OR c.cnae LIKE '43%')"));
if($verifica_construcao_civil['total'] > 0){
	$mostra_tomadores = true;
}
		
?>

<script>

var Arr_Salarios = new Array();
var Arr_TetoPrev = new Array();
var Arr_IR = new Array();
var contrMinima, contrMaxima, valor1, valor2, valor3, valor4, aliquota1, aliquota2, aliquota3, aliquota4, aliquota5, desconto1, desconto2, desconto3, desconto4, desconto5, descontoDep, ValorLiquido ;

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

	var CIDADE = '';
	var PREF_CAMPO_CIDADE = '';
	var CIDADE_Autonomo = '';
	var INSCR_MUNICIPAL = '';
	var RETEM_ISS = true;
<? // é utilizado para saber se há a necessidade de se validar a responta à lista de atividades ?>
	var validaRetencao = false;
	var validaRetencao_sp = false;



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


		<?
		//EM 23/01/2015 - colocada a checagem de CIDADE da empresa
		$sql_cidade = "SELECT cidade FROM dados_da_empresa WHERE id = " . $_SESSION["id_empresaSecao"];
		$rsCidade = mysql_fetch_array(mysql_query($sql_cidade));
		
		$sql_cidades = "SELECT cidade FROM cidades WHERE cidade like '%" . $rsCidade['cidade'] . "%'";
		$rsCidades = mysql_fetch_array(mysql_query($sql_cidades));
		
		if(mysql_num_rows(mysql_query($sql_cidades)) > 0){
			echo "CIDADE = '" . $rsCidade['cidade'] . "';";
		}else{
			echo "CIDADE = '';";
			echo "$('#alertaCidadeInvalida').css('display','block');";
		}
		?>		
		PREF_CAMPO_CIDADE = (CIDADE == 'São Paulo' ? '_SP' : '_N_SP'); <? // PARA SABER QUE DIV DEVE SER EXIBIDA NO FINAL DA PÁGINA ?>
		
		$('.link_atualiza').bind('click',function(e){
			e.preventDefault();
			var autonomo_selecionado = $('#NomeAutonomo').val();
			if(autonomo_selecionado > 0){
				location.href='meus_dados_autonomos.php?editar=' + autonomo_selecionado;
			}else{
				alert('Selecione um autônomo.');
//				location.href='meus_dados_autonomos.php';
			}

		});
		
		$('.link_atualiza_tomador').bind('click',function(e){
			e.preventDefault();
			var tomador_selecionado = $('#NomeTomador').val();
			if(tomador_selecionado > 0){
				location.href='meus_dados_tomadores.php?editar=' + tomador_selecionado;
			}else{
				location.href='meus_dados_tomadores.php';
			}

		});
		
		if($('#NomeAutonomo').val() != ''){
			if(CIDADE == 'São Paulo'){
				
				if(CIDADE_Autonomo == 'São Paulo'){
					if(INSCR_MUNICIPAL != ''){
//						RETEM_ISS = false;
//						validaRetencao_sp = false;
//						validaRetencao = false;
						$('#linha_lista_atividades').css('display','none');
						$('#linha_lista_atividades_sp').css('display','block');
						validaRetencao_sp = true;
						validaRetencao = false;

					}else{
//						$('#linha_lista_atividades').css('display','none');
//						$('#linha_lista_atividades_sp').css('display','block');
						$('#linha_lista_atividades').css('display','none');
						$('#linha_lista_atividades_sp').css('display','none');
						RETEM_ISS = true;
						validaRetencao_sp = false;
						validaRetencao = false;
					}
				}else{
					$('#linha_lista_atividades').css('display','block');
					$('#linha_lista_atividades_sp').css('display','none');
					validaRetencao_sp = false;
					validaRetencao = true;
				}
				
			}else{
				
				if(CIDADE != CIDADE_Autonomo){

					$('#linha_lista_atividades').css('display','block');
					$('#linha_lista_atividades_sp').css('display','none');
					validaRetencao_sp = false;
					validaRetencao = true;

				}else{

					//RETEM_ISS = false;

				}
			}
		}
		
		
		//CHANGE DA COMBO DE NOME QUE RETORNA AS VARIAVEIS RELATIVAS AO CALCULO DAS RETENCOES
		$('#NomeAutonomo').change(function(){
			if($(this).val() != ''){
				
				var $this = $(this);
				var selecionado = $this.val();
				// Reset do FORM
				$('#formGeraRPA').each (function(indice, elemento){
					this.reset();
				});
				// atribuindo novamente o valor do autonomo selecionado
				$this.val(selecionado);
				
				$('#OutraFonte').css('display','none');
				$('#linha_lista_atividades').css('display','none');
				$('#linha_lista_atividades_sp').css('display','none');
			

				$.ajax({
				  url:'pagamento_autonomos_retorna_dados_autonomo.php?aut=' + $(this).val() + '&dtPagto=' + $('#DataPgto').val(),
				  type: 'get',
				  cache:false,
				  async: true,
				  beforeSend: function(){
					$("body").css("cursor", "wait");
				  },
				  success: function(retorno){
					$("body").css("cursor", "auto");
					if(retorno != '0'){
						// QUEBRANDO O RETORNO PARA POPULAR OS CAMPOS RESPECTIVOS
						var ArrRetorno = retorno.split("|");

						$('#hddDependentes').val(ArrRetorno[2]);
						$('#hddAliquotaISS').val(ArrRetorno[3]);
						$('#hddPercPensao').val(ArrRetorno[4]);
						$('#hddSomaINSS').val(ArrRetorno[7]);
						$('#NomeOutraFonte').val(ArrRetorno[5]);
						$('#CidadeOutraFonte').val(ArrRetorno[6]);
						if(ArrRetorno[9] == ''){
							alert('O autônomo selecionado não possui cidade cadastrada. Acesse os dados deste autonomo e atualize o cadastro.');
						}
						INSCR_MUNICIPAL = ArrRetorno[8];
						CIDADE_Autonomo = ArrRetorno[9];

						if(CIDADE == 'São Paulo'){
							
							if(CIDADE_Autonomo == 'São Paulo'){
								if(INSCR_MUNICIPAL != ''){
//								RETEM_ISS = false;
//								validaRetencao_sp = false;
//								validaRetencao = false;
									$('#linha_lista_atividades').css('display','none');
									$('#linha_lista_atividades_sp').css('display','block');
									validaRetencao_sp = true;
									validaRetencao = false;
								}else{
//								$('#linha_lista_atividades').css('display','none');
//								$('#linha_lista_atividades_sp').css('display','block');
									$('#linha_lista_atividades').css('display','none');
									$('#linha_lista_atividades_sp').css('display','none');
									RETEM_ISS = true;
									validaRetencao_sp = false;
									validaRetencao = false;
								}
							}else{
								$('#linha_lista_atividades').css('display','block');
								$('#linha_lista_atividades_sp').css('display','none');
								validaRetencao_sp = false;
								validaRetencao = true;
							}
							
						}else{
							
							if(CIDADE != CIDADE_Autonomo){

								$('#linha_lista_atividades').css('display','block');
								$('#linha_lista_atividades_sp').css('display','none');
								validaRetencao_sp = false;
								validaRetencao = true;

							}else{

							//	RETEM_ISS = false;
	
							}
						}
	
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
			
			if($('#NomeAutonomo').val() == ''){
				alert('Selecione um autônomo');
				$('#OutraFonte').css('display','none');
				$('#INSSOutraFonte').val('');
				$('#NomeOutrafonte').val('');
				$('#CidadeOutrafonte').val('');
				$('#hddSomaINSS').val('');
				$('#OutraFontePagadora').attr('checked',false);
			}else{
				
				if($(this).attr('checked')==true){
					$('#OutraFonte').css('display','block');
				}else{
					$('#OutraFonte').css('display','none');
					$('#INSSOutraFonte').val('');
					$('#NomeOutrafonte').val('');
					$('#CidadeOutrafonte').val('');
					$('#hddSomaINSS').val('');
				}
			}
		});

		// FOCO NO CAMPO DO VALOR BRUTO DEVE LIMPAR OS RESULTADOS DAS RETENÇÕES
		$("#ValorBruto").focus(function(){
			$("#RetencaoIR").val('');
			$("#RetencaoINSS").val('');
			$("#RetencaoISS").val('');
			$("#ValorLiquido").val('');
		});

		// BOTAO PARA GERACAO DA DECLARACAO
		$("#geraDeclaracao").click(function(){
			$.ajax({
				  url:'declaracao_download.php',
				  data: 'aut=' + $('#NomeAutonomo').val() + '&inss=' + $('#INSSOutraFonte').val() + '&cidade=' + $('#CidadeOutraFonte').val() + '&empresa=' + $('#NomeOutraFonte').val(),
				  type: 'POST',
				  cache: false,
				  async: true
			});
		});
		
		
		// BOTAO RESPONSAVEL POR CADASTRAR OS DADOS DO PAGAMENTO NO BANCO DE DADOS E EFETUAR A GERAÇÃO DO RECIBO
		$('#btnGerarRPA').click(function(){
			var $botao = $(this);
			
			if($('#NomeAutonomo').val() == ''){
				alert('Selecione um autônomo');
				$('#NomeAutonomo').focus();
				return false;
			}
<? if($mostra_tomadores == true){ ?>
			if($('#NomeTomador').val() == ''){
				alert('Selecione um tomador');
				$('#NomeTomador').focus();
				return false;
			}
<? } ?>
			if($('#DataPgto').val() == ''){
				alert('Preencha a data de pagamento.');
				$('#DataPgto').focus();
				return false;
			}

			if($('#OutraFontePagadora').attr('checked') == true && $('#INSSOutraFonte').val() == ''){			
				alert('Preencha o valor recolhido do imposto.');
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
				alert('Não é possível gerar um RPA com Valor Líquido menor que o INSS.');
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
			  url:'pagamento_autonomos_checa.php?id=<?=$_SESSION["id_empresaSecao"]?>&aut=' + $('#NomeAutonomo').val() + '&data=' + $('#DataPgto').val(),
			  type: 'get',
			  cache: false,
			  async: false,
			  beforeSend: function(){
				$("body").css("cursor", "wait");
			  },
			  success: function(retorno){
				$("body").css("cursor", "auto");
				
				// SE HOUVER PAGAMENTO PARA O MESMO AUTONOMO NO MES, É EXIBIDA A ALERTA E O ENVIO É CANCELADO
				if(retorno == '1'){
					alert('Não foi possivel gerar o RPA. Verificamos que já existe um pagamento a este autônomo, efetuado no mesmo mês. A emissão de outro RPA poderia provocar discrepâncias no cálculo dos impostos. Delete o recibo anterior e emita um novo com o valor global.');
					return false;
				}else{
					
					
					var $data = $('#formGeraRPA').serialize();
					$.ajax({
						url:'RPA_download.php?acao=ins',
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
								$('#aviso-livro-caixa').css({
									'top':($('#caixa-botoes').offset().top - 218) + 'px'
									, 'left':($('#caixa-botoes').offset().left + 100) + 'px'
								}).fadeIn(100);

								
							}
						}
					});
					
				}
				
				$botao.css('display','none');
				$('#btnNovoPagto').css('display','block');
							
				if($('#OutraFontePagadora').attr('checked') == true){
					$('#aviso_declaracao').css('display','block');
				}
				
			  }
			});
			
		});
		
		
	

		
		$('#btSIMAvisoLivroCaixa').bind('click',function(){
			

			var $data = $('#formGeraRPA').serialize()
			, $this = $(this);
			
			var 
				$currURL = location.href
				, $Date = new Date($.formataDataEn("10/" + $('#DataPgto').val().substr(3,2) + "/" + $('#DataPgto').val().substr(6,4)))
				, $DateHoje = new Date()
				, $mesHoje = ($DateHoje.getMonth() + 1)
				, $anoHoje = ($DateHoje.getFullYear())
			;

			$data += "&nome=" + $('#NomeAutonomo option:selected').text() + "&idPagto=" + $this.attr('idPagto');

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
		});
		
		

		// BOTAO RESPONSAVEL POR ATUALIZAR A PAGINA PARA UM NOVO PAGAMENTO
		$('#btnNovoPagto').click(function(e){
			e.preventDefault();
			location.reload();
		});



		$('#linha_orientacoes').css('display','block');
		$('#divIR').css('display','block');
		$('#divIR').find('.infoOK').css('display','block');
		$('#divISS').css('display','block');
		$('#divISS').find('#divSIM' + PREF_CAMPO_CIDADE).css('display','block');
		$('#divNF').css('display','block');
		$('#cid_Dif'+PREF_CAMPO_CIDADE).css('display','block');
		$('#cid_Dif'+PREF_CAMPO_CIDADE).find('.infoOK').css('display','block');



    });

	function calculaRetencoes(){

		if($('#NomeAutonomo').val() == ''){
			alert('Selecione um autônomo');
			$('#NomeAutonomo').focus();
			return false;
		}


		if(validaRetencao == true){
			if($('input[name="retencao"]:checked').val() != '1' && $('input[name="retencao"]:checked').val() != '0'){
				alert('Selecione se o serviço prestado está entre as atividades listadas.');
				$('input[name="retencao"]').eq(0).focus();
				return false;
			}

			if($('input[name="retencao"]:checked').val() == '1'){
				RETEM_ISS = true;
			}else{
				RETEM_ISS = false;
			}

		}


		if(validaRetencao_sp == true){
			if($('input[name="retencao_sp"]:checked').val() != '1' && $('input[name="retencao_sp"]:checked').val() != '0'){
				alert('Selecione se o serviço prestado está entre as atividades listadas.');
				$('input[name="retencao_sp"]').eq(0).focus();
				return false;
			}


			if($('input[name="retencao_sp"]:checked').val() == '1'){
				RETEM_ISS = true;
			}else{
				RETEM_ISS = false;
			}

		}
		
		if($('#DataPgto').val() == ''){
			alert('Preencha a data de pagamento.');
			$('#DataPgto').focus();
			return false;
		}

		// transforma a data de pagamento preenchida em timestamp para fazer uma comparação mais prática entre datas
		//var dataPagto = (Date.UTC($('#DataPgto').val().substr(6,4),$('#DataPgto').val().substr(3,2),$('#DataPgto').val().substr(0,2)) / 1000);
		// transforma a data de pagamento preenchida em timestamp para fazer uma comparação mais prática entre datas
		var dataPagto = $('#DataPgto').val().substr(3,2)+"/"+$('#DataPgto').val().substr(0,2)+"/"+$('#DataPgto').val().substr(6,4);
		dataPagto = new Date(dataPagto).getTime()/1000;

		var anoPagto = $('#DataPgto').val().substr(6,4);
		var mesPagto = $('#DataPgto').val().substr(3,2);

 		if(anoPagto < 2011){
			alert('Não é possível realizar os cálculos pois o pagamento é muito antigo!');
			document.getElementById('RetencaoIR').value		=	"";
			document.getElementById('RetencaoINSS').value	=	"";
			document.getElementById('RetencaoISS').value	=	"";
			document.getElementById('ValorLiquido').value	=	"";
			return false;
		}
//		alert(anoPagto);

		// percorre a array com os valores dos salarios minimos para localizar a faixa correnpondente à data de pagamento preenchida
		// EM 06/08/2014 - Autonomos descontam 11% do bruto - INSS 
		//for(var i = 0; i < Arr_Salarios.length; i++){
		//	if(dataPagto >= Arr_Salarios[i][1] && dataPagto <= Arr_Salarios[i][2]){
				//alert(Arr_Salarios[i]);
// 				contrMinima = parseFloat((Arr_Salarios[i][0]) * 0.11).toFixed(2);
				contrMinima = parseFloat(document.getElementById('ValorBruto').value * 0.11).toFixed(2);
		//	}
		//}
		//alert(contrMinima);
		// percorre a array com os valores dos tetos previdenciarios para localizar a faixa correnpondente à data de pagamento preenchida
		for(var i = 0; i < Arr_TetoPrev.length; i++){
			if(dataPagto >= Arr_TetoPrev[i][1] && dataPagto <= Arr_TetoPrev[i][2]){
				//alert(Arr_TetoPrev[i]);
				contrMaxima = parseFloat((Arr_TetoPrev[i][0]) * 0.11).toFixed(2);

			}
		}
		//alert(contrMaxima);


		// percorre a array com os valores dos tetos previdenciarios para localizar a faixa correnpondente à data de pagamento preenchida
		for(var i = 0; i < Arr_IR.length; i++){
			/*if(anoPagto == Arr_IR[i][0]){
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
			}
			
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
					
		INSSOutra = document.getElementById('INSSOutraFonte').value;
		if(document.getElementById('OutraFontePagadora').checked && INSSOutra == ''){			
			alert('Preencha o valor recolhido do imposto.');
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
		if (SOMA > contrMaxima) {
			INSS = contrMaxima - INSSOutra;
		}

		if (SOMA < contrMinima) {
			INSS = contrMinima; 
		}


		// ARREDONDANDO OS DECIMAIS PARA RESOLVER A QUESTÃO DO INSS
		//INSS = Math.floor(INSS * 100) / 100;
	
		//	INSS = 205.63;//****************
		
		// calcula o desconto por quantidade de dependentes
		// PEGANDO DO VALUE DO COMBO A SEGUNDA POSIÇÃO DO VALUE = DEPENDENTES
		
		//arrValueAutonomo = document.getElementById('NomeAutonomo').value.split('|');
		//NumeroDep = arrValueAutonomo[1];
		
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
	
		// obtem o valor da pensao 
	
		//Pega percentual da pensao, transforma em float, põe no padrão americano
		//PercentPensao = arrValueAutonomo[3];
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
*/

		//Calcula o valor do IR incluindo a pensao
		IR = [(BaseCalculoIR * aliquotaIR)/100 ] - descontoIR;
		
		//calcula o ISS
		//aliquotaISS = arrValueAutonomo[2];
		if(RETEM_ISS == true){
			var aliquotaISS = document.getElementById('hddAliquotaISS').value;
			ISS = (ValorBruto * aliquotaISS)/100;
		}else{
			ISS = 0;
		}
		
		//valor liquido
		ValorLiquido = ValorBruto - INSS - IR - ISS;
	
		document.getElementById('RetencaoIR').value		=	formataCampoMoeda(limpaCaracteres(parseFloat(IR).toFixed(2)));
		document.getElementById('RetencaoINSS').value	=	formataCampoMoeda(limpaCaracteres(parseFloat(INSS).toFixed(2)));
		document.getElementById('RetencaoISS').value	=	formataCampoMoeda(limpaCaracteres(parseFloat(ISS).toFixed(2)));
		document.getElementById('ValorLiquido').value	=	formataCampoMoeda(limpaCaracteres(parseFloat(ValorLiquido).toFixed(2)));
		if(ValorLiquido < 0){
			//$('#ValorLiquido').css('color','#F00');
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
      <div class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; left:50%; margin-left: -400px; z-index: 9999;" id="aviso-delete-livro-caixa">
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
      

     <!--BALLOM Livro Caixa -->
      <div class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; left:120px; z-index: 9999;" id="aviso-livro-caixa">
          <div style="padding:20px; font-size:12px;">
            Deseja cadastrar este pagamento no seu livro caixa?<br><br>            
            <div style="clear: both; margin: 0 auto; display: inline;">
              <center>
                <button id="btSIMAvisoLivroCaixa" type="button" idPagto="" idSocio="">Sim</button>
                <button id="btNAOAvisoLivroCaixa" type="button">Não</button>
              </center>
            </div>
            <div style="clear: both;"></div>
          </div>
        </div>
      <!--FIM DO BALLOOM Livro Caixa -->
      
    </div>
    
    
<div class="principal">


<div style="position: relative; margin-bottom:30px; width:800px">

<div class="titulo" style="margin-bottom:20px;">Pagamentos</div>
<div class="tituloVermelho" style="float: left; margin-bottom:20px">Autônomos</div>
<div class="btReAbrirBox" div="video" style="display: none; padding-top: 3px; line-height: 18px; float: right;"><a href="">Vídeo de orientações</a></div>
<div style="clear:both;"></div>

<div id="video" class="box_visualizacao check_visualizacao x_visualizacao" style="border-style:solid; border-width:1px; border-color:#CCCCCC; position:absolute; left:50%; margin-left:-340px; top:148px; background-color:#fff; width:680px; display: none;">
<!-- style="border-style:solid; border-width:1px; width:680px; border-color:#CCCCCC; position:relative; left:50%; margin-left:-340px; background-color:#fff">-->
    <div style="padding:20px">
       <div class="titulo" style="text-align:left; margin-bottom:10px">Orientações Gerais</div>
       <video id="video1" width="640" height="360" controls>
        <source src="videos/autonomos.mp4" type='video/mp4'> 
        <source src="videos/autonomos.ogv" type='video/ogg'>
      <object id="video2" width="640" height="360" type="application/x-shockwave-flash" data="video_fac.swf"> 
        <param name="movie" value="video_fac.swf" />
        <param name="play" value="false" />
        <param name="flashvars" value="file=videos/autonomos.mp4" />
        </object>
      </video>
    </div>
</div>
Ao receber o pagamento pelo trabalho prestado, o profissional precisará assinar um recibo que possui a discriminação de todos os valores retidos - o RPA (Recibo de Pagamento a Autônomo). Posteriormente, você deverá enviar ao prestador os comprovantes do pagamento dos impostos.<br>
<br>
Para que você não tenha dificuldades, o Contador Amigo criou esta página<strong></strong> que calcula automaticamente as retenções devidas, emite o RPA a ser assinado pelo autônomo e mostra a você como gerar as guias de retenção.</div>


<div id="alertaCidadeInvalida" style="display: none;background-color:#FFFFFF; border-color:#CCC; border-style:solid; border-width:1px; padding:10px; margin-bottom:30px">
<span class="destaque">ATENÇÃO:</span><br /><br />
A cidade que consta em seu cadastro não é uma cidade válida. Por favor, acesse a página <a href="meus_dados_empresa.php">Dados da Empresa</a>, faça a alteração e retorne a esta página para continuar com o cálculo.
</div>


<div class="tituloVermelho" style="margin-bottom:10px">Emissão de RPA</div>

<form id="formGeraRPA" action="RPA_download.php" method="post">
<input type="hidden" name="hddDependentes" id="hddDependentes" value="" />
<input type="hidden" name="hddAliquotaISS" id="hddAliquotaISS" value="" />
<input type="hidden" name="hddPercPensao" id="hddPercPensao" value="" />
<input type="hidden" name="hddSomaINSS" id="hddSomaINSS" value="" />
<input type="hidden" name="hddValorDependentes" id="hddValorDependentes" value="" />
<input type="hidden" name="hddCategoria_livro_caixa" value="Pgto. a autônomos e fornecedores">

<div>

<!--nome -->
<label for="NomeAutonomo" style="margin-right:10px">Nome do autônomo: </label> 
<select name="NomeAutonomo" id="NomeAutonomo">
	<option value="">Selecione o autônomo</option>
<?
$query = mysql_query('SELECT id, nome FROM dados_autonomos WHERE id_login = '.$_SESSION["id_empresaSecao"].' ORDER BY nome');
while($dados = mysql_fetch_array($query)){
//	echo "<OPTION value=\"".$dados['id']."|".$dados['dependentes']."|".$dados['aliquota_ISS']."|".$dados['perc_pensao']."\">".$dados['nome']."</OPTION>";
	echo "<OPTION value=\"".$dados['id']."\">".$dados['nome']."</OPTION>";
}

?>
</select>
&nbsp;&nbsp;<a href="meus_dados_autonomos.php?act=new">cadastrar novo autônomo</a>&nbsp;&nbsp;ou&nbsp;&nbsp;<a href="#" class="link_atualiza">alterar dados de autônomo já cadastrado</a>
<div style="clear:both; height:10px"></div>



<div id="linha_lista_atividades" style="display:none;">
	<div style="clear:both; height:10px"></div>
    <div style="margin-bottom:15px">O serviço prestado está entre as atividades listadas a seguir?</div>
    
    <div style="float:left; margin-right:20px">
        <li>Instalação dos andaimes, palcos, coberturas e outras estruturas</li>
        <li>Execução de obras</li>
        <li>Demolição</li>
        <li>Edificações em geral</li>
        <li>Varrição, coleta, remoção</li>
        <li>Limpeza, manutenção e conservação</li>
        <li>Decoração e Jardinagem</li>
    </div>
    
    <div style="float:left; margin-right:20px">
        <li>Tratamento de Efluente</li>
        <li>Florestamento, reflorestamento</li>
        <li>Serviços de Escoramento</li>
        <li>Limpeza e drenagem</li>
        <li>Guarda, estacionamento</li>
        <li>Vigilância, monitoramento</li>
    </div>
    
    <div style="float:left; margin-right:20px">
        <li>Armazenamento, depósitos</li>
        <li>Serviços de lazer, diversão</li>
        <li>Transporte</li>
        <li>Fornecimento de mão de obra</li>
        <li>Feira, exposição, congresso</li>
        <li>Porto, aeroporto, rodoviário</li>
    </div>
    <div style="clear:both; height: 15px;"></div>
    <div>      
        <label>
          <input type="radio" name="retencao" value="1" />
          Sim</label>
    &nbsp;&nbsp;&nbsp;
        <label>
          <input type="radio" name="retencao" value="0" />
          Não</label>
    </div>
<div style="clear:both; margin-bottom:20px"></div>
</div>



<div id="linha_lista_atividades_sp" style="display:none;">
	<div style="clear:both; height:10px"></div>
    <div style="margin-bottom:15px">O serviço prestado está entre as atividades listadas a seguir?</div>
    
    <div style="float:left; margin-right:20px">
        <li>Cessão de andaimes, palcos, coberturas e outras estruturas de uso temporário.</li>
        <li>Varrição, coleta, remoção, incineração, tratamento, reciclagem, separação e destinação final de lixo, rejeitos e outros resíduos quaisquer.</li>
        <li>Limpeza, manutenção e conservação de vias e logradouros públicos, imóveis, chaminés, piscinas, parques, jardins e congêneres.</li>
        <li>Controle e tratamento de efluentes de qualquer natureza e de agentes físicos, químicos e biológicos.</li>
        <li>Florestamento, reflorestamento, semeadura, adubação e congêneres</li>
        <li>Vigilância, segurança ou monitoramento de bens e pessoas.</li>
        <li>Fornecimento de mão-de-obra, mesmo em caráter temporário, inclusive de empregados ou trabalhadores, avulsos ou temporários, contratados pelo prestador de serviço.</li>
        <li>Planejamento, organização e administração de feiras, exposições, congressos e congêneres.</li>
    </div>
    
    <div style="clear:both; height: 15px;"></div>
    <div>      
        <label>
          <input type="radio" name="retencao_sp" value="1" />
          Sim</label>
    &nbsp;&nbsp;&nbsp;
        <label>
          <input type="radio" name="retencao_sp" value="0" />
          Não</label>
    </div>
<div style="clear:both; margin-bottom:20px"></div>
</div>


<? if($mostra_tomadores == true){ ?>
		<input type="hidden" name="NomeTomador" id="NomeTomador" value="0">
    <!--tomador --
    
    SERÁ IMPLANTADO QUANDO HOUVER O CADASTRO DE TOMADORES
    
    
    <label for="NomeTomador" style="margin-right:10px">Tomador do serviço: </label> 
    <select name="NomeTomador" id="NomeTomador">
        <option value="">Selecione o tomador</option>
        <option value="0" selected="selected">A própria empresa</option>
    <?
    $query = mysql_query('SELECT id, nome, cei FROM dados_tomadores WHERE id_login = '.$_SESSION["id_empresaSecao"].' ORDER BY nome');
    while($dados = mysql_fetch_array($query)){
    //	echo "<OPTION value=\"".$dados['id']."|".$dados['dependentes']."|".$dados['aliquota_ISS']."|".$dados['perc_pensao']."\">".$dados['nome']."</OPTION>";
        echo "<OPTION value=\"".$dados['id']."\">".$dados['nome']." - ".$dados['cei']."</OPTION>";
    }
	//<a href="javascript:abreDiv('novo_tomador')">cadastrar novo tomador</a>&nbsp;&nbsp;ou&nbsp;&nbsp;
    ?>
    </select>
    <a href="#" class="link_atualiza_tomador">alterar dados de tomador já cadastrado</a>
    <div style="clear:both; height:10px"></div>-->
<? } ?>


<!--data -->
<label for="DataPgto" style="margin-right:10px">Data do pagamento:</label> <input name="DataPgto" id="DataPgto" type="text" size="12" maxlength="50" class="campoData" value="" /> (dd/mm/aaaa)
<div style="clear:both; height:10px"></div>

<label for="OutraFontePagadora" style="margin-right:10px">Autônomo recolheu INSS por meio de outra fonte pagadora este mês?</label>
<input name="OutraFontePagadora" id="OutraFontePagadora" type="checkbox" value="1" /> Sim 
<div style="clear:both; height:10px"></div>

<!--outra fonte pagadora -->
<div style="display:none" id="OutraFonte">

<label for="INSSOutrafonte" style="margin-left:30px; margin-right:10px">Valor recolhido do INSS:</label>(R$) <input name="INSSOutrafonte" id="INSSOutraFonte" type="text" size="30" maxlength="12" class="current" />
<div style="clear:both; height:10px"></div>

<label for="NomeOutraFonte" style="float: left; margin-left:30px; margin-right:10px">Nome da outra fonte pagadora:</label><input name="NomeOutraFonte" style="width:230px; float: left;" id="NomeOutraFonte" type="text" size="25" maxlength="50" />
<div style="clear:both; height:10px"></div>

<label for="CidadeOutraFonte" style="float: left; margin-left:30px; margin-right:10px">Cidade:</label> <input name="CidadeOutraFonte" style="width:230px; float: left;" id="CidadeOutraFonte" type="text" size="20" maxlength="50" />
<div style="clear:both; height:20px"></div>
</div>


<!--valor bruto -->
<label for="ValorBruto" style="margin-right:10px">Valor bruto cobrado pelo autônomo (R$)</label> <input name="ValorBruto" id="ValorBruto" type="text" size="30" maxlength="12" class="current" /> 
<div style="clear:both; height:10px"></div>


<!--botao calculo -->
<div style="margin-bottom:20px"><input name="btnCalculaRetencoes" id="btnCalculaRetencoes" type="button" value="Calcular retenções" onclick="javascript:calculaRetencoes();"/></div>
</div>

<div class="destaqueAzul" style="margin-bottom:20px">Retenções devidas:</div>

<div style="float:left; width:40px">INSS:</div> <input name="RetencaoINSS" id="RetencaoINSS" type="text" size="21" maxlength="50"  readonly="readonly" />
<div style="clear:both; height:5px"></div>
<div style="float:left; width:40px">IRRF:</div> <input name="RetencaoIR" id="RetencaoIR" type="text" size="21" maxlength="50"  readonly="readonly" />
<div style="clear:both; height:5px"></div>
<div style="float:left; width:40px">ISS:</div> <input name="RetencaoISS" id="RetencaoISS" type="text" size="21" maxlength="50"  readonly="readonly" />
<div style="clear:both; height:20px"></div>
<div style="margin-bottom:20px">Valor líquido a ser pago ao autônomo: <strong>R$</strong> <input name="ValorLiquido" id="ValorLiquido" type="text" size="21" maxlength="50" style="font-weight:bold" readonly /></div>

<div id="caixa-botoes">
<input name="btnGerarRPA" id="btnGerarRPA" type="button" value="Gerar RPA" />

<input type="button" id="btnNovoPagto" name="btnNovoPagto" value="Gerar novo RPA" style="display: none; " />
</div>
<!--<input name="btnLimparCampos" id="btnLimparCampos" type="button" value="Limpar" style="position: relative; margin-left: 10px; display: inline; " />-->

</form>


<div id="aviso_declaracao" style="display:none; margin-top: 20px; background-color:#FFF; border-color:#ccc; border-width:1px; border-style:solid; padding:20px;">
<strong>ATENÇÃO:</strong> você está recolhendo um valor menor de INSS pois o autônomo declarou já recolhê-lo, parcial ou integralmente, por meio de outra fonte pagadora. Para isso, a lei exige que você arquive junto com o RPA uma declaração assinada pela outra fonte pagadora, atestando o pagamento do imposto. Esta declaração, deve ser assinada também pelo autônomo. <a href="#" onclick="location.href='declaracao_download.php?aut=' + $('#NomeAutonomo').val() + '&data=' + $('#DataPgto').val()" id="geraDeclaracao">Imprima aqui</a> o modelo preenchido desta declaração e peça para o autônomo providenciar as assinaturas.
</div>






<div id="linha_orientacoes" style="display:none;">
	<div class="tituloVermelho" style="margin-top:20px; margin-bottom:20px; width:740px">Orientações</div>
    <div style="width:740px"> 
        
		<div id="divIR" style="display: none; clear: both; margin-bottom: 15px;">
            <!-- texto IR - comum para todos os assinantes -->
            <div class="destaqueAzul" style="margin-bottom:5px">Retenção de IR</div>
            <div class="infoOK" style="display: none; margin-bottom:20px">
          Com base nos pagamentos cadastrados por você, o Contador Amigo fará a apuração de todas as retenções de IR efetuadas no período. Na data do recolhimento, você será lembrado e receberá as orientações necessárias para pagá-las. </div>
		</div>
		<?php 
			$user = new Show();//Classe que pega dados do usuário
			$des = new DES();//Cria o objeto que trata os dados da DES 
			$des->setCidade($user->getCidade());//Define a cidade do usuário
			$des->getDadosDes();//Pega os dados da DES para a cidade do usuário 
		?>

		<!-- Frase sobre a DES -->
		<?php if( !$des->getPrestados() && !$des->getTomados() && $des->getTomados_outro_municipio() ) {?>
			<div id="divNF" style="display: block; clear: both; margin-bottom: 15px;">
	        	<div id="cid_Dif_SP" style="display: block;">
	                <!--texto NFTS para assinante de SP -->
	                <div class="destaqueAzul" style="margin-bottom:5px"><?php echo $des->getNomeTexto(); ?><?php echo $des->getNomeCompletoTexto(); ?></div>
	                <div class="infoOK" style="display: block; margin-bottom:20px">
	                	Quando este pagamento for executado por um prestador de outra cidade, ou estiver sujeito a retenção de ISS, 
	                	você precisará informá-lo na <?php echo $des->getNomeTexto(); ?>. <a href="des.php" title="Des">Veja como</a>.
	                </div>
				</div>
			</div>
		<?php } else if( !$des->getPrestados() && $des->getTomados() && $des->getTomados_outro_municipio() ) {?>
			<div id="divNF" style="display: block; clear: both; margin-bottom: 15px;">
	        	<div id="cid_Dif_SP" style="display: block;">
	                <!--texto NFTS para assinante de SP -->
	                <div class="destaqueAzul" style="margin-bottom:5px"><?php echo $des->getNomeTexto(); ?><?php echo $des->getNomeCompletoTexto(); ?></div>
	                <div class="infoOK" style="display: block; margin-bottom:20px">
	                	Você precisará informar  este pagamento na <?php echo $des->getNomeTexto(); ?>. <a href="des.php" title="Des">Veja como</a>.
	                </div>
				</div>
			</div>
		<?php } else if( $des->getPrestados() && $des->getTomados() && $des->getTomados_outro_municipio() ) {?>
			<div id="divNF" style="display: block; clear: both; margin-bottom: 15px;">
	        	<div id="cid_Dif_SP" style="display: block;">
	                <!--texto NFTS para assinante de SP -->
	                <div class="destaqueAzul" style="margin-bottom:5px"><?php echo $des->getNomeTexto(); ?><?php echo $des->getNomeCompletoTexto(); ?></div>
	                <div class="infoOK" style="display: block; margin-bottom:20px">
	                	Você precisará informar  este pagamento na <?php echo $des->getNomeTexto(); ?>. <a href="des.php" title="Des">Veja como</a>.
	                </div>
				</div>
			</div>
		<?php }else if( $des->getNomeTexto() == '' ) { ?>
			<div id="divNF" style="display: block; clear: both; margin-bottom: 15px;">
	        	<div id="cid_Dif_SP" style="display: block;">
	                <!--texto NFTS para assinante de SP -->
	                <div class="destaqueAzul" style="margin-bottom:5px">Declaração Eletrônica de Serviços</div>
	                <div class="infoOK" style="display: block; margin-bottom:20px">
	                	Alguns municípios exigem que a empresa registre os serviços tomados, especialmente quando o fornecedor é de outra cidade ou emite ainda nota em talão. Contate a prefeitura de seu município e veja se este é o seu caso.
	                </div>
				</div>
			</div>
		<?php } ?>
		

     	<?php 
			$user = new Show();//Classe que pega dados do usuário
			$des = new DES();//Cria o objeto que trata os dados da DES 
			$des->setCidade($user->getCidade());//Define a cidade do usuário
			$des->getDadosDes();//Pega os dados da DES para a cidade do usuário 
		?>

		<!-- Frase sobre a retenção -->
		<?php if( $des->getLink() != $des->getRetencao_iss() ) {?>
			<div id="divNF" style="display: block; clear: both; margin-bottom: 15px;">
	        	<div style="display: block;">
	                <!--texto NFTS para assinante de SP -->
	                <div class="destaqueAzul" style="margin-bottom:5px">Retenção de ISS</div>
	                <div class="infoOK" style="display: block; margin-bottom:20px">
	                <?php if($des->getRetencao_iss()):?>
	              		Quando o serviço tomado estiver sujeito a retenção de ISS, faça o recolhimento <a href="<?php echo $des->getRetencao_iss(); ?>" title="Retenção ISS">aqui</a>.
	                <?php else:?>	
	                	Quando o serviço tomado estiver sujeito a retenção de ISS, faça o recolhimento em sua cidade conforme orientado pela prefeitura.
                	<?php endif;?>
	                </div>
				</div>
			</div>
		<?php } else if( $des->getLink() == $des->getRetencao_iss() && $des->getNomeTexto() != ''  ) {?>
			<div id="divNF" style="display: block; clear: both; margin-bottom: 15px;">
	        	<div style="display: block;">
	                <!--texto NFTS para assinante de SP -->
	                <div class="destaqueAzul" style="margin-bottom:5px">Retenção de ISS</div>
	                <div class="infoOK" style="display: block; margin-bottom:20px">
	                	Ao fazer sua <?php echo $des->getNomeTexto(); ?>, será gerada a guia referente à retenção do ISS.
	                </div>
				</div>
			</div>
		<?php }else if( $des->getNomeTexto() == '' ) { ?>
			<div id="divNF" style="display: block; clear: both; margin-bottom: 15px;">
	        	<div style="display: block;">
	                <!--texto NFTS para assinante de SP -->
	                <div class="destaqueAzul" style="margin-bottom:5px">Retenção de ISS</div>
	                <div class="infoOK" style="display: block; margin-bottom:20px">
	                	Quando o serviço tomado estiver sujeito a retenção, verifique junto à prefeitura de sua cidade como fazer o recolhimento.
	                </div>
				</div>
			</div>
		<?php } ?>
 

	</div>
</div>
<div style="clear:both; margin-bottom:20px"></div>











<?

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

	// Informa o ano atual;
	$anoInicioPagamentos = date('Y');

	// Pega o menor ano para o filtro.
	$sql = "SELECT MIN(YEAR(data_pagto)) ano 
			FROM dados_pagamentos 
			WHERE id_login = '" . $_SESSION["id_empresaSecao"] . "' 
			AND id_autonomo != 0 LIMIT 1";	
	
	$consulta = mysql_query($sql);
	
	// Verifica se  existe lançamento de pagamento e pega a menor data.
	if(mysql_num_rows($consulta) > 0){
		
		$rsAnoInicial = mysql_fetch_array($consulta);
		if(!empty($rsAnoInicial['ano'])){
			$anoInicioPagamentos = $rsAnoInicial['ano'];
		}
	}
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
			montaCombo('populaCombo','area=folha_pagto&tipo=autônomos&id=<?=$_REQUEST["nome"]?>','nome');
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
			var QS = $(this).attr("qs");
			var queryString = "", queryString2 = "";
			var mensagem = "Você tem certeza que deseja excluir este Pagamento?";

			queryString = "excluir=" + idPagto;

			$('#aviso-delete-livro-caixa').fadeOut(100);

			queryString = "excluir=" + idPagto;

			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				mensagem = "Deseja excluir este lançamento do Livro Caixa?";
				queryString2 = "&idLivroCaixa=" + idLivroCaixa;
			}

			$('#aviso-delete-livro-caixa').find('#mensagemDELETEPagamento').html(mensagem);

			$('#btSIMDeletePagamentoLivroCaixa').attr("idpg",idPagto);
			$('#btSIMDeletePagamentoLivroCaixa').attr("idLC",idLivroCaixa);
			$('#btSIMDeletePagamentoLivroCaixa').attr("qs",QS);

			$('#btNAODeletePagamentoLivroCaixa').attr("idpg",idPagto);
			$('#btNAODeletePagamentoLivroCaixa').attr("idLC",idLivroCaixa);
			$('#btNAODeletePagamentoLivroCaixa').attr("qs",QS);

			$('#aviso-delete-livro-caixa').css('top',($(this).offset().top - 200) + 'px').fadeIn(100);
			
			
		});
		
				
		$('#btSIMDeletePagamentoLivroCaixa').bind('click',function(){
			var idPagto = $(this).attr("idpg");
			var idLivroCaixa = $(this).attr("idLC");
			var QS = $(this).attr("qs");
				
			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				location.href='folha_pagamentos_excluir.php?' + "excluir=" + idPagto + "&idLivroCaixa=" + idLivroCaixa + QS;
			}else{
				location.href='folha_pagamentos_excluir.php?' + "excluir=" + idPagto + QS;
			}
		});

		$('#btNAODeletePagamentoLivroCaixa').bind('click',function(){
			var idPagto = $(this).attr("idpg");
			var idLivroCaixa = $(this).attr("idLC");
			var QS = $(this).attr("qs");
			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				location.href='folha_pagamentos_excluir.php?' + "excluir=" + idPagto + QS;
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


  <div class="tituloVermelho" style="margin-top:20px; margin-bottom:20px;">Pagamentos efetuados</div>

    <form id="form_filtro" method="post" action="<?=$_SERVER['PHP_SELF']?>">
      
<?
		//Valores pré-definidos para a busca.
		if($_POST || $_GET){
		
			$tipoFiltro = $_REQUEST['hddTipoFiltro'];
			
			if($tipoFiltro == "mes"){

				if($_REQUEST["periodoMes"] != ""){ // selecionou mes/ano
					$dataInicio = date('Y-m-d',mktime(0,0,0,$_REQUEST["periodoMes"],'01',$_REQUEST["periodoAno"]));
					$dataFim = date('Y-m-d',mktime(0,0,0,$_REQUEST["periodoMes"],get_ultimo_dia_mes($_REQUEST["periodoMes"],$_REQUEST["periodoAno"]),$_REQUEST["periodoAno"]));
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
					}
				}

				$comparaMes = $_REQUEST["periodoMes"];
				$comparaAno = $_REQUEST["periodoAno"];

			} else{
				if($_REQUEST["dataFim"] != ""){
					$dataFim = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataFim"],3,2),substr($_REQUEST["dataFim"],0,2),substr($_REQUEST["dataFim"],6,4)));
				}
				if($_REQUEST["dataInicio"] != ""){
					$dataInicio = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataInicio"],3,2),substr($_REQUEST["dataInicio"],0,2),substr($_REQUEST["dataInicio"],6,4)));
				}
				
			}

		}
		
		if (empty($dataInicio)) {
			//Busca o ultimo
			$result2 = PegaUltimoPagamento();
			
			$dataInicio = date('Y-m-d',mktime(0,0,0,date('m') ,'01',date('Y')));
			$comparaMes = date('m');
			$comparaAno = date('Y');
			
			if(mysql_num_rows($result2) > 0){
				//Pega o mes e ano do ultimo pagamento
				$dadosPagamento = mysql_fetch_array($result2);
				
				$dataInicio = date('Y-m-', strtotime($dadosPagamento['data_pagto']))."01";
				$dataFim = date('Y-m-', strtotime($dadosPagamento['data_pagto']))."31";
				$comparaMes = date('m', strtotime($dadosPagamento['data_pagto']));
				$comparaAno = date('Y', strtotime($dadosPagamento['data_pagto']));
			}
		}

		if (empty($dataFim)) {
			$dataFim = date('Y-m-d',mktime(0,0,0,date('m'),date('t'),date('Y')));//date('Y-m-d',strtotime("-1 days",strtotime('01-'.(date('m')+1).'-'.date('Y'))));
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
        <? for($i = date('Y'); $i >= $anoInicioPagamentos; $i--) {?>
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
	function MontagemDaListagemDosPagamentos($dataInicio, $dataFim, $idNome){
	
		$sql = "SELECT 
					pgto.id_pagto
					, pgto.valor_bruto
					, pgto.INSS
					, pgto.IR
					, pgto.ISS
					, pgto.valor_liquido
					, pgto.data_pagto  
					, pgto.desconto_dependentes
					, pgto.idLivroCaixa
					, aut.dependentes dependentes
					, aut.id id
					, aut.nome nome
					, aut.cpf cpf
				FROM 
					dados_pagamentos pgto
					inner join dados_autonomos aut on pgto.id_autonomo = aut.id
				WHERE 
					pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'
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
				
				";
		
	//	echo $sql . $resDatas . $resColuna . $resOrdem;
		
	
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
	
		return $resultado;
	}
		 
 	function PegaUltimoPagamento(){
		$sql2 = "SELECT * FROM dados_pagamentos WHERE id_login = '" . $_SESSION["id_empresaSecao"] . "' AND id_autonomo != 0 ORDER BY data_pagto DESC LIMIT 1";
		
		//executa consulta.
		$result2 = mysql_query($sql2);
		return $result2;
	}
		 
	//Verifica se existe cadastro na data informada.
	$resultado = MontagemDaListagemDosPagamentos($dataInicio, $dataFim, $idNome);	
			
	?>

      <table width="900" cellpadding="5" style="margin-bottom:25px;">
          <tr>
            <th width="7%">Ação</th>
            <th width="40%">Nome</th>
            <th width="9%">Data</th>
            <th width="9%">Valor Bruto</th>
            <th width="9%">INSS</th>
<!--            <th width="10%">Desconto<br />Dependentes</th>-->
            <th width="9%">IR</th>
            <th width="8%">ISS</th>
            <th width="9%">Valor Líquido</th>
          </tr>
        
	<?	
		if(mysql_num_rows($resultado) > 0){
		
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 	= $linha["id_pagto"];
					$id 	= $linha["id"];
					$nome 	= $linha["nome"];
		
					$valor_bruto 	= $linha["valor_bruto"];
					$INSS		 	= $linha["INSS"];
					$dependentes	= $linha["dependentes"];
					$desc_dep 		= $linha["desconto_dependentes"];
					$IR			 	= $linha["IR"];
					$ISS		 	= $linha["ISS"];
					$valor_liquido 	= $linha["valor_liquido"];
					$idLivroCaixa		= $linha["idLivroCaixa"];
					$data_pagto 	= (date("d/m/Y",strtotime($linha['data_pagto'])));
					
					$idLoginPagamento = "";
					if(isset($_REQUEST['nome']) && $_REQUEST['nome'] != ''){
						$arrComboNomes = explode("|",$_REQUEST['nome']);
						$idLoginPagamento = $arrComboNomes[0];
					}
				
	?>
                    <tr>
                        <td class="td_calendario" align="center">
                          <a href="#" class="excluirPagamento" idpg="<?=$idPagto?>" idLC="<?=$idLivroCaixa?>" qs="&categoria=<?=$categoria?>&nome=<?=$idLoginPagamento?>&dataInicio=<?=date('d/m/Y',strtotime($dataInicio))?>&dataFim=<?=date('d/m/Y',strtotime($dataFim))?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
<!--                            <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este Pagamento?'))location.href='folha_pagamentos_excluir.php?excluir=<?=$idPagto?>&categoria=<?=$categoria?>&dataInicio=<?=date('d/m/Y',strtotime($dataInicio))?>&dataFim=<?=date('d/m/Y',strtotime($dataFim))?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>-->
                            <a href="RPA_download.php?id_pagto=<?=$idPagto?>" title="Salvar"><i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 20px;line-height: 20px;"></i></a>
                        </td>
                        <td class="td_calendario"><a href="meus_dados_autonomos.php?editar=<?=$id?>"><?=$nome?></a></td>
                        <td class="td_calendario" align="right"><?=$data_pagto?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($INSS,2,',','.')?></td>
<!--                        <td class="td_calendario" align="right"><?=number_format($desc_dep,2,',','.') . " (" . $dependentes . ")"?></td>-->
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
<!--					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_desc_dep,2,',','.')?></th>-->
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
<!--          <td class="td_calendario">&nbsp;</td>-->
        </tr>
	<?		
		}
		
	?>
	
		</table>



</div>


<!-- ************************************--BALLOM ISS **********************************-->
<div style="width: 310px; position: absolute; top: 553px; margin-left: 515px; display: none; z-index: 3" id="balloon_iss">
<div style="width:8px; position:absolute; margin-left:280px; margin-top:12px"><a href="javascript:fechaDiv('balloon_iss')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td>
    </tr>
    <tr>
      <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
      <td width="285" bgcolor="#ffff99" valign="top"><div style="width:245px; margin-left:20px; font-size:12px">
      <strong>Alíquota ISS</strong> - refere-se ao percentual que o autônomo deve pagar de <strong>ISS</strong>. A taxa varia de acordo com o município e a atividade desenvolvida. Pergunte a seu prestador qual a alíquota dele e se há algum tipo de isenção, ou consulte a tabela de ISS do seu município. Na dúvida, você pode deixar 5%, que é o teto permitido por lei federal. Dessa forma você estará seguro.  
      
      </div></td>
      <td background="images/balloon_fundo_dir.png" width="7"></td>
    </tr>
    <tr>
      <td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td>
    </tr>
  </table>
</div>
<!-- ***********************************FIM DO BALLOOM ISS***************************** -->



<!-- ************************************--BALLOM CBO **********************************-->
<div style="width:310px; position:absolute; top:190px; margin-left:475px; display:none; z-index:3" id="balloon_cbo">
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


<!--layer para cadastramento de novo autonomo (deve estar relacionado como a cadastro de autonomo na seção meus dados -->
<script>

$(document).ready(function(e) {
	// BOTAO RESPONSAVEL PELO ENVIO DOS DADOS DO NOVO AUTONOMO CADASTRADO NA JANELA MODAL DESTA MESMA PAGINA
    $('#btCadastroNovo').click(function(){
		if($('#cpf').val()!='' && $('#pis').val() != ''){
			$.ajax({
				url:'meus_dados_autonomos_checa.php?idLogin=' + $('#id_login').val() + '&cpf=' + $('#cpf').val() + '&pis=' + $('#pis').val(),
				type: 'get',
				cache: false,
				async: true,
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe um autônomo cadastrado com esses dados!');  
						return false;
					}
				}
			});
		}

		if( $('#nome').val() == ''){					alert('Preencha o campo ' + $('#nome').attr('alt')); return false}
		if( $('#CBO').val() == ''){						alert('Preencha o campo ' + $('#CBO').attr('alt')); return false}
		if( $('#cpf').val() == ''){						alert('Preencha o campo ' + $('#cpf').attr('alt')); return false}
		if( $('#rg').val() == ''){						alert('Preencha o campo ' + $('#rg').attr('alt')); return false}
		if( $('#orgao_emissor').val() == ''){			alert('Preencha o campo ' + $('#orgao_emissor').attr('alt')); return false}
		if( $('#pis').val() == ''){						alert('Preencha o campo ' + $('#pis').attr('alt')); return false}
		if( $('#NumeroDep').val() == ''){				alert('Preencha o campo ' + $('#NumeroDep').attr('alt')); return false}
		if( $('#endereco').val() == ''){				alert('Preencha o campo ' + $('#endereco').attr('alt')); return false}
		if( $('#cep').val() == ''){						alert('Preencha o campo ' + $('#cep').attr('alt')); return false}
		if( $('#cidade').val() == ''){					alert('Preencha o campo ' + $('#cidade').attr('alt')); return false}
		if( $('#estado').val() == ''){					alert('Preencha o campo ' + $('#estado').attr('alt')); return false}
		if( $('#tipo_servico').val() == ''){			alert('Preencha o campo ' + $('#tipo_servico').attr('alt')); return false}
		if( $('#AliquotaISS').val() == ''){				alert('Preencha o campo ' + $('#AliquotaISS').attr('alt')); return false}

		var arrData = $('#novoAutonomo').serialize();
		$.ajax({
			url: 'dados_autonomo_gravar.php',
			data: arrData,
			type: 'POST',
			cache:false,
			beforeSend: function(){
				$('body').css('cursor','wait');
			},
			success: function(result){
				$('body').css('cursor','default');
				if(result != ""){
					alert('Autônomo cadastrado');
					$('#novo_autonomo').css('display','none');
					$('#NomeAutonomo').html(result);
					var arrForm = $('#novoAutonomo').serializeArray();
					$.each(arrForm, function(i, objCampo){
						switch($("#novoAutonomo :input[name="+this.name+"]").attr('type')){
							case 'text':
								$("#novoAutonomo :input[name="+this.name+"]").attr('value','');
							break;
							case 'select-one':
								$('#'+this.name).attr('value','');
							break;
							case 'checkbox':
								$("#novoAutonomo :input[name="+this.name+"]").attr('checked','');
							break;
							case 'radio':
								$("#novoAutonomo :input[name="+this.name+"]").attr('checked','');
							break;
						}
					});
				}else{
					alert('Erro no cadastramento do autônomo');
				}
			}
		});
	});
})
</script>

<div id="novo_autonomo" class="layer_branco" style="border:#ccc solid 1px;  position:absolute; left:50%; top:130px; margin-left:-200px; display:none">
<div style="text-align:right; margin-right:20px; margin-top:15px"><a href="javascript:fechaDiv('novo_autonomo')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
<div style="padding:20px; padding-top:0px">
<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Autônomos</div>
<form name="novoAutonomo" id="novoAutonomo" method="post" style="display:inline">
<input type="hidden" name="id_login" value="<?=$_SESSION["id_empresaSecao"]?>" />
    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px;"><label for="nome">Nome:</label></div><div style="float:left;"><input name="nome" id="nome" type="text" size="50" maxlength="50" alt="Nome" /></div>
    </div>

    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="nome">CBO:</label></div><div style="float:left;"><input name="CBO" id="CBO" type="text" size="8" maxlength="10" alt="CBO" /> <a href="javascript:abreDiv('balloon_cbo')"><img src="images/dica.gif" width="13" height="14" border="0" align="texttop" /></a></div>
    <div style="clear:both;"></div></div>

    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cpf">CPF:</label></div><div style="float:left;"><input name="cpf" id="cpf" type="text" size="14" maxlength="14" alt="CPF" class="cpf" /></div>
    <div style="clear:both;"></div></div>
    
    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="rg">RG:</label></div><div style="float:left;"><input name="rg" id="rg" type="text" size="14" maxlength="12" alt="RG" class="rg" /> <label for="orgao_emissor">&nbsp;&nbsp;Órgão Emissor:</label> <input name="orgao_emissor" id="orgao_emissor" type="text" size="14" maxlength="25" alt="Órgão Emissor" /></div>
    <div style="clear:both;"></div></div>

    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="pis">PIS:</label></div><div style="float:left;"><input name="pis" id="pis" type="text" size="14" maxlength="14" alt="PIS" class="pis" /></div>
    <div style="clear:both;"></div></div>

    <div style="height:25px;">    
    <div style="float:left; width:110px; text-align:right; margin-right:3px; padding-top:3px"><label for="NumeroDep">Nº de dependentes:</label></div><div style="float:left;"><input name="NumeroDep" id="NumeroDep" type="text" size=2 maxlength="2" value="0" alt="Nº de Dependentes" /> <span style="font-size:10px">(Somente os declarados como dependentes no IR)</span></div>
    <div style="clear:both;"></div></div>

    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="endereco">Endereço:</label></div><div style="float:left;"><input name="endereco" id="endereco" type="text" size="50" maxlength="50" alt="Endereço" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cep">Cep:</label></div><div style="float:left;"><input name="cep" id="cep" type="text" maxlength="9" alt="CEP" class="cep" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cidade">Cidade:</label></div><div style="float:left;"><input name="cidade" id="cidade" type="text" maxlength="50" alt="Cidade" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:40px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:4px"><label for="estado">Estado:</label></div>
    <div style="float:left;">
    <select name="estado" id="estado" alt="Estado">
          <option value="AC">AC</option>
          <option value="AL">AL</option>
          <option value="AM">AM</option>
          <option value="AP">AP</option>
          <option value="BA">BA</option>
          <option value="CE">CE</option>
          <option value="DF">DF</option>
          <option value="ES">ES</option>
          <option value="GO">GO</option>
          <option value="MA">MA</option>
          <option value="MG">MG</option>
          <option value="MS">MS</option>
          <option value="MT">MT</option>
          <option value="PA">PA</option>
          <option value="PB">PB</option>
          <option value="PE">PE</option>
          <option value="PI">PI</option>
          <option value="PR">PR</option>
          <option value="RJ">RJ</option>
          <option value="RN">RN</option>
          <option value="RO">RO</option>
          <option value="RR">RR</option>
          <option value="RS">RS</option>
          <option value="SC">SC</option>
          <option value="SE">SE</option>
          <option value="SP" selected>SP</option>
          <option value="TO">TO</option>
        </select></div>
    <div style="clear:both;"></div>
    </div>
    
     <div style="height:50px;">
     <label for="tipo_servico">Tipo de Serviço a ser desenvolvido:</label><br />
	 <input name="tipo_servico" id="tipo_servico" type="text" size="60" maxlength="250" alt="Tipo de Serviço" />
    </div>
    
     <div style="height:50px;">
    <div style="float:left"><label for="pensao">Autônomo paga pensão alimentícia?</label> <input name="pensao" id="pensao" type="checkbox" value="1" />&nbsp;<label for="PercentPensao">Qual o percentual?</label> <input name="PercentPensao" id="PercentPensao" type="text" size=4 maxlength="50" />%</div>
    <div style="clear:both;"></div>
    <div style="font-size:10px">(Considerar apenas a pensão alimentícia quando definida em acordo judicial)</div>
    </div>
    
    <div style="height:25px;">
    <div style="float:left; width:113px; margin-right:3px; padding-top:3px"><label for="inscricao_municipal">Inscrição Municipal:</label></div><div style="float:left"><input name="inscricao_municipal" id="inscricao_municipal" type="text" size="12" maxlength="50" va;lue="não tem" alt="Inscrição Municipal" /></div>
    <div style="clear:both;"></div></div>
    
    <div style="float:left; width:113px; text-align:right; margin-right:3px; padding-top:3px"><label for="estado">Alíquota ISS:</label></div><input name="AliquotaISS" id="AliquotaISS" type="text" size="6" maxlength="6" value="5%" alt="Alíquota ISS" /> <a href="javascript:abreDiv('balloon_iss')"><img src="images/dica.gif" width="13" height="14" border="0" align="texttop" /></a>
    <div style="clear:both; height:15px"></div>
    
   <input name="btCadastroNovo" id="btCadastroNovo" type="button" value="Cadastrar" />
</form>

</div>
</div>
<!--fim do layer para cadastro de autônomo -->



<!--layer para cadastramento de novo tomador (deve estar relacionado como a cadastro de tomador na seção meus dados -->
<script>

$(document).ready(function(e) {
	// BOTAO RESPONSAVEL PELO ENVIO DOS DADOS DO NOVO TOMADOR CADASTRADO NA JANELA MODAL DESTA MESMA PAGINA
    $('#btCadastroNovoTomador').click(function(){
		if($('#CEI').val()!=''){
			$.ajax({
				url:'meus_dados_tomadores_checa.php?idLogin=' + $('#id_login').val() + '&cei=' + $('#CEI').val(),
				type: 'get',
				cache: false,
				async: true,
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe um tomador cadastrado com esses dados!'); 
						return false;
					}
				}
			});
		}

		if( $('#nomeTomador').val() == ''){				alert('Preencha o campo ' + $('#nomeTomador').attr('alt')); return false}
		if( $('#CEI').val() == ''){						alert('Preencha o campo ' + $('#CEI').attr('alt')); return false}
		if( $('#enderecoTomador').val() == ''){			alert('Preencha o campo ' + $('#enderecoTomador').attr('alt')); return false}
		if( $('#bairroTomador').val() == ''){			alert('Preencha o campo ' + $('#bairroTomador').attr('alt')); return false}
		if( $('#cepTomador').val() == ''){				alert('Preencha o campo ' + $('#cepTomador').attr('alt')); return false}
		if( $('#cidadeTomador').val() == ''){			alert('Preencha o campo ' + $('#cidadeTomador').attr('alt')); return false}
		if( $('#estadoTomador').val() == ''){			alert('Preencha o campo ' + $('#estadoTomador').attr('alt')); return false}

		var arrData = $('#novoTomador').serialize();
		$.ajax({
			url: 'dados_tomador_gravar.php',
			data: arrData,
			type: 'POST',
			cache:false,
			beforeSend: function(){
				$('body').css('cursor','wait');
			},
			success: function(result){
				$('body').css('cursor','default');
				if(result != ""){
					alert('Tomador cadastrado');
					$('#novo_tomador').css('display','none');
					$('#NomeTomador').html(result);
					var arrForm = $('#novoTomador').serializeArray();
					$.each(arrForm, function(i, objCampo){
						switch($("#novoTomador :input[name="+this.name+"]").attr('type')){
							case 'text':
								$("#novoTomador :input[name="+this.name+"]").attr('value','');
							break;
							case 'select-one':
								$('#'+this.name).attr('value','');
							break;
							case 'checkbox':
								$("#novoTomador :input[name="+this.name+"]").attr('checked','');
							break;
							case 'radio':
								$("#novoTomador :input[name="+this.name+"]").attr('checked','');
							break;
						}
					});
				}else{
					alert('Erro no cadastramento do tomador');
				}
			}
		});
	});
})
</script>

<div id="novo_tomador" class="layer_branco" style="border:#ccc solid 1px;  position:absolute; left:50%; top:130px; margin-left:-200px; display:none">
<div style="text-align:right; margin-right:20px; margin-top:15px"><a href="javascript:fechaDiv('novo_tomador')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
<div style="padding:20px; padding-top:0px">
<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Tomadores</div>
<form name="novoTomador" id="novoTomador" method="post" style="display:inline">
<input type="hidden" name="id_login" value="<?=$_SESSION["id_empresaSecao"]?>" />
    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px;"><label for="nomeTomador">Nome:</label></div><div style="float:left;"><input name="nomeTomador" id="nomeTomador" type="text" size="50" maxlength="50" alt="Nome" /></div>
    </div>

    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="CEI">CEI:</label></div><div style="float:left;"><input name="CEI" id="CEI" type="text" size="16" maxlength="15" alt="CEI" class="cei" /></div>
    <div style="clear:both;"></div></div>

    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="enderecoTomador">Endereço:</label></div><div style="float:left;"><input name="enderecoTomador" id="enderecoTomador" type="text" size="50" maxlength="50" alt="Endereço" /></div>
    <div style="clear:both;"></div>
    </div>

    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="bairroTomador">Bairro:</label></div><div style="float:left;"><input name="bairroTomador" id="bairroTomador" type="text" size="50" maxlength="20" alt="Bairro" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cepTomador">Cep:</label></div><div style="float:left;"><input name="cepTomador" id="cepTomador" type="text" maxlength="9" alt="CEP" class="cep" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cidadeTomador">Cidade:</label></div><div style="float:left;"><input name="cidadeTomador" id="cidadeTomador" type="text" maxlength="50" alt="Cidade" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:40px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:4px"><label for="estadoTomador">Estado:</label></div>
    <div style="float:left;">
    <select name="estadoTomador" id="estadoTomador" alt="Estado">
          <option value="AC">AC</option>
          <option value="AL">AL</option>
          <option value="AM">AM</option>
          <option value="AP">AP</option>
          <option value="BA">BA</option>
          <option value="CE">CE</option>
          <option value="DF">DF</option>
          <option value="ES">ES</option>
          <option value="GO">GO</option>
          <option value="MA">MA</option>
          <option value="MG">MG</option>
          <option value="MS">MS</option>
          <option value="MT">MT</option>
          <option value="PA">PA</option>
          <option value="PB">PB</option>
          <option value="PE">PE</option>
          <option value="PI">PI</option>
          <option value="PR">PR</option>
          <option value="RJ">RJ</option>
          <option value="RN">RN</option>
          <option value="RO">RO</option>
          <option value="RR">RR</option>
          <option value="RS">RS</option>
          <option value="SC">SC</option>
          <option value="SE">SE</option>
          <option value="SP" selected>SP</option>
          <option value="TO">TO</option>
        </select></div>
    <div style="clear:both;"></div>
    </div>
    
   <input name="btCadastroNovoTomador" id="btCadastroNovoTomador" type="button" value="Cadastrar" />
</form>

</div>
</div>
<!--fim do layer para cadastro de tomador -->


<!-- ************************************* Layer atividades sujeitas a retenção **************************** -->
<div class="layer_branco" id="lista" style="left:50%; margin-left:-250px; top:100px; display:none; position:absolute; width:540px; border:1px; border-style:solid; border-color:#CCC" >
<div style="padding:20px">

<div style="text-align:right; float:right; margin-left:10px; margin-top:0px"><a href="javascript:fechaDiv('lista')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
<div class="titulo" style="text-align:left; margin-bottom:10px">Atividades sujeitas a retenção </div>

<div style = "overflow:auto; text-align:left; width:500px; height:300px; " >
<ul id="lista_" style="margin-left:20px; padding-left:15px; padding-right:20px">
<li style="margin-bottom:10px"> Instalação dos andaimes, palcos, coberturas e outras estruturas;</li>
<li style="margin-bottom:10px"> Execução de obras de construção civil;</li>
<li style="margin-bottom:10px"> Serviços de demolição; </li>
<li style="margin-bottom:10px"> Edificações em geral, estradas, pontes, portos e congêneres; </li>
<li style="margin-bottom:10px"> Execução da varrição, coleta, remoção, incineração, tratamento, reciclagem, separação e destinação final de lixo, rejeitos e outros resíduos quaisquer;</li>
<li style="margin-bottom:10px"> Execução da limpeza, manutenção e conservação de vias e logradouros públicos, imóveis, chaminés, piscinas, parques, jardins e congêneres;</li>
<li style="margin-bottom:10px"> Execução da decoração e jardinagem, do corte e poda de árvores; </li>
<li style="margin-bottom:10px"> Controle e tratamento do efluente de qualquer natureza e de agentes físicos, químicos e biológicos;</li>
<li style="margin-bottom:10px"> Florestamento, reflorestamento, semeadura, adubação e congêneres; </li>
<li style="margin-bottom:10px"> Execução dos serviços de escoramento, contenção de encostas e congêneres; </li>
<li style="margin-bottom:10px"> Limpeza e dragagem; </li><br />
<li style="margin-bottom:10px"> Serviços de guarda, estacionamento, armazenamento e vigilância de bens e pessoas;</li>
<li style="margin-bottom:10px"> Armazenamento, depósito, carga, descarga, arrumação e guarda de bens; </li>
<li style="margin-bottom:10px"> Execução dos serviços de diversão, lazer, entretenimento e congêneres; </li>
<li style="margin-bottom:10px"> Serviço de transporte municipal de qualquer natureza; </li>
<li style="margin-bottom:10px"> Cessão de mão-de-obra; </li>
<li style="margin-bottom:10px"> Feira, exposição, congresso ou congênere a que se referir o planejamento, organização e administração destes serviços; </li>
<li style="margin-bottom:10px"> Porto, aeroporto, ferroporto, terminal rodoviário, ferroviário ou metroviário.</li>
</ul>
</div>

</div>
</div>
<!-- ************************************* fim do Layer atividades sujeitas a retenção **************************** -->

<!--O profissional autônomo nessa situação deve declarar, no verso do recibo de pagamento: "Profissional autônomo não estabelecido, estando isento do ISS e dispensado de inscrição municipal, conforme art. Inciso XIX do artigo 12 da Lei nº 691/84 com as alterações da Lei 3.691/03 e § 2º do art. 153 do Decreto 10.514, de 08 de outubro de 1991". -->
</div>

<?php include 'rodape.php' ?>
