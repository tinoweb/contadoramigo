<?
session_start();
/*
<!--
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
' Kit de Integração Cielo
' Versão: 3.0
' Arquivo: retorno.php
' Função: Retorno da página da Cielo Ecommerce
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
-->
*/
// IMPORTANTE: PARA USAR ESSE ARQUIVO COMO URL DE RETORNO DA PÁGINA DA CIELO, CADASTRE-O NA SUA CONFIGURAÇÃO EM SUA 
// CONFIGURAÇÃO CIELO ECOMMERCE NO SEU PAINEL DE GATEWAY DE PAGAMENTOS DA LOCAWEB.

// ########################################################################################################

echo '<b>Código de identificação do pedido (TID): </b>' . $_SESSION['tid'] . '<br />';
echo '<a href="consulta_transacao.php?tid=' . $_SESSION['tid'] . '">Consulta da transacao</a><br />'
?>
