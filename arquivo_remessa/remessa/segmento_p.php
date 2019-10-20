<div nome="registro-detalhe-segmento-p">
	<!-- Dados de controle -->
	<p class="par"    	title="codigo-do-banco-na-compensacao" 		inicio="1" 		fim="3" 	digitos="3" 	>	<?php echo $this->codigo_do_banco_na_compensacao; ?>	</p>
	<p class="impar"   	title="lote-de-servico"						inicio="4" 		fim="7" 	digitos="4" 	>	<?php echo $this->lote_de_servico; ?>	</p>
	<p class="par"   	title="tipo-de-registro"					inicio="8" 		fim="8" 	digitos="1" 	>	<?php echo $this->tipo_de_registro; ?>	</p>
	<!-- Serviço -->
	<p class="impar"   	title="n-sequencial-do-registro-no-lote"	inicio="9" 		fim="13" 	digitos="5" 	>	<?php echo $this->n_sequencial_do_registro_no_lote; ?>	</p>
	<p class="par"   	title="cod-segmento-do-registro-detalhe"	inicio="14"		fim="14" 	digitos="1" 	>	<?php echo $this->cod_segmento_do_registro_detalhe; ?>	</p>
	<p class="impar"   	title="uso-exclusivo-febraban-cnab"			inicio="15"		fim="15" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<p class="par"   	title="codigo-de-movimento-remessa"			inicio="16" 	fim="17" 	digitos="2" 	>	<?php echo $this->codigo_de_movimento_remessa; ?>	</p>
	<!-- C/C -->
	<p class="impar"   	title="agencia-mantenedora-da-conta"		inicio="18"		fim="22" 	digitos="5" 	>	<?php echo $this->agencia_mantenedora_da_conta; ?>	</p>
	<p class="par"   	title="digito-verificador-da-agencia"		inicio="23"		fim="23" 	digitos="1" 	>	<?php echo $this->digito_verificador_da_agencia; ?>	</p>
	<p class="impar"   	title="numero-da-conta-corrente"			inicio="24"		fim="35" 	digitos="12" 	>	<?php echo $this->numero_da_conta_corrente; ?>	</p>
	<p class="par"   	title="digito-verificador-da-conta"			inicio="36"		fim="36" 	digitos="1" 	>	<?php echo $this->digito_verificador_da_conta; ?>	</p>
	<p class="impar"   	title="digito-verificador-da-ag-conta"		inicio="37"		fim="37" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<!-- Nosso número -->
	<p class="par"   	title="identificacao-do-titulo-no-banco"	inicio="38"		fim="57" 	digitos="20" 	>	<?php echo $this->identificacao_do_titulo_no_banco; ?>	</p>
	<!-- Caracteristicas cobrança -->
	<p class="impar"   	title="codigo-da-carteira"					inicio="58" 	fim="58" 	digitos="1" 	>	<?php echo $this->codigo_da_carteira; ?>	</p>
	<p class="par"   	title="forma-de-cadastr-do-titulo-no-banco"	inicio="59"		fim="59" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<p class="impar"   	title="tipo-de-documento"					inicio="60"		fim="60" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<p class="par"   	title="identificacao-da-emissao-do-bloqueto"inicio="61"		fim="61" 	digitos="1" 	>	<?php echo $this->identificacao_da_emissao_do_bloqueto; ?>	</p>
	<p class="impar"   	title="identificacao-da-distribuicao"		inicio="62"		fim="62" 	digitos="1" 	>	<?php echo $this->identificacao_da_distribuicao; ?>	</p>

	<p class="par"   	title="numero-do-documento-de-cobranca"		inicio="63"		fim="77" 	digitos="15" 	>	<?php echo $this->numero_do_documento_de_cobranca; ?>	</p>
	<p class="impar"   	title="data-de-vencimento-do-titulo"		inicio="78"		fim="85" 	digitos="8" 	>	<?php echo $this->data_de_vencimento_do_titulo; ?>	</p>
	<p class="par"   	title="valor-nominal-do-titulo"				inicio="86"		fim="100" 	digitos="13+2" 	>	<?php echo $this->valor_nominal_do_titulo; ?>	</p>
	<p class="impar"   	title="agencia-encarregada-da-cobranca"		inicio="101"	fim="105" 	digitos="5" 	>	<?php echo $this->agencia_encarregada_da_cobranca; ?>	</p>
	<p class="par"   	title="digito-verificador-da-agencia"		inicio="106"	fim="106" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<p class="impar"   	title="especie-do-titulo"					inicio="107"	fim="108" 	digitos="2" 	>	<?php echo $this->especie_do_titulo; ?>	</p>
	<p class="par"   	title="identific-de-titulo-aceito-nao-aceit"inicio="109"	fim="109" 	digitos="1" 	>	<?php echo $this->identific_de_titulo_aceito_nao_aceit; ?>	</p>
	<p class="impar"   	title="data-da-emissao-do-titulo"			inicio="110"	fim="117" 	digitos="8" 	>	<?php echo date('dmY'); ?>	</p>
	<p class="par"   	title="codigo-do-juros-de-mora"				inicio="118"	fim="118" 	digitos="1" 	>	<?php echo $this->codigo_do_juros_de_mora; ?>	</p>
	<p class="impar"   	title="data-do-juros-de-mora"				inicio="119"	fim="126" 	digitos="8" 	>	<?php zeros(8) ?>	</p>
	<p class="par"   	title="juros-de-mora-por-dia-taxa"			inicio="127"	fim="141" 	digitos="13+2" 	>	<?php zeros(15) ?>	</p>
	<p class="impar"   	title="codigo-do-desconto-1"				inicio="142"	fim="142" 	digitos="1" 	>	<?php echo $this->codigo_do_desconto_1; ?>	</p>
	<p class="par"   	title="data-do-desconto-1"					inicio="143"	fim="150" 	digitos="8" 	>	<?php echo $this->data_do_desconto_1; ?>	</p>
	<p class="impar"   	title="valor-percentual-a-ser-concedido"	inicio="151"	fim="165" 	digitos="13+2" 	>	<?php zeros(15) ?>	</p>
	<p class="par"   	title="valor-do-iof-a-ser-recolhido"		inicio="166"	fim="180" 	digitos="13+2" 	>	<?php zeros(15) ?>	</p>
	<p class="impar"   	title="valor-do-abatimento"					inicio="181"	fim="195" 	digitos="13+2" 	>	<?php zeros(15) ?>	</p>
	<p class="par"   	title="identificacao-do-titulo-na-empresa"	inicio="196"	fim="220" 	digitos="25" 	>	<?php zeros(25) ?>	</p>
	<p class="impar"   	title="codigo-para-protesto"				inicio="221"	fim="221" 	digitos="1" 	>	<?php echo $this->codigo_para_protesto; ?>	</p>
	<p class="par"   	title="numero-de-dias-para-protesto"		inicio="222"	fim="223" 	digitos="2" 	>	<?php echo $this->numero_de_dias_para_protesto; ?>	</p>
	<p class="impar"   	title="codigo-para-baixa-devolucao"			inicio="224"	fim="224" 	digitos="1" 	>	<?php zeros(1) ?>	</p>
	<p class="par"   	title="numero-de-dias-para-baixa-devolucao"	inicio="225"	fim="227" 	digitos="3" 	>	<?php zeros(3) ?>	</p>
	<p class="impar"   	title="codigo-da-moeda"						inicio="228" 	fim="229" 	digitos="2" 	>	<?php echo $this->codigo_da_moeda; ?>	</p>
	<p class="par"   	title="n-do-contrato-da-operacao-de-cred"	inicio="230"	fim="239" 	digitos="10" 	>	<?php zeros(10) ?>	</p>
	<p class="impar"   	title="uso-livre-banco-empresa"				inicio="240"	fim="240" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
</div>

