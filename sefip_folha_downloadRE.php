<?php
session_start();

	// Realiza a inclusão do arquivo de conexão com o banco.
    require_once 'conect.php';

    $idSocio = $_SESSION["id_empresaSecao"];

    $sql = "SELECT * FROM dados_do_responsavel WHERE id='". $idSocio."'";
    $consulta = mysql_query($sql);

    $quantidade = mysql_num_rows($consulta);

    if ($quantidade == 0) {
        echo '<script>alert("É necessário cadastrar pelo menos um sócio para gerar a Folha de Pagamento.");window.location="/sefip_folha.php";</script>';
        return;
    }

    $erro = false;

    while($result = mysql_fetch_array($consulta)){

        $data_admissao = $result['data_admissao'];
        if($data_admissao == ''){
            $erro = true;
        }
        $nome = $result['nome'];
        if($nome == ''){
            $erro = true;
        }
        $nacionalidade = $result['nacionalidade'];
        if($nacionalidade == ''){
            $erro = true;
        }
        $naturalidade = $result['naturalidade'];
        if($naturalidade == ''){
            $erro = true;
        }
        $estado_civil = $result['estado_civil'];
        if($estado_civil == ''){
            $erro = true;
        }
        $dependentes = $result['dependentes'];
        if($dependentes == ''){
            $erro = true;
        }
        $codigo_cbo = $result['codigo_cbo'];
        if($codigo_cbo == ''){
            $erro = true;
        }
        $cpf = $result['cpf'];
        if($cpf == ''){
            $erro = true;
        }
        $endereco = $result['endereco'];
        if($endereco == ''){
            $erro = true;
        }
        $bairro = $result['bairro'];
        if($bairro == ''){
            $erro = true;
        }
        $cep = $result['cep'];
        if($cep == ''){
            $erro = true;
        }
        $cidade = $result['cidade'];
        if($cidade == ''){
            $erro = true;
        }
        $nit = $result['nit'];
        if($nit == ''){
            $erro = true;
        }
        if($erro == true){
            
            $consulta_pagamento_socio = mysql_query("SELECT * FROM dados_pagamentos WHERE id_socio = '".$result['idSocio']."' AND YEAR(data_pagto) = '".$_REQUEST['ano']."' AND MONTH(data_pagto) = '".$_REQUEST['mes']."' ");
            $objeto=mysql_fetch_array($consulta_pagamento_socio);
            if( isset( $objeto['id_pagto'] ) ){
                echo '<script>window.location="/sefip_folha.php?socio='.$result['idSocio'].'";</script>';
                return;
            }
            
            
        }

    }


    $file = ('./sefip/' . str_pad($_SESSION["id_userSecao"], 6, "0", STR_PAD_LEFT)) . '/sefip.re';

    $_SESSION['ano_sefip'] = '';
    $_SESSION['mes_sefip'] = '';

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    }



?>