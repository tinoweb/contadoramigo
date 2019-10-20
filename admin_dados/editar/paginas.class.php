
<?php
	class Paginas {

		# Variáveis
		public $tabela, $titulo, $campoID, $campos, $aviso = false;

		# Paginas do Menu
		public $paginas_menu = array(
			array('Agenda','agenda_index','itens_agenda')
		);


		# Páginas
		function __construct($pagina){

			switch($pagina):

				# case 'nome_da_tabela':
				# 	$this->tabela = "nome_da_tabela";
				# 	$this->titulo = "Titulo da Tabela";
				# 	$this->campoID = "id";
				#	$this->aviso = array("alerta(sucesso/erro)","Mensagem");
				#	$this->operacoes = array('inserir','listar','editar','deletar');
				#	$this->listar = array('id','campo01','campo02','campo03','select'=>array('tabela','titulo','id'),'acao'=>array('editar'));
				# 	$this->campos = array(
				# 		"input (text)" => array('Nome do Campo:','input',255,'placeholder','type'),
				# 		"select" => array('Nome do Campo:','select','tabela','id','nome')
				# 		"upload-imagem" => array('Logotipo','upload-img','../assets/images/',200,200),
				#		"upload-arquivo" => array('Titulo do Campo','upload-file','../assets/images/'),
				#		"textarea" => array('Titulo do Campo','textarea',1000(ou false=infinito),'placeholder'),
				#		"data" => array('Titulo do Campo','date','placeholder'),
				#		"radiobutton" => array('Nome do Campo:','radio', array('','0' => 'Opção 1', '1' => 'Opção 2', '2' => 'Opção 3', ... )),
				#		"imagem" => array('Imagem','upload-img','../assets/images/',false,false,'thumb'=>array(270,270,'../assets/images/','thumb'))
				# 	);
				# break;



				case 'agenda_index':
					$this->titulo = "Agenda";
					$this->tabela = "agenda_index";
					$this->campoID = "id";
					$this->operacoes = array( 'inserir','listar','editar','deletar');
					$this->listar = array('id','mes'=>array('meses','mes','id'),'dia','tipo'=>array('itens_agenda','frase','id'),'acao'=>array('editar','deletar'));
					$this->campos = array(
						"mes" => array('Mês:','select','meses','id','mes'),
						"dia" => array('Dia','input',255,'Dia','Dia'),
						"tipo" => array('Tipo:','select','itens_agenda','id','frase')
					);
				break;


				case 'itens_agenda':
					$this->titulo = "Tipos de item";
					$this->tabela = "itens_agenda";
					$this->campoID = "id";
					$this->operacoes = array( 'inserir','listar','editar','deletar');
					$this->listar = array('id','frase','texto','pagina','acao'=>array('editar','deletar'));
					$this->campos = array(
						"frase" => array('Tipo','input',255,'Tipo','Tipo'),
						"texto" => array('Texto','textarea',1000,'Texto'),
						"pagina" => array('Página destino','input',255,'Página destino','Página destino')
					);
				break;

			endswitch;
		}
	}
?>