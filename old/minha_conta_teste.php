<?php 
include 'header_restrita.php';


/*
#####################################
           VALIDAÇÕES
#####################################
*/

$show_log = false;

$arrEstados = array();
$sql = "SELECT * FROM estados ORDER BY sigla";
$result = mysql_query($sql) or die(mysql_error());
while($estados = mysql_fetch_array($result)){
	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
}



	
		

	
		

$sql_dados_login = "
	SELECT 
		l.id
		, l.email
		, l.status
		, dc.assinante
		, dc.data_inclusao
		, dc.forma_pagameto
		, dc.pref_telefone
		, dc.telefone
		, dc.numero_cartao
		, dc.codigo_seguranca
		, dc.nome_titular
		, dc.data_validade
	FROM 
		login l
		INNER JOIN dados_cobranca dc ON l.id = dc.id
	WHERE 
		l.id='" . $_SESSION["id_userSecaoMultiplo"] . "' 
	LIMIT 0, 1
	";
	
$resultado_dados_login = mysql_query($sql_dados_login)
or die (mysql_error());

$linha_dados_login = mysql_fetch_array($resultado_dados_login);

	$id_usuario = $linha_dados_login['id'];
	$email_usuario = $linha_dados_login['email'];
	$status_login = $linha_dados_login['status'];
	$assinante = $linha_dados_login['assinante'];
	$data_inclusao = $linha_dados_login['data_inclusao'];
	$forma_pagamento_assinante = $linha_dados_login['forma_pagameto'];
	$pref_telefone = $linha_dados_login['pref_telefone'];
	$telefone = $linha_dados_login['telefone'];

	$numero_cartao = $linha_dados_login['numero_cartao'];
	$codigo_seguranca = $linha_dados_login['codigo_seguranca'];
	$nome_titular = $linha_dados_login['nome_titular'];
	$data_validade = $linha_dados_login['data_validade'];
	
	$passNum = 0;
	while ($passNum < strlen($linha_dados_login["senha"])) {
		$senha = $senha . '*'; 
		$passNum = $passNum + 1;
	} 

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};

?>

<div class="principal">


<form name="form_forma_pagamento" id="form_forma_pagamento" method="post" action="minha_conta_forma_pagamento.php" style="display:inline">
<input type="hidden" name="hddStatus" id="hddStatus" value="<?=$linha_meus_dados['status']?>">
<div style="margin-bottom:20px" class="tituloVermelho">Dados de cobrança</div>


<div id="divPagamentoBoleto" style="margin-bottom:20px; <?php //if ($forma_pagamento_assinante != "boleto") {echo 'display:none';} ?>">
  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
    <tr>
      <td valign="middle" class="formTabela">
	  <?php
	  $sql = "SELECT * FROM dados_cobranca WHERE id='" . $linha_meus_dados["id"] . "' LIMIT 0, 1";
	  $resultado = mysql_query($sql)
	  or die (mysql_error());
	  $linha2=mysql_fetch_array($resultado);
	  ?>
        <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
          <tr>
            <td align="right" valign="middle" class="formTabela">Tipo:</td>
            <td class="formTabela">
            	<input type="radio" name="rdbTipo" id="boleto_PJ" value="J" <?=strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'checked' : ''?>> <label for="boleto_PJ">Pessoa Jurídica</label>
              &nbsp;
              <input type="radio" name="rdbTipo" id="boleto_PF" value="F" <?=strlen($linha2['documento']) <= 14 && $linha2['documento'] != '' ? 'checked' : ''?>> <label for="boleto_PF">Pessoa Física</label>
            </td>
          </tr>
          </table>
          
       <script>
	   function checkRadio() {
		   var rdbTipo = "";
		   var len = document.getElementById(form_forma_pagamento).rdbTipo.lenght;
		   var i;
		   
		   for (i = 0; i<len; i++) {
			   if (document.getElementById(form_forma_pagamento).rdbTipo[i].checked) {
				   rdbTipo = document.getElementById(form_forma_pagamento).rdbTipo[i].value;
				   break
			   }
		   }
		   
		   if rdbTipo == "" {
			   alert ("Informe se a cobrança será na Pessoa Jurídica ou na Física");
			   return false;
		   }
		   
		   else {}
			   
	   </script>   
          
          <table>
          <tr>
            <td colspan="2" style="height:10px;"></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela" id="txtSacado"><?=strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'Razão Social' : 'Nome'?>:</td>
            <td class="formTabela"><input type="text" name="boleto_sacado" id="boleto_sacado" maxlength="200" style="width: 100%" value="<?=$linha2['sacado']?>" alt="<?=strlen($linha2['documento']) > 14 ? 'Razão Social' : 'Nome'?>"></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela" id="txtDocumento"><?=strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'CNPJ' : 'CPF'?>:</td>
            <td class="formTabela">
            <input type="text" name="boleto_cnpj" id="boleto_cnpj" maxlength="18" size="18" class="campoCNPJ" value="<?=strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? $linha2['documento'] : ''?>" style="display: <?=strlen($linha2['documento']) > 14 || $linha2['documento'] == ''? 'block' : 'none'?>;" alt="CNPJ">
            <input type="text" name="boleto_cpf" id="boleto_cpf" maxlength="14" size="14" class="campoCPF" value="<?=strlen($linha2['documento']) <= 14 ? $linha2['documento'] : ''?>" style="display: <?=strlen($linha2['documento']) <= 14 && $linha2['documento'] != ''? 'block' : 'none'?>;" alt="CPF">
            </td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela">Endereço:</td>
            <td class="formTabela"><input type="text" name="boleto_endereco" id="boleto_endereco" maxlength="75" style="width: 100%" value="<?=$linha2['endereco']?>" alt="Endereço"></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela">Bairro:</td>
            <td class="formTabela"><input type="text" name="boleto_bairro" id="boleto_bairro" maxlength="30" style="width: 100%" value="<?=$linha2['bairro']?>" alt="Bairro"></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela">UF:</td>
            <td class="formTabela">
              <select name="selEstado" id="selEstado" alt="UF">
                  <option value="" <?php echo selected( '',$linha2['uf'] ); ?>></option>
      <?
            
                  foreach($arrEstados as $dadosEstado){
                    echo "<option value=\"".$dadosEstado['id'].";".$dadosEstado['sigla']."\" " . selected( $dadosEstado['sigla'], $linha2['uf'] ) . " >".$dadosEstado['sigla']."</option>";
                    if($dadosEstado['sigla'] == $linha2['uf']){
                      $idEstadoSelecionado = $dadosEstado['id'];
                    }
                  }
      
      ?>
              </select>
            </td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela">Cidade:</td>
            <td class="formTabela">
              <select name="txtCidade" id="txtCidade" style="width:300px" class="comboM" alt="Cidade">
                <option value="" <?php echo selected( '', $cidade ); ?>></option>
              <?
              if($idEstadoSelecionado != ''){
                $sql = "SELECT * FROM cidades WHERE id_uf = '" . $idEstadoSelecionado . "' ORDER BY cidade";
                $result = mysql_query($sql) or die(mysql_error());
                while($cidades = mysql_fetch_array($result)){
                  echo "<option value=\"".$cidades['cidade']."\" " . selected( $cidades['cidade'], $linha2['cidade']) . " >".$cidades['cidade']."</option>";
                }
              }
              ?>
              </select>
            </td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela">CEP:</td>
            <td class="formTabela"><input type="text" name="boleto_cep" id="boleto_cep" maxlength="9" size="12" class="campoCEP" value="<?=$linha2['cep']?>" alt="CEP"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
<div><input type="hidden" name="hidID" id="hidID" value="<?=$id_usuario?>" /><input name="btnSalvar2" type="button" id="btnSalvar2"  value="Salvar" onclick="formFormaPagamentoSubmit()" /></div>
</form>    
</div>
</div>




