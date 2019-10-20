<?php include 'header_restrita.php'; ?>
<div class="principal">
    <!--Dica Certificado eletronico-->
    <h1>Alteração de Empresa</h1>
    
    <h2>Via Rápida Empresa - Consultar se o processo já foi analisado pela Junta</h2>

    <!-- passo 1 -->
    <div id="passo1" style="display:block"> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
        <span class="tituloVermelho">Passo 1 de 3</span> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
        <br />
        <br />
        Com o <strong>Internet Explorer</strong>, acesse o  <a href="https://www.jucesp.sp.gov.br/VRE" target="_blank">Via Rápida Empresa</a>  e selecione a opção<strong> Processo Integrado de Viabilidade e Registro</strong>. Se o site aparecer desconfigurado, vá nas configurações do Internet Explorer, entre em Configurações do Modo de Exibição de Compatibilidade e inclua o site <em>sp.gov.br</em>.<br />
        <br />
        <img src="images/junta/consulta_processo1.png" width="100%" height="80%" alt="" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
    </div>
    
    <!-- passo 2 -->
    <div id="passo2" style="display:none"> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
        <span class="tituloVermelho">Passo 2 de 3</span> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
        <br />
        <br />
        Selecione <strong>nº do protocolo</strong>. Localize-o número no papel que lhe foi entregue pela Junta quando você deixou o processo e digite-o no local indicado. Arraste a imagem solicitada e clique em pesquisar. <br>
<br>

             <img src="images/junta/consulta_processo2.png" width="90%" height="70%" alt="" style="border-width:1px; border-color:#CCC; border-style:solid"/>
    </div>
        
        
        
        
       <!-- passo 3 -->
  <div id="passo3" style="display:none"> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
        <span class="tituloVermelho">Passo 3 de 3</span> 
        <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
        <br />
        <br /> 
        
        
        O resultado da pesquisa aparecerá. Nas colunas Viabilidade e Registro aparecerão o status <strong>Isento</strong>,<strong>Pendente</strong>, <strong>Deferido</strong> ou <strong>Indeferido/Exigência</strong>. Que significam:<br>
        <br>
      <strong>Em análise</strong>: será preciso aguardar mais um pouco. <br><br>

      <strong>Deferido</strong>: deu tudo certo. Vá até a Junta e retire a documentação. O próximo passo será registrar a alteração na Prefeitura. Para isso você deverá preencher <?php if( $user->getCidade() == 'São Paulo' ){ ?>o <a href="https://ccm.prefeitura.sp.gov.br/login/contribuinte?tipo=A" target="_blank">Requerimento de Atualização do CCM</a>, imprimi-lo e levá-lo na Praça de Atendimento da Secretaria de Finanças, localizada no Vale do Anhangabaú, 206/226, ao lado da Galeria Prestes Maia, de segunda a sexta-feira, das 8h às 18h. Mas atenção a entrega só pode ser feita mediante <a href="http://agendamentosf.prefeitura.sp.gov.br/forms/BemVindo.aspx" target="_blank">agendamento prévio</a>.<?php }else{ ?>um requerimento de atualização. Algumas cidades já possibilitam o preenchimento online. Verifique se esta facilidade  já está disponível em seu município. Mesmo com o preenchimento online, é bastante provável que você ainda precise ir até a Prefeitura para que seus dados sejam conferidos e sua identidade confirmada.<?php } ?><br><br>

      <strong>Indeferido/Exigência:</strong> verificaram algum erro ou inconsistência que você precisará corrigir. Vá até a Junta, pegue a documentação, veja qual a exigência e faça a correção no processo. Para saber como, acesse o<a href="alteracao_contrato_junta_sp_exigencia.php"> tutorial para cumprimento de exigência.</a><br>
      <br>
      O status <strong>isento</strong>, vale somente para a coluna <strong>Viabilidade</strong> e ocorre quando a alteração solicitada não requer novo alvará de funcionamento. <br />
      <br>
    <img src="images/junta/consulta_processo3.png" width="100%" height="70%" alt="" style="border-width:1px; border-color:#CCC; border-style:solid"/> </div>

</div>
<?php include 'rodape.php' ?>