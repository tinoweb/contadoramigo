<?php 
session_start();

// CONEXAO
//$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//$db = mysql_select_db("contadoramigo");
//mysql_query("SET NAMES 'utf8'");
//mysql_query('SET character_set_connection=utf8');
//mysql_query('SET character_set_client=utf8');
//mysql_query('SET character_set_results=utf8');

// inclui o arquivo de conexão com o banco.
require_once "conect.php";

// pega os cnaes da empresa
$sql_cnaes = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_empresaSecao"] . "'";
$resultado_cnaes = mysql_query($sql_cnaes)
or die (mysql_error());

//descobre qual o numero total de cnaes
$total_cnaes = mysql_num_rows($resultado_cnaes);

// SE A EMPRESA TIVER APENAS UM CNAE PASSA PARA O PASSO SEGUINTE - ESCOLHA DO ANEXO
if($total_cnaes == 1){
	$linha_cnaes = mysql_fetch_array($resultado_cnaes);

	$_SESSION['cnaes_empresa_mes'] = array($linha_cnaes['cnae']); // VARIAVEL DE SESSAO QUE ARMAZENA OS CNAES DA EMPRESA


    header('location: simples_orientacoes_anexos.php');
}	

?>
<?php include 'header_restrita.php';?>

<!--valida preenchimento das perguntas realtivas ao passo 7 e envia para a página simples_orientacoes_retencao.php -->
<script type="text/javascript">

	$(document).ready(function(e) {
	
		$("#btnContinuar").mouseenter(function() {
			$(this).css("background-color", "#a61d00");
		}).mouseleave(function(){
			$(this).css("background-color", "#024a68");
		});
		

		$('#btnContinuar').click(function(e){
	
			var linkFinal = '';
			var total = 0;
		
			for (i=1;i<=$('#hidTotalLinhas').val();i++) {
				if($('#atividade' + i).attr('checked')) {
		
					total = total + 1;
				}
			}
		
			if (total == 0) {
				window.alert('Selecione quais atividades exerceu no período.');
				return false;
			}
				
			$('#formAtividades').submit();
	
		});
		
	});

</script>


<div class="principal minHeight">

<h1>Impostos e Obrigações - Simples Nacional</h1>
<h2>Apuração do Simples</h2>


<?php
	
	// CHECA SE HÁ CNAE CADASTRADO PARA A EMPRESA
	if($total_cnaes <= 0){
?>
<div class="quadro_opcoes<? if($total_cnaes <= 0){ echo " minHeight";}?>" style="display: table;">


		<div style="margin-left:40px; margin-top:40px">Não há CNAE cadastrado para sua empresa. Vá até a página <a href="meus_dados_empresa.php">Meus Dados</a> e atualize o cadastro</div>
        
<?		
	}else{
?>
<div class="quadro_opcoes<? if($total_cnaes <= 0){ echo " minHeight";}?>" style="display: table;">

	<div style="font-size:20px; margin-top:30px" class="perguntas_simples2">Assinale as atividades que exerceu no período, entre aquelas previstas no seu CNPJ.<br /></div>
	<br />

	<form id="formAtividades" action="simples_orientacoes_anexos.php" method="post">


<?
	// faz o loop para gerar a lista de cnae (enquanto existirem cnaes nos paremetros indicados gere uma lista
	while ($linha_cnaes=mysql_fetch_array($resultado_cnaes)) { 

		//numera as linhas
		$linhaAtual = $linhaAtual + 1;
		
		//pega a descrição de cada cnae - em 09/02/2015 - retirada a informação do anexo da consulta
		$sql2 = "SELECT denominacao
				FROM 
					cnae
				WHERE 
					REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='" . str_replace(array("/","-","."),"",$linha_cnaes["cnae"]) . "' LIMIT 0, 1";
				

		$resultado2 = mysql_query($sql2)
		or die (mysql_error());

		
		//cria uma lista de descrições de cnaes
		$linha2=mysql_fetch_array($resultado2);
?>

		<div style="margin-bottom:5px; font-size:15px"  class="perguntas_simples2">

<?php 
            //monta conteúdo: radio button +  numero do cnae + descrição ( pre-seleciona e esconde radio buttom clicado se tiver apenas um cnae) 
            ?><label for="atividade<?=$linhaAtual?>"><?php ?><input id="atividade<?=$linhaAtual?>" type="checkbox" name="descricaoAtividade[]" value="<?=$linha_cnaes["cnae"]?>"  <?php if ($total == "1") { echo 'checked="checked" style="display:none"'; } 
            ?> />&nbsp;&nbsp;<?=$linha2["denominacao"]?></label>
    
		</div>
    


<?php 
		// fim do loop
		} 
?>

        <input type="hidden" id="hidTotalLinhas" value="<?=$linhaAtual?>" />
        <input type="hidden" name="id" value="<?=$_SESSION["id_empresaSecao"]?>" />

	    </form>

        <div style="margin: 10px auto 30px auto; display: table;">
        
            <div class="navegacao" id="btnContinuar" style="margin-left: 10px;">Continuar</div>
        
        </div>

<?		
	// FIM DO IF
	}
?>






	</div>


</div>


<?php include 'rodape.php' ?>