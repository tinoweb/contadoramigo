<?php 
session_start();
$_SESSION["id_userSecao"]						= $_SESSION["id_userSecaoMultiplo"];
//$_SESSION["id_empresaSecao"] == '';
//unset($_SESSION["id_empresaSecao"]);


//if($_SESSION["id_userSecaoMultiplo"] == 1581){
//			echo var_dump($_SESSION);
//			exit;
//}

?>

<?php include 'header_restrita.php' ?>

<script type="text/javascript">

$(document).ready(function() {		


	$.mostraResultado = function(texto, qualDiv){
		qualDiv.html(texto);
	}
	

	$.consultaBancoAjax = function(URL, qualDiv, oCampo){
//		alert(URL);
			$.ajax({
				url: URL,
				type: 'get',
				dataType:"json",
				cache: false,
				async: true,
				beforeSend: function(){
				},
				success: function(retorno){
					if(!retorno.status){
						alert(retorno.mensagem)
						$.mostraResultado('', qualDiv);
						oCampo.val('').focus();
					}else{
						$.mostraResultado(retorno.mensagem, qualDiv);
					}
				}
			});
	};

	function formSubmit(){   

		if( validElement('txtRazaoSocial', msg1) == false){return false}
		if( validElement('txtCNPJ', msg1) == false){return false}
		if(document.getElementById('txtCNPJ').value.length != 18){
			alert('Digite o CNPJ corretamente.'); 
			return false;
		}
		if($('#atividadePrincipal').html() == '' && $('#txtCNAE_Principal').val() != '') {
			alert("Digite um CNAE válido na atividade principal.");
			return false;
		}
		
		// conta numero de campos do hideen count da linha 373
		//
		var count = parseInt($('#count').val()) - 1;
		if (count >= 0) {
			for(i=1;i<=count;i++) {
				if($('#atividade'+i).html() == '' && $('#txtCodigoCNAE'+i).val() != '') {
					alert("Digite um CNAE válido no "+i+"º campo das atividades secundárias.");
					return false;
				}
			}
		}
		if( validElement('txtEndereco', msg1) == false){return false}
		if( validElement('txtBairro', msg1) == false){return false}
		if( validElement('txtCEP', msg1) == false){return false}

		if( validElement('txtCidade', msg1) == false){return false}
		var Cidade = document.getElementById('txtCidade');
		if(Cidade.value.toLowerCase() == 'são paulo' || Cidade.value.toLowerCase() == 'sao paulo') {
			Cidade.value = 'São Paulo';
		}
		var Estado = document.getElementById('selEstado');
					if(Estado.selectedIndex == ""){
							window.alert(msg2+'o Estado.');
							return false;
					}
//			if(Estado.value != 'SP') {
//				window.alert(msg5);
//				return false;
//			}
					var RamoAtividade = document.getElementById('selRamoAtividade');
					if(RamoAtividade.selectedIndex == ""){
							window.alert(msg2+'Ramo de Atividade de sua empresa.');
							return false;
					}
					if(RamoAtividade.value == "Comércio" || RamoAtividade.value == "Indústria"){
							window.alert(msg3);
							return false;
					}
/*
		var countPrefeitura = parseInt(document.getElementById('countPrefeitura').value) - 1;
		if (countPrefeitura >= 2) {
			for(i=1;i<=countPrefeitura;i++) {
				if(document.getElementById('pesquisaCampoPrefeitura'+i).value == 'erro' && document.getElementById('txtCodigoAtividadePrefeitura'+i).value != '') {
					alert("Digite Código de Serviço  valido no "+i+"º campo.");
					return false;
				}
			}
		}
*/			
		var RegimeTributacao = document.getElementById('selRegimeTributacao');
					if(RegimeTributacao.value == "Lucro Presumido"){
							window.alert(msg4);
							return false;
					}
		if(document.getElementById('selInscritaComo').value != 'Sociedade Simples'){
						if(document.getElementById('txtRegistroNire').value != ""){
/*
				if(document.getElementById('txtRegistroNire').value.length < 11){
					alert('Digite o Nire corretamente.'); 
					return false;
				}

				if (ValidaNire(document.getElementById('txtRegistroNire').value) == false){
					alert('Digite o Nire somente com números.'); 
					return false;
				}
*/
			}
		}

		if(document.getElementById('txtDataCriacao').value != ""){
			if (ValidaDataCriacao(document.getElementById('txtDataCriacao').value) == false){
				alert('Digite a data de criação no formato DD/MM/AAAA'); 
				return false;
			}
		}
		document.getElementById('form_empresa').submit();			
}

	$('#btCancelar').click(function(){
		location.href = 'meus_dados_empresa.php';
	});

	$('#btSalvar').click(function(){
		var erro = false;
		if($('#txtCNPJ').val()!='' && $('#hidID').val() != ''){
			$.ajax({
				url:'meus_dados_checa_cnpj.php?id=' + $('#hidID').val() + '&cnpj=' + $('#txtCNPJ').val(),
				type: 'get',
				cache: false,
				async: true,
				beforeSend: function(){
				
				},
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe uma empresa cadastrada com este CNPJ!');
						$('#txtCNPJ').focus();
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


	$('#txtCNPJ').blur(function(){
		
		var erro = false;
		if($(this).val()!='' && $('#hidID').val() != ''){
			$.ajax({
				url:'meus_dados_checa_cnpj.php?id=' + $('#hidID').val() + '&cnpj=' + $(this).val(),
				type: 'get',
				cache: false,
				async: true,
				beforeSend: function(){
				},
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe uma empresa cadastrada com este CNPJ!'); 
						$('#txtCNPJ').focus();
					}
				}
			});
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


	$('#txtCNAE_Principal, input[id^="txtCodigoCNAE"]').change(function(){
		var 
			$campo 	= $(this)
			, $div		= $campo.attr('div')
			, $campoCheck		= $('#' + $campo.attr('check'))
			, $idEmpresa = $('#hidID').val();			
		;
		if($campo.val() != ''){
			var URL = 'meus_dados_empresa_consulta_cnae.php?acao=blur&codigo=' + $campo.val() + '&campoCheck=' + $campoCheck.val() + '&idEmpresa=' + $idEmpresa
			$.consultaBancoAjax(URL, $('#'+$div), $campo);
		}
		
	});



});


 var msg1 = 'É necessário preencher o campo';
 var msg2 = 'É necessário selecionar ';
 var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
 var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';
// var msg5 = 'Empresas de outros estados ou cidades podem usar o Contador Amigo, porém não terão suporte referente à emissão de notas fiscais e para pagamento da TFE/TFA, cujos valores são determinados pelas respectivas prefeituras. Geralmente estes impostos chegam pelo correio e são pagos apenas 1 vez por ano.';

function consultaBanco22(qualPagina, qualDiv)
{
  var xmlHttp2 = getXMLHttp();
  
  xmlHttp2.onreadystatechange = function()
  {
    if(xmlHttp2.readyState == 4)
    {
      HandleResponse(xmlHttp2.responseText, qualDiv);
    }
  }

  xmlHttp2.open("GET", qualPagina, true); 
  xmlHttp2.send(null);
}

function HandleResponse(response, qualDiv)
{
  document.getElementById(qualDiv).value = response;
}

//function consultaCNPJ(){
//	if(document.getElementById('txtCNPJ').value != '') {
//		consultaBancoAjax('meus_dados_checa_cnpj.php?id=' + document.getElementById('hidID').value + '&cnpj=' + document.getElementById('txtCNPJ').value, 'divCNPJ');
//
//	}
//}

function valida_cnpj(cnpj) {
	exp = /\d{14}/
	if(!exp.test(cnpj))
	return false;
} 

function ValidaCep(cep){
	exp = /\d{8}/
	if(!exp.test(cep))
	return false; 
}

function ValidaNire(nire){
	exp = /\d{11}/
	if(!exp.test(nire))
	return false; 
}

function ValidaDataCriacao(dataCriacao){
	exp = /\d{2}\/\d{2}\/\d{4}/
	if(!exp.test(dataCriacao))
	return false; 
}
			

 
 function addJQuery(){
		var orig = $('#content');
		var count = parseInt($('#count').val());
/*
        	<input name="txtCodigoCNAE<?=$campo?>" id="txtCodigoCNAE<?=$campo?>" type="text" style="width:84px; margin-top:3px;" value="<?=$linha2["cnae"]?>" class="campoCNAE" div="atividade<?=$campo?>" check="hddCodigoCNAE<?=$campo?>">
			    <input type="hidden" name="hddCodigoCNAE<?=$campo?>" id="hddCodigoCNAE<?=$campo?>" value="<?=$linha2["cnae"];?>">
        </div>
        <div id="atividade<?=$campo?>" style="float:left; margin-top:5px"><?=$linha2['denominacao']?></div>
	*/		
		
		
		orig.append("<div id=\"item"+count+"\"><div style=\"float:left; margin-right:5px\"><input name=\"txtCodigoCNAE"+count+"\" id=\"txtCodigoCNAE"+count+"\" type=\"text\" style=\"width:84px; margin-top:3px;\" class=\"campoCNAE\" div=\"atividade"+count+"\" check=\"hddCodigoCNAE"+count+"\"><input type=\"hidden\" name=\"hddCodigoCNAE"+count+"\" id=\"hddCodigoCNAE"+count+"\" value=\"\"></div> <div id=\"atividade"+count+"\" style=\"float:left; margin-top:5px\"></div><div style=\"clear:both\"></div></div>");
		$('#count').val(count+1);
		
		$('.campoCNAE').mask('9999-9/99');
		
	
		$('#txtCodigoCNAE'+count).change(function(){
			var 
				$campo 	= $(this)
				, $div		= $campo.attr('div')
				, $campoCheck		= $('#' + $campo.attr('check'))			
			;
			if($campo.val() != ''){
				var URL = 'meus_dados_empresa_consulta_cnae2.php?acao=blur&codigo=' + $campo.val() + '&campoCheck=' + $campoCheck.val()
				$.consultaBancoAjax(URL, $('#'+$div), $campo);
			}
			
		});
		
		
 }
	function removeJQuery() {
		var count = parseInt($('#count').val());
		if (count > 1) {				
//			var orig = $('#content');
//			var removeDiv = document.getElementById('item'+(count-1));
			$('#item'+(count-1)).remove();	
			$('#count').val(count-1);
		}
	} 
	
	function add() {
		var orig = document.getElementById('content');
		var count = parseInt(document.getElementById('count').value);
		var newDiv = document.createElement('div');
		newDiv.setAttribute("id", "item"+count);
		var newContent = "<div style=\"float:left; margin-right:5px\"><input name=\"txtCodigoCNAE"+count+"\" id=\"txtCodigoCNAE"+count+"\" type=\"text\" style=\"width:84px; margin-top:3px;\" class=\"campoCNAE2\"></div> <div id=\"atividade"+count+"\" style=\"float:left; margin-top:5px\"><input type=\"hidden\" id=\"pesquisaCampo"+count+"\" name=\"pesquisaCampo"+count+"\" value=\"ok\" /></div> <div style=\"clear:both\"> </div>";
		newDiv.innerHTML = newContent;
		orig.appendChild(newDiv);
		document.getElementById('count').value = count+1;
	}	

	function remove() {
		var count = parseInt(document.getElementById('count').value);
		if (count > 1) {				
			var orig = document.getElementById('content');
			var removeDiv = document.getElementById('item'+(count-1));
			orig.removeChild(removeDiv);			
			document.getElementById('count').value = count - 1;
		}
	}
	
	function addPrefeitura() {
		var orig = document.getElementById('contentPrefeitura');
		var count = parseInt(document.getElementById('countPrefeitura').value);
		var newDiv = document.createElement('div');
		newDiv.setAttribute("id", "itemPrefeitura"+count);
		// insere input hidden pesquisa CampoPrefeitura, ca vez que a pessoa clica no botão de adicionar codigo da prefeitura
		var newContent = '<input name="txtCodigoAtividadePrefeitura'+count+'" id="txtCodigoAtividadePrefeitura'+count+'" type="text" style="width:40px; float:left; margin-right:3px; margin-top:3px" value="" maxlength="5" onblur="consultaBancoAjax(\'meus_dados_empresa_consulta_codigo_prefeitura.php?codigo=\'+document.getElementById(\'txtCodigoAtividadePrefeitura'+count+'\').value+\'&campo='+count+'\', \'denomicacaoPrefeitura'+count+'\');" /><div id="denomicacaoPrefeitura'+count+'" style="float:left; margin-top:5px"><input type="hidden" id="pesquisaCampoPrefeitura'+count+'" name="pesquisaCampoPrefeitura'+count+'" value="ok" /></div><div style="clear:both"> </div>';
		newDiv.innerHTML = newContent;
		orig.appendChild(newDiv);
		document.getElementById('countPrefeitura').value = count+1;
	}	
	function removePrefeitura() {
		var count = parseInt(document.getElementById('countPrefeitura').value);
		if (count > 2) {				
			var orig = document.getElementById('contentPrefeitura');
			var removeDiv = document.getElementById('itemPrefeitura'+(count-1));
			orig.removeChild(removeDiv);			
			document.getElementById('countPrefeitura').value = count - 1;
		}
	}

function selecionaRegistro() {
	if(document.getElementById('selInscritaComo').value == 'Sociedade Simples') {
		abreDiv2('divRegistroCartorio');
		fechaDiv('divRegistroNire');
	} else {
		abreDiv2('divRegistroNire');
		fechaDiv('divRegistroCartorio');
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

function reposicionaBallons() {
	var elementTop = 163;
	document.getElementById('ccm').style.marginTop = parseInt(getPosicaoElemento('dicaCCM').top) - elementTop + 'px';
	document.getElementById('cnae').style.marginTop = parseInt(getPosicaoElemento('dicaCNAE').top) - elementTop + 'px';
	document.getElementById('nire').style.marginTop = parseInt(getPosicaoElemento('dicaNire').top) - elementTop + 'px';
	document.getElementById('prefeitura').style.marginTop = parseInt(getPosicaoElemento('dicaPrefeitura').top) - elementTop + 'px';
	document.getElementById('tipo_emp').style.marginTop = parseInt(getPosicaoElemento('dicaTipo_Emp').top) - elementTop + 'px';
}
</script>
<?php
//$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";
//$resultado = mysql_query($sql)
//or die (mysql_error());

//$linha=mysql_fetch_array($resultado);

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};




//$arrEstados = array();
//$sql = "SELECT * FROM estados ORDER BY sigla";
//$result = mysql_query($sql) or die(mysql_error());
//while($estados = mysql_fetch_array($result)){
//	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
//}


?>
<div class="principal">
<div class="minHeight">


<div class="titulo" style="margin-bottom:20px">
<? if($_SESSION['n_empresasVinculadas'] > 0) { ?>
	Bem-vindo
<? } else { ?>
	Faltou um detalhe...
<? } ?>
</div>

<?
$mostrar_cadastrar_novo = false;

$acao = 'inserir';

$textoAcao = "- Incluir";

$arrEstados = array();
$sql = "SELECT * FROM estados ORDER BY sigla";
$result = mysql_query($sql) or die(mysql_error());
while($estados = mysql_fetch_array($result)){
	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
}


if($_GET['act'] != 'new'){// CHECANDO SE NÃO É INCLUSAO 

	// PEGANDO DADOS DA EMPRESA SELECIONADA
	$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_GET["editar"] . "' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$dadosEmpresa = mysql_fetch_array($resultado);
	$idEmpresa = $dadosEmpresa['id'];
	$mostrar_cadastrar_novo = true;
	
	if($idEmpresa){
		
		$textoAcao = "- Editar";
		$acao = 'editar';
	
		$id 																			= $dadosEmpresa["id"];
		$razao_social															= $dadosEmpresa["razao_social"];
		$nome_fantasia 														= $dadosEmpresa["nome_fantasia"];
		$cnpj																			= $dadosEmpresa["cnpj"];
		$inscricao_no_ccm 												= $dadosEmpresa["inscricao_no_ccm"];
		$inscricao_estadual												= $dadosEmpresa["inscricao_estadual"];
		$tipo_endereco														= $dadosEmpresa["tipo_endereco"];
		$endereco																	= $dadosEmpresa["endereco"];
		$numero																		= $dadosEmpresa["numero"];
		$complemento															= $dadosEmpresa["complemento"];
		$bairro 																	= $dadosEmpresa["bairro"];
		$cep																			= $dadosEmpresa["cep"];
		$cidade																		= $dadosEmpresa["cidade"];
//		if(strlen($rg)>0){
//			$rg = preg_replace("/\W/","",$rg);
//		}
		$estado																		= $dadosEmpresa["estado"];
		$pref_telefone														= $dadosEmpresa["pref_telefone"];	
		$telefone 																= $dadosEmpresa["telefone"];
		$ramo_de_atividade												= $dadosEmpresa["ramo_de_atividade"];
		$codigo_de_atividade_prefeitura						= $dadosEmpresa["codigo_de_atividade_prefeitura"];
		$regime_de_tributacao											= $dadosEmpresa["regime_de_tributacao"];	
		$inscrita_como														= $dadosEmpresa["inscrita_como"];	
		$registro_nire														= $dadosEmpresa["registro_nire"];
		$numero_cartorio													= $dadosEmpresa["numero_cartorio"];
		$registro_cartorio												= $dadosEmpresa["registro_cartorio"];



	}
}

	// LISTAGEM
	
	// CHECANDO QUANTIDADE DE SÓCIOS
	//$sql = "SELECT emp.id, razao_social, cnpj, nome_fantasia FROM dados_da_empresa emp INNER JOIN login l ON emp.id = l.id AND l.id <> l.idUsuarioPai WHERE l.idUsuarioPai = '" . $_SESSION["id_userSecaoMultiplo"] . "' AND ativa = 1 ORDER BY razao_social";
	$sql = "SELECT emp.id, razao_social, cnpj, nome_fantasia, ativa, data_desativacao, l.data_inclusao FROM dados_da_empresa emp INNER JOIN login l ON emp.id = l.id  WHERE l.idUsuarioPai = '" . $_SESSION["id_userSecaoMultiplo"] . "' AND ativa = 1 ORDER BY razao_social, l.data_inclusao DESC";
	$resultado = mysql_query($sql)
	or die (mysql_error());

?>

	<div style="float:left; width:350px">
  
    <div style="float:left; margin-top:10px; margin-right:25px">
      <img src="images/boneca.png" width="50" height="197" alt=""/> 
    </div>
  
    <div class="bubble_left_auto" style="width:260px;  margin-top:0px; float:left;"> 
    
        <div style="padding:15px; padding-right:10px; font-size:12px"> 
        
            <div class="saudacao">
              <div>Olá, <?=$_SESSION["nome_assinanteSecao"]?></div>
            </div>
        
            <div>
            	<? if($_SESSION['n_empresasVinculadas'] > 0) { ?>
                Selecione ao lado a empresa a ser gerenciada. Para cadastrar outra, vá em <br><a href="meus_dados_empresa.php">Meus Dados/Cadastro de Empresas</a>.<br />
            	<? } else { ?>
                Antes de iniciar você precisa primeiro <a href="meus_dados_empresa.php?act=new">cadastrar uma empresa</a>.<br />
            	<? } ?>
            </div>
    
      </div> 
      
    </div> 


  </div>
  
  
  <div style="float:right; width:610px">
  
		<h2>
		<? if($_SESSION['n_empresasVinculadas'] > 0) { ?>
	    Qual empresa deseja gerenciar?
    <? } else { ?>
	    Não há empresa cadastrada
    <? } ?>
    </h2>

		<table style="width: 100%;" cellpadding="5">
      <tr>
        <th style="width: 55%;" align="left">Razão social</th>
        <th style="width: 23%;" align="center">CNPJ</th>
        <th style="width: 9%;" align="center">Inclusão</th>
<!--        <th style="width: 4%;" align="center">Status</th>-->
        <th style="width: 4%;" align="center">Entrar</th>
      </tr>
<?

	if(mysql_num_rows($resultado) > 0){
		
		$esconde_botao_excluir = false;
		if(mysql_num_rows($resultado) == 1){ $esconde_botao_excluir = true;}
		
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$id 							= $linha["id"];
			$razao_social			= $linha["razao_social"];
			$cnpj 						= $linha["cnpj"];
			$dtInclusao				= $linha["data_inclusao"];
			$ativa						= $linha["ativa"];
			$data_desativacao	= $linha["data_desativacao"];
			
?>
      <tr>
        <td class="td_calendario"><?=$razao_social?></td>
        <td class="td_calendario" align="center"><?=$cnpj?></td> 
        <td class="td_calendario" align="center"><?=(date('Y',strtotime($dtInclusao)) > 0 ? date('d/m/Y',strtotime($dtInclusao)) : 'NA')?></td>
<!--        <td class="td_calendario" align="center"><?=($ativa == 1 ? '<i class="fa fa-circle iconesVerdes iconesPeq"></i>' : '<i class="fa fa-circle iconesVermelhos iconesPeq"></i>')?></td>-->
        <td class="td_calendario" align="center"><a href="meus_dados_empresa_gerenciar.php?id=<?=$id?>"><i class="fa fa-angle-double-right iconesAzuis iconesMed"></i></a></td>  	
      </tr>
<?	
		}

	}else{
?>
      <tr>
        <td class="td_calendario">&nbsp;</td>        
        <td class="td_calendario">&nbsp;</td>        
        <td class="td_calendario">&nbsp;</td>        
<!--        <td class="td_calendario">&nbsp;</td>        -->
        <td class="td_calendario">&nbsp;</td>        
      </tr>
<?		
	}
?>
		</table>
  
  </div>
  

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