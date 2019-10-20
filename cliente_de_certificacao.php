<?php 
session_start();
$nome_meta = "sem_certificado";
?>
<?php if(isset($_SESSION["id_userSecao"])){?>
<?php include 'header_restrita.php' ?>
<?php } else { ?>
<?php include 'header.php' ?>
<?php } ?>

<div class="principal">

<h1>Simples Nacional e Gfip sem certificado digital</h1>
<h2>Cliente de Certificação</h2>

<!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo15','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Abra o Cliente de Certificação e clique em <strong>Preencher</strong>.<br />
<br />
<img src="images/cliente_certificacao/1.png" width="70%" height="40%" style="border-width:1px; border-color:#CCC; border-style:solid" />
</div>

<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /></a>
<br />
<br />
Clique na aba <strong>identificação</strong> e preencha seus dados, conforme constam em seu <a href="http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/cnpjreva_solicitacao.asp" target="_blank">comprovante do CNPJ</a>.<br /><br />
<img src="images/cliente_certificacao/2.png" width="70%" height="40%" /><br />
</div>


<!--passo 3 -->
<div id="passo3" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Clique na aba <strong>Endereço</strong> e preencha seus dados, conforme constam em seu <a href="http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/cnpjreva_solicitacao.asp" target="_blank">comprovante do CNPJ</a>.<br /><br />
<img src="images/cliente_certificacao/3.png" width="70%" height="40%" /><br />
</div>

<!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Em <strong>Responsáveis</strong>, cadastre o sócio-administrador. Se for firma individual, é o proprietário da empresa. Clique em <strong>Adicionar</strong> para gravá-lo.<br /><br />
<img src="images/cliente_certificacao/4.png" width="70%" height="40%" /><br />
</div>

<!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Agora, você vai criar um certificado com os dados cadastrados. Clique em <strong>Novo</strong>.<br /><br />
<img src="images/cliente_certificacao/5.png" width="75%" height="40%" /><br />
</div>

<!--passo 6 -->
<div id="passo6" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Siga as instruções para geração da chave de segurança.<br /><br />
<img src="images/cliente_certificacao/6.png" width="70%" height="40%" /><br />
</div>

<!--passo 7 -->
<div id="passo7" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Crie a senha e confirme. Guarde-a com cuidado. Ela será necessária no futuro.<br /><br />
<img src="images/cliente_certificacao/7.png" width="75%" height="40%" /><br />
</div>

<!--passo 8 -->
<div id="passo8" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Agora você vai gravar a chave. Conecte um <em>pendrive</em> vazio no micro e veja em que letra (drive) ele aparece. Em seguida, vá em <strong>Arquivo / Configuração</strong>.<br /><br />
<img src="images/cliente_certificacao/7-1.png" width="60%" height="45%" /><br />
</div>

<!--passo 9 -->
<div id="passo9" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo9')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Selecione a letra (drive) do seu pendrive para que o programa saiba onde deve salvar a chave e clique <strong>Ok</strong>.<br /><br />
<img src="images/cliente_certificacao/7-2.png" width="75%" height="45%" /><br />
</div>


<!--passo 10 -->
<div id="passo10" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo10')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 10 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo10')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Clique em <strong>Enviar</strong>.<br /><br />
<img src="images/cliente_certificacao/8.png" width="65%" height="40%" /><br />
</div>

<!--passo 11 -->
<div id="passo11" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo11')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 11 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo11')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Informe a senha que você acabou de gerar no passo 6 e clique <strong>Ok</strong>.<br /><br />
<img src="images/cliente_certificacao/9.png" width="70%" height="40%" /><br />
</div>

<!--passo 12 -->
<div id="passo12" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo12')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 12 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo13','passo12')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Certifique-se de que o <em>pendrive</em> esteja conectado na letra (drive) selecionado e clique <strong>Ok</strong>.<br /><br />
<img src="images/cliente_certificacao/10.png" width="70%" height="40%" /><br />
</div>

<!--passo 13 -->
<div id="passo13" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo13')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 13 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo14','passo13')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Clique <strong>Ok</strong>.<br /><br />
<img src="images/cliente_certificacao/11.png" width="70%" height="40%" /><br />
</div>

<!--passo 14 -->
<div id="passo14" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo13','passo14')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 14 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo15','passo14')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Marque o item &quot;Eu concordo com os termos do convênio de prestação de serviços&quot; e clique em <strong>Termo de Adesão ao Convênio</strong>.<br /><br />
<img src="images/cliente_certificacao/12.png" width="70%" height="40%" /><br />
</div>

<!--passo 15 -->
<div id="passo15" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo14','passo15')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 15 de 15</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo15')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Imprima 2 cópias do Termo de Adesão e clique em <strong>Finalizar</strong>. Pronto a requisiç√ão está gravada no <em>pendrive</em>. Volte para a página <a href="simples_e_gfip_sem_certificado.php">Como pagar impostos sem certificado digital</a> e siga os procedimentos indicados.<br /><br />
<img src="images/cliente_certificacao/13.png" width="70%" height="40%" /><br />
</div>

</div>
</div>
<?php include 'rodape.php' ?>

