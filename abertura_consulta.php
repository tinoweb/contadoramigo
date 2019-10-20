<?php include 'header_restrita.php'; ?>
<div class="principal">
    <!--Dica Certificado eletronico-->
    <span class="titulo">Abertura de Empresa</span><br />
    <br />
    <div class="tituloVermelho">Via Rápida Empresa - Consultar se processo já foi analisado pelo Junta</div>

    <!-- passo 1 -->
    <div id="passo1" style="display:block"> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
        <span class="tituloVermelho">Passo 1 de 2</span> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
        <br />
        <br />
        Acesse <a href="https://www.jucesp.sp.gov.br/VRE/Consulta/ConsultaProcessoOFF.aspx" target="_blank">esta página</a> do Via Rápida Empresa. Selecione <strong>nº do protocolo</strong>. Localize-o número no papel que lhe foi entregue pela Junta quando você deixou o processo e digite-o no local indicado. Arraste a imagem solicitada e clique em pesquisar<br />
        <br />
        <img src="images/junta/consulta3.png" width="966" height="557" /><br />
    </div>
    <!-- passo 2 -->
    <div id="passo2" style="display:none"> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
        <span class="tituloVermelho">Passo 2 de 2</span> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
        <br />
        <br />
        Descendo a tela, aparecerá o resultado da 
        pesquisa. Nas colunas Viabilidade e Registro aparecerão o status <strong>Isento</strong>,<strong>Pendente</strong>, <strong>Deferido</strong> ou <strong>Indeferido/Exigência</strong>. Que significam:<br>
        <br>
      <strong>Em análise</strong>: será preciso aguardar mais um pouco. <br>
      <strong>Deferido</strong>: deu tudo certo. Vá até a Junta e retire a documentação.O próximo passo será registrar a alteração na prefeitura.<br>
      <strong>Indeferido/Exigência:</strong> verificaram algum erro ou inconsistência que você precisará corrigir. Vá até a Junta, pegue a documentação, veja qual a exigência e faça a correção no processo. Para saber como, acesse o<a href="https://www.contadoramigo.com.br/abertura_contrato_junta_sp_exigencia.php"> tutorial para cumprimento de exigência.</a><br>
      <br>
      O status <strong>isento</strong>, vale somente para a coluna <strong>Viabilidade</strong> e ocorre quando a alteração solicitada não requer novo alvará de funcionamento. <br />
      <br>
      <img src="images/junta/consulta4.png" width="966" height="827" /><br />
        <br />
        <br />
    </div>

</div>
<?php include 'rodape.php' ?>