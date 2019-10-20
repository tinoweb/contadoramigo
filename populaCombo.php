<?
session_start();

//echo $_GET['acao'];
//exit;

require_once 'conect.php' ;


if($_POST){

	switch($_POST['area']){

		case 'folha_pagto':
				
			switch(strtolower($_POST['tipo'])){
				case 'pró-labore':
				case 'distr. de lucros':
				case 'sócios':
					$sql = "
							select 
								idSocio as id
								, nome 
								, '" .$_POST['tipo']. "' as categoria
							from 
								dados_do_responsavel 
							WHERE 
								id='" . $_SESSION["id_empresaSecao"] . "'
					";
				break;
				case 'estagiário':
				case 'estagiários':
				case 'bolsa auxílio':
					$sql = "
								select 
									id
									, nome 
									, 'estagiários' as categoria
								from 
									estagiarios 
								WHERE 
									id_login='" . $_SESSION["id_empresaSecao"] . "'
					";
				break;
				case 'autônomo':
				case 'autônomos':
				case 'rpa autônomo':
					$sql = "
								select 
									id
									, nome 
									, 'autônomos' as categoria
								from 
									dados_autonomos 
								WHERE 
									id_login='" . $_SESSION["id_empresaSecao"] . "'
					";
				break;
				case 'pessoa jurídica':
					$sql = "
								select 
									id
									, nome 
									, 'pessoa jurídica' as categoria
								from 
									dados_pj 
								WHERE 
									id_login='" . $_SESSION["id_empresaSecao"] . "'
					";
				break;
				default:
					$sql = "
								select * from (
									select 
										idSocio as id
										, nome 
										, 'pró-labore' as categoria
									from 
										dados_do_responsavel 
									WHERE 
										id='" . $_SESSION["id_empresaSecao"] . "'
										
									UNION
									
									select 
										id
										, nome 
										, 'bolsa auxílio' as categoria
									from 
										estagiarios 
									WHERE 
										id_login='" . $_SESSION["id_empresaSecao"] . "'
									
									UNION
									
									select 
										id
										, nome 
										, 'RPA autônomo' as categoria
									from 
										dados_autonomos 
									WHERE 
										id_login='" . $_SESSION["id_empresaSecao"] . "'
										
									UNION
									
									select 
										id
										, nome 
										, 'pessoa jurídica' as categoria
									from 
										dados_pj 
									WHERE 
										id_login='" . $_SESSION["id_empresaSecao"] . "'
										
								) sel ORDER BY nome
					";
				break;
			}

			$query = mysql_query($sql);
				if(mysql_num_rows($query) > 0){
					$arrDados = explode("|",$_POST['id']);
					$idNome = $arrDados[0];
					$cat = $arrDados[1];
					echo "<OPTION value=\"\">Todos</OPTION>";
					while($dados = mysql_fetch_array($query)){
						echo "<OPTION value=\"".$dados['id']."|".$dados['categoria']."\"";
						if(($dados['id'] == $idNome)){// && ($dados['categoria'] == $cat)){
							echo " selected";
						}
						echo ">".$dados['nome']."</OPTION>";
					}
				}else{
					echo "<OPTION value=\"\">nenhum dado localizado</OPTION>";
				}
		break;
	}
}

?>