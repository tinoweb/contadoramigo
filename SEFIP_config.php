<?
	session_start();

	include 'conect.php';
	
	if(isset($_POST['p_vez'])){
		$_SESSION['p_vez'] = $_POST['p_vez'];
	}

	if(isset($_POST['IVeoutros'])){
		$_SESSION['SEFIP_anexoIVeoutros']=$_POST['IVeoutros'];
	}

	if(isset($_POST['empreitada'])){
		$_SESSION['SEFIP_empreitada']=$_POST['empreitada'];
	}

	if(isset($_POST['pgDestino'])){
		$_SESSION['SEFIP_pgdestino']=$_POST['pgDestino'];
	}

	if(isset($_POST['arrAnexos'])){
		$_SESSION['SEFIP_arrAnexos']=$_POST['arrAnexos'];
	}

	if( isset($_POST['compensacao_setar']) ){
		$_SESSION['compensacao'] = $_POST['compensacao_setar'];
		$_SESSION['anexo4'] = true;
	}
	//Marca as opções de recolhimento de CPRB com true ou false e se é empreitada com true ou false, se for empreitada é anexo 4
	if( isset( $_POST['recolhe_cprb'] ) ){
		
		$cnae = $_POST['anexo_s'];

		$_SESSION['recolhe_cprb'] = $_POST['recolhe_cprb'];	
		$_SESSION['e_empreitada'] = $_POST['e_empreitada'];	

		$_SESSION['compensacao'] = $_POST['recolhe_cprb'];
		if( $_SESSION['e_empreitada'] == 'true' )
			$_SESSION['anexo4'] = true;


		$consulta = mysql_query("SELECT * FROM cnae");
		while( $objeto=mysql_fetch_array($consulta) ){

			$cnae_limpo = $objeto['cnae'];
			$cnae_limpo = str_replace('-', '', $cnae_limpo );
			$cnae_limpo = str_replace('/', '', $cnae_limpo);

			if( $cnae_limpo == $cnae ){

				if( $objeto['anexo'] == "IV" )
					$_SESSION['e_anexo_IV'] = 'true';
				else
					$_SESSION['e_anexo_IV'] = 'false';

				if( $objeto['anexo'] == 's' && $_SESSION['e_empreitada'] == 'false' )
					$_SESSION['anexo_s'] = 'true';
				else
					$_SESSION['anexo_s'] = 'false';
			}


		}

	
	}
	//Salva os dados relativos aos tomadores
	if(isset($_POST['salvarDadosSefip'])){
		
		$_SESSION['trabalhadores_sefip'] = "";

		$_SESSION['tomadores_sefip'] = "";

		$_SESSION['SEFIP_retencao'] = true;

		$string = $_POST['string'];

		$string = explode("-", $string);
		
		$id_tomador = $string[0];
		$id_trabalhador = $string[1];
		$compensacao = $string[2];
		$retencao = $string[3];
		$tipo = $string[4];

		$ordem = $_POST['ordem'];

		$id_user = $_SESSION["id_empresaSecao"];

		$hash = $_POST['hash'];

		$consulta = mysql_query("DELETE FROM sefip_tomadores WHERE id_user = '".$id_user."' AND hash != '".$hash."' ");
		
		$consulta = mysql_query("INSERT INTO `sefip_tomadores`(`id`, `id_user`, `id_tomador`,`id_trabalhador`, `retencao`, `tipo`, `compensacao`, `ordem`, `hash`) VALUES ( '','".$id_user."','".$id_tomador."','".$id_trabalhador."','".$retencao."','".$tipo."','".$compensacao."','".$ordem."','".$hash."' )");


	}	
	//Traz as cidades do estado escolhido
	if( isset( $_POST['uf'] ) ):
		
		$consulta = mysql_query("SELECT * FROM estados WHERE sigla = '".$_POST['uf']."' ");
		$objeto=mysql_fetch_array($consulta);

		$consulta = mysql_query("SELECT * FROM cidades WHERE id_uf = '".$objeto['id']."' ");
		while( $objeto=mysql_fetch_array($consulta) ){
			echo '<option value="'.$objeto['cidade'].'">'.$objeto['cidade'].'</option>';
		}

	endif;
	//Salva um novo tomador
	if( isset( $_POST['salvarTomador'] ) ):
		
		$consulta = mysql_query("INSERT INTO `dados_tomadores`(`id_login`, `nome`, `cei`, `endereco`, `bairro`, `cep`, `estado`, `cidade`) VALUES (
			'".$_SESSION["id_empresaSecao"]."',
			'".$_POST['tomador_nome']."',
			'".$_POST['tomador_boleto_cnpj']."',
			'".$_POST['tomador_Endereco']."',
			'".$_POST['tomador_Bairro']."',
			'".$_POST['tomador_CEP']."',
			'".$_POST['tomador_Estado']."',
			'".$_POST['tomador_Cidade']."'
		)");

		$id_user = mysql_insert_id();
		//Exibe o novo omado no codigo
		echo '
				<div class="cada_tomador" status="off">
	            	<small class="erros"></small>
	            	<div class="coluna1">
	            		<input onclick="abrirTrabalhadores(this);" class="tomador" id="tomador'.$id_user.'" id-tomador="'.$id_user.'" nome="'.$_POST['tomador_nome'].'" type="checkbox" name="" value="">&nbsp;&nbsp;<label>'.$_POST['tomador_nome'].'</label>
	            	</div>
	            	<div class="coluna2">
	            		<div>
	            			Informe o valor de INSS retido pelo Tomador: <input class="retencao current" type="text" name="valor" value="" placeholder="">
	            		</div>
	            		<small class="retencao_erros"></small>
	            	</div>
	            	<div class="trabalhadores">
	            		
	            	</div>
	            </div>';

	endif;


?>




