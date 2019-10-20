<?php 
session_start();

if(isset($_SESSION['erro'])){
  echo "<script language='javascript'>alert('".$_SESSION['erro']."');</script>";
}

include "../conect.php" ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<title>Contador Amigo - Sistema Administrativo</title>
<link href="../estilo.css" rel="stylesheet" type="text/css" />
<LINK REL="SHORTCUT ICON" HREF="../icon.ico" type="image/x-icon"/>
<link rel="apple-touch-icon" href="logo_ipad.png" />
<script type="text/javascript" src="../scripts/meusScripts.js"></script>
<script type="text/javascript" src="../scripts/jquery.min.js"></script>
<script type="text/javascript" src="../scripts/jquery.maskedinput.js"></script>
<script>
	$(document).ready(function(e) {
        $('.campoData').mask('99/99/9999');
		$('.campoDDDTelefone').mask('99');
		$('.campoTelefone').mask('999999999');
		$('.campoCNPJ').mask('99.999.999/9999-99');
		$('.campoCPF').mask('999.999.999-99');
		$('.campoCEP').mask('99999-999');
		$('.campoNIRE').mask('9999999999-9');
		$('.campoCNAE').mask('9999-9/99');
    });
</script>
</head>
<body>

<div style="width:966px; margin:0 auto; margin-bottom:20px">
<div style="float:left"><img src="../images/logo.png" alt="Contador Amigo" width="400" height="68" border="0" style="margin-bottom:5px; float:left" /></div>
<div class="titulo" style="float:right; margin-top:36px; color:#C00">
  Sistema Administrativo
</div>
<div style="clear:both"> </div>


<div class="menu">
<div style="float:left">
<a class="linkMenu" href="clientes_lista.php">Dados dos Clientes</a> |
<a class="linkMenu" href="suporte.php">Suporte</a> |
<!--<a class="linkMenu" href="emkt/src/contatosDesativar.php">Envio de mensagens</a> | -->
<a class="linkMenu" href="cobranca.php">Cobrança</a> |
<a class="linkMenu" href="indice.php">Índices</a> |
<a class="linkMenu" href="tabelas.php">Tabelas</a> 

</div>
<div style="float:right"><a class="linkMenu" href="logout.php">Sair</a></div>
<div style="clear:both"> </div>
</div>
</div>

<!-- janelas ocultas -->

<div id="contatoCaixa" style="top:115px; left:50%; margin-left:250px; position:absolute; display:none">

<div class="janelaLayer" style="width:230px; height:145px">
<div style="text-align:right; background:#336699; padding:3px; border-bottom-color:#999999; border-bottom-style:solid; border-bottom-width:1px"><a href="javascript:fechaDiv('contatoCaixa')"><img src="images/botao_fechar.png" width="13" height="13" border="0" /></a></div>
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