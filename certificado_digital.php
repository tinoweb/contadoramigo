<?php 
  
  session_start();
  if (isset($_SESSION["id_userSecao"])) {
    include 'header_restrita.php';
  }
  else {
    $nome_meta = "certificado_digital";
    include 'header.php';
  } 

?>

<div class="principal">

  <h1>Certificado Digital</h1>
  <h2>Requisição junto aos Correios e Instalação</h2>
  O certificado digital é necessário para que você possa acessar os sites da<strong> Receita Federal</strong>, do <strong>Estado</strong> e do <strong>Município</strong> para emitir por conta própria as guias de seus impostos e declarações.  A seguir apresentamos o paso-a-passo para obteção do certificado E-CNPJ tipo A1 junto aos Correios que é a opção mais em conta (R$ 167,67).<br>
  <br>
  <br>  
  <!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Acesse a <a href="https://certificados.serpro.gov.br/arcorreiosrfb/" target="_blank">página de certificação dos Correios</a>. No menu superior, clique em <strong>Meu Certificado / Solicitar</strong>.<br />
<br />
<img src="images/certificado_digital/1.png" width="100%" height="70%" />
</div>


  <!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Selecione <strong>Pessoa Jurídica</strong> e escolha a <strong>opção A1</strong>. Você pode escolher outras, se quiser, mas a mais barata e recomendada pelo Contador Amigo é a A1.<br />
<br />
<img src="images/certificado_digital/2.png" width="100%" height="60%" />
</div>

  <!--passo 3 -->
<div id="passo3" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Preencha seus dados e clique <strong>Enviar</strong>.<br />
<br />
<img src="images/certificado_digital/3.png" width="100%" height="60%" />
</div>

  <!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Preencha o formulário. O campo <strong>CEI</strong> deve ficar em branco e em <strong>NIS</strong> coloque o seu número do PIS ou equivalente. Se não souber, ou não se lembrar, pode deixar em branco. <br />
Ao final, clique em <strong>Solicitar</strong>.<br />
<br />
<img src="images/certificado_digital/4.png" width="85%" height="100%" />
</div>

  <!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Pronto! Seu pedido de certificado foi enviado com sucesso. Anote o número de referência e o código de acesso. No pé da página, clique em <strong>Gerar Termo</strong>.<br />
<br />
<img src="images/certificado_digital/5.png" width="85%" height="85%" />
</div>

  <!--passo 6 -->
<div id="passo6" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Imprima o <strong>Termo de Titularidade</strong> que você acabou de baixar e junte os seguintes documentos:
<ul>
  <li>Ato constitutivo da empresa. Se for uma firma individual, trata-se do <strong>Requerimento de Empresário</strong> (aquele documento que você protocolou na junta Comercial quando abriu a empresa). Se for uma sociedade empresária, trata-se do <strong>Contrato Social</strong>;</li>
<li>Documento de Identidade ou Carteira de Habilitação do representante legal (o mesmo documento que você cadastrou no requerimento);</li>
<li>CNPJ da empresa. Imprima-o <a href="http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/cnpjreva_solicitacao.asp" target="_blank">aqui</a>;</li>
<li>Cartão do Pis ou Nit (apenas se você informou este número no requerimento);</li>
<li>Comprovante de residência do representante legal (conta de luz, gás ou telefone).</li>
</ul>

Com tudo na mão, agende uma visita a uma das <a href="http://www.correios.com.br/para-sua-empresa/comunicacao/certificados-digitais/agencias-credenciadas/agencias-credenciadas/" target="_blank">agências credenciadas</a> dos Correios.<br />
<br />
<div class="quadro_branco"> <span class="destaque">IMPORTANTE: </span><strong>leve os originais de todos estes documentos acompanhados de uma cópia simples</strong>. Tem também uma &quot;pegadinha&quot;: se a data de emissão do seu documento de identificação for há mais de 5 anos, você precisará levar uma foto 3x4 recente, caso contrário o requerimento não será aceito.</div>
</div>


  <!--passo 7 -->
<div id="passo7" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Muito bem, depois de levar os documentos aos Correios, você deve aguardar o email de confirmação, informando que seu certificado encontra-se disponível para download. Quando a mensagem chegar, acesse novamente a <a href="https://certificados.serpro.gov.br/arcorreiosrfb/" target="_blank">página de certificação dos Correios</a> e, desta vez, clique em meu <strong>Certificado / Instalar.</strong><br>
<br>
ATENÇÃO: para  baixar o certificado, você já precisa estar o Java instalado em sua máquina. Para isso siga nosso <a href="https://www.contadoramigo.com.br/java_tutorial.php">Tutorial de Configuração do Java. </a><br />
<br />
<img src="images/certificado_digital/7.png" width="100%" height="70%" alt=""/><br />
<br />
</div>


  <!--passo 8 -->
<div id="passo8" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 

Você deverá então preencher o <strong>Número de Referência</strong> e o <strong>Código de Acesso</strong> (gerados no passo 5, quando você enviou o formulário) e também a senha (gerada no passo 4). Clique no botão <strong>Continuar</strong>.
<br />
<br />
<img src="images/certificado_digital/8.png" width="100%" height="60%" alt=""/>
</div>

  <!--passo 9 -->
<div id="passo9" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo9')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Será apresentada a tela “<strong>Instalação do Certificado – Geração do Par de Chaves/Certificado</strong>. Em <strong>PIN do Certificado</strong> escolha um número com 6 algarismos e clique em <strong>Salvar Certificado</strong>. Vão aparecer algumas janelas do Java solicitando sua permissão. Autorize tudo, Em seguida aparecerá a mensagem<strong> Obtendo Certificado</strong>. Pode demorar até 3 minutos. Se não funcionar, experimente com outro navegador. <br>
<br>
Quando a mensagem de sucesso for exibida, isso significa que o arquivo de instalação do certificado foi baixado em seu micro. O nome aparecerá escrito também. Procure-o  em &quot;Pesquisar na Web e no Windows&quot; (no rodapé à esquerda da tela)<strong>. </strong>Após encontrá-lo, dê duplo clique e confirme. Pronto! Seu certificado está instalado.  <br>
<br>
Se quiser instalá-lo em outro computador, basta copiá-lo para o outro micro e dar o duplo clique novamente. Você pode mover o arquivo para um pasta de sua preferência. Isso não afetará o uso do certificado.<br />
<br />
<img src="images/certificado_digital/9.png" width="100%" height="80%" alt=""/><br />
</div>

<!--passo 10 -->
<div id="passo10" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo10')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 10 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo10')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Nesse ponto, você já está em condições de acessar o  <a href="https://conectividade.caixa.gov.br/" target="_blank">Conectividade Social</a> (necessário para o envio da Gfip) e também o site de sua nota fiscal eletrônica. Isto só será possível, porém, com o <strong>Internet Explorer</strong> e o <strong>Google Chrome</strong>. Se quiser utilizar o certificado no <strong>Firefox</strong>, você deverá fazer a instalação no próprio navegador com a cópia de segurança recém-gravada. Para isso, siga <a href="certificado_firefox.php">este tutorial</a>.<br />
<br />
Muito bem,  agora falta apenas a instalação da <strong>Cadeia de Certificados do Site da Receita Federal</strong>, para que você possa acessar também o sistema  do <strong>Simples Nacional </strong>no portal do E-CAC. Este processo é bem rápido. Clique nos links a seguir para baixar os certificados. 
<ul>
<li><a href="http://www.receita.fazenda.gov.br/publico/Certificados/icpbrasilv2.cer">ICP-Brasil v2 </a></li>
<li><a href="http://www.receita.fazenda.gov.br/publico/Certificados/acrfbv3.cer">Autoridade Certificadora da Secretaria da Receita Federal v3 </a></li>
<li><a href="http://www.receita.fazenda.gov.br/publico/Certificados/acserprorfbv3.cer">Autoridade Certificadora do SERPRORFB v3</a></li>
</ul>
Depois de baixados, clique com o botão direito do mouse em cada um dos arquivos e vá em <strong>Instalar Certificado</strong>. Siga as etapas, aceitando as opções sugeridas. Pronto! Faça o teste e veja se está conseguindo entrar no <a href="https://cav.receita.fazenda.gov.br/scripts/CAV/login/login.asp" target="_blank">E-CAC</a>. Missão cumprida!<br />
<br />
</div>

</div>
<?php include 'rodape.php' ?>
