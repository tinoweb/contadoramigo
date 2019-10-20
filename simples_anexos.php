<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php 
include 'session.php';
include 'check_login.php' ;

if(isset($_SESSION["idSecao"])) {
	include 'session.php';
	$painelLogin = "Logado em: " . $_SESSION["nome_userSecao"] . " | <a class=\"linkCinza\" href=\"logout.php\">Sair</a>";
}
else {
	$painelLogin = "";
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contador Amigo - Faça você mesmo sua própria contabilidade</title>
<link href="estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/meusScripts.js"></script>
</head>
<body>

<div style="margin:20px; text-align:left">

  <div class="titulo" style="margin-bottom:10px;">Simples Nacional</div>
  Distribuição de atividades segundo a forma de tribução prevista na  Lei Complementar nº 123, de 14 de dezembro de 2006.<br />
  <br />
  
    <span class="tituloVermelho">Anexo III</span>
    <ul>
    <li>Locação de bens móveis</li>
    <li>Creche</li>
    <li>Pré-escola</li>
    <li>Estabelecimento de ensino fundamental</li>
    <li>Escolas técnicas, profissionais e de ensino médio</li>
    <li>Escolas de línguas estrangeiras</li>
    <li>Escolas de artes </li>
    <li>Cursos técnicos de pilotagem</li>
    <li>Cursos preparatórios para concursos</li>
    <li>Cursos gerenciais </li>
    <li>Escolas livres</li>
    <li>Agência terceirizada de correios </li>
    <li>Agência de viagem e turismo </li>
    <li>Auto-escolas (passageiros e de carga)</li>
    <li>Agência lotérica </li>
    <li>Serviços de instalação, reparos e  manutenção em geral</li>
    <li>Usinagem, solda, tratamento e revestimento em metais </li>
    <li>Transporte municipal de passageiros</li>
    <li>Produções cinematográficas, audiovisuais, artísticas e culturais.</li>
    </ul>
<br />
<br />
<br />
<table border="0" cellspacing="0" cellpadding="0" width="732">
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">Receita Bruta em    12 meses (em R$)</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">ALÍQUOTA</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">IRPJ</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">CSLL</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">COFINS</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">PIS/PASEP</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">CPP</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">ISS</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">Até 120.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">6,00%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,00%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 120.000,01 a    240.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">8,21%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,42%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,00%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,79%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 240.000,01 a    360.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">10,26%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,48%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,43%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,43%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,35%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,07%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">3,50%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 360.000,01 a    480.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">11,31%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,53%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,53%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,56%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,38%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,47%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">3,84%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 480.000,01 a    600.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">11,40%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,53%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,52%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,58%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,38%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,52%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">3,87%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 600.000,01 a    720.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">12,42%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,57%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,57%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,73%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,40%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,92%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,23%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 720.000,01 a    840.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">12,54%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,59%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,56%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,74%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,42%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,97%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,26%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 840.000,01 a    960.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">12,68%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,59%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,57%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,76%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,42%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,03%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,31%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 960.000,01 a    1.080.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">13,55%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,63%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,61%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,88%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,45%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,37%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,61%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.080.000,01    a 1.200.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">13,68%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,63%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,64%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,89%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,45%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,42%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,65%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.200.000,01    a 1.320.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">14,93%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,69%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,69%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,07%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,50%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,98%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.320.000,01    a 1.440.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">15,06%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,69%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,69%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,09%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,50%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">6,09%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.440.000,01    a 1.560.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">15,20%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,71%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,70%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,10%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,50%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">6,19%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.560.000,01    a 1.680.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">15,35%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,71%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,70%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,13%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,51%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">6,30%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.680.000,01    a 1.800.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">15,48%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,72%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,70%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,15%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,51%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">6,40%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.800.000,01    a 1.920.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">16,85%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,78%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,76%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,34%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,56%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">7,41%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.920.000,01    a 2.040.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">16,98%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,78%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,78%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,36%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,56%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">7,50%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 2.040.000,01    a 2.160.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">17,13%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,80%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,79%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,37%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,57%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">7,60%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 2.160.000,01    a 2.280.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">17,27%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,80%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,79%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,40%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,57%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">7,71%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
  <tr>
    <td width="190"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 2.280.000,01    a 2.400.000,00</a></p></td>
    <td width="98"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">17,42%</a></p></td>
    <td width="61"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,81%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,79%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,42%</a></p></td>
    <td width="100"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,57%</a></p></td>
    <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">7,83%</a></p></td>
    <td width="48"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
  </tr>
</table>
<span class="tituloVermelho"><br /> 
Anexo IV</span>
  <ul>
    <li>Construção de imóveis </li>
    <li>Obras de engenharia em geral</li>
    <li>Paisagismo </li>
    <li>Decoração de interiores </li>
    <li>Serviço de vigilância </li>
    <li>Limpeza ou conservação</li>
  </ul>
    <table border="0" cellspacing="0" cellpadding="0" width="742">
      <tr>
        <td width="195"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">Receita Bruta em    12 meses&nbsp;(em R$)</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">ALÍQUOTA</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">IRPJ</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">CSLL</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">COFINS</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">PIS/PASEP</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">ISS</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">Até 120.000,00 </a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,50%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,22%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,28%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 120.000,01 a    240.000,00 </a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">6,54%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,84%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,91%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,00%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,79%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 240.000,01 a    360.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">7,70%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,16%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,85%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,95%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,24%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">3,50%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 360.000,01 a    480.000,00 </a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">8,49%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,52%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,87%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,99%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,27%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">3,84%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 480.000,01 a    600.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">8,97%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,89%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,89%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,03%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,29%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">3,87%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 600.000,01 a    720.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">9,78%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,25%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,91%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,07%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,32%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,23%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 720.000,01 a    840.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">10,26%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,62%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,93%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,11%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,34%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,26%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 840.000,01 a    960.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">10,76%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,00%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,95%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,15%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,35%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,31%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 960.000,01 a    1.080.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">11,51%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,37%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">1,97%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,19%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,37%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,61%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.080.000,01    a 1.200.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">12,00%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,74%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,00%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,23%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,38%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,65%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.200.000,01    a 1.320.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">12,80%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">3,12%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,01%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,27%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,40%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.320.000,01    a 1.440.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">13,25%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">3,49%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,03%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,31%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,42%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.440.000,01    a 1.560.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">13,70%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">3,86%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,05%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,35%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,44%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.560.000,01    a 1.680.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">14,15%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,23%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,07%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,39%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,46%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.680.000,01    a 1.800.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">14,60%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,60%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,10%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,43%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,47%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.800.000,01    a 1.920.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">15,05%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">4,90%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,19%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,47%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,49%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 1.920.000,01    a 2.040.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">15,50%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,21%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,27%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,51%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,51%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 2.040.000,01    a 2.160.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">15,95%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,51%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,36%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,55%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,53%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 2.160.000,01    a 2.280.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">16,40%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,81%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,45%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,59%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,55%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
      <tr>
        <td width="195" valign="top"><p><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">De 2.280.000,01    a 2.400.000,00</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">16,85%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">6,12%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,53%</a></p></td>
        <td width="91"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">2,63%</a></p></td>
        <td width="104"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">0,57%</a></p></td>
        <td width="78"><p align="center"><a href="http://www.planalto.gov.br/ccivil_03/Leis/LCP/Lcp128.htm">5,00%</a></p></td>
      </tr>
    </table>
<br />
  
    <span class="tituloVermelho">Anexo V</span>
    <ul>
    <li>Administração e locação de imóveis de terceiros</li>
    <li>Academias de dança, capoeira,  ioga e  artes marciais</li>
    <li>Academias de atividades físicas e desportivas e natação</li>
    <li>Escolas de esportes </li>
    <li>Elaboração de programas de computadores, inclusive jogos eletrônicos </li>
    <li>Licenciamento ou cessão de direito de uso de programas de computação </li>
    <li>Planejamento, confecção, manutenção e atualização de páginas eletrônicas</li>
    <li>Empresas montadoras de estandes para feiras </li>
    <li>Laboratórios de análises clínicas ou de patologia clínica </li>
    <li>Serviços de tomografia </li>
    <li>Diagnósticos médicos por imagem, registros gráficos e métodos óticos</li>
    <li>Serviços de ressonância magnética </li>
  <li>Serviços de prótese em geral</li>
  </ul>
  <strong><br />
  <br />
  </strong>

    1) Será apurada a relação (r) conforme abaixo:&nbsp;<br />
    (r) = Folha de Salários incluídos  encargos (em 12 meses)<br />
    Receita Bruta (em 12 meses)&nbsp;<br />
    2) Nas hipóteses em que (r) corresponda aos intervalos  centesimais da Tabela V-A, onde “&lt;” significa menor que, “&gt;” significa  maior que, “=&lt;” significa igual ou menor que e “&gt;=” significa maior ou  igual que, as alíquotas do Simples Nacional relativas ao IRPJ, PIS/Pasep, CSLL,  Cofins&nbsp; e CPP corresponderão ao seguinte:<br />
<br />

  TABELA V-A

  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="186"><br />
        Receita    Bruta em 12 meses (em R$) </td>
      <td width="80"><p align="center">(r)&lt;0,10</p></td>
      <td width="80"><p align="center">0,10=&lt; (r)<br />
        e<br />
        (r) &lt; 0,15</p></td>
      <td width="80"><p align="center">0,15=&lt; (r)<br />
        e<br />
        (r) &lt; 0,20</p></td>
      <td width="80"><p align="center">0,20=&lt; (r)<br />
        e<br />
        (r) &lt; 0,25</p></td>
      <td width="80"><p align="center">0,25=&lt; (r)<br />
        e<br />
        (r) &lt; 0,30</p></td>
      <td width="80"><p align="center">0,30=&lt; (r)<br />
        e<br />
        (r) &lt; 0,35</p></td>
      <td width="80"><p align="center">0,35=&lt; (r)<br />
        e<br />
        (r) &lt; 0,40</p></td>
      <td width="80"><p align="center">(r) &gt;= 0,40</p></td>
    </tr>
    <tr>
      <td width="186"><p>Até    120.000,00</p></td>
      <td width="80"><p align="center">17,50%</p></td>
      <td width="80"><p align="center">15,70%</p></td>
      <td width="80"><p align="center">13,70%</p></td>
      <td width="80"><p align="center">11,82%</p></td>
      <td width="80"><p align="center">10,47%</p></td>
      <td width="80"><p align="center">9,97%</p></td>
      <td width="80"><p align="center">8,80%</p></td>
      <td width="80"><p align="center">8,00%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    120.000,01 a 240.000,00</p></td>
      <td width="80"><p align="center">17,52%</p></td>
      <td width="80"><p align="center">15,75%</p></td>
      <td width="80"><p align="center">13,90%</p></td>
      <td width="80"><p align="center">12,60%</p></td>
      <td width="80"><p align="center">12,33%</p></td>
      <td width="80"><p align="center">10,72%</p></td>
      <td width="80"><p align="center">9,10%</p></td>
      <td width="80"><p align="center">8,48%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    240.000,01 a 360.000,00</p></td>
      <td width="80"><p align="center">17,55%</p></td>
      <td width="80"><p align="center">15,95%</p></td>
      <td width="80"><p align="center">14,20%</p></td>
      <td width="80"><p align="center">12,90%</p></td>
      <td width="80"><p align="center">12,64%</p></td>
      <td width="80"><p align="center">11,11%</p></td>
      <td width="80"><p align="center">9,58%</p></td>
      <td width="80"><p align="center">9,03%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    360.000,01 a 480.000,00</p></td>
      <td width="80"><p align="center">17,95%</p></td>
      <td width="80"><p align="center">16,70%</p></td>
      <td width="80"><p align="center">15,00%</p></td>
      <td width="80"><p align="center">13,70%</p></td>
      <td width="80"><p align="center">13,45%</p></td>
      <td width="80"><p align="center">12,00%</p></td>
      <td width="80"><p align="center">10,56%</p></td>
      <td width="80"><p align="center">9,34%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    480.000,01 a 600.000,00</p></td>
      <td width="80"><p align="center">18,15%</p></td>
      <td width="80"><p align="center">16,95%</p></td>
      <td width="80"><p align="center">15,30%</p></td>
      <td width="80"><p align="center">14,03%</p></td>
      <td width="80"><p align="center">13,53%</p></td>
      <td width="80"><p align="center">12,40%</p></td>
      <td width="80"><p align="center">11,04%</p></td>
      <td width="80"><p align="center">10,06%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    600.000,01 a 720.000,00</p></td>
      <td width="80"><p align="center">18,45%</p></td>
      <td width="80"><p align="center">17,20%</p></td>
      <td width="80"><p align="center">15,40%</p></td>
      <td width="80"><p align="center">14,10%</p></td>
      <td width="80"><p align="center">13,60%</p></td>
      <td width="80"><p align="center">12,60%</p></td>
      <td width="80"><p align="center">11,60%</p></td>
      <td width="80"><p align="center">10,60%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    720.000,01 a 840.000,00</p></td>
      <td width="80"><p align="center">18,55%</p></td>
      <td width="80"><p align="center">17,30%</p></td>
      <td width="80"><p align="center">15,50%</p></td>
      <td width="80"><p align="center">14,11%</p></td>
      <td width="80"><p align="center">13,68%</p></td>
      <td width="80"><p align="center">12,68%</p></td>
      <td width="80"><p align="center">11,68%</p></td>
      <td width="80"><p align="center">10,68%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    840.000,01 a 960.000,00</p></td>
      <td width="80"><p align="center">18,62%</p></td>
      <td width="80"><p align="center">17,32%</p></td>
      <td width="80"><p align="center">15,60%</p></td>
      <td width="80"><p align="center">14,12%</p></td>
      <td width="80"><p align="center">13,69%</p></td>
      <td width="80"><p align="center">12,69%</p></td>
      <td width="80"><p align="center">11,69%</p></td>
      <td width="80"><p align="center">10,69%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    960.000,01 a 1.080.000,00</p></td>
      <td width="80"><p align="center">18,72%</p></td>
      <td width="80"><p align="center">17,42%</p></td>
      <td width="80"><p align="center">15,70%</p></td>
      <td width="80"><p align="center">14,13%</p></td>
      <td width="80"><p align="center">14,08%</p></td>
      <td width="80"><p align="center">13,08%</p></td>
      <td width="80"><p align="center">12,08%</p></td>
      <td width="80"><p align="center">11,08%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    1.080.000,01 a 1.200.000,00</p></td>
      <td width="80"><p align="center">18,86%</p></td>
      <td width="80"><p align="center">17,56%</p></td>
      <td width="80"><p align="center">15,80%</p></td>
      <td width="80"><p align="center">14,14%</p></td>
      <td width="80"><p align="center">14,09%</p></td>
      <td width="80"><p align="center">13,09%</p></td>
      <td width="80"><p align="center">12,09%</p></td>
      <td width="80"><p align="center">11,09%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    1.200.000,01 a 1.320.000,00</p></td>
      <td width="80"><p align="center">18,96%</p></td>
      <td width="80"><p align="center">17,66%</p></td>
      <td width="80"><p align="center">15,90%</p></td>
      <td width="80"><p align="center">14,49%</p></td>
      <td width="80"><p align="center">14,45%</p></td>
      <td width="80"><p align="center">13,61%</p></td>
      <td width="80"><p align="center">12,78%</p></td>
      <td width="80"><p align="center">11,87%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    1.320.000,01 a 1.440.000,00</p></td>
      <td width="80"><p align="center">19,06%</p></td>
      <td width="80"><p align="center">17,76%</p></td>
      <td width="80"><p align="center">16,00%</p></td>
      <td width="80"><p align="center">14,67%</p></td>
      <td width="80"><p align="center">14,64%</p></td>
      <td width="80"><p align="center">13,89%</p></td>
      <td width="80"><p align="center">13,15%</p></td>
      <td width="80"><p align="center">12,28%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    1.440.000,01 a 1.560.000,00</p></td>
      <td width="80"><p align="center">19,26%</p></td>
      <td width="80"><p align="center">17,96%</p></td>
      <td width="80"><p align="center">16,20%</p></td>
      <td width="80"><p align="center">14,86%</p></td>
      <td width="80"><p align="center">14,82%</p></td>
      <td width="80"><p align="center">14,17%</p></td>
      <td width="80"><p align="center">13,51%</p></td>
      <td width="80"><p align="center">12,68%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    1.560.000,01 a 1.680.000,00</p></td>
      <td width="80"><p align="center">19,56%</p></td>
      <td width="80"><p align="center">18,30%</p></td>
      <td width="80"><p align="center">16,50%</p></td>
      <td width="80"><p align="center">15,46%</p></td>
      <td width="80"><p align="center">15,18%</p></td>
      <td width="80"><p align="center">14,61%</p></td>
      <td width="80"><p align="center">14,04%</p></td>
      <td width="80"><p align="center">13,26%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    1.680.000,01 a 1.800.000,00</p></td>
      <td width="80"><p align="center">20,70%</p></td>
      <td width="80"><p align="center">19,30%</p></td>
      <td width="80"><p align="center">17,45%</p></td>
      <td width="80"><p align="center">16,24%</p></td>
      <td width="80"><p align="center">16,00%</p></td>
      <td width="80"><p align="center">15,52%</p></td>
      <td width="80"><p align="center">15,03%</p></td>
      <td width="80"><p align="center">14,29%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    1.800.000,01 a 1.920.000,00</p></td>
      <td width="80"><p align="center">21,20%</p></td>
      <td width="80"><p align="center">20,00%</p></td>
      <td width="80"><p align="center">18,20%</p></td>
      <td width="80"><p align="center">16,91%</p></td>
      <td width="80"><p align="center">16,72%</p></td>
      <td width="80"><p align="center">16,32%</p></td>
      <td width="80"><p align="center">15,93%</p></td>
      <td width="80"><p align="center">15,23%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    1.920.000,01 a 2.040.000,00</p></td>
      <td width="80"><p align="center">21,70%</p></td>
      <td width="80"><p align="center">20,50%</p></td>
      <td width="80"><p align="center">18,70%</p></td>
      <td width="80"><p align="center">17,40%</p></td>
      <td width="80"><p align="center">17,13%</p></td>
      <td width="80"><p align="center">16,82%</p></td>
      <td width="80"><p align="center">16,38%</p></td>
      <td width="80"><p align="center">16,17%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    2.040.000,01 a 2.160.000,00</p></td>
      <td width="80"><p align="center">22,20%</p></td>
      <td width="80"><p align="center">20,90%</p></td>
      <td width="80"><p align="center">19,10%</p></td>
      <td width="80"><p align="center">17,80%</p></td>
      <td width="80"><p align="center">17,55%</p></td>
      <td width="80"><p align="center">17,22%</p></td>
      <td width="80"><p align="center">16,82%</p></td>
      <td width="80"><p align="center">16,51%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    2.160.000,01 a 2.280.000,00</p></td>
      <td width="80"><p align="center">22,50%</p></td>
      <td width="80"><p align="center">21,30%</p></td>
      <td width="80"><p align="center">19,50%</p></td>
      <td width="80"><p align="center">18,20%</p></td>
      <td width="80"><p align="center">17,97%</p></td>
      <td width="80"><p align="center">17,44%</p></td>
      <td width="80"><p align="center">17,21%</p></td>
      <td width="80"><p align="center">16,94%</p></td>
    </tr>
    <tr>
      <td width="186"><p>De    2.280.000,01 a 2.400.000,00</p></td>
      <td width="80"><p align="center">22,90%</p></td>
      <td width="80"><p align="center">21,80%</p></td>
      <td width="80"><p align="center">20,00%</p></td>
      <td width="80"><p align="center">18,60%</p></td>
      <td width="80"><p align="center">18,40%</p></td>
      <td width="80"><p align="center">17,85%</p></td>
      <td width="80"><p align="center">17,60%</p></td>
      <td width="80"><p align="center">17,18%</p></td>
    </tr>
  </table>
  <br />
  <br />
  TABELA V-B
  <br />
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="210" valign="bottom"><br />
        Receita Bruta em 12 meses (em R$) </td>
      <td width="80"><p align="center">CPP</p></td>
      <td width="80"><p align="center">IRPJ</p></td>
      <td width="80"><p align="center">CSLL</p></td>
      <td width="117"><p align="center">COFINS</p></td>
      <td width="133"><p align="center">PIS/PASEP</p></td>
    </tr>
    <tr>
      <td width="210"><p>&nbsp;</p></td>
      <td width="80"><p align="center">I</p></td>
      <td width="80"><p align="center">J</p></td>
      <td width="80"><p align="center">K</p></td>
      <td width="117"><p align="center">L</p></td>
      <td width="133"><p align="center">M</p></td>
    </tr>
    <tr>
      <td width="210"><p>Até    120.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,9</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    120.000,01 a 240.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,875</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    240.000,01 a 360.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,85</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    360.000,01 a 480.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,825</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    480.000,01 a 600.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,8</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    600.000,01 a 720.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,775</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    720.000,01 a 840.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,75</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    840.000,01 a 960.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,725</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    960.000,01 a 1.080.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,7</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    1.080.000,01 a 1.200.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,675</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    1.200.000,01 a 1.320.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,65</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    1.320.000,01 a 1.440.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,625</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    1.440.000,01 a 1.560.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,6</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    1.560.000,01 a 1.680.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,575</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    1.680.000,01 a 1.800.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,55</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    1.800.000,01 a 1.920.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,525</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    1.920.000,01 a 2.040.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,5</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    2.040.000,01 a 2.160.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,475</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    2.160.000,01 a 2.280.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,45</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
    <tr>
      <td width="210"><p>De    2.280.000,01 a 2.400.000,00</p></td>
      <td width="80"><p align="center">N x<br />
        0,425</p></td>
      <td width="80" valign="top"><p align="center">0,75 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="80" valign="top"><p align="center">0,25 X<br />
        (100 - I)<br />
        X P</p></td>
      <td width="117"><p align="center">0,75 X<br />
        (100 – I – J - K)</p></td>
      <td width="133"><p align="center">100 – I – J –    K - L</p></td>
    </tr>
  </table>
  3) Somar-se-á a alíquota do  Simples Nacional relativa ao IRPJ, PIS/Pasep, CSLL, Cofins e CPP apurada na  forma acima a parcela correspondente ao ISS prevista no Anexo IV.<br />
    4) A partilha das receitas relativas ao IRPJ, PIS/Pasep,  CSLL, Cofins e CPP arrecadadas na forma deste Anexo será realizada com base nos  parâmetros definidos na Tabela V-B, onde:<br />
    (I) = pontos percentuais da partilha destinada à CPP;<br />
    (J) = pontos percentuais da partilha destinada ao IRPJ,  calculados após o resultado do fator (I);<br />
    (K) = pontos percentuais da partilha destinada à CSLL,  calculados após o resultado dos fatores (I) e (J);<br />
    L = pontos percentuais da partilha destinada à COFINS,  calculados após o resultado dos fatores (I), (J) e (K);<br />
    (M) = pontos percentuais da partilha destinada à  contribuição para o PIS/PASEP, calculados após os resultados dos fatores (I),  (J), (K) e (L);<br />
    (I) + (J) + (K) + (L) + (M) = 100<br />
    N = relação (r) dividida por 0,004, limitando-se o resultado  a 100;<br />
    P = 0,1 dividido pela relação (r), limitando-se o resultado  a 1.<br />

  <p align="center">TABELA V-B</p>
  <div align="center"></div>
  <strong><br />
  <br />
  <br />
  <br />
  <br />
  <br />
  Observações Importantes</strong>:<br />
  <br />
  Se a sua atividade não estiver listada aqui, selecione Anexo III, na página do Simples.<br />
  <br />
  Tome cuidado para não escolher  atividades diferentes daquelas registradas no seu CNPJ. Com base nos códigos CNAE que você gravou em seus dados, as atividades previstas para sua empresa são: <span class="destaque">nome da atividade 1, nome da atividade 2</span><br />
<br />

  <strong>E</strong>sta é uma lista simplificada   <a href="http://www.receita.fazenda.gov.br/legislacao/leiscomplementares/2006/leicp123.htm" target="_blank"></a> e está sujeita a alterações. Para a confirmação do enquadramento de sua atividade, sugerimos a consulta direta  da <a href="http://www.receita.fazenda.gov.br/legislacao/leiscomplementares/2006/leicp123.htm" target="_blank">Lei Complementar nº 123, de 14 de dezembro de 2006</a> (Seção III, a partir do artigo 18º).
</div>

</body>
</html>
