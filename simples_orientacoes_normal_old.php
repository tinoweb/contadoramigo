<?php include 'header_restrita.php' ;?>
<?php
// ARRAY CONTENDO CHECKS QUE NÃO TEM CONTEUDO ASSOCIADO
$check_nao_mostrar_pagina = array('01','05','11','25','31','36','39');

$criterios_retensiveis = array('7739003','7732202','7410202','8121400','8122200','8129000','8130300','5223100','8011101','5211701','5211702','5211799','5212500','9001901','9001902','9001903','9001904','9001905','9001999','9102301','9103100','9200301','9200302','9200399','9312300','9313100','9319101','9319199','9321200','9329801','9329802','9329803','9329804','9329899','4912402','4921301','4923001','4924800','4929901','4929903','4930201','4950700','5021101','5022001','5091201','5099801','4110700','4120400','4211101','4211102','4212000','4213800','4221901','4221902','4221903','4221904','4221905','4222701','4222702','4223500','4291000','4292801','4292802','4299501','4299599','4311801','4311802','4312600','4313400','4319300','4321500','4322301','4322302','4322303','4329101','4329102','4329103','4329104','4329105','4329199','4330401','4330402','4330403','4330404','4330405','4330499','4391600','4399101','4399102','4399103','4399104','4399105','4399199','8230001','7810800','7820500','7830200','5240101','5222200');

$countAtividades3 = 0;
$countAtividades3R = 0;
$countAtividades4 = 0;
$countAtividades4R = 0;
$countAtividades5 = 0;
$countAtividades5R = 0;
$countAtividades6 = 0;
$countAtividades6R = 0;


$acrescentaSalarios = 0; // VARIAVEL DE CONTROLE QUE DETERMINA SE DEVE SER SOMADO 1 NA NAVEGAÇÃO DOS PASSOS - ANEXO V
$acrescentaAnexoIV = 0; // VARIAVEL DE CONTROLE QUE DETERMINA SE DEVE SER SOMADO 1 NA NAVEGAÇÃO DOS PASSOS - ANEXO IV
$ja_mostrou = false; // VARIAVEL QUE CONTROLA SE JÁ FOI MOSTRADA A PAGINA COM INSTRUÇÕES PARA PREENCHIMENTO DE SALARIOS - ANEXO V
$mostrar_pagina = false;


echo "<pre>";
	print_r($_SESSION['passou_direto']);
echo "</pre>";


// VARIAVEL QUE CONTROLA SE VEIO DIRETO DE UMA ATIVIDADE QUE NÃO PRECISA DE ESCOLHA DE OPÇÃO DE RETENÇÃO
if($_SESSION['passou_direto'] == 0){

	// PERCORRENDO A VARIAVEL DE SESSAO QUE CONTEM A ARRAY DE CNAEs PARA MONTAR OS CHECKS E DIVS DA PAGINA
	foreach($_SESSION['cnaes_empresa_mes'] as $cnae){
	
		// PREGANDO A DESCRICAO DO CNAE
		$descr_cnae = mysql_fetch_array(mysql_query("SELECT * FROM cnae WHERE cnae = '" .  $cnae . "'"));
		
		// TIRANDO OS CARACTERES ESPECIAIS PARA MONTAR O NOME DO CAMPO DO FORM ANTERIOR
		$CNAE = str_replace("/","",str_replace("-","",$cnae));

		// MONTANDO STRING COM OS VALORES DOS CAMPOS QUE VIERAM DO FORM ANTERIOR (lista de checks)
		$checks .= $_REQUEST['marcar_check_'.$CNAE] . ';';
		
		// DE ACORDO COM O ANEXO QUE VEIO DO FORM ANTERIOR, DETERMINAR AS ATIVIDADES PARA CADA ANEXO
		switch($_REQUEST['anexo_'.$CNAE]){
			case 'I':
				$atividades1 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			break;
			case 'II':
				$atividades2 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			break;
			case 'IIc':
				$atividades2C .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			break;
			case 'III':
				$countAtividades3 += 1;
				$atividades3 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
				if(in_array($CNAE,$criterios_retensiveis)){
					$countAtividades3R += 1;
					$atividades3Retensiveis .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
				}
			break;
			case 'IV':
				$countAtividades4 += 1;
				$atividades4 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
				if(in_array($CNAE,$criterios_retensiveis)){
					$countAtividades4R += 1;
					$atividades4Retensiveis .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
				}
				$acrescentaAnexoIV = 1;
			break;
			case 'V':
				$countAtividades5 += 1;
				$atividades5 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
				if(in_array($CNAE,$criterios_retensiveis)){
					$countAtividades5R += 1;
					$atividades5Retensiveis .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
				}
				$acrescentaSalarios = 1;
			break;
			case 'VI':
				$countAtividades6 += 1;
				$atividades6 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
				if(in_array($CNAE,$criterios_retensiveis)){
					$countAtividades6R += 1;
					$atividades6Retensiveis .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
				}
				$acrescentaSalarios = 1;
			break;
		}
	
	}

echo "<pre>";
	print_r($checks);
echo "</pre>";

	
	
	// CRIANDO UM ARRAY COM OS CHECKs QUE VIERAM DO FORM ANTERIOR
	$arr_checks = explode(';',substr($checks,0,strlen($checks)-1));
	
	// ARRAY QUE CONTEM OS PREFIXOS DE ARQUIVOS DE CHECKS QUE NÃO POSSUEM PAGINAS DE CONTEUDO
	$arr_checks_unique = (array_diff($arr_checks,$check_nao_mostrar_pagina));
	
	// RETIRA OS REGISTROS DUPLICADOS
	$arr_checks_unique = array_unique($arr_checks_unique);
	
}else{
	
	echo "aqui 2";
	
	$cnae = $_SESSION['cnaes_empresa_mes'][0];
	
	// PREGANDO A DESCRICAO DO CNAE
	$descr_cnae = mysql_fetch_array(mysql_query("SELECT * FROM cnae WHERE cnae = '" .  $cnae . "'"));
	
	// TIRANDO OS CARACTERES ESPECIAIS PARA MONTAR O NOME DO CAMPO DO FORM ANTERIOR
	$CNAE = str_replace("/","",str_replace("-","",$cnae));
	//echo 'marcar_check_'.$CNAE . '->' . $_SESSION['marcar_check_'.$CNAE] . "<BR>";
	//echo 'anexo_'.$CNAE . '->' . $_SESSION['anexo_'.$CNAE] . "<BR>";
	$checks = $_SESSION['marcar_check_'.$CNAE].";";
	
	// DE ACORDO COM O ANEXO QUE VEIO DO FORM ANTERIOR, DETERMINAR AS ATIVIDADES PARA CADA ANEXO
	switch($_SESSION['anexo_'.$CNAE]){
		case 'I':
			$atividades1 = "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
		break;
		case 'II':
			$atividades2 = "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
		break;
		case 'IIc':
			$atividades2C .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
		break;
		case 'III':
			$countAtividades3 += 1;
			$atividades3 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
    		if(in_array($CNAE,$criterios_retensiveis)){
				$countAtividades3R += 1;
				$atividades3Retensiveis .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			}
		break;
		case 'IV':
			$countAtividades4 += 1;
			$atividades4 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			if(in_array($CNAE,$criterios_retensiveis)){
				$countAtividades4R += 1;
				$atividades4Retensiveis .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			}
			$acrescentaAnexoIV = 1;
		break;
		case 'V':
			$countAtividades5 += 1;
			$atividades5 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			if(in_array($CNAE,$criterios_retensiveis)){
				$countAtividades5R += 1;
				$atividades5Retensiveis .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			}
			$acrescentaSalarios = 1;
		break;
		case 'VI':
			$countAtividades6 += 1;
			$atividades6 .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			if(in_array($CNAE,$criterios_retensiveis)){
				$countAtividades6R += 1;
				$atividades6Retensiveis .= "<li><strong>" . ltrim($descr_cnae['denominacao']) . "</strong></li>";
			}
			$acrescentaSalarios = 1;
		break;
	}

	// CRIANDO UM ARRAY COM OS CHECKs QUE VIERAM DO FORM ANTERIOR
	$arr_checks = explode(';',substr($checks,0,strlen($checks)-1));
	
	$arr_checks_unique = (array_diff($arr_checks,$check_nao_mostrar_pagina));
		
		
		
}

$sql = "SELECT t1.cnae FROM dados_da_empresa_codigos t1 WHERE t1.id='" . $_SESSION["id_empresaSecao"] . "' AND  t1.tipo='1' LIMIT 0,1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha = mysql_fetch_array($resultado);


$check_cnae = array('412','421','422','429','431','432','433','439');
$cnae_principal = substr($linha['cnae'],0,3);

$resposta = "NÃO";
if(in_array($cnae_principal, $check_cnae)){
	$resposta = "SIM";
}


/*
print_r($arr_checks) . "<BR>";
print_r($arr_checks_unique) . "<BR>";
*/
?>
<script type="text/javascript">
var anterior = 0;
var atual = 1;
var proxima = 2;
var total = 11 + <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV) - 1?>;

var atualpasso7 = 0;
<?
// O PASSO 8, ONDE HAVIA A EXPLICAÇÃO DOS CHECKs PASSOU A SER PASSO 6
// O PASSO 6 ANTIGO, ONDE HAVIA A EXPLICAÇÃO DOS CHECKs PASSOU A SER PASSO 7
?>

$(document).ready(function(e) {

	$('#lblAtualPagina').html(atual);
	$('#lblTotalPaginas').html(total - <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV) - 1?>);

	$('#btAnterior').click(function(e){
		e.preventDefault();
		if(atual > 1){
			$('.passos').eq(atual-1).css('display','none');
			$('.passos').eq(anterior-1).css('display','block');
			
			atual--;
			proxima--;
			anterior--;
			
			$('#lblAtualPagina').html(atual);

			if(atual > 6 && atualpasso7 > 0 && atual <= 6 + <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)?> ){
				if( total > 11 ){
					$('#lblAtualPagina').html('7.'+atualpasso7);
					atualpasso7--;
				}
			}else{
				
				if(atual <= 6){
					atualpasso7 = 0;
				}
				if(atual >= (6 + <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)?>)){
					$('#lblAtualPagina').html(atual - <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)-1?>);
				}else{
					$('#lblAtualPagina').html(atual);
				}
			}
/*			$('#passoanterior').html(anterior);
			$('#passoatual').html(atual);
			$('#passoproxima').html(proxima);*/
		}

	});
	
	
	$('#btProxima').click(function(e){
		e.preventDefault();
		if(atual < $('.passos').size()){
			$('.passos').eq(atual-1).css('display','none');
			$('.passos').eq(proxima-1).css('display','block');
			
			atual++;
			proxima++;
			anterior++;
			
			$('#lblAtualPagina').html(atual);

			if(atual > 6 && atualpasso7 < <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)?>){
				if( total > 11 ){
					atualpasso7++;
					$('#lblAtualPagina').html('7.'+atualpasso7);
				}
			}
			else{
				if(atual > (6 + <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)?>)){
					// atualpasso7 = <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)+1?>;
					$('#lblAtualPagina').html(atual - <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)-1?>);
				}else{
					$('#lblAtualPagina').html(atual);
				}
			}

			// se for um sub item do passo 7
			// if(atual > 6 && atual <= (6 + <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)?>)  ){

			// 	atualpasso7++

			// 	$('#lblAtualPagina').html('7');
				
			// 	if( '7.' + (atualpasso7) === '7.2' )
			// 		$("#btProxima").click();

			// }else{

			// 	if(atual > (6 + <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)?>)){
			// 		atualpasso7 = <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)+1?>;
			// 		$('#lblAtualPagina').html(atual - <?=(count($arr_checks_unique) + $acrescentaSalarios + $acrescentaAnexoIV)-1?>);
			// 	}else{
			// 		$('#lblAtualPagina').html(atual);
			// 	}

			// }
/*			$('#passoanterior').html(anterior);
			$('#passoatual').html(atual);
			$('#passoproxima').html(proxima);*/
		}

	});
	
});

</script>

<div class="principal">

    <span class="titulo">Impostos e Obrigações - Simples Nacional</span><br />
    <br />
    <div class="tituloVermelho" style="margin-bottom:20px">Apuração do Simples</div>
    
    
    <a class="linkMenu" style="font-size:10px" id="btAnterior" href="#">
        <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp;
    </a>
    <span class="tituloVermelho">Passo <span id="lblAtualPagina"></span> de <span id="lblTotalPaginas"></span></span> 
    <a class="linkMenu" style="font-size:10px" id="btProxima" href="#">
        &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" />
    </a>
    
    <!--passo 1 NOVO -->
    <div id="passo1" class="passos" style="display:block"> 
        <br />
       
        Usando o navegador Chrome, Firefox ou Safari (não use o Internet Explorer) acesse o <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/Servicos/Grupo.aspx?grp=5" target="_blank">Portal do Simples Nacional</a> e vá na opção <strong>PGDAS-D e Defis - até 12/2017</strong>. Clique em <strong>código de acesso</strong> ou em <strong>certificado digital</strong>, para entrar. Se você não tiver nenhum dos dois, <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/controleAcesso/GeraCodigo.aspx" target="_blank">gere agora mesmo seu código de acesso</a>.<br />
        
        <br />
				<img src="images/simples2/entrada_no_simples.png" width="960" height="628" />
    </div>

    
    <!--passo 2 -->
    <div id="passo2" class="passos" style="display:none">
        <br />
        Se você entrou com o certificado digital, cairá na página de entrada do Portal do E-CAC (imagem 1). Clique no botão Simples Nacional e depois no link <strong>PGDAS a partir de 01/2012</strong>. A tela do sistema de apuração do Simples se abrirá (imagem 2). Clique em <strong>Apuração/Calcular Valor Devido</strong>. Se você entrou com o código de acesso, já cairá diretamente no sistema de apuração do Simples. Clique em <strong>Apuração/Calcular Valor Devido</strong>.<br>

IMPORTANTE:</span> Se aparecer uma mensagem solicitando que você informe primeiramente o regime de apuração de receitas a ser adotado, veja <a href="regime_de_apuracao.php">aqui</a> como fazê-lo.
<br>
<br>
<strong>IMAGEM 1</strong><br><br>


<img src="images/entrada_no_simples2.png" width="966" height="519" alt=""/> <br>
<br>


        <strong>IMAGEM 2</strong><br><br>


        <img src="images/simples_passo3.jpg" width="966" height="210" border="1" />
        <br>
<br>

    </div>
    
    <!-- passo 3 -->
    <div id="passo3" class="passos" style="display:none"> 
        <br />
        
        Informe o Período de Apuração (em pagamentos regulares,  o mês imediatamente anterior). Atente para o formato da data (MMAAAA), onde MM é o número do mês e AAAA é o ano. Clique em <strong>Continuar</strong><br />
        <br />
        <img src="images/simples_passo4.jpg" width="961" height="591" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
    </div>
    
    <!-- passo 4 -->
    <div id="passo4" class="passos" style="display:none">
        <br />
        
        No primeiro campo, informe a <strong>receita </strong> do período, exceto exportações  (isto é, a soma das notas fiscais emitidas no período, excetuando-se aquelas emitidas para empresas no exterior). No segundo campo, informe a receita com exportações (a soma das notas emitidas para empresas no exterior).<br />
        <br />
        <img src="images/simples_passo5.jpg" width="966" height="622" style="border-width:1px; border-color:#CCC; border-style:solid"/>
    </div>
        
    <!-- passo 5 -->
    <div id="passo5" class="passos" style="display:none">
		<br />
		<?php if( $user->getCidade() == 'São Paulo' ){ ?>
			Na cidade de São Paulo, entre as empresas optantes pelo Simples, apenas os escritórios contábeis podem optar pelo recolhimento de valores fixos de ISS. Esta opção é feita quando a empresa é aberta e <a href="https://dsup.prefeitura.sp.gov.br/" target="_blank">renovada anualmente</a>. Se for este o caso de sua empresa, preencha abaixo os valores fixos de ISS ou ICMS. Caso contrário, deixe em branco e prossiga.
			Na dúvida, consulte nosso <a href="suporte.php">help desk</a>.
		<?php }else if( $user->getCidade() == 'Brasília' ){ ?>
			Em Brasília, as microempresas optantes pelo Simples com faturamento bruto no ano-calendário de até R$ 120 mil, recolhem o ISS (prestadores de serviços) ou o ICMS (comércio) fixo. Se este for o seu caso, preencha os valores fixos de ISS ou ICMS, caso contrário, deixe em branco e prossiga. Na dúvida, consulte nosso <a href="suporte.php">help desk</a>.
		<?php }else{ ?>
			Alguns municípios possibilitam a determinadas categorias de empresas optarem pelo recolhimento de valores fixos de ISS (prestadores de serviços) ou ICMS (comércio). Isto ocorre geralmente com sociedades <a href="javascript:abreDiv('uniprof')">uniprofissionais</a>. Esta opção é feita quando a empresa é aberta e renovada anualmente.  Se este for o seu caso, preencha os valores fixos de ISS ou ICMS, definidos por seu município. Caso contrário, deixe em branco e prossiga. Na dúvida, consulte nosso <a href="suporte.php">help desk</a>.
<?php } ?>
		<br><br>
		<!-- quadro uniprofisisonal-->
		<div id="uniprof" class="layer_branco" style="position:absolute; top:200px; left:50%; margin-left:-250px; display:none; z-index:2; padding:10px 20px 20px 20px">

<div style="width:100%; text-align:right" ><a href="javascript:fechaDiv('uniprof')"><img src="images/x.png" width="8" height="9" border="0" alt="Fecha janela" title="Fecha janela" /></a></div>

<div style="height:320px; width:500px; overflow-y:scroll;margin-top:5px" >
<div class="tituloAzul">Sociedades Uniprofissionais</div>
      Caracterizam-se como sociedades uniprofissionais as empresas constituídas exclusivamente por profissionais
 habilitados para exercício das atividades de:
<ul>
      <li> Medicina e biomedicina</li>
        <li> Análises clínicas, patologia, eletricidade médica, radioterapia, quimioterapia, ultrasonografia,
          ressonância magnética, radiologia, tomografia e congêneres</li>
        <li>Enfermagem, inclusive serviços auxiliares</li>
        <li>Terapia ocupacional, fisioterapia e fonoaudiologia</li>
        <li>Obstetrícia</li>
        <li>Odontologia</li>
        <li>Ortóptica</li>
        <li>Próteses sob encomenda</li>
        <li>Psicologia</li>
        <li>Medicina veterinária e zootecnia</li>
        <li>Engenharia, agronomia, agrimensura, arquitetura, geologia, urbanismo, paisagismo e
          congêneres (exceto paisagismo)</li>
        <li>Serviços próprio de economistas</li>
        <li>Advocacia</li>
        <li>Auditoria</li>
        <li>Contabilidade, inclusive serviços técnicos e auxiliares</li>
      </ul>
Excluem-se desta categoria as sociedades que:
          <ul>
          <li>Tenham como sócio pessoa jurídica;</li>
          <li>Sejam sócias de outra sociedade;</li>
          <li>Desenvolvam atividade diversa daquela a que estejam habilitados profissionalmente os sócios;</li>
          <li>Tenham sócio que delas participe tão-somente para aportar capital ou administrar;</li>
         <li>Explorem mais de uma atividade de prestação de serviços.</li>
          <li>Terceirizem ou repassem a terceiros os serviços relacionados à atividade da sociedade;</li>
          <li>Caracterizem-se como empresárias ou cuja atividade constitua elemento de empresa;</li>
      <li>Sejam filiais, sucursais, agências, escritório de representação ou contato, ou qualquer outro estabelecimento descentralizado ou relacionado a sociedade sediada no exterior.</li>
      </ul>
      Normalmente as sociedades uniprofissionais são registradas em cartório e o contrato social ou registro é averbado pelo respectivo conselho de classe.<br>
<br>
Caso ainda tenha dúvida, consulte nosso <a href="suporte.php">Help Desk</a>
 </div>
</div>
     <!--fim do ballon uniprofisisonal--> 
<span class="destaque">Se esta tela não apareceu para você,  vá para o próximo passo. </span><br />
<br />
<img src="images/simples2/iss_icms_fixos.png" width="900" height="210" /><br />

    </div>
    
    
    <!--passo 6 --> 
    <div id="passo6" class="passos" style="display:none">
        <br />
        
Marque a(s) opção(es) indicada(s) pelo "tique" <img src="images/simples2/simples_check.png" width="13" height="14"/> na imagem abaixo. O Contador Amigo as identificou baseado nas respostas assinaladas no início deste tutorial. Tome muito cuidado para não selecionar a opção errada, elas são  parecidas entre si!<br /><br />
        
    
        <div style="background-image:url(images/simples2/simples_selecao_2015.jpg); width:966px; height:1324px">
            <div style="height:395px"></div>
            <? // revenda de mercadorias exceto para o exterior ?>
            <div style="margin-left:25px; width:13px; height:16px">
                <img id="check_01" src="images/simples2/<?=in_array('01',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:20px">
                <img id="check_02" src="images/simples2/<?=in_array('02',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:36px">
                <img id="check_03" src="images/simples2/<?=in_array('03',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

            <? // revenda de mercadorias para o exterior ?>
            <div style="margin-left:25px; width:13px; height:17px">
                <img id="check_04" src="images/simples2/<?=in_array('04',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

            <? // Venda de mercadorias industrializadas pelo contribuinte, exceto para o exterior ?>
            <div style="margin-left:25px; width:13px; height:16px">
                <img id="check_05" src="images/simples2/<?=in_array('05',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:39px; width:13px; height:19px">
                <img id="check_06" src="images/simples2/<?=in_array('06',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:39px; width:13px; height:37px">
                <img id="check_07" src="images/simples2/<?=in_array('07',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

            <? // Venda de mercadorias industrializadas pelo contribuinte para o exterior ?>
            <div style="margin-left:25px; width:13px; height:19px">
                <img id="check_08" src="images/simples2/<?=in_array('08',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

            <? // Locação de bens móveis, exceto para o exterior ?>
            <div style="margin-left:25px; width:13px; height:17px">
                <img id="check_09" src="images/simples2/<?=in_array('09',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

            <? // Locação de bens móveis para o exterior ?>
            <div style="margin-left:25px; width:13px; height:19px">
                <img id="check_10" src="images/simples2/<?=in_array('10',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

            <? // Prestação de serviço, exceto para o exterior ?>
            <div style="margin-left:25px; width:13px; height:17px">
                <img id="check_11" src="images/simples2/<?=in_array('11',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_12" src="images/simples2/<?=in_array('12',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_13" src="images/simples2/<?=in_array('13',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_14" src="images/simples2/<?=in_array('14',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_15" src="images/simples2/<?=in_array('15',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_16" src="images/simples2/<?=in_array('16',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_17" src="images/simples2/<?=in_array('17',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_18" src="images/simples2/<?=in_array('18',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_19" src="images/simples2/<?=in_array('19',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_20" src="images/simples2/<?=in_array('20',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_21" src="images/simples2/<?=in_array('21',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_22" src="images/simples2/<?=in_array('22',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_23" src="images/simples2/<?=in_array('23',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:20px">
                <img id="check_24" src="images/simples2/<?=in_array('24',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

            <? // Prestação de serviço para o exterior ?>
						<div style="margin-left:25px; width:13px; height:18px">
                <img id="check_25" src="images/simples2/<?=in_array('25',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:17px">
                <img id="check_26" src="images/simples2/<?=in_array('26',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_27" src="images/simples2/<?=in_array('27',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_28" src="images/simples2/<?=in_array('28',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_29" src="images/simples2/<?=in_array('29',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:22px">
                <img id="check_30" src="images/simples2/<?=in_array('30',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>


            <? // Prestação de serviços de comunicação, exceto para o exterior ?>
            <div style="margin-left:25px; width:13px; height:29px">
                <img id="check_31" src="images/simples2/<?=in_array('31',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_32" src="images/simples2/<?=in_array('32',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_33" src="images/simples2/<?=in_array('33',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_34" src="images/simples2/<?=in_array('34',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:23px">
                <img id="check_35" src="images/simples2/<?=in_array('35',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

            <? // Prestação de serviços de comunicação para o exterior ?>
            <div style="margin-left:25px; width:13px; height:29px">
                <img id="check_36" src="images/simples2/<?=in_array('36',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_37" src="images/simples2/<?=in_array('37',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:22px">
                <img id="check_38" src="images/simples2/<?=in_array('38',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

            <? // Atividades com incidência simultanea de IPI e ISS, exceto para o exterior ?>
            <div style="margin-left:25px; width:13px; height:16px">
                <img id="check_39" src="images/simples2/<?=in_array('39',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_40" src="images/simples2/<?=in_array('40',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:19px">
                <img id="check_41" src="images/simples2/<?=in_array('41',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>
            <div style="margin-left:40px; width:13px; height:21px">
                <img id="check_42" src="images/simples2/<?=in_array('42',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>


            <? // Atividades com incidência simultanea de IPI e ISS para o exterior ?>
            <div style="margin-left:25px; width:13px; height:16px">
                <img id="check_43" src="images/simples2/<?=in_array('43',$arr_checks) ? 'simples_check' : 'fundotransparente'?>.png" width="13" height="14"/>
            </div>

        </div>
    </div>
    
    <!-- passo 7 -->
    <?
    $paginaPasso7 = 0;
    // MONTANDO OS DIVS PARA O PASSO 7
    if(count($arr_checks_unique) >= 1){

			//	retirando as ultimas virgulas
			//$atividades3Extenso = substr($atividades3Extenso, 0, strlen($atividades3Extenso) - 2);// . "</strong>";
			//$atividades4Extenso = substr($atividades4Extenso, 0, strlen($atividades4Extenso) - 2);// . "</strong>";
			//$atividades5Extenso = substr($atividades5Extenso, 0, strlen($atividades5Extenso) - 2);// . "</strong>";
			//$atividades6Extenso = substr($atividades6Extenso, 0, strlen($atividades6Extenso) - 2);// . "</strong>";
			
			//$atividades3Retensiveis = substr($atividades3Retensiveis, 0, strlen($atividades3Retensiveis) - 2);// . "</strong>";
			//$atividades4Retensiveis = substr($atividades4Retensiveis, 0, strlen($atividades4Retensiveis) - 2);// . "</strong>";
			//$atividades5Retensiveis = substr($atividades5Retensiveis, 0, strlen($atividades5Retensiveis) - 2);// . "</strong>";
			//$atividades6Retensiveis = substr($atividades6Retensiveis, 0, strlen($atividades6Retensiveis) - 2);// . "</strong>";

			sort($arr_checks_unique);
			foreach($arr_checks_unique as $check){
				if(
					($check == 13 || $check == 14 || $check == 15)
					|| ($check == 16 || $check == 17 || $check == 18)
					|| ($check == 19 || $check == 20 || $check == 21)
					|| ($check == 22 || $check == 23 || $check == 24)
				){
					$arrAtividadesRetensiveis = array();
					$retensivel = false;
					foreach($_SESSION['cnaes_empresa_mes'] as $arrCNAE){
						if(in_array(preg_replace('/\W/','',$arrCNAE),$criterios_retensiveis)){
							$retensivel = true;
							break;
						}
					}
				}
			
				// CHECANDO SE O ARQUIVO QUE CONTEM O CONTEUDO DO PASSO 8 EXISTE
				if(file_exists('simples_' . (int)$check . '.php')){

					$div_passo7 = file_get_contents('simples_' . (int)$check . '.php') . "\n";

					if($retensivel){
	                    $infoAdicional = "do <strong>MESMO MUNICÍPIO</strong>";
						
						$div_passo7 = str_replace("+atividades1+","<ul>".$atividades1."</ul>",$div_passo7);
						$div_passo7 = str_replace("+atividades2+","<ul>".$atividades2."</ul>",$div_passo7);
						$div_passo7 = str_replace("+atividades2C+","<ul>".$atividades2C."</ul>",$div_passo7);
	
						$div_passo7 = str_replace("+infoAdicional+","".$infoAdicional."",$div_passo7);

						switch($check){
							case 13:
							case 16:
							case 19:
							case 22:// SEM RETENCAO A OUTRO MUNICIPIO
								$div_passo7 = str_replace("+atividades3+","<ul>".$atividades3Retensiveis."</ul>",$div_passo7);
								$div_passo7 = str_replace("+atividades4+","<ul>".$atividades4Retensiveis."</ul>",$div_passo7);
								$div_passo7 = str_replace("+atividades5+","<ul>".$atividades5Retensiveis."</ul>",$div_passo7);
								$div_passo7 = str_replace("+atividades6+","<ul>".$atividades6Retensiveis."</ul>",$div_passo7);
							break;
							case 14:
							case 17:
							case 20:
							case 23:// SEM RETENCAO AO MESMO MUNICIPIO
							case 15:
							case 18:
							case 21:
							case 24:// COM RETENCAO
		
								$div_passo7 = str_replace("+atividades3+","<ul>".$atividades3."</ul>",$div_passo7);
								$div_passo7 = str_replace("+atividades4+","<ul>".$atividades4."</ul>",$div_passo7);
								$div_passo7 = str_replace("+atividades5+","<ul>".$atividades5."</ul>",$div_passo7);
								$div_passo7 = str_replace("+atividades6+","<ul>".$atividades6."</ul>",$div_passo7);
							
							break;
							
							default:
		
								$div_passo7 = str_replace("+atividades3+","<ul>".$atividades3."</ul>",$div_passo7);
								$div_passo7 = str_replace("+atividades4+","<ul>".$atividades4."</ul>",$div_passo7);
								$div_passo7 = str_replace("+atividades5+","<ul>".$atividades5."</ul>",$div_passo7);
								$div_passo7 = str_replace("+atividades6+","<ul>".$atividades6."</ul>",$div_passo7);

							break;
						}
						
					}else{
	                    $infoAdicional = "de <strong>QUALQUER MUNICÍPIO</strong>";

						$div_passo7 = str_replace("+infoAdicional+","".$infoAdicional."",$div_passo7);
						
						$div_passo7 = str_replace("+atividades1+","<ul>".$atividades1."</ul>",$div_passo7);
						$div_passo7 = str_replace("+atividades2+","<ul>".$atividades2."</ul>",$div_passo7);
						$div_passo7 = str_replace("+atividades2C+","<ul>".$atividades2C."</ul>",$div_passo7);
						$div_passo7 = str_replace("+atividades3+","<ul>".$atividades3."</ul>",$div_passo7);
						$div_passo7 = str_replace("+atividades4+","<ul>".$atividades4."</ul>",$div_passo7);
						$div_passo7 = str_replace("+atividades5+","<ul>".$atividades5."</ul>",$div_passo7);
						$div_passo7 = str_replace("+atividades6+","<ul>".$atividades6."</ul>",$div_passo7);
	
					}
	
					echo $div_passo7;
				}

			} // fim do loop
			
			
    }

		if($ja_mostrou == false && $acrescentaSalarios == 1){ // 
			// ESTÁ NO FINAL TAMBÉM, POIS SE O ULTIMO CHECK DO ARRAY FOI O ULTIMO QUE DEVE SER MARCADO FOI DO ANEXO V NÃO SERÁ MOSTRADO NO IF QUE ESTÁ DENTRO DO LOOP - AQUI É CHECADO SE A VARIAVEL DE CONTROLE ja_mostrou ESTÁ FALSE AINDA - SE ESTIVER ESCREVE A DIV COM A INSTRUÇÃO PARA PREENCHIMENTO DOS PAGAMENTOS DE SALARIOS
			// $JA_MOSTROU == FALSE - VARIAVEL QUE CONTROLA A EXIBIÇÃO DE UMA ÚNICA VEZ
			// $acrescentaSalarios == 1 - DEVERMINA SE DEVE MOSTRAR A INSTRUÇÃO ESPECIFICA DO ANEXO V
			echo file_get_contents('simples_salarios.php') . "\n";
		}
		if($ja_mostrou == false && $acrescentaAnexoIV == 1){ // 
			// ESTÁ NO FINAL TAMBÉM, POIS SE O ULTIMO CHECK DO ARRAY FOI O ULTIMO QUE DEVE SER MARCADO FOI DO ANEXO V NÃO SERÁ MOSTRADO NO IF QUE ESTÁ DENTRO DO LOOP - AQUI É CHECADO SE A VARIAVEL DE CONTROLE ja_mostrou ESTÁ FALSE AINDA - SE ESTIVER ESCREVE A DIV COM A INSTRUÇÃO PARA PREENCHIMENTO DOS PAGAMENTOS DE SALARIOS
			// $JA_MOSTROU == FALSE - VARIAVEL QUE CONTROLA A EXIBIÇÃO DE UMA ÚNICA VEZ
			// $acrescentaSalarios == 1 - DEVERMINA SE DEVE MOSTRAR A INSTRUÇÃO ESPECIFICA DO ANEXO V
			$arquivo = file_get_contents('simples_anexo_iv.php');
			$arquivo = str_replace("######",$resposta,$arquivo);
			echo $arquivo . "\n";
		}
    ?>
    
    
       <!-- passo 8 -->
    <div id="passo8" class="passos" style="display:none"> 
        <br />
       
        Confira os dados e clique no botão <strong>Salvar</strong>.<br />
        <br />
        <img src="images/simples_passo8.jpg" width="961" height="563" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
    </div> 
    
    <!-- passo 9-->
    <div id="passo9" class="passos" style="display:none"> 
        <br />
       
        Clique no botão transmitir.<br />
        <br />
        <img src="images/simples_passo10.jpg" width="961" height="553" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
    </div>
    
    
    <!-- passo 10-->
    <div id="passo10" class="passos" style="display:none"> 
        <br />
        
        Uma tela de confirmação do envio será apresentada. Clique em Gerar DAS.<br />
        <br />
        <img src="images/simples_passo11.jpg" width="961" height="544" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
    </div>
    
    
    <!-- passo 11-->
    <div id="passo11" class="passos" style="display:none"> 
        <br />
        
        Todos os dados da DAS serão mostrados. Role a tela até embaixo e clique no botão <b>Gerar DAS</b>. Uma nova janela com o boleto será aberta, imprima o boleto. Se nada acontecer, desative o bloqueador de pop ups (veja como no <a href="https://support.google.com/chrome/answer/95472?hl=pt" target="_blank">Chrome</a>, <a href="https://support.mozilla.org/pt-BR/kb/configuracoes-do-bloqueador-de-popups-excecoes-solucoes-problemas" target="_blank">Firefox</a>, <a href="http://windows.microsoft.com/pt-BR/windows-vista/Internet-Explorer-Pop-up-Blocker-frequently-asked-questions?26470dc0" target="_blank">Internet Explorer</a>) e tente novamente. Pronto missão cumprida!<br />
        <br />
        <img src="images/simples_passo12.jpg" width="961" height="665" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
    </div>






</div>
<br />
<br />
<?php include 'rodape.php' ?>

