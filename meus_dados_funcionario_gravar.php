<?php

 ini_set('display_errors',1);
 ini_set('display_startup_erros',1);
 error_reporting(E_ALL);

include "conect.php";
include "session.php";


$acao 					= $_POST["acao"];
$status 				= $_POST["hidStatus"];

if($status == '0' || $status == ''){
	$novoStatus = '1';	
}else{
	$novoStatus = $status;	
}

$ID = $_SESSION["id_empresaSecao"];

$idFuncionario = $_POST['hidFuncionarioID'];

$_SESSION['idFuncionario'] = $idFuncionario;

$nome = mysql_real_escape_string($_POST['txtNome']);
$sexo = $_POST['radSexo'];

$dataAdmissao = $_POST['txtDataAdmissao'];
$dataAdmissao = str_replace('/', '-', $dataAdmissao);
$dataAdmissao = (!empty($dataAdmissao) ? date('Y-m-d', strtotime($dataAdmissao)) : 'NULL');

$dataDemissao = $_POST['txtDataDemissao'];
$dataDemissao = str_replace('/', '-', $dataDemissao);
$dataDemissao = (!empty($dataDemissao) ? "'".date('Y-m-d', strtotime($dataDemissao))."'" : 'NULL');

$nacionalidade = $_POST['txtNacionalidade'];
$naturalidade = $_POST['txtNaturalidade'];
$estadoCivil = $_POST['selEstadoCivil'];
$codigo_cbo = mysql_real_escape_string($_POST['txtCodigoCBO']);
$cpf = $_POST['txtCPF'];
$rg = $_POST['txtRG'];
$ctps = $_POST['txtCarteiraTrabalho'];
$serie_ctps = $_POST['txtSerieCarteiraTrabalho'];
$uf_ctps = $_POST['selEstadoCarteiraTrabalho'];
$uf_ctpsAux = explode(';', $uf_ctps);
$uf_ctps = $uf_ctpsAux[1];

$dataEmissao = $_POST['txtDataEmissao'];
$orgaoExpedidor = mysql_real_escape_string($_POST['txtOrgaoExpedidor']);
$dataNascimento = $_POST['txtDataNascimento'];
$endereco = mysql_real_escape_string($_POST['txtEndereco']);
$bairro = mysql_real_escape_string($_POST['txtBairro']);
$cep = $_POST['txtCEP'];

$cidade = mysql_real_escape_string($_POST['txtCidade']);
// alterado em 27/11/2014 - adequando todos os cadastros para combo de cidades
$arrEstado = explode(';',$_POST['selEstado']);
$estado = $arrEstado[1];

$prefTelefone = $_POST['txtPrefixoTelefone'];
$telefone = $_POST['txtTelefone'];
$funcao = mysql_real_escape_string($_POST['txtFuncao']);
$pis = $_POST['txtPIS'];
$salario = $_POST['txtSalario'];
$salario = str_replace(".","",$salario);
$salario = str_replace(",",".",$salario);

$pensao = $_POST['pensao'];
$percPensao = str_replace(",",".",$_POST['PercentPensao']);
$valorPensao = str_replace(",",".",str_replace(".","",$_POST['valorPensao']));


$valeRefeicao = (!empty($_POST['valeRefeicao']) ? $_POST['valeRefeicao'] : 0);
$valeRefeicaoPorc = (!empty($_POST['valeRefeicaoPorc']) ? str_replace(",",".",$_POST['valeRefeicaoPorc']) : 0);

$nomeBeneficiaria = mysql_real_escape_string($_POST['txtNomeBeneficiaria']);
$cpfBeneficiaria = $_POST['txtCPFBeneficiaria'];
$rgBeneficiaria = $_POST['txtRGBeneficiaria'];
$dataEmissaoBeneficiaria = $_POST['txtDataEmissaoBeneficiaria'];
$orgaoExpedidorBeneficiaria = mysql_real_escape_string($_POST['txtOrgaoExpedidorBeneficiaria']);

$jornadaTrabalhoDiaria = $_POST['jornadaTrabalho'];
$inicioJornada = $_POST['inicioJornada'];
$fimJornada = $_POST['fimJornada'];
$inicioIntervalo = $_POST['inicioIntervalo'];
$fimIntervalo = $_POST['fimIntervalo'];

$trabalhaSabado = $_POST['trabalhaSabado'];
$inicioJornadaSabado = $_POST['inicioJornadaSabado'];
$fimJornadaSabado = $_POST['fimJornadaSabado'];

$valeTransporte = $_POST['vt'];

$tipoConta				= $_POST["tipo_conta"] != "" ? "'".$_POST["tipo_conta"]."'" : "null";
$idBanco				= $_POST["id_banco"] != "" ? "".$_POST["id_banco"]."" : "0";
$numAgencia				= $_POST["agencia"] != "" ? "'".$_POST["agencia"]."'" : "null";
$digAgencia				= $_POST["dig_agencia"] != "" ? "'".$_POST["dig_agencia"]."'" : "null";
$numConta				= $_POST["conta"] != "" ? "'".$_POST["conta"]."'" : "null";
$digConta				= $_POST["dig_conta"] != "" ? "'".$_POST["dig_conta"]."'" : "null";
	
	
//Formatar CPF
$CPFFormatado = $cpf;//substr($CPF,0,3) . "." . substr($CPF,3,3) . "." . substr($CPF,6,3) . "-" . substr($CPF,9,2);

//Formatar RG
$RGFormatado = $rg;//number_format($RG / 10,1,"-",".");

//Formatar CEP
//$CEP = str_replace(array("/","-","."),"",$CEP);
$CEPFormatado = $cep;//substr($CEP,0,5) . "-" . substr($CEP,5,3);

//Formatar pis
$pis = str_replace(array("/","-","."),"",$pis);
$pisFormatado = $pis;//substr($Nit,0,1) . "." . substr($Nit,1,3) . "." . substr($Nit,4,3) . "." . substr($Nit,7,3) . "-" . substr($Nit,10,1);


mysql_query("DELETE FROM dados_transporte_funcionario WHERE idFuncionario = " . $idFuncionario . " AND trajeto = 'ida'");

for($i = 0; $i < 3; $i++){
	if(strlen($_POST['txtLinhaIda'][$i]) > 0 || strlen($_POST['txtEmpresaIda'][$i]) > 0 || strlen($_POST['txtTarifaIda'][$i]) > 0){
		mysql_query("INSERT INTO dados_transporte_funcionario SET 
						idFuncionario = " . $idFuncionario . "
						, trajeto='ida'
						, tipo='".$_POST['selTipoIda'][$i]."'
						, linha='".$_POST['txtLinhaIda'][$i]."'
						, empresa='".$_POST['txtEmpresaIda'][$i]."'
						, tarifa=".str_replace(",",".",($_POST['txtTarifaIda'][$i] > 0 ? $_POST['txtTarifaIda'][$i] : '0.00'))."");
	}
}


mysql_query("DELETE FROM dados_transporte_funcionario WHERE idFuncionario = " . $idFuncionario . " AND trajeto = 'volta'");

for($i = 0; $i < 3; $i++){
	if(strlen($_POST['txtLinhaVolta'][$i]) > 0 || strlen($_POST['txtEmpresaVolta'][$i]) > 0 || strlen($_POST['txtTarifaVolta'][$i]) > 0){
		mysql_query("INSERT INTO dados_transporte_funcionario SET 
						idFuncionario = " . $idFuncionario . "
						, trajeto='volta'
						, tipo='".$_POST['selTipoVolta'][$i]."'
						, linha='".$_POST['txtLinhaVolta'][$i]."'
						, empresa='".$_POST['txtEmpresaVolta'][$i]."'
						, tarifa=".str_replace(",",".",($_POST['txtTarifaVolta'][$i] > 0 ? $_POST['txtTarifaVolta'][$i] : '0.00'))."");
	}
}

	//Atualizar dados em dados da empresa.
	$sql = "UPDATE dados_do_funcionario SET 
			nome='" . $nome  . "'
			, sexo='" . $sexo  . "'
			, data_admissao='" . $dataAdmissao  . "'
			, nacionalidade='" . $nacionalidade  . "'
			, naturalidade='" . $naturalidade  . "'
			, estado_civil='" . $estadoCivil  . "'
			, codigo_cbo='" . $codigo_cbo  . "'
			, cpf='" . $cpf  . "'
			, rg='" . $rg  . "'
			, ctps='" . $ctps  . "'
			, serie_ctps='" . $serie_ctps  . "'
			, uf_ctps='" . $uf_ctps  . "'
			, data_de_emissao='" . $dataEmissao  . "'
			, orgao_expeditor='" . $orgaoExpedidor  . "'
			, data_de_nascimento='" . $dataNascimento  . "'
			, endereco='" . $endereco  . "'
			, bairro='" . $bairro  . "'
			, cep='" . $cep  . "'
			, cidade='" . $cidade  . "'
			, estado='" . $estado  . "'
			, pref_telefone='" . $prefTelefone  . "'
			, telefone='" . $telefone  . "'
			, funcao='" . $funcao  . "'
			, pis='" . $pis  . "'
			, dependentes='" . $dependentes  . "'
			, vale_transporte='" . $valeTransporte . "'
			, pensao='" . $pensao  . "'
			, perc_pensao='" . $percPensao  . "'
			, valor_pensao='" . $valorPensao . "'
			, tipo_conta=" . $tipoConta  . "
			, id_banco=" . $idBanco  . "
			, agencia=" . $numAgencia  . "
			, dig_agencia=" . $digAgencia  . "
			, conta=" . $numConta  . "
			, dig_conta=" . $digConta  . "
			, status=" . $novoStatus . "
			, valor_salario='".$salario."'
			, vale_refeicao='".$valeRefeicao."'
			, vale_refeicao_porc='".$valeRefeicaoPorc."'
			, jornada_trabalho_diaria='".$jornadaTrabalhoDiaria."'
			, inicio_jornada='".$inicioJornada."'
			, fim_jornada='".$fimJornada."'
			, inicio_intervalo='".$inicioIntervalo."'
			, fim_intervalo='".$fimIntervalo."'
			, trabalhoSabado='".$trabalhaSabado."'
			, inicio_horario_Sabado='".$inicioJornadaSabado."'
			, fim_horario_Sabado='".$fimJornadaSabado."'
			, data_demissao = ".$dataDemissao."
		WHERE 
			id = " . $ID . "
			AND idFuncionario = " . $idFuncionario . "";
	
//echo $sql;
//exit;

$resultado = mysql_query($sql)
or die (mysql_error());

if($pensao == '1'){
	mysql_query("DELETE FROM dados_pensao_funcionario WHERE idFuncionario = " . $idFuncionario . "");
	$sql_pensao = "INSERT INTO dados_pensao_funcionario SET
						idFuncionario='" . $idFuncionario . "'
						, nome='" . $nomeBeneficiaria  . "'
						, cpf='" . $cpfBeneficiaria  . "'
						, rg='" . $rgBeneficiaria  . "'
						, data_emissao='" . $dataEmissaoBeneficiaria  . "'
						, orgao_expeditor='" . $orgaoExpedidorBeneficiaria  . "'";
//	echo $sql_pensao;
//	exit;
	mysql_query($sql_pensao);
}

$proximo_passo = "meus_dados_funcionario.php";

//if($valeTransporte == '1'){
//	$proximo_passo = "meus_dados_transporte_funcionario.php";
//}

//if($dependentes == '1'){
//	$proximo_passo = "meus_dados_dependentes_funcionario.php";
//}

if($status == '1'){
	$proximo_passo = "meus_dados_funcionario.php";
}

header('Location: ' . $proximo_passo );
?>