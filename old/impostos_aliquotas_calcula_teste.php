<?php 
session_start();

$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
$db = mysql_select_db("contadoramigo");
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

$mostra_lista = false;

$resultado_checa = mysql_query("SELECT * FROM dados_impostos_aliquotas WHERE id = " . $_SESSION["id_empresaSecao"]); // consulta que verifica se os cnaes da tabela com as aliquotas são os mesmos da tabela com os cnaes do periodo

$fator = '0,00';

if(mysql_num_rows($resultado_checa) > 0){ // se a quantidade for a mesma
	while($linha_dados_impostos = mysql_fetch_array($resultado_checa)){
		$faixa = $linha_dados_impostos['faixa_faturamento'];
		$fator = is_numeric(str_replace(',','.',$linha_dados_impostos['fator_r'])) > 0 ? $linha_dados_impostos['fator_r'] : "";
	}
	
	$mostra_lista = false;
}

//	ARRAY CONTENDO TODOS OS CNAES QUE POSSUEM ALGUMA CHECAGEM EXTRA A SER FEITA
$array_cnaes = array(
				'1412-6/02'=>array(
					array('II','')
				)
				,'1413-4/02'=>array(
					array('II','')
				)
				,'1610-2/02'=>array(
					array('II','')
				)
				,'1811-3/01'=>array(
					array('II','')
				)
				,'1811-3/02'=>array(
					array('II','')
				)
				,'1812-1/00'=>array(
					array('II','')
				)
				,'1813-0/01'=>array(
					array('II','')
				)				
				,'1813-0/99'=>array(
					array('II','')
				)				
				,'2539-0/00'=>array(
					array('II','')
				)				
				,'2722-8/02'=>array(
					array('II','')
				)				
				,'6920-6/01'=>array(
					array('III','')
				)
				,'5620-1/02'=>array(
					array('I','Quando estiver vinculada ao fornecimento de alimentos para eventos.')
					,array('III','Quando não estiver vinculada ao fornecimento de alimentos para eventos.')
				)
				,'4330-4/99'=>array(
					array('III','Quando não estiver vinculada a uma empreitada da construção civil.')
					,array('IV','Quando estiver vinculada a uma empreitada da construção civil.')
				)
				,'4330-4/05'=>array(
					array('III','Quando não estiver vinculada a uma empreitada da construção civil.')
					,array('IV','Quando estiver vinculada a uma empreitada da construção civil.')
				)
				,'4330-4/04'=>array(
					array('III','Quando não estiver vinculada a uma empreitada da construção civil.')
					,array('IV','Quando estiver vinculada a uma empreitada da construção civil.')
				)
				,'4330-4/03'=>array(
					array('III','Quando não estiver vinculada a uma empreitada da construção civil.')
					,array('IV','Quando estiver vinculada a uma empreitada da construção civil.')
				)
				,'4330-4/02'=>array(
					array('III','Quando não estiver vinculada a uma empreitada da construção civil.')
					,array('IV','Quando estiver vinculada a uma empreitada da construção civil.')
				)
				,'4330-4/01'=>array(
					array('III','Quando não estiver vinculada a uma empreitada, mas não se tratar de montagem e instalação para feiras e eventos.')
					,array('IV','Quando estiver vinculada a uma empreitada.')
					,array('V','Quando não estiver vinculada a uma empreitada e consistir na montagem de instalações para feiras e eventos.')
				)
				,'4322-3/03'=>array(
					array('III','Quando não estiver vinculada a uma empreitada.')
					,array('IV','Quando estiver vinculada a uma empreitada.')
				)
				,'4322-3/01'=>array(
					array('III','Quando executar apenas serviço de manutenção.')
					,array('IV','Quando estiver vinculada a uma empreitada.')
				)
				,'4321-5/00'=>array(
					array('III','Quando executar apenas serviço de manutenção.')
					,array('IV','Quando estiver vinculada a uma empreitada.')
				)
				,'3240-0/03'=>array(
					array('II','Quando os bens não forem destinados exclusivamente à locação.')
					,array('III','Quando os bens forem destinados exclusivamente à locação.')
				)
				);
		

// pega os cnaes da empresa
$sql_cnaes = "SELECT d.cnae, c.anexo FROM dados_da_empresa_codigos d INNER JOIN cnae c ON d.cnae = c.cnae WHERE d.id='" . $_SESSION["id_empresaSecao"] . "' AND c.anexo != 'x'";
$resultado_cnaes = mysql_query($sql_cnaes)
or die (mysql_error());

$arrCNAEs = array();
while($linha_cnaes = mysql_fetch_array($resultado_cnaes)){
	
	$arrValorAnexos = array();
	
	if(array_key_exists($linha_cnaes['cnae'],$array_cnaes)){
		foreach($array_cnaes[$linha_cnaes['cnae']] as $valor){
			array_push($arrValorAnexos,array($valor[0],$valor[1]));
		}
		$arrAnexos[str_replace("-","",str_replace("/","",$linha_cnaes['cnae']))] = $arrValorAnexos;
		
	}else{
		$arrAnexos[str_replace("-","",str_replace("/","",$linha_cnaes['cnae']))] = array(array($linha_cnaes['anexo'],''));
	}
	
	array_push($arrCNAEs,str_replace("-","",str_replace("/","",$linha_cnaes['cnae'])));
	
}

?>

<?php include 'header_restrita.php' ;?>

<!--valida preenchimento das perguntas realtivas ao passo 7 e envia para a página simples_orientacoes_retencao.php -->
<script type="text/javascript">
	
	<?
	//if(!$mostra_lista){  // somente montar as arrays javascript se os dados não estiverem gravados no banco de dados
	
		
		
		// MONTANDO ARRAY COM AS ALIQUOTAS DO ANEXO I
		$sql_tabelaI = "SELECT * FROM tabelaI ORDER BY aliquota";
		$resultado_tabelaI = mysql_query($sql_tabelaI)
		or die (mysql_error());
		// EXECUTA CONSULTA
		$arr_tabelaI = array(); // CRIANDO UM ARRAY PHP
		if(mysql_num_rows($resultado_tabelaI) > 0){
			echo "
				var Arr_TabelaI = [];
				";
			while($linha_tabelaI = mysql_fetch_array($resultado_tabelaI)){ // LOOP NO RESULTADO
				array_push($arr_tabelaI,
					array(
						'faixa'=>$linha_tabelaI['faixa']
						,'aliquota'=>(float)$linha_tabelaI['aliquota']
						,'irpj'=>(float)$linha_tabelaI['irpj']
						,'csll'=>(float)$linha_tabelaI['csll']
						,'cofins'=>(float)$linha_tabelaI['cofins']
						,'pis'=>(float)$linha_tabelaI['pis']
						,'cpp'=>(float)$linha_tabelaI['cpp']
						,'icms'=>(float)$linha_tabelaI['icms']
					)
				);// INSERINDO NO ARRAY PHP DE CHAVES "FAIXA", "ALIQUOTA", "IRPJ", "CSLL", "COFINS", "PIS", "CPP", "ISS" OS RESPECTIVOS VALORES
			}
	
			foreach($arr_tabelaI as $chave => $AnexoI){ // PERCORRENDO A ARRAY PHP PARA MONTAR A ARRAY JAVASCRIPT
	
				echo 'Arr_TabelaI.push(Array("'.$AnexoI['faixa'].'",'.$AnexoI['aliquota'].','.$AnexoI['irpj'].','.$AnexoI['csll'].','.$AnexoI['cofins'].','.$AnexoI['pis'].','.$AnexoI['cpp'].','.$AnexoI['icms'].'));' . "\n"; // ARRAY MULTIPLA DE 8 POSICOES
	
			}
	
		}


	
		// MONTANDO ARRAY COM AS ALIQUOTAS DO ANEXO II
		$sql_tabelaII = "SELECT * FROM tabelaII ORDER BY aliquota";
		$resultado_tabelaII = mysql_query($sql_tabelaII)
		or die (mysql_error());
		// EXECUTA CONSULTA
		$arr_tabelaII = array(); // CRIANDO UM ARRAY PHP
		if(mysql_num_rows($resultado_tabelaII) > 0){
			echo "
				var Arr_TabelaII = [];
				";
			while($linha_tabelaII = mysql_fetch_array($resultado_tabelaII)){ // LOOP NO RESULTADO
				array_push($arr_tabelaII,
					array(
						'faixa'=>$linha_tabelaII['faixa']
						,'aliquota'=>(float)$linha_tabelaII['aliquota']
						,'irpj'=>(float)$linha_tabelaII['irpj']
						,'csll'=>(float)$linha_tabelaII['csll']
						,'cofins'=>(float)$linha_tabelaII['cofins']
						,'pis'=>(float)$linha_tabelaII['pis']
						,'cpp'=>(float)$linha_tabelaII['cpp']
						,'icms'=>(float)$linha_tabelaII['icms']
						,'ipi'=>(float)$linha_tabelaII['ipi']
					)
				);// INSERINDO NO ARRAY PHP DE CHAVES "FAIXA", "ALIQUOTA", "IRPJ", "CSLL", "COFINS", "PIS", "CPP", "ISS" OS RESPECTIVOS VALORES
			}
	
			foreach($arr_tabelaII as $chave => $AnexoII){ // PERCORRENDO A ARRAY PHP PARA MONTAR A ARRAY JAVASCRIPT
	
				echo 'Arr_TabelaII.push(Array("'.$AnexoII['faixa'].'",'.$AnexoII['aliquota'].','.$AnexoII['irpj'].','.$AnexoII['csll'].','.$AnexoII['cofins'].','.$AnexoII['pis'].','.$AnexoII['cpp'].','.$AnexoII['icms'].','.$AnexoII['ipi'].'));' . "\n"; // ARRAY MULTIPLA DE 9 POSICOES
	
			}
	
		}

	
	
		// MONTANDO ARRAY COM AS ALIQUOTAS DO ANEXO III
		$sql_tabelaIII = "SELECT * FROM tabelaIII ORDER BY aliquota";
		$resultado_tabelaIII = mysql_query($sql_tabelaIII)
		or die (mysql_error());
		// EXECUTA CONSULTA
		$arr_tabelaIII = array(); // CRIANDO UM ARRAY PHP
		if(mysql_num_rows($resultado_tabelaIII) > 0){
			echo "
				var Arr_TabelaIII = [];
				";
			while($linha_tabelaIII = mysql_fetch_array($resultado_tabelaIII)){ // LOOP NO RESULTADO
				array_push($arr_tabelaIII,
					array(
						'faixa'=>$linha_tabelaIII['faixa']
						,'aliquota'=>(float)$linha_tabelaIII['aliquota']
						,'irpj'=>(float)$linha_tabelaIII['irpj']
						,'csll'=>(float)$linha_tabelaIII['csll']
						,'cofins'=>(float)$linha_tabelaIII['cofins']
						,'pis'=>(float)$linha_tabelaIII['pis']
						,'cpp'=>(float)$linha_tabelaIII['cpp']
						,'iss'=>(float)$linha_tabelaIII['iss']
					)
				);// INSERINDO NO ARRAY PHP DE CHAVES "FAIXA", "ALIQUOTA", "IRPJ", "CSLL", "COFINS", "PIS", "CPP", "ISS" OS RESPECTIVOS VALORES
			}
	
			foreach($arr_tabelaIII as $chave => $AnexoIII){ // PERCORRENDO A ARRAY PHP PARA MONTAR A ARRAY JAVASCRIPT
	
				echo 'Arr_TabelaIII.push(Array("'.$AnexoIII['faixa'].'",'.$AnexoIII['aliquota'].','.$AnexoIII['irpj'].','.$AnexoIII['csll'].','.$AnexoIII['cofins'].','.$AnexoIII['pis'].','.$AnexoIII['cpp'].','.$AnexoIII['iss'].'));' . "\n"; // ARRAY MULTIPLA DE 8 POSICOES
	
			}
	
		}

	
		// MONTANDO ARRAY COM AS ALIQUOTAS DO ANEXO IV
		$sql_tabelaIV = "SELECT * FROM tabelaIV ORDER BY aliquota";
		$resultado_tabelaIV = mysql_query($sql_tabelaIV)
		or die (mysql_error());
		// EXECUTA CONSULTA
		$arr_tabelaIV = array(); // CRIANDO UM ARRAY PHP
		if(mysql_num_rows($resultado_tabelaIV) > 0){
			echo "
				var Arr_TabelaIV = [];
				";
			while($linha_tabelaIV = mysql_fetch_array($resultado_tabelaIV)){ // LOOP NO RESULTADO
				array_push($arr_tabelaIV,
					array(
						'faixa'=>$linha_tabelaIV['faixa']
						,'aliquota'=>(float)$linha_tabelaIV['aliquota']
						,'irpj'=>(float)$linha_tabelaIV['irpj']
						,'csll'=>(float)$linha_tabelaIV['csll']
						,'cofins'=>(float)$linha_tabelaIV['cofins']
						,'pis'=>(float)$linha_tabelaIV['pis']
						,'iss'=>(float)$linha_tabelaIV['iss']
					)
				);// INSERINDO NO ARRAY PHP DE CHAVES "FAIXA", "ALIQUOTA", "IRPJ", "CSLL", "COFINS", "PIS", "ISS" OS RESPECTIVOS VALORES
			}
	
			foreach($arr_tabelaIV as $chave => $AnexoIV){ // PERCORRENDO A ARRAY PHP PARA MONTAR A ARRAY JAVASCRIPT
	
				echo 'Arr_TabelaIV.push(Array("'.$AnexoIV['faixa'].'",'.$AnexoIV['aliquota'].','.$AnexoIV['irpj'].','.$AnexoIV['csll'].','.$AnexoIV['cofins'].','.$AnexoIV['pis'].','.$AnexoIV['iss'].'));' . "\n"; // ARRAY MULTIPLA DE 7 POSICOES
	
			}
	
		}


	
		// MONTANDO ARRAY COM AS ALIQUOTAS DO ANEXO V
		$sql_tabelaV = "SELECT * FROM tabelaV ORDER BY `r<10`";
		$resultado_tabelaV = mysql_query($sql_tabelaV)
		or die (mysql_error());
		// EXECUTA CONSULTA
		$arr_tabelaV = array(); // CRIANDO UM ARRAY PHP
		if(mysql_num_rows($resultado_tabelaV) > 0){
			echo "
				var Arr_TabelaV = [];
				";
			while($linha_tabelaV = mysql_fetch_array($resultado_tabelaV)){ // LOOP NO RESULTADO
				array_push($arr_tabelaV,
					array(
						'faixa'=>$linha_tabelaV['faixa']
						,'r1'=>(float)$linha_tabelaV['r<10']
						,'r2'=>(float)$linha_tabelaV['r=10 e r<15']
						,'r3'=>(float)$linha_tabelaV['r=15 e r<20']
						,'r4'=>(float)$linha_tabelaV['r=20 e r<25']
						,'r5'=>(float)$linha_tabelaV['r=25 e r<30']
						,'r6'=>(float)$linha_tabelaV['r=30 e r<35']
						,'r7'=>(float)$linha_tabelaV['r=35 e r<40']
						,'r8'=>(float)$linha_tabelaV['r>=40']
					)
				);// INSERINDO NO ARRAY PHP DE CHAVES "FAIXA", etc...
			}
	
			foreach($arr_tabelaV as $chave => $AnexoV){ // PERCORRENDO A ARRAY PHP PARA MONTAR A ARRAY JAVASCRIPT
	
				echo 'Arr_TabelaV.push(Array("'.$AnexoV['faixa'].'",'.$AnexoV['r1'].','.$AnexoV['r2'].','.$AnexoV['r3'].','.$AnexoV['r4'].','.$AnexoV['r5'].','.$AnexoV['r6'].','.$AnexoV['r7'].','.$AnexoV['r8'].'));' . "\n"; // ARRAY MULTIPLA DE 9 POSICOES
	
			}
	
		}



	
		// MONTANDO ARRAY COM AS ALIQUOTAS DO ANEXO VI
		$sql_tabelaVI = "SELECT * FROM tabelaVI ORDER BY aliquota";
		$resultado_tabelaVI = mysql_query($sql_tabelaVI)
		or die (mysql_error());
		// EXECUTA CONSULTA
		$arr_tabelaVI = array(); // CRIANDO UM ARRAY PHP
		if(mysql_num_rows($resultado_tabelaVI) > 0){
			echo "
				var Arr_TabelaVI = [];
				";
			while($linha_tabelaVI = mysql_fetch_array($resultado_tabelaVI)){ // LOOP NO RESULTADO
				array_push($arr_tabelaVI,
					array(
						'faixa'=>$linha_tabelaVI['faixa']
						,'aliquota'=>(float)$linha_tabelaVI['aliquota']
						,'irpj'=>(float)$linha_tabelaVI['irpj']
						,'iss'=>(float)$linha_tabelaVI['iss']
					)
				);// INSERINDO NO ARRAY PHP DE CHAVES "FAIXA", "ALIQUOTA", "IRPJ", "ISS" OS RESPECTIVOS VALORES
			}
	
			foreach($arr_tabelaVI as $chave => $AnexoVI){ // PERCORRENDO A ARRAY PHP PARA MONTAR A ARRAY JAVASCRIPT
	
				echo 'Arr_TabelaVI.push(Array("'.$AnexoVI['faixa'].'",'.$AnexoVI['aliquota'].','.$AnexoVI['irpj'].','.$AnexoVI['iss'].'));' . "\n"; // ARRAY MULTIPLA DE 4 POSICOES
	
			}
	
		}
		
		
		
	//}
	?>
	
	$(document).ready(function(e) {
	
/*		$("#btnContinuar").mouseenter(function() {
			$(this).css("background-color", "#a61d00");
		}).mouseleave(function(){
			$(this).css("background-color", "#024a68");
		});

		$("#btnVoltar").mouseenter(function() {
			$(this).css("background-color", "#a61d00");
		}).mouseleave(function(){
			$(this).css("background-color", "#024a68");
		});*/
			
		$('#btnVoltar').click(function(){
			history.go(-1);
		});
	
		$('input[id^="atividade"]').click(function(){
			$(this).attr('checked',true);
		});

		<? // if(!$mostra_lista){ ?>

		$('#btCalculaAliquotas').bind('click',function(e){
			e.preventDefault();
			var faixa_selecionada = ($('#selFaixa>option:selected').index()); // pegando o indice da faixa selecionada

			var separador = " - ";
			
			//if($(".divAliquotasV").size() && $('#txtPorcentagem').val() == ""){
			//	alert('Preencha o fator r!');
			//	$('#txtPorcentagem').focus();
			//	return false;
			//}
			
			//checando se existe a div do anexo I
			if($(".divAliquotasI").size()){
				//var html_anexoI = "Aliquota: " + Arr_TabelaI[faixa_selecionada][1].toFixed(2).replace('.',',') + "%" + separador;
				var html_anexoI = "IRPJ: " + Arr_TabelaI[faixa_selecionada][2].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoI += "CSLL: " + Arr_TabelaI[faixa_selecionada][3].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoI += "COFINS: " + Arr_TabelaI[faixa_selecionada][4].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoI += "PIS: " + Arr_TabelaI[faixa_selecionada][5].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoI += "CPP: " + Arr_TabelaI[faixa_selecionada][6].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoI += "ICMS: " + Arr_TabelaI[faixa_selecionada][7].toFixed(2).replace('.',',') + "%";
				
				$(".divAliquotasI").html('Alíquotas de impostos: ' + html_anexoI);
			}


			//checando se existe a div do anexo II
			if($(".divAliquotasII").size()){
				//var html_anexoII = "Aliquota: " + Arr_TabelaII[faixa_selecionada][1].toFixed(2).replace('.',',') + "%" + separador;
				var html_anexoII = "IRPJ: " + Arr_TabelaII[faixa_selecionada][2].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoII += "CSLL: " + Arr_TabelaII[faixa_selecionada][3].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoII += "COFINS: " + Arr_TabelaII[faixa_selecionada][4].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoII += "PIS: " + Arr_TabelaII[faixa_selecionada][5].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoII += "CPP: " + Arr_TabelaII[faixa_selecionada][6].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoII += "ICMS: " + Arr_TabelaII[faixa_selecionada][7].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoII += "IPI: " + Arr_TabelaII[faixa_selecionada][8].toFixed(2).replace('.',',') + "%";
				
				$(".divAliquotasII").html('Alíquotas de impostos: ' + html_anexoII);
			}


			//checando se existe a div do anexo III
			if($(".divAliquotasIII").size()){
				//var html_anexoIII = "Aliquota: " + Arr_TabelaIII[faixa_selecionada][1].toFixed(2).replace('.',',') + "%" + separador;
				var html_anexoIII = "IRPJ: " + Arr_TabelaIII[faixa_selecionada][2].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoIII += "CSLL: " + Arr_TabelaIII[faixa_selecionada][3].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoIII += "COFINS: " + Arr_TabelaIII[faixa_selecionada][4].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoIII += "PIS: " + Arr_TabelaIII[faixa_selecionada][5].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoIII += "CPP: " + Arr_TabelaIII[faixa_selecionada][6].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoIII += "ISS: " + Arr_TabelaIII[faixa_selecionada][7].toFixed(2).replace('.',',') + "%";
				
				$(".divAliquotasIII").html('Alíquotas de impostos: ' + html_anexoIII);
			}

			//checando se existe a div do anexo IV
			if($(".divAliquotasIV").size()){
				//var html_anexoIV = "Aliquota: " + Arr_TabelaIV[faixa_selecionada][1].toFixed(2).replace('.',',') + "%" + separador;
				var html_anexoIV = "IRPJ: " + Arr_TabelaIV[faixa_selecionada][2].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoIV += "CSLL: " + Arr_TabelaIV[faixa_selecionada][3].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoIV += "COFINS: " + Arr_TabelaIV[faixa_selecionada][4].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoIV += "PIS: " + Arr_TabelaIV[faixa_selecionada][5].toFixed(2).replace('.',',') + "%" + separador;
				html_anexoIV += "ISS: " + Arr_TabelaIV[faixa_selecionada][6].toFixed(2).replace('.',',') + "%";
				
				$(".divAliquotasIV").html('Alíquotas de impostos: ' + html_anexoIV);
			}

			//checando se existe a div do anexo V
			if($(".divAliquotasV").size()){

				var r = (parseFloat($('#txtPorcentagem').val().replace(',','.')));

				if(r > 0){
	
					if(r > 1){
						r = 1;
					}
					
					var indice_porcentagem = 0;
					if(r < 0.10){
						indice_porcentagem = 1;
					}else{
						if(r >= 0.10 && r < 0.15){
							indice_porcentagem = 2;
						} else {
							if(r >= 0.15 && r < 0.20){
								indice_porcentagem = 3;
							} else {
								if(r >= 0.20 && r < 0.25){
									indice_porcentagem = 4;
								} else {
									if(r >= 0.25 && r < 0.30){
										indice_porcentagem = 5;
									} else {
										if(r >= 0.30 && r < 0.35){
											indice_porcentagem = 6;
										} else {
											if(r >= 0.35 && r < 0.40){
												indice_porcentagem = 7;
											} else {
												if(r >= 0.40){
													indice_porcentagem = 8;
												}
											}
										}
									}
								}
							}
						}
					}
					
					//var porcentagem_pagtos = $('#selPorcentagem>option:selected').index(); // somente para anexo 5, saber a porcentagem dos pagamentos
					
					var aliquota = (Arr_TabelaV[faixa_selecionada][indice_porcentagem]);
					
					var Arr_FatoresFaixas = [0.9,0.875,0.85,0.825,0.8,0.775,0.75,0.725,0.7,0.675,0.65,0.625,0.6,0.575,0.55,0.525,0.5,0.475,0.45,0.425];
								
	//				var r = Arr_TabelaV[faixa_selecionada][porcentagem_pagtos+1];
	//				r = 0.03;
	//alert(r);
					var N = ((r) / 0.004);
	
					if(N > 100){ N = 100}
					
					var P = (0.1 / r);
					if(P > 1){ P = 1}
	
					var I = (N * Arr_FatoresFaixas[faixa_selecionada]);
					var CPP = (aliquota / 100) * (N * Arr_FatoresFaixas[faixa_selecionada]);
	
					var J = (0.75 * (100-I) * P);
					var IRPJ = (aliquota / 100) * (0.75 * (100-I) * P);
	
					var K = (0.25 * (100-I) * P);
					var CSLL = (aliquota / 100) * (0.25 * (100-I) * P);
					
					var L = (0.75 * (100-I-J-K));
					var COFINS = (aliquota / 100) * (0.75 * (100-I-J-K));
					
					var M = (100-I-J-K-L);
					var PIS = (aliquota / 100) * (100-I-J-K-L);
	
					var 
					html_anexoV = "IRPJ: " + IRPJ.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoV += "CSLL: " + CSLL.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoV += "COFINS: " + COFINS.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoV += "PIS: " + PIS.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoV += "CPP: " + CPP.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoV += "ISS: " + Arr_TabelaIV[faixa_selecionada][6].toFixed(2).replace('.',',') + "%";
					
					$(".divAliquotasV").html('Alíquotas de impostos: ' + html_anexoV);
				
				} else {
					
					$(".divAliquotasV").html('Alíquotas não disponíveis. Falta informar o fator R.');
					
				}
				
			}
			
			
			//checando se existe a div do anexo VI
			if($(".divAliquotasVI").size()){

				var r = (parseFloat($('#txtPorcentagem').val().replace(',','.')));

				if(r > 0){
					
					//var porcentagem_pagtos = $('#selPorcentagem>option:selected').index(); // somente para anexo 5, saber a porcentagem dos pagamentos
					
					var aliquota = (Arr_TabelaVI[faixa_selecionada][2]);
					
					var Arr_FatoresFaixas = [0.9,0.875,0.85,0.825,0.8,0.775,0.75,0.725,0.7,0.675,0.65,0.625,0.6,0.575,0.55,0.525,0.5,0.475,0.45,0.425];
								
	//				var r = Arr_TabelaV[faixa_selecionada][porcentagem_pagtos+1];
	//				r = 0.03;
	//alert(r);
					var N = ((r) / 0.004);
	
					if(N > 100){ N = 100}
					
					var P = (0.1 / r);
					if(P > 1){ P = 1}
	
					var I = (N * Arr_FatoresFaixas[faixa_selecionada]);
					var CPP = (aliquota / 100) * (N * Arr_FatoresFaixas[faixa_selecionada]);
	
					var J = (0.75 * (100-I) * P);
					var IRPJ = (aliquota / 100) * (0.75 * (100-I) * P);
	
					var K = (0.25 * (100-I) * P);
					var CSLL = (aliquota / 100) * (0.25 * (100-I) * P);
					
					var L = (0.75 * (100-I-J-K));
					var COFINS = (aliquota / 100) * (0.75 * (100-I-J-K));
					
					var M = (100-I-J-K-L);
					var PIS = (aliquota / 100) * (100-I-J-K-L);
	
					var 
					html_anexoVI = "IRPJ: " + IRPJ.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoVI += "CSLL: " + CSLL.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoVI += "COFINS: " + COFINS.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoVI += "PIS: " + PIS.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoVI += "CPP: " + CPP.toFixed(2).replace('.',',') + "%" + separador;
					html_anexoVI += "ISS: " + Arr_TabelaVI[faixa_selecionada][3].toFixed(2).replace('.',',') + "%";
					
					$(".divAliquotasVI").html('Alíquotas de impostos: ' + html_anexoVI);
				
				} else {
					
					$(".divAliquotasVI").html('Alíquotas não disponíveis. Falta informar o fator R.');
					
				}
				
			}
			
			
			
						
//			$("div[class^='divAliquotas']").css('color','#024a68');
//			$("#form_aliquotas").css('display','none');
			$("#lista_aliquotas").css('display','block');

			if($('#txtPorcentagem').val()){
				var fator = $('#txtPorcentagem').val();
			}else{
				var fator = 0;
			}
					
			$.ajax({
				url:"impostos_aliquotas_gravar.php"
				,type:"POST"
				,data:"id=<?=$_SESSION["id_empresaSecao"]?>&faixa=" + $('#selFaixa>option:selected').text() + "&fator="+ fator
				,cache: false
				,success: function(data){
					if(data == '1'){
						//alert('Estes dados foram cadastrados no banco de dados.');
					}
				}
			});

		});
		<? // } ?>



		$('#btLimpaDados').bind('click',function(e){
			e.preventDefault();

			$.ajax({
				url:"impostos_aliquotas_excluir.php"
				,type:"POST"
				, cache: false
				,success: function(data){
					if(data == '1'){
						//
						location.href="impostos_aliquotas.php";
					}
				}
			});
			
		});


	});

</script>

<div class="principal">

    <!--BALLOM FATOR R -->
    <div style="width:310px; position:absolute; margin-left:170px; margin-top:45px; display:none;" id="fator_r">
    <div style="width:8px; position:absolute; margin-left:280px; margin-top:12px"><a href="javascript:fechaDiv('fator_r')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td>
        </tr>
        <tr>
          <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
          <td width="285" bgcolor="#ffff99" valign="top"><div style="width:245px; margin-top:5px; margin-left:20px; font-size:12px">
          <strong>Fator R</strong>.</div></td>
          <td background="images/balloon_fundo_dir.png" width="7"></td>
        </tr>
        <tr>
          <td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td>
        </tr>
      </table>
    </div>
    <!--FIM DO BALLOOM FATOR R -->
    

	<span class="titulo">Alíquotas de Impostos</span><br /><br />

		<div id="form_aliquotas" style="margin-bottom:5px;">

        
        	<div style="margin:0 10px 10px 0;float: left;padding:5px 0 0 0">Com a entrada em vigor da <strong>Lei da Transparência Fiscal</strong>, agora todas as empresas precisam discriminar em suas notas fiscais os valores ou alíquotas dos impostos embutidos em seu preço. Para ajudá-lo nesse processo, o Contador Amigo criou este aplicativo.  Selecione a faixa de faturamento, clique em <strong>calcular impostos</strong> e você conhecerá as alíquotas incidentes para cada atividade de sua empresa. <br />
<br /></div>
            <div style="clear: both; height: 10px"></div>

<?
			if($arrAnexos){
?>
                <div style="margin:0 10px 10px 0;float: left;padding:5px 0 0 0">
                Faturamento da empresa nos últimos 12 meses: R$ 
                <select name="selFaixa" id="selFaixa">
                            <?
                            $rsFaixas = mysql_query("SELECT faixa FROM tabelaIII order by aliquota");
                            while($faixas = mysql_fetch_array($rsFaixas)){ ?>
                                    <option value="<?=$faixas['faixa']?>" <?=$faixas['faixa'] == $faixa ? 'selected' : ''?>><?=$faixas['faixa']?></option>
                            <? } ?>
                            </select>
                            
                &nbsp;&nbsp;&nbsp;&nbsp;Não sabe o faturamento da sua empresa? Veja <a href="faturamento_12meses.php">aqui</a> como obtê-lo. </div>
                <div style="clear: both; height: 10px"></div>
    
			<?
				$mostra_campo_fator = false;
				foreach($arrAnexos as $array){
					foreach($array as $valor){
						if($valor[0] == "V" || $valor[0] == "VI"){
							$mostra_campo_fator = true;
						}
					}
				}
				if($mostra_campo_fator){
            ?>

                <div style="background-color:#FFFFFF; border-color:#CCC; border-style:solid; border-width:1px; padding:10px; margin-bottom:30px">
                <div class="destaque" style="clear:both;margin-bottom:10px;">Fator R:</div>
                <div style="margin-bottom:20px;">
                Pelo menos uma atividade de sua empresa se enquadra em uma tabela de impostos que varia  de acordo com os valores pagos  a título  de pró-labore e salários. Para calcular corretamente as alíquotas desta(s) atividade(s), é preciso informar um índice, chamado <strong>Fator R</strong>, que você pode encontrar no extrato de sua última DAS. </div>
    
    Digite o Fator R: 
    <input type="text" name="txtPorcentagem" id="txtPorcentagem" class="current" maxlength="5" value="<?=$fator?>" size="10" /> (0,00)&nbsp;&nbsp;&nbsp;&nbsp;
                Veja <span style="margin-bottom:20px;"><a href="fator_r.php">aqui</a> como encontrar o <strong>Fator R</strong> de sua empresa. </span><br />
    <br />
    <strong>Observação:</strong> Você pode prosseguir sem informar o fator R, porém as alíquotas das atividades que dependem deste índice não serão exibidas</div>
            <?
	            }
			?>
    </div>
	<?
        } else {
    ?>
            <div style="margin:0 10px 10px 0;float: left;padding:5px 0 0 0">
                Para saber as alíquotas, você precisa primeiro cadastrar as atividades principal e secundárias de sua empresa em <a href="meus_dados_empresa.php">dados da empresa</a>.
            </div>
            <div style="clear: both; height: 10px"></div>

	<?
        }
    ?>
    
    <div id="lista_aliquotas" style="display:<?=$mostra_lista ? 'block' : 'none'?>;">    
		<div class="tituloVermelhoLight" style="margin-bottom: 20px;">Alíquotas por atividade</div>
    
    <?php
        $linhaAtual = 0;
    
	
		//if(!$mostra_lista){
			foreach($arrCNAEs as $cnae){
				// somando 1 na variavel que conta o loop
				$linhaAtual++;
				$descr_cnae = mysql_fetch_array(mysql_query("SELECT * FROM cnae WHERE REPLACE(REPLACE(cnae,'/',''),'-','') = '" .  $cnae . "'"));
				//$CNAE = str_replace(" ","",str_replace("/","",str_replace("-","",$cnae)));
				// Escreve a denominação do cnae vinda do banco
				echo "<div class=\"tituloAzul\">" . ltrim(rtrim($descr_cnae['denominacao'])) . "</div>";
					
					
				if(is_array($arrAnexos[$cnae])){
					foreach($arrAnexos[$cnae] as $arr){
			?>
						<strong><?=$arr[1]?></strong>
                        <div class="divAliquotas<?=str_replace("c","",$arr[0])?>" style="margin:10px 0 20px 0; display:block"> 
                        
                        </div>
            <?
					}
				}
			?>
				
				<div style="clear:both; height:5px"></div>
			<?
			}
		?>

    	Copie (Ctrl + C) a alíquota da atividade referente ao serviço prestado e cole (Ctrl + V) no campo descrição da sua nota fiscal eletrônica de serviços. Empresas do comércio e indústria normalmente já terão os valores dos impostos automaticamente gerados no sistema de emissão da nota.<br />

        <div style="margin-bottom:20px; display:none">
            <input type="button" value="Alterar parâmetros" name="btLimpaDados" id="btLimpaDados" />
        </div>

    </div> <!-- FIM DA DIV DA LISTA DAS ATIVIDADES -->

<?
	if($arrAnexos){
?>

    <div style="margin:20px 0;">
        <input type="button" value="Calcular Alíquotas" name="btCalculaAliquotas" id="btCalculaAliquotas" />
    </div>

<?
	}
?>


</div>



<?php include 'rodape.php' ?>