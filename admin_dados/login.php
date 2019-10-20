<?php
    require 'editar/configuracoes.inc.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Painel de Gerenciamento de Conteúdo</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/main.css">
</head>
<body class="login">
	<form method="post" action="valida.php">
		<a href="<?php echo $url_painel; ?>">
			<img src="<?php echo $logotipo; ?>" alt="" style="max-width:160px; max-height:40px; margin:10px auto; display:block;" />
		</a>
		<label>Usuário</label>
		<input type="text" name="usuario" maxlength="50" />
		<label>Senha</label>
		<input type="password" name="senha" maxlength="50" />
		<input type="submit" value="Entrar" />
	</form>
</body>
</html>