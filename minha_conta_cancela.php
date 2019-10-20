<?php 
include 'header_restrita.php';

//if($_SESSION["id_userSecaoMultiplo"] != 1581){
//	$sql_configuracoes = "SELECT * FROM configuracoes WHERE configuracao = 'mensalidade'";
//	$rsConfiguracoes = mysql_fetch_array(mysql_query($sql_configuracoes));
//	
//	$mensalidade = $rsConfiguracoes['valor'];
//}else{
//	$mensalidade = 1;
//}



/*
#####################################
           VALIDAÇÕES
#####################################
*/


$show_log = false;

	/*
	DEVE SER ALTERADO O STATUS DE PAGAMENTO ANTES DE TUDO PARA NÃO CORRER O RISCO DE EFETIVAR PAGAMENTOS FUTUROS (STATUS A VENCER)
	
	
	####################################################################
	ATUALIZANDO PAGAMENTOS VENCIDOS
	####################################################################
	*/
	
		$sqlUpdateVencidos = "
			UPDATE 
				historico_cobranca h 
			INNER JOIN 
				login l ON h.id = l.id 
			SET 
				h.status_pagamento = (CASE 
										WHEN l.status in ('demo','demoInativo') THEN 'não pago' 
										ELSE 'vencido' 
									END) 
			WHERE 
				l.id = '" . $_SESSION["id_userSecaoMultiplo"] . "' 
				AND status_pagamento IN ('a vencer') 
				AND DATEDIFF(data_pagamento, DATE(now())) <= 0
			";
		$rsAtualizaVencidos = mysql_query($sqlUpdateVencidos);

		// ############################## LOG ###############################
		if($show_log == true){
			echo "alterando status de pagamentos para vencido (afetou " . mysql_affected_rows() . ")<BR><BR>";
		}
		// ############################## LOG ###############################

	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS VENCIDOS
	####################################################################
	*/


	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS NAO PAGOS
	####################################################################
	*/

		//$sqlUpdateNaoPago = "UPDATE `historico_cobranca` SET status_pagamento = 'não pago' WHERE DATEDIFF(data_pagamento, DATE(now())) between -90 AND -6 AND status_pagamento IN ('vencido', 'a vencer')";
		$sqlUpdateNaoPago = "UPDATE `historico_cobranca` SET status_pagamento = 'não pago' WHERE id = '" . $_SESSION["id_userSecaoMultiplo"] . "' AND status_pagamento IN ('vencido') AND DATEDIFF(data_pagamento, DATE(now())) <= -6";
		$rsAtualizaNaoPago = mysql_query($sqlUpdateNaoPago);

		// ############################## LOG ###############################
		if($show_log == true){
			echo "alterando status de pagamentos para não pago (afetou " . mysql_affected_rows() . ")<BR><BR>";
		}
		// ############################## LOG ###############################

	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS NAO PAGOS
	####################################################################
	*/



	/*
	####################################################################
	DESATIVANDO USUARIO
	####################################################################
	

		$sqlUpdateLogin = "
			UPDATE 
				login l 
			SET 
				l.status = CASE WHEN l.status =  'ativo' THEN  'inativo' 
								WHEN l.status =  'demo' THEN  'demoInativo' 
								ELSE l.status END 
			WHERE 
				l.id = '" . $_SESSION["id_userSecao"] . "' 
				AND ( SELECT COUNT(*) FROM historico_cobranca WHERE id = l.id AND status_pagamento =  'não pago') >0
		";
		$rsUpdateLogin = mysql_query($sqlUpdateLogin);

		// ############################## LOG ###############################
		if($show_log == true){
			echo "inativando usuario (afetou " . mysql_affected_rows() . ")<BR><BR>";
		}
		// ############################## LOG ###############################


	
	####################################################################
	DESATIVANDO USUARIO
	####################################################################
	*/



	/*
	####################################################################
	ATIVANDO USUARIO
	####################################################################
	

		$sqlUpdateLogin = "
			UPDATE 
				login l 
			SET 
				l.status = CASE WHEN l.status =  'inativo' THEN  'ativo' 
								ELSE l.status END 
			WHERE 
				l.id = '" . $_SESSION["id_userSecao"] . "' 
				AND (SELECT COUNT(*) FROM historico_cobranca WHERE id = l.id AND status_pagamento = 'não pago') = 0
		";
		
		$rsUpdateLogin = mysql_query($sqlUpdateLogin);

		// ############################## LOG ###############################
		if($show_log == true){
			echo "ativando usuario (afetou " . mysql_affected_rows() . ")<BR><BR>";
		}
		// ############################## LOG ###############################


	
	####################################################################
	ATIVANDO USUARIO
	####################################################################
	*/




/*
#####################################
           VALIDAÇÕES
#####################################
*/




$sql_dados_login = "
	SELECT 
		l.id
		, l.email
		, l.status
		, dc.assinante
		, dc.data_inclusao
		, dc.forma_pagameto
		, dc.pref_telefone
		, dc.telefone
		, dc.numero_cartao
		, dc.codigo_seguranca
		, dc.nome_titular
		, dc.data_validade
	FROM 
		login l
		INNER JOIN dados_cobranca dc ON l.id = dc.id
	WHERE 
		l.id='" . $_SESSION["id_userSecaoMultiplo"] . "' 
		AND l.id = l.idUsuarioPai
	LIMIT 0, 1
	";
$resultado_dados_login = mysql_query($sql_dados_login)
or die (mysql_error());

$linha_dados_login = mysql_fetch_array($resultado_dados_login);

	$id_usuario = $linha_dados_login['id'];
	$email_usuario = $linha_dados_login['email'];
	$status_login = $linha_dados_login['status'];
	$assinante = $linha_dados_login['assinante'];
	$data_inclusao = $linha_dados_login['data_inclusao'];
	$forma_pagamento_assinante = $linha_dados_login['forma_pagameto'];
	$pref_telefone = $linha_dados_login['pref_telefone'];
	$telefone = $linha_dados_login['telefone'];

	$numero_cartao = $linha_dados_login['numero_cartao'];
	$codigo_seguranca = $linha_dados_login['codigo_seguranca'];
	$nome_titular = $linha_dados_login['nome_titular'];
	$data_validade = $linha_dados_login['data_validade'];
	
	$passNum = 0;
	while ($passNum < strlen($linha_dados_login["senha"])) {
		$senha = $senha . '*'; 
		$passNum = $passNum + 1;
	} 

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};

?>
<script type="text/javascript">

 var msg1 = 'É necessário preencher o campo';
 var msg2 = 'É necessário selecionar ';



$(document).ready(function(e) {

	$('#btnCancelar').click(function(e){
		e.preventDefault();

		if(validElement('txtMotivo', msg1) == false){return false}
				
		var check_selecionado = ($('input[name=rdbSatisfacao]:checked').val());
		
		if(!check_selecionado){
			alert('É necessário informar o grau de satisfação.');
			$('input[name=rdbSatisfacao]').eq(0).focus();
			return false;
		}
		
		if(confirm('Após o cancelamento da sua assinatura o acesso ao Portal Contador Amigo será totalmente interrompido! Deseja prosseguir?')){

			if(confirm('Os dados cadastrais da sua empresa serão arquivados por um período de 6 meses caso deseje retornar ao Contador Amigo. Boa sorte!')){

				document.getElementById('form_cancela_conta').submit();				

			}

		}

		
	});


	$('#btnVoltar').click(function(e){
		e.preventDefault();

		location.href='minha_conta.php';
		
	});



});

</script>
<div class="principal">
  <div style="width:630px">
  <form name="form_cancela_conta" id="form_cancela_conta" method="post" action="minha_conta_cancela_gravar.php" style="display:inline">

<div class="titulo" style="margin-bottom:20px">Dados da Conta</div>

<div class="tituloVermelho" style="margin-bottom:20px">Cancelamento de assinatura</div>

<p>Que pena, <?=$assinante?>!</p>

<p>Existe algo que possamos fazer para que você desista de cancelar sua assinatura? Em caso afirmativo, contate-nos agora mesmo pelo <a href="suporte.php">Help Desk</a>, ou pelo telefone: (11) 3434-6631. <br>
<br>
Se estiver decidido em prosseguir com o cancelamento, por favor, nos informe abaixo o motivo da desistência para que possamos seguir aprimorando nossos serviços a cada dia.</p>

<p><textarea name="txtMotivo" id="txtMotivo" cols="62" rows="5" alt="Motivo"></textarea></p>

<p>No período em que utilizou o Contador Amigo, qual o grau de satisfação?</p>

<p>
  <input type="radio" value="1" name="rdbSatisfacao" id="rdbSatisfacao" style="margin-bottom: 5px;"> Muito satisfeito<br>
  <input type="radio" value="2" name="rdbSatisfacao" style="margin-bottom: 5px;"> Satisfeito<br>
  <input type="radio" value="3" name="rdbSatisfacao" style="margin-bottom: 5px;"> Insatisfeito<br>
  <input type="radio" value="4" name="rdbSatisfacao" style="margin-bottom: 5px;"> Muito insatisfeito<br>
</p>


<input type="hidden" name="hidID" id="hidID" value="<?=$id_usuario?>" />

<p style="text-align:center;">
	<input name="btnCancelar" type="button" id="btnCancelar"  value="Cancelar assinatura" />&nbsp;&nbsp;
  <input name="btnVoltar" type="button" id="btnVoltar"  value="Voltar" />
</p>


</form>

</div>

</div>

<?php include 'rodape.php' ?>
