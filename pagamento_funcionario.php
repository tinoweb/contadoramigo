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
		$ControlePF->ExcluiPagamento($empresaId, $_POST['excluirPagtoId'], $_POST['tipoPagamento']);
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

	
		<div class="titulo">Pagamentos</div>
		
		
	
	<?php
		// Verifica se esta página esta sendo acessada pelo o contador.
		//	if(isset($_SESSION['DadosContador']) && !empty($_SESSION['DadosContador'])):		
	?>
		
		<div class="tituloVermelho" style="margin-bottom: 20px">Funcionários</div>
		<div style="margin-bottom: 30px">Esta página para o cálculo das retenções no pagamento de funcionários é <strong>apenas referencial</strong>. Empresas com funcionários precisam aderir ao <strong>plano Premium</strong> para que a folha de salários seja validada por um contador.</div>

		<form id="formGeraHolerite" action="pagamento_funcionario_grava.php" method="post">
		
			<input name="empresaId" id="empresaId" type="hidden" value="<?php echo $empresaId;?>" />
			<input name="porcInss" id="porcInss" type="hidden" value="" />
			<input name="porcIR" id="porcIR" type="hidden" value="" />
			<input name="porcVT" id="porcVT" type="hidden" value="" />
			<input name="porcVR" id="porcVR" type="hidden" value="" />
			<input name="faixaIR" id="faixaIR" type="hidden" value="" />
			<input name="descontoDep" id="descontoDep" type="hidden" value="" />
			<input name="valorPensao" id="valorPensao" type="hidden" value="" />
			<input name="porcPensao" id="porcPensao" type="hidden" value="" />
			<input name="salario_Funcionario" id="salario_Funcionario" type="hidden" value="" />
			<input name="valor_Salario" id="valor_Salario" type="hidden" value="" />			
			<input name="salario_Bruto" id="salarioBruto" type="hidden" value="" />
			<input name="retencao_INSS" id="retencao_INSS" type="hidden" value="" />
			<input name="retencao_IR" id="retencao_IR" type="hidden" value="" />
			<input name="retencao_VR" id="retencao_VR" type="hidden" value="" />
			<input name="retencao_VT" id="retencao_VT" type="hidden" value="" />			
			<input name="retencao_Faltas" id="retencao_Faltas" type="hidden" value="" />
			<input name="valor_Liquido" id="valor_Liquido" type="hidden" value="" />
			<input name="diasTrabalhado" id="diasTrabalhado" type="hidden" value="" />
			<input name="valorFeriasMes1" id="valorFeriasMes1" type="hidden" value="" />
			<input name="valorFeriasMes2" id="valorFeriasMes2" type="hidden" value="" />
			<input name="valorFerias" id="valorFerias" type="hidden" value="" />
			<input name="valorUmTercoFerias" id="valorUmTercoFerias" type="hidden" value="" />			
			<input name="vendaUmTercoFerias" id="vendaUmTercoFerias" type="hidden" value="" />
			<input name="valorFeriasVendida" id="valorFeriasVendida" type="hidden" value="" />
			<input name="valorUmTercoFeriasVendida" id="valorUmTercoFeriasVendida" type="hidden" value="" />
			<input name="porcIRFerias" id="porcIRFerias" type="hidden" value="" />
			<input name="valorIRFerias" id="valorIRFerias" type="hidden" value="" />
			<input name="valorAbonoPecuniario" id="valorAbonoPecuniario" type="hidden" value="" />
			<input name="abonoPecuniarioUmTerco" id="abonoPecuniarioUmTerco" type="hidden" value="" />
			<input name="totalValoresFeriasAbono" id="totalValoresFeriasAbono" type="hidden" value="" />
			<input name="numDependentes" id="numDependentes" type="hidden" value="" />			
			<input name="diasFerias" id="diasDeFerias" type="hidden" value="" />
			<input name="liquidoFerias" id="liquidoFerias" type="hidden" value="" />
			<input name="feriasId" id="feriasId" type="hidden" value="" />
			<input name="porcInssSecundario" id="porcInssSecundario" type="hidden" value="" />
			<input name="retencaoSecundarioINSS" id="retencaoSecundarioINSS" type="hidden" value="" />
			<input name="GravarLivroCaixa" id="GravarLivroCaixa" type="hidden" value="Não" />
			
			<div>
				<!-- Tipo de pagamento -->
				<label for="TipoPagamento" style="margin-right:10px">Tipo Pagamento: </label> 
				<select id="tipoPagto" name="tipoPagto">
					<option value="salario">Salário</option>
					<option value="ferias">Férias</option>
					<option value="decimoTerceiro">Décimo Terceiro</option>
				</select>
				
				<div style="clear:both; height:10px"></div>
				
				<!-- Parcela do décimo terceiro -->
				<div id="campoDecimoTerceiroParcela" style="display:none;">
					<label for="decimoParcela" style="margin-right:10px">Parcela: </label> 				
					<select id="decimoTerceiroParcela" name="decimoTerceiroParcela">
						<option value="0">Única</option>
						<option value="1">Primeira</option>
						<option value="2">Segunda</option>
					</select>
				
					<div style="clear:both; height:10px"></div>
				</div>
			
				<!--nome -->
				<label for="NomeAutonomo" style="margin-right:10px">Nome do funcionário: </label> 

				<?php
					// Inclui o select com os Funcionários.
					echo $ControlePF->PegaDadosFuncionarios($empresaId);
				?>

				&nbsp;&nbsp;<a href="meus_dados_funcionario.php?act=new">cadastrar novo funcionário</a>
				
				<?php
					if($ControlePF->ExisteFuncionario($empresaId)){
						echo ' &nbsp;&nbsp; ou &nbsp;&nbsp; <a id="atualizeFuncionario" href="#">alterar dados de funcionário já cadastrado</a>';	
					}
				?>
				<div style="clear:both; height:10px"></div>

			
				<div id="campoSalario">
					<!--data-->
					<label for="DataPgto" style="margin-right:10px">Data do Pagamento:</label> <input name="dataPgto" id="DataPgto" type="text" size="9" maxlength="10" class="campoData" value="" /> (dd/mm/aaaa)
					
					<div style="clear:both; height:10px"></div>
					
					<!--data-->
					<label for="dataReferencia" style="margin-right:10px">Referente ao Mês e Ano:</label> <input name="dataReferencia" id="dataReferencia" type="text" size="6" maxlength="7" class="campoDataMesAno" value="" /> (mm/aaaa)
					
					<div style="clear:both; height:10px"></div>					
																					
				</div>
				
				
				<!--Dias trabalhados-->
				<div id="campoDiasTrabalhadoFaltas">
					<label id="labelFaltas" for="faltas" style="margin-right:10px">Faltas:</label> <input name="txtFaltas" id="faltas" type="text" size="2" maxlength="2" class="inteiro" value="" />
				
					<div style="clear:both; height:10px"></div>
				</div>
				
				<!-- Dias de férias que pretende tirar -->
				<div id="campoDiasferias" style="display:none;">
				
					<label style="margin-right:10px">Data do Pagamento:</label> <input name="dataPagtoFeiras" id="dataPagtoFeiras" type="text" size="9" maxlength="10" class="campoData" value="" /> (dd/mm/aaaa)
					
					<div style="clear:both; height:10px"></div>
						
					<!-- Periodo de aquisição -->
					<label style="margin-right:10px">Período de gozo de:</label> 
					<input name="dataFeriasInicio" id="dataFeriasInicio" type="text" size="12" maxlength="10" class="campoData" value="" /> 
					<label style="margin-right:5px; margin-left:5px;">até:</label> 
					<input name="dataFeriasFim" id="dataFeriasFim" type="text" size="12" maxlength="10" class="campoData" value="" />
					
					<!-- Numeros de dias -->				
					<label id="labelDiasferias" style="margin-right:10px; margin-left:10px">Numero de dias de férias:</label> <input name="txtDiasferias" id="diasferias" type="text" size="2" maxlength="2" class="inteiro" value="" style="margin-right: 10px;" readonly disabled />				
<!--				
					<label id="labelHorasExtrasAno" style="margin-right:10px">Valor Médio de Horas Extras no Ano:</label>
					<input name="txtHorasExtrasAno" id="HorasExtrasAno" type="text" size="12" class="current" value="" style="margin-right: 10px;" />-->
					
					<div style="clear:both; height:10px"></div>
					
					<label id="labelAbonoPecuniario" style="margin-right:10px">Será realizada a venda de 1/3 das férias? :</label> 
					
					<input id="vFeriasSim" name="vendaFerias" value="S" type="radio">
					<label>Sim</label>
					<input id="vFeriasNao" name="vendaFerias" value="N" type="radio" checked="" />
					<label>Não</label>
					
					<div style="clear:both; height:10px"></div>
					
					<div id="periodoAbono" style="display: none;">
						<!-- Periodo do abono -->
						<label style="margin-right:10px">Período do abono de:</label> 
						<input name="dataFeriasAbonoInicio" id="dataFeriasAbonoInicio" type="text" size="12" maxlength="10" class="campoData" value="" /> 
						<label style="margin-right:5px; margin-left:5px;">até:</label> 
						<input name="dataFeriasAbonoFim" id="dataFeriasAbonoFim" type="text" size="12" maxlength="10" class="campoData" value="" />
					</div>
					
					<div style="clear:both; height:10px"></div>					
				</div>
									
				<!--Número Meses Trabalhados-->
				<div id="campoDecimoMesesTrabalhado" style="display:none;">
					<label for="decimoMesesTrabalhado" style="margin-right:10px">Número Meses Trabalhados:</label> <input name="txtDecimoMesesTrabalhado" id="decimoMesesTrabalhado" type="text" size="2" maxlength="2" class="inteiro" value="" style="margin-right: 10px;" />
					<div style="clear:both; height:10px"></div>
				</div>									
	
				<div id="outrosProventos" style="border: 1px solid #CCC; margin-top:20px; padding: 20px 0px 0px 10px; border-radius: 3px;">
					<div id="ValorOpcional" data-quantidade='2'>
						<label style="margin-top:-30px; margin-bottom:10px; display:block; background:#F5F6F7; width:100px; padding:0px 10px">Outros Proventos</label>

						<div style="margin-bottom:20px"> 
							<select name="filtroValor1" class="filtroValor" id="filtroValor1" style="margin-right: 20px; margin-left: 24px;">
								<option value="">Selecione</option>
								<option value="abono">Abono</option>
								<option value="bonus">Bônus</option>
								<option value="familia">Salario Familia</option>
								<option value="martenidade">Salario Maternidade</option>
							</select>
							<input name="valorOpcional1" id="valorOpc1" class="valorOpc" size="30" maxlength="12" type="text" data-itemid="1" />
						</div>
					
					</div>
								
					<a id="btAddValorOP" style="margin-left: 5px; cursor: pointer; text-decoration: underline; color:#336699;">Adicionar</a> | <a id="btRemoveValorOP" style="cursor: pointer; text-decoration: underline; color:#336699;">Remover</a>
					
					<div style="clear:both; height:10px"></div>
				</div>
								
				<div style="clear:both; height:10px"></div>
				
				<!--botao calculo -->
				<div style="margin-bottom:20px; margin-top:10px;">
					<input name="btnCalculaRetencoes" id="btnCalculaRetencoes" type="button" value="Calcular retenções" />
					<div id="divCarregando" style="margin-top: 10px; text-align: center; width: 157px; display: none;">
						<img src="images/loading.gif" width="16" height="16">
					</div>
				</div>
			</div>
			
			<label style="margin-right:10px">Data Admissão:</label>
			<input id="dataAdmissao" type="text" size="8" maxlength="50"  readonly="readonly" disabled/>
			
			<div style="clear:both; height:10px"></div>	
			
			<label style="margin-right:10px">Total dos vencimentos (R$):</label>
			<input name="totalVencimentos" id="totalVencimentos" type="text" size="30" maxlength="12" class="current" readonly disabled/>
			
			<div style="clear:both; height:10px"></div>			
												
			<div class="destaqueAzul" style="margin-bottom:20px">Retenções devidas:</div>

			<div style="float:left; width:50px; text-align: right; padding-right: 10px">INSS:</div> 
			<input name="retencaoINSS" id="RetencaoINSS" type="text" size="21" maxlength="50"  readonly="readonly" disabled/>
			<div style="clear:both; height:5px"></div>
			<div style="float:left; width:50px; text-align: right; padding-right: 10px">IRRF:</div>
			<input name="retencaoIR" id="RetencaoIR" type="text" size="21" maxlength="50"  readonly="readonly" disabled/>
			
			<div id="campoDescontos">
			
				<div class="destaqueAzul" style="margin-bottom:10px; margin-top: 15px;">Descontos:</div>	
			
				<div style="clear:both; height:5px"></div>
				<div style="float:left; width:50px; text-align: right; padding-right: 10px">VT:</div>
				<input name="retencaoVT" id="RetencaoVT" type="text" size="21" maxlength="50"  readonly="readonly" disabled/>

				<div style="clear:both; height:5px"></div>
				<div style="float:left; width:50px; text-align: right; padding-right: 10px">VR:</div>
				<input name="retencaoVR" id="RetencaoVR" type="text" size="21" maxlength="50"  readonly="readonly" disabled/>

				<div style="clear:both; height:5px"></div>
				<div style="float:left; width:50px; text-align: right; padding-right: 10px">Faltas:</div>
				<input name="retencaoFaltas" id="RetencaoFaltas" type="text" size="21" maxlength="50"  readonly="readonly" disabled/>
				
			</div>
			
			<div id="campoDescontos13Salario" style="display: none;">
			
				<div class="destaqueAzul" style="margin-bottom:10px; margin-top: 15px;">Descontos:</div>	
			
				<div style="clear:both; height:5px"></div>
				<div style="float:left; width:110px; text-align: right; padding-right: 10px">Adiantamento 13°:</div>
				<input name="adiantamento13Salario" id="adiantamento13Salario" type="text" size="21" maxlength="50"  readonly="readonly" disabled/>
				
			</div>
			
			<div style="clear:both; height:5px"></div>
			<div style="margin-bottom:20px; margin-top:20px">
				Valor líquido a ser pago ao funcionário: <strong>R$</strong> <input name="ValorLiquido" id="ValorLiquido" type="text" size="21" maxlength="50" style="font-weight:bold" readonly disabled />
			</div>

			<div id="caixa-botoes">
				<input name="btnGerarHolerite" id="btnGerarHolerite" type="button" value="Gerar Holerite" />
			</div>
		</form>

		<div style="clear:both; margin-bottom:20px"></div>
	<?php 
		// Final do IF que realiza a verificação se esta pagina esta sendo logada pelo contador.
		//endif;
	?>
		<div class="tituloVermelho" style="margin-top:20px; margin-bottom:20px;">Pagamentos efetuados</div>

			<form id="form_filtro" method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">

				<div style="display:inline;float:left;margin-right:5px;">
					<?php
						// Inclui o select com os Funcionários.
						
						if(isset($_GET['funcionarioId']) && !empty($_GET['funcionarioId']) ) {
							echo $ControlePF->PegaDadosFuncionariosFiltro($empresaId, $_GET['funcionarioId']);
						} else {
							echo $ControlePF->PegaDadosFuncionariosFiltro($empresaId);
						}
					?>
				</div>

				<div id="form_mes_ano" style="display:none; float:left; margin-right:5px;">
					No mês de 
					<?php
						// Inclui a lista de Meses.
						echo $ControlePF->FiltroMeses($empresaId);
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
				<th width="18%">Nome</th>
				<th width="15%">Tipo Pagto</th>
				<th width="10%">Data</th>
				<th width="10%">vencimentos</th>
				<th width="10%">INSS</th>
				<th width="10%">IR</th>
				<th width="10%">descontos</th>
				<th width="10%">Líquido</th>
			</tr>

			<?php
				// Inclui as linhas de pagamento na tabela
				echo $ControlePF->CriaTabelaComPagamentos();
			?>

		</table>

	
</div>


<!--BALLOM excluir pagamento-->
<div class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; left:50%; margin-left: -400px; z-index: 9999;" id="aviso-delete-livro-caixa">
  <div style="padding:20px; font-size:12px;">
	  <div id="mensagemExcluirPagamento"></div><br>
	  <div style="clear: both; margin: 0 auto; display: inline;">
	  	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<center>
				<input id="excluirPagtoId" name="excluirPagtoId" type="hidden" value="" />
				<input id="tipoPagamento" name="tipoPagamento" type="hidden" />
				<button id="btExcluir" type="submit">Sim</button>
				<button id="btCancelar" type="button">Não</button>
			</center>
		</form>
	  </div>
	  <div style="clear: both;"></div>
  </div>
</div>
<!--FIM DO BALLOOM excluir pagamento -->

<!--BALLOM Livro Caixa -->
<div id="aviso-livro-caixa" class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; margin-left:-145.5px; left:50%; z-index: 999;">
  <div style="padding:20px; font-size:12px;">
	Deseja cadastrar este pagamento no seu livro caixa?<br><br>            
	<div style="clear: both; margin: 0 auto; display: inline;">
		<center>
		<button id="btSIMLivroCaixa" type="button" idPagto="">Sim</button>
		<button id="btNAOLivroCaixa" type="button">Não</button>
	  </center>
	</div>
	<div style="clear: both;"></div>
  </div>
</div>
<!--FIM DO BALLOOM Livro Caixa -->

<script>
	
var calculoOk = false;	
	
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
		$('#tipoPagamento').val('holerite');
		$('#aviso-delete-livro-caixa').fadeOut(100);
		
		$('#excluirPagtoId').val(pagtoId);
		$('#aviso-delete-livro-caixa').find('#mensagemExcluirPagamento').html(mensagem);
		$('#aviso-delete-livro-caixa').css('top',($(this).offset().top - 50) + 'px').fadeIn(100);
	});
	
	$('.excluirPagtoFerias').bind('click',function(e){

		var mensagem = "Você tem certeza que deseja excluir este Pagamento?";
		var pagtoId = $(this).attr('data-feriasId');
		
		$('#excluirPagtoId').val(0);
		$('#tipoPagamento').val('ferias');
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
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;
		
		if($('#ValorOpcional').attr('data-quantidade') != controleValoresAdd) {
			controleValoresAdd = $('#ValorOpcional').attr('data-quantidade');
		}
		
		var tag = '<div class="vrOpcional" style="margin-bottom:20px" id="campVl_'+controleValoresAdd+'">'
				+'  <a class="removeCamp" style="magin-right: 5px; text-decoration: none; cursor: pointer;" onclick="$(function(){ removeItemVlOp('+controleValoresAdd+'); });">'
				+'  	<i class="fa fa-trash-o iconesAzul iconesGrd"></i>'
				+'  </a>'
				+'	<select class="filtroValor" name="filtroValor'+controleValoresAdd+'" id="filtroValor'+controleValoresAdd+'" style="margin-right: 20px;">'
				+'		<option value="">Selecione</option>'
				+'		<option value="abono">Abono</option>'
				+'		<option value="bonus">Bonus</option>'
				+'		<option value="familia">Salario Familia</option>'
				+'		<option value="martenidade">Salario Maternidade</option>'	
				+'	</select>'
				+'	<input name="valorOpcional'+controleValoresAdd+'" id="valorOpc'+controleValoresAdd+'" class="valorOpc" type="text" size="30" maxlength="12" data-itemid="'+controleValoresAdd+'" />'
				+'</div>';
		
		if(controleValoresAdd <= 4) {
			
			controleValoresAdd = parseInt(controleValoresAdd) + 1;
			$('#ValorOpcional').attr('data-quantidade', controleValoresAdd);
			
			$( "#ValorOpcional" ).append( tag );
		}
	});

	// Remove todos itens adininais.
	$('#btRemoveValorOP').click(function() {
		
		controleValoresAdd = 2;
		$('#ValorOpcional').attr('data-quantidade', controleValoresAdd);
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;
		
		$(".vrOpcional").remove();
	});	
	
	// Solicita a gravação dos dados do pagamento do contador.
	$('#btnGerarHolerite').click(function(){
		if(validaCampos()) {
										
			var position = $(this).offset().top - 50;							
			
			checkDataDemissao(position);
			//$('#formGeraHolerite').submit();
		}
	});
	
	// Mostra período do abono
	$('#vFeriasSim').click(function() {
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;
		
		$("#periodoAbono").show();	
	});
	
	// Oculta período do abono e limpa os campos.
	$('#vFeriasNao').click(function() {
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;		
		
		$("#periodoAbono").hide();
		$('#dataFeriasAbonoInicio, #dataFeriasAbonoFim').val('');
		// limpa os campos
		$("#dataFeriasAbonoInicio").val('');
		$("#dataFeriasAbonoFim").val('');
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
	
	// Valida do campos.
	function validaCampos() {
		
		if($('#funcionarioId').val().length == 0){
			alert('Selecione um fucionário');
			$(this).focus();
			return false;
		}

		
		if($('#tipoPagto').val() != 'ferias') {
			
			if($('#DataPgto').val().length == 0){
				alert('Preencha a data de pagamento.');
				$(this).focus();
				return false;
			}
			
			// Define Parametros para validação da data.
			var dataPgtoFomat = dataCorreta($('#DataPgto').val());
			var checkDataPgto = new Date(dataPgtoFomat);

			if(checkDataPgto == 'Invalid Date') {
				alert('Preencha a data de pagamento corretamente.');
				$('#dataPagtoFeiras').focus();
				return false;
			}		
			
			if($('#dataReferencia').val().length == 0) {
				alert('Preencha o mês e ano de pagamento.');
				$(this).focus();
				return false;
			}
			
			// Define Parametros para validação da data.
			var dataPagtoArray = ($('#dataReferencia').val().split("/")); 
			var dataPgtoFomat = dataPagtoArray[0]+'/01/'+dataPagtoArray[1];
			var checkDataPgto = new Date(dataPgtoFomat);

			if(checkDataPgto == 'Invalid Date') {
				alert('Preencha o mês e ano de pagamento corretamente.');
				$('#DataPgto').focus();
				return false;
			}
		}
		
		// Define o tipo de validação.
		switch($('#tipoPagto').val()) {
				
			case 'ferias':
				
				if($('#dataPagtoFeiras').val().length == 0){
					alert('Preencha a data de pagamento.');
					$(this).focus();
					return false;
				}

				// Define Parametros para validação da data.
				var dataPgtoFomat = dataCorreta($('#dataPagtoFeiras').val());
				var checkDataPgto = new Date(dataPgtoFomat);

				if(checkDataPgto == 'Invalid Date') {
					alert('Preencha a data de pagamento corretamente.');
					$('#dataPagtoFeiras').focus();
					return false;
				}
				
				if($('#diasferias').val().length == 0){
					alert('Informe o perído de férias.');
					$(this).focus();
					return false;
				}
				
				// Verifica se 1/3 das ferias sera vendida pelo funcionario.
				if($('#vFeriasSim').is(':checked')) {
					
					// Verifica se os campos da data do abono não estão em branco.
					if($('#dataFeriasAbonoInicio').val().length <= 0 || $('#dataFeriasAbonoFim').val().length <= 0) {
						alert('Preencha o período do abono corretamente.');
						$('#dataFeriasAbonoInicio').focus();
						return false;					
					}
				}
				break;
			case'decimoTerceiro':
					
				if($('#decimoTerceiroParcela').val().length == 0){
					alert('Selecione uma forma de pagamento do décimo.');
					$(this).focus();
					return false;
				}

				if($('#decimoMesesTrabalhado').val().length == 0){
					alert('Informe os meses trabalhado.');
					$(this).focus();
					return false;
				}
				
				break;
		}		
		
		// Variaveis que recebem os dados referente o pagamento.
		var empresaId = $('#funcionarioId').val();
		var funcionarioId = $('#funcionarioId').val();
		var dataPagamento = $('#DataPgto').val(); 
		var proventos = 0;
		
		var arrayElemento = [];
		var statusProventos = true;
		
		// Verifica se outros proventos esta repetido.	
		$('.filtroValor').each(function(){
			if($(this).val().length > 0) {
				if (arrayElemento.indexOf( $(this).val() ) !== -1 ) {
					$(this).focus();
					arrayElemento = [];
					statusProventos = false;
					return false;
				}
				arrayElemento.push($(this).val());
			}
		});
		
		// Verifica se houve erro em outros proventos esta repetido.	
		if(!statusProventos) {
			alert('Outros proventos esta duplicado');
			return false;
		}
		
		// Verifica se o calculo ja foi feito.
		if(!calculoOk) {
			alert('Realize o calculo da retenções');
			return false;
		}
		
		return true;
	}
	
	// Método criado para realizar o calculo.
	function calculaRetencoes(){
		
		var valorDescontoVT = 0;
		var valorDescontoVR = 0;
		var valorAdicionais = 0;
		var valorAddAux = 0;

		if($('#tipoPagto').val().length == 0){
			alert('Selecione o tipo de pagamento');
			$(this).focus();
			return false;
		}
			
		if($('#funcionarioId').val().length == 0){
			alert('Selecione um fucionário');
			$(this).focus();
			return false;
		}

		if($('#tipoPagto').val() != 'ferias') {
			
			if($('#DataPgto').val().length == 0){
				alert('Preencha a data de pagamento.');
				$(this).focus();
				return false;
			}
			
			// Define Parametros para validação da data.
			var dataPgtoFomat = dataCorreta($('#DataPgto').val());
			var checkDataPgto = new Date(dataPgtoFomat);

			if(checkDataPgto == 'Invalid Date') {
				alert('Preencha a data de pagamento corretamente.');
				$('#dataPagtoFeiras').focus();
				return false;
			}		
			
			if($('#dataReferencia').val().length == 0) {
				alert('Preencha o mês e ano de pagamento.');
				$(this).focus();
				return false;
			}
			
			// Define Parametros para validação da data.
			var dataPagtoArray = ($('#dataReferencia').val().split("/")); 
			var dataPgtoFomat = dataPagtoArray[0]+'/01/'+dataPagtoArray[1];
			var checkDataPgto = new Date(dataPgtoFomat);

			if(checkDataPgto == 'Invalid Date') {
				alert('Preencha o mês e ano de pagamento corretamente.');
				$('#DataPgto').focus();
				return false;
			}
			
		}

		// Define o tipo de validação.
		switch($('#tipoPagto').val()) {

			case 'ferias':
				
				if($('#dataPagtoFeiras').val().length == 0){
					alert('Preencha a data de pagamento.');
					$(this).focus();
					return false;
				}

				// Define Parametros para validação da data.
				var dataPgtoFomat = dataCorreta($('#dataPagtoFeiras').val());
				var checkDataPgto = new Date(dataPgtoFomat);

				if(checkDataPgto == 'Invalid Date') {
					alert('Preencha a data de pagamento corretamente.');
					$('#dataPagtoFeiras').focus();
					return false;
				}
				
				if($('#diasferias').val().length == 0){
					alert('Informe o perído de férias.');
					$(this).focus();
					return false;
				}
				
				// Verifica se 1/3 das ferias sera vendida pelo funcionario.
				if($('#vFeriasSim').is(':checked')) {
					
					// Verifica se os campos da data do abono não estão em branco.
					if($('#dataFeriasAbonoInicio').val().length <= 0 || $('#dataFeriasAbonoFim').val().length <= 0) {
						alert('Preencha o período do abono corretamente.');
						$('#dataFeriasAbonoInicio').focus();
						return false;					
					}
				}
				
				break;
			case'decimoTerceiro':
					
				if($('#decimoTerceiroParcela').val().length == 0){
					alert('Selecione uma forma de pagamento do décimo.');
					$(this).focus();
					return false;
				}

				if($('#decimoMesesTrabalhado').val().length == 0){
					alert('Informe os meses trabalhado.');
					$(this).focus();
					return false;
				}
				break;
		}
	
		// Variaveis que recebem os dados referente o pagamento.
		var empresaId = $('#funcionarioId').val();
		var funcionarioId = $('#funcionarioId').val();
		var dataPagamento = $('#DataPgto').val();
		var faltas = $('#faltas').val();
		var tipoPagto = $('#tipoPagto').val();
		var diasFerias = $('#diasferias').val();
		var HorasExtrasAno = $('#HorasExtrasAno').val();
		var decimo = $('#decimoTerceiroParcela').val();
		var decimoMesesTrabalhado = $('#decimoMesesTrabalhado').val();
		var abonoPecuniario = $('#abonoPecuniario').val();
		var dataPagtoFeiras = $('#dataPagtoFeiras').val();
		var dataFeriasInicio = $('#dataFeriasInicio').val();
		var	dataFeriasFim = $('#dataFeriasFim').val();
		var dataAbonoInicio = $('#dataFeriasAbonoInicio').val(); 
	   	var dataAbonoFim = $('#dataFeriasAbonoFim').val();
		var dataReferencia = $('#dataReferencia').val();
	
		var proventos = 0;
		
		var arrayElemento = [];
		var statusProventos = true;
		
		var vendaUmtercoFerias = 'N';
		if($('#vFeriasSim').is(':checked')){
			vendaUmtercoFerias = 'S';
		}
				
		// Verifica se outros proventos esta repetido.	
		$('.filtroValor').each(function(){
			if($(this).val().length > 0) {
				if (arrayElemento.indexOf( $(this).val() ) !== -1 ) {
					$(this).focus();
					arrayElemento = [];
					statusProventos = false;
					return false;
				}
				arrayElemento.push($(this).val());
			}
		});
		
		// Verifica se houve erro em outros proventos esta repetido.	
		if(!statusProventos) {
			alert('Outros proventos esta duplicado');
			return false;
		}
				
		var proventos = {}
		
		// Pega valores adicionais e acrecenta no salario salario bruto.
		$('.valorOpc').each(function(){
			
			if($(this).val() != 0) {
				
				valorAddAux = $(this).val().replace('.','');
				valorAddAux = valorAddAux.replace(',', '.');
				
				var index = $('#filtroValor'+$(this).attr('data-itemid')).val();
				
				//data-itemid
				proventos[index] = parseFloat(valorAddAux);
			}
		});
		
		// Transforma os valores dos proventos em um json.
		proventos = JSON.stringify(proventos);

		console.log(proventos);
		
		// Faz uma requisição em ajax para fazer os calculos.
		$.ajax({
			url:'pagamento_funcionario_ajax.php',
			data: {method:'RealizaCalculoPagamento', funcionarioId:funcionarioId, decimo:decimo, decimoMesesTrabalhado:decimoMesesTrabalhado, tipoPagto:tipoPagto, dataPagamento:dataPagamento, dataPagtoFeiras:dataPagtoFeiras, proventos:proventos, faltas:faltas, diasFerias:diasFerias, vendaUmtercoFerias:vendaUmtercoFerias, HorasExtrasAno:HorasExtrasAno, dataFeriasInicio:dataFeriasInicio, dataFeriasFim:dataFeriasFim, dataAbonoInicio:dataAbonoInicio, dataAbonoFim:dataAbonoFim, dataReferencia:dataReferencia}, 
			type: 'post',
			async: false,
			cache:false,
			dataType: 'text',
			beforeSend: function() {
				$('#btnCalculaRetencoes').hide();
				$('#divCarregando').show();
			},
			success: function(data) {
				
				var json = $.parseJSON(data);

				console.log(json); 
				
				switch($('#tipoPagto').val()) {
					case 'salario':
										
						// Inclui os dados nos input hidder.
						$('#porcInss').val(json['porcentagemInss']);
						$('#retencao_INSS').val(json['valorInss']);
						$('#porcIR').val(json['aliquotaIR']);
						$('#retencao_IR').val(json['valorIR']);
						$('#porcVR').val(json['valeRefeicaoPorc']);
						$('#retencao_VR').val(json['valeRefeicao']);
						$('#porcVT').val(json['valeTransportePorc']);
						$('#retencao_VT').val(json['valeTransporte']);
						$('#faixaIR').val(json['faixaIR']);
						$('#descontoDep').val(json['descontoDep']);
						$('#valorPensao').val(json['valorPensao']);
						$('#porcPensao').val(json['porcPensao']);
						$('#retencao_Faltas').val(json['valorFaltas']);				
						$('#valor_Salario').val(json['salario']);
						$('#salario_Funcionario').val(json['salarioFuncionario']);
						$('#valor_Liquido').val(json['valorLiquido']);
						$('#diasTrabalhado').val(json['diasTrabalhado']);
						$('#numDependentes').val(json['numDependentes']);
						$('#valorFerias').val(json['valorFerias']);
						$('#valorUmTercoFerias').val(json['valorUmTercoFerias']);						
						$('#vendaUmTercoFerias').val(json['vendaUmTercoFerias']);
						$('#valorFeriasVendida').val(json['valorFeriasVendida']);
						$('#valorUmTercoFeriasVendida').val(json['valorUmTercoFeriasVendida']);
						$('#porcIRFerias').val(json['aliquotaIRFerias']);
						$('#valorIRFerias').val(json['valorIRFerias']);						
						$('#salarioBruto').val(json['salarioBruto']);
						$('#diasDeFerias').val(json['diasFerias']);
						$('#feriasId').val(json['feriasId']);
						$('#liquidoFerias').val(json['liquidoFerias']);
						
						// Inclui os dados para visualização.
						$('#dataAdmissao').val(json['dataAdmissao']);
						$('#RetencaoINSS').val(json['valorInss']);
						$('#RetencaoIR').val(json['valorIR']);
						$('#RetencaoVR').val(json['valeRefeicao']);
						$('#RetencaoFaltas').val(json['valorFaltas']);
						$('#RetencaoVT').val(json['valeTransporte']);
						$('#totalVencimentos').val(json['totalVencimentos']);
						$('#ValorLiquido').val(json['valorLiquido']);
	
						break;
					case 'ferias':
						
						// Inclui os dados nos input hidder.
						$('#porcInss').val(json['porcentagemInss']);
						$('#retencao_INSS').val(json['valorInss']);
						$('#porcIR').val(json['aliquotaIR']);
						$('#retencao_IR').val(json['valorIR']);
						$('#porcVR').val(json['valeRefeicaoPorc']);
						$('#retencao_VR').val(json['valeRefeicao']);
						$('#porcVT').val(json['valeTransportePorc']);
						$('#retencao_VT').val(json['valeTransporte']);
						$('#faixaIR').val(json['faixaIR']);
						$('#descontoDep').val(json['descontoDep']);
						$('#valorPensao').val(json['valorPensao']);
						$('#porcPensao').val(json['porcPensao']);
						$('#retencao_Faltas').val(json['valorFaltas']);				
						$('#valor_Salario').val(json['salario']);
						$('#salario_Funcionario').val(json['salarioFuncionario']);
						$('#valor_Liquido').val(json['valorLiquido']);
						$('#diasTrabalhado').val(json['diasTrabalhado']);
						$('#valorFeriasMes1').val(json['valorFeriasMes1']);
						$('#valorFeriasMes2').val(json['valorFeriasMes2']);						
						$('#valorFerias').val(json['valorFerias']);
						$('#valorUmTercoFerias').val(json['valorUmTercoFerias']);
						$('#valorAbonoPecuniario').val(json['valorFeriasVendida']);
						$('#abonoPecuniarioUmTerco').val(json['valorUmTercoFeriasVendida']);
						$('#totalValoresFeriasAbono').val(json['totalValoresFeriasAbono']);
						$('#numDependentes').val(json['numDependentes']);
						$('#diasDeFerias').val(json['diasFerias']);
						$('#porcInssSecundario').val(json['porcSecundarioInss']);
						$('#retencaoSecundarioINSS').val(json['valorSecundarioINSS']);
												
						// Inclui os dados para visualização.
						$('#dataAdmissao').val(json['dataAdmissao']);
						$('#RetencaoIR').val(json['valorIR']);
						$('#RetencaoVR').val(json['valeRefeicao']);
						$('#RetencaoFaltas').val(json['valorFaltas']);
						$('#RetencaoVT').val(json['valeTransporte']);
						$('#totalVencimentos').val(json['totalVencimentos']);
						$('#ValorLiquido').val(json['valorLiquido']);
						
						var valINSS = json['valorSecundarioINSS'].replace(".", "");
						valINSS = valINSS.replace(",", ".");
						
						if(parseInt(valINSS) > 0) {						
							$('#RetencaoINSS').val(json['valorSecundarioINSS']);
						} else {
							$('#RetencaoINSS').val(json['valorInss']);
						}
						
						break;
					case'decimoTerceiro':
						
						// Inclui os dados nos input hidder.
						$('#porcInss').val(json['porcentagemInss']);
						$('#retencao_INSS').val(json['valorInss']);
						$('#porcIR').val(json['aliquotaIR']);
						$('#retencao_IR').val(json['valorIR']);
						$('#porcVR').val(json['valeRefeicaoPorc']);
						$('#retencao_VR').val(json['valeRefeicao']);
						$('#porcVT').val(json['valeTransportePorc']);
						$('#retencao_VT').val(json['valeTransporte']);
						$('#faixaIR').val(json['faixaIR']);
						$('#descontoDep').val(json['descontoDep']);
						$('#valorPensao').val(json['valorPensao']);
						$('#porcPensao').val(json['porcPensao']);
						$('#retencao_Faltas').val(json['valorFaltas']);				
						$('#valor_Salario').val(json['salario']);
						$('#salario_Funcionario').val(json['salarioFuncionario']);
						$('#valor_Liquido').val(json['valorLiquido']);
						$('#diasTrabalhado').val(json['diasTrabalhado']);
						$('#numDependentes').val(json['numDependentes']);
												
						// Inclui os dados para visualização.
						$('#dataAdmissao').val(json['dataAdmissao']);
						$('#RetencaoINSS').val(json['valorInss']);
						$('#RetencaoIR').val(json['valorIR']);
						$('#RetencaoVR').val(json['valeRefeicao']);
						$('#RetencaoFaltas').val(json['valorFaltas']);
						$('#RetencaoVT').val(json['valeTransporte']);
						$('#totalVencimentos').val(json['totalVencimentos']);
						$('#ValorLiquido').val(json['valorLiquido']);	
						$('#adiantamento13Salario').val(json['adiantamentoDecimoTerceiro']);
						break;
				}
			},
			error: function(erro) { // if error occured
				alert("Ocorreu um erro, por favor, contate o help Desk \n");
			},
			complete: function() {
				$('#btnCalculaRetencoes').show();
				$('#divCarregando').hide();
				
				// Seta a variável como verdadeira para liberar a gravação dos dados no banco.
				calculoOk = true;
			}
		});
	}
	
	// Define o tipo de pagamento.
	$('#tipoPagto').change(function(){
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;		
		
		switch($(this).val()) {
			case 'salario':
				salario();		
				break;
			case 'ferias':
				ferias();	
				break;
			case'decimoTerceiro':
				decimoTerceiro();	
				break;
		}
	});
	
		// Define o tipo de pagamento.
	$('#decimoTerceiroParcela').change(function(){
	
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;		
		
		switch($(this).val()) {
			case '2':
				$('#campoDescontos13Salario').show();		
				break;
			default:
				$('#campoDescontos13Salario').hide();
				break;
		}
	});
	
	function salario() {
		$('#DataPgto').val('');
		$('#dataPagtoFeiras').val('');
		$('#dataReferencia').val('');
		$('#campoDecimoTerceiroParcela').hide();
		$('#outrosProventos').show();
		$('#campoDescontos').show();
		$('#campoSalario').show();
		$('#campoDiasferias').hide();
		$('#campoDiasTrabalhadoFaltas').show();
		$('#campoDecimoMesesTrabalhado').hide();
		$('#campoDescontos13Salario').hide();
		$('#campoDescontos13Salario').val('');
	}
	
	function ferias() {
		$('#DataPgto').val('');
		$('#dataPagtoFeiras').val('');
		$('#dataReferencia').val('');		
		$('#campoDecimoTerceiroParcela').hide();
		$('#campoDescontos').hide();
		$('#outrosProventos').hide();
		$('#campoSalario').hide();
		$('#campoDiasferias').show();
		$('#campoDiasTrabalhadoFaltas').hide();
		$('#campoDecimoMesesTrabalhado').hide();
		$('#RetencaoVT').val('');
		$('#RetencaoVR').val('');
		$('#RetencaoFaltas').val('');
		$('#campoDescontos13Salario').hide();
		$('#campoDescontos13Salario').val('');
	}
	
	function decimoTerceiro() {
		$('#DataPgto').val('');
		$('#dataPagtoFeiras').val('');
		$('#dataReferencia').val('');		
		$('#campoDecimoTerceiroParcela').show();
		$('#campoDescontos').hide();
		$('#outrosProventos').hide();
		$('#campoDiasferias').hide();
		$('#campoSalario').show();
		$('#campoDiasTrabalhadoFaltas').hide();
		$('#campoDecimoMesesTrabalhado').show();
		$('#RetencaoVT').val('');
		$('#RetencaoVR').val('');
		$('#RetencaoFaltas').val('');
		$('#campoDescontos13Salario').hide();
		$('#campoDescontos13Salario').val('');
		
		if($('#decimoTerceiroParcela').val() == 2){
			$('#campoDescontos13Salario').show(); 
		}
	}
	
	// Verifica sera possivel realizar a venda das ferias.
	$('input:radio[name="vendaFerias"]').change(function(){
				
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;		
		
		if($(this).val() == 'S' && $('#diasferias').val() > 20){
			alert('Para cálculos de férias com a venda de 1/3, o máximo dias a serem gozados é de 20 dias');
			$('#vFeriasNao').attr('checked',true);
		}
	});
	
	// verifica se o numero de ferias é valido.
	$('#faltas').change(function(){
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;
		
		if($(this).val() > 31 || $(this).val() < 0) {
			alert('Numero de faltas não é valido.');	
			$(this).val('');
		}
	});
	
	// Verifica a quantidade de meses trabalhado é valido.
	$('#decimoMesesTrabalhado').change(function(){
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;
		
		if($(this).val() > 12 || $(this).val() < 0) {
			alert('Numero de meses trabalhado não é valido.');	
			$(this).val('');
		}
	});
	
	// Verifica se o período de férias e valido
	$('#dataFeriasInicio').change(function(){

		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;
		
		$('#diasferias').val('');
		
		// define a variável para realizar a verificação
		var checkData = new Date(dataCorreta($(this).val()));
		
		// Realiza a verificação da data
		if(checkData == 'Invalid Date') { 
			alert('Data informada não e valida.');
			$(this).val('');
		} else {
			if($('#dataFeriasFim').val().length > 0) {
				var data1 = new Date(dataCorreta($(this).val()));
				var data2 = new Date(dataCorreta($('#dataFeriasFim').val()));
				if(data2.getTime() >= data1.getTime()) {

					//Diferença em milésimos e positivo
					var diferenca = Math.abs(data1 - data2); 
					// Milésimos de segundo correspondente a um dia
					var dia = 1000*60*60*24; 					
					//valor total de dias arredondado 	
					var diffDays = Math.round(diferenca/dia) + 1;				
					
					if(diffDays <= 30) {
						$('#diasferias').val(diffDays);
					} else {
						alert('período de férias não é valido');
						$(this).val('');
					}
				} else {
					alert('A data inicial do período de férias não pode ser maior que a final');
				}
			}
		}
		
		// verifica os campos comrelação ao salário.
		if($('#vFeriasSim').is(':checked') && $('#diasferias').val() > 20) {
			alert('Para cálculos de férias com a venda de 1/3, o máximo dias a serem gozados é de 20 dias');
			$(this).val('');
			$('#diasferias').val('');
		}
		
	});

	// Verifica se o período de férias e valido
	$('#dataFeriasFim').change(function(){
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;
		
		$('#diasferias').val('');
		
		// define a variável para realizar a verificação
		var checkData = new Date(dataCorreta($(this).val()));
		
		// Realiza a verificação da data
		if(checkData == 'Invalid Date') { 
			alert('Data informada não e valida.');
			$(this).val('');
		} else {
			if($('#dataFeriasInicio').val().length > 0) {

				var data1 = new Date(dataCorreta($('#dataFeriasInicio').val()));
				var data2 = new Date(dataCorreta($(this).val()));
				if(data2.getTime() >= data1.getTime()) {
					
					//Diferença em milésimos e positivo
					var diferenca = Math.abs(data1 - data2); 
					// Milésimos de segundo correspondente a um dia
					var dia = 1000*60*60*24; 					
					//valor total de dias arredondado 	
					var diffDays = Math.round(diferenca/dia) + 1;				
					
					if(diffDays <= 30) {
						$('#diasferias').val(diffDays);
					} else {
						alert('período de férias não é valido');
						$(this).val('');
					}
				} else {
					alert('A data inicial do período de férias não pode ser maior que a final');
				}
			}
		}
		
		// verifica os campos com relação ao salário.
		if($('#vFeriasSim').is(':checked') && $('#diasferias').val() > 20) {
			alert('Para cálculos de férias com a venda de 1/3, o máximo dias a serem gozados é de 20 dias');
			$(this).val('');
			$('#diasferias').val('');
		}
	});	
	
	// Verificação pera o periodo de abono de ferias.
	$('#dataFeriasAbonoInicio').change(function(){
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;

		// define a variável para realizar a verificação
		var checkData = new Date(dataCorreta($(this).val()));
		
		// Realiza a verificação da data
		if(checkData == 'Invalid Date') { 
			alert('Data informada não e valida.');
			$(this).val('');
		} else {
			if($('#dataFeriasAbonoFim').val().length > 0) {
				var data1 = new Date(dataCorreta($(this).val()));
				var data2 = new Date(dataCorreta($('#dataFeriasAbonoFim').val()));
				if(data2.getTime() >= data1.getTime()) {

					//Diferença em milésimos e positivo
					var diferenca = Math.abs(data1 - data2); 
					// Milésimos de segundo correspondente a um dia
					var dia = 1000*60*60*24; 					
					//valor total de dias arredondado 	
					var diffDays = Math.round(diferenca/dia) + 1;				
					
					if(diffDays != 10) {
						alert('período do abono férias não é valido');
						$(this).val('');
					}
				} else {
					alert('A data inicial do período do abono não pode ser maior que a final');
				}
			}
		}
	});	
	
	// Verifica se o período do abono e valido
	$('#dataFeriasAbonoFim').change(function(){
		
		// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
		calculoOk = false;
		
		// define a variável para realizar a verificação
		var checkData = new Date(dataCorreta($(this).val()));
		
		// Realiza a verificação da data
		if(checkData == 'Invalid Date') { 
			alert('Data informada não e valida.');
			$(this).val('');
		} else {
			if($('#dataFeriasAbonoInicio').val().length > 0) {

				var data1 = new Date(dataCorreta($('#dataFeriasAbonoInicio').val()));
				var data2 = new Date(dataCorreta($(this).val()));
				if(data2.getTime() >= data1.getTime()) {
					
					//Diferença em milésimos e positivo
					var diferenca = Math.abs(data1 - data2); 
					// Milésimos de segundo correspondente a um dia
					var dia = 1000*60*60*24; 					
					//valor total de dias arredondado 	
					var diffDays = Math.round(diferenca/dia) + 1;				
					
					console.log(diffDays);
					
					if(diffDays != 10) {
						alert('período do abono férias não é valido');
						$(this).val('');
					}
					
				} else {
					alert('A data inicial do período do abono não pode ser maior que a final');
				}
			}
		}
	});	
	
	// Retorna uma data valida para realiza o calculo de dias.
	function dataCorreta(data){
		var dataArray = data.split('/');
		return dataArray[1]+'/'+dataArray[0]+'/'+dataArray[2];
	}
	
});	
	
// Remove o item do valor adicional
function removeItemVlOp(num) {

	var removeCampo = '#campVl_'+num;
	var numAtual = $('#ValorOpcional').attr('data-quantidade');
	var numNew = numAtual - 1; 
	
	// Seta a variavel para mostra que teve alteração e que devera ser realizado o recalculo.
	calculoOk = false;
	
	//controleValoresAdd = controleValoresAdd - 1;
	$('#ValorOpcional').attr('data-quantidade', numNew);
	$(removeCampo).remove();
}
	
function checkDataDemissao(position){

	var method = 'checkDataAdmissaoDemissao';
	var funcionarioId = $('#funcionarioId').val(); 
	var data = $('#dataReferencia').val();
	var status = true;
	var statusRet = '';
	var statusFerias = '';
	
	if($('#tipoPagto').val() == 'ferias') { 
		data = $('#dataPagtoFeiras').val();
		statusFerias = 'sim';
	}
	
	$.ajax({
		url:'meus_dados_funcionario_ajax.php',
		type: 'post',
		data: {method:method, funcionarioId:funcionarioId, data: data, statusFerias:statusFerias},
		dataType: 'json',
		success: function(ret) {
			
			console.log(ret);
			
			if(ret['status'] == 'admissao') {
				status = false;
				statusRet = ret['status'];
			}
			else if(ret['status'] == 'demissao') {
				status = false;
				statusRet = ret['status'];
			}
		},
		error: function(xhr) {
			console.log('Erro retorno');
		},
		beforeSend: function() {},					
		complete: function() {
			
			console.log(status);
			
			if(status){
				// define a posição do balão
				$('#aviso-livro-caixa').css('top', position + 'px').fadeIn(100);
			} else if( statusRet == 'admissao' ){
				alert('Não é possível fazer o lançamento, pois a data da competência e menor que a data de admissão.');
			} else if( statusRet == 'demissao' ){
				alert('Não é possível fazer o lançamento, pois a data da competência e maior que a data de demissão.');
			}
			
		}
	});
}	

// Confirma a gração dos dados do pagamento do funcionario no livro caixa.
$('#btSIMLivroCaixa').click(function(){
	$('#GravarLivroCaixa').val('Sim');
	$('#formGeraHolerite').submit();	
});	

// Não realiza a gração dos dados do pagamento do funcionario no livro caixa.	
$('#btNAOLivroCaixa').click(function(){
	$('#aviso-livro-caixa').fadeOut(100);
	$('#formGeraHolerite').submit();
});	
	
</script>	

<?php include 'rodape.php' ?>
