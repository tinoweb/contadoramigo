<?php include 'header_restrita.php' ?>

<?
$_SESSION['categoria'] = 'estagiários';


$arrUrl_origem = explode('/',$_SERVER['PHP_SELF']);
// VARIAVEL com o nome da página
$pagina_origem = $arrUrl_origem[count($arrUrl_origem) - 1];

$_SESSION['paginaOrigemEstagiarios'] = $pagina_origem;
?>

<script>
$(document).ready(function(e) {	

		$.formataDataEn = function(data){
			var dia = 0;
			var mes = 0;
			var ano = 0;
			dia = data.substr(0,2);
			mes = data.substr(3,2);
			ano = data.substr(6,4);
			return ano + '-' + mes + '-' + dia;
		}
	
		
		$('.link_atualiza').bind('click',function(e){
			e.preventDefault();
			var estagiario_selecionado = $('#txtnome').val();
			if(estagiario_selecionado > 0){
				location.href='meus_dados_estagiarios.php?editar=' + estagiario_selecionado;
			}else{
				alert('Selecione um estagiário.');
				//location.href='meus_dados_estagiarios.php';
			}

		});

	// DITA A AÇÃO DO BOTAO LIMPAR
	$('#btnLimparCampos').click(function(){
		history.go(0);
	});

	// BOTAO RESPONSAVEL POR CADASTRAR OS DADOS DO PAGAMENTO NO BANCO DE DADOS E EFETUAR A GERAÇÃO DO RECIBO
	$('#btnGerarRecibo').click(function(){
		var $botao = $(this);
		
		if($('#txtnome').val() == ''){
			alert('Escolha um estagiário.');
			$('#txtnome').focus();
			return false;
		}

		if($('#DataPgto').val() == ''){
			alert('Preencha a data de pagamento.');
			$('#DataPgto').focus();
			return false;
		}
					
		if($('#ValorBruto').val() == ''){
			alert('Preencha o valor da Bolsa auxílio.');
			$('#ValorBruto').focus();
			return false;
		}

		valor_bruto = parseFloat($('#ValorBruto').val().replace('.','').replace(',','.'));
		if(valor_bruto > <?=$ValorBruto1?>){
			alert('A Bolsa Auxílio está acima do limite isento de IR.\rIndique um valor menor que R$ <?=number_format($ValorBruto1,2,",",".")?>');
			$('#ValorBruto').focus();
			return false;
		}

		var 
			$currURL = location.href
			, $Date = new Date($.formataDataEn($('#DataPgto').val()))
			, $DateHoje = new Date()
			, $mesHoje = ($DateHoje.getMonth() + 1)
			, $anoHoje = ($DateHoje.getFullYear())
		;
				
		$.ajax({
		  url:'estagiarios_checa.php?id=<?=$_SESSION["id_empresaSecao"]?>&est=' + $('#txtnome').val() + '&data=' + $('#DataPgto').val(),
		  type: 'get',
		  cache: false,
		  async: false,
		  beforeSend: function(){
			$("body").css("cursor", "wait");
		  },
		  success: function(retorno){
			$("body").css("cursor", "auto");
			
			// SE HOUVER PAGAMENTO PARA O MESMO ESTAGIARIO NO MES, É EXIBIDA A ALERTA E O ENVIO É CANCELADO
			if(retorno == '1'){
				alert('Não foi possível gerar o Recibo. Verificamos que já existe um pagamento a este estagiário, efetuado no mesmo mês. A emissão de outro recibo poderia provocar discrepâncias no cálculo dos impostos. Delete o recibo anterior e emita um novo com o valor global.');
				return false;
			}else{
				
				
					var $data = $('#formGeraRecibo').serialize();
					$.ajax({
						url:'Recibo_estagiarios_download.php?acao=ins',
						type: 'post',
						data: $data,
						cache: false,
						async: true,
						beforeSend: function(){
							$("body").css("cursor", "wait");
						},
						success: function(retorno){

							$("body").css("cursor", "auto");
							if(retorno > 0){
								



								$('#btSIMAvisoLivroCaixa').attr('idPagto',retorno);
								$('#aviso-livro-caixa').css({
									'top':($('#caixa-botoes').offset().top - 218) + 'px'
									, 'left':($('#caixa-botoes').offset().left + 100) + 'px'
								}).fadeIn(100);
								
							}
						}
					});
					
					
					
				
				//$('#formGeraRecibo').attr('action','Recibo_estagiarios_download.php?acao=ins');
				$botao.css('display','none');
				$('#btnNovoPagto').css('display','block');
			}

//			$('#formGeraRecibo').submit();
							
		  }
		});
		
	});





		
		$('#btSIMAvisoLivroCaixa').bind('click',function(){
			
			var $data = $('#formGeraRecibo').serialize(), $this = $(this);
					$data += "&nome=" + $('#txtnome option:selected').text() + "&idPagto=" + $this.attr('idPagto');
	
			var 
				$currURL = location.href
				, $Date = new Date($.formataDataEn("10/" + $('#DataPgto').val().substr(3,2) + "/" + $('#DataPgto').val().substr(6,4)))
				, $DateHoje = new Date()
				, $mesHoje = ($DateHoje.getMonth() + 1)
				, $anoHoje = ($DateHoje.getFullYear())
			;
			
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
					}
				}
			});
						
		});
		
		
		$('#btNAOAvisoLivroCaixa').bind('click',function(){										
			// faz o post do formulário de pesquisa para listar os do mes do pagamento recem cadastrado
			var 
				$Date = new Date($.formataDataEn("10/" + $('#DataPgto').val().substr(3,2) + "/" + $('#DataPgto').val().substr(6,4)))
			;
			
			$('#periodoAno').val($Date.getFullYear());
			$('#periodoMes').val($Date.getMonth()+1);
			$('#hddTipoFiltro').val('mes');

			$('#form_filtro').submit();			
		});
		
		

	// BOTAO RESPONSAVEL POR ATUALIZAR A PAGINA PARA UM NOVO PAGAMENTO
	$('#btnNovoPagto').click(function(e){
		e.preventDefault();
		location.reload();
	});

})
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


<h1>Pagamentos</h1>
<h2>Estagiários</h2>

 <div style="width:80%; margin-bottom:20px">
  A contratação de estagiários pode ser uma boa alternativa para quem precisa de uma ajuda 
  <a href="simples_orientacoes.php"></a>em seu negócio, mas não tem dinheiro para arcar com os custos de um profissional efetivo. Você não precisará pagar nenhum imposto,  nem 13º salário ou férias e pode rescindir o contrato a qualquer momento, sem aviso prévio. Existem, porém, alguns pontos  a sem observados:<br />

<ul>
  <li>O prazo máximo de permanência do estagiário na empresa é de 2 anos;</li>
  <li>A carga horária máxima é de 6 horas por dia;</li>
  <li>O candidato deve estar cursando regularmente uma faculdade ou o ensino médio;</li>
  <li>A jornada de trabalho deve ser reduzida pela metade nos períodos de provas;</li>
  <li>Após 1 ano de estágio, o aprendiz terá direito a um recesso (férias) remunerado de 30  dias, a ser gozado preferencialmente durante suas férias escolares (mas não há o acréscimo de 1/3 no salário como ocorre com funcionários contratados);</li>
  <li>É preciso enviar a cada 6 meses  à instituição de ensino o relatório de atividades do estagiário</li>
  <li>Você precisa sempre verificar a regularidade da situação escolar do estudante, pois a conclusão e o abandono do curso, ou trancamento de matrícula, são eventos que impedem a continuidade das atividades de estágio;</li>
  <li>É obrigatória ainda a contratação de um seguro de acidentes pessoais para o estagiário (mas é bem barato);
    </li>
    <li>O estagiário tem direito também a auxílio-transporte, cujo valor deve ser fixado de comum acordo.</li>
</ul>
<strong>ATENÇÃO: Descumprir qualquer uma dessas condições descaracterizará a condição de estágio e o aprendiz passará a  gozar de direitos trabalhistas como empregado efetivo.</strong>
</div>



<h2>Como contratar</h2>
 <div style="width:80%; margin-bottom:20px">
É preciso firmar um <strong>Termo de Compromisso</strong> com o aluno e a instituição de ensino, ao qual deverá ser anexado um <strong>Plano de Atividades</strong>. Normalmente a instituição de ensino já tem toda essa papelada pronta para ser preenchida e basta o aluno solicitá-la na secretaria de sua escola). Não se esqueça do <strong>seguro de acidentes pessoais</strong>. Trata-se de um item indispensável para que o contrato tenha validade. Entre as opções pesquisadas pelo Contador Amigo, a mais prática e econômica nos pareceu o do <a href="http://www.seguroestagiario.com.br/" target="_blank">Seguro Facil Net</a>, que trabalha com a Porto Seguro e pode ser feito inteiramente pela Internet.<br />
<br />
Você pode também contratar estagiários através de entidades voltadas especificamente para este fim, como o <a href="http://www.ciee.org.br/portal/index.asp" target="_blank">CIEE</a>, que possui sedes nas principais cidades do Brasil.
</div>


<h2>Emissão de Recibo da Bolsa-Auxílio</h2>


	<form id="formGeraRecibo" action="Recibo_estagiarios_download.php" method="post">
		<input type="hidden" name="hddCategoria_livro_caixa" value="Estagiários">
		<!--nome -->
		<label for="txtnome" style="width:120px; text-align:right; margin-right:10px">Nome do estagiário: </label> 
		<select name="txtnome" id="txtnome">
			<option value="">Selecione o estagiário</option>
<?
			$query = mysql_query('SELECT id, nome FROM estagiarios WHERE id_login = '.$_SESSION["id_empresaSecao"].' ORDER BY nome');
			while($dados = mysql_fetch_array($query)){
				echo "<OPTION value=\"".$dados['id']."\">".$dados['nome']."</OPTION>";
			}
?>
		</select>&nbsp;&nbsp;<a href="meus_dados_estagiarios.php?act=new">cadastrar novo estagiário</a>&nbsp;&nbsp;ou&nbsp;&nbsp;<a class="link_atualiza" href="#">alterar dados de estagiário já cadastrado</a>
	  <div style="clear:both; height:5px"></div>

    <!--cpf--><!--data -->
    <label for="DataPgto" style="margin-right:10px">Data do pagamento:</label>
    <input name="DataPgto" id="DataPgto" type="text" size="12" maxlength="50" class="campoData" value="" />
    (dd/mm/aaaa)<br />
    <div style="clear:both; height:5px"></div>
  
    <!--valor bruto -->
    <label for="ValorBruto" style="margin-right:25px">Bolsa auxílio (R$)</label>
    <input name="ValorBruto" id="ValorBruto" type="text" size="30" maxlength="12" class="current" />
    <div style="clear:both; height:20px"></div>
    
    <!--botao calculo -->
    <div id="caixa-botoes">
	    <input name="btnGerarRecibo" id="btnGerarRecibo" type="button" value="Gerar Recibo" />
	    <input type="button" id="btnNovoPagto" name="btnNovoPagto" value="Gerar novo Recibo" style="position: relative; display: none; " />
			<!--<input name="btnLimparCampos" id="btnLimparCampos" type="button" value="Limpar" style="position: relative; margin-left: 10px; display: inline; " />-->
		</div>

	</form>
  
  
  
  



<?

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


	/*$sql = "SELECT 
				MIN(YEAR(pgto.data_pagto)) ano
			FROM 
				dados_pagamentos pgto
			WHERE 
				pgto.id_login='" . $_SESSION["id_empresaSecao"] . "'
			LIMIT 0,1";*/
	
	// Informa o ano atual;
	$anoInicioPagamentos = date('Y');
	
	// Pega o menor ano para o filtro.
	$sql = "SELECT MIN(YEAR(data_pagto)) ano 
			FROM dados_pagamentos 
			WHERE id_login = '" . $_SESSION["id_empresaSecao"] . "' 
			AND id_estagiario != 0 LIMIT 1";
	
	$consulta = mysql_query($sql);
	
	// Verifica se  existe lançamento de pagamento e pega a menor data.
	if(mysql_num_rows($consulta) > 0){
		$rsAnoInicial = mysql_fetch_array($consulta);
		
		if(!empty($rsAnoInicial['ano'])){
			$anoInicioPagamentos = $rsAnoInicial['ano'];
		}
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
			montaCombo('populaCombo','area=folha_pagto&tipo=estagiários&id=<?=$_REQUEST["nome"]?>','nome');
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
			var QS = $(this).attr("qs");
			var queryString = "", queryString2 = "";
			var mensagem = "Você tem certeza que deseja excluir este Pagamento?";

			queryString = "excluir=" + idPagto;

			$('#aviso-delete-livro-caixa').fadeOut(100);

			queryString = "excluir=" + idPagto;

			if(idLivroCaixa != 0 && idLivroCaixa != ''){
				mensagem = "Deseja excluir este lançamento do Livro Caixa?";
				queryString2 = "&idLivroCaixa=" + idLivroCaixa;
				queryString2 += QS;				
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


  <h2 style="margin-top: 10px">Pagamentos efetuados</h2>

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

		if (empty($dataInicio)) {
			//Busca o ultimo pagamento
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

		if (empty($dataFim)) {
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
        <?php 
		  
		  	$option = '';
		  
			for($i = date('Y'); $i >= $anoInicioPagamentos; $i--) {
		  		$option .= '<option value="'.$i.'"'.($comparaAno == $i ? " selected" : "").'>'.$i.'</option>';
			}
		  
		  	echo $option;		  
		 ?>
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
					, pgto.valor_bruto
					, pgto.INSS
					, pgto.IR
					, pgto.ISS
					, pgto.valor_liquido
					, pgto.data_emissao
					, pgto.data_pagto  
					, pgto.codigo_servico
					, pgto.descricao_servico
					, pgto.idLivroCaixa
					, est.id id
					, est.nome nome
					, est.cpf cpf
				FROM 
					dados_pagamentos pgto
					inner join estagiarios est on pgto.id_estagiario = est.id
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
				ORDER BY data_pagto DESC
				
				";
		
		//	echo $sql . $resDatas . $resColuna . $resOrdem;
		
	
		$resultado = mysql_query($sql . $resDatas . $resColuna . $resOrdem)
		or die (mysql_error());
	
		return $resultado;
	}
		 
 	function PegaUltimoPagamento(){
		
		$sql2 = "SELECT * FROM dados_pagamentos WHERE id_login = '" . $_SESSION["id_empresaSecao"] . "' AND id_estagiario != 0 ORDER BY data_pagto DESC LIMIT 1";
		
		//executa consulta.
		$result2 = mysql_query($sql2);
		return $result2;
	}
	
	//Verifica se existe cadastro na data informada.
	$resultado = MontagemDaListagemDosPagamentos($dataInicio, $dataFim, $idNome);
			
	?>

      <table width="98.8%" cellpadding="5" style="margin-bottom:25px; min-width: 450px;">
				  <tr>
            <th width="7%">Ação</th>
            <th width="30%">Nome</th>
            <th width="9%">Data</th>
            <th width="9%">Valor Bruto</th>
            <th width="9%">INSS</th>
            <th width="9%">IR</th>
            <th width="8%">ISS</th>
            <th width="9%">Valor Líquido</th>
          </tr>
        
	<?	
			if(mysql_num_rows($resultado) > 0){				
		
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idPagto 	= $linha["id_pagto"];
					$id 	= $linha["id"];
					$nome 	= $linha["nome"];
		
					$valor_bruto 	= $linha["valor_bruto"];
					$INSS		 	= $linha["INSS"];
					$dependentes	= $linha["dependentes"];
					$desc_dep 		= $linha["desconto_dependentes"];
					$IR			 	= $linha["IR"];
					$ISS		 	= $linha["ISS"];
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
                          <a href="#" class="excluirPagamento" idpg="<?=$idPagto?>" idLC="<?=$idLivroCaixa?>" qs="&categoria=<?=$categoria?>&nome=<?=$idLoginPagamento?>&dataInicio=<?=date('d/m/Y',strtotime($dataInicio))?>&dataFim=<?=date('d/m/Y',strtotime($dataFim))?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>" title="Excluir"><i class="fa fa-trash-o iconesAzul iconesGrd"></i></a>
<!--                            <a href="#" onClick="if (confirm('Você tem certeza que deseja excluir este Pagamento?'))location.href='folha_pagamentos_excluir.php?excluir=<?=$idPagto?>&categoria=<?=$categoria?>&dataInicio=<?=date('d/m/Y',strtotime($dataInicio))?>&dataFim=<?=date('d/m/Y',strtotime($dataFim))?>&periodoMes=<?=$comparaMes?>&periodoAno=<?=$comparaAno?>&hddTipoFiltro=<?=$tipoFiltro?>';"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
-->                            <a href="Recibo_estagiarios_download.php?id_pagto=<?=$idPagto?>" title="Salvar"><i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 20px;line-height: 20px;"></i></a>
                        </td>
                        <td class="td_calendario"><a href="meus_dados_estagiarios.php?editar=<?=$id?>"><?=$nome?></a></td>
                        <td class="td_calendario" align="right"><?=$data_pagto?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_bruto,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($INSS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($IR,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($ISS,2,',','.')?></td>
                        <td class="td_calendario" align="right"><?=number_format($valor_liquido,2,',','.')?></td>
                    </tr>
        <?
			
					$total_INSS += $INSS;
		
					$total_valor_bruto += $valor_bruto;
					$total_IR += $IR;
					$total_ISS += $ISS;
					$total_valor_liquido += $valor_liquido;	
		
					// FIM DO LOOP
				}
	
	?>
				<tr>
					<th style="background-color: #999; font-weight: normal" colspan="3" align="right">Totais:&nbsp;</th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_bruto,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_INSS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_IR,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_ISS,2,',','.')?></th>
					<th style="background-color: #999; font-weight: normal" align="right"><?=number_format($total_valor_liquido,2,',','.')?></th>
				</tr>
		<?
				$total_INSS = 0;
				$total_valor_bruto = 0;
				$total_IR = 0;
				$total_ISS = 0;
				$total_valor_liquido = 0;	
	
			}else{
		?>
				<tr>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
					<td class="td_calendario">&nbsp;</td>
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
    
  
  

<!--layer para cadastro estagiario -->

<div id="novo_estagiario" class="layer_branco" style="width:430px; border:#ccc solid 1px;  position:absolute; left:50%; top:50%; margin-left:-200px; margin-top:-220px; display:none">
<div style="text-align:right; margin-right:20px; margin-top:15px"><a href="javascript:fechaDiv('novo_estagiario')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
<div style="margin-bottom:0px; padding:20px; padding-top:0px">
<div class="tituloVermelho" style="margin-bottom:20px">Cadastro de Estagiário</div>
<form name="novoEstagiario" id="novoEstagiario" method="post" style="display:inline">
<input type="hidden" name="id_login" value="<?=$_SESSION["id_empresaSecao"]?>" />
    
    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px;"><label for="nome">Nome:</label></div><div style="float:left;"><input name="nome" id="nome" type="text" size="40" maxlength="50" alt="Nome" style="width:300px" /></div>
     <div style="clear:both;"></div>
    </div>

    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cpf">CPF:</label></div><div style="float:left;"><input name="cpf" id="cpf" type="text" size="14" maxlength="14" alt="CPF" class="cpf"  style="width:100px" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:25px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="rg">RG:</label></div><div style="float:left;"><input name="rg" id="rg" type="text" size="14" maxlength="12" alt="RG" class="rg" style="width:100px" /></div>
    <div style="clear:both;"></div>
    </div>

    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="endereco">Endereço:</label></div><div style="float:left;"><input name="endereco" id="endereco" type="text" size="40" maxlength="50" alt="Endereço" style="width:300px"/></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cep">Cep:</label></div><div style="float:left;"><input name="cep" id="cep" type="text" maxlength="9" alt="CEP" class="cep" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:25px;"> 
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:3px"><label for="cidade">Cidade:</label></div><div style="float:left;"><input name="cidade" id="cidade" type="text" maxlength="50" alt="Cidade" /></div>
    <div style="clear:both;"></div>
    </div>
    
    <div style="height:50px;">
    <div style="float:left; width:60px; text-align:right; margin-right:3px; padding-top:4px"><label for="estado">Estado:</label></div>
    <div style="float:left;">
    <select name="estado" id="estado" alt="Estado">
  		  <option value=""></option>
          <option value="AC">AC</option>
          <option value="AL">AL</option>
          <option value="AM">AM</option>
          <option value="AP">AP</option>
          <option value="BA">BA</option>
          <option value="CE">CE</option>
          <option value="DF">DF</option>
          <option value="ES">ES</option>
          <option value="GO">GO</option>
          <option value="MA">MA</option>
          <option value="MG">MG</option>
          <option value="MS">MS</option>
          <option value="MT">MT</option>
          <option value="PA">PA</option>
          <option value="PB">PB</option>
          <option value="PE">PE</option>
          <option value="PI">PI</option>
          <option value="PR">PR</option>
          <option value="RJ">RJ</option>
          <option value="RN">RN</option>
          <option value="RO">RO</option>
          <option value="RR">RR</option>
          <option value="RS">RS</option>
          <option value="SC">SC</option>
          <option value="SE">SE</option>
          <option value="SP">SP</option>
          <option value="TO">TO</option>
        </select></div>
    <div style="clear:both;"></div>
    </div>
    
    
   <input name="btCadastroNovo" id="btCadastroNovo" type="button" value="Cadastrar" />
</form>
</div>

</div>

<script>		
$(document).ready(function(e) {
	
	// BOTAO RESPONSAVEL PELO ENVIO DOS DADOS DO NOVO ESTAGIARIO CADASTRADO NA JANELA MODAL DESTA MESMA PAGINA
    $('#btCadastroNovo').click(function(){
		if($('#cpf').val()!='' && $('#pis').val() != ''){
			$.ajax({
				url:'meus_dados_estagiarios_checa.php?idLogin=' + $('#id_login').val() + '&cpf=' + $('#cpf').val(),
				type: 'get',
				cache: false,
				async: true,
				success: function(retorno){
					if(retorno == 1){
						alert('Já existe um estagiário cadastrado com esses dados!');  
						return false;
					}
				}
			});
		}

		if( $('#nome').val() == ''){					alert('Preencha o campo ' + $('#nome').attr('alt')); $('#nome').focus(); return false}
		if( $('#cpf').val() == ''){						alert('Preencha o campo ' + $('#cpf').attr('alt')); $('#cpf').focus(); return false}
		if( $('#rg').val() == ''){						alert('Preencha o campo ' + $('#rg').attr('alt')); $('#rg').focus(); return false}
		if( $('#endereco').val() == ''){				alert('Preencha o campo ' + $('#endereco').attr('alt')); $('#endereco').focus(); return false}
		if( $('#cep').val() == ''){						alert('Preencha o campo ' + $('#cep').attr('alt')); $('#cep').focus(); return false}
		if( $('#cidade').val() == ''){					alert('Preencha o campo ' + $('#cidade').attr('alt')); $('#cidade').focus(); return false}
		if( $('#estado').val() == ''){					alert('Preencha o campo ' + $('#estado').attr('alt')); $('#estado').focus(); return false}

		var arrData = $('#novoEstagiario').serialize();
		$.ajax({
			url: 'dados_estagiario_gravar.php',
			data: arrData,
			type: 'POST',
			cache: false,
			beforeSend: function(){
				$('body').css('cursor','wait');
			},
			success: function(result){
				$('body').css('cursor','default');
				if(result != ""){
					alert('Estagiário cadastrado');
					$('#txtnome').html(result);
					$('#novo_estagiario').css('display','none');
					var arrForm = $('#novoEstagiario').serializeArray();
					$.each(arrForm, function(i, objCampo){
						switch($("#novoEstagiario :input[name="+this.name+"]").attr('type')){
							case 'text':
								$("#novoEstagiario :input[name="+this.name+"]").attr('value','');
							break;
							case 'select-one':
								$('#'+this.name).attr('value','');
							break;
							case 'checkbox':
								$("#novoEstagiario :input[name="+this.name+"]").attr('checked','');
							break;
							case 'radio':
								$("#novoEstagiario :input[name="+this.name+"]").attr('checked','');
							break;
						}
					});
				}else{
					alert('Erro no cadastramento do estagiário');
				}
				//$('#testaResultado').html(result).show();
			}
		});
	});
	

})
</script>
<!--fim do layer para cadastro de estagiário -->

</div>

<?php include 'rodape.php' ?>