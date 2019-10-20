<?php include 'header_restrita.php' ?>
<?php include 'livros_caixa_fluxo_class.php' ?>
<?php include 'balanco.class.php'; ?>
<?php 
	
	if( isset( $_GET['ano'] ) ):
		$_SESSION['ano_carta'] = $_GET['ano'];
	else:
		$_SESSION['ano_carta'] = date("Y");				
	endif;

?>
<style type="text/css" media="screen">
	html{
		display: none;
	}
	.input_tabela{
		/*text-align: right;*/
	}
	.total_ativo_nao_circulante{
		text-align: right;
	}
	.total_ativo_circulante{
		text-align: right;
	}
	.total_passivo_circulante{
		text-align: right;
	}
	.total_passivo_nao_circulante{
		text-align: right;
	}
	.tabela_circulant_ativo{
		text-align: right;
	}
	.total_patrimonio_liquido{
		text-align: right;
	}
	.total_passivo_geral{
		text-align: right;
	}
	.total_ativo_geral{
		text-align: right;
	}
</style>
<script>
	$( document ).ready(function() {
		var texto = $("#contingenciaTexto").val();

			$("#textoContingencia").empty();
			$("#textoContingencia").append(texto);

			if( texto === "" ){
				$(".remove_contingencias").addClass('hideImpressao');
				$(".remove_contingencias").removeClass('apenasImpressao');
			}
			else{
				$(".remove_contingencias").removeClass('apenasImpressao');
				$(".remove_contingencias").addClass('apenasImpressao');
			}
				
			var ano_aux = $(".ano_tabela").val();
			var id = $(".id_tabela").val();	
			if( '<?php echo $_GET["contador_proprio"]; ?>' === 'true' ){
				var nome = $("#dados_contador .nome").val();
				var crc = $("#dados_contador .crc").val();
				var endereco = $("#dados_contador .endereco").val();
				var estado = $("#dados_contador .estado").val();
				var cidade = $("#dados_contador .cidade").val();
				var cep = $("#dados_contador .cep").val();		
				var tipo = $("#dados_contador .tipo_item").val();		
			}
			else{
				var nome = $(".nosso_contador_nome").val();
				var crc = $(".nosso_contador_crc").val();
				var endereco = $(".nosso_contador_endereco").val();
				var estado = $(".nosso_contador_cidade").val();
				var cidade = $(".nosso_contador_estado").val();
				var tipo = $(".nosso_contador_tipo").val();
				var cep = $(".nosso_contador_cep").val();
			}

			//Dados do contador atual
			$(".contador_nome_empresa_contabilidade").empty();
			$(".contador_nome_empresa_contabilidade").append(nome);
			$(".contador_CRC_empresa_contabilidade").empty();
			$(".contador_CRC_empresa_contabilidade").append(crc);
			$(".contador_endereco").empty();
			$(".contador_endereco").append(endereco);
			$(".contador_cidade").empty();
			$(".contador_cidade").append(cidade+', '+estado);
			$(".contador_cep").empty();
			$(".contador_cep").append(cep);
			$(".tipo_contador1").empty();
			$(".tipo_contador1").append(tipo);
			$(".tipo_contador2").empty();
			$(".tipo_contador2").append(tipo);

			$(".impressao_contador_nome").empty();
			$(".impressao_contador_crc").empty();
			$(".impressao_contador_nome").append(nome);
			$(".impressao_contador_crc").append(crc);
			var ano_aux = $(".ano_tabela").val();
			var nome_arquivo = '<?php echo 'Balanço Patrimonial - '; ?>'+ano_aux;

			$("title").empty();

			$("title").append(nome_arquivo);

			window.print();
			
	        $(window).hover(function() {
	        	// alert("aki");
	        	window.close();
	        });


	});
</script>
<script src="jquery.maskMoney.js" type="text/javascript"></script>
<div class="principal minHeight">		
	<div class="titulo" style="margin-bottom:25px;">Balanço</div>
	
	 <?php 
	 	echo '<div style="clear: both;height: 10px;"></div>';
	 	echo '<div class="pagina">';
		echo '<div class="tituloVermelho apenasImpressao" style="font-size:18px;"><strong>'.$_SESSION['nome_userSecao'].'</strong></div>';


	 	//Deleta os dados nao preenchidos
	 	$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND item = '' AND quantidade = '0' AND valor = '0' AND ano_item = 0");
		if( mysql_num_rows($consulta) > 0 )
			mysql_query("DELETE FROM imobilizados WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND item = '' AND quantidade = '0' AND valor = '0' AND ano_item = 0 LIMIT 1 ");

		$consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND item = '' AND quantidade = '0' AND valor = '0' AND ano_item = 0");
		if( mysql_num_rows($consulta) > 0 )
			mysql_query("DELETE FROM intangiveis WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND item = '' AND quantidade = '0' AND valor = '0' AND ano_item = 0 LIMIT 1 ");

		$emprestimos = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE data = '0000-00-00' AND categoria = 'Empréstimos' AND entrada = '0' ");	
		$string = '';
		if( mysql_num_rows($emprestimos) > 0 ){
			mysql_query("DELETE FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE data = '0000-00-00' AND categoria = 'Empréstimos' AND entrada = '0' ORDER BY id DESC ");
			mysql_query("INSERT INTO user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos WHERE meses = '0' AND valor_pago = '0' ORDER BY id DESC ");
		}
		//Fim Deleta os dados nao preenchidos

		$balanco_patrimonial = new Balanco_patrimonial();

		$id = $_SESSION["id_empresaSecao"];
		
		if( isset( $_GET['ano'] ) ):
			$ano = $_GET['ano'];
		else:
			$ano = date("Y");				
		endif;

		include 'conect.php';

		$consulta = mysql_query("SELECT * FROM balanco_patrimonial WHERE id_user = '".$id."' AND ano = '".$ano."' ");
		$objeto=mysql_fetch_array($consulta);

		$balanco_patrimonial->setDados($objeto,$id,$ano);

		echo '<div class="tituloVermelho" id="inicioTabelaBalanco">Balanço Patrimonial encerrado em 31/12/'.$ano.'</div><br>';

		echo '<div class="tituloVermelho apenasImpressao"><strong>Balanço Patrimonial encerrado em 31/12/'.$ano.'</strong></div><br>';

		$balanco_patrimonial->gerarTabelaAtivo();
		$balanco_patrimonial->gerarTabelaPassivoInput();
		$balanco_patrimonial->gerarTabelaPatrimonioLiquidoInput();
		$balanco_patrimonial->gerarTabelaFinalInput();
		echo '<input type="hidden" class="ano_tabela" value="'.$ano.'">';
		echo '<input type="hidden" class="id_tabela" value="'.$id.'">';
		echo '<div style="clear: both;height: 10px;"></div>';

		echo $balanco_patrimonial->gerarBallonInputA_c_caixa_equivalente_caixa("Saldo no banco no último dia do ano, mais valores em espécie e aplicações de curto prazo que serão resgatadas em um prazo inferior a 12 meses.");
		echo $balanco_patrimonial->gerarBallonInputA_c_contas_receber("Valores a receber em um prazo inferior a 12 meses, referentes a serviços prestados.");
		echo $balanco_patrimonial->gerarBallonInputA_c_estoques("Como sua empresa é uma prestadora de serviços, não há estoques a declarar.");
		echo $balanco_patrimonial->gerarBallonInputA_c_outros_creditos("Impostos a recuperar, adiantamento a terceiros, funcionários, adiantamento de 13°, adiantamento de férias.");
		echo $balanco_patrimonial->gerarBallonInputA_c_despesas_exercicio_seguinte("Despesas pagas antecipadamente, mas que se referem ao próximo ano. Exemplos: Prêmios de seguro (somente a parte proporcional ao ano seguinte); alugueis, assinatura de periódicos e anuidades, juros sobre descontos de duplicatas.");
		echo $balanco_patrimonial->gerarBallonInputA_c_total("");
		echo $balanco_patrimonial->gerarBallonInputA_n_c_contas_receber("Valores a receber em um prazo superior a 12 meses referentes a serviços prestados.");
		echo $balanco_patrimonial->gerarBallonInputA_n_c_investimentos("Bens e direitos não sejam destinados à manutenção das atividades normais da companhia. Exemplo: aplicações financeiras de caráter permanente, participações societárias em outras empresas e terrenos que não sejam de uso da empresa.");
		echo $balanco_patrimonial->gerarBallonInputA_n_c_imobilizado("Bens e direitos destinado à manutenção das atividades da empresa. Exemplo: máquinas e equipamentos, móveis e utensílios, ferramentas, veículos e terrenos de uso da empresa.");
		echo $balanco_patrimonial->gerarBallonInputA_n_c_intangivel("São Intangíveis os bens que não podem ser tocados ou vistos, já que são incorpóreos (não tem corpo). Eles possuem valor econômico mas carecem de substância física (material), tendo o valor patrimonial nos direitos de propriedade imaterial que são conferidos a seus possuidores. Exemplo: Patentes e intenções; Marcas registradas; Licenças; Direitos autorais; Benfeitorias; Websites e softwares; Tecnologia.");
		echo $balanco_patrimonial->gerarBallonInputA_n_c_depreciacao("");
		echo $balanco_patrimonial->gerarBallonInputA_n_c_total("");
		echo $balanco_patrimonial->gerarBallonInputP_c_fornecedores("Dívidas com seus fornecedores a serem quitadas até o final do ano seguinte. Inclui energia elétrica, internet, seguros etc. Atenção informe apenas aquelas já efetivamente devidas. Por exemplo conta de luz atrasada ou com o boleto já emitido e não a conta de luz futura. ");
		echo $balanco_patrimonial->gerarBallonInputP_c_emprestimos_bancarios("Informe o saldo devedor do empréstimo no último dia do ano do balanço.");
		echo $balanco_patrimonial->gerarBallonInputP_c_obrigacoes_sociais_impostos("DAS e INSS em atraso ou já geradas.");
		echo $balanco_patrimonial->gerarBallonInputP_c_contas_pagar("Dívidas assumidas com a compra de materiais, pagamento de juros e aluguel que vencerão até o final do ano seguinte ao balanço.");
		echo $balanco_patrimonial->gerarBallonInputP_c_lucros_distribuir("Parte do lucro apurado, ainda não distribuída, mas que pretende distribuir aos sócios/proprietário da empresa até o final do ano seguinte ao balanço.");
		echo $balanco_patrimonial->gerarBallonInputP_c_provisoes("Se você está fazendo uma reserva para pagar encargos futuros (cíveis, fiscais ou trabalhistas) previstos para vencer até o final do ano seguinte ao balanço, informe aqui o valor guardado.");
		echo $balanco_patrimonial->gerarBallonInputP_c_total();
		echo $balanco_patrimonial->gerarBallonInputP_n_c_contas_pagar("Dívidas a serem quitadas APÓS o final do ano seguinte.");
		echo $balanco_patrimonial->gerarBallonInputP_n_c_financiamentos_bancarios("Saldo de empréstimo a ser liquidado somente após o final do ano seguinte.");
		echo $balanco_patrimonial->gerarBallonInputP_n_c_total("");
		echo $balanco_patrimonial->gerarBallonInputP_l_capital_social("O valor do capital social registrado em nome de sua empresa.");
		echo $balanco_patrimonial->gerarBallonInputP_l_reservas_capital("São recursos que não foram obtidos pela empresa pela venda de seus produtos ou prestação de serviços. Por exemplo se você vende um equipamento ou qualquer outro bem de propriedade da empresa, o dinheiro recebido deve ser contabilizado no balanço como reserva de capital.");
		echo $balanco_patrimonial->gerarBallonInputP_l_ajustes_avaliacao_patrimonial("Se o valor de mercado de algum de seus ativos ou passivos for diferente do resultado apresentado no balanço patrimonial, você deve informar aqui a diferença. Por exemplo, a empresa adquiriu um automóvel por R$ 30 mil. Sobre este valor, nosso sistema aplica a depreciação por tempo de uso, resultando num valor final de R$ 26 mil. É este o montante que será registrado na coluna de ativos. No entanto o preço de mercado do carro é 28 mil, então o ajuste de avaliação patrimonial deste bem será de R$ 2 mil.");
		echo $balanco_patrimonial->gerarBallonInputP_l_reservas_lucro("Parte do lucro separada pela empresa como reserva, ou seja, que você não tem a intenção de mexer.");
		echo $balanco_patrimonial->gerarBallonInputP_l_lucros_acumulados("");
		echo $balanco_patrimonial->gerarBallonInputP_l_prejuizos_acumulados("");
		echo $balanco_patrimonial->gerarBallonInputP_l_total("");
		echo '</div>';

		echo $balanco_patrimonial->inserirIntangiveis();
		echo $balanco_patrimonial->inserirImobilizados();
		echo $balanco_patrimonial->emprestimosPrazo();
		echo $balanco_patrimonial->financiamentosPrazo();
		
		echo '<div class="carta pagina" style="text-align:justify">';

		$carta = new Carta();

		$carta->gerarCarta($_SESSION['id_empresaSecao'],$ano);

		$consulta = mysql_query("SELECT * FROM contingenciass WHERE id_user = '".$id."' AND ano = '".$ano."'  ");
		$objeto=mysql_fetch_array($consulta);

		$carta->gerarTextAreaContingencia($objeto['texto']);
		
		echo '<div style="display:none">';
		$carta->cadastroContador();
		echo '</div>';

		$consulta_dados_responsavel = mysql_query("SELECT * FROM dados_do_responsavel WHERE id = '".$_SESSION['id_empresaSecao']."' AND responsavel = '1'	");
		$objeto_dados_responsavel=mysql_fetch_array($consulta_dados_responsavel);

		$nome_responsavel = $objeto_dados_responsavel['nome'];
		$cpf_responsavel = $objeto_dados_responsavel['cpf'];

		echo '<div style="width:966px;"><br><center><button style="margin-right:20px;"class="btnPRINT">Imprimir Balanço</button><button id="btnCSV2">Exportar para Excel</button></center></div>';
 		echo '<center></center>';
 		echo '<div class="apenasImpressao" style="text-align:center;width:40%;float:left;margin-top:100px"><p>________________________________________</p>'.$nome_responsavel.'<br>CPF: '.$cpf_responsavel.'<br>Responsável Legal '.$nome_empresa.'</div>';
		echo '<div class="apenasImpressao" style="text-align:center;width:40%;float:right;margin-top:100px"><p>________________________________________</p><span class="impressao_contador_nome"></span><br><span class="tipo_contador2">CRC</span>: <span class="impressao_contador_crc"></span><br>Contador Responsável '.$nome_empresa.'</div>';
		echo '</div>';
		echo '<div style="clear: both;height: 10px;"></div>';
		echo '<div class="carta apenasImpressao" style="text-align:justify">';
		echo '<div style="clear: both;height: 10px;"></div>';
		// echo '<div class="tituloVermelho apenasImpressao"><strong>'.$_SESSION['nome_userSecao'].'</strong></div><br>';
		$carta->apendice1($_SESSION['id_empresaSecao'],$ano);
		echo '</div>';
?>


</div>
	<style type="text/css" media="screen">
		.a_n_c_intangivel,.a_n_c_imobilizado,.a_n_c_depreciacao,.p_l_lucros_acumulados,.p_l_prejuizos_acumulados,.p_c_emprestimos_bancarios,.a_c_caixa_equivalente_caixa,.a_c_contas_receber,.a_c_estoques,.a_c_outros_creditos,.a_c_despesas_exercicio_seguinte,.a_n_c_contas_receber,.a_n_c_investimentos,.p_c_fornecedores,.p_c_emprestimos_bancarios,.p_c_obrigacoes_sociais_impostos,.p_c_contas_pagar,.p_c_lucros_distribuir,.p_c_provisoes,.p_n_c_contas_pagar,.p_n_c_financiamentos_bancarios,.p_l_capital_social,.p_l_reservas_capital,.p_l_ajustes_avaliacao_patrimonial,.p_l_reservas_lucro{
			border:0;
			background: #e5e5e5;
		}
		.agrupado{
			font-weight: bold;
			color: #000;
		}
		.texto_ballon_explicacao{
			display: none;
		}
		.item{
			position: relative;
		}
		.subtitulo_tabela{
			/*background-color:#ccc;*/
			font-weight:bold;
			color:#000;
		}
		.titulo_tabela{
			background-color:#ccc;
			font-weight:bold;
			color:#000;
		}
		.td_calendario{
			color:#000;
		}
		.input_tabela{
			/*width: 90%;*/
			float: left;
		}
		.imagemDica{
			float: right;
			margin-top: 2px !important;
		}
		tr{
			height: 32px;
		}
		#abrirCadastrarValorPagoEmprestimos{
			outline: none;
		}

	</style>	
	<style type="text/css" media="print">
			@media print {
				#tabela_nao_circulante_ativo{
					margin-top: 64px !important;
				}
				#tabela_total_ativo{
					margin-top: 160px!important;
				}

				.celula_invisivel{
					border: 0 !important;
				}
				.celula_invisivel td{
					border: 0 !important;	
					border-left: 2px solid #fff;
				}
				body{
					margin-left: 1.91cm;
					margin-right: : 1.91cm;
					margin-top: 2.54cm;
					margin-bottom: : 2.54cm;
					font-family: arial !important;
					font-size: 12px!important;
				}
				table{
					border-spacing: 0!important;
					border:solid!important;
					border-width:1px 0px 0px 1px!important;
					border-color:#000!important;
					/*margin-left: 0!important;*/
					font-size: 12px!important;

				}
				tr{
					height: 32px !important;
				}
				td{
					border-style:solid!important;
					border-width:0px 1px 1px 0px!important;
					border-color:#000!important;
				}
				th{
					border-style:solid!important;
					border-width:0px 1px 1px 0px!important;
					border-color:#000!important;
				}
				.anexo_tabela{
					display: none;
				}
				.tituloVermelho,p,.apenasImpressao,th{
					font-family: arial;
					color:#000000 !important;
					font-style: bold;
				}
				.principal:first-child,.anos,.titulo,.tituloVermelho,.rodape,.btnPRINT,#btnCSV1,#btnCSV2,.imagemDica,form,button,a{
					display: none;
				}
				.hideImpressao{
					display: none !important; 	
				}
				.apenasImpressao{
					display: block !important;
				}
				th,td {
				    background-color: #024a68;
				    color: #FFF;
				    border: 1px solid #000;
				}
				input{
					border: 0;
				}
				table{
					border: 1px solid #000;
				}
				.td_calendario{
					color: #000;
					font-style: normal;
				}
			}
			.tipo_contador1,.tipo_contador2{
				text-transform: uppercase;
			}
			.pagina {page-break-after: always;text-align: justify;}
			.carta .tituloVermelho{
				display: block;
			}
			.carta{
				max-width: 900px;
			}
	</style>
	<script>

	$(".editar_item").change(function() {
		
		var valor = $(this).val();
		var valor = valor.replace(".", "");
		var valor = valor.replace(",", ".");
		var id = $(this).attr("id");
		var tabela = $(this).attr("tabela");
		var campo = $(this).attr("campo");

		$.ajax({
		  url:'ajax.php'
		  , data: 'editar_item=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
		  , type: 'post'
		  , async: true
		  , cache:false
		  , success: function(retorno){
		  	console.log(retorno);
		  }
		}); 
	});

	$("#abrirCadastrarValorPagoFinanciamentos").click(function() {
		
		$("#cadastrarValorPagoFinanciamentos").css('display', 'block');

	});

	$("#abrirCadastrarValorPagoEmprestimos").click(function() {
		
		$("#cadastrarValorPagoEmprestimos").css('display', 'block');

	});

	$(".contador_proprio").change(function() {
		if( $(".contador_proprio").attr("checked") === false ){
			$("#dados_contador").css('display', 'none');
			salvarTipoContador(0);
		}
		else{
			$("#dados_contador").css('display', 'block');
			salvarTipoContador(1);
		}


	});

	function salvarTipoContador(tipo){

		$.ajax({
			url:'ajax.php'
			, data: 'acao=salvarTipoContador'+'&tipo='+tipo
			, type: 'post'
			, async: true
			, cache:false
			, success: function(retorno){
				
			}
		});
	}

	$("#contador_PJ").click(function() {
		
		$("#contador_crc").css("display","table-row");
		$("#contador_cpf").css("display","none");

	});

	$("#contador_PF").click(function() {
		
		$("#contador_cpf").css("display","table-row");
		$("#contador_crc").css("display","none");

	});

	$("#estado_contador").change(function(event) {
		var uf = $(this).val();
		$.ajax({
			url: 'SEFIP_config.php',
			data: 'uf='+uf,
			dataType:"text",
			type:"POST",
			cache: false,
			success: function(response){
				$("#contador_cidade").empty();
				$("#contador_cidade").append(response);
			}
		});
	});
	$("#dados_contador input,#dados_contador select").change(function() {
		// erro = false;
		var nome = $("#dados_contador .nome").val();
		// if(nome === ''){
		// 	alert("Preencha o campo Nome");
		// 	erro = true
		// 	return;
		// }
		if( $("#contador_PJ").attr("checked") === true ){
			var crc = $("#dados_contador .crc").val();
			var tipo = 'crc';
		}
		else{
			var crc = $("#dados_contador .cpf_contador").val();
			var tipo = 'cpf';
		}
		// if(crc === ''){
		// 	alert("Preencha o campo Crc");
		// 	erro = true
		// 	return;
		// }
		var endereco = $("#dados_contador .endereco").val();
		// if(endereco === ''){
		// 	alert("Preencha o campo Endereco");
		// 	erro = true
		// 	return;
		// }
		var cidade = $("#dados_contador .cidade").val();
		// if(cidade === ''){
		// 	alert("Preencha o campo Cidade");
		// 	erro = true
		// 	return;
		// }
		var estado = $("#dados_contador .estado").val();
		// if(estado === ''){
		// 	alert("Preencha o campo Estado");
		// 	erro = true
		// 	return;
		// }
		var cep = $("#dados_contador .cep").val();
		// if(cep === ''){
		// 	alert("Preencha o campo Cep");
		// 	erro = true
		// 	return;
		// }
		var id_item = $("#dados_contador .id_item").val();
		// if( erro === false )
		salvarDadosContador(tipo,nome,crc,endereco,cidade,estado,cep,id_item);


	});

	function salvarDadosContador(tipo,nome,crc,endereco,cidade,estado,cep,id_item){
		$.ajax({
			url:'ajax.php'
			, data: 'acao=salvarContador&tipo='+tipo+'&nome='+nome+'&crc='+crc+'&endereco='+endereco+'&cidade='+cidade+'&estado='+estado+'&cep='+cep+'&id_item='+id_item
			, type: 'post'
			, async: true
			, cache:false
			, success: function(retorno){
				// $("#dados_contador").css("display","none");
				// $("#dados_contador").attr("status","fechado");
				$(".contador_nome_empresa_contabilidade").empty();
				$(".contador_nome_empresa_contabilidade").append(nome);
				$(".contador_CRC_empresa_contabilidade").empty();
				$(".contador_CRC_empresa_contabilidade").append(crc);
				$(".contador_endereco").empty();
				$(".contador_endereco").append(endereco);
				$(".contador_cidade").empty();
				$(".contador_cidade").append(cidade+', '+estado);
				$(".contador_cep").empty();
				$(".contador_cep").append(cep);
				// console.log(retorno);
				// if( id_item === '-' )
				location.reload();	
			}
		});

	}
		//Imobilizado
		function salvarDadosImobilizado(){

			var ano_aux = $(".ano_tabela").val();
			var id = $(".id_tabela").val();

			var selects = $("#itens_imobilizados").find('select');
			var quantidades = $("#itens_imobilizados").find('.input_quantidade');
			var valores = $("#itens_imobilizados").find('.input_valor');
			var anos = $("#itens_imobilizados").find('.input_ano');
			var ids = $("#itens_imobilizados").find('.input_id');
			var string = "";
			for (var i = 0; i < selects.length; i++) {
				var tipo = $(selects[i]).val();
				var quantidade = $(quantidades[i]).val();
				var valor = $(valores[i]).val();
				var ano = $(anos[i]).val();
				var id_aux = $(ids[i]).val();

				string = string + (tipo+'_;_'+quantidade+'_;_ '+valor+'_;_'+ano+'_;_'+id_aux+'_:_');

			};
			// console.log(string);

			$.ajax({
			  url:'ajax.php'
			  , data: 'acao=salvarImobilizados&itens='+string+'&ano='+ano_aux+'&id='+id
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  		// window.location.replace(window.location.href + "#imobilizados");
			  		// location.reload();
			  		console.log(retorno);
			  		obj = JSON.parse(retorno);
			  		if( obj[0] === 'erro' ){
			  			if( obj.length > 2 )
			  				var frase = 'Os seguintes itens não estão cadastrados no livro caixa\n\n';
			  			else
			  				var frase = 'O seguinte item não esta cadastrado no livro caixa\n\n';
			  			
			  			for (var i = 1; i < obj.length ; i++) {
			  				frase = frase + obj[i]+'\n';
			  			};
			  			alert(frase);
			  			return false;
			  		}	
			  		else if( obj[0] === 'ok' ){
			  			setTimeout('window.location.replace(window.location.href + "#imobilizados");location.reload();', 500);
			  		}
			  }
			}); 

		}
		$("#remover_imobilizado").click(function() {
					
			var selects = $("#itens_imobilizados").find('select');	
			var remover = $(selects[selects.length-1]);
			var linha = $(remover).parent().parent();
			$(linha).remove();
		

		});
		$("#btSalvarImobilizado").click(function() {
			
			salvarDadosImobilizado()
			
			// setTimeout('window.location.replace(window.location.href + "#imobilizados");location.reload();', 500);

		});


		$("#cadastrarImobilizado").click(function() {

			$("#cadastrar_imobilizado").css("display","block");
			$("#cadastrar_imobilizado").attr("status","aberto");

			$("#upload_anexos").find('.fecharDiv').attr("abrir","imobilizados");

		});

		$("#inserir_outro_imobilizado").click(function() {
			
			var tag = '#tag=1_';

			var tr = $("#itens_imobilizados").find('tr');

			tag = tag+parseInt(tr.length);

			$("#itens_imobilizados").append('<tr><td class="td_calendario" align="center" valign="middle" width="150"><select class="item"><option value="">Selecione</option><option value="Veículos">Veículos</option><option value="Imóveis (prédios)">Imóveis (prédios)</option><option value="Móveis e utensílios">Móveis e utensílios</option><option value="Computadores e periféricos">Computadores e periféricos</option><option value="Máquinas e equipamentos">Máquinas e equipamentos</option><option value="Terreno">Terreno</option></select></td><td class="td_calendario" align="center" valign="middle" width="150"><label><input class="input_quantidade" type="number" value="" min="1" style="width:50px;"></label></td><td class="td_calendario" align="center" valign="middle" width="150"><label><input class="input_valor currency" type="text" style="width:70px;"><input type="hidden" class="input_id" value=""></label></td><td class="td_calendario" align="center" valign="middle" width="100"><label><input class="campoData input_ano" type="text" style="width:60px;" size="10"></label></td><td class="td_calendario" align="center" valign="middle" width="40"><i class="fa fa-file-text-o anexo_aux" aria-hidden="true" tag="'+tag+'" ></i></td></tr>');
			setarMascara();

			$(".anexo_aux").click(function() {
					
				salvarDadosImobilizado();

				var tag = $(this).attr("tag");

				setTimeout('window.location.replace(window.location.href + "#imobilizados" +"'+tag+'");location.reload();', 500);

				// window.location.replace(window.location.href+tag);
				// location.reload();

			});


		});

		//INTANGIVEL


		function salvarDadosIntangiveis(){

			var ano_aux = $(".ano_tabela").val();
			var id = $(".id_tabela").val();

			var selects = $("#itens_intangiveis").find('select');
			var quantidades = $("#itens_intangiveis").find('.input_quantidade');
			var valores = $("#itens_intangiveis").find('.input_valor');
			var anos = $("#itens_intangiveis").find('.input_ano');
			var ids = $("#itens_intangiveis").find('.input_id');
			var string = "";
			for (var i = 0; i < selects.length; i++) {
				var tipo = $(selects[i]).val();
				var quantidade = $(quantidades[i]).val();
				var valor = $(valores[i]).val();
				var ano = $(anos[i]).val();
				var id_aux = $(ids[i]).val();

				string = string + (tipo+'_;_'+quantidade+'_;_ '+valor+'_;_'+ano+'_;_'+id_aux+'_:_');

			};
			// console.log(string);

			$.ajax({
			  url:'ajax.php'
			  , data: 'acao=salvarIntangiveis&itens='+string+'&ano='+ano_aux+'&id='+id
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  	console.log(retorno);
			  }
			}); 

		}
		$("#remover_intangiveis").click(function() {
					
			var selects = $("#itens_intangiveis").find('select');	
			var remover = $(selects[selects.length-1]);
			var linha = $(remover).parent().parent();
			$(linha).remove();
		

		});
		$("#btSalvarIntangiveis").click(function() {
			
			salvarDadosIntangiveis();

			setTimeout('window.location.replace(window.location.href + "#intangiveis");location.reload();', 500);
			// window.location.replace(window.location.href + "#intangiveis");
			// location.reload();

			// $("#cadastrar_intangiveis").css("display","none");			

		});

		$("#btCancelarIntangiveis").click(function() {
			$("#cadastrar_intangiveis").css("display","none");			
			location.reload();
		});

		$("#cadastrarIntangiveis").click(function() {

			$("#cadastrar_intangiveis").css("display","block");

			$("#cadastrar_intangiveis").attr("status","aberto");

			$("#upload_anexos").find('.fecharDiv').attr("abrir","intangiveis");

		});

		$("#inserir_outro_intangiveis").click(function() {

			var tag = '#tag=2_';

			var tr = $("#itens_intangiveis").find('tr');

			tag = tag+parseInt(tr.length);

			$("#itens_intangiveis").append('<tr><td class="td_calendario" align="center" valign="middle" width="150"><select class="item"><option value="">Selecione</option><option value="Software">Software</option><option value="Marca">Marca</option><option value="Patente">Patente</option><option value="Direitos autorais">Direitos autorais</option><option value="Licenças">Licenças</option><option value="Pesquisa e desenvolvimento">Pesquisa e desenvolvimento</option></select></td><td class="td_calendario" align="center" valign="middle" width="150"><label><input class="input_quantidade" type="number" value="" min="1" style="width:50px;"></label></td><td class="td_calendario" align="center" valign="middle" width="150"><label><input class="input_valor currency" type="text" style="width:70px;"><input type="hidden" class="input_id" value=""></label></td><td class="td_calendario" align="center" valign="middle" width="100"><label><input class="input_ano" type="text" style="width:50px;" size="4"></label></td><td class="td_calendario" align="center" valign="middle" width="40"><i class="fa fa-file-text-o anexo_aux" aria-hidden="true" tag="'+tag+'" ></i></td></tr>');
			setarMascara();

			$(".anexo_aux").click(function() {
					
				salvarDadosIntangiveis();

				var tag = $(this).attr("tag");
				setTimeout('window.location.replace(window.location.href+"'+tag+'");location.reload();', 500);
				

			});

		});

		$(".anexo_aux_intangiveis").click(function() {
					
			salvarDadosIntangiveis();

			var tag = $(this).attr("tag");

			setTimeout('window.location.replace(window.location.href+"'+tag+'");location.reload();', 500);

			// window.location.replace(window.location.href+tag);
			// location.reload();

		});

		$(".anexo_aux_imobilizados").click(function() {
					
			salvarDadosImobilizado();

			var tag = $(this).attr("tag");

			setTimeout('window.location.replace(window.location.href+"'+tag+'");location.reload();', 500);

			// window.location.replace(window.location.href+tag);
			// location.reload();

		});
		

		$("#btnCSV1").click(function() {
			var ano = $(".ano_tabela").val();
			var id = $(".id_tabela").val();
			window.location = 'exportar-csv-dre.php?id='+id+'&ano='+ano;
		});
		$("#btnCSV2").click(function() {
			var ano = $(".ano_tabela").val();
			var id = $(".id_tabela").val();
			window.location = 'exportar-csv-balanco.php?id='+id+'&ano='+ano;
		});
		$(".btnPRINT").click(function() {
			var texto = $("#contingenciaTexto").val();

			$("#textoContingencia").empty();
			$("#textoContingencia").append(texto);

			if( texto === "" ){
				$(".remove_contingencias").addClass('hideImpressao');
				$(".remove_contingencias").removeClass('apenasImpressao');
			}
			else{
				$(".remove_contingencias").removeClass('apenasImpressao');
				$(".remove_contingencias").addClass('apenasImpressao');
			}
				
			var ano_aux = $(".ano_tabela").val();
			var id = $(".id_tabela").val();	
			
			if( $(".contador_proprio").attr("checked") === true ){
				var nome = $("#dados_contador .nome").val();
				var crc = $("#dados_contador .crc").val();
				var endereco = $("#dados_contador .endereco").val();
				var estado = $("#dados_contador .estado").val();
				var cidade = $("#dados_contador .cidade").val();
				var cep = $("#dados_contador .cep").val();		
				var tipo = $("#dados_contador .tipo_item").val();		
			}
			else{
				var nome = $(".nosso_contador_nome").val();
				var crc = $(".nosso_contador_crc").val();
				var endereco = $(".nosso_contador_endereco").val();
				var estado = $(".nosso_contador_cidade").val();
				var cidade = $(".nosso_contador_estado").val();
				var tipo = $(".nosso_contador_tipo").val();
				var cep = $(".nosso_contador_cep").val();
			}
			
			

			//Dados do contador atual
			$(".contador_nome_empresa_contabilidade").empty();
			$(".contador_nome_empresa_contabilidade").append(nome);
			$(".contador_CRC_empresa_contabilidade").empty();
			$(".contador_CRC_empresa_contabilidade").append(crc);
			$(".contador_endereco").empty();
			$(".contador_endereco").append(endereco);
			$(".contador_cidade").empty();
			$(".contador_cidade").append(cidade+', '+estado);
			$(".contador_cep").empty();
			$(".contador_cep").append(cep);
			$(".tipo_contador1").empty();
			$(".tipo_contador1").append(tipo);
			$(".tipo_contador2").empty();
			$(".tipo_contador2").append(tipo);

			$(".impressao_contador_nome").empty();
			$(".impressao_contador_crc").empty();
			$(".impressao_contador_nome").append(nome);
			$(".impressao_contador_crc").append(crc);
			var ano_aux = $(".ano_tabela").val();
			var nome_arquivo = '<?php echo 'Balanço Patrimonial - '; ?>'+ano_aux;

			$("title").empty();

			$("title").append(nome_arquivo);

			window.print();

		});

		$("textarea").focusout(function() {
			var texto = $("#contingenciaTexto").val();

			$("#textoContingencia").empty();
			$("#textoContingencia").append(texto);

			if( texto === "" ){
				$(".remove_contingencias").addClass('hideImpressao');
				$(".remove_contingencias").removeClass('apenasImpressao');
			}
			else{
				$(".remove_contingencias").removeClass('apenasImpressao');
				$(".remove_contingencias").addClass('apenasImpressao');
			}

			var ano_aux = $(".ano_tabela").val();
			var id = $(".id_tabela").val();	

			$.ajax({
			  url:'ajax.php'
			  , data: 'acao=salvarContingencia&texto='+texto+'&ano='+ano_aux+'&id='+id
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  }
			}); 

		});

		$("#botGerarBalancoPatrimonial").click(function(event) {

			$("#form_balanco_patrimonial").submit();

		});

		$(".input_valor").focusout(function() {
			var ano = $(".ano_tabela").val();
			var id = $(".id_tabela").val();
			var campo = $(this).attr("name");
			var valor = $(this).val();
			$.ajax({
				  url:'ajax.php'
				  , data: 'acao=atualizarCampoBalanco&campo='+campo+'&valor='+valor+'&ano='+ano+'&id='+id
				  , type: 'post'
				  , async: true
				  , cache:false
				  , success: function(retorno){
				  	atualizaTotal(campo);
				  }
				}); 
			// alert("a_c_disponibilidade");
		});
		function setFloatVal(string){

			return string.replace(".", ",");

		}
		function inverterString(string){
			var num = Number(string).toFixed(2);
			num = setFloatVal(num);
			var j = -2;
			var aux = '';
			for (var i = num.length - 1; i >= 0; i--) {
				aux = aux + num[i];
				if( j % 3 === 0 && num.length > 6 && j > 1 && i > 0)
					aux = aux + '.';	
				j = j + 1;
			};
			result = '';
			for (var i = aux.length - 1; i >= 0; i--) {
				result = result + aux[i];
			};
			result = result.replace("-.", "-");
			return result;
		}
		function getFloatVal(classe){
			var valor = $(classe).val();
			var result = valor.replace(".", "");
			var result = result.replace(",", ".");
			if( result === '' )
				result = 0;
			return parseFloat(result);
		}
		function getFloatValHtml(classe){
			var valor = $(classe).html();
			var result = valor.replace(".", "");
			var result = result.replace(",", ".");
			if( result === '' )
				result = 0;
			return parseFloat(result);
		}
		function atualizaTotalAtivo(){
			var total =
				getFloatVal(".a_c_caixa_equivalente_caixa")+
				getFloatVal(".a_c_contas_receber")+
				getFloatVal(".a_c_estoques")+
				getFloatVal(".a_c_outros_creditos")+
				getFloatVal(".a_c_despesas_exercicio_seguinte")+
				getFloatVal(".a_n_c_contas_receber")+
				getFloatVal(".a_n_c_investimentos")+
				getFloatVal(".a_n_c_imobilizado")+
				getFloatVal(".a_n_c_intangivel")+
				getFloatVal(".a_n_c_depreciacao");
			
			$(".total_ativo_geral").empty();
			$(".total_ativo_geral").append(inverterString(total));
		}
		function atualizaTotalAtivoCirculante(){
			var total =
				getFloatVal(".a_c_caixa_equivalente_caixa")+
				getFloatVal(".a_c_contas_receber")+
				getFloatVal(".a_c_estoques")+
				getFloatVal(".a_c_outros_creditos")+
				getFloatVal(".a_c_despesas_exercicio_seguinte");
			$(".total_ativo_circulante").empty();
			$(".total_ativo_circulante").append(inverterString(total));
			atualizaTotalAtivo();
		}
		function atualizaTotalAtivoNaoCirculante(){
			var total =
				getFloatVal(".a_n_c_contas_receber")+
				getFloatVal(".a_n_c_investimentos")+
				getFloatVal(".a_n_c_imobilizado")+
				getFloatVal(".a_n_c_intangivel")+
				getFloatVal(".a_n_c_depreciacao");
			$(".total_ativo_nao_circulante").empty();
			$(".total_ativo_nao_circulante").append(inverterString(total));
			atualizaTotalAtivo();
		}
		function atualizaTotalPassivo(){
			var total =
				getFloatVal(".p_c_fornecedores")+
				getFloatVal(".p_c_emprestimos_bancarios")+
				getFloatVal(".p_c_obrigacoes_sociais_impostos")+
				getFloatVal(".p_c_contas_pagar")+
				getFloatVal(".p_c_provisoes")+
				getFloatVal(".p_c_lucros_distribuir")+
				getFloatVal(".p_n_c_contas_pagar")+
				getFloatVal(".p_n_c_financiamentos_bancarios")+
				getFloatVal(".p_l_capital_social")+
				getFloatVal(".p_l_reservas_capital")+
				getFloatVal(".p_l_ajustes_avaliacao_patrimonial")+
				getFloatVal(".p_l_reservas_lucro")+
				getFloatVal(".p_l_lucros_acumulados")+
				getFloatVal(".p_l_prejuizos_acumulados");
				
			$(".total_passivo_geral").empty();
			$(".total_passivo_geral").append(inverterString(total));
		}
		function atualizaTotalPassivoCirculante(){

			var total =
				getFloatVal(".p_c_fornecedores")+
				getFloatVal(".p_c_emprestimos_bancarios")+
				getFloatVal(".p_c_obrigacoes_sociais_impostos")+
				getFloatVal(".p_c_contas_pagar")+
				getFloatVal(".p_c_provisoes")+
				getFloatVal(".p_c_lucros_distribuir");
			$(".total_passivo_circulante").empty();
			$(".total_passivo_circulante").append(inverterString(total));
			atualizaTotalPassivo();
		}
		function atualizaTotalPassivoNaoCirculante(){

			var total =
				getFloatVal(".p_n_c_contas_pagar")+
				getFloatVal(".p_n_c_financiamentos_bancarios");
			$(".total_passivo_nao_circulante").empty();
			$(".total_passivo_nao_circulante").append(inverterString(total));
			atualizaTotalPassivo();
		}
		function atualizaTotalPatrimonioLiquido(){
			var total =
				getFloatVal(".p_l_capital_social")+
				getFloatVal(".p_l_reservas_capital")+
				getFloatVal(".p_l_ajustes_avaliacao_patrimonial")+
				getFloatVal(".p_l_reservas_lucro")+
				getFloatVal(".p_l_lucros_acumulados")+
				getFloatVal(".p_l_prejuizos_acumulados");
			$(".total_patrimonio_liquido").empty();
			$(".total_patrimonio_liquido").append(inverterString(total));
			atualizaTotalPassivo();
		}
		function atualizarCaixaInvestimento(){
			var total = 
					(
						getFloatVal(".a_n_c_contas_receber")+
						getFloatVal(".a_n_c_investimentos")+
						getFloatVal(".a_n_c_imobilizado")+
						getFloatVal(".a_n_c_intangivel")+
						getFloatVal(".a_n_c_depreciacao")
					) - getFloatValHtml(".total_ativo_nao_circulante");
			//Pega o total anterior
			total_anterior = getFloatVal(".a_n_c_investimentos") - total;
			//Define o novo total para o caixa, subtraindo o novo investimento e adicionando o total antrior
			total = getFloatVal(".a_c_caixa_equivalente_caixa")+total_anterior-getFloatVal(".a_n_c_investimentos");
			$(".a_c_caixa_equivalente_caixa").val(inverterString(total));
			atualizaTotalAtivoCirculante();
		
		}
		function atualizaTotal(campo){
			if( campo ===  "a_c_caixa_equivalente_caixa" )
				atualizaTotalAtivoCirculante();
			if( campo ===  "a_c_contas_receber" )
				atualizaTotalAtivoCirculante();
			if( campo ===  "a_c_estoques" )
				atualizaTotalAtivoCirculante();
			if( campo ===  "a_c_outros_creditos" )
				atualizaTotalAtivoCirculante();
			if( campo ===  "a_c_despesas_exercicio_seguinte" )
				atualizaTotalAtivoCirculante();
			if( campo ===  "a_n_c_contas_receber" )
				atualizaTotalAtivoNaoCirculante();
			if( campo ===  "a_n_c_investimentos" ){
				atualizarCaixaInvestimento();
				atualizaTotalAtivoNaoCirculante();
			}
			if( campo ===  "a_n_c_imobilizado" )
				atualizaTotalAtivoNaoCirculante();
			if( campo ===  "a_n_c_intangivel" )
				atualizaTotalAtivoNaoCirculante();
			if( campo ===  "a_n_c_depreciacao" )
				atualizaTotalAtivoNaoCirculante();
			if( campo ===  "p_c_fornecedores" )
				atualizaTotalPassivoCirculante();
			if( campo ===  "p_c_emprestimos_bancarios" )
				atualizaTotalPassivoCirculante();
			if( campo ===  "p_c_obrigacoes_sociais_impostos" )
				atualizaTotalPassivoCirculante();
			if( campo ===  "p_c_contas_pagar" )
				atualizaTotalPassivoCirculante();
			if( campo ===  "p_c_lucros_distribuir" )
				atualizaTotalPassivoCirculante();
			if( campo ===  "p_c_provisoes" )
				atualizaTotalPassivoCirculante();
			if( campo ===  "p_n_c_contas_pagar" )
				atualizaTotalPassivoNaoCirculante();
			if( campo ===  "p_n_c_financiamentos_bancarios" )
				atualizaTotalPassivoNaoCirculante();
			if( campo ===  "p_l_capital_social" )
				atualizaTotalPatrimonioLiquido();
			if( campo ===  "p_l_reservas_capital" )
				atualizaTotalPatrimonioLiquido();
			if( campo ===  "p_l_ajustes_avaliacao_patrimonial" )
				atualizaTotalPatrimonioLiquido();
			if( campo ===  "p_l_reservas_lucro" )
				atualizaTotalPatrimonioLiquido();
			if( campo ===  "p_l_lucros_acumulados" )
				atualizaTotalPatrimonioLiquido();
			if( campo ===  "p_l_prejuizos_acumulados" )
				atualizaTotalPatrimonioLiquido();
		}
	
	</script>
<style type="text/css" media="screen">
	.agrupado{
		font-weight: bold;
		color: #000;
	}
	.apenasImpressao{
		display: none;
	}
	.icone-download{
		cursor: pointer;
	}
	.excluirAnexoBalanco{
		cursor: pointer;
	}
	.anexo_aux,.anexo_aux_intangiveis,.anexo_aux_imobilizados{
		cursor: pointer;
	}
</style>
<style type="text/css" media="print">
	@media print {
		.principal:first-child,.anos,.titulo,.tituloVermelho,.rodape,.btnPRINT,#btnCSV{
			display: none;
		}
		th {
		    background-color: #024a68;
		    color: #FFF;
		}
		.erro_merda{
			min-width: 251px !important;
		}
		.erro_merda_{
			min-width: 130px !important;
		}
	}
</style>
<div id="upload_anexos" class="" style="width: 300px;left: 47.5%;margin-left: -223px;top: 195px;display: none;z-index: 999;position: fixed;border-radius: 0px;border: 1px solid rgb(204, 204, 204);padding: 20px;background: rgb(255, 255, 255);">
	<img class="fecharDiv" src="images/x.png" width="8" height="9" border="0" alt="Mídia sobre o Contador Amigo" title="" style="float: right;cursor:pointer" fechar="false">
	<div class="tituloVermelho" style="margin-bottom:20px;text-align: left;">Anexar comprovante do bem</div>
    <form id="form_anexos" action="upload-anexos-balanco.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">

    	<!-- <center> -->
			<table border="0" cellpadding="0" cellspacing="3" class="anexos_existentes" style="text-align:left">
				<tbody>
					
				</tbody>
			</table>
		<!-- </center>	 -->

    	<br>
	   
		<br><br>
		<!-- <center> -->
			<input type="hidden" name="tipo" class="tipo" value="">
			<input type="hidden" name="ano" class="ano" value="">
			<input type="hidden" name="id" class="id" value="">
			<input type="hidden" name="tag" class="tag" value="">

		<!-- </center> -->	

	</form>
</div>
<script>

	var aux_abrirdiv = String(location.hash);

	if( aux_abrirdiv.indexOf("tag") > 0 ){
		
		var pos = aux_abrirdiv.indexOf("tag");

		var tipo = aux_abrirdiv[pos+4];
		var item = '';
		for (var i = pos+6; i < aux_abrirdiv.length; i++) {
			item = item+aux_abrirdiv[i];
		};
		var tag = item;
		item = parseInt(item)-parseInt(1);

		if( tipo === '1' ){
			$("#cadastrarImobilizado").click();
			var tr = $("#itens_imobilizados").find('tr');
			var linha = tr.find('.abrirJanelaAnexos');
			abrirAnexos($(linha[item]).attr("titulo"),$(linha[item]).attr("tipo"),"#tag=1_"+tag);
		}
		if( tipo === '2' ){
			$("#cadastrarIntangiveis").click();
			var tr = $("#itens_intangiveis").find('tr');
			var linha = tr.find('.abrirJanelaAnexos');
			abrirAnexos($(linha[item]).attr("titulo"),$(linha[item]).attr("tipo"),"#tag=2_"+tag);
		}
		if( tipo === '0' ){
			// $("#cadastrarIntangiveis").click();
			// var tr = $("#itens_intangiveis").find('tr');
			// var linha = tr.find('.abrirJanelaAnexos');

			var anexos = $(".anexo_tabela i");
			var titulo = '';
			var tipo = '';
			for (var i = 0; i < anexos.length; i++) {
				if( tag === $(anexos[i]).attr("tipo") ){
					titulo = $(anexos[i]).attr("titulo");
					tipo = $(anexos[i]).attr("tipo");
				}
			};

			abrirAnexos(titulo,tipo,"#tag=0_"+tag);
		}

		window.location.href='#inicioTabelaBalanco';
		
	}

	if( aux_abrirdiv.indexOf("imobilizado") > 0 ){
		$("#cadastrarImobilizado").click();
		var url = window.location.href;

		url = url.replace("#imobilizados", "");
		url = url.replace("#intangiveis", "");
		url = url.replace("#undefined", "");
		window.location.href = url;
	}
	if( aux_abrirdiv.indexOf("intangiveis") > 0 ){
		$("#cadastrarIntangiveis").click();
		var url = window.location.href;

		url = url.replace("#imobilizados", "");
		url = url.replace("#intangiveis", "");
		url = url.replace("#undefined", "");
		window.location.href = url;
	}
	if( aux_abrirdiv.indexOf("undefined") > 0 ){
		var url = window.location.href;

		url = url.replace("#imobilizados", "");
		url = url.replace("#intangiveis", "");
		url = url.replace("#undefined", "");
		window.location.href = url;
	}

	
	$(".enviar_anexos").click(function() {
		
		$("#form_anexos").append('<input type="hidden" name="hash" value="'+$(this).parent().parent().parent().find(".fecharDiv").attr("abrir")+'">');
		if( $(this).parent().parent().find(".fecharDiv").attr("abrir") === "intangiveis" ){
			salvarDadosIntangiveis();	
		}
		if( $(this).parent().parent().find(".fecharDiv").attr("abrir") === "imobilizados" ){
			salvarDadosImobilizado();	
		}		
		$("#form_anexos").submit();
	});

	$(".abrirJanelaAnexos").click(function() {

		var titulo = $(this).attr("titulo");
		var tipo = $(this).attr("tipo");
		var ano = $(".ano_tabela").val();
		var id = $(".id_tabela").val();

		var tag = $(this).attr("tag");		

		// $("#upload_anexos").find('.fecharDiv').attr("abrir","imobilizados");

		$("#upload_anexos").find('.tituloVermelho').empty();

		$("#upload_anexos").find('.tituloVermelho').append("Anexar comprovante do bem");

		$("#upload_anexos").find('form').find('.tipo').val(tipo);		
		$("#upload_anexos").find('form').find('.ano').val(ano);		
		$("#upload_anexos").find('form').find('.id').val(id);		

		$("#upload_anexos").find('form').find('.tag').val(tag);

		$("#upload_anexos").css("display","block");

		$.ajax({
			url:'ajax.php'
			, data: 'acao=gerarItensAnexo&tipo='+tipo+'&ano='+ano+'&id='+id
			, type: 'post'
			, async: true
			, cache:false
			, success: function(retorno){
				$(".anexos_existentes tbody").empty();
				$(".anexos_existentes tbody").append(retorno);
				funcaoExcluirItem();
			}
		}); 


		

	});

	function abrirAnexos(item_titulo,item_tipo,tag){
		
		var titulo = item_titulo;
		var tipo = item_tipo;
		var ano = $(".ano_tabela").val();
		var id = $(".id_tabela").val();

		// $("#upload_anexos").find('.fecharDiv').attr("abrir","imobilizados");

		$("#upload_anexos").find('.tituloVermelho').empty();

		$("#upload_anexos").find('.tituloVermelho').append("Anexar comprovante do bem");

		$("#upload_anexos").find('form').find('.tipo').val(tipo);		
		$("#upload_anexos").find('form').find('.ano').val(ano);		
		$("#upload_anexos").find('form').find('.id').val(id);		

		$("#upload_anexos").find('form').find('.tag').val(tag);

		$("#upload_anexos").css("display","block");
		$.ajax({
			url:'ajax.php'
			, data: 'acao=gerarItensAnexo&tipo='+tipo+'&ano='+ano+'&id='+id
			, type: 'post'
			, async: true
			, cache:false
			, success: function(retorno){
				$(".anexos_existentes tbody").empty();
				$(".anexos_existentes tbody").append(retorno);
				funcaoExcluirItem();
			}
		}); 

	}
	

	function funcaoExcluirItem(){
		$(".excluirAnexoBalanco").click(function() {
			
			var id_item = $(this).attr("id");
			var id = $(".id_tabela").val();

			var confirmacao = confirm("Tem certeza que deseja excluir o anexo '"+$(this).attr("nome-arquivo")+"'");
			var item = $(this).parent().parent();

			if( confirmacao ){

				$.ajax({
					url:'ajax.php'
					, data: 'acao=excluirItemId'+'&id_item='+id_item+'&id='+id
					, type: 'post'
					, async: true
					, cache:false
					, success: function(retorno){
						$(item).remove();
					}
				});
			}
		});
	}

	

	$(".fecharDiv").click(function() {
		$(this).parent().css('display', 'none');

		if( $(this).attr("fechar") === "false" )
			return;

		var url = window.location.href;

		url = url.replace("#imobilizados", "");
		url = url.replace("#intangiveis", "");


		if( url.indexOf("#tag") > 0 ){
			var aux = '';
			for (var i = url.indexOf("#tag"); i < url.length; i++) {
				console.log(url[i]);
				aux = aux + url[i];

			};
			url = url.replace(aux, "");
		}
		
	});

	$("#btnCSVdre").click(function(event) {
		var aux = '';
		var anos = $("input");
		for (var i = 0; i < anos.length; i++) {
			if( $(anos[i]).attr("checked") === true ){
				aux = $(anos[i]).val();
			}
		};
		anos = aux;
		window.open("exportar-csv-dre.php?anos="+anos,'_blank');
	});

</script>
<script>
	$("table").find('.currency').maskMoney();	
	 $(function() {
	    $('.currency').maskMoney();
	  })

	 function setarMascara(){

	 	$("table").find('.currency').maskMoney();
	 	$("table").find('.campoData').mask('99/99/9999');
	 }
</script>	
	
<?php include 'rodape.php' ?>