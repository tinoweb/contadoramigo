<?php 
$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Prezado  ' . $nomedestinatario . ',<br /><br />
					Você está recebendo esta mensagem porque ' . $AssinanteExplode[0] . ' queria lhe indicar o <a href="http://www.contadoramigo.com.br">Portal Contador Amigo</a>. Neste site, você poderá pagar seus impostos e cumprir com todas as suas obrigações fiscais, de maneira simples e rápida, sem a necessidade de um contador.<br />
<br />
Veja a mensagem de ' . $AssinanteExplode[0] . ':<br /><br />
					' . $mensagem . '
<br /><br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>';
?>