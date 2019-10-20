<?php 

// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

include '../conect.php';
include '../session.php';
include 'check_login.php';
include '../classes/config.php';
?>

<?
	if ($_GET['acao'] == 'editarPlano') {
		
		//Cria novo objeto de configuração
        $Config = new Config();
		
		$configuracao = $_GET['configuracao'];
		$valor = (isset($_GET['valor']) && !empty($_GET['valor']) ? $_GET['valor'] : 0 );
		$tipoPlano = $_GET['tipoPlano'];
		$valorDesconto = (isset($_GET['valorDesconto']) && !empty($_GET['valorDesconto']) ? $_GET['valorDesconto'] : 0 );
		
		$Config->alteraValor($configuracao, $valor, $tipoPlano, $valorDesconto);
		 
//		$sql="UPDATE configuracoes SET valor = " . $_GET['valor'] . ", valor_desconto = " . $_GET['valorDesconto'] . " WHERE configuracao = '" . $_GET['configuracao'] . "' AND tipo_plano = '".$_GET['tipoPlano']."' ";
//		mysql_query($sql) or die (mysql_error);
		
		header('location:planos_assinatura.php');
	} 
?>

<?php include 'header.php' ?>

<style>
	.btSalvar{
		color: #0c0;
	}
	.btCancelar{
		color: #c00;
	}
</style>

<div class="principal">

	<div class="titulo" style="margin-bottom:10px;">Planos de Assinatura Standard</div>

	<?php
        //Cria novo objeto de configuração
        $Config = new Config();
        
        //Recebe array de valores de assinaturas
        $valores1 = $Config->listarValores('S');
		
        //Recebe array de valores de assinaturas
        $valores2 = $Config->listarValores('P');

    ?>
    
    <table border="0" cellspacing="2" cellpadding="4" style="font-size:12px">
        <tr>
			<td align="center"><b>Assinatura</b></td>
			<td align="center"><b>Valor</b></td>
			<td align="center"><b>Desconto</b></td>
			<td align="center"><b>Ação</b></td>
		</tr>	
    <?php foreach ($valores1 as $item) {?>
        <?$plano_nome = $Config->verPlano($item['configuracao']);?>
        <tr>
            <td><?=$plano_nome?></td>
            <td>
            	R$ <input type="text" id="txt_plano_<?=$item["configuracao"]?>_<?=$item["tipo_plano"]?>" name="txt_plano" value="<?=$item["valor"]?>" class="inteiro" size="4" maxlength="10" />
          	</td>
           	<td>
           		R$ <input type="text" id="desconto_<?=$item["configuracao"]?>_<?=$item["tipo_plano"]?>" name="txt_plano" value="<?=$item['valor_desconto']?>" class="inteiro" size="4" maxlength="10" />
           	</td>
           	<td>
				<a href="#" class="editarPlano" data-configuracao="<?=$item['configuracao']?>" data-tipoPlano="<?=$item['tipo_plano']?>">Editar</a>
       		</td>
        </tr>
    <?php } ?>
    </table>

	</div>    
</div>    

<div class="principal">
	<div class="titulo" style="margin-bottom:10px;">Planos de Assinatura Premium</div>
    
    <table border="0" cellspacing="2" cellpadding="4" style="font-size:12px">
        <tr>
			<td align="center"><b>Assinatura</b></td>
			<td align="center"><b>Valor</b></td>
			<td align="center"><b>Desconto</b></td>
			<td align="center"><b>Ação</b></td>
		</tr>
    <?php foreach ($valores2 as $item) {?>
        <?$plano_nome = $Config->verPlano($item['configuracao']);?>
        <tr>
            <td><?=$plano_nome?></td>
            <td>
            	R$ <input type="text" id="txt_plano_<?=$item["configuracao"]?>_<?=$item["tipo_plano"]?>" name="txt_plano" value="<?=$item["valor"]?>" class="inteiro" size="4" maxlength="10" />
           	</td>
           	<td>
           		R$ <input type="text" id="desconto_<?=$item["configuracao"]?>_<?=$item["tipo_plano"]?>" name="txt_plano" value="<?=$item['valor_desconto']?>" class="inteiro" size="4" maxlength="10" />
           	</td>
           	<td>
				<a href="#" class="editarPlano" data-configuracao="<?=$item['configuracao']?>"  data-tipoPlano="<?=$item['tipo_plano']?>">Editar</a>
       		</td>
        </tr>
    <?php } ?>
    </table>

	</div>    
</div>

<script>
$(document).ready(function(e) {

	$('.editarPlano').click(function(e)
	{
		var configuracao = $(this).attr("data-configuracao");
		var tipoPlano = $(this).attr("data-tipoPlano");
		var valor = $("#txt_plano_" + configuracao+"_"+tipoPlano).val();
		var valorDesconto = $("#desconto_" + configuracao+"_"+tipoPlano).val();
		location.href='planos_assinatura.php?acao=editarPlano&configuracao=' + configuracao + '&valor=' + valor + '&valorDesconto=' + valorDesconto + '&tipoPlano=' + tipoPlano;
	});
});
</script>


<?php include '../rodape.php' ?>
