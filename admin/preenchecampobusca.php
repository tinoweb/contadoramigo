<?php include '../conect.php';

		$sql = "
				SELECT 
					DISTINCT
					t.id
					, u.assinante CAMPO
				FROM 
					suporte t 
					INNER JOIN login u ON t.id = u.id
				WHERE 
					u.assinante like '%" . $_REQUEST['valor'] . "%'
					AND u.id = u.idUsuarioPai
				ORDER BY u.assinante
				";
        $resultado = mysql_query($sql)
        or die (mysql_error());
        
		if(mysql_num_rows($resultado) > 0){
			while ($linha=mysql_fetch_array($resultado)) { 
				echo "<a class=\"selResultBusca\" iduser=\"" . $linha['id'] . "\">" . (strlen($linha['CAMPO'])>30 ? substr($linha['CAMPO'],0,30) : $linha['CAMPO']) . "</a>";
			}
		}
?>