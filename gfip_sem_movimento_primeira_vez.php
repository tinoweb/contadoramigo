<?php include 'header_restrita.php' ?>

<div class="principal">

<!--BALLOM Sefip -->
<div style="width:310px; position:absolute; margin-left:130px; margin-top:190px; display:none" id="sefip">
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
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo19','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />
<div>
	Se não houve faturamento no período,  nenhum pagamento de salário e você não deseja retirar pró-labore,  deverá enviar uma <strong>Gfip sem movimento</strong> informando a ausência de fato gerador. Nos meses subsequentes, enquanto a empresa permanecer na mesma situação, não será  mais necessário fazê-lo. Para enviar a Gfip com ausência de fato gerador siga os passos a seguir:<br /><br />
	Abra o programa  <a href="javascript:abreDiv('sefip')">Sefip</a>  e selecione a opção <strong>Cadastrar Responsável</strong><br /><br />
</div>
<img src="images/sefip1_sem_movimento.png" width="55%" height="40%" /><br /><br />
</div>


 <!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />
<div>
	Preencha o Cadastro de Responsável com os dados de sua empresa e clique em <strong>Finalizar</strong><br /><br />
</div>
<img src="images/sefip2.png" width="70%" height="45%" /><br /><br />
</div>


 <!--passo 3 -->
<div id="passo3" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique no botão <strong>Nova Empresa</strong>.<br /><br />
</div>
<img src="images/sefip3.png" width="80%" height="60%" /><br /><br />
</div>

 <!--passo 4 -->
<?php
//pega cnae principal da empresa
$sql3 = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $_SESSION["id_userSecao"] . "' AND tipo='1' LIMIT 0, 1";
$resultado3 = mysql_query($sql3) or die (mysql_error());
$linha_codigo=mysql_fetch_array($resultado3);
$cnae = $linha_codigo[cnae];
if ($cnae == '') { $cnae='você não preencheu este campo em Meus Dados';}

//paga campo fpas 
$sql5 = "SELECT * FROM cnae_fpas WHERE cnae='" . $linha_codigo[cnae] . "' LIMIT 0, 1";
$resultado5 = mysql_query($sql5) or die (mysql_error());
$linha_cnae_fpas = mysql_fetch_array($resultado5);
$fpas = $linha_cnae_fpas[fpas];
if ($fpas == '') { $fpas='preencha o campo CNAE em Meus Dados, para saber seu FPAS';}
if ($fpas == 736) { $fpas= 515;}
?>

<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Preencha os campos informando novamente os dados de sua empresa. Para os códigos CNAE e FPAS use:<br />
	CNAE: <span class="destaque"><?=$cnae?></span><br />
	CNAE Preponderante: <span class="destaque"><?=$cnae?></span><br />
	FPAS: <span class="destaque"><?=$fpas?></span> <br /><br />
</div>
<img src="images/sefip4.png" width="80%" height="60%" /><br /><br />
</div>


 <!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique na aba <strong>Movimento</strong>.<br /><br />
</div>
<img src="images/sefip9.png" width="75%" height="60%" /><br /><br />
</div>


 <!--passo 6 -->
<div id="passo6" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique no botão <strong>Novo</strong>.<br /><br />
</div>
<img src="images/sefip10.png" width="75%" height="60%" /><br /><br />
</div>

 <!--passo 7 -->
<div id="passo7" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Preencha a Competência (o mês e ano ao qual se refere o movimento). Caso esteja gerando a gfip da competência 13, use 13 como sendo o mês.<br />Em <strong>Código de Recolhimento</strong>, selecione 115  e em <strong>Fato Gerador </strong>, selecione "Ausência de Fato Gerador". <br />
	Em seguida, clique em <strong>Salvar</strong>. <br /><br />
</div>
<img src="images/sefip_novo_movimento.png" width="80%" height="60%" /><br /><br />
</div>

 <!--passo 8 -->
<div id="passo8" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique no ícone <img src="images/ico_nova_empresa.gif" width="12" height="15" /> para selecionar sua empresa. Em seguida clique no ícone <img src="images/ico_participacao.gif" width="23" height="22" /> para incluí-la no movimento do período. Finalmente, clique em <strong>Dados do Movimento</strong>.<br /><br />
</div>
<img src="images/sefip_alocacao.png" width="80%" height="60%" /><br /><br />
</div>

 <!--passo 9 -->
<div id="passo9" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo9')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Em <strong>Informações do Movimento</strong>, no campo <strong>Simples</strong> selecione a opção 2 - Optante. Em <strong>F.A.P.</strong> preencha com o valor 1 e Salve.<br /><br />
</div>
<img src="images/sefip_info_movimento03.png" width="80%" height="60%" /><br /><br />
</div>

<!--passo 10 -->
<div id="passo10" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo10')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 10 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo10')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />   
<div>
	Clique no ícone da calculadora <img src="images/ico_calculadora.gif" width="16" height="12" /> e em seguida no botão executar <img src="images/ico_executar.gif" width="11" height="13" />.  Algumas janelas de alerta aparecerão. Clique ok em todas elas.<br /><br />
</div>
<img src="images/sefip_executar.png" width="75%" height="60%" /><br /><br />
</div>
    
    
<!-- passo 11 -->
<div id="passo11" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo11')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 11 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo11')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique no botão [...] do campo <strong>Caminho</strong> e escolha uma pasta para guardar seu  Gfip. <strong>Atenção: não salve no local sugerido pelo programa, você não conseguirá encontrá-lo</strong>. Selecione, ao invés disso, a pasta Área de Trabalho, clique<strong> Ok</strong> e<strong> Salve</strong>. Algumas janelas de confirmação e alerta aparecerão, clique<strong> Ok</strong>. Pronto o arquivo da Gfip está criado e salvo em seu computador.<br /><br />
</div>
<img src="images/sefip_salvar.png" width="100%" height="60%" /><br /><br />
</div>
    

    
<!-- passo 12 -->
<div id="passo12" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo12')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 12 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo13','passo12')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
Agora vamos transmitir a Gfip para os servidores da Previdência Social. <br />
<br />
<span class="destaqueAzul">Envio COM Certificado Digital:</span> entre no site <a href="https://conectividade.caixa.gov.br" target="_blank">Conectividade Social ICP</a> da Caixa, usando o navegador <strong>Internet Explorer</strong> (<a href="ie_tutorial.php">já previamente configurado</a>). No  primeiro acesso, você passará por um processo de validação. Ao final, a tela abaixo será exibida. No menu superior, clique em <strong>Caixa Postal</strong>.<br />
<br />
<span class="destaqueAzul">Envio SEM Certificado Digital (Com chave PRI):</span> <a href="gfip_envio_cns.php">acesse este outro tutorial</a>.<br /><br />


<img src="images/conectividade_CaixaPostal.png" width="90%" height="70%" /><br /><br />


</div>
    
<!-- passo 13 -->
<div id="passo13" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo13')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 13 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo14','passo13')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	No menu lateral, clique em <strong>Nova Mensagem</strong>.<br /><br />
</div>
<img src="images/conectividade_NovaMsg.png" width="90%" height="70%" /><br /><br />
</div>    
  
<!-- passo 14 -->
<div id="passo14" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo13','passo14')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 14 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo15','passo14')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Escolha a opção <strong>Envio de arquivo SEFIP </strong> e clique em <strong>Continuar</strong>.<br /><br />
</div>
<img src="images/conectividade_sefip.png" width="90%" height="75%" /> <br /><br />
</div> 

 <!-- passo 15 -->
<div id="passo15" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo14','passo15')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 15 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo16','passo15')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Preencha os campos indicados. <br />
	Em <strong>Município de Arrecadação</strong>, selecione a cidade onde você fará o recolhimento do INSS.<br />
	Selecione o item "Usar município selecionado como município padrão" para que o mesmo já venha definido no próximo envio.<br />
	Deixe o campo <strong>Nome da Mensagem</strong> em branco.<br />
	Clique em <strong>Anexar Arquivo</strong> e localize o arquivo que você gerou através do Sefip (a extensão dele é .SFP).<br /><br />
</div>
<img src="images/conectividade_anexar.png" width="90%" height="70%" /><br /><br />
</div>

<!-- passo 16 -->
<div id="passo16" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo15','passo16')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 16 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo17','passo16')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Clique em <strong>Salvar</strong>. Será gerado um arquivo zip de sua mensagem. Salve-o junto do seu arquivo Sefip. Em seguida, clique no botão <strong>Enviar</strong> para prosseguir.<br /><br />
</div>
<img src="images/conectividade_salvar.png" width="90%" height="70%" /><br /><br />
</div>
 
 <!-- passo 17 -->
<div id="passo17" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo16','passo17')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 17 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo18','passo17')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Agora, você deverá enviar o arquivo zip da mensagem que acabou de salvar. Clique no botão <strong>Procurar</strong> e localize-a em seu micro. Em seguida, clique no botão <strong>Enviar</strong>.<br /><br />
    <div class="quadro_branco"><strong>ATENÇÃO:</strong> se nesse momento aparecer a mensagem <strong>"Alguns componentes não foram carregados corretamente. Processo de envio não poderá ser concluído."</strong>. Apenas clique OK e aguarde cerca de 30 segundos. O processo será retomado automaticamente e você poderá seguir para o próximo passo.</div>
</div>
<img src="images/conectividade_EnviarZip.png" width="90%" height="70%" /><br /><br />
</div>
 
 <!-- passo 18 -->
<div id="passo18" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo17','passo18')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 18 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo19','passo18')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Acompanhe o envio do arquivo e em seguida clique em <strong>Salvar Protocolo</strong>.<br /><br />
</div>
<img src="images/conectividade_protocolo.png" width="90%" height="85%" /><br /><br />
</div>
 
 <!-- passo 19 -->
<div id="passo19" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo18','passo19')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 19 de 19</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo19')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong>  <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
<div>
	Imprima o protocolo de envio e arquive-o. Pronto, missão cumprida! Seu arquivo Gfip foi enviado.<br /><br />
</div>
<img src="images/conectividade_protocolo2.png" width="70%" height="60%" /><br /><br />
</div>

 
</div>
</div>

<?php include 'rodape.php' ?>