<?php 
session_start();

//$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//$db = mysql_select_db("contadoramigo");

// inclui o arquivo de conexão.
require_once "conect.php";

unset($_SESSION['cnaes_empresa_mes']);
unset($_SESSION['anexos_empresa_mes']);

// pega as atividades exercidas pela empresa no periodo
$sql_atividades = "SELECT * FROM dados_atividades_periodo WHERE id='" . $_SESSION["id_empresaSecao"] . "'";
$resultado_atividades_periodo = mysql_query($sql_atividades)
or die (mysql_error());
if(mysql_num_rows($resultado_atividades_periodo) > 0){

	$arrCNAEs = array();
	$arrAnexos = array();
	while($linha_cnaes = mysql_fetch_array($resultado_atividades_periodo)){
		array_push($arrCNAEs,$linha_cnaes['cnae']);
		$arrAnexos[str_replace("-","",str_replace("/","",$linha_cnaes['cnae']))] = str_replace("c","",$linha_cnaes['anexo']);
	}
	$_SESSION['cnaes_empresa_mes'] = $arrCNAEs;
	$_SESSION['anexos_empresa_mes'] = $arrAnexos;
	
	header('location:impostos_aliquotas_calcula.php');

}else{

	// pega os cnaes da empresa
	$sql_cnaes = "SELECT empCod.cnae FROM dados_da_empresa_codigos empCod INNER JOIN cnae c ON empCod.cnae = c.cnae WHERE empCod.id='" . $_SESSION["id_empresaSecao"] . "' AND c.anexo != 'x'";
	$resultado_cnaes = mysql_query($sql_cnaes)
	or die (mysql_error());

	$arrCNAEs = array();
	while($linha_cnaes = mysql_fetch_array($resultado_cnaes)){
		array_push($arrCNAEs,$linha_cnaes['cnae']);
	}

	$_SESSION['cnaes_empresa_mes'] = $arrCNAEs;
	
}

$resultado_checa = mysql_query("SELECT * FROM dados_impostos_aliquotas WHERE id = " . $_SESSION["id_empresaSecao"]);
if(mysql_num_rows($resultado_checa) > 0){

	header('location:impostos_aliquotas_calcula.php');
}


$cnae_impeditivo = false;

// CRIANDO UMA STRING COM OS CNAEs PARA PODER BUSCAR NO BANCO DE DADOS OS IMPEDITIVOS
$param_cnae = "'" . implode('\',\'',$_SESSION['cnaes_empresa_mes']) . "'";

//	ARRAY CONTENDO TODOS OS CNAES QUE POSSUEM ALGUMA CHECAGEM EXTRA A SER FEITA
$array_cnaes = array(
				'1'=>'1412602'
				,'2'=>'4330401'
				,'3'=>'5620102'
				,'4'=>'4330499'
				,'5'=>'4330405'
				,'6'=>'4330404'
				,'7'=>'4330403'
				,'8'=>'4330402'
				,'9'=>'4330401'
				,'10'=>'4322303'
				,'11'=>'4322301'
				,'12'=>'4321500'
				,'13'=>'3240003'
				,'14'=>'2722802'
				,'15'=>'2539000'
				,'16'=>'1813099'
				,'17'=>'1813001'
				,'18'=>'1812100'
				,'19'=>'1811302'
				,'20'=>'1811301'
				,'21'=>'1610202'
				,'22'=>'6920601'
				);

// PERCORRENDO O PARAMETRO PASSADO PELO FORM ANTERIOR PARA ELIMINAR OS CARACTERES A MAIS DO CNAE
foreach($_SESSION['cnaes_empresa_mes'] as $codigo => $valor){
	$array_cnaes_empresa[$codigo] = str_replace(" ","",str_replace("/","",str_replace("-","",$valor)));
}

// CHECANDO SE O CNAE É IMPEDITIVO
//$checa_impeditivo = mysql_fetch_array(mysql_query("SELECT count(*) total FROM cnae WHERE cnae IN (" .  $param_cnae . ") AND anexo = 'x'"));
// SE FOR DEVE MOSTRAR MENSAGEM
//if($checa_impeditivo['total'] > 0){
//	$cnae_impeditivo = true;
//}

// CRIANDO UMA ARRAY COM OS VALORES QUE FORAM ENCONTRADOS NAS 2 ARRAYS PARA DETERMINAR SE HÁ A NECESSIDADE DE FAZER AS PERGUNTAS
$localiza_dubios = array_intersect($array_cnaes,$array_cnaes_empresa);

// SE O CNAE NÃO É DÚBIO PASSA PARA O PASSO SEGUINTE - ESCOLHA DA RETENCAO
if(count($localiza_dubios) == 0 && $cnae_impeditivo == false){
    header('location: impostos_aliquotas_calcula.php');
}
?>

<?php include 'header_restrita.php' ;?>

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
				// somando 1 na variavel que conta o loop
				$linhaAtual++;
		
				$descr_cnae = mysql_fetch_array(mysql_query("SELECT * FROM cnae WHERE cnae = '" .  $cnae . "'"));
				
				$CNAE = str_replace("/","",str_replace("-","",$cnae));
				
				if(in_array($CNAE,$array_cnaes)){
				
					echo "if(!$('input:radio[name=ativ_".$CNAE."]:checked').val()){
								alert('Selecione uma resposta referente à pergunta da atividade\\n\"" . $descr_cnae['denominacao'] . "\"');
								//$('input:radio[name=ativ_".$CNAE."]').eq(0).focus();
								return false;
							}
						";
				}
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

<span class="titulo">Alíquotas de Impostos</span><br /><br />

<div class="quadro_opcoes" style="display: table; padding:15px;">

<?
if(	$cnae_impeditivo == true){
?>

	<div class="perguntas_simples2" style="font-size:20px; margin:30px; color: #C00">
    	A atividade cadastrada é impeditiva para optantes pelo Simples.<br />
		Por favor, entre em contato com o Help Desk informando este problema.<br />
			<br />
        <div style="margin: 0 auto 30px auto; display: table;">
        
            <div class="navegacao" id="btnVoltar" style="margin-right: 10px;">Voltar</div>
            
        </div>

    </div>
	

<?	
} else {
?>
<!--	<div style="font-size:20px; margin-top:30px" class="perguntas_simples2">Dentre as atividades selecionadas, determine a forma de prestação.<br /></div>-->
<form id="form_anexos" action="impostos_aliquotas_calcula.php" method="post"><!-- onsubmit="return listar();">-->
<?php
$linhaAtual = 0;
foreach($_SESSION['cnaes_empresa_mes'] as $cnae){
$CNAE = str_replace("/","",str_replace("-","",$cnae));
// somando 1 na variavel que conta o loop
$linhaAtual++;
$descr_cnae = mysql_fetch_array(mysql_query("SELECT * FROM cnae WHERE cnae = '" .  $cnae . "'"));?>
<div style="font-size:15px;display:<?=array_search($CNAE,$array_cnaes) ? "block" : "none" ?>;font-family:vagLight;color: #a61d00;">
<?=ltrim(rtrim($descr_cnae['denominacao']));?>
<div id="divRetencao<?=$linhaAtual?>" style="display:block"> 
<?
		// AQUI DEVE SER INSERIDO UM NOVO GRUPO SEGUINDO O PADRÃO ABAIXO
		switch($CNAE){
		
			case "1412602":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_1412602" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_1412602" type="radio" value="IIc" /> Para terceiros
				</div>
<?php 
			break;
			
			case "1413402":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_1413402" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_1413402" type="radio" value="IIc" /> Para terceiros
				</div>
<?php
			break;
			
			case "1610202":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_1610202" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_1610202" type="radio" value="IIc" /> Para terceiros
				</div>
<?php
			break;
			
			case "1811301":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_1811301" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_1811301" type="radio" value="IIc" /> Para terceiros
				</div>
<?php
			break;
			
			case "1811302":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_1811302" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_1811302" type="radio" value="IIc" /> Para terceiros
				</div>
<?php
			break;
			
			case "1812100":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_1812100" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_1812100" type="radio"  value="IIc" /> Para terceiros
				</div>
<?php
			break;
			
			case "1813001":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_1813001" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_1813001" type="radio"  value="IIc" /> Para terceiros
				</div>
<?php
			break;
			
			case "1813099":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_1813099" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_1813099" type="radio" value="IIc" /> Para terceiros
				</div>
<?php
			break;
			
			case "2539000":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_2539000" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_2539000" type="radio" value="IIc" /> Para terceiros
				</div>
<?php
			break;
			
			case "2722802":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Desenvolve esta atividade por conta própria ou para terceiros?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_2722802" type="radio" value="II" /> Por conta própria
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_2722802" type="radio"  value="IIc" /> Para terceiros
				</div>
<?php
			break;
			
			case "3240003":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Os bens são destinados exclusivamente à locação?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_3240003" type="radio" value="II" /> Não
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_3240003" type="radio" value="III" /> Sim
				</div>
<?php
			break;
			
			case "4321500":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada a empreitada ou trata-se apenas de um serviço de manutenção?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_4321500" type="radio" value="IV" /> Está vinculada a uma empreitada
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4321500" type="radio" value="III" /> Apenas serviço de manutenção
				</div>
<?php
			break;
			
			case "4322301":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada a empreitada ou trata-se apenas de um serviço de manutenção?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_4322301" type="radio" value="IV" /> Está vinculada a uma empreitada
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4322301" type="radio" value="III" /> Apenas serviço de manutenção
				</div>
<?php
			break;
			
			case "4322303":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada a empreitada da construção civil?<<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_4322303" type="radio" value="IV" /> Está vinculada a uma empreitada
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4322303" type="radio" value="III" /> Não está vinculado a uma empreitada
				</div>
<?php
			break;
			
			case "4330401";
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada a empreitada da construção civil? Realizam montagens e instalações para feiras e eventos?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_4330401" type="radio" value="IV" /> É vinculada à empreitada.
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4330401" type="radio" value="V" /> Não é vinculada à empreitada e consiste na montagens e instalações para feiras e eventos.
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4330401" type="radio" value="III" /> Não é vinculada à empreitada, mas não se trata de montagem e instalação para feiras e eventos.
				</div>
<?php
			break;
			
			case "4330402":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada a empreitada da construção civil?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_4330402" type="radio" value="IV" /> Sim
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4330402" type="radio" value="III" /> Não
				</div>
<?php
			break;
			
			case "4330403":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada a empreitada da construção civil?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_4330403" type="radio" value="IV" /> Sim
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4330403" type="radio" value="III" /> Não
				</div>
<?php
			break;
			
			case "4330404":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada a empreitada da construção civil?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_4330404" type="radio" value="IV" /> Sim
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4330404" type="radio" value="III" /> Não
				</div>
<?php
			break;
			
			case "4330405":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada a empreitada da construção civil?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_4330405" type="radio" value="IV" /> Sim
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4330405" type="radio" value="III" /> Não
				</div>
<?php
			break;
			
			case "4330499":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada a empreitada da construção civil?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_4330499" type="radio" value="IV" /> Sim
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_4330499" type="radio" value="III" /> Não
				</div>
<?php
			break;
			
			case "5620102":
?>	
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Esta atividade está vinculada ao fornecimento de alimentos para eventos?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_5620102" type="radio" value="I" /> Sim
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_5620102" type="radio" value="III" /> Não
				</div>
<?php
			break;
			
			case "6920601":
?>
				<div style="margin-bottom:10px; margin-top:10px" class="opcao_simples" >
					Em sua atividade de serviços contábeis, está autorizado pela legislação municipal a pagar ISS em valor fixo em guia do município?<br />
					<div style="clear: both; height: 15px" ></div>
					<input name="ativ_6920601" type="radio" value="IIIc" /> Sim
					<div style="clear: both; height: 5px" ></div>
					<input name="ativ_6920601" type="radio" value="III" /> Não
				</div>
<?			
			break;
			
			case "x":
?>
				<div style="margin-bottom:10px; margin-top:10px" >
					A atividade cadastrada é impeditiva para optantes do Simples. Por favor, entre em contato com o Help Desk informando este problema.
				</div>
<?
			break;
			
			default:
?>
				<input type="hidden" name="ativ_<?=$CNAE?>" value="<?=$descr_cnae['anexo']?>" />
<?				
			break;
		}
	?>
			</div>
		<div style="clear:both; height:10px"></div>
	</div>
<?
	}
?>
<input type="hidden" id="hidTotalLinhas" value="<?=$linhaAtual?>" />
<input type="hidden" name="id" value="<?=$_SESSION["id_empresaSecao"]?>" />
</form>
<div style="margin: 0 auto; display: table;">
    <div class="navegacao" id="btnContinuar">Continuar</div>
</div>

<?
// FIM DO IF DE IMPEDIMENTO
}
?>



</div>


</div>



<?php include 'rodape.php' ?>