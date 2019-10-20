<?php 
	//ini_set('display_errors',1);
	//ini_set('display_startup_erros',1);
	//error_reporting(E_ALL);

include 'header_restrita.php'; ?>

<?
//$arrUrl_origem = explode('/',$_SERVER['PHP_SELF']);
// VARIAVEL com o nome da página
//$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];

//$_SESSION['paginaOrigem'] = $pagina_origem;
?>

<script>
function calculaTeto () {
	//validação do formulário
	if (document.getElementById('atividade').value == "") { alert('Selecione a atividade de sua empresa'); document.getElementById('atividade').focus(); return false}
	if (document.getElementById('faturamento_periodo').value == ""){ alert('Informe o faturamento no período.'); document.getElementById('faturamento_periodo').focus(); return false}
	if (document.getElementById('IR_periodo').value == "") { alert('Informe o valor do IR do período.'); document.getElementById('IR_periodo').focus(); return false}

	//pega o faturamento, transforma em float e põe no padrão americano para efeito de cálculo
	faturamento = document.getElementById('faturamento_periodo').value;
	faturamento = faturamento.replace(".","");
	faturamento = faturamento.replace(",",".");
	faturamento = parseFloat(faturamento);
	
	//pega o percentual maximo de lucro de acordo ocm a atividade selecionada
	lucroMax = parseFloat(document.getElementById('atividade').value)

	ir = document.getElementById('IR_periodo').value;
	ir = ir.replace(".","");
	ir = ir.replace(",",".");
	ir = parseFloat(ir);
	//pega o valor do IR
	

	// depois de colhidas todas as variáveis, calcula o teto permitido para distribuição de lucro, que é igual a um percentual do faturamento, menos o IR

	var teto = ((faturamento * lucroMax)/100) - ir
	
	// ************* chama função que formata o valor do teto para o Brasil**************
	
	formataBrasil(teto)
	
	function formataBrasil(num) {
	
		x = 0;
		
		if(num<0) {
			num = Math.abs(num);
			x = 1;
		}
		if(isNaN(num)) num = "0";
		cents = Math.floor((num*100+0.5)%100);
		
		num = Math.floor((num*100+0.5)/100).toString();
		
		if(cents < 10) cents = "0" + cents;
		for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+'.'
		+num.substring(num.length-(4*i+3));
		teto = num + ',' + cents;
		if (x == 1) teto = ' - ' + teto;return teto;
		
	}
	
	
	//************* fim da função que formata o valor para o Brasil**********
	
	
	//escreve o valor final no form da pagina
	document.getElementById('teto').value = teto
	document.getElementById('teto').style.backgroundColor = '#a61d00'
		
}



$(document).ready(function(e) {
});

</script>



		<div style="position: relative;">
    
      <!--BALLOM excluir pagamento-->
      <div class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; left:50%; margin-left: -400px; z-index: 9999;" id="aviso-delete-livro-caixa">
          <div style="padding:20px; font-size:12px;">
              <div id="mensagemDELETEPagamento"></div><br>
              <div style="clear: both; margin: 0 auto; display: inline;">
                <center>
                  <button id="btSIMDeletePagamentoLivroCaixa" type="button" idpg="" idLC="">Sim</button>
                  <button id="btNAODeletePagamentoLivroCaixa" type="button" idpg="" idLC="">Não</button>
                </center>
              </div>
              <div style="clear: both;"></div>
          </div>
        </div>
      <!--FIM DO BALLOOM excluir pagamento -->
      

     <!--BALLOM Livro Caixa -->
      <div class="bubble only-box" style="display: none; padding:0; position:absolute; top: -50px; left:120px; z-index: 9999;" id="aviso-livro-caixa">
          <div style="padding:20px; font-size:12px;">
            Deseja cadastrar este pagamento no seu livro caixa?<br><br>            
            <div style="clear: both; margin: 0 auto; display: inline;">
              <center>
                <button id="btSIMAvisoLivroCaixa" type="button" idPagto="" idSocio="">Sim</button>
                <button id="btNAOAvisoLivroCaixa" type="button">Não</button>
              </center>
            </div>
            <div style="clear: both;"></div>
          </div>
        </div>
      <!--FIM DO BALLOOM Livro Caixa -->
      
    </div>



<div class="principal">


<div class="titulo" style="margin-bottom:20px;">Pagamentos</div>

<div class="tituloVermelho" style="float: left">Distribuição de Lucros</div>

<div class="tituloAzul" style="float:right">
<a href="#" class="btReAbrirBox" div="video"  style="text-decoration: none"><i class="fa fa-file-video-o" aria-hidden="true"></i> Vídeo de orientações</a>
</div>
<div style="clear:both;"></div>

<!--video de instrucoes-->
<div id="video" class="box_visualizacao check_visualizacao x_visualizacao" style="border-style:solid; border-width:1px; border-color:#CCCCCC; position:absolute; left:50%; margin-left:-340px; top:0px; background-color:#fff; width:680px; display: block;">
    <div style="padding:20px">
       <div class="titulo" style="text-align:left; margin-bottom:10px">Orientações Gerais</div>
       <video id="video1" width="640" height="360" controls>
        <source src="videos/distribuicao_lucros.mp4" type='video/mp4'> 
        </video>
    </div>
</div>

			<script>
			
				
				$( document ).ready(function() {
				    
					if( $(".check_caixa_visualizacao").attr("checked") === false ){
						// $("#video").css("display","block");
						$(".btReAbrirBox").click()
					}
				    
				});
			
			</script>


 <div style="clear:both;"></div>
 <br>
Existem duas formas dos sócios  usufruirem da renda de suas empresas. A primeira é através do pró-labore, uma retirada mensal em valores constantes, a título de remuneração pelo trabalho desempenhado. O pró-labore é uma renda tributável, ou seja, você terá que recolher um percentual  para o IR e para a previdência. Para estar isento do IR, sua retirada líquida mensal de pró-labore não pode ser superior a R$ 1.903,98    ao mês. Já para o recolhimento à Previdência, não há isenção. Você deverá recolher o equivalente a 11% do pró-labore.<br>
<br>
A outra forma de retirar dinheiro da empresa é pela distribuição de lucros. A vantagem da distribuição de lucros é que não há cobrança de IR, nem recolhimento à previdência. Normalmente a distribuição de lucros é feita no final do ano,
    após contabilizado o lucro obtido no período. Neste caso, fazer antecipações mensais pode ser uma opção. <br>
    <br>
Para quem  faz a contabilidade simplificada (como é o seu caso) há um teto para a distribuição de lucros, que é um percentual do faturamento total da empresa. Veja a seguir como calculá-lo:<br>
<br>
<br>
<span class="tituloVermelho">Calcule do teto   para distribuição de lucros de sua empresa:</span><br>
<br>
<form id="formGeraRecibo" method="post">
	<input type="hidden" name="hddCategoria_livro_caixa" id="hddCategoria_livro_caixa" value="Distribuição de lucros">
  
  <label for="atividade">Atividade: </label>
  <select name="atividade" id="atividade">
    <option value="">Selecione</option>
    <option value="1.6">Revenda, para consumo, de combustível</option>  
    <option value="16">Transporte de passageiros</option>
    <option value="8">Transporte de cargas</option>
    <option value="8">Serviços hospitalares e de auxílio diagnóstico e terapia</option>
    <option value="16">Bancos</option>
    <option value="16">Corretoras de títulos, valores mobiliários e câmbio</option> 
    <option value="16">Distribuidoras de títulos e valores mobiliários</option> 
    <option value="16">Arrendamento mercantil</option> 
    <option value="16">Cooperativas de crédito</option> 
    <option value="16">Empresas de seguros privados e de capitalização</option> 
    <option value="16">Entidades de previdência privada aberta</option> 
    <option value="32">Intermediação de negócios</option>
    <option value="32">Administração, locação ou cessão de bens imóveis;</option>
    <option value="32">Administração, locação ou cessão de bens móveis, e direitos de qualquer natureza;</option>
    <option value="32">Assessoria Creditícia e mercadológica</option>
    <option value="32">Factoring</option>
    <option value="32" selected="selected">Prestação de serviços em geral não especificados anteriormente</option>
    <option value="8">Comércio e Indústria em geral </option>
  </select>
  <br /><br />

  <span id="linha_faturamento_periodo">
      <label for="faturamento_periodo">Digite o faturamento no período:</label>  R$ <input name="faturamento_periodo" id="faturamento_periodo" type="text" style="margin-right:10px" class="current" maxlength="12" /><br /><br />
  </span>

  <span id="linha_IR_periodo">
      <label for="IR_periodo">Digite o IRPJ recolhido no período:</label>  R$ <input name="IR_periodo" id="IR_periodo" type="text" style="margin-right:10px" class="current" maxlength="12" />
       Não sabe  qual o valor pago? <a href="distribuicao_de_lucros_tutorial.php">Veja como descobrir</a>
      <br /><br />
  </span>


  <br />
  
  
  <input name="" type="button" value="Calcular" onclick="javascript:calculaTeto()" />
<br />
<br />
<br />
<span style="font-weight:bold;">Resultado: </span>
<label for="teto"> R$ </label><input name="teto" id="teto" type="text" style="color:#FFF" /> 
Este é valor máximo para distribuição de lucros em sua empresa no período.
  </form>

<div style="clear:both;margin-bottom:30px;"></div>

<div class="tituloVermelho" style="clear:both;margin-bottom:10px;">
Emissão de recibo
</div>

<div style="clear:both;">
Você pode distribuir o resultado da distribuição de lucros entre os sócios. Digite abaixo o valor a ser atribuído a cada sócio.<br />
<div style="height:20px;clear:both;"></div>
	<?php 

		$consulta_recibos_temp = mysql_query("SELECT * FROM recibos_temp WHERE id_user = '".$_SESSION["id_empresaSecao"]."' ");
		$data1_recibos_temp = "";
		$data2_recibos_temp = "";
		$user_temp = false;
		while( $objeto_recibos_temp = mysql_fetch_array($consulta_recibos_temp )){
			if( $user_temp == false && ( $objeto_recibos_temp['data1'] != '' || $objeto_recibos_temp['data2'] != '' ) ){
				$user_temp = true;
			}
			if( $objeto_recibos_temp['valor'] != '' ){
				$data1_recibos_temp = $objeto_recibos_temp['data1'];
				$data2_recibos_temp = $objeto_recibos_temp['data2'];	
			}
		}
		$selected_recibo_temp_mensal = "";
		$selected_recibo_temp_anual = "";
		if($user_temp && strlen($data1_recibos_temp) > 5){
			$selected_recibo_temp_mensal = "selected='selectd'";
		}
		else if($user_temp && strlen($data1_recibos_temp) > 1)
			$selected_recibo_temp_anual = "selected='selectd'";

		

	?>
<span id="linha_periodo">
    <label for="periodo">Período:</label>
    <select name="selPeriodo" id="selPeriodo">
      <option value="mensal" <?php echo $selected_recibo_temp_mensal; ?>>Mensal</option>
      <option value="anual" <?php echo $selected_recibo_temp_anual; ?>>Anual</option>
    </select>

    <div style="display:none" id="div_mensal">
      <input name="periodo" id="mensal" type="text" style="width:80px;" value="<?php echo $data1_recibos_temp; ?>" tabela="recibos_temp" campo="data1" id-aux="<?php echo $_SESSION["id_empresaSecao"]; ?>" class="editar_item2 campoDataMesAno" maxlength="7" /> (mm/aaaa)
    </div>
    <div style="display:none" id="div_anual">
      <input name="periodo" id="anual" type="text" style="width:60px;" value="<?php echo $data1_recibos_temp; ?> "tabela="recibos_temp" campo="data1" id-aux="<?php echo $_SESSION["id_empresaSecao"]; ?>" class="editar_item2 campoDataAno" maxlength="4" /> (aaaa)
    </div> 
    <label for="dtPagto" style="margin-left:50px">Data do pagamento:</label>
    <input name="dtPagto" id="dtPagto" type="text" style="width:80px;" value="<?php echo $data2_recibos_temp; ?>" tabela="recibos_temp" campo="data2" id-aux="<?php echo $_SESSION["id_empresaSecao"]; ?>" class="editar_item2 campoData" maxlength="10" /> (dd/mm/aaaa)
    
</span>
  
<div style="clear:both;height:10px;"></div>

<?
$query = mysql_query('SELECT idSocio, nome FROM dados_do_responsavel WHERE id = '.$_SESSION["id_empresaSecao"].' ORDER BY responsavel desc, nome');
while($dados = mysql_fetch_array($query)){

	$id_recibos_temp = "recibo_".$dados['idSocio'];

	$consulta_recibos_temp = mysql_query("SELECT * FROM recibos_temp WHERE id = '".$id_recibos_temp."' ");
	$objeto_recibos_temp = mysql_fetch_array($consulta_recibos_temp);
	$valor_recibos_temp = '';
	if( $objeto_recibos_temp['id'] == $id_recibos_temp ){
		$valor_recibos_temp = $objeto_recibos_temp['valor'];
	}
	else{
		mysql_query("INSERT INTO `recibos_temp`(`id`,`id_user`) VALUES ( '".$id_recibos_temp."','".$_SESSION["id_empresaSecao"]."' )");
	}

	echo "	<!--<div style=\"position:relative;float:left;padding:5px;width:20px;\"><input type=\"checkbox\" name=\"idSocio[]\" value=\"".$dados['idSocio']."\"></div>" . "\r\n" . "-->
					<div style=\"position:relative;float:left;padding:5px 5px 5px 0;width:240px;\" class=\"txtNomeSocio\">".$dados['nome']."</div>" . "\r\n" . "
					<div style=\"position:relative;float:left;padding:0px;width:140px;\">R$ <input type=\"text\" name=\"valorSocio\" style=\"width:100px;\" class=\"current editar_item\" tabela=\"recibos_temp\" campo=\"valor\" id-aux=\"recibo_".$dados['idSocio']."\" maxlength=\"14\" value='".$valor_recibos_temp."'/></div>" . "\r\n" . "
					<div style=\"position:relative;float:left;padding:0px;width:80px;\"><input type=\"button\" name=\"btGerarRecibo\" value=\"Gerar recibo\" id=\"recibo_".$dados['idSocio']."\" /></div>" . "\r\n" . "
					<div style=\"height:5px;clear:both;\"></div>" . "\r\n" . "
";
}
?>
  
 <script>
 	
 	$(document).ready(function(e) {

	 	$(".editar_item").change(function() {
					
			var valor = $(this).val();
			var id = $(this).attr("id-aux");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");

			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  	console.log(retorno);
			  }
			}); 
		});
		$(".editar_item2").change(function() {
					
			var valor = $(this).val();
			var id = $(this).attr("id-aux");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");

			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item2=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  	console.log(retorno);
			  }
			}); 
		});
	});
 
 </script>

  
<script>
$(document).ready(function(e) {

	


	(function($){
		jQuery.fn.validaCampo = function(option){
			var defaults = {
				tipo:'texto'
				, vazio:true
				, maxCaracteres:0
			}
			
			var option = $.extend(defaults,option)
			, $retorno = true;
			
			this.each(function(){
				var opt = option;
				var obj = $(this);

				if(opt.vazio){
					if(!obj.val()){
						alert('Preencha o campo!');// + $('label[for='+obj.attr('name')+']').html().replace(":","") + "!");
						obj.focus();
						$retorno = false;
					}
				}

				if(opt.maxCaracteres > 0){
					if(obj.val() && opt.maxCaracteres < obj.val().length){
						alert('O campo não pode ter mais que ' + opt.maxCaracteres + ' caracteres!')
						obj.focus();
						$retorno = false;
					}
				}
				
				switch (opt.tipo){
					case 'texto':
					break;
					case 'data':
						var content = obj.val().split('/');
						var dia = content[0];
						var mes = content[1];
						var ano = content[2];
						if(
							(mes < 1 || mes > 12)
							|| (dia < 0 || dia > 31)
							|| (ano < 1970 || ano > 2030)
						){
							alert('Data inválida!');
							obj.focus();
							$retorno = false;
						}
					break;
					case 'mesano':
						var content = obj.val().split('/');
						var mes = content[0];
						var ano = content[1];
						if(mes < 1 || mes > 12){
							alert('Mês inválido!');
							obj.focus();
							$retorno = false;
						}
						if(ano < 1970 || ano > 2100){
							alert('Ano inválido!');
							obj.focus();
							$retorno = false;
						}
					break;
					case 'ano':
					break;
				}
				
			});
			
			return $retorno;
		}
	})(jQuery);
	
/*	$('.campoDataMesAno').bind('blur',function(){
		if($(this).val()){
			var content = $(this).val().split('/');
			var mes = content[0];
			var ano = content[1];
			if(mes < 1 || mes > 12){
				alert('Mês inválido!');
				$(this).focus();
				return false;
			}
		}
	});*/
	
	$.formataDataEn = function(data){
		var dia = 0;
		var mes = 0;
		var ano = 0;
		dia = data.substr(0,2);
		mes = data.substr(3,2);
		ano = data.substr(6,4);
		return ano + '-' + mes + '-' + dia;
	}
		
		
	var periodo_pagto = $('#selPeriodo').val();
	
	$('#div_' + periodo_pagto).css('display','inline');
	
	$('#selPeriodo').bind('change',function(){
		$('div[id^=div_]').css('display','none');
		$('input[name=periodo]').val('');
		$('#div_' + $(this).val()).css('display','inline');
	});
	
	
	
	

	
  	$('input[id^=recibo_]').bind('click',function(){
		var valor_teto = 0;
		
		var $this = $(this);
		
		//return $('.campoDataMesAno').validaCampo({tipo:'mesano',vazio:true,maxCaracteres:7});

		//		if($('#teto').val() == ''){
		//			alert('É necessário calcular o teto para distribuição de lucros de sua empresa.');
		//			$('#faturamento_periodo').focus();
		//			return false;			
		//		}else{
					valor_teto = parseFloat($('#teto').val().replace('.','').replace(',','.'));
		//		}
		
		var objPeriodo = $('#'+$('#selPeriodo').val());
		
		if(objPeriodo.val() == ''){ // checando se o periodo foi preenchido
		
			alert('É necessário preencher o período.');
			objPeriodo.focus();
			return false;			
		
		}else{ // checando se o periodo está coerente
		
			var dataCorrente = new Date();
					
			if($('#selPeriodo').val() == 'anual'){
				var content = objPeriodo.val();
				var ano = content;

				if(ano < 1970 || ano > 2100){
					alert('Ano inválido!');
					objPeriodo.focus();
					return false;
				}
								
				if(ano >= dataCorrente.getFullYear()){
					alert('O período deve se referir a um mês ou ano já encerrado.');
					objPeriodo.focus();
					return false;
				}
				

			}else{
							
				var content = objPeriodo.val().split('/');
				var mes = content[0];
				var ano = content[1];
				
				if(mes < 1 || mes > 12){
					alert('Mês inválido!');
					objPeriodo.focus();
					return false;
				}
				if(ano < 1970 || ano > 2100){
					alert('Ano inválido!');
					objPeriodo.focus();
					return false;
				}
				
				var dataPeriodo = new Date(ano+'-'+mes+'-01');
				var dataCorrenteMesAno = new Date(dataCorrente.getFullYear()+'-'+dataCorrente.getMonth()+'-01');

				if(dataPeriodo > dataCorrenteMesAno){
					alert('O período deve se referir a um mês ou ano já encerrado.');
					objPeriodo.focus();
					return false;
				}
				
			}
			
		}

		var dtPagto = $('#dtPagto').val();
		if(dtPagto == ''){
			alert('É necessário preencher a data do Pagamento.');
			$('#dtPagto').focus();
			return false;	
		}

		var $checkData = $('#dtPagto').validaCampo({tipo:'data',vazio:true,maxCaracteres:10});

		if(!$checkData){
			return false;
		}

		var id_socio = $(this).attr('id').split('_')[1];
		var indexSocio = ($('input[id^=recibo_]').index(this));
		var valor = $('input[name=valorSocio]').eq(indexSocio).val();


		var aux_valor = valor;
		var aux_aux_valor = valor;
		aux_valor = aux_valor.replace(",",".");
		aux_valor = parseFloat(aux_valor);
		// return;

		if(valor === '' || aux_valor === 0){
			alert('O valor da distribuição de lucros não pode ser R$ 0,00 ou em branco.');
			$('input[name=valorSocio]').eq(indexSocio).focus();
			return false;	
		}

		var total = 0;
		for(var i = 0; i < $('input[name=valorSocio]').size(); i++){
			if($('input[name=valorSocio]').eq(i).val()){
				var valor_socio = (parseFloat($('input[name=valorSocio]').eq(i).val().replace('.','').replace(',','.')));
				total += valor_socio;
			}
		}

		if(total > valor_teto){
			alert('O valor distribuído não pode ser superior ao teto calculado.');
			$('input[name=valorSocio]').eq(indexSocio).focus();
			return false;
		}

		var dataPagto = objPeriodo.val();

		var 
			$currURL = location.href
			, $Date = new Date($.formataDataEn($('#dtPagto').val()))
			, $DateHoje = new Date()
			, $mesHoje = ($DateHoje.getMonth() + 1)
			, $anoHoje = ($DateHoje.getFullYear())
		;

		$.ajax({
			url:'Recibo_distribuicao_download.php?acao=ins&id=' + id_socio + '&ValorLiquido=' + valor + '&periodo=' + objPeriodo.val() + '&DataPgto=' + dtPagto,
			type: 'post',
			cache: false,
			async: true,
			beforeSend: function(){
				$("body").css("cursor", "wait");
			},
			success: function(retorno){

				$("body").css("cursor", "default");
				if(retorno > 0){

					var nomeSocio = ($('.txtNomeSocio').eq(indexSocio).html());
				//
				//					var $data = 'id=' + id_socio + '&ValorLiquido=' + valor + '&periodo=' + objPeriodo.val() + '&DataPgto=' + dtPagto;
				//					$data += "&nome=" + nomeSocio + '&hddCategoria_livro_caixa=' + $('#hddCategoria_livro_caixa').val();
				//					$data += "&idPagto=" + retorno;
					
					$('#btSIMAvisoLivroCaixa').attr('valor',aux_aux_valor);
					$('#btSIMAvisoLivroCaixa').attr('indiceCampo',indexSocio);
					$('#btSIMAvisoLivroCaixa').attr('idSocio',id_socio);
					
					$('#btSIMAvisoLivroCaixa').attr('periodo',objPeriodo.val());
					$('#btSIMAvisoLivroCaixa').attr('dataPagto',dtPagto);
					$('#btSIMAvisoLivroCaixa').attr('nome',nomeSocio);
					$('#btSIMAvisoLivroCaixa').attr('categoria',$('#hddCategoria_livro_caixa').val());
					$('#btSIMAvisoLivroCaixa').attr('idPagto',retorno);
					
					$('#btNAOAvisoLivroCaixa').attr('dataPagto',dtPagto);
					
					$('#aviso-livro-caixa').css({
						'top':($this.offset().top - 230) + 'px'
						, 'left':($this.offset().left - 50) + 'px'
					}).fadeIn(100);

					
					
					var valor = '';
					var id = $this.attr("id");
					var tabela = "recibos_temp";
					var campo = "valor";

					$.ajax({
					  url:'ajax.php'
					  , data: 'editar_item2=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
					  , type: 'post'
					  , async: true
					  , cache:false
					  , success: function(retorno){
					  	console.log(retorno);
					  }
					}); 

					$.ajax({
					  url:'ajax.php'
					  , data: 'editar_item=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
					  , type: 'post'
					  , async: true
					  , cache:false
					  , success: function(retorno){
					  	console.log(retorno);
					  }
					}); 

	
					// $.ajax({
					//   url:'ajax.php'
					//   , data: 'editar_item2=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
					//   , type: 'post'
					//   , async: true
					//   , cache:false
					//   , success: function(retorno){
					//   	console.log(retorno);
					//   }
					// }); 
					
					
				//					if(confirm('Deseja cadastrar este pagamento no seu livro caixa? Clique OK para incluí-lo no livro, ou Cancelar para cadastrá-lo somente aqui.')){
				//						$.ajax({
				//							url:'atualiza_livros_caixa.php',
				//							type: 'post',
				//							data: $data,
				//							cache: false,
				//							async: true,
				//							beforeSend: function(){
				//								$("body").css("cursor", "wait");
				//							},
				//							success: function(retorno){
				//								$("body").css("cursor", "default");
				//								if(retorno == 1){
				//									// faz o post do formulário de pesquisa para listar os do mes do pagamento recem cadastrado
				//									$('#periodoAno').val($Date.getFullYear());
				//									$('#periodoMes').val($Date.getMonth()+1);
				//									$('#hddTipoFiltro').val('mes');
				//
				//									$('#form_filtro').submit();			
				//
				////									location.href = $currURL;
				//								}
				//							}
				//						});
											
				//					}else{

										// faz o post do formulário de pesquisa para listar os do mes do pagamento recem cadastrado
				//						$('#periodoAno').val($Date.getFullYear());
				//						$('#periodoMes').val($Date.getMonth()+1);
				//						$('#hddTipoFiltro').val('mes');
				//
				//						$('#form_filtro').submit();			
				//
				//									location.href = $currURL;
				//					
				//					}
												
				}
			}
		});
		
		//location.href='Recibo_distribuicao_download.php?acao=ins&id=' + id_socio + '&ValorLiquido=' + valor + '&periodo=' + objPeriodo.val() + '&DataPgto=' + dtPagto;
	});
	
	

		
		$('#btSIMAvisoLivroCaixa').bind('click',function(){
			

			var id_socio = $(this).attr('idSocio');
			var valor = $(this).attr('valor');
			var periodo = $(this).attr('periodo');
			var dtPagto = $(this).attr('dataPagto');
			var nomeSocio = $(this).attr('nome');
			var categoria = $(this).attr('categoria');
			var idPagto = $(this).attr('idPagto');

			var 
				$currURL = location.href
				, $Date = new Date($.formataDataEn("10/" + dtPagto.substr(3,2) + "/" + dtPagto.substr(6,4)))
				, $DateHoje = new Date()
				, $mesHoje = ($DateHoje.getMonth() + 1)
				, $anoHoje = ($DateHoje.getFullYear())
				, $this = $(this)
			;
			
			var $data = 'id=' + id_socio + '&ValorLiquido=' + valor + '&periodo=' + periodo + '&DataPgto=' + dtPagto;
			$data += "&nome=" + nomeSocio + '&hddCategoria_livro_caixa=' + categoria;
			$data += "&idPagto=" + idPagto;

			$.ajax({
				url:'atualiza_livros_caixa.php',
				type: 'post',
				data: $data,
				cache: false,
				async: true,
				beforeSend: function(){
					$("body").css("cursor", "wait");
				},
				success: function(retorno){
					$("body").css("cursor", "default");
					if(retorno == 1){
						// faz o post do formulário de pesquisa para listar os do mes do pagamento recem cadastrado
						$('#periodoAno').val($Date.getFullYear());
						$('#periodoMes').val($Date.getMonth()+1);
						$('#hddTipoFiltro').val('mes');

						$('#form_filtro').submit();			

//									location.href = $currURL;
					}
				}
			});
			
		});
		
		
		$('#btNAOAvisoLivroCaixa').bind('click',function(){										
			// faz o post do formulário de pesquisa para listar os do mes do pagamento recem cadastrado
			var 
				$Date = new Date($.formataDataEn("10/" + $(this).attr('dataPagto').substr(3,2) + "/" + $(this).attr('dataPagto').substr(6,4)))
			;
			
			$('#periodoAno').val($Date.getFullYear());
			$('#periodoMes').val($Date.getMonth()+1);
			$('#hddTipoFiltro').val('mes');

			$('#form_filtro').submit();			

//									location.href = $currURL;
		});
		
		
});
</script>




<?
$_SESSION['categoria'] = "distr. de lucros";

$categoria = $_SESSION['categoria'];


$categoria = ($categoria != "" ? strtolower($categoria) : '');

$tipoFiltro = "mes";



function get_nome_mes($numero_mes){
	$arrMonth = array(
		1 => 'janeiro',
		2 => 'fevereiro',
		3 => 'março',
		4 => 'abril',
		5 => 'maio',
		6 => 'junho',
		7 => 'julho',
		8 => 'agosto',
		9 => 'setembro',
		10 => 'outubro',
		11 => 'novembro',
		12 => 'dezembro'
		);
	return $arrMonth[(int)$numero_mes];
}

function get_ultimo_dia_mes($mes, $ano){
	switch($mes){
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			return '31';
		break;
		case 4:
		case 6:
		case 9:
		case 11:
			return '30';
		break;
		case 2:
			if($ano % 4 == 0){
				return '29';
			}else{
				return '28';
			}
		break;
	}
}
	
	// Informa o ano atual;
	$anoInicioPagamentos = date('Y');
	
	// Pega o menor ano para o filtro.
	$sql = "SELECT MIN(YEAR(data_pagto)) ano FROM `dados_pagamentos` WHERE `id_login` = '" . $_SESSION["id_empresaSecao"] . "' AND `id_lucro` != 0 LIMIT 1";
	$consulta = mysql_query($sql);
	
	// Verifica se  existe lançamento de pagamento e pega a menor data.
	if(mysql_num_rows($consulta) > 0){
		
		$rsAnoInicial = mysql_fetch_array($consulta);
		$anoInicioPagamentos = $rsAnoInicial['ano'];
	}
?>


<script>

	

	$(document).ready(function(e) {
		
		$('#link_outro_periodo').bind('click',function(e){
			e.preventDefault();
			if($(this).html() == 'definir período maior'){
				$(this).html('definir período por mês');
				$('#hddTipoFiltro').val('periodo');
				$('#form_mes_ano').css('display','none');
				$('#form_outro_periodo').css('display','inline');
				$('#form_mes_ano').find('select').val('<?=date('Y')?>');
			}else{
				$(this).html('definir período maior');
				$('#form_mes_ano').css('display','inline');
				$('#form_mes_ano').find('select').val('<?=date('Y')?>');
				$('#form_outro_periodo').css('display','none');
				$('#hddTipoFiltro').val('mes');
				$('#dataInicio').val('');
				$('#dataFim').val('');
			}
		});
		
		// MONTA A COMBO COM OS NOMES  é passado o id do request para deixar o ultimo
		<?
		if(isset($_REQUEST["nome"])){
			$arrDadosNome = explode("|",$_REQUEST["nome"]);
			$idNome = $arrDadosNome[0];
			$filtro_categoria = $arrDadosNome[1];
		}
		?>
	
		// MONTA A COMBO COM OS NOMES  é passado o id do request para deixar o ultimo
		// FOI SOLICITADA MUDANÇA PARA EXECUTAR O FILTRO A CADA CHANGE
		$('#categoria').change(function(){
			$('#nome').val(''); // PARA QUE O FILTRO FUNCIONE CORRETAMENTE É NECESSÁRIO ZERAR A COMBO DE NOMES
			$('#form_filtro').attr('action','<?=$_SERVER['PHP_SELF']?>');
			$('#form_filtro').submit();
		});

		if($('#categoria').val() == "" || $('#categoria').val() == "sefip"){
			$('#nome').css('display','none');
		}else{
			$('#nome').css('display','inline');
			montaCombo('populaCombo','area=folha_pagto&tipo=distr. de lucros&id=<?=$_REQUEST["nome"]?>','nome');
		}

		// FOI SOLICITADA MUDANÇA PARA EXECUTAR O FILTRO A CADA CHANGE
		$('#nome').change(function(){
			$('#form_filtro').submit();
		});
		
		$('#cad_pagamento').bind('click',function(e){
			location.href=$('#opt_pagamento').val() + ".php";
		});
		
		
			
		$('.excluirPagamento').bind('click',function(e){
			e.preventDefault();
			var idPagto = $(this).attr("idpg");
			var idLivroCaixa = $(this).attr("idLC");
			var queryString = "", queryString2 = "";
			var QS = $(this).attr("qs");
			var mensagem = "Você tem certeza que deseja excluir este Pagamento?";


			$('#aviso-delete-livro-caixa').fadeOut(100);

			queryString = "excluir=" + idPagto;

			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				mensagem = "Deseja excluir este lançamento do Livro Caixa?";
				queryString2 = "&idLivroCaixa=" + idLivroCaixa;
			}

			$('#aviso-delete-livro-caixa').find('#mensagemDELETEPagamento').html(mensagem);

			$('#btSIMDeletePagamentoLivroCaixa').attr("idpg",idPagto);
			$('#btSIMDeletePagamentoLivroCaixa').attr("idLC",idLivroCaixa);
			$('#btSIMDeletePagamentoLivroCaixa').attr("qs",QS);

			$('#btNAODeletePagamentoLivroCaixa').attr("idpg",idPagto);
			$('#btNAODeletePagamentoLivroCaixa').attr("idLC",idLivroCaixa);
			$('#btNAODeletePagamentoLivroCaixa').attr("qs",QS);

			$('#aviso-delete-livro-caixa').css('top',($(this).offset().top - 200) + 'px').fadeIn(100);

		});
		
		
		$('#btSIMDeletePagamentoLivroCaixa').bind('click',function(){
			var idPagto = $(this).attr("idpg");
			var idLivroCaixa = $(this).attr("idLC");
			var QS = $(this).attr("qs");
				
				
			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				location.href='folha_pagamentos_excluir.php?' + "excluir=" + idPagto + "&idLivroCaixa=" + idLivroCaixa + QS;
			}else{
				location.href='folha_pagamentos_excluir.php?' + "excluir=" + idPagto + QS;
			}
		});

		$('#btNAODeletePagamentoLivroCaixa').bind('click',function(){
			var idPagto = $(this).attr("idpg");
			var idLivroCaixa = $(this).attr("idLC");
			var QS = $(this).attr("qs");

			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				location.href='folha_pagamentos_excluir.php?' + "excluir=" + idPagto + QS;
			}else{
				$('#aviso-delete-livro-caixa').fadeOut(100);
			}
		});
		
		
		
		
		
	});


	// MONTAR COMBO
	function montaCombo(codigo, parametros, idCampoDestino){
		$.ajax({
			url: codigo+'.php',
			data: parametros,
			type: 'POST',
			async: false ,
			cache: false,
			success: function(result){
//					$('#resultado').html(result);
				$('#'+idCampoDestino).html(result);//arrResult[1]);
			}
			
		});
	}



	
	
	function ultimoDiaMes(mes,ano){
		switch(mes){
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
				return '31';
			break;
			case 4:
			case 6:
			case 9:
			case 11:
				return '30';
			break;
			case 2:
				if(ano % 4 == 0){
					return '29';
				}else{
					return '28';
				}
			break;
		}
	}
	
	function alterarPeriodo() {
		
		if(document.getElementById('dataInicio').value != '' && document.getElementById('dataFim').value != ''){
			
			dataInicio = document.getElementById('dataInicio').value;
			anoInicio = dataInicio.substr(6,4);
			mesInicio = dataInicio.substr(3,2);
			diaInicio = dataInicio.substr(0,2);
			dataFim = document.getElementById('dataFim').value;
			anoFim = dataFim.substr(6,4);
			mesFim = dataFim.substr(3,2);
			diaFim = dataFim.substr(0,2);
	
		}else{
			if(document.getElementById('periodoMes').value != '' && document.getElementById('periodoAno').value != ''){
				anoInicio = anoFim = document.getElementById('periodoAno').value;
				mesInicio = mesFim = document.getElementById('periodoMes').value;
				diaInicio = '01';
				diaFim = ultimoDiaMes(mesInicio,anoInicio);
				alert(diaFim);
			}
		}
		
		
		window.location='<?=$_SERVER['PHP_SELF']?>?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&busca=<?=$_REQUEST["busca"]?>&coluna=<?=$_REQUEST["coluna"]?>';
	}
	
</script>


  <div class="tituloVermelho" style="margin-top:20px; margin-bottom:20px;">Pagamentos efetuados</div>

    <form id="form_filtro" method="post" action="<?=$_SERVER['PHP_SELF']?>">
      
<?

		//Valores pré-definidos para a busca.
		if($_POST || $_GET){
		
			$tipoFiltro = $_REQUEST['hddTipoFiltro'];
			
			if($tipoFiltro == "mes"){

				if($_REQUEST["periodoMes"] != ""){ // selecionou mes/ano
					$dataInicio = date('Y-m-d',mktime(0,0,0,$_REQUEST["periodoMes"],'01',$_REQUEST["periodoAno"]));
					$dataFim = date('Y-m-d',mktime(0,0,0,$_REQUEST["periodoMes"],get_ultimo_dia_mes($_REQUEST["periodoMes"],$_REQUEST["periodoAno"]),$_REQUEST["periodoAno"]));
				}else{
					if($_REQUEST["dataInicio"] != "" && $_REQUEST["dataFim"] != ""){ // selecionou periodo de data
						if($_REQUEST["dataFim"] != ""){
							$dataFim = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataFim"],3,2),substr($_REQUEST["dataFim"],0,2),substr($_REQUEST["dataFim"],6,4)));
						}
						if($_REQUEST["dataInicio"] != ""){
							$dataInicio = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataInicio"],3,2),substr($_REQUEST["dataInicio"],0,2),substr($_REQUEST["dataInicio"],6,4)));
						}
					}else{ // mostrar todos os meses
						$dataInicio = date('Y-m-d',mktime(0,0,0,'01','01',$_REQUEST["periodoAno"]));
						$dataFim = date('Y-m-d',mktime(0,0,0,'12','31',$_REQUEST["periodoAno"]));
					}
				}

					$comparaMes = $_REQUEST["periodoMes"];
					$comparaAno = $_REQUEST["periodoAno"];

			} else{
				if($_REQUEST["dataFim"] != ""){
					$dataFim = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataFim"],3,2),substr($_REQUEST["dataFim"],0,2),substr($_REQUEST["dataFim"],6,4)));
				}
				if($_REQUEST["dataInicio"] != ""){
					$dataInicio = date('Y-m-d',mktime(0,0,0,substr($_REQUEST["dataInicio"],3,2),substr($_REQUEST["dataInicio"],0,2),substr($_REQUEST["dataInicio"],6,4)));
				}
				
			}

		}

		if ($dataInicio == "") {
			//Busca o ultimo
			$result2 = PegaUltimoPagamento();
			
			$dataInicio = date('Y-m-d',mktime(0,0,0,date('m') ,'01',date('Y')));
			$comparaMes = date('m');
			$comparaAno = date('Y');
			
			if(mysql_num_rows($result2) > 0){
				//Pega o mes e ano do ultimo pagamento
				$dadosPagamento = mysql_fetch_array($result2);
				
				$dataInicio = date('Y-m-', strtotime($dadosPagamento['data_pagto']))."01";
				$dataFim = date('Y-m-', strtotime($dadosPagamento['data_pagto']))."31";
				$comparaMes = date('m', strtotime($dadosPagamento['data_pagto']));
				$comparaAno = date('Y', strtotime($dadosPagamento['data_pagto']));
			}
		}

		if ($dataFim == "") {
			$dataFim = date('Y-m-d',mktime(0,0,0,date('m'),date('t'),date('Y')));//date('Y-m-d',strtotime("-1 days",strtotime('01-'.(date('m')+1).'-'.date('Y'))));
		}
?>
    <div style="display:inline;float:left;margin-right:5px;">
      <select name="nome" id="nome">
      
      </select>
    </div>
    
    <div id="form_mes_ano" style="display:<?=$tipoFiltro == "mes" ? 'inline' : 'none' ?>;float:left;margin-right:5px;">
      No mês de 
      <select name="periodoMes" id="periodoMes">
        <option value="">Todos</option>
        <? for($i = 1; $i <= 12; $i++) {?>
        <option value="<?=$i?>"<?=(($comparaMes == $i) ? " selected" : "")?>><?=ucfirst(get_nome_mes($i))?></option>
        <? } ?>
      </select>
      de 
      <select name="periodoAno" id="periodoAno">
        <? for($i = date('Y'); $i >= $anoInicioPagamentos; $i--) {?>
        <option value="<?=$i?>"<?=($comparaAno == $i ? " selected" : "")?>><?=$i?></option>
        <? } ?>
      </select>
    </div>
    
    <div id="form_outro_periodo" style="display:<?=$tipoFiltro == "mes" ? 'none' : 'inline' ?>;float:left;margin-right:5px;">
      Período de: <input name="dataInicio" id="dataInicio" type="text" value="<?=$_REQUEST['dataInicio'] != "" ? date('d/m/Y',strtotime($dataInicio)) : ""?>" maxlength="10"  style="width:80px" class="campoData" /> 
      até: <input name="dataFim" id="dataFim" type="text" value="<?=$_REQUEST['dataFim'] != "" ? date('d/m/Y',strtotime($dataFim)) : ""?>" maxlength="10"  style="width:80px" class="campoData" /> 
    </div>
    
    <div style="display:inline;float:left;margin-right:5px;">
      <input type="hidden" name="hddTipoFiltro" id="hddTipoFiltro" value="<?=$tipoFiltro?>" />
      <input type="submit" value="Pesquisar" />
    </div>
    
    <div style="display:inline;float:left;padding-top:5px;margin-right:5px;">
      ou <a href="#" id="link_outro_periodo"><?=(($_REQUEST['dataInicio'] != "") || ($_REQUEST['dataFim'] != "") ? 'definir período por mês' : 'definir período maior' )?></a>
    </div>
     
    </form>
    <div style="clear: both; margin-bottom: 20px;"></div>



<?
	//PRO-LABORE
		// MONTAGEM DA LISTAGEM DOS PAGAMENTOS
	function MontagemDaListagemDosPagamentos($dataInicio, $dataFim, $idNome){
		$sql = "SELECT 
					pgto.id_pagto
					, pgto.valor_liquido
					, pgto.data_pagto  
					, pgto.idLivroCaixa
					, case 
						  when pgto.id_lucro <> 0 AND LENGTH(pgto.data_periodo_ini) = 4 then 'Anual' 
						  when pgto.id_lucro <> 0 AND LENGTH(pgto.data_periodo_ini) > 4 then 'Antecipação mensal' 
						  else '' 
					  end periodo
					, dl.idSocio id
					, dl.nome nome
				FROM 
					dados_pagamentos pgto
					INNER join dados_do_responsavel dl on pgto.id_lucro = dl.idSocio
				WHERE 
					pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'
				";	
										
		$resDatas = "";
		if($dataInicio != ''){
			$resDatas .= " AND pgto.data_pagto >= '" . $dataInicio . "'"; 
		}
		if($dataFim != ''){
			$resDatas .= " AND pgto.data_pagto <= '" . $dataFim . "'"; 
		}
	
		if ($_REQUEST["nome"] != ""){
			$resColuna = " HAVING 1=1 AND id = ". $idNome . "";
		}
				
		$resOrdem = "
				ORDER BY data_pagto DESC";
		if($dataInicio == '' && $dataFim == ''){
			$resOrdem .= "				LIMIT 0,12
				";
		}
			
				
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
	
		return $resultado;
	}
		 
 	//Pega o ultimo pagamento caso não tenha nenhum no mês atual.
 	function PegaUltimoPagamento(){
		
		$sql2 = "SELECT * FROM dados_pagamentos WHERE id_login = '" . $_SESSION["id_empresaSecao"] . "' AND id_lucro != 0 ORDER BY data_pagto DESC LIMIT 1";
		
		//executa consulta.
		$result2 = mysql_query($sql2);
		return $result2;
	}
		 
	//Verifica se existe cadastro na data informada.
	$resultado = MontagemDaListagemDosPagamentos($dataInicio, $dataFim, $idNome);
	
		$categoria = "distr. de lucros";
			
	?>

      <table width="900" cellpadding="5" style="margin-bottom:25px;">
          <tr>
            <th width="7%">Ação</th>
            <th width="45%">Nome</th>
            <th width="15%">Tipo</th>
            <th width="9%">Data</th>
            <th width="14%">Valor Distribuído</th>
          </tr>
        
	<?	
		if(mysql_num_rows($resultado) > 0){			

				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 	= $linha["id_pagto"];
					$id 	= $linha["id"];
					$nome 	= $linha["nome"];
					$periodo 	= $linha["periodo"];
					$valor_liquido 	= $linha["valor_liquido"];
					$idLivroCaixa		= $linha["idLivroCaixa"];
					$data_pagto 	= (date("d/m/Y",strtotime($linha['data_pagto'])));					
					
					$idLoginPagamento = "";
					if(isset($_REQUEST['nome']) && $_REQUEST['nome'] != ''){
						$arrComboNomes = explode("|",$_REQUEST['nome']);
						$idLoginPagamento = $arrComboNomes[0];
					}
	?>
                    <tr>
                        <td class="td_calendario" align="center">
<!--                            <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este Pagamento?'))location.href='folha_pagamentos_excluir.php?excluir=<?=$idPagto?>;"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>-->
                            <a href="#" class="excluirPagamento" idpg="<?=$idPagto?>" idLC="<?=$idLivroCaixa?>" qs="&categoria=<?=$categoria?>&nome=<?=$idLoginPagamento?>&dataInicio=<?=date('d/m/Y',strtotime($dataInicio))?>&dataFim=<?=date('d/m/Y',strtotime($dataFim))?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
                            <a href="Recibo_distribuicao_download.php?id_pagto=<?=$idPagto?>" title="Salvar"><i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 20px;line-height: 20px;"></i></a>
                        </td>
                        <td class="td_calendario"><a href="meus_dados_socio.php?editar=<?=$id?>"><?=$nome?></a></td>
                        <td class="td_calendario"><?=$periodo?></td>
                        <td class="td_calendario" align="right"><?=$data_pagto?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_liquido,2,',','.')?></td>
                    </tr>
	<?
					$total_valor_liquido += ($op_simples == 0 ? $valor_liquido : $valor_bruto);	
		
					// FIM DO LOOP
		
				}
	
	?>
				<tr>
          <th style="background-color: #999; font-weight: normal" colspan="4" align="right">Totais:&nbsp;</th>
          <th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_liquido,2,',','.')?></th>
				</tr>
		<?
				$total_valor_liquido = 0;	
	
	
			}else{
	?>
                <tr>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                </tr>
	<?		
		}
		
	?>
	
		</table>


</div>

<div style="clear:both;">

	</div>
</div>
<?php include 'rodape.php' ?>