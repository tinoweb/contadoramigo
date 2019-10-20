<?php include 'header_restrita.php'; ?>

<?php
session_start(); 
$_SESSION["folha_pagamento"] = "sim";

// função que checa o preenchimento correto de campos
function checaCampo ($tipoComparacao, $campo, $tabela, $nomeCampo, $numSocio) {
	global $camposNecessarios;
	if((($tipoComparacao == 1) and ($campo == '')) or (($tipoComparacao == 2) and ($campo == 0))) {
		$camposNecessarios = false;
		if($tabela == 1) {
			global $campoEmpresa;
			$campoEmpresa .= '<img src="images/avancar_azul.png" /> ' . $nomeCampo . '<br>';
		}
		if($tabela == 2) {
			global $campoSocio;
			$campoSocio[$numSocio] .= '<img src="images/avancar_azul.png" /> ' . $nomeCampo . '<br>';
		}
	}
}

$month = array (1=>"Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro", "Gfip 13");
$valor = array (1=>"01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13");


$camposNecessarios = true;


?>


<script>

$(document).ready(function() {
	
	var p_vez = '<?php echo $_SESSION['p_vez'];?>';
	
    $('#btnGerarFolha').click(function(){
		if($('#indicador_previdencia').val() == "2"){

			if($('input[name="data_atraso"]').val() == ""){
				
				alert('Preencha a data do recolhimento em atraso.');
				return false;

			}else{
		
				var dia_campo = parseInt($('input[name="data_atraso"]').val().substr(0,2));
				var mes_campo = parseInt($('input[name="data_atraso"]').val().substr(3,2));
				var ano_campo = parseInt($('input[name="data_atraso"]').val().substr(6,4));
		
				var data = new Date();
				var dia_corrente = data.getDate();
				var mes_corrente = data.getMonth()+1;
				var ano_corrente = data.getFullYear();
				
				var mensagem = "";
				
//				if(ano_campo != ano_corrente){
//					mensagem = "O ano deve ser o corrente!";
//				}else{		
					if(ano_campo < ano_corrente){
						mensagem = "A data em que será efetuado o recolhimento não pode ser anterior ao dia de hoje!";
					}else{
						if(mes_campo < mes_corrente){
							mensagem = "A data em que será efetuado o recolhimento não pode ser anterior ao dia de hoje!";
						}else{
							if(mes_campo > mes_corrente){
								mensagem = "A data em que será efetuado o recolhimento não pode ser de um mês diferente do atual!";
							}else{
								if(dia_campo < dia_corrente){
									mensagem = "A data em que será efetuado o recolhimento não pode ser anterior ao dia de hoje!";
								}
							}
						}
					}
//				}
			
				if(mensagem != ""){
					alert(mensagem);
					$('input[name="data_atraso"]').focus();
					return false;
				}	
			}
		}
		
		// ajax valida funcionario.
		$.ajax({
			url:'sefip_folha_checa_movimento.php?id=<?=$_SESSION["id_empresaSecao"]?>&mes=' + $('#mes').val() + '&ano=' + $('#ano').val(),
			type: 'get',
			async: false,
			cache: false,
			dataType: 'json',
			success: function(ret) {
				
				console.log(ret);

				$("body").css("cursor", "auto");
				if(ret['status'] == 'erro'){
					$('.boxMSG').css('display','none');
					$('#alertaMovimento2').css('display','block');
					
					var nomes = ret['nomesFuncionario'].split(',');
	
					var tagLi = '';
					
					nomes.forEach(function(valor, chave){
						tagLi = tagLi + '<li>'+valor+'</li>';
					});
										
					$( "#funcionarioSemLancamento1" ).html(tagLi);
					
					return false;
				}
				if(ret['status'] == 'ok' || ret['status'] == 'ok1'){
					$('.boxMSG').css('display','none');
					formSubmit();
				}
				if(ret['status'] == 'erroProLabore'){
					$('.boxMSG').css('display','none');
					if(confirm(ret['msg'])){
						formSubmit();
					}else{
						return false;
					}
				}
				if(ret['status'] == 'erroSemFuncionario13'){
					$('.boxMSG').css('display','none');
					if(p_vez == 'sim') {
						location.href="gfip_sem_movimento_primeira_vez.php";
					} else {
						location.href="gfip_sem_movimento.php";
					}					
				}
				if(ret['status'] == 'erroSemPagatoFunc'){
					$('.boxMSG').css('display','none');
					$('#alertaMovimento3').css('display','block');
					
					var nomes = ret['nomesFuncionario'].split(',');
	
					var tagLi = '';
					
					nomes.forEach(function(valor, chave){
						tagLi = tagLi + '<li>'+valor+'</li>';
					});
										
					$( "#funcionarioSemLancamento2" ).html(tagLi);
				}				
				if(ret['status'] == 'erroSemProLaboreSocio'){
					$('.boxMSG').css('display','none');
					if(confirm(ret['msg'])){
						
						if(p_vez == 'sim') {
							location.href="gfip_sem_movimento_primeira_vez.php";
						} else {
							location.href="gfip_sem_movimento.php";
						}
					}else{
						$('#alertaMovimento').css('display','block');
						return false;
					}
				}
			},
			error: function(xhr) { console.log('Erro retorno');	},
			beforeSend: function() {},					
			complete: function() {}
		});
	});	
	
});

function Obs(qualDiv) {
    if (document.getElementById('1gfip').style.display == "block") {document.getElementById('1gfip').style.display = "none"}
	if (document.getElementById('2gfip').style.display == "block") {document.getElementById('2gfip').style.display = "none"}
	document.getElementById(qualDiv).style.display = "block"
}
	
	
function formSubmit() { 
	
	//se tem atividades concomitantes, checar quais estão clicadas
	if(document.getElementById('hidVerificaAtividade').value == 'sim') {
		var total = 0;
		var anexo4 = "false"
		var outros = "false"
		
		for (i=1;i<=document.getElementById('hidTotalLinhas').value;i++) {
			if(document.getElementById('atividade' + i).checked) {
				
				// se a atividade clicada for do anexo 4 atribui o valor do radio button empreitada para o valor do hidden código de recolhimento
				if(document.getElementById('hidAtividade' + i + 'anexo').value == 'IV') {
							for (var j=0; j<4; j++){
							if (document.frmFolhaPagamento.empreitada[j].checked) {
							rad_val = document.frmFolhaPagamento.empreitada[j].value;
							document.getElementById('hidCod_Recolhimento').value = rad_val}
							}
				
				anexo4 = "true";
					
					}
				// se a atividade clicada for diferente do anexo 4 apenas indica que o outros é verdadeiro e que o campo está preenchido	
				if(document.getElementById('hidAtividade' + i + 'anexo').value != 'IV') {outros = "true";}
				total = total + 1;
			}
		}

		// se o campo nao estiver preenchido avise
		if (total == 0) {
			window.alert('Selecione quais atividades exerceu no período.');
			return false;}
			 
		if (anexo4 == "true" && outros == "false") {document.getElementById('hidSimples').value = '1'} 
		else {document.getElementById('hidSimples').value = '2';}


		if (anexo4 == "true" && outros == "true") {document.getElementById('hidConcomitante').value = 'sim'}
		else {document.getElementById('hidConcomitante').value = 'nao'}
		
	}
	
	//se não tiver atividades concomitantes, mas for construtora atribuir o devido código de recolhimento
	if (document.frmFolhaPagamento.empreitada != null) {
	for (var j=0; j<4; j++){
	if (document.frmFolhaPagamento.empreitada[j].checked) {
	rad_val = document.frmFolhaPagamento.empreitada[j].value;
	document.getElementById('hidCod_Recolhimento').value = rad_val}
	}
	
		}
	
	document.getElementById('frmFolhaPagamento').submit()
}
</script>

<?php

$html = '<div class="principal">
	<div class="minHeight">
		<div class="titulo">Impostos e Obrigações</div>
		<div class="tituloVermelho" style="margin-bottom:20px">Envio da Gfip</div>
		
		<div style="margin-bottom:20px"><strong>Gfip COM movimento:</strong> certifique-se de já ter emitido  os recibos de pagamento dos sócios, funcionários e autônomos. Faça isso nas páginas <a href="pro_labore.php">pró-labore</a>, <a href="pagamento_funcionario.php">funcionários</a> e <a href="pagamento_autonomos.php" >autônomos</a>. Atenção: o pró-labore deve ter o mesmo mês da Gfip. Por exemplo, se você estiver enviando a Gfip de janeiro (transmitida em 7/02), então o pró-labore deve ter sido gerado em janeiro e não fevereiro.<br /><br />
		<strong>Gfip SEM movimento:</strong> se a sua empresa não teve faturamento no período e você não deseja declarar retirada de pró-labore, deve enviar uma Gfip sem movimento. Vale lembrar que este período sem recolhimento não será contado como tempo de contribuição para sua aposentadoria. Para enviar a Gfip sem movimento, não deve haver nenhum pagamento de pró-labore, salários ou remuneração a autônomos  cadastrados para o período.</div>';
 

// SE FOI POSTADO O FORMULARIO DE PERIODO
if($_POST) {

	// fazendo consulta dos códigos da empresa para checar inconsistências
	$sql3 = "SELECT cnae FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_empresaSecao"] . "' AND tipo='1'";
	$resultado3 = mysql_query($sql3) or die (mysql_error());
	$campoEmpresa = '';
		
	//$linha_cnae = mysql_fetch_array($resultado3);
	
	checaCampo (2, mysql_fetch_array($resultado3), 1, 'Código CNAE da atividade Principal', ''); // checando o campo cnae
	
	// criando um loop dos CNAE para a validação do FPAS
	while($linha_cnae = mysql_fetch_array($resultado3)) {
		
		$sql_fpas = "SELECT * FROM cnae_fpas WHERE cnae='" . $linha_cnae['cnae'] . "' LIMIT 0, 1";
		$resultado_fpas = mysql_query($sql_fpas) or die (mysql_error());
		
		checaCampo (2, mysql_num_rows($resultado_fpas), 1, 'Não foi possível localizar seu código FPAS a partir da atividade cadastrada para sua empresa. Por favor, contate o Help Desk do Contador Amigo informando este problema.', ''); // checando o campo fpas
	
	}
		
	// fazendo consulta dos dados da empresa para checar inconsistências
	$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
	$resultado = mysql_query($sql) or die (mysql_error());
	$linha_empresa=mysql_fetch_array($resultado);
	
	checaCampo (1, $linha_empresa['cnpj'], 1, 'CNPJ', '');
	checaCampo (1, $linha_empresa['razao_social'], 1, 'Razão Social', '');

	checaCampo (1, $linha_empresa['endereco'], 1, 'Endereço', '');
	checaCampo (1, $linha_empresa['bairro'], 1, 'Bairro', '');
	checaCampo (1, $linha_empresa['cep'], 1, 'CEP', '');
	checaCampo (1, $linha_empresa['cidade'], 1, 'Cidade', '');
	checaCampo (1, $linha_empresa['estado'], 1, 'Estado', '');
	checaCampo (1, $linha_empresa['pref_telefone'].$linha_empresa['telefone'], 1, 'Telefone', '');

	
	$sql_socio = "
	SELECT resp.nome, resp.data_admissao, resp.data_de_nascimento, resp.codigo_cbo, resp.nit 
	FROM dados_do_responsavel resp 
	INNER JOIN dados_pagamentos pgto ON resp.idSocio = pgto.id_socio
	WHERE resp.id='" . $_SESSION["id_empresaSecao"] . "' 
	AND id_socio > 0 
	AND MONTH(data_pagto) = '".$_REQUEST['mes']."' 
	AND YEAR(data_pagto) = '".$_REQUEST['ano']."'";
		
	$resultado_socio = mysql_query($sql_socio) or die (mysql_error());
	$linhaAtual = 0;
	
	while ($linha_socio=mysql_fetch_array($resultado_socio)) {
		$linhaAtual = $linhaAtual + 1;
		$campoSocio[$linhaAtual] = '';
		
		checaCampo (1, $linha_socio['nome'], 2, 'Nome', $linhaAtual);
		checaCampo (1, $linha_socio['data_admissao'], 2, 'Data de Admissão', $linhaAtual);
		checaCampo (1, $linha_socio['data_de_nascimento'], 2, 'Data de Nascimento', $linhaAtual);
		checaCampo (1, $linha_socio['codigo_cbo'], 2, 'Código CBO', $linhaAtual);
		//checaCampo (2, $linha_socio[pro_labore], 2, 'Pró-labore', $linhaAtual);
		checaCampo (1, $linha_socio['nit'], 2, 'NIT/PIS', $linhaAtual);
	}


	// CASO TENHA DADO ALGUMA INCONSISTENCIA MOSTRA A MENSAGEM DE ERRO
	if ($camposNecessarios == false) {
		
		$html .= "Para emitir a Folha de Pagamento, é necessário preencher os seguintes campos em Meus Dados:";
		
		$html .= "<ul>";

		if ($campoEmpresa != '') { 

			$html .='<li><a href="meus_dados_empresa.php"><strong>Dados da empresa</strong></a><br />
			<?=$campoEmpresa?><br /></li>';
		} 
	
		$sql_socio = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "'";
		$resultado_socio = mysql_query($sql_socio) or die (mysql_error());
		$linhaAtual = 0;
	
		while ($linha_socio=mysql_fetch_array($resultado_socio)){
			$linhaAtual = $linhaAtual + 1;
			if ($campoSocio[$linhaAtual] != '') { 
				if ($linha_socio['nome'] == '') {
					$linha_socio['nome'] = $linhaAtual;
				} 
				$html .= '<li><a href="meus_dados_socio.php?editar='.$linha_socio['idSocio'].'"><strong>Dados do(a) Sócio(a) '.$linha_socio['nome'].'</strong></a><br />'.$campoSocio[$linhaAtual].'<br /></li>';
			}
		} 

		$html .= '</ul>';
		
		// Mostra o html.
		echo $html;
		
	} else {
	?>
		<form name="frmGerarFolha" id="frmGerarFolha" style="display:inline" action="sefip_folha_gravar.php" method="post">
			<input type="hidden" name="mes" id="mes" value="<?=$_REQUEST['mes']?>" />
			<input type="hidden" name="ano" id="ano" value="<?=$_REQUEST['ano']?>" />
			<input type="hidden" name="hidVerificaAtividade" id="hidVerificaAtividade" value="nao" />
			<input type="hidden" name="hidSimples" id="hidSimples" value="2" />
			<input type="hidden" name="hidConcomitante" id="hidConcomitante" value="nao" />
			<input type="hidden" name="hidCod_Recolhimento" id="hidCod_Recolhimento" value="115" />
		</form>
		<script>
			document.getElementById('frmGerarFolha').submit();
		</script>
	<?
	}
} else {
	
	echo $html;	
?>

<!--<form name="frmFolhaPagamento" id="frmFolhaPagamento" style="display:inline" action="sefip_folha_gravar.php" method="post">-->
<form name="frmFolhaPagamento" id="frmFolhaPagamento" style="display:inline" action="sefip_folha.php" method="post">

<input type="hidden" name="hidVerificaAtividade" id="hidVerificaAtividade" value="nao" />
<input type="hidden" name="hidSimples" id="hidSimples" value="2" />
<input type="hidden" name="hidConcomitante" id="hidConcomitante" value="nao" />
<input type="hidden" name="hidCod_Recolhimento" id="hidCod_Recolhimento" value="115" />
    <div style="float:left; margin-right:10px" class="escolherMes">
		A Gfip que pretende enviar se refere a pró-labores e salários de:

	<?php
		// checa mês e ano
		if(isset($_REQUEST['periodoMes']) && isset($_REQUEST['periodoAno']) && $_REQUEST['periodoMes'] != '' && $_REQUEST['periodoAno'] != ''){
			$curr_month = $_REQUEST['periodoMes'];
			$curr_year = $_REQUEST['periodoAno'];
		}else{
			$curr_month = date("m")-1;
			$curr_year = date("Y");
			if(date("m") == '01'){
				$curr_month = 12;
				$curr_year = date("Y") - 1;
			}
		}
    ?>

    <select name="mes" id="mes">
    <?
    $select = "";
    foreach ($month as $key => $val) {
        $select .= "\t<option value=\"".str_pad($key, 2, "0", STR_PAD_LEFT)."\"";
        //if ($key == $curr_month) {
		if ($key == $curr_month) {
            $select .= " selected=\"selected\">".$val."</option>\n";
        } else {
            $select .= ">".$val."</option>\n";
        }
    }
    echo $select;
    ?>
    </select>

<!--checa prazo -->
&nbsp;&nbsp;&nbsp;Ano de 
	<?php //Caso seja anexo 4, exibe o botao para exibir a tela dos tomadores quando for escolhido o periodo da gefip ?>
	<input name="ano" id="ano" type="text" value="<?=$curr_year?>" width="40" maxlength="4" size="4" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php if( $_SESSION['anexo4'] == true ) {?>

		<input name="" type="button" value="Continuar" id="btnEscolherTomador" />

	<?php }else{ echo $_SESSION['anexo4'];?>

	<input name="" type="button" value="Continuar" id="btnGerarFolha" />

	<?php } ?>

</div>

<div id="content"></div>

<div style="clear: both;"></div>
<?php 
	
	if( isset( $_GET['socio'] ) ):
		
			$consulta_pagamento_socio = mysql_query("SELECT * FROM dados_pagamentos WHERE id_socio = '".$_GET['socio']."' ");
	        $objeto=mysql_fetch_array($consulta_pagamento_socio);
	        if( isset( $objeto['id_pagto'] ) ){
	        	echo "SELECT * FROM dados_pagamentos WHERE id_socio = '".$_GET['socio']."' ";
				echo '<script>alert("É necessário completar o cadastro dos sócios para gerar a sefip");</script>';
				echo '<br><a href="meus_dados_socio.php?editar='.$_GET['socio'].'" target="_blank">Clique aqui para completar as informações do Sócio</a>';
			}
	endif;

?>

<div id="alertaMovimento" class="boxMSG" style="clear: both;margin-top: 20px; background-color:#FFF; border-color:#a61d00; border-width:1px; border-style:solid; padding:20px; display: none">
	<strong>ATENÇÃO:</strong> Para conseguir gerar a folha de pagamento, você precisa primeiro emitir os recibos de pagamento dos sócios (e autônomos se houver). Faça isso nas páginas <a href="pro_labore.php">pró-labore</a> e <a href="pagamento_autonomos.php" >autônomos</a>. <!--<strong>Lembre-se que data dos recibos deve pertencer ao mês ao qual a Gfip de refere</strong>-->.
</div>

<div id="alertaMovimento2" class="boxMSG" style="clear: both;margin-top: 20px; background-color:#FFF; border-color:#a61d00; border-width:1px; border-style:solid; padding:20px; display: none">
	<strong>ATENÇÃO:</strong> Para conseguir gerar a folha de pagamento, você precisa primeiro emitir os recibos de pagamento dos sócios, funcionários (e autônomos se houver). Faça isso nas páginas <a href="pro_labore.php">pró-labore</a>, <a href="pagamento_funcionario.php">funcionários</a> e <a href="pagamento_autonomos.php" >autônomos</a>. <!--<strong>Lembre-se que data dos recibos deve pertencer ao mês ao qual a Gfip de refere</strong>-->.
	<br/><br/>
	<strong>Funcionários sem lançamento:</strong> 
	<ul id="funcionarioSemLancamento1">
		<li>Atano teste4.</li>
		<li>Atano teste3.</li>
	</ul>
</div>

<div id="alertaMovimento3" class="boxMSG" style="clear: both;margin-top: 20px; background-color:#FFF; border-color:#a61d00; border-width:1px; border-style:solid; padding:20px; display: none">
	<strong>ATENÇÃO:</strong> Para conseguir gerar a folha de pagamento, você precisa primeiro emitir os recibos de pagamento dos funcionários. Faça isso na página <a href="pagamento_funcionario.php">funcionários</a>. <!--<strong>Lembre-se que data dos recibos deve pertencer ao mês ao qual a Gfip de refere</strong>-->.
	<br/><br/>
	<strong>Funcionários sem lançamento:</strong>
	<ul id="funcionarioSemLancamento2">
		<li>Atano teste1.</li>
		<li>Atano teste2.</li>
	</ul>
</div>

</form>

<? } ?>

<script>
    	
     	var erro = false;
    	var mensagem = "";
    	var quantidade_trabalhadores_selecionados = 0;
    	var quantidade_trabalhadores_selecionados_total = 0;
    	$(document).ready(function() {
    		
    		$(".trabalhadores_aux").html( $(".trabalhadores").html() );

    		$("#btnEscolherTomador").click(function() {

    			$("#aviso_cessao_mao_de_obra").css("display","block");

    			var mes_escolhido = $("#mes").val();
    			var ano_escolhido = $("#ano").val();

    			var inputs = $(".mes_"+mes_escolhido+"_"+ano_escolhido);
    			
				console.log(inputs);			
				
    			quantidade_trabalhadores_selecionados_total = inputs.length / 2;

				console.log(quantidade_trabalhadores_selecionados_total);
				
    			if( inputs.length === 0 ){
    				$(".trabalhadores p").css("display","none");
    				// $("#aviso_cessao_mao_de_obra").attr("style","padding:40px;display:block");
    				$("#tomadores").css("display","none");
    				$(".novo_tomador").css("display","none");
    				$("#btContinuarEmpreitada").css("display","none");
    				
    				$(".aviso_sem_pagamentos").empty();

    				$("#aviso_cessao_mao_de_obra").prepend("<div class='aviso_sem_pagamentos' style='font-family: Open Sans, sans-serif;'><div style='clear: both; margin-top: 20px; border: 1px solid rgb(166, 29, 0); padding: 20px; display: block; background-color: rgb(255, 255, 255);'><strong>ATENÇÃO:</strong> Para conseguir gerar a folha de pagamento, você precisa primeiro emitir os recibos de pagamento dos sócios (e autônomos se houver). Faça isso nas páginas <a href='pro_labore.php'>pró-labore</a> e <a href='pagamento_autonomos.php'>autônomos</a>. <strong>Lembre-se que data dos recibos deve pertencer ao mês ao qual a Gfip de refere</strong>. </div></div>");
    			}
    			else{
    				$(".trabalhadores p").css("display","block");
    				$(".escolherMes").css("display","none");
    				
    				$("#aviso_cessao_mao_de_obra").attr("style","display: block; border: 0px; padding-left: 0px; background: none;");
    				$("#tomadores").css("display","block");
    				$(".novo_tomador").css("display","block");
    				$("#btContinuarEmpreitada").css("display","block");

    				$(".aviso_sem_pagamentos").css("display","none");
    				

    			}

    			for (var i = 0; i < inputs.length; i++) {
    				var input = inputs[i];
    				$(input).css("display","block");
    			};
    		});

    		$("#tomador_Estado").change(function(event) {
    			var uf = $(this).val();
    			$.ajax({
					url: 'SEFIP_config.php',
					data: 'uf='+uf,
					dataType:"text",
					type:"POST",
					cache: false,
					success: function(response){
						$("#tomador_Cidade").empty();
						$("#tomador_Cidade").append(response);
					}
				});
    		});

    		$(".novo_tomador").click(function() {

    			// $("#aviso_cessao_mao_de_obra").css("display","none");
    			$("#cadastrar_novo_tomador").css("display","block");

    		});

    		$("#btCancelarTomador").click(function() {
    			// $("#aviso_cessao_mao_de_obra").css("display","block");
    			$("#cadastrar_novo_tomador").css("display","none");
    		});

    		$("#btSalvarTomador").click(function() {

    			var error_form = false;

				var tomador_nome 			= $("#tomador_nome").val();
				if(tomador_nome === ''){
					alert("Informe o campo nome");
					error_form = true;
					return;
				}

				var tomador_boleto_cnpj 	= $("#tomador_boleto_cnpj").val();
				if(tomador_boleto_cnpj === ''){
					alert("Informe o campo CNPJ");
					error_form = true;
					return;
				}

				var tomador_Endereco 		= $("#tomador_Endereco").val();
				if(tomador_Endereco === ''){
					alert("Informe o campo Endereco");
					error_form = true;
					return;
				}

				var tomador_Bairro 			= $("#tomador_Bairro").val();
				if(tomador_Bairro === ''){
					alert("Informe o campo Bairro");
					error_form = true;
					return;
				}

				var tomador_CEP 			= $("#tomador_CEP").val();
				if(tomador_CEP === ''){
					alert("Informe o campo CEP");
					error_form = true;
					return;
				}

				var tomador_Estado 			= $("#tomador_Estado").val();
				if(tomador_Estado === ''){
					alert("Informe o campo Estado");
					error_form = true;
					return;
				}

				var tomador_Cidade 			= $("#tomador_Cidade").val();
				if(tomador_Cidade === ''){
					alert("Informe o campo Cidade");
					error_form = true;
					return;
				}

				if( error_form === false ){
					$.ajax({
						url: 'SEFIP_config.php',
						data: 'salvarTomador=ok&tomador_nome='+tomador_nome+'&tomador_boleto_cnpj='+tomador_boleto_cnpj+'&tomador_Endereco='+tomador_Endereco+'&tomador_Bairro='+tomador_Bairro+'&tomador_CEP='+tomador_CEP+'&tomador_Estado='+tomador_Estado+'&tomador_Cidade='+tomador_Cidade,
						dataType:"text",
						type:"POST",
						cache: false,
						success: function(response){
							$("#tomadores").append(response);
							$("#aviso_cessao_mao_de_obra").css("display","block");
							$("#cadastrar_novo_tomador").css("display","none");

							var item = $(".cada_tomador .trabalhadores");
							$(item[item.length-1]).append($(".trabalhadores_aux").html());
						}
					});
					

				}

    		});
	
			$("#tomadores .trabalhadores input").click(function() {
				
				var id_click = $(this).attr("id-trabalhador") ;
				var tipo_click = $(this).attr("tipo") ;
				var status_click = $(this).attr("checked") ;

				var inputs = $("#tomadores .trabalhadores input");
				console.log(status_click);				
				if( status_click === false ){

					for (var i = 0; i < inputs.length; i++) {
						var aux = inputs[i];
						if( aux != this ){
							if( $( aux ).attr("id-trabalhador") === id_click && $( aux ).attr("tipo") === tipo_click ){
								$(aux).removeAttr("disabled");
							}
						}
					};
					quantidade_trabalhadores_selecionados = parseInt(quantidade_trabalhadores_selecionados) - 1;
				}
				else{

					for (var i = 0; i < inputs.length; i++) {
						var aux = inputs[i];
						if( aux != this ){
							if( $( aux ).attr("id-trabalhador") === id_click && $( aux ).attr("tipo") === tipo_click ){
								$(aux).attr("disabled","disabled");
							}
						}
					};
					quantidade_trabalhadores_selecionados = parseInt(quantidade_trabalhadores_selecionados) + 1;

				}

				console.log(quantidade_trabalhadores_selecionados);
			});

    		//Percorre cada input e verifica os que estao marcados para salvar as relações no banvo
    		$("#btContinuarEmpreitada").click(function() {

    			error = false;
    			reset();
    			var dados = Array();
    			//Pega cada input dos tomadores
    			var tomadores = $("#tomadores .coluna1 input");
    			//Percorre os inputs dos tomadores
				for (var i = 0; i < tomadores.length; i++) {
    				var tomador = tomadores[i];
    				//Pega o status do input
    				var status_tomador = $(tomador).attr("checked");
    				//Pega o nome do tomador
    				var nome_tomador = $(tomador).attr("nome");
    				//Pega o id do tomadr
    				var id_tomador = $(tomador).attr("id-tomador");
    				//Caso o input esteja selecionado, verifica quais trabalhadores estao relacionados
    				if( status_tomador === true ){
    					//Verifica se o usuario preencheu a retencao
    					error = verificaRetencao(id_tomador,nome_tomador);

    					if( error === false ){
    						$(this).find(".salvar_dados").css("display","block");
    						return;
    					}

    					//Pega a div dos inputs relacionados com o tomador
    					var aux_trabalhador = $(tomador).parent().parent();
    					//Pega os inputs do atual tomador
    					var trabalhadores = $(aux_trabalhador).find(".trabalhador");
    					//Variavel que guarda a quantidade de trabalhadores para o tomador atual, que se selecionado, devera ter ao menos um trabalhador relacionado
    					var quantidade_trabalhadores = 0;
    					//Percorre cada trabalhador
    					for (var j = 0; j < trabalhadores.length; j++) {
    						var trabalhador = trabalhadores[j];
    						// Pega o status do trabalhador
    						var status_trabalhador = $(trabalhador).attr("checked");
    						//Se o trabalhador foi selecionado, guarda os id's do tomador e do trabalhador no banco
    						if( status_trabalhador === true ){
    							//Incrementa a quantidade de trabalhadores selecionados para o tomador
    							quantidade_trabalhadores = parseInt(quantidade_trabalhadores) + parseInt(1);
    							var id_trabalhador = $(trabalhador).attr("id-trabalhador");
    							var tipo_trabalhador = $(trabalhador).attr("tipo");

    							var valor_retencao_tomador = $("#tomador"+id_tomador).parent().parent().find(".retencao").val();
    							valor_retencao_tomador = valor_retencao_tomador.replace(".","");
    							valor_retencao_tomador = valor_retencao_tomador.replace(",",".");
    							//Verifica se recolhe cprb, se sim, pega o valor da compensacao, se nao, o valor da compensacao e ZERO
    							
    							<?php 

    								if( $_SESSION['compensacao'] == 'true' )
    									echo 'compensacao = "1"';
    								else
    									echo 'compensacao = "0"';

    							?>


	    						dados.push(id_tomador+"-"+id_trabalhador+"-"+compensacao+"-"+valor_retencao_tomador+"-"+tipo_trabalhador);
	    						console.log("Relacao: "+id_tomador+"-"+id_trabalhador+" valor compensacao: "+compensacao+" valor retencao: "+valor_retencao_tomador+"-"+tipo_trabalhador);
    						}
    					};
    					//Verifica se existe al menos 1 trabalhador no tomador marcado como se
	    				if( quantidade_trabalhadores === 0 ){
							erro = 1;
							mensagem = "Relacione pelo menos 1 trabalhador para o tomador(a) "+nome_tomador;
							error = trataErroTrabalhador(id_tomador);
							$(this).find(".salvar_dados").css("display","block");
							error = false;
							return;
						}
    				}
    			};
    			// if( quantidade_trabalhadores_selecionados === 1 )
    			// 	quantidade_trabalhadores_selecionados = parseFloat(quantidade_trabalhadores_selecionados) * parseFloat(quantidade_trabalhadores_selecionados_total);
    			console.log(quantidade_trabalhadores_selecionados+'-'+quantidade_trabalhadores_selecionados_total);
    			
    			if( quantidade_trabalhadores_selecionados*2 < quantidade_trabalhadores_selecionados_total ){
//    				console.log(quantidade_trabalhadores_selecionados+'-'+quantidade_trabalhadores_selecionados_total);
    				error = false;
    				alert("Um dos trabalhadores não foi alocado a nenhum tomador.");
    			}    			    			

    			if( error === true ){
    				$(".salvar_dados").css("display","none");
    				$(this).find(".divCarregando2").css("display","block");
    				// $(this).find(".salvar_dados").css("display","block");
    				salvarDados(dados);
    				gerarFolha();
    			}
    			else{
    				$(this).find(".salvar_dados").css("display","block");
    			}

    		});
			
			function gerarFolha(){
				if($('#indicador_previdencia').val() == "2"){

					if($('input[name="data_atraso"]').val() == ""){
						
						alert('Preencha a data do recolhimento em atraso.');
						return false;

					}else{
				
						var dia_campo = parseInt($('input[name="data_atraso"]').val().substr(0,2));
						var mes_campo = parseInt($('input[name="data_atraso"]').val().substr(3,2));
						var ano_campo = parseInt($('input[name="data_atraso"]').val().substr(6,4));
				
						var data = new Date();
						var dia_corrente = data.getDate();
						var mes_corrente = data.getMonth()+1;
						var ano_corrente = data.getFullYear();
						
						var mensagem = "";
						
		//				if(ano_campo != ano_corrente){
		//					mensagem = "O ano deve ser o corrente!";
		//				}else{		
							if(ano_campo < ano_corrente){
								mensagem = "A data em que será efetuado o recolhimento não pode ser anterior ao dia de hoje!";
							}else{
								if(mes_campo < mes_corrente){
									mensagem = "A data em que será efetuado o recolhimento não pode ser anterior ao dia de hoje!";
								}else{
									if(mes_campo > mes_corrente){
										mensagem = "A data em que será efetuado o recolhimento não pode ser de um mês diferente do atual!";
									}else{
										if(dia_campo < dia_corrente){
											mensagem = "A data em que será efetuado o recolhimento não pode ser anterior ao dia de hoje!";
										}
									}
								}
							}
		//				}
					
						if(mensagem != ""){
							alert(mensagem);
							$('input[name="data_atraso"]').focus();
							return false;
						}	
					}
				}
				
				console.log('');
				
				$.ajax({
					url:'sefip_folha_checa_movimento.php?id=<?=$_SESSION["id_empresaSecao"]?>&mes=' + $('#mes').val() + '&ano=' + $('#ano').val(),
					type: 'get',
					async: false,
					cache: false,
					dataType: 'json',
					success: function(ret) {

						$("body").css("cursor", "auto");
						if(ret['status'] == 'erro'){
							$('#alertaMovimento').css('display','block');
							return false;
						}
						if(ret['status'] == 'ok' || ret['status'] == 'ok1'){
							$('#alertaMovimento').css('display','none');
							formSubmit();
						}
						if(ret['status'] == 'erro1'){
							$('#alertaMovimento').css('display','none');
							if(confirm(ret['msg'])){
								formSubmit();
							}else{
								return false;
							}
						}
						if(ret['status'] == 'erro2'){
							location.href="gfip_sem_movimento.php";
						}
						if(ret['status'] == 'erro3'){
							$('#alertaMovimento').css('display','block');
							if(confirm(ret['msg'])){
								location.href="gfip_sem_movimento.php";
							}else{
								return false;
							}
						}						
					},
					error: function(xhr) { console.log('Erro retorno');	},
					beforeSend: function() {},					
					complete: function() {}
				});				
			}
			
    		$("#tomadores .coluna1 input").click(function() {
    			var item = $(this).parent().parent();
    			var status = $(item).attr('status');
    			if( status === 'off' ){
    				show(item);
    			}
    			else{
    				hide(item);
    			}
    		});

    		$("#cprb_sim").click(function() {
    			
    			if( $(this).attr("checked") === 'false' ){
    				$(this).attr("checked",'true');
    				$(this).css("background-color","rgb(166, 29, 0)");
    				$("#cprb_nao").attr("checked","false");
    				$("#cprb_nao").css("background-color","rgb(2, 74, 104)");
    			}

    		});

    		$("#cprb_nao").click(function() {
    			
    			if( $(this).attr("checked") === 'false' ){
    				$(this).attr("checked",'true');
    				$(this).css("background-color","rgb(166, 29, 0)");
    				$("#cprb_sim").attr("checked",'false');
    				$("#cprb_sim").css("background-color","rgb(2, 74, 104)");
    			}

    		});
    		
    		$("#btContinuarEmpreitadaParcial").click(function() {

    			if( $("#cprb_sim").attr("checked") === 'false' && $("#cprb_nao").attr("checked") == 'false' )
    				return;

    			$("#pergunta_inicial_cprb").css("display","none");
    			$("#tomadores").css('display', 'block');
    			$("#btContinuarEmpreitada").css("display","block");
    			$("#btContinuarEmpreitadaParcial").css('display', 'none');

    			$(".novo_tomador").css("display","block");


    			$(".quadro_passos_sem_largura").css("background","none");
    			$(".quadro_passos_sem_largura").css("border","0");
    			$(".quadro_passos_sem_largura").css("padding","0");
    			// $(".quadro_passos_sem_largura").css("background","none");

    		});

    		$(".tomador").click(function() {
    			var tomador = $(this).parent().parent();
    			resetErro(tomador);
    		});

    	});

		function salvarDados(dados){

			for (var i = 0; i < dados.length; i++) {
				var item = dados[i];

				enviaAjax(item,i);

			};

			$.ajax({
				url: 'SEFIP_config.php',
				data: 'empreitada=s',
				dataType:"text",
				type:"POST",
				cache: false,
				success: function(response){
				}
			});

			// escolhe_destino();

		}

		function enviaAjax(string,ordem){
			var hash = '<?php echo md5(date("Y-m-d H:m:s")); ?>';
			$.ajax({
				url: 'SEFIP_config.php',
				data: 'salvarDadosSefip=salvarDadosSefip&ordem='+ordem+'&string='+string+'&hash='+hash,
				dataType:"text",
				type:"POST",
				cache: false,
				success: function(response){
					console.log(response);
				}
			});

		}

		function trataErroTrabalhador(tomador){
    			
    		alert(mensagem)	;
    			
		}
		function verificaRetencao(tomador,nome_tomador){
			var aux = $("#tomador"+tomador).parent().parent().find(".retencao");
			var valor_retencao = $(aux).val();
			if( valor_retencao === '' ){
				erro = 2;
				mensagem = "Informe o valor da retenção para o tomador(a) "+nome_tomador;
				trataErroTrabalhador(tomador);
				return false;
			}
			return true;
		}
		function reset(){
			$(".erros").empty();
			$(".retencao_erros").empty();
		}
		function resetErro(tomador){
			$(tomador).find(".erros").empty();
			$(tomador).find(".retencao_erros").empty();
		}
		function show(item){
			$(item).find('.trabalhadores').fadeIn();
			$(item).find('.coluna2').fadeIn();
			$(item).attr('status','on');

			if( $(item).find('.tomador').attr('id-tomador') != 0 )
				$(item).find(".retencao").removeAttr('disabled');
			else
				$(item).find(".coluna2").css("display","none");
		}
		function hide(item){
			$(item).find('.trabalhadores').fadeOut(0);
			$(item).find('.coluna2').fadeOut(0);
			$(item).attr('status','off');	
			$(item).find(".retencao").attr('disabled', 'disabled');
			$(item).find(".retencao").val("");

			var aux = $(item).find('.trabalhadores').find('input');

			for (var i = 0; i < aux.length; i++) {
				var trabalhador = aux[i];
				var status = $(trabalhador).attr("checked");
				if( status === true ){
					$(trabalhador).attr("checked",false);
					verificarCompensacao(trabalhador);
				}
			};
		}
		function verificarCompensacao(item){
			var status = $(item).attr('checked');
			var id = $(item).attr("id-trabalhador");
			var quantidade = parseInt($(".compensacao"+id).attr("quantidade"));

			if( status === true ){
				var nova_quantidade = parseInt( parseInt(quantidade) + parseInt(1));
				$(".compensacao"+id).attr( "quantidade" , nova_quantidade );
				if( nova_quantidade === 1 )
					$(".compensacao"+id).fadeIn(0);
			}
			else{
				var nova_quantidade = parseInt( parseInt(quantidade) - parseInt(1));
				$(".compensacao"+id).attr( "quantidade" , nova_quantidade );
				if( nova_quantidade === 0 )
					$(".compensacao"+id).fadeOut(0);	
			}
		}
		function gerarCompensacao(item){
			
			var valor = $(item).val();
			
			if( valor === '' )
				return;

			var valor = valor.replace('.',"");
			var valor = parseFloat(valor);

			var porcentagem_fixa = 20;
			//Pegar de acordo com o cnae do usuario
			var porcentagem_rat = 3;
			//Soma as porcentagens fixa de 20% e a porcentagem do RAT
			var porcentagem = parseFloat( parseFloat(porcentagem_rat) + parseFloat(porcentagem_fixa) );
			//Transfrma em formato de porcentagem
			porcentagem = parseFloat(porcentagem/100);
			//Pega o fvalor final no formato float
			valor_final = parseFloat( valor ) * parseFloat( porcentagem );
			//Define o local onde o valor final calculado aparece na tela
			var show_compensacao = $(item).parent().find("span");
			var setValue = $(item).parent().find(".valor_compensacao");

			$(show_compensacao).empty();

			valor_final = valor_final.toFixed(2);

			valor_final_string = valor_final.toString();

			var valor_formatado = valor_final_string.replace('.',",");
			
			$(show_compensacao).append(" R$ "+valor_formatado);

			$(setValue).val(valor_final);
		}
		function abrirTrabalhadores(item){
			var item = $(item).parent().parent();
			var status = $(item).attr('status');
			if( status === 'off' ){
				show(item);
			}
			else{
				hide(item);
			}
		}
    </script>
<?php //Exibe os tomadores, trabalhadores socios para serem relacionados ?>
<div id="aviso_cessao_mao_de_obra" class="quadro_passos_sem_largura" style="display: none;background: none;border: 0;padding-left: 0;">
    
    	<div style="display: block; width:100%;">

    		<?php 
    			//Pega os tomadores para este usuario
    			$consulta_existem_tomadores = mysql_query("SELECT * FROM dados_tomadores WHERE id_login = '".$_SESSION['id_empresaSecao']."' ");
    			$objeto_consulta_existem_tomadores=mysql_num_rows($consulta_existem_tomadores);

    			$consulta_existem_trabalhadores = mysql_query("SELECT * FROM dados_do_responsavel WHERE id = '".$_SESSION['id_empresaSecao']."' ");
    			$objeto_consulta_existem_trabalhadores=mysql_num_rows($consulta_existem_trabalhadores);
    			
    			$consulta_existem_pagamentos = mysql_query("SELECT * FROM dados_pagamentos WHERE id_login = '".$_SESSION['id_empresaSecao']."' ");
    			$objeto_consulta_existem_pagamentos=mysql_num_rows($consulta_existem_pagamentos);
    			

    			// $objeto_consulta_existem_tomadores = 0;
    			//Se nao existirem tomadores, avisa o usuario
    			if( $objeto_consulta_existem_tomadores == 0 ){
    				$texto_erro_tomadores = "Toda empreitada requer a indicação de pelo menos um tomador (a empresa que o contratou para executar o serviço). Cadastre-os em <a href='meus_dados_tomadores.php?act=new' title='Cadastro de Tomadores'>Meus Dados/Cadastro de Tomadores</a>.";
    			}//Se nao existem pagamento, avisa o usuario
    			else if( $objeto_consulta_existem_pagamentos == 0 ){
    				$texto_erro_tomadores = "Não há nenhum pagamento de pró-labores, salários ou remunerações a autônomos do período.<br><br><a href='pro_labore.php' title='Cadastrar Pró-labore'>Pró-labore</a><br><a href='pagamento_autonomos.php' title='Cadastrar Pagamento'>Cadastrar Pagamento</a>";	
    			}
    			//Monta o texto do aviso
    			if( $texto_erro_tomadores != '' ){
    				echo "<div style='font-family: Open Sans, sans-serif;clear: both; margin-top: 20px; border: 1px solid rgb(166, 29, 0); padding: 20px; display: block; background-color: rgb(255, 255, 255);'>";
    				echo $texto_erro_tomadores;
    				echo "</div>";
    				// echo '<script>$("#aviso_cessao_mao_de_obra").attr("style","padding:40px;display:none");</script>';
    			}else{
    		?>



            <div id="tomadores" >
            	<div class="" style="margin-bottom: 20px;">
            		Marque os tomadores para serviços de empreitada realizados no período.<br>
            	</div>

            	<?php 
            		//Pega cada tomador
            		$consulta_tomadores = mysql_query("SELECT id , `dados_tomadores`.nome as nome FROM `dados_tomadores` WHERE id_login = '".$_SESSION['id_empresaSecao']."' UNION SELECT id , `dados_da_empresa`.razao_social as nome FROM `dados_da_empresa` where id = '".$_SESSION['id_empresaSecao']."' ");
            		$consulta_tomadores_quantidade = mysql_num_rows($consulta_tomadores);
            		while( $tomadores=mysql_fetch_array($consulta_tomadores) ){

            	?>

            	  <div class="cada_tomador" status="off">
	            	<small class="erros"></small>
	            	<div class="coluna1">
	            		<input class="tomador" id="tomador<?php if( $_SESSION['id_empresaSecao'] ==  $tomadores['id'] ) echo '0'; else echo $tomadores['id']; ?>" id-tomador="<?php if( $_SESSION['id_empresaSecao'] ==  $tomadores['id'] ) echo '0'; else echo $tomadores['id']; ?>" nome="<?php echo $tomadores['nome']; ?>" type="checkbox" name="" value="">&nbsp;&nbsp;<label><?php echo $tomadores['nome']; ?><?php if( $tomadores['id'] == $_SESSION['id_empresaSecao'] ) echo "  (marque sua empresa como tomadora caso você, algum de seus sócios ou colaborador NÃO tenham trabalhado diretamente em nenhuma das obras)"; ?></label>
	            	</div>
	            	<div class="coluna2">
	            		<div>
	            			Informe o valor de INSS retido pelo Tomador: <input class="retencao current" type="text" name="valor" value="0,00" placeholder="" disabled="disabled">
	            		</div>
	            		<small class="retencao_erros"></small>
	            	</div>
	            	<div class="trabalhadores">
	            		<p>
	            			Marque os funcionários que trabalharam para este Tomador
	            		</p>
	            		<?php 
	            			//Para cada tomador, exibe os trabalhadores, sendo socios e autonomos
	            			$consulta_trabalhadores = mysql_query("SELECT id as id,pis as pis, cbo as cbo,	nome as nome, data_nasc as data	FROM dados_autonomos WHERE id_login = '".$_SESSION['id_empresaSecao']."' UNION 
							SELECT 	idSocio as id,nit as pis,codigo_cbo as cbo,nome as nome,data_admissao as data FROM dados_do_responsavel WHERE id = '".$_SESSION['id_empresaSecao']."' ORDER BY pis");
	            			while( $trabalhadores=mysql_fetch_array($consulta_trabalhadores) ){
	            				if( intval($trabalhadores['data']) == 0 )
	            					$tipo_trabalhador = 'autonomo';
	            				else
	            					$tipo_trabalhador = 'socio';
	            				//Pega os dados de pagamento para o trabalhador
	            				$consulta_pagamento_existente = mysql_query("SELECT * FROM dados_pagamentos	WHERE id_".$tipo_trabalhador." = '".$trabalhadores['id']."' ");

	            				$class_pagamento_existente = "";
	            				//Seta uma classe definindo o mes e o ano do pagamento, sendo que apenas os pagamentos com o mes e ano escolhido pelo usuario aparecerão para escolha
	            				while($objeto_pagamento_existente=mysql_fetch_array($consulta_pagamento_existente)){
	            					$aux__pagamento_existente = explode('-', $objeto_pagamento_existente['data_pagto']);
	            					$class_pagamento_existente .= 'mes_'.$aux__pagamento_existente[1].'_'.$aux__pagamento_existente[0].' ';
	            				}

	            				

	            		?>
	            			<div class="<?php echo $class_pagamento_existente; ?>" style="display:none">
	            				<input class="trabalhador" id-trabalhador="<?php echo $trabalhadores['id'] ?>" tipo="<?php echo $tipo_trabalhador; ?>" type="checkbox" name="" value="">&nbsp;&nbsp;<label><?php echo $trabalhadores['nome'] ?></label><br>	
	            			</div>
	            		
	            		<?php } ?>
	            	</div>
	            	<div class="trabalhadores_aux" style="display:none" ></div>
	            </div>


	            <?php } ?>
	            <?php if( $consulta_tomadores_quantidade == 1 ){ ?>
		            
		            <script>
		            //Se tiver apenas um tomador, ja deixa aparecendo as opções e deixa o input clicado
		            $(document).ready(function() {
		            	$(".coluna1 input").click();
		            	show($(".coluna1 input"));
		            });
		            
		            </script>

	            <?php } ?>

	            <br>
	            <br>
	            <br>
	            <div style="clear: both; height: 5px" ></div>
            	
            </div>
            <a href="#tomador"><span class="novo_tomador">Cadastrar novo tomador</span></a>
         </div>
         
	     <!-- <a href="#tomador"><span>Inserir novo Trabalhador</span></a> -->
         <div style="clear: both; height: 5px;" ></div>
         <div class="" id="btContinuarEmpreitada" style="float:left">
	         	<br><input name="" type="button" class="salvar_dados" value="Continuar"><br>
	         	<input name="" type="button" value="Continuar" id="btnGerarFolha" style="display:none" />
	         	<div class="divCarregando2" style="margin-top:10px; text-align:left;display:none"><img src="images/loading.gif" width="16" height="16"></div>
	     </div>
	     <div style="clear: both; height: 5px;" ></div>
	     <?php } ?>
    </div>
   

    <div id="cadastrar_novo_tomador" class="" style="border: 0px; width: 446px; height: 365px; left: 50%; margin-left: -223px; top: 150px; display: none; z-index: 999; position: absolute; border-radius: 18px; box-shadow: rgb(153, 153, 153) 3px 3px 8px; padding: 20px; background: rgb(255, 255, 255);">


		<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Tomadores (Construção Civil)</div>

	    <table border="0" cellpadding="0" cellspacing="3" class="formTabela">
		  <tbody><tr>
		    <td width="115" align="right" valign="middle" class="formTabela">Nome:</td>
		    <td class="formTabela"><input name="nome" id="tomador_nome" type="text" size="50" maxlength="50" value="" alt="Nome"></td>
		  </tr>

		  <tr>
		    <td align="right" valign="middle" class="formTabela">CNPJ/CEI:</td>
		    <td class="formTabela">  <input type="text" name="CEI" id="tomador_boleto_cnpj" maxlength="18" size="18" class="campoCNPJ" value="" style="display: block;" alt="CNPJ">
			</td>
		  </tr>

		  <tr>
		    <td align="right" valign="middle" class="formTabela">Endereço:</td>
		    <td class="formTabela"><input name="Endereco" id="tomador_Endereco" type="text" style="width:300px" value="" maxlength="200" alt="Endereço"></td>
		  </tr>

		  <tr>
		    <td align="right" valign="middle" class="formTabela">Bairro:</td>
		    <td class="formTabela"><input name="Bairro" id="tomador_Bairro" type="text" value="" size="40" maxlength="20" alt="Bairro"></td>
		  </tr>

		  <tr>
		    <td align="right" valign="middle" class="formTabela">CEP:</td>
		    <td class="formTabela"><input name="CEP" id="tomador_CEP" type="text" style="width:80px" value="" maxlength="9" alt="CEP" class="cep"> 
		      <span style="font-size:10px; display: none">(somente números)</span></td>
		  </tr>

		  <tr>
		  	<?php 
		  		$arrEstados = array();
				$sql = "SELECT * FROM estados ORDER BY sigla";
				$result = mysql_query($sql) or die(mysql_error());
				while($estados = mysql_fetch_array($result)){
					array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
				}

		  	 ?>
		    <td align="right" valign="middle" class="formTabela">Estado:</td>
		    <td class="formTabela"><select name="Estado" id="tomador_Estado" alt="Estado">
		    	<option value="">Selecione o estado</option>
		          <?
		            foreach($arrEstados as $dadosEstado){
						echo "<option class=\"escolher_estado\" id-uf=\"".$dadosEstado['id']."\" value=\"".$dadosEstado['sigla']."\" >".$dadosEstado['sigla']."</option>";
		            }
				?>
		    </select></td>
		  </tr>
		  
		  <tr>
		    <td align="right" valign="middle" class="formTabela">Cidade:</td>
		    <td class="formTabela">
		            <select name="Cidade" id="tomador_Cidade" style="width:300px" class="comboM" alt="Cidade">
		            
		            </select>
		    </td>
		  </tr>
		  <tr>
		  	<br>
		    <td style="padding-top: 10px;" colspan="2" align="center" valign="middle" class="formTabela"><input type="button" value="Salvar" id="btSalvarTomador">
		        	<input type="button" value="Cancelar" id="btCancelarTomador">
		        </td>
		    </tr>
		</tbody></table>

    </div>	

    <style>
    	.cidade{
    		display: none;
    	}
    	#tomadores{
    		width: 100%;
    		
    	}
    	#tomadores .cada_tomador{
			margin-bottom: 8px;
			float: left;
			width: 100%;
    	}
    	#tomadores .coluna1{
    		width: 100%;
    		float: left;
    	}
    	#tomadores .coluna2{
    		width: 100%;	
    		float: left;
    		display: none;
    	}
    	#tomadores .trabalhadores{
    		width: 100%;
    		float: left;
    		padding-left: 30px;
    		display: none;
    		margin-bottom: 10px;
    	}
    	#tomadores .trabalhadores input{
    		margin-bottom: 7px;
    	}
    	.erros{
    		font-size: 10px;
    		color: red;
    	}
    	#tomadores .erros{
    		color: red;
    		display: none;
    		/*width: 100%;*/
    	}
    	#tomadores .retencao_erros{
    		color: red;
    		display: none;
			margin-top: 8px;
			margin-left: 10px;
			float: left;
    	}
    	#tomadores .coluna2 div{
    		float: left;
			margin-left: 20px;
			margin-top: 5px;
    	}
    	#compensacao_nao{
    		display: none;
    	}
    	.compensacao{
    		margin-left: 10px;
    		margin-right: 10px;
    	}
    	#compensacao_sim div{
    		margin-bottom: 8px;
    		width: 100%;
    		float: left;
    		display: none;
    	}
    	#compensacao_sim{
    		display: none;
    	}
    </style>


</div>
</div>

<?php include 'rodape.php' ?>