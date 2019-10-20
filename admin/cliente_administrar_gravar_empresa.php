<?php
include "../conect.php";
//Editar Dados da Empresa
$ID = $_POST["hidID"];
$RazaoSocial = $_POST["txtRazaoSocial"];
$NomeFantasia = $_POST["txtNomeFantasia"];
$CNPJ = $_POST["txtCNPJ"];
$InscricaoCCM = $_POST["txtInscricaoCCM"];
$InscricaoEstadual = $_POST["txtInscricaoEstadual"];
$Endereco = $_POST["txtEndereco"];
$Bairro = $_POST["txtBairro"];
$CEP = $_POST["txtCEP"];
$Cidade = $_POST["txtCidade"];
$Estado = $_POST["selEstado"];
$PrefixoTelefone = $_POST["txtPrefixoTelefone"];
$Telefone = $_POST["txtTelefone"];
$RamoAtividade = $_POST["selRamoAtividade"];
$RegimeTributacao = $_POST["selRegimeTributacao"];
$InscritaComo = $_POST["selInscritaComo"];
$RegistroNire = $_POST["txtRegistroNire"];
$NumCartorio = $_POST["txtNumCartorio"];
$RegistroCartorio = $_POST["txtRegistroCartorio"];
$DataCriacao = $_POST["txtDataCriacao"];


//Apaga Códigos já existentes
$sql = "DELETE FROM dados_da_empresa_codigos WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());

//Insere os códigos novos
$CodigoCNAE = $_POST["txtCNAE_Principal"];

if ($CodigoCNAE != "") {
	$ConsultaCNAE = str_replace(array("/","-","."),"",$CodigoCNAE);

	$sql = "SELECT * FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='$ConsultaCNAE' LIMIT 0,1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	$linha=mysql_fetch_array($resultado);

	$sql = "INSERT INTO dados_da_empresa_codigos (id, cnae, tipo) VALUES ('$ID', '" . $linha["cnae"] . "', '1')";
	$resultado = mysql_query($sql)
	or die (mysql_error());
}

$count = $_POST["skill_count"] - 1;
$campo = 1;

for($i=0;$i<=$count;$i++) {
	$CodigoCNAE = $_POST["txtCodigoCNAE".$campo];
	$ConsultaCNAE = str_replace(array("/","-","."),"",$CodigoCNAE);
	
	
	if ($CodigoCNAE!=""){
		//Consultar CNAE com grafia correta
		$sql = "SELECT * FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='$ConsultaCNAE' LIMIT 0,1";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		$linha=mysql_fetch_array($resultado);
		
		$sql = "INSERT INTO dados_da_empresa_codigos (id, cnae, tipo) VALUES ('$ID', '" . $linha["cnae"] . "', '2')";
		$resultado = mysql_query($sql)
		or die (mysql_error());
	}
	$campo = $campo + 1;
}

//Apaga códigos da prefeitura já existentes
/*$sql = "DELETE FROM dados_da_empresa_codigo_prefeitura WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());

//Insere os códigos da prefeitura novos
$countPrefeitura = $_POST["countPrefeitura"] - 1;

for($i=0;$i<=$countPrefeitura;$i++) {
	if ($_POST["txtCodigoAtividadePrefeitura".$i]!=""){
		$sql = "SELECT * FROM codigos_prefeitura WHERE codigo_prefeitura='" . $_POST["txtCodigoAtividadePrefeitura".$i] . "' LIMIT 0,1";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		$linha=mysql_fetch_array($resultado);
		
		$sql = "INSERT INTO dados_da_empresa_codigo_prefeitura (id, codigo_prefeitura) VALUES ('$ID', '" . $linha["codigo_prefeitura"] . "')";
		$resultado = mysql_query($sql)
		or die (mysql_error());
	}
}*/

//Atualizar dados em dados da empresa.
$sql = "UPDATE dados_da_empresa SET razao_social='$RazaoSocial', nome_fantasia='$NomeFantasia', cnpj='$CNPJ', inscricao_no_ccm='$InscricaoCCM', inscricao_estadual='$InscricaoEstadual', endereco='$Endereco', bairro='$Bairro', cep='$CEP', cidade='$txtCidade', estado='$Estado', pref_telefone='$PrefixoTelefone', telefone='$Telefone', ramo_de_atividade='$RamoAtividade', codigo_de_atividade_prefeitura='$CodigoAtividadePrefeitura', regime_de_tributacao='$RegimeTributacao', inscrita_como='$InscritaComo', registro_nire='$RegistroNire', data_de_criacao='$DataCriacao' WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());

if($InscritaComo == 'Sociedade Simples'){
	$sql = "UPDATE dados_da_empresa SET registro_nire='', numero_cartorio='$NumCartorio', registro_cartorio='$RegistroCartorio' WHERE id='$ID'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
} else {
	$sql = "UPDATE dados_da_empresa SET registro_nire='$RegistroNire', numero_cartorio='', registro_cartorio='' WHERE id='$ID'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
}

//Atualizar nome no login.
$sql = "UPDATE login SET nome='$RazaoSocial' WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());


echo "<script>opener.location.reload();window.self.close(); </script>";

//header('Location: cliente_administrar.php?id=' . $ID );
?>