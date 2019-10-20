<?php 
//Matheus Cruz
//Data: 28/02/2018
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

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
/* campo 6 */$identificador_estrutura ="Q84FV63"; // IDENTIFICADOR de 2013 = "F8UCL6S; identificador de 2014 : M1LB5V2"; identificador de 2016 :L35QJS2";

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

//REGISTRO DO RESPONSAVEL (identificador RESPO)

$sqlRESPO = "SELECT resp.cpf, resp.nome, resp.pref_telefone, resp.telefone, l.email FROM dados_do_responsavel resp INNER JOIN login l ON resp.id = l.id WHERE resp.id='" . $id . "' AND resp.responsavel = 1 LIMIT 0,1";
$rsRESPO = mysql_fetch_array(mysql_query($sqlRESPO));

if(empty(
	$rsRESPO['cpf']) 
   || empty($rsRESPO['nome']) 
   || empty($rsRESPO['pref_telefone']) 
   || empty($rsRESPO['telefone']) 
   || empty($rsRESPO['email'])
  ){
	$msgAlert = "Complete seu cadastro (Meus Dados > Sócio ou Proprietário) com o seguinte campo:\n";	
	
	if(empty($rsRESPO['nome'])){
		$msgAlert .= "- Nome do responsavel\n";
	} if(empty($rsRESPO['pref_telefone'])){
		$msgAlert .= "- Prefixo Telefone do responsavel\n";
	} if(empty($rsRESPO['telefone'])){
		$msgAlert .= "- Telefone do responsavel\n";
	} if(empty($rsRESPO['email'])){
		$msgAlert .= "- Email do responsavel\n";
	} if(empty($rsRESPO['cpf'])){
		$msgAlert .= "- CPF do responsavel\n";
	}
} 


//REGISTRO DO DECLARANTE PESSOA JURIDICA (identificador DECPJ)
	
$sqlDECPJ = "SELECT emp.cnpj, emp.razao_social, resp.cpf FROM dados_da_empresa emp INNER JOIN dados_do_responsavel resp ON emp.id = resp.id WHERE emp.id='" . $id . "' AND resp.responsavel = 1 LIMIT 0,1";
$rsDECPJ = mysql_fetch_array(mysql_query($sqlDECPJ));

if(
	empty($rsDECPJ['cnpj']) 
	|| empty($rsDECPJ['razao_social'])
){
	$msgAlert .= "Complete seu cadastro (Meus Dados > minhas empresas) com o seguinte campo:\n";
	if(
		empty($rsDECPJ['cnpj']) 
	    || empty($rsDECPJ['razao_social'])
	){
		if(empty($rsDECPJ['cnpj'])){
			$msgAlert .= "- CNPJ\n";
		} if(empty($rsDECPJ['razao_social'])){
			$msgAlert .= "- Razão Social\n";
		}
	}
}

// PEGANDO OS PAGAMENTOS PARA OS PRÓXIMOS REGISTROS SÓCIOS

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
	AND (pgto.id_autonomo > 0 OR pgto.id_socio > 0 OR (pgto.id_pj > 0 AND pgto.IR > 0 AND  pgto.descricao_servico <> 'Plano ou Seguro Saúde Privado') OR pgto.id_lucro > 0)
	AND YEAR(pgto.data_pagto) = '" . $ano_calendario . "'
GROUP BY 1,2,3,4,5,6
HAVING 1=1 
	AND tipo IN ('autonomo','prolabore','pj','lucros')
ORDER BY 
	codigo_receita
	, documento
	, ordem
";

$PAGTOS = mysql_query($sqlPAGTOS);
while($rsPAGTOS = mysql_fetch_array($PAGTOS)){	
		
	if(
		empty($rsPAGTOS["documento"]) 
		|| empty($rsPAGTOS['nome'])
	){
		$msgAlert .= "Complete seu cadastro (Meus Dados) com o seguinte campo:\n";
		
		if(empty($rsPAGTOS['documento']) && $rsPAGTOS['tipo'] != 'pj'){
			$msgAlert .= "- CPF\n";
		} elseif(empty($rsPAGTOS['documento'])){
			$msgAlert .= "- CNPJ\n";
		} if(empty($rsPAGTOS['nome'])){
			$msgAlert .= "- Nome\n";
		}
	}	
}


/**** Inclui o Pagamento do funcionario ***********************************************************/

$queryPagtoFuncionario = "SELECT pf.funcionarioId,
			f.nome,
			f.cpf as documento, SUM(case when MONTH(data_pagto) = '1' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_JANEIRO
			, SUM(case when MONTH(data_pagto) = '2' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_FEVEREIRO
			, SUM(case when MONTH(data_pagto) = '3' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_MARCO
			, SUM(case when MONTH(data_pagto) = '4' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_ABRIL
			, SUM(case when MONTH(data_pagto) = '5' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_MAIO
			, SUM(case when MONTH(data_pagto) = '6' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_JUNHO
			, SUM(case when MONTH(data_pagto) = '7' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_JULHO
			, SUM(case when MONTH(data_pagto) = '8' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_AGOSTO
			, SUM(case when MONTH(data_pagto) = '9' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_SETEMBRO
			, SUM(case when MONTH(data_pagto) = '10' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_OUTUBRO
			, SUM(case when MONTH(data_pagto) = '11' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_NOVEMBRO
			, SUM(case when MONTH(data_pagto) = '12' AND tipoPagamento = 'salario' then pf.valor_bruto else 0 end) VALOR_BRUTO_DEZEMBRO
			, SUM(case when tipoPagamento = 'decimoTerceiro' AND parcelaDecimo != 'primeira' then pf.valor_bruto else 0 end) VALOR_BRUTO_DECIMO_TERCEIRO

			, SUM(case when MONTH(data_pagto) = '1' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_JANEIRO
			, SUM(case when MONTH(data_pagto) = '2' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_FEVEREIRO
			, SUM(case when MONTH(data_pagto) = '3' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_MARCO
			, SUM(case when MONTH(data_pagto) = '4' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_ABRIL
			, SUM(case when MONTH(data_pagto) = '5' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_MAIO
			, SUM(case when MONTH(data_pagto) = '6' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_JUNHO
			, SUM(case when MONTH(data_pagto) = '7' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_JULHO
			, SUM(case when MONTH(data_pagto) = '8' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_AGOSTO
			, SUM(case when MONTH(data_pagto) = '9' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_SETEMBRO
			, SUM(case when MONTH(data_pagto) = '10' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_OUTUBRO
			, SUM(case when MONTH(data_pagto) = '11' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_NOVEMBRO
			, SUM(case when MONTH(data_pagto) = '12' AND tipoPagamento = 'salario' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_DEZEMBRO
			, SUM(case when tipoPagamento = 'decimoTerceiro' AND parcelaDecimo != 'primeira' then pf.valor_liquido else 0 end) VALOR_LIQUIDO_DECIMO_TERCEIRO

			, SUM(case when MONTH(data_pagto) = '1' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_JANEIRO
			, SUM(case when MONTH(data_pagto) = '2' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_FEVEREIRO
			, SUM(case when MONTH(data_pagto) = '3' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_MARCO
			, SUM(case when MONTH(data_pagto) = '4' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_ABRIL
			, SUM(case when MONTH(data_pagto) = '5' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_MAIO
			, SUM(case when MONTH(data_pagto) = '6' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_JUNHO
			, SUM(case when MONTH(data_pagto) = '7' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_JULHO
			, SUM(case when MONTH(data_pagto) = '8' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_AGOSTO
			, SUM(case when MONTH(data_pagto) = '9' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_SETEMBRO
			, SUM(case when MONTH(data_pagto) = '10' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_OUTUBRO
			, SUM(case when MONTH(data_pagto) = '11' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_NOVEMBRO
			, SUM(case when MONTH(data_pagto) = '12' AND tipoPagamento = 'salario' then pf.valor_INSS else 0 end) INSS_DEZEMBRO
			, SUM(case when tipoPagamento = 'decimoTerceiro' AND parcelaDecimo != 'primeira' then pf.valor_INSS else 0 end) INSS_DECIMO_TERCEIRO

			, SUM(case when MONTH(data_pagto) = '1' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_JANEIRO
			, SUM(case when MONTH(data_pagto) = '2' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_FEVEREIRO
			, SUM(case when MONTH(data_pagto) = '3' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_MARCO
			, SUM(case when MONTH(data_pagto) = '4' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_ABRIL
			, SUM(case when MONTH(data_pagto) = '5' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_MAIO
			, SUM(case when MONTH(data_pagto) = '6' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_JUNHO
			, SUM(case when MONTH(data_pagto) = '7' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_JULHO
			, SUM(case when MONTH(data_pagto) = '8' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_AGOSTO
			, SUM(case when MONTH(data_pagto) = '9' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_SETEMBRO
			, SUM(case when MONTH(data_pagto) = '10' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_OUTUBRO
			, SUM(case when MONTH(data_pagto) = '11' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_NOVEMBRO
			, SUM(case when MONTH(data_pagto) = '12' AND tipoPagamento = 'salario' then pf.valor_IR else 0 end) IR_DEZEMBRO
			, SUM(case when tipoPagamento = 'decimoTerceiro' AND parcelaDecimo != 'primeira' then pf.valor_IR else 0 end) IR_DECIMO_TERCEIRO

			, SUM(case when MONTH(data_pagto) = '1' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_JANEIRO
			, SUM(case when MONTH(data_pagto) = '2' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_FEVEREIRO
			, SUM(case when MONTH(data_pagto) = '3' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_MARCO
			, SUM(case when MONTH(data_pagto) = '4' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_ABRIL
			, SUM(case when MONTH(data_pagto) = '5' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_MAIO
			, SUM(case when MONTH(data_pagto) = '6' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_JUNHO
			, SUM(case when MONTH(data_pagto) = '7' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_JULHO
			, SUM(case when MONTH(data_pagto) = '8' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_AGOSTO
			, SUM(case when MONTH(data_pagto) = '9' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_SETEMBRO
			, SUM(case when MONTH(data_pagto) = '10' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_OUTUBRO
			, SUM(case when MONTH(data_pagto) = '11' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_NOVEMBRO
			, SUM(case when MONTH(data_pagto) = '12' AND tipoPagamento = 'salario' then pf.valorDescontoDependente else 0 end) desconto_dependentes_DEZEMBRO
			, SUM(case when tipoPagamento = 'decimoTerceiro' AND parcelaDecimo != 'primeira' then pf.valorDescontoDependente else 0 end) desconto_dependentes_DECIMO_TERCEIRO

			, SUM(pf.valor_liquido) valor_liquido_anual

		FROM dados_pagamentos_funcionario pf
		JOIN dados_do_funcionario f ON f.idFuncionario = pf.funcionarioId
		WHERE 
			pf.empresaId='" . $id . "'
			AND YEAR(pf.data_pagto) = '" . $ano_calendario . "'
		GROUP BY 1
		ORDER BY f.nome;
";

$pagtoFuncionario = mysql_query($queryPagtoFuncionario);

while($rsPAGTOS = mysql_fetch_array($pagtoFuncionario)){
	if(
		empty($rsPAGTOS["documento"]) 
		|| empty($rsPAGTOS['nome'])
	){
		$msgAlert .= "Complete seu cadastro (Meus Dados > funcionario) com o seguinte campo:\n";
		if(empty($rsPAGTOS['documento']) && !empty($rsPAGTOS['nome'])){
			$msgAlert .= "- ".$rsPAGTOS['nome']."\n";
			$msgAlert .= "- CPF\n";
		} elseif(empty($rsPAGTOS['documento'])){
			$msgAlert .= "- CPF\n";
		} if(empty($rsPAGTOS['nome']) && !empty($rsPAGTOS['documento'])){
			$msgAlert .= "- Nome\n";
			$msgAlert .= "- ".$rsPAGTOS['documento']."\n";
		} elseif(empty($rsPAGTOS['nome'])){
			$msgAlert .= "- Nome\n";
		} 
	}
}

echo $msgAlert;





?>