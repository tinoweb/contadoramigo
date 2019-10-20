<?php include 'header_restrita.php' ?>

<?php 
	$script = ""	;
	if( isset( $_POST['gerarEmprestimo'] ) ):
		
		$tipo = $_POST['tipo'];

		$id_user = $_POST['id_user'];
		$nome_emprestimo = $_POST['nome_emprestimo'];
		$rua_emprestimo = $_POST['rua_emprestimo'];
		$bairro_emprestimo = $_POST['bairro_emprestimo'];
		$cidade_emprestimo = $_POST['cidade_emprestimo'];
		$estado_emprestimo = $_POST['estado_emprestimo'];
		$rg_emprestimo = $_POST['rg_emprestimo'];
		$cpf_emprestimo = $_POST['cpf_emprestimo'];
		$valor_emprestimo = str_replace(",",".",str_replace(".","",$_POST['valor_emprestimo']));
		$valor_liquido_emprestar = str_replace(",",".",str_replace(".","",$_POST['valor_liquido_emprestar']));
		$juros_emprestimo = floatval(str_replace(",",".",str_replace(",",".",$_POST['juros_emprestimo'])))/100;
		$nacionalidade_emprestimo = $_POST['nacionalidade_emprestimo'];
		$profissao_emprestimo = $_POST['profissao_emprestimo'];
		$estado_civil_emprestimo = $_POST['estado_civil_emprestimo'];

		$nome1_testemunha = $_POST['nome1_testemunha'];
		$rg1_testemunha = $_POST['rg1_testemunha'];
		$nome2_testemunha = $_POST['nome2_testemunha'];
		$rg2_testemunha = $_POST['rg2_testemunha'];

		$data_pagamento_emprestimo = $_POST['devolucao_emprestimo'];
		$data_pagamento_emprestimo = explode("/", $data_pagamento_emprestimo);
		$data_pagamento_emprestimo = $data_pagamento_emprestimo[2].'-'.$data_pagamento_emprestimo[1].'-'.$data_pagamento_emprestimo[0];

		$data_emprestimo = $_POST['data_emprestimo'];
		$data_emprestimo = explode("/", $data_emprestimo);
		$data_emprestimo = $data_emprestimo[2].'-'.$data_emprestimo[1].'-'.$data_emprestimo[0];
		
		$IOF_a_ser_recolhido = (isset($_POST['IOF']) && $_POST['IOF'] != 'Isento' ? str_replace(",",".",str_replace(".","",$_POST['IOF'])) : '');
		$IRRF_a_ser_pago = str_replace(",",".",str_replace(".","",$_POST['IRRF']));
		
		$valor_liquido = str_replace(",",".",str_replace(".","",$_POST['valor_liquido']));
		$valor_a_restituir = str_replace(",",".",str_replace(".","",$_POST['valor_a_restituir']));


		$livro_caixa = $_POST['livro_caixa'];

		$values = "

			'".$id_user."',
			'".$nome_emprestimo."',
			'".$tipo."',
			'".$rua_emprestimo."',
			'".$bairro_emprestimo."',
			'".$cidade_emprestimo."',
			'".$estado_emprestimo."',
			'".$rg_emprestimo."',
			'".$cpf_emprestimo."',
			'".$nacionalidade_emprestimo."',
			'".$profissao_emprestimo."',
			'".$estado_civil_emprestimo."',
			'".$valor_emprestimo."',
			'".$juros_emprestimo."',
			'".$data_emprestimo."',
			'".$data_pagamento_emprestimo."',
			'".$nome1_testemunha."',
			'".$rg1_testemunha."',
			'".$nome2_testemunha."',
			'".$rg2_testemunha."',
			'".$IOF_a_ser_recolhido."',
			'".$IRRF_a_ser_pago."',
			'".$valor_liquido."',
			'".$valor_a_restituir."'

		";

		mysql_query("INSERT INTO `emprestimos`(`id_user`, `nome`,  `tipo`, `rua`, `bairro`, `cidade`, `estado`, `rg`, `cpf`, `nacionalidade`, `profissao`, `estado_civil`, `valor`, `juros`, `data`, `data_pagamento`, `nome_testemunha1` , `rg_testemunha1` , `nome_testemunha2` , `rg_testemunha2`, `IOF_a_ser_recolhido`, `IRRF_a_ser_pago`, `valor_liquido`, `valor_a_restituir`) VALUES ( ".$values." )");

		$last_id = mysql_insert_id();

		if( $livro_caixa == 'sim' ){
			
			$ID = $id_user;
			$Valor = $valor_liquido_emprestar;
			$DocumentoNumero = "";
			if( $tipo == 1 ){
				$Descricao = mysql_real_escape_string("Empréstimo de ".$nome_emprestimo);
				$categoria = ", categoria = '" . mysql_real_escape_string("Empréstimo sócio x empresa") . "'";
				$TipoLancamento = "entrada";
			}
			else{
				$Descricao = mysql_real_escape_string("Empréstimo para ".$nome_emprestimo);
				$categoria = ", categoria = '" . mysql_real_escape_string("Empréstimo empresa x sócio") . "'";
				$TipoLancamento = "saida";
			}
			$Data = $data_emprestimo;

			$sql = "INSERT INTO user_" . $ID . "_livro_caixa SET
				data = '$Data'
				, $TipoLancamento = '$Valor'
				, documento_numero = '$DocumentoNumero'
				, descricao = '$Descricao'
				" . $categoria . "
				";
			$resultado = mysql_query($sql)
			or die (mysql_error());

		}

		$script = 'window.open("download_contrato_mutuo.php?id='.$last_id.'&user='.$id_user.'","_blank");';
		$script .= 'location.href = "emprestimos.php";';
	endif;

	if( isset( $_GET['deletar'] ) ):
		
		$id = $_GET['id'];
		$id_user = $_GET['user'];

		mysql_query("DELETE FROM `emprestimos` WHERE id = '".$id."' AND id_user = '".$id_user."' ");
		
		$script = 'location.href = "emprestimos.php";';

	endif;



?>

<script>

	<?php echo $script; ?>

</script>