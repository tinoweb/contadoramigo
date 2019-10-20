<?php
include "conect.php";
include "session.php";

$acao 					= $_POST["acao"];

$ID = $_SESSION["id_userSecao"];

$idFuncionario = $_POST['hidFuncioanrioID'];
$idTransporte = $_POST['hidTransporteID'];
$origem = mysql_real_escape_string($_POST['txtOrigem']);
$destino = mysql_real_escape_string($_POST['txtDestino']);
$linha = mysql_real_escape_string($_POST['txtLinha']);
$empresa = mysql_real_escape_string($_POST['txtEmpresa']);
$trajeto = $_POST['radTrajeto'];
$tipo = $_POST['radTipo'];
$tarifa = str_replace(',','.',$_POST['txtTarifa']);
$tarifa = number_format($tarifa,2,'.',',');

if($acao == 'inserir'){
	$sql = "INSERT INTO dados_transporte_funcionario SET 
						idFuncionario='".$idFuncionario."'
						, origem='".$origem."'
						, destino='".$destino."'
						, tipo='".$tipo."'
						, linha='".$linha."'
						, empresa='".$empresa."'
						, tarifa=".$tarifa."
						, trajeto='".$trajeto."'
						";
	
}else{
	
	//Atualizar dados em dados da empresa.
	$sql = "UPDATE dados_transporte_funcionario SET 
						origem='".$origem."'
						, destino='".$destino."'
						, tipo='".$tipo."'
						, linha='".$linha."'
						, empresa='".$empresa."'
						, tarifa=".$tarifa."
						, trajeto='".$trajeto."'
					WHERE 
						idTransporte = " . $idTransporte . "";

}

//echo $sql;
//exit;

$resultado = mysql_query($sql)
or die (mysql_error());

if(strlen($_POST['pgOrigem']) > 0){

	$proximo_passo = $_POST['pgOrigem'];

}else{

	$proximo_passo = "meus_dados_funcionario.php";
	
}

header('Location: ' . $proximo_passo );
?>