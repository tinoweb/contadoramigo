<?php 

session_start();
if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
else {
	$nome_meta = "enquadramento_simples";
	include 'header.php';} 
?>


<div class="principal">
  <h1>Como aderir ao Simples Nacional</h1>
  
	<h2>Veja aqui como solicitar o enquadramento no Simples Nacional.</h2>
	
  A opção pelo Simples Nacional só pode ser realizada em janeiro. Uma vez aprovada, produz efeitos desde o primeiro dia do ano.
    Para empresas em início de atividade, o prazo de solicitação  é de até 30 dias da  abertura (a partir da última inscrição na prefeitura ou no estado). Neste caso, a opção começa a valer a partir da abertura do CNPJ.<br />
    <br />
    É preciso estar em dia com as obrigações fiscais para que o pedido de enquadramento no simples seja aceito. Faça o seu o quanto antes, assim você terá tempo de regularizar qualquer pendência apontada pela Receita durante a tramitação do processo. <br />
    <br />
    Você pode inclusive fazer o <strong>agendamento antecipado</strong> da opção pelo Simples Nacional 
    no período de 1º de novembro até o penúltimo dia útil de dezembro. Ao fazer este agendamento, a Receita  faz a análise prévia de sua situacão, dando-lhe mais tempo para para regularizar-se.<br />
    <br />
    Bem, mas vamos ao que interessa: para encaminhar seu pedido, siga os procedimentos abaixo.<br />
    <br />
    Acesse o <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/Servicos/Grupo.aspx?grp=4" target="_blank">Portal do Simples Nacional</a> e vá na opção: <strong>Solicitação de Opção pelo Simples Nacional</strong>. Se ainda estiver no prazo para solicitar o agendamento, você pode ir em<strong> Agendamento da Opção pelo Simples Nacional</strong>. Observe na imagem abaixo que há duas formas de entrar nestas opções: com o seu certificado digital (e-cnpj) ou através de um código de acesso. <br />
    <br />
    Se  ainda não tiver o certificado digital, entre via código de acesso. Você poderá gerá-lo na hora, desde que possua o número do recibo de sua última declaração de IR pessoa física.<br />
    <br />
    Siga as instruções na própria página e envie o seu pedido. Retorne a essa página diariamente para saber se houve alguma resposta da Receita. Para tal, você deverá clicar em <strong>Acompanhamento da Formalização da Opção pelo Simples Nacional</strong>.<br />
  <br />
  Pronto é isso! <br>
<br>

  Depois que o seu pedido for &quot;deferido&quot;, isto é, aceito. Você já estará automaticamente incluído no Simples e poderá utilizar o <strong>Contador Amigo</strong> para cumprir com todas as suas obrigações fiscais.
  <br>
<br>

  <img src="images/enquadramento_simples/1.png"  alt="Simples Nacional - Opção" tile="enquadramento_simples.php" style="width: 100%; max-width: 966px" />





<?php include 'rodape.php' ?>