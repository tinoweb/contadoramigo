<?php include 'header_restrita.php' ?>

<?
if($_SESSION['dados_DARF_userSessao'][$_GET['id']]['tipo'] != 'pessoa jurídica'){
	echo "<script>alert('Não foram localizadas informações a respeito deste DARF.');location.href='darf_orientacoes.php';</script>";
}
?>

<div class="principal">

<span class="titulo">Pagamentos </span><br /><br />
<div style="margin-bottom:20px" class="tituloVermelho"><span class="tituloVermelho" style="margin-bottom:20px">Emissão de DARF referente a IRRF</span> sobre pagamentos a pessoas jurídicas</div>

<div style="margin-bottom:20px">Utilize o tutorial abaixo para recolher as retenções de IR referentes aos pagamentos efetuados a pessoas jurídicas. O recolhimento deve ser feito até o dia 20 do mês subsequente ao do pagamento. Se cair em um feriado, o recolhimento deverá ser antecipado.</div>

 <!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
Para iniciar o processo, acesse a <a href="http://www.receita.fazenda.gov.br/Aplicacoes/ATSPO/SicalcWeb/default.asp?TipTributo=2&amp;FormaPagto=1" target="_blank">página para emissão de DARF</a> da Receita Federal  e clique em <strong>Pagamento</strong>, conforme indicado abaixo.<br /><br />
<img src="images/darf_pj/darf1.png" width="966" height="553" /><br /><br />
</div>


 <!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
Preencha o campo com os caracteres 	que aparecem na imagem ao lado e clique em <strong>Continuar</strong>.<br /><br />
<img src="images/darf_pj/darf2.png" width="966" height="553" /><br /><br />
</div>


 <!--passo 3 -->
<div id="passo3" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
Selecione o Estado (UF) de domicílio da sua empresa e clique em <strong>Continuar</strong>. Você só precisará defini-lo  uma  única vez no mesmo computador. Nas próximas você deverá ir direto para o passo 5.<br /><br />
<img src="images/darf_pj/darf3.png" width="966" height="553" /><br /><br />
</div>

 <!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br /><br /> 
Selecione o Município  de domicílio da sua empresa e clique em <strong>Continuar</strong>. Você só precisará defini-lo uma única vez no mesmo computador. Nas próximas você deverá ir direto para o passo 5. <br />
<br />
<img src="images/darf_pj/darf4.png" width="966" height="553" /><br />
<br />
</div>


 <!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
Em <strong>Código da Receita</strong>, digite o número <strong><?=$_SESSION['dados_DARF_userSessao'][$_GET['id']]['codigo_servico']?></strong> (<?=$_SESSION['dados_DARF_userSessao'][$_GET['id']]['descricao_servico']?>). Lembre-se você deverá gerar um DARF diferente para cada código de receita. Em seguida, clique em <strong>Continuar</strong>.<br /><br />
<img src="images/darf_pj/darf5_pro-labore.png" width="966" height="553" /><br /><br />
</div>


 <!--passo 6 -->
<div id="passo6" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
Em Tipo de Período, selecione: <strong>Mensal - a partir de Jan/2008</strong><br />
Em Período, informe: <strong><?=str_pad($_SESSION['mes_DARF_userSessao'],2,"0",STR_PAD_LEFT)?><?=($_SESSION['ano_DARF_userSessao'])?></strong><br />
Em Valor Principal, informe: <strong>R$ <?=number_format($_SESSION['dados_DARF_userSessao'][$_GET['id']]['valor'],2,",",".")?></strong>
<br /><br />
<img src="images/darf_pj/darf6_pro-labore.png" width="966" height="553" /><br /><br />
</div>

 <!--passo 7 -->
<div id="passo7" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
O campo <strong>Referência</strong> não deve ser usado nesse caso. Deixe em branco.<br /><br />
<img src="images/darf_pj/darf7_pro-labore.png" width="966" height="553" /><br /><br />
</div>

 <!--passo 8 -->
<div id="passo8" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />
Informe o CNPJ de sua empresa
.<br /><br />
<img src="images/darf_pj/darf8_pro-labore.png" width="966" height="553" /><br /><br />
</div>

 <!--passo 9 -->
<div id="passo9" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo9')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
Pronto! Se o pagamento estiver em atraso, os juros e multa serão calculados automaticamente. Imprima o DARF e pague-o na rede bancária na data indicada.<br /><br />
<img src="images/darf_pj/darf9_pro-labore.png" width="966" height="553" /><br /><br />
</div>

 
</div>
</div>

<?php include 'rodape.php' ?>

