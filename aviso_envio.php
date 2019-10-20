<?php
session_start();

$_SESSION['email_aviso_enviado'] = 'ok';

$NomePessoal = $_POST["avisoNomePessoal"];
$emailPessoal = strtolower($_POST["avisoEmailPessoal"]);
$paginaAtual = $_POST["AvisoPaginaAtual"];


################################################################################################################################################	
//Inserir no emkt
include 'emkt.api.class.php';

$emkt = new APi_EMKT();

$id_lista = $emkt->getIdLista("Comércio e indústria");

$emkt->inserirContatoEMKTsemCadastro($emailPessoal,$NomePessoal,$id_lista);

$id_da_lista = $emkt->getIdLista("Prospects");

$contatos = array($emailPessoal);

$emkt->addContatoLista($contatos,$id_da_lista);

################################################################################################################################################


/* Redirecionando para a página de sucesso */
if($paginaAtual == "") {
	$paginaAtual = "index.php";
}

echo '<script>window.location="' . $paginaAtual . '"</script>';
?>