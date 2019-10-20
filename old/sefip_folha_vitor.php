<?php include 'header_restrita.php' ?>

<?php
session_start(); $_SESSION["folha_pagamento"] = "sim";

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

$month = array (1=>"Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
$valor = array (1=>"01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");



$camposNecessarios = true;


?>


<script>

$(document).ready(function() {
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
		
		
		$.ajax({
		  url:'sefip_folha_checa_movimento.php?id=<?=$_SESSION["id_userSecao"]?>&mes=' + $('#mes').val() + '&ano=' + $('#ano').val(),
		  type: 'get',
		  async: false,
		  cache: false,
		  success: function(ret){
			  
			$("body").css("cursor", "auto");
			if(ret == 'erro'){
				$('#alertaMovimento').css('display','block');
				return false;
			}
			if(ret == 'ok' || ret == 'ok1'){
				$('#alertaMovimento').css('display','none');
				formSubmit();
			}
			if(ret == 'erro1'){
				$('#alertaMovimento').css('display','none');
				if(confirm('Não foi detectado nenhum recolhimento de pró-labore para este mês. Deseja continuar mesmo assim?')){
					formSubmit();
				}else{
					return false;
				}
			}
		  }
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
<div class="principal minHeight">
  <span class="titulo">Impostos e Obrigações</span><br /><br />

  <div style="margin-bottom:20px" class="tituloVermelho">Envio da Gfip</div>

<div style="width:780px;">
Certifique-se de já ter cadastrado em <strong>Pagamentos</strong> os pró-labores, salários e as remunerações a autônomos do período. Se ainda não o fez, faça agora. Atenção: o pró-labore deve ter o mesmo mês da Gfip. Por exemplo, se você estiver enviando a Gfip de janeiro (transmitida em 7/02), então o pró-labore deve ter sido gerado em janeiro e não fevereiro.<br /><br />
</div>
<?php 


// SE FOI POSTADO O FORMULARIO DE PERIODO
if($_POST){

	// fazendo consulta dos códigos da empresa para checar inconsistências
	$sql3 = "SELECT cnae FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_empresaSecao"] . "' AND tipo='1'";
	$resultado3 = mysql_query($sql3) or die (mysql_error());
	$campoEmpresa = '';
		
//	$linha_cnae = mysql_fetch_array($resultado3);
	
	checaCampo (2, mysql_fetch_array($resultado3), 1, 'Código CNAE da atividade Principal', ''); // checando o campo cnae
	
	// criando um loop dos CNAE para a validação do FPAS
	while($linha_cnae = mysql_fetch_array($resultado3)){
		
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
	
	while ($linha_socio=mysql_fetch_array($resultado_socio)){
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
	?>
		Para emitir a Folha de Pagamento, é necessário preencher os seguintes campos em Meus Dados:
		<ul>
		<?php 
		if ($campoEmpresa != '') { 
		?>
			<li><a href="meus_dados_empresa.php"><strong>Dados da empresa</strong></a><br />
			<?=$campoEmpresa?><br /></li>
		<?php 
		} 
	
		$sql_socio = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_userSecao"] . "'";
		$resultado_socio = mysql_query($sql_socio) or die (mysql_error());
		$linhaAtual = 0;
	
		while ($linha_socio=mysql_fetch_array($resultado_socio)){
			$linhaAtual = $linhaAtual + 1;
			if ($campoSocio[$linhaAtual] != '') { 
				if ($linha_socio['nome'] == '') {
					$linha_socio['nome'] = $linhaAtual;
				} ?>
				<li><a href="meus_dados_socio.php?editar=<?=$linha_socio['idSocio']?>"><strong>Dados do(a) Sócio(a) <?=$linha_socio['nome']?></strong></a><br />
				<?=$campoSocio[$linhaAtual]?><br /></li>
				<?php
			}
		} 
		?>
		</ul>

	<?
	}else{
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



}else{

?>



<!--<form name="frmFolhaPagamento" id="frmFolhaPagamento" style="display:inline" action="sefip_folha_gravar.php" method="post">-->
<form name="frmFolhaPagamento" id="frmFolhaPagamento" style="display:inline" action="sefip_folha.php" method="post">

<input type="hidden" name="hidVerificaAtividade" id="hidVerificaAtividade" value="nao" />
<input type="hidden" name="hidSimples" id="hidSimples" value="2" />
<input type="hidden" name="hidConcomitante" id="hidConcomitante" value="nao" />
<input type="hidden" name="hidCod_Recolhimento" id="hidCod_Recolhimento" value="115" />
    <div style="float:left; margin-right:10px">
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
<input name="ano" id="ano" type="text" value="<?=$curr_year?>" width="40" maxlength="4" size="4" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Continuar" id="btnGerarFolha" />
</div>

<div id="content"></div>

<div style="clear: both;"></div>

<div id="alertaMovimento" style="clear: both;margin-top: 20px; background-color:#FFF; border-color:#a61d00; border-width:1px; border-style:solid; padding:20px; display: none"><strong>ATENÇÃO:</strong> Para conseguir gerar a folha de pagamento, você precisa primeiro emitir os recibos de pagamento dos sócios (e autônomos se houver). Faça isso nas páginas <a href="pro_labore.php">pró-labore</a> e <a href="pagamento_autonomos.php" >autônomos</a>. <strong>Lembre-se que data dos recibos deve pertencer ao mês ao qual a Gfip de refere</strong>. </div>

</form>

<? } ?>

</div>
<?php include 'rodape.php' ?>