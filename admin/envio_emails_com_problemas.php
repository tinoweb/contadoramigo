<?php include '../conect.php';

include('../classes/phpmailer.class.php');


	function trataTxt($var) {
	
		$str = $var; 
		
		$str = str_replace("á","a",$str);	
		$str = str_replace("à","a",$str);	
		$str = str_replace("â","a",$str);	
		$str = str_replace("ã","a",$str);	
		$str = str_replace("Á","A",$str);	
		$str = str_replace("À","A",$str);	
		$str = str_replace("Â","A",$str);	
		$str = str_replace("Ã","A",$str);	
	
		$str = str_replace("é","e",$str);	
		$str = str_replace("è","e",$str);	
		$str = str_replace("ê","e",$str);	
		$str = str_replace("É","E",$str);	
		$str = str_replace("È","E",$str);	
		$str = str_replace("Ê","E",$str);	
	
		$str = str_replace("í","i",$str);	
		$str = str_replace("ì","i",$str);	
		$str = str_replace("î","i",$str);	
		$str = str_replace("Í","I",$str);	
		$str = str_replace("Ì","I",$str);	
		$str = str_replace("Î","I",$str);	
	
		$str = str_replace("ó","o",$str);	
		$str = str_replace("ò","o",$str);	
		$str = str_replace("ô","o",$str);	
		$str = str_replace("õ","o",$str);	
		$str = str_replace("Ó","O",$str);	
		$str = str_replace("Ò","O",$str);	
		$str = str_replace("Ô","O",$str);	
		$str = str_replace("Õ","O",$str);	
	
		$str = str_replace("ú","u",$str);	
		$str = str_replace("ù","u",$str);	
		$str = str_replace("û","u",$str);	
		$str = str_replace("ü","u",$str);	
		$str = str_replace("Ú","U",$str);	
		$str = str_replace("Ù","U",$str);	
		$str = str_replace("Û","U",$str);	
		$str = str_replace("Ü","U",$str);	
	
		$str = str_replace("ñ","n",$str);	
		$str = str_replace("Ñ","N",$str);	
	
		$str = str_replace("ç","c",$str);
		$str = str_replace("Ç","C",$str);

		$str = str_replace("&","E",$str);
		
		return $str;
	}
	
	
/*
jfernandoandrade@uol.com.br   pagou boleto em 20 ago
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='jfernandoandrade@uol.com.br' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);
				
				//Envio de e-mail alertando o pagamento do boleto.
				$statusUser = $linhaEmail["status"];
				$Assinante = $linhaEmail["assinante"];
				//printf($Assinante . "<BR><BR>");
				
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $linhaEmail["email"];
				//printf($emailuser . "<BR><BR>");
				$valorEmail = "55";
				$assuntoMail = "Pagamento recebido";

$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
				  Acusamos, na data de hoje, o pagamento do boleto no valor de R$ ' . number_format($valorEmail,2,",",".") . ', referente à  sua assinatura mensal no Portal Contador Amigo. A nota fiscal será enviada por email dentro de alguns dias.<br />
				  <br />
				  Em caso de dúvida, contate-nos pelo <em>help desk</em>. Estamos sempre à disposição.<br />
<br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>
<p>
	<br />
	&nbsp;</p>
</body>
</html>';
				//include '../mensagens/componente_envio.php';
				include '../mensagens/componente_envio_novo.php';
				//printf($mensagemHTML . "<BR><BR>");
/*
adm@voviajar.com.br    pagou boleto em 20 ago
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='adm@voviajar.com.br' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);
				
				//Envio de e-mail alertando o pagamento do boleto.
				$statusUser = $linhaEmail["status"];
				$Assinante = $linhaEmail["assinante"];
				//printf($Assinante . "<BR><BR>");
				
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $linhaEmail["email"];
				//printf($emailuser . "<BR><BR>");
				$valorEmail = "55";
				$assuntoMail = "Pagamento recebido";

$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
				  Acusamos, na data de hoje, o pagamento do boleto no valor de R$ ' . number_format($valorEmail,2,",",".") . ', referente à  sua assinatura mensal no Portal Contador Amigo. A nota fiscal será enviada por email dentro de alguns dias.<br />
				  <br />
				  Em caso de dúvida, contate-nos pelo <em>help desk</em>. Estamos sempre à disposição.<br />
<br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>
<p>
	<br />
	&nbsp;</p>
</body>
</html>';
				//include '../mensagens/componente_envio.php';
				include '../mensagens/componente_envio_novo.php';
				//printf($mensagemHTML . "<BR><BR>");
/*
cursomonitor@yahoo.com.br   pagou boleto em 20 ago
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='cursomonitor@yahoo.com.br' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);

				
				//Envio de e-mail alertando o pagamento do boleto.
				$statusUser = $linhaEmail["status"];
				$Assinante = $linhaEmail["assinante"];
				//printf($Assinante . "<BR><BR>");
				
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $linhaEmail["email"];
				//printf($emailuser . "<BR><BR>");
				$valorEmail = "55";
				$assuntoMail = "Pagamento recebido";
$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
				  Acusamos, na data de hoje, o pagamento do boleto no valor de R$ ' . number_format($valorEmail,2,",",".") . ', referente à  sua assinatura mensal no Portal Contador Amigo. A nota fiscal será enviada por email dentro de alguns dias.<br />
				  <br />
				  Em caso de dúvida, contate-nos pelo <em>help desk</em>. Estamos sempre à disposição.<br />
<br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>
<p>
	<br />
	&nbsp;</p>
</body>
</html>';
				//include '../mensagens/componente_envio.php';
				include '../mensagens/componente_envio_novo.php';
				//printf($mensagemHTML . "<BR><BR>");
/*
claudia@tmcomercializacao.com.br  ativo. parcela vence em 25 ago. boleto.
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='claudia@tmcomercializacao.com.br' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);


		//Componente de Envio de e-mail.
		$Assinante = $linhaEmail["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);
		$vencimento = "25/08/2014";
		$emailuser = $linhaEmail["email"];
		$assuntoMail = "Boleto a vencer";
		
		// VARIAVEIS QUE SÃO UTILIZADAS NO CORPO DO EMAIL
		
		$sql_dados_empresa = "SELECT * FROM dados_da_empresa WHERE id = " . $linhaEmail["id"];
		$linha_dados_empresa = mysql_fetch_array(mysql_query($sql_dados_empresa));
		
		$id_usuario_boleto = $linhaEmail["id"];
		$valor_boleto = "55,00";
		$mes_boleto = "08";
		$data_pagamento_boleto = "08";
		
$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
<tr>
<td>
<img alt="Contador Amigo" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo">
<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br /><br />Enviamos <a href="https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor='.$valor_boleto.'&numdoc=' . str_pad($id_usuario_boleto . "082014", 10, '0', STR_PAD_LEFT) . '&sacado=' . trataTxt($linha_dados_empresa['razao_social']) . '&cgccpfsac=&enderecosac=' . trataTxt($linha_dados_empresa['endereco']) . '&numeroendsac=&complementosac=&bairrosac=' . trataTxt($linha_dados_empresa['bairro']) . '&cidadesac=' . trataTxt($linha_dados_empresa['cidade']) . '&cepsac=' . $linha_dados_empresa['cep'] . '&ufsac=' . ($linha_dados_empresa['estado']) . '&datadoc=' . date("d/m/Y") . '&vencto=' . date('d/m/Y',(mktime(0,0,0,date('m'),date('d')+5,date('Y')))) . '&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1" style="color: #369">neste link</a> o boleto de pagamento, referente <br />à sua assinatura mensal do Contador Amigo.<br /><br />
					Para simplificar os pagamentos, você pode sempre optar pelo cartão de crédito. Basta alterar forma de cobrança no link <strong>Dados do Assinante</strong> do portal. A nova opção será válida já para sua próxima fatura.<br /><br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a>
</div>
</td>
</tr>
</table>';

		//include '../mensagens/componente_envio.php';
		include '../mensagens/componente_envio_novo.php';
/*
fernandowiek@gmail.com   ativo. Parcela vence em 25 ago. boleto.
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='fernandowiek@gmail.com' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);


		//Componente de Envio de e-mail.
		$Assinante = $linhaEmail["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);
		$vencimento = "25/08/2014";
		$emailuser = $linhaEmail["email"];
		$assuntoMail = "Boleto a vencer";
		
		// VARIAVEIS QUE SÃO UTILIZADAS NO CORPO DO EMAIL
		
		$sql_dados_empresa = "SELECT * FROM dados_da_empresa WHERE id = " . $linhaEmail["id"];
		$linha_dados_empresa = mysql_fetch_array(mysql_query($sql_dados_empresa));
		
		$id_usuario_boleto = $linhaEmail["id"];
		$valor_boleto = "55,00";
		$mes_boleto = "08";
		$data_pagamento_boleto = "08";
		
$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
<tr>
<td>
<img alt="Contador Amigo" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo">
<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br /><br />Enviamos <a href="https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor='.$valor_boleto.'&numdoc=' . str_pad($id_usuario_boleto . "082014", 10, '0', STR_PAD_LEFT) . '&sacado=' . trataTxt($linha_dados_empresa['razao_social']) . '&cgccpfsac=&enderecosac=' . trataTxt($linha_dados_empresa['endereco']) . '&numeroendsac=&complementosac=&bairrosac=' . trataTxt($linha_dados_empresa['bairro']) . '&cidadesac=' . trataTxt($linha_dados_empresa['cidade']) . '&cepsac=' . $linha_dados_empresa['cep'] . '&ufsac=' . ($linha_dados_empresa['estado']) . '&datadoc=' . date("d/m/Y") . '&vencto=' . date('d/m/Y',(mktime(0,0,0,date('m'),date('d')+5,date('Y')))) . '&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1" style="color: #369">neste link</a> o boleto de pagamento, referente <br />à sua assinatura mensal do Contador Amigo.<br /><br />
					Para simplificar os pagamentos, você pode sempre optar pelo cartão de crédito. Basta alterar forma de cobrança no link <strong>Dados do Assinante</strong> do portal. A nova opção será válida já para sua próxima fatura.<br /><br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a>
</div>
</td>
</tr>
</table>';

		//include '../mensagens/componente_envio.php';
		include '../mensagens/componente_envio_novo.php';
/*
deiacouto@yahoo.com    inativo. Vence em 25 ago. duas perdoadas, outra não paga. Boleto.
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='deiacouto@yahoo.com' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);


		//Componente de Envio de e-mail.
		$Assinante = $linhaEmail["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);
		$vencimento = "25/08/2014";
		$emailuser = $linhaEmail["email"];
		$assuntoMail = "Boleto a vencer";
		
		// VARIAVEIS QUE SÃO UTILIZADAS NO CORPO DO EMAIL
		
		$sql_dados_empresa = "SELECT * FROM dados_da_empresa WHERE id = " . $linhaEmail["id"];
		$linha_dados_empresa = mysql_fetch_array(mysql_query($sql_dados_empresa));
		
		$id_usuario_boleto = $linhaEmail["id"];
		$valor_boleto = "55,00";
		$mes_boleto = "08";
		$data_pagamento_boleto = "08";
		
$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
<tr>
<td>
<img alt="Contador Amigo" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo">
<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br /><br />Enviamos <a href="https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor='.$valor_boleto.'&numdoc=' . str_pad($id_usuario_boleto . "082014", 10, '0', STR_PAD_LEFT) . '&sacado=' . trataTxt($linha_dados_empresa['razao_social']) . '&cgccpfsac=&enderecosac=' . trataTxt($linha_dados_empresa['endereco']) . '&numeroendsac=&complementosac=&bairrosac=' . trataTxt($linha_dados_empresa['bairro']) . '&cidadesac=' . trataTxt($linha_dados_empresa['cidade']) . '&cepsac=' . $linha_dados_empresa['cep'] . '&ufsac=' . ($linha_dados_empresa['estado']) . '&datadoc=' . date("d/m/Y") . '&vencto=' . date('d/m/Y',(mktime(0,0,0,date('m'),date('d')+5,date('Y')))) . '&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1" style="color: #369">neste link</a> o boleto de pagamento, referente <br />à sua assinatura mensal do Contador Amigo.<br /><br />
					Para simplificar os pagamentos, você pode sempre optar pelo cartão de crédito. Basta alterar forma de cobrança no link <strong>Dados do Assinante</strong> do portal. A nova opção será válida já para sua próxima fatura.<br /><br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a>
</div>
</td>
</tr>
</table>';

		//include '../mensagens/componente_envio.php';
		include '../mensagens/componente_envio_novo.php';
/*
alessandro.preisler@hotmail.com  inativo. Vence 25 ago. duas não pagas. Boleto.
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='alessandro.preisler@hotmail.com' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);


		//Componente de Envio de e-mail.
		$Assinante = $linhaEmail["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);
		$vencimento = "25/08/2014";
		$emailuser = $linhaEmail["email"];
		$assuntoMail = "Boleto a vencer";
		
		// VARIAVEIS QUE SÃO UTILIZADAS NO CORPO DO EMAIL
		
		$sql_dados_empresa = "SELECT * FROM dados_da_empresa WHERE id = " . $linhaEmail["id"];
		$linha_dados_empresa = mysql_fetch_array(mysql_query($sql_dados_empresa));
		
		$id_usuario_boleto = $linhaEmail["id"];
		$valor_boleto = "55,00";
		$mes_boleto = "08";
		$data_pagamento_boleto = "08";
		
$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
<tr>
<td>
<img alt="Contador Amigo" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo">
<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br /><br />Enviamos <a href="https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor='.$valor_boleto.'&numdoc=' . str_pad($id_usuario_boleto . "082014", 10, '0', STR_PAD_LEFT) . '&sacado=' . trataTxt($linha_dados_empresa['razao_social']) . '&cgccpfsac=&enderecosac=' . trataTxt($linha_dados_empresa['endereco']) . '&numeroendsac=&complementosac=&bairrosac=' . trataTxt($linha_dados_empresa['bairro']) . '&cidadesac=' . trataTxt($linha_dados_empresa['cidade']) . '&cepsac=' . $linha_dados_empresa['cep'] . '&ufsac=' . ($linha_dados_empresa['estado']) . '&datadoc=' . date("d/m/Y") . '&vencto=' . date('d/m/Y',(mktime(0,0,0,date('m'),date('d')+5,date('Y')))) . '&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1" style="color: #369">neste link</a> o boleto de pagamento, referente <br />à sua assinatura mensal do Contador Amigo.<br /><br />
					Para simplificar os pagamentos, você pode sempre optar pelo cartão de crédito. Basta alterar forma de cobrança no link <strong>Dados do Assinante</strong> do portal. A nova opção será válida já para sua próxima fatura.<br /><br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a>
</div>
</td>
</tr>
</table>';

		//include '../mensagens/componente_envio.php';
		include '../mensagens/componente_envio_novo.php';
/*
rachelcuocolo@hotmail.com   demo. Vence 25 ago.
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='rachelcuocolo@hotmail.com' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);

		//Componente de Envio de e-mail.
		$dia_vencimento = "25";
		$dias_a_vencer = "3";
		$Assinante = $linhaEmail["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);
		$vencimento = "25/08/2014";
		$emailuser = $linhaEmail["email"];		
		$assuntoMail = "Período de avaliação prestes a vencer";

$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
					Informamos que o período gratuito de sua assinatura<br /> do Contador Amigo irá expirar no próximo dia ' . $dia_vencimento . '. Esperamos que o portal tenha atendido suas expectativas e possamos tê-lo como nosso assinante efetivo daqui para frente.<br />
					<br />
					Para confirmar sua adesão, acesse o portal e dirija-se à página <strong>Dados do Assinante</strong> para cadastrar seus dados de cobrança. <br />
					<br />
					Se preferir, clique <a href="https://contadoramigo.websiteseguro.com/minha_conta.php">neste link</a> para acessar seus dados diretamente. Você poderá optar pelo pagamento por cartão ou boleto, conforme sua conveniência. <br /><br /><strong>Contador Amigo</strong>
					<br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a>
				</div>
			</td>
		</tr>
	</tbody>
</table>';

		//include '../mensagens/componente_envio.php';
		include '../mensagens/componente_envio_novo.php';

/*
ricardo@rinisa.com.br    pagou com cartão em 20 ago
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='ricardo@rinisa.com.br' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);

							$Assinante = $linhaEmail["assinante"];
							$AssinanteExplode = explode(" ", $Assinante);
							$emailuser = $linhaEmail["email"];	
							
				
							$assuntoMail = "Aviso de cobrança de assinatura";

$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
				  Efetuamos o débito de R$ 55,00 em seu cartão de crédito, referente à  sua assinatura mensal do Contador Amigo. A nota fiscal será enviada por email dentro de alguns dias.<br />
				  <br />
				  Em caso de dúvida, contate-nos pelo <em>help desk</em> ou no atendimento remoto. Estamos sempre à disposição.<br />
<br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>
<p>
	<br />
	&nbsp;</p>
</body>
</html>';
							//include '../mensagens/componente_envio.php';
							include '../mensagens/componente_envio_novo.php';
							
/*
estevam.leal@gmail.com   pagou com cartão em 20 ago
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='estevam.leal@gmail.com' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);

							$Assinante = $linhaEmail["assinante"];
							$AssinanteExplode = explode(" ", $Assinante);
							$emailuser = $linhaEmail["email"];	
							
				
							$assuntoMail = "Aviso de cobrança de assinatura";

$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
				  Efetuamos o débito de R$ 55,00 em seu cartão de crédito, referente à  sua assinatura mensal do Contador Amigo. A nota fiscal será enviada por email dentro de alguns dias.<br />
				  <br />
				  Em caso de dúvida, contate-nos pelo <em>help desk</em> ou no atendimento remoto. Estamos sempre à disposição.<br />
<br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>
<p>
	<br />
	&nbsp;</p>
</body>
</html>';
							//include '../mensagens/componente_envio.php';
							include '../mensagens/componente_envio_novo.php';
							
/*
fsassoon@gmail.com    destativação de conta em 20 ago. Não pagou em 14 ago. Boleto.
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='fsassoon@gmail.com' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $linhaEmail['assinante'];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $linhaEmail['email'];
				$assuntoMail = "Conta Inativa";



$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
				  Informamos que sua assinatura do Contador Amigo está temporariamente inativa. Nosso sistema verificou <br />
				  uma mensalidade pendente há mais de 5 dias.<br />
				  <br />
				  Para regularizar sua situação, acesse o portal usando seu e-mail e senha. Você será automaticamente redirecionado à página com as instruções de pagamento.<br />
<br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>
<p>
	<br />
	&nbsp;</p>
</body>
</html>';

				//include '../mensagens/componente_envio.php';
				include '../mensagens/componente_envio_novo.php';
				
/*
CIROLLA@GMAIL.COM    demo inativo. Não pagou em 20 ago. Boleto.
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='cirolla@gmail.com' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);
	
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $linhaEmail['assinante'];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $linhaEmail['email'];
				$assuntoMail = "Período de avaliação expirado";

$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
				  Informamos que o período gratuito de sua assinatura<br /> do Contador Amigo acaba de expirar. Esperamos que o portal tenha atendido suas expectativas e possamos <br />tê-lo como nosso assinante efetivo daqui para frente.<br />
				  <br />
				  Para reativar sua conta, acesse o portal usando seu <br />e-mail e senha. Você será automaticamente redirecionado à página com as instruções de pagamento.<br />
<br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>';
				//include '../mensagens/componente_envio.php';
				include '../mensagens/componente_envio_novo.php';

				
/*
sisdatanet@gmail.com    demo inativo. Não pagou em 20 ago. Boleto.
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='sisdatanet@gmail.com' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);
	
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $linhaEmail['assinante'];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $linhaEmail['email'];
				$assuntoMail = "Período de avaliação expirado";

$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
				  Informamos que o período gratuito de sua assinatura<br /> do Contador Amigo acaba de expirar. Esperamos que o portal tenha atendido suas expectativas e possamos <br />tê-lo como nosso assinante efetivo daqui para frente.<br />
				  <br />
				  Para reativar sua conta, acesse o portal usando seu <br />e-mail e senha. Você será automaticamente redirecionado à página com as instruções de pagamento.<br />
<br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>';
				//include '../mensagens/componente_envio.php';
				include '../mensagens/componente_envio_novo.php';

				
/*
rolf@zornig.adv.br    demo inativo. Não pagou em 20 ago. Boleto.
*/
				// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
				$sqlEmail = "SELECT id,assinante, email, status FROM login WHERE email='rolf@zornig.adv.br' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
				$resultadoEmail = mysql_query($sqlEmail)
				or die (mysql_error());
				
				$linhaEmail = mysql_fetch_array($resultadoEmail);
	
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $linhaEmail['assinante'];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $linhaEmail['email'];
				$assuntoMail = "Período de avaliação expirado";

$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
	<tbody>
		<tr>
			<td>
				<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
				<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
					Olá ' . $AssinanteExplode[0] . ', <br />
					<br />
				  Informamos que o período gratuito de sua assinatura<br /> do Contador Amigo acaba de expirar. Esperamos que o portal tenha atendido suas expectativas e possamos <br />tê-lo como nosso assinante efetivo daqui para frente.<br />
				  <br />
				  Para reativar sua conta, acesse o portal usando seu <br />e-mail e senha. Você será automaticamente redirecionado à página com as instruções de pagamento.<br />
<br />
					<strong>Contador Amigo</strong><br />
					<a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
			</td>
		</tr>
	</tbody>
</table>';
				//include '../mensagens/componente_envio.php';
				include '../mensagens/componente_envio_novo.php';

				
/*
adm@voviajar.com.br    EMAIL REPETIDO. VIDE ACIMA.
*/



?>