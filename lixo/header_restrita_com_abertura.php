<?php 
//ini_set('session.cookie_secure',true);
// CÓDIGO para fazer o cookie funcionar no IE
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

// SESSION
session_start();

$dominio = "";
$dominio_seguro = "";

// expressão regular para checar o domínio que está acessando do site
preg_match('#^(1[2,9][2,7]).([0]|[168])#',$_SERVER['HTTP_HOST'],$checkDominio);

if(count($checkDominio) <= 0){
//	$dominio = "http://www.contadoramigo.com.br/";
//	$dominio_seguro = "https://contadoramigo.websiteseguro.com/";
	$dominio_seguro = "https://www.contadoramigo.com.br/";
	$dominio = $dominio_seguro;
}

$_SESSION['url'] = ($_SESSION['url'] == "" ? substr($_SERVER['PHP_SELF'],1) . ($_SERVER['QUERY_STRING'] != '' ? '?' . $_SERVER['QUERY_STRING'] : '') : $_SESSION['url']);





if (!isset($_SESSION["id_userSecao"])){
	
	if(!isset($_COOKIE["contadoramigoHTTPS"]) || $_COOKIE["contadoramigoHTTPS"]==""){

		header('Location: '.$dominio.'assinatura_pagina_restrita.php' );
		exit;

	}else{

		header('Location: '.$dominio.'auto_login.php?login&cookie');
		exit;	
	
	}
	
}





if(!(bool)$_SERVER['HTTPS']){
	header('Location: '.$dominio_seguro . str_replace("/","",$_SERVER["REQUEST_URI"]));
	exit;
}


// GET NOME DA PAGINA
$pagina = end(explode("/", $_SERVER['PHP_SELF']));


/*if($pagina != 'meus_dados_empresa.php'){
	if($_SESSION['n_empresasVinculadas'] == 1){
		header('Location: '.$dominio_seguro.'meus_dados_empresa_gerenciar.php?id=' . $_SESSION["id_empresaSecao"] );
		exit;
	}else{
		$_SESSION["id_empresaSecao"]				= "";
	}
}*/


//$painelLogin = "Conectado a: <b>" . substr($_SESSION["nome_userSecao"],0,30) . "</b> | <a  href=\"minha_conta.php\">Dados da conta</a> | <a href=\"".$dominio_seguro."auto_login.php?logout\">Sair</a>";

if($_SESSION["id_empresaSecao"] != '' || isset($_SESSION["id_empresaSecao"])){ // caso já tenha sido selecionada uma empresa, mostrar no topo a informação de logado como

	$painelLogin = "Conectado a: <b>" . substr($_SESSION["nome_userSecao"],0,30) . "</b> | ";

}

// verificando a quantidade de empresas que o usuario tem cadastradas. 
if($_SESSION['n_empresasVinculadas'] > 1){ // se for mais de 1 deve mostrar o link para a página gerenciar empresa

	if($pagina != 'gerenciar_empresa.php'){ // só mostrará o link caso o usuario não esteja na página gerenciar empresa
		$painelLogin .= "<a href=\"gerenciar_empresa.php\">Trocar Empresa</a> | ";
	}
	$painelLogin .= "<a  href=\"minha_conta.php\">Dados da conta</a> | <a href=\"".$dominio_seguro."auto_login.php?logout\">Sair</a>";

} elseif($_SESSION['n_empresasVinculadas'] == 1){ // se for somente 1 empresa, só mostrará os links para dados da conta sair

//	$painelLogin .= "<a href=\"gerenciar_empresa.php\">Trocar Empresa</a> | <a  href=\"minha_conta.php\">Dados da conta</a> | <a href=\"".$dominio_seguro."auto_login.php?logout\">Sair</a>";
	$painelLogin .= "<a  href=\"minha_conta.php\">Dados da conta</a> | <a href=\"".$dominio_seguro."auto_login.php?logout\">Sair</a>";

} else {

	$painelLogin .= "<a href=\"meus_dados_empresa.php?act=new\">Cadastrar Empresa</a> | <a  href=\"minha_conta.php\">Dados da conta</a> | <a href=\"".$dominio_seguro."auto_login.php?logout\">Sair</a>";// se não houver empresa cadastrada, mostrar o link para o cadastro de empresas.

}

$_SESSION["alertaSelecioneEmpresa"] = "Selecione ";
if(
	(
		($pagina != 'gerenciar_empresa.php')
		&& ($pagina != 'minha_conta.php')
		&& ($pagina != 'meus_dados_empresa.php')
		&& ($pagina != 'minha_conta.php')
		&& ($pagina != 'minha_conta_cancela.php')
		&& ($pagina != 'suporte.php')
		&& ($pagina != 'suporte')
		&& ($pagina != 'suporte_visualizar.php')
		&& ($pagina != 'index_restrita.php')
		&& ($pagina != 'procedimentos_iniciais.php')
	) && ($_SESSION["id_empresaSecao"] == '' || !isset($_SESSION["id_empresaSecao"]))
) { 
	header('Location: '.$dominio_seguro.'gerenciar_empresa.php' );
	exit;
}




//if(($_SESSION["id_empresaSecao"] != '' && isset($_SESSION["id_empresaSecao"])) && ($_SESSION['statusEmpresaSelecionada'] == 0)){ 
//	header('Location: '.$dominio_seguro.'gerenciar_empresa.php' );
//	exit;
//}





// CONEXAO
$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
$db = mysql_select_db("contadoramigo");
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');


//CHECK LOGIN
if(($_SESSION['status_userSecao']=='inativo') or ($_SESSION['status_userSecao']=='demoInativo') or ($_SESSION['status_userSecao']=='cancelado')) {
	if((substr($_SERVER['REQUEST_URI'],0,16) == '/minha_conta.php') or (substr($_SERVER['REQUEST_URI'],0,21) == '/minha_conta.php') or (substr($_SERVER['REQUEST_URI'],0,24) == '/minha_conta_cancela.php') or (substr($_SERVER['REQUEST_URI'],0,12) == '/suporte.php') or (substr($_SERVER['REQUEST_URI'],0,8) == '/suporte') or (substr($_SERVER['REQUEST_URI'],0,23) == '/suporte_visualizar.php')) { 
	



	//lista de páginas que o usuário inativo possui acesso		  
	} else {
		header('Location: '.$dominio_seguro.'minha_conta.php' );
		exit;
	}
}

//echo "'" . $_SESSION["id_userSecao"] . "'<BR>";

if($_SESSION["id_PlanoUserSecao"] == 3){
	
}




// GERA VARIÁVEIS DOS INDICES USADOS NO SITE
//$sql = "SELECT * FROM indices";
//$resultado = mysql_query($sql)
//or die (mysql_error());
//$linha=mysql_fetch_array($resultado);

//$Limite_Insencao = $linha['Limite_Insencao'];
//$Salario_Minimo = $linha['Salario_Minimo'];
//$Contribuicao_Minima = $linha['Contribuicao_Minima'];
//$Teto_Previdenciario = $linha['Teto_Previdenciario'];
//$Contribuicao_Maxima = $linha['Contribuicao_Maxima'];
//$Desconto_Ir_Dependentes = $linha['Desconto_Ir_Dependentes'];



// REDIRECIONANDO PARA A PAGINA DE COMPARTILHAMENTO NAS REDES SOCIAIS
//mysql_query("DELETE FROM alertas_usuarios_paginas WHERE id_login = " . $_SESSION["id_userSecao"] . " AND nome_pagina = 'compartilhe' AND status_alerta = 1");
//	if($_SESSION['status_userSecao'] == 'ativo' && $_SERVER['PHP_SELF'] != '/chamada_alerta.php'){
//		
//		$sql_alerta_compartilhe = "SELECT * FROM alertas_usuarios_paginas WHERE id_login = '" . $_SESSION["id_userSecao"] . "' AND nome_pagina = 'compartilhe' AND status_alerta = '1'";
//		if (mysql_num_rows(mysql_query($sql_alerta_compartilhe)) == 0){
//			header('location: chamada_alerta.php');
//			exit;
//		}
//				
//	}

// MENSALIDADE
if($_SESSION["id_userSecao"] != 1581 && $_SESSION["id_userSecaoMultiplo"] != 1581){
	$sql_configuracoes = "SELECT * FROM configuracoes WHERE configuracao = 'mensalidade'";
	$rsConfiguracoes = mysql_fetch_array(mysql_query($sql_configuracoes));
	
	$checkData = (strtotime(date("Y-m-d")) >= strtotime("2015-12-01"));
	
	//if($checkData){
	//	$mensalidade = "59";
	//}else{
		$mensalidade = $rsConfiguracoes['valor'];
	//}

	
}else{
//	echo "'".(bool)($_SERVER['HTTPS'])."'";
	$mensalidade = 1;

}

$mensalidade_unitaria = $mensalidade;

//echo $_SESSION['n_empresasVinculadas'];

if($_SESSION['n_empresasVinculadas'] > 0){
	
	if($_SESSION['n_empresasVinculadas'] <= 5){
		$mensalidade = $mensalidade * $_SESSION['n_empresasVinculadas'];
	} else {
		$mensalidade = $mensalidade * 5 + (($_SESSION['n_empresasVinculadas'] - 5) * 10);
	}
	
}
//$mensalidade = $_SESSION['n_empresasVinculadas'] > 0 ? $mensalidade * $_SESSION['n_empresasVinculadas'] : $mensalidade;

//echo "id_empresaSecao: " . $_SESSION['id_empresaSecao'] . "<BR>";
//echo "n_empresasVinculadas: " . $_SESSION['n_empresasVinculadas'] . "<BR>";
//echo "mensalidade: " . $mensalidade;

// VALOR DO SALARIO MINIMO USADO NO SITE
$sql_sm = "SELECT * FROM salario_minimo WHERE (YEAR(inicio_vigencia) = ".date("Y")." AND (YEAR(fim_vigencia) = ".date("Y")." OR fim_vigencia = 0)) OR fim_vigencia = 0 ORDER BY inicio_vigencia DESC LIMIT 1";
$resultado_sm = mysql_query($sql_sm)
or die (mysql_error());
$linha_sm=mysql_fetch_array($resultado_sm);
$Salario_Minimo = $linha_sm['valor'];
$Contribuicao_Minima = (string)number_format((float)str_replace(',','.',str_replace('.','',$Salario_Minimo)) * 0.11, 2,',','.');

$ano_vigencia = date("Y",strtotime($linha_sm['inicio_vigencia']));

// VALOR DO TETO PREVIDENCIARIO USADO NO SITE
$sql_tp = "SELECT * FROM teto_previdenciario WHERE (YEAR(inicio_vigencia) = ".date("Y")." AND (YEAR(fim_vigencia) = ".date("Y")." OR fim_vigencia = 0)) OR fim_vigencia = 0 ORDER BY inicio_vigencia DESC LIMIT 1";
$resultado_tp = mysql_query($sql_tp)
or die (mysql_error());
$linha_tp=mysql_fetch_array($resultado_tp);
$Teto_Previdenciario = $linha_tp['valor'];
$Contribuicao_Maxima = (string)number_format((float)str_replace(',','.',str_replace('.','',$Teto_Previdenciario)) * 0.11, 2,',','.');

		
		
// FIM DA GERAÇÃO DAS VARIÁVEIS

// GERA VARIÁVEIS DAS TABELAS USADOS NO SITE
$sql2 = "SELECT * FROM tabelas where ValorBruto1 > 0 AND ValorBruto2 > 0 AND ValorBruto3 > 0 AND ValorBruto4 > 0 AND ano_calendario = '".date("Y")."' ORDER BY ano_calendario DESC LIMIT 0,1";
$resultado2 = mysql_query($sql2)
or die (mysql_error());
$linha2=mysql_fetch_array($resultado2);

$Limite_Insencao = (string)number_format($linha2['ValorBruto1'],2,',','.');
$ValorBruto1 = $linha2['ValorBruto1'];
$ValorBruto2 = $linha2['ValorBruto2'];
$ValorBruto3 = $linha2['ValorBruto3'];
$ValorBruto4 = $linha2['ValorBruto4'];
$Aliquota1 = $linha2['Aliquota1'];
$Aliquota2 = $linha2['Aliquota2'];
$Aliquota3 = $linha2['Aliquota3'];
$Aliquota4 = $linha2['Aliquota4'];
$Aliquota5 = $linha2['Aliquota5'];
$Desconto1 = $linha2['Desconto1'];
$Desconto2 = $linha2['Desconto2'];
$Desconto3 = $linha2['Desconto3'];
$Desconto4 = $linha2['Desconto4'];
$Desconto5 = $linha2['Desconto5'];
$Desconto_Ir_Dependentes = $linha2['Desconto_Ir_Dependentes'];
// FIM DA GERAÇÃO DAS VARIÁVEIS

// TRANSFORMA ALGUNS ÍNDICES EM FLOAT PARA USA-LOS EM CÁLCULOS
$Contribuicao_Maxima_n = (float)str_replace(",",".",str_replace(".","",$Contribuicao_Maxima));
$Contribuicao_Minima_n = (float)str_replace(",",".",str_replace(".","",$Contribuicao_Minima));
$Desconto_Ir_Dependentes_n = $Desconto_Ir_Dependentes;//(float)str_replace(",",".",str_replace(".","",$Desconto_Ir_Dependentes));
	
function mesExtenso($mes){

	$arrMonth = array(
		1 => 'Janeiro',
		2 => 'Fevereiro',
		3 => 'Março',
		4 => 'Abril',
		5 => 'Maio',
		6 => 'Junho',
		7 => 'Julho',
		8 => 'Agosto',
		9 => 'Setembro',
		10 => 'Outubro',
		11 => 'Novembro',
		12 => 'Dezembro'
		);
	if(is_numeric($mes)){
		return $arrMonth[(int)$mes];
	}else{
		return 'não localizado';
	}
	
}
	
function trataTxt($var) {

	$var = str_replace("á","a",$var);	
	$var = str_replace("à","a",$var);	
	$var = str_replace("â","a",$var);	
	$var = str_replace("ã","a",$var);	
	$var = str_replace("Á","A",$var);	
	$var = str_replace("À","A",$var);	
	$var = str_replace("Â","A",$var);	
	$var = str_replace("Ã","A",$var);	

	$var = str_replace("é","e",$var);	
	$var = str_replace("è","e",$var);	
	$var = str_replace("ê","e",$var);	
	$var = str_replace("É","E",$var);	
	$var = str_replace("È","E",$var);	
	$var = str_replace("Ê","E",$var);	

	$var = str_replace("í","i",$var);	
	$var = str_replace("ì","i",$var);	
	$var = str_replace("î","i",$var);	
	$var = str_replace("Í","I",$var);	
	$var = str_replace("Ì","I",$var);	
	$var = str_replace("Î","I",$var);	

	$var = str_replace("ó","o",$var);	
	$var = str_replace("ò","o",$var);	
	$var = str_replace("ô","o",$var);	
	$var = str_replace("õ","o",$var);	
	$var = str_replace("Ó","O",$var);	
	$var = str_replace("Ò","O",$var);	
	$var = str_replace("Ô","O",$var);	
	$var = str_replace("Õ","O",$var);	

	$var = str_replace("ú","u",$var);	
	$var = str_replace("ù","u",$var);	
	$var = str_replace("û","u",$var);	
	$var = str_replace("ü","u",$var);	
	$var = str_replace("Ú","U",$var);	
	$var = str_replace("Ù","U",$var);	
	$var = str_replace("Û","U",$var);	
	$var = str_replace("Ü","U",$var);	

	$var = str_replace("ñ","n",$var);	
	$var = str_replace("Ñ","N",$var);	

	$var = str_replace("ç","c",$var);
	$var = str_replace("Ç","C",$var);

	$var = str_replace("&","E",$var);
	
	return $var;
}


//var_dump($_SESSION);


?>
<!doctype html>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contador Amigo - faça você mesmo a contabilidade de sua microempresa</title>

<?php 
if(isset($nome_meta)){
	include 'meta_'.$nome_meta.'.php';
}?>

<link href="estilo.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
<link rel="apple-touch-icon" href="logo_ipad.png" />
<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
<link rel="stylesheet" media="screen" href="estilo/font-awesome.min.css?v"><!--estilo font-awesome-->
<link rel="stylesheet" media="screen" href="jquery.powertip.css"><!--estilo jquery ballon -->
<link rel="stylesheet" media="screen" href="ballon.css?v"><!--estilo ballon CSS -->


<script type="text/javascript" src="./scripts/jquery.min.js"></script>
<script type="text/javascript" src="./scripts/jquery.maskedinput-1.2.2.js"></script>
<!--<script type="text/javascript" src="./scripts/jquery.maskedinput.js"></script>-->
<script type="text/javascript" src="./scripts/jquery.powertip.js"></script><!--plug in jquery ballon -->
<script type="text/javascript" src="./scripts/jquery.flash.js"></script>
<script type="text/javascript" src="./scripts/meusScripts.js"></script>

<!--[if lt IE 9]>
<script type="text/javascript" src="./scripts/html5.js"></script>


<script type="text/javascript">
var htmlshim='abbr,article,aside,audio,canvas,details,figcaption,figure,footer,header,mark,meter,nav,output,progress,section,summary,time,video'.split(',');
var htmlshimtotal=htmlshim.length;
for(var i=0;i<htmlshimtotal;i++) document.createElement(htmlshim[i]);
</script>

<![endif]-->

<script>
<?
// MENSALIDADE
if($_SESSION["id_userSecaoMultiplo"] == 1581){

//	echo 'alert("Cookies Enabled: " + navigator.cookieEnabled);';
//	echo 'alert("'.$_SERVER['REMOTE_ADDR'].'");';

}
?>

	$(document).ready(function(e) {

		if($('.imagemDica').length){
			$('.imagemDica').css('cursor','pointer').bind('click',function(e){
				e.preventDefault();
				if($('#' + $(this).attr('div')).hasClass('bubble_left_auto')){
					$('#' + $(this).attr('div')).removeClass('bubble_left_auto');
				}
				if($('#' + $(this).attr('div')).hasClass('bubble_left_bottom')){
					$('#' + $(this).attr('div')).removeClass('bubble_left_bottom');
				}
//console.log($(this).offset().top - $(document).height());
				var novoTop = 55;
				if(($(document).height() - $(this).offset().top) < $('#' + $(this).attr('div')).height()){
//					if(!$('#' + $(this).attr('div')).hasClass('bubble_left_bottom')){
						$('#' + $(this).attr('div')).removeClass('bubble_left');
						$('#' + $(this).attr('div')).addClass('bubble_left_bottom');
//						$('#' + $(this).attr('div')).removeClass('bubble_left_auto');
//					}
					novoTop = ($('#' + $(this).attr('div')).height() - 56);
				}else{
//					if(!$('#' + $(this).attr('div')).hasClass('bubble_left_auto')){
						$('#' + $(this).attr('div')).removeClass('bubble_left');
						$('#' + $(this).attr('div')).addClass('bubble_left_auto');
//						$('#' + $(this).attr('div')).removeClass('bubble_left_bottom');
//					}
				}

		
				$('#' + $(this).attr('div')).css({
					'top':$(this).offset().top - novoTop
					, 'left':$(this).offset().left + 30
				});


		
			//	alert();
			//	alert('left: ' + $(this).offset().left);
			//	alert('top: ' + $(this).offset().top);
				
				abreDiv($(this).attr('div'));
			});
		}

		
		// botoes menu
		$('#botao_meus_dados').mouseenter(function() { 
			$('#botao_meus_dados').css('z-index','1')
			$('#layer_meus_dados').css('display','block')
			$('#layer_meus_dados').css( 'cursor', 'pointer' );
			} );
		
		
		$('#layer_meus_dados').mouseleave(function() {
			$('#botao_meus_dados').css('z-index','3') 
			$('#layer_meus_dados').css('display','none')			 
			} );
			
		// botoes outros
		$('#botao_outros').mouseenter(function() { 
			$('#botao_outros').css('z-index','1')
			$('#layer_outros').css('display','block')
			$('#layer_outros').css( 'cursor', 'pointer' );
			} );
		
		
		$('#layer_outros').mouseleave(function() {
			$('#botao_outros').css('z-index','3') 
			$('#layer_outros').css('display','none')			 
			} );
			
					
		// botoes pagamentos
		$('#botao_pagamentos').mouseenter(function() { 
			$('#botao_pagamentos').css('z-index','1')
			$('#layer_pagamentos').css('display','block')
			$('#layer_pagamentos').css( 'cursor', 'pointer' );
			} );
		
		
		$('#layer_pagamentos').mouseleave(function() {
			$('#botao_pagamentos').css('z-index','3') 
			$('#layer_pagamentos').css('display','none')			 
			} );		
		
		
		// botoes impostos
		$('#botao_impostos').mouseenter(function() { 
			$('#botao_impostos').css('z-index','1')
			$('#layer_impostos').css('display','block')
			$('#layer_impostos').css( 'cursor', 'pointer' );
			} );
		
		
		$('#layer_impostos').mouseleave(function() {
			$('#botao_impostos').css('z-index','3') 
			$('#layer_impostos').css('display','none')			 
			} );		
		

	
		// botoes livro_caixa
		$('#botao_livro_caixa').mouseenter(function() { 
			$('#botao_livro_caixa').css('z-index','1')
			$('#layer_livro_caixa').css('display','block')
			$('#layer_livro_caixa').css( 'cursor', 'pointer' );
			} );
		
		
		$('#layer_livro_caixa').mouseleave(function() {
			$('#botao_livro_caixa').css('z-index','3') 
			$('#layer_livro_caixa').css('display','none')			 
			} );

		// botoes alteracao
		$('#botao_alteracao').mouseenter(function() { 
			$('#botao_alteracao').css('z-index','1')
			$('#layer_alteracao').css('display','block')
			$('#layer_alteracao').css( 'cursor', 'pointer' );
			} );
		
		
		$('#layer_alteracao').mouseleave(function() {
			$('#botao_alteracao').css('z-index','3') 
			$('#layer_alteracao').css('display','none')			 
			} );
		
		
		$('.campoData').mask('99/99/9999');
		$('.campoDataMesAno').mask('99/9999');
		$('.campoDataAno').mask('9999');
//		$('.campoDDDTelefone').mask('99');
//		$('.campoTelefone').mask('999999999');
		$('.campoCNPJ').mask('99.999.999/9999-99');
		$('.campoCPF').mask('999.999.999-99');
		$('.campoCEP').mask('99999-999');
		$('.campoNIRE').mask('9999999999-9');
		$('.campoCNAE').mask('9999-9/99');
		$('.campoCNAE2').mask('9999-9/99');
		$('.campoNIT').mask('999.99999.99-9');
		$('.campoCBO').mask('9999-99');
		$('.campoCEI').mask('99.999.99999/99');
// inserido pelo vitor o sel_ativi_cnae
		$('.sel_ativi_cnae').mask('9999-9/99');

//		alert($('.campoRG').length);
		if($('.campoRG').length){
			if($('.campoRG').val().length <= 8){
				$('.campoRG').mask("9.999.999-*?*");
			}else{
				$('.campoRG').mask("9?9.999.999-*");
			}
		}
				
		$('.campoCelular').mask("9999-9999?9");
		$(".campoCelular").focusout(function(e) {
		    var phone, element;
			element = $(this);
			element.unmask();
			phone = element.val().replace(/\D/g, '');
			if(phone.length > 9) {
				element.mask("99999-999?9");
			} else {
				element.mask("9999-9999?9");
			}
		}).trigger('focusout');
			
			

		$('.cei').keypress(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);

			if(!parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0){
				return false;
			}else{
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = limpaCaracteres($(this).val()); 
				$(this).val(formataCampo('00.000.00000/00',valor));
			}
		});

		$('.cei').blur(function(e){
			if($(this).val() != ''){
				valor = limpaCaracteres($(this).val());
				if(valor.length < 12){
					alert('Quantidade de caracteres incorreta!');
					$(this).focus();
				}
				$(this).val(formataCampo('00.000.00000/00',valor));
			}
		});
			
		$('.current').focus(function(){
			$(this).select();
		});

		$('.cnpj').keypress(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);

			if(!parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0){
				return false;
			}else{
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = limpaCaracteres($(this).val()); 
				$(this).val(formataCampo('00.000.000/0000-00',valor));
			}
		});
		/*
		$('.cnpj').blur(function(){
			Zeros = '00000000000000';
			valor = limpaCaracteres($(this).val()); 
			if(valor.length < 18 && valor != ''){
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = Zeros.substr(0, (Zeros.length - valor.length)) + valor; 
				$(this).val(formataCampo('00.000.000/0000-00',valor));
			}
		});
		*/
		$('.cnpj').blur(function(e){
			if($(this).val() != ''){
				valor = limpaCaracteres($(this).val());
				if(valor.length < 14){
					alert('Quantidade de caracteres incorreta!');
					$(this).focus();
				}
				$(this).val(formataCampo('00.000.000/0000-00',valor));
			}
		});
		
		$('.cpf').keypress(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);

			if(!parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0){
				return false;
			}else{
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = limpaCaracteres($(this).val()); 
				$(this).val(formataCampo('000.000.000-00',valor));
			}
		});
		/*
		$('.cpf').blur(function(){
			Zeros = '00000000000';
			valor = limpaCaracteres($(this).val()); 
			if(valor.length < 11 && valor != ''){
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = Zeros.substr(0, (Zeros.length - valor.length)) + valor; 
				$(this).val(formataCampo('000.000.000-00',valor));
			}
		});
		*/
		$('.cpf').blur(function(e){
			if($(this).val() != ''){
				valor = limpaCaracteres($(this).val());
				if(valor.length < 11){
					alert('Quantidade de caracteres incorreta!');
					$(this).focus();
				}
				$(this).val(formataCampo('000.000.000-00',valor));
			}
		});

		$('.rg').keypress(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			if(!parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0 && String.fromCharCode(code).toUpperCase() != 'X'){
				return false;
			}else{
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = limpaCaracteres($(this).val());
				$(this).val(formataCampo('00.000.000-0',valor));
			}
		});
		
		$('.rg').blur(function(){
			Zeros = '000000000';
			valor = limpaCaracteres($(this).val()); 
			if(valor.length < 9 && valor != ''){
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = Zeros.substr(0, (Zeros.length - valor.length)) + valor; 

				$(this).val(formataCampo('00.000.000-0',valor));

			}
		});
		
		$('.campoRG').blur(function(){
			valor = limpaCaracteres($(this).val()); 
			if(valor != ''){
				if(valor.length > 8){
					Zeros = '000000000';
					// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
					valor = Zeros.substr(0, (Zeros.length - valor.length)) + valor; 
					$(this).val(formataCampo('00.000.000-0',valor));
				}else{
					Zeros = '00000000';
					// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
					valor = Zeros.substr(0, (Zeros.length - valor.length)) + valor; 
					$(this).val(formataCampo('0.000.000-0',valor));
				}
			}
		});
		
		
		$('.cep').keypress(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			if(!parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0){
				return false;
			}else{
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = limpaCaracteres($(this).val());
				$(this).val(formataCampo('00000-000',valor));
			}
		});

		$('.cep').blur(function(e){
			if($(this).val() != ''){
				valor = limpaCaracteres($(this).val()).substr(0,8);
				if(valor.length < 8){
					alert('Quantidade de caracteres incorreta!');
					$(this).focus();
				}
				$(this).val(formataCampo('00000-000',valor));
			}
		});


//inserido pelo vitor em 20/03/16

		$('.sel_ativi_cnae').keypress(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			if(!parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0){
				return false;
			}else{
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = limpaCaracteres($(this).val());
				$(this).val(formataCampo('0000-0/00',valor));
			}
		});

		$('.sel_ativi_cnae').blur(function(e){
			if($(this).val() != ''){
				valor = limpaCaracteres($(this).val()).substr(0,8);
				if(valor.length < 8){
					alert('Quantidade de caracteres incorreta!');
					$(this).focus();
				}
				$(this).val(formataCampo('0000-0/00',valor));
			}
		});


// fim do inserido pelo vitor

		$('.pis').keypress(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			if(!parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0){
				return false;
			}else{
				// LIMPANDO A CONTEUDO DO CAMPO PARA FICARMOS SOMENTE COM OS NUMEROS E LETRAS
				valor = limpaCaracteres($(this).val());
				$(this).val(formataCampo('000.00000.00.0',valor));
			}
		});
		
		$('.pis').blur(function(e){
			if($(this).val() != ''){
				valor = limpaCaracteres($(this).val());
				if(valor.length < 11){
					alert('Quantidade de caracteres incorreta!');
					$(this).focus();
				}
				$(this).val(formataCampo('000.00000.00.0',valor));
			}
		});

		$('.current').keypress(function(e){
			// PARA ACERTAR O PROBLEMA DE NÃO ACEITAR O keyCode NO FIREFOX
/*			if(e.keyCode){
				var code = e.keyCode;
			}else{
				var code = e.which;
			}*/
			var code = (e.keyCode ? e.keyCode : e.which);

			if(code != 8 && code != 9){
				var tamanho_maximo = 10;
				
				if($(this).attr('maxlength').length){
					tamanho_maximo = $(this).attr('maxlength');
				}

				if($(this).val().length >= tamanho_maximo){
					e.preventDefault();
					return false;
				}
				
				if(!parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0){
					return false;
				}else{
					valor = limpaCaracteres($(this).val());
					
					$(this).val(formataMoeda(valor));
					/*if($(this).val().length >= 5){
						milhar = (valor.substring(0, valor.length - 3));
					}else{
						if($(this).val().length >= 2){
							//alert((valor.substring(valor.length, valor.length - 1)));
							decimal = (valor.substring(valor.length, valor.length - 1));
		//					inteiro = (valor.substring(0, valor.length - 1));
							centena = (valor.substring(0, valor.length - 1));
						}
					}*/
	//				$(this).val(milhar+'.'+centena+','+decimal);
				}
			}
		});

		$('.current').blur(function(e){
			if($(this).val() != ''){
				valor = limpaCaracteres($(this).val());
				$(this).val(formatReal(valor));
			}
		});
	
		$('.inteiro').focus(function(){
			$(this).select();
		});
	
		$('.inteiro').keypress(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			
			
			if(code != 8 && code != 9 && !parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0){
				return false;
			}

			
		});
		
		
		(function($, window) {
	
	
		// FUNÇÃO QUE CRIA O BOTÃO DA CAIXA DE VISUALIZAÇÃO
		  $.fn.botaoXVisualizacao = function(valueBotao) {
			var obj = $(this);
	
			var div_botao = $('<div></div>');
			div_botao.css({
				'position':'absolute'
				, 'top':'10px'
				, 'right':'10px'
				, 'display':'block'
			});
					
			var elementoButton = $('<img src="images/x.png" />');
			elementoButton.css({
				'width':'8px'
				, 'height':'9px'
				, 'border':'0px'
				, 'cursor':'pointer'
			});
			
			div_botao.append(elementoButton);
	
			obj.first('div').prepend(div_botao);
	
			elementoButton.bind('click',function(){
	//			alert($(this).val());
	
				if(obj.find('video').length){
					var video_html5 = obj.find('video');
					video_html5.load();
				}

				if($('.check_caixa_visualizacao').length){
					
					var nome_pagina = location.href.substr(location.href.lastIndexOf("/") + 1,location.href.length);
					
					var checado = $('.check_caixa_visualizacao').attr('checked');
					
					//if(checado){
						$.ajax({
						  url:'marca_alerta_paginas.php'
						  , data: 'id_login=<?=$_SESSION["id_userSecaoMultiplo"]?>&nome_pagina=' + nome_pagina + '&status='+checado
						  , type: 'post'
						  , async: true
						  , cache: false
						  , success: function(retorno){
						  }
						});
					//}
				}
	
				//obj.css('display',(obj.css('display') == 'block' ? 'none' : 'block'));
				if($('.mascara').length){
					$('.mascara').toggle();
				}

				if($('.btReAbrirBox').length){
					$('.btReAbrirBox').toggle();
				}
				
				
				
				obj.toggle();
				
				$('body').css('overflow','auto');
		
			});
		  }
		  
		  
		// FUNÇÃO QUE CRIA O BOTÃO DA CAIXA DE VISUALIZAÇÃO
		  $.fn.botaoVisualizacao = function(valueBotao) {
			var obj = $(this);
	
			var div_botao = $('<div></div>');
			div_botao.css({
				'margin' : '10px auto 0 auto'
				, 'position':'relative'
				, 'text-align':'center'
				, 'clear' : 'both'
				//, 'border':'1px solid #0F0'
			});
					
			var elementoButton = $('<input type="button" />');
			elementoButton.css({
			});
			elementoButton.attr("name","bt_caixa_visualizacao");
			elementoButton.attr("id","bt_caixa_visualizacao");
			elementoButton.attr("value",valueBotao);
			
			div_botao.append(elementoButton);
	
			obj.last('div').append(div_botao);
	
			elementoButton.bind('click',function(){
	//			alert($(this).val());
				if(obj.find('video').length){
					var video_html5 = obj.find('video');
					video_html5.load();
				}

				if($('.check_caixa_visualizacao').length){
					
					var nome_pagina = location.href.substr(location.href.lastIndexOf("/") + 1,location.href.length);
					
					var checado = $('.check_caixa_visualizacao').attr('checked');
										
					$.ajax({
						url:'marca_alerta_paginas.php'
						, data: 'id_login=<?=$_SESSION["id_userSecaoMultiplo"]?>&nome_pagina=' + nome_pagina + '&status='+checado
						, type: 'post'
						, async: true
						, cache: false
						, success: function(retorno){
						}
					});	
					

				}
	
				//obj.css('display',(obj.css('display') == 'block' ? 'none' : 'block'));
				obj.toggle();
				
			});
		  }
		
		  
		// FUNÇÃO QUE CRIA O CHECKBOX DA CAIXA DE VISUALIZAÇÃO
		  $.fn.checkCaixaVisualizacao = function(checado) {
	
			var obj = $(this);
	
			var div_checkbox = $('<div></div>');
			div_checkbox.css({
				'position':'relative'
				, 'clear' : 'both'
				, 'margin-top':'10px'
				, 'width': 'auto'
				, 'display':'table'
				//, 'border':'1px solid #F00'
			});
			var elementoCheck = $('<input>');
			elementoCheck.css({
				'position':'relative'
				, 'margin': '0 5px 0 0'
				, 'float': 'left'
			});
			elementoCheck.attr("type","checkbox");
			elementoCheck.attr("checked",checado);
			elementoCheck.attr("id","check_caixa_visualizacao");
			elementoCheck.attr("name","check_caixa_visualizacao");
			elementoCheck.attr("class","check_caixa_visualizacao");
			elementoCheck.attr("value","1");
	
			var elementoLabel = $('<label for="check_caixa_visualizacao">NÃO EXIBIR NOVAMENTE</label>');
/*			elementoLabel.css({
				'position':'relative'
				, 'float': 'left'
				, 'padding': '-5px 0 0 0'
				, 'font-size' : '12px'
			});*/
	
	//alert(obj.first('div').css('margin-bottom'));
	
			div_checkbox.append(elementoCheck).append(elementoLabel);//.append(elementoButton);
			obj.first('div').children().append(div_checkbox);
	
		  };
	
		})($, window);
	
		$('.box_visualizacao').each(function(index, element) {
			<?
			// CHECANDO SE DEVE MOSTRAR A CAIXA DE INFORMAÇÃO OU NÃO 
			$checa = mysql_query("SELECT id FROM alertas_usuarios_paginas WHERE id_login = " . $_SESSION["id_userSecaoMultiplo"] . " AND nome_pagina = '" . str_replace("/","",$_SERVER['PHP_SELF']) . "' AND status_alerta = 1");
			if(mysql_num_rows($checa) > 0){
				echo "$(this).css('display','none');";
				$checado = "1";
			}/*else{
				echo "$(this).css('display','block');";	
			}*/
			?>
		});

		$('.check_visualizacao').each(function(index, element) {
		   $(this).checkCaixaVisualizacao('<?=$checado?>'); // acrescenta o checkbox na caixa de visualização
		});

		$('.botao_visualizacao').each(function(index, element) {
		   $(this).botaoVisualizacao('Fechar'); // acrescenta o botão na caixa de visualização 
		});

		$('.x_visualizacao').each(function(index, element) {
		   $(this).botaoXVisualizacao(); // acrescenta o botão na caixa de visualização 
		});

		$('.btReAbrirBox').each(function(index, element) {
			<?
			// CHECANDO SE DEVE MOSTRAR A CAIXA DE INFORMAÇÃO OU NÃO 
			$checa = mysql_query("SELECT id FROM alertas_usuarios_paginas WHERE id_login = " . $_SESSION["id_userSecaoMultiplo"] . " AND nome_pagina = '" . str_replace("/","",$_SERVER['PHP_SELF']) . "' AND status_alerta = 1");
			if(mysql_num_rows($checa) > 0){
				echo "$(this).css('display','block');";
				$checado = "1";
			}//else{
			//	echo "$(this).css('display','block');";	
			//}
			?>
		});
		
		$('.btReAbrirBox').bind('click',function(e){
			e.preventDefault();
			var $div = eval($('#' + $(this).attr('div'))); // acessando o objeto em questão
			$div.css('display','block');
			$(this).toggle();
		});
		
		
		$('.fechar_mensagem_compartilhamento').click(function(e){
			// usado na mensagem de compartilhamento em redes sociais
			e.preventDefault();
			$.ajax({
				url:'marca_alerta_paginas.php'
				, data: 'id_login=<?=$_SESSION["id_userSecaoMultiplo"]?>&nome_pagina=compartilhe&status=1'
				, type: 'post'
				, async: true
				, cache: false
				, success: function(retorno){
					fechaDiv('chamada');
					location.href='index_restrita.php';
				}
			});	
		});
	
	});

function formatReal(int){
	var tmp = int+'';
	tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
	if( tmp.length > 6 )
			tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
	if( tmp.length > 10 )
			tmp = tmp.replace(/([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g, ".$1.$2,$3");

	return tmp;
}

function formataMoeda(vValor){
	vNovoValor = "";
	switch(vValor.length){
		case 2:
			vNovoValor = (vValor.substr(0,1) + ',' + vValor.substr(1,1));
		break;
		case 3:
			vNovoValor = (vValor.substr(0,1) + vValor.substr(1,1) + ',' + vValor.substr(2,1));
		break;
		case 4:
			vNovoValor = (vValor.substr(0,1) + vValor.substr(1,1) + vValor.substr(2,1) + ',' + vValor.substr(3,1));
		break;
		case 5:
			vNovoValor = (vValor.substr(0,1) + '.' + vValor.substr(1,1) + vValor.substr(2,1) + vValor.substr(3,1) + ',' + vValor.substr(4,1));
		break;
		case 6:
			vNovoValor = (vValor.substr(0,1) + vValor.substr(1,1) + '.' + vValor.substr(2,1) + vValor.substr(3,1) + vValor.substr(4,1) + ',' + vValor.substr(5,1));
		break;
		case 7:
			vNovoValor = (vValor.substr(0,1) + vValor.substr(1,1) + vValor.substr(2,1) + '.' + vValor.substr(3,1) + vValor.substr(4,1) + vValor.substr(5,1) + ',' + vValor.substr(6,1));
		break;
		case 8:
			vNovoValor = (vValor.substr(0,1) + '.' + vValor.substr(1,1) + vValor.substr(2,1) + vValor.substr(3,1) + '.' + vValor.substr(4,1) + vValor.substr(5,1) + vValor.substr(6,1) + ',' + vValor.substr(7,1));
		break;
		case 9:
			vNovoValor = (vValor.substr(0,1) + vValor.substr(1,1) + '.' + vValor.substr(2,1) + vValor.substr(3,1) + vValor.substr(4,1) + '.' + vValor.substr(5,1) + vValor.substr(6,1) + vValor.substr(7,1) + ',' + vValor.substr(8,1));
		case 10:
			vNovoValor = (vValor.substr(0,1) + vValor.substr(1,1) + vValor.substr(2,1) + '.' + vValor.substr(3,1) + vValor.substr(4,1) + vValor.substr(5,1) + '.' + vValor.substr(6,1) + vValor.substr(7,1) + vValor.substr(8,1) + ',' + vValor.substr(9,1));
		break;
		default:
			vNovoValor = vValor;
		break;
	}
	
	return vNovoValor;

}
			
	$(function(){
		// VALIDAR  EMAIL
		$.validateEmail = function(email)
		{
			er = /^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2}/;
			if(er.exec(email))
				return true;
			else
				return false;
		};
	});

	function validElement(idElement, msg){
//		console.log(idElement + ' - ' + msg);
		var Element=document.getElementById(idElement);

		if(Element.value == "" || Element.value == false && Element.value != 0 ){
			window.alert(msg+' '+$(Element).attr('alt')+'.');
			Element.focus();
			return false;
		}
	}
	
	function validRadio(nameElement, msg){
		var Element = $('input[name=' + nameElement + ']');
		if(!Element.is(':checked')){
			window.alert(msg+' '+ Element.eq(0).attr('alt')+'.');
			Element.eq(0).focus();
			return false;
		}
	}


									
	function limpaCaracteres(strValor){
		exp = /\,|\-|\.|\/|\(|\)| /g
		novoValor = strValor.toString().replace( exp, "" ); 
		return novoValor;
	}



	function formataCampo(Mascara,valor){
		posicaoCampo = 0;
		novoCampo = "";
		for(i=0; i<= Mascara.length; i++) { 
			// CHECA SE A MASCARA ESTA EM UM CARACTER ESPECIAL
			boleanoMascara = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".") || (Mascara.charAt(i) == "/") || (Mascara.charAt(i) == "(") || (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " "));
			// SE FOR UM CARACTER ESPECIAL PREENCHE O CAMPO COM ELE
			if (boleanoMascara) { 
				novoCampo += Mascara.charAt(i); 
			// SE NÃO COLOCA O QUE FOI DIGITADO
			}else { 
				novoCampo += valor.charAt(posicaoCampo); 
				posicaoCampo++;
			}
		}
		return novoCampo;
	}

	function subtrairDias(data, dias){
		return new Date(data.getTime() - (dias * 24 * 60 * 60 * 1000));
	}
  
	$(document).ready(function(e) {
        $('#emailPessoal').blur(function(){
			if($(this).val()!=''){
				if(!$.validateEmail($(this).val())){
					$(this).focus();
					window.alert('E-mail inválido!');
					return false;
				}
			}
		});
    });
	
</script>

<!--google analitics -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28088679-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<!--fim google analitics -->
</head>
<body>

<div class="principal" style="margin-bottom:20px">

<div style="float:left; width:400px"><a href="index_restrita.php"><img src="images/logo.png" alt="Contador Amigo" width="400" height="68" border="0" style="margin-bottom:5px; float:left" /></a></div>


<div style="float:right; width:560px">



<div style="text-align:right; font-size:24px; margin-bottom:10px; margin-top:15px">
<a href="https://www.youtube.com/c/ContadoramigoBrasil" target="_blank"><i class="fa fa-youtube-square"></i></a>
<a href="https://www.facebook.com/contadoramigoBrasil" target="_blank"><i class="fa fa-facebook-square"></i></a>
<a href="https://plus.google.com/+ContadoramigoBrasil/posts" target="_blank"><i class="fa fa-google-plus-square"></i></a>
<a href="https://www.linkedin.com/company/contador-amigo" target="_blank"><i class="fa fa-linkedin-square"></i></a>
<a href="https://twitter.com/contadoramigo" target="_blank"><i class="fa fa-twitter-square"></i></a>
<a href="https://contadoramigo.blogspot.com.br/" target="_blank"><i class="fa fa-wordpress"></i></a>
</div> 

<div style="text-align:right"><?=$painelLogin;?></div>

</div>


<div style="clear:both"></div>

<div class="menu" id="menu">
<ul>
	<li><a class="linkMenu" href="index_restrita.php">Inicio</a></li>
<!--  <li>|</li>-->
	<li class="hasChild"><a class="linkMenu" href="#">Meus Dados</a>
	  <div class="linha-branca-menu"></div>
	  <ul style="width: 160px !important;">
	    <li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="minha_conta.php">Dados da Conta</a></li>
    	<li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="meus_dados_empresa.php">Cadastro de Empresas</a></li>
    	<li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="meus_dados_socio.php">Cadastro de Sócios</a></li>
    	<li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="meus_dados_autonomos.php">Cadastro de Autônomos</a></li>
    	<li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="meus_dados_clientes.php">Cadastro de Clientes</a></li>
			<?
      if($mostra_opcao == true){
      ?>
	      <li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="meus_dados_tomadores.php">Cadastro de Tomadores</a></li>
			<?
      }
      ?>
    	<li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="meus_dados_pj.php">Cadastro de Fornecedores</a></li>
	    <!--<li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="meus_dados_funcionarios.php">Cadastro de Funcionários</a></li>-->
	    <li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="meus_dados_estagiarios.php">Cadastro de Estagiários</a></li>
    </ul>
  </li>
<!--  <li>|</li>-->
	<li><a class="linkMenu"  href="calendario.php">Calendário</a></li>
<!--  <li>|</li>-->
	<li class="hasChild"><a class="linkMenu" href="#">Pagamentos</a>
	  <div class="linha-branca-menu"></div>
	  <ul style="width: 150px !important;">
	    <li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="pro_labore.php">Pró-labore</a></li>
	    <li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="distribuicao_de_lucros.php">Distribuição de lucros</a></li>
	    <li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="pagamento_autonomos.php">Autônomos</a></li>
	    <li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="pagamento_pj.php">Pessoa Jurídica</a></li>
	    <li><img src="images/seta_opcoes.png"/><a class="linkMenu"  href="estagiarios.php">Estagiários</a></li>
	    
    </ul>
  </li>
<!--  <li>|</li>-->
	<li class="hasChild"><a class="linkMenu" href="#">Impostos e Obrigações</a>
	  <div class="linha-branca-menu"></div>
	  <ul style="width: 170px !important;">
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="impostos_e_obrigacoes.php">Informações Gerais</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="simples_orientacoes.php">Apuração do Simples</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="gfip_orientacoes.php">Envio da Gfip</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="inss.php">Recolhimento do INSS</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="darf_orientacoes.php">Pagamento do DARF</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="taxas_municipais.php">Taxas Municipais</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="contribuicao_sindical.php">Contribuição Sindical</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="comprovante_retencao.php">Informe de Rendimentos</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="rais.php">Transmissão da RAIS</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="defis_orientacoes.php">Transmissão da DEFIS</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="dirf_orientacoes.php">Transmissão da DIRF</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="DCTF_orientacoes.php">Envio da DCTF</a></li>        
	  </ul>
  </li>
<!--  <li>|</li>-->
	<li><a class="linkMenu"  href="checkup.php">CheckUp Fiscal</a></li>
<!--  <li>|</li>-->
	<li class="hasChild"><a class="linkMenu" href="#">Livro Caixa</a>
	  <div class="linha-branca-menu"></div>
	  <ul style="width: 100px !important;">
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="livros_caixa_movimentacao.php">Lançamentos</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="livros_caixa_graficos.php">Gráficos</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="livros_caixa_fluxo.php">Fluxo de Caixa</a></li>
	  </ul>
  </li>
  
  <!--  <li>|</li>-->
	<li><a class="linkMenu"  href="abertura_empresa.php">Abertura de Empresa</a></li>
	  
  
<!--  <li>|</li>-->
	<li class="hasChild"><a class="linkMenu" href="#">Alterações</a>
	  <div class="linha-branca-menu"></div>
	  <ul style="width: 220px !important;">
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="alteracao_contrato_sociedade_junta.php">Sociedades registradas na Junta</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="alteracao_contrato_sociedade_cartorio.php">Sociedades registradas em cartório</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="alteracao_contrato_individual.php">Empresario individual ou EIRELI</a></li>
	  </ul>
  </li>
<!--  <li>|</li>-->
	<li class="hasChild"><a class="linkMenu" href="#">Outros</a>
	  <div class="linha-branca-menu"></div>
	  <ul style="width: 195px !important;">
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="declaracao_simples.php">Declaração do Simples</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="pis.php">Descubra o número do PIS</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/cnpjreva_solicitacao.asp" target="_blank">Impressão do CNPJ</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="https://www.receita.fazenda.gov.br/Aplicacoes/SSL/ATBHE/SAGA/RegrasAgendamento.aspx" target="_blank">Agendamento de visita ao INSS</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="procuracao.php">Procuração - Receita e INSS</a></li>
	    <li><img src="images/seta_opcoes.png"/>&nbsp;&nbsp;<a class="linkMenu"  href="impostos_aliquotas.php">Aliquotas de Impostos</a></li>
	  </ul>
  </li>
<!--  <li>|</li>-->
	<li><a class="linkMenu"  href="nota_fiscal_orientacoes.php">Nota Fiscal</a></li>
<!--  <li>|</li>-->
	<li><a class="linkMenu"  href="suporte.php">Help Desk</a></li>
</ul>
</div>

<script>
$('#menu > ul > li').each(function(index,element){
	//alert();
});
</script>

<div style="clear:both"> </div>

</div>
<!--Layer Meus Dados -->
<?
$verifica_construcao_civil = mysql_fetch_array(mysql_query("SELECT count(*) total FROM cnae c INNER JOIN dados_da_empresa_codigos cod ON c.cnae = cod.cnae WHERE cod.id='" . $_SESSION["id_userSecaoMultiplo"] . "' AND (c.cnae LIKE '41%' OR c.cnae LIKE '42%' OR c.cnae LIKE '43%')"));
if($verifica_construcao_civil['total'] > 0){
	$altura_div = '210';
	//$altura_div = '231';
	$imagem_fundo = 'meus_dados_fundo2';
	$mostra_opcao = true;
}else{
	$altura_div = '189';
	//$altura_div = '210';
	$imagem_fundo = 'meus_dados_fundo';
	$mostra_opcao = false;
}
?>



<!-- janela contato -->

<div id="contatoCaixa" style="top:115px; left:50%; margin-left:250px; position:absolute; display:none">

<div class="janelaLayer" style="width:230px; height:145px">
<div style="text-align:right; background:#336699; padding:3px; border-bottom-color:#999999; border-bottom-style:solid; border-bottom-width:1px"><a href="javascript:fechaDiv('contatoCaixa')"><img src="images/botao_fechar.png" width="13" height="13" border="0" /></a></div>
<div style="padding:10px">

<div class="tituloPeq">Contato</div>
<br />
<div class="tituloVermelho" style="margin-bottom:10px">Central de atendimento ao cliente</div>
<span style="line-height:20px"><strong>tel:</strong> 11 3815-4110<br />
<strong> e-mail:</strong> <a href="mailto:info@contadoramigo.com.br">info@contadoramigo.com.br</a></span></div>

</div>
<div class="janelaLayer_BG" style="width:230px; height:145px"> </div>
</div>

<!--fim da janela contato -->
<?
if($_SESSION["id_userSecaoMultiplo"] == '1581'){
//var_dump($_COOKIE);
}
?>