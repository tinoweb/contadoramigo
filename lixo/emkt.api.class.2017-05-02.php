<?php 
	
	class EMKT{
		
		private $tipo;
		private $remetente;

		function __construct(){



		}

		function criarMensagem($mensagem){
			
			$string = '{ "message": { "domain_id": "89611", "html_body": "'.$mensagem->gethtml_body().'", "text_body": "'.$mensagem->gettext_body().'", "list_ids": ["'.$mensagem->getlist_ids().'"], "name": "'.$mensagem->getname().'", "sender_name": "'.$mensagem->getsender_name().'", "sender": "'.$mensagem->getsender().'", "subject": "'.$mensagem->getsubject().'", "scheduled_to": "'.$mensagem->getscheduled_to().'" } } ';
			return $string;

		}

		function criarContato($dados){

			//https://DOMINIO/api/v1/accounts/ID_CONTA/contacts
			//retorno esperado: id do contato
			$string = 	'{"contact": { "email": "'.$dados->getemail().'", "list_ids": ["'.$dados->getid_lista().'"], "custom_fields": { "nome": "'.$dados->getnome().'", "cidade": "'.$dados->getcidade().'"} }}';

			return $string;

		}
		function criarLista($dados){

			$string = '{  "list" : {    "name" : "'.$dados->getnome().'",    "description" : "'.$dados->getdescricao().'"  }}';

			return $string;
		}
		function adicionarContatoLista($dados){

			$string = 	'{  "list" : {    "contacts" : [   '.$this->addContatos($dados).'   ] }}';

			return $string;

		}

		function addContatos($array){
			$string = '';
			for ($i=0; $i < count($array); $i++) { 
				
				$string .= ' {"email": "'.$array[$i].'"} ';

				if( $i < count($array) - 1 )
					$string .= ' , ';
			}

			return $string;

		}
	}
	class Lista{
		
		private $nome;
		private $descricao;

		function getnome(){
			return $this->nome;
		}
		function setnome($string){
			$this->nome = $string;
		}
		function getdescricao(){
			return $this->descricao;
		}
		function setdescricao($string){
			$this->descricao = $string;
		}
		function getListasDeleteDiaria($array){
			$deletar = array();
			foreach ($array as $key => $listas) {
				foreach ($listas as $key2 => $lista) {
					$ids = array();
					foreach ($lista as $key => $value) {
						$ids[] = $value;
					}
					if(count($ids) > 1){
						$id = $ids[0];
						$descricao = $ids[1];
						if( $this->iflistasParaRemoverDiaria($descricao) )
							$deletar[] = $id;
					}
				}
			}
			return $deletar;
		}
		function getListasDeleteGeral($array){
			$deletar = array();
			foreach ($array as $key => $listas) {
				foreach ($listas as $key2 => $lista) {
					$ids = array();
					foreach ($lista as $key => $value) {
						$ids[] = $value;
					}
					if(count($ids) > 1){
						$id = $ids[0];
						$descricao = $ids[1];
						if( $this->iflistasParaRemoverGeral($descricao) )
							$deletar[] = $id;
					}
				}
			}
			return $deletar;
		}
		function getListasDeleteEspecifica($array,$lista_deletar){
			$deletar = array();
			foreach ($array as $key => $listas) {
				foreach ($listas as $key2 => $lista) {
					$ids = array();
					foreach ($lista as $key => $value) {
						$ids[] = $value;
					}
					if(count($ids) > 1){
						$id = $ids[0];
						$descricao = $ids[1];
						if( $descricao == $lista_deletar  )
							$deletar[] = $id;
					}
				}
			}
			return $deletar;
		}
		function getidListaEspecifica($array,$lista_nome){
			foreach ($array as $key => $listas) {
				foreach ($listas as $key2 => $lista) {
					$ids = array();
					foreach ($lista as $key => $value) {
						$ids[] = $value;
					}
					if(count($ids) > 1){
						$id = $ids[0];
						$descricao = $ids[1];
						if( strtolower($descricao) == strtolower($lista_nome)  )
							return $id;
					}
				}
			}
		}
		function iflistasParaRemoverDiaria($string){
			if( $string == 'assinatura_inativa' )
				return true;
			if( $string == 'assinatura_reativada' )
				return true;
			if( $string == 'boleto_a_vencer' )
				return true;
			if( $string == 'boleto_compensado' )
				return true;
			if( $string == 'cartao_autorizado' )
				return true;
			if( $string == 'cartao_nao_autorizado' )
				return true;
			if( $string == 'demo_inativo_info' )
				return true;
			if( $string == 'demo_info' )
				return true;
			if( $string == 'demo_inativo' )
				return true;
			if( $string == 'demo_a_vencer' )
				return true;
			if( $string == 'demo_info_15' )
				return true;
			if( $string == 'user_vencidos' )
				return true;

		}
		function iflistasParaRemoverGeral($string){
			if( $string == 'Total Ativos' )
				return true;
			if( $string == 'Total cancelados' )
				return true;
			if( $string == 'Total Demo' )
				return true;
			if( $string == 'Total Demo inativo' )
				return true;
			if( $string == 'Total inativos' )
				return true;
		}
	}
	class Mensagem{
		
		private $domain_id;
		private $html_body;
		private $text_body;
		private $list_ids;
		private $name;
		private $campaign_id;
		private $sender_name;
		private $sender;
		private $subject;
		private $scheduled_to;
		private $tipo;

		function getscheduled_to(){
			return $this->scheduled_to;
		}
		function setscheduled_to($string){
			$this->scheduled_to = $string;
		}
		function getsubject(){
			return $this->subject;
		}
		function setsubject($string){
			$this->subject = $string;
		}
		function getsender(){
			return $this->sender;
		}
		function setsender($string){
			$this->sender = $string;
		}
		function getsender_name(){
			return $this->sender_name;
		}
		function setsender_name($string){
			$this->sender_name = $string;
		}
		function getcampaign_id(){
			return $this->campaign_id;
		}
		function setcampaign_id($string){
			$this->campaign_id = $string;
		}
		function getname(){
			return $this->name;
		}
		function setname($string){
			$this->name = $string;
		}
		function getlist_ids(){
			return $this->list_ids;
		}
		function setlist_ids($string){
			$this->list_ids = $string;
		}
		function gettext_body(){
			return $this->text_body;
		}
		function settext_body($string){
			$this->text_body = $string;
		}
		function gethtml_body(){
			return $this->html_body;
		}
		function sethtml_body($string){
			$this->html_body = $string;
		}
		function getdomain_id(){
			return $this->domain_id;
		}
		function setdomain_id($string){
			$this->domain_id = $string;
		}
	}
	class User_emkt{
		
		private $email;
		private $nome;
		private $cidade;
		private $id_lista;

		function __construct($id=0){
			$consulta = mysql_query("SELECT * FROM login WHERE idUsuarioPai = '".$id."' LIMIT 1 ");
			$user=mysql_fetch_array($consulta);

			$consulta = mysql_query("SELECT cidade FROM dados_cobranca WHERE id = '".$id."' ");
			$user_cidade=mysql_fetch_array($consulta);

			$this->setemail($user['email']);
			$this->setnome($user['assinante']);
			$this->setcidade($user_cidade['cidade']);
			// $this->setid_lista($user['status']);

		}
		function getid_lista(){
			return $this->id_lista;
		}
		function setid_lista($string){
			$this->id_lista = $string;
		}
		function getcidade(){
			return $this->cidade;
		}
		function setcidade($string){
			$this->cidade = $string;
		}
		function getnome(){
			return $this->nome;
		}
		function setnome($string){
			$this->nome = $string;
		}
		function getemail(){
			return $this->email;
		}
		function setemail($string){
			$this->email = $string;
		}
	}	
	class JSON{
		
		private $url;
		private $object;
		private $tipo;
		private $id_lista;
		function gettipo(){
			return $this->tipo;
		}
		function settipo($string){
			$this->tipo = $string;
		}
		function getobject(){
			return $this->object;
		}
		function setobject($string){
			$this->object = $string;
		}
		function getid_lista(){
			return $this->id_lista;
		}
		function setid_lista($string){
			$this->id_lista = $string;
		}
		function geturl(){
			if( $this->gettipo() == 'newLista' )
				return "https://emailmarketing.locaweb.com.br/api/v1/accounts/92222/lists";
			if( $this->gettipo() == 'newContato' )
				return "https://emailmarketing.locaweb.com.br/api/v1/accounts/92222/contacts";
			if( $this->gettipo() == 'newMensagem' )
				return "https://emailmarketing.locaweb.com.br/api/v1/accounts/92222/messages";
			if( $this->gettipo() == 'listas' )
				return "https://emailmarketing.locaweb.com.br/api/v1/accounts/92222/lists";
			if( $this->gettipo() == 'addContatosLista' )
				return "https://emailmarketing.locaweb.com.br/api/v1/accounts/92222/lists/".$this->getid_lista()."/contacts";
			if( $this->gettipo() == 'removerLista' )
				return "https://emailmarketing.locaweb.com.br/api/v1/accounts/92222/lists/".$this->getid_lista();
			if( $this->gettipo() == 'verListas' )
				return "https://emailmarketing.locaweb.com.br/api/v1/accounts/92222/lists/".$this->getid_lista();

		}

		function seturl($string){
			$this->url = $string;
		}
		function sendObject(){
			$curl= curl_init();
		    curl_setopt($curl, CURLOPT_URL, $this->geturl());
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_POST, 1);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getobject());
		    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Auth-Token: zsdqJ5sbktyuq5HbsbR6C8pSe7WdmPaL3AyJwu6uJgEr")); 
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    $resultado_http = curl_exec($curl);
		    $http_code= curl_getinfo($curl, CURLINFO_HTTP_CODE);
		    curl_close($curl);
		    return $resultado_http;
		}
		function verLista(){
			$curl= curl_init();
		    curl_setopt($curl, CURLOPT_URL, $this->geturl());
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_POST, 0);
		    // curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getobject());
		    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Auth-Token: zsdqJ5sbktyuq5HbsbR6C8pSe7WdmPaL3AyJwu6uJgEr")); 
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    $resultado_http = curl_exec($curl);
		    $http_code= curl_getinfo($curl, CURLINFO_HTTP_CODE);
		    curl_close($curl);
		    return $resultado_http;
		}
		function deleteLista(){
			$curl= curl_init();
		    curl_setopt($curl, CURLOPT_URL, $this->geturl());
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json","X-Auth-Token: zsdqJ5sbktyuq5HbsbR6C8pSe7WdmPaL3AyJwu6uJgEr")); 
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    $resultado_http = curl_exec($curl);
		    $http_code= curl_getinfo($curl, CURLINFO_HTTP_CODE);
		    curl_close($curl);
		}
		
	}	

	class APi_EMKT{
		// INSERE UM CONTADO no emkt
		// Parametro 1: id do contato
		// Parametro 2: id da lista do contato
		// Retorno: Id do contato criado
		function inserirContatoEMKT($id_user,$lista = 0){
			$user = new User_emkt($id_user);
			$user->setid_lista($lista);
			$emkt = new EMKT();
			$json = new JSON();
			$json->setobject($emkt->criarContato($user));
			$json->settipo("newContato");
			$array = json_decode($json->sendObject());
			foreach ($array as $key => $value) {
				$id_contato = $value;
			}
			return $id_contato;
		}
		function inserirContatoEMKTsemCadastro($email,$nome,$lista){
			$user = new User_emkt(0);
			$user->setid_lista($lista);
			$user->setemail($email);
			$user->setnome($nome);
			$user->setcidade("");

			$emkt = new EMKT();
			$json = new JSON();
			$json->setobject($emkt->criarContato($user));
			$json->settipo("newContato");
			$array = json_decode($json->sendObject());
			foreach ($array as $key => $value) {
				$id_contato = $value;
			}
			return $id_contato;
		}
		// CRIAR UMA NOVA LISTA
		// Parametro 1: Nome da lista
		// Parametro 2: Descricao da lista
		// Retorno: Id da lista criada
		function criarListaEMKT($nome_lista,$descricao_lista){
			$emkt = new EMKT();
			$lista = new Lista();
			$lista->setnome($nome_lista);
			$lista->setdescricao($descricao_lista);
			$json = new JSON();
			$json->setobject($emkt->criarLista($lista));
			$json->settipo("newLista");
			$array = json_decode($json->sendObject());
			foreach ($array as $key => $value) {
				$id_lista = $value;
			}
			return $id_lista;
		}
		// AGENDA UMA NOVA MENSAGEM
		// Parametro 1: Tipo da mensagem
		// Parametro 2: Id da Lista para envio da mensagem
		// Retorno: Id da mensagem criada
		function agendarMensagem($tipo_mensagem,$id_lista){
			$emkt = new EMKT();
			$json = new JSON();

			$consulta = mysql_query("SELECT * FROM mensagens_emkt WHERE tipo = '".$tipo_mensagem."' ");
			$objeto=mysql_fetch_array($consulta);
			$mensagem = new mensagem();
			
			$mensagem->sethtml_body(addslashes($objeto['html_body']));
			$mensagem->settext_body($objeto['text_body']);
			$mensagem->setname($objeto['name']);
			$mensagem->setlist_ids($id_lista);
			$mensagem->setsender_name($objeto['sender_name']);
			$mensagem->setsubject($objeto['subject']);
			$mensagem->setsender($objeto['sender']);
			$mensagem->setscheduled_to(date("Y-m-d H:i:s",strtotime("+5 minutes")));
			$json->setobject($emkt->criarMensagem($mensagem));
			$json->settipo("newMensagem");
			$array = json_decode($json->sendObject());
			foreach ($array as $key => $value) {
				$id_lista = $value;
			}
			return $id_lista;
		}
		// Função que reseta as listas atualizadas diariamente
		// As listas são definidas na função iflistasParaRemoverDiaria() na class Listas	
		function resetarListasCobranca(){
			// //REMOVER UMA LISTA
			$emkt = new EMKT();
			$json = new JSON();
			$json->settipo("verListas");
			$array = json_decode($json->verLista());
			$listas_para_remover = new Lista();
			$ids_remover_lista = $listas_para_remover->getListasDeleteDiaria($array);
			foreach ($ids_remover_lista as $key => $value) {
				$json = new JSON();
				$json->settipo("removerLista");
				$id_lista = $value;
				$json->setid_lista($id_lista);
				$json->deleteLista();
			}
		}
		// Função que reseta as listas gerias
		// As listas são definidas na função iflistasParaRemoverGeral() na class Listas	
		function resetarListasGeral(){
			// //REMOVER UMA LISTA
			$emkt = new EMKT();
			$json = new JSON();
			$json->settipo("verListas");
			$array = json_decode($json->verLista());
			$listas_para_remover = new Lista();
			$ids_remover_lista = $listas_para_remover->getListasDeleteGeral($array);

			foreach ($ids_remover_lista as $key => $value) {
				$json = new JSON();
				$json->settipo("removerLista");
				$id_lista = $value;
				$json->setid_lista($id_lista);
				$json->deleteLista();
			}
		}
		function resetarLista($id){
			// //REMOVER UMA LISTA
			$emkt = new EMKT();
			$json = new JSON();
			$json->settipo("verListas");
			$array = json_decode($json->verLista());
			$listas_para_remover = new Lista();
			$ids_remover_lista = $listas_para_remover->getListasDeleteEspecifica($array,$id);

			foreach ($ids_remover_lista as $key => $value) {
				$json = new JSON();
				$json->settipo("removerLista");
				$id_lista = $value;
				$json->setid_lista($id_lista);
				$json->deleteLista();
			}
		}
		function getIdLista($lista){
			// //REMOVER UMA LISTA
			$emkt = new EMKT();
			$json = new JSON();
			$json->settipo("verListas");
			$array = json_decode($json->verLista());
			// var_dump($array);
			$listas_para_remover = new Lista();
			return $listas_para_remover->getidListaEspecifica($array,$lista);

			
		}
		//Função que adiciona contatos a uma lista
		//Parametro 1: Array com os contatos a serem inseridos
		//Parametro 2: id da lista
		function addContatoLista($contatos,$id_lista){
			
			$contatos_aux = array();
			$limit = 100;
			foreach ($contatos as $value) {
				$i = $i + 1;
				$contatos_aux [] = $value;
				if( $i == $limit ){
					$i = 0;
					$emkt = new EMKT();
					$json = new JSON();
					$json->setobject($emkt->adicionarContatoLista($contatos_aux));
					$json->settipo("addContatosLista");
					$json->setid_lista($id_lista);
					$json->sendObject();
					// sleep(1);
				}
				

			}		
			if(count($contatos_aux) > 0){
				$emkt = new EMKT();
				$json = new JSON();
				$json->setobject($emkt->adicionarContatoLista($contatos_aux));
				$json->settipo("addContatosLista");
				$json->setid_lista($id_lista);
				$json->sendObject();
				// sleep(1);
			}
				

		}	
	}

?>