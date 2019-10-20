<?php

class UsuarioVo {
	/**
	 * Classe Para realizar o encapsulamento
	 * autor: Atano de Farias Jacinto
	 * Date: 17/02/2017
	 */
	 
	private $UserId;
	private $UserName;
	private $UserPassword;
	private $UserNoMaskKey;
	private $UserSecurityKey; 
	private $UserEmail; 
	private $UserDate;
	private $UserActive;
	private $UserType;
	
	public function getUserId(){
		return $this->UserId;  
	}
	
	public function setUserId($UserId){
		$this->UserId = $UserId;
	}

	public function getUserName(){
		return $this->UserName;  
	}
	
	public function setUserName($UserName){
		$this->UserName = $UserName;
	}
	
	public function getUserActive(){
		return $this->UserActive;  
	}
	
	public function setUserPassword($userPassword) {
		$this->UserPassword = $userPassword;	
	}
	
	public function getUserPassword() {
		return $this->UserPassword;
	}
	
	public function setUserNoMaskKey($userNoMaskKey) {
		$this->UserNoMaskKey = $userNoMaskKey;
	}
	
	
	public function getUserNoMaskKey() {
		return $this->UserNoMaskKey;
	}
	
	public function setUserSecurityKey($userSecurityKey) {
		$this->UserSecurityKey = $userSecurityKey;
	}
	
	public function getUserSecurityKey() {
		return $this->UserSecurityKey;
	}
	
	public function setUserEmail($userEmail) {
		$this->UserEmail = $userEmail;
	}
	
	public function getUserEmail() {
		return $this->UserEmail;
	}
	
	public function setUserActive($UserActive){
		$this->UserActive = $UserActive;
	}
	
	public function getUserType(){
		return $this->UserType;  
	}
	
	public function setUserType($UserType){
		$this->UserType = $UserType;
	}
	
	public function getUserDate(){
		return $this->UserDate;  
	}
	
	public function setUserDate($UserDate){
		$this->UserDate = $UserDate;
	}
}