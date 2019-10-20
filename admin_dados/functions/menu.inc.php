<div class="sidebar-menu">
	<header class="logo-env">
		<div class="logo">
			<a href="<?php echo $url_painel; ?>">
				<img src="<?php echo $logotipo; ?>" alt="" style="max-width:160px; max-height:40px;" />
			</a>
		</div>
		<div class="sidebar-collapse">
			<a href="#" class="sidebar-collapse-icon with-animation">
			<i class="entypo-menu"></i>
			</a>
		</div>
		<div class="sidebar-mobile-menu visible-xs">
			<a href="#" class="with-animation">
			<i class="entypo-menu"></i>
			</a>
		</div>
	</header>
	<?php 
		$paginas = new Paginas('');
	    echo $functions->gerarMenu($paginas,$url_painel);
	?>
</div>