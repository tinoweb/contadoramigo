<?php 
session_start();
if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
else {
	$nome_meta = "anexos";
	include 'header.php';} 
?>

<div class="principal">

  <h1>Anexos do Simples Nacional - 2018</h1>
 
	<h2>Anexo I</h2>
	<div style="margin-bottom: 20px">Todas as atividades de comércio</div>
	
  <table border="0" cellspacing="1" cellpadding="3" width="65%" style="margin-bottom: 20px">
    <tr>
      <th>Receita Bruta em 12 Meses (em R$)</th>
      <th>Alíquota</th>
      <th>Pacela a deduzir</th>
    </tr>
    <tr>
      <td class="td_calendario">Até 180.000,00</td>
      <td class="td_calendario">4,00%</td>
      <td class="td_calendario">-</td>
    </tr>
    <tr>
      <td class="td_calendario">De 180.000,01 a 360.000,00</td>
      <td class="td_calendario">7,30%</td>
      <td class="td_calendario">5.940,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 360.000,01 a 720.000,00</td>
      <td class="td_calendario">9,50%</td>
      <td class="td_calendario">13.860,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 720.000,01 a 1.800.000,00</td>
      <td class="td_calendario">10,70%</td>
      <td class="td_calendario">22.500,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 1.800.000,01 a 3.600.000,00</td>
      <td class="td_calendario">14,30%</td>
      <td class="td_calendario">87.300,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 3.600.000,01 a 4.800.000,00</td>
      <td class="td_calendario">19,00%</td>
      <td class="td_calendario">378.000,00</td>
    </tr>
	</table>
   
	<h2>Anexo II</h2>
	<div style="margin-bottom: 20px">Todos as atividades industriais</div>
 <table border="0" cellspacing="1" cellpadding="3" width="65%" style="margin-bottom: 20px">
    <tr>
      <th>Receita Bruta em 12 Meses (em R$)</th>
      <th>Alíquota</th>
      <th>Pacela a deduzir</th>
    </tr>
    <tr>
      <td class="td_calendario">Até 180.000,00</td>
      <td class="td_calendario">4,50%</td>
      <td class="td_calendario">-</td>
    </tr>
    <tr>
      <td class="td_calendario">De 180.000,01 a 360.000,00</td>
      <td class="td_calendario">7,80%</td>
      <td class="td_calendario">5.940,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 360.000,01 a 720.000,00</td>
      <td class="td_calendario">10,00%</td>
      <td class="td_calendario">13.860,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 720.000,01 a 1.800.000,00</td>
      <td class="td_calendario">11,20%</td>
      <td class="td_calendario">22.500,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 1.800.000,01 a 3.600.000,00</td>
      <td class="td_calendario">14,70%</td>
      <td class="td_calendario">85.500,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 3.600.000,01 a 4.800.000,00</td>
      <td class="td_calendario">30,00%</td>
      <td class="td_calendario">720.000,00</td>
    </tr>
	</table>

	<h2>Anexo III</h2>
	<div style="margin-bottom: 20px">Todos os serviços não discriminados nas tabelas IV ou V abaixo</div>
	
 <table border="0" cellspacing="1" cellpadding="3" width="65%" style="margin-bottom: 20px">
    <tr>
      <th>Receita Bruta em 12 Meses (em R$)</th>
      <th>Alíquota</th>
      <th>Pacela a deduzir</th>
    </tr>
    <tr>
      <td class="td_calendario">Até 180.000,00</td>
      <td class="td_calendario">6,00%</td>
      <td class="td_calendario">-</td>
    </tr>
    <tr>
      <td class="td_calendario">De 180.000,01 a 360.000,00</td>
      <td class="td_calendario">11,20%</td>
      <td class="td_calendario">9.360,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 360.000,01 a 720.000,00</td>
      <td class="td_calendario">13,50%</td>
      <td class="td_calendario">17.640,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 720.000,01 a 1.800.000,00</td>
      <td class="td_calendario">16,00%</td>
      <td class="td_calendario">35.640,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 1.800.000,01 a 3.600.000,00</td>
      <td class="td_calendario">21,00%</td>
      <td class="td_calendario">125.640,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 3.600.000,01 a 4.800.000,00</td>
      <td class="td_calendario">33,00%</td>
      <td class="td_calendario">648.000,00</td>
    </tr>
</table>

	<h2>Anexo IV</h2>
	<div style="margin-bottom: 20px">Advocacia, Decoração de interiores, Construção civil, Limpeza ou conservação, Paisagismo, Vigilância</div>
	
 <table border="0" cellspacing="1" cellpadding="3" width="65%" style="margin-bottom: 20px">
    <tr>
      <th>Receita Bruta em 12 Meses (em R$)</th>
      <th>Alíquota</th>
      <th>Pacela a deduzir</th>
    </tr>
    <tr>
      <td class="td_calendario">Até 180.000,00</td>
      <td class="td_calendario">4,50%</td>
      <td class="td_calendario">-</td>
    </tr>
    <tr>
      <td class="td_calendario">De 180.000,01 a 360.000,00</td>
      <td class="td_calendario">9,00%</td>
      <td class="td_calendario">8.100,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 360.000,01 a 720.000,00</td>
      <td class="td_calendario">10,20%</td>
      <td class="td_calendario">12.420,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 720.000,01 a 1.800.000,00</td>
      <td class="td_calendario">14,00%</td>
      <td class="td_calendario">39.780,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 1.800.000,01 a 3.600.000,00</td>
      <td class="td_calendario">22,00%</td>
      <td class="td_calendario">183.780,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 3.600.000,01 a 4.800.000,00</td>
      <td class="td_calendario">33,00%</td>
      <td class="td_calendario">828.000,00</td>
    </tr>
		</table>
		
		
	<h2>Anexo V</h2>
	<div style="margin-bottom: 20px">
Academias de dança e desportivas,
Acupuntura, 
Administração, 
Administração e locação de imóveis de terceiros, 
Agenciamento, exceto de mão de obra, 
Agronomia, 
Análises Técnicas, 
Arquitetura e urbanismo, 
Auditoria, 
Cartografia, 
Clínicas de nutrição,
Comissária, serviços de,
Consultoria,   
Despachantes,
Economia, 
Enfermagem, 
Engenharia, 
Fisioterapia,  
Fonoaudiologia, 
Geodesia, 
Geologia, 
Jornalismo, 
Laboratórios de análises clínicas, 
Medicina veterinária, 
Medicina, 
Montadoras de estandes para feiras, 
Odontologia e prótese dentária, 
Perícia, leilão e avaliação, 
Pesquisa, design, desenho, 
Podologia, 
Programação,
Prótese, serviços 
Psicologia, 
Publicidade, 
Representação comercial,  
Tradução interpretação, 
Topografia,
Web design.
</div>

			
 <table border="0" cellspacing="1" cellpadding="3" width="65%">
    <tr>
      <th>Receita Bruta em 12 Meses (em R$)</th>
      <th>Alíquota</th>
      <th>Pacela a deduzir</th>
    </tr>
    <tr>
      <td class="td_calendario">Até 180.000,00</td>
      <td class="td_calendario">15,50%</td>
      <td class="td_calendario">-</td>
    </tr>
    <tr>
      <td class="td_calendario">De 180.000,01 a 360.000,00</td>
      <td class="td_calendario">18,00%</td>
      <td class="td_calendario">4.500,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 360.000,01 a 720.000,00</td>
      <td class="td_calendario">19,50%</td>
      <td class="td_calendario">9.900,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 720.000,01 a 1.800.000,00</td>
      <td class="td_calendario">20,50%</td>
      <td class="td_calendario">17.100,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 1.800.000,01 a 3.600.000,00</td>
      <td class="td_calendario">23,00%</td>
      <td class="td_calendario">62.100,00</td>
    </tr>
    <tr>
      <td class="td_calendario">De 3.600.000,01 a 4.800.000,00</td>
      <td class="td_calendario">30,50%</td>
      <td class="td_calendario">540.000,00</td>
    </tr>
  </table>
</div>


<?php include 'rodape.php' ?>
