<?php
include_once("config/CONSTANTS");
include_once("controller/IL_DEBUG");
include_once("controller/IL_DB");

if(isset($_POST)){
	// IL_DEBUG::pr($_POST);

	// DB登録処理
	$action = new IL_DB();
	$action->saveDbBookData($_POST);

	// list_book.phpに移動
	header( "Location: list_book.php" ) ;
	
}


