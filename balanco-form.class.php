<?php 
	class Carta{

		private $nome_empresa;
		private $natureza_juridica;
		private $cidade;
		private $estado;
		private $endereco;
		private $objetivo_social;
		private $contingencia;

		function getDados($id = 9){
			$consulta = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$id."' ");
			$objeto=mysql_fetch_array($consulta);

			$this->nome_empresa = $objeto['nome_fantasia'];
			$this->natureza_juridica = $objeto['inscrita_como'];
			$this->cidade = $objeto['cidade'];
			$this->estado = $objeto['estado'];
			$this->endereco = $objeto['endereco'];
			$this->contingencia = "contingencia";

			$consulta = mysql_query("SELECT * FROM dados_da_empresa_codigos WHERE id = '".$id."' ORDER BY tipo ASC");
			while( $objeto=mysql_fetch_array($consulta) ){
				$consulta2 = mysql_query("SELECT * FROM cnae WHERE cnae = '".$objeto['cnae']."' ");
				$objeto2=mysql_fetch_array($consulta2);
				$this->objetivo_social .= $objeto2['denominacao'].', ';
			}

		}
		function gerarTextAreaContingencia($texto=""){
			echo '
				<div class="hideImpressao">
					<textarea id="contingenciaTexto" placeholder="Campo não obrigatório">'.$texto.'</textarea>
				</div>';
		}
		function cadastroContador(){
			$consulta = mysql_query("SELECT * FROM dados_contador_balanco WHERE id_user = '".$_SESSION['id_empresaSecao']."' ");
			$objeto=mysql_fetch_array($consulta);

			if( mysql_num_rows($consulta) == 0 ){
				mysql_query("INSERT INTO `dados_contador_balanco` (`id_user`) VALUES ('".$_SESSION['id_empresaSecao']."')");
				$consulta = mysql_query("SELECT * FROM dados_contador_balanco WHERE id_user = '".$_SESSION['id_empresaSecao']."' ");
				$objeto=mysql_fetch_array($consulta);				
			}

			$consulta_empresa = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$_SESSION['id_empresaSecao']."' ");
			$objeto_empresa=mysql_fetch_array($consulta_empresa);

			$consulta_contador_padrao = mysql_query("SELECT * FROM dados_contador_balanco WHERE id_user = '".$objeto_empresa['estado']."' ");
			$objeto_contador_padrao=mysql_fetch_array($consulta_contador_padrao);

			$nome = $objeto['nome'];
			$nosso_contador_nome = $objeto_contador_padrao['nome'];
		
			$crc = $objeto['crc'];
			$nosso_contador_crc = $objeto_contador_padrao['crc'];
		
			$endereco = $objeto['endereco'];
			$nosso_contador_endereco = $objeto_contador_padrao['endereco'];
		
			$cidade = $objeto['cidade'];
			$nosso_contador_cidade = $objeto_contador_padrao['cidade'];
		
			$estado = $objeto['estado'];
			$nosso_contador_estado = $objeto_contador_padrao['estado'];
		
			$cep = $objeto['cep'];
			$nosso_contador_cep = $objeto_contador_padrao['cep'];

			$tipo = $objeto['tipo'];
			$nosso_contador_tipo = $objeto_contador_padrao['tipo'];
			
			$id = $objeto['id'];

			echo '<br>';
			echo '<div class="hideImpressao tituloVermelho" id="inicioTabelaBalanco">Dados do Contador</div>';

			echo ' 	<div id="dados_contador_indicado">
						<strong>Nome:</strong> '.$nosso_contador_nome.'<br>
						<strong>Crc:</strong> '.$nosso_contador_crc.'<br>
						<strong>Endereco:</strong> '.$nosso_contador_endereco.'<br>
						<strong>Estado:</strong> '.$nosso_contador_estado.'<br>
						<strong>Cidade:</strong> '.$nosso_contador_cidade.'<br>
						<strong>Cep:</strong> '.$nosso_contador_cep.'<br><br>
					</div>
				';

			if( isset($_SESSION['tipoContador']) && $_SESSION['tipoContador'] == 1 ){
				echo '<style type="text/css" media="screen">#dados_contador_indicado{display:none}</style>';
				echo '<input type="checkbox" class="hideImpressao contador_proprio" value="" checked="checked"> Desejo indicar um contabilista de minha confiança para validar meu balanço.';
				echo '<script>$(document).ready(function(e) {$("#dados_contador").css("display","block");});</script>';
			}
			else{
				echo '<style type="text/css" media="screen">#dados_contador{display:none}</style>';
				echo '<input type="checkbox" class="hideImpressao contador_proprio" value=""> <span class="hideImpressao">Desejo indicar um contabilista de minha confiança para validar meu balanço.</span>';
			}
			
			echo '<br><div class="hideImpressao" style="width:400px;">';

			$tipo_crc = '';
			$tipo_cpf = '';
			$estilo_crc = '';
			$estilo_cpf = '';
			if( $objeto['tipo'] == 'crc' ){
				$tipo_crc = 'checked';
				$estilo_crc = 'style="display:table-row"';
				$estilo_cpf = 'style="display:none"';
			}
			if( $objeto['tipo'] == 'cpf' ){
				$tipo_cpf = 'checked';
				$estilo_cpf = 'style="display:table-row"';
				$estilo_crc = 'style="display:none"';
			}

			echo '<table id="dados_contador" >
			    	<tbody>
			    		<tr>
			    			<td>
			    				<input type="hidden" class="nosso_contador_nome" value="'.$nosso_contador_nome.'">
			    				<input type="hidden" class="nosso_contador_crc" value="'.$nosso_contador_crc.'">
			    				<input type="hidden" class="nosso_contador_endereco" value="'.$nosso_contador_endereco.'">
			    				<input type="hidden" class="nosso_contador_cidade" value="'.$nosso_contador_cidade.'">
			    				<input type="hidden" class="nosso_contador_estado" value="'.$nosso_contador_estado.'">
			    				<input type="hidden" class="nosso_contador_cep" value="'.$nosso_contador_cep.'">
			    				<input type="hidden" class="nosso_contador_tipo" value="'.$nosso_contador_tipo.'">
			    			</td>
			    		</tr>
			    		<tr>
				    		<td align="right">
				    			Tipo:
				    		</td>
			    			<td class="formTabela">
				            	<input type="radio" class="editar_item_contador_tipo" name="rdbTipo" tabela="dados_contador_balanco" campo="tipo" id-item="'.$objeto['id'].'" id="contador_PJ" value="crc" '.$tipo_crc.'> <label for="contador_PJ">Pessoa Jurídica</label>&nbsp;&nbsp;
				              	<input type="radio" class="item_contador_usuario editar_item_contador_tipo" name="rdbTipo" tabela="dados_contador_balanco" campo="tipo" id-item="'.$objeto['id'].'" id="contador_PF" value="cpf" '.$tipo_cpf.'> <label for="boleto_PF" class="item_contador_usuario">Pessoa Física</label>
				            </td>
			    		</tr>
			    		<tr>
			    			<td align="right">
			    				Nome:
			    			</td>
			    			<td width="300">
			    				<input type="text" class="item_contador_padrao" name="rdbTipo" value="'.$nosso_contador_nome.'" placeholder="" style="width:300px;" disabled="disabled">
			    				<input type="text" class="nome item_contador_usuario editar_item_contador" name="rdbTipo" tabela="dados_contador_balanco" campo="nome" id-item="'.$objeto['id'].'" value="'.$nome.'" placeholder="" style="width:300px;">
			    			</td>
			    		</tr>
			    		<tr id="contador_cpf" '.$estilo_cpf.'>
					    	<td align="right">
					    		CPF:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="campoCPF cpf_contador item_contador_usuario editar_item_contador" name="rdbTipo" tabela="dados_contador_balanco" campo="crc" id-item="'.$objeto['id'].'" id="boleto_cpf" value="'.$crc.'" placeholder="" style="width:100px;">
					    	</td>
					    </tr>
					    <tr id="contador_crc" '.$estilo_crc.'>
					    	<td align="right">
					    		Crc:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="item_contador_padrao" name="rdbTipo" value="'.$nosso_contador_crc.'" placeholder="" style="width:100px;" disabled="disabled"> 
					    		<input type="text" class="campoCRC crc item_contador_usuario editar_item_contador" name="rdbTipo" tabela="dados_contador_balanco" campo="crc" id-item="'.$objeto['id'].'" value="'.$crc.'" placeholder="" style="width:100px;">
					    	</td>
					    </tr>
					    <tr>
					    	<td align="right">
					    		Endereco:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="item_contador_padrao" name="rdbTipo" value="'.$nosso_contador_endereco.'" placeholder="" style="width:300px;" disabled="disabled">
					    		<input type="text" class="endereco item_contador_usuario editar_item_contador" name="rdbTipo" tabela="dados_contador_balanco" campo="endereco" id-item="'.$objeto['id'].'" value="'.$endereco.'" placeholder="" style="width:300px;">
					    	</td>
					    </tr>
					    
					    <tr>
					    	<td align="right">
					    		Estado:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="item_contador_padrao" name="rdbTipo" value="'.$nosso_contador_estado.'" placeholder="" style="width:100px;" disabled="disabled">
						    	<select class="estado item_contador_usuario editar_item_contador" name="rdbTipo" tabela="dados_contador_balanco" campo="estado" id-item="'.$objeto['id'].'" id="estado_contador" placeholder="" >
							    	<option value="'.$estado.'">'.$estado.'</option>';

							  		$arrEstados = array();
									$sql = "SELECT * FROM estados ORDER BY sigla";
									$result = mysql_query($sql) or die(mysql_error());
									while($estados = mysql_fetch_array($result)){
										array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
									}	    	
						            foreach($arrEstados as $dadosEstado){
										echo "<option class=\"escolher_estado\" id-uf=\"".$dadosEstado['id']."\" value=\"".$dadosEstado['sigla']."\" >".$dadosEstado['sigla']."</option>";
						            }
						            $aux = '';
						            if( $estado != '' ){
						            	$arrEstados = array();
										$sql = "SELECT * FROM cidades ORDER BY cidade";
										$result = mysql_query($sql) or die(mysql_error());
										while($cidades = mysql_fetch_array($result))
											$aux .= '<option value="'.$cidades['cidade'].'">'.$cidades['cidade'].'</option>';
						            }

						echo '
							    </select>
					    		
					    	</td>
					    </tr>
					    <tr>
					    	<td align="right">
					    		Cidade:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="item_contador_padrao" name="rdbTipo" value="'.$nosso_contador_cidade.'" placeholder="" style="width:100px;" disabled="disabled">
					    		<select class="cidade item_contador_usuario editar_item_contador" name="rdbTipo" tabela="dados_contador_balanco" campo="cidade" id-item="'.$objeto['id'].'" id="contador_cidade" >
					    			<option value="'.$cidade.'">'.$cidade.'	</option>
					    			'.$aux.'
					    		</select>
					    	</td>
					    </tr>
					    
					    <tr>
					    	<td align="right">
					    		Cep:
					    	</td>
					    	<td width="300">
					    		<input type="text" class="item_contador_padrao" name="rdbTipo" value="'.$nosso_contador_cep.'" placeholder="" style="width:80px;" disabled="disabled">
					    		<input type="text" class="cep item_contador_usuario editar_item_contador" name="rdbTipo" tabela="dados_contador_balanco" campo="cep" id-item="'.$objeto['id'].'" value="'.$cep.'" placeholder="" style="width:80px;">
					    	</td>
					    </tr>
			    	</tbody>
			    	<input type="hidden" class="id_item" value="'.$id.'" placeholder="" style="width:100%;">
			    	<input type="hidden" class="tipo_item" value="'.$tipo.'" placeholder="" style="width:100%;">
			    </table>
			    <br>
			</div>';




		}
		function getMes($string){

			if( $string == '01' )
				return 'Janeiro';
			if( $string == '02' )
				return 'Fevereiro';
			if( $string == '03' )
				return 'Março';
			if( $string == '04' )
				return 'Abril';
			if( $string == '05' )
				return 'Maio';
			if( $string == '06' )
				return 'Junho';
			if( $string == '07' )
				return 'Julho';
			if( $string == '08' )
				return 'Agosto';
			if( $string == '09' )
				return 'Setembro';
			if( $string == '10' )
				return 'Outubro';
			if( $string == '11' )
				return 'Novembro';
			if( $string == '12' )
				return 'Dezembro';

		}
		function apendice1($id,$ano){

			$consulta = mysql_query("SELECT * FROM dados_contador_balanco WHERE id_user = '".$_SESSION['id_empresaSecao']."' ");
			$objeto=mysql_fetch_array($consulta);

			$consulta_login = mysql_query("SELECT * FROM login WHERE id = '".$id."' ");
			$objeto_login=mysql_fetch_array($consulta_login);

			$consulta_empresa = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$id."' ");
			$objeto_empresa=mysql_fetch_array($consulta_empresa);

			$nome_empresa_contabilidade = $objeto['nome'];
			$CRC_empresa_contabilidade = $objeto['crc'];
			$endereco = $objeto['endereco'];
			$cidade = $objeto['cidade'];
			$estado = $objeto['estado'];
			$cep = $objeto['cep'];
					
			if( $objeto['id'] == '' ){

				$consulta_contador_padrao = mysql_query("SELECT * FROM dados_contador_balanco WHERE id_user = '".$objeto_empresa['estado']."' ");
				$objeto_contador_padrao=mysql_fetch_array($consulta_contador_padrao);

				$nome_empresa_contabilidade = $objeto_contador_padrao['nome'];
				$CRC_empresa_contabilidade = $objeto_contador_padrao['crc'];
				$endereco = $objeto_contador_padrao['endereco'];
				$cidade = $objeto_contador_padrao['cidade'];
				$estado = $objeto_contador_padrao['estado'];
				$cep = $objeto_contador_padrao['cep'];
			}

			$denominacao_social = $objeto_empresa['nome_fantasia'];
			$cnpj = $objeto_empresa['cnpj'];
			$nome_empresa = $objeto_empresa['razao_social'];
			$nome = $objeto_login['assinante'];
			$ano_base = $ano;
			$sistema_em_uso = "Contador Amigo";

			$consulta_dados_responsavel = mysql_query("SELECT * FROM dados_do_responsavel WHERE id = '".$_SESSION['id_empresaSecao']."' AND responsavel = '1'	");
			$objeto_dados_responsavel=mysql_fetch_array($consulta_dados_responsavel);

			$nome_responsavel = $objeto_dados_responsavel['nome'];
			$cpf_responsavel = $objeto_dados_responsavel['cpf'];

			echo '
		<div style="position: relative;">
		<div style="margin-top: 25%;">
			<div class="tituloVermelho"><center>Carta de Responsabilidades da Administração</center></div>
			<br>
			<div style="float:right">'.$objeto_empresa['cidade'].', '.date("d").' de '.$this->getMes(date("m")).' de '.$ano_base.'</div>
			
			<span class="contador_nome_empresa_contabilidade"></span><br>
			
			<span class="tipo_contador1">CRC</span>: <span class="contador_CRC_empresa_contabilidade"></span><br>
			Endereço: <span class="contador_endereco"></span><br>
			Cidade e Estado: <span class="contador_cidade"></span><br>
			CEP: <span class="contador_cep"></span>

			<br><br><br><br>
			Prezados Senhores:
			<br><br>
			Declaramos para os devidos fins, como administrador e responsável legal da empresa
			'.$denominacao_social.', CNPJ '.$cnpj.', que as informações fornecidas à V.Sas.
			para preparação das demonstrações contábeis, obrigações acessórias, apuração de
			impostos e arquivos eletrônicos exigidos pela fiscalização federal, estadual, municipal,
			trabalhista e previdenciária são fidedignos e compreendem a realidade do que diz
			respeito a:
			<br>
			
			<ol style="list-style: decimal">		
				<li>o valor apresentado na conta caixa, perfaz a realidade que tínhamos no
				encerramento do exercício de '.$ano_base.';</li>
				
				<li>informamos desconhecer e não possuir nenhuma operação que não tenha sido
				registrada em nossa contabilidade, pois, todas as nossas operações são
				geradas com documentação suporte adequada;</li>
				
				<li>asseguramos que os controles internos adotados pela nossa entidade são de
				responsabilidade da administração e adequados ao tipo de atividade e volume
				de transações;</li>
				
				<li>não realizamos nenhum tipo de operação que possa ser considerada ilegal,
				frente à legislação vigente ;</li>
				
				<li>todos os documentos que geramos e recebemos de nossos fornecedores estão
				revestidos de total idoneidade;</li>
				
				<li>todos os ativos que informamos para V.Sas., são de nossa propriedade;
				<li>os estoques registrados em conta própria, foram por nós avaliados, contados e
				levantados fisicamente, e perfazem a realidade do exercício encerrado em
				'.$ano_base.';</li>
				
				<li>as informações registradas no sistema de gestão e controle interno,
				denominado '.$sistema_em_uso.' são controladas e validadas com a
				documentação suporte adequada, sendo de nossa inteira responsabilidade todo
				conteúdo do banco de dados e arquivos eletrônicos gerados.</li>
			</ol>
				
			
			
			Além disso, não temos conhecimento:
			<br>
			
				<ol style="list-style: decimal">
				<li> de que não tenhamos cumprido todas as leis, normas e regulamentos a que
				à empresa está sujeita. Também não temos conhecimento de que houve,
				durante o exercício, operações ou transações que possam ser reconhecidas
				como irregulares ou ilegais e/ou que não tenham sido realizadas no
				interesse da empresa;</li>
				<li>de que diretores ou empregados em cargos de responsabilidade ou
				confiança tenham participado ou participem da administração ou tenham
				interesses em sociedades com as quais a empresa manteve transações;
				</li>
				<li>de quaisquer fatos ocorridos que possam afetar as demonstrações contábeis
				ou que as afetam até a data desta carta ou, ainda, que possam afetar a
				continuidade das operações da empresa;
				</li>
				<li>de efeitos relevantes nas demonstrações contábeis, decorrentes das
				seguintes situações:
				</li>
				<ol style="list-style:lower-alpha">				
					<li>ações ou reclamações materiais contra a empresa;
					</li>
					<li>acordos ou operações estranhos aos negócios normais ou quaisquer
					outros acordos;
					</li>
					<li>inadimplências contratuais que possam resultar em prejuízos para a
					empresa;
					</li>
					<li>existência de contingências (ativas ou passivas) além daquelas que
					estejam descritas, reconhecidas ou provisionadas, por serem
					consideradas virtualmente certas (contingências ativas) ou prováveis
					(contingências passivas).</li>
				</ol>
			</ol>
			Também confirmamos que não houve:
			<ol style="list-style: decimal">		
				<li> fraude envolvendo administração ou empregados em cargos de
				responsabilidades ou confiança;
				</li>
				<li> fraude envolvendo terceiros que poderiam ter efeito material nas
				demonstrações contábeis;
				</li>
				<li> violação ou possíveis violações de leis, normas ou regulamentos cujos
				efeitos deveriam ser considerados para divulgação nas demonstrações
				contábeis ou mesmo dar origem ao registro de provisão para
				contingências passivas.
				</li>
			</ol>
		</ol>
			Como também declaramos ciência quanto a:
			<br>
			<ol style="list-style: decimal">		
				<li>exigência da fiscalização eletrônica federal, estadual, municipal, trabalhista e
				previdenciária, relacionadas a obrigatoriedade de: SPED FISCAL; SPED
				CONTRIBUIÇÕES; SPED ECD; SPED NFE; MANAD; SINTEGRA; Certificação
				Digital;
				</li>
				<li>necessidade de auditoria eletrônica de dados, haja vista que os arquivos
				exigidos pela fiscalização eletrônica contem informações de diversas fontes e
				sistemas, tais como: contábil, fiscal, trabalhista, financeiro, administrativo,
				comercial, entre outros;
				</li>
				<li>toda e qualquer divergência encontrada pelo fisco nos arquivos eletrônicos são
				de nossa inteira responsabilidade.
				</li>
			</ol>
			Atenciosamente,<br><br><br><br><br>
			<br>
			
				<center>
					<p>_______________________________________________________</p>
					'.$nome_responsavel.'<br>
					CPF: . '.$cpf_responsavel.'<br>
					Responsável Legal
				</center>

	
			';
			echo '<div style="clear: both;height: 10px;"></div></div></div>';
		}
		function gerarCarta($id,$ano){
			$this->getDados($id);
			$string = '<br><br>
			
				<div class="apenasImpressao">
				<div class="tituloVermelho"><strong>Notas Explicativas</strong></div><br>
				<div class="tituloVermelho"><strong>1. Contexto Operacional</strong></div>
				A '.$this->nome_empresa.' é uma '.$this->natureza_juridica.' com sede em '.$this->cidade.', '.$this->estado.' e '.$this->endereco.' e tem como principal por objeto '.$this->objetivo_social.'. Foi constituída em data conforme seu documento constitutivo.
				<br><br><br>
				<div class="tituloVermelho"><strong>2. Declaração de conformidade e política contábil significativas</strong></div>
				A administração declara que as Demonstrações Contábeis da '.$this->nome_empresa.' do período compreendido entre 01 de janeiro de '.$ano.' e 31 de dezembro de '.$ano.', apresentam adequadamente a posição patrimonial e financeira, o desempenho e os fluxos de caixa da entidade, com observância aos Princípios de Contabilidade e foram elaboradas em conformidade com a ITG 1000, aprovada pela resolução CFC  1418/2012. As demonstrações contábeis, exceto informações de fluxo de caixa foram elaborados segundo o regime de competência e estão representadas em real, a moeda nacional brasileira.
				<br><br>
				<strong>2.1. Imobilizado – </strong>Os terrenos e imóveis estão demonstrados ao valor justo (custo atribuído) conforme opção prevista no Pronunciamento Técnico CPC 27, aprovado pelo CFC – Conselho Federal de Contabilidade pela Resolução 1.177/09. A avaliação pelo custo atribuído, bem como suas estimativas de vida útil dos imóveis foram determinadas com base em laudo técnico emitida por empresa especializada para a data base de 1º de janeiro de 201X. Os demais itens de ativo imobilizado são demonstrados ao custo de aquisição, mais todos os gastos incorridos para colocar o bem em condições de uso. As depreciações das edificações são calculadas com base na estimativa de vida útil dos bens determinados em virtude do custo atribuído. Os demtais itens são depreciados linearmente com base nas mesmas taxas estabelecidas conforme legislação brasileira.”
				<br><br>
				<div class="remove_contingencias">
				<strong>2.2 Contingências passivas -</strong>  <span id="textoContingencia"></span>
				<br><br><br>
				</div>
				<div class="tituloVermelho"><strong>3. Apresentação das demonstrações contábeis</strong></div>
				<strong>3.1. Demonstração do resultado do exercício -</strong> Demonstração contábil que apresenta todos os itens de receita e despesa reconhecidos no período, excluindo os itens de outros resultados abrangentes;
				<br><br>
				<strong>3.2. Balanço patrimonial -</strong> Demonstração que apresenta a relação de ativos, passivos e patrimônio líquido de uma entidade em data específica,entendendo que Ativos são recursos controlados pela entidade como resultado de eventos passados do qual se esperam benefícios econômicos futuros para a entidade, passivo,como Obrigação presente da entidade, derivada de eventos já ocorridos,, cuja liquidação se espera resulte em saída de recursos capazes de gerar benefícios econômicos e patrimônio líquido como o valor residual dos ativos da entidade após a dedução de todos os seus passivos;
				<br><br>
				<strong>3.3. Demonstração de lucros ou prejuízos acumulados  -</strong> Demonstração contábil que apresenta as alterações em lucros ou prejuízos acumulados para um período.
				<br><br>
				<strong>3.4. Demonstração do resultado abrangente - </strong>Demonstração que começa com lucro ou prejuízo do período e a seguir mostra os itens de outros resultados abrangentes do período, que não foram demonstradas no Resultado do Exercício. 
				<br><br>
				<strong>3.5. Demonstração dos fluxos de caixa -</strong> Demonstração que oferece informações sobre as alterações em caixa e equivalentes de caixa da entidade por um período, mostrando alterações separadamente durante o período em atividades operacionais, de investimento e de financiamento.
			</div>
			';

			echo str_replace(", .", ".", $string);
		}

	}	
	class Balanco_form{
		private $id;
		private $id_user;
		private $ano;
		private $a_c_caixa_equivalente_caixa;
		private $a_c_contas_receber;
		private $a_c_estoques;
		private $a_c_outros_creditos;
		private $a_c_despesas_exercicio_seguinte;
		private $a_n_c_contas_receber;
		private $a_n_c_investimentos;
		private $a_n_c_imobilizado;
		private $a_n_c_intangivel;
		private $a_n_c_depreciacao;
		private $p_c_fornecedores;
		private $p_c_emprestimos_bancarios;
		private $p_c_obrigacoes_sociais_impostos;
		private $p_c_contas_pagar;
		private $p_c_lucros_distribuir;
		private $p_c_provisoes;
		private $p_n_c_contas_pagar;
		private $p_n_c_financiamentos_bancarios;
		private $p_l_capital_social;
		private $p_l_reservas_capital;
		private $p_l_ajustes_avaliacao_patrimonial;
		private $p_l_reservas_lucro;
		private $p_l_lucros_acumulados;
		private $p_l_prejuizos_acumulados;
		private $caixa_final;

		function __construct($dados=NULL,$ano){

			$this->ano = $ano;

			$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND item = '' AND quantidade = '0' AND valor = '0' AND ano_item = 0");
			if( mysql_num_rows($consulta) > 1 )
				mysql_query("DELETE FROM imobilizados WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND item = '' AND quantidade = '0' AND valor = '0' AND ano_item = 0 LIMIT 1 ");

			$consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND item = '' AND quantidade = '0' AND valor = '0' AND ano_item = 0");
			if( mysql_num_rows($consulta) > 1 )
				mysql_query("DELETE FROM intangiveis WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND item = '' AND quantidade = '0' AND valor = '0' AND ano_item = 0 LIMIT 1 ");

			$emprestimos = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE data = '0000-00-00' AND categoria = 'Empréstimos' AND entrada = '0' ");	
			$string = '';
			if( mysql_num_rows($emprestimos) > 1 ){
				mysql_query("DELETE FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE data = '0000-00-00' AND categoria = 'Empréstimos' AND entrada = '0' ORDER BY id DESC ");
				mysql_query("INSERT INTO user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos WHERE meses = '0' AND valor_pago = '0' ORDER BY id DESC ");
			}

			//Pega o saldo em 31/12/AAAA
			$sql = "SELECT SUM(entrada) entrada,SUM(saida) saida FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data < '".$this->ano."-12-31' ";
			$resultado = mysql_query($sql)
			or die (mysql_error());
			$saldo = 0;
			while ($linha=mysql_fetch_array($resultado)) {
				$entrada = $linha["entrada"];
				$saida = $linha["saida"];
				$saldo = bcadd($saldo,bcsub($entrada, $saida, 2),2);
			}	
			$this->caixa_final = $saldo;
			$this->a_c_caixa_equivalente_caixa = $saldo;
			if( $dados != NULL ){
				$this->id = $dados['id'];
				$this->id_user = $dados['id_user'];
				$this->ano = $dados['ano'];
				if( $dados['a_c_caixa_equivalente_caixa'] == 0 || $dados['a_c_caixa_equivalente_caixa'] == '' ){
					$this->a_c_caixa_equivalente_caixa = $saldo;
					mysql_query("UPDATE  balanco_patrimonial set a_c_caixa_equivalente_caixa = '".$saldo."' WHERE ano = '".$ano."' AND id_user = '".$_SESSION['id_empresaSecao']."' ");
				}//Se ja existir um saldo cadastrado no balanço, pega o valor salvo
				else
					$this->a_c_caixa_equivalente_caixa = floatval($dados['a_c_caixa_equivalente_caixa']);

				$this->caixa_final = $this->a_c_caixa_equivalente_caixa - $dados['a_n_c_investimentos'];

				$this->a_c_contas_receber = $dados['a_c_contas_receber'];
				$this->a_c_estoques = $dados['a_c_estoques'];
				$this->a_c_outros_creditos = $dados['a_c_outros_creditos'];
				$this->a_c_despesas_exercicio_seguinte = $dados['a_c_despesas_exercicio_seguinte'];
				$this->a_n_c_contas_receber = $dados['a_n_c_contas_receber'];
				$this->a_n_c_investimentos = $dados['a_n_c_investimentos'];
				$this->a_n_c_imobilizado = $dados['a_n_c_imobilizado'];
				$this->a_n_c_intangivel = $dados['a_n_c_intangivel'];
				$this->a_n_c_depreciacao = $dados['a_n_c_depreciacao'];
				$this->p_c_fornecedores = $dados['p_c_fornecedores'];
				$this->p_c_emprestimos_bancarios = $dados['p_c_emprestimos_bancarios'];
				$this->p_c_obrigacoes_sociais_impostos = $dados['p_c_obrigacoes_sociais_impostos'];
				$this->p_c_contas_pagar = $dados['p_c_contas_pagar'];
				$this->p_c_lucros_distribuir = $dados['p_c_lucros_distribuir'];
				$this->p_c_provisoes = $dados['p_c_provisoes'];
				$this->p_n_c_contas_pagar = $dados['p_n_c_contas_pagar'];
				$this->p_n_c_financiamentos_bancarios = $dados['p_n_c_financiamentos_bancarios'];
				$this->p_l_capital_social = $dados['p_l_capital_social'];
				$this->p_l_reservas_capital = $dados['p_l_reservas_capital'];
				$this->p_l_ajustes_avaliacao_patrimonial = $dados['p_l_ajustes_avaliacao_patrimonial'];
				$this->p_l_reservas_lucro = $dados['p_l_reservas_lucro'];
				$this->p_l_lucros_acumulados = $dados['p_l_lucros_acumulados'];
				$this->p_l_prejuizos_acumulados = $dados['p_l_prejuizos_acumulados'];
			}
		}
		function selected($string1,$string2){
			if( $string1 == $string2 ){
				return 'selected="selected"';
			}
		}
		function inserirImobilizados(){
			$datas = new Datas();
			$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '".$_SESSION['id_empresaSecao']."' AND YEAR(data) <= '".$this->ano."' ");
			if( mysql_num_rows($consulta) == 0 ){
				mysql_query("INSERT INTO `imobilizados`(`id_user`) VALUES ('". $_SESSION['id_empresaSecao']."')");
				$consulta = mysql_query("SELECT * FROM imobilizados WHERE id_user = '". $_SESSION['id_empresaSecao']."' AND YEAR(data) <= '".$this->ano."' ");
			}
			$string = '';
			$objeto_id = '';
			while( $objeto=mysql_fetch_array($consulta) ){
				$objeto_item = $objeto['item'];
				if( $objeto['quantidade'] != 0 )
					$objeto_quantidade = $objeto['quantidade'];
				else
					$objeto_quantidade = '';
				if( $objeto['valor'] != 0 )
					$objeto_valor = $objeto['valor'];
				else
					$objeto_valor = '';
				if( $objeto['data'] != 0 )
					$objeto_data = $objeto['data'];
				else
					$objeto_data = '';
				if( $objeto['id'] != 0 )
					$objeto_id = $objeto['id'];
				else
					$objeto_id = '';
				if( $objeto['valor_mercado'] != 0 )
					$objeto_valor_mercado = $objeto['valor_mercado'];
				else
					$objeto_valor_mercado = 0;


				$string .= 	'
					<tr>
						<td class="td_calendario" align="center" valign="middle" >
							<select class="item item_imobilizado_editar" tabela="imobilizados" campo="item" id="'.$objeto_id.'" style="width:370px;">
								<option value="" '.$this->selected('Selecione',$objeto_item).'>Selecione</option>
								<option value="Veículos" '.$this->selected('Veículos',$objeto_item).'>Veículos</option>
								<option value="Imóveis (prédios)" '.$this->selected('Imóveis (prédios)',$objeto_item).'>Imóveis (prédios)</option>
								<option value="Móveis e utensílios" '.$this->selected('Móveis e utensílios',$objeto_item).'>Móveis e utensílios</option>
								<option value="Computadores e periféricos" '.$this->selected('Computadores e periféricos',$objeto_item).'>Computadores e periféricos</option>
								<option value="Máquinas e equipamentos" '.$this->selected('Máquinas e equipamentos',$objeto_item).'>Máquinas e equipamentos</option>
								<option value="Terreno" '.$this->selected('Terreno',$objeto_item).'>Terreno</option>
							</select>
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<label>
								<input class="input_quantidade item_imobilizado_editar"  tabela="imobilizados" campo="quantidade" id="'.$objeto_id.'"  type="number" value="'.$objeto_quantidade.'" min="1" style="width:50px;">
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<label>
								<input class="input_valor currency item_imobilizado_editar"  tabela="imobilizados" campo="valor" id="'.$objeto_id.'"  type="text" value="'.number_format($objeto_valor,2,',','.').'" style="width:80px;">
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle">
							<label>
								<input class="campoData input_ano item_imobilizado_editar"  tabela="imobilizados" campo="data" id="'.$objeto_id.'"  value="'.$datas->desconverterData($objeto_data).'" type="text" size="10">
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<label>
								<input class="input_valor_mercado currency item_imobilizado_editar"  tabela="imobilizados" campo="valor_mercado" id="'.$objeto_id.'"  type="text" value="'.number_format($objeto_valor_mercado,2,',','.').'" style="width:80px;">
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="'.$objeto_id.'" titulo="'.$objeto_item.'" ></i>
						</td>
					</tr>';
			}
			echo '
				    <table id="itens_imobilizados" border="0" cellspacing="2" cellpadding="4" class="formTabela" style="width:966px">
					  	<tbody>
						  	<tr>
				       			<th align="center" width="380">Tipo de Ativo</th>
				       			<th align="center" >Quantidade</th>
				       			<th align="center" >Valor Unitário</th>
				       			<th align="center" >Ano da compra</th>
				       			<th align="center" >Valor de mercado</th>
				       			<th align="center" width="100">Comprovante</th>
				    		</tr>
					  		'.$string.'
					  	</tbody>
					</table>
					<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="width:100%;float:right">
					  	<tbody>
					  		<tr>
					  			<td colspan="" align="left">
					  				<a id="inserir_outro_imobilizado" href="" title="">Adicionar </a>
					  				|
					  				<a id="remover_imobilizado" href="" title=""> Remover</a>
					  			</td>
					  		</tr>
						</tbody>
					</table>
					<input type="hidden" id="imobilizado_deletar" value="'.$objeto_id.'">
			';
		}
		function inserirIntangiveis(){
			$datas = new Datas();
			$consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '". $_SESSION['id_empresaSecao']."' AND YEAR(data) <= '".$this->ano."' ");
			$string = '';
			if( mysql_num_rows($consulta) == 0 ){
				mysql_query("INSERT INTO `intangiveis`(`id_user`) VALUES ('". $_SESSION['id_empresaSecao']."')");
				$consulta = mysql_query("SELECT * FROM intangiveis WHERE id_user = '". $_SESSION['id_empresaSecao']."' AND YEAR(data) <= '".$this->ano."' ");
			}
			$objeto_id = '';
			while( $objeto=mysql_fetch_array($consulta) ){
				$objeto_item = $objeto['item'];
				if( $objeto['quantidade'] != 0 )
					$objeto_quantidade = $objeto['quantidade'];
				else
					$objeto_quantidade = '';
				if( $objeto['valor'] != 0 )
					$objeto_valor = $objeto['valor'];
				else
					$objeto_valor = '';
				if( $objeto['data'] != 0 )
					$objeto_data = $objeto['data'];
				else
					$objeto_data = '';
				if( $objeto['id'] != 0 )
					$objeto_id = $objeto['id'];
				else
					$objeto_id = '';
				$string .= 	'
					<tr>
						<td class="td_calendario" align="center" valign="middle" >
							<select class="item editar_item_intengivel" tabela="intangiveis" campo="item" id="'.$objeto_id.'" style="width:370px">
								<option value="" '.$this->selected('Selecione',$objeto_item).'>Selecione</option>
								<option value="Software" '.$this->selected('Software',$objeto_item).'>Software</option>
								<option value="Marca" '.$this->selected('Marca',$objeto_item).'>Marca</option>
								<option value="Patente" '.$this->selected('Patente',$objeto_item).'>Patente</option>
								<option value="Direitos autorais" '.$this->selected('Direitos autorais',$objeto_item).'>Direitos autorais</option>
								<option value="Licenças" '.$this->selected('Licenças',$objeto_item).'>Licenças</option>
								<option value="Pesquisa e desenvolvimento" '.$this->selected('Pesquisa e desenvolvimento',$objeto_item).'>Pesquisa e desenvolvimento</option>
							</select>
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<label>
								<input class="input_quantidade editar_item_intengivel" tabela="intangiveis" campo="quantidade" id="'.$objeto_id.'" type="number" value="'.$objeto_quantidade.'" min="1" style="width:50px;">
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle" >
							<label>
								<input class="input_valor currency editar_item_intengivel" tabela="intangiveis" campo="valor" id="'.$objeto_id.'" type="text" value="'.number_format($objeto_valor,2,',','.').'" style="width:80px;">
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle">
							<label>
								<input class="campoData input_ano editar_item_intengivel" tabela="intangiveis" campo="data" id="'.$objeto_id.'" type="text" style="width:80px;" value="'.$datas->desconverterData($objeto_data).'" size="10">
							</label>
						</td>
						<td class="td_calendario" align="center" valign="middle">
							<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="'.$objeto_id.'" titulo="'.$objeto_item.'" ></i>
						</td>
					</tr>';
			}
			echo '  <table id="itens_intangiveis" border="0" cellpadding="4" cellspacing="2" class="formTabela" style="width:966px">
					  	<tbody>
					  	<tr>
			       			<th align="center" width="380">Tipo de Ativo</th>
			       			<th align="center" >Quantidade</th>
			       			<th align="center" >Valor Unitário</th>
			       			<th align="center" >Ano da compra</th>
			       			<th align="center" width="100">Comprovante</th>
			    		</tr>
					  	'.$string.'			
						</tbody>
					</table>
					<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="width:100%;flot:left">
					  	<tbody>
					  		<tr>
					  			<td colspan="" align="left">
					  				<a id="inserir_outro_intangiveis" href="" title="">Adicionar </a>
					  				|
					  				<a id="remover_intangiveis" href="" title=""> Remover</a>
					  			</td>
					  		</tr>
						</tbody>
					</table>
					<input type="hidden" id="intangivel_deletar" value="'.$objeto_id.'">
			';
		}
		function transformarEmprestimos(){
			if ($consulta = mysql_query("SHOW TABLES LIKE 'user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos' ")) {
			    if( mysql_num_rows($consulta) == 0 ) {
			    	echo 'aki';
			        mysql_query("CREATE TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` (`id` int(11) NOT NULL,`id_item` int(11) NOT NULL,`valor_pago` float NOT NULL,`meses` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;");
					mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` ADD PRIMARY KEY (`id`); ");
					mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
			    }   
			}
			$livro_caixa = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE categoria = 'Empréstimos' ");
			while( $objeto_livro_caixa = mysql_fetch_array($livro_caixa) ){
				$emprestimos = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos WHERE id_item = '".$objeto_livro_caixa['id']."' ");
				$objeto_emprestimos = mysql_fetch_array($emprestimos);
				if( mysql_num_rows($emprestimos) == 0 ){
					mysql_query("INSERT INTO `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` (`id_item`) VALUES ( '".$objeto_livro_caixa['id']."'  )");
				}
			}
		}
		function gerarTabelaEmprestimos(){

			$this->transformarEmprestimos();

			$datas = new Datas();
			$emprestimos = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos ORDER BY id_item");	
			$string = '';
			$aux = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE categoria = 'Empréstimos' AND YEAR(data) = '".$this->ano."' ");
			$gambiarra = false;
			while( $objeto_consulta = mysql_fetch_array($consulta) ){
				$livro_caixa_emprestimo = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE id = '".$objeto_consulta['id_item']."' AND ( YEAR(data) <= '".$this->ano."' OR data = '0000-00-00' ) ");
				if( mysql_num_rows($livro_caixa_emprestimo) == 0 )
					$gambiarra = true;
			}
			if( $gambiarra ){
				mysql_query("INSERT INTO user_".$_SESSION['id_empresaSecao']."_livro_caixa (`categoria`) VALUES ('Empréstimos')");
				mysql_query("INSERT INTO user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos (`id_item`) VALUES ('".mysql_insert_id()."')");
				$emprestimos = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos ORDER BY id_item");	
			}
			while( $objeto_emprestimos = mysql_fetch_array($emprestimos) ){
				$livro_caixa_emprestimo = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE id = '".$objeto_emprestimos['id_item']."' AND ( YEAR(data) <= '".$this->ano."' OR data = '0000-00-00' ) ");
				while( $objeto_livro_caixa_emprestimo = mysql_fetch_array($livro_caixa_emprestimo) ){
					$id = $objeto_livro_caixa_emprestimo['id'];
					if( $objeto_livro_caixa_emprestimo['data'] == '0000-00-00' )
						$data = '';
					else
						$data = $objeto_livro_caixa_emprestimo['data'];
					if( $objeto_emprestimos['valor_pago'] == 0 )
						$valor_pago = 0;
					else
						$valor_pago = $objeto_emprestimos['valor_pago'];
					if( $objeto_livro_caixa_emprestimo['entrada'] == 0 )
						$valor = '';
					else
						$valor = $objeto_livro_caixa_emprestimo['entrada'];

					$meses = '<option value="">Selecione</option>';
					$meses = '<option value="0">Não tem</option>';
					for ($i=1; $i <= 100; $i++) { 
						if( $i == 1 )
							$meses .= '<option value="'.$i.'" '.$this->selected($objeto_emprestimos['meses'],$i).'>'.$i.' mes</option>';
						else
							$meses .= '<option value="'.$i.'" '.$this->selected($objeto_emprestimos['meses'],$i).'>'.$i.' meses</option>';
					}
					$string .= '
						<tr>
			    			<td class="td_calendario" align="center" valign="middle" >
			    				<input type="text" style="text-align:left;width:97%;background: #e5e5e5;border: 0;outline: none;" value="'.$objeto_livro_caixa_emprestimo['descricao'].'" placeholder="" style="width:370px" disabled="disabled">
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle" >
			    				<input style="background: #e5e5e5;border: 0;outline: none;" type="text" class="campoData" value="'.$datas->desconverterData($data).'" size="15" disabled="disabled">
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle" >
			    				<input style="background: #e5e5e5;border: 0;outline: none;" type="text" class="currency" tabela="user_'.$_SESSION['id_empresaSecao'].'_livro_caixa" value="'.number_format($valor , 2 , ',' , '.').'" style="width:80px" disabled="disabled">
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle" >
			    				<select class="editar_item_emprestimo" tabela="user_'.$_SESSION['id_empresaSecao'].'_livro_caixa_emprestimos" campo="meses" id="'.$objeto_emprestimos['id'].'">
			    					<option value="">Selecione</option>
			    					
			    					'.$meses.'
			    					
			    				</select>
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle">
			    				<input type="text" name="" class="currency editar_item_emprestimos" campo="valor_pago" id="'.$objeto_emprestimos['id'].'" tabela="user_'.$_SESSION['id_empresaSecao'].'_livro_caixa_emprestimos" value="'.number_format($valor_pago , 2 , ',' , '.').'" style="width:80px">
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle" >
			    				<i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_c_emprestimos_bancarios'.$objeto_emprestimos['id'].'" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i>
		    				</td>
			    		</tr>';
				}
			}
			if( $string == '' ){
				$string = '
						<tr>
			    			<td class="td_calendario" align="center" valign="middle" style="height:30px;">
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle" >
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle" >
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle" >
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle">
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle" >
		    				</td>
			    		</tr>';
			}
			$string .= '<input type="hidden" id="emprestimo_deletar" value="'.$id.'">';
			return $string;
		}
		function gerarTabelaFinanciamentoBancario(){
			$datas = new Datas();

			$emprestimos = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos ORDER BY id_item");	
			$string = '';
			while( $objeto_emprestimos = mysql_fetch_array($emprestimos) ){
				$livro_caixa_emprestimo = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE id = '".$objeto_emprestimos['id_item']."' ");
				if( mysql_num_rows($livro_caixa_emprestimo) == 0 )
					$string = '<style>#itens_financiamentos{display:none}#aviso_financiamentos{display:initial!important}</style>';
				while( $objeto_livro_caixa_emprestimo = mysql_fetch_array($livro_caixa_emprestimo) ){
					$string .= '
						<tr>
			    			<td class="td_calendario" align="center" valign="middle" width="120">'.$objeto_livro_caixa_emprestimo['descricao'].'</td>
			    			<td class="td_calendario" align="center" valign="middle" width="80">'.$datas->desconverterData($objeto_livro_caixa_emprestimo['data']).'</td>
			    			<td class="td_calendario" align="center" valign="middle" width="50">
			    				<input type="text" name="" class="currency editar_item_emprestimos" campo="valor_pago" id="'.$objeto_emprestimos['id'].'" tabela="user_'.$_SESSION['id_empresaSecao'].'_livro_caixa_emprestimos" value="'.number_format($objeto_emprestimos['valor_pago'] , 2 , ',' , '.').'" style="width:100px">
			    			</td>
			    			<td class="td_calendario" align="center" valign="middle" width="100"><i class="fa fa-file-text-o icone-download abrirJanelaAnexos" aria-hidden="true" tipo="p_n_c_financiamentos_bancarios'.$objeto_emprestimos['id'].'" tag="#tag=0_a_n_c_investimentos" titulo="Investimentos" ></i></td>
			    		</tr>';
				}
			}
			return $string;
		}
		function gerarInputano($ano){
						
			$ano_atual = intval(date("Y"));
			
			$getAno = isset($_GET['ano']) ? $_GET['ano'] : '';
			
			for ($i=intval(date("Y")); $i > intval(date("Y")) - 5; $i--) { 
				echo '	<label style="margin-right:20px;">
							<input class="atualizarAno" style="margin-right:5px;" type="radio" name="ano" value="'.$ano_atual.'"';
				if( ($ano_atual == intval(date("Y")) || $ano_atual == $getAno ) && $ano != 0)
					echo 'checked="checked"';
				echo '" >'.$ano_atual.'
						</label>';
				$ano_atual = $ano_atual - 1;
			}
		}
		function gerarInputa_c_caixa_equivalente_caixa(){
			return '<input type="hidden" class="original_a_c_caixa_equivalente_caixa" value="'.$this->geta_c_caixa_equivalente_caixa().'"><input class="input_tabela currency input_valor a_c_caixa_equivalente_caixa editar_item_balanco" tabela="balanco_patrimonial" campo="a_c_caixa_equivalente_caixa" id="'.$this->getid().'" type="text" name="a_c_caixa_equivalente_caixa" value="'.$this->getcaixa_final().'" disabled="disabled">';
		}
		function gerarInputa_c_contas_receber(){
			return '<input class="input_tabela currency input_valor a_c_contas_receber editar_item_balanco" tabela="balanco_patrimonial" campo="a_c_contas_receber" id="'.$this->getid().'" type="text" name="a_c_contas_receber" value="'.$this->geta_c_contas_receber().'" placeholder="">';
		}
		function gerarInputa_c_estoques(){
			return '<input class="input_tabela currency input_valor a_c_estoques editar_item_balanco" tabela="balanco_patrimonial" campo="a_c_estoques" id="'.$this->getid().'" type="text" name="a_c_estoques" value="'.$this->geta_c_estoques().'" placeholder="">';
		}
		function gerarInputa_c_outros_creditos(){
			return '<input class="input_tabela currency input_valor a_c_outros_creditos editar_item_balanco" tabela="balanco_patrimonial" campo="a_c_outros_creditos" id="'.$this->getid().'" type="text" name="a_c_outros_creditos" value="'.$this->geta_c_outros_creditos().'" placeholder="">';
		}
		function gerarInputa_c_despesas_exercicio_seguinte(){
			return '<input class="input_tabela currency input_valor a_c_despesas_exercicio_seguinte editar_item_balanco" tabela="balanco_patrimonial" campo="a_c_despesas_exercicio_seguinte" id="'.$this->getid().'" type="text" name="a_c_despesas_exercicio_seguinte" value="'.$this->geta_c_despesas_exercicio_seguinte().'" placeholder="">';
		}
		function gerarInputa_n_c_contas_receber(){
			return '<input class="input_tabela currency input_valor a_n_c_contas_receber editar_item_balanco" tabela="balanco_patrimonial" campo="a_n_c_contas_receber" id="'.$this->getid().'" type="text" name="a_n_c_contas_receber" value="'.$this->geta_n_c_contas_receber().'" placeholder="">';
		}
		function gerarInputa_n_c_investimentos(){
			return '<input class="input_tabela currency input_valor a_n_c_investimentos editar_item_balanco" tabela="balanco_patrimonial" campo="a_n_c_investimentos" id="'.$this->getid().'" type="text" name="a_n_c_investimentos" value="'.$this->geta_n_c_investimentos().'" placeholder="">';
		}
		function gerarInputa_n_c_imobilizado(){
			return '<input class="input_tabela currency input_valor a_n_c_imobilizado editar_item_balanco" tabela="balanco_patrimonial" campo="a_n_c_imobilizado" id="'.$this->getid().'" type="text" name="a_n_c_imobilizado" value="'.$this->geta_n_c_imobilizado().'" placeholder="" >';
		}
		function gerarInputa_n_c_intangivel(){
			return '<input class="input_tabela currency input_valor a_n_c_intangivel editar_item_balanco" tabela="balanco_patrimonial" campo="a_n_c_intangivel" id="'.$this->getid().'" type="text" name="a_n_c_intangivel" value="'.$this->geta_n_c_intangivel().'" placeholder="" >';
		}
		function gerarInputa_n_c_depreciacao(){
			return '<input class="input_tabela currency input_valor a_n_c_depreciacao editar_item_balanco" tabela="balanco_patrimonial" campo="a_n_c_depreciacao" id="'.$this->getid().'" type="text" name="a_n_c_depreciacao" value="'.$this->geta_n_c_depreciacao().'" placeholder="" >';
		}
		function gerarInputp_c_fornecedores(){
			return '<input class="input_tabela currency input_valor p_c_fornecedores editar_item_balanco" tabela="balanco_patrimonial" campo="p_c_fornecedores" id="'.$this->getid().'" type="text" name="p_c_fornecedores" value="'.$this->getp_c_fornecedores().'" placeholder="">';
		}
		function gerarInputp_c_emprestimos_bancarios(){
			return '<input class="input_tabela currency input_valor p_c_emprestimos_bancarios editar_item_balanco" tabela="balanco_patrimonial" campo="p_c_emprestimos_bancarios" id="'.$this->getid().'" type="text" name="p_c_emprestimos_bancarios" value="'.$this->getp_c_emprestimos_bancarios().'" >';
		}
		function gerarInputp_c_obrigacoes_sociais_impostos(){
			return '<input class="input_tabela currency input_valor p_c_obrigacoes_sociais_impostos editar_item_balanco" tabela="balanco_patrimonial" campo="p_c_obrigacoes_sociais_impostos" id="'.$this->getid().'" type="text" name="p_c_obrigacoes_sociais_impostos" value="'.$this->getp_c_obrigacoes_sociais_impostos().'" placeholder="">';
		}
		function gerarInputp_c_contas_pagar(){
			return '<input class="input_tabela currency input_valor p_c_contas_pagar editar_item_balanco" tabela="balanco_patrimonial" campo="p_c_contas_pagar" id="'.$this->getid().'" type="text" name="p_c_contas_pagar" value="'.$this->getp_c_contas_pagar().'" placeholder="">';
		}
		function gerarInputp_c_lucros_distribuir(){
			return '<input class="input_tabela currency input_valor p_c_lucros_distribuir editar_item_balanco" tabela="balanco_patrimonial" campo="p_c_lucros_distribuir" id="'.$this->getid().'" type="text" name="p_c_lucros_distribuir" value="'.$this->getp_c_lucros_distribuir().'" placeholder="">';
		}
		function gerarInputp_c_provisoes(){
			return '<input class="input_tabela currency input_valor p_c_provisoes editar_item_balanco" tabela="balanco_patrimonial" campo="p_c_provisoes" id="'.$this->getid().'" type="text" name="p_c_provisoes" value="'.$this->getp_c_provisoes().'" placeholder="">';
		}
		function gerarInputp_n_c_contas_pagar(){
			return '<input class="input_tabela currency input_valor p_n_c_contas_pagar editar_item_balanco" tabela="balanco_patrimonial" campo="p_n_c_contas_pagar" id="'.$this->getid().'" type="text" name="p_n_c_contas_pagar" value="'.$this->getp_n_c_contas_pagar().'" placeholder="">';
		}
		function gerarInputp_n_c_financiamentos_bancarios(){
			return '<input class="input_tabela currency input_valor p_n_c_financiamentos_bancarios editar_item_balanco" tabela="balanco_patrimonial" campo="p_n_c_financiamentos_bancarios" id="'.$this->getid().'" type="text" name="p_n_c_financiamentos_bancarios" value="'.$this->getp_n_c_financiamentos_bancarios().'" placeholder="">';
		}
		function gerarInputp_l_capital_social(){
			return '<input class="input_tabela currency input_valor p_l_capital_social editar_item_balanco" tabela="balanco_patrimonial" campo="p_l_capital_social" id="'.$this->getid().'" type="text" name="p_l_capital_social" value="'.$this->getp_l_capital_social().'" placeholder="">';
		}
		function gerarInputp_l_reservas_capital(){
			return '<input class="input_tabela currency input_valor p_l_reservas_capital editar_item_balanco" tabela="balanco_patrimonial" campo="p_l_reservas_capital" id="'.$this->getid().'" type="text" name="p_l_reservas_capital" value="'.$this->getp_l_reservas_capital().'" placeholder="">';
		}
		function gerarInputp_l_ajustes_avaliacao_patrimonial(){
			return '<input class="input_tabela currency input_valor p_l_ajustes_avaliacao_patrimonial editar_item_balanco" tabela="balanco_patrimonial" campo="p_l_ajustes_avaliacao_patrimonial" id="'.$this->getid().'" type="text" name="p_l_ajustes_avaliacao_patrimonial" value="'.$this->getp_l_ajustes_avaliacao_patrimonial().'" placeholder="">';
		}
		function gerarInputp_l_reservas_lucro(){
			return '<input class="input_tabela currency input_valor p_l_reservas_lucro editar_item_balanco" tabela="balanco_patrimonial" campo="p_l_reservas_lucro" id="'.$this->getid().'" type="text" name="p_l_reservas_lucro" value="'.$this->getp_l_reservas_lucro().'" placeholder="">';
		}
		
		function gerarBalloncontingencias($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="contingencias" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_c_caixa_equivalente_caixa($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_caixa_equivalente_caixa" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_c_contas_receber($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_contas_receber" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_c_estoques($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_estoques" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_c_outros_creditos($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_outros_creditos" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_c_despesas_exercicio_seguinte($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_c_despesas_exercicio_seguinte" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_n_c_contas_receber($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_contas_receber" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_n_c_investimentos($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_investimentos" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_n_c_imobilizado($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_imobilizado" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_n_c_intangivel($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_intangivel" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallona_n_c_depreciacao($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="a_n_c_depreciacao" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_c_fornecedores($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_fornecedores" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_c_emprestimos_bancarios($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_emprestimos_bancarios" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_c_obrigacoes_sociais_impostos($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_obrigacoes_sociais_impostos" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_c_contas_pagar($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_contas_pagar" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_c_lucros_distribuir($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_lucros_distribuir" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_c_provisoes($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_c_provisoes" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_n_c_contas_pagar($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_n_c_contas_pagar" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_n_c_financiamentos_bancarios($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_n_c_financiamentos_bancarios" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_l_capital_social($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_capital_social" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_l_reservas_capital($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_reservas_capital" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_l_ajustes_avaliacao_patrimonial($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_ajustes_avaliacao_patrimonial" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_l_reservas_lucro($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_reservas_lucro" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_l_lucros_acumulados($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_lucros_acumulados" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarBallonp_l_prejuizos_acumulados($string){				
			return '

				<div style="width:310px; position:absolute; display:none;" id="p_l_prejuizos_acumulados" class="bubble_left box_visualizacao x_visualizacao">
					<div style="padding:20px;min-height:45px;">
				       '.$string.'
				    </div>
				</div>

			';
		}
		function gerarImagemBallona_c_caixa_equivalente_caixa(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_caixa_equivalente_caixa" style="cursor: pointer;">';
		}
		function gerarImagemBallona_c_contas_receber(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_contas_receber" style="cursor: pointer;">';
		}
		function gerarImagemBallona_c_estoques(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_estoques" style="cursor: pointer;">';
		}
		function gerarImagemBallona_c_outros_creditos(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_outros_creditos" style="cursor: pointer;">';
		}
		function gerarImagemBallona_c_despesas_exercicio_seguinte(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_c_despesas_exercicio_seguinte" style="cursor: pointer;">';
		}
		function gerarImagemBallona_n_c_contas_receber(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_n_c_contas_receber" style="cursor: pointer;">';
		}
		function gerarImagemBallona_n_c_investimentos(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_n_c_investimentos" style="cursor: pointer;">';
		}
		function gerarImagemBallona_n_c_imobilizado(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:4px;" width="13" height="14" border="0" align="texttop" div="a_n_c_imobilizado" style="cursor: pointer;">';
		}
		function gerarImagemBallona_n_c_intangivel(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:4px;" width="13" height="14" border="0" align="texttop" div="a_n_c_intangivel" style="cursor: pointer;">';
		}
		function gerarImagemBallona_n_c_depreciacao(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="a_n_c_depreciacao" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_c_fornecedores(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_fornecedores" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_c_emprestimos_bancarios(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:4px;" width="13" height="14" border="0" align="texttop" div="p_c_emprestimos_bancarios" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_c_obrigacoes_sociais_impostos(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_obrigacoes_sociais_impostos" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_c_contas_pagar(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_contas_pagar" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_c_lucros_distribuir(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_lucros_distribuir" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_c_provisoes(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_c_provisoes" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_n_c_contas_pagar(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_n_c_contas_pagar" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_n_c_financiamentos_bancarios(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:4px;" width="13" height="14" border="0" align="texttop" div="p_n_c_financiamentos_bancarios" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_l_capital_social(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_capital_social" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_l_reservas_capital(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_reservas_capital" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_l_ajustes_avaliacao_patrimonial(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_ajustes_avaliacao_patrimonial" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_l_reservas_lucro(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_reservas_lucro" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_l_lucros_acumulados(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_lucros_acumulados" style="cursor: pointer;">';
		}
		function gerarImagemBallonp_l_prejuizos_acumulados(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="p_l_prejuizos_acumulados" style="cursor: pointer;">';
		}
		function gerarImagemBalloncontingencias(){
			return '<img class="imagemDica" src="images/dica.gif" style="margin-left:5px;margin-top:2px;" width="13" height="14" border="0" align="texttop" div="contingencias" style="cursor: pointer;">';
		}
		function getcaixa_final(){
			return number_format($this->caixa_final,2,',','.');
		}
		function setcaixa_final($string){
			$this->caixa_final = $string;
		}
		function getid(){
			return $this->id;
		}
		function setid($string){
			$this->id = $string;
		}
		function getid_user(){
			return $this->id_user;
		}
		function setid_user($string){
			$this->id_user = $string;
		}
		function getano(){
			return $this->ano;
		}
		function setano($string){
			$this->ano = $string;
		}
		function geta_c_caixa_equivalente_caixa(){
			return number_format($this->a_c_caixa_equivalente_caixa,2,',','.');
		}
		function seta_c_caixa_equivalente_caixa($string){
			$this->a_c_caixa_equivalente_caixa = $string;
		}
		function geta_c_contas_receber(){
			return number_format($this->a_c_contas_receber,2,',','.');
		}
		function seta_c_contas_receber($string){
			$this->a_c_contas_receber = $string;
		}
		function geta_c_estoques(){
			return number_format($this->a_c_estoques,2,',','.');
		}
		function seta_c_estoques($string){
			$this->a_c_estoques = $string;
		}
		function geta_c_outros_creditos(){
			return number_format($this->a_c_outros_creditos,2,',','.');
		}
		function seta_c_outros_creditos($string){
			$this->a_c_outros_creditos = $string;
		}
		function geta_c_despesas_exercicio_seguinte(){
			return number_format($this->a_c_despesas_exercicio_seguinte,2,',','.');
		}
		function seta_c_despesas_exercicio_seguinte($string){
			$this->a_c_despesas_exercicio_seguinte = $string;
		}
		function geta_n_c_contas_receber(){
			return number_format($this->a_n_c_contas_receber,2,',','.');
		}
		function seta_n_c_contas_receber($string){
			$this->a_n_c_contas_receber = $string;
		}
		function geta_n_c_investimentos(){
			return number_format($this->a_n_c_investimentos,2,',','.');
		}
		function seta_n_c_investimentos($string){
			$this->a_n_c_investimentos = $string;
		}
		function geta_n_c_imobilizado(){
			return number_format($this->a_n_c_imobilizado,2,',','.');
		}
		function seta_n_c_imobilizado($string){
			$this->a_n_c_imobilizado = $string;
		}
		function geta_n_c_intangivel(){
			return number_format($this->a_n_c_intangivel,2,',','.');
		}
		function seta_n_c_intangivel($string){
			$this->a_n_c_intangivel = $string;
		}
		function geta_n_c_depreciacao(){
			return number_format($this->a_n_c_depreciacao,2,',','.');
		}
		function seta_n_c_depreciacao($string){
			$this->a_n_c_depreciacao = $string;
		}
		function getp_c_fornecedores(){
			return number_format($this->p_c_fornecedores,2,',','.');
		}
		function setp_c_fornecedores($string){
			$this->p_c_fornecedores = $string;
		}
		function getp_c_emprestimos_bancarios(){
			return number_format($this->p_c_emprestimos_bancarios,2,',','.');
		}
		function setp_c_emprestimos_bancarios($string){
			$this->p_c_emprestimos_bancarios = $string;
		}
		function getp_c_obrigacoes_sociais_impostos(){
			return number_format($this->p_c_obrigacoes_sociais_impostos,2,',','.');
		}
		function setp_c_obrigacoes_sociais_impostos($string){
			$this->p_c_obrigacoes_sociais_impostos = $string;
		}
		function getp_c_contas_pagar(){
			return number_format($this->p_c_contas_pagar,2,',','.');
		}
		function setp_c_contas_pagar($string){
			$this->p_c_contas_pagar = $string;
		}
		function getp_c_lucros_distribuir(){
			return number_format($this->p_c_lucros_distribuir,2,',','.');
		}
		function setp_c_lucros_distribuir($string){
			$this->p_c_lucros_distribuir = $string;
		}
		function getp_c_provisoes(){
			return number_format($this->p_c_provisoes,2,',','.');
		}
		function setp_c_provisoes($string){
			$this->p_c_provisoes = $string;
		}
		function getp_n_c_contas_pagar(){
			return number_format($this->p_n_c_contas_pagar,2,',','.');
		}
		function setp_n_c_contas_pagar($string){
			$this->p_n_c_contas_pagar = $string;
		}
		function getp_n_c_financiamentos_bancarios(){
			return number_format($this->p_n_c_financiamentos_bancarios,2,',','.');
		}
		function setp_n_c_financiamentos_bancarios($string){
			$this->p_n_c_financiamentos_bancarios = $string;
		}
		function getp_l_capital_social(){
			return number_format($this->p_l_capital_social,2,',','.');
		}
		function setp_l_capital_social($string){
			$this->p_l_capital_social = $string;
		}
		function getp_l_reservas_capital(){
			return number_format($this->p_l_reservas_capital,2,',','.');
		}
		function setp_l_reservas_capital($string){
			$this->p_l_reservas_capital = $string;
		}
		function getp_l_ajustes_avaliacao_patrimonial(){
			return number_format($this->p_l_ajustes_avaliacao_patrimonial,2,',','.');
		}
		function setp_l_ajustes_avaliacao_patrimonial($string){
			$this->p_l_ajustes_avaliacao_patrimonial = $string;
		}
		function getp_l_reservas_lucro(){
			return number_format($this->p_l_reservas_lucro,2,',','.');
		}
		function setp_l_reservas_lucro($string){
			$this->p_l_reservas_lucro = $string;
		}
		function getp_l_lucros_acumulados(){
			return number_format($this->p_l_lucros_acumulados,2,',','.');
		}
		function setp_l_lucros_acumulados($string){
			$this->p_l_lucros_acumulados = $string;
		}
		function getp_l_prejuizos_acumulados(){
			return number_format($this->p_l_prejuizos_acumulados,2,',','.');
		}
		function setp_l_prejuizos_acumulados($string){
			$this->p_l_prejuizos_acumulados = $string;
		}
	}

?>