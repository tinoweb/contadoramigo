<?php
class fluxoCaixa 
{        
	function __construct($id) 
	{
		$this->id = $id;
   	}

    public function listarCategorias($tipo, $ano, $categoriaEmprestimo)
    {
		$this->result = mysql_query(
			"SELECT categoria
			FROM user_" . $this->id . "_livro_caixa
			WHERE (" . $tipo . " > 0) AND (YEAR(data) = " . $ano . ") AND (Categoria <> '" . $categoriaEmprestimo . "')
			GROUP BY categoria"

		)

		or die (mysql_error());	

        return $this->result;
    }

    public function listarValores($tipo, $ano, $categoriaInclude, $categoriaExclude)
    {
		$sqlFilter = "(YEAR(data) = " . $ano . ")";

		/*
		if ($categoriaInclude != "")
		{
			$sqlFilter = $sqlFilter . " AND (Categoria = '" .  $categoriaInclude . "')";			
		}			
		
		if ($categoriaExclude != "")
		{
			$sqlFilter = $sqlFilter . " AND (Categoria <> '" .  $categoriaExclude . "')";			
		}
		*/	

		$sql = "SELECT T2.total, T2.mes
			FROM (
			select 1 as mes UNION ALL select 2 UNION ALL select 3 UNION ALL select 4 UNION ALL select 5   UNION ALL select 6 
			union all select 7 UNION ALL select 8 UNION ALL select 9 UNION ALL select 10 UNION ALL select 11  UNION ALL select 12
			) as T1
			LEFT JOIN (
			SELECT SUM(" . $tipo . ") as total, MONTH(data) AS mes 
			FROM user_" . $this->id . "_livro_caixa 
			WHERE " . $sqlFilter . " 
			GROUP BY MONTH(data)
			) as T2
			ON T1.mes = T2.mes
			UNION ALL
			(
				SELECT SUM(" . $tipo . ") as total, 0 AS MES
				FROM user_" . $this->id . "_livro_caixa
				WHERE " . $sqlFilter . " 
			)";

    	$this->result = mysql_query($sql)or die (mysql_error());

		return $this->result;
    }

    public function totalCategoria($tipo, $ano, $categoria)
    {
    	$result = mysql_query(
			"SELECT SUM(" . $tipo . ") as total 
			FROM user_" . $this->id . "_livro_caixa 
			WHERE (YEAR(data) = " . $ano . ") AND (categoria = '" . $categoria . "')"
		)or die (mysql_error());

		$row = mysql_fetch_array($result);

		return $row["total"];
    }

    function listarAnos()
	{
		$this->result = mysql_query(
			"SELECT YEAR(data) AS 'ano'
			FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa
			GROUP BY YEAR(data)
			ORDER BY YEAR(data)"
		);

		return $this->result;
	}

    public function exibirMes($mes)
	{	
		switch ($mes) 
		{
		    case 1: return "JAN";
		    case 2: return "FEV";
		    case 3: return "MAR";
		    case 4: return "ABR";
		    case 5: return "MAI";
		    case 6: return "JUN";
		    case 7: return "JUL";
		    case 8: return "AGO";
		    case 9: return "SET";
		    case 10: return "OUT";
		    case 11: return "NOV";
		    case 12: return "DEZ";
		    default: return "";
		}
	}

	public function exibirValor($valor)
	{
		if (($valor != "") & ($valor > 0))
		{
			return number_format($valor,2,",",".");
		}
		else
		{
			return "-";
		}
	}
} 
?>