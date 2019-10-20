<?php 
include '../conect.php';

//mysql_query("INSERT INTO relatorio_cobranca (id, data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago) VALUES ('4433', '2015-09-03', '2015-09-03', 'boleto', '1.2', 'enviado', 55.00)");
// ATRIBUINDO O ARQUIVO À VARIÁVEL
$arquivo = file("arquivos_retorno/IEDCBR1800809201523951.ret");

// PROCESSANDO O RODAPÉ (LINHAS PENULTIMA E ULTIMA(
$total_linhas_arquivo = (int)(substr($arquivo[count($arquivo)-1],23,6));
$total_pagamentos = (($total_linhas_arquivo - 4)/2);


// PROCESSANDO O CABEÇALHO (LINHAS 1 E 2)
$data_pagamento = (substr($arquivo[0],143,8));

$data_pagto = date('Y-m-d',strtotime((substr($data_pagamento,4,4)) . "-" . substr($data_pagamento,2,2) . "-" . substr($data_pagamento,0,2)));
$data_hoje = date('Y-m-d');

//var_dump($arquivo);

echo "<BR>Linhas no arquivo: " . $total_linhas_arquivo . "<BR>";
echo "<BR>Total paramentos: " . $total_pagamentos . "<BR>";

		for($i=0; $i<=($total_linhas_arquivo); $i++){

			$enviarEmail = false;

//			printf("<BR>" . ($total_linhas_arquivo) . "<BR>");

//			printf("<BR> LINHA PROCESSADA: " . $arquivo[$i] . "<BR>");

			// CHEGANDO SE A LINHA EM QUESTÃO É DE PAGAMENTO (POSSUI O "T" NA POSIÇÃO 13)
			if(substr($arquivo[$i],13,1) == "T"){
				// ATRIBUINDO VALORES ÀS VARIÁVEIS QUE SERÃO CADASTRADAS NA TABELA DE COBRANÇA
				//echo '<strong>id_usuario:</strong> ' . (int)(substr($arquivo[$i],44,4)) . "<br>";
				//echo '<strong>mes: </strong>' . (int)(substr($arquivo[$i],48,2)) . "<br>";
				
				$id_user = (int)(substr($arquivo[$i],44,4));
				$mes_boleto = (string)(substr($arquivo[$i],48,2));
				$ano_boleto = (string)(substr($arquivo[$i],50,4));
				$valor_pago = (int)(substr($arquivo[$i],81,13));
				$valor_decimal_pago = (int)(substr($arquivo[$i],94,2));
				
				echo "<BR>SELECT * FROM historico_cobranca WHERE MONTH(data_pagamento) = '" . $mes_boleto . "' AND YEAR(data_pagamento) = '" . $ano_boleto . "' AND id = '" . $id_user . "' AND status_pagamento NOT IN ('pago','perdoado') ORDER BY idHistorico<BR>";

				echo "<BR>INSERT INTO relatorio_cobranca (id, data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago) VALUES ('" . $id_user . "', '" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', 'enviado', " . $valor_pago . ".00)<BR>";

				
			}
			
		}


				$teste = (mysql_fetch_array(mysql_query("SELECT * FROM historico_cobranca WHERE MONTH(data_pagamento) = '09' AND YEAR(data_pagamento) = '2015' AND id = '6082' AND status_pagamento NOT IN ('pago','perdoado') ORDER BY idHistorico")));


	var_dump($teste);


?>