<?php include 'header_restrita.php'?>

<?

// CHECANDO SE HÁ RESPONSAVEL
$validar_responsavel = false;

$checa_responsavel = mysql_fetch_array(mysql_query("SELECT sum(case when responsavel = 1 then 1 else 0 end) responsavel , sum(case when responsavel = 0 then 1 else 0 end) normal  FROM dados_do_responsavel WHERE id = '" . $_SESSION["id_empresaSecao"] . "'"));

$n_responsavel = $checa_responsavel['responsavel'];
$n_normal = $checa_responsavel['normal'];

//echo "SELECT sum(case when responsavel = 1 then 1 else 0 end) responsavel , sum(case when responsavel = 0 then 1 else 0 end) normal  FROM dados_do_responsavel WHERE id = '" . $_SESSION["id_empresaSecao"] . "'";

if($n_responsavel == 0 && (($n_responsavel + $n_normal) >= 1)){
	$validar_responsavel = true;
}

$acao = 'inserir';

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};

?>

<script type="text/javascript">

<?
// quebrando a url
//$arrUrl_origem = explode('/',$_SERVER['HTTP_REFERER']);
$arrUrl_origem = explode('/',$_SERVER['PHP_SELF']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];
	
//if(!isset($_SESSION['paginaOrigem'])){
//if($_SESSION['paginaOrigem'] != 'pro_labore.php' || isset($_GET['editar'])){
//	$_SESSION['paginaOrigem'] = $pagina_origem;
//}

if(!isset($_SESSION['paginaOrigemSocios']) || $_SESSION['paginaOrigemSocios'] == ''){
	$_SESSION['paginaOrigemSocios'] = $pagina_origem;
}


echo " var PaginaOrigem = '" . $_SESSION['paginaOrigemSocios'] . "';";
?>


jQuery(document).ready(function() {		

	$('#btReativar').css('display','none');

//	$('#btCancelar').click(function(){
//		location.href = 'meus_dados_socio.php'
//	});

	$('#btCancelar').click(function(){
		location.href = PaginaOrigem;
	});
	
	$('#btReativar').click(function(){
		location.href = 'meus_dados_socio_reativar.php?socio=' + $('#hidSocioID').val();
	});
	
	$('#chkDocExterior').bind('click',function(e){
		if($(this).attr('checked')){
			$('#labelRG').text('RNE:');
			$('#campoRG').css('display','none');
			$('#campoRNE').css('display','block');
			$('#txtRNE').focus();
			$('#txtRG').val('');
		}else{
			$('#labelRG').text('RG:');
			$('#campoRG').css('display','block');
			$('#campoRNE').css('display','none');
			$('#txtRG').focus();
			$('#txtRNE').val('');
		}
	});

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
	
	
	$('#btSalvar').click(function(){
		var erro = false;
		if($('#CNPJ').val()!='' && $('#hidSocioLog').val() == ''){
			$.ajax({
				url:'meus_dados_socio_checa.php?id=' + $('#hidSocioID').val() + '&idLogin=' + $('#hidSocioLog').val() + '&cpf=' + $('#txtCPF').val(),
				type: 'get',
				async: 	false,
				cache: false,
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe um sócio cadastrado com este CPF!');  
						erro = true;
						return false;
					}
				}
			});
		}
		if(erro == false){
			
			formSubmit();	
			
		}						
		
	});
	
	
});

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

function checaProLabore(){
	if (document.getElementById('cheRetiraProLabore').checked) {
		document.getElementById('txtProLabore').disabled = true;
	} else {
		document.getElementById('txtProLabore').disabled = false;
	}
}
	
	


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

function ValidaNit(nit){
	exp = /\d{11}/
	if(!exp.test(nit))
	return false; 
}

/*function validElement(idElement, msg){

	var Element= document.getElementById(idElement);

	if(Element.value == "" || Element.value == false ){

		//window.alert(msg+' '+Element.alt+' do primeiro Sócio Responsável.');
		window.alert(msg+' '+Element.alt);
		return false;
	}
}*/


 var msg1 = 'É necessário preencher o campo';
 var msg2 = 'É necessário selecionar ';
 var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
 var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';

			
	function formSubmit(){  
		var responsavel = false;

		if( validElement('txtNome', msg1) == false){return false}
		if(!$('input[name="radSexo"]').is(':checked')){
			alert('Selecione o Sexo!');
			$('input[name="radSexo"]').focus();
			return false;
		}
		if( validElement('txtDataAdmissao', msg1) == false){return false}
		if( validElement('txtNacionalidade', msg1) == false){return false}
		if( validElement('txtNaturalidade', msg1) == false){return false}

		if(document.getElementById('txtNacionalidade').value.toLowerCase() == 'brasileiro' || document.getElementById('txtNacionalidade').value.toLowerCase() == 'brasileira') {
			if(document.getElementById('rad'+'Sexo1').checked) {
				document.getElementById('txtNacionalidade').value = 'Brasileiro';
			} else if(document.getElementById('rad'+'Sexo2').checked) {
				document.getElementById('txtNacionalidade').value = 'Brasileira';
			}
		}
		
		if( validElement('selEstadoCivil', msg1) == false){return false}
		if( validElement('txtNumeroDep', msg1) == false){return false}
		if( validElement('txtCPF', msg1) == false){return false}
		if($('input[name="chkDocExterior"]').is(':checked')){
			if(validElement('txtRNE', msg1) == false){return false}
		}else{
			if( validElement('txtRG', msg1) == false){return false}
			if( validElement('txtDataEmissao', msg1) == false){return false}
			if( validElement('txtOrgaoExpedidor', msg1) == false){return false}
		}
		if( validElement('txtDataNascimento', msg1) == false){return false}

		if( validElement('txtEndereco', msg1) == false){return false}
		if( validElement('txtBairro', msg1) == false){return false}
		if( validElement('txtCEP', msg1) == false){return false}
		if( validElement('selEstado', msg2) == false){return false}
		if( validElement('txtCidade', msg2) == false){return false}
		
		//if( validElement('txtCodigoCBO', msg1) == false){return false}

		<?
		if($validar_responsavel == true){
		?>
			if(!$('input[name="radResponsavel"]:checked').length){
				if(confirm('Não foi selecionado um sócio responsável para a sua empresa. Deseja marcar este sócio como sendo o Responsável?')){
					$('input[name="radResponsavel"]').attr('checked', true);
				}
			}
		<?	
		}
		?>

		document.getElementById('form_responsavel').submit()	;		
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

<div class="principal">
<div class="minHeight">

<!--BALLOM CBO -->
<div style="width:310px; position:absolute; display:none;" id="cbo" class="bubble_left box_visualizacao x_visualizacao">

	<div style="padding:20px;">
        <strong>CBO</strong> - Código Brasileiro de Ocupações - é o número que identifica seu cargo dentro da empresa. Veja alguns números:<br />
        <br />
        <strong>1210-10</strong> Diretor geral<br />
        <strong>1231-05</strong> Diretor Admiinstrativo<br />
        <strong>1231-10</strong> Diretor administrativo e financeiro.<br />
        <br />
        ou consulte a <a href="http://www.mtecbo.gov.br/cbosite/pages/home.jsf" target="_blank">lista completa</a>.
    </div>
    
</div>
<!--FIM DO BALLOOM CBO -->

<!--BALLOM NIT -->
<div style="width:310px; position:absolute; display:none;" id="nit" class="bubble_left box_visualizacao x_visualizacao">

	<div style="padding:20px;">
        <strong>NIT</strong> - Número de Inscrição do Trabalhador - é o seu registro na Previdência Social. Sócios de empresa são registrados como <strong>CI - Contribuinte Individual</strong>, mas podem usar também o número do <strong>PIS</strong>, caso já tenham trabalhado com funcionários.<br />
        <br />
      Se você não sabe qual o seu número de CI/PIS, veja <a href="https://www.contadoramigo.com.br/pis.php">aqui</a> como descobrir. </div>
    
</div>
<!--FIM DO BALLOOM NIT -->
    
<div class="titulo" style="margin-bottom:20px">Meus Dados</div>

<?
$mostrar_cadastrar_novo = false;

$textoAcao = "- Incluir";

$arrEstados = array();
$sql = "SELECT * FROM estados ORDER BY sigla";
$result = mysql_query($sql) or die(mysql_error());
while($estados = mysql_fetch_array($result)){
	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
}
if($_GET['act'] != 'new'){// CHECANDO SE NÃO É A INCLUSAO DE UM NOVO SOCIO

	// CHECANDO QUANTIDADE DE SÓCIOS
	$sql = "SELECT idSocio, nome, id, cpf, status, responsavel FROM dados_do_responsavel WHERE id = '" . $_SESSION["id_empresaSecao"] . "' ORDER BY nome";
	$resultado = mysql_query($sql)
	or die (mysql_error());

	// if(mysql_num_rows($resultado) == 1){
	// 	$socio = mysql_fetch_array($resultado);
	// 	$idSocio = $socio['idSocio'];
	// 	$mostrar_cadastrar_novo = true;
	// }
	
	if($_GET["editar"]){
	
		$idSocio = $_GET["editar"];
	
	}
	
	
	
	if($idSocio){
		
		$textoAcao = "- Editar";
		$acao = 'editar';
		// ALTERAÇÂO DE AUTONOMOS
		$sql = "SELECT * FROM dados_do_responsavel WHERE idSocio='" . $idSocio . "' LIMIT 0, 1";
		$consulta = mysql_query($sql)
		or die (mysql_error());
		
		$linha=mysql_fetch_array($consulta);
	
		$id 						= $linha["id"];
		$idSocio					= $linha["idSocio"];
		$nome 						= $linha["nome"];
		$sexo 						= $linha["sexo"];
		$data_admissao				= $linha["data_admissao"];
		$nacionalidade 				= $linha["nacionalidade"];
		$naturalidade				= $linha["naturalidade"];
		$estado_civil				= $linha["estado_civil"];
		$dependentes				= $linha["dependentes"];
		$pensao						= $linha["pensao"];
		$perc_pensao				= $linha["perc_pensao"];
		$profissao 					= $linha["profissao"];
		$cpf						= $linha["cpf"];
		$rg							= $linha["rg"];
		if(strlen($rg)>0){
			$rg = preg_replace("/\W/","",$rg);
		}

		$rne						= $linha["rne"];

		$data_de_emissao			= $linha["data_de_emissao"];
		$orgao_expeditor			= $linha["orgao_expeditor"];	
		$data_de_nascimento 		= $linha["data_de_nascimento"];
		$endereco					= $linha["endereco"];
		$bairro						= $linha["bairro"];
		$cep						= $linha["cep"];	
		$cidade						= $linha["cidade"];	
		$estado						= $linha["estado"];
		$pref_telefone				= $linha["pref_telefone"];
		$telefone					= $linha["telefone"];
		$email						= $linha["email_socio"];
		$codigo_cbo					= $linha["codigo_cbo"];
		$funcao						= $linha["funcao"];
		$pro_labore					= $linha["pro_labore"];
		$retira_pro_labore			= $linha["retira_pro_labore"];
		$nit						= $linha["nit"];
		$responsavel				= $linha["responsavel"];
		$status						= $linha["status"];
	
		$tipo_conta					= $linha["tipo_conta"];
		$id_banco					= $linha["id_banco"];
		$num_agencia				= $linha["agencia"];
		$dig_agencia				= $linha["dig_agencia"];
		$num_conta					= $linha["conta"];
		$dig_conta					= $linha["dig_conta"];

		$tipo						= $linha["tipo"];

		if($status == 2){ // se o socio estiver como ex-socio deve desabilitar os botoes e campos do form
?>
				<script>
				$(document).ready(function(){
					$("#form_responsavel").find("input").not('#btCancelar').attr("disabled","disable");
					$("#form_responsavel").find("select").attr("disabled","disable");
					$("#btSalvar").css('display','none');
					$("#btReativar").css('display','block').attr("disabled","");
					//.prop("disabled",true);
				});
				</script>
<?
		}

	}
}
?>

<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Sócios

<?

if($_GET['act'] == 'new' || $_GET["editar"] || mysql_num_rows($resultado) == 0){ 

?>
<?=$textoAcao?></div>
 
<form method="post" name="form_responsavel" id="form_responsavel" action="meus_dados_socio_gravar.php" >
	O sócio desempenha tarefas: 
	<select name="tipo">
		<option value="">selecione</option>
		<option value="Exclusivamente Administrativas" <?=$tipo == 'Exclusivamente Administrativas' ? 'selected' : ''?> >Exclusivamente Administrativas</option>
		<option value="Administrativas e Operacionais" <?=$tipo == 'Administrativas e Operacionais' ? 'selected' : ''?>>Administrativas e Operacionais</option>
		<option value="Exclusivamente Operacionais" <?=$tipo == 'Exclusivamente Operacionais' ? 'selected' : ''?>>Exclusivamente Operacionais</option>
	</select>
	<br><br>	
  <input type="hidden" name="acao" value="<?=$acao?>" />
  
  <table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
 <tr>
   <td align="right" valign="middle" class="formTabela">Nome:</td>
   <td class="formTabela" width="300"><input name="txtNome" type="text" id="txtNome" style="width:300px" value="<?=$nome?>" maxlength="200" alt="Nome" /> </td>
 </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Sexo:</td>
    <td class="formTabela">
      <label style="margin-right:15px"><input type="radio" name="radSexo" id="radSexo1" value="Masculino" onclick="estadoCivilMasculino()" <?php echo checked( 'Masculino', $sexo ); ?> /> Masculino</label>
      <label><input type="radio" name="radSexo" id="radSexo2" value="Feminino" onclick="estadoCivilFeminino()" <?php echo checked( 'Feminino', $sexo ); ?> /> Feminino</label>
    </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Data de Admissão:</td>
    <td class="formTabela"><input name="txtDataAdmissao" type="text" id="txtDataAdmissao" style="width:80px" value="<?=$data_admissao?>" maxlength="10" alt="Data de Admissão" class="campoData" />
      <span style="font-size:10px; display: none">DD/MM/AAAA</span></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Nacionalidade:</td>
    <td class="formTabela"><input name="txtNacionalidade" type="text" id="txtNacionalidade" style="width:300px" value="<?=$nacionalidade?>" maxlength="200" alt="Nacionalidade" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Naturalidade:</td>
    <td class="formTabela"><input name="txtNaturalidade" type="text" id="txtNaturalidade" style="width:300px" value="<?=$naturalidade?>" maxlength="200" alt="Naturalidade" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Estado Civil:</td>
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
    <td align="right" valign="middle" class="formTabela">Nº de dependentes:</td>
    <td class="formTabela"><input name="txtNumeroDep" type="text" id="txtNumeroDep" style="width:30px" value="<?=$dependentes?>" maxlength="200" alt="Nº de dependentes" />
      <span class="formTabela" style="color: #000; padding: 2px 0"> <span style="font-size:10px">(Somente os declarados como dependentes no IR)</span> </span></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Paga pensão alimentícia?</td>
    <td class="formTabela">
    	<input name="pensao" id="pensao" type="checkbox" value="1" <?=($pensao == 1 ? 'checked' : '')?> />&nbsp;
        Qual o percentual? <input name="PercentPensao" id="PercentPensao" type="text" size=4 maxlength="2" value="<?=$perc_pensao?>" />%
    </td>
  </tr>  
  <tr>
    <td align="right" valign="middle" class="formTabela">Profissão:</td>
    <td class="formTabela"><input name="txtProfissao" type="text" id="txtProfissao" style="width:300px" value="<?=$profissao?>" maxlength="200" alt="Nome" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">CPF:</td>
    <td class="formTabela"><input name="txtCPF" type="text" id="txtCPF" style="width:100px" value="<?=str_replace(array("/","-","."),"",$cpf)?>" alt="CPF" class="campoCPF" /> 
      <span style="font-size:10px; display: none"> (somente números)</span></td>
  </tr>
  <tr>
    <td align="right" valign="middle" id="labelRG" class="formTabela"><?=(strlen($rne) > 1 ? "RNE:" : "RG:")?></td>
    <td class="formTabela">
    <div id="campoRG" style="float: left; margin-right:10px; display:<?=(strlen($rne) > 1 ? " none" : "block")?>;">
    	<input name="txtRG" type="text" id="txtRG" style="width:100px" value="<?=$rg?>" maxlength="14" class="_campoRG" alt="RG" />
    </div>
    <div id="campoRNE" style="float: left; margin-right:10px; display:<?=(strlen($rne) > 1 ? " block" : "none")?>;">
	    <input name="txtRNE" type="text" id="txtRNE" style="width:80px" value="<?=$rne?>" alt="RNE" />
		</div>
    <div style="float: left; padding-top: 4px; margin-right:10px;">
    <input type="checkbox" name="chkDocExterior" id="chkDocExterior" value="1" <?=(strlen($rne) > 1 ? " checked" : "")?> style="float:left;margin-right:2px;" /> Sou estrangeiro
    </div>
    </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Data de Emissão:</td>
    <td class="formTabela"><input name="txtDataEmissao" id="txtDataEmissao" type="text" style="width:80px" value="<?=$data_de_emissao?>" maxlength="10" class="campoData" alt="Data de Emissão" />
      <span style="font-size:10px; display: none">DD/MM/AAAA</span></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Orgão Emissor:</td>
    <td class="formTabela"><input name="txtOrgaoExpedidor" type="text" id="txtOrgaoExpedidor" style="width:70px" value="<?=$orgao_expeditor?>" maxlength="250" alt="Orgão Emissor" /></td>
  </tr>
   <tr>
    <td align="right" valign="middle" class="formTabela">Data de Nascimento:</td>
    <td class="formTabela"><input name="txtDataNascimento" type="text" id="txtDataNascimento" style="width:80px" value="<?=$data_de_nascimento?>" maxlength="10" class="campoData" alt="Data de Nascimento" /> 
      <span style="font-size:10px; display:none">DD/MM/AAAA</span></td>
  </tr>
    <tr>
    <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
    <td class="formTabela"><input name="txtEndereco" id="txtEndereco" type="text" style="width:300px" value="<?=$endereco?>" maxlength="250" alt="Endereço" /></td>
  </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Bairro:</td>
      <td class="formTabela"><input name="txtBairro" id="txtBairro" type="text" style="width:300px" value="<?=$bairro?>" maxlength="200" alt="Bairro" /></td>
    </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">CEP:</td>
    <td class="formTabela"><input name="txtCEP" type="text" id="txtCEP" style="width:80px" value="<?=str_replace(array("/","-","."),"",$cep)?>"  class="campoCEP" alt="CEP" /> <span style="font-size:10px; display:none">(somente números)</span></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Estado:</td>
    <td class="formTabela"><select name="selEstado" id="selEstado" alt="Estado">
            <option value="" <?php echo selected( '',$estado ); ?>></option>
          <?
		  if($_GET['act'] == 'new'){
			  $estado = 'SP';
		  }
		  
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
    <td align="right" valign="middle" class="formTabela">Cidade:</td>
    <td class="formTabela"><!--<input name="txtCidade" type="text" id="txtCidade" style="width:300px" value="<?=$cidade?>" maxlength="200" />-->
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
    <td align="right" valign="middle" class="formTabela">Telefone:</td>
    <td valign="middle" class="formTabela">
      <div style="float:left; margin-right: 3px;"><input name="txtPrefixoTelefone" type="text" id="txtPrefixoTelefone" style="width:30px" value="<?=$pref_telefone?>" maxlength="2" /></div>
      <div style="float:left"><input name="txtTelefone" type="text" id="txtTelefone" style="width:75px" value="<?=$telefone?>" maxlength="9" /></div></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">E-mail:</td>
    <td valign="middle" class="formTabela">
		<div style="float:left">
			<input name="txtEmail" type="text" id="txtEmail" style="width:200px" value="<?=$email?>" />
		</div>
	</td>
  </tr>  
  <tr>
    <td align="right" valign="middle" class="formTabela">Código CBO:</td>
    <td class="formTabela"><input name="txtCodigoCBO" type="text" id="txtCodigoCBO" style="width:72px; float:left; margin-right:3px" value="<?=str_replace(array("/","-","."),"",$codigo_cbo)?>" class="campoCBO" alt="Código CBO" /> 
    <div style="float:left; margin-right:5px; margin-top:5px">
      <img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="cbo" />
    </div>
    <div style="float:left; margin-right:5px; margin-top:5px"><span style="font-size:10px; display: none">(somente números)</span></div></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Função na empresa:</td>
    <td class="formTabela"><input name="txtFuncao" type="text" id="txtFuncao" maxlength="50" style="width:300px; float:left; margin-right:3px" value="<?=$funcao?>" /></td>
  </tr>
  
<!--  
  <tr>
    <td align="right" valign="middle" class="formTabela">Pró-labore:</td>
    <td class="formTabela"><input name="txtProLabore" type="text" id="txtProLabore" style="width:150px" value="<?=number_format($pro_labore,2,",",".")?>" maxlength="200" <?php if($retira_pro_labore == "não") { echo 'disabled="disabled"'; } ?> /> <label><input type="checkbox" name="cheRetiraProLabore" id="cheRetiraProLabore" onclick="checaProLabore"  <?php echo checked( 'não', $retira_pro_labore ); ?> /> 
    Não retira pró-labore</label></td>
  </tr>
-->
  <tr>
  <td align="right" valign="middle" class="formTabela">NIT ou PIS: </td>
  <td class="formTabela"><input name="txtNit" type="text" id="txtNit" style="width:100px; float:left; margin-right:3px" value="<?=str_replace(array("/","-","."),"",$nit)?>"  class="campoNIT" /> 
  <div style="float:left; margin-right:5px; margin-top:5px">
      <img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="nit" />
  </div>
  <div style="float:left; margin-right:5px; margin-top:5px"><span style="font-size:10px; display: none">(somente números)</span></div></td>
  </tr>


  <?php 
  	//INICIO ALTERAÇÂO 04/05/2016 - SELECIONAR APENAS UM SOCIO RESPONSÁVEL - ARQUIVOS: meus_dados_socio.php e meus_dados_socio_gravar.php

  	#MAL - Apenas um sócio é responsável: 	Se for primeiro socio inserido, vai como socio responsável
 	#										Se for outro sócio inserido
  	#										Se selecioado socio responsável, desativa o outro socio responsavel e seleciona o novo
  

  	$quantidade_socios = mysql_query("SELECT * FROM dados_do_responsavel WHERE id = '" . $_SESSION["id_empresaSecao"] . "' ");
	$objeto=mysql_fetch_array($quantidade_socios);

	$num_rows = mysql_num_rows($quantidade_socios);

	$acao_socio = '';

	if( $num_rows < 1 ){
		$acao_socio = 'primeiro';
	}
	else if( $num_rows >= 1 ){
		$acao_socio = 'outro';
	}

  ?>

  <?php if( $acao_socio == "primeiro" ){ ?>

 	<tr>
		<td align="right" valign="middle" class="formTabela">&nbsp;</td>
		<td class="formTabela">
			<label style="margin-right:15px"><input type="checkbox" name="" value="1" disabled="disabled" checked/>
				<input type="hidden" name="radResponsavel" value="1">
			Selecionado como sócio responsável</label>
		</td>
	</tr>
  <?php } else if( $acao_socio == "outro" ){ ?>


	<?php if( $responsavel == 1 ){ ?>

	<tr>
		<td align="right" valign="middle" class="formTabela">&nbsp;</td>
		<td class="formTabela">
			<label style="margin-right:15px"><input type="checkbox" name="" value="1" disabled="disabled" checked/>
				<input type="hidden" name="radResponsavel" value="1">
			Selecionado como sócio responsável</label>
		</td>
	</tr>
<!-- Checkbox desabilidato  -->
	<?php } else{ ?>
	<tr>
		<td align="right" valign="middle" class="formTabela">&nbsp;</td>
		<td class="formTabela">
			<label style="margin-right:15px"><input type="checkbox" name="radResponsavel" id="radResponsavelInfo" value="1"
			<?php 
			if($responsavel == 1){
				echo "checked";
			}
			?>
			/>
			Selecionar como sócio responsável</label>
		</td>
	</tr>
		<?php } ?>
	<?php } ?>
	<?php //FIM ALTERAÇÕES 04/05/2016  ?>

  <tr>
    <td align="left" valign="middle" class="formTabela" colspan="2"><br />
    <strong>Dados Bancários</strong></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Banco:</td>
    <td class="formTabela"><select name="id_banco" id="id_banco" alt="">
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
    <td align="right" valign="middle" class="formTabela">Tipo Conta:</td>
    <td class="formTabela"><select name="tipo_conta" id="tipo_conta" alt="">
      <option value="" <?php echo selected( '', $tipo_conta ); ?> ></option>
      <option value="corrente" <?php echo selected( 'corrente', $tipo_conta ); ?> >Conta Corrente</option>
      <option value="poupança" <?php echo selected( 'poupança', $tipo_conta ); ?> >Conta Poupança</option>
      </select>
    </td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="formTabela">Nº Agência:</td>
    <td class="formTabela"><input name="agencia" id="agencia" type="text" style="width:80px" value="<?=$num_agencia?>" maxlength="9" alt="" class="inteiro" /> <input name="dig_agencia" id="dig_agencia" type="text" style="width:30px" value="<?=$dig_agencia?>" maxlength="2" alt="" class="inteiro" /> 
    </td>
  </tr>


  <tr>
    <td align="right" valign="middle" class="formTabela">Número Conta:</td>
    <td class="formTabela"><input name="conta" id="conta" type="text" style="width:80px" value="<?=$num_conta?>" maxlength="9" alt="" class="inteiro" /> <input name="dig_conta" id="dig_conta" type="text" style="width:30px" value="<?=$dig_conta?>" maxlength="2" alt="" class="inteiro" />
	</td>
  </tr>

  <tr>
    <td colspan="2" valign="middle" class="formTabela">
      <input type="hidden" name="hidSocioID" id="hidSocioID" value="<?=$idSocio?>" />
      <input type="hidden" name="hidSocioLog" id="hidSocioLog" value="<?=$_SESSION["id_empresaSecao"]?>" />
      <input type="hidden" name="hidSocioResponsavelID" id="hidSocioResponsavelID" value="" />
            
      &nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">&nbsp;</td>
    <td class="formTabela">
		<input type="button" value="Salvar<?=($_GET['act'] != 'new' ? ' Alterações' : '')?>" id="btSalvar" style="margin-right:5px;float:left" />
		<input type="button" value="Reativar Sócio" id="btReativar" style="margin-right:5px;float:left" />
    	<input type="button" value="<?=isset($_GET["editar"]) ? "Voltar" : "Cancelar"?>" id="btCancelar" />
    </td>
	</tr>
</table>
</form>
<br />

	<?
  if($mostrar_cadastrar_novo){
  ?>
  	<div style="text-align: left; margin-bottom:10px; width:75%"><a href="meus_dados_socio.php?act=new">Cadastrar novo Sócio</a></div>
	<?	
  }
  




} else {
	// LISTAGEM
	
	$_SESSION['paginaOrigemSocios'] = $pagina_origem;
	
	
	
	
?>
</div>

<div style="text-align: right; margin-bottom:10px; width:75%"><a href="meus_dados_socio.php?act=new">Cadastrar novo Sócio</a></div>
		<table width="75%" cellpadding="5">
        	<tr>
            	<th width="80">Ação</th>
            	<th>Nome</th>
            	<th>CPF</th>
            	<th>Status</th>
            </tr>
<?

	if(mysql_num_rows($resultado) > 0){
		
		$esconde_botao_excluir = false;
		if(mysql_num_rows($resultado) == 1){ $esconde_botao_excluir = true;}
		
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$id 	= $linha["idSocio"];
			$nome 	= $linha["nome"];
			$cpf 	= $linha["cpf"];
			$responsavel 	= $linha["responsavel"];
			$status 	= $linha["status"];
			
			if($status == 2){
				$esconde_botao_excluir = true;
			}else{
				$esconde_botao_excluir = false;
			}
?>
            <tr>
                <td class="td_calendario" align="center">
								<?=!$esconde_botao_excluir ? '<a href="#" onClick="if (confirm(\'Você tem certeza que deseja excluir este Sócio?\'))location.href=\'meus_dados_socio_excluir.php?socio='.$id.'\';" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>' : ''?>
                <a href="meus_dados_socio.php?editar=<?=$id?>" title="Editar"><i class="fa fa-pencil-square-o iconesAzul iconesGrd"></i></a>
                </td>
                <td class="td_calendario"><?=$nome?></td>
                <td class="td_calendario"><?=$cpf?></td>
                <td class="td_calendario"><?
                if($responsavel == 1){
									echo "Sócio Responsável";
								}else{
									if($status == 2){
										echo "<font style=\"color:#C00;\">Ex-Sócio</font>";
									}else{
										echo "Sócio";
									}
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
        </tr>
<?		
	}
}
?>

		</table>

</div>
</div>

<script>
<?
if(($_SESSION["aviso"] != '')){
	echo "alert('" . $_SESSION["aviso"] . "');";
	$_SESSION["aviso"] = "";
}


?>
</script>

<?php include 'rodape.php' ?>


