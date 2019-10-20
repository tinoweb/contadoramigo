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
<div class="titulo" style="margin-bottom:20px;">Simples Nacional e Gfip sem certificado digital</div>
<div style="margin-bottom:20px"><a href="http://www.caixa.gov.br/Downloads/fgts-conectividade-social/conectividade_social_instalacao_v1207.EXE">Baixe e instale o programa Conectividade Social - CNS</a>. Depois de instalado, clique com o botão direto do mouse no ícone do programa (em sua área de trabalho), vá em <strong>Propriedades</strong>, selecione a aba <strong>Compatibilidade</strong> e marque a opção<strong> Executar este programa como administrador,</strong> caso contrário ele não rodará. Em seguida, abra o programa e faça a configuração inicial, seguindo os passos abaixo.</div>
<div style="margin-bottom:20px" class="tituloVermelho">Conectividade Social CNS - configuração inicial</div>

<!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 5</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique em <strong>Próximo</strong><br />
<br />
<img src="images/conectividade_CNS_configuracao/1.png" width="527" height="391" style="border-width:1px; border-color:#CCC; border-style:solid" />
</div>

<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 5</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique no botão indicado pela seta vermelha abaixo e <strong>localize a chave PRI em seu micro</strong>. Você pode acessá-la diretamente do <em>pendrive</em> ou fazer uma cópia em seu HD. Dessa forma poderá acessar o Conectividade Social - CNS mesmo se não estiver com o <em>pendrive</em> à mão.<br /><br />
<img src="images/conectividade_CNS_configuracao/2.png" width="527" height="391" style="border-width:1px; border-color:#CCC; border-style:solid" />
</div>

<!--passo 3 -->
<div id="passo3" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 5</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Apenas clique em <strong>Próximo</strong>.<br /><br />
<img src="images/conectividade_CNS_configuracao/3.png" width="527" height="391" style="border-width:1px; border-color:#CCC; border-style:solid" />
</div>

<!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 5</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Apenas clique em <strong>Próximo</strong>.<br /><br />
<img src="images/conectividade_CNS_configuracao/4.png" width="527" height="391" style="border-width:1px; border-color:#CCC; border-style:solid" />
</div>

<!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 5</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique em terminar e pronto! A janela inicial do programa se abrirá, pronta para uso.<br /><br />
<img src="images/conectividade_CNS_configuracao/5.png" width="527" height="391" style="border-width:1px; border-color:#CCC; border-style:solid" />
</div>



</div>
</div>

<?php include 'rodape.php' ?>

