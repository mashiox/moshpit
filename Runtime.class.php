<?php

/**

    Runtime inspired by:
    Lance G. Newman 
    http://lancenewman.me/posting-a-photo-to-instagram-without-a-phone/

 **/

require_once('IGImage.class.php');
require_once('Instagram.class.php');
require_once('instagram.lib.php');

class Runtime {

	private $igUser;
	private $igImage;
	private $loginStatus;
	private $agent;
	private $guid;
	private $device_id;

	public function __construct( &$igUser, &$igImage ){
		$this->igUser = $igUser;
		$this->igImage = $igImage;
	}

	public function loginUser(){
		$this->agent = GenerateUserAgent();
		$this->guid = GenerateGuid();
		$this->device_id = "android-".$this->guid;

		$data = '{"device_id":"'.$this->device_id.'","guid":"'.$this->guid.'","username":"'.$this->igUser->getUser().'","password":"'.$this->igUser->getPassword().'","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
		$signature = GenerateSignature( $data );
		$data = 'signed_body='.$signature.'.'.urlencode( $data ).'&ig_sig_key_version=4';
		$this->loginStatus = SendRequest( 'accounts/login/', true, $data, $this->agent, false );
	}
	
	public function statusOK(){
		if ( strpos( $this->loginStatus['response'], "Sorry, an error occurred while processing this request." ) ) {
			return false;
		}
		return true;
	}

	public function postImage(){
		if ( $this->loginStatus['code'] !== 200 ){
			return false;
		}
		$obj = @json_decode( $this->loginStatus['response'], true );
		if ( empty($obj) ) return false;
		
		// This posts the initial xmission to IG.
		$data = GetPostData( $this->igImage->getFilename() );
		$post = SendRequest( 'media/upload/', true, $data, $this->agent, true );
		
		if ( $post['code'] !== 200 ) return false;
		
		$obj = @json_decode( $post['response'], true );
		if ( empty($obj) ) return false;
		
		$status = $obj['status'];
		if ( $status == 'ok' ){
			$data = '{"device_id":"'.$this->device_id.'","guid":"'.$this->guid.'","media_id":"'.$obj['media_id'].'","caption":"'.$this->igImage->getCaption().'","device_timestamp":"'.time().'","source_type":"5","filter_type":"0","extra":"{}","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
			$signature = GenerateSignature( $data );
			$signedData = 'signed_body='.$signature.'.'.urlencode( $data ).'&ig_sig_key_version=4';
			
			// Posts the final xmission to IG. 
			$configure = SendRequest( 'media/configure/', true, $signedData, $this->agent, true );
			
			if ( $configure['code'] != 200 ) return false;
			if ( strpos( $configure['response'], "login_required" ) ) return false;
			
			$obj = @json_decode( $configure['response'], true );

			if ( $obj['status'] !== 'fail' ) return true; // yay!
			return false;
		}
		return false;
		
	}

}


