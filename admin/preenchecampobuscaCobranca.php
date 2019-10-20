<?php include '../conect.php';

		$sql = "
				SELECT 
					DISTINCT
					l.id
					, l.email CAMPO
				FROM 
					login l 
				WHERE 
					l.email like '%" . $_REQUEST['valor'] . "%'
					AND l.id = l.idUsuarioPai
				ORDER BY l.email
				";
        $resultado = mysql_query($sql)
        or die (mysql_error());
        
		if(mysql_num_rows($resultado) > 0){
			while ($linha=mysql_fetch_array($resultado)) { 
				echo "<a class=\"selResultBusca\" iduser=\"" . $linha['id'] . "\">" . (strlen($linha['CAMPO'])>30 ? substr($linha['CAMPO'],0,30) : $linha['CAMPO']) . "</a>";
			}
		}
?>