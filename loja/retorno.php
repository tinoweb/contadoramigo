<?
session_start();
/*
<!--
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
' Kit de Integra��o Cielo
' Vers�o: 3.0
' Arquivo: retorno.php
' Fun��o: Retorno da p�gina da Cielo Ecommerce
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
-->
*/
// IMPORTANTE: PARA USAR ESSE ARQUIVO COMO URL DE RETORNO DA P�GINA DA CIELO, CADASTRE-O NA SUA CONFIGURA��O EM SUA 
// CONFIGURA��O CIELO ECOMMERCE NO SEU PAINEL DE GATEWAY DE PAGAMENTOS DA LOCAWEB.

// ########################################################################################################

echo '<b>C�digo de identifica��o do pedido (TID): </b>' . $_SESSION['tid'] . '<br />';
echo '<a href="consulta_transacao.php?tid=' . $_SESSION['tid'] . '">Consulta da transacao</a><br />'
?>
