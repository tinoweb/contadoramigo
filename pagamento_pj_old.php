<?php include 'header_restrita.php' ?>


<?
$_SESSION['categoria'] = 'pessoa jurídica';

//unset($_SESSION['paginaOrigem']); // variavel que controla o retorno da página de cadastro de PJ
$arrUrl_origem = explode('/',$_SERVER['PHP_SELF']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];

$_SESSION['paginaOrigemPJ'] = $pagina_origem;



?>

<script>

var SIMPLES = 0;<? //EM 25/07/2014 - colocada a checagem de empresa optante do SIMPLES para cálculo de alíquota ?>
var validaRetencao = false;<? // é utilizado para saber se há a necessidade de se validar a responta à lista de atividades ?>
var validaRetencao_sp = false;
var validaISS = false;<? // é utilizado para saber se há a necessidade de se validar o campo ISS ?>
var pagaISS = true;<? // utilizada na função de cálculo para checar se deve ou não preencher o campo ISS ?>
var issInalterado = false; <? // utilizada para caso o assinante e a empresa fornecedora sejam da mesma cidade <> de são paulo - neste caso este campo deve ser sempre (0) zero?>

var valEmpresa = '';

var mensagemBallonISS = '';

var CIDADE_Fornecedora = '';
var MEI_Fornecedora = '';
var CPOM_Fornecedora = '';

var codigo_darf = 0;

<?
//EM 25/11/2014 - colocada a checagem de CIDADE da empresa
$sql_cidade = "SELECT cidade FROM dados_da_empresa WHERE id = " . $_SESSION["id_empresaSecao"];
$rsCidade = mysql_fetch_array(mysql_query($sql_cidade));

$sql_cidades = "SELECT cidade FROM cidades WHERE cidade like '%" . $rsCidade['cidade'] . "%'";
$rsCidades = mysql_fetch_array(mysql_query($sql_cidades));

if(mysql_num_rows(mysql_query($sql_cidades)) > 0){
	echo "var CIDADE = '" . $rsCidade['cidade'] . "';";
}else{
	echo "var CIDADE = '';";
	echo "$('#alertaCidadeInvalida').css('display','block');";
}
?>		
var PREF_CAMPO_CIDADE = (CIDADE == 'São Paulo' ? '_SP' : '_N_SP'); <? // PARA SABER QUE DIV DEVE SER EXIBIDA NO FINAL DA PÁGINA ?>


function formataCampoMoeda(varValor){
	varResult = '0,00';
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



	pagaISS = false;
	issInalterado = false;

	function montaTextoBallon(){ // FUNÇÃO RESPONSAVEL POR DETERMINAR O CONTEUDO DO BALLON DO ISS
		issInalterado = false;
		$('#aliquota').val('');

		<? //Se o assinante for de São Paulo>>>>?>
		if(CIDADE == 'São Paulo'){ 
			// ballon SÃO PAULO
			mensagemBallonISS = 'Para saber com certeza o valor do ISS, emita primeiro a <a href="nota_fiscal_tomador.php">Nota Fiscal de Tomador de Serviço</a> (necessária nesse caso) e confira na própria nota qual a alíquota ou valor do ISS atribuído e digite-o no campo ao lado.';
		}else{
			if(CIDADE_Fornecedora == CIDADE){
				<? // CASO A CIDADE DO ASSINANTE E DA EMPRESA FORNECEDORA SELECIONADA SEJAM A MESMA, MOSTRAR O CONTEUDO DE ATENÇÃO E PREENCHER COM 0 (zero) O CAMPO ALIQUOTA ISS?>
				
				//MAL ALTERADI OARA NÃO EXIBIR O CAMPO #divAliquotaISS 20/05/2016
				//INICIO
				
					//$('#divAliquotaISS').css('display','block');
					//$('#aliquota').val('0,00');

					//pagaISS = true;
					//validaISS = true;
					//issInalterado = true; <? // neste caso não se deve alterar o campo da aliquota de iss?>
					// ballon ATENÇÃO
					<? //Se o assinante não for de São Paulo>>>>>?>
					//mensagemBallonISS = 'ATENÇÃO: embora não haja retenção de ISS prevista na Lei Federal para este caso, alguns municípios podem ter leis próprias impondo retenção de ISS em certas atividades. Certifique-se junto a seu município de que não há retenção de ISS para a atividade tomada, ou confirme  o recolhimento do imposto por parte de seu prestador, para que você não seja responsabilizado por uma eventual falta de pagamento.';

				//FIM

			}else{
				// ballon FORA DE SÃO PAULO
				<? //Se o assinante não for de São Paulo>>>>>?>
				mensagemBallonISS = 'Se o prestador for optante pelo Simples, pergunte a alíquota de ISS ao qual está sujeito e digite-a no campo ao lado. Se ele não for optante, você precisará obter junto à prefeitura de sua cidade a alíquota de ISS devida para este tipo de serviço. Normalmente varia de 2 a 5%.';
			}
		}
		$('#ballonconteudo').html(mensagemBallonISS);
	};

	function posicionaBallon(idImgDica){ // FUNÇÃO RESPONSÁVEL PELO POSICIONAMENTO CORRETO DO BALLON
		idImgDica.css('display','block');
		var divConteudo = eval($('#' + idImgDica.attr('div')));
		if(divConteudo.hasClass('bubble_left_auto')){
			divConteudo.removeClass('bubble_left_auto');
		}
		if(divConteudo.hasClass('bubble_left_bottom')){
			divConteudo.removeClass('bubble_left_bottom');
		}
		var novoTop = 55;
		if(($(document).height() - idImgDica.offset().top) < divConteudo.height()){
			divConteudo.removeClass('bubble_left');
			divConteudo.addClass('bubble_left_auto');
		}else{
			divConteudo.removeClass('bubble_left');
			divConteudo.addClass('bubble_left_bottom');
		}
		novoTop = (divConteudo.height() - 56);
		divConteudo.css({
			'top':idImgDica.offset().top - novoTop
			, 'left':idImgDica.offset().left + 30
		});
		abreDiv(idImgDica.attr('div'));
	};

	$('#DataEmissao').val('');
	$('#DataPgto').val('');
	$('#atividades').val(0);
	$('#ValorBrutoPJ').val('');
	$('#aliquota').val('');
	$('#RetencaoIR').val('');
	$('#RetencaoISS').val('');
	$('#hddCodigoDARF').val('');
	$('#Liquido').val('');
	$('#linha_lista_atividades').css('display','none');
	$('#linha_lista_atividades_sp').css('display','none');

	$('input[name="retencao"]').attr('checked',false);
	$('#divAliquotaISS').css('display','none');
	$('#divBotaoCadastro').css('display','none');
	$('#divBotaoNovoCadastro').css('display','none');
	$('#NomeEmpresa').val('');

	$('#iss').css('display','none');

	$('#linha_orientacoes').css('display','block');
	$('#divIR').css('display','block');
	$('#divIR').find('.infoOK').css('display','block');
	$('#divISS').css('display','block');
	$('#divISS').find('#divSIM' + PREF_CAMPO_CIDADE).css('display','block');
	$('#divNF').css('display','block');
	$('#cid_Dif'+PREF_CAMPO_CIDADE).css('display','block');
	$('#cid_Dif'+PREF_CAMPO_CIDADE).find('.infoOK').css('display','block');



	$('#NomeEmpresa').bind('change',function(e){ // ESCOLHA DA EMPRESA FORNECEDORA
		// LIMPANDO OS CAMPOS E ESCONDENDO DIVS
		$('#DataEmissao').val('');
		$('#DataPgto').val('');
		$('#atividades').val(0);
		$('#ValorBrutoPJ').val('');
		$('#aliquota').val('');
		$('#RetencaoIR').val('');
		$('#RetencaoISS').val('');
		$('#hddCodigoDARF').val('');
		$('#Liquido').val('');
		$('#linha_lista_atividades').css('display','none');
		$('#linha_lista_atividades_sp').css('display','none');

		$('input[name="retencao"]').attr('checked',false);
		$('#divAliquotaISS').css('display','none');
		$('#divBotaoCadastro').css('display','none');
		$('#divBotaoNovoCadastro').css('display','none');
		$('#divResultados').css('display','none');

		$('#iss').css('display','none');


		validaRetencao = false;
		validaRetencao_sp = false;
		validaISS = false;
		pagaISS = false;
		issInalterado = false;

		if($(this).val() != ''){
			
			valEmpresa = $('#NomeEmpresa').val().split(';');

			SIMPLES = valEmpresa[1];
			CIDADE_Fornecedora = valEmpresa[2];
			MEI_Fornecedora = valEmpresa[3];
			CPOM_Fornecedora = valEmpresa[4];				

			// função que monta a mensagem do ballon do ISS correta
			montaTextoBallon();

/*
Assinante de SP
>> Ballon de SP

MEI: Não
Prestador: outra cidade
Atividade pertencente à lista: sim
>>>> tem ISS

MEI: Não
Prestador: outra cidade
lista atividade: nao
CPOM: não
>>> tem ISS

MEI: Não
Prestador: mesma cidade
lista atividade SP: sim
>>> tem ISS

__________________________________

Assinante de outra cidade
>> Ballon fora de SP

MEI: Não
Prestador: outra cidade
Atividade pertencente à lista: sim
>>>> tem ISS

MEI: Não
Prestador: mesma cidade
>>> mostra ISS com valor igual a zero e usa ballon atenção
*/
			if((MEI_Fornecedora == 1) || (CIDADE == 'São Paulo' && CIDADE != CIDADE_Fornecedora && MEI_Fornecedora == 0 && CPOM_Fornecedora == 0)){
				$('#linha_lista_atividades').css('display','none');
				validaRetencao = false;
				$('#linha_lista_atividades_sp').css('display','none');
				validaRetencao_sp = false;
				if(MEI_Fornecedora != 1){
					$('#divAliquotaISS').css('display','block');
					pagaISS = true;
					validaISS = true;
				}
			}else{
				if(CIDADE != CIDADE_Fornecedora){
					$('#linha_lista_atividades').css('display','block');
					validaRetencao = true;
					$('#linha_lista_atividades_sp').css('display','none');
					validaRetencao_sp = false;
				}else{
					if(CIDADE == 'São Paulo' && CIDADE_Fornecedora == 'São Paulo'){
						$('#linha_lista_atividades').css('display','none');
						validaRetencao = false;
						$('#linha_lista_atividades_sp').css('display','block');
						validaRetencao_sp = true;
					}
				}
			}

			if(SIMPLES == 1){
				$('#linha_tipo_servico').css('display','none');
				$('#linha_codigo_darf').css('display','none');
				$('#atividades').val(0);
			}else{
				$('#linha_tipo_servico').css('display','block');
				$('#linha_codigo_darf').css('display','block');
			}
				

		}

	});
			
	
	$('.link_atualiza').bind('click',function(e){
		e.preventDefault();
		var empresa_selecionada = $('#NomeEmpresa').val();
		empresa_selecionada = empresa_selecionada.split(';');
		if(empresa_selecionada[0] > 0){
			location.href='meus_dados_pj.php?editar=' + empresa_selecionada[0];
		}else{
			alert('Selecione uma empresa.');
			//location.href='meus_dados_pj.php';
		}

	});

<? // VALIDACAO DO CAMPO SIM E NAO DA LISTAGEM PARA FORNECEDOR E ASSINANTE DE SAO PAULO?>
	$('input[name="retencao"], input[name="retencao_sp"]').bind('click',function(){
		if(issInalterado == false){
			if($(this).val()=='1'){
				$('#divAliquotaISS').css('display','block');
				$('#aliquota').val('');
				pagaISS = true;
				validaISS = true;
			}else{
				$('#divAliquotaISS').css('display','none');
				$('#aliquota').val('');
				pagaISS = false;
				validaISS = false;
			}
		}
	});

	
	// BOTAO RESPONSAVEL POR CADASTRAR OS DADOS DO PAGAMENTO NO BANCO DE DADOS 
	$('#btnGravarPagto').click(function(e){
		e.preventDefault();

		var $this = $(this);
		
		$('#iss').css('display','none');

		// validando campos
		if($('#NomeEmpresa').val() == ''){
			alert('Selecione uma empresa fornecedora');
			$('#NomeEmpresa').focus();
			return false;
		}

		if($('#atividades').val() == '' && SIMPLES == 0){
			alert('Selecione um serviço.');
			$('#atividades').focus();
			return false;
		}
		
		if(validaRetencao == true){
			if($('input[name="retencao"]:checked').val() != '1' && $('input[name="retencao"]:checked').val() != '0'){
				alert('Selecione se o serviço prestado está entre as atividades listadas.');
				$('input[name="retencao"]').eq(0).focus();
				return false;
			}
			if($('input[name="retencao"]:checked').val() == '1' && $('#aliquota').val() == ''){
				alert('Preencha a alíquota do ISS.');
				$('#aliquota').focus();
				return false;
			}

		}


		if(validaRetencao_sp == true){
			if($('input[name="retencao_sp"]:checked').val() != '1' && $('input[name="retencao_sp"]:checked').val() != '0'){
				alert('Selecione se o serviço prestado está entre as atividades listadas.');
				$('input[name="retencao_sp"]').eq(0).focus();
				return false;
			}
			if($('input[name="retencao_sp"]:checked').val() == '1' && $('#aliquota').val() == ''){
				alert('Preencha a alíquota do ISS.');
				$('#aliquota').focus();
				return false;
			}

		}


		if($('#DataEmissao').val() == ''){
			alert('Preencha a data da Nota Fiscal.');
			$('#DataEmissao').focus();
			return false;
		}

		if($('#DataPgto').val() == ''){
			alert('Preencha a data de pagamento.');
			$('#DataPgto').focus();
			return false;
		}
				
		if($('#ValorBrutoPJ').val() == ''){
			alert('Preencha o valor bruto.');
			$('#ValorBrutoPJ').focus();
			return false;
		}
				
		if($('#Liquido').val() == ''){
			calculaRetencoes();
		}
		
		
//		$('#divNF').css('display','block');
//		$('#cid_Dif'+PREF_CAMPO_CIDADE).css('display','block');
//		$('#cid_Dif'+PREF_CAMPO_CIDADE).find('.infoOK').css('display','block');

//		if($('#RetencaoIR').val() != '0,00' && $('#RetencaoIR').val() != ''){
//			$('#divIR').css('display','block');
//			$('#divIR').find('.infoOK').css('display','block');
//		}else{
//			$('#divIR').css('display','none');
//		}


//		if($('#RetencaoISS').val() != '0,00' && $('#RetencaoISS').val() != ''){
//			$('#divISS').css('display','block');
//			$('#divISS').find('#divSIM' + PREF_CAMPO_CIDADE).css('display','block');
//		}else{
//			$('#divISS').find('#divSIM' + PREF_CAMPO_CIDADE).css('display','none');
//			$('#divISS').css('display','none');
//		}

//		if(
//			($('#divISS').css('display') == 'block')
//			|| ($('#divNF').css('display') == 'block')
//			|| ($('#divIR').css('display') == 'block')
//		){
//			$('#linha_orientacoes').css('display','block');
//		} else {
//			$('#linha_orientacoes').css('display','none');
//		}
		


		var 
			$currURL = location.href
			, $Date = new Date($.formataDataEn($('#DataPgto').val()))
			, $DateHoje = new Date()
			, $mesHoje = ($DateHoje.getMonth() + 1)
			, $anoHoje = ($DateHoje.getFullYear())
		;
		
		var $data = $('#form_Calcula_Retencao').serializeArray();
		var $data2 = $('#form_Calcula_Retencao').serialize();
		
		$.ajax({ <? // no change do campo da empresa fornecedora deve ser feita a checagem da cidade?>
			url:'pagamento_pj_gravar.php?acao=ins',
			type: 'post',
			data: $data,
			cache: false,
			async: false,
			dataType:"html",
			beforeSend: function(){
				$(this).val('Processando...').attr('disabled',true);
				$('#divBotaoCadastro').css('display','none');

				$('#divBotaoNovoCadastro').css('display','block');

			},
			success: function(retorno){
				$(this).val('Cadastrar Pagamento').attr('disabled',false);
				if(retorno != ''){


								$('#btSIMAvisoLivroCaixa').attr('idPagto',retorno);
								$('#aviso-livro-caixa').css({
									'top':($('#caixa-botoes').offset().top - 218) + 'px'
									, 'left':($('#caixa-botoes').offset().left + 100) + 'px'
								}).fadeIn(100);

				}

			}
		});
		

		
	});
	
	
	

		
		$('#btSIMAvisoLivroCaixa').bind('click',function(){
			
			var $data2 = $('#form_Calcula_Retencao').serialize(), $this = $(this);
								
			$data2 += "&nome=" + $('#NomeEmpresa option:selected').text() +  "&tipo=PJ";
			$data2 += "&idPagto=" + $this.attr('idPagto');
	
			var 
				$currURL = location.href
				, $Date = new Date($.formataDataEn("10/" + $('#DataPgto').val().substr(3,2) + "/" + $('#DataPgto').val().substr(6,4)))
				, $DateHoje = new Date()
				, $mesHoje = ($DateHoje.getMonth() + 1)
				, $anoHoje = ($DateHoje.getFullYear())
			;
			
			$.ajax({
				url:'atualiza_livros_caixa.php',
				type: 'post',
				data: $data2,
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

	
	
		
	// BOTAO RESPONSAVEL POR ATUALIZAR A PAGINA PARA UM NOVO PAGAMENTO
	$('#btnNovoPagto').click(function(e){
		e.preventDefault();
		location.reload();
	});
	
	
	$('#btCalculo').bind('click',function() {

		$('#iss').css('display','none');

		// checar se nome da empresa esta preenchido
		var a = $('#NomeEmpresa');
		if (a.val() == ""){
			alert('Selecione a empresa a ser paga.');  
			a.focus();
			return false
			}
	
		// checar se tipo de serviço ou prestador esta preenchido	
		var b = $('#atividades');
		if (b.val() == "" && SIMPLES != 1){ <? //EM 25/07/2014 - colocada a checagem de empresa optante do SIMPLES para cálculo de alíquota ?>
			alert('Selecione o tipo de serviço ou prestador.');
			b.focus();
			return false
			}
			
		if(validaRetencao == true){
			if($('input[name="retencao"]:checked').index() < 0){
				alert('Selecione se o serviço prestado está entre as atividades listadas.');
				$('input[name="retencao"]').focus();
				return false;
			}
		}
	
		if(validaRetencao_sp == true){
			if($('input[name="retencao_sp"]:checked').index() < 0){
				alert('Selecione se o serviço prestado está entre as atividades listadas.');
				$('input[name="retencao_sp"]').focus();
				return false;
			}
		}
			
		
		// checar se valor bruto esta preenchido
		var c = $('#ValorBrutoPJ');
		if (c.val() == ""){
			alert('Preencha o valor bruto a ser pago.'); 
			$('#ValorBrutoPJ').focus();
			return false
			}

		// checar se valor liquido apresentado na nf foi preenchido
		var LiquidoNF = $('#ValorLiquidoNF').val();
		if(LiquidoNF != ''){
			LiquidoNF = LiquidoNF.replace(".","");
			LiquidoNF = LiquidoNF.replace(",",".");
			LiquidoNF = parseFloat(LiquidoNF);
		}

		// checar se aliquota esta preenchido
		if(validaISS == true && ($('#aliquota').val() == "") || (issInalterado == false && $('#aliquota').val() == "0,00")){
			alert('Preencha a alíquota do ISS.'); 
			$('#aliquota').focus();
			return false;
		}
		
		// pega valor bruto e transforma em numero	
		var ValorBrutoPJ = c.val();
		ValorBrutoPJ = ValorBrutoPJ.replace(".","");
		ValorBrutoPJ = ValorBrutoPJ.replace(",",".");
		ValorBrutoPJ = parseFloat(ValorBrutoPJ);
		
		//define variaveis aliquota, codigo darf e observaçao a partir do tipo de serviço
		//var e = $('#atividades'); 
//		CHECANDO A ATIVIDADE SELECIONADA
		if (b.val() == "Serviços Profissionais"){
			aliquota = 1.50;
			codigo_darf = 1708;
		}
		
		if (b.val() == "Comissões e Corretagens") {
			aliquota = 1.50;
			codigo_darf = 8045;
		}
		
		
		if (b.val() == "Cooperativas de Trabalho") {
			aliquota = 1.50;
			codigo_darf = 3280;
		}
		
		if (b.val() == "Limpeza Conservação") {
			aliquota = 1.00;
			codigo_darf = 1708;
		}
		
		if (b.val() == "Propaganda e Publicidade") {
			aliquota = 1.50;
			codigo_darf = 8045;
		} 
		
		if(b.val() == "Plano ou Seguro Saúde Privado" || SIMPLES == 1) {
			aliquota = 0;
			codigo_darf = '';
		}
		
		if(b.val() == "outros" || SIMPLES == 1) {
			aliquota = 0;
			codigo_darf = '';
		}
		
		if(LiquidoNF != ''){
			//calculando a retenção do IR para comparação
			RetencaoIRCompara = ValorBrutoPJ * (aliquota/100);
			RetencaoIRCompara = Math.round(RetencaoIRCompara * 100) / 100;
			RetencaoIRCompara = RetencaoIRCompara.toFixed(2);
	
			//atribui os valores aos campos retenção e codigo darf 
			RetencaoIR = ValorBrutoPJ - LiquidoNF;
			RetencaoIR = Math.round(RetencaoIR * 100) / 100;
			RetencaoIR = RetencaoIR.toFixed(2);
			
			if(RetencaoIRCompara != RetencaoIR){
				var txtAliquota = String(aliquota);
				if(!confirm('O valor líquido a pagar e a retenção de IR indicada na nota do prestador, diferem do cálculo do Contador Amigo, efetuado com base no valor bruto declarado. Isso pode ocorrer, quando o prestador se enquadra em algumas situações especiais prevista em lei. Se estiver seguro dos valores declarados pelo prestador, prossiga. Caso contrário, entre em contado com ele e solicite maiores esclarecimentos.')){
					return false;
				}
			}
			
		}else{
			//atribui os valores aos campos retenção e codigo darf 
			RetencaoIR = ValorBrutoPJ * (aliquota/100);
			RetencaoIR = Math.round(RetencaoIR * 100) / 100;
			RetencaoIR = RetencaoIR.toFixed(2);
		}

		//calcula o ISS
		if(pagaISS == true){
			aliquotaISS = $('#aliquota').val();
			aliquotaISS = aliquotaISS.replace(".","");
			aliquotaISS = aliquotaISS.replace(",",".");
			aliquotaISS = parseFloat(aliquotaISS);
			
			ISS = (ValorBrutoPJ * aliquotaISS)/100;
		}else{
			ISS = 0;
		}
		
		//valor liquido
		Liquido = (ValorBrutoPJ - RetencaoIR - ISS);
		
		// FOI COLOCADO O MATH.ROUND POIS HÁ UM PROBLEMA COM A FUNÇÃO parseFloat DO JAVASCRIPT - PRECISA SER ESTUDADO
		$('#RetencaoIR').val(formataCampoMoeda(limpaCaracteres((Math.round(RetencaoIR * 100)))));
		$('#RetencaoISS').val(formataCampoMoeda(limpaCaracteres(parseFloat(ISS).toFixed(2))));
		$('#Liquido').val(formataCampoMoeda(limpaCaracteres(Math.round(Liquido*100))));
		$('#hddCodigoDARF').val(codigo_darf);
		
		$('#divBotaoCadastro').css('display','block');
		$('#divBotaoNovoCadastro').css('display','none');
		$('#divResultados').css('display','block');
		
	});


});



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
<!--BALLOM ISS -->
<div style="width:310px; position:absolute; display:none;" id="iss" class="bubble_left box_visualizacao x_visualizacao">

	<div id="ballonconteudo" style="padding:20px;">
    </div>
    
</div>
<!--FIM DO BALLOOM ISS -->

<div class="titulo" style="margin-bottom:20px;">Pagamentos</div>
<div class="tituloVermelho" style="margin-bottom:10px">Pessoas Jurídicas</div>
<div style="width:740px; margin-bottom:20px">Antes de fazer o pagamento a qualquer pessoa jurídica que lhe prestou serviços, você precisa saber se  a mesma está sujeita a retenção de Imposto de Renda e/ou ISS. Embora o imposto seja devido pela empresa que lhe prestou o serviço, você é quem fica o responsável pelo pagamento. Nesta página você poderá cadastrar os serviços tomados, verificar se há alguma retenção, qual o valor e será orientado sobre como pagá-la.<br />
  <br />
  <span style="margin-bottom:20px; width:740px;">  Não deixe de cadastrar os pagamentos, especialmente aqueles sujeitos a retenção. Você precisará saber os valores  retidos para enviar no início do ano a <strong>Declaração de Imposto de Renda Retido na Fonte</strong> (DIRF) e o <strong>Comprovante de Rendimentos Pagos e de Retenção na Fonte</strong>. Não se desespere, o Contador Amigo vai ajudá-lo nisso também! </span> <br />
</div>

<div id="alertaCidadeInvalida" style="display: none;background-color:#FFFFFF; border-color:#CCC; border-style:solid; border-width:1px; padding:10px; margin-bottom:30px">
<span class="destaque">ATENÇÃO:</span><br /><br />
A cidade que consta em seu cadastro não é uma cidade válida. Por favor, acesse a página <a href="meus_dados_empresa.php">Dados da Empresa</a>, faça a alteração e retorne a esta página para continuar com o cálculo.
</div>


<div class="tituloVermelho" style="margin-bottom:20px">Cálculo de retenção e pagamento líquido</div>

<!--formulario de calcul oda retencao -->
<form name="form_Calcula_Retencao" id="form_Calcula_Retencao" method="post" style="display:inline">
<input type="hidden" name="hddAliquotaISS" id="hddAliquotaISS" value="2" />
<input type="hidden" name="hddCategoria_livro_caixa" value="Pgto. a autônomos e fornecedores">

<!--nome -->
<div style="float:left; width:155px; padding-top: 3px;"><label for="NomeEmpresa">Nome da empresa: </label></div>
<div style="float:left;"><select name="NomeEmpresa" id="NomeEmpresa">
	<option value="">Selecione a empresa</option>
<?
	$query = mysql_query('SELECT id, nome, op_simples, cidade, mei, cpom FROM dados_pj WHERE id_login = '.$_SESSION["id_empresaSecao"].' ORDER BY nome');
	while($dados = mysql_fetch_array($query)){
		echo "<OPTION value=\"".$dados['id'].";".$dados['op_simples'].";".$dados['cidade'].";".$dados['mei'].";".$dados['cpom']."\">".$dados['nome']."</OPTION>";
	}
?>
</select>
&nbsp;&nbsp;<a href="meus_dados_pj.php?act=new">cadastrar empresa</a>&nbsp;&nbsp;ou&nbsp;&nbsp;<a class="link_atualiza" href="#">alterar dados de empresa já cadastrada</a></div>
<div style="clear:both; height:10px"></div>






<div id="linha_tipo_servico" style="display:none;">
<div style="float:left; width:155px; padding-top: 3px;">Tipo de serviço: </div>
<div style="float:left;"><select name="atividades" id="atividades">
	<option value="">Selecione </option>
    <option value="Serviços Profissionais">Administração de bens ou negócios em geral</option>
    <option value="Serviços Profissionais">Advocacia</option>
    <option value="Serviços Profissionais">Análise clínica laboratorial</option>
    <option value="Serviços Profissionais">Análises técnicas</option>
    <option value="Serviços Profissionais">Arquitetura</option>
    <option value="Serviços Profissionais">Assessoria e consultoria técnica</option>
    <option value="Serviços Profissionais">Assistência social</option>
    <option value="Serviços Profissionais">Auditoria</option>
    <option value="Serviços Profissionais">Avaliação e perícia</option>
    <option value="Serviços Profissionais">Biologia e biomedicina</option>
    <option value="Serviços Profissionais">Cálculo em geral</option>
    <option value="Comissões e Corretagens">Comissões e Corretagens </option>
    <option value="Serviços Profissionais">Consultoria</option>
    <option value="Serviços Profissionais">Contabilidade</option>
    <option value="Cooperativas de Trabalho">Cooperativas de Trabalho</option>
    <option value="Serviços Profissionais">Desenho técnico</option>
    <option value="Serviços Profissionais">Despachante</option>
    <option value="Serviços Profissionais">Economia</option>
    <option value="Serviços Profissionais">Elaboração de projetos</option>
    <option value="Serviços Profissionais">Engenharia</option>
    <option value="Serviços Profissionais">Ensino e treinamento</option>
    <option value="Serviços Profissionais">Estatística</option>
    <option value="Serviços Profissionais">Fisioterapia</option>
    <option value="Serviços Profissionais">Fonoaudiologia</option>
    <option value="Serviços Profissionais">Geologia</option>
    <option value="Serviços Profissionais">Leilão</option>
    <option value="Limpeza Conservação">Limpeza e Conservação</option>
    <option value="Limpeza Conservação">Locação de Mão-de-Obra</option>
    <option value="Serviços Profissionais">Medicina</option>
    <option value="Serviços Profissionais">Nutricionismo e dietética</option>
    <option value="Serviços Profissionais">Odontologia</option>
    <option value="Serviços Profissionais">Organização de feiras de amostras, congressos, seminários, simpósios e congêneres</option>
    <option value="Serviços Profissionais">Pesquisa em geral</option>
    <option value="Serviços Profissionais">Planejamento</option>
    <option value="Plano ou Seguro Saúde Privado">Plano ou Seguro Saúde Privado</option>
    <option value="Serviços Profissionais">Programação</option>
    <option value="Serviços Profissionais">Prótese</option>
    <option value="Propaganda e Publicidade">Propaganda e Publicidade</option>
    <option value="Serviços Profissionais">Psicologia e psicanálise</option>
    <option value="Serviços Profissionais">Química</option>
    <option value="Serviços Profissionais">Radiologia e radioterapia</option>
    <option value="Serviços Profissionais">Relações públicas</option>
    <option value="Limpeza Conservação">Segurança</option>
    <option value="Serviços Profissionais">Terapêutica ocupacional</option>
    <option value="Serviços Profissionais">Tradução ou interpretação comercial</option>
    <option value="Serviços Profissionais">Urbanismo</option>
    <option value="Serviços Profissionais">Veterinária</option>
    <option value="outros">OUTROS SERVIÇOS NÃO LISTADOS ACIMA</option>
 </select></div>
 <div style="clear:both; height:10px"></div>
</div>


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


<!--Data Emissão-->
<div style="float:left; width:155px; padding-top: 3px;"><label for="DataEmissao" style="margin-right:10px;">Data da Nota Fiscal:</label></div>
<div style="float:left;"><input name="DataEmissao" id="DataEmissao" type="text" size="12" maxlength="10" class="campoData" /> </div>
<div style="clear:both; height:10px"></div>
<!--Data pagamento-->
<div style="float:left; width:155px; padding-top: 3px;"><label for="DataPgto" style="margin-right:10px;">Data Pagamento:</label></div>
<div style="float:left;"><input name="DataPgto" id="DataPgto" type="text" size="12" maxlength="10" class="campoData" /> </div>
<div style="clear:both; height:10px"></div>
<!--valor bruto -->
<div style="float:left; width:155px; padding-top: 3px;"><label for="ValorBrutoPJ" style="margin-right:10px; width:120px">Valor bruto (R$):</label></div>
<div style="float:left;"><input name="ValorBrutoPJ" id="ValorBrutoPJ" type="text" size="30" maxlength="12" class="current" /></div>
<div style="clear:both; height:10px"></div>
<!--valor liquido a pagar -->
<div style="float:left; width:155px; padding-top: 3px;"><label for="ValorLiquidoNF" style="margin-right:10px; width:155px">Valor líquido a pagar (R$):</label></div>
<div style="float:left;"><input name="ValorLiquidoNF" id="ValorLiquidoNF" type="text" size="30" maxlength="12" class="current" /> (indicado na nota fiscal do prestador. Se não estiver informado, deixe em branco)</div>
<div style="clear:both; height:20px"></div>


<!--Aliquota ISS-->
<div id="divAliquotaISS" style="margin-top: -10px;display: none;">
	<div style="float:left; width:155px; padding-top: 3px;"><label for="aliquota" style="margin-right:10px;">Alíquota do ISS (%):</label></div>
	<div style="float:left;">
		<input name="aliquota" id="aliquota" type="text" size="6" maxlength="5" class="current" style="float:left;" />
	    <div style="float:left; margin-left:5px; margin-top:5px">
		    <img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="iss" />
	    </div>
	</div>
	<div style="clear:both; height:20px"></div>
</div>

<!--botao calculo -->
<div style="margin-bottom:20px"><input type="button" value="Calcular" id="btCalculo" /></div>

<div id="divResultados" style="display: none;">
	<div class="destaqueAzul" style="margin-bottom:10px">Resultados:</div>
    <div id="linha_valor_imposto" style="display:block;">
        <div style="float:left; width:155px">Retenção de IR:</div> <input name="RetencaoIR" id="RetencaoIR" type="text" size="21" maxlength="50"  readonly="readonly"class="current" />
        <div style="clear:both; height:5px"></div>
    </div>
    <div id="linha_valor_iss" style="display:block;">
        <div style="float:left; width:155px">Retenção de ISS:</div>
        <div style="float:left;"><input name="RetencaoISS" id="RetencaoISS" type="text" size="21" maxlength="50"  readonly="readonly"class="current" /></div>
        <div style="clear:both; height:5px"></div>
    </div>
    <div style="float:left; width:155px">Líquido a pagar:</div> <input name="Liquido" id="Liquido" type="text" size="21" maxlength="50"   class="current" />
    <div style="clear:both; height:5px"></div>
	<div style="clear:both; height:15px"></div>
</div>

<input name="CodigoDARF" id="hddCodigoDARF" type="hidden" size="21" maxlength="50"  readonly="readonly" />

<div id="caixa-botoes">
	<div id="divBotaoCadastro" style="display: none; margin-bottom:20px;"><input type="button" id="btnGravarPagto" name="btnGravarPagto" value="Cadastrar Pagamento" /></div>
	<div id="divBotaoNovoCadastro" style="display: none; margin-bottom:20px;"><input type="button" id="btnNovoPagto" name="btnNovoPagto" value="Novo Pagamento" /></div>
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
	                <div class="destaqueAzul" style="margin-bottom:5px"><?php echo $des->getNomeTexto(); ?></div>
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
	                <div class="destaqueAzul" style="margin-bottom:5px"><?php echo $des->getNomeTexto(); ?></div>
	                <div class="infoOK" style="display: block; margin-bottom:20px">
	                	Você precisará informar  este pagamento na <?php echo $des->getNomeTexto(); ?>. <a href="des.php" title="Des">Veja como</a>.
	                </div>
				</div>
			</div>
		<?php } else if( $des->getPrestados() && $des->getTomados() && $des->getTomados_outro_municipio() ) {?>
			<div id="divNF" style="display: block; clear: both; margin-bottom: 15px;">
	        	<div id="cid_Dif_SP" style="display: block;">
	                <!--texto NFTS para assinante de SP -->
	                <div class="destaqueAzul" style="margin-bottom:5px"><?php echo $des->getNomeTexto(); ?><?php echo $des->getNomeCompletoTexto(); ?> </div>
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

</form>






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
				AND id_pj != 0 LIMIT 1";	
	
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
			montaCombo('populaCombo','area=folha_pagto&tipo=pessoa jurídica&id=<?=$_REQUEST["nome"]?>','nome');
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
				queryString2 += QS;				
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
			//Busca o ultimo pagamento
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
					, pgto.data_emissao
					, pgto.data_pagto  
					, pgto.idLivroCaixa
					, pgto.desconto_dependentes
					, pj.id id
					, pj.nome nome
					, pj.cnpj cpf
					, pj.op_simples op_simples
				FROM 
					dados_pagamentos pgto
					inner join dados_pj pj on pgto.id_pj = pj.id
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
		//LIMIT 0,12
	//	echo $sql . $resDatas . $resColuna . $resOrdem;
		
	
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
	
		return $resultado;
	
	}
		 
 	function PegaUltimoPagamento(){
		$sql2 = "SELECT * FROM dados_pagamentos WHERE id_login = '" . $_SESSION["id_empresaSecao"] . "' AND id_pj != 0 ORDER BY data_pagto DESC LIMIT 1";
		
		//executa consulta.
		$result2 = mysql_query($sql2);
		return $result2;
	}
		 
	//Verifica se existe cadastro na data informada.
	$resultado = MontagemDaListagemDosPagamentos($dataInicio, $dataFim, $idNome);		 
			
	?>

      <table width="900" cellpadding="5" style="margin-bottom:25px;">
          <tr>
            <th width="4%">Ação</th>
            <th width="47%">Nome</th>
            <th width="9%">Data da NF</th>
            <th width="9%">Valor Bruto</th>
            <th width="8%">IR</th>
            <th width="9%">ISS</th>
            <th width="9%">Valor Líquido</th>
          </tr>
        
	<?	
			if(mysql_num_rows($resultado) > 0){
				$loop = 0;
				$codigo_servicoAnterior 	= "0";
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 					= $linha["id_pagto"];
					$id 						= $linha["id"];
					$nome 						= $linha["nome"];
					$periodo 					= $linha["periodo"];
					$tipo 						= $linha["tipo"];
					$cpf 						= $linha["cpf"];
					$idLivroCaixa		= $linha["idLivroCaixa"];
		
					$op_simples 				= $linha["op_simples"];
	//				$codigo_servico 			= $linha["codigo_servico"];
	//				$descricao_servico 			= $linha["descricao_servico"];
					
					$valor_bruto 				= $linha["valor_bruto"];
					$INSS		 				= $linha["INSS"];
					$IR			 				= $linha["IR"];
					$ISS		 				= $linha["ISS"];
					$valor_liquido 				= $linha["valor_liquido"];
					
					$data_pagto 				= (date("d/m/Y",strtotime($linha['data_pagto'])));
					$data_emissao 				= $linha['data_emissao'] != null ? date("d/m/Y",strtotime($linha['data_emissao'])) : "";
					
					$idLoginPagamento = "";
					if(isset($_REQUEST['nome']) && $_REQUEST['nome'] != ''){
						$arrComboNomes = explode("|",$_REQUEST['nome']);
						$idLoginPagamento = $arrComboNomes[0];
					}
					

	?>
        
          <tr>
            <td class="td_calendario" align="center">
            		<a href="#" class="excluirPagamento" idpg="<?=$idPagto?>" idLC="<?=$idLivroCaixa?>" qs="&categoria=<?=$categoria?>&nome=<?=$idLoginPagamento?>&dataInicio=<?=$_REQUEST['dataInicio']?>&dataFim=<?=$_REQUEST['dataFim']?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
<!--                <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este Pagamento?'))location.href='folha_pagamentos_excluir.php?excluir=<?=$idPagto?>&categoria=<?=$categoria?>&dataInicio=<?=$_REQUEST['dataInicio']?>&dataFim=<?=$_REQUEST['dataFim']?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>-->
            </td>
            <td class="td_calendario"><a href="meus_dados_pj.php?editar=<?=$id?>"><?=$nome?></a></td>
            <td class="td_calendario" align="right"><?=$data_emissao?></td>
            <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
            <td class="td_calendario" align="right"><?=number_format($IR,2,',','.')?></td>
            <td class="td_calendario" align="right"><?=number_format($ISS,2,',','.')?></td>
            <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
          </tr>
	<?	
					$total_desc_dep += $desc_dep;
					$total_INSS += $INSS;
		
					$total_valor_bruto += $valor_bruto;
					$total_IR += ($op_simples == 0 ? $IR : 0);
					$total_ISS += ($op_simples == 0 ? $ISS : 0);
					$total_valor_liquido += ($op_simples == 0 ? $valor_liquido : $valor_bruto);	
		
					$codigo_servicoAnterior 	= $codigo_servico;
					$descricao_servicoAnterior	= $descricao_servico;
					
					$loop++;
					// FIM DO LOOP
					}
	?>
					<tr>
						<th style="background-color: #999; font-weight: normal" colspan="3" align="right">Totais:&nbsp;</th>
						<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
						<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_IR,2,',','.')?></th>
						<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_ISS,2,',','.')?></th>
						<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_liquido,2,',','.')?></th>
					</tr>
					<?
					$total_valor_bruto = 0;
					$total_INSS = 0;
					$total_desc_dep = 0;
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
          </tr>
        
	<?	
			}
		
	?>
	
		</table>



</div>
</div>

<?
if($_SESSION['mensagem_pagamento_cadastrado'] != ''){
	echo "<script>alert('".$_SESSION['mensagem_pagamento_cadastrado']."')</script>";
	$_SESSION['mensagem_pagamento_cadastrado'] = '';
}
?>

<?php include 'rodape.php' ?>
