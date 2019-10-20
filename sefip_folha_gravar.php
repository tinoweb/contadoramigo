<?php 
/** Mostra os erros da página **/
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

session_start();

// Verifica se devera gerar o arquivo sefip via ajax.
if(isset($_POST['ajaxSefip'])) {
	
	// Realiza a inclusão do aqruivo de controle da sefip.
	require_once('Controller/sefip_folha-controller.php');
	
	$sefipFolha = new SefipFolha();
	
	// Pega o código da empresa.
	$empresaId = $_SESSION["id_empresaSecao"];
	
	// Pega o código do usuário.
	$userId = $_SESSION['id_userSecaoMultiplo'];
	
	// Pega o mes.
	$mes = $_POST['mes'];
	
	// Pega o ano.
	$ano = $_POST['ano'];
			
	$tipoArquivo = $_POST['tipoArquivo'];
	
	$sefipFolha->GeraArquivoSefip($empresaId, $userId, $mes, $ano, $tipoArquivo);
	
	// Para a execução do programa quando for uma execução ajax. 
	die();
}
?>

<?php
/**
 * Página com a tabela com as informações do socio autonomo e funcionario.
 */
if(isset($_REQUEST['ano']) && !empty($_REQUEST['ano'])) {
	$ano = $_SESSION['ano_sefip'] = $_REQUEST['ano'];
} else {
	$ano = $_SESSION['ano_sefip'];
}	
	
if(isset($_REQUEST['mes']) && !empty($_REQUEST['mes'])) {
	$mes = $_SESSION['mes_sefip'] = $_REQUEST['mes'];
} else {
	$mes = $_SESSION['mes_sefip'];
}

// faz o redirecionamento caso o mês sejá zero
if(empty($mes) || empty($ano)){
	header('Location: /sefip_folha.php');
}

// Realiza a inclusão do cabeçalho.
include 'header_restrita.php';

// Realiza a inclusão do aqruivo de controle da sefip.
require_once('Controller/sefip_folha-controller.php');

$sefipFolha = new SefipFolha();

// Pega o código da empresa.
$empresaId = $_SESSION["id_empresaSecao"];

?>

<?php

//pega cnae principal da empresa
$sql3 = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $empresaId . "' AND tipo='1' LIMIT 0, 1";
$resultado3 = mysql_query($sql3) or die (mysql_error());
$linha_codigo=mysql_fetch_array($resultado3);

//pega anexo do cnae principal
$sql4 = "SELECT * FROM cnae WHERE cnae='" . $linha_codigo['cnae'] . "' LIMIT 0, 1";
$resultado4 = mysql_query($sql4) or die (mysql_error());
$linha_cnae=mysql_fetch_array($resultado4);

$codigo_recolhimento = 115;

if( in_array('IV',$arrAnexos) && count($arrAnexos) > 1 ) {
	/*Anexo IV + III SEM empreitada*/
	if($_SESSION['SEFIP_empreitada'] == 'n'){ // não realiza empreitada
		$codigo_recolhimento = 115;
		
	/*Anexo IV + III COM empreitada*/
	}elseif($_SESSION['SEFIP_empreitada'] == 's'){ // não realiza empreitada
		$codigo_recolhimento = 150;
	}

} elseif( in_array('IV',$arrAnexos) && count($arrAnexos) == 1 ) {
	/*Anexo IV SEM empreitada*/
	if($_SESSION['SEFIP_empreitada'] == 'n'){ // não realiza empreitada
		$codigo_recolhimento = 115;

	/*Anexo IV COM empreitada*/
	} elseif($_SESSION['SEFIP_empreitada'] == 's'){ // não realiza empreitada
		$codigo_recolhimento = 150;
	}
	
}

//Se nao for empreitada o codigo e 115 - MAL
if( $_SESSION['e_empreitada'] == 'false' ){
	$codigo_recolhimento = 115;
}
//FIM

//Faz a soma da compensação - MAL
$compensacao_cprb = 0;
if( true){
	//Busca todos os pagamentos no periodo informado
	$consulta_pagamentos = mysql_query("SELECT * FROM dados_pagamentos WHERE id_login = '".$_SESSION['id_empresaSecao']."' AND YEAR(data_pagto) = '".$_REQUEST['ano']."' AND MONTH(data_pagto) = '".$_REQUEST['mes']."' ");

	while($objeto=mysql_fetch_array($consulta_pagamentos) ){
		//Soma cada pagamento multiplicado pela porcentagem de acordo com o tipo de trabalhador
		if( $objeto['id_autonomo'] != 0 ){
			$compensacao_cprb = floatval($compensacao_cprb) + floatval(($objeto['valor_bruto']) * (( 20 )/100));
		}
		else if( $objeto['id_socio'] != 0 ){
			$compensacao_cprb = floatval($compensacao_cprb) + floatval(($objeto['valor_bruto']) * (( 20 )/100));
		}
	}
	if( $_SESSION['e_empreitada'] === 'false' )
		$_SESSION['compensacao'] = number_format($compensacao_cprb,'2',',','');

	//Coloca no forma inteiro sem virgulas
	$compensacao_cprb = $compensacao_cprb * 100;


}
//FIM 

// ****************** 4. REGISTRO DO TOMADOR DE SERVIÇO/OBRA DE CONSTRUÇÃO CIVIL

if($codigo_recolhimento == '150') {

	//Guarda a informações se deve ser declarada ou não a compensação
	$houver_compensacao = true;
	
	//Verifica se houve retenção
	if( isset( $_SESSION['SEFIP_retencao'] ) ):
		//Pega os ids de tomadores a serem inseridos no arquivo
		$lista_de_tomadores = mysql_query("SELECT * FROM sefip_tomadores WHERE id_user = '".$_SESSION['id_empresaSecao']."' group by id_tomador ");
		while( $cada_tomador=mysql_fetch_array($lista_de_tomadores) ){ 
			
			//Zera o valor da compensação
			$compensacao21 = 0;

			if( $cada_tomador['id_tomador'] === '0' ){
				//Pega os dados do tomador
				$tomador_consulta = mysql_query("SELECT cnpj as cei, razao_social as nome, endereco as endereco, bairro as bairro, cep as cep, cidade as cidade, estado as estado FROM dados_da_empresa WHERE id = '".$_SESSION['id_empresaSecao']."' ");
				$tomador=mysql_fetch_array($tomador_consulta);
				$cada_tomador['retencao'] = 0;
			}
			else{
				//Pega os dados do tomador
				$tomador_consulta = mysql_query("SELECT * FROM dados_tomadores WHERE id = '".$cada_tomador['id_tomador']."' ");
				$tomador=mysql_fetch_array($tomador_consulta);
			}


			$_SESSION['tomadores_sefip'] .= '
				<tr>
					<td class="td_calendario">'.$tomador['nome'].'</td>
		            <td class="td_calendario">R$ '.number_format($cada_tomador['retencao'],2,',','.').'</td>		
		        </tr>';

			//Se deve haver compensação, seta a variavel que marca a ação
			if ( $cada_tomador['compensacao'] == 1 ) {
				$houver_compensacao = true;
			}

			//Pega os ids dos trabalhadores para este tomador
			$lista_trabalhadores_tomador = mysql_query("SELECT * FROM sefip_tomadores WHERE id_tomador = '".$cada_tomador['id_tomador']."' ORDER BY ordem ASC");

			//Percore os trabalhadores para este tomador
			while( $cada_trabalhador=mysql_fetch_array($lista_trabalhadores_tomador) ){ 			

				$_SESSION['trabalhadores_sefip'] .= $cada_trabalhador['id_trabalhador'].',';
		
				//Pega o valor do pagamento realizado para o trabalhador
				$consulta_pagamentos = mysql_query("SELECT * FROM dados_pagamentos WHERE id_login = '".$_SESSION['id_empresaSecao']."' AND id_autonomo = '".$cada_trabalhador['id_trabalhador']."' AND YEAR(data_pagto) = '".$_REQUEST['ano']."' AND MONTH(data_pagto) = '".$_REQUEST['mes']."' ");
				$pagamento=mysql_fetch_array($consulta_pagamentos);

			
				//Somatorio dos pagamentos para calcular a compensação(se houver_compensacao)
				if( $cada_trabalhador['tipo'] == 'autonomo' ){
					$compensacao21 = floatval($compensacao21) + floatval(($pagamento['valor_bruto']) * (( 20 )/100));
				} else {
					$compensacao21 = floatval($compensacao21) + floatval(($pagamento['valor_bruto']) * (( 20 )/100));
				}

			}					
			
			//Se houve compensação, monta o registro 21 para o tomador informando o valor e o período, que correnpondem a:
			// somatorio do Pagamentos_trabalhadores * ( 20% + RAT )
			// Data inicio: Mes anterior do mes informado dos pagamentos
			// Data Fim: Mes informado dos pagamentos
			if( $houver_compensacao == true ) {
				$compensacao21 = $compensacao21 * 100;
				$_SESSION['compensacao'] = floatval($_SESSION['compensacao']) + floatval($compensacao21);
			}
		}
		
	endif;
}

// ****************** 3 INFORMACOES ADICONAIS RECOLHIMENTO EMPRESA
	
	//Se recolhe CPRB mais nao faz empreitada, adiciona a compensação no registro 12 da empresa - MAL
	if( isset($_SESSION['recolhe_cprb']) && $_SESSION['recolhe_cprb'] == 'true' && $_SESSION['e_empreitada'] == 'false' ){
		$_SESSION['compensacao'] = $compensacao_cprb;
	}
	//FIM
?>

<div class="principal minHeight" style="top: -17px;">

  <span class="titulo">Impostos e Obrigações</span><br /><br />

  <div style="margin-bottom:20px" class="tituloVermelho">Envio da Gfip</div>

<div>
Confira na tabela abaixo os pagamentos de pró-labores, salários e remunerações a autônomos para o período selecionado. <br />
Certifique-se de que estejam corretos, caso contrário, vá à aba pagamentos e corrija. Uma vez conferidos os valores, você está pronto para baixar a folha de pagamento, que será usada no processo de geração da Gfip.<br />
<br />
A folha de pagmento será salva em seu micro com o nome de <strong>sefip.re</strong>. Você não conseguirá visualizar seu conteúdo. Trata-se de um arquivo codificado para leitura nos sistemas da Caixa Econômica Federal. Guarde-o em um local de fácil acesso (na sua área de trabalho, por exemplo), pois você precisará dele logo em seguida.<br>
<br>
	<div class="destaque" style="margin-bottom: 20px">ATENÇÃO: Se a sua empresa tiver funcionários,você terá duas folhas de pagamento, uma para pró-labores e autônomos e outra para os funcionários. Baixe as duas.</div>

</div>

<?php 
	
	// MONTAGEM DA LISTAGEM DOS AUTONOMOS
	$sql = "SELECT 
				pgto.id_pagto
				, pgto.valor_bruto
				, pgto.INSS
				, pgto.IR
				, pgto.ISS
				, pgto.valor_liquido
				, pgto.data_pagto  
				, case when pgto.id_autonomo <> 0 then 'autonomo' else 'socio' end tipo
				, case when pgto.id_autonomo <> 0 then aut.id else socio.idSocio end id
				, case when pgto.id_autonomo <> 0 then aut.nome else socio.nome end nome
				, case when pgto.id_autonomo <> 0 then aut.cpf else socio.cpf end cpf
			FROM 
				dados_pagamentos pgto
				left join dados_autonomos aut on pgto.id_autonomo = aut.id
				left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
			WHERE pgto.id_login='" . $empresaId . "'
			AND pgto.id_estagiario = '0'
			AND pgto.id_pj = '0'
			AND pgto.id_lucro = '0'
			AND YEAR(data_pagto) = '".$ano."'
			AND MONTH(data_pagto) = '".$mes."'
			ORDER BY data_pagto DESC";
	
	$resultado = mysql_query($sql) or die (mysql_error());
	
	$tabelaProlabore = false; 
	
	
	if(mysql_num_rows($resultado) > 0):

		if( isset( $_SESSION['SEFIP_retencao'] ) && $_SESSION['SEFIP_retencao']  == true ) {
			echo '	<table width="100%" cellpadding="5" style="margin-bottom:30px;"><tr><th width="23%">Tomador</th><th width="11%">Retenção</th></tr>';
			echo $_SESSION['tomadores_sefip'];
			echo '</<table>';
		}
		
		if( isset( $_SESSION['SEFIP_retencao'] ) ) {
			unset( $_SESSION['SEFIP_retencao'] );
		}

?>
		<div class="tituloAzulPequeno">Autônomos e Pró Labore</div>
		<table width="100%" cellpadding="5">
        	<tr>
            	<th width="62%">Nome</th>
            	<th width="16%">Categoria</th>
            	<th width="12%">Data Pagto.</th>
            	<th width="10%">INSS</th>
            </tr>
<?php
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){

			$block_trabalhador = true;

			if( $_SESSION['trabalhadores_sefip'] != '' ){
				$block_trabalhador = false;	
				$aux_sefip = explode(',', $_SESSION['trabalhadores_sefip']);

				foreach ($aux_sefip as $key) {
					if( $key != '' ){
						if( $linha["id"] == $key )
							$block_trabalhador = true;						
					}
				}
			}

			if( $block_trabalhador ) {

				
				$tabelaProlabore = true;
				
				$idPagto = $linha["id_pagto"];
				$id = $linha["id"];
				$nome = $linha["nome"];
				$tipo = $linha["tipo"];
				$cpf = $linha["cpf"];

				$valor_bruto = $linha["valor_bruto"];
				$total_valor_bruto += $valor_bruto;

				$INSS = $linha["INSS"];
				$total_INSS += $INSS;

				$IR = $linha["IR"];
				$total_IR += $IR;

				$ISS = $linha["ISS"];
				$total_ISS += $ISS;

				$valor_liquido = $linha["valor_liquido"];
				$total_valor_liquido += $valor_liquido;

				$data_pagto = date("d/m/Y",strtotime($linha['data_pagto']));
?>
				<tr>
					<td class="td_calendario"><?=$nome?></td>
					<td class="td_calendario"><?=$tipo?></td>
					<td class="td_calendario"><?=$data_pagto?></td>
					<td class="td_calendario" align="right"><?=number_format($INSS,2,',','.')?></td>
				</tr>
<?php	
			}
		}
?>
				<tr>
					<th style="background-color: #999; font-weight: normal" colspan="3" align="right">Totais:&nbsp;</th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_INSS,2,',','.')?></th>
				</tr>
			</table>
		
<?php 

			if( isset($_SESSION['recolhe_cprb']) && $_SESSION['recolhe_cprb'] == 'true' && $_SESSION['e_anexo_IV'] == 'true' ){
				echo "<span class='destaque'>Como você já recolhe a CPRB (Contribuição Patronal sobre a Receita Bruta) na apuração do Simples, não será devida a CPP na Gfip, por isso será feita a compensação automática deste imposto.</span>";
				unset($_SESSION['compensacao']);
			}	
			else if( isset($_SESSION['recolhe_cprb']) && $_SESSION['recolhe_cprb'] == 'false' ){

				if( $_SESSION['e_anexo_IV'] == 'true' ){

					if( $_SESSION['e_empreitada'] == 'true' )
						$compensacao_valor = floatval($_SESSION['compensacao'] / 100);
					else
						$compensacao_valor = floatval($_SESSION['compensacao']);

						echo '	<span class="destaque">
									Esta Gfip prevê o recolhimento da CPP (Contribuição Previdenciária Patronal) no valor de R$ '.number_format($compensacao_valor,2,',','.').'
								</span>';
						$_SESSION['compensacao'] = '';
				}
				unset($_SESSION['compensacao']);
			}

			if($mostra_variaveis == true){

				echo $variaveis;

			}
?>						
	<div style="clear:both; height:30px"></div>
<?php			
	endif;
?>

<?php	
	$pagamentoLista = $sefipFolha->PegaDadosPagamentoFuncionario($empresaId, $mes, $ano);
	
	// Tudo a tabela sera incluida na variável para evitar lentidão na hora de carregar a tabela de funcionario. 
	$out = '';
	$total_INSS = 0;
	
	// Verifica se existe pagamento para o funcionario.
	if($pagamentoLista) {

		$out .= '<div class="tituloAzulPequeno">Funcionários: Rendimento do trabalho assalariado</div>';

		$out .= "<table width='100%' cellpadding='5'>
				<tr>
					<th width='62%'>Nome</th>
					<th width='16%' align='center'>Tipo</th>
					<th width='12%' align='center'>Data Pagto.</th>
					<th width='10%' align='center'>INSS</th>
				</tr>";

		foreach($pagamentoLista as $val) {

			// Verifica se e salário ou 13º Salário
			if($val->getTipoPagto() == 'salario') {
				$tipoPagto = 'Salário';
			} elseif($val->getTipoPagto() == 'decimoTerceiro') {

				if($val->getParcelaDecimo() == 'primeira') {
					$tipoPagto = '13° - Primeira parcela';
				} elseif($val->getParcelaDecimo() == 'segunda') {
					$tipoPagto = '13° - Segunda parcela';
				} else {
					$tipoPagto = '13° - Parcela única';
				}

			}

			$dataPagto = date('d/m/Y', strtotime($val->getDataPagto()));
			$valorINSS = number_format($val->getValorINSS(),2,',','.');

			// Linha da tabela como as informações para gfip. 
			$out .= "<tr>
					<td class='td_calendario'>".$val->getFuncionarioNome()."</td>
					<td class='td_calendario' align='center'>".$tipoPagto."</td>
					<td class='td_calendario' align='center'>".$dataPagto."</td>
					<td class='td_calendario' align='right'>".$valorINSS."</td>
				</tr>";

			// Soma todos os INSS	
			$total_INSS += $val->getValorINSS();
		}

		// Formata o valor total do INSS para moeda.
		$total_INSS = number_format($total_INSS,2,',','.');

		// Linha com o rodapé da tabela.
		$out .= "<tr>
				<th style='background-color: #999; font-weight: normal' colspan='3' align='right'>Totais:&nbsp;</th>
				<th style='background-color: #999; font-weight: normal' align='right'>".$total_INSS."</th>
			</tr></table>
			<div style='clear:both; height:30px'></div>";				


	}
	
	// Mostra os dados na tela.
	echo $out;	
?>
<?php
    
	$campoBotao = '';
                     
    // DIV com os botões de continuar e baixar o arquivo sefip.re.
	$campoBotao .= "<div class='caixa_bt_opcoes_quadro' style='padding: 5px 0; width:900px;'>";

	if($tabelaProlabore){
		$campoBotao .= "<input type='button' class='link_download' value='Baixar folha de pagamento do autônomos e pró labore' style='margin-right: 10px;' />";
	}
	
	if($pagamentoLista) {
		$campoBotao .= "<input type='button' class='link_download_funcionario' value='Baixar folha de pagamento do funcionário' style='margin-right: 10px;' />";
	}
	
	$campoBotao .= " <input type='button' id='btContinuar' value='Continuar'> ";
	
	$campoBotao .= " </div>";
	
	$campoBotao .= "<div class='divLoadCard' style='text-align:center; display: none;'>
			<img src='images/loading.gif' width='16' height='16'>
		</div>";                   
    
	echo $campoBotao;
?>
                                                                                                                         
</div>

<script>

var clickLink1 = false;
var clickLink2 = false;	
			
$(document).ready(function(e) {

	$('.link_download_funcionario').click(function(){
		
		// define o tipo do arquivo como 1 para poder gerar o arquivo da sefip com os funcionários
		var tipoArquivo = 1;
		
		// Chama o metodo ajax para gerar arquivo sefip.re
		GeraAquivoViaAjax(tipoArquivo);
	});
		
	$(".link_download").click(function(){

		// define o tipo do arquivo como 2 para poder gerar o arquivo da sefip com os sócios e autônomos
		var tipoArquivo = 2;	
		
		// Chama o metodo ajax para gerar arquivo sefip.re
		GeraAquivoViaAjax(tipoArquivo);
	});	

	$("#btContinuar").click(function(){
		if( clickLink1 == true ) {
			location.href="<?=$_SESSION['SEFIP_pgdestino']?>";
		}else{
			if(confirm('Você ainda não fez o download do arquivo Sefip. Deseja prosseguir mesmo assim?')){
				location.href="<?=$_SESSION['SEFIP_pgdestino']?>";
			}else{
				// define o tipo do arquivo como 2 para poder gerar o arquivo da sefip com os sócios e autônomos
				var tipoArquivo = 1;	

				// Chama o metodo ajax para gerar arquivo sefip.re
				//GeraAquivoViaAjax(tipoArquivo);
			}
		}
	});	
	
	function GeraAquivoViaAjax(tipoArquivo){
		
		var status = false;
		var mes = <?php echo $mes;?>;
		var ano = <?php echo $ano;?>;
		
		$.ajax({
			type: 'POST',
			url: 'sefip_folha_gravar.php',
			data: {ajaxSefip:'',mes:mes,ano:ano,tipoArquivo:tipoArquivo},
			dataType: 'json',
			beforeSend: function() {
				$('.caixa_bt_opcoes_quadro').hide();
				$('.divLoadCard').show();
			},
			success: function(data) {
				
				console.log(data);
				
				if(data['status'] === 'OK') {
					status = true;
				}
			},
			error: function(xhr) { // if error occured
				console.log('Erro retorno');
			},  
			complete: function() {
				
				if(status){
					
					// Define qual dos links foi clicado.
					clickLink1 = true;					

					$('.divLoadCard').hide();
					$('.caixa_bt_opcoes_quadro').show();
					location.href="sefip_folha_downloadRE.php";
				} else {
					
					$('.divLoadCard').hide();
					$('.caixa_bt_opcoes_quadro').show();
					alert("Houve um erro na geração da folha de pagamento. Por favor contate nosso Help Desk.");
				}
				
			}
		});
	}

//	$(".link_download").click(function(){
//		clickLink = true;
//		location.href="sefip_folha_downloadRE.php";
//	});		
	
//	$("#btContinuar2").click(function(){
//		if(clickLink2 == true){
//			
//			location.href="<?=$_SESSION['SEFIP_pgdestino']?>";
//			
//		}else{
//			if(confirm('Você ainda não fez o download do arquivo Sefip. Deseja prosseguir mesmo assim?')){
//				location.href="<?=$_SESSION['SEFIP_pgdestino']?>";
//			}else{
////				alert('Para prosseguir, faça o download do arquivo da sefip gerado.');
//				$(".link_download").focus();
////				location.href="sefip_folha_downloadRE.php";
//			}
//		}
//	});

});

</script>


<?php  include 'rodape.php'; ?>