<?php

class Instagram {

	private $username;
	private $password;

	public function __construct( $u, $p ){
		$this->username = $u;
		$this->password= $p;
	}

	public function getUser(){
		return $this->username;
	}

	public function getPassword(){
		return $this->password;
	}

}
