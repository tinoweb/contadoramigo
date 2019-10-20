<?php
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	if(isset($_GET['contadorId'])&&!empty($_GET['contadorId'])) { 
		$contadorId = $_GET['contadorId'];
	}

	require_once ('header.php'); 
	require_once ('clientes_premium_contador-Controller.php');
?>

<style>
	a.linkMenu {display: inline-block; margin-left: 5px; margin-right: 5px;}
	a.linkMenuinicio {display: inline-block; margin-left: 25px; margin-right: 5px; text-decoration: none; color: #024a68;}
</style>

<div class="minHeight"> 
   
    <div style="text-align:left; margin-bottom:0px; margin-top: -15px;">
   		<span class="titulo" >Premium - <?php echo pegaNomeContador();?></span>
		<a class="linkMenuinicio" href="clientes_premium_contador.php?contadorId=<?php echo $contadorId;?>">Premium</a>
		<a class="linkMenu" href="clientes_avulso_contador.php?contadorId=<?php echo $contadorId;?>">Avulsos</a>
<!--		<a class="linkMenu" href="pagamentocontador.php?contadorId=<?php echo $contadorId;?>">Pagamentos</a>-->
		<a class="linkMenu" href="listapagamentocontador.php?contadorId=<?php echo $contadorId;?>">Pagamentos</a>
		<a class="linkMenu" href="listapagamentocomissao.php?contadorId=<?php echo $contadorId;?>">Comissão</a>
	</div>   
    <div style="float:right; margin-bottom: 10px;">
         <form method="get" action="clientes_premium_contador.php" class="">
            <select name="filtro">
				<?php if($filtro == 'id') :?>
                    <option value="">Todos</option>
                    <option value="assinante" >Assinante</option>
                    <option value="id" selected>Código</option>
                <?php elseif($filtro == 'assinante') :?>
                    <option value="">Todos</option>
                    <option value="assinante" selected>Assinante</option>
                    <option value="id" >Código</option>
                <?php else:?>
                    <option value="">Todos</option>
                    <option value="assinante" >Assinante</option>
                    <option value="id" >Código</option>
                <?php endif;?>
            </select>
            <input name="valor" id="txtBusca" style="width:100px; margin: 0 10px" value="<?php echo $valor;?>" type="text">
            <input name="contadorId" type="hidden" value="<?php echo $contadorId;?>">
            <button value="Pesquisar" type="submit">Pesquisa</button>
        </form>
    </div>    
            
    <div class="campoClientes"><?php echo $tagTable;?></div>
    <div style="margin: 10px 0;"><?php echo $paginacao; ?></div>
</div>
<?php require_once '../rodape.php'; ?>
