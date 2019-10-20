<?php
	class Funcoes {
		# Variáveis

		# Menu
		public function gerarMenu($paginas,$url_painel){
			$menu_pronto = '<ul id="main-menu" class="">';
			if(isset($_GET['m']))
            	$menu_pronto .= '<li><a href="'.$url_painel.'" class="open"><i class="entypo-layout"></i><span>Principal</span></a></li>';
            else
            	$menu_pronto .= '<li class="opened active"><a href="'.$url_painel.'" class="open"><i class="entypo-layout"></i><span>Principal</span></a></li>';
            foreach($paginas->paginas_menu as $pagina):
            	if(is_array($pagina)):
            		$class = "";
            		if(isset($_GET['m'])):
            			if(in_array($_GET['m'],$pagina)):
            				$class = "active opened";
            			endif;
            		endif;
            		$menu_pronto .= '<li class="'.$class.'"><a href="#"><i class="entypo-123"></i><span>'.$pagina[0].'</span></a>';
            		$menu_pronto .= '<ul>';
            		foreach ($pagina as $pagina_submenu):
            			if(is_array($pagina_submenu)):
			            	$menu_pronto .= '<li><a href="#"><i class="entypo-123"></i><span>'.$pagina_submenu[0].'</span></a>';
					        $menu_pronto .= '<ul>';
        					foreach ($pagina_submenu as $pagina_sub_submenu):
	            				if($pagina_sub_submenu <> $pagina_submenu[0]):
		            				$pagina_atual = new Paginas($pagina_sub_submenu);
					            	$menu_pronto .= '<li><a href="#"><i class="entypo-123"></i><span>'.$pagina_atual->titulo.'</span></a>';
					        		$menu_pronto .= '<ul>';
					                if(isset($pagina_atual->operacoes)):
					                    foreach($pagina_atual->operacoes as $operacao_atual):
					                    	if($operacao_atual == 'listar') $icone = '<i class="entypo-list"></i>';
					                    	if($operacao_atual == 'inserir') $icone = '<i class="entypo-list-add"></i>';
					                        if($operacao_atual <> 'editar' && $operacao_atual <> 'deletar' && $operacao_atual <> 'mail')
					                            $menu_pronto .= '<li><a href="?m='.$pagina_atual->tabela.'&t='.$operacao_atual.'">'.$icone.ucfirst($operacao_atual).' '.$pagina_atual->titulo.'</a></li>';
					                    endforeach;
					                endif;
					                $menu_pronto .= '</ul>';
					        	endif;
				                $menu_pronto .= '</li>';
					        endforeach;
						    $menu_pronto .= '</ul>';
						    $menu_pronto .= '</li>';
	            		else:
	            			if($pagina_submenu <> $pagina[0]):
	            				$pagina_atual = new Paginas($pagina_submenu);
				            	$menu_pronto .= '<li><a href="#"><i class="entypo-123"></i><span>'.$pagina_atual->titulo.'</span></a>';
				                if(isset($pagina_atual->operacoes)):
				                    $menu_pronto .= '<ul>';
				                    foreach($pagina_atual->operacoes as $operacao_atual):
				                    	if($operacao_atual == 'listar') $icone = '<i class="entypo-list"></i>';
				                    	if($operacao_atual == 'inserir') $icone = '<i class="entypo-list-add"></i>';

				                        if($operacao_atual <> 'editar' && $operacao_atual <> 'deletar' && $operacao_atual <> 'mail')
				                            $menu_pronto .= '<li><a href="?m='.$pagina_atual->tabela.'&t='.$operacao_atual.'">'.$icone.ucfirst($operacao_atual).' '.$pagina_atual->titulo.'</a></li>';
				                    endforeach;
				                    $menu_pronto .= '</ul>';
				                endif;
				                $menu_pronto .= '</li>';
	            			endif;
	            		endif;
            		endforeach;
            		$menu_pronto .= '</ul>';
            		$menu_pronto .= '</li>';
            	else:
            		$class = "";
            		if(isset($_GET['m'])):
            			if($_GET['m'] == $pagina):
            				$class = "active opened";
            			endif;
            		endif;
	                $pagina_atual = new Paginas($pagina);
	            	$menu_pronto .= '<li class="'.$class.'"><a href="#"><i class="entypo-123"></i><span>'.$pagina_atual->titulo.'</span></a>';
	                if(isset($pagina_atual->operacoes)):
	                    $menu_pronto .= '<ul>';
	                    foreach($pagina_atual->operacoes as $operacao_atual):
	                    	if($operacao_atual == 'listar') $icone = '<i class="entypo-list"></i>';
	                    	if($operacao_atual == 'inserir') $icone = '<i class="entypo-list-add"></i>';

	                        if($operacao_atual <> 'editar' && $operacao_atual <> 'deletar' && $operacao_atual <> 'mail')
	                            $menu_pronto .= '<li><a href="?m='.$pagina_atual->tabela.'&t='.$operacao_atual.'">'.$icone.ucfirst($operacao_atual).' '.$pagina_atual->titulo.'</a></li>';
	                    endforeach;
	                    $menu_pronto .= '</ul>';
	                endif;
	                $menu_pronto .= '</li>';
	            endif;
            endforeach;
            $menu_pronto .= '</ul>';
            return $menu_pronto;
        }

		# Funções
		public function gerarLista($campos,$campoID,$tabela,$modulo,$camposPagina){
			$tabela_gerada = '<div class="row"><div class="col-md-12">'; // Tema
			$tabela_gerada .= '<table class="table table-bordered datatable" id="lista-dados">';
			$tabela_gerada .= '<thead><tr>';
			foreach($campos as $campos_h => $campo):
				if(is_array($campo)):
					$tabela_gerada .= '<th>Ação</th>';
				else:
					$tabela_gerada .= '<th>'.ucwords(str_replace("_"," ",$campo)).'</th>';
				endif;
			endforeach;
			$tabela_gerada .= '</tr></thead>';
			$tabela_gerada .= '<tbody>';
			$sql = mysql_query("SELECT * FROM $tabela ORDER BY $campoID DESC");
			while($row = mysql_fetch_array($sql)):
				$tabela_gerada .= '<tr class="odd gradeX">';
				foreach($campos as $campos_b => $campo_b):
					if(is_array($campo_b)):
						$tabela_gerada .= '<td class="center">';
						switch($campos_b):
							case 'acao':
								foreach ($campo_b as $campos_a => $campo_a):
									if($campo_a == 'editar'):
										$tabela_gerada .= '<a href="?m='.$modulo.'&t=editar&id='.$row[$campoID].'" title="Editar" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Editar</a>';
									elseif($campo_a == 'deletar'):
										$tabela_gerada .= '<a href="?m='.$modulo.'&t=deletar&id='.$row[$campoID].'" title="Deletar" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Deletar</a>';
									elseif($campo_a == 'download'):
										$tabela_gerada .= '<a href="../assets/curriculos/'.$row['arquivo'].'" download target="_blank" title="Download" class="btn btn-blue btn-sm btn-icon icon-left"><i class="entypo-print"></i>Download</a>';
									elseif($campo_a == 'mail'):
										$tabela_gerada .= '<a href="mailto:'.$row['email'].'" title="Enviar E-mail" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-mail"></i>Enviar E-mail</a>';
									endif;
								endforeach;
							break;
							default:
								$query_c = "SELECT ".$campo_b[1]." FROM ".$campo_b[0]." WHERE ".$campo_b[2]." = ".$row[$campos_b]."";
								//echo $query_c.'<br><br>';
								$sql_c = mysql_query($query_c);
								$row_c = mysql_fetch_array($sql_c);
								$tabela_gerada .= $row_c[$campo_b[1]];
							break;
						endswitch;
						$tabela_gerada .= '</td>';
					else:
						if(isset($camposPagina[$campo_b][1]) && $camposPagina[$campo_b][1] == 'upload-img'):
							if($row[$campo_b] <> ''):
								$tabela_gerada .= '<td style="text-align:center;"><img src="'.$camposPagina[$campo_b][2].$row[$campo_b].'" height="40" title="'.$camposPagina[$campo_b][2].$row[$campo_b].'"></td>';
							else:
								$tabela_gerada .= '<td>[Sem imagem]</td>';
							endif;
						elseif(isset($camposPagina[$campo_b][1]) && $camposPagina[$campo_b][1] == 'date'):
							$tabela_gerada .= '<td>'.$this->desconverteData($row[$campo_b]).'</td>';
						else:
							$tabela_gerada .= '<td>'.$this->trataTexto($row[$campo_b]).'</td>';
						endif;
					endif;
				endforeach;
				$tabela_gerada .= '</tr>';
			endwhile;
			$tabela_gerada .= '</tbody>';
			$tabela_gerada .= '<tfoot><tr>';
			foreach($campos as $campos_h => $campo):
				if(is_array($campo)):
					$tabela_gerada .= '<th>Ação</th>';
				else:
					$tabela_gerada .= '<th>'.ucfirst($campo).'</th>';
				endif;
			endforeach;
			$tabela_gerada .= '</tr></tfoot>';
			$tabela_gerada .= '</table>';
			$tabela_gerada .= '</div></div>'; // Tema

			return $tabela_gerada;
		}

		public function deletarRegistro($dados,$id,$banco){
			$objeto = new stdClass();
			$objeto->tabela = $dados['tabela_form'];
			$objeto->campoID = $id;
			$objeto->valorID = $dados['id_form'];
			$banco->delete($objeto);
			return $banco->affected_rows;
		}

		public function gerarFormDeletar($id,$tabela,$campo,$acao,$operacoes){
			if(in_array($acao,$operacoes)){
				$formulario = '<form name="formulario" action="?'.$_SERVER['QUERY_STRING'].'" method="post" accept-charset="utf-8" enctype="multipart/form-data" role="form" class="form-horizontal form-groups-bordered">';
				$formulario .= '<div class="alert alert-minimal">Deseja excluir este item?</div>';
				$formulario	.= '<input type="hidden" name="acao_form" value="deletar" />';
				$formulario	.= '<input type="hidden" name="tabela_form" value="'.$tabela.'" />';
				$formulario	.= '<input type="hidden" name="id_form" value="'.$id.'" />';
				$formulario .= '</form>';
				$formulario .= $this->gerarBotoes('deletar');
			}else{
				$formulario = 'Tela não existente.';
			}
			return $formulario;
		}

		public function gerarForm($campos,$db,$acao,$operacoes,$tabela,$campoID){
			if(in_array($acao,$operacoes)){
				if($acao == 'inserir') $db = null;
				if($acao == 'editar' && $db == null){
					$formulario = $this->printMSG("Nenhum registro foi selecionado. <a href='?m=".$tabela."&t=listar'><strong>Selecione um registro</strong>.</a>","sucesso");
				}else{
					$formulario = '<form name="formulario" action="?'.$_SERVER['QUERY_STRING'].'" method="post" accept-charset="utf-8" enctype="multipart/form-data" role="form" class="form-horizontal form-groups-bordered">';
					foreach($campos as $campos => $campo):
						$formulario .= $this->gerarCampos($campos,$campo,$db);
					endforeach;
					$formulario	.= '<input type="hidden" name="acao_form" value="'.$acao.'" />';
					$formulario	.= '<input type="hidden" name="tabela_form" value="'.$tabela.'" />';
					$formulario	.= '<input type="hidden" name="id_form" value="'.$db[$campoID].'" />';
					$formulario .= '</form>';
					$formulario .= $this->gerarBotoes($acao);
				}
			}else{
				$formulario = 'Tela não existente.';
			}
			return $formulario;
		}

		public function popularCampos($modulo,$campoID,$id){
			$sql = mysql_query("SELECT * FROM $modulo WHERE $campoID = '$id'");
			$row = mysql_fetch_array($sql);
			//$retorno = $modulo.' - '.$campoID.' - '.$id;
			return $row;
		}

		public function gerarCampos($campos,$campo,$db){
			if(isset($campo['dica']) && $campo['dica'] <> '') $dica = ' data-dica="'.$campo['dica'].'"'; else $dica = '';
			if(isset($campo['sufixo']) && $campo['sufixo'] <> '') $sufixo = ' data-sufixo="'.$campo['sufixo'].'"'; else $sufixo = '';
			$retorno = '<div class="form-group">';
			$retorno .= '<label for="field-1" class="col-sm-3 control-label">'.$campo[0].'</label><div class="col-sm-6">';
			switch($campo[1]):
				default:
					$retorno .= 'Você informou um formato errado.';
				break;
				case 'input':
					if($campo[4] == 'number')
						$retorno .= '<input '.$dica.$sufixo.' onkeypress="return isNumberKey(event)" class="form-control" name="'.$campos.'" maxlength="'.$campo[2].'" placeholder="'.$campo[3].'" value="'.$db[$campos].'" type="text">';
					else
						$retorno .= '<input '.$dica.$sufixo.' class="form-control '.$campo[5].'" name="'.$campos.'" maxlength="'.$campo[2].'" placeholder="'.$campo[3].'" value="'.$db[$campos].'" type="'.$campo[4].'">';
				break;
				case 'input-preco':
					$retorno .= '<input '.$dica.$sufixo.' class="form-control preco" name="'.$campos.'" maxlength="'.$campo[2].'" placeholder="'.$campo[3].'" value="'.$db[$campos].'" type="'.$campo[4].'">';
				break;
				case 'textarea':
					$retorno .= '<textarea '.$dica.$sufixo.' class="form-control wysihtml5" name="'.$campos.'" maxlength="'.$campo[2].'" placeholder="'.$campo[3].'">'.$db[$campos].'</textarea>';
				break;
				case 'select':				
					if($campo[5]):
						$retorno .= '<select '.$dica.$sufixo.' multiple="multiple" name="'.$campos.'[]" class="form-control multi-select">';
					else:
						$retorno .= '<select '.$dica.$sufixo.' name="'.$campos.'" class="form-control">';
					endif;
						if(is_array($campo[2])):
							$index = 0;
							foreach($campo[2] as $titulo => $valor):
								$retorno .= '<optgroup label="'.$titulo.'">';
								$sql = mysql_query("SELECT * FROM $valor");
								while($row = mysql_fetch_array($sql)):
									if($campo[6]):
										if($titulo.'-'.$row[$campo[3][$index]] == $db[$campos]) $select = "selected"; else $select = "";
										$retorno .= '<option value="'.$titulo.'-'.$row[$campo[3][$index]].'" '.$select.'>'.$this->trataTexto($row[$campo[4][$index]],60).' (#'.$row[$campo[3][$index]].')</option>';
									else:
										if($row[$campo[3][$index]] == $db[$campos]) $select = "selected"; else $select = "";
										$retorno .= '<option value="'.$row[$campo[3][$index]].'" '.$select.'>'.$this->trataTexto($row[$campo[4][$index]],60).'</option>';
									endif;
								endwhile;
								$retorno .= '</optgroup>';
								$index = $index + 1;
							endforeach;
						else:
							$sql = mysql_query("SELECT * FROM $campo[2]");
							while($row = mysql_fetch_array($sql)):
								if($row[$campo[3]] == $db[$campos]) $select = "selected"; else $select = "";
								$retorno .= '<option value="'.$row[$campo[3]].'" '.$select.'>'.$this->trataTexto($row[$campo[4]],60).'</option>';
							endwhile;
						endif;
					$retorno .= '</select>';
				break;
				case 'upload-img':
					if($db[$campos] == '' || isset($_GET[$campos])){
						$retorno .= '<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="input-group">
											<div class="form-control uneditable-input" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span class="fileinput-filename"></span>
											</div>
											<span class="input-group-addon btn btn-default btn-file">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Trocar</span>';
						$retorno .= '<input class="form-control" type="file" name="'.$campos.'" accept="image/x-png, image/gif, image/jpeg, image/png, image/jpg">';
						$retorno .= '		</span>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
										</div>
									</div>';
						if($campo[3] && $campo[4]):
							$retorno .= '<span class="img-size">'.$campo[3].'px X '.$campo[4].'px</span>';
						endif;
					}else{
						$retorno .= '<img src="'.$campo[2].$db[$campos].'" class="imagem_form" alt="">';
						$retorno .= '<a href="?'.$_SERVER['QUERY_STRING'].'&'.$campos.'=false">Remover</a>';
					}
				break;
				case 'upload-file':
					if($db[$campos] == '' || isset($_GET[$campos])){
						$retorno .= '<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="input-group">
											<div class="form-control uneditable-input" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span class="fileinput-filename"></span>
											</div>
											<span class="input-group-addon btn btn-default btn-file">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Trocar</span>';
						$retorno .= '<input class="form-control" type="file" name="'.$campos.'">';
						$retorno .= '		</span>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
										</div>
									</div>';
					}else{
						$array_nome = explode("/",$db[$campos]);
						$index_nome = key(array_slice($array_nome, -1, 1,TRUE));
						$nome_arquivo = $array_nome[$index_nome];
						$retorno .= 'Arquivo: <strong>'.$nome_arquivo.'</strong>';
						$retorno .= '<a href="?'.$_SERVER['QUERY_STRING'].'&'.$campos.'=false">Remover</a>';
					}
				break;
				case 'checkbox':
					foreach($campo[2] as $value => $option){
						if($value <> '')
							$retorno .= '<div class="checkbox"><label><input '.$dica.$sufixo.' type="checkbox" name="'.$campos.'[]" value="'.$value.'">'.$option.'</label></div>';
					}
				break;
				case 'radio':
					$o = 0;
					foreach($campo[2] as $value => $option){
						if($value == $db[$campos]) $check = 'checked'; else $check = '';
						if(empty($db[$campos])):
							$o = $o+1;
							if($o == 1) $check = 'checked';
						endif;
						if($value <> '')
							$retorno .= '<div class="radio"><label><input  '.$dica.$sufixo.'type="radio" name="'.$campos.'" value="'.$value.'" '.$check.'>'.$option.'</label></div>';
					}
				break;
				case 'date':
					$retorno .= '<input '.$dica.$sufixo.' class="data form-control datepicker" data-format="dd/mm/yyyy" name="'.$campos.'" placeholder="'.$campo[2].'" value="'.$this->desconverteData($db[$campos]).'" type="text">';
				break;
			endswitch;
			$retorno .= '</div>';
			$retorno .= '</div>';
			return $retorno;
		}

		public function trataCampo($campo,$valor){
			$retorno = "";
			if(is_numeric($valor))
				$retorno .= $campo." = ".$valor.",";
			else
				$retorno .= $campo." = '".$valor."',";
			return $retorno;
		}

		public function reajustaImagem($pasta,$nome_imagem,$caminho_imagem, $large_width, $large_height,$thumb=false){
			$array_nome = explode(".",$caminho_imagem);
			$ext = $array_nome[key(array_slice($array_nome, -1, 1,TRUE))];

			if($ext == 'png') $image = imagecreatefrompng($caminho_imagem);
			else $image = imagecreatefromjpeg($caminho_imagem);
			$rename1 = explode(".",$nome_imagem);
			if($thumb)
				$filename = $pasta.$rename1[0].'_thumb.'.$rename1[1];
			else
				$filename = $pasta.$rename1[0].'.'.$rename1[1];
					
			if(!isset($large_width)) $large_width = 9000;
			if(!isset($large_height)) $large_height = 9000;
			
			$width = imagesx($image);
			$height = imagesy($image);
			
			$original_aspect = $width / $height;
			$large_aspect = $large_width / $large_height;
			
			if ( $original_aspect >= $large_aspect ){
			   $new_height = $large_height;
			   $new_width = $width / ($height / $large_height);
			}else{
			   $new_width = $large_width;
			   $new_height = $height / ($width / $large_width);
			}
			
			$large = imagecreatetruecolor( $large_width, $large_height );
			
			if($ext == 'png'){
				imagealphablending($large , false);
				imagesavealpha($large , true);
			}
			imagecopyresampled($large, $image, 0 - ($new_width - $large_width) / 2, 0 - ($new_height - $large_height) / 2, 0, 0, $new_width, $new_height, $width, $height);
			imagejpeg($large, $filename, 100);
			
			if($ext == 'png'){
				imagealphablending($large , false);
				imagesavealpha($large , true);
				imagepng($large, $filename, 9);
			}
			$large_name = $rename1[0].'.'.$rename1[1];
			return $large_name;
		}

		public function uploadImagem($arquivo,$pasta,$width,$height){
			if (!empty($arquivo["name"])){ 
				$image = WideImage::load($arquivo["tmp_name"]);
				
				$qualidade_compressao = 70;

				preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);
				$nome_imagem = md5(uniqid(time())) . "." . $ext[1];
				$caminho_imagem = $pasta.$nome_imagem;

				if($width || $height):
					$image = $image->resize($width,$height,"inside");
				endif;

				if($ext[1] == 'png'){
					$qualidade_compressao = intval($qualidade_compressao /10);
				}

				$image->saveToFile($caminho_imagem,$qualidade_compressao);
				$image->destroy();
				return $nome_imagem;

				

			}
		}

		public function uploadThumb($arquivo,$pasta,$width,$height){
			$thumb_redim = $this->reajustaImagem($pasta,$arquivo,$pasta.$arquivo,$width,$height,true);
			$rename1 = explode(".",$thumb_redim);
			$thumb_redim = $rename1[0].'_thumb.'.$rename1[1];
			return $thumb_redim;
		}

		public function uploadArquivo($arquivo,$pasta){
			if (!empty($arquivo["name"])){ 
				$file_name= $arquivo["name"];
				$file_name = $this->strip_special_chars($file_name);
				move_uploaded_file($arquivo["tmp_name"], $pasta.$file_name);
				return $file_name;
			}
		}

		public function converterData($data){
			if($data <> ''){
				$dataC = explode("/",$data);
				return $dataC[2].'-'.$dataC[1].'-'.$dataC[0];
			}else{
				return '';
			}
		}

		public function desconverteData($data){
			if($data <> ''){
				$dataC = explode("-",$data);
				return $dataC[2].'/'.$dataC[1].'/'.$dataC[0];
			}else{
				return '';
			}
		}

		public function salvarDados($campos,$dados,$arquivos,$id,$db,$acao,$banco,$tabela){
			$objeto = new stdClass();
			$objeto->tabela = $tabela;
			$objeto->campoID = $id;
			$objeto->valorID = $db[$id];
			$objeto->valor_campos = array();
			$dados_diferentes = "";
			foreach ($campos as $campos => $campo):
				if($campo[1] == 'upload-img' || $campo[1] == 'upload-file'):
					if(isset($arquivos[$campos])):
						if($arquivos[$campos]['name'] == $db[$campos]):
							// Igual
						else:
							if(isset($arquivos[$campos])):
								if($campo[1] == 'upload-img'):
									if(isset($campo['thumb'])):
										$valor = $this->uploadImagem($arquivos[$campos], $campo[2], $campo[3], $campo[4]);
										$valor_thumb = $this->uploadThumb($valor, $campo['thumb'][2], $campo['thumb'][0], $campo['thumb'][1]);

										$objeto->valor_campos[$campo['thumb'][3]] = $valor_thumb;
									else:
										$valor = $this->uploadImagem($arquivos[$campos], $campo[2], $campo[3], $campo[4]);
									endif;
								else:
									$valor = $this->uploadArquivo($arquivos[$campos], $campo[2]);
								endif;
								//$valor = $arquivos[$campos]['name'];
								$objeto->valor_campos[$campos] = $valor;
							endif;
						endif;
					elseif(isset($dados[$campos])):
						if($dados[$campos] == $db[$campos]):
							// Igual
						else:
							if(isset($dados[$campos])):
								$valor = $dados[$campos];
								$objeto->valor_campos[$campos] = $valor;
							endif;
						endif;
					else:
						if(isset($dados[$campos])):
							$valor = $dados[$campos];
							$objeto->valor_campos[$campos] = $valor;
						endif;
					endif;
				elseif(isset($dados[$campos])):
					if($dados[$campos] <> $db[$campos]):
						if(isset($dados[$campos])):
							$valor = $dados[$campos];
							if($campo[1] == 'date'){
								$valor = $this->converterData($valor);
							}
							$objeto->valor_campos[$campos] = $valor;
						endif;
					else:
						// Igual
					endif;
				else:
					// Igual
				endif;
			endforeach;
			# echo '<pre>';
			# var_dump($objeto->valor_campos);
			# echo '</pre>';
			if(!empty($objeto->valor_campos)):
				switch($acao):
					case 'editar':
						$banco->update($objeto);
					break;
					case 'inserir':
						$banco->insert($objeto);
					break;
				endswitch;
			endif;
			return $banco->affected_rows;
			//return substr($dados_diferentes,0,-1);
		}

		public function gerarBotoes($acao){
			$botoes = '';
			$botoes .= '<div class="form-group"><div class="col-sm-offset-3 col-sm-6" style="text-align:right;">';
			switch($acao):
				case 'inserir':
					$botoes .= '<div onclick="javascript:document.formulario.submit();" class="btn btn-primary" style="margin:15px 5px;">Inserir</div>';
				break;
				case 'editar':
					$botoes .= '<div onclick="javascript:document.formulario.submit();" class="btn btn-primary" style="margin:15px 5px;">Salvar</div>';
				break;
				case 'deletar':
					$botoes .= '<div onclick="javascript:document.formulario.submit();" class="btn btn-primary" style="margin:15px 5px;">Deletar</div>';
				break;
				default:
					$botoes .= '<div onclick="javascript:document.formulario.submit();" class="btn btn-primary" style="margin:15px 5px;">Inserir</div>';
				break;
			endswitch;
			$botoes .= '<a href="javascript:history.back()"><div class="btn btn-default" style="margin:15px 5px;">Cancelar</div></a>';
			$botoes .= '</div></div>';
			return $botoes;
		}

		# Sistema
		public function console($data){
			 if (is_array($data))
		        $output = "<script>console.log('Log do Sistema: ".implode(',',$data)."');</script>";
		    else
		        $output = "<script>console.log('Log do Sistema: ".$data."');</script>";
		    echo $output;
		}
		function printMSG($msg=NULL,$tipo=NULL){
			if($msg!=NULL):
				switch($tipo):
					case 'erro':
						echo '<div class="alert alert-danger">'.$msg.'</div>';
					break;
					case 'alerta':
						echo '<div class="alert alert-warning">'.$msg.'</div>';
					break;
					case 'pergunta':
						echo '<div class="alert alert-info">'.$msg.'</div>';
					break;
					case 'sucesso':
						echo '<div class="alert alert-success">'.$msg.'</div>';
					break;
					default:
						echo '<div class="alert alert-success">'.$msg.'</div>';
					break;
				endswitch;
			endif;
		}
		function strip_special_chars($string){ 
		  $string = preg_replace('/([^.a-z0-9]+)/i', '_', $string);
		  return $string;  
		}
		function trataTexto($texto,$tamMax = 200){
			if(strlen($texto) > $tamMax) 
			   $novo_texto = substr(strip_tags($texto),0,$tamMax).'...';
			else
			   $novo_texto = $texto;   
			return $novo_texto;
		}
	}
?>