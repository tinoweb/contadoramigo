<?php session_start(); ?>
 <form method=post>
 <input name=texto type=text>
 <input type=submit>
 </form>
 <?php
 $texto = $_POST["texto"];
 if($texto) {
 $_SESSION["texto"] = $texto ;
 echo "Voc&ecirc; inseriu na sess&atilde;o [ $texto ]";
 echo "Clique no link para abrir outra p&aacute;gina e ler os dados da sess&atilde;o:
 <a href=\"pagina2.php\">Abrir a p&aacute;gina 2</a>";
 }
 ?>

