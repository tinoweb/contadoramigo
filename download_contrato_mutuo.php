<?
	session_start();

	//echo $_GET['acao'];
	//exit;

	// require_once 'classes/numero_extenso.php';

	require_once 'conect.php' ;

	require_once 'classes/numero_extenso_2.php'; 
	require_once 'classes/fpdf/fpdf.php'; 

	function mascaraRG($string){
		$aux = '';
		for ($i=strlen($string)-1; $i >= 0 ; $i--) { 
			if( $i == strlen($string) - 2 )
				$aux .= '-';
			if( $i == strlen($string) - 5 )
				$aux .= '.';
			if( $i == strlen($string) - 8 )
				$aux .= '.'; 
			$aux .= $string[$i];
		}
		return strrev($aux);

	}

	function data_extenso($data,$separador){

		$arrData = explode($separador,$data);
		$day = $arrData[2];
		$month = $arrData[1];
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
		$year = $arrData[0];
		
		return $day . " de " . $arrMonth[(int)$month] . " de " . $year;
		
	}
	

	//pega dados da tabela empresa

		class Dados_emprestimo{
		function mask($val, $mask)
		{
			 $maskared = '';
			 $k = 0;
			 for($i = 0; $i<=strlen($mask)-1; $i++){
				 if($mask[$i] == '#'){
					 if(isset($val[$k]))
					 	$maskared .= $val[$k++];
				 }
				 else{
				 	if(isset($mask[$i]))
				 	$maskared .= $mask[$i];
				 }
			 }
			 if( strlen($val) + 3 == strlen($mask) )
			 	return $maskared;
			 else
			 	return substr($maskared, 0 , strlen($val) + 2 );
		}

		function calcularIOF($item){
			
			include 'datas.class.php';	

			$data = new Datas();
			$dataInicio = explode(' ', $item['data']);
			$dataInicio = $dataInicio[0];
			$dataFim = explode(' ', $item['data_pagamento']);
			$dataFim = $dataFim[0];
			$dias = $data->diferencaData($dataFim,$dataInicio);

			$valor = $item['valor'];

			//Define a aliquota em relação ao valor e tipo de emprestimo(tipo = PF->PJ ou PJ->PF)
			if( $item['tipo'] == 1 && $valor > 30000 ){
				$aliquota_dia_iof = 0.000041;
			}
			else if( $item['tipo'] == 2 && $valor > 30000 ){
				$aliquota_dia_iof = 0.000082;
			}
			else if( $item['tipo'] == 2 && $valor <= 30000 ){
				$aliquota_dia_iof = 0.000082;	
			}
			else if( $item['tipo'] == 1 && $valor <= 30000 ){
				$aliquota_dia_iof = 0.0000137;	
			}
			//multiplica o numero de dias do emprestimo pela aliquota, determinada pelo valor e tipo de emprestimo(tipo = PF->PJ ou PJ->PF)

			$aux = floatval($dias * $aliquota_dia_iof);
			if( $aux > 0.015 )
				$aux = 0.015;

			//Define o valor do IFO -> // [(dias * aliquota) + 0.38%] * valor
			$valor = (0.0038 + $aux) * $valor;

			if( $item['tipo'] == 1 )
				return 0;
			return $valor;

		}

		function __construct(){

			$id = $_GET['id'];
			$id_user = $_GET['user'];

			$consulta = mysql_query("SELECT * FROM emprestimos WHERE id = '".$id."' AND id_user = '".$id_user."' ");
			$objeto=mysql_fetch_array($consulta);
			
			$this->settipo(trim($objeto['tipo']));

			$this->setmutuario_nome(trim($objeto['nome']));
			$this->setmutuario_rua(trim($objeto['rua']));
			$this->setmutuario_cep(trim($objeto['bairro']));
			$this->setmutuario_cidade(trim($objeto['cidade']));
			$this->setmutuario_estado(trim($objeto['estado']));
			$this->setmutuario_rg(trim($objeto['rg'],'##.###.###-#'));
			$this->setmutuario_cpf(trim($objeto['cpf']));
			$this->setmutuario_profissao(strtolower(trim($objeto['profissao'])));
			$this->setmutuario_nacionalidade(strtolower(trim($objeto['nacionalidade'])));
			$this->setmutuario_estado_civil(strtolower(trim($objeto['estado_civil'])));

			$aux_data = explode(' ', $objeto['data']);
			$aux_data = explode('-', $aux_data[0]);

			$aux_data_devolucao = explode(' ', $objeto['data_pagamento']);
			$aux_data_devolucao = explode('-', $aux_data_devolucao[0]);
			$this->setdata_devolucao($aux_data_devolucao[2]."/".$aux_data_devolucao[1]."/".$aux_data_devolucao[0]);


			$this->setnome1_testemunha(trim($objeto['nome_testemunha1']));
			$this->setrg1_testemunha(trim($objeto['rg_testemunha1']));
			$this->setnome2_testemunha(trim($objeto['nome_testemunha2']));
			$this->setrg2_testemunha(trim($objeto['rg_testemunha2']));	

		
			$this->setvalor($objeto['valor']);
			// [ (dias * aliquota) + 0.38% ] * valor



			$consulta_gambiarra = mysql_query("SELECT * FROM dados_do_responsavel WHERE rg = '".$objeto['rg']."' ");
			$objeto_gambiarra = mysql_fetch_array($consulta_gambiarra);
			$this->setmutuario_sexo(strtolower(trim($objeto_gambiarra['sexo'])));

			// $this->setvalor($objeto['valor']);
			$this->setjuros($objeto['juros']*100);

			$data_aux = explode(' ', $objeto['data'] );
			$data_aux = explode('-', $data_aux[0]);

			$this->setdata_emprestimo($data_aux[2].'/'.$data_aux[1].'/'.$data_aux[0]);



			$consulta = mysql_query("SELECT * FROM estados WHERE coluna = '".$valor."' ");
			$objeto=mysql_fetch_array($consulta);

			$this->setdia($aux_data[2]);
			$this->setmes($aux_data[1]);
			$this->setano($aux_data[0]);

			$consulta = mysql_query("SELECT * FROM dados_do_responsavel WHERE id = '".$id_user."' AND responsavel = 1 ");
			$objeto=mysql_fetch_array($consulta);

			$this->setcredor_nome(trim($objeto['nome']));
			$this->setcredor_rua(trim($objeto['endereco']));
			$this->setcredor_cep(trim($objeto['cep']));
			$this->setcredor_cidade(trim($objeto['cidade']));
			$this->setcredor_estado(trim($objeto['estado']));
			$this->setcredor_rg(trim($objeto['rg'],'##.###.###-#'));
			$this->setcredor_cpf(trim($objeto['cpf']));
			$this->setcredor_profissao(strtolower(trim($objeto['profissao'])));
			$this->setcredor_nacionalidade(strtolower(trim($objeto['nacionalidade'])));
			$this->setcredor_estado_civil(strtolower(trim($objeto['estado_civil'])));

			$this->setcredor_sexo(strtolower(trim($objeto['sexo'])));

			

			$consulta = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$id_user."' ");
			$objeto=mysql_fetch_array($consulta);

			$this->setrua_empresa(trim($objeto['endereco']));
			$this->setbairro_empresa(trim($objeto['bairro']));
			$this->setcnpj_empresa(trim($objeto['cnpj']));
			$this->setcidade_empresa(trim($objeto['cidade']));
			$this->setestado_empresa(trim($objeto['estado']));
			$this->setnome_empresa(trim($objeto['razao_social']));

		}

		private $tipo;

		private $credor_nome;
		private $credor_rua;
		private $credor_cep;
		private $credor_cidade;
		private $credor_estado;
		private $credor_rg;
		private $credor_cpf;
		private $credor_profissao;
		private $credor_nacionalidade;
		private $credor_estado_civil;

		private $credor_sexo;


		private $mutuario_nome;
		private $mutuario_rua;
		private $mutuario_cep;
		private $mutuario_cidade;
		private $mutuario_estado;
		private $mutuario_rg;
		private $mutuario_cpf;
		private $mutuario_profissao;
		private $mutuario_nacionalidade;
		private $mutuario_estado_civil;

		private $mutuario_sexo;

		private $valor;
		private $juros;
		private $data_emprestimo;

		private $cidade;
		private $dia;
		private $mes;
		private $ano;		

		private $data_devolucao;	

		private $rua_empresa;
		private $bairro_empresa;
		private $cnpj_empresa;
		private $cidade_empresa;
		private $estado_empresa;
		private $nome_empresa;

		private $numero_credor;
		private $numero_mutuario;

		private $nome_credor;
		private $nome_mutuario;

		private $nome1_testemunha;
		private $rg1_testemunha;
		private $nome2_testemunha;
		private $rg2_testemunha;

		function getdata_devolucao(){
			return $this->data_devolucao;
		}
		function setdata_devolucao($string){
			$this->data_devolucao = $string;
		}
		function getrg2_testemunha(){
			return $this->rg2_testemunha;
		}
		function setrg2_testemunha($string){
			$this->rg2_testemunha = $string;
		}
		function getnome2_testemunha(){
			return $this->nome2_testemunha;
		}
		function setnome2_testemunha($string){
			$this->nome2_testemunha = $string;
		}
		function getrg1_testemunha(){
			return $this->rg1_testemunha;
		}
		function setrg1_testemunha($string){
			$this->rg1_testemunha = $string;
		}
		function getnome1_testemunha(){
			return $this->nome1_testemunha;
		}
		function setnome1_testemunha($string){
			$this->nome1_testemunha = $string;
		}
		function getnome_empresa(){
			return $this->nome_empresa;
		}
		function setnome_empresa($string){
			$this->nome_empresa = $string;
		}
		function getnome_mutuario(){
			return $this->nome_mutuario;
		}
		function setnome_mutuario($string){
			$this->nome_mutuario = $string;
		}
		function getnome_credor(){
			return $this->nome_credor;
		}
		function setnome_credor($string){
			$this->nome_credor = $string;
		}
		function getcredor_sexo(){
			if( $this->credor_sexo == 'masculino' )
				return 'denominado';
			if( $this->credor_sexo == 'feminino' )
				return 'denominada';
			return 'denominada';
		}
		function setcredor_sexo($string){
			$this->credor_sexo = $string;
		}
		function getmutuario_sexo0(){
			return $this->mutuario_sexo;
		}
		function getcredor_sexo0(){
			return $this->credor_sexo;
		}
		function getmutuario_sexo(){
			if( $this->mutuario_sexo == 'masculino' )
				return 'denominado';
			if( $this->mutuario_sexo == 'feminino' )
				return 'denominada';
			return 'denominada';
		}

		function getcredor_sexo2(){
			if( $this->credor_sexo == 'masculino' )
				return 'representado';
			if( $this->credor_sexo == 'feminino' )
				return 'representada';
			return 'representada';
		}
		function getmutuario_sexo2(){
			if( $this->mutuario_sexo == 'masculino' )
				return 'representado';
			if( $this->mutuario_sexo == 'feminino' )
				return 'representada';
			return 'representada';
		}

		function getcredor_sexo3(){
			if( $this->credor_sexo == 'masculino' )
				return 'MUTUÁRIO';
			if( $this->credor_sexo == 'feminino' )
				return 'MUTUÁRIA';
			return 'MUTUÁRIA';
		}
		function getmutuario_sexo3(){
			if( $this->mutuario_sexo == 'masculino' )
				return 'MUTUÁRIO';
			if( $this->mutuario_sexo == 'feminino' )
				return 'MUTUÁRIA';
			return 'MUTUÁRIA';
		}


		function getnumero_mutuario(){
			return $this->numero_mutuario;
		}
		function setnumero_mutuario($string){
			$this->numero_mutuario = $string;
		}
		function getnumero_credor(){
			return $this->numero_credor;
		}
		function setnumero_credor($string){
			$this->numero_credor = $string;
		}
		function setmutuario_sexo($string){
			$this->mutuario_sexo = $string;
		}
		function gettipo(){
			return $this->tipo;
		}
		function settipo($string){
			$this->tipo = $string;
		}
		function getjuros(){
			return $this->juros;
		}
		function setjuros($string){
			$this->juros = $string;
		}
		function getdata_emprestimo(){
			return $this->data_emprestimo;
		}
		function setdata_emprestimo($string){
			$this->data_emprestimo = $string;
		}
		function getestado_empresa(){
			return $this->estado_empresa;
		}
		function setestado_empresa($string){
			$this->estado_empresa = $string;
		}
		function getcidade_empresa(){
			return $this->cidade_empresa;
		}
		function setcidade_empresa($string){
			$this->cidade_empresa = $string;
		}
		function getcnpj_empresa(){
			return $this->cnpj_empresa;
		}
		function setcnpj_empresa($string){
			$this->cnpj_empresa = $string;
		}
		function getbairro_empresa(){
			return $this->bairro_empresa;
		}
		function setbairro_empresa($string){
			$this->bairro_empresa = $string;
		}
		function getrua_empresa(){
			return $this->rua_empresa;
		}
		function setrua_empresa($string){
			$this->rua_empresa = $string;
		}
		function getmutuario_estado_civil(){
			return $this->mutuario_estado_civil;
		}
		function setmutuario_estado_civil($string){
			$this->mutuario_estado_civil = $string;
		}
		function getmutuario_nacionalidade(){
			return $this->mutuario_nacionalidade;
		}
		function setmutuario_nacionalidade($string){
			$this->mutuario_nacionalidade = $string;
		}
		function getmutuario_profissao(){
			return $this->mutuario_profissao;
		}
		function setmutuario_profissao($string){
			$this->mutuario_profissao = $string;
		}
		function getcredor_estado_civil(){
			return $this->credor_estado_civil;
		}
		function setcredor_estado_civil($string){
			$this->credor_estado_civil = $string;
		}
		function getcredor_nacionalidade(){
			return $this->credor_nacionalidade;
		}
		function setcredor_nacionalidade($string){
			$this->credor_nacionalidade = $string;
		}
		function getcredor_profissao(){
			return $this->credor_profissao;
		}
		function setcredor_profissao($string){
			$this->credor_profissao = $string;
		}
		function getmutuario_estado(){
			return $this->mutuario_estado;
		}
		function setmutuario_estado($string){
			$this->mutuario_estado = $string;
		}
		function getcredor_estado(){
			return $this->credor_estado;
		}
		function setcredor_estado($string){
			$this->credor_estado = $string;
		}
		function getmutuario_cidade(){
			return $this->mutuario_cidade;
		}
		function setmutuario_cidade($string){
			$this->mutuario_cidade = $string;
		}
		function getcredor_cidade(){
			return $this->credor_cidade;
		}
		function setcredor_cidade($string){
			$this->credor_cidade = $string;
		}
		function getano(){
			return $this->ano;
		}
		function setano($string){
			$this->ano = $string;
		}
		function getmes(){
			return $this->mes;
		}
		function setmes($string){
			$this->mes = $string;
		}
		function getdia(){
			return $this->dia;
		}
		function setdia($string){
			$this->dia = $string;
		}
		function getcidade(){
			return $this->cidade;
		}
		function setcidade($string){
			$this->cidade = $string;
		}
		function getvalor(){
			return $this->valor;
		}
		function setvalor($string){
			$this->valor = $string;
		}
		function getmutuario_cpf(){
			return $this->mutuario_cpf;
		}
		function setmutuario_cpf($string){
			$this->mutuario_cpf = $string;
		}
		function getmutuario_rg(){
			return $this->mutuario_rg;
		}
		function setmutuario_rg($string){
			$this->mutuario_rg = $string;
		}
		function getmutuario_cep(){
			return $this->mutuario_cep;
		}
		function setmutuario_cep($string){
			$this->mutuario_cep = $string;
		}
		function getmutuario_rua(){
			return $this->mutuario_rua;
		}
		function setmutuario_rua($string){
			$this->mutuario_rua = $string;
		}
		function getmutuario_nome(){
			return $this->mutuario_nome;
		}
		function setmutuario_nome($string){
			$this->mutuario_nome = $string;
		}
		function getcredor_cpf(){
			return $this->credor_cpf;
		}
		function setcredor_cpf($string){
			$this->credor_cpf = $string;
		}
		function getcredor_rg(){
			return $this->credor_rg;
		}
		function setcredor_rg($string){
			$this->credor_rg = $string;
		}
		function getcredor_cep(){
			return $this->credor_cep;
		}
		function setcredor_cep($string){
			$this->credor_cep = $string;
		}
		function getcredor_rua(){
			return $this->credor_rua;
		}
		function setcredor_rua($string){
			$this->credor_rua = $string;
		}
		function getcredor_nome(){
			return $this->credor_nome;
		}
		function setcredor_nome($string){
			$this->credor_nome = $string;
		}

	}

	function getMes($mes){
		if($mes == 1)
			return "janeiro";
		if($mes == 2)
			return "fevereiro";
		if($mes == 3)
			return "março";
		if($mes == 4)
			return "abril";
		if($mes == 5)
			return "maio";
		if($mes == 6)
			return "junho";
		if($mes == 7)
			return "julho";
		if($mes == 8)
			return "agosto";
		if($mes == 9)
			return "setembro";
		if($mes == 10)
			return "outubro";
		if($mes == 11)
			return "novembro";
		if($mes == 12)
			return "dezembro";

	}

	$dados = new Dados_emprestimo();
	
	
	$pdf= new FPDF("P","mm","A4");
	
	$pdf->SetTopMargin(20);
	$pdf->AddPage();
	$pdf->SetleftMargin(20);
	$pdf->SetrightMargin(20); 
	$pdf->SetFont('arial','',18);
	$pdf->MultiCell(170,5,utf8_decode("CONTRATO DE MÚTUO MERCANTIL"),0,'C');
	$pdf->Ln(10);
	
	//Tipo 2 = empresa para o sócio
	//Tipo 1 = sócio para a empresa
	if( $dados->gettipo() == 1 ){

		$credor = "".$dados->getmutuario_nome().", ".$dados->getmutuario_nacionalidade().", ".$dados->getmutuario_estado_civil().", ".$dados->getmutuario_profissao().", residente e domiciliado em ".$dados->getmutuario_cidade()." - ".$dados->getmutuario_estado()." à ".$dados->getmutuario_rua().", CEP ".$dados->getmutuario_cep().", RG n.º ".mascaraRG($dados->getmutuario_rg())." e CPF n.º ".$dados->getmutuario_cpf().", doravante ".$dados->getmutuario_sexo()." MUTUANTE,";

		$mutuario = $dados->getnome_empresa().", pessoa jurídica de direito privado, com sede em ".$dados->getcidade_empresa()." - ".$dados->getestado_empresa().", à ".$dados->getrua_empresa().", CNPJ n.º ".$dados->getcnpj_empresa().", neste ato representada pelo seu sócio-administrador, ".$dados->getcredor_nome().", RG n.º ".mascaraRG($dados->getcredor_rg())." e CPF n.º ".$dados->getcredor_cpf().", doravante denominada MUTUÁRIA,";

		$dados->setnome_credor($dados->getmutuario_nome());
		$dados->setnome_mutuario($dados->getnome_empresa());

		$dados->setnumero_credor($dados->getmutuario_cpf());
		$dados->setnumero_mutuario($dados->getcnpj_empresa());

	}
	else{

		$mutuario = "".$dados->getmutuario_nome().", ".$dados->getmutuario_nacionalidade().", ".$dados->getmutuario_estado_civil().", ".$dados->getmutuario_profissao().", residente e domiciliado em ".$dados->getmutuario_cidade()." - ".$dados->getmutuario_estado()." à ".$dados->getmutuario_rua().", CEP ".$dados->getmutuario_cep().", RG n.º ".mascaraRG($dados->getmutuario_rg())." e CPF n.º ".$dados->getmutuario_cpf().", doravante ".$dados->getmutuario_sexo()." ".strtoupper($dados->getcredor_sexo3()).",";

		$credor = $dados->getnome_empresa().", pessoa jurídica de direito privado, com sede em ".$dados->getcidade_empresa()." - ".$dados->getestado_empresa().", à ".$dados->getrua_empresa().", CNPJ n.º ".$dados->getcnpj_empresa().", neste ato representada pelo seu sócio-administrador, ".$dados->getcredor_nome().", RG n.º ".mascaraRG($dados->getcredor_rg())." e CPF n.º ".$dados->getcredor_cpf().", doravante denominada MUTUANTE,";

		$dados->setnome_credor($dados->getnome_empresa());
		$dados->setnome_mutuario($dados->getmutuario_nome());

		$dados->setnumero_credor($dados->getcnpj_empresa());
		$dados->setnumero_mutuario($dados->getmutuario_cpf());

	}
	
	$gambiarras_mutuante = "";
	$gambiarras_mutuante2 = "";
	$gambiarras_mutuario = "";
	$gambiarras_mutuario2 = "";
	$gambiarras_mutuario3 = "";
	$gambiarras_mutuario4 = "";

	if( $dados->gettipo() == 1 ){

		if( $dados->getcredor_sexo0() == 'masculino' ){
			$gambiarras_mutuante = 'o MUTUANTE';
			$gambiarras_mutuante2 = 'ao MUTUANTE';
		}else{
			$gambiarras_mutuante = 'a MUTUANTE';
			$gambiarras_mutuante2 = 'MUTUANTE';
		}
		$gambiarras_mutuario = 'à MUTUÁRIA';
		$gambiarras_mutuario2 = 'MUTUÁRIA';
		$gambiarras_mutuario3 = 'a MUTUÁRIA';
		$gambiarras_mutuario4 = 'Mutuária';

	}
	else{

		$gambiarras_mutuante = 'a MUTUANTE';
		$gambiarras_mutuante2 = 'MUTUANTE';
		if( $dados->getmutuario_sexo0() == 'masculino' ){
			$gambiarras_mutuario = 'ao MUTUÁRIO';
			$gambiarras_mutuario2 = 'ao MUTUÁRIO';
			$gambiarras_mutuario3 = 'o MUTUÁRIO';
			$gambiarras_mutuario4 = 'Mutuário';
		}else{
			$gambiarras_mutuario = 'à MUTUÁRIA';
			$gambiarras_mutuario2 = 'à MUTUÁRIA';
			$gambiarras_mutuario3 = 'a MUTUÁRIA';
			$gambiarras_mutuario4 = 'Mutuária';
		}

	}

	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(170,5,utf8_decode("Pelo presente instrumento particular, de um lado ".$credor." e de outro lado ".$mutuario." têm entre si justo e contratado o seguinte:"),0,'J');
	$pdf->Ln(5);

	$numero = new GExtenso();

	$valor_total = explode('.', $dados->getvalor());

	$valor_extenso = $numero->numero($valor_total[0]);
	$valor_extenso .= " reais";

	if( isset($valor_total[1]) ){

		$valor_extenso .= " e ".$numero->numero($valor_total[1]);
		$valor_extenso .= " centavo";

		if( floatval($valor_total[1]) > 1  )
			$valor_extenso .= "s";
	}

	$valor_total = explode('.', $dados->getjuros());

	$porcentagem_extenso = $numero->numero($valor_total[0]);
	

	if( isset($valor_total[1]) ){

		$porcentagem_extenso .= " virgula ";

		$porcentagem_extenso .= $numero->numero($valor_total[1]);

	}

	$porcentagem_extenso .= " por cento";

	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(170,5,utf8_decode("CLÁUSULA PRIMEIRA: ".ucfirst($gambiarras_mutuante)." empresta ".$gambiarras_mutuario." a quantia de R$ ".number_format($dados->getvalor(),2,',','.')." (".$valor_extenso."), que deverá ser quitada até o dia ".$dados->getdata_devolucao().", acrescida de juros de ".number_format($dados->getjuros(),2,',','.')."% ao mês."),0,'J');

	$pdf->Ln(5);

	$pdf->SetleftMargin(40);
	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(150,5,utf8_decode("PARÁGRAFO ÚNICO - Fica facultada ".$gambiarras_mutuario." a quitação do compromisso, em parte ou na totalidade, em data anterior à estipulada, pagando juros proporcionais ao prazo decorrido."),0,'J');
	$pdf->SetleftMargin(20);
	
	$pdf->Ln(5);

	// $pdf->SetFont('arial','',10);
	// $pdf->MultiCell(170,5,utf8_decode("CLÁUSULA SEGUNDA: A importância mutuada não terá incidência de juros e correção monetária, se for efetuado o pagamento estipulado na cláusula primeira. "),0,'J');	
	// $pdf->Ln(5); 

	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(170,5,utf8_decode("CLÁUSULA SEGUNDA: Todo e qualquer atraso no pagamento da obrigação acarretará multa de 2% ao mês mais juros de mora, contados até a data da efetiva liquidação do MÚTUO. Fica facultado ".$gambiarras_mutuario." tomar todas as medidas judiciais e extra-judiciais para a salvaguarda de seus interesses. As despesas decorrentes e mais honorários advocatícios, se tal for o caso, serão de responsabilidade d".$gambiarras_mutuario3."."),0,'J');	
	$pdf->Ln(5);

	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(170,5,utf8_decode("CLÁUSULA TERCEIRA: O presente instrumento será celebrado em caráter irretratável, obrigando as partes, por si e por seus herdeiros."),0,'J');	
	$pdf->Ln(5);

	// $pdf->SetFont('arial','',10);
	// $pdf->MultiCell(170,5,utf8_decode("CLÁUSULA QUARTA: Este instrumento particular, naquilo que for omisso reger-se-á pelo Novo Código Civil."),0,'J');	
	// $pdf->Ln(5);

	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(170,5,utf8_decode("CLAUSULA QUARTA: Fica eleito o foro desta cidade de ".$dados->getcidade_empresa().", com exclusão de qualquer outro, por mais privilegiado que seja, para dirimir as dúvidas que possam surgir na execução do presente contrato."),0,'J');	
	$pdf->Ln(5);

	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(170,5,utf8_decode("E por estarem as partes em pleno acordo, assinam o presente instrumento particular juntamente com as testemunhas abaixo, em 2 (duas) vias de igual teor e forma."),0,'J');	
	$pdf->Ln(10);

	$pdf->SetFont('arial','',10);
	$pdf->MultiCell(170,5,utf8_decode($dados->getcidade_empresa().", ".$dados->getdia()."  de ".getMes($dados->getmes())." de ".$dados->getano()).".",0,'J');	
	$pdf->Ln(10);
	
	if( $dados->gettipo() == 1 ){
		$legenda1 = "CPF: ";
		$legenda2 = "CNPJ: ";
	}
	else{
		$legenda1 = "CNPJ: ";
		$legenda2 = "CPF: ";
	}


	$pdf->SetFont('arial','',10);
	$pdf->Cell(85,5,utf8_decode("Mutuante"),0,0,'L');
	$pdf->Cell(85,5,utf8_decode($gambiarras_mutuario4),0,1,'L');
	$pdf->Ln(5);
	$pdf->SetFont('arial','',10);
	$pdf->Cell(85,5,utf8_decode("_____________________________"),0,0,'L');
	$pdf->Cell(85,5,utf8_decode("_____________________________"),0,1,'L');
	$pdf->Cell(85,5,utf8_decode($dados->getnome_credor()),0,0,'L');
	$pdf->Cell(85,5,utf8_decode($dados->getnome_mutuario()),0,1,'L');
	$pdf->Cell(85,5,utf8_decode($legenda1.$dados->getnumero_credor()),0,0,'L');
	$pdf->Cell(85,5,utf8_decode($legenda2.$dados->getnumero_mutuario()),0,1,'L');

	// $pdf->MultiCell(170,5,utf8_decode("Nome"),0,'L');
	// $pdf->MultiCell(170,5,utf8_decode("                    RG: ".$dados->getcredor_rg()."                                                      RG: ".$dados->getmutuario_rg()."               "),0,'L');
	$pdf->Ln(15);
	
	$pdf->MultiCell(170,5,utf8_decode("Testemunhas:"),0,'L');
	$pdf->Ln(5);

	$pdf->SetFont('arial','',10);
	$pdf->Cell(85,5,utf8_decode("_____________________________"),0,0,'L');
	$pdf->Cell(85,5,utf8_decode("_____________________________"),0,1,'L');
	$pdf->Cell(85,5,utf8_decode($dados->getnome1_testemunha()),0,0,'L');
	$pdf->Cell(85,5,utf8_decode($dados->getnome2_testemunha()),0,1,'L');
	$pdf->Cell(85,5,utf8_decode("RG: ".mascaraRG($dados->getrg1_testemunha())),0,0,'L');
	$pdf->Cell(85,5,utf8_decode("RG: ".mascaraRG($dados->getrg2_testemunha())),0,1,'L');

	//$pdf->Write(30, $corpo, '');
	
	$pdf->Output("contrato_mutuo_mercantil.pdf","D");
?>