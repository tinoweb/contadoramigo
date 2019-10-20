<?php 
include 'conect.php';

include 'session.php';

include 'check_login.php';

//printf("%%s = '%s'\n", "º");

$titulo_vermelho = "Arquivos de RPS";

function checa_caracteres_especiais($string){
	$qtd = 0;
	if(preg_match_all('/[ªº]/i',$string,$arrResultado)){
		foreach($arrResultado[0] as $campo=>$valor){
			if(preg_match('/[ªº]/i',utf8_encode($valor))){
				$qtd++;
			}
		}	
	}
	return $qtd;
}


$dirf = "";
			

	// ############################# GERAÇÃO DO DIRF #################################

$trans = array(
	"á" => "a" 
	,"à" => "a"
	,"ã" => "a"
	,"â" => "a"
	,"ä" => "a"
	,"é" => "e"
	,"ê" => "e"
	,"ë" => "e"
	,"í" => "i"
	,"ï" => "i"
	,"ó" => "o"
	,"ô" => "o"
	,"õ" => "o"
	,"ö" => "o"
	,"ú" => "u"
	,"ü" => "u"
	,"ç" => "c"
	,"Á" => "A"
	,"À" => "A"
	,"Ã" => "A"
	,"Â" => "A"
	,"Ä" => "A"
	,"É" => "E"
	,"Ê" => "E"
	,"Ë" => "E"
	,"Í" => "I"
	,"Ï" => "I"
	,"Ó" => "O"
	,"Ô" => "O"
	,"Õ" => "O"
	,"Ö" => "O"
	,"Ú" => "U"
	,"Ü" => "U"
	,"Ç" => "C"
	,"º" => "."
	,"ª" => "."
	,"´" => " "
	,"'" => " "
	,"`" => " "
	,"-" => "-"
	,"-" => "-"
	,"ñ" => "n"
	,"Ñ" => "N"
);

$tipos = array(
	"avenida" => "av", 
	"estrada" => "est",
	"praca" => "prc",
	"alameda" => "alm",
	"travessa" => "trv",
	"rodovia" => "rod"
);

/* campo final de linha */$fim_linha = chr(10);// . chr(10);//"\n";

/* campo final de linha */$fim_campo = chr(124);// . chr(10);//"\n";

/* IDENTIFICADOR */

// LAYOUT 3.1 - IDENTIFICADOR DIRF

/* campo 1 */$identificador_registro = "Dirf";
/* campo 2 */$ano_referencia = date('Y');
/* campo 3 */$ano_calendario = date('Y')-1;
/* campo 4 */$identificador_retificadora = "N";
/* campo 5 */$numero_recibo = ""; // preenchimento obrigatório se o campo 4 for igual a "S" e declaração transmitida sem o uso de certificação digital
/* campo 6 */$identificador_estrutura ="M1LB5V2"; // IDENTIFICADOR de 2014 = "F8UCL6S";

$identificador = $identificador_registro.$fim_campo;
$identificador .= $ano_referencia.$fim_campo;
$identificador .= $ano_calendario.$fim_campo;
$identificador .= $identificador_retificadora.$fim_campo;
$identificador .= $numero_recibo.$fim_campo;
$identificador .= $identificador_estrutura.$fim_campo;
$identificador .= $fim_linha;

/* /IDENTIFICADOR */

//echo $identificador;
//exit;

$dirf .= $identificador;

$id = $_SESSION["id_userSecao"];

/* RESPO */

$sqlRESPO = "SELECT resp.cpf, resp.nome, resp.pref_telefone, resp.telefone, l.email FROM dados_do_responsavel resp INNER JOIN login l ON resp.id = l.id WHERE resp.id='" . $id . "' AND resp.responsavel = 1 LIMIT 0,1";
$rsRESPO = mysql_fetch_array(mysql_query($sqlRESPO));

// LAYOUT 3.2 - REGISTRO DO RESPONSAVEL (identificador RESPO)

/* campo 1 */$identificador_registro = "RESPO";
/* campo 2 */$cpf = preg_replace("#\W#","",$rsRESPO["cpf"]);
///* campo 3 */$nome = $rsRESPO["nome"];
/* campo 3 */$nome = substr(strtr($rsRESPO["nome"], $trans), 0, 60);
/* campo 4 */$ddd = substr($rsRESPO["pref_telefone"],0,2); // o primeiro não pode ser "0"
/* campo 5 */$telefone = substr($rsRESPO["telefone"],0,9); // oito ou nove algarismos
/* campo 6 */$ramal = "";
/* campo 7 */$fax = ""; // oito ou nove algarismos
/* campo 8 */$correio_eletronico = $rsRESPO["email"];

$respo = $identificador_registro.$fim_campo;
$respo .= $cpf.$fim_campo;
$respo .= $nome.$fim_campo;
$respo .= $ddd.$fim_campo;
$respo .= $telefone.$fim_campo;
$respo .= $ramal.$fim_campo;
$respo .= $fax.$fim_campo;
$respo .= $correio_eletronico.$fim_campo;
$respo .= $fim_linha;

/* /RESPO */

/*echo $respo;
exit;*/

$dirf .= $respo;


/* DECPF */

// LAYOUT 3.3 - REGISTRO DO DECLARANTE PESSOA FISICA (identificador DECPF)
/*
$sqlDECPF = "SELECT resp.cpf, resp.nome, resp.pref_telefone, resp.telefone, l.email FROM dados_do_responsavel resp INNER JOIN login l ON resp.id = l.id WHERE resp.id='" . $id . "' AND resp.responsavel = 1 LIMIT 0,1";
$rsDECPF = mysql_fetch_array(mysql_query($sqlDECPF));
*/
/* campo 1 */$identificador_registro = "DECPF";
/* campo 2 */$cpf = "29368310831";
/* campo 3 */$nome = "Fabio Henrique";
/* campo 4 */$identificador_rendimentos_exterior = "N"; // S - pagou rendimentos a residentes ou domiciliados no exterior / N - não pagou rendimentos a residentes ou domiciliados no exterior
/* campo 5 */$identificador_titular = "N";// S - titular de serviços notariais e de registros / N - não é titular de serviços notariais e de registros
/* campo 6 */$identificador_plano_saude = "N"; // S - Existe pagamento de valor pelo titular/dependente do plano de saude / N - Não existe pagamento de valor pelo titular/dependente do plano de saúde
/* campo 7 */$identificacao_situacao = "N"; // S - Encerramento de espólio/saída definitiva do país / N - Não é encerramento de espólio/saída definitiva do país
/* campo 8 */$data_evento = ""; // OBRIGATORIO SE O CAMPO 7 = "S"
/* campo 9 */$tipo_evento = ""; // 1 - Encerramento de espólio / 2 - saída definitiva do Brasil - OBRIGATORIO SE O CAMPO 7 = "S"
/*
$decpf = str_pad($identificador_registro,5," ",STR_PAD_RIGHT).$fim_campo;
$decpf .= str_pad($cpf,11,"0",STR_PAD_LEFT).$fim_campo;
$decpf .= str_pad($nome,60," ",STR_PAD_RIGHT).$fim_campo;
$decpf .= str_pad($identificador_rendimentos_exterior,1," ",STR_PAD_RIGHT).$fim_campo;
$decpf .= str_pad($identificador_titular,1," ",STR_PAD_RIGHT).$fim_campo;
$decpf .= str_pad($identificador_plano_saude,1," ",STR_PAD_RIGHT).$fim_campo;
$decpf .= str_pad($identificacao_situacao,1," ",STR_PAD_RIGHT).$fim_campo;
$decpf .= ($data_evento != "" ? str_pad($data_evento,8," ",STR_PAD_RIGHT) : "").$fim_campo;
$decpf .= ($tipo_evento != "" ? str_pad($tipo_evento,1,"0",STR_PAD_LEFT) : "").$fim_campo;
$decpf .= $fim_linha;
*/
/* /DECPF */

//echo $decpf;
//exit;

$dirf .= $decpf;


/* DECPJ */

// LAYOUT 3.4 - REGISTRO DO DECLARANTE PESSOA JURIDICA (identificador DECPJ)

$sqlDECPJ = "SELECT emp.cnpj, emp.razao_social, resp.cpf FROM dados_da_empresa emp INNER JOIN dados_do_responsavel resp ON emp.id = resp.id WHERE emp.id='" . $id . "' AND resp.responsavel = 1 LIMIT 0,1";
$rsDECPJ = mysql_fetch_array(mysql_query($sqlDECPJ));

/* campo 1 */$identificador_registro = "DECPJ";
/* campo 2 */$cnpj = preg_replace("#\W#","",$rsDECPJ["cnpj"]);
///* campo 3 */$nome = $rsDECPJ["razao_social"];
/* campo 3 */$nome = strtr($rsDECPJ["razao_social"], $trans);
/* campo 4 */$natureza_declarante = "0"; 
/*
0 - pessoa jurídica de direito privado 
1 - órgãos, autarquias e fundações de administração pública federal
2 - órgãos, autarquias e fundações de administração pública estadual, municipal ou do Distrito federal 
3 - empresa pública ou sociedade de economia mista federal
4 - empresa pública ou sociedade de economia mista estadual, municipal ou do Distrito federal 
8 - Entidade com alteração de natureza jurídica (uso restrito)
*/
/* campo 5 */$cpf_responsavel = preg_replace("#\W#","",$rsDECPJ["cpf"]);
/* campo 6 */$identificador_socio_ostensivo = "N"; // S - Sócio ostensivo / N - Não é sócio ostensivo 
/* campo 7 */$identificador_depositario = "N"; // S - Depositário de crédito decorrente de decisão judicial / N - Não é depositário de crédito decorrente de decisão judicial
/* campo 8 */$identificador_instituicao = "N"; // S - Instituição administradora ou intermediadora de fundo ou clube de investimento / N - Não é instituição administradora ou intermediadora de fundo ou clube de investimento 
/* campo 9 */$identificador_rendimentos_exterior = "N"; // S - pagou rendimentos a residentes ou domiciliados no exterior / N - não pagou rendimentos a residentes ou domiciliados no exterior
/* campo 10 */$identificador_plano_saude = "N"; // S - Existe pagamento de valor pelo titular/dependente do plano de saude / N - Não existe pagamento de valor pelo titular/dependente do plano de saúde
/* campo 11 */$identificador_pagamentos_copa = "N"; // S - Existe pagamento relacionado a copa / N - Não existe pagamento relacionado a copa
/* campo 12 */$identificador_situacao_declaracao = "N"; // S - Declaração de situação especial / N - Não é declaração de situação especial
/* campo 13 */$data_evento = ""; // OBRIGATORIO SE O CAMPO 12 = "S"

$decpj = str_pad($identificador_registro,5," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= str_pad($cnpj,14,"0",STR_PAD_LEFT).$fim_campo;
$decpj .= str_pad($nome,150," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= str_pad($natureza_declarante,1," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= str_pad($cpf_responsavel,11,"0",STR_PAD_LEFT).$fim_campo;
$decpj .= str_pad($identificador_socio_ostensivo,1," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= str_pad($identificador_depositario,1," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= str_pad($identificador_instituicao,1," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= str_pad($identificador_rendimentos_exterior,1," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= str_pad($identificador_plano_saude,1," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= str_pad($identificador_pagamentos_copa,1," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= str_pad($identificador_situacao_declaracao,1," ",STR_PAD_RIGHT).$fim_campo;
$decpj .= ($data_evento != "" ? str_pad($data_evento,8," ",STR_PAD_RIGHT) : "").$fim_campo;
$decpj .= $fim_linha;

/* /DECPJ */

//echo $decpj;
//exit;

$dirf .= $decpj;



// PEGANDO OS PAGAMENTOS PARA OS PRÓXIMOS REGISTROS

$sqlPAGTOS = "SELECT 
	case 
		  when pgto.id_autonomo <> 0 then '0588' 
		  when pgto.id_socio <> 0 OR pgto.id_lucro <> 0 then '0561' 
		  when pgto.id_pj <> 0 AND pgto.codigo_servico <> 0 then pgto.codigo_servico
		  else '1708'
	  end codigo_receita
	, case 
		  when pgto.id_autonomo <> 0 then aut.dependentes 
		  when pgto.id_socio <> 0 then socio.dependentes
	  end dependentes
	, case 
		  when pgto.id_autonomo <> 0 then 'autonomo' 
		  when pgto.id_socio <> 0 then 'prolabore' 
		  when pgto.id_pj <> 0 then 'pj' 
		  when pgto.id_lucro <> 0 then 'lucros' 
	  end tipo
	, case 
		  when pgto.id_autonomo <> 0 then aut.nome
		  when pgto.id_socio <> 0 then socio.nome
		  when pgto.id_pj <> 0 then pj.nome
		  when pgto.id_lucro <> 0 then dl.nome
	  end nome
	, case 
		  when pgto.id_autonomo <> 0 then aut.cpf 
		  when pgto.id_socio <> 0 then socio.cpf 
		  when pgto.id_pj <> 0 then pj.cnpj 
		  when pgto.id_lucro <> 0 then dl.cpf 
	  end documento
	, case 
		  when pgto.id_autonomo <> 0 then '2' 
		  when pgto.id_socio <> 0 then '1' 
		  when pgto.id_pj <> 0 then '4' 
		  when pgto.id_lucro <> 0 then '3' 
	  end ordem
	, SUM(case when MONTH(data_pagto) = '1' then pgto.valor_bruto else 0 end) VALOR_BRUTO_JANEIRO
	, SUM(case when MONTH(data_pagto) = '2' then pgto.valor_bruto else 0 end) VALOR_BRUTO_FEVEREIRO
	, SUM(case when MONTH(data_pagto) = '3' then pgto.valor_bruto else 0 end) VALOR_BRUTO_MARCO
	, SUM(case when MONTH(data_pagto) = '4' then pgto.valor_bruto else 0 end) VALOR_BRUTO_ABRIL
	, SUM(case when MONTH(data_pagto) = '5' then pgto.valor_bruto else 0 end) VALOR_BRUTO_MAIO
	, SUM(case when MONTH(data_pagto) = '6' then pgto.valor_bruto else 0 end) VALOR_BRUTO_JUNHO
	, SUM(case when MONTH(data_pagto) = '7' then pgto.valor_bruto else 0 end) VALOR_BRUTO_JULHO
	, SUM(case when MONTH(data_pagto) = '8' then pgto.valor_bruto else 0 end) VALOR_BRUTO_AGOSTO
	, SUM(case when MONTH(data_pagto) = '9' then pgto.valor_bruto else 0 end) VALOR_BRUTO_SETEMBRO
	, SUM(case when MONTH(data_pagto) = '10' then pgto.valor_bruto else 0 end) VALOR_BRUTO_OUTUBRO
	, SUM(case when MONTH(data_pagto) = '11' then pgto.valor_bruto else 0 end) VALOR_BRUTO_NOVEMBRO
	, SUM(case when MONTH(data_pagto) = '12' then pgto.valor_bruto else 0 end) VALOR_BRUTO_DEZEMBRO

	, SUM(case when MONTH(data_pagto) = '1' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_JANEIRO
	, SUM(case when MONTH(data_pagto) = '2' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_FEVEREIRO
	, SUM(case when MONTH(data_pagto) = '3' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_MARCO
	, SUM(case when MONTH(data_pagto) = '4' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_ABRIL
	, SUM(case when MONTH(data_pagto) = '5' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_MAIO
	, SUM(case when MONTH(data_pagto) = '6' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_JUNHO
	, SUM(case when MONTH(data_pagto) = '7' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_JULHO
	, SUM(case when MONTH(data_pagto) = '8' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_AGOSTO
	, SUM(case when MONTH(data_pagto) = '9' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_SETEMBRO
	, SUM(case when MONTH(data_pagto) = '10' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_OUTUBRO
	, SUM(case when MONTH(data_pagto) = '11' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_NOVEMBRO
	, SUM(case when MONTH(data_pagto) = '12' then pgto.valor_liquido else 0 end) VALOR_LIQUIDO_DEZEMBRO

	, SUM(case when MONTH(data_pagto) = '1' then pgto.INSS else 0 end) INSS_JANEIRO
	, SUM(case when MONTH(data_pagto) = '2' then pgto.INSS else 0 end) INSS_FEVEREIRO
	, SUM(case when MONTH(data_pagto) = '3' then pgto.INSS else 0 end) INSS_MARCO
	, SUM(case when MONTH(data_pagto) = '4' then pgto.INSS else 0 end) INSS_ABRIL
	, SUM(case when MONTH(data_pagto) = '5' then pgto.INSS else 0 end) INSS_MAIO
	, SUM(case when MONTH(data_pagto) = '6' then pgto.INSS else 0 end) INSS_JUNHO
	, SUM(case when MONTH(data_pagto) = '7' then pgto.INSS else 0 end) INSS_JULHO
	, SUM(case when MONTH(data_pagto) = '8' then pgto.INSS else 0 end) INSS_AGOSTO
	, SUM(case when MONTH(data_pagto) = '9' then pgto.INSS else 0 end) INSS_SETEMBRO
	, SUM(case when MONTH(data_pagto) = '10' then pgto.INSS else 0 end) INSS_OUTUBRO
	, SUM(case when MONTH(data_pagto) = '11' then pgto.INSS else 0 end) INSS_NOVEMBRO
	, SUM(case when MONTH(data_pagto) = '12' then pgto.INSS else 0 end) INSS_DEZEMBRO

	, SUM(case when MONTH(data_pagto) = '1' then pgto.IR else 0 end) IR_JANEIRO
	, SUM(case when MONTH(data_pagto) = '2' then pgto.IR else 0 end) IR_FEVEREIRO
	, SUM(case when MONTH(data_pagto) = '3' then pgto.IR else 0 end) IR_MARCO
	, SUM(case when MONTH(data_pagto) = '4' then pgto.IR else 0 end) IR_ABRIL
	, SUM(case when MONTH(data_pagto) = '5' then pgto.IR else 0 end) IR_MAIO
	, SUM(case when MONTH(data_pagto) = '6' then pgto.IR else 0 end) IR_JUNHO
	, SUM(case when MONTH(data_pagto) = '7' then pgto.IR else 0 end) IR_JULHO
	, SUM(case when MONTH(data_pagto) = '8' then pgto.IR else 0 end) IR_AGOSTO
	, SUM(case when MONTH(data_pagto) = '9' then pgto.IR else 0 end) IR_SETEMBRO
	, SUM(case when MONTH(data_pagto) = '10' then pgto.IR else 0 end) IR_OUTUBRO
	, SUM(case when MONTH(data_pagto) = '11' then pgto.IR else 0 end) IR_NOVEMBRO
	, SUM(case when MONTH(data_pagto) = '12' then pgto.IR else 0 end) IR_DEZEMBRO

	, SUM(case when MONTH(data_pagto) = '1' then pgto.desconto_dependentes else 0 end) desconto_dependentes_JANEIRO
	, SUM(case when MONTH(data_pagto) = '2' then pgto.desconto_dependentes else 0 end) desconto_dependentes_FEVEREIRO
	, SUM(case when MONTH(data_pagto) = '3' then pgto.desconto_dependentes else 0 end) desconto_dependentes_MARCO
	, SUM(case when MONTH(data_pagto) = '4' then pgto.desconto_dependentes else 0 end) desconto_dependentes_ABRIL
	, SUM(case when MONTH(data_pagto) = '5' then pgto.desconto_dependentes else 0 end) desconto_dependentes_MAIO
	, SUM(case when MONTH(data_pagto) = '6' then pgto.desconto_dependentes else 0 end) desconto_dependentes_JUNHO
	, SUM(case when MONTH(data_pagto) = '7' then pgto.desconto_dependentes else 0 end) desconto_dependentes_JULHO
	, SUM(case when MONTH(data_pagto) = '8' then pgto.desconto_dependentes else 0 end) desconto_dependentes_AGOSTO
	, SUM(case when MONTH(data_pagto) = '9' then pgto.desconto_dependentes else 0 end) desconto_dependentes_SETEMBRO
	, SUM(case when MONTH(data_pagto) = '10' then pgto.desconto_dependentes else 0 end) desconto_dependentes_OUTUBRO
	, SUM(case when MONTH(data_pagto) = '11' then pgto.desconto_dependentes else 0 end) desconto_dependentes_NOVEMBRO
	, SUM(case when MONTH(data_pagto) = '12' then pgto.desconto_dependentes else 0 end) desconto_dependentes_DEZEMBRO

	, SUM(pgto.valor_liquido) valor_liquido_anual

FROM 
	dados_pagamentos pgto
	left join dados_autonomos aut on pgto.id_autonomo = aut.id
	left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
	left join dados_pj pj on pgto.id_pj = pj.id
	left join dados_do_responsavel dl on pgto.id_lucro = dl.idSocio
WHERE 
	pgto.id_login='" . $id . "'
	AND (pgto.id_autonomo > 0 OR pgto.id_socio > 0 OR pgto.id_pj > 0 OR pgto.id_lucro > 0)
	AND YEAR(pgto.data_pagto) = '" . $ano_calendario . "'
GROUP BY 1,2,3,4,5,6
HAVING 1=1 
	AND tipo IN ('autonomo','prolabore','pj','lucros')
ORDER BY 
	codigo_receita
	, documento
	, ordem
";

//echo $sqlPAGTOS;
//exit;

$PAGTOS = mysql_query($sqlPAGTOS);

$bdec_anterior = 0;
$codigo_receita_anterior = 0;

while($rsPAGTOS = mysql_fetch_array($PAGTOS)){

	if($codigo_receita_anterior != $rsPAGTOS['codigo_receita']){

		//echo $rsPAGTOS['codigo_receita']."--->";

		/* IDREC */
		
		// LAYOUT 3.5 - REGISTRO DE IDENTIFICAÇÃO DO CODIGO DA RECEITA (identificador IDREC)
		
		/* campo 1 */$identificador_registro = "IDREC";
		/* campo 2 */$codigo_receita = $rsPAGTOS['codigo_receita']; // DE ACORDO COM TABELA DE CODIGOS DE RECEITAS CONSTANTES NA IN QUE DISPOE SOBRE A DIRF
		
		$idrec = str_pad($identificador_registro,5," ",STR_PAD_RIGHT).$fim_campo;
		$idrec .= str_pad($codigo_receita,4,"0",STR_PAD_LEFT).$fim_campo;
		$idrec .= $fim_linha;
		
		/* /IDREC */
		
		//echo $idrec;
		//exit;

		// incluindo o registro no arquivo final
		$dirf .= $idrec;
	}
	
	if($bdec_anterior != $rsPAGTOS['documento']){
		
		//echo $rsPAGTOS['tipo']."--->";
		//echo $rsPAGTOS['documento']."--->";
		//echo $rsPAGTOS['nome']."<BR>";

		if($rsPAGTOS['tipo'] != 'pj'){

			/* BPFDEC */
			
			// LAYOUT 3.6 - REGISTRO DE BENEFICIÁRIO PESSOA FISICA DO DECLARANTE (identificador BPFDEC)
			
			/* campo 1 */$identificador_registro = "BPFDEC";
			/* campo 2 */$cpf = preg_replace("#\W#","",$rsPAGTOS["documento"]);
//			/* campo 3 */$nome = $rsPAGTOS['nome'];
			/* campo 3 */$nome = strtr($rsPAGTOS['nome'], $trans);
			/* campo 4 */$data_laudo = ""; 
			
			$bpfdec = str_pad($identificador_registro,6," ",STR_PAD_RIGHT).$fim_campo;
			$bpfdec .= str_pad($cpf,11,"0",STR_PAD_LEFT).$fim_campo;
			$bpfdec .= str_pad($nome,60," ",STR_PAD_RIGHT).$fim_campo;
			$bpfdec .= ($data_laudo != "" ? str_pad($data_laudo,8,"0",STR_PAD_LEFT) : "").$fim_campo;
			$bpfdec .= $fim_linha;
			
			/* /BPFDEC */
			
			//echo $bpfdec;
			//exit;
		
			// incluindo o registro no arquivo final
			$dirf .= $bpfdec;
			
			
			
		} else {
						
			/* BPJDEC */
			
			// LAYOUT 3.7 - REGISTRO DE BENEFICIÁRIO PESSOA JURIDICA DO DECLARANTE (identificador BPJDEC)
			
			/* campo 1 */$identificador_registro = "BPJDEC";
			/* campo 2 */$cnpj = preg_replace("#\W#","",$rsPAGTOS["documento"]);
//			/* campo 3 */$nome = $rsPAGTOS['nome'];
			/* campo 3 */$nome = strtr($rsPAGTOS['nome'], $trans);
			
			$bpjdec = str_pad($identificador_registro,6," ",STR_PAD_RIGHT).$fim_campo;
			$bpjdec .= str_pad($cnpj,14,"0",STR_PAD_LEFT).$fim_campo;
			$bpjdec .= str_pad($nome,150," ",STR_PAD_RIGHT).$fim_campo;
			$bpjdec .= $fim_linha;
			
			/* /BPJDEC */
			
			//echo $bpjdec;
			//exit;
		
			$dirf .= $bpjdec;
			
			
		}
		
	}
	
	
	if($rsPAGTOS['tipo'] != 'pj'){		
		
		
		if(
			$rsPAGTOS["VALOR_BRUTO_JANEIRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_FEVEREIRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_MARCO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_ABRIL"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_MAIO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_JUNHO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_JULHO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_AGOSTO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_SETEMBRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_OUTUBRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_NOVEMBRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_DEZEMBRO"] > 0
		){
			/* RVM */
			
			// LAYOUT 4.6 - REGISTRO DE VALORES MENSAIS (identificadores RTRT, RTPO, RTPP, RTDP, RTPA, RTIRF, CJAA, CJAC, ESRT, ESPO, ESPP, ESDP, ESPA, ESIR, ESDJ, RIP65, RIDAC, RIIRP, RIAP, RIMOG, RIVC, RIBMR, DAJUD)
			
			/* campo 1 */$identificador_registro = "RTRT"; // rtrt, rtpo, rtpp, rtdp, rtpa, rtirf, cjaa, cjac, esrt, espo, espp, esdp, esir, esdj, rip65, ridac, riirc, riap, rimog, rivc, ribmr, dajud
			/* campo 2 */$janeiro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_JANEIRO"]);
			/* campo 3 */$fevereiro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_FEVEREIRO"]);
			/* campo 4 */$marco = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_MARCO"]);
			/* campo 5 */$abril = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_ABRIL"]);
			/* campo 6 */$maio = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_MAIO"]);
			/* campo 7 */$junho = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_JUNHO"]);
			/* campo 8 */$julho = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_JULHO"]);
			/* campo 9 */$agosto = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_AGOSTO"]);
			/* campo 10 */$setembro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_SETEMBRO"]);
			/* campo 11 */$outubro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_OUTUBRO"]);
			/* campo 12 */$novembro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_NOVEMBRO"]);
			/* campo 13 */$dezembro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_DEZEMBRO"]);
			/* campo 14 */$decimo_terceiro = "";
			
			$rvm = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$rvm .= ($janeiro != "" ? str_pad($janeiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($fevereiro != "" ? str_pad($fevereiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($marco != "" ? str_pad($marco,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($abril != "" ? str_pad($abril,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($maio != "" ? str_pad($maio,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($junho != "" ? str_pad($junho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($julho != "" ? str_pad($julho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($agosto != "" ? str_pad($agosto,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($setembro != "" ? str_pad($setembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($outubro != "" ? str_pad($outubro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($novembro != "" ? str_pad($novembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($dezembro != "" ? str_pad($dezembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($decimo_terceiro != "" ? str_pad($decimo_terceiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= $fim_linha;
			
			/* /RVM */
			
			//echo $rvm;
			//exit;
			
			 $dirf .= $rvm;

		}


		
		if(
			$rsPAGTOS["INSS_JANEIRO"] > 0
			|| $rsPAGTOS["INSS_FEVEREIRO"] > 0
			|| $rsPAGTOS["INSS_MARCO"] > 0
			|| $rsPAGTOS["INSS_ABRIL"] > 0
			|| $rsPAGTOS["INSS_MAIO"] > 0
			|| $rsPAGTOS["INSS_JUNHO"] > 0
			|| $rsPAGTOS["INSS_JULHO"] > 0
			|| $rsPAGTOS["INSS_AGOSTO"] > 0
			|| $rsPAGTOS["INSS_SETEMBRO"] > 0
			|| $rsPAGTOS["INSS_OUTUBRO"] > 0
			|| $rsPAGTOS["INSS_NOVEMBRO"] > 0
			|| $rsPAGTOS["INSS_DEZEMBRO"] > 0
		){
			/* RVM */
			
			// LAYOUT 4.6 - REGISTRO DE VALORES MENSAIS (identificadores RTRT, RTPO, RTPP, RTDP, RTPA, RTIRF, CJAA, CJAC, ESRT, ESPO, ESPP, ESDP, ESPA, ESIR, ESDJ, RIP65, RIDAC, RIIRP, RIAP, RIMOG, RIVC, RIBMR, DAJUD)
			
			/* campo 1 */$identificador_registro = "RTPO"; // rtrt, rtpo, rtpp, rtdp, rtpa, rtirf, cjaa, cjac, esrt, espo, espp, esdp, esir, esdj, rip65, ridac, riirc, riap, rimog, rivc, ribmr, dajud
			/* campo 2 */$janeiro = preg_replace("#\W#","",$rsPAGTOS["INSS_JANEIRO"]);
			/* campo 3 */$fevereiro = preg_replace("#\W#","",$rsPAGTOS["INSS_FEVEREIRO"]);
			/* campo 4 */$marco = preg_replace("#\W#","",$rsPAGTOS["INSS_MARCO"]);
			/* campo 5 */$abril = preg_replace("#\W#","",$rsPAGTOS["INSS_ABRIL"]);
			/* campo 6 */$maio = preg_replace("#\W#","",$rsPAGTOS["INSS_MAIO"]);
			/* campo 7 */$junho = preg_replace("#\W#","",$rsPAGTOS["INSS_JUNHO"]);
			/* campo 8 */$julho = preg_replace("#\W#","",$rsPAGTOS["INSS_JULHO"]);
			/* campo 9 */$agosto = preg_replace("#\W#","",$rsPAGTOS["INSS_AGOSTO"]);
			/* campo 10 */$setembro = preg_replace("#\W#","",$rsPAGTOS["INSS_SETEMBRO"]);
			/* campo 11 */$outubro = preg_replace("#\W#","",$rsPAGTOS["INSS_OUTUBRO"]);
			/* campo 12 */$novembro = preg_replace("#\W#","",$rsPAGTOS["INSS_NOVEMBRO"]);
			/* campo 13 */$dezembro = preg_replace("#\W#","",$rsPAGTOS["INSS_DEZEMBRO"]);
			/* campo 14 */$decimo_terceiro = "";
			
			$rvm = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$rvm .= ($janeiro != "" ? str_pad($janeiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($fevereiro != "" ? str_pad($fevereiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($marco != "" ? str_pad($marco,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($abril != "" ? str_pad($abril,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($maio != "" ? str_pad($maio,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($junho != "" ? str_pad($junho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($julho != "" ? str_pad($julho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($agosto != "" ? str_pad($agosto,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($setembro != "" ? str_pad($setembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($outubro != "" ? str_pad($outubro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($novembro != "" ? str_pad($novembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($dezembro != "" ? str_pad($dezembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($decimo_terceiro != "" ? str_pad($decimo_terceiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= $fim_linha;
			
			/* /RVM */
			
			//echo $rvm;
			//exit;
			
			 $dirf .= $rvm;

		}



		
		if(
			$rsPAGTOS["desconto_dependentes_JANEIRO"] > 0
			|| $rsPAGTOS["desconto_dependentes_FEVEREIRO"] > 0
			|| $rsPAGTOS["desconto_dependentes_MARCO"] > 0
			|| $rsPAGTOS["desconto_dependentes_ABRIL"] > 0
			|| $rsPAGTOS["desconto_dependentes_MAIO"] > 0
			|| $rsPAGTOS["desconto_dependentes_JUNHO"] > 0
			|| $rsPAGTOS["desconto_dependentes_JULHO"] > 0
			|| $rsPAGTOS["desconto_dependentes_AGOSTO"] > 0
			|| $rsPAGTOS["desconto_dependentes_SETEMBRO"] > 0
			|| $rsPAGTOS["desconto_dependentes_OUTUBRO"] > 0
			|| $rsPAGTOS["desconto_dependentes_NOVEMBRO"] > 0
			|| $rsPAGTOS["desconto_dependentes_DEZEMBRO"] > 0
		){
			/* RVM */
			
			// LAYOUT 4.6 - REGISTRO DE VALORES MENSAIS (identificadores RTRT, RTPO, RTPP, RTDP, RTPA, RTIRF, CJAA, CJAC, ESRT, ESPO, ESPP, ESDP, ESPA, ESIR, ESDJ, RIP65, RIDAC, RIIRP, RIAP, RIMOG, RIVC, RIBMR, DAJUD)
			
			/* campo 1 */$identificador_registro = "RTDP"; // rtrt, rtpo, rtpp, rtdp, rtpa, rtirf, cjaa, cjac, esrt, espo, espp, esdp, esir, esdj, rip65, ridac, riirc, riap, rimog, rivc, ribmr, dajud
			/* campo 2 */$janeiro = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_JANEIRO"]);
			/* campo 3 */$fevereiro = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_FEVEREIRO"]);
			/* campo 4 */$marco = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_MARCO"]);
			/* campo 5 */$abril = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_ABRIL"]);
			/* campo 6 */$maio = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_MAIO"]);
			/* campo 7 */$junho = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_JUNHO"]);
			/* campo 8 */$julho = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_JULHO"]);
			/* campo 9 */$agosto = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_AGOSTO"]);
			/* campo 10 */$setembro = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_SETEMBRO"]);
			/* campo 11 */$outubro = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_OUTUBRO"]);
			/* campo 12 */$novembro = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_NOVEMBRO"]);
			/* campo 13 */$dezembro = preg_replace("#\W#","",$rsPAGTOS["desconto_dependentes_DEZEMBRO"]);
			/* campo 14 */$decimo_terceiro = "";
			
			$rvm = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$rvm .= ($janeiro != "" ? str_pad($janeiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($fevereiro != "" ? str_pad($fevereiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($marco != "" ? str_pad($marco,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($abril != "" ? str_pad($abril,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($maio != "" ? str_pad($maio,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($junho != "" ? str_pad($junho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($julho != "" ? str_pad($julho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($agosto != "" ? str_pad($agosto,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($setembro != "" ? str_pad($setembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($outubro != "" ? str_pad($outubro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($novembro != "" ? str_pad($novembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($dezembro != "" ? str_pad($dezembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($decimo_terceiro != "" ? str_pad($decimo_terceiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= $fim_linha;
			
			/* /RVM */
			
			//echo $rvm;
			//exit;
			
			 $dirf .= $rvm;

		}

		
		if(
			$rsPAGTOS["IR_JANEIRO"] > 0
			|| $rsPAGTOS["IR_FEVEREIRO"] > 0
			|| $rsPAGTOS["IR_MARCO"] > 0
			|| $rsPAGTOS["IR_ABRIL"] > 0
			|| $rsPAGTOS["IR_MAIO"] > 0
			|| $rsPAGTOS["IR_JUNHO"] > 0
			|| $rsPAGTOS["IR_JULHO"] > 0
			|| $rsPAGTOS["IR_AGOSTO"] > 0
			|| $rsPAGTOS["IR_SETEMBRO"] > 0
			|| $rsPAGTOS["IR_OUTUBRO"] > 0
			|| $rsPAGTOS["IR_NOVEMBRO"] > 0
			|| $rsPAGTOS["IR_DEZEMBRO"] > 0
		){
			/* RVM */
			
			// LAYOUT 4.6 - REGISTRO DE VALORES MENSAIS (identificadores RTRT, RTPO, RTPP, RTDP, RTPA, RTIRF, CJAA, CJAC, ESRT, ESPO, ESPP, ESDP, ESPA, ESIR, ESDJ, RIP65, RIDAC, RIIRP, RIAP, RIMOG, RIVC, RIBMR, DAJUD)
			
			/* campo 1 */$identificador_registro = "RTIRF"; // rtrt, rtpo, rtpp, rtdp, rtpa, rtirf, cjaa, cjac, esrt, espo, espp, esdp, esir, esdj, rip65, ridac, riirc, riap, rimog, rivc, ribmr, dajud
			/* campo 2 */$janeiro = preg_replace("#\W#","",$rsPAGTOS["IR_JANEIRO"]);
			/* campo 3 */$fevereiro = preg_replace("#\W#","",$rsPAGTOS["IR_FEVEREIRO"]);
			/* campo 4 */$marco = preg_replace("#\W#","",$rsPAGTOS["IR_MARCO"]);
			/* campo 5 */$abril = preg_replace("#\W#","",$rsPAGTOS["IR_ABRIL"]);
			/* campo 6 */$maio = preg_replace("#\W#","",$rsPAGTOS["IR_MAIO"]);
			/* campo 7 */$junho = preg_replace("#\W#","",$rsPAGTOS["IR_JUNHO"]);
			/* campo 8 */$julho = preg_replace("#\W#","",$rsPAGTOS["IR_JULHO"]);
			/* campo 9 */$agosto = preg_replace("#\W#","",$rsPAGTOS["IR_AGOSTO"]);
			/* campo 10 */$setembro = preg_replace("#\W#","",$rsPAGTOS["IR_SETEMBRO"]);
			/* campo 11 */$outubro = preg_replace("#\W#","",$rsPAGTOS["IR_OUTUBRO"]);
			/* campo 12 */$novembro = preg_replace("#\W#","",$rsPAGTOS["IR_NOVEMBRO"]);
			/* campo 13 */$dezembro = preg_replace("#\W#","",$rsPAGTOS["IR_DEZEMBRO"]);
			/* campo 14 */$decimo_terceiro = "";
			
			$rvm = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$rvm .= ($janeiro != "" ? str_pad($janeiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($fevereiro != "" ? str_pad($fevereiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($marco != "" ? str_pad($marco,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($abril != "" ? str_pad($abril,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($maio != "" ? str_pad($maio,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($junho != "" ? str_pad($junho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($julho != "" ? str_pad($julho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($agosto != "" ? str_pad($agosto,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($setembro != "" ? str_pad($setembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($outubro != "" ? str_pad($outubro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($novembro != "" ? str_pad($novembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($dezembro != "" ? str_pad($dezembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($decimo_terceiro != "" ? str_pad($decimo_terceiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= $fim_linha;
			
			/* /RVM */
			
			//echo $rvm;
			//exit;
			
			 $dirf .= $rvm;

		}



		if($rsPAGTOS['tipo'] == 'lucros'){
	
					
			if(
				$rsPAGTOS["valor_liquido_anual"] > 0
			){
			
				/* RIL96 e RIPTS */
				
				// LAYOUT 4.7 - REGISTRO DE VALORES ANUAIS ISENTOS (identificador RIL96 e RIPTS)
				
				/* campo 1 $identificador_registro = "RIL96"; // RIL96 ou RIPTS*/
				/* campo 1 */$identificador_registro = "RIPTS"; // RIL96 ou RIPTS
				
				/* campo 2 */$valor_pago_ano = preg_replace("#\W#","",$rsPAGTOS["valor_liquido_anual"]);
				
				$ril96 = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
				$ril96 .= str_pad($valor_pago_ano,13,"0",STR_PAD_LEFT).$fim_campo;
				$ril96 .= $fim_linha;
	
				/* /RIL96 e RIPTS */
				
				//echo $ril96;
				//exit;
				
				$dirf .= $ril96;
	
			}
			
		}
	

			
	} else {


		if(
			$rsPAGTOS["VALOR_BRUTO_JANEIRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_FEVEREIRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_MARCO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_ABRIL"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_MAIO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_JUNHO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_JULHO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_AGOSTO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_SETEMBRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_OUTUBRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_NOVEMBRO"] > 0
			|| $rsPAGTOS["VALOR_BRUTO_DEZEMBRO"] > 0
		){
		
		
					
			/* RVM */
			
			// LAYOUT 4.6 - REGISTRO DE VALORES MENSAIS (identificadores RTRT, RTPO, RTPP, RTDP, RTPA, RTIRF, CJAA, CJAC, ESRT, ESPO, ESPP, ESDP, ESPA, ESIR, ESDJ, RIP65, RIDAC, RIIRP, RIAP, RIMOG, RIVC, RIBMR, DAJUD)
			
			/* campo 1 */$identificador_registro = "RTRT"; // rtrt, rtpo, rtpp, rtdp, rtpa, rtirf, cjaa, cjac, esrt, espo, espp, esdp, esir, esdj, rip65, ridac, riirc, riap, rimog, rivc, ribmr, dajud
			/* campo 2 */$janeiro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_JANEIRO"]);
			/* campo 3 */$fevereiro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_FEVEREIRO"]);
			/* campo 4 */$marco = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_MARCO"]);
			/* campo 5 */$abril = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_ABRIL"]);
			/* campo 6 */$maio = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_MAIO"]);
			/* campo 7 */$junho = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_JUNHO"]);
			/* campo 8 */$julho = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_JULHO"]);
			/* campo 9 */$agosto = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_AGOSTO"]);
			/* campo 10 */$setembro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_SETEMBRO"]);
			/* campo 11 */$outubro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_OUTUBRO"]);
			/* campo 12 */$novembro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_NOVEMBRO"]);
			/* campo 13 */$dezembro = preg_replace("#\W#","",$rsPAGTOS["VALOR_BRUTO_DEZEMBRO"]);
			/* campo 14 */$decimo_terceiro = "";
			
			$rvm = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$rvm .= ($janeiro != "" ? str_pad($janeiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($fevereiro != "" ? str_pad($fevereiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($marco != "" ? str_pad($marco,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($abril != "" ? str_pad($abril,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($maio != "" ? str_pad($maio,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($junho != "" ? str_pad($junho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($julho != "" ? str_pad($julho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($agosto != "" ? str_pad($agosto,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($setembro != "" ? str_pad($setembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($outubro != "" ? str_pad($outubro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($novembro != "" ? str_pad($novembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($dezembro != "" ? str_pad($dezembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($decimo_terceiro != "" ? str_pad($decimo_terceiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= $fim_linha;
			
			/* /RVM */
			
			//echo $rvm;
			//exit;
			
			 $dirf .= $rvm;

		}
		
		if(
			$rsPAGTOS["IR_JANEIRO"] > 0
			|| $rsPAGTOS["IR_FEVEREIRO"] > 0
			|| $rsPAGTOS["IR_MARCO"] > 0
			|| $rsPAGTOS["IR_ABRIL"] > 0
			|| $rsPAGTOS["IR_MAIO"] > 0
			|| $rsPAGTOS["IR_JUNHO"] > 0
			|| $rsPAGTOS["IR_JULHO"] > 0
			|| $rsPAGTOS["IR_AGOSTO"] > 0
			|| $rsPAGTOS["IR_SETEMBRO"] > 0
			|| $rsPAGTOS["IR_OUTUBRO"] > 0
			|| $rsPAGTOS["IR_NOVEMBRO"] > 0
			|| $rsPAGTOS["IR_DEZEMBRO"] > 0
		){
			/* RVM */
			
			// LAYOUT 4.6 - REGISTRO DE VALORES MENSAIS (identificadores RTRT, RTPO, RTPP, RTDP, RTPA, RTIRF, CJAA, CJAC, ESRT, ESPO, ESPP, ESDP, ESPA, ESIR, ESDJ, RIP65, RIDAC, RIIRP, RIAP, RIMOG, RIVC, RIBMR, DAJUD)
			
			/* campo 1 */$identificador_registro = "RTIRF"; // rtrt, rtpo, rtpp, rtdp, rtpa, rtirf, cjaa, cjac, esrt, espo, espp, esdp, esir, esdj, rip65, ridac, riirc, riap, rimog, rivc, ribmr, dajud
			/* campo 2 */$janeiro = preg_replace("#\W#","",$rsPAGTOS["IR_JANEIRO"]);
			/* campo 3 */$fevereiro = preg_replace("#\W#","",$rsPAGTOS["IR_FEVEREIRO"]);
			/* campo 4 */$marco = preg_replace("#\W#","",$rsPAGTOS["IR_MARCO"]);
			/* campo 5 */$abril = preg_replace("#\W#","",$rsPAGTOS["IR_ABRIL"]);
			/* campo 6 */$maio = preg_replace("#\W#","",$rsPAGTOS["IR_MAIO"]);
			/* campo 7 */$junho = preg_replace("#\W#","",$rsPAGTOS["IR_JUNHO"]);
			/* campo 8 */$julho = preg_replace("#\W#","",$rsPAGTOS["IR_JULHO"]);
			/* campo 9 */$agosto = preg_replace("#\W#","",$rsPAGTOS["IR_AGOSTO"]);
			/* campo 10 */$setembro = preg_replace("#\W#","",$rsPAGTOS["IR_SETEMBRO"]);
			/* campo 11 */$outubro = preg_replace("#\W#","",$rsPAGTOS["IR_OUTUBRO"]);
			/* campo 12 */$novembro = preg_replace("#\W#","",$rsPAGTOS["IR_NOVEMBRO"]);
			/* campo 13 */$dezembro = preg_replace("#\W#","",$rsPAGTOS["IR_DEZEMBRO"]);
			/* campo 14 */$decimo_terceiro = "";
			
			$rvm = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$rvm .= ($janeiro != "" ? str_pad($janeiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($fevereiro != "" ? str_pad($fevereiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($marco != "" ? str_pad($marco,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($abril != "" ? str_pad($abril,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($maio != "" ? str_pad($maio,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($junho != "" ? str_pad($junho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($julho != "" ? str_pad($julho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($agosto != "" ? str_pad($agosto,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($setembro != "" ? str_pad($setembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($outubro != "" ? str_pad($outubro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($novembro != "" ? str_pad($novembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($dezembro != "" ? str_pad($dezembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($decimo_terceiro != "" ? str_pad($decimo_terceiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= $fim_linha;
			
			/* /RVM */
			
			//echo $rvm;
			//exit;
			
			 $dirf .= $rvm;

		}

	}


	
/* ##################### O BLOCO A SEGUIR SE REPETIRÁ NOS VARIOS LOOPS PARA GERAÇÃO DO ARQUIVO DIRF ######################### */
/* ##################### EXEMPLO: REGISTRO DECPJ - INCLUIR O BLOCO A SEGUIR ################################################# */
/* ##################### 		  REGISTRO FCI - INCLUIR O BLOCO A SEGUIR ################################################### */
/* ##################### 		  REGISTRO PROC - INCLUIR O BLOCO A SEGUIR ################################################## */
			
			/* RVM */
			
			// LAYOUT 4.6 - REGISTRO DE VALORES MENSAIS (identificadores RTRT, RTPO, RTPP, RTDP, RTPA, RTIRF, CJAA, CJAC, ESRT, ESPO, ESPP, ESDP, ESPA, ESIR, ESDJ, RIP65, RIDAC, RIIRP, RIAP, RIMOG, RIVC, RIBMR, DAJUD)
			
			/* campo 1 *$identificador_registro = "RTRT"; // rtrt, rtpo, rtpp, rtdp, rtpa, rtirf, cjaa, cjac, esrt, espo, espp, esdp, esir, esdj, rip65, ridac, riirc, riap, rimog, rivc, ribmr, dajud
			/* campo 2 *$janeiro = "";
			/* campo 3 *$fevereiro = "";
			/* campo 4 *$marco = "";
			/* campo 5 *$abril = "";
			/* campo 6 *$maio = "";
			/* campo 7 *$junho = "";
			/* campo 8 *$julho = "";
			/* campo 9 *$agosto = "";
			/* campo 10 *$setembro = "";
			/* campo 11 *$outubro = "";
			/* campo 12 *$novembro = "";
			/* campo 13 *$dezembro = "";
			/* campo 14 *$decimo_terceiro = "";
			/*
			$rvm = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$rvm .= ($janeiro != "" ? str_pad($janeiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($fevereiro != "" ? str_pad($fevereiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($marco != "" ? str_pad($marco,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($abril != "" ? str_pad($abril,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($maio != "" ? str_pad($maio,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($junho != "" ? str_pad($junho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($julho != "" ? str_pad($julho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($agosto != "" ? str_pad($agosto,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($setembro != "" ? str_pad($setembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($outubro != "" ? str_pad($outubro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($novembro != "" ? str_pad($novembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($dezembro != "" ? str_pad($dezembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($decimo_terceiro != "" ? str_pad($decimo_terceiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= $fim_linha;
			*/
			/* /RVM */
			
			//echo $rvm;
			//exit;
			
			// $dirf .= $rvm;
			
			
			
			
			
			
			
			/* RIL96 e RIPTS */
			
			// LAYOUT 4.7 - REGISTRO DE VALORES ANUAIS ISENTOS (identificador RIL96 e RIPTS)
			
			/* campo 1 *$identificador_registro = "RIL96"; // RIL96 ou RIPTS
			/* campo 2 *$valor_pago_ano = "";
			/*
			$ril96 = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$ril96 .= str_pad($valor_pago_ano,13,"0",STR_PAD_LEFT).$fim_campo;
			$ril96 .= $fim_linha;
			*/
			/* /RIL96 e RIPTS */
			
			//echo $ril96;
			//exit;
			
			// $dirf .= $ril96;
			
			
			
			
			
			/* RIO */
			
			// LAYOUT 4.8 - REGISTRO DE VALORES ANUAIS DE RENDIMENTOS ISENTOS - OURTOS (identificador RIO)
			
			/* campo 1 *$identificador_registro = "RIO"; 
			/* campo 2 *$valor_pago_ano = "";
			/* campo 3 *$descricao = "";
			/*
			$rio = str_pad($identificador_registro,3," ",STR_PAD_RIGHT).$fim_campo;
			$rio .= str_pad($valor_pago_ano,13,"0",STR_PAD_LEFT).$fim_campo;
			$rio .= str_pad($descricao,60," ",STR_PAD_RIGHT).$fim_campo;
			$rio .= $fim_linha;
			*/
			/* /RIO */
			
			//echo $rio;
			//exit;

			// $dirf .= $rio;

/* ##################### FIM DO BLOCO ######################### */
	

	$bdec_anterior = $rsPAGTOS["documento"];
	$codigo_receita_anterior = $rsPAGTOS["codigo_receita"];


//echo "<BR>###" . $dirf . "###<BR>";
//exit;
	
}


	
/* FCI */

// LAYOUT 3.8 - REGISTRO DE IDENTIFICACAO DO FUNDO OU CLUBE DE INVESTIMENTO (identificador FCI)

/* campo 1 */$identificador_registro = "FCI";
/* campo 2 */$cnpj = "15556741000120";
/* campo 3 */$nome = "FABIO RIBEIRO ME";
/*
$fci = str_pad($identificador_registro,3," ",STR_PAD_RIGHT).$fim_campo;
$fci .= str_pad($cnpj,14,"0",STR_PAD_LEFT).$fim_campo;
$fci .= str_pad($nome,150," ",STR_PAD_RIGHT).$fim_campo;
$fci .= $fim_linha;
*/
/* /FCI */

//echo $fci;
//exit;

// $dirf .= $fci;



/* BPFFCI */

// LAYOUT 3.9 - REGISTRO DO BENEFICIARIO PESSOA FISICA DO FUNDO OU CLUBE DE INVESTIMENTO (identificador BPFFCI)

/* campo 1 */$identificador_registro = "BPFFCI";
/* campo 2 */$cpf = "29368310831";
/* campo 3 */$nome = "Fabio Henrique";
/* campo 4 */$data_laudo = "";
/*
$bpffci = str_pad($identificador_registro,6," ",STR_PAD_RIGHT).$fim_campo;
$bpffci .= str_pad($cpf,11,"0",STR_PAD_LEFT).$fim_campo;
$bpffci .= str_pad($nome,60," ",STR_PAD_RIGHT).$fim_campo;
$bpffci .= ($data_laudo != "" ? str_pad($data_laudo,8,"0",STR_PAD_LEFT) : "").$fim_campo;
$bpffci .= $fim_linha;
*/
/* /BPFFCI */

//echo $bpffci;
//exit;

// $dirf .= $bpffci;



/* BPJFCI */

// LAYOUT 4 - REGISTRO DO BENEFICIARIO PESSOA JURIDICA DO FUNDO OU CLUBE DE INVESTIMENTO (identificador BPJFCI)

/* campo 1 */$identificador_registro = "BPJFCI";
/* campo 2 */$cnpj = "29368310831";
/* campo 3 */$nome = "Fabio Henrique";
/*
$bpjfci = str_pad($identificador_registro,6," ",STR_PAD_RIGHT).$fim_campo;
$bpjfci .= str_pad($cnpj,14,"0",STR_PAD_LEFT).$fim_campo;
$bpjfci .= str_pad($nome,150," ",STR_PAD_RIGHT).$fim_campo;
$bpjfci .= $fim_linha;
*/
/* /BPJFCI */

//echo $bpjfci;
//exit;

// $dirf .= $bpjfci;






/* PROC */

// LAYOUT 4.1 - REGISTRO DE PROCESSO DA JUSTICA DO TRABALHO/FEDERAL/ESTADUAL/DF (identificador PROC)

/* campo 1 */$identificador_registro = "PROC";
/* campo 2 */$indicador_justica = "1";
/*
1 - Justica federal
2 - Justica do trabalho
3 - Justica estadual / Distrito federal
*/
/* campo 3 */$numero_processo = "0";
/* campo 4 */$tipo_advogado = ""; // 1 - pessoa fisica / 2 - pessoa juridica
/* campo 5 */$cpf_advogado = ""; // cpf ou cnpj
/* campo 6 */$nome_advogado = "";
/*
$proc = str_pad($identificador_registro,4," ",STR_PAD_RIGHT).$fim_campo;
$proc .= str_pad($indicador_justica,1,"0",STR_PAD_LEFT).$fim_campo;
$proc .= str_pad($numero_processo,20," ",STR_PAD_RIGHT).$fim_campo;
$proc .= ($tipo_advogado != "" ? str_pad($tipo_advogado,1,"0",STR_PAD_LEFT) : "").$fim_campo;
$proc .= ($cpf_advogado != "" ? str_pad($cpf_advogado,14,"0",STR_PAD_LEFT) : "").$fim_campo;
$proc .= ($nome_advogado != "" ? str_pad($nome_advogado,150," ",STR_PAD_RIGHT) : "").$fim_campo;
$proc .= $fim_linha;
*/
/* /PROC */

//echo $proc;
//exit;

// $dirf .= $proc;




/* BPFPROC */

// LAYOUT 4.2 - REGISTRO DE BENEFICIARIO PESSOA FISICA DO PROCESSO DA JUSTICA DO TRABALHO/FEDERAL/ESTADUAÇ/DISTRITO FEDERAL (identificador BPFPROC)

/* campo 1 */$identificador_registro = "BPFPROC";
/* campo 2 */$cpf = "29368310831";
/* campo 3 */$nome = "Fabio Henrique";
/* campo 4 */$data_laudo = "";
/*
$bpfproc = str_pad($identificador_registro,7," ",STR_PAD_RIGHT).$fim_campo;
$bpfproc .= str_pad($cpf,11,"0",STR_PAD_LEFT).$fim_campo;
$bpfproc .= str_pad($nome,60," ",STR_PAD_RIGHT).$fim_campo;
$bpfproc .= ($data_laudo != "" ? str_pad($data_laudo,8,"0",STR_PAD_LEFT) : "").$fim_campo;
$bpfproc .= $fim_linha;
*/
/* /BPFPROC */

//echo $bpfproc;
//exit;

// $dirf .= $bpfproc;






/* BPJPROC */

// LAYOUT 4.3 - REGISTRO DE BENEFICIARIO PESSOA JURIDICA DO PROCESSO DA JUSTICA DO TRABALHO/FEDERAL/ESTADUAÇ/DISTRITO FEDERAL (identificador BPJPROC)

/* campo 1 */$identificador_registro = "BPJPROC";
/* campo 2 */$cnpj = "29368310831";
/* campo 3 */$nome = "Fabio Henrique";
/*
$bpjproc = str_pad($identificador_registro,7," ",STR_PAD_RIGHT).$fim_campo;
$bpjproc .= str_pad($cnpj,14,"0",STR_PAD_LEFT).$fim_campo;
$bpjproc .= str_pad($nome,150," ",STR_PAD_RIGHT).$fim_campo;
$bpjproc .= $fim_linha;
*/
/* /BPJPROC */

//echo $bpjproc;
//exit;

// $dirf .= $bpjproc;





/* RRA */

// LAYOUT 4.4 - REGISTRO DE RENDIMENTOS RECEBIDOS ACUMULADAMENTE (identificador RRA)

/* campo 1 */$identificador_registro = "RRA";
/* campo 2 */$indicador_rendimento = "1";
/*
1 - Justica federal
2 - Justica do trabalho
3 - Justica estadual / Distrito federal
*/
/* campo 3 */$numero_processo = "0";
/* campo 4 */$tipo_advogado = ""; // 1 - pessoa fisica / 2 - pessoa juridica
/* campo 5 */$cpf_advogado = ""; // cpf ou cnpj
/* campo 6 */$nome_advogado = "";
/*
$rra = str_pad($identificador_registro,3," ",STR_PAD_RIGHT).$fim_campo;
$rra .= str_pad($indicador_rendimento,1,"0",STR_PAD_LEFT).$fim_campo;
$rra .= str_pad($numero_processo,20," ",STR_PAD_RIGHT).$fim_campo;
$rra .= ($tipo_advogado != "" ? str_pad($tipo_advogado,1,"0",STR_PAD_LEFT) : "").$fim_campo;
$rra .= ($cpf_advogado != "" ? str_pad($cpf_advogado,14,"0",STR_PAD_LEFT) : "").$fim_campo;
$rra .= ($nome_advogado != "" ? str_pad($nome_advogado,150," ",STR_PAD_RIGHT) : "").$fim_campo;
$rra .= $fim_linha;
*/
/* /RRA */

//echo $rra;
//exit;

// $dirf .= $rra;







/* BPFRRA */

// LAYOUT 4.5 - REGISTRO DE BENEFICIARIO PESSOA FISICA DOS RENDIMENTOS RECEBIDOS ACUMULADAMENTE (identificador BPFRRA)

/* campo 1 */$identificador_registro = "BPFRRA";
/* campo 2 */$cpf = "29368310831";
/* campo 3 */$nome = "Fabio Henrique";
/* campo 4 */$natureza_rra = "";
/* campo 5 */$data_laudo = "";
/*
$bpfrra = str_pad($identificador_registro,6," ",STR_PAD_RIGHT).$fim_campo;
$bpfrra .= str_pad($cpf,11,"0",STR_PAD_LEFT).$fim_campo;
$bpfrra .= str_pad($nome,60," ",STR_PAD_RIGHT).$fim_campo;
$bpfrra .= ($natureza_rra != "" ? str_pad($natureza_rra,50," ",STR_PAD_RIGHT) : "").$fim_campo;
$bpfrra .= ($data_laudo != "" ? str_pad($data_laudo,8,"0",STR_PAD_LEFT) : "").$fim_campo;
$bpfrra .= $fim_linha;
*/
/* /BPFRRA */

//echo $bpfrra;
//exit;

// $dirf .= $bpfrra;








/* ##################### O BLOCO A SEGUIR SE REPETIRÁ NOS VARIOS LOOPS PARA GERAÇÃO DO ARQUIVO DIRF ######################### */
/* ##################### EXEMPLO: REGISTRO DECPJ - INCLUIR O BLOCO A SEGUIR ################################################# */
/* ##################### 		  REGISTRO FCI - INCLUIR O BLOCO A SEGUIR ################################################### */
/* ##################### 		  REGISTRO PROC - INCLUIR O BLOCO A SEGUIR ################################################## */
			
			/* RVM */
			
			// LAYOUT 4.6 - REGISTRO DE VALORES MENSAIS (identificadores RTRT, RTPO, RTPP, RTDP, RTPA, RTIRF, CJAA, CJAC, ESRT, ESPO, ESPP, ESDP, ESPA, ESIR, ESDJ, RIP65, RIDAC, RIIRP, RIAP, RIMOG, RIVC, RIBMR, DAJUD)
			
			/* campo 1 */$identificador_registro = "RTRT"; // rtrt, rtpo, rtpp, rtdp, rtpa, rtirf, cjaa, cjac, esrt, espo, espp, esdp, esir, esdj, rip65, ridac, riirc, riap, rimog, rivc, ribmr, dajud
			/* campo 2 */$janeiro = "";
			/* campo 3 */$fevereiro = "";
			/* campo 4 */$marco = "";
			/* campo 5 */$abril = "";
			/* campo 6 */$maio = "";
			/* campo 7 */$junho = "";
			/* campo 8 */$julho = "";
			/* campo 9 */$agosto = "";
			/* campo 10 */$setembro = "";
			/* campo 11 */$outubro = "";
			/* campo 12 */$novembro = "";
			/* campo 13 */$dezembro = "";
			/* campo 14 */$decimo_terceiro = "";
			/*
			$rvm = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$rvm .= ($janeiro != "" ? str_pad($janeiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($fevereiro != "" ? str_pad($fevereiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($marco != "" ? str_pad($marco,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($abril != "" ? str_pad($abril,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($maio != "" ? str_pad($maio,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($junho != "" ? str_pad($junho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($julho != "" ? str_pad($julho,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($agosto != "" ? str_pad($agosto,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($setembro != "" ? str_pad($setembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($outubro != "" ? str_pad($outubro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($novembro != "" ? str_pad($novembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($dezembro != "" ? str_pad($dezembro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= ($decimo_terceiro != "" ? str_pad($decimo_terceiro,13,"0",STR_PAD_LEFT) : "").$fim_campo;
			$rvm .= $fim_linha;
			*/
			/* /RVM */
			
			//echo $rvm;
			//exit;
			
			// $dirf .= $rvm;
			
			
			
			
			
			
			
			/* RIL96 e RIPTS */
			
			// LAYOUT 4.7 - REGISTRO DE VALORES ANUAIS ISENTOS (identificador RIL96 e RIPTS)
			
			/* campo 1 */$identificador_registro = "RIL96"; // RIL96 ou RIPTS
			/* campo 2 */$valor_pago_ano = "";
			/*
			$ril96 = str_pad($identificador_registro,strlen($identificador_registro)," ",STR_PAD_RIGHT).$fim_campo;
			$ril96 .= str_pad($valor_pago_ano,13,"0",STR_PAD_LEFT).$fim_campo;
			*/
			/* /RIL96 e RIPTS */
			
			//echo $ril96;
			//exit;
			
			// $dirf .= $ril96;
			
			
			
			
			
			/* RIO */
			
			// LAYOUT 4.8 - REGISTRO DE VALORES ANUAIS DE RENDIMENTOS ISENTOS - OURTOS (identificador RIO)
			
			/* campo 1 */$identificador_registro = "RIO"; 
			/* campo 2 */$valor_pago_ano = "";
			/* campo 3 */$descricao = "";
			/*
			$rio = str_pad($identificador_registro,3," ",STR_PAD_RIGHT).$fim_campo;
			$rio .= str_pad($valor_pago_ano,13,"0",STR_PAD_LEFT).$fim_campo;
			$rio .= str_pad($descricao,60," ",STR_PAD_RIGHT).$fim_campo;
			*/
			/* /RIO */
			
			//echo $rio;
			//exit;

			// $dirf .= $rio;

/* ##################### FIM DO BLOCO ######################### */





/* ##################### O BLOCO A SEGUIR SÓ SERÁ VINCULADO AO REGISTRO RRA ######################### */

		/* QTMESES */
		
		// LAYOUT 4.9 - REGISTRO DE QUANTIDADE DE MESES (identificador QTMESES)
		
		/* campo 1 */$identificador_registro = "QTMESES"; 
		/* campo 2 */$quantidade_janeiro = "";
		/* campo 3 */$quantidade_fevereiro = "";
		/* campo 4 */$quantidade_marco = "";
		/* campo 5 */$quantidade_abril = "";
		/* campo 6 */$quantidade_maio = "";
		/* campo 7 */$quantidade_junho = "";
		/* campo 8 */$quantidade_julho = "";
		/* campo 9 */$quantidade_agosto = "";
		/* campo 10 */$quantidade_setembro = "";
		/* campo 11 */$quantidade_outubro = "";
		/* campo 12 */$quantidade_novembro = "";
		/* campo 13 */$quantidade_dezembro = "";
		/*
		$qtmeses = str_pad($identificador_registro,3," ",STR_PAD_RIGHT).$fim_campo;
		$qtmeses .= ($quantidade_janeiro != "" ? str_pad($quantidade_janeiro,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_fevereiro != "" ? str_pad($quantidade_fevereiro,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_marco != "" ? str_pad($quantidade_marco,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_abril != "" ? str_pad($quantidade_abril,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_maio != "" ? str_pad($quantidade_maio,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_junho != "" ? str_pad($quantidade_junho,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_julho != "" ? str_pad($quantidade_julho,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_agosto != "" ? str_pad($quantidade_agosto,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_setembro != "" ? str_pad($quantidade_setembro,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_outubro != "" ? str_pad($quantidade_outubro,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_novembro != "" ? str_pad($quantidade_novembro,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		$qtmeses .= ($quantidade_dezembro != "" ? str_pad($quantidade_dezembro,4,"0",STR_PAD_LEFT) : "").$fim_campo;
		*/
		/* /QTMESES */
		
		//echo $qtmeses;
		//exit;

		// $dirf .= $qtmeses;

/* ##################### FIM DO BLOCO ######################### */






/* PSE */

// LAYOUT 5 - REGISTRO DE PAGAMENTOS A PLANO PRIVADO DE ASSISTENCIA A SAUDE (identificador PSE)

// ocorre somente uma vez no arquivo caso exista informação de valores pagos pelo titular/dependente do plano de assistencia a saude
/* campo 1 */$identificador_registro = "PSE"; 
/*
$pse = str_pad($identificador_registro,3," ",STR_PAD_RIGHT).$fim_campo;
*/
/* /PSE */

//echo $pse;
//exit;

// $dirf .= $pse;




/* OPSE */

// LAYOUT 5.1 - REGISTRO DE OPERADORA DO PLANO PRIVADO DE ASSISTENCIA A SAUDE - COLETIVO EMPRESARIAL (identificador OPSE)
// ocorre caso exista o registro PSE 
// serão apresentados todos os cnpj em ordem crescente
/* campo 1 */$identificador_registro = "OPSE";
/* campo 2 */$cnpj = "";
/* campo 3 */$nome = "";
/* campo 3 */$registro_ans = "";
/*
$opse = str_pad($identificador_registro,4," ",STR_PAD_RIGHT).$fim_campo;
$opse .= str_pad($cnpj,14,"0",STR_PAD_LEFT).$fim_campo;
$opse .= str_pad($nome,150," ",STR_PAD_RIGHT).$fim_campo;
$opse .= str_pad($registro_ans,6,"0",STR_PAD_LEFT).$fim_campo;
*/
/* /OPSE */

//echo $opse;
//exit;

// $dirf .= $opse;







/* TPSE */

// LAYOUT 5.2 - REGISTRO DE TITULAR DO PLANO PRIVADO DE ASSISTENCIA A SAUDE - COLETIVO EMPRESARIAL (identificador TPSE)
// serão apresentados todos os cpf em ordem crescente
// deve ser associado ao registro OPSE
/* campo 1 */$identificador_registro = "TPSE";
/* campo 2 */$cpf = "";
/* campo 3 */$nome = "";
/* campo 3 */$valor_pago_ano = "";
/*
$tpse = str_pad($identificador_registro,4," ",STR_PAD_RIGHT).$fim_campo;
$tpse .= str_pad($cpf,11,"0",STR_PAD_LEFT).$fim_campo;
$tpse .= str_pad($nome,60," ",STR_PAD_RIGHT).$fim_campo;
$tpse .= str_pad($valor_pago_ano,13,"0",STR_PAD_LEFT).$fim_campo;
*/
/* /TPSE */

//echo $tpse;
//exit;

// $dirf .= $tpse;




/* DTPSE */

// LAYOUT 5.3 - REGISTRO DE DEPENDENTE DO PLANO PRIVADO DE ASSISTENCIA A SAUDE - COLETIVO EMPRESARIAL (identificador DTPSE)
// serão apresentados todos os cpf e data de nascimento em ordem crescente
// deve ser associado ao registro TPSE
/* campo 1 */$identificador_registro = "DTPSE";
/* campo 2 */$cpf = "";
/* campo 3 */$data_nascimento = "";
/* campo 4 */$nome = "";
/* campo 5 */$relacao_dependencia = ""; 
/*
3 - conjuge/companheiro
4 - filho
6 - enteado
8 - pai/mae
10 - agregado/outros
*/
/* campo 6 */$valor_pago_ano = "";
/*
$dtpse = str_pad($identificador_registro,5," ",STR_PAD_RIGHT).$fim_campo;
$dtpse .= str_pad($cpf,11,"0",STR_PAD_LEFT).$fim_campo;
$dtpse .= str_pad($data_nascimento,8,"0",STR_PAD_LEFT).$fim_campo;
$dtpse .= str_pad($nome,60," ",STR_PAD_RIGHT).$fim_campo;
$dtpse .= ($relacao_dependencia != "" ? str_pad($relacao_dependencia,2,"0",STR_PAD_LEFT) : "").$fim_campo;
$dtpse .= str_pad($valor_pago_ano,13,"0",STR_PAD_LEFT).$fim_campo;
*/
/* /DTPSE */

//echo $dtpse;
//exit;

// $dirf .= $dtpse;









/* RPDE */

// LAYOUT 5.4 - REGISTRO DE RENDIMENTOS PAGOS A RESIDENTES OU DOMICILIADOS NO EXTERIOR (identificador RPDE)
// ocorre somente uma vez no arquivo caso exista informação de rendimentos pagos a residentes no exterior
/* campo 1 */$identificador_registro = "RPDE";
/*
$rpde = str_pad($identificador_registro,4," ",STR_PAD_RIGHT).$fim_campo;
*/
/* /RPDE */

//echo $rpde;
//exit;

// $dirf .= $rpde;





/* BRPDE */

// LAYOUT 5.5 - REGISTRO DE QUANTIDADE DE MESES (identificador BRPDE)
/*
deve estar classificado em orde crescente por:
beneficiario
codigo do pais
numero de identificacao fiscal NIF
deve estar associado ao registro do tipo RPDE
*/
 
/* campo 1 */$identificador_registro = "BRPDE"; 
/* campo 2 */$beneficiario = ""; // 1 - pessoa fisica / 2 - pessoa juridica
/* campo 3 */$codigo_pais = "";
/* campo 4 */$nif = "";
/* campo 5 */$identificador_beneficiario_dispensado_nif = "";// S - Dispensado do Número de identificação fiscal (NIF) /  N - Não é dispensado do Número de identificação fiscal (NIF) 
/* campo 6 */$identificador_pais_dispensado_nif = ""; // S - Dispensado do Número de identificação fiscal (NIF) /  N - Não é dispensado do Número de identificação fiscal (NIF) 
/* campo 7 */$cpf= "";
/* campo 8 */$nome = "";
/* campo 9 */$relacao_fonte_pagadora = ""; // CONFORME TABELA DE INFORMAÇÕES SOBRE OS BENEFICIARIOS DOS RENDIMENTOS CONSTANTE NA IN QUE DISPOE SOBRE A DIRF
/* campo 10 */$logradouro = "";
/* campo 11 */$numero = "";
/* campo 12 */$complemento = "";
/* campo 13 */$bairro= "";
/* campo 14 */$cep= "";
/* campo 15 */$cidade= "";
/* campo 16 */$estado= "";
/* campo 17 */$telefone= "";
/*
$brpde = str_pad($identificador_registro,5," ",STR_PAD_RIGHT).$fim_campo;
$brpde .= str_pad($beneficiario,1,"0",STR_PAD_LEFT).$fim_campo;
$brpde .= str_pad($codigo_pais,3,"0",STR_PAD_LEFT).$fim_campo;
$brpde .= ($nif != "" ? str_pad($nif,30," ",STR_PAD_RIGHT) : "").$fim_campo;
$brpde .= str_pad($identificador_beneficiario_dispensado_nif,1," ",STR_PAD_RIGHT).$fim_campo;
$brpde .= str_pad($identificador_pais_dispensado_nif,1," ",STR_PAD_RIGHT).$fim_campo;
$brpde .= ($cpf != "" ? str_pad($cpf,14,"0",STR_PAD_LEFT) : "").$fim_campo;
$brpde .= str_pad($nome,150," ",STR_PAD_RIGHT).$fim_campo;
$brpde .= ($relacao_fonte_pagadora != "" ? str_pad($relacao_fonte_pagadora,3,"0",STR_PAD_LEFT) : "").$fim_campo;
$brpde .= ($logradouro != "" ? str_pad($logradouro,60," ",STR_PAD_RIGHT) : "").$fim_campo;
$brpde .= ($numero != "" ? str_pad($numero,6," ",STR_PAD_RIGHT) : "").$fim_campo;
$brpde .= ($complemento != "" ? str_pad($complemento,25," ",STR_PAD_RIGHT) : "").$fim_campo;
$brpde .= ($bairro != "" ? str_pad($bairro,20," ",STR_PAD_RIGHT) : "").$fim_campo;
$brpde .= ($cep != "" ? str_pad($cep,10,"0",STR_PAD_LEFT) : "").$fim_campo;
$brpde .= ($cidade != "" ? str_pad($cidade,40," ",STR_PAD_RIGHT) : "").$fim_campo;
$brpde .= ($estado != "" ? str_pad($estado,40," ",STR_PAD_RIGHT) : "").$fim_campo;
$brpde .= ($telefone != "" ? str_pad($telefone,15,"0",STR_PAD_LEFT) : "").$fim_campo;
*/
/* /BRPDE */

//echo $brpde;
//exit;

// $dirf .= $brpde;





/* VRPDE */

// LAYOUT 5.6 - REGISTRO DE VALORES DE RENDIMENTOS PAGOS A RESIDENTES NO EXTERIOR (identificador VRPDE)
/*
deve estar classificado em orde crescente por:
data pagamento
codigo da receita
deve estar associado ao registro do tipo BRPDE
*/
 
/* campo 1 */$identificador_registro = "VRPDE"; 
/* campo 2 */$data_pagamento = ""; 
/* campo 3 */$codigo_receita = "";
/* campo 4 */$tipo_rendimento = ""; // de acordo com tabela de informações sobre os resdimentos constante na IN que dispoe a DIRF
/* campo 5 */$rendimento_pago = "";
/* campo 6 */$imposto_retido = "";
/* campo 7 */$forma_tributacao= ""; // de acordo com tabela de informações sobre a forma de tributação constante na IN que dispoe a DIRF
/*
$vrpde = str_pad($identificador_registro,5," ",STR_PAD_RIGHT).$fim_campo;
$vrpde .= str_pad($data_pagamento,8,"0",STR_PAD_LEFT).$fim_campo;
$vrpde .= str_pad($codigo_receita,4,"0",STR_PAD_LEFT).$fim_campo;
$vrpde .= str_pad($tipo_rendimento,3,"0",STR_PAD_LEFT).$fim_campo;
$vrpde .= str_pad($rendimento_pago,13,"0",STR_PAD_LEFT).$fim_campo;
$vrpde .= ($imposto_retido != "" ? str_pad($imposto_retido,13,"0",STR_PAD_LEFT) : "").$fim_campo;
$vrpde .= str_pad($forma_tributacao,2,"0",STR_PAD_LEFT).$fim_campo;
*/
/* /VRPDE */

//echo $vrpde;
//exit;

// $dirf .= $vrpde;










/* INF */

// LAYOUT 5.7 - REGISTRO DE INFORMAÇÕES COMPLEMENTARES PARA O COMPROVANTE DE RENDIMENTO (identificador INF)
// serão apresentados todos os cpf em ordem crescente
// deve haver um registro BPFDEC, BPFPROC e/ou BPFRRA correspondente na declaracao
// deve ocorrer apenas um registro para cada beneficiario
/* campo 1 */$identificador_registro = "INF";
/* campo 2 */$cpf = "";
/* campo 3 */$informacoes = "";
/*
$inf = str_pad($identificador_registro,3," ",STR_PAD_RIGHT).$fim_campo;
$inf .= str_pad($cpf,11,"0",STR_PAD_LEFT).$fim_campo;
$inf .= str_pad($informacoes,200," ",STR_PAD_RIGHT).$fim_campo;
*/
/* /INF */

//echo $inf;
//exit;

// $dirf .= $inf;






/* FIMDIRF */

// LAYOUT 5.8 - REGISTRO IDENTIFICADOR DO TERMINO DA DECLARACAO (identificador FIMDIRF)
// REGISTRO OBRIGATORIO
// DEVE SER O ULTIMO REGISTRO DO ARQUIVO
// OCORRE SOMENTE UMA VEZ NO ARQUIVO
/* campo 1 */$identificador_registro = "FIMDirf";

$fimdirf = str_pad($identificador_registro,7," ",STR_PAD_RIGHT).$fim_campo;

/* /FIMDIRF */

//echo $fimdirf;
//exit;

$dirf .= $fimdirf;










//echo $dirf;
//exit;




// MONTANDO O DIRETORIO PARA SALVAR O ARQUIVO sefip.re DO USUARIO CASO NÃO EXISTA
$nome_pasta = 'DIRF/';

// VERIFICANDO SE A PASTA JÁ EXISTE
if(!is_dir($nome_pasta)){
	mkdir($nome_pasta, 0777, true);
}

$nome_arquivo = 'DIRF_'.date('Y').date('m').date('d').'_'.time().'.txt';

// SALVANDO O ARQUIVO
file_put_contents($nome_pasta . "/" . $nome_arquivo, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',$dirf));

if (file_exists($nome_pasta . "/" . $nome_arquivo)) {
    header('Content-Description: File Transfer');
    header('Content-Type: document/text');
    header('Content-Disposition: attachment; filename='.basename($nome_pasta . "/" . $nome_arquivo));
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($nome_pasta . "/" . $nome_arquivo));
    ob_clean();
    flush();
    readfile($nome_pasta . "/" . $nome_arquivo);
    exit;
}

//$rsArquivos = mysql_query("SELECT id, nome, data_geracao, baixado, processado, inicio_numeracao_rps, fim_numeracao_rps FROM rps WHERE DATE(data_geracao) = '".date('Y-m-d')."'");

//if($linha = mysql_fetch_array($rsArquivos)){
//	mysql_query("UPDATE rps SET
//	nome = '".$nome_arquivo."'
//	, data_geracao = '" . date('Y-m-d H:i:s') . "'
//	, inicio_numeracao_rps = " . ($numero_inicial+1) . "
//	, fim_numeracao_rps = " . ($numero_RPS) . "
//	, ids_relatorio_cobranca = '" . json_encode($ids_relatorios_cobranca) . "'
//	WHERE id = '".$linha['id']."'
//	");
//}else{


//}
//	header('location: rps.php');


	// ######################### FIM DA GERAÇÃO DO RPS ##############################

?>