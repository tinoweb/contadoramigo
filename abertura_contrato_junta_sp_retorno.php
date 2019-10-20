<?php include 'header_restrita.php' ?>

<script language="JavaScript">
function validaForm(form) {	
	if (form.txtEmail.value==''){
		alert('Digite o Email.');
		form.txtEmail.focus();
	}else{		
		if (form.txtSenha.value==''){
			alert('Digite a Senha.');
			form.txtSenha.focus();
		}else{		
			form.submit();
		}
	}
}
</script>

<div class="principal">

<h1>Abertura de empresa</h1>

<h2>Via Rápida Empresa - Retornar ao processo após pagar as taxas</h2>


<!--passo 1 -->
<div id="passo1" style="display:block">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
  <span class="tituloVermelho">Passo 1 de 7</span> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
<br />
Muito bem, você já pagou as taxas a agora quer seguir com o processo de abertura ou alteração da sua empresa. Acesse novamente o  <a href="https://www.jucesp.sp.gov.br/VRE/" target="_blank">Via Rápida Empresa</a>  e selecione a opção<strong> Retornar a um processo previamente inciado</strong>.<br />
<br />
<img src="images/junta/retorno1.png" width="100%" height="45%" alt=""/>
</div>

<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Use seu certificado digital. Clique em <strong>Acessar</strong> e siga em frente.<strong><br />
</strong><br />
<img src="images/junta/retorno2.png" width="97%" height="40%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>

<!--passo 3 -->
<div id="passo3" style="display:none">
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
No menu superior, selecione a opção <strong>Consulta de Processos</strong> e clique em <strong>Lista de Processos</strong>.<br />
</strong><br />
<img src="images/junta/retorno3.png" width="100%" height="40%" alt=""/><br />
</div>

<!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Selecione a opção <strong>Processos Enviados</strong>. Em seguida clique em <strong>Pesquisar</strong>. Não é necessário preencher os demais campos. <br />
<br />
<img src="images/junta/retorno4.png" width="100%" height="40%" alt=""/><br />
</div>

<!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Os processos iniciados por você aparecerão listados. Clique no ícone <img src="images/impressora.png" width="14" height="16" alt="Impressão"/>. <br />
<br />
<img src="images/junta/retorno5b.png" width="100%" height="40%" alt=""/><br />
</div>


<!--passo 6-->
<div id="passo6" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
      <br /> 
      A tela com todos os arquivos para impressão será exibida. Mas antes você precisará  preencher alguns campos. Clique em cada um dos  ícones <img src="images/junta/edicao.png" alt="" width="13" height="15" /> <strong>Ação</strong> e preencha os dados solicitados. Observe que, ao fazê-lo, a coluna validação mudará de <img src="images/pendencia.png" width="16" height="16" alt=""/> <b>Pendente</b> para <img src="images/validado.png" width="16" height="16" alt=""/> <b>Validado</b>. Em seguida clique nos ícones <img src="images/impressora.png" alt="" width="14" height="16" /> e imprima todos os formulários.<br />
      <br />
<img src="images/junta/retorno12.png" width="100%" height="80%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>

<!--div 7-->
<div id="passo7" style="display:none">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
  <span class="tituloVermelho">Passo 7 de 7</span> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br /><br /> 
  
Agora só falta fazer o Documento Básico de Entrada (DBE) no Portal da RedeSIM. Nele você estará informando à Receita Federal a abertura da Empresa, para gerar seu CNPJ. Acesse nosso <a href="abertura_dbe.php">tutorial para geração do DBE.</a> <br />
<br />
Finalmente, quando estiver com todos os documentos em mãos, dirija-se à Junta Comercial para dar entrada ao pedido. Consulte os <a href="http://www.institucional.jucesp.sp.gov.br/institucional_locais.php" target="_blank">locais e horários de atendimento</a>. Se sua empresa for uma sociedade limitada, não esqueça de anexar também  3 vias do <a href="http://www.jucespciesp.com.br/modelo/Modelo%20Contrato%20Social.doc" target="_blank">Contrato Social</a>. Se estiver abrindo um Eireli, ao invés do contrato social, deve ser anexado o <a href="https://www.contadoramigo.com.br/downloads/modelo_de_ato_constituvico_do_eireli.doc" target="_blank">ato constitutivo</a>. Em caso de alteração do quadro societário, junte também cópia autenticada do documento de identidade de todos os sócios admitidos ou administradores. <br />
<br />
Aguarde alguns dias e <a href="abertura_consulta.php">consulte se o processo já foi analisado pela Junta</a>.<br>
<br>
Quando o processo aparecer como deferido (aprovado), volte à Junta e retire-o. Verifique se entre os documentos há uma Declaração na qual você afirma estar ciente de que "não poderá exercer atividade na empresa sem que obtenha primeiro o parecer municipal sobre a viabilidade de sua instalação". Isto ocorre quando seu município ainda não tiver convênio com o VRE para gerar a licença de funcionamento automaticamente.  Aliás, a cidade de São Paulo não tem convênio.<br>
<br>
Se for este o seu caso, você então terá mais uma etapa a fazer: deve <a href="https://www.jucesp.sp.gov.br/vre/" target="_blank">entrar novamente no VRE</a> e desta vez clicar em <strong>Licenciamento</strong>. Atenção: este módulo só pode ser acessado com o Certificado E-CNPJ de sua própria empresa. Em caso de dúvida no preenchimento, consulte o <a href="downloads/manual_viarapida.pdf">manual do VRE</a> (a partir da página 93). Se a sua atividade for de baixo risco, a licença sairá na hora, se for de médio ou alto risco, poderão ser solicidatos laudos da CETESB, Vigilância sanitária e do Corpo de Bombeiros.<br>
<br>

Ufa! realizadas estas etapas, já estará tudo em ordem perante o <strong>Estado de São Paulo</strong> e a <strong>União</strong>, mas faltará ainda fazer a <a href="abertura_empresa.php#prefeitura">inscrição na prefeitura</a>.<br>
  </div>




</div>
<?php include 'rodape.php' ?>
