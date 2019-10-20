<?php include 'header_restrita.php' ?>

<div class="principal">
<div class="minHeight">
<!--BALLOM Sefip -->
<div style="width:310px; position:absolute; margin-left:170px; margin-top:100px; display:none" id="sefip">
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

<h1>Impostos e Obrigações - Gfip</h1>

<h2>Atualização manual de tabelas no Sefip.</h2>

Faça o download do arquivo (tipo zip) de atualização das das tabelas do INSS <a href="http://www.caixa.gov.br/Downloads/fgts-sefip-grf/AUXILIAR_01_2018.zip">neste link</a>.
Descompacte-o. <br />
<br />
Você verá uma pasta com dois arquivos:
<ul>
<li>Auxiliar.txt</li>
<li>Auxiliar.001</li>
</ul>

Volte ao Sefip e tente atualizar novamente as tabelas, dessa vez manualmente. Para isso vá em <strong>Ferramentas / Carga Manual de Tabelas / Auxiliares - INSS / Manual</strong>. <br />
<br />
Uma janela se abrirá, para que você localize a pasta onde está o arquivo <strong>auxiliar.txt</strong>, selecione-o e clique &quot;abrir&quot;. <br />
<br />
Pronto, sua tabela foi atualizada!  Se aparecer a mensagem: <strong>&quot;Não existe tabela auxiliar atualizada para captura&quot;</strong>, é porque você já está com a tabela mais recente.<br />
<br />
<br />
<div style="background-color:#FFFFFF; border-color:#CCC; border-style:solid; border-width:1px; padding:10px"> <div class="destaque" style="margin-bottom:10px">IMPORTANTE:</div>
 <div>O outro arquivo, <strong>Auxiliar.001</strong>, não será carregado, mas deve estar na mesma pasta do arquivo auxiliar.txt, do contrário dará &quot;erro de lacre&quot;. <br />
Se aparecer a mensagem: &quot;<strong>Não existe tabela auxiliar atualizada para captura&quot;</strong>, não se preocupe. Isso significa que você já está com a tabela mais recente. </div></div>
</div>


</div>
</div>

<?php include 'rodape.php' ?>

