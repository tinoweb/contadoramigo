<?php include 'header_restrita.php' ?>

<div class="principal">

<h1>Impostos e Obrigações</h1>
<h2>Envio da Gfip sem certificado digital</h2>
<div style="margin-bottom:20px">
Para enviar a Gfip sem certificado digital é preciso obter uma chave especial de acesso junto à Caixa Econômica Federal. Se você já tem esta chave, siga os passos abaixo. Caso contrário, acesse primeiro nosso <a href="gfip_obtencao_chave_pri.php">guia para obtenção da chave PRI</a>. </div>

<!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Abra programa <strong>CNS - Conectividade Social</strong> e clique em <strong>Operações com Sefip</strong>. Se você ainda não tem este programa, siga o <a href="conectividade_cns_configuracao_restrita.php">Tutorial de Instalação e Configuração</a>.<br /><br />
<img src="images/conectividade_CNS/1.png" width="70%" height="45%" />
</div>

<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique em <b>Envio de RE</b><br /><br />
<img src="images/conectividade_CNS/2.png" width="70%" height="45%" />
</div>

<!--passo 3 -->
<div id="passo3" style="display:none">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
    <span class="tituloVermelho">Passo 3 de 10</span> 
    <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
  <br /> 
    Clique em adicionar. Se aparecer a mensagem <b>Erro :429 - ActiveX componente can't create objec</b>t, não se desespere. Você precisará fazer mais um ajuste para que este programa velhinho funcione em seu micro. Veja no quadro abaixo como fazê-lo.<br />
  <br />
  <div class="quadro_passos" style="padding:20px;font-family: Arial, Helvetica, sans-serif;">
  <span class="destaque">Erro :429 - ActiveX componente can't create object</span><br /><br />
  Se aparecer esta mensagem, não se desespere. Você precisará fazer mais um ajuste para que este programa velhinho funcione em seu micro. Feche o  Conectividade Social - CNS. Baixe <a href="downloads/cnsselo.dll">este</a> arquivo e copie-o para as seguintes pastas em seu micro:<br />
    <br />C:/Windows/System32/<br />
    C:/Windows/sysWOW64 (se houver)<br />
    C:/Arquivos de Programas (x86)/Caixa/CNS. Se não encontrar a pasta CNS neste local, ela pode estar em Program Files (x86).<br />
    <br />
    Feito isto, localize o arquivo <b>CNSComReg.exe</b> na pasta CNS e execute-o como administrador (para isso, clique com o botão direito do mouse e escolha a opção executar como administrador). <br />
    <br />
    Realizadas estas operações, retome o passo a passo. Se ainda der erro, tente desinstalar o CNS - Conectividade Social e reinstalá-lo novamente. Execute o arquivo <b>CNSComReg.exe</b> outra vez. Se os problemas persistirem, entre em contato com o suporte da Caixa pelos telefones 3004 1104 (capitais) ou 0800 726 0104 (demais regiões).</div>
    
    <img src="images/conectividade_CNS/3.png" width="55%" height="40%" /></p>

</div>

<!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Localize o arquivo .SFP que você gerou
no Sefip e clique <b>Ok.</b><br /><br />
<img src="images/conectividade_CNS/4.png" width="55%" height="40%" />
</div>

<!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Selecione a cidade sede de sua empresa (conforme consta no CNPJ)
<br /><br />
<img src="images/conectividade_CNS/5.png" width="65%" height="45%" />
</div>

<!--passo 6 -->
<div id="passo6" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique duas vezes no local indicado pela seta abaixo para selecionar o arquivo que deseja enviar. Atenção: o &quot;X&quot; indicando que a linha foi selecionada deve aparecer ao clicá-la.<br /><br />
<img src="images/conectividade_CNS/6.png" width="55%" height="40%" />
</div>

<!--passo 7 -->
<div id="passo7" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Responda <b>Sim</b> para o alerta &quot;Deseja enviar a operação imediatamente?&quot;<br /><br />
<img src="images/conectividade_CNS/7.png" width="60%" height="40%" />
</div>

<!--passo 8 -->
<div id="passo8" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Se aparecer uma caixa com mensagens, vá clicando em próxima até passar por todas as mesnagens, caso contrário você não conseguirá enviar o arquivo<br /><br />
<img src="images/conectividade_CNS/8.png" width="70%" height="45%" />
</div>

<!--passo 9 -->
<div id="passo9" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo9')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Digite a senha de sua Chave PRI (aquela que você gravou no <i>pendrive</i>).<br /><br />
<img src="images/conectividade_CNS/9.png" width="65%" height="40%" />
</div>

<!--passo 10 -->
<div id="passo10" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo10')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 10 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo10')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Imprima o protocolo de transmissão e arquive-o. <br /><br />
<img src="images/conectividade_CNS/10.png" width="65%" height="50%"/><br />
<br />
<br />
Pronto, missão cumprida! Seu arquivo Gfip foi enviado. Siga agora as <a href="inss.php">orientações para recolhimento do INSS</a>.<br />
</div>

 
</div>
</div>

<?php include 'rodape.php' ?>

