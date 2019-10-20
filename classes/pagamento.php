<?php
/*
Observação F. Dias
Essa classe abstrai algumas rotinas comuns usadas na págian minha_conta_quitar_cartao.php e no cobranca.php
Recomenda-se futuramente abstrair todas as rotinas de cobranças em classes específicas para que a manutenção
do código seja uma tarefa viável.
*/	
class Pagamento
{
	//RETORNA DATA DO ÚLTIMO PAGAMENTO A SER SOMADO
	function retornarUltimoPagamentoASomar($id)
	{
		//LOCALIZA O ÚLTIMO PAGAMENTO VENCIDO OU NÃO PAGO
		$sql_ultimo_pendente = "SELECT data_pagamento FROM historico_cobranca
		WHERE status_pagamento IN ('vencido','não pago') AND id='" . $id . "' ORDER BY data_pagamento DESC LIMIT 0, 1";
		$rs_sql_ultimo_pendente = mysql_query($sql_ultimo_pendente);
		if (mysql_num_rows($rs_sql_ultimo_pendente) > 0)
		{
			$linha_ultimo_pendente = mysql_fetch_array($rs_sql_ultimo_pendente);
			return $linha_ultimo_pendente["data_pagamento"];
		}
		else
		{
			//NÃO FORAM LOCALIZADAS PENDÊNCIAS, LOCALIZANDO PAGAMENTO EM ABERTO
			$sql_ultimo_pendente = "SELECT data_pagamento FROM historico_cobranca
			WHERE status_pagamento IN ('a vencer') AND id='" . $id . "' ORDER BY data_pagamento DESC LIMIT 0, 1";
			$linha_ultimo_pendente = mysql_fetch_array(mysql_query($sql_ultimo_pendente));
			return $linha_ultimo_pendente["data_pagamento"];
		}	
	}
	function calcularMesesSomarBoleto($plano_meses, $total_devendo)
	{
		if ($total_devendo >= $plano_meses)
		{
			//QUANTIDADE É NEVATIVA, SIGNIFICA QUE OS MESES DO PLANO SÃO INFERIORES AO TOTAL DE MESES EM ABERTO
			//NESSE CASO SUBTRAIR 1 DO NÚMERO DE MESES DO PLANO
			if ($plano_meses <= 1)
			{
				return 1;
			}
			else				
			{
				return $plano_meses - 1;	
			}
		}
		else
		{
			if ($total_devendo == 0)
			{
				//NÃO HÁ DÍVIDAS, SOMAR O NÚMERO DE MESES DO PLANO
				return $plano_meses;
			}
			else
			{
				//CALCULA MESES A SOMAR
				return $plano_meses - ($total_devendo);
			}
		}
	}
	//CALCULA NÚMERO DE MESES A SOMAR NO PRÓXIMO PAGAMENTO
	function calcularMesesSomar($plano_meses, $total_devendo)
	{
		if ($total_devendo >= $plano_meses)
		{
			//QUANTIDADE É NEVATIVA, SIGNIFICA QUE OS MESES DO PLANO SÃO INFERIORES AO TOTAL DE MESES EM ABERTO
			//NESSE CASO SUBTRAIR 1 DO NÚMERO DE MESES DO PLANO
			if ($plano_meses <= 1)
			{
				return 1;
			}
			else				
			{
				return $plano_meses - 1;	
			}
		}
		else
		{
			if ($total_devendo == 0)
			{
				//NÃO HÁ DÍVIDAS, SOMAR O NÚMERO DE MESES DO PLANO
				return $plano_meses;
			}
			else
			{
				//CALCULA MESES A SOMAR
				return $plano_meses - ($total_devendo - 1);
			}
		}
	}
	
	//CALCULA DATA DO PRÓXIMO PAGAMENTO
	function calcularProximoPagamento($status, $ultimo_pagamento_pendente, $meses_a_somar)
	{
		if($status == 'cancelado' || $status == 'demoInativo')
		{
			// DEFININDO DATA DO PRÓXIMO VENCIMENTO A PARTIR DA DATA ATUAL
			return date('Y-m-d',(mktime(0,0,0,date('m') + $meses_a_somar,date('d'),date('Y'))));
		}
		else
		{
			if(is_null($ultimo_pagamento_pendente) || ($ultimo_pagamento_pendente  == ""))
			{
				// HOUVE UMA INCONSISTÊNCIA, NÃO HÁ PAGAMENTOS LOCALIZADOS. ASSIM DEFININDO DATA DO PRÓXIMO VENCIMENTO A PARTIR DA DATA ATUAL
				return date('Y-m-d',(mktime(0,0,0,date('m') + $meses_a_somar,date('d'),date('Y'))));
			}
			else
			{
				// PEGANDO O ULTIMO PAGO PARA DETERMINAR A PROXIMA DATA DE VENCIMENTO
				return date('Y-m-d',strtotime($ultimo_pagamento_pendente  . " + " . $meses_a_somar . " month"));
			}
		}
	}
}