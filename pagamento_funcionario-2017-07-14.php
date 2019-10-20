<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	// Verifica se foi solicitada a exclusão de algum pagamento
	if(isset($_POST['excluirPagtoId']) && !empty($_POST['excluirPagtoId'])) {
		
		ini_set('display_errors',1);
		ini_set('display_startup_erros',1);
		error_reporting(E_ALL);
		
		// inicia a sessão
		session_start();
	
		$empresaId = (isset($_SESSION["id_empresaSecao"]) ? $_SESSION["id_empresaSecao"] : 0 );
		
		// Realiza a requizição dos arquivo que realiza a conexão com o banco. 
		require_once('conect.php');
		require_once('Controller/pagamento_funcionario-controller.php');
		
		$ControlePF = new PagamentoFuncionarioController();
		
		// Chama o método para realizar a exclusão.
		$ControlePF->ExcluiPagamento($empresaId, $_POST['excluirPagtoId']);
	}

	require_once('header_restrita.php');
	require_once('Controller/pagamento_funcionario-controller.php');

	$empresaId = (isset($_SESSION["id_empresaSecao"]) ? $_SESSION["id_empresaSecao"] : 0 );

	// Instância da classe responsável por realizar os controle de dados do funcionário.
	$ControlePF = new PagamentoFuncionarioController();

	//Pega a data do filtro.
	$dataInicio = (isset($_GET['dataInicio']) ? $_GET['dataInicio'] : '');
	$dataFim = (isset($_GET['dataFim']) ? $_GET['dataFim'] : '');
	$classTipoFiltro = (isset($_GET['dataInicio']) ? 'campodata1' : 'campodata2');
?>

<div class="principal">

	<div style="position: relative; margin-bottom:30px; width:900px">

		<div class="titulo" style="margin-bottom:20px;">Pagamentos</div>
		<div class="tituloVermelho" style="float: left; margin-bottom:20px">Funcionários</div>
		<div style="clear:both;"></div>

		<div class="tituloVermelho" style="margin-bottom:10px">Emissão do Holerite</div>

		<form id="formGeraRPA" action="RPA_download.php" method="post">
			<?php
				// Inclui os input hidden com os valores e porcentagem do inss.
				echo $ControlePF->PegaValorINSS();
			?>

			<div>
				<!--nome -->
				<label for="NomeAutonomo" style="margin-right:10px">Nome do funcionário: </label> 

				<?php
					// Inclui o select com os Funcionários.
					echo $ControlePF->PegaDadosFuncionarios($empresaId);
				?>

				&nbsp;&nbsp;<a href="meus_dados_funcionario.php?act=new">cadastrar novo funcionário</a>
				&nbsp;&nbsp;ou
				&nbsp;&nbsp;<a id="atualizeFuncionario" href="#">alterar dados de funcionário já cadastrado</a>
				<div style="clear:both; height:10px"></div>

				<!--data -->
				<label for="DataPgto" style="margin-right:10px">Data do pagamento:</label> <input name="DataPgto" id="DataPgto" type="text" size="12" maxlength="50" class="campoData" value="" /> (dd/mm/aaaa)
				<div style="clear:both; height:10px"></div>

				<!--label for="ValorBruto" style="margin-right:10px">Valor bruto cobrado pelo funcionário (R$)</label-->
				<label style="margin-right:10px">Valor do Salário do funcionário (R$):</label>
				<input name="ValorSalario" id="ValorSalario" type="text" size="30" maxlength="12" class="current" /> 
				<div style="clear:both; height:10px"></div>
				
				
				<div id="ValorOpcional" data-quantidade='2' style="border: 1px solid #CCC; padding: 20px 0px 0px 20px; border-radius: 3px;">
					<label style="margin-top:-30px; margin-bottom:10px; display:block; background:#F5F6F7; width:89px; padding:0px 10px">Valor Adicionais</label>
				
					<div style="margin-bottom:20px"> 
						<select name="filtroValor1" id="filtroValor1" style="margin-right: 20px; margin-left: 24px;">
							<option value="">Selecione</option>
							<option value="ferias">Valor Ferias</option>
							<option value="extra">Valor Horas Extra</option>
							<option value="abono">Valor do Abono</option>
							<option value="familia">Salario Familia</option>
							<option value="martenidade">Salario Maternidade</option>
						</select>
						<input name="valorOpcional1" id="valorOpc1" class="valorOpc current" size="30" maxlength="12" type="text">
					</div>
					
				</div>
				
				<div style="clear:both; height:10px"></div>
				
				<a id="btAddValorOP" style="cursor: pointer; text-decoration: underline; color:#336699;">Adicionar</a> | <a id="btRemoveValorOP" style="cursor: pointer; text-decoration: underline; color:#336699;">Remover</a>
				
				<div style="clear:both; height:10px"></div>
				
				<label style="margin-right:10px"> Porcentagem de desconto do transporte (%): </label>
				<input name="descontoVT" id="descontoVT" type="text" size="6" maxlength="2" class="campoNumerico" value="0" />
				
				<div style="clear:both; height:10px"></div>

				<label style="margin-right:10px"> Porcentagem de desconto do vale refeição (%): </label>
				<input name="descontoVR" id="descontoVR" type="text" size="6" maxlength="2" class="campoNumerico" value="0" />
				
				<div style="clear:both; height:10px"></div>				
				
				<!--botao calculo -->
				<div style="margin-bottom:20px; margin-top:10px;">
					<input name="btnCalculaRetencoes" id="btnCalculaRetencoes" type="button" value="Calcular retenções" />
				</div>
			</div>

			<div class="destaqueAzul" style="margin-bottom:20px">Retenções devidas:</div>

			<div style="float:left; width:40px">INSS:</div> <input name="RetencaoINSS" id="RetencaoINSS" type="text" size="21" maxlength="50"  readonly="readonly" />
			<div style="clear:both; height:5px"></div>
			<div style="float:left; width:40px">IRRF:</div> <input name="RetencaoIR" id="RetencaoIR" type="text" size="21" maxlength="50"  readonly="readonly" />
			<div style="clear:both; height:5px"></div>
			<div style="float:left; width:40px">VT:</div> <input name="RetencaoVT" id="RetencaoVT" type="text" size="21" maxlength="50"  readonly="readonly" />
			<div style="clear:both; height:5px"></div>
			<div style="float:left; width:40px">VR:</div> <input name="RetencaoVR" id="RetencaoVR" type="text" size="21" maxlength="50"  readonly="readonly" />

			<div style="clear:both; height:5px"></div>
			<div style="margin-bottom:20px; margin-top:20px">
				Valor líquido a ser pago ao funcionário: <strong>R$</strong> <input name="ValorLiquido" id="ValorLiquido" type="text" size="21" maxlength="50" style="font-weight:bold" readonly />
			</div>

			<div id="caixa-botoes">
			<input name="btnGerarRPA" id="btnGerarRPA" type="button" value="Gerar Holerite" />

			<input type="button" id="btnNovoPagto" name="btnNovoPagto" value="Gerar novo RPA" style="display: none; " />
			</div>
		</form>

		<div style="clear:both; margin-bottom:20px"></div>

		<div class="tituloVermelho" style="margin-top:20px; margin-bottom:20px;">Pagamentos efetuados</div>

			<form id="form_filtro" method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">

				<div style="display:inline;float:left;margin-right:5px;">
					<?php
						// Inclui o select com os Funcionários.
						echo $ControlePF->PegaDadosFuncionariosFiltro($empresaId);
					?>
				</div>

				<div id="form_mes_ano" style="display:none; float:left; margin-right:5px;">
					No mês de 
					<?php
						// Inclui a lista de Meses.
						echo $ControlePF->FiltroMeses();
					?>
					de 
					<?php
						// Inclui a lista de Ano.
						echo $ControlePF->FiltroAno($empresaId);
					?>
				</div>

				<div id="form_outro_periodo" style="display:none;float:left;margin-right:5px;">
					Período de: <input name="dataInicio" id="dataInicio" value="<?php echo $dataInicio;?>" maxlength="10" style="width:80px" class="campoData" type="text"> 
					até: <input name="dataFim" id="dataFim" value="<?php echo $dataFim;?>" maxlength="10" style="width:80px" class="campoData" type="text"> 
				</div>

				<div style="display:inline;float:left;margin-right:5px;">
					<input name="hddTipoFiltro" id="hddTipoFiltro" value="mes" type="hidden">
					<input value="Pesquisar" type="submit">
				</div>

				<div style="display:inline;float:left;padding-top:5px;margin-right:5px;">
				ou <a id="link_outro_periodo" class="<?php echo $classTipoFiltro;?>" style="color:#336699; cursor:pointer; text-decoration: underline;">definir período maior</a>
				</div>

		</form>

		<div style="clear: both; margin-bottom: 20px;"></div>

		<table style="margin-bottom:25px;" width="970" cellpadding="5">
			<tr>
				<th width="7%">Ação</th>
				<th width="38%">Nome</th>
				<th width="9%">Data</th>
				<th width="9%">Valor Bruto</th>
				<th width="9%">INSS</th>
				<th width="9%">IR</th>
				<th width="9%">VR</th>
				<th width="10%">Valor Líquido</th>
			</tr>

			<?php
				// Inclui as linhas de pagamento na tabela
				echo $ControlePF->CriaTabelaComPagamentos();
			?>

		</table>

	</div>
</div>


<!--BALLOM excluir pagamento-->
<div class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; left:50%; margin-left: -400px; z-index: 9999;" id="aviso-delete-livro-caixa">
  <div style="padding:20px; font-size:12px;">
	  <div id="mensagemExcluirPagamento"></div><br>
	  <div style="clear: both; margin: 0 auto; display: inline;">
	  	<form action="/pagamento_funcionario.php" method="post">
			<center>
				<input id="excluirPagtoId" name="excluirPagtoId" type="hidden" value="">
				<button id="btExcluir" type="submit">Sim</button>
				<button id="btCancelar" type="button">Não</button>
			</center>
		</form>
	  </div>
	  <div style="clear: both;"></div>
  </div>
</div>
<!--FIM DO BALLOOM excluir pagamento -->

<script>
$(function(){
	
	//Variavel para realizar o controle de itens de valores adicionais.
	var controleValoresAdd = 2;
	
	$('#ValorOpcional').attr('data-quantidade', controleValoresAdd);

	// Chama o metodo de controle do filtro.
	FintroDeData();
	
	// Redireciona para página de edição do link.
	$('#atualizeFuncionario').click(function() {
		var funcionarioId = $('#funcionarioId').val();
		if( funcionarioId.length > 0 ){
			location.href='meus_dados_funcionario.php?editar=' + funcionarioId;
		}else{
			alert('Selecione um funcionário.');
		}
	});

	// Efetua ação do clique do botão calcular.
	$('#btnCalculaRetencoes').click(function(){ calculaRetencoes(); });
	
	// Ação do filtro da tabela.
	$('#link_outro_periodo').click(function() { FintroDeData();	});

	$('.excluirPagamento').bind('click',function(e){

		var mensagem = "Você tem certeza que deseja excluir este Pagamento?";
		var pagtoId = $(this).attr('data-pagtoId');
		
		$('#excluirPagtoId').val(0);
		$('#aviso-delete-livro-caixa').fadeOut(100);
		
		$('#excluirPagtoId').val(pagtoId);
		$('#aviso-delete-livro-caixa').find('#mensagemExcluirPagamento').html(mensagem);
		$('#aviso-delete-livro-caixa').css('top',($(this).offset().top - 50) + 'px').fadeIn(100);
	});
	
	$('#btCancelar').click(function() {
		
		$('#excluirPagtoId').val(0);		
		$('#aviso-delete-livro-caixa').fadeOut(100);		
	});
	
	$('#btAddValorOP').click(function(){
		
		if($('#ValorOpcional').attr('data-quantidade') != controleValoresAdd) {
			controleValoresAdd = $('#ValorOpcional').attr('data-quantidade');
		}
		
		
		var tag = '<div class="vrOpcional" style="margin-bottom:20px" id="campVl_'+controleValoresAdd+'">'
				+'  <a class="removeCamp" style="magin-right: 5px; text-decoration: none; cursor: pointer;" onclick="$(function(){ removeItemVlOp('+controleValoresAdd+'); });">'
				+'  	<i class="fa fa-trash-o iconesAzul iconesGrd"></i>'
				+'  </a>'
				+'	<select name="filtroValor'+controleValoresAdd+'" id="filtroValor'+controleValoresAdd+'" style="margin-right: 20px;">'
				+'		<option value="">Selecione</option>'
				+'		<option value="ferias">Valor Ferias</option>'
				+'		<option value="extra">Valor Horas Extra</option>'
				+'		<option value="abono">Valor do Abono</option>'
				+'		<option value="familia">Salario Familia</option>'
				+'		<option value="martenidade">Salario Maternidade</option>'	
				+'	</select>'
				+'	<input name="valorOpcional'+controleValoresAdd+'" id="valorOpc'+controleValoresAdd+'" class="valorOpc current" type="text" size="30" maxlength="12" />'
				+'</div>';
		
		if(controleValoresAdd <= 5) {
			
			controleValoresAdd = parseInt(controleValoresAdd) + 1;
			$('#ValorOpcional').attr('data-quantidade', controleValoresAdd);
			
			$( "#ValorOpcional" ).append( tag );
		}
	});

	// Remove todos itens adininais.
	$('#btRemoveValorOP').click(function() {
		
		controleValoresAdd = 2;
		$('#ValorOpcional').attr('data-quantidade', controleValoresAdd);
		
		$(".vrOpcional").remove();
	});	
	
	// Método criado para realizar o controle do filtro.
	function FintroDeData() {
		
		if($('#link_outro_periodo').attr('class') == 'campodata1') {
			$("#form_mes_ano").hide();
			$("#form_outro_periodo").show();
			$('#link_outro_periodo').attr('class', 'campodata2');
			$('#dataInicio').removeAttr("disabled");
			$('#dataFim').removeAttr("disabled");
			$('#periodoMes').attr("disabled", "disabled");
			$('#periodoAno').attr("disabled", "disabled");	
		} else {
			$("#form_mes_ano").show();
			$("#form_outro_periodo").hide();
			$('#link_outro_periodo').attr('class', 'campodata1');
			$('#dataInicio').attr("disabled", "disabled");
			$('#dataFim').attr("disabled", "disabled");		
			$('#periodoMes').removeAttr("disabled");
			$('#periodoAno').removeAttr("disabled");
		}
	}
	
	// Método criado para realizar o calculo.
	function calculaRetencoes(){

		var valorInss = 0;
		var porcentagemInss = 0;
		var valorInssAux = 0;
		var	porcentagemInssAux = 0;
		var valorDescontoInss = 0;		
		var salarioLiquido = 0;
		var valorDescontoVT = 0;
		var valorDescontoVR = 0;
		var valorDescontoVA = 0;
		var valorAdicionais = 0;
		var valorAddAux = 0;
		
		if($('#funcionarioId').val().length == 0){
			alert('Selecione um fucionário');
			$(this).focus();
			return false;
		}

		if($('#DataPgto').val().length == 0){
			alert('Preencha a data de pagamento.');
			$(this).focus();
			return false;
		}
		
		if($('#ValorSalario').val().length == 0){
			alert('Preencha o valor do salário do funcionário.');
			$(this).focus();
			return false;
		}
		
		var salario = $('#ValorSalario').val().replace('.','');	
		var salarioBase = salario.replace(',', '.');
		
		// Pega valores adicionais e acrecenta no salario salario bruto.
		$('.valorOpc').each(function(){
			
			if($(this).val() != 0){
				valorAddAux = $(this).val().replace('.','');
				valorAddAux = valorAddAux.replace(',', '.');
				valorAdicionais = parseFloat(valorAdicionais) + parseFloat(valorAddAux.replace(',', '.'));
			}
		});
		
		// Define o salario Bruto.
		salarioBase = parseFloat(salarioBase) + parseFloat(valorAdicionais);
		
		// Percorre a lista de Inss.
		$('.inss').each(function(){
			
			// Verifica se 
			if(parseFloat($(this).val()) > parseFloat(salarioBase) && porcentagemInss == 0) {
				valorInss = $(this).val();
				porcentagemInss = $(this).attr('data-porcentagem');
			} else {
				valorInssAux = $(this).val();
				porcentagemInssAux = $(this).attr('data-porcentagem');
			}
			
		});		
		
		// Realiza o calculo do inss.
		if(porcentagemInss != 0) {
			// Faz o calculo do inss encima do salário bruto.
			valorDescontoInss = ((salarioBase * porcentagemInss) / 100);
			valorDescontoInss = parseFloat(valorDescontoInss).toFixed(2);
		} else {
			// O valor do salário bruto ultrapassou o valor da tabela do inss.
			valorDescontoInss = ((valorInssAux * porcentagemInssAux) / 100);
			valorDescontoInss = parseFloat(valorDescontoInss).toFixed(2);
		}
				
		// Desconto do vale transporte.
		if($('#descontoVT').val() != 0){
			valorDescontoVT = ((salarioBase * $('#descontoVT').val()) / 100);
			valorDescontoVT = parseFloat(valorDescontoVT).toFixed(2);
		}
		
		// Desconto do vale refeição.
		if($('#descontoVR').val() != 0){
			valorDescontoVR = ((salarioBase * $('#descontoVR').val()) / 100);
			valorDescontoVR = parseFloat(valorDescontoVR).toFixed(2);
		}	
				
		salarioLiquido = salarioBase - valorDescontoInss - valorDescontoVR - valorDescontoVT;
		
		salarioLiquido = parseFloat(salarioLiquido.toFixed(2));
		
		// Campos de Retenção
		$('#RetencaoINSS').val(formataCampoMoeda(limpaCaracteres(valorDescontoInss)));
		$('#RetencaoIR').val('0,00');
		$('#RetencaoVR').val(formataCampoMoeda(limpaCaracteres(valorDescontoVR)));
		$('#RetencaoVT').val(formataCampoMoeda(limpaCaracteres(valorDescontoVT)));
		$('#ValorLiquido').val(formataCampoMoeda(limpaCaracteres(parseFloat(salarioLiquido).toFixed(2))));
	}
	
	$('.campoNumerico').keyup(function() {
	  $(this).val(this.value.replace(/\D/g, ''));
	});
	
	// Coverte em moeda.
	function formataCampoMoeda(varValor){
		
		
		if(varValor == 0) {
			varValor = 0;
			varResult = 0.00;
		}
		
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
});	
	
// Remove o item do valor adicional
function removeItemVlOp(num) {

	var removeCampo = '#campVl_'+num;
	var numAtual = $('#ValorOpcional').attr('data-quantidade');
	var numNew = numAtual - 1; 	
	
	//controleValoresAdd = controleValoresAdd - 1;
	$('#ValorOpcional').attr('data-quantidade', numNew);
	$(removeCampo).remove();
}
	
</script>	



<?php include 'rodape.php' ?>
