<?php

class IGImage {

	private $filepath;
	private $image;
	private $caption;

	public function __construct( $file, $caption = '' ){
		$this->caption = trim( preg_replace("/\r|\n/", "", $caption) );
		$this->filepath = $file[0];
		$this->image = getimagesize( $this->filepath );
	}

	public function getFormat(){
		return $this->image['mime'];
	}

	public function isSquare(){
		if ( $this->image[0] === $this->image[1] ) return true;
		return false; 
	}
	
	public function getFilename(){
		return $this->filepath;
	}

	public function getCaption(){
		return $this->caption;
	}

}

