<?php

require_once('config.php');
require_once('IGImage.class.php');
require_once('Instagram.class.php');
require_once('Runtime.class.php');

if ( isset( $_POST['user'], $_POST['password'], $_POST['caption'], $_FILES['userfile'] ) ) {
	
	$insta = new Instagram( $_POST['user'], $_POST['password'] );
	$igImage = new IGImage( $_FILES['userfile']['tmp_name'], $_POST['caption'] );
	
	if ( $igImage->isSquare() && $igImage->getFormat() === "JPEG" ) {
		
		$runtime = new Runtime( $insta, $igImage );
		$runtime->loginUser();
		if ( $runtime->statusOK() ){
			
			if ( $runtime->postImage() ) echo json_encode( array( 'status' => 0 ) );
			else echo json_encode( array( 'status' => 1 ) );
			
		}
		else echo json_encode( array( 'status' => 2 ) );
	}
	else {
		echo json_encode( array( 'status' => 3 ) );
	}
	
}
else echo json_encode( array( 'status' => 4 ) );

?>
