<?php
session_start();

// inclui o arquivo de conexão com o banco.
require_once "conect.php";

// DE OUTRAS PAGINAS -> $_SESSION['cnaes_empresa_mes'] = $_POST['descricaoAtividade']

// SEPARANCO OS CNAEs EM GRUPOS 
// AQUI OS NUMEROS DAS ATIVIDADES NÃO DEVEM CONTER CARACTERES ESPECIAIS - PARA INCLUIR OU ALTERAR BASTA ADICIONAR OS TEXTOS SEPARADOS POR VIRGULA

/*
* PORQUE SEPARAMOS EM BLOCOS ????
*/
$criterios_transporte_municipal = array('4912402', '4912403', '4921301', '4923001', '4923002', '4924800', '4929901', '4929903', '4930201', '5021101', '5022001', '5091201', '5030101', '5030102', '5030103', '5099801', '5112999');

$criterios_transporte = array('4911600', '4912401', '4921302', '4922101', '4922102', '4922103', '4929902', '4929904', '4930202', '5012201', '5012202', '5021102', '5022002', '5091202', '5111100', '5120000', '5130700');

$criterios_comunicacao = array('6010100', '6021700', '6022501', '6022502', '6110801', '6110802', '6110803', '6110899', '6120501', '6120502', '6120599', '6130200', '6141800', '6142600', '6143400', '6190601', '6190602', '6190699');

$criterios_locacao = array('5011401', '5011402', '7711000', '7719501', '7719502', '7719599', '7721700', '7722500', '7723300', '7729201', '7729202', '7729203', '7729299', '7731400', '7732201', '7733100', '7739001', '7739002', '7739099');

$criterios_retensiveis = array('7739003', '7732202', '7410202', '8121400', '8122200', '8129000', '8130300', '5223100', '8011101', '5211701', '5211702', '5211799', '5212500', '9001901', '9001902', '9001903', '9001904', '9001905', '9001999', '9102301', '9103100', '9200301', '9200302', '9200399', '9312300', '9313100', '9319101', '9319199', '9321200', '9329801', '9329802', '9329803', '9329804', '9329899', '4950700', '4110700', '4120400', '4211101', '4211102', '4212000', '4213800', '4221901', '4221902', '4221903', '4221904', '4221905', '4222701', '4222702', '4223500', '4291000', '4292801', '4292802', '4299501', '4299599', '4311801', '4311802', '4312600', '4313400', '4319300', '4321500', '4322301', '4322302', '4322303', '4329101', '4329102', '4329103', '4329104', '4329105', '4329199', '4330401', '4330402', '4330403', '4330404', '4330405', '4330499', '4391600', '4399101', '4399102', '4399103', '4399104', '4399105', '4399199', '8230001', '7810800', '7820500', '7830200', '5240101', '5222200');

$criterios_retensiveis_transporte = array('49', '50', '51', '52', '53');
$criterios_dubios = array('4929999', '4930203', '4930204', '4940000', '4950700', '5099899', '5111100', '5112901', '5120000', '7500100', '8012900');
$criterios_outros = array();
$criterios_contabilidade = array();

$_SESSION['passou_direto'] = 0; // VARIAVEL CONTROLA SE A ATIVIDADE NÃO NECESSITA DE ESCOLHA DE OPÇÃO DE RETENÇÃO

// LIMPANDO OS DADOS DA TABELA COM AS ATIVIDADES DO MÊS
//mysql_query("DELETE FROM dados_atividades_periodo WHERE id = ".$_SESSION["id_empresaSecao"]);
//mysql_query("OPTIMIZE TABLE dados_atividades_periodo");

// O LOOP ABAIXO É UTILIZADO PARA TRARAR ATIVIDADES DE CONTABILIDADE. NA PAGINA simples_orientacoes_anexos.php FOI COLOCADA A PERGUNTA QUANTO AO ISS E O VALOR DO RADIO "sim" É "IIIc" PARA DIFERENCIARMOS
foreach($_SESSION['cnaes_empresa_mes'] as $cnae){

	$CNAE = str_replace("/","",str_replace("-","",$cnae));
	
	// SE FOR A ATIVIDADE LIGADA A CONTABILIDADE E O RADIO SELECIONADO FOI O "sim"
	if($CNAE == '6920601' && $_POST['ativ_'.$CNAE] == 'IIIc'){
		// A ARRAY DE CNAES DE CONTABILIDADE RECEBE ESTE E NÃO HAVERÁ MAIS PERGUNTAS
		$criterios_contabilidade = array('6920601');
		
	}
}
//var_dump($criterios_contabilidade);
// CHECA SE SÓ FOI SELECIONADA UMA ATIVIDADE QUE NÃO NECESSITA DE ESCOLHA DE OPÇÃO DE RETENÇÃO
if(count($_SESSION['cnaes_empresa_mes']) == 1
	&& !in_array($CNAE,$criterios_transporte)
	&& !in_array($CNAE,$criterios_comunicacao)
	&& !in_array($CNAE,$criterios_retensiveis)
	&& !in_array($CNAE,$criterios_dubios)
	){

	// PREGANDO A DESCRICAO DO CNAE
	$descr_cnae = mysql_fetch_array(mysql_query("SELECT * FROM cnae_2018 WHERE cnae = '" .  $_SESSION['cnaes_empresa_mes'][0] . "'"));
}

?>


<?php 
include 'header_restrita.php';
?>
<!--valida preenchimento das perguntas realtivas ao passo 7 e envia para a página simples_orientacoes_retencao.php -->
<script type="text/javascript">
	
$(document).ready(function(e) {

	$("#btnContinuar").mouseenter(function() {
		$(this).css("background-color", "#a61d00");
	}).mouseleave(function(){
		$(this).css("background-color", "#024a68");
	});

	$("#btnVoltar").mouseenter(function() {
		$(this).css("background-color", "#a61d00");
	}).mouseleave(function(){
		$(this).css("background-color", "#024a68");
	});
				
	$('#btnContinuar').click(function(e){
		e.preventDefault();

<?
		foreach($_SESSION['cnaes_empresa_mes'] as $cnae){
			
			$descr_cnae = mysql_fetch_array(mysql_query("SELECT * FROM cnae_2018 WHERE cnae = '" .  $cnae . "'"));
			
			$CNAE = str_replace("/","",str_replace("-","",$cnae));
			
			echo "
			if($('input[name=marcar_check_".$CNAE."]').val() == ''){
				alert('Responda a(s) pergunta(s) referente(s) à atividade\\n\"" . $descr_cnae['denominacao'] . "\"');
				return false;
			}
";
		}
?>
		$('#form_anexos').submit();
		
	});
	
	
	$('#btnVoltar').click(function(){
		history.go(-1);
	});

	$('input[id^="atividade"]').click(function(){
		$(this).attr('checked',true);
	});
	
});

</script>


<div class="principal">

<h1>Impostos e Obrigações - Simples Nacional</h1>
<h2>Apuração do Simples</h2>

<div class="quadro_opcoes">

        
<?php
$linhaAtual = 0;
$dubio_encontrado = false;

foreach($_SESSION['cnaes_empresa_mes'] as $cnae){
	
	$CNAE = str_replace("/","",str_replace("-","",$cnae));

	if(in_array($CNAE,$criterios_dubios)){
				// GRUPOS DE DUBIOS
?>		
	 <div style="margin-bottom:5px; font-size:15px"  class="perguntas_simples2">		
		<div class="perguntas_simples2" style="font-size:20px; margin:0px; color: #C00"><br />
			<br />
			Não foi possível identificar os parâmetros necessários à sua atividade.<br />
			Por favor contate o Help Desk.<br />
			<br />

            <div style="margin: 0 auto 30px auto; display: table;">
                <div class="navegacao" id="btnVoltar" style="margin-right: 10px;">Voltar</div>
            </div>
    
    		</div>
	</div>
<?					
			$dubio_encontrado = true;
		break;
		
	} 
}

if($dubio_encontrado == false){

?>
	<div style="font-size:20px; margin-top:30px" class="perguntas_simples2">Dentre as atividades selecionadas, determine a forma de retenção.<br /></div>
	<br />
    
	<form id="form_anexos" action="simples_orientacoes_2_normal.php" method="post">


	    <div style="margin-bottom:5px; font-size:15px"  class="perguntas_simples2">


<?
	foreach($_SESSION['cnaes_empresa_mes'] as $cnae){

		// somando 1 na variavel que conta o loop
		$linhaAtual++;

		$descr_cnae = mysql_fetch_array(mysql_query("SELECT * FROM cnae_2018 WHERE cnae = '" .  $cnae . "'"));

		$CNAE = str_replace("/","",str_replace("-","",$cnae));

		// Verifica se o cnae pertence a construção.
		$check_cnae = array('412', '421', '422', '429', '431', '432', '433', '439');
		$cnae_principal = substr($CNAE,0,3);		
		
		if(is_null($_POST['ativ_' . $CNAE]) || $_POST['ativ_' . $CNAE] == ""){
			
			// Se o cnae for referente a contrução define ele como anexo 4.
			if(in_array($cnae_principal,$check_cnae)){
				
				$anexo_CNAE = "IV-C";
				
			} else {
				// Verifica se é anexo 3 para poder ver se tem fator r. 
				if($descr_cnae['anexo'] == 'III') {

					// Verifica se tem fator R
					if($descr_cnae['Fator_R'] == '1'){
						$anexo_CNAE = "IIIR";
					} else {
						$anexo_CNAE = "III";
					}			

				} // Verifica se é anexo 5 para poder definir ele como anexo 3 com ou sem fator r. 
				else if($descr_cnae['anexo'] == 'V') {

					// Verifica se tem fator R
					if($descr_cnae['Fator_R'] == '1'){
						$anexo_CNAE = "IIIR";
					} else {
						$anexo_CNAE = "III";
					}

				} // Pega o anexo.
				else {
					$anexo_CNAE = $descr_cnae['anexo'];
				}
			}
		} else {
			
			// Realiz a verificação do cnae para saber se e construção. 
			if(in_array($cnae_principal,$check_cnae)){
				
				// PARA TRATAR DO CASO ESPECIFICO DE CONTABILIDADE, FOI COLOCADO O "c" NO FINAL DO CAMPO ativ_xxxx
				if($_POST['ativ_' . $CNAE] == "IIIc"){
					$anexo_CNAE = "III";
				} // Quando for anexo 3 infoma que ele e de contrução.
				else if($_POST['ativ_' . $CNAE] == "III"){
					$anexo_CNAE = "III-C"; 
				} // Quando for anexo 4 infoma que ele e de contrução.
				else if($_POST['ativ_' . $CNAE] == "IV"){
					$anexo_CNAE = "IV-C";
				} else {
					$anexo_CNAE = $_POST['ativ_' . $CNAE];
				}
				
			} else {
				
				// PARA TRATAR DO CASO ESPECIFICO DE CONTABILIDADE, FOI COLOCADO O "c" NO FINAL DO CAMPO ativ_xxxx
				if($_POST['ativ_' . $CNAE] == "IIIc"){
					$anexo_CNAE = "III";
				} else if($_POST['ativ_' . $CNAE] == "V"){
					$anexo_CNAE = "III";
				} else if($_POST['ativ_' . $CNAE] == "VI"){
					$anexo_CNAE = "III";
				} else {
					$anexo_CNAE = $_POST['ativ_' . $CNAE];
				}
			}
			
		}

		// Escreve a denominação do cnae vinda do banco
?>

	<input type="hidden" name="anexo_<?=$CNAE?>" value="<?=$anexo_CNAE?>" />
    <input id="atividade<?=$linhaAtual?>" type="checkbox" name="descricaoAtividade[]" value="<?=$cnae?>" checked="checked" />&nbsp;&nbsp;<?=$descr_cnae['denominacao']?>. 
<?


		// SEPARANDO OS ANEXOS EM GRUPOS PARA DEPOIS COMPARAR COM O ANEXO DA ATIVIDADE EXERCIDA
		$anexos3456 = array('III', 'IIIR', 'III-C', 'IV', 'IV-C', 'V', 'VI');
		$anexos2 = array('II', 'IIc');
		$anexos1 = array('I');

		// CHECANDO SE A ATIVIDADE EXERCIDA ESTÁ NO ANEXO III, IV, V OU VI
		if(in_array($anexo_CNAE,$anexos3456)){
						
			// COMPARANDO O CNAE PARA TENTAR ENQUADRAR EM ALGUM GRUPO
			
			if(in_array($CNAE,$criterios_transporte_municipal)) {

?>
			<script type="text/javascript">
				
         		$(document).ready(function(e) {
						
					var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');

					var check1_<?=$CNAE?> = '22';
					var check2_<?=$CNAE?> = '29';
					var check3_<?=$CNAE?> = '30'; 
					var check4_<?=$CNAE?> = '31'; 
					var check5_<?=$CNAE?> = '45'; 
					var check6_<?=$CNAE?> = '46'; 	

					if(campo_hidden<?=$CNAE?>.val() != '' && ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]')) != 2){
						$('#div_respostas_retensiveis_brasil_<?=$CNAE?>').css('display', 'block');
					}

					$('input:radio[name=prestacao_em_<?=$CNAE?>], input:radio[name=retencao_<?=$CNAE?>], input:radio[name=outro_municipio_<?=$CNAE?>]').click(function(){

						var prestacao_em_<?=$CNAE?> = ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]'));
						var retencao = ($('input:radio[name=retencao_<?=$CNAE?>]:checked').index('input[name=retencao_<?=$CNAE?>]'));
						var outro_municipio = ($('input:radio[name=outro_municipio_<?=$CNAE?>]:checked').index('input[name=outro_municipio_<?=$CNAE?>]'));

						if(prestacao_em_<?=$CNAE?> == 0 || prestacao_em_<?=$CNAE?> == 1){
							campo_hidden<?=$CNAE?>.val('');
							$('#div_respostas_retensiveis_brasil_<?=$CNAE?>').css('display', 'block');
							if(outro_municipio == 0 && retencao == 0){
								campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check3_<?=$CNAE?>);
							} else if(outro_municipio == 0 && retencao == 1){
								campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check3_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
							} else if(outro_municipio == 0 && retencao == 2){
								campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
							} else if(outro_municipio == 1 && retencao == 0){
								campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?> + ';' + check3_<?=$CNAE?>);
							} else if(outro_municipio == 1 && retencao == 1){
								campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?> + ';' + check3_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
							} else if(outro_municipio == 1 && retencao == 2){
								campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
							} else if(outro_municipio == 2 && retencao == 0){
								campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?>);
							} else if(outro_municipio == 2 && retencao == 1){
								campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
							} else if(outro_municipio == 2 && retencao == 2){
								campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
							}
							if(outro_municipio >= 0 && retencao >= 0 && prestacao_em_<?=$CNAE?> == 1){
								campo_hidden<?=$CNAE?>.val(campo_hidden<?=$CNAE?>.val() + ';'  + check5_<?=$CNAE?> + ';' + check6_<?=$CNAE?>);
							}
						}else{
							$('#div_respostas_retensiveis_brasil_<?=$CNAE?>').css('display', 'none');
							$('input:radio[name=opcoes_<?=$CNAE?>]').attr('checked',false);
							campo_hidden<?=$CNAE?>.val(check5_<?=$CNAE?> + ';' + check6_<?=$CNAE?>);
						}
					});
          	});
          </script>

        <div style="margin:10px 0 20px 30px;" class="opcao_simples" >
          Prestação de serviços no período:<br />
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no Brasil.
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados no Brasil e no exterior.
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no exterior.
        </div>

        <div id="div_respostas_retensiveis_brasil_<?=$CNAE?>" style="display: none; margin:10px 0 20px 30px;">

			<div style="margin:10px 0 20px 0;" class="opcao_simples" >
				O serviço de transporte prestado teve início em outro município?<br />
				<div style="clear: both; height: 5px" ></div>
				<input name="outro_municipio_<?=$CNAE?>" type="radio" /> Todas as notas se referem a serviços inciados no meu município.
				<div style="clear: both; height: 5px" ></div>
				<input name="outro_municipio_<?=$CNAE?>" type="radio" /> Algumas notas se referem a serviços inciados no meu município, outras não.
				<div style="clear: both; height: 5px" ></div>
				<input name="outro_municipio_<?=$CNAE?>" type="radio" /> Todas as notas se referem a serviços inciados em outros municípios.
			</div>

			<div style="margin:10px 0 20px 0;" class="opcao_simples" >
				Algum cliente reteve o ISS ao pagar sua nota?<br />
				<div style="clear: both; height: 5px" ></div>
				<input name="retencao_<?=$CNAE?>" type="radio" /> <strong>NENHUMA</strong> nota teve retenção.
				<div style="clear: both; height: 5px" ></div>
				<input name="retencao_<?=$CNAE?>" type="radio" /> <strong>ALGUMAS</strong> notas tiveram e outras não.
				<div style="clear: both; height: 5px" ></div>
				<input name="retencao_<?=$CNAE?>" type="radio" /> <strong>TODAS</strong> tiveram retenção.
			</div>
		</div>

		<input type="hidden" name="marcar_check_<?=$CNAE?>" />						
								
										
		
		
		
<?				
			} else if(in_array($CNAE,$criterios_transporte)) {
				// GRUPOS DE TRANSPORTE

				$check1 = "40"; //'31';
				$check2 = "41"; //'32';
				$check3 = "42"; //'33';
					
				$check4 = "45"; //'36';
				$check5 = "46"; //'37';
				
?>				
				<script type="text/javascript">
        	$(document).ready(function(e) {
						
						var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');

						if(campo_hidden<?=$CNAE?>.val() != '' && ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]')) != 2){
							$('#div_respostas_transporte_brasil_<?=$CNAE?>').css('display', 'block');
						}
						
						$('input:radio[name=prestacao_em_<?=$CNAE?>], input:radio[name=opcoes_<?=$CNAE?>]').click(function(){

							var prestacao_em_<?=$CNAE?> = ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]'));
							var opcoes_<?=$CNAE?> = ($('input:radio[name=opcoes_<?=$CNAE?>]:checked').index('input[name=opcoes_<?=$CNAE?>]'));
							
							if(prestacao_em_<?=$CNAE?> == 0 || prestacao_em_<?=$CNAE?> == 1){
								$('#div_respostas_transporte_brasil_<?=$CNAE?>').css('display', 'block');
								campo_hidden<?=$CNAE?>.val('');
								if(opcoes_<?=$CNAE?> == 0){
									campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>');
								} else if(opcoes_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>;<?=$check3?>');
								} else if(opcoes_<?=$CNAE?> == 2){
									campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check3?>');
								} 
								if(opcoes_<?=$CNAE?> >= 0 && prestacao_em_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val(campo_hidden<?=$CNAE?>.val() + ';<?=$check4?>;<?=$check5?>');
								}
							}else{
								$('#div_respostas_transporte_brasil_<?=$CNAE?>').css('display', 'none');
								$('input:radio[name=opcoes_<?=$CNAE?>]').attr('checked',false);
								campo_hidden<?=$CNAE?>.val('<?=$check4?>;<?=$check5?>');
							}
							
							//alert('valor marcar_check_<?=$CNAE?>: ' + campo_hidden<?=$CNAE?>.val());
							
						});
						
         });
        </script>
              
        <div style="margin:10px 0 20px 30px;" class="opcao_simples" >
          Prestação de serviços de transporte no período:<br />
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no Brasil.
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados no Brasil e no exterior.
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no exterior.
        </div>

        <div id="div_respostas_transporte_brasil_<?=$CNAE?>" style="display: none; margin:10px 0 20px 30px;" class="opcao_simples" >
					Executou serviços de transporte sujeitos à substituição tributária?<br />
					<div style="clear: both; height: 5px" ></div>
					<input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>NENHUM</strong> serviço de transporte efetuado no período está sujeito à substituição tributária.
					<div style="clear: both; height: 5px" ></div>
					<input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>ALGUNS</strong> serviços de transporte efetuados no período estão sujeitos à substituição tributária e outros não.
					<div style="clear: both; height: 5px" ></div>
					<input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>TODOS</strong> serviços de transporte efetuados no período estão sujeitos à substituição tributária.
        </div>

        <input type="hidden" name="marcar_check_<?=$CNAE?>" />

<?
			} else if(in_array($CNAE,$criterios_comunicacao)){
				// GRUPOS DE COMUNICAÇÃO

				$check1 = '40'; //'31';
				$check2 = '43'; //'34';
				$check3 = '44'; //'35';
					
				$check4 = '45'; //'36';
				$check5 = '47'; //'38';
				
?>				
				<script type="text/javascript">
        	$(document).ready(function(e) {
						
						var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');

						if(campo_hidden<?=$CNAE?>.val() != '' && ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]')) != 2){
							$('#div_respostas_comunicacao_brasil_<?=$CNAE?>').css('display', 'block');
						}
						
						$('input:radio[name=prestacao_em_<?=$CNAE?>], input:radio[name=opcoes_<?=$CNAE?>]').click(function(){

							var prestacao_em_<?=$CNAE?> = ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]'));
							var opcoes_<?=$CNAE?> = ($('input:radio[name=opcoes_<?=$CNAE?>]:checked').index('input[name=opcoes_<?=$CNAE?>]'));
							
							if(prestacao_em_<?=$CNAE?> == 0 || prestacao_em_<?=$CNAE?> == 1){
								$('#div_respostas_comunicacao_brasil_<?=$CNAE?>').css('display', 'block');
								campo_hidden<?=$CNAE?>.val('');
								if(opcoes_<?=$CNAE?> == 0){
									campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>');
								} else if(opcoes_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>;<?=$check3?>');
								} else if(opcoes_<?=$CNAE?> == 2){
									campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check3?>');
								} 
								if(opcoes_<?=$CNAE?> >= 0 && prestacao_em_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val(campo_hidden<?=$CNAE?>.val() + ';<?=$check4?>;<?=$check5?>');
								}
							}else{
								$('#div_respostas_comunicacao_brasil_<?=$CNAE?>').css('display', 'none');
								$('input:radio[name=opcoes_<?=$CNAE?>]').attr('checked',false);
								campo_hidden<?=$CNAE?>.val('<?=$check4?>;<?=$check5?>');
							}
							
							//alert('valor marcar_check_<?=$CNAE?>: ' + campo_hidden<?=$CNAE?>.val());
							
						});
						
         });
        </script>
              
        <div style="margin:10px 0 20px 30px;" class="opcao_simples" >
          Prestação de serviços de comunicação no período:<br />
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no Brasil.
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados no Brasil e no exterior.
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no exterior.
        </div>

        <div id="div_respostas_comunicacao_brasil_<?=$CNAE?>" style="display: none; margin:10px 0 20px 30px;" class="opcao_simples" >
          O serviço de comunicação prestado está sujeito à substituição tributária?<br />
          <div style="clear: both; height: 5px" ></div>
          <input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>NENHUM</strong> dos serviços de comunicação prestado está sujeito à substituição tributária.
          <div style="clear: both; height: 5px" ></div>
          <input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>ALGUNS</strong> dos serviços de comunicação prestados estão sujeitos à substituição tributária outros não.
          <div style="clear: both; height: 5px" ></div>
          <input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>TODOS</strong> os serviços de comunicação prestados estão sujeitos à substituição tributária.
        </div>

        <input type="hidden" name="marcar_check_<?=$CNAE?>" />
<?

			} else if(in_array($CNAE,$criterios_locacao)){
				// GRUPOS DE LOCAÇÃO
?>				


				<script type="text/javascript">
				$(document).ready(function(e) {
						
					var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');

					$('input:radio[name=opcoes_<?=$CNAE?>]').click(function(){

						var opcoes = ($('input:radio[name=opcoes_<?=$CNAE?>]:checked').index('input[name=opcoes_<?=$CNAE?>]'));
						
						if(opcoes == 0){
							campo_hidden<?=$CNAE?>.val('9');
						} else if(opcoes == 1){
							campo_hidden<?=$CNAE?>.val('9;10');
						} else if(opcoes == 2){
							campo_hidden<?=$CNAE?>.val('10');
						}
						
					});
				});
        </script>
        <div style="margin:10px 0 20px 30px;" class="opcao_simples" >
          Locação foi realizada para o exterior:<br />
          <div style="clear: both; height: 5px" ></div>
          <input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>NENHUMA </strong> locação realizada para o exterior.
          <div style="clear: both; height: 5px" ></div>
          <input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>ALGUMAS </strong> locações realizada para o exterior e outras para o Brasil.
          <div style="clear: both; height: 5px" ></div>
          <input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>TODAS </strong> locações realizadas para o exterior.
        </div>
				<input type="hidden" name="marcar_check_<?=$CNAE?>" />

<?
				
			} else if(in_array($CNAE,$criterios_contabilidade)){
				// GRUPOS DE CONTABILIDADE

				$check1 = '11';
				$check2 = '12';
				$check3 = '32'; //'25';
				$check4 = '33'; //'26';
?>				
				<script type="text/javascript">
        	$(document).ready(function(e) {
						
						var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');

					
						$('input:radio[name=prestacao_em_<?=$CNAE?>], input:radio[name=opcoes_<?=$CNAE?>]').click(function(){

							var prestacao_em_<?=$CNAE?> = ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]'));
							
							if(prestacao_em_<?=$CNAE?> == 0){
								campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>');
							} else if(prestacao_em_<?=$CNAE?> == 1){
								campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>;<?=$check3?>;<?=$check4?>');
							} else if(prestacao_em_<?=$CNAE?> == 2){
								campo_hidden<?=$CNAE?>.val('<?=$check3?>;<?=$check4?>');
							} 
							
						});
						
         });
         </script>
                
          <div style="margin:10px 0 20px 30px;" class="opcao_simples" >
            Prestação de serviços de contabilidade no período:<br />
            <div style="clear: both; height: 5px" ></div>
            <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no Brasil.
            <div style="clear: both; height: 5px" ></div>
            <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados no Brasil e no exterior.
            <div style="clear: both; height: 5px" ></div>
            <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no exterior.
          </div>

					<input type="hidden" name="marcar_check_<?=$CNAE?>" />
<?
	
			} else if(in_array($CNAE,$criterios_retensiveis)){
				// GRUPOS DE RETENSIVEIS
?>				

					<script type="text/javascript">
          $(document).ready(function(e) {
						
						var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');

						switch($('input[name=anexo_<?=$CNAE?>]').val()){
							case "III":

								var check1_<?=$CNAE?> = '11';
								var check2_<?=$CNAE?> = '16'; //'13';
								var check3_<?=$CNAE?> = '17'; //'14';
								var check4_<?=$CNAE?> = '18'; //'15';
								var check5_<?=$CNAE?> = '32'; //'25';
								var check6_<?=$CNAE?> = '35'; //'27';

							break;
							case "IIIR":

								var check1_<?=$CNAE?> = '11';
								var check2_<?=$CNAE?> = '13'; //'13';
								var check3_<?=$CNAE?> = '14'; //'14';
								var check4_<?=$CNAE?> = '15'; //'15';
								var check5_<?=$CNAE?> = '32'; //'25';
								var check6_<?=$CNAE?> = '34'; //'27';

							break;
							case "III-C":

								var check1_<?=$CNAE?> = '22';
								var check2_<?=$CNAE?> = '23';
								var check3_<?=$CNAE?> = '24';
								var check4_<?=$CNAE?> = '25';
								var check5_<?=$CNAE?> = '37';
								var check6_<?=$CNAE?> = '38';

							break;								
							case "IV":

								var check1_<?=$CNAE?> = '11';
								var check2_<?=$CNAE?> = '19'; //'16';
								var check3_<?=$CNAE?> = '20'; //'17';
								var check4_<?=$CNAE?> = '21'; //'18';
								var check5_<?=$CNAE?> = '32'; //'25';
								var check6_<?=$CNAE?> = '36'; //'28';

							break;								
							case "IV-C":

								var check1_<?=$CNAE?> = '22';
								var check2_<?=$CNAE?> = '26';
								var check3_<?=$CNAE?> = '27';
								var check4_<?=$CNAE?> = '28';
								var check5_<?=$CNAE?> = '37';
								var check6_<?=$CNAE?> = '39';

							break;

						}
						// ABAIXO ESTÁ FUNÇÃO JQUERY QUE MONTA O VALUE COM OS CHECKS A SEREM MARCADOS PARA ANEXO 3, 4, 5 OU 6

						if(campo_hidden<?=$CNAE?>.val() != '' && ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]')) != 2){
							$('#div_respostas_retensiveis_brasil_<?=$CNAE?>').css('display', 'block');
						}

            $('input:radio[name=prestacao_em_<?=$CNAE?>], input:radio[name=retencao_<?=$CNAE?>], input:radio[name=outro_municipio_<?=$CNAE?>]').click(function(){

							var prestacao_em_<?=$CNAE?> = ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]'));
							var retencao = ($('input:radio[name=retencao_<?=$CNAE?>]:checked').index('input[name=retencao_<?=$CNAE?>]'));
							var outro_municipio = ($('input:radio[name=outro_municipio_<?=$CNAE?>]:checked').index('input[name=outro_municipio_<?=$CNAE?>]'));
														
							if(prestacao_em_<?=$CNAE?> == 0 || prestacao_em_<?=$CNAE?> == 1){
								campo_hidden<?=$CNAE?>.val('');
								$('#div_respostas_retensiveis_brasil_<?=$CNAE?>').css('display', 'block');
								if(outro_municipio == 0 && retencao == 0){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check3_<?=$CNAE?>);
								} else if(outro_municipio == 0 && retencao == 1){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check3_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
								} else if(outro_municipio == 0 && retencao == 2){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
								} else if(outro_municipio == 1 && retencao == 0){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?> + ';' + check3_<?=$CNAE?>);
								} else if(outro_municipio == 1 && retencao == 1){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?> + ';' + check3_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
								} else if(outro_municipio == 1 && retencao == 2){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
								} else if(outro_municipio == 2 && retencao == 0){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?>);
								} else if(outro_municipio == 2 && retencao == 1){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
								} else if(outro_municipio == 2 && retencao == 2){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check4_<?=$CNAE?>);
								}
								if(outro_municipio >= 0 && retencao >= 0 && prestacao_em_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val(campo_hidden<?=$CNAE?>.val() + ';'  + check5_<?=$CNAE?> + ';' + check6_<?=$CNAE?>);
								}
							}else{
								$('#div_respostas_retensiveis_brasil_<?=$CNAE?>').css('display', 'none');
								$('input:radio[name=opcoes_<?=$CNAE?>]').attr('checked',false);
								campo_hidden<?=$CNAE?>.val(check5_<?=$CNAE?> + ';' + check6_<?=$CNAE?>);
							}



							
						});
          });
          </script>

        <div style="margin:10px 0 20px 30px;" class="opcao_simples" >
          Prestação de serviços no período:<br />
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no Brasil.
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados no Brasil e no exterior.
          <div style="clear: both; height: 5px" ></div>
          <input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no exterior.
        </div>

        <div id="div_respostas_retensiveis_brasil_<?=$CNAE?>" style="display: none; margin:10px 0 20px 30px;">

					<?
	        // DETERMINANDO SE É TRANSPORTE INTERMUNICIPAL
					if(in_array(substr($CNAE,0,2),$criterios_retensiveis_transporte)){
					?>
						<div style="margin:10px 0 20px 0;" class="opcao_simples" >
							O serviço de transporte prestado teve início em outro município?<br />
							<div style="clear: both; height: 5px" ></div>
							<input name="outro_municipio_<?=$CNAE?>" type="radio" /> Todas as notas se referem a serviços inciados no meu município.
							<div style="clear: both; height: 5px" ></div>
							<input name="outro_municipio_<?=$CNAE?>" type="radio" /> Algumas notas se referem a serviços inciados no meu município, outras não.
							<div style="clear: both; height: 5px" ></div>
							<input name="outro_municipio_<?=$CNAE?>" type="radio" /> Todas as notas se referem a serviços inciados em outros municípios.
						</div>
					<?
					} else {
					?>
						<div style="margin:10px 0 20px 0;" class="opcao_simples" >
							Prestou serviços desta atividade para clientes de outros municípios?<br />
							<div style="clear: both; height: 5px" ></div>
							<input name="outro_municipio_<?=$CNAE?>" type="radio" /> Nenhuma nota foi para clientes de outro município.
							<div style="clear: both; height: 5px" ></div>
							<input name="outro_municipio_<?=$CNAE?>" type="radio" /> Algumas notas foram para clientes de outros municípios.
							<div style="clear: both; height: 5px" ></div>
							<input name="outro_municipio_<?=$CNAE?>" type="radio" /> Todas as notas foram para clientes de outros municípios.
						</div>
					<?
					}
					?>
					
          <div style="margin:10px 0 20px 0;" class="opcao_simples" >
            Algum cliente reteve o ISS ao pagar sua nota?<br />
            <div style="clear: both; height: 5px" ></div>
            <input name="retencao_<?=$CNAE?>" type="radio" /> <strong>NENHUMA</strong> nota teve retenção.
            <div style="clear: both; height: 5px" ></div>
            <input name="retencao_<?=$CNAE?>" type="radio" /> <strong>ALGUMAS</strong> notas tiveram e outras não.
            <div style="clear: both; height: 5px" ></div>
            <input name="retencao_<?=$CNAE?>" type="radio" /> <strong>TODAS</strong> tiveram retenção.
          </div>
        </div>
        
				<input type="hidden" name="marcar_check_<?=$CNAE?>" />

<?			
			} else {

				switch($anexo_CNAE){
					case "III":

						$check1 = '11';
						$check2 = '17'; //'14';
						$check3 = '18'; //'15';
						$check4 = '32'; //'25';
						$check5 = '35'; //'27';

					break;
					case "IIIR":

						$check1 = '11';
						$check2 = '14'; //'14';
						$check3 = '15'; //'15';
						$check4 = '32'; //'25';
						$check5 = '34'; //'27';

					break;	
					case "III-C":

						$check1 = '22';
						$check2 = '24';
						$check3 = '25';
						$check4 = '37';
						$check5 = '38';

					break;						
					case "IV":
						$check1 = '11';
						$check2 = '20'; //'17';
						$check3 = '21'; //'18';
						$check4 = '32'; //'25';
						$check5 = '36'; //'28';						
					break;
					case "IV-C":
						$check1 = '22';
						$check2 = '27';
						$check3 = '28';
						$check4 = '37';
						$check5 = '39'; 
					break;					
				}
?>

<script type="text/javascript">
	$(document).ready(function(e) {

		var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');

		if(campo_hidden<?=$CNAE?>.val() != '' && ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]')) != 2){
			$('#div_respostas_prestacao_brasil_<?=$CNAE?>').css('display', 'block');
		}

		$('input:radio[name=prestacao_em_<?=$CNAE?>], input:radio[name=opcoes_<?=$CNAE?>]').click(function(){

			var prestacao_em_<?=$CNAE?> = ($('input:radio[name=prestacao_em_<?=$CNAE?>]:checked').index('input[name=prestacao_em_<?=$CNAE?>]'));
			var opcoes_<?=$CNAE?> = ($('input:radio[name=opcoes_<?=$CNAE?>]:checked').index('input[name=opcoes_<?=$CNAE?>]'));

			if(prestacao_em_<?=$CNAE?> == 0 || prestacao_em_<?=$CNAE?> == 1){
				$('#div_respostas_prestacao_brasil_<?=$CNAE?>').css('display', 'block');
				campo_hidden<?=$CNAE?>.val('');
				if(opcoes_<?=$CNAE?> == 0){
					campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>');
				} else if(opcoes_<?=$CNAE?> == 1){
					campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>;<?=$check3?>');
				} else if(opcoes_<?=$CNAE?> == 2){
					campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check3?>');
				} 
				if(opcoes_<?=$CNAE?> >= 0 && prestacao_em_<?=$CNAE?> == 1){
					campo_hidden<?=$CNAE?>.val(campo_hidden<?=$CNAE?>.val() + ';<?=$check4?>;<?=$check5?>');
				}
			}else{
				$('#div_respostas_prestacao_brasil_<?=$CNAE?>').css('display', 'none');
				$('input:radio[name=opcoes_<?=$CNAE?>]').attr('checked',false);
				campo_hidden<?=$CNAE?>.val('<?=$check4?>;<?=$check5?>');
			}

			//alert('valor marcar_check_<?=$CNAE?>: ' + campo_hidden<?=$CNAE?>.val());

		});

	});
</script>
                
		<div style="margin:10px 0 20px 30px;" class="opcao_simples" >
			Prestação de serviços no período:<br />
			<div style="clear: both; height: 5px" ></div>
			<input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no Brasil.
			<div style="clear: both; height: 5px" ></div>
			<input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados no Brasil e no exterior.
			<div style="clear: both; height: 5px" ></div>
			<input name="prestacao_em_<?=$CNAE?>" type="radio" /> Foram prestados apenas no exterior.
		</div>

		<div id="div_respostas_prestacao_brasil_<?=$CNAE?>" style="display: none; margin:10px 0 20px 30px;" class="opcao_simples" >
		Nos serviços prestados para o Brasil, algum cliente reteve o ISS ao pagar sua nota desta atividade?<br />
		<div style="clear: both; height: 5px" ></div>
			<input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>NENHUMA</strong> nota teve retenção.
			<div style="clear: both; height: 5px" ></div>
			<input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>ALGUMAS</strong> notas tiveram e outras não.
			<div style="clear: both; height: 5px" ></div>
			<input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>TODAS</strong> tiveram retenção.
		</div>
		<input type="hidden" name="marcar_check_<?=$CNAE?>" />
<?			
			}
			
		// ATIVIDADES DO ANEXO 2	
		} else if(in_array($anexo_CNAE,$anexos2)){
			// É ANEXO 2 COM SERVIÇOS PARA TERCEIROS
			if($anexo_CNAE == 'IIc'){


				$check1 = '48'; //'39';
				$check2 = '49'; //'40';
				$check3 = '50'; //'41';
				$check4 = '51'; //'42';
				$check5 = '52'; //'43';
				
?>

				<script type="text/javascript">
        	$(document).ready(function(e) {
						
						var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');
						
						if(campo_hidden<?=$CNAE?>.val() != '' && ($('input:radio[name=atividades_em_<?=$CNAE?>]:checked').index('input[name=atividades_em_<?=$CNAE?>]')) != 2){
							$('#div_respostas_ISS_brasil_<?=$CNAE?>').css('display', 'block');
						}
						
						$('input:radio[name=atividades_em_<?=$CNAE?>], input:radio[name=opcoes_<?=$CNAE?>]').click(function(){

							var atividades_em_<?=$CNAE?> = ($('input:radio[name=atividades_em_<?=$CNAE?>]:checked').index('input[name=atividades_em_<?=$CNAE?>]'));
							var opcoes_<?=$CNAE?> = ($('input:radio[name=opcoes_<?=$CNAE?>]:checked').index('input[name=opcoes_<?=$CNAE?>]'));
							
							
							if(atividades_em_<?=$CNAE?> == 0 || atividades_em_<?=$CNAE?> == 1){
								$('#div_respostas_ISS_brasil_<?=$CNAE?>').css('display', 'block');
								campo_hidden<?=$CNAE?>.val('');
								if(opcoes_<?=$CNAE?> == 0){
									campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>;<?=$check3?>');
								} else if(opcoes_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check2?>;<?=$check3?>;<?=$check4?>');
								} else if(opcoes_<?=$CNAE?> == 2){
									campo_hidden<?=$CNAE?>.val('<?=$check1?>;<?=$check4?>');
								} 
								if(opcoes_<?=$CNAE?> >= 0 && atividades_em_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val(campo_hidden<?=$CNAE?>.val() + ';<?=$check5?>');
								}
							}else{
								$('#div_respostas_ISS_brasil_<?=$CNAE?>').css('display', 'none');
								$('input:radio[name=opcoes_<?=$CNAE?>]').attr('checked',false);
								campo_hidden<?=$CNAE?>.val('<?=$check5?>');
							}
							
							//alert('valor marcar_check_<?=$CNAE?>: ' + campo_hidden<?=$CNAE?>.val());
							
						});
						
         });
         </script>
                
          <div style="margin:10px 0 20px 30px;" class="opcao_simples" >
            Atividades exercidas no período:<br />
            <div style="clear: both; height: 5px" ></div>
            <input name="atividades_em_<?=$CNAE?>" type="radio" /> Apenas no Brasil.
            <div style="clear: both; height: 5px" ></div>
            <input name="atividades_em_<?=$CNAE?>" type="radio" /> No Brasil e no exterior.
            <div style="clear: both; height: 5px" ></div>
            <input name="atividades_em_<?=$CNAE?>" type="radio" /> Apenas no exterior.
          </div>

          <div id="div_respostas_ISS_brasil_<?=$CNAE?>" style="display: none; margin:10px 0 20px 30px;" class="opcao_simples" >
            Nos serviços prestados para o Brasil, algum cliente reteve o ISS ao pagar sua nota desta atividade?<br />
            <div style="clear: both; height: 5px" ></div>
            <input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>NENHUMA</strong> nota teve retenção.
            <div style="clear: both; height: 5px" ></div>
            <input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>ALGUMAS</strong> notas tiveram e outras não.
            <div style="clear: both; height: 5px" ></div>
            <input name="opcoes_<?=$CNAE?>" type="radio" /> <strong>TODAS</strong> tiveram retenção.
          </div>
<?
			} else { 
?>
			<script type="text/javascript">
        		$(document).ready(function(e) {
						
						var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');

						var check1_<?=$CNAE?> = '05';
						var check2_<?=$CNAE?> = '06';
						var check3_<?=$CNAE?> = '07';
						var check4_<?=$CNAE?> = '08';

/*						if(campo_hidden<?=$CNAE?>.val() != ''){*/
						if(campo_hidden<?=$CNAE?>.val() != '' && ($('input:radio[name=vendida_em_<?=$CNAE?>]:checked').index('input[name=vendida_em_<?=$CNAE?>]')) != 2){
							$('#div_respostas_vendas_brasil_<?=$CNAE?>').css('display', 'block');
						}
						
						$('input:radio[name=vendida_em_<?=$CNAE?>], input:radio[name=subst_tributaria_<?=$CNAE?>]').click(function(){

							var vendida_em_<?=$CNAE?> = ($('input:radio[name=vendida_em_<?=$CNAE?>]:checked').index('input[name=vendida_em_<?=$CNAE?>]'));
							var subst_tributaria_<?=$CNAE?> = ($('input:radio[name=subst_tributaria_<?=$CNAE?>]:checked').index('input[name=subst_tributaria_<?=$CNAE?>]'));
							
							if(vendida_em_<?=$CNAE?> == 0 || vendida_em_<?=$CNAE?> == 1){
								$('#div_respostas_vendas_brasil_<?=$CNAE?>').css('display', 'block');
								campo_hidden<?=$CNAE?>.val('');
								if(subst_tributaria_<?=$CNAE?> == 0){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?>);
								} else if(subst_tributaria_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?> + ';' + check3_<?=$CNAE?>);
								} else if(subst_tributaria_<?=$CNAE?> == 2){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check3_<?=$CNAE?>);
								} else {
									campo_hidden<?=$CNAE?>.val('');
								}
								
								
								if(subst_tributaria_<?=$CNAE?> >= 0 && vendida_em_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val(campo_hidden<?=$CNAE?>.val() + ';' + check4_<?=$CNAE?>);
								}
							}else{
								$('#div_respostas_vendas_brasil_<?=$CNAE?>').css('display', 'none');
								$('input:radio[name=subst_tributaria_<?=$CNAE?>]').attr('checked',false);
								campo_hidden<?=$CNAE?>.val(check4_<?=$CNAE?>);
							}
						});
						
				});
         </script>
                
          <div style="margin:10px 0 20px 30px;" class="opcao_simples" >
            As mercadorias industrializadas no período:<br />
            <div style="clear: both; height: 5px" ></div>
            <input name="vendida_em_<?=$CNAE?>" type="radio" /> Foram vendidas apenas no Brasil.
            <div style="clear: both; height: 5px" ></div>
            <input name="vendida_em_<?=$CNAE?>" type="radio" /> Foram vendidas no Brasil e no exterior.
            <div style="clear: both; height: 5px" ></div>
            <input name="vendida_em_<?=$CNAE?>" type="radio" /> Foram vendidas apenas no exterior.
          </div>

          <div id="div_respostas_vendas_brasil_<?=$CNAE?>" style="display: none; margin:10px 0 20px 30px;" class="opcao_simples" >
            Nas vendas para o Brasil:<br />
            <div style="clear: both; height: 5px" ></div>
            <input name="subst_tributaria_<?=$CNAE?>" type="radio" /> <strong>NENHUMA</strong> das mercadorias vendidas no período está sujeita à substituição tributária.
            <div style="clear: both; height: 5px" ></div>
            <input name="subst_tributaria_<?=$CNAE?>" type="radio" /> <strong>ALGUMAS</strong> das mercarias vendidas no período estão sujeitas à substituição tributária e outras não.
            <div style="clear: both; height: 5px" ></div>
            <input name="subst_tributaria_<?=$CNAE?>" type="radio" /> <strong>TODAS</strong> as das mercarias vendidas estão sujeitas à substituição tributária.
          </div>
<?
			}
?>
      <input type="hidden" name="marcar_check_<?=$CNAE?>" />
                
<?
		// ATIVIDADES DO ANEXO 1
    } else if(in_array($anexo_CNAE,$anexos1)){
?>
				<script type="text/javascript">
        	$(document).ready(function(e) {
						
						var campo_hidden<?=$CNAE?> = $('input[name=marcar_check_<?=$CNAE?>]');

						var check1_<?=$CNAE?> = '01';
						var check2_<?=$CNAE?> = '02';
						var check3_<?=$CNAE?> = '03';
						var check4_<?=$CNAE?> = '04';

						if(campo_hidden<?=$CNAE?>.val() != '' && ($('input:radio[name=vendida_em_<?=$CNAE?>]:checked').index('input[name=vendida_em_<?=$CNAE?>]')) != 2){
							$('#div_respostas_vendas_brasil_<?=$CNAE?>').css('display', 'block');
						}
						
						$('input:radio[name=vendida_em_<?=$CNAE?>], input:radio[name=subst_tributaria_<?=$CNAE?>]').click(function(){

							var vendida_em_<?=$CNAE?> = ($('input:radio[name=vendida_em_<?=$CNAE?>]:checked').index('input[name=vendida_em_<?=$CNAE?>]'));
							var subst_tributaria_<?=$CNAE?> = ($('input:radio[name=subst_tributaria_<?=$CNAE?>]:checked').index('input[name=subst_tributaria_<?=$CNAE?>]'));
							
							if(vendida_em_<?=$CNAE?> == 0 || vendida_em_<?=$CNAE?> == 1){
								$('#div_respostas_vendas_brasil_<?=$CNAE?>').css('display', 'block');
								campo_hidden<?=$CNAE?>.val('');
								if(subst_tributaria_<?=$CNAE?> == 0){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?>);
								} else if(subst_tributaria_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check2_<?=$CNAE?> + ';' + check3_<?=$CNAE?>);
								} else if(subst_tributaria_<?=$CNAE?> == 2){
									campo_hidden<?=$CNAE?>.val(check1_<?=$CNAE?> + ';' + check3_<?=$CNAE?>);
								} else {
									campo_hidden<?=$CNAE?>.val('');
								}
								
								if(subst_tributaria_<?=$CNAE?> >= 0 && vendida_em_<?=$CNAE?> == 1){
									campo_hidden<?=$CNAE?>.val(campo_hidden<?=$CNAE?>.val() + ';' + check4_<?=$CNAE?>);
								}
							}else{
								$('#div_respostas_vendas_brasil_<?=$CNAE?>').css('display', 'none');
								$('input:radio[name=subst_tributaria_<?=$CNAE?>]').attr('checked',false);
								campo_hidden<?=$CNAE?>.val(check4_<?=$CNAE?>);
							}
						});
						
          });
          </script>
                
          <div style="margin:10px 0 20px 30px;" class="opcao_simples" >
            Sobre as vendas efetuadas no período:<br />
            <div style="clear: both; height: 5px" ></div>
            <input name="vendida_em_<?=$CNAE?>" type="radio" /> Realizei apenas vendas para o Brasil.
            <div style="clear: both; height: 5px" ></div>
            <input name="vendida_em_<?=$CNAE?>" type="radio" /> Realizei vendas para o Brasil e para o exterior.
            <div style="clear: both; height: 5px" ></div>
            <input name="vendida_em_<?=$CNAE?>" type="radio" /> Realizei apenas vendas para o exterior.
          </div>
          
          <div id="div_respostas_vendas_brasil_<?=$CNAE?>" style="display: none; margin:10px 0 20px 30px;" class="opcao_simples" >
            Nas vendas para o Brasil:<br />
            <div style="clear: both; height: 5px" ></div>
            <input name="subst_tributaria_<?=$CNAE?>" type="radio" /> <strong>NENHUMA</strong> das mercadorias vendidas no período está sujeita à substituição tributária.
            <div style="clear: both; height: 5px" ></div>
            <input name="subst_tributaria_<?=$CNAE?>" type="radio" /> <strong>ALGUMAS</strong> das mercarias vendidas no período estão sujeitas à substituição tributária e outras não.
            <div style="clear: both; height: 5px" ></div>
            <input name="subst_tributaria_<?=$CNAE?>" type="radio" /> <strong>TODAS</strong> as das mercarias vendidas estão sujeitas à substituição tributária.
          </div>
          <input type="hidden" name="marcar_check_<?=$CNAE?>" />
<?
    }
	// fim do foreach	
	}
?>
	</div>
    
    <input type="hidden" id="hidTotalLinhas" value="<?=$linhaAtual?>" />
    <input type="hidden" name="id" value="<?=$_SESSION["id_empresaSecao"]?>" />

    <div style="margin: 0 auto 30px auto; display: table;">
    
        <div class="navegacao" id="btnVoltar" style="margin-right: 10px;">Voltar</div>
        
        <div class="navegacao" id="btnContinuar" style="margin-left: 10px;">Continuar</div>
    
    </div>
    

</form>

<?
	// fim do if de dubios
}
?>




</div>


</div>



<?php include 'rodape.php' ?>





