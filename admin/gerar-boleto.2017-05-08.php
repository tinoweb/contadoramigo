	<form action="" method="POST" accept-charset="utf-8">
		<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px">	
			<tr>
				<td align="right">
					Valor:
				</td>
				<td>
					<input type="text" class="current" name="" value="">
				</td>
			</tr>
			<tr>
				<td align="right">
					Vencimento:
				</td>
				<td>
					<input type="text" class="campoData campoDatavencimento" name="" value="<?php echo date("d/m/Y", time() + (2 * 86400)) ?>">
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

			var current = $(".current").val();
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
			 abreJanela("../boleto.class.php?tipo=avulso&valor="+current+"&data="+vencimento+"&id_user=<?php echo $_GET['id'] ?>",'_blank','width=700,height=600,top=150,left=150,scrollbars=yes,resizable=yes');


			



		});

	</script>