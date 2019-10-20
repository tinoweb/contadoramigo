<?php 
    
    class Estabelecimento{
        
        private $estabelecimento;
        private $chave;
        private $cert_path;

        function __construct(){   
            if(false):
                $this->estabelecimento = "1006993069";
                $this->chave = "25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3";
                $this->cert_path = "ssl/VeriSignClass3PublicPrimaryCertificationAuthority-G5.CRT";    
            else:
                $this->estabelecimento = "1032144332";//1032144332
                $this->chave = "9a0b779866fabd0e0771e503f61a0eaa5b9789cdf9e84f482624db50475e89ba";
                $this->cert_path = "ssl/VeriSignClass3PublicPrimaryCertificationAuthority-G5.CRT";
                
            endif;
        }

        function setEstabelecimento($string){
            $this->estabelecimento = $string;
        }
        function getEstabelecimento(){
            return $this->estabelecimento;
        }

        function setChave($string){
            $this->chave = $string;
        }
        function getChave(){
            return $this->chave;
        }

        function setCert_path($string){
            $this->cert_path = $string;
        }
        function getCert_path(){
            return $this->cert_path;
        }
    }

    class Dados_cartao{
        
        private $nome;
        private $numero_cartao;
        private $validade;
        private $codigo_seguranca;
        private $valor;
        private $bandeira;
        private $token;

        function __construct($_VAR){
            if( isset( $_VAR['cartaoNome'] ) ):  
                $this->nome = $_VAR['cartaoNome'];
            endif;

            if( isset( $_VAR['cartaoNumero'] ) ):  
                $this->numero_cartao = $_VAR['cartaoNumero'];
            endif;

            if( isset( $_VAR['cartaoValidade'] ) ):  
                $this->validade = $_VAR['cartaoValidade'];
            endif;

            if( isset( $_VAR['cartaoCodigoSeguranca'] ) ):  
                $this->codigo_seguranca = $_VAR['cartaoCodigoSeguranca'];
            endif;

            if( isset( $_VAR['valor'] ) ):  
                $this->valor = $_VAR['valor'];
            endif;

            if( isset( $_VAR['bandeira'] ) ):  
                $this->bandeira = $_VAR['bandeira'];
            endif;

            if( isset( $_VAR['token'] ) ):  
                $this->token = $_VAR['token'];
            endif;
        }
        function setNome($string){
            $this->nome = $string;
        }
        function getNome(){
            return $this->nome;
        }
        function setNumero_cartao($string){
            $this->numero_cartao = $string;
        }
        function getNumero_cartao(){
            return $this->numero_cartao;
        }

        function setValidade($string){
            $this->validade = $string;
        }
        function getValidade(){
            return $this->validade;
        }

        function setCodigo_seguranca($string){
            $this->codigo_seguranca = $string;
        }
        function getCodigo_seguranca(){
            return $this->codigo_seguranca;
        }

        function setValor($string){
            $this->valor = $string;
        }
        function getValor(){
            return $this->valor;
        }

        function setBandeira($string){
            $this->bandeira = $string;
        }
        function getBandeira(){
            return $this->bandeira;
        }

        function setValorFinal($string){
            $this->valor = $string;
        }
        function getValorFinal(){
            return $this->valor*100;
        }



        

    }

?>