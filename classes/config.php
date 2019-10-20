<?php
/* Classe que gerencia as configurações de preços do site */	
class Config
{

  /* Retorna valor de uma configuração */
  public static function verValor($configuracao, $tipoPlano = 'S', $userDesconto = '0')
  { 
    $sql_config = "SELECT * FROM configuracoes WHERE configuracao = '" . $configuracao . "' AND tipo_plano = '".$tipoPlano."'" ;
    $rsConfig = mysql_fetch_array(mysql_query($sql_config));    

	// Verifica se sera necessario aplica o desconto.  	
	if( $userDesconto == '1' ) {
		return $rsConfig['valor'] - $rsConfig['valor_desconto'];
	} else {
		return $rsConfig['valor'];	
	}   
  }

  /* Altera valor de uma configuração */
  public function alteraValor($configuracao, $valor, $tipoPlano = 'S', $valorDesconto = 0)
  {    
    $sql_update = "UPDATE configuracoes SET valor = " . $valor . ", valor_desconto = " . $valorDesconto . " WHERE configuracao = '" . $configuracao . "' AND tipo_plano = '".$tipoPlano."'";
    mysql_query($sql_update) or die (mysql_error);
  }  

  /* Retorna lista de configurações e valores */
  public function listaValor($tipoPlano = 'S')
  {
    $arrValores = array();
    $sql_valores = "SELECT valor, configuracao, tipo_plano FROM configuracoes WHERE tipo_plano = '".$tipoPlano."' order by valor";
    $result = mysql_query($sql_valores) or die(mysql_error());
    while($lista = mysql_fetch_array($result))
    {
      array_push($arrValores,array(
        'valor'=>$lista['valor'],
		'valor_desconto'=>$lista['valor_desconto'],  
        'plano'=>$lista['configuracao'],
		'tipo_plano'=>$lista['tipo_plano'],
        'plano_nome'=> $this->verPlano($lista['configuracao']),
        'meses'=> $this->verMeses($lista['configuracao'])
        )
      );
    }
    return $arrValores;
  }

  /* Retorna lista de configurações e valores */
  public function listarValores($tipoPlano = 'S')
  {
    $arrValores = array();
    $sql_valores = "SELECT valor, configuracao, tipo_plano, valor_desconto FROM configuracoes WHERE tipo_plano = '".$tipoPlano."' order by valor";
    $result = mysql_query($sql_valores) or die(mysql_error());
    while($lista = mysql_fetch_array($result))
    {
      array_push($arrValores,array(
        'valor'=>$lista['valor'],
		'valor_desconto'=>$lista['valor_desconto'],  
		'tipo_plano'=>$lista['tipo_plano'],        
        'configuracao'=>$lista['configuracao']
        )
      );
    }
    return $arrValores;
  }

  /* Retorna número de meses do plano */
  public function verMeses($plano)
  {
    switch ($plano) 
    {
      case "mensalidade": return 1;
      case "trimestral":  return 3;
      case "semestral":   return 6;
      case "anual":       return 12;
      default:       return 0;
    }
  }

  /* Recupera nome do plano */
  public function verPlano($plano)
  {
    switch ($plano) 
    {
      case "mensalidade": return "Mensal";
      case "trimestral":  return "Trimestral";
      case "semestral":   return "Semestral";
      case "anual":       return "Anual";
      case "bianual":       return "Binual";
      default:       return 0;
    }
  }

  /* Retorna valor da mensalidade */
  public function verMensalidade()
  {
    return $this->verValor("mensalidade");
  }

  /* Exibe texto do radiobutton da página de alteração do plano */
/*function exibirDesconto($plano, $plano_nome, $valor_plano, $valor_mensalidade, $meses)
  {
    $valorDesconto = ($valor_mensalidade - ($valor_plano / $meses));

    if ($valorDesconto <= 0)
    {
      return $plano_nome . ": " . "R$ " . number_format($valor_plano,2,",",".") ;
    } 
    else
    {
      $desconto = $valor_plano  / $valor_mensalidade;

      if ($valor_plano == 588) {
        $aux = " (R$ 49,00 por mês)";
      }
      if ($valor_plano == 336) {
        $aux = " (R$ 56,00 por mês)";
      }


      return $plano_nome . ": " . "R$ " . number_format($valor_plano,2,",",".") . $aux;  
    }
  }*/

  //CALCULA VALOR COM BASE NO NÚMERO DE EMRPESAS
  function calcularValorEmpresas($valor, $empresas)
  {
    if ($empresas > 5) 
    {
      //Se houver mais de 5 empresas cobrar R$ 10,00 a partir da 6a empresa
      return ($valor * 5) + (($empresas - 5) * 10);
    }
    else
    {
      //Multiplicando valor do plano por empresas
      return $valor * $empresas;
    }
  }
  
}