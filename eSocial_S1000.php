<?php 

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

// Variável utilizada para pegar erro se houver.
$erroXML = 0;

if (isset($_GET['gerar_xml'])) {
	
	session_start();
	
	require_once('conect.php');
	
	// Pega o id com o codigo da página.
	$empresaId = $_SESSION[ "id_empresaSecao" ];
	
	$xml = gera_xml($empresaId);

	
	$codigo_do_evento = 'S1000';

	$empresa = PegaDadosEmpresa($empresaId);

	$_SESSION['registro_eSocial_Id'] = insertXML($empresaId, $codigo_do_evento, $empresa['cnpj'], $xml);

	// Realiza o redirecionamento da página.
	header('Location: eSocial_resultado.php');

	// Mata a execução do código. 	
	die();
}

// Realiza a chamada do cabeçalho com o menu da página.
require_once('header_restrita.php');

// Pega os dados da empresa. 
function PegaDadosEmpresa($empresaId) {
	
	$dadoEmpresa = array ("id"=>''
		,"razao_social"=>''
		,"cnpj"=>''
		,"tipo_endereco"=>''
		,"ramo_de_atividade"=>''
		,"codigo_de_atividade_prefeitura"=>''
		,"regime_de_tributacao"=>''
		,"inscrita_como"=>''
		,"email"=>''
		,"recolhe_cprb"=>'');	
	
	// pega dados da empresa
	$sql = "SELECT * FROM dados_da_empresa WHERE id='".$empresaId."' LIMIT 0, 1";
	
	// Execulta a query.
	$resultado = mysql_query( $sql ) or die( mysql_error() );

	// Verifica se houve retorno de dados da empresa.
	if(mysql_num_rows($resultado) > 0){
		
		$dados_db_Empresa = mysql_fetch_array( $resultado );
		
		$dadoEmpresa['id'] = $dados_db_Empresa["id"];
		$dadoEmpresa['razao_social'] = $dados_db_Empresa["razao_social"];
		$dadoEmpresa['cnpj'] = $dados_db_Empresa["cnpj"];
		$dadoEmpresa['tipo_endereco'] = $dados_db_Empresa["tipo_endereco"];
		$dadoEmpresa['ramo_de_atividade'] = $dados_db_Empresa["ramo_de_atividade"];
		$dadoEmpresa['codigo_de_atividade_prefeitura'] = $dados_db_Empresa["codigo_de_atividade_prefeitura"];
		$dadoEmpresa['regime_de_tributacao'] = $dados_db_Empresa["regime_de_tributacao"];
		$dadoEmpresa['inscrita_como'] = $dados_db_Empresa["inscrita_como"];
		$dadoEmpresa['recolhe_cprb'] = $dados_db_Empresa["recolhe_cprb"];
		
	} else {
		
		$dadoEmpresa = false;
	}
	
	// retorna o array com os dados da empresa.
	return $dadoEmpresa;
}

// Pega os cnaes da empresa iniciado em 41, 42, 43.
function PegaCodigoCnaeEmpresa($empresaId) {
	
	// Verifica se é construtora.
	$construtora = 0;

	$sql = "SELECT * FROM dados_da_empresa_codigos ec 
				INNER JOIN cnae c ON ec.cnae = c.cnae 
				WHERE ec.id = '".$empresaId."' 
				AND (c.cnae LIKE '41%' OR c.cnae LIKE '42%' OR c.cnae LIKE '43%');";

	$lista_cnae = mysql_query($sql) or die (mysql_error());

	if(mysql_num_rows($lista_cnae) > 0){
		$construtora = 1;
	}
	
	return $construtora;
}

// Pega os dados do responsavel.
function PegaDadosSocio($empresaId){
	
	// Defina o array de retorno do sócio
	$dadosSocio = array('cpf'=>'','nome'=>'', 'pref_telefone'=>'','telefone'=>'','email_socio'=>'','email_socio'=>'');
	
	// Verifica dados do responsável
	$qryResponsavel = "SELECT * FROM `dados_do_responsavel` WHERE `id` = '".$empresaId."' AND `responsavel` = 1";

	// Veriáveis utilizadas para receber os dados da empresa.
	$doc = $nome = $telefone = $email = '';

	// Execulta a pesquisa.
	$resultado = mysql_query($qryResponsavel) or die (mysql_error());
	
	// Verifica se houve retorno de dados da empresa.
	if(mysql_num_rows($resultado) > 0){
		
		$dados_db_Socio = mysql_fetch_array( $resultado );

		$dadosSocio['cpf'] = $dados_db_Socio["cpf"];
		$dadosSocio['nome'] = $dados_db_Socio["nome"];
		$dadosSocio['pref_telefone'] = $dados_db_Socio['pref_telefone'];
		$dadosSocio['telefone'] = $dados_db_Socio['telefone'];
		$dadosSocio['email_socio'] = $dados_db_Socio['email_socio'];
		
	} else {
		
		$dadosSocio = false;
	}	
	
	return $dadosSocio;	
}

// Método utilizado para fazer a inclusão dos dados do envio do xml.
function insertXML($empresaId, $codigo_do_evento, $doc, $xml_envio) {
	
	$insert = "INSERT INTO esocial_envio_retorno_xml ( user_id, data_inclusao, codigo_do_evento, CNPJ, xml_envio ) VALUES ('".$empresaId."', NOW(), '".$codigo_do_evento."', '".$doc."', '".$xml_envio."');";
	
	$consulta = mysql_query($insert);
	
	// Retorna o id da inclusão.
	return mysql_insert_id();
}

// Função para gerar o Id do evento de Infoção do empregador.
function PegaEvtInfoEmpregadorId($empresa){
	
	$id = 0; //str_pad($input, 36 , '0'); 
	
	$cnpjOUcpf = str_pad(0, 14 , '0'); 
	
	if(isset($empresa['cnpj'])){
		
		$doc = OitoDigitosCNPJ($empresa['cnpj']);
		
		$cnpjOUcpf = str_pad($doc, 14 , '0');	
	}
	
	// Data e hora da geração do evento;
	$dataHora = date('YmdHms');
		
	$id='ID1'.$cnpjOUcpf.$dataHora;
	
	return str_pad($id, 36 , '0');
}

// devolve os primeiros oito digitos do cnpj
function OitoDigitosCNPJ($doc){
	
	$doc = str_replace('/', '', $doc);
	$docAux = str_replace('-', '', $docAux);
	$docAux = str_replace('.', '', $docAux);
	
	return substr($docAux, 0, 8);
	
}

// Método criado para pegar o código de natureza jurídica do contribuinte.
function Pega_codigo_natJurid($inscrita_como, $registrado_em){
	
	switch($inscrita_como) {
		
		case 'Sociedade Empresária Limitada':
			return '2062';
			break;
		case 'Sociedade Simples':
			return '2232';
			break;
		case 'Empresa Individual de Responsabilidade Limitada (EIRELI)':
			
			// Verifica em que a empresa foi registrada.
			if($registrado_em == 'junta comercial'){
				return '2305';
			} elseif($registrado_em == 'cartório') {
				return '2313';	
			}
			
			break;
		case 'Empresa Individual':
			return '2135';
			break;
	}
	
	return false;
}

// Define o codigo da classse 
function Pega_codigo_classTrib($construtora){
	
	//sobre o item classTrib o padrão é 02, mas se for construção civil é 03
	return $construtora == 1 ? '03' : '02';
}

function gera_xml($empresaId) {
	
	$xml = false;
	
	// Pega os dados da empresa.
	$empresa = PegaDadosEmpresa($empresaId);
	$socio = PegaDadosSocio($empresaId);
	$construtora = PegaCodigoCnaeEmpresa($empresaId);	
	$evtInfoEmpregadorId = PegaEvtInfoEmpregadorId($empresa);
	$natJurid = Pega_codigo_natJurid($empresa['inscrita_como'], $empresa['registrado_em']);
	$nrInsc = OitoDigitosCNPJ($empresa['cnpj']);
	$classTrib = Pega_codigo_classTrib($construtora);
	
	if($empresa && $socio){
		$xml = '<eSocial xmlns="http://www.esocial.gov.br/schema/evt/evtInfoEmpregador/v02_04_02">'
				.'<evtInfoEmpregador Id="'.$evtInfoEmpregadorId.'">'
				.'<ideEvento>'
				.'<tpAmb>1</tpAmb>'
				.'<procEmi>1</procEmi>'
				.'<verProc>2018.01.00.00</verProc>'
				.'</ideEvento>'
				.'<ideEmpregador>'
				.'<tpInsc>1</tpInsc>'
				.'<nrInsc>'.$nrInsc.'</nrInsc>'
				.'</ideEmpregador>'
				.'<infoEmpregador>'
				.'<inclusao>'
				.'<idePeriodo>'
				.'<iniValid>'.date('Y-m').'</iniValid>'
				.'</idePeriodo>'
				.'<infoCadastro>'
				.'<nmRazao>'.$empresa["razao_social"].'</nmRazao>'
				.'<classTrib>'.$classTrib.'</classTrib>'
				.'<natJurid>'.$natJurid.'</natJurid>'
				.'<indCoop>0</indCoop>'
				.'<indConstr>'.$construtora.'</indConstr>'
				.'<indDesFolha>'.$empresa["recolhe_cprb"].'</indDesFolha>'
				.'<indOptRegEletron>0</indOptRegEletron>'
				.'<indEntEd>N</indEntEd>'
				.'<indEtt>N</indEtt>'
				.'<contato>'
				.'<nmCtt>'.$socio['nome'].'</nmCtt>'
				.'<cpfCtt>'.$socio['cpf'].'</cpfCtt>'
				.'<foneFixo>'.$socio['pref_telefone'].$socio['telefone'].'</foneFixo>'
				.'<email>'.$socio['email_socio'].'</email>'
				.'</contato>'
				.'<softwareHouse>'
				.'<cnpjSoftHouse>96533310000140</cnpjSoftHouse>'
				.'<nmRazao>Contador Amigo Ltda</nmRazao>'
				.'<nmCont>Vitor Maradei</nmCont>'
				.'<telefone>1134346631</telefone>'
				.'<email>atendimento@contadoramigo.com.br</email>'
				.'</softwareHouse>'
				.'<infoComplementares>'
				.'<situacaoPJ>'
				.'<indSitPJ>0</indSitPJ>'
				.'</situacaoPJ>'
				.'</infoComplementares>'
				.'</infoCadastro>'
				.'</inclusao>'
				.'</infoEmpregador>'
				.'</evtInfoEmpregador>'
				.'</eSocial>';
	}
	
	// retorna o xml para salvar no banco de dados.
	return $xml;	
}

// Pega o id com o codigo da página.
$empresaId = $_SESSION[ "id_empresaSecao" ];

// Pega os dados da empresa.
$empresa = PegaDadosEmpresa($empresaId);
$socio = PegaDadosSocio($empresaId);
$construtora = PegaCodigoCnaeEmpresa($empresaId);

// Pega o código de natureza jurídica do contribuinte;
$natJurid = Pega_codigo_natJurid($empresa['inscrita_como'], $empresa['registrado_em']);

// Verifica se código de natureza jurídica do contribuinte foi informado.
if($natJurid){
	$erroXML = 1;
}


if(!$empresa){
	$erroXML = 1;
}

if(!$socio){
	$erroXML = 1;
}

?>

<div class="principal">

	<H1>Impostos e Obrigações</H1>
	<H2>E-Social</H2>
  
	As seguintes informações serão enviadas ao sistema do e-social:<br>
	<br>

	<div class="tituloAzulPequeno" style="margin-bottom: 10px">Grupo de Eventos: Informações do Empregador</div>

	<div class="quadro_branco" style="width: 100%; max-width: 500px">
	<span class="destaqueAzul">Dados da empresa</span><br>
	Razão social: <strong><?= $empresa['razao_social'] ?></strong><br>
	CNPJ: <strong><?= $empresa['cnpj'] ?></strong><br>
	Classificação Tributária: <strong>Optante pelo Simples / Sem substituição tributária</strong><br> 
	Natureza Jurídica: <strong><?= $empresa['inscrita_como'] ?></strong><br> 
	Opção pelo Registro Eletrônico de Empregados: <strong>Sim</strong><br> 
	Indicativo de cooperativa: <strong>Não</strong><br> 
	Indicativo de construtora: <strong><?= ( $construtora == 1 ? 'Sim': 'Não' )   ?></strong> <br> 
	Indicativo de Desoneração da Folha: <strong>Não recolhe CPRB</strong><br> 
	Indicativo de Entidade Educativa sem fins lucrativos: <strong>Não</strong><br> 
	Indicativo de Empresa de Trabalho Temporário: <strong>Não</strong><br> 
	<br>
	<span class="destaqueAzul">Informações para contato</span>
	<br>
	CPF: <strong><?= $socio['cpf'] ?></strong><br>
	Nome: <strong><?= $socio['nome'] ?></strong><br> 
	Telefone: <strong><?= '('.$socio['pref_telefone'].')'.$socio['telefone'] ?></strong><br> 
	<!--Endereço eletrônico: <strong></strong>-->
	</div>

	<form id="form_gera_xml" method="get" action="eSocial_envio.php">
		<input id="GeraXML" type="button" name="gerar_xml" value="Gerar arquivo de envio">	
	</form>
	
</div>

<script>
	
	$(function(){
		
		var erroXML = <?=$erroXML?>;
		
		$('#GeraXML').click(function(e){
			e.preventDefault();
			
			switch(erroXML){	
				case 1:	
					alert('Vá na aba Meus Dados e complete o cadastro de sua empresa  e do sócio-responsável para poder enviar o E-Social.');
					break;	
				case 0:	
					window.location = '/eSocial_S1000.php?gerar_xml'
					break;
			}
		});		
	});
	
</script>	

<?php include 'rodape.php' ?>






