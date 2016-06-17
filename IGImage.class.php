<?php

class IGImage {

	private $filepath;
	private $image;
	private $caption;

	public function __construct( $file, $caption = '' ){
		$this->caption = trim( preg_replace("/\r|\n/", "", $caption) );
		$this->filepath = $file;
		$this->image = new Imagick( $this->filepath );
	}

	public function getFormat(){
		return $this->image->getImageFormat();
	}

	public function isSquare(){
		$imageSize = $this->image->getImageGeometry();
		if ( $imageSize['width'] === $imageSize['height'] ) return true;
		return false; 
	}
	
	public function getFilename(){
		return $this->filepath;
	}

	public function getCaption(){
		return $this->caption;
	}

}

