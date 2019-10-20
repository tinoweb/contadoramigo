<?php
include "conect.php";
include "session.php";
?>

      <table border="0">
      <tr>
        <td colspan="2">
            <table border="0" width="659" cellpadding="5">
                <tr>
                    <th width="80">Ação</th>
                    <th width="310">Nome</th>
                    <th width="231">Vínculo</th>
                </tr>	
	<?
	$sql = "SELECT idDependente, nome, vinculo FROM dados_dependentes_funcionario WHERE idFuncionario = " . $_POST['id'];
	$resultado = mysql_query($sql)
	or die (mysql_error());

	$possui_dependentes = false;

	if(mysql_num_rows($resultado) > 0){

		$possui_dependentes = true;
		
		// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
		while($linha=mysql_fetch_array($resultado)){
	
			$id 	= $linha["idDependente"];
			$nome 	= $linha["nome"];
			$vinculo= $linha["vinculo"];
				
	?>
                <tr>
                    <td class="td_calendario" align="center"><a href="#" class="btExcluirDependente" alt="<?=$id?>"><img src="images/del.png" width="24" height="23" border="0" title="Excluir" /></a>
                    <a href="meus_dados_dependentes_funcionario.php?editar=<?=$id?>"><img src="images/edit.png" width="24" height="23" border="0" title="Editar" /></a></td>
                    <td class="td_calendario"><?=$nome?></td>
                    <td class="td_calendario"><?=$vinculo?></td>
                </tr>
	<?	
		}
			
	 } else {?>
                <tr>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                    <td class="td_calendario">&nbsp;</td>
                </tr>

	<? } ?>

                </table>
            </td>
          </tr>
      </table>