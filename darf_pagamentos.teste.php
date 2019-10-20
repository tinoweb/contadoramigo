<?php include 'header_restrita.php' ?>

<?	
	// include 'datas.class.php';
	function calcularDataVencimento($data){

		$datas = new Datas();
				
		
		$data_aux = explode('-', $data);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';
		$aux = $datas->somarData($data_aux,30);
		
		$data_aux = explode('-',$aux);

		$mes = $data_aux[1];
		$ano = $data_aux[0];

		$data_vencimento = $data_aux[0].'-'.$data_aux[1].'-20';

		$dias = $datas->diferencaData(date("Y-m-d"),$data_vencimento);

		if( $dias > 0 ){
			
		} 

		return $data_vencimento;
	}

function get_nome_mes($numero_mes){
	$arrMonth = array(
		1 => 'janeiro',
		2 => 'fevereiro',
		3 => 'março',
		4 => 'abril',
		5 => 'maio',
		6 => 'junho',
		7 => 'julho',
		8 => 'agosto',
		9 => 'setembro',
		10 => 'outubro',
		11 => 'novembro',
		12 => 'dezembro'
		);
	return $arrMonth[(int)$numero_mes];
}
?>

<div  class="minHeight principal">

<div class="titulo">Impostos e Obrigações</div>

<div class="tituloVermelho">DARF</div>

<div style="width:760px">

<?	
	
	// MONTAGEM DA LISTAGEM DOS PAGAMENTOS
	$sql = "SELECT 
				pgto.id_pagto
				, pgto.valor_bruto
				, pgto.INSS
				, pgto.IR
				, pgto.ISS
				, pgto.valor_liquido
				, pgto.data_emissao
				, pgto.data_pagto  
				, pgto.desconto_dependentes
				, pgto.codigo_servico
				, pgto.descricao_servico
				, case 
					  when pgto.id_lucro <> 0 AND LENGTH(pgto.data_periodo_ini) = 4 then 'Anual' 
					  when pgto.id_lucro <> 0 AND LENGTH(pgto.data_periodo_ini) > 4 then 'Antecipação mensal' 
					  else '' 
				  end periodo
				, case 
					  when pgto.id_autonomo <> 0 then aut.dependentes 
					  when pgto.id_socio <> 0 then socio.dependentes
					  else 0
				  end dependentes
				, case 
					  when pgto.id_autonomo <> 0 then 'Autônomos' 
					  when pgto.id_socio <> 0 then 'pró-labore' 
					  when pgto.id_pj <> 0 then 'pessoa jurídica' 
					  when pgto.id_lucro <> 0 then 'distr. de lucros' 
					  else 'Estagiários' 
				  end tipo
				, case 
					  when pgto.id_autonomo <> 0 then aut.id
						when pgto.id_socio <> 0 then socio.idSocio
					  when pgto.id_pj <> 0 then pj.id 
					  when pgto.id_lucro <> 0 then dl.idSocio
					  else est.id 
				  end id
				, case 
					  when pgto.id_autonomo <> 0 then aut.nome
					  when pgto.id_socio <> 0 then socio.nome
					  when pgto.id_pj <> 0 then pj.nome
					  when pgto.id_lucro <> 0 then dl.nome
					  else est.nome 
				  end nome
				, case 
					  when pgto.id_autonomo <> 0 then aut.cpf 
					  when pgto.id_socio <> 0 then socio.cpf 
					  when pgto.id_pj <> 0 then pj.cnpj 
					  when pgto.id_lucro <> 0 then dl.cpf 
					  else est.cpf
				  end cpf
				, case 
					  when pgto.id_pj <> 0 then pj.op_simples
					  else 0
				  end op_simples
				, case 
					  when pgto.id_autonomo <> 0 then '3' 
					  when pgto.id_socio <> 0 then '2' 
					  when pgto.id_pj <> 0 then '5' 
					  when pgto.id_lucro <> 0 then '1' 
					  else '4' 
				  end ordem
			FROM 
				dados_pagamentos pgto
				left join dados_autonomos aut on pgto.id_autonomo = aut.id
				left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
				left join estagiarios est on pgto.id_estagiario = est.id
				left join dados_pj pj on pgto.id_pj = pj.id
				left join dados_do_responsavel dl on pgto.id_lucro = dl.idSocio
			WHERE 
				pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'";
				
	$resDatas = "";
	if($_POST['mes'] != ''){
		$resDatas .= " AND MONTH(CASE WHEN pgto.id_pj > 0 THEN pgto.data_emissao ELSE pgto.data_pagto END) = '" . $_POST['mes'] . "'"; 
//		$resDatasEmissao .= " pgto.data_emissao >= '" . $dataInicio . "'"; 
	}
	if($_POST['ano'] != ''){
		$resDatas .= " AND YEAR(CASE WHEN pgto.id_pj > 0 THEN pgto.data_emissao ELSE pgto.data_pagto END) = '" . $_POST['ano'] . "'"; 
//		$resDatasEmissao .= " AND pgto.data_emissao <= '" . $dataFim . "'"; 
	}

	$resColuna .= " HAVING 1=1 ";

	$_SESSION['mes_DARF_userSessao'] = $_POST['mes']; // USADA NO TUTORIAL DOS DARF
	$_SESSION['ano_DARF_userSessao'] = $_POST['ano']; // USADA NO TUTORIAL DOS DARF
	$aux_data_emissao = $_POST['ano'].'-'.$_POST['mes'].'-'.date("d");
	// TIRADA DA CATEGORIA EM 01/04/2014
	// SOLICITADA NOVAMENTE EM 10/07/2014
	$resColuna .= " AND tipo in ('pró-labore', 'Autônomos', 'pessoa jurídica')";			

	$resOrdem = " ORDER BY ordem, codigo_servico, data_pagto DESC";
	
//	echo $sql . $resDatas . $resColuna . $resOrdem;
	

	$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
	or die (mysql_error());
	
	$totalGeralIR = 0;

	if(mysql_num_rows($resultado) > 0){
		
		$_SESSION['dados_DARF_userSessao'] = array();
		
?>

Os pagamentos que você cadastrou no período selecionado estão relacionados abaixo. Verifique se faltou incluir algum pró-labore, funcionário, trabalhador autônomo ou pessoas jurídicas que lhe pestaram serviços durante este período. Caso necessário, vá na aba pagamentos e atualize estas informações. Se os pagamentos estiverem todos relacionados, clique em prosseguir.

<div style="clear:both;margin-bottom:20px;"></div>


<?
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
			$idPagto 	= $linha["id_pagto"];
			$id 	= $linha["id"];
			$nome 	= $linha["nome"];
			$periodo 	= $linha["periodo"];
			$tipo 	= $linha["tipo"];
			$cpf 	= $linha["cpf"];
			$dependentes 	= $linha["dependentes"];

			$op_simples 		= $linha["op_simples"];
			$codigo_servico 	= $linha["codigo_servico"];
			$descricao_servico 	= $linha["descricao_servico"];
			
			$desc_dep 	= $linha["desconto_dependentes"];
			
			$valor_bruto 	= $linha["valor_bruto"];
			
			$INSS		 	= $linha["INSS"];
			
			$IR			 	= $linha["IR"];
			
			$ISS		 	= $linha["ISS"];
			
			$valor_liquido 	= $linha["valor_liquido"];
			
			$data_pagto 	= (date("d/m/Y",strtotime($linha['data_pagto'])));
			
			$data_emissao 	= $linha['data_emissao'] != null ? date("d/m/Y",strtotime($linha['data_emissao'])) : "";

			if($tipoAnterior != $tipo || ($codigo_servico != $codigo_servicoAnterior)){

				if($tipoAnterior != ""){
	
					// CONTROLA AS VARIAVEIS DE SESSAO PARA APRESENTAÇÃO DOS DARFs A SEREM GERADOS NA PÁGINA SEGUINTE
//					echo "TIPO: " . $tipoAnterior . " - CODIGO: " . $codigo_servicoAnterior . "<BR>";
					
					if(($tipoAnterior != 'pessoa jurídica') || ($tipoAnterior == 'pessoa jurídica' && $codigo_servicoAnterior != '')){
//						echo "monta array";
						switch($tipoAnterior){
							case 'pró-labore':
								$codigo_servico_array = "0561";
								$descricao_servico_array = "IRRF - Rendimento do trabalho assalariado";
							break;
							case 'Autônomos':
								$codigo_servico_array = "0588";
								$descricao_servico_array = "IRRF - Rendimento do trabalho sem vínculo empregatício";
							break;
							default:
								$codigo_servico_array = $codigo_servicoAnterior;
								$descricao_servico_array = $descricao_servicoAnterior;							
							break;
						}
						array_push($_SESSION['dados_DARF_userSessao'],array('codigo_servico'=>$codigo_servico_array,'descricao_servico'=>$descricao_servico_array,'tipo'=>$tipoAnterior,'valor'=>$total_IR));
//						echo "<BR>";
//						var_dump($_SESSION['dados_DARF_userSessao']);
//						echo "<BR>";
						
					}					
			?>
                        <tr>
                            <th style="background-color: #999; font-weight: normal" colspan="2" align="right">Totais:&nbsp;</th>
                            <th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
                            <th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_IR,2,',','.')?></th>
                        </tr>

	            	  </table>
	            	  
	            	<?php 

						$datas = new Datas();

						$script = "";
						$converter = "";
						if( $datas->diferencaData(date("Y-m-d"),calcularDataVencimento($aux_data_emissao)) > 0 ){
							
							echo '<div style="float: left;margin-top: 3px;margin-right: 10px;color:#a61d00">DARF em atraso. Informe a data em  que pretende pagar o imposto para calculo de juros e correção: </div><input name="data_emprestimo" id="data_darf'.$idPagto.'" class="campoData" type="text" size="10" style="float: left;"><div style="clear:both;margin-bottom:10px;"></div>';
							
							$script = "var data = $('#data_darf".$idPagto."').val();if( data === '' ){alert('Informe a data em que pretende efetuar o pagamento.');$('#data_darf".$idPagto."').focus();return;	} ";
							$converter = 'var converter="&converter=converter";';
							$data_aux = calcularDataVencimento($aux_data_emissao);

						}

					?>
                    <center><button class="enviarDarf<?php echo $idPagto; ?>">Gerar Darf</button></center>
                    <div style="clear:both;"></div>
                    <script>
                    
                    	
                    	$( document ).ready(function() {
                    	    
                    		$(".enviarDarf<?php echo $idPagto; ?>").click(function() {
                    			gerarDarf<?php echo $idPagto; ?>();
                    		});

                	    	function gerarDarf<?php echo $idPagto; ?>(){
                	    		var data = '';
                	    		var data2 = '<?php echo $data_aux; ?>';
                	    		var converter = '';



                	    		<?php 

                	    			echo $script;
                	    			echo $converter;
                	    			if( $converter != '' ){
	                	    			echo "  data = data.split('/'); ";

	                	    			echo "

	                	    				if( parseInt(data[1]) <= 0 || parseInt(data[1]) > 12  ){
			                	    			alert('Informe uma data válida.');
			                	    			return;
			                	    		}
			                	    		if( parseInt(data[0]) <= 0 || parseInt(data[0]) > 31  ){
			                	    			alert('Informe uma data válida.');
			                	    			return;
			                	    		}

	                	    			";

	                	    			echo " if( parseInt(data[0]) < ".date('d')." || parseInt(data[0]) >= 31  ){
				                	    			if( parseInt(data[1]) <= ".date('m')." ){
				                	    				alert('A data não pode ser anterior à atual.');
				                	    				return;
				                	    			}
				                	    		}

				                	    		if( parseInt(data[1]) < ".date('m')." || parseInt(data[1]) > 12  ){
				                	    			if( parseInt(data[2]) <= ".date('Y')." ){
					                	    			alert('A data não pode ser anterior à atual.');
					                	    			return;
					                	    		} 
				                	    		}


				                	    		if( parseInt(data[1]) != ".date('m')."){
				                	    			alert('Mês de pagamento não pode ultrapassar o mês corrente.');
				                	    			return;
				                	    		}

				                	    		if( parseInt(data[2]) < ".date('Y')." ){
				                	    			alert('A data não pode ser anterior à atual.');
				                	    			return;
				                	    		} ";

	                	    			echo " data = data[2]+'-'+data[1]+'-'+data[0]; ";
	                	    		}
	                	    		if( $total_IR == 0 )
	                	    			echo 'alert("Não há Darf a ser recolhido no período.");return;';
                	    		?>


                	    		// aux_erro = data[2]+data[1]+data[0];
                	    		// aux_erro = parseFloat(aux_erro);
                	    		// console.log(aux_erro);
                	    		// <?php $aux_erro = date("Ymd") ;?>

                	    		// if( aux_erro < <?php echo $aux_erro ?> ){
                	    		// 	alert("erro4");
                	    		// 	return;
                	    		// }
                	    		// console.log(data);
                    			abreJanela('gerar-darf-geral.php?data=<?php echo $aux_data_emissao ?>&data2='+data+converter+'&codigo_receita=<?php echo $codigo_servico_array ?>&valor=<?php echo $total_IR ?>','_blank','width=700, height=400, top=150, left=150, scrollbars=yes, resizable=yes');	
                    		}
                    	    
                    	});
                    
                    </script>
                    <br>
                    <br>

                <?
					$total_valor_bruto = 0;
					$total_INSS = 0;
					$total_desc_dep = 0;
					$total_IR = 0;
					$total_ISS = 0;
					$total_valor_liquido = 0;
				
				}
				?>
                <div class="tituloAzulPequeno">
                    <?
					switch($tipo){
						
						case 'pró-labore':
							echo 'Pró Labore: Rendimento do trabalho assalariado';
						break;
						case 'Estagiários':
							echo 'Estagiários';
						break;
						case 'Autônomos':
							echo 'Autônomos: Rendimento do trabalho sem vínculo empregatício';
						break;
						case 'distr. de lucros':
							echo 'Distribuição de Lucros';
						break;
						case 'pessoa jurídica':
							if($codigo_servico != ''){
								echo 'Pessoa Jurídica: ' . $descricao_servico . '';
							}else{
								echo 'Pessoas Jurídicas Isentas';
							}
						break;
						case '':
							echo 'Funcionários: Rendimento do trabalho sem vínculo empregatício';	
						break;
						default:
							echo '';
						break;
						
					}
					?>
                </div>	
				<table width="100%" cellpadding="5" style="margin-bottom:10px;">
			<?
				if($tipo == 'pessoa jurídica'){
?>
                    <tr>
                        <th width="62%">Nome</th>
                        <th width="9%">Data da NF</th>
                        <th width="10%">Valor Bruto</th>
                        <th width="9%">IR</th>
                    </tr>

<?
				}else{
?>
                    <tr>
                        <th width="62%">Nome</th>
                        <th width="9%">Data</th>
                        <th width="10%">Valor Bruto</th>
                        <th width="9%">IR</th>
                    </tr>

<?
				}


            }

?>

            

            <tr>
            
			<?

				if($tipo == 'pessoa jurídica'){
?>
                    <td class="td_calendario"><?=$nome?></td>
                    <td class="td_calendario" align="right"><?=$data_emissao?></td>
                    <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                    <td class="td_calendario" align="right"><?=number_format(($op_simples == 0 ? $IR : 0),2,',','.')?></td>
<?
				}else{
?>
                    <td class="td_calendario"><?=$nome?></td>
                    <td class="td_calendario" align="right"><?=$data_pagto?></td>
                    <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                    <td class="td_calendario" align="right"><?=number_format($IR,2,',','.')?></td>
<?
				}
?>
            </tr>
<?	
		
			$total_desc_dep += $desc_dep;
			$total_INSS += $INSS;

			$total_valor_bruto += $valor_bruto;
			$total_IR += ($op_simples == 0 ? $IR : 0);
			$total_ISS += ($op_simples == 0 ? $ISS : 0);
			$total_valor_liquido += ($op_simples == 0 ? $valor_liquido : $valor_bruto);	

			$totalGeralIR += $total_IR;

//			$total_valor_bruto += $valor_bruto;
//			$total_IR += $IR;
//			$total_ISS += $ISS;
//			$total_valor_liquido += $valor_liquido;

			$tipoAnterior = $tipo;
			$codigo_servicoAnterior 	= $codigo_servico;
			$descricao_servicoAnterior	= $descricao_servico;


			
			// FIM DO LOOP
		}
		
		
				// CONTROLA AS VARIAVEIS DE SESSAO PARA APRESENTAÇÃO DOS DARFs A SEREM GERADOS NA PÁGINA SEGUINTE
//				echo "TIPO: " . $tipoAnterior . " - CODIGO: " . $codigo_servicoAnterior . "<BR>";
				
				if(($tipoAnterior != 'pessoa jurídica') || ($tipoAnterior == 'pessoa jurídica' && $codigo_servicoAnterior != '')){
//					echo "monta array";
					switch($tipoAnterior){
						case 'pró-labore':
							$codigo_servico_array = "0561";
							$descricao_servico_array = "IRRF - Rendimento do trabalho assalariado";
						break;
						case 'Autônomos':
							$codigo_servico_array = "0588";
							$descricao_servico_array = "IRRF - Rendimento do trabalho sem vínculo empregatício";
						break;
						default:
							$codigo_servico_array = $codigo_servico;
							$descricao_servico_array = $descricao_servico;							
						break;
					}
					array_push($_SESSION['dados_DARF_userSessao'],array('codigo_servico'=>$codigo_servico_array,'descricao_servico'=>$descricao_servico_array,'tipo'=>$tipoAnterior,'valor'=>$total_IR));
//					echo "<BR>";
//					var_dump($_SESSION['dados_DARF_userSessao']);
//					echo "<BR>";
					
				}
		
?>
        <tr>
            <th style="background-color: #999; font-weight: normal" colspan="2" align="right">Totais:&nbsp;</th>
            <th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
            <th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_IR,2,',','.')?></th>
        </tr>
        
    </table>
   
    
<!-- <input type="button" id="btVoltar" value="Voltar" /> -->

<!-- <input type="button" id="btProsseguir" value="Prosseguir" /> -->
	<?php 

						$datas = new Datas();

						$script = "";
						$converter = "";
						if( $datas->diferencaData(date("Y-m-d"),calcularDataVencimento($aux_data_emissao)) > 0 ){
							
							echo '<div style="float: left;margin-top: 3px;margin-right: 10px;color:#a61d00">DARF em atraso. Informe a data em  que pretende pagar o imposto para calculo de juros e correção: </div><input name="data_emprestimo" id="data_darf2" class="campoData" type="text" size="10" style="float: left;"><div style="clear:both;margin-bottom:10px;"></div>';
							
							$script = "var data = $('#data_darf2').val();if( data === '' ){alert('Informe a data em que pretende efetuar o pagamento.');$('#data_darf2').focus();return;	} ";
							$converter = 'var converter="&converter=converter";';
							$data_aux = calcularDataVencimento($aux_data_emissao);

						}

					?>
                    <center><button class="enviarDarf2">Gerar Darf</button></center>
                    <div style="clear:both;"></div>
                    <script>
                    
                    	
                    	$( document ).ready(function() {
                    	    
                    		$(".enviarDarf2").click(function() {
                    			gerarDarf2();
                    		});

                	    	function gerarDarf2(){
                	    		var data = '';
                	    		var data2 = '<?php echo $data_aux; ?>';
                	    		var converter = '';
                	    		<?php 

                	    			echo $script;
                	    			echo $converter;
                	    			if( $converter != '' ){
	                	    			echo "  data = data.split('/'); ";

	                	    			echo "

	                	    				
	                	    				if( parseInt(data[1]) <= 0 || parseInt(data[1]) > 12  ){
			                	    			alert('Informe uma data válida.');
			                	    			return;
			                	    		}
			                	    		if( parseInt(data[0]) <= 0 || parseInt(data[0]) > 31  ){
			                	    			alert('Informe uma data válida.');
			                	    			return;
			                	    		}

	                	    			";

	                	    			echo " if( parseInt(data[0]) < ".date('d')." || parseInt(data[0]) >= 31  ){
				                	    			if( parseInt(data[1]) <= ".date('m')." ){
				                	    				alert('A data não pode ser anterior à atual');
				                	    				return;
				                	    			}
				                	    		}
				                	    		if( parseInt(data[1]) < ".date('m')." || parseInt(data[1]) > 12  ){
				                	    			if( parseInt(data[2]) <= ".date('Y')." ){
					                	    			alert('A data não pode ser anterior à atual');
					                	    			return;
					                	    		} 
				                	    		}
				                	    		if( parseInt(data[2]) < ".date('Y')." ){
				                	    			alert('A data não pode ser anterior à atual');
				                	    			return;
				                	    		} ";
	                	    			echo " data = data[2]+'-'+data[1]+'-'+data[0]; ";
	                	    		}

	                	    		if( $total_IR == 0 )
	                	    			echo 'alert("Não há Darf a ser recolhido no período.");return;';

                	    		?>
                	    		// console.log(data);
                    			abreJanela('gerar-darf-geral.php?data=<?php echo $aux_data_emissao ?>&data2='+data+converter+'&codigo_receita=<?php echo $codigo_servico_array ?>&valor=<?php echo $total_IR ?>','_blank','width=700, height=400, top=150, left=150, scrollbars=yes, resizable=yes');	
                    		}
                    	    
                    	});
                    
                    </script>
    
<?
	}else{
?>

<div style="background-color:#FFFFFF; border-color:#CCC; border-style:solid; border-width:1px; padding:10px; margin-bottom:30px">
<span class="destaque">ATENÇÃO:</span><br /><br />
Não foi encontrado nenhum registro de pró-labore, pagamento a autonômo ou a pessoas jurídicas no período selecionado. Vá na aba pagamentos e registre estes valores, para que o sistema verifique se há Darf a ser recolhido.<br />
<br />
Caso não haja realmente nenhum pagamento no período, então não há Darf a ser recolhido.
</div>

<input type="button" id="btVoltar" value="Voltar" />

<?	
	}
	
	
?>
  
</div>
   
<div style="clear: both; height: 30px"></div>                        
<div class="quadro_branco"><span class="destaque">Pagamento sem código de barras:</span> você pode quitar normalmente o DARF pela Internet. Para isso, acesse o site de seu banco, localize a opção Pagamento/Darf e preencha os dados de pagamento com as mesmas informações constantes na guia gerada aqui pelo Contador Amigo.</div>



</div>

<script>
	$(document).ready(function(e) {
        $('#btVoltar').bind('click',function(e){
			e.preventDefault();
			history.go(-1);
		});
        $('#btProsseguir').bind('click',function(e){
			e.preventDefault();
			location.href="darf_gerar.php";
		});
    });
</script>
<div style="clear:both; margin-bottom:10px"></div>

<?php include 'rodape.php' ?>

