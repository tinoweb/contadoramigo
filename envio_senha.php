<?php 
include 'conect.php';
$email = $_POST['txtEmail'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">
<title>Contador Amigo - Envio de Senha</title>
<?php include 'header.php' ?>
<script type="text/javascript">

 var msg1 = 'É necessário preencher o campo';

function fnValidaEmail(email){

        var v_email = email.value;
        var jSintaxe;
        var jArroba;
        var jPontos;

	var ExpReg = new RegExp('[^a-zA-Z0-9\.@_-]', 'g');

        jSintaxe = !ExpReg.test(v_email);
	if(jSintaxe == false){
            window.alert('Favor digitar o e-mail corretamente!');
            return false;
	}
	jPontos = (v_email.indexOf('.') > 0) && !(v_email.indexOf('..') > 0);
	if (jPontos == false){
            window.alert('Favor digitar o e-mail corretamente!');
            return false;
	}
	jArroba = (v_email.indexOf('@') > 0) && (v_email.indexOf('@') == v_email.lastIndexOf('@'));
	if (jArroba == false){
            window.alert('Favor digitar o e-mail corretamente!');
            return false;
	}

        return true;

}

 function enviaSenha(){

			if( validElement('txtEmailSenha', msg1) == false){
               return false
            } else {
               var email = document.getElementById('txtEmailSenha');
                if(fnValidaEmail(email) == false){
                    return false
                }
            }

     document.getElementById('frmEnvioSenha').submit();
	
 }
 
</script>

<div class="principal">
<div class="minHeight">
<h1>Envio de Senha</h1>
Digite seu e-mail no campo abaixo. Sua senha será enviada em seguida.<br />
<br />
<form name="frmEnvioSenha" id="frmEnvioSenha" method="post" action="envio_senha_processo.php" style="margin-top:3px" onkeypress="if (event.keyCode == 13) formSubmit()">
  <input name="txtEmailSenha" type="text" id="txtEmailSenha" style="width:245px;" value="<?=$email?>" alt="E-mail" /> <input type="button" onClick="enviaSenha()" value="Enviar" />
</form>
</div>
</div>

<?php include 'rodape.php' ?>