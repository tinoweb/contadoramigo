<?php
	require_once 'header.php'; 
	require_once 'index-Controller.php';
?>
<div class="minHeight"> 
    <div class="titulo" style="text-align:left; margin-bottom:0px;">Premium</div>
    	
    
    
	<form method="get" action="index.php" class="">
		<div style="float:left; margin-bottom: 10px;">
			status:	
			<select id="statusCliente" name="status">
				<?php if($status == 'ativo'):?>
					<option value="">Todos</option>
					<option value="ativo" selected>Ativo</option>
					<option value="inativo">Inativo</option>				
				<?php elseif($status == 'inativo'):?>
					<option value="">Todos</option>
					<option value="ativo">Ativo</option>
					<option value="inativo" selected>Inativo</option>
				<?php else:?>
					<option value="">Todos</option>
					<option value="ativo">Ativo</option>
					<option value="inativo">Inativo</option>
				<?php endif;?>
			</select>
			&nbsp;&nbsp; opções:
			<select id="statusOpcoes" name="opcoes">
				<?php if($opcoes == 'funcionario'):?>
					<option value="">Todos</option>
					<option value="funcionario" selected>Funcionario</option>
					<option value="dimob">DIMOB</option>
					<option value="dmed">DMED</option>
				<?php elseif($opcoes == 'dimob'):?>
					<option value="">Todos</option>
					<option value="funcionario">Funcionario</option>
					<option value="dimob" selected>DIMOB</option>
					<option value="dmed">DMED</option>
				<?php elseif($opcoes == 'dmed'):?>
					<option value="">Todos</option>
					<option value="funcionario">Funcionario</option>
					<option value="dimob">DIMOB</option>
					<option value="dmed" selected>DMED</option>
				<?php else:?>
					<option value="">Todos</option>
					<option value="funcionario">Funcionario</option>
					<option value="dimob">DIMOB</option>
					<option value="dmed">DMED</option>
				<?php endif;?>
			</select>
		</div>
		
		<div style="float:right; margin-bottom: 10px;">
		Busca Por:
		<select id="statusFiltro" name="filtro">
			<?php if($filtro == 'id') :?>
				<option value="assinante" >Assinante</option>
				<option value="id" selected>Código</option>
			<?php elseif($filtro == 'assinante') :?>
				<option value="assinante" selected>Assinante</option>
				<option value="id" >Código</option>
			<?php else:?>
				<option value="assinante" >Assinante</option>
				<option value="id" >Código</option>
			<?php endif;?>
		</select>
		<input name="valor" id="txtBusca" style="width:100px; margin: 0 10px" value="<?php echo $valor;?>" type="text">		
		<button value="Pesquisar" type="submit">Pesquisa</button>
		</div>
	</form>
        
    
    <div class="campoClientes"><?php echo $tagTable;?></div>
    <div style="margin: 10px 0;"><?php echo $paginacao; ?></div>
</div>


<?php require_once '../rodape.php'; ?>
