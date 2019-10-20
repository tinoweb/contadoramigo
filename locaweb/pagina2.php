<?php session_start(); ?>
 <?php
 $texto = $_SESSION["texto"];
 if($texto) {
 echo "A sess&atilde;o guardou: [ $texto ]";
 } else { echo "Houve um erro na sess&atilde;o";  }
 ?>

