<div nome="registro-header-de-arquivo">
	<!-- Dados de controle -->
	<p class="par"    	title="codigo-do-banco-na-compensacao" 		inicio="1" 		fim="3" 	digitos="3" 	>	<?php echo $this->codigo_do_banco_na_compensacao; ?>	</p>
	<p class="impar"   	title="lote-de-servico"						inicio="4" 		fim="7" 	digitos="4" 	>	<?php echo $this->lote_de_servico; ?>	</p>
	<p class="par"   	title="tipo-de-registro"					inicio="8" 		fim="8" 	digitos="1" 	>	<?php echo $this->tipo_de_registro; ?>	</p>
	<!-- Dados do serviço-->
	<p class="impar"   	title="uso-exclusivo-febraban-cnab"	 		inicio="9" 		fim="17" 	digitos="9" 	>	<?php echo brancos(9) ?>	</p>
	<!-- Dados da empresa -->
	<p class="par"   	title="tipo-de-inscricao-da-empresa"		inicio="18"		fim="18" 	digitos="1" 	>	<?php echo $this->tipo_de_inscricao_da_empresa; ?>	</p>
	<p class="impar"   	title="numero-de-inscricao-da-empresa"		inicio="19"		fim="32" 	digitos="14" 	>	<?php echo $this->numero_de_inscricao_da_empresa; ?>	</p>
	<p class="par"   	title="codigo-do-convenio-no-banco"			inicio="33" 	fim="52" 	digitos="20" 	>	<?php echo $this->codigo_do_convenio_no_banco; ?>	</p>
	<p class="impar"   	title="agencia-mantenedora-da-conta"		inicio="53"		fim="57" 	digitos="5" 	>	<?php echo $this->agencia_mantenedora_da_conta; ?>	</p>
	<p class="par"   	title="digito-verificador-da-agencia"		inicio="58"		fim="58" 	digitos="1" 	>	<?php echo $this->digito_verificador_da_agencia; ?>	</p>
	<p class="impar"   	title="numero-da-conta-corrente"			inicio="59"		fim="70" 	digitos="12" 	>	<?php echo $this->numero_da_conta_corrente; ?>	</p>
	<p class="par"   	title="digito-verificador-da-conta"			inicio="71"		fim="71" 	digitos="1" 	>	<?php echo $this->digito_verificador_da_conta; ?>	</p>
	<p class="impar"   	title="digito-verificador-da-ag-conta"		inicio="72"		fim="72" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<p class="par"   	title="nome-da-empresa"						inicio="73"		fim="102" 	digitos="30" 	>	<?php echo $this->nome_da_empresa; ?></p>
	<p class="impar"   	title="nome-do-banco"						inicio="102"	fim="132" 	digitos="30" 	>	<?php echo $this->nome_do_banco; ?>
	<p class="par"   	title="uso-exclusivo-febraban-cnab"			inicio="133" 	fim="142" 	digitos="10" 	>	<?php brancos(10) ?>	</p>
	<p class="impar"   	title="codigo-remessa-retorno"				inicio="143"	fim="143" 	digitos="1" 	>	<?php echo $this->codigo_remessa_retorno ?>	</p>
	<p class="par"   	title="data-de-geracao-do-arquivo"			inicio="144"	fim="151" 	digitos="8" 	>	<?php echo $this->data_de_geracao_do_arquivo; ?>	</p>
	<p class="impar"   	title="hora-de-geracao-do-arquivo"			inicio="152"	fim="157" 	digitos="6" 	>	<?php echo $this->hora_de_geracao_do_arquivo; ?>	</p>
	<p class="par"   	title="numero-seq-encial-do-arquivo"		inicio="158"	fim="163" 	digitos="6" 	>	<?php zeros(6) ?>	</p>
	<p class="impar"   	title="n-da-versao-do-layout-do-arquivo"	inicio="164"	fim="166" 	digitos="3" 	>	<?php zeros(3) ?>	</p>
	<p class="par"   	title="densidade-de-gravacao-do-arquivo"	inicio="167"	fim="171" 	digitos="5" 	>	<?php brancos(5) ?>	</p>
	<p class="impar"   	title="para-uso-reservado-do-banco"			inicio="172"	fim="191" 	digitos="20" 	>	<?php brancos(20) ?>	</p>
	<p class="par"   	title="para-uso-reservado-da-empresa"		inicio="192"	fim="211" 	digitos="20" 	>	<?php brancos(20) ?>	</p>
	<!-- CNAB -->
	<p class="impar"   	title="uso-exclusivo-febraban-cnab" 		inicio="211"	fim="240" 	digitos="29" 	>	<?php brancos(29) ?>	</p>
</div>

