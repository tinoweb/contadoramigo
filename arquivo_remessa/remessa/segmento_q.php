<div nome="registro-detalhe-segmento-q">
	<!-- Controle -->
	<p class="par"   	title="codigo-do-banco-na-compensacao"		inicio="1"		fim="3" 	digitos="3" 	>	<?php echo $this->codigo_do_banco_na_compensacao;  ?>	</p>
	<p class="impar"   	title="lote-de-servico"						inicio="4"		fim="7" 	digitos="4" 	>	<?php echo $this->lote_de_servico;  ?>	</p>
	<p class="par"   	title="tipo-de-registro"					inicio="8"		fim="8" 	digitos="1" 	>	<?php echo $this->tipo_de_registro;  ?>	</p>
	<!-- Serviço -->
	<p class="impar"   	title="n-sequencial-do-registro-no-lote"	inicio="9"		fim="13" 	digitos="5" 	>	<?php echo $this->n_sequencial_do_registro_no_lote;  ?>	</p>
	<p class="par"   	title="cod-segmento-do-registro-detalhe"	inicio="14"		fim="14" 	digitos="1" 	>	<?php echo $this->cod_segmento_do_registro_detalhe;  ?>	</p>
	<p class="impar"   	title="uso-exclusivo-febraban-cnab"			inicio="15"		fim="15" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<p class="par"   	title="codigo-de-movimento-remessa"			inicio="16"		fim="17" 	digitos="2" 	>	<?php echo $this->codigo_de_movimento_remessa;  ?>	</p>
	<!-- Dados do sacado -->
	<p class="impar"   	title="tipo-de-inscricao"					inicio="18"		fim="18" 	digitos="1" 	>	<?php echo $this->tipo_de_inscricao;  ?>	</p>
	<p class="par"   	title="numero-de-inscricao"					inicio="19"		fim="33" 	digitos="15" 	>	<?php echo $this->numero_de_inscricao;  ?>	</p>
	<p class="impar"   	title="nome"								inicio="34"		fim="73" 	digitos="40" 	>	<?php echo $this->nome;  ?>	</p>
	<p class="par"   	title="endereco"							inicio="74"		fim="113" 	digitos="40" 	>	<?php echo $this->endereco;  ?>	</p>
	<p class="impar"   	title="bairro"								inicio="114"	fim="128" 	digitos="15" 	>	<?php echo $this->bairro;  ?>	</p>
	<p class="par"   	title="cep"									inicio="129"	fim="133" 	digitos="5" 	>	<?php echo $this->cep;  ?>	</p>
	<p class="impar"   	title="sufixo-do-cep"						inicio="134"	fim="136" 	digitos="3" 	>	<?php echo $this->sufixo_do_cep;  ?>	</p>
	<p class="par"   	title="cidade"								inicio="137"	fim="151" 	digitos="15" 	>	<?php echo $this->cidade;  ?>	</p>
	<p class="impar"   	title="unidade-da-federacao"				inicio="152"	fim="153" 	digitos="2" 	>	<?php echo $this->unidade_da_federacao;  ?>	</p>
	<!-- Sacado / aval -->
	<p class="par"   	title="tipo-de-inscricao"					inicio="154"	fim="154" 	digitos="1" 	>	<?php brancos(1) ?>	</p>
	<p class="impar"   	title="numero-de-inscricao"					inicio="155"	fim="169" 	digitos="15" 	>	<?php brancos(15) ?>	</p>
	<p class="par"   	title="nome-do-sacador-avalista"			inicio="170"	fim="209" 	digitos="40" 	>	<?php brancos(40) ?>	</p>

	<p class="impar"   	title="cod-bco-corresp-na-compensacao"		inicio="210"	fim="212" 	digitos="3" 	>	<?php zeros(3) ?>	</p>
	<p class="par"   	title="nosso-n-no-banco-correspondente"		inicio="213"	fim="232" 	digitos="20" 	>	<?php brancos(20) ?>	</p>
	<!-- CNAB -->
	<p class="impar"   	title="uso-exclusivo-febraban-cnab"			inicio="233"	fim="240" 	digitos="8" 	>	<?php zeros(8) ?>	</p>
</div>
















