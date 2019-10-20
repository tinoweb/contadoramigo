<?php include 'header_restrita.php'?>

<?
//$acao = 'inserir';
$acao = 'editar';
$mostra_botoes_formularios_complementares = false;

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};

?>

<script type="text/javascript">


jQuery(document).ready(function() {		

	$('input[name="pensao"]').bind('click',function(){

		if($(this).val() == '1'){
			$('#linha_dados_beneficiaria_pensao').css('display','block');	
		}else{
			$('#linha_dados_beneficiaria_pensao').css('display','none');	
			$('#linha_dados_beneficiaria_pensao').find("input[type='text']").val('');
			
		}
	});	
	
	$('input[name="trabalhaSabado"]').bind('click',function(){
		
		if($(this).attr("checked")== true) {
			
			if($(this).val() == 'S') {
				$('#sabadoHorario').show();
			} else {
				$('#sabadoHorario').hide();
				$('#inicioJornadaSabado').val('00:00');
				$('#fimJornadaSabado').val('00:00');
			}
		}
	});	

	$('input[name="vt"]').bind('click',function(){

		if($(this).val() == '1'){
			$('#linha_vt').css('display','block');	
		}else{
			$('#linha_vt').css('display','none');	
			$('#linha_vt').find("select").val('');
			$('#linha_vt').find("input[type='text']").val('');
			$('#total_tabela_ida').html(mostraTotal('txtTarifaIda'));
			$('#total_tabela_volta').html(mostraTotal('txtTarifaVolta'));
		}
	});
	
	$('input[name="valeRefeicao"]').click(function() {
		
		if($(this).val() == 0) {
			$('#vtPercentual').hide();
			$('#valeRefeicaoPorc').val('');
		} else {
			$('#vtPercentual').show();
		}
	});
	
	$('#btAbreCadastroDependente').bind('click',function(e){
		e.preventDefault();
		$('#cadastro_dependente').css("display","block");
		$(document).scrollTop($(this).offset().top - 35);
	});
	
	$('#btSalvarDependente').bind('click',function(e){
		e.preventDefault();
		formSubmitDependente();
			
	});
	
	$('#btCancelarDependente').bind('click',function(e){
		e.preventDefault();
		// limpa os campos do formulario de dependente
		limpaCamposDependente();
		$('#cadastro_dependente').css("display","none");
	});

	$('#btReativar').css('display','none');

	$('#btCancelar').click(function(){
		location.href = 'meus_dados_funcionario.php'
	});
	$('#btReativar').click(function(){
		location.href = 'meus_dados_funcionario_reativar.php?socio=' + $('#hidSocioID').val();
	});

	///checaDependentes();
	
	montaListaDependentes(); // monta a listagem de dependentes no load da página
	
});


function checaDependentes(){// checando a quantidade de dependentes
			$.ajax({
			url: 'meus_dados_dependentes_funcionario_checa.php',
			data: "id="+$('#hidFuncionarioID').val(),
			type: 'GET',
			async:false,
			cache:false,
			success: function(result){
				if(result > 0 ){
					$('.declaracoes_dependentes').css('display','block');
				} else {
					$('.declaracoes_dependentes').css('display','none');
				}
			}
			});
}

function montaListaDependentes(){// monta a listagem de dependentes

//		$.ajax({
//		url: 'meus_dados_dependentes_funcionario_checa.php',
//		data: "id="+$('#hidFuncionarioID').val(),
//		type: 'GET',
//		async:false,
//		success: function(result){
			//alert(result);
//			if(result > 0 ){
//				$('.declaracoes_dependentes').css('display','block');
//			} else {
//				$('.declaracoes_dependentes').css('display','none');
//			}
				$.ajax({
				url: 'meus_dados_dependentes_funcionario_lista.php',
				data: "id="+$('#hidFuncionarioID').val(),
				type: 'POST',
				async:false,
				cache:false,
				success: function(result){
					$('#tabela_dependentes').html(result);
					checaDependentes();
					$('.btExcluirDependente').bind('click',function(e){
						e.preventDefault();
						if (confirm('Você tem certeza que deseja excluir este Dependente?')){

							$.ajax({
							url: 'meus_dados_dependentes_funcionario_excluir.php',
							data: "dep="+$(this).attr('alt'),
							type: 'GET',
							async:false,
							cache:false,
							success: function(result){
								if(result == "1"){
									alert("Dados excluídos com sucesso!");
								}
								montaListaDependentes();
								checaDependentes();
							}
							});
							//location.href='meus_dados_dependentes_funcionario_excluir.php?dep=' + $(this).attr('alt');
						}
					});
				}
				});
//		}
//		});
}

function formSubmitDependente(){
		if( validRadio('radSexoDependente', msg2) == false){return false}
		if( validElement('txtNomeDependente', msg1) == false){return false}
		if( validElement('selVinculoDependente', msg2) == false){return false}
		if( validElement('txtCPFDependente', msg1) == false){return false}
		if( validElement('txtRGDependente', msg1) == false){return false}
		if( validElement('txtDataEmissaoDependente', msg1) == false){return false}
		if( validElement('txtOrgaoExpedidorDependente', msg1) == false){return false}
		if( validElement('txtDataNascimentoDependente', msg1) == false){return false}
		if( validElement('txtEnderecoDependente', msg1) == false){return false}
		if( validElement('txtBairroDependente', msg1) == false){return false}
		if( validElement('txtCEPDependente', msg1) == false){return false}
		if( validElement('txtCidadeDependente', msg1) == false){return false}
		if( validElement('selEstadoDependente', msg2) == false){return false}
		if( validRadio('radInvalidezDependente', msg2) == false){return false}
		
		if($('#txtCPFDependente').val()!=''){
			$.ajax({
				url:'meus_dados_dependentes_funcionario_checa.php?id=' + $('#hidFuncionarioID').val() + '&cpf=' + $('#txtCPFDependente').val(),
				type: 'get',
				cache: false,
				async: true,
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe um dependente cadastrado para esse funcionário!');  
						return false;
					}
				}
			});
		}
		
		//monta querystring
		var arrData = "";
		arrData += "acao=inserir";
		arrData += "&idFuncionario=" + $("#hidFuncionarioID").val();
		arrData += "&radSexoDependente=" + $("input[name='radSexoDependente']:checked").val();
		arrData += "&txtNomeDependente=" + $("#txtNomeDependente").val();
		arrData += "&selVinculoDependente=" + $("#selVinculoDependente").val();
		arrData += "&txtCPFDependente=" + $("#txtCPFDependente").val();
		arrData += "&txtRGDependente=" + $("#txtRGDependente").val();
		arrData += "&txtDataEmissaoDependente=" + $("#txtDataEmissaoDependente").val();
		arrData += "&txtOrgaoExpedidorDependente=" + $("#txtOrgaoExpedidorDependente").val();
		arrData += "&txtDataNascimentoDependente=" + $("#txtDataNascimentoDependente").val();
		arrData += "&txtEnderecoDependente=" + $("#txtEnderecoDependente").val();
		arrData += "&txtBairroDependente=" + $("#txtBairroDependente").val();
		arrData += "&txtCEPDependente=" + $("#txtCEPDependente").val();
		arrData += "&txtCidadeDependente=" + $("#txtCidadeDependente").val();
		
		var arrDadosEstadoDependente = $('#selEstadoDependente').val().split(';');
		
		arrData += "&selEstadoDependente=" + arrDadosEstadoDependente[1];
		arrData += "&radInvalidezDependente=" + $("input[name='radInvalidezDependente']:checked").val();

		$.ajax({ // envia os dados
			url: 'meus_dados_dependentes_funcionario_gravar.php',
			data: arrData,
			type: 'POST',
			async:false,
			cache:false,
			success: function(result){
				if(result == "1"){ // cadastro ok

					$('#cadastro_dependente').css('display','none');

					// limpa os campos do formulario de dependente
					limpaCamposDependente();
			
					montaListaDependentes();// monta a listagem de dependentes

					$('.declaracoes_dependentes').css('display','block');
										
					//alert('Dependente cadastrado');
					
					return true;
					
				}else{
					
					alert('Erro no cadastramento do dependente');
					$('#cadastro_dependente').css('display','none');
					// limpa os campos do formulario de dependente
					limpaCamposDependente();
					montaListaDependentes();// monta a listagem de dependentes

					return false;
				}
			}
		});

}

function limpaCamposDependente(){
	$("input[name='radSexoDependente']").attr('checked','');
	$("#txtNomeDependente").val('');
	$("#selVinculoDependente").val('');
	$("#txtCPFDependente").val('');
	$("#txtRGDependente").val('');
	$("#txtDataEmissaoDependente").val('');
	$("#txtOrgaoExpedidorDependente").val('');
	$("#txtDataNascimentoDependente").val('');
	$("#txtEnderecoDependente").val('');
	$("#txtBairroDependente").val('');
	$("#txtCEPDependente").val('');
	$("#txtCidadeDependente").val('');
	$("#selEstadoDependente").val('');	
}

function estadoCivilMasculino(){
	document.getElementById('op1EC').text = 'Solteiro';
	document.getElementById('op1EC').value = 'Solteiro';
	document.getElementById('op2EC').text = 'Casado com separação total de bens';
	document.getElementById('op2EC').value = 'Casado com separação total de bens';
	document.getElementById('op3EC').text = 'Casado com comunhão total de bens';
	document.getElementById('op3EC').value = 'Casado com comunhão total de bens';
	document.getElementById('op4EC').text = 'Casado com comunhão parcial de bens';
	document.getElementById('op4EC').value = 'Casado com comunhão parcial de bens';
	document.getElementById('op5EC').text = 'Divorciado';
	document.getElementById('op5EC').value = 'Divorciado';
	document.getElementById('op6EC').text = 'Viúvo';
	document.getElementById('op6EC').value = 'Viúvo';
}

function estadoCivilFeminino(){
	document.getElementById('op1EC').text = 'Solteira';
	document.getElementById('op1EC').value = 'Solteira';
	document.getElementById('op2EC').text = 'Casada com separação total de bens';
	document.getElementById('op2EC').value = 'Casada com separação total de bens';
	document.getElementById('op3EC').text = 'Casada com comunhão total de bens';
	document.getElementById('op3EC').value = 'Casada com comunhão total de bens';
	document.getElementById('op4EC').text = 'Casada com comunhão parcial de bens';
	document.getElementById('op4EC').value = 'Casada com comunhão parcial de bens';
	document.getElementById('op5EC').text = 'Divorciada';
	document.getElementById('op5EC').value = 'Divorciada';
	document.getElementById('op6EC').text = 'Viúva';
	document.getElementById('op6EC').value = 'Viúva';
}
	

function vinculoMasculino(){
	document.getElementById('op1V').text = 'Filho';
	document.getElementById('op1V').value = 'filho';
	document.getElementById('op2V').text = 'Cônjuge';
	document.getElementById('op2V').value = 'cônjuge';
	document.getElementById('op3V').text = 'Genitor';
	document.getElementById('op3V').value = 'genitor';
}

function vinculoFeminino(){
	document.getElementById('op1V').text = 'Filha';
	document.getElementById('op1V').value = 'filha';
	document.getElementById('op2V').text = 'Cônjuge';
	document.getElementById('op2V').value = 'cônjuge';
	document.getElementById('op3V').text = 'Genitora';
	document.getElementById('op3V').value = 'genitora';
}

var msg1 = 'É necessário preencher o campo';
var msg2 = 'É necessário selecionar ';
var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';


function ValidaData(data){
	exp = /\d{2}\/\d{2}\/\d{4}/
	if(!exp.test(data))
	return false; 
}

function ValidaCPF(CPF){
	exp = /\d{11}/
	if(!exp.test(CPF))
	return false; 
}

function ValidaCep(cep){
	exp = /\d{8}/
	if(!exp.test(cep))
	return false; 
}

function ValidaCbo(cbo){
	exp = /\d{6}/
	if(!exp.test(cbo))
	return false; 
}
function formSubmit(nomeFormulario) {
	
	if( validElement('txtNome', msg1) == false){return false}
	if( validRadio('radSexo', msg2) == false){return false}

	if( validElement('txtDataAdmissao', msg1) == false){return false}
	if( validElement('txtNacionalidade', msg1) == false){return false}
	if( validElement('txtNaturalidade', msg1) == false){return false}
	if( validElement('selEstadoCivil', msg2) == false){return false}
	if( validElement('txtCodigoCBO', msg1) == false){return false}
	if( validElement('txtCPF', msg1) == false){return false}
	if( validElement('txtRG', msg1) == false){return false}
	if( validElement('txtCarteiraTrabalho', msg1) == false){return false}
	if( validElement('txtSerieCarteiraTrabalho', msg1) == false){return false}
	if( validElement('selEstadoCarteiraTrabalho', msg2) == false){return false}
	if( validElement('txtDataEmissao', msg1) == false){return false}
	if( validElement('txtOrgaoExpedidor', msg1) == false){return false}
	if( validElement('txtDataNascimento', msg1) == false){return false}
	if( validElement('txtEndereco', msg1) == false){return false}
	if( validElement('txtBairro', msg1) == false){return false}
	if( validElement('txtCEP', msg1) == false){return false}
	if( validElement('txtCidade', msg1) == false){return false}
	if( validElement('selEstado', msg2) == false){return false}
	if( validElement('txtPrefixoTelefone', msg1) == false){return false}
	if( validElement('txtTelefone', msg1) == false){return false}
	if( validElement('txtFuncao', msg1) == false){return false}
	if( validElement('txtPIS', msg1) == false){return false}

//	if( validRadio('txtNumeroDep', msg2) == false){return false}
//	if( validRadio('txtValeTransporte', msg2) == false){return false}

	if( validRadio('pensao', msg2) == false){
		return false;
	}else{
		if($('input[name="pensao"]:checked').val() == '1'){
			if( validElement('PercentPensao', msg1) == false){return false}
			if( validElement('valorPensao', msg1) == false){return false}
//			if( validElement('txtNomeBeneficiaria', msg1) == false){return false}
//			if( validElement('txtCPFBeneficiaria', msg1) == false){return false}
//			if( validElement('txtRGBeneficiaria', msg1) == false){return false}
//			if( validElement('txtDataEmissaoBeneficiaria', msg1) == false){return false}
//			if( validElement('txtOrgaoExpedidorBeneficiaria', msg1) == false){return false}
		}
	}

	if( validRadio('vt', msg2) == false){return false}

	if( validElement('id_banco', msg1) == false){return false}
	if( validElement('tipo_conta', msg1) == false){return false}
	if( validElement('agencia', msg1) == false){return false}
	if( validElement('dig_agencia', msg1) == false){return false}
	if( validElement('conta', msg1) == false){return false}
	if( validElement('dig_conta', msg1) == false){return false}

	if($('#cadastro_dependente').css("display") == "block"){
		return formSubmitDependente(); // chama a função que cadastra o dependente se a janela do formulario estiver visível
	}
	
	if(document.getElementById('txtDataAdmissao').value != ""){
		if (ValidaData(document.getElementById('txtDataAdmissao').value) == false){
			alert('Digite a data de admissão no formato DD/MM/AAAA'); 
			return false;
		}
	}
	if(document.getElementById('txtNacionalidade').value.toLowerCase() == 'brasileiro' || document.getElementById('txtNacionalidade').value.toLowerCase() == 'brasileira') {
		if(document.getElementById('rad'+'Sexo1').checked) {
			document.getElementById('txtNacionalidade').value = 'Brasileiro';
		} else if(document.getElementById('rad'+'Sexo2').checked) {
			document.getElementById('txtNacionalidade').value = 'Brasileira';
		}
	}
	
	var dataAdmissao = $('#txtDataAdmissao').val().split('/');
	var dataDemissao = $('#txtDataDemissao').val().split('/');
	
	dataAdmissao =  new Date(dataAdmissao[2], dataAdmissao[1] - 1, dataAdmissao[0]);
	dataDemissao =  new Date(dataDemissao[2], dataDemissao[1] - 1, dataDemissao[0]);	

	if(dataAdmissao > dataDemissao) {
		alert('A data de demissão não pode ser menor que adminsão');
		return false;
	}
	
	if($('#txtDataDemissao').val().length > 0) {
	
		// Verifica se a data de demissão 	
		checkDemissao();
		
	} else {
 		document.getElementById(nomeFormulario).submit();
	}
}

function getPosicaoElemento(elemID){
    var offsetTrail = document.getElementById(elemID);
    var offsetLeft = 0;
    var offsetTop = 0;
    while (offsetTrail) {
        offsetLeft += offsetTrail.offsetLeft;
        offsetTop += offsetTrail.offsetTop;
        offsetTrail = offsetTrail.offsetParent;
    }
    if (navigator.userAgent.indexOf("Mac") != -1 && 
        typeof document.body.leftMargin != "undefined") {
        offsetLeft += document.body.leftMargin;
        offsetTop += document.body.topMargin;
    }
    return {left:offsetLeft, top:offsetTop};
}

function reposicionaBallons(ballID, refID) {
	var elementTop = 163;
	document.getElementById(ballID).style.marginTop = parseInt(getPosicaoElemento(refID).top) - elementTop + 'px';
}
</script>


<div class="principal" style="height:auto !important;min-height:400px;">


<!--BALLOM CBO -->
<div style="width:310px; position:absolute; top:116px; margin-left:235px; display:none;" id="cbo" class="bubble_left box_visualizacao x_visualizacao">

	<div style="padding:20px;">
        <strong>CBO</strong> - Código Brasileiro de Ocupações é o número que identifica a profissão do trabalhador. Consulte a <a href="http://www.mtecbo.gov.br/cbosite/pages/home.jsf" target="_blank">lista completa</a>.
    </div>
    
</div>
<!--FIM DO BALLOOM CBO -->

<!--BALLOM NIT -->
<div style="width:310px; position:absolute; top:104px; margin-left:260px; display:none;" id="nit" class="bubble_left box_visualizacao x_visualizacao">

	<div style="padding:20px;">
        <strong>PIS</strong> - Programa de Integração Social - é o número de identificação do trabalhador junto à Previdência Social. <br />
        Se você não sabe qual o seu número de PIS, poderá encontrá-lo <a href="http://www1.dataprev.gov.br/cadint/sp2cgi.exe?sp2application=cadint" target="_blank">aqui</a>.
    </div>
    
</div>
<!--FIM DO BALLOOM NIT -->


<div class="titulo" style="margin-bottom:20px">Meus Dados</div>

<?
$mostrar_cadastrar_novo = false;


if($_GET['act'] == 'new'){
	mysql_query("DELETE FROM dados_do_funcionario WHERE id = '" . $_SESSION["id_empresaSecao"] . "' AND status = '0'");
//	if(!$linha = mysql_fetch_array(mysql_query("SELECT * FROM dados_do_funcionario WHERE id = '" . $_SESSION["id_empresaSecao"] . "' AND status = '0'"))){
		mysql_query("INSERT INTO dados_do_funcionario SET id = '" . $_SESSION["id_empresaSecao"] . "', status = '0'");
		$idFuncionario = mysql_insert_id();
//	}else{
//		$idFuncionario = $linha['idFuncionario'];
//	}
}

$textoAcao = "- Incluir";
		
if($_GET['act'] != 'new'){// CHECANDO SE NÃO É A INCLUSAO DE UM NOVO FUNCIONARIO	

	// CHECANDO QUANTIDADE DE FUNCIONÁRIOS
	$sql = "SELECT idFuncionario, nome, sexo, id, cpf, status, data_demissao FROM dados_do_funcionario WHERE id = '" . $_SESSION["id_empresaSecao"] . "' and status > 0 ORDER BY nome";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	if(mysql_num_rows($resultado) == 1){
		$funcionario = mysql_fetch_array($resultado);
		$idFuncionario = $funcionario['idFuncionario'];
		$mostrar_cadastrar_novo = true;
	}
	
	if($_GET["editar"]){
	
		$idFuncionario = $_GET["editar"];
		$mostra_botoes_formularios_complementares = true;	
		
	}
	
	if($idFuncionario){
		
		$textoAcao = "- Editar";
		$acao = 'editar';
		// ALTERAÇÂO DE AUTONOMOS
		$sql = "SELECT * FROM dados_do_funcionario WHERE idFuncionario='" . $idFuncionario . "' LIMIT 0, 1";
		$consulta = mysql_query($sql)
		or die (mysql_error());
		
		if($linha=mysql_fetch_array($consulta)){
			$erro = "";
		
			$id 							= $linha["id"];
			$idFuncionario					= $linha["idFuncionario"];
			$data_admissao					= $linha["data_admissao"];
			$data_admissao = date('d/m/Y', strtotime($data_admissao));
			$data_demissao					= $linha["data_demissao"];
			$data_demissao = (!empty($data_demissao) ? date('d/m/Y', strtotime($data_demissao)) : '');
			
			$nome 							= $linha["nome"];
			$sexo 							= $linha["sexo"];
			$nacionalidade 					= $linha["nacionalidade"];
			$naturalidade					= $linha["naturalidade"];
			$estado_civil					= $linha["estado_civil"];
			$codigo_cbo						= $linha["codigo_cbo"];
			$cpf							= $linha["cpf"];
			$rg								= $linha["rg"];
			if(strlen($rg)>0){
				$rg = preg_replace("/\W/","",$rg);
			}
	
			$ctps							= $linha["ctps"];
			
			$jornadaTrabalhoDiaria 			= $linha["jornada_trabalho_diaria"];
			$inicioJornada 					= date("H:i", strtotime($linha["inicio_jornada"]));
			$fimJornada 					= date("H:i", strtotime($linha["fim_jornada"]));
			$inicioIntervalo 				= date("H:i", strtotime($linha["inicio_intervalo"]));
			$fimIntervalo 					= date("H:i", strtotime($linha["fim_intervalo"]));
			
			$tabalhaSabado					= $linha["trabalhoSabado"];
			$inicioJornadaSabado 			= date("H:i", strtotime($linha["inicio_horario_Sabado"]));
			$fimJornadaSabado 				= date("H:i", strtotime($linha["fim_horario_Sabado"]));
			
			$serie_ctps						= $linha["serie_ctps"];
			$estado_ctps					= $linha["uf_ctps"];
			$data_de_emissao				= $linha["data_de_emissao"];
			$orgao_expeditor				= $linha["orgao_expeditor"];	
			$data_de_nascimento 			= $linha["data_de_nascimento"];
			$endereco						= $linha["endereco"];
			$bairro							= $linha["bairro"];
			$cep							= $linha["cep"];	
			$cidade							= $linha["cidade"];	
			$estado							= $linha["estado"];
			$pref_telefone					= $linha["pref_telefone"];
			$telefone						= $linha["telefone"];
			$funcao							= $linha["funcao"];
			$pis							= $linha["pis"];
			
			$valeTransporte					= $linha["vale_transporte"];
			
			$dependentes					= $linha["dependentes"];
			$pensao							= $linha["pensao"];
			$perc_pensao					= $linha["perc_pensao"];
			$valorPensao					= $linha["valor_pensao"];
			$vale_transporte				= $linha["vale_transporte"];
			//$status							= $linha["status"];
			
			$demissao = $linha["data_demissao"];		
			$status   = (!empty($demissao) && $demissao <= date('Y-m-d') ? 2 : 1 );
						
			$salario						= $linha["valor_salario"];
			$vale_refeicao					= $linha["vale_refeicao"];
			$vale_refeicao_porc				= $linha["vale_refeicao_porc"];
			
			
			$tipo_conta						= $linha["tipo_conta"];
			$id_banco						= $linha["id_banco"];
			$num_agencia					= $linha["agencia"];
			$dig_agencia					= $linha["dig_agencia"];
			$num_conta						= $linha["conta"];
			$dig_conta						= $linha["dig_conta"];
			
			$status_administracao			= $linha["status_administracao"];

			$sql_pensao = "SELECT * FROM dados_pensao_funcionario WHERE idFuncionario='" . $idFuncionario . "' LIMIT 0, 1";
			$consulta_pensao = mysql_query($sql_pensao)
			or die (mysql_error());
			
			if($linha_pensao=mysql_fetch_array($consulta_pensao)){
				$nomeBeneficiaria					= $linha_pensao["nome"];
				$cpfBeneficiaria					= $linha_pensao["cpf"];
				$rgBeneficiaria						= $linha_pensao["rg"];
				$data_de_emissaoBeneficiaria		= $linha_pensao["data_emissao"];
				$orgao_expeditorBeneficiaria		= $linha_pensao["orgao_expeditor"];
			}

			if($status == 2){ // se o funcionario estiver como DEMITIDO (status = 2) deve desabilitar os botoes e campos do form
	?>
					<script>
					$(document).ready(function(){
						$("#form_funcionario").find("input").not('#btCancelar').attr("disabled","disable");
						$("#form_funcionario").find("select").attr("disabled","disable");
						$("#btSalvar").css('display','none');
						$("#btReativar").css('display','block').attr("disabled","");
						//.prop("disabled",true);
					});
					</script>
	<?
			}
//		}else{
//			$erro = "1";	// não foi encontrado funcionario com o ID informado
		}

	}
}

//echo "ID: " . $idFuncionario;

$_SESSION['idFuncionario'] = $idFuncionario;

?>

<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Funcionários

<?

//if($erro != "1"){

	if($_GET['act'] == 'new' || $_GET["editar"]){ 
	
			$arrEstados = array();
            $sql = "SELECT * FROM estados ORDER BY sigla";
            $result = mysql_query($sql) or die(mysql_error());
            while($estados = mysql_fetch_array($result)){
                array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
            }
            
	
	
	echo $textoAcao?>
	</div>
	 
	<div style="margin-bottom:20px;">
	<form method="post" name="form_funcionario" id="form_funcionario" action="meus_dados_funcionario_gravar.php" >
	
	<input type="hidden" name="hidFuncionarioID" id="hidFuncionarioID" value="<?=$idFuncionario?>" />
	<input type="hidden" name="hidStatus" value="<?=$status?>" />
	<input type="hidden" name="acao" value="<?=$acao?>" />
	<table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="radSexo">Sexo:</label></td>
		<td class="formTabela">
		  <label style="margin-right:15px"><input type="radio" name="radSexo" id="radSexo1" value="Masculino" alt="Sexo" onClick="estadoCivilMasculino()" <?php echo checked( 'Masculino', $sexo ); ?> /> Masculino</label>
		  <label><input type="radio" name="radSexo" id="radSexo2" value="Feminino" onClick="estadoCivilFeminino()" <?php echo checked( 'Feminino', $sexo ); ?> /> Feminino</label>
		</td>
	  </tr>
	 <tr>
  	 <tr>
	   <td width="120" align="right" valign="middle" class="formTabela"><label for="txtNome">Nome:</label></td>
	   <td class="formTabela" width="541"><input name="txtNome" type="text" id="txtNome" style="width:300px" value="<?=$nome?>" maxlength="200" alt="Nome" /> </td>
	 </tr> 

	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtDataAdmissao">Data de Admissão:</label></td>
		<td class="formTabela"><input name="txtDataAdmissao" type="text" id="txtDataAdmissao" style="width:80px" value="<?=$data_admissao?>" maxlength="10" alt="Data de Admissão" class="campoData" />
		  <span style="font-size:10px; display: none">DD/MM/AAAA</span></td>
	  </tr>
	  
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtDataDemissao">Data de Demissão:</label></td>
		<td class="formTabela">
	  		<input name="txtDataDemissao" type="text" id="txtDataDemissao" style="width:80px" value="<?=$data_demissao?>" maxlength="10" alt="Data de Demissão" class="campoData" />
			<span style="font-size:10px; display: none">DD/MM/AAAA</span></td>
	  </tr>	  
	  
	  
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtNacionalidade">Nacionalidade:</label></td>
		<td class="formTabela"><input name="txtNacionalidade" type="text" id="txtNacionalidade" style="width:300px" value="<?=$nacionalidade?>" maxlength="100" alt="Nacionalidade" /></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtNaturalidade">Naturalidade:</label></td>
		<td class="formTabela"><input name="txtNaturalidade" type="text" id="txtNaturalidade" style="width:300px" value="<?=$naturalidade?>" maxlength="100" alt="Naturalidade" /></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="selEstadoCivil">Estado Civil:</label></td>
		<td class="formTabela"><select name="selEstadoCivil" id="selEstadoCivil" alt="Estado Civil">
		<option value="">Selecione...</option>
		<?php if ($sexo == 'Feminino') { ?>
		<option id="op1EC" value="Solteira" <?php echo selected( 'Solteira', $estado_civil ); ?> >Solteira</option>    
		<option id="op2EC" value="Casada com separação total de bens" <?php echo selected( 'Casada com separação total de bens', $estado_civil ); ?> >Casada com separação total de bens</option>
		<option id="op3EC" value="Casada com comunhão total de bens" <?php echo selected( 'Casada com comunhão total de bens', $estado_civil ); ?> >Casada com comunhão total de bens</option>
		<option id="op4EC" value="Casada com comunhão parcial de bens" <?php echo selected( 'Casada com comunhão parcial de bens', $estado_civil ); ?> >Casada com comunhão parcial de bens</option>
		<option id="op5EC" value="Divorciada" <?php echo selected( 'Divorciada', $estado_civil ); ?> >Divorciada</option>
		<option id="op6EC" value="Viúva" <?php echo selected( 'Viúva', $estado_civil ); ?> >Viúva</option>
		<?php } else { ?>
		<option id="op1EC" value="Solteiro" <?php echo selected( 'Solteiro', $estado_civil ); ?> >Solteiro</option>    
		<option id="op2EC" value="Casado com separação total de bens" <?php echo selected( 'Casado com separação total de bens', $estado_civil ); ?> >Casado com separação total de bens</option>
		<option id="op3EC" value="Casado com comunhão total de bens" <?php echo selected( 'Casado com comunhão total de bens', $estado_civil ); ?> >Casado com comunhão total de bens</option>
		<option id="op4EC" value="Casado com comunhão parcial de bens" <?php echo selected( 'Casado com comunhão parcial de bens', $estado_civil ); ?> >Casado com comunhão parcial de bens</option>
		<option id="op5EC" value="Divorciado" <?php echo selected( 'Divorciado', $estado_civil ); ?> >Divorciado</option>
		<option id="op6EC" value="Viúvo" <?php echo selected( 'Viúvo', $estado_civil ); ?> >Viúvo</option>
		<?php } ?>
		</select>
	   </td>
	  </tr>
      <tr>
        <td align="right" valign="middle" class="formTabela"><label for="txtCodigoCBO">Código CBO:</label></td>
        <td class="formTabela"><input name="txtCodigoCBO" type="text" id="txtCodigoCBO" style="width:72px; float:left; margin-right:3px" value="<?=str_replace(array("/","-","."),"",$codigo_cbo)?>" class="campoCBO" alt="Código CBO" /> 
        <div style="float:left; margin-right:5px; margin-top:5px"><a href="javascript:abreDiv('cbo');reposicionaBallons('cbo','dicaCBO')"><img src="images/dica.gif" width="13" height="14" border="0" align="texttop" id="dicaCBO" /></a></div>
        <div style="float:left; margin-right:5px; margin-top:5px"><span style="font-size:10px; display: none">(somente números)</span></div></td>
      </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtCPF">CPF:</label></td>
		<td class="formTabela"><input name="txtCPF" type="text" id="txtCPF" style="width:100px" value="<?=str_replace(array("/","-","."),"",$cpf)?>" alt="CPF" class="campoCPF" /> 
		  <span style="font-size:10px; display: none"> (somente números)</span></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtRG">RG:</label></td>
		<td class="formTabela"><input name="txtRG" type="text" id="txtRG" style="width:80px" value="<?=$rg?>" alt="RG" class="campoRG" />
		<span style="font-size:10px; display: none"> (somente números)</span>&nbsp;&nbsp;&nbsp;Data de Emissão: <input name="txtDataEmissao" id="txtDataEmissao" type="text" style="width:80px" value="<?=$data_de_emissao?>" alt="Data de emissão do RG" maxlength="10" class="campoData" />&nbsp;&nbsp;&nbsp;Orgão Expedidor: <input name="txtOrgaoExpedidor" type="text" id="txtOrgaoExpedidor" style="width:70px" value="<?=$orgao_expeditor?>" alt="Órgão Expedidor do RG" maxlength="50" /></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtCarteiraTrabalho">Carteira de Trabalho:</label></td>
		<td class="formTabela"><input name="txtCarteiraTrabalho" type="text" id="txtCarteiraTrabalho" style="width:60px" value="<?=str_replace(array("/","-","."),"",$ctps)?>" alt="Carteira de Trabalho" maxlength="7" class="inteiro" />
		<span style="font-size:10px; display: none"> (somente números)</span>&nbsp;&nbsp;&nbsp;<label for="txtSerieCarteiraTrabalho">Série:</label> <input name="txtSerieCarteiraTrabalho" type="text" id="txtSerieCarteiraTrabalho" style="width:45px" value="<?=str_replace(array("/","-","."),"",$serie_ctps)?>" alt="Série da carteira de trabalho" maxlength="5" class="inteiro" />
		<span style="font-size:10px; display: none"> (somente números)</span>&nbsp;&nbsp;&nbsp;<label for="selEstadoCarteiraTrabalho">Estado:</label> <select name="selEstadoCarteiraTrabalho" id="selEstadoCarteiraTrabalho" alt="Estado de emissão da CTPS">
		  <option value="" <?php echo selected( '', $estado_ctps ); ?> >--</option>
          <?
            foreach($arrEstados as $dadosEstado){
				echo "<option value=\"".$dadosEstado['id'].";".$dadosEstado['sigla']."\" " . selected( $dadosEstado['sigla'], $estado_ctps ) . " >".$dadosEstado['sigla']."</option>";
            }
	?>
		</select></td>
	  </tr>
	  <tr>
	  	<td align="right" valign="middle" class="formTabela"><label for="txtFuncao">Cargo:</label></td>
	  	<td class="formTabela">
	  		<input name="txtFuncao" type="text" id="txtFuncao" maxlength="50" style="width:300px; float:left; margin-right:3px" value="<?=$funcao?>" alt="Função na empresa" />
	  	</td>
	  </tr>
	  <tr>
	  	<td align="right" valign="middle" class="formTabela"><label>Jornada de trabalho:<label></td>
	  	<td>
	  		<input name="jornadaTrabalho" type="text" value="<?=$jornadaTrabalhoDiaria?>" maxlength="2" size="1" />&nbsp; 
	  		<span>horas diárias</span>
	  	</td>
	  </tr>
	  <tr>
	  	<td align="right" valign="middle" class="formTabela"><label>Início da Jornada:<label></td>
	  	<td>	  	  	  
	  		<input name="inicioJornada" type="text" value="<?=$inicioJornada?>" maxlength="5" size="3" />&nbsp;&nbsp;&nbsp;&nbsp;  
	  		<label>Fim da Jornada:</label>&nbsp;<input name="fimJornada" type="text" value="<?=$fimJornada?>" maxlength="5" size="3" />
	  	</td>
	  </tr>	
	  <tr>
	  	<td align="right" valign="middle" class="formTabela"><label>Início do Intervalo:<label></td>
	  	<td>	  	  	  
	  		<input name="inicioIntervalo" type="text" value="<?=$inicioIntervalo?>" maxlength="5" size="3" />&nbsp;&nbsp; 
	  		<label>Fim do Intervalo:</label>&nbsp;<input name="fimIntervalo" type="text" value="<?=$fimIntervalo?>" maxlength="5" size="3" />
	  	</td>
	  </tr>

 	  <tr>
		<td colspan="2" class="formTabela" valign="middle" align="left">
			<label for="vr">Trabalha aos Sábados?</label>&nbsp;&nbsp;
			<input name="trabalhaSabado" value="S" type="radio" <?php echo checked( 'S', $tabalhaSabado ); ?> /> Sim &nbsp;&nbsp;
			<input name="trabalhaSabado" value="N" type="radio" <?php echo checked( 'N', $tabalhaSabado ); ?> /> Não	
		</td>
	  </tr>
 	  <tr id="sabadoHorario" <?php echo ($tabalhaSabado == 'N' ? 'style="display:none;"' :'')?> >
	  	<td align="right" valign="middle" class="formTabela"><label>Início da Jornada:<label></td>
	  	<td>	  	  	  
	  		<input id="inicioJornadaSabado" name="inicioJornadaSabado" type="text" value="<?=$inicioJornadaSabado?>" maxlength="5" size="3" />&nbsp;&nbsp;&nbsp;&nbsp;  
	  		<label>Fim da Jornada:</label>&nbsp;
	  		<input id="fimJornadaSabado" name="fimJornadaSabado" type="text" value="<?=$fimJornadaSabado?>" maxlength="5" size="3" />
	  	</td>
	  </tr>  	  	  	  	  	    	  	  	  	  	  
  	  	  	    	  	  	  	  	    	  	  	  	  	  		  
	   <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtDataNascimento">Data de Nascimento:</label></td>
		<td class="formTabela"><input name="txtDataNascimento" type="text" id="txtDataNascimento" style="width:80px" value="<?=$data_de_nascimento?>" alt="Data de nascimento" maxlength="10" class="campoData" /> 
		  <span style="font-size:10px; display:none">DD/MM/AAAA</span></td>
	  </tr>
		<tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtEndereco">Endere&ccedil;o:</label></td>
		<td class="formTabela"><input name="txtEndereco" id="txtEndereco" type="text" style="width:300px" value="<?=$endereco?>" maxlength="200" alt="Endereço" /></td>
	  </tr>
		<tr>
		  <td align="right" valign="middle" class="formTabela"><label for="txtBairro">Bairro:</label></td>
		  <td class="formTabela"><input name="txtBairro" id="txtBairro" type="text" style="width:300px" value="<?=$bairro?>" maxlength="200" alt="Bairro" /></td>
		</tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtCEP">CEP:</label></td>
		<td class="formTabela"><input name="txtCEP" type="text" id="txtCEP" style="width:80px" value="<?=str_replace(array("/","-","."),"",$cep)?>" alt="CEP" class="campoCEP" /> <span style="font-size:10px; display:none">(somente números)</span></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="selEstado">Estado:</label></td>
		<td class="formTabela"><select name="selEstado" id="selEstado" alt="Estado">
		  <option value="" <?php echo selected( '', $estado ); ?> ></option>
          <?
            foreach($arrEstados as $dadosEstado){
				echo "<option value=\"".$dadosEstado['id'].";".$dadosEstado['sigla']."\" " . selected( $dadosEstado['sigla'], $estado ) . " >".$dadosEstado['sigla']."</option>";
				if($dadosEstado['sigla'] == $estado){
					$idEstadoSelecionado = $dadosEstado['id'];
				}
            }
		?>
		</select></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtCidade">Cidade:</label></td>
		<td class="formTabela"><!--<input name="txtCidade" type="text" id="txtCidade" style="width:300px" value="<?=$cidade?>" alt="Cidade" maxlength="200" />-->
            <select name="txtCidade" id="txtCidade" style="width:300px" class="comboM" alt="Cidade">
            <option value="" <?php echo selected( '', $cidade ); ?>></option>
			<?
			if($idEstadoSelecionado != ''){
				$sql = "SELECT * FROM cidades WHERE id_uf = '" . $idEstadoSelecionado . "' ORDER BY cidade";
				$result = mysql_query($sql) or die(mysql_error());
				while($cidades = mysql_fetch_array($result)){
					echo "<option value=\"".$cidades['cidade']."\" " . selected( $cidades['cidade'], $cidade) . " >".$cidades['cidade']."</option>";
				}
			}
            ?>
            </select>

        </td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtTelefone">Telefone:</label></td>
		<td valign="middle" class="formTabela"><div style="float:left; margin-right:3px; margin-top:3px; font-size:12px">(</div>
		  <div style="float:left"><input name="txtPrefixoTelefone" type="text" id="txtPrefixoTelefone" style="width:35px" value="<?=$pref_telefone?>" maxlength="4" class="inteiro" alt="Prefixo do Telefone" /></div>
		  <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
		  <div style="float:left"><input name="txtTelefone" type="text" id="txtTelefone" style="width:70px" value="<?=$telefone?>" maxlength="9" class="inteiro" alt="Telefone" /></div></td>
	  </tr>
	  <tr>
	  <td align="right" valign="middle" class="formTabela"><label for="txtPIS">PIS:</label> </td>
	  <td class="formTabela"><input name="txtPIS" type="text" id="txtPIS" style="width:100px; float:left; margin-right:3px" value="<?=str_replace(array("/","-","."),"",$pis)?>" maxlength="15" class="campoNIT" alt="PIS" /> 
	  <div style="float:left; margin-right:5px; margin-top:5px"><a href="javascript:abreDiv('nit');reposicionaBallons('nit','dicaPIS')"><img src="images/dica.gif" width="13" height="14" border="0" align="texttop" id="dicaPIS" /></a></div>
	  <div style="float:left; margin-right:5px; margin-top:5px"><span style="font-size:10px; display: none">(somente números)</span></div></td>
	  </tr>
  	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="txtSalario">Salário:</label></td>
		<td class="formTabela">
			<input name="txtSalario" type="text" id="txtSalario" maxlength="12" size='10'  class="current" style="float:left; margin-right:3px" value="<?php echo number_format($salario, 2, ",", ".");?>" alt="Salário do funcionário" />
		</td>
	  </tr>
	  <tr>
		<td colspan="3" align="left" valign="middle" class="formTabela"><br />
			<label for="pensao">Tem pensão alimentícia descontada da folha de pagamento?</label>&nbsp;&nbsp;
			<input name="pensao" type="radio" value="1" <?php echo checked( '1', $pensao ); ?> alt="Pensão alimentícia" /> Sim&nbsp;&nbsp;
			<input name="pensao" type="radio" value="0" <?php echo checked( '0', $pensao ); echo checked( '', $pensao ); ?> /> Não
		</td>
	  </tr>
	  <tr>
		<td colspan="2" style="padding:0; margin:0;" align="left">
			<table border="0" cellpadding="0" cellspacing="3"  id="linha_dados_beneficiaria_pensao" style="display:<?php echo $pensao == '1' ? 'block' : 'none' ; ?>;margin:10px 0;" class="formTabela">
		 	  <tr style="display: none;">
				<td colspan="2" class="formTabela">
					<label>Qual o valor?</label> <input name="valorPensao" id="valorPensao" type="text" size=10 maxlength="9" class="current" style="text-align: right;" value="<?php echo number_format($valorPensao,2,",",".");?>" />
				</td>
			  </tr>
			  <tr style="display: none;"><td colspan="2">&nbsp;</td></tr>
			  <tr>
				<td colspan="2" class="formTabela">
					<label>Qual o percentual?</label> <input name="PercentPensao" id="PercentPensao" type="text" size=4 maxlength="2" value="<?php echo $perc_pensao;?>" />%
				</td>
			  </tr>			  
			  <tr style="display: none;"><td colspan="2">&nbsp;</td></tr>
			  
			  <tr style="display: none;">
				<td colspan="2">
				<div class="tituloAzul">Dados da beneficiária</div>
				</td>
			  </tr>
			  
			  <tr style="display: none;">
				<td width="60" align="right" valign="middle" class="formTabela"><label for="txtNomeBeneficiaria">Nome:</label></td>
				<td width="597" class="formTabela"><input name="txtNomeBeneficiaria" type="text" id="txtNomeBeneficiaria" style="width:300px" value="<?=$nomeBeneficiaria?>" maxlength="200" alt="Nome da Beneficiária" /></td>
			  </tr>
			
			  <tr style="display: none;">
				<td align="right" valign="middle" class="formTabela"><label for="txtCPFBeneficiaria">CPF:</label></td>
				<td class="formTabela"><input name="txtCPFBeneficiaria" type="text" id="txtCPFBeneficiaria" style="width:100px" value="<?=str_replace(array("/","-","."),"",$cpfBeneficiaria)?>" alt="CPF da Beneficiária" class="campoCPF" /> 
				  <span style="font-size:10px; display: none"> (somente números)</span></td>
			  </tr>
			  
			  <tr style="display: none;">
				<td align="right" valign="middle" class="formTabela"><label for="txtRGBeneficiaria">RG:</label></td>
				<td class="formTabela"><input name="txtRGBeneficiaria" type="text" id="txtRGBeneficiaria" style="width:80px" value="<?=preg_replace("/\D/","",$rgBeneficiaria)?>" class="campoRG" />&nbsp;&nbsp;&nbsp;
                <label for="txtDataEmissaoBeneficiaria">Data de Emissão:</label> <input name="txtDataEmissaoBeneficiaria" id="txtDataEmissaoBeneficiaria" type="text" style="width:80px" value="<?=$data_de_emissaoBeneficiaria?>" maxlength="10" class="campoData" />&nbsp;&nbsp;&nbsp;
                <label for="txtOrgaoExpedidorBeneficiaria">Orgão Expedidor:</label> <input name="txtOrgaoExpedidorBeneficiaria" type="text" id="txtOrgaoExpedidorBeneficiaria" style="width:70px" value="<?=$orgao_expeditorBeneficiaria?>" maxlength="250" />
                </td>
			  </tr>
			</table>
		</td>
	  </tr>
	  <tr>
		<td colspan="3" align="left" valign="middle" class="formTabela"><br />
			<label for="vr">Utilizará vale refeição?</label>&nbsp;&nbsp;
			<input name="valeRefeicao" type="radio" value="1" <?php echo checked( '1', $vale_refeicao ); ?> alt="Vale refeição" /> Sim &nbsp;&nbsp;
			<input name="valeRefeicao" type="radio" value="0" <?php echo checked( '0', $vale_refeicao ); echo checked( '', $vale_refeicao ); ?> /> Não
		</td>
	  </tr> 
	  <tr>
		<td colspan="3" align="left" valign="middle" class="formTabela"><br />
			<table border="0" cellpadding="0" cellspacing="3" id="vtPercentual" style="display: <?php echo ($vale_refeicao == 1 ? 'inline-grid' : 'none')?>" >
				<tr>
					<td colspan="2" class="formTabela"  style="padding-left: 20px;">
						<label for="valeRefeicaoPorc">Percentual descontado do funcionário referente ao vale-refeição:</label> 
						<input name="valeRefeicaoPorc" id="valeRefeicaoPorc" type="text" size=4 maxlength="8" value="<?php echo str_replace(".",",",$vale_refeicao_porc);?>" />%
					</td>
				</tr>
			</table>		
		</td>
	  </tr> 	  	  
	  <tr>
		<td colspan="3" align="left" valign="middle" class="formTabela"><br />
			<label for="vt">Utilizará vale transporte?</label>&nbsp;&nbsp;
			<input name="vt" type="radio" value="1" <?php echo checked( '1', $valeTransporte ); ?> alt="Vale transporte" /> Sim&nbsp;&nbsp;
			<input name="vt" type="radio" value="0" <?php echo checked( '0', $valeTransporte ); echo checked( '', $valeTransporte ); ?> /> Não
		</td>
	  </tr>    
      </table>
      </div>


	  <div id="linha_vt" style="display:<?=$valeTransporte == '1' ? 'block' : 'none'?>">
          <div class="tituloAzul" style="margin-bottom:10px;">Vale Transporte</div>
          <div style="margin-bottom:20px;">
          <table>
          <tr>
            <td align="left" valign="middle" class="formTabela" colspan="2" style="margin-bottom: 10px;">
            <strong>Ida</strong></td>
          </tr>
          <tr>
            <td colspan="2">
                <table border="0" width="659" cellpadding="5">
                    <tr>
                        <th width="80"><label for="selTipoIda">Tipo</label></th>
                        <th width="200"><label for="txtLinhaIda">Nome ou nº da linha</label></th>
                        <th width="235"><label for="txtEmpresaIda">Empresa Transportadora</label></th>
                        <th width="94"><label for="txtTarifaIda">Tarifa (R$)</label></th>
                    </tr>	
    <?
        $sql = "SELECT tipo, trajeto, tarifa, linha, empresa FROM dados_transporte_funcionario WHERE idFuncionario = " . $idFuncionario . " AND trajeto = 'ida' ORDER BY idTransporte";
        $resultado = mysql_query($sql)
        or die (mysql_error());
    
        $possui_vt = false;
    
        if(mysql_num_rows($resultado) > 0){
    
            $possui_vt = true;
    
            // TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
            $ida = array();
            
            while($dados=mysql_fetch_array($resultado)){
        
                array_push($ida,array('tipo'=>$dados["tipo"], 'linha'=>$dados["linha"], 'empresa'=>$dados["empresa"], 'tarifa'=>$dados["tarifa"]));
    
            }
            
        }
        for($i=0; $i < 3; $i++){
    ?>
                    <tr>
                        <td class="td_calendario" align="left">
                            <select name="selTipoIda[]">
                                <option value="" <?php echo selected( '', $ida[$i]['tipo']); ?>></option>
                                <option value="ônibus" <?php echo selected( 'ônibus', $ida[$i]['tipo']); ?>>ônibus</option>
                                <option value="metrô" <?php echo selected( 'metrô', $ida[$i]['tipo']); ?>>metrô</option>
                                <option value="trem" <?php echo selected( 'trem', $ida[$i]['tipo']); ?>>trem</option>
                            </select>
                        </td>
                        <td class="td_calendario" align="left"><input type="text" name="txtLinhaIda[]" maxlength="100" style="width:98%;" value="<?=$ida[$i]['linha']?>" /></td>
                        <td class="td_calendario" align="left"><input type="text" name="txtEmpresaIda[]" maxlength="100" style="width:98%;" value="<?=$ida[$i]['empresa']?>" /></td>
                        <td class="td_calendario" align="left"><input type="text" name="txtTarifaIda[]" style="width:98%;text-align:right;" maxlength="5" class="current" value="<?=str_replace('.',',',$ida[$i]['tarifa'])?>" /></td>
                    </tr>	
    
        <? } ?>
                    <tr>
                        <td class="td_calendario" colspan="3">&nbsp;</td>
                        <td class="td_calendario" align="right" id="total_tabela_ida"></td>
                    </tr>
    
                    </table>
                </td>
              </tr>
    
          <tr>
            <td align="left" valign="middle" class="formTabela" colspan="2" style="margin-bottom: 10px;"><br />
            <strong>Volta</strong></td>
          </tr>
          <tr>
            <td colspan="2">
                <table border="0" width="659" cellpadding="5">
                    <tr>
                        <th width="80"><label for="selTipoVolta">Tipo</label></th>
                        <th width="200"><label for="txtLinhaVolta">Nome ou nº da linha</label></th>
                        <th width="235"><label for="txtEmpresaVolta">Empresa Transportadora</label></th>
                        <th width="94"><label for="txtTarifaVolta">Tarifa (R$)</label></th>
                    </tr>	
    <?
        $sql = "SELECT tipo, trajeto, tarifa, linha, empresa FROM dados_transporte_funcionario WHERE idFuncionario = " . $idFuncionario . " AND trajeto = 'volta' ORDER BY idTransporte";
        $resultado = mysql_query($sql)
        or die (mysql_error());
        if(mysql_num_rows($resultado) > 0){
    
            $possui_vt = true;
    
            // TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
            $volta = array();
            
            while($dados=mysql_fetch_array($resultado)){
        
                array_push($volta,array('tipo'=>$dados["tipo"], 'linha'=>$dados["linha"], 'empresa'=>$dados["empresa"], 'tarifa'=>$dados["tarifa"]));
    
            }
            
        }
        for($i=0; $i < 3; $i++){
    ?>
                    <tr>
                        <td class="td_calendario" align="left">
                            <select name="selTipoVolta[]">
                                <option value="" <?php echo selected( '', $volta[$i]['tipo']); ?>></option>
                                <option value="ônibus" <?php echo selected( 'ônibus', $volta[$i]['tipo']); ?>>ônibus</option>
                                <option value="metrô" <?php echo selected( 'metrô', $volta[$i]['tipo']); ?>>metrô</option>
                                <option value="trem" <?php echo selected( 'trem', $volta[$i]['tipo']); ?>>trem</option>
                            </select>
                        </td>
                        <td class="td_calendario" align="left"><input type="text" name="txtLinhaVolta[]" maxlength="100" style="width:98%;" value="<?=$volta[$i]['linha']?>" /></td>
                        <td class="td_calendario" align="left"><input type="text" name="txtEmpresaVolta[]" maxlength="100" style="width:98%;" value="<?=$volta[$i]['empresa']?>" /></td>
                        <td class="td_calendario" align="left"><input type="text" name="txtTarifaVolta[]" style="width:98%;text-align:right;" maxlength="5" class="current" value="<?=str_replace('.',',',$volta[$i]['tarifa'])?>" /></td>
                    </tr>	
    
        <? } ?>
                    <tr>
                        <td class="td_calendario" colspan="3">&nbsp;</td>
                        <td class="td_calendario" align="right" id="total_tabela_volta"></td>
                    </tr>
    
                </table>
            </td>
          </tr>
          </table>
          </div>
      </div>
      

	  <div class="tituloAzul" style="margin:0;">Dependentes</div>
	  <div style="margin:0;width:660px; text-align:right"><a href="#" id="btAbreCadastroDependente">Cadastrar novo Dependente</a></div> <? //meus_dados_dependentes_funcionario.php?act=new ?>

      <div style="margin-bottom: 30px;" id="tabela_dependentes">

      </div>

		<div id="cadastro_dependente" style="margin-bottom: 20px;display:none;">
        	<div style="margin-bottom:10px;"><strong>Cadastro de dependente</strong></div>
            <table border="0" width="660" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
              <tr>
                <td width="70" align="right" valign="middle" class="formTabela"><label for="radSexoDependente">Sexo:</label></td>
                <td class="formTabela">
                  <label style="margin-right:15px"><input type="radio" name="radSexoDependente" id="radSexo1" value="Masculino" alt="Sexo do dependente" onClick="vinculoMasculino()" /> Masculino</label>
                  <label><input type="radio" name="radSexoDependente" id="radSexo2" value="Feminino" onClick="vinculoFeminino()" /> Feminino</label>
                </td>
              </tr>
             <tr>
               <td align="right" valign="middle" class="formTabela"><label for="txtNomeDependente">Nome:</label></td>
               <td class="formTabela" width="300"><input name="txtNomeDependente" type="text" id="txtNomeDependente" style="width:300px" maxlength="200" alt="Nome do dependente" /> </td>
             </tr>
              <tr>
                <td align="right" valign="middle" class="formTabela"><label for="selVinculoDependente">Vínculo:</label></td>
                <td class="formTabela"><select name="selVinculoDependente" id="selVinculoDependente" alt="Vínculo do dependente">
                  <option value="">Selecione...</option>
                    <option id="op1V" value="filha">Filha</option>    
                    <option id="op2V" value="cônjuge">Cônjuge</option>
                    <option id="op3V" value="genitora">Genitora</option>
                </select></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="formTabela"><label for="txtCPFDependente">CPF:</label></td>
                <td class="formTabela"><input name="txtCPFDependente" type="text" id="txtCPFDependente" style="width:100px" alt="CPF do dependente" class="campoCPF" /> 
                  <span style="font-size:10px; display: none"> (somente números)</span></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="formTabela"><label for="txtRGDependente">RG:</label></td>
                <td class="formTabela"><input name="txtRGDependente" type="text" id="txtRGDependente" style="width:80px" maxlength="12" alt="RG do dependente" class="campoRG" />
                &nbsp;&nbsp;&nbsp;<label for="txtDataEmissaoDependente">Data de Emissão:</label> <input name="txtDataEmissaoDependente" id="txtDataEmissaoDependente" type="text" style="width:80px" alt="Data de emissão do RG do dependente" maxlength="10" class="campoData" />
                &nbsp;&nbsp;&nbsp;<label for="txtOrgaoExpedidorDependente">Orgão Expedidor:</label> <input name="txtOrgaoExpedidorDependente" type="text" id="txtOrgaoExpedidorDependente" style="width:70px" alt="Órgão Expedidor do RG do dependente" maxlength="50" />
                </td>
              </tr>
               <tr>
                <td align="right" valign="middle" class="formTabela"><label for="txtDataNascimentoDependente">Data de Nascimento:</label></td>
                <td class="formTabela"><input name="txtDataNascimentoDependente" type="text" id="txtDataNascimentoDependente" style="width:80px" alt="Data de nascimento do dependente" maxlength="10" class="campoData" /> 
                  <span style="font-size:10px; display:none">DD/MM/AAAA</span></td>
              </tr>
                <tr>
                <td align="right" valign="middle" class="formTabela"><label for="txtEnderecoDependente">Endere&ccedil;o:</label></td>
                <td class="formTabela"><input name="txtEnderecoDependente" id="txtEnderecoDependente" type="text" style="width:300px" maxlength="200" alt="Endereço do dependente" /></td>
              </tr>
                <tr>
                  <td align="right" valign="middle" class="formTabela"><label for="txtBairroDependente">Bairro:</label></td>
                  <td class="formTabela"><input name="txtBairroDependente" id="txtBairroDependente" type="text" style="width:300px" maxlength="200" alt="Bairro do dependente" /></td>
                </tr>
              <tr>
                <td align="right" valign="middle" class="formTabela"><label for="txtCEPDependente">CEP:</label></td>
                <td class="formTabela"><input name="txtCEPDependente" type="text" id="txtCEPDependente" style="width:80px" alt="CEP do dependente" class="campoCEP" /> <span style="font-size:10px; display:none">(somente números)</span></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="formTabela"><label for="selEstadoDependente">Estado:</label></td>
                <td class="formTabela"><select name="selEstadoDependente" id="selEstadoDependente" alt="Estado do dependente">
				  <option value="" selected="selected">Selecione...</option>
			<?
					foreach($arrEstados as $dadosEstado){
						echo "<option value=\"".$dadosEstado['id'].";".$dadosEstado['sigla']."\">".$dadosEstado['sigla']."</option>";
					}
            ?>
                 </select></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="formTabela"><label for="txtCidadeDependente">Cidade:</label></td>
                <td class="formTabela"><!--<input name="txtCidadeDependente" type="text" id="txtCidadeDependente" style="width:300px" alt="Cidade do dependente" maxlength="200" />-->
                    <select name="txtCidadeDependente" id="txtCidadeDependente" style="width:300px" class="comboM">
                    <option value=""></option>
                    </select>
                </td>
              </tr>
              <tr>
                <td width="70" align="right" valign="middle" class="formTabela"><label for="radInvalidezDependente">Invalidez:</label></td>
                <td class="formTabela">
                  <label style="margin-right:15px"><input type="radio" name="radInvalidezDependente" value="0" alt="Invalidez do dependente" checked="checked" /> Não</label>
                  <label><input type="radio" name="radInvalidezDependente" value="1" /> Sim</label>
                </td>
              </tr>
<!--              <tr>
                <td width="70" align="right" valign="middle" class="formTabela"><label for="radTempoInvalidezDependente">Tipo de invalidez:</label></td>
                <td class="formTabela">
                  <label style="margin-right:15px"><input type="radio" name="radTempoInvalidezDependente" value="temporária" alt="Tipo de Invalidez do dependente" checked="checked" /> Temporária</label>
                  <label><input type="radio" name="radTempoInvalidezDependente" value="permanente" /> Permanente</label>
                </td>
              </tr>
              <tr>
                <td width="70" align="right" valign="middle" class="formTabela"><label for="txtCIDInvalidezDependente">CID:</label></td>
                <td class="formTabela">
                  <input name="txtCIDInvalidezDependente" type="text" id="txtCIDInvalidezDependente" style="width:20px" alt="CID da invalidez" maxlength="10" />
                </td>
              </tr>
-->
              <tr>
                <td align="right" valign="middle" class="formTabela">&nbsp;</td>
                <td class="formTabela">
                    <input type="button" value="Salvar" id="btSalvarDependente" />
                    <input type="button" value="Cancelar" id="btCancelarDependente" />
                </td>
                </tr>
            </table>
          </div>


	  <div class="tituloAzul" style="margin-bottom:10px;">Dados bancários do funcionário</div>
      <div style="margin-bottom:20px;">
      <table>
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="id_banco">Banco:</label></td>
		<td class="formTabela"><select name="id_banco" id="id_banco" alt="Banco">
		  <option value="" <?php echo selected( '', $id_banco ); ?> ></option>
		  <?
		  $rsBancos = mysql_query("SELECT * FROM lista_bancos WHERE codigo <> 0 ORDER BY ordem,nome");
		  while($lista_bancos = mysql_fetch_array($rsBancos)){ 
			$codigo_banco = str_pad($lista_bancos['codigo'],3,"0",STR_PAD_LEFT);
		  ?>
			  <option value="<?=$lista_bancos['id']?>" <?=selected( $lista_bancos['id'], $id_banco ); ?> ><?="[" . $codigo_banco . "]&nbsp;&nbsp;&nbsp;&nbsp;" . $lista_bancos['nome']?></option>
		  <? } ?>
		  </select>
		</td>
	  </tr>
	
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="id_banco">Tipo Conta:</td>
		<td class="formTabela"><select name="tipo_conta" id="tipo_conta" alt=" Tipo da conta">
		  <option value="" <?php echo selected( '', $tipo_conta ); ?> ></option>
		  <option value="corrente" <?php echo selected( 'corrente', $tipo_conta ); ?> >Conta Corrente</option>
		  <option value="poupança" <?php echo selected( 'poupança', $tipo_conta ); ?> >Conta Poupança</option>
		  </select>
		</td>
	  </tr>
	
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="agencia">Nº Agência:</label></td>
		<td class="formTabela"><input name="agencia" id="agencia" type="text" style="width:80px" value="<?=$num_agencia?>" maxlength="10" alt="Número da agência" class="inteiro" /> 
		  <span style="font-size:10px; display: none">(somente números)</span></td>
	  </tr>
	
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="dig_agencia">Dígito Agência:</label></td>
		<td class="formTabela"><input name="dig_agencia" id="dig_agencia" type="text" style="width:30px" value="<?=$dig_agencia?>" maxlength="2" alt="Dígito da agência" class="inteiro" /> 
		  <span style="font-size:10px; display: none">(somente números)</span></td>
	  </tr>
	
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="conta">Número Conta:</label></td>
		<td class="formTabela"><input name="conta" id="conta" type="text" style="width:80px" value="<?=$num_conta?>" maxlength="10" alt="Número da conta" class="inteiro" /> 
		  <span style="font-size:10px; display: none">(somente números)</span></td>
	  </tr>
	
	  <tr>
		<td align="right" valign="middle" class="formTabela"><label for="dig_conta">Dígito Conta:</label></td>
		<td class="formTabela"><input name="dig_conta" id="dig_conta" type="text" style="width:30px" value="<?=$dig_conta?>" maxlength="2" alt="Dígito da conta" class="inteiro" />
		  <span style="font-size:10px; display: none">(somente números)</span></td>
	  </tr>
	
	  <tr>
		<td align="left" valign="middle" class="formTabela" colspan="2" style="margin-bottom: 10px;"><br /></td>
	  </tr>
	</table>
    </div>
    
    
	<div style="width:660px;text-align:center">

			<input type="button" value="<?=$status=='1' ? 'Salvar Alterações' : 'Salvar'?>" id="btSalvar" onClick="formSubmit('form_funcionario')" />
		<? if($_GET["editar"]){ ?>
			<input type="button" value="Voltar" id="btCancelar" />
		<? 	}else{ 
						if(!$mostrar_cadastrar_novo){
			?>
			<input type="button" value="Cancelar" id="btCancelar" />
		<? 
						}
					} ?>
	<?    
	//	 <input name="Botão" type="button" value="Salvar Alterações" onClick="formSubmit('form_funcionario')" />
	//  php if ($totalSocio != 1) { 
	//	 <input type="button" value="Excluir sócio" onClick="if (confirm('Você tem certeza que deseja excluir este Sócio?'))location.href='meus_dados_funcionario_excluir.php?socio=$idSocio';" />
	//  php } 
	?>
   		<a target="_blank" style="background-color: #024a68; border: 0 none; border-radius: 10px; color: #fff; cursor: pointer; font-family: Varela Round,sans-serif; margin: 0 auto; min-height: 25px; padding: 5px 20px; text-align: center; text-decoration: none;" href="gerar_declaracao_valeTransporte.php?funcionarioId=<?php echo $idFuncionario;?>" onclick="window.open('gerar_contrato_funcionario.php?funcionarioId=<?php echo $idFuncionario.'&empresaId='.$id;?>');">Gera contrato e declaração de vt</a>
  
    </div>
	</form>
    

      <div style="margin-top:20px;">
	  <table>
	  <tr>
		<td align="left" valign="middle" class="formTabela" colspan="2" style=""><br />
		<div class="tituloVermelho">Declarações</div></td>
	  </tr>
	  <tr>
		<td align="left" valign="middle" class="formTabela" colspan="2" style="">
        	<ul class="declaracoes_dependentes" style="margin:0;">
                <li><a href="declaracao_dependencia_download.php?id=<?=$idFuncionario?>" target="_blank">Declaração de dependentes</a></li>
                <li><a href="declaracao_salario_familia_download.php?id=<?=$idFuncionario?>" target="_blank">Declaração Salário Família</a></li>
			</ul>
            <ul style="margin:0;">
            	<li><a href="solicitacao_vale_transporte_download.php?id=<?=$idFuncionario?>" target="_blank">Solicitação de vale transporte</a></li>
            </ul>
		</td>
	  </tr>
      </table>
      </div>


	<br />
	
	<?
} else { // ao invés de mostrar o formulário mostrar a tabela
	?>
	</div>
	
	<div style="text-align: right; margin-bottom:10px; width:75%"><a href="meus_dados_funcionario.php?act=new">Cadastrar novo Funcionário</a></div>
			<table width="75%" cellpadding="5">
				<tr>
					<th width="80">Açâo</th>
					<th>Nome</th>
					<th>Sexo</th>
					<th>CPF</th>
					<th>Status</th>
				</tr>
	<?
		if(mysql_num_rows($resultado) > 0){
			$esconde_botao_excluir = false;
			if(mysql_num_rows($resultado) == 1){ $esconde_botao_excluir = true;}
			
			mysql_data_seek($resultado,0); // posicionando ponteiro do recordset no primeiro registro
			
			// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
			while($linha=mysql_fetch_array($resultado)){
				$id 	  = $linha["idFuncionario"];
				$nome 	  = $linha["nome"];
				$sexo 	  = $linha["sexo"];
				$cpf 	  = $linha["cpf"];
				$demissao = $linha["data_demissao"];
					
				$status   = (!empty($demissao) && $demissao <= date('Y-m-d') ? 2 : 1 );
								
				if($status == 2){
					$esconde_botao_excluir = true;
				}else{
					$esconde_botao_excluir = false;
				}
	?>
				<tr>
					<td class="td_calendario" align="center"><?=!$esconde_botao_excluir ? '<a href="#" onClick="ExcluirFuncionario(\''.$id.'\')"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>' : ''?>
					<a href="meus_dados_funcionario.php?editar=<?=$id?>"><img src="images/edit.png" width="24" height="23" border="0" title="Editar" /></a></td>
					<td class="td_calendario"><?=$nome?></td>
					<td class="td_calendario"><?=$sexo?></td>
					<td class="td_calendario"><?=$cpf?></td>
					<td class="td_calendario"><?
						if($status == 2){
							echo "<font style=\"color:#C00;\">Demitido</font>";
						}else{
							echo "Ativo";
						}	
					?></td>
				</tr>
	<?	
			}
	
		}else{
	?>
			<tr>
				<td class="td_calendario">&nbsp;</td>        
				<td class="td_calendario">&nbsp;</td>        
				<td class="td_calendario">&nbsp;</td>        
				<td class="td_calendario">&nbsp;</td>        
				<td class="td_calendario">&nbsp;</td>        
			</tr>
	<?		
		}
	}

//}else{


//	</div>
//    <div style="text-align: left; margin-bottom:10px; width:75%">Não foi encontrado nenhum funcionário!</div>
//    <div style="text-align: left; margin-bottom:10px; width:75%"><a href="meus_dados_funcionario.php?act=new">Cadastrar novo Funcionário</a></div>


//} // FIM DO IF QUE TRATA O ERRO
?>

		</table>

	<div style="cleat:both;height:50px;"></div>

</div>

<script>
$(document).ready(function(e) {
	
	
	$("input[name^='txtTarifaIda']").bind('blur',function(){
		$('#total_tabela_ida').html(mostraTotal('txtTarifaIda'));
	});

	$("input[name^='txtTarifaVolta']").bind('blur',function(){
		$('#total_tabela_volta').html(mostraTotal('txtTarifaVolta'));
	});
	
	$('#total_tabela_ida').html(mostraTotal('txtTarifaIda'));
	
	$('#total_tabela_volta').html(mostraTotal('txtTarifaVolta'));


	$('#selEstado').bind('change',function(){
		var arrDadosEstado = $('#selEstado').val().split(';');
		var idUF = arrDadosEstado[0];
		$.getJSON('consultas.php?opcao=cidades&valor='+idUF, function (dados){ 
			if (dados.length > 0){
				var option = '<option></option>';
				$.each(dados, function(i, obj){
					option += '<option value="'+obj.cidade+'">'+obj.cidade+'</option>';
					})
				$('#txtCidade').html(option).show();
			}
		});
	});
	
	$('#selEstadoDependente').bind('change',function(){
		var arrDadosEstado = $('#selEstadoDependente').val().split(';');
		var idUF = arrDadosEstado[0];
		$.getJSON('consultas.php?opcao=cidades&valor='+idUF, function (dados){ 
			if (dados.length > 0){
				var option = '<option></option>';
				$.each(dados, function(i, obj){
					option += '<option value="'+obj.cidade+'">'+obj.cidade+'</option>';
					})
				$('#txtCidadeDependente').html(option).show();
			}
		});
	});
});

function mostraTotal(campoTarifa){
	var campo = $("input[name^='"+campoTarifa+"']");
	var total_loop = (campo.size());
	var total = 0.0;
	for(var i=0; i < total_loop; i++){
		var valor = campo.eq(i).val().replace(",",".");
		if(valor > 0){
			total += parseFloat(valor);
		}
	}
	var tot = total.toFixed(2);
	return tot.replace('.',',');
}

function ExcluirFuncionario(funcionarioId){
	
	var method = 'CheckPagtoFuncionario';
	var empresaId = '<?php echo $_SESSION["id_empresaSecao"];?>';
	var flag = false;
	
	$.ajax({
		url:'meus_dados_funcionario_ajax.php',
		type: 'post',
		data: {method:method, empresaId:empresaId, funcionarioId:funcionarioId},
		dataType: 'json',
		success: function(ret) {

			// Se não existir dados permite a exclusão.
			if(ret['status'] == 'sem dados'){
				flag = true;
			}
		},
		error: function(xhr) {
			console.log('Erro retorno'+xhr);
		},
		beforeSend: function() {},					
		complete: function() {
			
			if(flag){
				
				var status2 = confirm('Você tem certeza que deseja excluir este Funcionário?\rTodas as informações relacionadas a este funcionário serão removidas.');
				if (status2) {
					location.href= 'meus_dados_funcionario_excluir.php?func='+funcionarioId;	 
				}

			} else {
				alert('Não é possível excluir este funcionário, pois há pagamentos registrados para ele. Se não estiver mais na empresa, entre no cadastro dele e informe a data de demissão. Dessa forma ele será desativado.');
			}
		}
	});	
}

<?
if(($_SESSION["aviso"] != '')){
	echo "alert('" . $_SESSION["aviso"] . "');";
	$_SESSION["aviso"] = "";
}
?>
	
function checkDemissao(){
	
	var method = 'checkDemissao';
	var empresaId = '<?php echo $_SESSION["id_empresaSecao"];?>';
	var funcionarioId = '<?php echo $idFuncionario;?>'; 
	var data = $('#txtDataDemissao').val();
	var status = false;
		
	$.ajax({
		url:'meus_dados_funcionario_ajax.php',
		type: 'post',
		data: {method:method, empresaId:empresaId, funcionarioId:funcionarioId, data: data},
		dataType: 'json',
		success: function(ret) {
			
			console.log(ret);
			
			if(ret['status'] == 'ExisteDados'){
				status = true;
			}
		},
		error: function(xhr) {
			console.log('Erro retorno');
		},
		beforeSend: function() {},					
		complete: function() {
			
			if(!status){
				$('#form_funcionario').submit();
			} else {
				alert('Não é possivel salvar os dados, pois existe pagamento na data de demissão informada.');
			}
			
		}
	});
}	
</script>

<?php include 'rodape.php' ?>