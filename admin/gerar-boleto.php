    <?php
//		ini_set('display_errors',1);
//		ini_set('display_startup_erros',1);
//		error_reporting(E_ALL);

		$dadoscontador = '';
	
		if(isset($_GET['id']) && !empty($_GET['id'])){
			
			$idUser = $_GET['id'];
	
			// Inclui a chamada do arquivo que manipula os dados do cliente.
			require_once('../Model/DadosContratoContadorCliente/DadosContratoContadorClienteData.php');
		
			$objectData = new DadosContratoContadorClienteData();
	
			// Pega os dados do usuário
			$dadosUsuario = $objectData->GetDataDadosCliente($idUser);
	
			// Verifica se houve retorno dos dados do usuário.
			if($dadosUsuario) {
				
				// Verifica se o estado foi informado.
				if($dadosUsuario->getUF()) {
					
					// Pega os dados do contadores referente ao estado do cliente.
					//$dadoscontador = $objectData->GetDataDadosContadorUF($dadosUsuario->getUF());	
					
					$dadoscontador = false;
					
					// Caso não seja encontrado o ele passa para o id do adinaldo. 
					if(!$dadoscontador) {				
	
						// Pega os dados do contador amigo = id 13.
						$dadoscontador = $objectData->GetDataDadosContadorId(13);
					}
				}
			}
		}

		if($dadoscontador) {
			$contadorId = $dadoscontador->getId();
		} else {
			$contadorId = 0;	
		}



	?>	

    <form action="" method="POST" accept-charset="utf-8">
		<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px">	
			<tr>
				<td align="right">
					Tipo Boleto:
				</td>
				<td>
                    <select id="tipe_boleto" name="tipo">
                        <option value="">Selecione</option>
                        <option value="servico_geral">Serviço Geral</option>
                        <option value="ComplementarPremium">Complementar Premium</option>
                        <option value="A_empresa">Abertura e alteração de empresa individual</option>
                        <option value="A_sociedade">Abertura e alteração de sociedade limitada</option>
                        <option value="Simples_DAS">Simples e DAS</option>
                        <option value="CPOM">CPOM</option>
                        <option value="DBE">DBE</option>
                        <option value="decore">DECORE</option>
                        <option value="Gfip_GPS">Envio da Gfip e geração da GPS</option>
                        <option value="F_empresa">Fechamento de empresa individual</option>
                        <option value="F_sociedade">Fechamento de sociedade limitada</option>
                        <option value="MEI-ME">MEI em ME</option>
                        <option value="Rais_negativa">Rais Negativa</option>
                        <option value="Defis">Defis</option>
                        <option value="Dirf">Dirf</option>
                        <option value="regularizacao_emp">Regularização de Empresa</option>
                    </select> 
				</td>
			</tr>
			<tr>
				<td align="right">
					Valor:
				</td>
				<td>
					<input id="valorBoleto" type="text" class="current" name="" value="">
				</td>
			</tr>
			<tr>
				<td align="right">
					Vencimento:
				</td>
				<td>
                	<input type="text" class="campoData campoDatavencimento" name="" value="<?php echo date("d/m/Y", time() + (1 * 86400)) ?>">
					<!--<input type="text" class="campoData campoDatavencimento" name="" value="<?php echo date("d/m/Y") ?>">-->
				</td>
			</tr>
			<tr style="display:none">
				<td>
					Vencimento original:
				</td>
				<td>
					<input type="text" class="campoData campoDataOriginal" name="" value="">
				</td>
			</tr>
			<tr style="display:none">
				<td align="right">
					Mês:
				</td>
				<td>
					<input type="text" class="mes" name="" value="99">
				</td>
			</tr>
			<tr style="display:none">
				<td align="right">
					Ano:
				</td>
				<td>
					<input type="text" class="ano" name="" value="<?php echo rand(2000, 2099); ?>">
				</td>
			</tr>
			<tr style="display:none">
				<td>
					Não Gerar Remessa
				</td>
				<td>
					<input type="checkbox" class="remessa" value="sim" placeholder="">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right" headers="">
					<input class="gerarBoleto" type="button" name="" value="Gerar Boleto">
				</td>
			</tr>
		</table>
		
	</form>
	


	<script>

		$(".gerarBoleto").click(function() {

			var id = '<?php echo $_GET['id']; ?>';
			
			var tipo = $('#tipe_boleto').val();

			var current = $("#valorBoleto").val();
			
			current = current;
			
			if(	current === "" ){
				alert("informe o Valor");
				$(".current").focus();
				return;
			}
			
			var vencimento = $(".campoDatavencimento").val();
			
			if(	vencimento === "" ){
				alert("informe a Data de vencimento");
				$(".campoDatavencimento").focus();
				return;
			}
			
			<?php 
				if( $linhalogin["assinante"] == '' || $linhalogin["email"] == '' || $linhalogin["pref_telefone"] == '' || $linhalogin["telefone"] == '' || $linhalogin['tipo'] == '' || $linhalogin['sacado'] == '' || $linhalogin['tipo'] == '' || $linhalogin['endereco'] == '' || $linhalogin['bairro'] == '' || $linhalogin['cep'] == '' || $linhalogin['forma_pagameto'] == '' ){
					echo 'alert("Preencha todos os dados de cobrança e clique em Salvar para gerar o boleto");return;';
				}
			 ?>
			 
			if(tipo.length > 0) {
			 	
				if(tipo == 'avulso'){
					abreJanela("../boleto.class.php?sistema=sim&tipo="+tipo+"&valor="+current+"&data="+vencimento+"&id_user=<?php echo $_GET['id'] ?>",'_blank','width=700,height=600,top=150,left=150,scrollbars=yes,resizable=yes');
				} else {
					abreJanela("../boleto.class.php?sistema=sim&tipo="+tipo+"&valor="+current+"&data="+vencimento+"&id_user=<?php echo $_GET['id'] ?>&contadorId=<?php echo $contadorId;?>",'_blank','width=700,height=600,top=150,left=150,scrollbars=yes,resizable=yes');
				}
				
			} else {
				 alert('Selecione o tipo do boleto');
			}
		});

	</script>