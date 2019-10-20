<?php include 'header_restrita.php' ?>

<div class="principal">
  <script>
	  
	function aviso1() { 
		if (document.getElementById('continuacao').style.display == "block") {document.getElementById('continuacao').style.display = "none"}
		document.getElementById('agendar_visita').style.display = 'block'
	}
		
	function aviso2() { 
		if (document.getElementById('agendar_visita').style.display == "block") {document.getElementById('agendar_visita').style.display = "none"}
		document.getElementById('continuacao').style.display = 'block'
	} 
	  
	function aviso3() { 
	if (document.getElementById('pergunta_recibo').style.display == "block") {document.getElementById('pergunta_recibo').style.display = "none"}
		document.getElementById('continuacao').style.display = "block"
	} 
	  
	function aviso4() { 
	if (document.getElementById('continuacao').style.display == "block") {document.getElementById('continuacao').style.display = "none"}
	document.getElementById('pergunta_recibo').style.display = "block"
	}
	  
	function aviso5() { 
	if (document.getElementById('optante_sim').checked & document.getElementById('funcionarios_nao').checked & document.getElementById('servicos_sim').checked){	
	document.getElementById('orcamento').style.display = "block"
	} else {
		this.form().submit
	}
	}
</script>

  <H1>Serviços Avulsos</H1>
  <H2>Regularização de Empresa</H2>

 O primeiro passo para a regularização de uma empresa é conhecer todas as suas pendências. Para isso é preciso consultar a situação da empresas junto à União, ao estado e ao município. Podemos fazer esta verificação gratuitamente para você, mas é necessário que a empresa tenha um <strong>certificado digital E-CNPJ, ou que o sócio/proprietário disponha do número do recibo de entrega de sua última declaração de IR pessoa física</strong>, para que possa gerar um código de acesso ao site da Receita Federal (E-CAC).<br><br>
 


<form action="enviaremos_orcamento.php">

<div style="margin-bottom: 10px; font-weight: bold">Tem um certificado digital E-CNPJ?</div>
<div style="margin-bottom: 20px">
<input type="radio" name="certificado"  onClick="javascript:aviso3()"> Sim<br>
<input type="radio" name="certificado"  onClick="javascript:aviso4()"> Não<br>
</div>

<div id="pergunta_recibo" style="display: none">
<div style="margin-bottom: 10px; font-weight: bold">O sócio responsável e possui o recibo de entrega de suas 2 últimas declarações de IR pessoa física?</div>
<div style="margin-bottom: 5px"><input type="radio" name="recibo_IR" onClick="javascript:aviso2(); javascript:abreDiv('recibos')"> Sim</div>

<div id="recibos" style="margin-bottom: 20px; display: none">
<div style="margin-bottom: 5px">
Nº recibo 1: <input type="text" name='numero_recibo1' size="17" maxlength="17" style="margin-right: 10px"> Ano: <input type="text" name='ano_recibo1' size="10" maxlength="10">
</div>
<div>
Nº recibo 2: <input type="text" name='numero_recibo2'  size="17" maxlength="17" style="margin-right: 10px"> Ano: <input type="text" name='ano_recibo2' size="10" maxlength="10">
</div>
</div>	
<div style="margin-bottom: 20px"><input type="radio" name="recibo_IR"  onClick="javascript:aviso1(); javascript:fechaDiv('recibos')"> Não</div>
</div>


<div class="quadro_branco" id="agendar_visita" style="display: none"><span class="destaque">ATENÇÃO:</span> Se você não certificado, nem os recibos do IR do sócio, antes de prosseguir com este pedido de regularização, deverá primeiramente <a href="https://www.receita.fazenda.gov.br/Aplicacoes/SSL/ATBHE/SAGA/RegrasAgendamento.aspx" target="_blank">agendar uma visita</a> a uma agência da Receita Federal e solicitar o levantamento de suas pendências federais, depois, se for prestador de serviços, agendar uma visita à prefeitura de sua cidade, para conhecer as pendências municipais e, se for comércio, deverá comparecer ao Posto Fiscal para conhecer as pendências estaduais. É muito trabalho, convém ao invés disso, adquirir primerio um certificado digital. Assim poderá fazer tudo online. Assinantes do Contador Amigo podem adquirir o Certificado Digital E-CNPJ A1 pela Valid Certificadora com um super desconto: apenas R$ 189,75, em 3 x sem juros. <a href="https://onedrive.live.com/edit.aspx/Favoritos/Documentos/Bloco%20de%20anota%c3%a7%c3%b5es%20de%20Vitor?cid=67cd8c2a2c15c196&id=documents&wd=target%28Contador%20Amigo.one%7C4CED9EAF-CA5F-4572-BACC-8A5A8BD45997%2FAtendimento%7C95E270D7-F14A-4A23-891D-92CE7234C7A4%2F%29" target="_blank">Solicite agora mesmo o seu</a>.</div>

	
<div id="continuacao" style="display: none; margin-bottom: 20px">
	
<div style="margin-bottom: 10px; font-weight: bold">Optante pelo Simples?</div>	
<div style="margin-bottom: 20px">
<input type="radio" name="optante_simples" id="optante_sim"> Sim<br>
<input type="radio" name="optante_simples"> Não<br>
</div>

<div style="margin-bottom: 10px; font-weight: bold">A regularização envolve funcionários?</div>	
<div style="margin-bottom: 20px">
<input type="radio" name="funcionarios"> Sim<br>
<input type="radio" name="funcionarios" id="funcionarios_nao"> Não<br>
</div>

<div style="margin-bottom: 10px; font-weight: bold">Qual o tipo de atividade desenvolvida?</div>
<div style="margin-bottom: 20px">
<input type="radio" name="tipo" id="servicos_sim"> Prestação de Serviços<br>
<input type="radio" name="tipo"> Comércio<br>
<input type="radio" name="tipo"> Indústria<br>
</div>

<div class="tituloAzul" style="margin-bottom: 20px">Dados da empresa a ser regularizada</div>

	<div style="float: left; text-align: right; width: 120px; margin-right: 10px">CNPJ:</div><div style="float: left; text-align: left"><input type="text" name="cnpj" size="18" maxlength="18"></div>
	<div style="clear: both; height: 10px"></div>
	
	<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Sócio Responsável: </div><div style="float: left; text-align: left"><input type="text" name="socio_resp" size="30" maxlength="30"></div>
	<div style="clear: both; height: 10px"></div>
	
	<div style="float: left; text-align: right; width: 120px; margin-right: 10px">CPF:</div><div style="float: left; text-align: left"><input type="text" name="cpf_resp" size="14" maxlength="14"></div>
	<div style="clear: both; height: 10px"></div>
	
	<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Data de Nascimento: </div><div style="float: left; text-align: left"><input type="text" name="nascimento_socio_resp" size="12" maxlength="12"> (do sócio-responsável)</div>
	<div style="clear: both; height: 10px"></div>
	
	<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Tel. para contato: </div><div style="float: left; text-align: left; margin-right: 10px;"><input type="text" name="ddd" size="3" maxlength="3"></div><div style="float: left; text-align: left"><input type="text" name="tel_contato" size="9" maxlength="9"></div>
	<div style="clear: both; height: 10px"></div>
	
	<div style="float: left; text-align: right; width: 120px; margin-right: 10px">Possui WhatsApp?</div><div style="float: left; text-align: left; margin-right: 10px"><input type="radio" name="whatsapp" onClick="javascript:abreDiv('numero_whatsapp')"> Sim</div><div id="numero_whatsapp" style="float:left; display: none"><input type="text" name='numero_whatsapp' size="14" maxlength="14"></div>
	<div style="clear: both; height: 10px"></div>
	
	<div style="float: left; text-align: right; width: 120px; margin-right: 10px">E-mail:</div><div style="float: left; text-align: left"><input type="text" name="e-mail"></div>
	<div style="clear: both; height: 20px"></div>
	<input type="button" value="prosseguir" onClick="aviso5()">
</div>
	

	<div id="orcamento" style="display:none">
		<div class="tituloVermelho">Tarifa Padrão</div>
	As características de sua empresa nos permitem enquadrá-la em nosso <strong>custo mínimo de regularização, que é de R$ 300</strong>. O serviço inclui:
	<ul>
		<li>Regularização do Simples</li>
		<li>Regularização da Gfip</li>
		<li>Envio da Defis e RAIS em atraso</li>
		<li>Declarações eletrônicas de serviços (se houver em seu município)</li>
		<li>Taxas municipais</li>
	</ul>
	
		<div class="quadro_branco"><strong>Observação:</strong> Em municípios onde não seja possivel a consulta e geração de guias online, você precisará se dirigir ao órgão por nós indicado para efetuar concluir o processo de regularização.</div>

<input type="button" value="contratar" onClick="paginadepagamento">
	</div>
</form>	
</div>

<?php include 'rodape.php' ?>
