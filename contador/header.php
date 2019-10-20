    <!DOCTYPE html>
    <!-- PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">-->
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
        <title>Contador Amigo - Sistema Administrativo</title>
        <link href="../estilo.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" media="screen" href="../estilo/font-awesome.min.css?v">
        <link rel="stylesheet" media="screen" href="../ballon.css?v">
        <LINK REL="SHORTCUT ICON" HREF="../icon.ico" type="image/x-icon"/>
        <link rel="apple-touch-icon" href="logo_ipad.png" />
        <script type="text/javascript" src="../scripts/jquery_1_7.js"></script>
        <script type="text/javascript" src="../scripts/meusScripts.js"></script>
        <script type="text/javascript" src="../scripts/jquery.maskedinput.js"></script>
        <script type="text/javascript" src="../scripts/jquery.form.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,600italic" rel="stylesheet" type="text/css">
		<style>
            .campoLogin {
                background: #FFFF99 none repeat scroll 0 0;
                border-radius: 3px;
                display: inline-block;
                margin-top: 100px;
                padding: 10px;
                width: 300px;
				border: 1px solid #CCC;
            }
			body .bt_logof {
				background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
				border: 0 none;
				border-radius: 0;
				color: #143c62;
				cursor: pointer;
				font-family: 'Varela Round', sans-serif;
				font-size: 12px;
				margin: 0 auto;
				padding: 0;
				text-align: center;				
			}
			.gridButton {
				background:none; 
				color: #024A68;
				font-size: 12px;
				padding: 0;
			}
			.gridButton:hover {
				background:none; 
				color: #333333;
				cursor:pointer;
			}
        </style> 
        
        <script type="text/javascript">
        	
			$('.bt_logof').click(function(){
				
				alert("passou aqui agora");
				
				//$('#logoff').submit();
			});
        
        </script>   
    </head>

<?php

	// ini_set('display_errors',1);
	// ini_set('display_startup_erros',1);
	// error_reporting(E_ALL);
	
	// Inicia a Sessão
	session_start();
	
	// incluir o controlador do login.
	require_once "login-Controller.php";
	
	// Realiza a chamada da classe de controle de login.
	$login = new Login();	
	
	// Realiza o logoff.
	if(isset($_POST['toLogoff'])) {
		$login->getout();
	}	
	
	// Verificar se o usuario ja esta logado.
	if($login->checkLogin()) {
		if($_SERVER['REQUEST_URI'] == '/contador/login.php') {
			Header( "Location: /contador");
		}
	} else {
		if($_SERVER['REQUEST_URI'] <> '/contador/login.php') {
			Header( "Location: /contador/login.php");
		}	
	}

	// Realiza o login do usuario.
	if(isset($_POST['toaccess'])){
		$login->toaccess();	
	}
	
?>
<body>
<?php if($login->checkLogin()):?>    
    <div style="width:966px; margin:0 auto; margin-bottom:20px">
    <div style="float:left"><img src="../images/logo.png" alt="Contador Amigo" width="400" height="68" border="0" style="margin-bottom:5px; float:left" /></div>
    <div style="float:right; margin-top:36px;margin-bottom:16px; color:#666">
    	<div style="text-align:right">
        	<form id="bt_logof" method="post" action="login.php">
            	Contador: <b><?php echo $login->getUserLogin(); ?></b> | 
            	<button class="bt_logof" type="submit" name="toLogoff">Sair</button>
        	</form>        
        </div>
    </div>    
    <div style="clear:both; border-bottom-color:#113b63; border-bottom-style:solid; border-bottom-width:1px;; height:10px; width:100%"></div>
    <div class="menu" id="menu">
		<ul>
            <li>
                <a class="linkMenu" href="index.php">Premium</a>
            </li>
			<li>
                <a class="linkMenu" href="clientes_avulso.php">Avulsos</a>
            </li>            
<!--            <li>
                <a class="linkMenu" href="pagamentos.php">Pagamentos</a>
            </li>-->	
            <li>
                <a class="linkMenu" href="listaPagamentos.php">Pagamentos</a>
            </li>	            		
        </ul>
    </div>
    <div style="clear:both"> </div>
<?php else: ?> 
    <div style="width:966px; margin:0 auto; margin-bottom:20px">
    <div style="float:left"><img src="../images/logo.png" alt="Contador Amigo" width="400" height="68" border="0" style="margin-bottom:5px; float:left" /></div>
    <div style="margin-top:5px;margin-bottom:15px;">
    	<div style="text-align:right"></div>
        <div style="clear:both; border-bottom-color:#113b63; border-bottom-style:solid; border-bottom-width:1px;; height:10px; width:100%"></div>
        <div class="menu" id="menu"></div>
        <br>
<?php endIf; ?> 