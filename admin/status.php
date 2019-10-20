<?php
include "../conect.php";
$ID = $_POST["hidID"];
$SocioID = $_POST["hidSocioID"];
$Nome = $_POST["txtNome"];
$CPF = $_POST["txtCPF"];
$RG = $_POST["txtRG"];
$DataEmissao = $_POST["txtDataEmissao"];
$OrgaoExpedidor = $_POST["txtOrgaoExpedidor"];
$DataNascimento = $_POST["txtDataNascimento"];
$CEP = $_POST["txtCEP"];
$Cidade = $_POST["txtCidade"];
$Estado = $_POST["selEstado"];
$PrefixoTelefone = $_POST["txtPrefixoTelefone"];
$Telefone = $_POST["txtTelefone"];
$CodigoCBO = $_POST["txtCodigoCBO"];
$ProLabore = str_replace(",",".",str_replace(".","",$_POST["txtProLabore"]));
$Nit = $_POST["txtNit"];
//Atualizar dados em dados da empresa.
$sql = "UPDATE dados_do_responsavel SET nome='$Nome', cpf='$CPF', rg='$RG', data_de_emissao='$DataEmissao', orgao_expeditor='$OrgaoExpedidor', data_de_nascimento='$DataNascimento', cep='$CEP', cidade='$txtCidade', estado='$Estado', pref_telefone='$PrefixoTelefone', telefone='$Telefone', codigo_cbo='$CodigoCBO', pro_labore='$ProLabore', nit='$Nit' WHERE idSocio='$SocioID'";
$resultado = mysql_query($sql)
or die (mysql_error());
header('Location: cliente_administrar.php?id=' . $ID );
?>