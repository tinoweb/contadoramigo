<?
include '../conect.php';

$sql = "
SELECT 
h.id
, MAX(h.data_pagamento) dtPagto
, SUM(case when status_pagamento = 'a vencer' then 1 else 0 end) aVencer
, SUM(case when status_pagamento = 'pago' then 1 else 0 end) pago
, SUM(case when status_pagamento = 'não pago' then 1 else 0 end) naoPago
, SUM(case when status_pagamento = 'vencido' then 1 else 0 end) vencido
FROM `historico_cobranca` h INNER JOIN login l ON h.id = l.id 
WHERE l.status NOT IN ('cancelado','demoInativo')
group by h.id
having aVencer = 0 AND vencido = 0
order by h.id
LIMIT 0, 1000
";

$atualizar = mysql_query($sql);

while($linha = mysql_fetch_array($atualizar)){
	
	$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linha["dtPagto"]))+1,date('d',strtotime($linha["dtPagto"])),date('Y',strtotime($linha["dtPagto"])))));

/*
INSERT INTO historico_cobranca SET id = '1161', data_pagamento = '2015-11-23', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '1577', data_pagamento = '2015-12-04', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '1630', data_pagamento = '2015-11-15', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '1916', data_pagamento = '2015-11-29', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '1945', data_pagamento = '2016-01-03', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '1981', data_pagamento = '2015-12-04', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '2260', data_pagamento = '2015-12-14', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '2507', data_pagamento = '2015-12-20', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '2612', data_pagamento = '2015-11-18', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '2668', data_pagamento = '2016-01-01', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '2806', data_pagamento = '2015-12-05', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '3157', data_pagamento = '2016-01-04', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '3188', data_pagamento = '2016-01-04', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '3219', data_pagamento = '2015-12-13', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '3318', data_pagamento = '2016-01-02', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '3755', data_pagamento = '2015-11-24', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '4380', data_pagamento = '2015-12-18', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '4532', data_pagamento = '2016-01-06', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '4833', data_pagamento = '2015-12-04', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '4975', data_pagamento = '2015-12-12', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '4980', data_pagamento = '2015-11-06', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5020', data_pagamento = '2016-01-02', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5025', data_pagamento = '2015-12-26', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5036', data_pagamento = '2015-11-05', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5206', data_pagamento = '2015-11-15', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5240', data_pagamento = '2016-01-01', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5250', data_pagamento = '2015-12-19', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5299', data_pagamento = '2016-01-05', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5455', data_pagamento = '2016-01-06', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5465', data_pagamento = '2015-12-10', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5514', data_pagamento = '2015-11-13', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5581', data_pagamento = '2015-12-07', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5683', data_pagamento = '2016-01-05', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5702', data_pagamento = '2015-11-25', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5863', data_pagamento = '2015-12-27', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5879', data_pagamento = '2015-12-26', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5887', data_pagamento = '2015-12-20', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '5952', data_pagamento = '2016-01-06', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6028', data_pagamento = '2015-11-30', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6063', data_pagamento = '2015-11-14', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6087', data_pagamento = '2015-12-12', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6102', data_pagamento = '2015-12-11', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6189', data_pagamento = '2015-12-26', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6199', data_pagamento = '2015-12-05', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6260', data_pagamento = '2016-01-01', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6298', data_pagamento = '2015-12-16', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6301', data_pagamento = '2016-01-02', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6385', data_pagamento = '2016-01-04', status_pagamento = 'a vencer';
INSERT INTO historico_cobranca SET id = '6529', data_pagamento = '2016-01-04', status_pagamento = 'a vencer';
*/
	mysql_query("INSERT INTO historico_cobranca SET id = '" . $linha["id"] . "', data_pagamento = '" . $dataPagamento . "', status_pagamento = 'a vencer'");
		
}

?>