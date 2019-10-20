<?php 

	include 'header_restrita.php';
	
	function geraTimestamp($data) {
		$partes = explode('-', $data);
		return mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
	}

	function getBotaoPagar($string,$data_pagamento,$valor_cobrar){
		if($string == 'boleto'){
			//Pega os dados do usuario
			$sql_dados_empresa = "SELECT * FROM dados_cobranca WHERE id='" . $_SESSION["id_userSecaoMultiplo"] . "' LIMIT 0, 1";
		    $resultado_dados_empresa = mysql_query($sql_dados_empresa) or die(mysql_error());
		    $linha_dados_empresa = mysql_fetch_array($resultado_dados_empresa);

		    //Define o mes e ano de vencimento
		    $data = explode('-',date("Y-m-d"));
		    $mes_boleto = 99;
		    $data_pagamento = date("Y-m-d");
		    $data_proximo_pagamento = date('Y-m-d', strtotime(date("Y-m-d"). ' + 7 days'));
		    $gerar_multa = '&gerar_multa=false';


		    $numdoc = urlencode(str_pad($_SESSION["id_userSecaoMultiplo"] . $mes_boleto . date('y', strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT));

		    	return "<iframe style='    padding-top: 50px;width: 766px;height:1000px;border: 1px solid #cccccc;' src='https://www.contadoramigo.com.br/boleto/boleto.php?identificacao=4843543&promo=true&user=".$_SESSION["id_userSecaoMultiplo"].$gerar_multa."&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor=" . urlencode($valor_cobrar) . "&numdoc=" . urlencode(str_pad($_SESSION["id_userSecaoMultiplo"] . $mes_boleto . date('y', strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT)) . "&sacado=" . urlencode(trataTxt($linha_dados_empresa['sacado'])) . "&cgccpfsac=&enderecosac=" . urlencode(trataTxt($linha_dados_empresa['endereco'])) . "&numeroendsac=&complementosac=&bairrosac=" . urlencode(trataTxt($linha_dados_empresa["bairro"])) . "&cidadesac=" . urlencode(trataTxt($linha_dados_empresa['cidade'])) . "&cepsac=" . urlencode($linha_dados_empresa['cep']) . "&ufsac=" . urlencode(trataTxt($linha_dados_empresa['uf'])) . "&datadoc=" . urlencode(date("d/m/Y")) . "&vencto=" . urlencode(date('d/m/Y', strtotime($data_proximo_pagamento))) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1'></iframe>";

			}
		}
?>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Promoção</div>
  <div class="tituloVermelho">Certificado Digital GRÁTIS! </div>
<div style="font-size:14px; margin-bottom:20px">
	Quite o boleto abaixo e ative sua assinatura do Contador Amigo por 3 meses. Você será presenteado com um vale para aquisição de um Certificado Digital, 
	modelo E-CNPJ - A1 da Valid Certificadora. Com ele, você poderá  cumprir sozinho ssuas obrigações fiscais junto à Receita Federal e à Previdência Social.
	<br><br>
	Atenção, esta oferta tem validade de 7 dias a contar da data de recebimento do e-mail promocional.</div>
<br>
<?php 
	
		$data_pagamento = array("data_pagamento" => '2016-10-10');
		echo getBotaoPagar('boleto',$data_pagamento,177);
		echo '<br>';
		include 'rodape.php';


?>

</div>