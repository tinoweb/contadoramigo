<?php include '../conect.php';
include '../session.php';


//ATUALIZA VALORES DA TABELA DE IR

if ($_POST) { 

	switch($_POST['acao']){
		case 'IR':
			$ValorBruto1 = str_replace(",",".",str_replace(".","",$_POST['ValorBruto1']));
			$ValorBruto2 = str_replace(",",".",str_replace(".","",$_POST['ValorBruto2']));
			$ValorBruto3 = str_replace(",",".",str_replace(".","",$_POST['ValorBruto3']));
			$ValorBruto4 = str_replace(",",".",str_replace(".","",$_POST['ValorBruto4']));
			$Aliquota1 = str_replace(",",".",str_replace(".","",$_POST['Aliquota1']));
			$Aliquota2 = str_replace(",",".",str_replace(".","",$_POST['Aliquota2']));
			$Aliquota3 = str_replace(",",".",str_replace(".","",$_POST['Aliquota3']));
			$Aliquota4 = str_replace(",",".",str_replace(".","",$_POST['Aliquota4']));
			$Aliquota5 = str_replace(",",".",str_replace(".","",$_POST['Aliquota5']));
			$Desconto1 = str_replace(",",".",str_replace(".","",$_POST['Desconto1']));
			$Desconto2 = str_replace(",",".",str_replace(".","",$_POST['Desconto2']));
			$Desconto3 = str_replace(",",".",str_replace(".","",$_POST['Desconto3']));
			$Desconto4 = str_replace(",",".",str_replace(".","",$_POST['Desconto4']));
			$Desconto5 = str_replace(",",".",str_replace(".","",$_POST['Desconto5']));
			$Deducao = str_replace(",",".",str_replace(".","",$_POST['Deducao']));
			
			$checa_ano = mysql_query('SELECT * FROM tabelas WHERE ano_calendario = ' . $_REQUEST['ano_calendario']);
			
			if(mysql_num_rows($checa_ano) > 0){
				$sql2="UPDATE tabelas SET";
				$clausula_where = " WHERE ano_calendario = '" . $_REQUEST['ano_calendario'] . "'";
			}else{
				$sql2="INSERT INTO tabelas SET ano_calendario = '" . $_REQUEST['ano_calendario'] . "',";
				$clausula_where = "";
			}
			
			
			$sql2 .= " ValorBruto1='$ValorBruto1'
			, ValorBruto2='$ValorBruto2'
			, ValorBruto3='$ValorBruto3'
			, ValorBruto4='$ValorBruto4'
			, Aliquota1='$Aliquota1'
			, Aliquota2='$Aliquota2'
			, Aliquota3='$Aliquota3'
			, Aliquota4='$Aliquota4'
			, Aliquota5='$Aliquota5'
			, Desconto1='$Desconto1'
			, Desconto2='$Desconto2'
			, Desconto3='$Desconto3'
			, Desconto4='$Desconto4'
			, Desconto5='$Desconto5'
			, Desconto_Ir_Dependentes='$Deducao'" . $clausula_where;
			
			mysql_query($sql2)
			or die (mysql_error());

			echo "
			<script>
				alert('Tabela de retenção de IR atualizada com sucesso!');
				location.href='retencao_ir.php?ano_calendario=" . $_REQUEST['ano_calendario'] . "';
			</script>";
		
		break;

		
		case 'anexo1':
		
			$fat_min1 = ($_POST['faturamento_min1']);
			$fat_max1 = ($_POST['faturamento_max1']);
			$ir1 = ($_POST['ir1']);
		
			$updates_erro = 0;
			
			for($i=0; $i < count($fat_min1); $i++) {
				$sql = "
					UPDATE anexoI SET 
				 		faturamento_min = '" . str_replace(",",".",str_replace(".","",$fat_min1[$i])) . "',
				 		faturamento_max = '" . str_replace(",",".",str_replace(".","",$fat_max1[$i])) . "',
				 		ir = '" . str_replace(",",".",str_replace(".","",$ir1[$i])) . "'
				 	WHERE 
						id = " . ($i+1) . "
				";
			
				//echo $sql . "<BR>";
					
				if(!mysql_query($sql)){
					$updates_erro++;
				}
			}
			
			//exit;
			
			if($updates_erro > 0){
				echo "
				<script>
					alert('Ocorreu um erro na atualização dos dados da tabela anexoI!');
					location.href='tabelas.php';
				</script>";
				
			}else{
				
				echo "
				<script>
					alert('Tabela do anexo I atualizada com sucesso!');
					location.href='tabelas.php';
				</script>";
			}
		break;
		
		
		case 'anexo2':
		
			$fat_min2 = ($_POST['faturamento_min2']);
			$fat_max2 = ($_POST['faturamento_max2']);
			$ir2 = ($_POST['ir2']);
		
			$updates_erro = 0;
			
			for($i=0; $i < count($fat_min2); $i++) {
				$sql = "
					UPDATE anexoII SET 
				 		faturamento_min = '" . str_replace(",",".",str_replace(".","",$fat_min2[$i])) . "',
				 		faturamento_max = '" . str_replace(",",".",str_replace(".","",$fat_max2[$i])) . "',
				 		ir = '" . str_replace(",",".",str_replace(".","",$ir2[$i])) . "'
				 	WHERE 
						id = " . ($i+1) . "
				";
			
				//echo $sql . "<BR>";
					
				if(!mysql_query($sql)){
					$updates_erro++;
				}
			}
			
			//exit;
			
			if($updates_erro > 0){
				echo "
				<script>
					alert('Ocorreu um erro na atualização dos dados da tabela anexoII!');
					location.href='tabelas.php';
				</script>";
				
			}else{
			
				echo "
				<script>
					alert('Tabela do anexo II atualizada com sucesso!');
					location.href='tabelas.php';
				</script>";
			}
		break;
		
				
		case 'anexo3':
		
			$fat_min3 = ($_POST['faturamento_min3']);
			$fat_max3 = ($_POST['faturamento_max3']);
			$ir3 = ($_POST['ir3']);
		
			$updates_erro = 0;
			
			for($i=0; $i < count($fat_min3); $i++) {
				$sql = "
					UPDATE anexoIII SET 
				 		faturamento_min = '" . str_replace(",",".",str_replace(".","",$fat_min3[$i])) . "',
				 		faturamento_max = '" . str_replace(",",".",str_replace(".","",$fat_max3[$i])) . "',
				 		ir = '" . str_replace(",",".",str_replace(".","",$ir3[$i])) . "'
				 	WHERE 
						id = " . ($i+1) . "
				";
			
				//echo $sql . "<BR>";
					
				if(!mysql_query($sql)){
					$updates_erro++;
				}
			}
			
			//exit;
			
			if($updates_erro > 0){
				echo "
				<script>
					alert('Ocorreu um erro na atualização dos dados da tabela anexoIII!');
					location.href='tabelas.php';
				</script>";
				
			}else{
			
				echo "
				<script>
					alert('Tabela do anexo III atualizada com sucesso!');
					location.href='tabelas.php';
				</script>";
			}
		break;
		
		case 'anexo4':
		
			$fat_min4 = ($_POST['faturamento_min4']);
			$fat_max4 = ($_POST['faturamento_max4']);
			$ir4 = ($_POST['ir4']);
		
			$updates_erro = 0;
			
			for($i=0; $i < count($fat_min4); $i++) {
				$sql = "
					UPDATE anexoIV SET 
				 		faturamento_min = '" . str_replace(",",".",str_replace(".","",$fat_min4[$i])) . "',
				 		faturamento_max = '" . str_replace(",",".",str_replace(".","",$fat_max4[$i])) . "',
				 		ir = '" . str_replace(",",".",str_replace(".","",$ir4[$i])) . "'
				 	WHERE 
						id = " . ($i+1) . "
				";
				
				//echo $sql . "<BR>";
				
				if(!mysql_query($sql)){
					$updates_erro++;
				}
			}
			
			//exit;
			
			if($updates_erro > 0){
				echo "
				<script>
					alert('Ocorreu um erro na atualização dos dados da tabela anexoIV!');
					location.href='tabelas.php';
				</script>";
				
			}else{
			
				echo "
				<script>
					alert('Tabela do anexo IV atualizada com sucesso!');
					location.href='tabelas.php';
				</script>";
			}
			
		break;
		
		case 'anexo5':
		
			$fat_min5 = ($_POST['faturamento_min5']);
			$fat_max5 = ($_POST['faturamento_max5']);
			$cpp5 = ($_POST['cpp']);
		
			$updates_erro = 0;
			
			for($i=0; $i < count($fat_min5); $i++) {
				$sql = "
					UPDATE anexoV SET 
				 		faturamento_min = '" . str_replace(",",".",str_replace(".","",$fat_min5[$i])) . "',
				 		faturamento_max = '" . str_replace(",",".",str_replace(".","",$fat_max5[$i])) . "',
				 		cpp = '" . str_replace(",",".",str_replace(".","",$cpp5[$i])) . "'
				 	WHERE 
						id = " . ($i+1) . "
				";
				
				//echo $sql . "<BR>";
				
				if(!mysql_query($sql)){
					$updates_erro++;
				}
			}
			
			//exit;
			
			if($updates_erro > 0){
				echo "
				<script>
					alert('Ocorreu um erro na atualização dos dados da tabela anexoV!');
					location.href='tabelas.php';
				</script>";
				
			}else{
				
				echo "
				<script>
					alert('Tabela do anexo V atualizada com sucesso!');
					location.href='tabelas.php';
				</script>";
			}
		
		break;
		
		
		case 'anexo6':
		
			$fat_min6 = ($_POST['faturamento_min6']);
			$fat_max6 = ($_POST['faturamento_max6']);
			$cpp6 = ($_POST['cpp6']);
		
			$updates_erro = 0;
			
			for($i=0; $i < count($fat_min6); $i++) {
				$sql = "
					UPDATE anexoVI SET 
				 		faturamento_min = '" . str_replace(",",".",str_replace(".","",$fat_min6[$i])) . "',
				 		faturamento_max = '" . str_replace(",",".",str_replace(".","",$fat_max6[$i])) . "',
				 		cpp = '" . str_replace(",",".",str_replace(".","",$cpp6[$i])) . "'
				 	WHERE 
						id = " . ($i+1) . "
				";
				
				//echo $sql . "<BR>";
				
				if(!mysql_query($sql)){
					$updates_erro++;
				}
			}
			
			//exit;
			
			if($updates_erro > 0){
				echo "
				<script>
					alert('Ocorreu um erro na atualização dos dados da tabela anexoVI!');
					location.href='tabelas.php';
				</script>";
				
			}else{
			
				echo "
				<script>
					alert('Tabela do anexo VI atualizada com sucesso!');
					location.href='tabelas.php';
				</script>";
			}
		
		break;
	}

}

?>