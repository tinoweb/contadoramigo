<?php
include "conect.php";

$ID = $_POST["hidID"];
$Assinante = $_POST["txtAssinante"];
$Email = $_POST["txtEmail"];
$NovaSenha = $_POST["passNovaSenha"];
$ConfirmaSenha = $_POST["passConfirmaSenha"];
$PrefixoTelefone = $_POST["txtPrefixoTelefone"];
$Telefone = $_POST["txtTelefone"];
$Endereco = $_POST["txtEndereco"];
$Numero = $_POST["txtNumero"];
$Bairro = $_POST["txtBairro"];
$Cidade = $_POST["txtCidade"];
$CEP = $_POST["txtCEP"];
$Estado = $_POST["selEstado"];
$plano = $_POST['radPlano'];

//Atualizar dados em dados de cobrança.
$sql = "UPDATE dados_cobranca SET assinante='$Assinante', pref_telefone='$PrefixoTelefone', telefone='$Telefone', plano='$plano' WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());

//Atualizar e-mail em login.
$sql = "UPDATE login SET assinante='$Assinante', email='$Email' WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());

session_start();
$_SESSION["email_userSecao"]=$Email;
$AssinanteExplode = explode(" ", $Assinante);
$_SESSION["nome_assinanteSecao"] = $AssinanteExplode[0];

//Modificar senha.
if (($NovaSenha != "") and ($NovaSenha == $ConfirmaSenha)) {
$sql = "UPDATE login SET senha='$NovaSenha' WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());
}

header('Location: minha_conta.php' );
?>