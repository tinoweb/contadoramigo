<?php 
include 'classes/numero_extenso.php'; 

//Função para converter texto para caixa alta ou baixa
function convertem($term, $tp) {	
    if ($tp == "1") $palavra = strtr(strtoupper($term),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
    elseif ($tp == "0") $palavra = strtr(mb_strtolower($term, mb_detect_encoding($term)),"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß","àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
    return $palavra;
}

function mascaraRG($string){
	$string = str_replace('-','',str_replace('.','',$string));
	$aux = '';
	for ($i=strlen($string)-1; $i >= 0 ; $i--) { 
		if( $i == strlen($string) - 2 )
			$aux .= '-';
		if( $i == strlen($string) - 5 )
			$aux .= '.';
		if( $i == strlen($string) - 8 )
			$aux .= '.'; 
		$aux .= strtoupper($string[$i]);
	}
	echo strrev($aux);

}
//Função para formatar o texto de acordo com o sexo
function checaSexo ($sexo, $masculino, $feminino) {	
	$retorno = "";
		
	if (strtolower($sexo) == "masculino") $retorno = $masculino;
	else $retorno = $feminino;
	
	return $retorno;
}

function extensoEstado($sigla){
	
	$estados = array(
		'AC' => 'Acre'
		, 'AL' => 'Alagoas'
		, 'AM' => 'Amazonas'
		, 'AP' => 'Amapá'
		, 'BA' => 'Bahia'
		, 'CE' => 'Ceará'
		, 'DF' => 'Distrito Federal'
		, 'ES' => 'Espírito Santo'
		, 'GO' => 'Goiás'
		, 'MA' => 'Maranhão'
		, 'MG' => 'Minas Gerais'
		, 'MS' => 'Mato Grosso do Sul'
		, 'MT' => 'Mato Grosso'
		, 'PA' => 'Pará'
		, 'PB' => 'Paraíba'
		, 'PE' => 'Pernambuco'
		, 'PI' => 'Piauí'
		, 'PR' => 'Paraná'
		, 'RJ' => 'Rio de Janeiro'
		, 'RN' => 'Rio Grande do Norte'
		, 'RO' => 'Rondônia'
		, 'RR' => 'Roraima'
		, 'RS' => 'Rio Grande do Sul'
		, 'SC' => 'Santa Catarina'
		, 'SE' => 'Sergipe'
		, 'SP' => 'São Paulo'
		, 'TO' => 'Tocantins'
	);
	
	return $estados[$sigla];

}

function preposicaoEstado($sigla){
	switch($sigla){
		case 'BA';
			$preposicao = 'da';
		break;
		case 'AL';
		case 'GO';
		case 'MG';
		case 'PE';
		case 'RO';
		case 'RR';
		case 'SC';
		case 'SP';
			$preposicao = 'de';
		break;
		default;
			$preposicao = 'do';
		break;
	}

	return $preposicao;
}


function getDadosCliente($tabela, $nomeCampo, $idUser){

	// TRAZ DADOS DO CLIENTE
	$res = mysql_query("SELECT " . $nomeCampo . " valor FROM " . $tabela . " WHERE id='" . $idUser . "'") or die (mysql_error());
	if($dados = mysql_fetch_array($res)){
		return $dados['valor'];
	}else{
		return "";
	}
	
}

?>


<center>
<div style="width:750px; text-align:justify; margin:0 auto 10px; font-family:arial, sans-serif; font-size:14px;">
<?php
//Consulta dados da empresa para formar o cabeçalho de Alteração contratual com nome da empresa e CNPJ
$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);
?>
<div style="text-align:center; font-weight:bold; font-size:15px">ALTERAÇÃO DO CONTRATO SOCIAL DE <br>
  &quot;<?=convertem($linha["razao_social"], 1)?>&quot;<br>
  CNPJ: <?=$linha["cnpj"]?>
</div><br>

<?php
//Loop para exibir dados do sócio
$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
$resultado = mysql_query($sql)
or die (mysql_error());
$totalSocio = mysql_num_rows($resultado);
$numeroSocio = "0";
while ($linha=mysql_fetch_array($resultado)) {
	if($totalSocio == 1) {
		$SexoUnico = $linha["sexo"];
	}
	$numeroSocio = $numeroSocio + 1;
?>
<strong><?=convertem($linha["nome"], 1)?>,</strong> <?=convertem($linha["nacionalidade"], 0)?>, <?=convertem($linha["estado_civil"], 0)?>, <?=convertem($linha["profissao"], 0)?>, portador<?=checaSexo($linha["sexo"], '','a')?> da Cédula de Identidade RG nº <?=str_replace('-','',str_replace('.','',mascaraRG($linha["rg"])))?> – <?=$linha["orgao_expeditor"]?> e CPF nº <?=$linha["cpf"]?>, residente e domiciliad<?=checaSexo($linha["sexo"], 'o','a')?> à <?=$linha["endereco"]?> <?php if ($linha["bairro"] != "") { echo "– " . $linha["bairro"]; } ?> – <?=$linha["cidade"]?> – <?=$linha["estado"]?> – CEP <?=$linha["cep"]?><?php 
if ($numeroSocio == ($totalSocio - 1)) {
	echo " e ";
} else {
	echo ", ";
}
} 
$datas = new Datas();
//Exibir dados da empresa
$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);

if($totalSocio == "1") {
	echo checaSexo($SexoUnico, 'único sócio','única sócia');
} else {
	echo "únicos sócios";
}
?> 
da <?=$linha["inscrita_como"]?> <strong>&quot;<?=convertem($linha["razao_social"], 1)?>&quot;,</strong> com sede à <?=$linha["endereco"]?> <?php if ($linha["bairro"] != "") { echo "– " . $linha["bairro"]; } ?> – <?=$linha["cidade"]?> – <?=$linha["estado"]?> – CEP <?=$linha["cep"]?>, com Contrato Social devidamente registrado <?php if($linha["inscrita_como"] == 'Sociedade Simples') { ?>no <?=$linha["numero_cartorio"]?>º Cartório Oficial de Registro Civil de Pessoa Jurídica da Capital, sob o nº <?=$linha["registro_cartorio"]?> e devidamente inscrita no CNPJ sob o nº <?=$linha["cnpj"]?> <?php } else { ?>na Junta Comercial <?=preposicaoEstado($linha["estado"])?> <?=extensoEstado($linha["estado"])?>, sob Nire nº <?=$linha["registro_nire"]?> em seção de <?php echo $datas->desconverterData($linha["data_de_criacao"]); ?><?php } ?>, <?php
if($totalSocio == "1") {
	echo "resolve";
} else {
	echo "resolvem de comum acordo,";
}?> alterar as seguintes cláusulas:<br>
<br>


<?php
function clausula($numero) {
	echo "<strong>CLÁUSULA ". $numero . "ª</strong>";
} 
$numClau = 1;

//Mudança da razão social
if (isset($_POST["cheMudancaRazao"])) {
	echo $clausula[$numClau];
	clausula($numClau);
	$numClau = $numClau + 1;
?><br>
<?php if(($totalSocio == "1") and (!isset($_POST["cheIncluirSocio"]))) {	?> <?=checaSexo($SexoUnico, 'O sócio','A sócia')?> resolve <?php } else { ?> Os sócios resolvem <?php } ?> neste ato alterar a razão social para <strong>"<?=convertem($_POST['txtRazaoSocial'], 1)?>"</strong>.<br>
<br>

<?php
}
//Mudança da sede da empresa
if (isset($_POST["cheMudancaEndereco"])) {
	echo $clausula[$numClau];
	clausula($numClau);
	$numClau = $numClau + 1;
	
	$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());

	$linha=mysql_fetch_array($resultado);

?><br>
A sede da sociedade passa a ser na <?=$_POST["txtEndereco"]?> <?php if ($_POST["txtBairro"] != "") { echo "– " . $_POST["txtBairro"]; } ?> – <?=$_POST["txtCidade"]?> – <?=$_POST["selEstado"]?> – CEP <?=$_POST["txtCEP"]?>.<br>
<br>
<?php 
} 

// SE FOR EXCLUIR OU ADICIONAR UMA ATIVIDADE DEVE MOSTRAR AS ATIVIDADES ATUAIS DA EMPRESA
if (isset($_POST['cheIncluirAtividade']) || isset($_POST['cheExcluirAtividade'])) {
	
	clausula($numClau);
	$numClau = $numClau + 1;
	echo "<br>";
	
	echo "A sociedade que hoje tem por objeto as atividades: ";
	// Portais, provedores de conteúdo e outros serviços de informação na internet (CNAE: 6319-4/00), Edição de livros (CNAE: 5811-5/00), Edição de jornais (CNAE: 5812-3/00), Edição de revistas (CNAE: 5813-1/00) e Desenvolvimento de programas de computador sob encomenda (CNAE: 6201-5/01), <strong>passa a </strong>
	
	$sql = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idCodigo ASC";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$campo = 1;
	$linhaAdicionada = 1;

	$qtdAtividades = mysql_num_rows($resultado);
	while ($linha=mysql_fetch_array($resultado)) {
		// PEGANDO AS DENOMINAÇÕES DAS ATIVIDADES CORRENTES DA EMPRESA
		$sql2 = "SELECT denominacao FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='" . str_replace(array("/","-","."),"",$linha["cnae"]) . "' LIMIT 0, 1";
		$resultado2 = mysql_query($sql2)
		or die (mysql_error());

		$linha2=mysql_fetch_array($resultado2);

		if($qtdAtividades < mysql_num_rows($resultado)){
			if( $qtdAtividades <= 1){
				echo " e ";
			}else{
				echo ", ";
			}
		}
		
		echo $linha2['denominacao'] . " (CNAE: " . $linha["cnae"] . ")";
	
		$qtdAtividades--;
		
	}
	
	
	echo ", passa a ";
	
	
	//Exclusão de atividade CNAE
	if (isset($_POST["cheExcluirAtividade"])) {
		//	clausula($numClau);
		//	$numClau = $numClau + 1;
		// echo "<BR>";
		?>
		excluir <?=($_POST['hidTotalCnaeExcluido'] == 1 ? " a atividade: " : " as  atividades: ")?> 
		<?php 
		$sql = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idCodigo ASC";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		
		$campo = 1;
		$linhaAdicionada = 1;
		
		while ($linha=mysql_fetch_array($resultado)) {
			$sql2 = "SELECT denominacao FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='" . str_replace(array("/","-","."),"",$linha["cnae"]) . "' LIMIT 0, 1";
			$resultado2 = mysql_query($sql2)
			or die (mysql_error());
			$linha2=mysql_fetch_array($resultado2);
			
			if (isset($_POST['cheCnaeExcluso' . $campo])) {
				?>
				<?=$linha2["denominacao"]?> (CNAE: <?=$linha["cnae"]?>)<?php 
				if (($_POST['hidTotalCnaeExcluido'] - 1) == $linhaAdicionada) {
					echo ' e'; 
				} else if($_POST['hidTotalCnaeExcluido'] != $linhaAdicionada) { 
					echo ',';
				} else if(!isset($_POST['cheIncluirAtividade'])) {
					echo '.<br><br>'; 
				}


				$linhaAdicionada = $linhaAdicionada + 1;
			}
			$campo = $campo + 1; 
		}
	
	}

	//Inclusão de atividade CNAE
	if (isset($_POST['cheIncluirAtividade'])) {
		if (isset($_POST['cheExcluirAtividade'])) {
			echo ' e ';
		}
	
		//clausula($numClau);
		//$numClau = $numClau + 1;
		//echo "<br>";
		?>
		incluir <?=($_POST['countCNAEIncluso'] == 1 ? " a atividade: " : " as  atividades: ")?>  
		<?php
		$count = $_POST["countCNAEIncluso"];// - 1;
		$campo = 1;

		$aux = 0;
		$itens_validos = array();
		for($i=1;$i<=$count;$i++) {		
			$CodigoCNAE = $_POST["txtCodigoCNAE".$campo];
			$ConsultaCNAE = str_replace(array("/","-","."),"",$CodigoCNAE);
	
	
			$sql = "SELECT * FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='$ConsultaCNAE' LIMIT 0,1";
			$resultado = mysql_query($sql)
			or die (mysql_error());
			$linha=mysql_fetch_array($resultado);

			if( $linha['cnae'] != '' ){
				$itens_validos[$i] = $linha['cnae'];
				$aux = $aux + 1;
			}
			$campo = $campo + 1; 
		}
		$campo = 1;
		$count = $aux;
		for($i=1;$i<=$count;$i++) {
			$CodigoCNAE = $itens_validos[$i];
			$ConsultaCNAE = str_replace(array("/","-","."),"",$CodigoCNAE);
	
	
			$sql = "SELECT * FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='$ConsultaCNAE' LIMIT 0,1";
			$resultado = mysql_query($sql)
			or die (mysql_error());
			$linha=mysql_fetch_array($resultado);
?>
			<?=$linha["denominacao"]?> (CNAE: <?=$linha["cnae"]?>)<?php 
			if (($count - 1) == $campo) {
				echo ' e'; 
			} else if($count != $campo) { 
				echo ',';
			} else {
				echo '.<br><br>'; 
			}
?>
<?php
			$campo = $campo + 1; 
		} 
	}

	echo "";
}




//Inclusão de sócio
if (isset($_POST["cheIncluirSocio"])) {
	clausula($numClau);
	$numClau = $numClau + 1;
		
?>
<br>
<?php
	$count = $_POST["skill_count"];
	for($i=1;$i<=$count;$i++) {

	echo 'É admitid' . checaSexo($_POST['radSexoSocio'.$i], 'o','a') . ' na qualidade de  ';

echo checaSexo($_POST['radSexoSocio'.$i], 'sócio','sócia')?> <strong><?=convertem($_POST["txtNome".$i], 1)?></strong>, <?=convertem($_POST["txtNacionalidade".$i], 0)?>, <?=convertem($_POST["txtEstadoCivil".$i], 0)?>, <?=convertem($_POST["txtProfissao".$i], 0).','?> portador<?=checaSexo($_POST['radSexoSocio'.$i], '','a')?> da Cédula de Identidade RG nº <?=mascaraRG($_POST["txtRG".$i])?> – <?=$_POST["txtOrgaoExpedidor".$i]?> e CPF nº <?=$_POST["txtCPF".$i]?>, residente e domiciliad<?=checaSexo($_POST['radSexoSocio'.$i], 'o','a')?> à <?=$_POST["txtEnderecoSocio".$i]?> <?php if ($_POST["txtBairroSocio".$i] != "") { echo "– " . $_POST["txtBairroSocio".$i]; } ?> – <?=$_POST["txtCidadeSocio".$i]?> – <?=$_POST["selEstadoSocio".$i]?> – CEP <?=$_POST["txtCEPSocio".$i]?><?php 
		if ($i == ($count - 1)) {
			echo " e também é admitido na qualidade de ";
		} else if($i == $count) {
			echo ".";
		} else {
			echo ", também é admitido na qualidade de ";
		}
	} 
?> <br>
<br>

<?php
} 


$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
$resultado = mysql_query($sql)
or die (mysql_error());

$campo = 1;
$linhaAdicionada = 1;

//Exclusão de sócio
if (isset($_POST["cheExcluirSocio"])) {
	clausula($numClau);
	$numClau = $numClau + 1;
?><br>Retira-se <?

		while ($linha=mysql_fetch_array($resultado)) {

			if (isset($_POST['cheSocioExcluso' . $campo])) {
				//Caso as cotas do sócio excluido seja transferida para apenas 1 outro sócio, indentifica a quantidade de cotas e para quem transferiu
				if($_POST['hidTotalSocio' . $campo . 'Transfere'] == 1) {
					for($i=1;$i<=$_POST['hidTotalSocioExistente'];$i++) { 
						if(isset($_POST['cheSocioTransfere'. $i])) {
							for($o=1;$o<=$_POST['hidTotalSocioExistente'];$o++) {
								if(($i != $o) and ($_POST['txtSocio' . $i . 'TransfereCotaPara' . $o] != "") and (!isset($_POST['cheSocioExcluso' . $o]))) {
									$consultaCotaUnicaTransferida = $_POST['txtSocio' . $i . 'TransfereCotaPara' . $o];
								}
							}
							if(isset($_POST['cheIncluirSocio'])) {
								for($o=1;$o<=$_POST['skill_count'];$o++) {
									if($_POST['txtSocio' . $i . 'TransfereCotaParaNovo' . $o] != ""){
										$consultaCotaUnicaTransferida = $_POST['txtSocio' . $i . 'TransfereCotaParaNovo' . $o];
									}
								}
							}
						}
					}			
				}
				$socioRetira = $linha["nome"];	
	?>da sociedade <?=checaSexo($linha["sexo"], 'o sócio','a sócia')?> <strong><?=convertem($linha["nome"], 1)?></strong><?php if (isset($_POST['cheSocioTransfere' . $campo])) { ?>, transferindo 
		<?php if (($_POST['hidTotalSocio' . $campo . 'Transfere'] == 1) and ($consultaCotaUnicaTransferida == $_POST['txtDistribuicaoAtualSocio' . $campo])) { ?>
			a totalidade de suas 
		<?php }
			$resultado2 = mysql_query($sql)
			or die (mysql_error());
			
			$campo2 = 1;
			$linhaAdicionada2 = 1;
			
			while ($linha2=mysql_fetch_array($resultado2)) {
				if (($linha["idSocio"] != $linha2["idSocio"]) and ($_POST['txtSocio' . $campo . 'TransfereCotaPara' . $campo2] != "") and (!isset($_POST['cheSocioExcluso' . $campo2]))){
					echo number_format(str_replace(",",".",str_replace(".","",$_POST['txtSocio' . $campo . 'TransfereCotaPara' . $campo2])),0,",",".");				
					?> (<?=GExtenso::numero($_POST['txtSocio' . $campo . 'TransfereCotaPara' . $campo2], GExtenso::GENERO_FEM)?>) quota<?php if ($_POST['txtSocio' . $campo . 'TransfereCotaPara' . $campo2] != "1") {?>s<?php } 
					
					echo checaSexo($linha2["sexo"], ' ao sócio ',' à sócia ');
					
					echo $linha2["nome"];
					if (($_POST['hidTotalSocio' . $campo . 'Transfere'] - 1) == $linhaAdicionada2) {
						echo ' e transferindo '; 
					} else if($_POST['hidTotalSocio' . $campo . 'Transfere'] != $linhaAdicionada2) { 
						echo ', transferindo ';
					}
					$linhaAdicionada2 = $linhaAdicionada2 + 1;
				}
				$campo2 = $campo2 + 1;
			} 
		}
		for($i=1;$i<=$_POST['skill_count'];$i++) {
			if ($_POST['txtSocio' . $campo . 'TransfereCotaParaNovo' . $i] != "") {
				echo number_format(str_replace(",",".",str_replace(".","",$_POST['txtSocio' . $campo . 'TransfereCotaParaNovo' . $i])),0,",",".");?> (<?=GExtenso::numero($_POST['txtSocio' . $campo . 'TransfereCotaParaNovo' . $i], GExtenso::GENERO_FEM)?>) quota<?php if ($_POST['txtSocio' . $campo . 'TransfereCotaParaNovo' . $i] != "1") {?>s<?php } ?> <?=checaSexo($_POST['radSexoSocio' . $i], 'ao sócio','à sócia')?> ora admitid<?=checaSexo($_POST['radSexoSocio' . $i], 'o','a')?> <?php echo  "<strong>" . convertem($_POST['txtNome' . $i], 1) . "</strong>";
				if (($_POST['hidTotalSocio' . $campo . 'Transfere'] - 1) == $linhaAdicionada2) {
					echo ' e transferindo '; 
				} else if($_POST['hidTotalSocio' . $campo . 'Transfere'] != $linhaAdicionada2) { 
					echo ', transferindo ';
				}
				$linhaAdicionada2 = $linhaAdicionada2 + 1;
			}
		}
		if (isset($_POST['cheSocioTransfere' . $campo])) {
	?> no valor nominal de R$ <?php if(isset($_POST['cheAlterarCotas'])) { echo number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,",",".") ?>  (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,"",""))?>)<?php } else { echo number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,",",".") ?>  (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,"",""))?>)<?php } ?>, totalmente subscrito e integralizado em moeda corrente do país<?php
			}
				if (($_POST['hidTotalSocioExcluido'] - 1) == $linhaAdicionada) {
					echo ' e retira-se também '; 
				} else if($_POST['hidTotalSocioExcluido'] != $linhaAdicionada) { 
					echo ', retira-se também ';
				}
				$linhaAdicionada = $linhaAdicionada + 1;
			}
			$campo = $campo + 1; 	
		} 
		
		
		
	// SE A EMPRESA FICAR COM APENAS UM SOCIO
	if($_POST['hidTotalSocioFinal'] ==1){
				
			echo ', transferindo a totalidade de suas quotas ao sócio remanescente. Por este ato também, o sócio que se retira dá a mais ampla e rasa quitação de seus direitos, nada mais tendo a reclamar em tempo algum quanto a seus direitos na sociedade.';
			
			if(!isset($_POST["cheAlterarCapitalSocial"])){
				echo ' Desta forma o capital social passa a ser distribuído conforme segue:<br /><br />
  
				<table style="color:#000; font-family:arial, sans-serif; font-size:14px" border="0" cellspacing="0" cellpadding="0">';
 
				$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
				$resultado = mysql_query($sql) or die (mysql_error());
				$totalSocio = mysql_num_rows($resultado);
				$numeroSocio = "0";
				$campo = 0;
		
				while ($linhaSoc=mysql_fetch_array($resultado)) {
					if(trim($socioRetira) != trim($linhaSoc["nome"])){
						$campo = $campo + 1;
				
						$porcentagemQuotasAtualSocio 	= ((number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,".","")) / number_format($_POST["totalCotasAtual"],0,".",""));
						$quotasSocioNova 							= number_format($_POST["txtDistribuicaoFuturaSocio" . $campo],0,".","");
						$valorQuotasSocioNova 				= number_format($_POST["txtDistribuicaoFuturaSocio" . $campo],0,".","") * str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual']));
						
						echo '<tr>
								<td style="padding-right:20px">' . $linhaSoc["nome"] . '</td>
								<td style="padding-right:5px;text-align:right">' . number_format($quotasSocioNova,0,"",".") . '</td>
								<td style="padding-right:20px">quota'. ($_POST["txtDistribuicaoFuturaSocio" . $campo] != "1" ? 's' : '') . '</td>
								<td>R$</td>
								<td align="right">' . number_format($valorQuotasSocioNova,2,",",".") . '</td>
							</tr>';
			
						$totalquotas = $totalquotas + $quotasSocioNova;//$_POST["txtDistribuicaoAtualSocio" . $campo];
						$totalEmReais = $totalEmReais + $valorQuotasSocioNova;//(str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoAtualSocio' . $campo])) * $valorCotas);
					}
				} 

				echo  '<tr>
						<td style="padding-right:20px">Total</td>
						<td style="padding-right:5px;text-align:right">' . number_format($totalquotas,0,"",".") . '</td>
						<td style="padding-right:20px">quota' . ($_POST["txtDistribuicaoAtualSocio" . $campo] != "1" ? 's' : '') . '</td>
						<td>R$</td>
						<td align="right">' . number_format($totalEmReais,2,",",".") . '</td>
					</tr>
				</table>';

			}else{
				
				echo '<br>';
				
			}
			
			echo '<br>PARÁGRAFO ÚNICO: Nos termos do artigo 1033, IV, da Lei 10.406/02, a sociedade permanecerá unipessoal, devendo recompor seu quadro societário no prazo máximo de 180 (cento e oitenta) dias, sob pena de dissolução.';
		
		
	} else {
		
		echo '.';
		
		if(isset($_POST['cheAlterarCotas'])){
			
			echo ' Desta forma o capital social passa a ser distribuído conforme segue:<br /><br />
		
			<table style="color:#000; font-family:arial, sans-serif; font-size:14px" border="0" cellspacing="0" cellpadding="0">';
	 
			$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
			$resultado = mysql_query($sql) or die (mysql_error());
			$totalSocio = mysql_num_rows($resultado);
			$numeroSocio = "0";
			$campo = 0;
	
			while ($linhaSoc=mysql_fetch_array($resultado)) {
				if(trim($socioRetira) != trim($linhaSoc["nome"])){
					$campo = $campo + 1;
			
					$porcentagemQuotasAtualSocio 	= ((number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,".","")) / number_format($_POST["totalCotasAtual"],0,".",""));
					$quotasSocioNova 							= number_format($_POST["txtDistribuicaoFuturaSocio" . $campo],0,".","");
					$valorQuotasSocioNova 				= number_format($_POST["txtDistribuicaoFuturaSocio" . $campo],0,".","") * str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual']));
					
					echo 
					'<tr>
						<td style="padding-right:20px">' . $linhaSoc["nome"] . '</td>
						<td style="padding-right:5px;text-align:right">' . number_format($quotasSocioNova,0,"",".") . '</td>
						<td style="padding-right:20px">quota'. ($_POST["txtDistribuicaoFuturaSocio" . $campo] != "1" ? 's' : '') . '</td>
						<td>R$</td>
						<td align="right">' . number_format($valorQuotasSocioNova,2,",",".") . '</td>
					</tr>';
		
					$totalquotas = $totalquotas + $quotasSocioNova;//$_POST["txtDistribuicaoAtualSocio" . $campo];
					$totalEmReais = $totalEmReais + $valorQuotasSocioNova;//(str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoAtualSocio' . $campo])) * $valorCotas);
				}
			} 
			
	
			if(isset($_POST["cheIncluirSocio"])){
				$count = $_POST["skill_count"];
				for($i=1;$i<=$count;$i++) {
					echo  '
						<tr>
							<td style="padding-right:20px">' . $_POST["txtNome" . $i] . '</td>
							<td style="padding-right:5px;text-align:right">' . number_format($_POST["txtDistribuicaoFuturaNovoSocio" . $i],0,"",".") . '</td>
							<td style="padding-right:20px">quota' . ($_POST["txtDistribuicaoFuturaNovoSocio" . $i] != "1" ? 's' : '') . '</td>
							<td>R$</td>
							<td align="right">' . number_format(str_replace(",",".",str_replace(".","",$_POST["txtDistribuicaoFuturaNovoSocio" . $i])) * str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,",",".") . '</td>
						</tr>';
	
					$totalquotas = $totalquotas + $_POST["txtDistribuicaoFuturaNovoSocio" . $i];
					$totalEmReais = $totalEmReais + (str_replace(",",".",str_replace(".","",$_POST["txtDistribuicaoFuturaNovoSocio" . $i])) * str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])));
				}
			}
	
	
			echo  '
				<tr>
					<td style="padding-right:20px">Total</td>
					<td style="padding-right:5px;text-align:right">' . number_format($totalquotas,0,"",".") . '</td>
					<td style="padding-right:20px">quota' . ($_POST["txtDistribuicaoAtualSocio" . $campo] != "1" ? 's' : '') . '</td>
					<td>R$</td>
					<td align="right">' . number_format($totalEmReais,2,",",".") . '</td>
				</tr>
			</table>';
		}
		
	}// FIM IF quantidade socios =1
?>
<br>
<br>
<?php
}

//clausula($numClau);
//$numClau = $numClau + 1;
//<br>




//Alterar Cotas
if ((isset($_POST['cheAlterarCotas'])) or (isset($_POST['cheExcluirSocio'])) or (isset($_POST['cheIncluirSocio']))){
	$transfereCotas = false;
//	echo "###" . $_POST['hidTotalSocioExistente'] . "<BR>";
	for($i=1;$i<=$_POST['hidTotalSocioExistente'];$i++) {
		
		if(((isset($_POST['cheSocioTransfere' . $i])) and (isset($_POST['cheExcluirSocio'])) and (!isset($_POST['cheSocioExcluso' . $i]))) or ((isset($_POST['cheSocioTransfere' . $i])) and (!isset($_POST['cheExcluirSocio'])))){
			$transfereCotas = true;
			$totalTransfere = $totalTransfere + 1;
		}
	}
	if ($transfereCotas == true) { 
		clausula($numClau);
		$numClau = $numClau + 1;?><br>
    
<?php

		$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
		$resultado = mysql_query($sql)
		or die (mysql_error());

		$campo = 1;
		$linhaAdicionada = 1;

		while ($linha=mysql_fetch_array($resultado)) {
		
			if(((isset($_POST['cheSocioTransfere' . $campo])) and (isset($_POST['cheExcluirSocio'])) and (!isset($_POST['cheSocioExcluso' . $campo]))) or ((isset($_POST['cheSocioTransfere' . $campo])) and (!isset($_POST['cheExcluirSocio'])))){
?>
				<?=checaSexo($linha["sexo"], 'O sócio','A sócia')?>  
        
				<strong><?=convertem($linha["nome"], 1)?></strong><?php if ($linhaAdicionada > 1) { echo ' também '; }

				$resultado2 = mysql_query($sql)
				or die (mysql_error());
		
				$campo2 = 1;
				$linhaAdicionada2 = 1;
		
				while ($linha2=mysql_fetch_array($resultado2)) {
					if (($linha["idSocio"] != $linha2["idSocio"]) && ($_POST['txtSocio' . $campo . 'TransfereCotaPara' . $campo2] != "") && (!isset($_POST['cheSocioExcluso' . $campo2]))){
?> 
						cede e transfere <?=number_format(str_replace(",",".",str_replace(".","",$_POST['txtSocio' . $campo . 'TransfereCotaPara' . $campo2])),0,",",".")?> (<?=GExtenso::numero($_POST['txtSocio' . $campo . 'TransfereCotaPara' . $campo2], GExtenso::GENERO_FEM)?>) quota<?php if ($_POST['txtSocio' . $campo . 'TransfereCotaPara' . $campo2] != "1") {?>s <?php } echo checaSexo($linha2['sexo'], 'ao sócio','à sócia');?> <?=$linha2["nome"];
 						if (($_POST['hidTotalSocio' . $campo . 'Transfere'] - 1) == $linhaAdicionada2) {
							echo ' e também '; 
						} else if($_POST['hidTotalSocio' . $campo . 'Transfere'] != $linhaAdicionada2) { 
							echo ', também ';
						}
						$linhaAdicionada2 = $linhaAdicionada2 + 1;
					}
					$campo2 = $campo2 + 1;
				}
				
				for($i=1;$i<=$_POST['skill_count'];$i++) {
					if ($_POST['txtSocio' . $campo . 'TransfereCotaParaNovo' . $i] != "") {
?> 
						cede e transfere <?=number_format(str_replace(",",".",str_replace(".","",$_POST['txtSocio' . $campo . 'TransfereCotaParaNovo' . $i])),0,",",".")?> (<?=GExtenso::numero($_POST['txtSocio' . $campo . 'TransfereCotaParaNovo' . $i], GExtenso::GENERO_FEM)?>) quota<?=($_POST['txtSocio' . $campo . 'TransfereCotaParaNovo' . $i] != "1" ? "s" : "")?>
            <?
						checaSexo($_POST['radSexoSocio' . $i], 'ao sócio','à sócia')?> ora admitid<?=checaSexo($_POST['radSexoSocio' . $i], 'o','a');
						echo " <strong> " . convertem($_POST['txtNome' . $i], 1) . "</strong>";
						if (($_POST['hidTotalSocio' . $campo . 'Transfere'] - 1) == $linhaAdicionada2) {
							echo ' e também cede e transfere'; 
						} else if($_POST['hidTotalSocio' . $campo . 'Transfere'] != $linhaAdicionada2) { 
							echo ', também cede e transfere';
						}
						$linhaAdicionada2 = $linhaAdicionada2 + 1;
					}
				}
				
/*
no valor nominal de R$ <?php if(isset($_POST['cheAlterarCotas'])) { echo number_format(str_replace(",",".",str_replace(".","",($_POST['totalReaisAtual']))),2,",",".") ?>  (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo'])),2,"",""))?>)<?php } else { echo number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,",",".") ?>  (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,"",""))?>)<?php } ?> cada, totalmente subscrito e integralizado em moeda corrente do país, pelo que a sociedade e os quotistas trocam plena, geral, rasa e irrevogável quitação, não tendo mais nada a reclamar em juízo ou fora dele
*/
				
?> no valor nominal de R$ <?php echo number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,",",".") ?>  (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,"",""))?>) cada, totalmente subscrito e integralizado em moeda corrente do país, pelo que a sociedade e os quotistas trocam plena, geral, rasa e irrevogável quitação, não tendo mais nada a reclamar em juízo ou fora dele<?php

				if (($totalTransfere - 1) == $linhaAdicionada) {
					echo ' e o sócio'; 
				} else if($totalTransfere != $linhaAdicionada) { 
					echo ', o sócio';
				}
				$linhaAdicionada = $linhaAdicionada + 1;
			}
			$campo = $campo + 1; 	
		}?>.
<?php 
if(!isset($_POST["cheIncluirSocio"]) && !isset($_POST["cheExcluirSocio"])){
?>Desta forma o capital social passa a ser distribuído conforme segue:<br /><br />
  
<table style="color:#000; font-family:arial, sans-serif; font-size:14px" border="0" cellspacing="0" cellpadding="0">
<?php
		$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
		$resultado = mysql_query($sql) or die (mysql_error());
		$totalSocio = mysql_num_rows($resultado);
		$numeroSocio = "0";
		$campo = 0;

		while ($linha=mysql_fetch_array($resultado)) {
			$campo = $campo + 1;
	
			$porcentagemQuotasAtualSocio 	= ((number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,".","")) / number_format($_POST["totalCotasAtual"],0,".",""));
//			$quotasSocioNova 							= $_POST['totalCotasFuturo'] * $porcentagemQuotasAtualSocio;// * number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,".","");
//			$valorQuotasSocioNova 				= $_POST['totalCotasFuturo'] * $porcentagemQuotasAtualSocio * $valorCotas;// * number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,".","") * $valorCotas;

			$quotasSocioNova 							= number_format($_POST["txtDistribuicaoFuturaSocio" . $campo],0,".","");
			$valorQuotasSocioNova 				= number_format($_POST["txtDistribuicaoFuturaSocio" . $campo],0,".","") * str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual']));
?>
  <tr>
    <td style="padding-right:20px"><?=$linha["nome"]?></td>
    <td style="padding-right:5px;text-align:right"><?=number_format($quotasSocioNova,0,"",".");//number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,"",".")?> </td>
    <td style="padding-right:20px">quota<?php if ($_POST["txtDistribuicaoFuturaSocio" . $campo] != "1") {?>s<?php } ?></td>
    <td>R$</td>
    <td align="right"><?=number_format($valorQuotasSocioNova,2,",",".");//number_format(str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoAtualSocio' . $campo])) * $valorCotas,2,",",".")?></td>
  </tr>
<?php 
			$totalquotas = $totalquotas + $quotasSocioNova;//$_POST["txtDistribuicaoAtualSocio" . $campo];
			$totalEmReais = $totalEmReais + $valorQuotasSocioNova;//(str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoAtualSocio' . $campo])) * $valorCotas);
		} 
?>
  <tr>
    <td style="padding-right:20px">Total</td>
    <td style="padding-right:5px;text-align:right"><?=number_format($totalquotas,0,"",".")?> </td>
    <td style="padding-right:20px">quota<?php if ($_POST["txtDistribuicaoAtualSocio" . $campo] != "1") {?>s<?php } ?></td>
    <td>R$</td>
    <td align="right"><?=number_format($totalEmReais,2,",",".")?></td>
  </tr>
</table><br />

<?php 
}
?>

<BR /><BR />
<?php
	} 
} 





//Alterar capital social
if (isset($_POST["cheAlterarCapitalSocial"])) {
	clausula($numClau);
	$numClau = $numClau + 1;
?><br>
O capital social, que é de R$ <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])) * $_POST['totalCotasAtual'],2,",",".")?> (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])) * $_POST['totalCotasAtual'],2,"",""))?>) totalmente integralizado, e dividido em  <?=number_format($_POST['totalCotasAtual'],0,"",".")?> (<?=GExtenso::numero($_POST['totalCotasAtual'], GExtenso::GENERO_FEM)?>)  quota<?=($_POST["totalCotasFuturo"] != "1" ? "s" : "")?>, cada uma valendo R$ <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,",",".")?> (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,"",""))?>), passa a ser de R$ <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo'])) * $_POST['totalCotasFuturo'],2,",",".")?> (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo'])) * $_POST['totalCotasFuturo'],2,"",""))?>), dividido em <?=number_format($_POST['totalCotasFuturo'],0,"",".")?> (<?=GExtenso::numero($_POST['totalCotasFuturo'], GExtenso::GENERO_FEM)?>) quota<?=($_POST["totalCotasFuturo"] != "1" ? "s" : "")?>, no valor de R$ <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo'])),2,",",".")?> (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo'])),2,"",""))?>
) cada uma. <?php 
	$valorCotas = str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo']));
	$totalquotas = 0;
	$totalEmReais = 0;
	if ((isset($_POST['cheAlterarCotas'])) or (isset($_POST['cheExcluirSocio'])) or (isset($_POST['cheIncluirSocio']))){ 
?>Desta forma o capital social passa a ser distribuído conforme segue:<br><br>
<table style="color:#000; font-family:arial, sans-serif; font-size:14px" border="0" cellspacing="0" cellpadding="0">
<?php
		$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
		$resultado = mysql_query($sql) or die (mysql_error());
		$numeroSocio = 0;
		$campo = 0;
		while ($linha=mysql_fetch_array($resultado)) {
			$campo = $campo + 1;
			if ((isset($_POST["cheExcluirSocio"]) and !isset($_POST["cheSocioExcluso" . $campo])) or (!isset($_POST["cheExcluirSocio"]))) {
?>
  <tr>
    <td style="padding-right:20px"><?=$linha["nome"]?></td>
    <td style="padding-right:5px;text-align:right"><?=number_format($_POST["txtDistribuicaoFuturaSocio" . $campo],0,"",".")?> </td>
    <td style="padding-right:20px">quota<?php if ($_POST["txtDistribuicaoFuturaSocio" . $campo] != "1") {?>s<?php } ?></td>
    <td>R$</td>
    <td align="right"><?=number_format(str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoFuturaSocio' . $campo])) * $valorCotas,2,",",".")?></td>
  </tr>
<?php 
				$totalquotas = $totalquotas + $_POST["txtDistribuicaoFuturaSocio" . $campo];
				$totalEmReais = $totalEmReais + (str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoFuturaSocio' . $campo])) * $valorCotas);
			}
		} 

		if(isset($_POST["cheIncluirSocio"])){
			$count = $_POST["skill_count"];
			for($i=1;$i<=$count;$i++) {
?>
  <tr>
    <td style="padding-right:20px"><?=$_POST["txtNome" . $i]?></td>
    <td style="padding-right:5px;text-align:right"><?=number_format($_POST["txtDistribuicaoFuturaNovoSocio" . $i],0,"",".")?> </td>
    <td style="padding-right:20px">quota<?php if ($_POST["txtDistribuicaoFuturaNovoSocio" . $i] != "1") {?>s<?php } ?></td>
    <td>R$</td>
    <td align="right"><?=number_format(str_replace(",",".",str_replace(".","",$_POST["txtDistribuicaoFuturaNovoSocio" . $i])) * $valorCotas,2,",",".")?></td>
  </tr>
<?php 
				$totalquotas = $totalquotas + $_POST["txtDistribuicaoFuturaNovoSocio" . $i];
				$totalEmReais = $totalEmReais + (str_replace(",",".",str_replace(".","",$_POST["txtDistribuicaoFuturaNovoSocio" . $i])) * $valorCotas);
			}
		}
?>
  <tr>
    <td style="padding-right:20px">Total</td>
    <td style="padding-right:5px;text-align:right"><?=number_format($totalquotas,0,"",".")?> </td>
    <td style="padding-right:20px">quota<?php if ($_POST["txtDistribuicaoAtualSocio" . $campo] != "1") {?>s<?php } ?></td>
    <td>R$</td>
    <td align="right"><?=number_format($totalEmReais,2,",",".")?></td>
  </tr>
</table><br><?php 

	} else { 
	
?>, sendo distribuídas entre os sócios na mesma proporção do capital atual, conforme segue:<br /><br />
<table style="color:#000; font-family:arial, sans-serif; font-size:14px" border="0" cellspacing="0" cellpadding="0">
<?php
		$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
		$resultado = mysql_query($sql) or die (mysql_error());
		$totalSocio = mysql_num_rows($resultado);
		$numeroSocio = "0";
		$campo = 0;

		while ($linha=mysql_fetch_array($resultado)) {
			$campo = $campo + 1;
	
			$porcentagemQuotasAtualSocio 	= ((number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,".","")) / number_format($_POST["totalCotasAtual"],0,".",""));
			$quotasSocioNova 							= $_POST['totalCotasFuturo'] * $porcentagemQuotasAtualSocio;// * number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,".","");
			$valorQuotasSocioNova 				= $_POST['totalCotasFuturo'] * $porcentagemQuotasAtualSocio * $valorCotas;// * number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,".","") * $valorCotas;
?>
  <tr>
    <td style="padding-right:20px"><?=$linha["nome"]?></td>
    <td style="padding-right:5px;text-align:right"><?=number_format($quotasSocioNova,0,"",".");//number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,"",".")?> </td>
    <td style="padding-right:20px">quota<?php if ($_POST["txtDistribuicaoAtualSocio" . $campo] != "1") {?>s<?php } ?></td>
    <td>R$</td>
    <td align="right"><?=number_format($valorQuotasSocioNova,2,",",".");//number_format(str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoAtualSocio' . $campo])) * $valorCotas,2,",",".")?></td>
  </tr>
<?php 
			$totalquotas = $totalquotas + $quotasSocioNova;//$_POST["txtDistribuicaoAtualSocio" . $campo];
			$totalEmReais = $totalEmReais + $valorQuotasSocioNova;//(str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoAtualSocio' . $campo])) * $valorCotas);
		} 
?>
  <tr>
    <td style="padding-right:20px">Total</td>
    <td style="padding-right:5px;text-align:right"><?=number_format($totalquotas,0,"",".")?> </td>
    <td style="padding-right:20px">quota<?php if ($_POST["txtDistribuicaoAtualSocio" . $campo] != "1") {?>s<?php } ?></td>
    <td>R$</td>
    <td align="right"><?=number_format($totalEmReais,2,",",".")?></td>
  </tr>
</table><br>
<?php
	
?>
<br>
Parágrafo único - As novas quotas subscritas são integralizadas, neste ato, em moeda corrente, pelos subscritores.<br>
<br><br><br>
<?php
}


}


?>
Em virtude das alterações havidas, fica o presente contrato social vigorando com as cláusulas e condições seguintes, totalmente consolidadas neste presente instrumento de alteração contratual.<br>
<br><br>
<div style="text-align:center; font-weight:bold; font-size:15px">CONTRATO SOCIAL<br>
    CONSOLIDAÇÃO</div>
<br>
<?php 
//Inicio da consolidação
//Dados da empresa
$numClau = 1;
clausula($numClau);
$numClau = $numClau + 1;

$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_empresaSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);
?><br>
Por este instrumento fica constituída uma  <?=$linha["inscrita_como"]?> que gira sob a razão social de  <strong>&quot;<?php if (isset($_POST['cheMudancaRazao'])) { echo convertem($_POST["txtRazaoSocial"], 1); } else { echo convertem($linha["razao_social"], 1); } ?>&quot;,</strong> com sede à <?php if (isset($_POST["cheMudancaEndereco"])) {?> <?=$_POST["txtEndereco"]?> <?php if ($_POST["txtBairro"] != "") { echo " " . $_POST["txtBairro"]; } ?> – <?=$_POST["txtCidade"]?> – <?=$_POST["selEstado"]?> – CEP <?=$_POST["txtCEP"]?><?php } else { ?><?=$linha["endereco"]?> <?php if ($linha[""] != "") { echo " " . $linha[""]; } ?> – <?=$linha["cidade"]?> – <?=$linha["estado"]?> – CEP <?=$linha["cep"]?><?php } ?>.<br>
<br>
<?php 
//Data de início das atividades
clausula($numClau);
$numClau = $numClau + 1; ?><br>
A sociedade iniciou suas atividades em <?php echo $datas->desconverterData($linha["data_de_criacao"]);?> e o prazo de duração é indeterminado.<br>
<br>
<?php 
//Atividades CNAE
clausula($numClau);
$numClau = $numClau + 1; 

$sql = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idCodigo ASC";
$resultado = mysql_query($sql)
or die (mysql_error());

$campo = 1;
$totalLinhas = mysql_num_rows($resultado);
if (isset($_POST["cheExcluirAtividade"])) {
$totalLinhas = $totalLinhas - $_POST['hidTotalCnaeExcluido'];
}
if (isset($_POST['cheIncluirAtividade'])) {
$totalLinhas = $totalLinhas + $_POST["countCNAEIncluso"] - 1;
}

$linhaAdicionada = 1;
?><br>
<?php 
//if ($totalLinhas == '1') { echo 'O objeto social é:'; } else { echo 'Os objetos sociais são:'; } 
?>
O objeto social é:
<?php
while ($linha=mysql_fetch_array($resultado)) {
	$sql2 = "SELECT denominacao FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='" . str_replace(array("/","-","."),"",$linha["cnae"]) . "' LIMIT 0, 1";
	$resultado2 = mysql_query($sql2)
	or die (mysql_error());
	$linha2=mysql_fetch_array($resultado2);

	if (!isset($_POST['cheCnaeExcluso' . $campo])) {
?><?=$linha2["denominacao"]?> (CNAE: <?=$linha["cnae"]?>)<?php 
		if (($totalLinhas - 1) == $linhaAdicionada) {
			echo ' e '; 
		} else if($totalLinhas != $linhaAdicionada) { 
			echo ', ';
		}
		
?>
<?php 
		$linhaAdicionada = $linhaAdicionada + 1;
	}
$campo = $campo + 1; 
} 

if (isset($_POST['cheIncluirAtividade'])) {
$count = $_POST["countCNAEIncluso"] - 1;
$campo = 1;

	for($i=1;$i<=$count;$i++) {
		$CodigoCNAE = $_POST["txtCodigoCNAE".$campo];
		$ConsultaCNAE = str_replace(array("/","-","."),"",$CodigoCNAE);
	
		$sql = "SELECT * FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='$ConsultaCNAE' LIMIT 0,1";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		$linha=mysql_fetch_array($resultado);
?> <?=$linha["denominacao"]?> (CNAE: <?=$linha["cnae"]?>)<?php 
		if (($totalLinhas - 1) == $linhaAdicionada) {
				echo ' e'; 
			} else if($totalLinhas != $linhaAdicionada) { 
				echo ',';
			} 
			$campo = $campo + 1; 
			$linhaAdicionada = $linhaAdicionada + 1;
		} 
}
?>.<br>
<br>
<?php
//Distribuição do capital social
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
O capital social da empresa é de 
<?php if(isset($_POST['cheAlterarCapitalSocial'])) { ?> R$ <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo'])) * $_POST['totalCotasFuturo'],2,",",".")?> (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo']))  * $_POST['totalCotasFuturo'],2,"",""))?>) representados por <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalCotasFuturo'])),0,",",".")?> (<?=GExtenso::numero($_POST['totalCotasFuturo'], GExtenso::GENERO_FEM)?>) quota<?php if($_POST['totalCotasFuturo'] != "1"){ ?>s<?php } ?> no valor de R$ <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo'])),2,",",".")?>

<?php } else { ?> R$ <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])) * $_POST['totalCotasAtual'],2,",",".")?> (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual']))  * $_POST['totalCotasAtual'],2,"",""))?>), representados por <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalCotasAtual'])),0,",",".")?> (<?=GExtenso::numero($_POST['totalCotasAtual'], GExtenso::GENERO_FEM)?>) quota<?php if($_POST['totalCotasAtual'] != "1"){ ?>s<?php } ?> no valor de R$ <?=number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,",",".")?> (<?=GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual'])),2,"",""))?>)

<?php } ?> cada uma, integralizadas em moeda corrente do país, assim distribuídas entre os sócios:<br>
<br>
<?php 
$totalcotas = 0;
$totalEmReais = 0;
if(isset($_POST['cheAlterarCapitalSocial'])) {
	$valorCotas = str_replace(",",".",str_replace(".","",$_POST['totalReaisFuturo']));
} else {
	$valorCotas = str_replace(",",".",str_replace(".","",$_POST['totalReaisAtual']));
}

if ((isset($_POST['cheAlterarCotas'])) or (isset($_POST['cheExcluirSocio'])) or (isset($_POST['cheIncluirSocio']))){ ?>
<table style="color:#000; font-family:arial, sans-serif; font-size:14px" border="0" cellspacing="0" cellpadding="0">
<?php
$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
$resultado = mysql_query($sql)
or die (mysql_error());
$totalSocio = mysql_num_rows($resultado);
$numeroSocio = "0";
$campo = 0;
$totalquotas = 0;
$totalEmReais = 0;
while ($linha=mysql_fetch_array($resultado)) {
	$campo = $campo + 1;
	if ((isset($_POST["cheExcluirSocio"]) and !isset($_POST["cheSocioExcluso" . $campo])) or (!isset($_POST["cheExcluirSocio"]))) {
?>
  <tr>
    <td style="padding-right:20px"><?=$linha["nome"]?>&nbsp;&nbsp;</td>
    <td style="padding-right:5px;text-align:right"><?=number_format($_POST["txtDistribuicaoFuturaSocio" . $campo],0,"",".")?> </td>
    <td style="padding-right:20px">&nbsp;&nbsp;&nbsp;quota<?php if ($_POST["txtDistribuicaoFuturaSocio" . $campo] != "1") {?>s<?php }; ?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;R$&nbsp;</td>
    <td align="right"><?=number_format(str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoFuturaSocio' . $campo])) * $valorCotas,2,",",".")?></td>
  </tr>
<?php 
	$totalquotas = $totalquotas + $_POST["txtDistribuicaoFuturaSocio" . $campo];
	$totalEmReais = $totalEmReais + (str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoFuturaSocio' . $campo])) * $valorCotas);
	}
} 
if(isset($_POST["cheIncluirSocio"])){
$count = $_POST["skill_count"];
for($i=1;$i<=$count;$i++) {
?>
  <tr>
    <td style="padding-right:20px"><?=$_POST["txtNome" . $i]?>&nbsp;&nbsp;</td>
    <td style="padding-right:5px;text-align:right"><?=number_format($_POST["txtDistribuicaoFuturaNovoSocio" . $i],0,"",".")?> </td>
    <td style="padding-right:20px">&nbsp;&nbsp;&nbsp;quota<?php if ($_POST["txtDistribuicaoFuturaNovoSocio" . $i] != "1") {?>s<?php } ?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;R$&nbsp;</td>
    <td align="right"><?=number_format(str_replace(",",".",str_replace(".","",$_POST["txtDistribuicaoFuturaNovoSocio" . $i])) * $valorCotas,2,",",".")?></td>
  </tr>
<?php 
$totalquotas = $totalquotas + $_POST["txtDistribuicaoFuturaNovoSocio" . $i];
$totalEmReais = $totalEmReais + (str_replace(",",".",str_replace(".","",$_POST["txtDistribuicaoFuturaNovoSocio" . $i])) * $valorCotas);
} } ?>
  <tr>
    <td style="padding-right:20px">Total</td>
    <td style="padding-right:5px;text-align:right"><?=number_format($totalquotas,0,"",".")?> </td>
    <td style="padding-right:20px">&nbsp;&nbsp;&nbsp;quota<?php if ($_POST["txtDistribuicaoAtualSocio" . $campo] != "1") {?>s<?php } ?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;R$&nbsp;</td>
    <td align="right"><?=number_format($totalEmReais,2,",",".")?></td>
  </tr>
</table>
<?php } else { 
?>
<table style="color:#000; font-family:arial, sans-serif; font-size:14px" border="0" cellspacing="0" cellpadding="0">
<?php
$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
$resultado = mysql_query($sql)
or die (mysql_error());
$totalSocio = mysql_num_rows($resultado);
$numeroSocio = "0";
$campo = 0;
while ($linha=mysql_fetch_array($resultado)) {
	$campo = $campo + 1;
?>
  <tr>
    <td style="padding-right:20px"><?=$linha["nome"]?>&nbsp;&nbsp;</td>
    <td style="padding-right:5px;text-align:right"><?=number_format($_POST["txtDistribuicaoAtualSocio" . $campo],0,"",".")?> </td>
    <td style="padding-right:20px">&nbsp;&nbsp;quota<?php if ($_POST["txtDistribuicaoAtualSocio" . $campo] != "1") {?>s<?php } ?></td>
    <td>&nbsp;&nbsp;R$&nbsp;&nbsp;</td>
    <td align="right"><?=number_format(str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoAtualSocio' . $campo])) * $valorCotas,2,",",".")?></td>
  </tr>
<?php 
$totalquotas = $totalquotas + $_POST["txtDistribuicaoAtualSocio" . $campo];
$totalEmReais = $totalEmReais + (str_replace(",",".",str_replace(".","",$_POST['txtDistribuicaoAtualSocio' . $campo])) * $valorCotas);
} 
?>
  <tr>
    <td style="padding-right:20px">Total</td>
    <td style="padding-right:5px;text-align:right"><?=number_format($totalquotas,0,"",".")?> </td>
    <td style="padding-right:20px">&nbsp;&nbsp;quota<?php if ($_POST["txtDistribuicaoAtualSocio" . $campo] != "1") {?>s<?php } ?></td>
    <td>&nbsp;&nbsp;R$&nbsp;&nbsp;</td>
    <td align="right"><?=number_format($totalEmReais,2,",",".")?></td>
  </tr>
</table>
<?php } ?><br>
<?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
A responsabilidade de cada sócio é restrita ao valor de suas quotas, mas todos respondem solidariamente pela integralização do capital social.<br>
<br>
<?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
As quotas são indivisíveis e não podem ser cedidas ou transferidas a terceiros sem o consentimento do outro sócio, a quem fica assegurado, em igualdade de condições e preço, direito de preferência para a sua aquisição, se postas à venda, formalizando, se realizada a cessão delas, a alteração contratual pertinente.<br>
<br>
<?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
<?php
if($_POST['hidTotalSocioFinal'] == $_POST['hidTotalAdministracao']){
?>
A administração da sociedade será de todos os sócios, em conjunto ou separadamente, com os poderes e atribuições de representação ativa e passiva na sociedade, judicial e extrajudicialmente, podendo praticar todos os atos compreendidos no objeto social, sempre de interesse da sociedade, sendo vedado o uso do nome empresarial em negócios estranhos aos fins sociais, nos termos do art. 1.064 da Lei n° 10.406/2002.<br>
<br>
§ 1º Fica facultada a nomeação de administradores não pertencentes ao quadro societário, desde que aprovado por dois terços dos sócios, nos termos do art. 1.061 da Lei n° 10.406/ 2002.<br>
<br>
§ 2º No exercício da administração, os administradores terão direitos a uma retirada mensal, a título de pró-labore, cujo valor será definido de comum acordo entre os sócios.
<?php } else { ?>
A administração da sociedade <?php if ($_POST['hidTotalAdministracao'] == 1) { ?> caberá <?php } else { ?> caberão <?php } ?> a <strong><?php
	$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	$totalSocio = mysql_num_rows($resultado);
	$numeroSocio = 0;
	$linhaAdicionada = 1;
	while ($linha=mysql_fetch_array($resultado)) {
		$numeroSocio = $numeroSocio + 1;
		if (isset($_POST['cheAdministracaoSocio' . $numeroSocio])) {
			if (isset($_POST['cheExcluirSocio'])) {
				if(!isset($_POST['cheSocioExcluso' . $numeroSocio])) {
					echo convertem($linha["nome"], 1);
					if($linhaAdicionada == $_POST['hidTotalAdministracao'] - 1) {
						echo ' e '	;
					} else if($linhaAdicionada != $_POST['hidTotalAdministracao']) {
						echo ', ';
					}
					$linhaAdicionada = $linhaAdicionada + 1;
				}
			} else {
				echo convertem($linha["nome"], 1);
				if($linhaAdicionada == $_POST['hidTotalAdministracao'] - 1) {
					echo ' e '	;
				} else if($linhaAdicionada != $_POST['hidTotalAdministracao']) {
					echo ', ';
				}
				$linhaAdicionada = $linhaAdicionada + 1;
			}
		}
	}
	if (isset($_POST['cheIncluirSocio'])) {
		for($i=1;$i<=$_POST['skill_count'];$i++) {
			if(isset($_POST['cheAdministracaoNovoSocio' . $i])){
				echo convertem($_POST['txtNome' . $i], 1);
				if($linhaAdicionada == $_POST['hidTotalAdministracao'] - 1) {
					echo ' e '	;
				} else if($linhaAdicionada != $_POST['hidTotalAdministracao']) {
					echo ', ';
				}
				$linhaAdicionada = $linhaAdicionada + 1;
			}
		}
	}
?></strong> com os poderes e atribuições de representação ativa e passiva na sociedade, judicial e extrajudicialmente, podendo praticar todos os atos compreendidos no objeto social, sempre no interesse da sociedade, autorizado o uso do nome empresarial, vedado, no entanto, fazê-lo em atividades estranhas ao interesse social ou assumir obrigações seja em favor de qualquer dos quotistas ou de terceiros, bem como onerar ou alienar bens imóveis da sociedade, sem autorização do(s) outro(s) sócio(s).<br>
<br>
<strong>Parágrafo único</strong>: No exercício da administração, o administrador terá direito a uma retirada mensal, a título de pró-labore, cujo valor será definido de comum acordo entre os sócios.
<?php } ?><br>
  <br>
<?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
Os sócios podem, de comum acordo, fixar uma retirada mensal, a título de pró-labore, observadas as disposições regulamentares pertinentes.<br>
<br>
<?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
Falecendo ou interditado qualquer sócio, a sociedade continuará suas atividades com os herdeiros, sucessores e representante legal do incapaz. Não sendo possível ou inexistindo interesse destes ou do(s) sócio(s) remanescente(s), o valor de seus haveres será apurado e liquidado com base na situação patrimonial da sociedade, à data da resolução, verificada em balanço especialmente levantado. <br>
<br>
<strong>Parágrafo único:</strong> O mesmo procedimento será adotado em outros casos em que a sociedade se resolva em relação a seu sócio.<br>
<br>
<?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
Ao término da cada exercício social, em 31 de dezembro, o administrador prestará contas justificadas de sua administração, procedendo a elaboração do inventário, do balanço patrimonial e do balanço de resultado econômico, cabendo aos sócios, na proporção de suas quotas, os lucros ou perdas apurados.<br>
 <br>
 <?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
A sociedade poderá levantar balanços ou balancetes patrimoniais em períodos inferiores a um ano, e o lucro apurado nessas demonstrações intermediarias, poderão ser distribuídos mensalmente aos sócios cotistas, a título de Antecipação de Lucros, proporcionalmente às cotas de capital de cada um.
<br>
 <br>
 <?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
A sociedade pode a qualquer tempo, abrir ou fechar filial ou outra dependência, mediante alteração contratual assinada por todos os sócios. <br>
 <br>
 <?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
A sociedade dispensa a publicação de convocações de sócios bastando somente assinatura dos sócios.<br>
<br>
<?php 
clausula($numClau);
$numClau = $numClau + 1; 
?><br>
Fica eleito o foro da comarca de <?php echo $_POST["txtComarca"]?>
 – Estado <?=preposicaoEstado($_POST["selUFComarca"])?> <?=extensoEstado($_POST["selUFComarca"])?> para o exercício e o cumprimento dos direitos e obrigações resultantes deste contrato.
 <br>
</p>
<div style="text-align:center; font-weight:bold; font-size:15px">DECLARAÇÃO DE DESIMPEDIMENTO</div>
<br>
Os administradores declaram, sob as penas da lei, que não estão impedidos de exercer a administração da sociedade, por lei especial, ou em virtude de condenação criminal, ou por se encontrarem sob os efeitos dela, a pena que vede, ainda que temporariamente, o acesso a cargos públicos; ou por crime falimentar, de prevaricação, peita ou suborno, concussão, peculato, ou contra a economia popular, contra o sistema financeiro nacional, contra normas de defesa da concorrência, contra as relações de consumo, fé pública, ou a 
propriedade.
<br>
<br>

E por estarem assim justos e contatados assinam o presente instrumento em 03 vias.<br>
<br><br>
<?php echo getDadosCliente('dados_da_empresa', 'cidade', $_SESSION["id_empresaSecao"]);
?>, <?=date('d')?> de <?php 
$mes = date('m');

switch ($mes){

case 1: $mes = "Janeiro"; break;
case 2: $mes = "Fevereiro"; break;
case 3: $mes = "Março"; break;
case 4: $mes = "Abril"; break;
case 5: $mes = "Maio"; break;
case 6: $mes = "Junho"; break;
case 7: $mes = "Julho"; break;
case 8: $mes = "Agosto"; break;
case 9: $mes = "Setembro"; break;
case 10: $mes = "Outubro"; break;
case 11: $mes = "Novembro"; break;
case 12: $mes = "Dezembro"; break;

}

echo $mes; ?> de <?=date('Y')?>.<br>
<br><br>
Sócios:<br>
<table border="0" cellspacing="0" cellpadding="0" style="color:#000; font-family:arial, sans-serif; font-size:14px">
<?php 
$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $_SESSION["id_empresaSecao"] . "' ORDER BY idSocio ASC";
$resultado = mysql_query($sql)
or die (mysql_error());
$coluna = 1;
while ($linha=mysql_fetch_array($resultado)) {
	if($coluna == 1) {
?>
  <tr valign="top">
    <td width="350"><img src="images/contrato_assinatura.gif" width="350" height="106"><br>
      <strong><?=convertem($linha["nome"], 1)?></strong><br>
RG nº <?=mascaraRG($linha["rg"])?> – <?=$linha["orgao_expeditor"]?></td>
<?php $coluna = 2;
	} else {
?>
    <td width="50" style="padding:25px">&nbsp;</td>
    <td width="350">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/contrato_assinatura.gif" width="350" height="106"><br>
      <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=convertem($linha["nome"], 1)?>
      </strong><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RG nº <?=mascaraRG($linha["rg"])?> – <?=$linha["orgao_expeditor"]?></td>
  </tr>
<?php $coluna = 1;
	}
}

if (isset($_POST["cheIncluirSocio"])) {
	$count = $_POST["skill_count"];
	for($i=1;$i<=$count;$i++) {
		if($coluna == 1) {
?>
  <tr valign="top">
    <td width="350"><img src="images/contrato_assinatura.gif" width="350" height="106"><br>
      <strong><?=convertem($_POST['txtNome' . $i], 1)?></strong><br>
RG nº <?=mascaraRG($_POST['txtRG' . $i])?> – <?=$_POST['txtOrgaoExpedidor' . $i]?></td>
<?php $coluna = 2;
		} else {
?>
    <td width="50" style="padding:25px">&nbsp;</td>
    <td width="350"><img src="images/contrato_assinatura.gif" width="350" height="106"><br>
      <strong>
      <?=convertem($_POST['txtNome' . $i], 1)?>
      </strong><br>
RG nº <?=mascaraRG($_POST['txtRG' . $i])?> – <?=$_POST['txtOrgaoExpedidor' . $i]?></td>
  </tr>
<?php $coluna = 1;
		}
	}
}
if($coluna == 1) {
	echo "</tr>";
}
?>
</table>
<br>
<br>
Testemunhas:<br>
<table border="0" cellspacing="0" cellpadding="0" style="color:#000; font-family:arial, sans-serif; font-size:14px">
  <tr valign="top">
    <td width="350"><img src="images/contrato_assinatura.gif" width="350" height="106"><br>
      <strong><?=convertem($_POST['txtNomeTest1'], 1)?></strong><br>
RG nº <?=mascaraRG($_POST['txtRGTest1'])?> – <?=$_POST['txtOrgaoExpedidorTest1']?></td>
    <td width="50" style="padding:25px">&nbsp;</td>
    <td width="350">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/contrato_assinatura.gif" width="350" height="106"><br>
      <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=convertem($_POST['txtNomeTest2'], 1)?></strong><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RG nº – <?=mascaraRG($_POST['txtRGTest2'])?> – <?=$_POST['txtOrgaoExpedidorTest2']?></td>
  </tr>
</table>
<div style="clear:both"> </div>

</div></center>
<?
/*
O sócio FULANO DE TAL, não desejando mais permanecer na sociedade, cede e transfere a totalidade de suas quotas ao sócio remanescente. Por este ato também, o sócio que se retira dá a mais ampla e rasa quitação de seus direitos, nada mais tendo a reclamar em tempo algum quanto a seus direitos na sociedade.


CLÁUSULA 2ª - DA ALTERAÇÃO DO QUADRO SOCIETÁRIO

 Em razão da alteração havida, o capital social, que permanece inalterado no valor de R$ 0.000,00 (  reais ) representando por  ( ___ ) quotas de valor unitário R$ 1,00, passa a ser dividido entre os sócios na seguinte proporção:

-  BELTRANO DE TAL - nº de quotas  - R$ 
- TOTAL - nº de quotas  - R$ 


PARÁGRAFO ÚNICO: Nos termos do artigo 1033, IV, da Lei 10.406/02, a sociedade permanecerá unipessoal, devendo recompor seu quadro societário no prazo máximo de 180 ( cento e oitenta ) dias, sob pena de dissolução.
*/
?>