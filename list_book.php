<?php
include_once("config/CONSTANTS");
include_once("controller/IL_DEBUG");
include_once("controller/CALL_API.php");
include_once("controller/IL_DB");

// DB読み込み処理
$action = new IL_DB();
$book_list = $action->getDbBookData();

// DEBUG
// IL_DEBUG::pr($book_list);
?>
<?php
// ヘッダー読み込み
require_once ("/view/partial/_header");
?>
<?php
	// table th出力
	require_once("/view/partial/_book_list_th");
	// table td出力
	foreach($book_list as $book){
	$id = $book['id']; // ID
	$isbn = $book['isbn']; // ISBN
	$title = $book['title']; // 書名
	$dcndl_titleTranscription = $book['dcndl_titleTranscription']; // 書名カナ
	$author = $book['author']; // 著者
	$dcndl_creatorTranscription = $book['dcndl_creatorTranscription']; // 著者カナ
	$dcterms_extent = $book['dcterms_extent']; // 体裁
	$dc_publisher = $book['dc_publisher']; // 出版社
	$dc_date = $book['dc_date']; // 出版年
	$dcndl_price = $book['dcndl_price']; // 価格

	include("/view/partial/_book_list_td");
	}
	require_once("/view/partial/_keyword_result_table_close");
?>
<?php
require_once ("/view/partial/_footer");
?>