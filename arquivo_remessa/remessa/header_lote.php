<div nome="registro-header-de-lote">.
	<!-- Dados de controle -->
	<p class="par"    	title="codigo-do-banco-na-compensacao" 		inicio="1" 		fim="3" 	digitos="3" 	>	<?php echo $this->codigo_do_banco_na_compensacao; ?>	</p>
	<p class="impar"   	title="lote-de-servico"						inicio="4" 		fim="7" 	digitos="4" 	>	<?php echo $this->lote_de_servico; ?>	</p>
	<p class="par"   	title="tipo-de-registro"					inicio="8" 		fim="8" 	digitos="1" 	>	<?php echo $this->tipo_de_registro; ?>	</p>
	<!-- Dados do serviço-->
	<p class="impar"   	title="tipo-de-operacao"			 		inicio="9" 		fim="9" 	digitos="1" 	>	<?php echo $this->tipo_de_operacao; ?>	</p>
	<p class="par"   	title="tipo-de-servico"						inicio="10"		fim="11" 	digitos="2" 	>	<?php echo $this->tipo_de_servico; ?>	</p>
	<p class="impar"   	title="uso-exclusivo-febraban-cnab"			inicio="12" 	fim="13" 	digitos="2" 	>	<?php brancos(2) ?>	</p>
	<p class="par"   	title="n-da-versao-do-layout-do-lote"		inicio="14" 	fim="16" 	digitos="3" 	>	<?php echo $this->n_da_versao_do_layout_do_lote; ?>	</p>
	<p class="impar"   	title="Uso Exclusivo FEBRABAN/CNAB"			inicio="17"		fim="17" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<!-- Dados da empresa -->
	<p class="par"   	title="tipo-de-inscricao-da-empresa"		inicio="18"		fim="18" 	digitos="1" 	>	<?php echo $this->tipo_de_inscricao_da_empresa; ?>	</p>
	<p class="impar"   	title="n-de-inscricao-da-empresa"			inicio="19"		fim="33" 	digitos="15" 	>	<?php echo $this->n_de_inscricao_da_empresa; ?>	</p>
	<p class="par"   	title="codigo-do-convenio-no-banco"			inicio="34" 	fim="53" 	digitos="20" 	>	<?php echo $this->codigo_do_convenio_no_banco; ?>	</p>
	<p class="impar"   	title="agencia-mantenedora-da-conta"		inicio="54"		fim="58" 	digitos="5" 	>	<?php echo $this->agencia_mantenedora_da_conta; ?>	</p>
	<p class="par"   	title="digito-verificador-da-conta"			inicio="59"		fim="59" 	digitos="1" 	>	<?php echo $this->digito_verificador_da_conta; ?>	</p>
	<p class="impar"   	title="numero-da-conta-corrente"			inicio="60"		fim="71" 	digitos="12" 	>	<?php echo $this->numero_da_conta_corrente; ?>	</p>
	<p class="par"   	title="digito-verificador-da-conta"			inicio="72"		fim="72" 	digitos="1" 	>	<?php echo $this->digito_verificador_da_conta_2; ?>	</p>
	<p class="impar"   	title="digito-verificador-da-ag-conta"		inicio="73"		fim="73" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<p class="par"   	title="nome-da-empresa"						inicio="74"		fim="103" 	digitos="30" 	>	<?php echo $this->nome_da_empresa; ?>	</p>
	<p class="impar"   	title="mensagem-1"							inicio="104"	fim="143" 	digitos="40" 	>	<?php brancos(40) ?>	</p>
	<p class="par"   	title="mensagem-2"							inicio="144" 	fim="183" 	digitos="40" 	>	<?php brancos(40) ?>	</p>
	<p class="impar"   	title="numero-remessa-retorno"				inicio="184"	fim="191" 	digitos="8" 	>	<?php zeros(8) ?>	</p>
	<p class="par"   	title="data-de-gravacao-remessa-retorno"	inicio="192"	fim="199" 	digitos="8" 	>	<?php echo $this->data_de_gravacao_remessa_retorno; ?>	</p>
	<p class="impar"   	title="data-do-credito"						inicio="200"	fim="207" 	digitos="8" 	>	<?php brancos(8) ?>	</p>
	<!-- CNAB -->
	<p class="par"   	title="uso-exclusivo-febraban-cnab" 		inicio="208"	fim="240" 	digitos="33" 	>	<?php brancos(33) ?>	</p>
</div>


