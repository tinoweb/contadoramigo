<?php include 'header_restrita.php' ?>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Outros Serviços</div>
  <div class="tituloVermelho" style="margin-bottom:20px">Obter o número do PIS ou NIT</div>
  
  <!-- passo 1 -->
<div id="passo1" style="display:block"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a><span class="tituloVermelho">Passo 1 de 2</span> <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Para obter o número de seu PIS ou NIT, acesse a página do <a href="https://www5.dataprev.gov.br/cnisinternet/faces/pages/index.xhtml" target="_blank">Cadastro Nacional de Informações Sociais</a> e clique em <strong>Inscrição / Filiado</strong>. Esta página é, na verdade, destinada a quem ainda não possui e deseja criar um número de PIS ou NIT, mas como no início do processo é feita  uma checagem para saber se usuário já possui um registro anterior, você conseguirá  descobrir o seu número.<br />
<br />
No formulário da página que se abriu, em <strong>Dados Básicos</strong> preencha  o nome do filiado (seu nome), nome da mãe completo,  CPF e data de nascimento.<br />
Em <strong>Documentos Complementares</strong>, basta preencher 1 item (a identidade, por exemplo, incluindo o dígito).<br />
<br />
Repita os caracteres exibidos na imagem de seguranca e  <strong>continue</strong>.<br /><br />
<img src="images/pis_novo.png" width="859" height="709" />
</div>


<!-- passo 2 -->
<div id="passo2" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 2</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Encontre seu PIS ou NIT na tarja vermelha, conforme indicado na figura abaixo.
<br /><br />
<img src="images/pis_novo2.png" width="859" height="709" /><br /><br />
</div>

</div>
<?php include 'rodape.php' ?>
