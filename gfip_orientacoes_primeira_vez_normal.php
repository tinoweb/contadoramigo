<?php include 'header_restrita.php' ?>

<div class="principal">

<!--BALLOM Sefip -->
<div style="width:310px; position:absolute; margin-left:210px; margin-top:130px; display:none" id="sefip">
<div style="width:8px; position:absolute; margin-left:280px; margin-top:12px"><a href="javascript:fechaDiv('sefip')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td>
    </tr>
    <tr>
      <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
      <td width="285" bgcolor="#ffff99" valign="top"><div style="width:245px; margin-left:20px; font-size:12px">
      <strong>Sefip</strong> é um programa disponibilizado gratuitamente pela Caixa Econômica Federal para geração da Gfip. Veja em <a href="procedimentos_iniciais.php">procedimentos iniciais</a> como baixá-lo e instalá-lo em seu computador</div></td>
      <td background="images/balloon_fundo_dir.png" width="7"></td>
    </tr>
    <tr>
      <td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td>
    </tr>
  </table>
</div>
<!--FIM DO BALLOOM CCM -->

<h1>Impostos e Obrigações</h1>
<h2>Envio da Gfip</h2>

<div style="margin-bottom:20px">Lembre-se de que, para conseguir enviar a Gfip, você precisa primeiro fazer o <a href="https://www.contadoramigo.com.br/configuracao_sefip.php">download e configuração do Sefip</a>, <a href="java_tutorial.php">habilitar o Java</a>, <a href="ie_tutorial.php">configurar o Internet Explorer</a> e, muitas vezes, <a href="avast_avg_tutorial.php">configurar o antivírus</a> também. Caso tenha alguma dificuldade, contate nosso  <a href="suporte.php">Help Desk</a> e agende um suporte remoto.</div>

 <!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo16','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br />
<br />

	Localize em seu micro o programa Sefip e abra-o. Selecione a opção <strong>Importar Folha</strong> e clique no botão "<strong>Próximo</strong>".<br /><br />

<img src="images/sefip1.png" width="55%" height="40%" /><br /><br />
</div>


<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2');">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br />
<br />
<div>
	Localize o arquivo <strong>sefip.re</strong> que você baixou para seu computador e clique em <strong>Abrir</strong>. O nome do arquivo deve estar como "sefip.re", do contrário você não conseguirá importá-lo. Ao importar a Folha, aparecerá um alerta: "O movimento atual será fechado. Todos os dados serão excluídos. confirma?". Pode confirmar sem medo.<br>
<br>
<div class="destaque">ATENCÃO: se você tiver funcionários, deverá importar as duas folhas (pró-labore/autônomos e funcionários), uma de cada vez. Ambas precisarão ter o mesmo nome sefip.re para conseguir importá-las.</div>
<br /> <br />
</div>

<img src="images/sefip_importar2.png" width="73%" height="30%" /><br /><br />
</div>


 <!--passo 3 -->
<div id="passo3" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3'); javascript:fechaDiv('tabela')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3'); javascript:fechaDiv('tabela')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br />
<br />
<div>
	Pronto, sua folha foi carregada. Agora você deve atualizar as tabelas de cálculo. Para isso clique em <strong>Ferramentas</strong> / <strong>Carga Manual de Tabelas</strong> / <strong>Auxiliares - INSS</strong> / <strong>Automático</strong>. As tabelas auxiliares devem ser atualizadas sempre que o governo corrigir o salário mínimo e os tetos dos salários de contribuição. Na dúvida, atualize. Se aparecer a mensagem "Não existe tabela auxiliar atualizada para captura". É porque você já está com a tabela mais recente.<br /><br />
</div>

<img src="images/sefip_new.png" width="100%" height="60%" /><br /><br />
    
</div>

 <!--BALLOM tabela -->
<div style="width:310px; position:absolute; margin-top:-650px; margin-left:300px; display:none" id="tabela">
<div style="width:8px; position:absolute; margin-left:280px; margin-top:12px; background-color:#FF9"><a href="javascript:fechaDiv('tabela')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>

  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td>
    </tr>
    <tr>
      <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
      <td width="285" bgcolor="#FFFF99" valign="top"><div style="width:245px; margin-left:20px; font-size:12px">
      <strong>ATENÇÃO:</strong> todo ano as tabelas do INSS são alteradas. Se você recolhe o INSS pelo teto precisa atualizar as tabelas no Sefip para que a GPS saia com o valor atualizado.
</div></td>
      <td background="images/balloon_fundo_dir.png" width="7"></td>
    </tr>
    <tr>
      <td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td>
    </tr>
  </table>
</div>
<!--FIM DO BALLOOM tabela -->


 <!--passo 4-->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4');"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique no ícone <img src="images/ico_nova_empresa.gif" width="12" height="15" /> para selecionar sua empresa. E depois, em <strong>Dados do Movimento</strong>.<br /><br />
</div>
    
<img src="images/sefip_fap.png" width="75%" height="60%" /><br /><br />
</div>

 <!--passo 5-->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Em <strong>Informações do Movimento</strong>, no campo <strong>F.A.P.</strong> preencha com o valor 1 e Salve.<br /><br />
</div>
<img src="images/sefip_info_movimento02.png" width="75%" height="60%" /><br /><br />
</div>


<!--passo 6 -->
<div id="passo6" style="display:none"> 
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />   
<div>
	Clique no ícone da calculadora <img src="images/ico_calculadora.gif" width="16" height="12" /> e em seguida no botão executar <img src="images/ico_executar.gif" width="11" height="13" />. Se tudo correr bem, aparecerá uma mensagem que começa assim: &quot;Para garantir a preservação do arquivo...&quot;. Clique ok . Se aparecer uma mensagem informando &quot;<strong>Inconsistência</strong>&quot;, abra o relatório de inconsistências e envie-o pelo Help Desk, para que possamos analisá-lo e fornecer-lhe a solução. Um dos erros mais comuns é o assinante esquecer de informar a alíquota FAP, conforme orientado no passo anterior.<br />
<br />
	Quando a Execução for bem sucedida, uma janela será aberta, sugerindo um caminho para salvar do arquivo de transmissão da Gfip (extensão .spf). Antes de salvá-lo, siga as orientações do passo a seguir deste tutorial.<br />
<br />
</div>

<img src="images/sefip_executar.png" width="80%" height="60%" /><br /><br />
</div>
    
      
<!-- passo 7 -->
<div id="passo7" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique no botão [...] do campo <strong>Caminho</strong> e escolha uma pasta para guardar seu  Gfip. <strong>Atenção: não salve no local sugerido pelo programa, você não conseguirá encontrá-lo</strong>. Selecione, ao invés disso, a pasta Área de Trabalho, clique <strong>Ok</strong> e <strong>Salve</strong>. Algumas janelas de confirmação e alerta aparecerão, clique ok. Pronto o arquivo da Gfip está criado e salvo em seu computador.<br /><br />
</div>

<img src="images/sefip_salvar.png" width="100%" height="60%" /><br /><br />
</div>
    
<!-- passo 8 -->
<div id="passo8" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 

Agora vamos transmitir a Gfip para os servidores da Previdência Social. <br />
<br />

<span class="destaqueAzul">Envio COM Certificado Digital:</span> entre no site <a href="https://conectividade.caixa.gov.br" target="_blank">Conectividade Social ICP</a> da Caixa, usando o navegador <strong>Internet Explorer</strong> (<a href="ie_tutorial.php">já previamente configurado</a>). No  primeiro acesso, você passará por um processo de validação. Ao final, a tela abaixo será exibida. No menu superior, clique em <strong>Caixa Postal</strong>.<br />
<br />
<span class="destaqueAzul">Envio SEM Certificado Digital (Chave PRI):</span> <a href="gfip_envio_cns.php">acesse este outro tutorial</a>. <br />
<br /> 

<img src="images/conectividade_CaixaPostal.png" width="90%" height="75%" /><br /><br />
</div>
    
<!-- passo 9 -->
<div id="passo9" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo9')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	No menu lateral, clique em <strong>Nova Mensagem</strong>.<br /><br />
</div>
<img src="images/conectividade_NovaMsg.png" width="90%" height="65%" /><br /><br />
</div>    
  
<!-- passo 10 -->
<div id="passo10" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo10')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 10 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo10')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Escolha a opção <strong>Envio de arquivo SEFIP </strong> e clique em <strong>Continuar</strong>.<br /><br />
</div>
<img src="images/conectividade_sefip.png" width="90%" height="65%" /><br /><br /> 
</div> 

<!-- passo 11 -->
<div id="passo11" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo11')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 11 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo11')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Se aparecer uma mensagem de alerta como abaixo, não se assute. Os aplicativos do Conectividade Social não são reconhecidos pelo Navegador, mas são seguros (afinal o site é do Governo Federal). Clique em &quot;eu aceito o risco e desejo executar esta aplicação&quot;<strong></strong>. Em seguida, clique no botão <strong>Executar</strong>.<br /><br />
</div>
<img src="images/conectividade_advert_sefip.png" width="90%" height="65%" /><br /><br /> 
</div> 

 <!-- passo12 -->
<div id="passo12" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo12')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 12 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo13','passo12')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<div>
	<ul> 
		<li>Preencha os campos indicados.</li>
		<li>Em <strong>Município de Arrecadação</strong>, selecione a cidade onde você fará o recolhimento do INSS.</li>
		<li>Selecione o item "Usar município selecionado como município padrão" para que o mesmo já venha definido no próximo envio.</li>
		<li>Deixe o campo <strong>Nome da Mensagem</strong> em branco.</li>
		<li>Clique em <strong>Anexar Arquivo</strong> e localize o arquivo que você gerou através do Sefip (a extensão dele é .SFP).</li>
	</ul>
</div>
<img src="images/conectividade_anexar.png" width="90%" height="65%" /><br /><br />
</div>

<!-- passo 13 -->
<div id="passo13" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo13')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 13 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo14','passo13')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique em <strong>Salvar</strong>. Será gerado um arquivo zip de sua mensagem. Salve-o junto do seu arquivo Sefip. Em seguida, clique no botão <strong>enviar</strong> para prosseguir.<br /><br />
<div class="quadro_branco"><strong>ATENÇÃO:</strong> se nesse momento aparecer a mensagem <strong>"Alguns componentes não foram carregados corretamente. Processo de envio não poderá ser concluído."</strong>. Apenas clique OK e aguarde cerca de 30 segundos. O processo será retomado automaticamente e você poderá seguir para o próximo passo.</div>

</div>
<img src="images/conectividade_salvar.png" width="90%" height="65%" /><br /><br />
</div>
 
 <!-- passo 14 -->
<div id="passo14" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo13','passo14')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 14 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo15','passo14')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Agora, você deverá enviar o arquivo zip da mensagem que acabou de salvar. Clique no botão <strong>Procurar</strong> e localize-a em seu micro. Em seguida, clique no botão <strong>Enviar</strong>.<br /><br />
</div>
<img src="images/conectividade_EnviarZip.png" width="90%" height="65%" /><br /><br />
</div>
 
 <!-- passo 15 -->
<div id="passo15" style="display:none">
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo14','passo15')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 15 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo16','passo15')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Acompanhe o envio do arquivo e em seguida clique em Salvar Protocolo.<br /><br />
</div>
<img src="images/conectividade_protocolo.png" width="90%" height="85%" /><br /><br />
</div>
 
<!-- passo 16 -->
<div id="passo16" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo15','passo16')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 16 de 16</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo16')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique em <strong>Salvar Pdf</strong> em seguida imprima o protocolo de envio e arquive-o. Pronto, missão cumprida! Seu arquivo Gfip foi enviado. <br />
	Siga agora as <a href="inss.php">orientações para recolhimento do INSS</a>.<br /><br />
</div>
<img src="images/conectividade_protocolo2.png" width="70%" height="60%" /><br /><br />
</div>


 
</div>
</div>

<?php include 'rodape.php' ?>

