<!DOCTYPE html>

<?php 
if(session_id() == ''){
session_start();
}

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

include "../conect.php";
include "linkContador.php";

?>

<!-- PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<title>Contador Amigo - Sistema Administrativo</title>
<link href="../estilo.css?code=<?php echo time();?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="screen" href="../estilo/font-awesome.min.css?code=<?php echo time();?>"><!--estilo font-awesome-->
<link rel="stylesheet" media="screen" href="../ballon.css?code=<?php echo time();?>"><!--estilo ballon CSS -->
<link rel="icon" type="image/x-icon" href="../favicon_azul.ico">
<script type="text/javascript" src="../scripts/jquery_1_7.js"></script>
<script type="text/javascript" src="../scripts/meusScripts.js"></script>
<script type="text/javascript" src="../scripts/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../scripts/jquery.form.js"></script>

<link href="https://fonts.googleapis.com/css?family=Open+Sans|Varela+Round" rel="stylesheet">

<script>
	$(document).ready(function(e) {
	
		var $corFundoOriginalLinhasTabela = "";
	
		$('.guiaTabela').hover(function(){
			$corFundoOriginalLinhasTabela = $(this).css('background-color');
			$(this).css('background-color','#f9f9fb');
			}
		,function(){
			$(this).css('background-color',$corFundoOriginalLinhasTabela);
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
					
			var elementoButton = $('<img src="../images/x.png" />');
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
						  , data: 'id_login=<?=$_SESSION["id_userSecao"]?>&nome_pagina=' + nome_pagina + '&status='+checado
						  , type: 'post'
						  , async: true
						  ,	cache: false
						  , success: function(retorno){
						  }
						});
					//}
				}
	
				//obj.css('display',(obj.css('display') == 'block' ? 'none' : 'block'));
				obj.toggle();
				
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
						, data: 'id_login=<?=$_SESSION["id_userSecao"]?>&nome_pagina=' + nome_pagina + '&status='+checado
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
			elementoCheck.attr("name","check_caixa_visualizacao");
			elementoCheck.attr("class","check_caixa_visualizacao");
			elementoCheck.attr("value","1");
	
			var elementoLabel = $('<label>NÃO EXIBIR NOVAMENTE</label>');
			elementoLabel.css({
				'position':'relative'
				, 'float': 'left'
				, 'padding': '-5px 0 0 0'
				, 'font-size' : '80%'
			});
	
	//alert(obj.first('div').css('margin-bottom'));
	
			div_checkbox.append(elementoCheck).append(elementoLabel);//.append(elementoButton);
			obj.first('div').children().append(div_checkbox);
	
		  };
	
		})($, window);
	
		$('.box_visualizacao').each(function(index, element) {
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
		
		
	
	
	
		if($('.imagemDica').length){
			$('.imagemDica').css('cursor','pointer').bind('click',function(e){
				e.preventDefault();
				var $div = eval($('#' + $(this).attr('div')));
				var novoLeft = 0;
				switch($(this).attr('position')){
					case 'right':
						novoLeft = $(this).offset().left + (parseInt($(this).css('width'))) - ($div.innerWidth())
					break;
					case 'left':
						novoLeft = $(this).offset().left;
					break;
					default:
						novoLeft = $(this).offset().left + (parseInt($(this).css('width')) / 2) - ($div.innerWidth() / 2);
					break;
				}

				$div.css({
					'top':$(this).offset().top + 35
					, 'left': novoLeft
				});
				
				abreDiv($(this).attr('div'));
			});
		}

	
	
        $('.campoData').mask('99/99/9999');
		$('.campoDDDTelefone').mask('99');
		$('.campoTelefone').mask('999999999');
		$('.campoCNPJ').mask('99.999.999/9999-99');
		$('.campoCPF').mask('999.999.999-99');
		$('.campoCEP').mask('99999-999');
		$('.campoNIRE').mask('9999999999-9');
		$('.campoCNAE').mask('9999-9/99');
		$('.campoNumeroNFCobranca').mask('9999');
		$('.campoNumeroTIDCobranca').mask('9999999999');
		
		$('.inteiro').focus(function(){
			$(this).select();
		});
	
		$('.inteiro').keypress(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			
			
			if(code != 8 && code != 9 && !parseInt(String.fromCharCode(code)) && parseInt(String.fromCharCode(code)) != 0){
				return false;
			}

			
		});
		
		$('.current').keypress(function(e){
			// PARA ACERTAR O PROBLEMA DE NÃO ACEITAR O keyCode NO FIREFOX
			if(e.keyCode){
				var code = e.keyCode;
			}else{
				var code = e.which;
			}
			

			if(code != 8 && code != 9){
				if($(this).attr('maxlength')){
					var tamanho_maximo = $(this).attr('maxlength');
				}else{
					var tamanho_maximo = 10;
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



    });


function limpaCaracteres(strValor){
	exp = /\,|\-|\.|\/|\(|\)| /g
	novoValor = strValor.toString().replace( exp, "" ); 
	return novoValor;
}

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
</script>
<?
if(isset($_SESSION['erro'])){
  echo "<script language='javascript'>alert('".$_SESSION['erro']."');</script>";
	//unset($_SESSION['erro']);
}
?>
</head>
<body>

<div style="width:966px; margin:0 auto; margin-bottom:20px">
<div style="float:left"><img src="../images/logo.png" alt="Contador Amigo" width="400" height="68" border="0" style="margin-bottom:5px; float:left" /></div>
<div class="tituloVermelho" style="float:right; margin-top:36px;margin-bottom:15px">
    <form id="bt_logof" method="post" action="/admin/logout.php">
    	Sistema Administrativo | <button class="bt_logof" type="submit" name="toLogoff" style="background: none; color: #555; padding: 0px;">Sair</button>
    </form>
</div>

<div style="clear:both"> </div>


<?
$sql = "SELECT * FROM suporte WHERE tipoMensagem='pergunta' AND status='Em análise' ORDER BY ultimaResposta DESC";
$resultado = mysql_query($sql)
or die (mysql_error());
$pendente = mysql_num_rows($resultado);
?>

<div style="clear:both; border-bottom-color:#113b63; border-bottom-style:solid; 
	border-bottom-width:1px;; height:10px; width:100%"></div>

<div class="menu" id="menu">
    <ul>
        <li>
        	<a class="linkMenu" href="clientes_lista.php">Dados dos Clientes</a>
        </li>
        
        <li class="hasChild">
            <a class="linkMenu" href="#">Cobrança</a>
            <div class="linha-branca-menu"></div>
            <ul style="width: 160px !important;">
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="cobranca.php">Pagamentos</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="listas_emailmarketing.php">Enviar Emkt</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="rps.php">Gerar RPS</a>
                </li>
				<li>
					<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="arquivo_remessa.php">Arquivo Remessa</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="subir_arquivo_retorno.php">Arquivo Retorno</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="gerenciar-boletos.php">Boletos</a>
                </li>
            </ul>
        </li>
        
        <li>
        	<a class="linkMenu" href="atualiza_livro_caixa.php">Livro Caixa</a>
        </li>
        
        <li>
        	<a class="linkMenu" href="cancelamentos_lista.php">Cancelamentos</a>
        </li>
        
        <li>
        	<a class="linkMenu" href="suporte.php">Suporte
				<? if($pendente > 0){ ?>
				<span style="text-align: center; display: inline-block; font-size: 12px; line-height: 13px; background-color:#a61d00; color: #fff; width: 20px; height: 12px; border-radius: 3px;" title="<?=$pendente?> Mensagens pendentes"><?=$pendente?></span>
				<? } ?>
			</a>
        </li>
        
        <li>
        	<a class="linkMenu" href="faq_lista.php">FAQ</a>
        </li>

        <li class="hasChild">
            <a class="linkMenu" href="#">Índices e Tabelas</a>
            <div class="linha-branca-menu"></div>
            <ul style="width: 160px !important;">
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="planos_assinatura.php">Planos Assinatura</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="indice.php">Previdência</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="tabelas.php">Anexos do Simples</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="retencao_ir.php">Retenção de IR</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="retencao_inss.php">Retenção de INSS</a>
                </li>                
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="des.php">Des</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="selic.php">Selic</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="cnaes-livro-diario-razao.php">Cnaes</a>
                </li>
            </ul>
        </li>        
        <li>
        	<a class="linkMenu" href="indicacoes.php">Indicações</a>
        </li>
        <li class="hasChild">
            <a class="linkMenu" href="#">Home</a>
            <div class="linha-branca-menu"></div>
            <ul style="width: 160px !important;">
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="agenda.php">Agenda</a>
                </li>
                <li>
                	<img src="../images/seta_opcoes.png">
                	<a class="linkMenu" href="textos_home.php">Chamada</a>
                </li>
            </ul>
        </li>        
        <li>
        	<a class="linkMenu" href="graficos_metricas.php">Métricas</a> 
        </li>
        <li>
        	<a class="linkMenu" href="demos.php">Demos</a> 
        </li>
        <li class="hasChild">
            <a class="linkMenu" href="#">Contador</a>
            <div class="linha-branca-menu"></div>
			<?php echo linkContador();?>
        </li>
        <li>
        	<a class="linkMenu" href="tabelasLivroCaixa.php">limpeza</a> 
        </li>         
    </ul>
</div>

<div style="clear:both"> </div>
<br>
<!-- janelas ocultas -->

<div id="contatoCaixa" style="top:115px; left:50%; margin-left:250px; position:absolute; display:none">

<div class="janelaLayer" style="width:230px; height:145px">
<div style="text-align:right; background:#336699; padding:3px; border-bottom-color:#999999; border-bottom-style:solid; border-bottom-width:1px"><a href="javascript:fechaDiv('contatoCaixa')"><img src="../images/botao_fechar.png" width="13" height="13" border="0" /></a></div>
<div style="padding:10px">
<!--aqui começa o conteudo -->
<div class="tituloPeq">Contato</div>
<br />
<div class="tituloVermelho" style="margin-bottom:10px">Central de atendimento ao cliente</div>
<span style="line-height:20px"><strong>tel:</strong> 11 3815-4110<br />
<strong> e-mail:</strong> <a href="mailto:info@contadoramigo.com.br">info@contadoramigo.com.br</a></span></div>
<!--aqui termina o conteudo -->
</div>
<div class="janelaLayer_BG" style="width:230px; height:145px"> </div>
</div>
