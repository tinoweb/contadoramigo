<?php include 'header_restrita.php'; ?>

<?
if(isset($_GET['passo']) && $_GET['passo'] != ''){
?>
	<script>
	$(document).ready(function(e){
		abreDivFechaOutro('passo<?=$_GET['passo']?>','passo1')
	});
  </script>
<?
}
?>

<div class="principal">

<!--Dica Certificado eletronico-->

<h1>Alteração Contratual</h1>

<h2>Orientação para geração do DBE</h2>

Para gerar o Documento Básico de Entrada (DBE), você deve usar preferenciamente o <strong>Internet Explorer</strong>, assim conseguirá-la trasmiti-lo com seu Certificado Digital. Se estiver em um Mac, use o Firefox, mas o plugin do java deve estar habilitado. <br>
<br>
	Antes de iniciar, <a href="java_tutorial.php">atualize e configure seu Java</a> e <a href="ie_tutorial.php">configure o Internet Explorer</a>. Depois, acesse o <a href="https://www38.receita.fazenda.gov.br/redesim/" target="_blank">Cadastro Sincronizado Nacional</a> e proceda como a seguir:<br /><br>

 

 <!-- passo 1 -->
<div id="passo1" style="display:block"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo17','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br />
<br />
Clique em<strong> Preencher nova solicitação</strong>, ou <strong>Recuperar solicitação</strong> para voltar a um processo inciado anteriormente. Aguarde um pouco até aparecerem os campos seguintes. Escolha o<strong> Estado (UF)</strong>, o <strong>Município</strong> e, no ato de cadastro, selecione <strong>Alteração</strong>. Informe se estiver alterando o endereço para outra UF/Município. Clique em <strong>Solicitar.</strong>Para os estados e Minas, Alagoas e Pará, será necessário informar o protocolo do pedido de viabilidade (solictado na Junta), em determinadas alterações. <br />

    <br />
    
  <img src="images/dbe1.png" width="60%" height="75%" style="border-style:solid; border-width:1px; border-color:#ccc" /> </div>
    

<!-- passo 2 -->
<div id="passo2" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />   
  
Você será direcionado ao sitema <strong>Coleta Web</strong>. Digite os caracteres de segurança e clique no botão <strong>Prosseguir</strong>. <br />
  <br />
  <img src="images/dbe2.png" width="45%" height="30%" /> </div> 
  



<!-- passo 3 -->
<div id="passo3" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Digite o número do CNPJ e clique em <strong>Iniciar</strong>.<br />

    <br />
    
  <img src="images/dbe3.png" width="75%" height="20%" style="border-style:solid; border-width:1px; border-color:#ccc"  /> </div>

 <!-- passo 4 -->
<div id="passo4" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
A sua <strong>chave de acesso</strong> será gerada. Faça o download ou imprima o recibo. Os códigos de acesso serão usados para você acessar este documento no futuro.  Em seguida clique  em <strong>Eventos</strong> para especificar as alterações a serem realizadas no contrato social.<br />

    <br />
    
  <img src="images/dbe4.png" width="100%" height="60%" /> </div>
  
  <!-- passo 5 -->
<div id="passo5" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Clique na caixa <strong>Dados Cadastrais / Situações Especiais.</strong> Logo abaixo, em <strong>Eventos (Motivo do Preenchimento)</strong> selecione os eventos (isto é, os tipos de alterações a serem feitas no contrato social) e informe a <strong>Data</strong> (pode ser a data atual). Se você deseja incluir/excluir algum sócio, não encontrará esta opção na lista de eventos. Neste caso, o procedimento é um pouco diferente. Veja como fazer a <a href="orientacoes_dbe_altera_socio.php">inclusão/exclusão de sócios</a>. <br /><br />
    
  <img src="images/dbe5.png" width="100%" height="60%" /> </div>
  
  <!-- passo 6 -->
<div id="passo6" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Adicione tantos eventos quantos forem as alterações a serem efetuadas no contrato social.<br />

    <br />
    
  <img src="images/dbe6.png" width="100%" height="60%" /> </div>
  
  <!-- passo 7 -->
<div id="passo7" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Dependendo dos eventos escolhidos, uma lista de itens será exibida no menu da esquerda (FCPJ). Ao clicar em cada item da lista, serão exibidos campos para preenchimento. O primeiro deles será sempre o de <strong>Identificação</strong> onde você deverá especificar a Natureza Jurídica da sua empresa. No seu caso, a Natureza Jurídica poderá ser:
<ul>
<li>Sociedade Empresária Limitada</li>
<li>Empresário (Individual)</li>
<li>Sociedade Simples Limitada</li>
<li>Sociedade Simples Pura (se tiver registro na OAB)</li>
<li>Empresa Individual de Responsabilidade Limitada (de Natureza Empresária)</li>
<li>Empresa Individual de Responsabilidade Limitada (de Natureza Simples)</li>
</ul>
Se estiver na dúvida, sobre qual a natureza jurídica de sua empresa veja em seu <a href="http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/cnpjreva_solicitacao.asp" target="_blank">certificado do CNPJ</a>. <br /><br />
    
  <img src="images/dbe7.png" width="100%" height="60%" /> </div>
  
  <!-- passo 8 -->
<div id="passo8" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Ao definir a Natureza Jurídica, será exibida uma caixa com a seguinte pergunta: &quot;Seu ato constitutivo/alterador já foi registrado no respectivo órgão de registro?&quot;, responda <strong>Não</strong><br />

    <br />
    
  <img src="images/dbe8.png" width="100%" height="60%" /> </div>
  
  <!-- passo 9 -->
<div id="passo9" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo9')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Caso apareça uma segunda janela com a pergunta: &quot;Deseja utilizar o convênio para deferimento pela Junta Comercial/Cartório?&quot;, responda <strong>Sim</strong>.<br />

    <br />
    
  <img src="images/dbe9.png" width="100%" height="60%" /> </div>
  
  <!-- passo 10 -->
<div id="passo10" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo10')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 10 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo10')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Continue clicando nos itens do menu lateral esquerdo (FCPJ) até preencher todos os campos que aparecerem.<br />

    <br />
    
  <img src="images/dbe10.png" width="100%" height="60%" /> </div>
  
  <!-- passo 11 -->
<div id="passo11" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo11')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 11 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo11')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Para ter certeza de que não esqueceu de nenhum, clique em Verificar Pendências no menu superior. Na verificação, podem aparecer alguns campos opcionais em branco. Não é necessário preenchê-los.<br />

    <br />
    
  <img src="images/dbe11.png" width="100%" height="60%" /> </div>
  
  <!-- passo 12 -->
<div id="passo12" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo12')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 12 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo13','passo12')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Após verificar as pendências, clique em <strong>Resumo do Documento</strong> para conferir se todos os campos estão preenchidos corretamente. <br />

    <br />
    
  <img src="images/dbe12.png" width="100%" height="60%" /> </div>
  
  <!-- passo 13 -->
<div id="passo13" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo13')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 13 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo14','passo13')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique em <strong>Finalizar Preenchimento. </strong>Não havendo mais pendências, uma caixa se abrirá, exibindo o <strong>Botão de Transmissão</strong>. Se você marcar a opção <strong>Assinar com certificado digital</strong>  não precisará reconhecer firma do documento para enviá-lo depois à Junta Comercial.<br />

    <br />
    
  <img src="images/dbe13.png" width="100%" height="60%" /> </div>
  
  <!-- passo 14 -->
<div id="passo14" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo13','passo14')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 14 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo15','passo14')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Será gerado o <strong>Recibo de Entrega do Documento. </strong>Imprima-o e depois clique no link indicado (<a href="http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/fcpj/consulta.asp" target="_blank">página da RFB</a>) para acompanhar o processamento do documento.<br />

    <br />
    
  <img src="images/dbe14.png" width="100%" height="60%" /> </div>
  
  <!-- passo 15 -->
<div id="passo15" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo14','passo15')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 15 de 17</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo16','passo15')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Preencha os campos com os dados da Chave de Acesso que lhe foram fornecidos no início do preenchemento e clique em Consultar.
<br />

    <br />
    
  <img src="images/dbe15.png" width="100%" height="60%" /> </div>
  
  <!-- passo 16 -->
<div id="passo16" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo15','passo16')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
    <span class="tituloVermelho">Passo 16 de 17</span> 
    <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo17','passo16')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br />
    <br />
    Se a tela a baixo aparecer, é porque seu pedido ainda não foi registrado na base de dados da Fazenda. Volte a consultar após alguns minutos.<br />
    
    <br />
    
    <img src="images/dbe16.png" width="100%" height="60%" /> </p>
</div>
  
  <!-- passo 17 -->
<div id="passo17" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo16','passo17')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 17 de 17</span>
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo17')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Se aparecer a tela abaixo, isso significa que seu documento já está disponível. Se você transmitiu o DBE usando seu certificado digital, ao invés de estar escrito "<strong>clique aqui para imprimir o DBE"</strong>, aparecerá <strong>"clique aqui para imprimir o protocolo FCPJ"</strong>, isto porque, neste caso, o DBE foi direto para o órgão de registro.<br>
<br>
Junte o DBE ou Protocolo aos demais documentos e leve tudo até a Junta Comercial, ou a uma unidade da Receita Federal conforme indicado do documento.<br />
<div class="quadro_branco">
<strong>ATENÇÃO:</strong> é possível que, ao invés  da tela abaixo, apareça  outra informando que o DBE foi negado. Refaça do pedido, regularizando as pendências relacionadas e transmita-o  novamente.</div>
<br />
    
  <img src="images/dbe17.png" width="100%" height="60%" /> </div>
  
  

   
</div>

<?php include 'rodape.php' ?>