<?php 
		
	function trataTxt($var) {

		$var = str_replace("á","a",$var);	
		$var = str_replace("à","a",$var);	
		$var = str_replace("â","a",$var);	
		$var = str_replace("ã","a",$var);	
		$var = str_replace("Á","A",$var);	
		$var = str_replace("À","A",$var);	
		$var = str_replace("Â","A",$var);	
		$var = str_replace("Ã","A",$var);	

		$var = str_replace("é","e",$var);	
		$var = str_replace("è","e",$var);	
		$var = str_replace("ê","e",$var);	
		$var = str_replace("É","E",$var);	
		$var = str_replace("È","E",$var);	
		$var = str_replace("Ê","E",$var);	

		$var = str_replace("í","i",$var);	
		$var = str_replace("ì","i",$var);	
		$var = str_replace("î","i",$var);	
		$var = str_replace("Í","I",$var);	
		$var = str_replace("Ì","I",$var);	
		$var = str_replace("Î","I",$var);	

		$var = str_replace("ó","o",$var);	
		$var = str_replace("ò","o",$var);	
		$var = str_replace("ô","o",$var);	
		$var = str_replace("õ","o",$var);	
		$var = str_replace("Ó","O",$var);	
		$var = str_replace("Ò","O",$var);	
		$var = str_replace("Ô","O",$var);	
		$var = str_replace("Õ","O",$var);	

		$var = str_replace("ú","u",$var);	
		$var = str_replace("ù","u",$var);	
		$var = str_replace("û","u",$var);	
		$var = str_replace("ü","u",$var);	
		$var = str_replace("Ú","U",$var);	
		$var = str_replace("Ù","U",$var);	
		$var = str_replace("Û","U",$var);	
		$var = str_replace("Ü","U",$var);	

		$var = str_replace("ñ","n",$var);	
		$var = str_replace("Ñ","N",$var);	

		$var = str_replace("ç","c",$var);
		$var = str_replace("Ç","C",$var);

		$var = str_replace("&","E",$var);
		
		return $var;
	}

	

	if( isset($_POST['inserirCnaeLivroDiarioRazao']) ){
		include '../conect.php';
		mysql_query("INSERT INTO `livro_diario_custo_despesa`(`tipo`) VALUES ('custo')");
		return mysql_insert_id();
	}
	
	if( isset($_POST['atualizarFeriados']) ){
		include '../conect.php';
		mysql_query("UPDATE agenda_index_feriados set string = '".$_POST['valor']."' WHERE mes = '".$_POST['mes']."' ");
	}

	if( isset($_POST['publicarAgenda']) ){
		include '../conect.php';
		$mes = $_POST['mes'];
		mysql_query("DELETE FROM agenda_index_publicar WHERE 1 ");
		$consulta = mysql_query("SELECT * FROM agenda_index WHERE 1 ");
		while( $objeto_consulta = mysql_fetch_array($consulta) ){
			mysql_query("INSERT INTO `agenda_index_publicar`(`mes`, `dia`, `tipo`) VALUES ( '".$objeto_consulta['mes']."' , '".$objeto_consulta['dia']."' , '".$objeto_consulta['tipo']."' )");
		}
		mysql_query("DELETE FROM agenda_index_feriados_publicar WHERE 1 ");
		$consulta = mysql_query("SELECT * FROM agenda_index_feriados WHERE 1 ");
		while( $objeto_consulta = mysql_fetch_array($consulta) ){
			mysql_query("INSERT INTO `agenda_index_feriados_publicar`(`mes`, `string`) VALUES ( '".$objeto_consulta['mes']."' , '".$objeto_consulta['string']."' )");
		}
	}



	if( isset( $_POST['inserirNovaLinhaMes'] ) ):
		
		include '../conect.php';

		$mes = $_POST['mes'];
		mysql_query("INSERT INTO `agenda_index`( `mes` ) VALUES ( '".$mes."' )");

		echo mysql_insert_id();
	
	endif;

	//Traz as cidades do estado escolhido
	if( isset( $_POST['uf'] ) ):
		
		include '../conect.php';
		
		$consulta = mysql_query("SELECT * FROM estados WHERE sigla = '".$_POST['uf']."' ");
		$objeto=mysql_fetch_array($consulta);

		$consulta = mysql_query("SELECT * FROM cidades WHERE id_uf = '".$objeto['id']."' ");
		while( $objeto=mysql_fetch_array($consulta) ){
			echo '<option value="'.$objeto['cidade'].'">'.$objeto['cidade'].'</option>';
		}

	endif;

	if( isset( $_POST['editar_item'] ) ):
		
		include '../conect.php';

		$id = $_POST['id'];
		$valor = $_POST['valor'];
		$campo = $_POST['campo'];
		$tabela = $_POST['tabela'];

		mysql_query("UPDATE ".$tabela." SET `".$campo."`= '".$valor."' WHERE id = '".$id."' ");
			
	endif;

	if( isset( $_POST['boleto'] ) ):
			
		$id = $_POST['id'];
		$mes_boleto = $_POST['mes_boleto'];
		// $mes_boleto = 10;
		$data_pagamento = $_POST['ano_boleto'];

		// $data_pagamento = 2016;
		$data_proximo_pagamento = $_POST['data_proximo_pagamento'];
		$data_proximo_pagamento = explode('/', $data_proximo_pagamento);
		$data_proximo_pagamento = $data_proximo_pagamento[2].'-'.$data_proximo_pagamento[1].'-'.$data_proximo_pagamento[0];	
		// $data_proximo_pagamento = '10/10/2016';

		$valor_cobrar = str_replace(',', '.', $_POST['valor']);

		function geraTimestamp($data) {
			$partes = explode('-', $data);
			return mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
		}

		if( $_POST['vencimentoOriginal'] != '' ){
			$data_original = explode('/', $_POST['vencimentoOriginal']);
			$data_original = $data_original[2].'-'.$data_original[1].'-'.$data_original[0];	
			// Usa a função criada e pega o timestamp das duas datas:
			$time_inicial = geraTimestamp($data_original);
			$time_final = geraTimestamp($data_proximo_pagamento);
			// Calcula a diferença de segundos entre as duas datas:
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			// Calcula a diferença de dias
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias

			$valor_cobrar = $valor_cobrar + ($dias * 0.22);
		}



		
		// $valor_cobrar = 59;

		include '../conect.php';

		$aux = urlencode(str_pad($id . $mes_boleto . date('y', strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT));

		$consulta = mysql_query("SELECT * FROM boleto WHERE numdoc_get = '".$aux."' ");
		$objeto=mysql_fetch_array($consulta);

		while( $objeto['numdoc_get'] == $aux ) {
			$data_pagamento = rand(2000, 2099);

			$aux = urlencode(str_pad($id . $mes_boleto . date('y', strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT));

			$consulta = mysql_query("SELECT * FROM boleto WHERE numdoc_get = '".$aux."' ");
			$objeto=mysql_fetch_array($consulta);
		}

		

		$consulta = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$id."' ");
		$linha_dados_empresa=mysql_fetch_array($consulta);

		$promo = '';
		if( isset($_POST['promo']) )
			$promo = '&promo=true';

		echo "https://www.contadoramigo.com.br/boleto/boleto.php?identificacao=4843543".$promo."&user=".$id."&gerar_multa=true&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor=" . urlencode($valor_cobrar) . "&numdoc=" . urlencode(str_pad($id . $mes_boleto . date('y', strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT)) . "&sacado=" . urlencode(trataTxt($linha_dados_empresa['sacado'])) . "&cgccpfsac=&enderecosac=" . urlencode(trataTxt($linha_dados_empresa['endereco'])) ."&numeroendsac=" . urlencode(trataTxt($linha_dados_empresa['numero'])) ."&complementosac=&bairrosac=" . urlencode(trataTxt($linha_dados_empresa["bairro"])) ."&cidadesac=" . urlencode(trataTxt($linha_dados_empresa['cidade'])) . "&cepsac=" . urlencode($linha_dados_empresa['cep']) . "&ufsac=" . urlencode(trataTxt($linha_dados_empresa['uf'])) . "&datadoc=" . urlencode(date("d/m/Y")) . "&vencto=" . urlencode(date('d/m/Y', strtotime($data_proximo_pagamento))) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=ContadorAmigo&botoesboleto=1&urltopoloja=&cabecalho=1";
	
	endif;
	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'alterar_resultado_acao' ):
		
		include '../conect.php';

		$id = $_POST['id'];
		$valor = $_POST['valor'];

		$nota_fiscal = '';
		if( $valor == '9.9' || $valor == '2.3' || $valor == '10.1' ) {
		
			$idHistorico = ', `idHistorico` = 0';
			$nota_fiscal = ', `emissao_NF` = 1';
		} else {
			$idHistorico = '';
			$nota_fiscal = ', `emissao_NF` = 0';
		}
		
		$consulta = mysql_query("UPDATE `relatorio_cobranca` SET `resultado_acao` = '".$valor."' ".$nota_fiscal.$idHistorico."  WHERE idRelatorio = '".$id."' ");
	
	endif;
	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'resultado_acao' ):
		$resultado_acao_anterior = $_POST['resultado_acao_anterior'];
		echo '<select class="select_resultado_da_acao"><option value="">Selecione...</option><option value="1.2"> Boleto com sucesso</option><option value="2.1"> Cartão com sucesso</option><option value="2.3"> Cartão não autorizado</option><option value="9.9"> Venda cancelada</option><option value="10.1">Certificado Digital</option>';
		echo '	<script>
					$(".select_resultado_da_acao").change(function() {
						var id = $(this).parent().attr("id");
						var valor = $(this).val();
						if( valor != "" ){
							$.ajax({
								url:"ajax.php"
								, data: "acao=alterar_resultado_acao&id="+id+"&valor="+valor
								, type: "post"
								, async: true
								, cache:false
								, success: function(retorno){
									console.log(retorno);
									location.reload();
								}
							});
						}

					});
					
					$(".select_resultado_da_acao").blur(function() {
						if( $(this).val() === "" ){
							var item = $(this).parent();
							$(item).empty();
							$(item).append("<span>'.$resultado_acao_anterior.'</span>");
							location.reload();
						}
					});
				</script>';
	
	endif;

	if( isset( $_GET['acao'] ) && $_GET['acao'] == 'rascunho_suporte' ):
			

		$codigo = $_GET['codigo'];
		$texto = str_replace("___", "&", $_GET['texto']);

		include '../conect.php';

		$consulta = mysql_query("INSERT INTO `suporte_rascunho`( `codigo`, `texto`) VALUES ( '".$codigo."','".$texto."' )");

		var_dump($_GET);
	
	endif;

	if(isset($_POST['PegaPagamentoCliente'])) {
		
		include '../conect.php';
		
		$teste = $out = false;
		
		if(isset($_POST['idUser']) && isset($_POST['idHistorico'])){
			
			$id = $_POST['idUser'];

			$idHistorico = $_POST['idHistorico'];

			$sqlPagto = "SELECT * FROM relatorio_cobranca 
						WHERE id = ".$id."
						AND (resultado_acao = '1.2' OR resultado_acao = '2.1')
						AND (idHistorico = ".$idHistorico." OR idHistorico = '')";

			$resultado = mysql_query($sqlPagto)	or die (mysql_error());
			
			if(mysql_num_rows($resultado) > 0){

				$out .= '<option value="sem">Selecione</optin>';
				
				while($dados = mysql_fetch_array($resultado)){
								
					if($idHistorico == $dados['idHistorico']){
						$out .= '<option value="'.$dados['idRelatorio'].'" selected>'.date('d/m/y', strtotime($dados['data'])).'</optin>';
					} else {
						$out .= '<option value="'.$dados['idRelatorio'].'">'.date('d/m/y', strtotime($dados['data'])).'</optin>';
					}
					
				}
			}
		}
				
		echo json_encode(array('optionPgto'=>$out, 'teste'=>$teste));
	}
?>