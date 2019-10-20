<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	session_start();

	if(isset($_POST['pesquisaAjax'])) {
		
		require_once('conect.php');
		
		require_once('Controller/contas_a_pagar-controller.php');
		
		$contasAPagar = new ContasAPagarController();
		
		$retorno = false;
		
		$idEmpresa = $_POST['idEmpresa'];
		$idLinha = $_POST['idLinha'];
		
		$resultado = $contasAPagar->verificaIdLancamentoExistente($idEmpresa, $idLinha);
				
		if($resultado){
			$retorno = true;
		}		
				
		echo json_encode(array('status'=>$retorno, 'teste'=>$idLinha));
		
		// Mata a execução do codigo do ajax
		die();
	}


	if(isset($_POST['data'])) {
		
		require_once('conect.php');		
		
		// Inclui arquivo responsavel pelas ações da página.
		require_once('Controller/contas_a_pagar-controller.php');

		// Instancia o controle da página.
		$contasAPagar = new ContasAPagarController();
				
		// Prepara os dados para a inclusão.
		$data = (isset($_POST['data']) ? date('Y-m-d', strtotime(str_replace("/", "-", $_POST['data']))) : ''); 
		$valor = (isset($_POST['valor']) ? str_replace(",", ".", str_replace(".", "", $_POST['valor'])) : '');
		$vencimento = (isset($_POST['vencimento']) ? date('Y-m-d', strtotime(str_replace('/', '-', $_POST['vencimento']))) : '');
		$docNum = (isset($_POST['docNum']) ? $_POST['docNum'] : ''); 
		$descricao = (isset($_POST['descricao']) ? $_POST['descricao'] : ''); 
		$empresaId = (isset($_POST['empresaId']) ? $_POST['empresaId'] : '');		
		$categoria = (isset($_POST['categoria']) ? $_POST['categoria'] : '');
				
		// Chama o metodo que realiza ainclusão dos dados.
		$contasAPagar->IncluiContasAPagar($empresaId, $data, $valor, $docNum, $descricao, $categoria, $vencimento);		
		
		// Para a execução do arquivo.
		die();
	}

	if(isset($_POST['idLinha'])){
		
		require_once('conect.php');		
		
		// Inclui arquivo responsavel pelas ações da página.
		require_once('Controller/contas_a_pagar-controller.php');
		
		$contasAPagar = new ContasAPagarController();
				
		// Método para excluir conta a pagar.
		$contasAPagar->excluiDados($_POST['idLinha'], $_POST["idEmpresa"]);
		
	}

	// Inclui cabeçalho.
	require_once('header_restrita.php'); 
	
	// Inclui arquivo responsavel pelas ações da página.
	require_once('Controller/contas_a_pagar-controller.php');	

	$empresaId = $_SESSION["id_empresaSecao"];

	// Instancia o controle da página.
	$contasAPagar = new ContasAPagarController($empresaId);
?>
<style>
	
	/*.download-arquivo{display:inline-block; margin: 0 1px;}*/
	
	.download-arquivo{
		float: right;
		margin-left: 1px;
		position: relative;
	}	
	
	.mouse_over_nome_arquivo{
		position: absolute;
		display: none;
		background: #FFFF99;
		padding: 10px;
		margin-top: 14px;
		right: -20px;
		min-width: 150px;
		z-index: 99999;

	}
	.mouse_over_nome_arquivo:before{
		content: '';
		position: absolute;
		border-style: solid;
		border-width: 0 15px 15px;
		border-color: #FFFF99 transparent;
		display: block;
		width: 0;
		z-index: 0;
		top: -12px;;
		right: 13px;
	}
</style>
<script>
	$(function() {
		// Informações - Hover
		$(".download-arquivo").hover(function(){
			$(this).find(".mouse_over_nome_arquivo").fadeIn(0);
		}, function(){
			$(this).find(".mouse_over_nome_arquivo").fadeOut(0);
		});
	});	
</script>
<div class="principal">
	
	<h1 class="titulo">Escrituração</h1>
	
	<h2>Contas a pagar</h2>
		
	<div style="margin-bottom:20px">
		 Caros amigos, a execução dos pontos do programa promove a alavancagem do retorno esperado a longo prazo. Podemos já vislumbrar o modo pelo qual a determinação clara de objetivos faz parte de um processo de gerenciamento de alternativas às soluções ortodoxas. Assim mesmo, a necessidade de renovação processual é uma das consequências das posturas dos órgãos dirigentes com relação às suas atribuições. <br><br>
		
		 Por conseguinte, a estrutura atual da organização possibilita uma melhor visão global das diretrizes de desenvolvimento para o futuro. Neste sentido, a crescente influência da mídia causa impacto indireto na reavaliação da gestão inovadora da qual fazemos parte. Pensando mais a longo prazo, o desenvolvimento contínuo de distintas formas de atuação prepara-nos para enfrentar situações atípicas decorrentes das direções preferenciais no sentido do progresso. 
	</div>
	
	<form id="form_contas" method="post" action="contas_a_pagar.php" enctype="multipart/form-data">
		
		<div style="clear:both; margin-bottom: 10px;">
			<label>Categoria:</label>
			
			<select name="categoria" id="categoria">
				<option value="">Selecione</option>
				<option value="Aluguel">Aluguel</option>
				<option value="Aluguel de software">Aluguel de software</option>
				<option value="Água">Água</option>
				<option value="Combustível">Combustível</option>
				<option value="Comissões">Comissões</option>
				<option value="Condomínio">Condomínio</option>
				<option value="Contador">Contador</option>
				<option value="Cursos e treinamentos">Cursos e treinamentos</option>
				<option value="Energia elétrica">Energia elétrica</option>
				<option value="Estagiários">Estagiários</option>
				<option value="Impostos e encargos">Impostos e encargos</option>
				<option value="Internet">Internet</option>
				<option value="Marketing e publicidade">Marketing e publicidade</option>
				<option value="Material de escritório">Material de escritório</option>
				<option value="Pagto. de Salários">Pagto. de Salários</option>
				<option value="Pgto. a autônomos e fornecedores">Pgto. a autônomos e fornecedores</option>
				<option value="Pró-Labore">Pró-Labore</option>
				<option value="Segurança">Segurança</option>
				<option value="Seguros">Seguros</option>
				<option value="Telefone">Telefone</option>
				<option value="Transportadora / Motoboy">Transportadora / Motoboy</option>
			</select>
			
		</div>
	
		<div style="clear:both; margin-bottom: 10px;">
			<label>Data do documento:</label>
			<input type="hidden" name="empresaId" value="<?=$empresaId;?>">
			<input type="text" name="data" id="data" value="" class="campoData" style="width: 80px;" />
		</div>
		
		<div style="clear:both; margin-bottom: 10px;">
			<label>Valor:</label>
			<input type="text" name="valor" id="valor" value="" class="current" style="width: 100px;" />
		</div>

		<div style="clear:both; margin-bottom: 10px;">
			<label>Vencimento:</label>
			<input type="text" name="vencimento" id="vencimento" value="" class="campoData" style="width: 80px;" />
		</div>
						
		<div style="clear:both; margin-bottom: 10px;">
			<label>Numero do documento:</label>
			<input type="text" name="docNum" id="docNum"  value="" maxlength="256" style="width: 100px;" />
		</div>

		<div style="clear:both; margin-bottom: 10px;">
			<label> Descrição: </label>
			<input type="text"  name="descricao" id="descricao" value="" maxlength="70" style="width:350px;" />
		</div>
		
		<div style="clear:both; height:20px">
			Anexar Comprovante(s): <input type="file" name="anexos_doc[]" value="" multiple style="margin-left:10px;margin-right:10px;">
		</div>
		
		<div style="clear:both; height:20px"></div>
		
		<div id="caixa-botoes">
			<input id="bt_enviar" type="button" value="Incluir lançamento">
		</div>
		
	</form>
	
	<div style="clear:both; height:20px"></div>
	
	<h2>Lançamento</h2>
	
	<?php
	
		// Inclui o filtro da tabela. estes métodos estão em Controller/contas_a_receber-controller.php
		echo $contasAPagar->MontaFiltro();
	
		// Apresenta a tabela. estes métodos estão em Controller/contas_a_receber-controller.php
		echo $contasAPagar->MontaTabela($empresaId);
	?>
	
	
</div>

<!--BALLOM excluir contas a pagar-->
<div class="bubble only-box" style="display: none; padding:0; position:absolute; top: 50%; left:50%; margin-left: -450px; z-index: 9999;" id="aviso-delete-livro-caixa">
  <div style="padding:20px; font-size:12px;">
	  <div id="mensagemExcluirPagamento"></div><br>
	  <div style="clear: both; margin: 0 auto; display: inline;">
	  	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<center>
				<input id="idEmpresa" name="idEmpresa" type="hidden" value="" />
				<input id="idLinha" name="idLinha" type="hidden" value="" />
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
	
	function excluirLinha(idEmpresa, idLinha, idBotao){
		
		$.ajax({
			url: 'contas_a_pagar.php',
			data: {pesquisaAjax:'', idEmpresa:idEmpresa, idLinha:idLinha},
			type: 'post',
			dataType: 'json',
			success: function(dados){
											
				if(dados['status']){
					
				   alert('Esse campo não pode ser apagado pois contém registro de pagamento');
					
				   } else {
					 $('#aviso-delete-livro-caixa').fadeOut(100);		

					var mensagem = "Você tem certeza que deseja excluir este Pagamento?";	

					$('#aviso-delete-livro-caixa').find('#mensagemExcluirPagamento').html(mensagem);		

					$('#idEmpresa').val(idEmpresa);
					$('#idLinha').val(idLinha);

					$('#aviso-delete-livro-caixa').css('top',($('#excluirLinha_'+idBotao).offset().top - 5) + 'px').fadeIn(100);

					$('#btCancelar').click(function(){
						$('#aviso-delete-livro-caixa').fadeOut(100);
					});
				} 
				   
			}
		});		
	}
	
	
	$(function(){		
		
		$('#bt_enviar').click(function(){
			if(ValidarCampos()){
				$('#form_contas').submit();
			}			
		});		
	});
	
	function ValidarCampos(){				
		
		if($('#categoria').val() == ''){
			$('#categoria').focus();
			alert('Selecione a categoria');
			return false;
		}
		
		else if($('#data').val() == ''){
			$('#data').focus();
			alert('Preencha o campo data');
			return false;
		}
		
		else if($('#valor').val() == ''){
			$('#valor').focus();
			alert('Preencha o campo valor');
			return false;
		}
		
		else if($('#docNum').val() == ''){
			$('#docNum').focus();
			alert('Preencha o número do documento');
			return false;
		}
		
		else if($('#descricao').val() == ''){
			$('#descricao').focus();
			alert('Preencha o campo descrição');
			return false;
			
		}
		
		// Retorna true caso não haja erro
		return true;
		
	}

</script>



<?php include 'rodape.php' ?>