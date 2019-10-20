<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	require_once "header.php";
	
	// Verifica se Existe Mensagem.
	$msgLogin = "";
	if(isset($_SESSION['MESSAGE_USER'])) {
		$msgLogin = $_SESSION['MESSAGE_USER'];
		unset($_SESSION['MESSAGE_USER']);
	}
?>
    <div style="text-align:center" class="minHeight"> 
        <div class="campoLogin">
        	<span style="color:#F00;"><?php echo $msgLogin;?></span>
        	<form name="login" id="login" action="login.php" method="post" onsubmit="return validaForm()">
                <!-- onkeypress="if (event.keyCode == 13) validaForm()"-->
                <table cellspacing="0" cellpadding="2" border="0" align="center">
                    <tbody>
                        <tr>
                            <td colspan="2" align="left">
                            <div class="tituloPeq" style="font-size:16px; margin-bottom:10px">Login:</div>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">Email: </td>
                            <td>
                                <input name="user" id="txtEmail" value="" maxlength="60" style="width:130px" type="text">
                            </td>
                        </tr> 
                        <tr>
                            <td align="left">Senha: </td>
                            <td>
                                <input name="passwd" id="txtSenha" value="" maxlength="60" style="width:130px" type="password">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
        						<input name="manter" id="manter" value="1" type="checkbox"> Manter-me conectado
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="bottom">
                                <input value="Entrar" name="toaccess" id="btnLogin" type="submit" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
   
    </div>

<?php include '../rodape.php' ?>