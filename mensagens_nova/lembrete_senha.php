<?php 
$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
					Essa mensagem foi gerada através do pedido de recuperação de senha efetuado em nosso portal.<br />
<br />
Sua senha de acesso é: <strong>' . $senha . '</strong><br />
No campo email, digite o mesmo  endereço usado <br />
para o envio desta mensagem. <br />
<br />
					<strong>Contador Amigo</strong><br />
			  <a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>';
?>