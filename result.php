<?php
require_once ("controller/CALL_API.php");
require_once("controller/IL_DB.php");
$api = new CALL_API();
$detail_flag = false;
$result_flag;
$book_id_flag;
$result_message;


if (!isset($_POST['ndl_link']) && !isset($_POST['book_id'])){
	$channel = $api -> openSearchApi();

	// 検索方法判別フラグ(これでよいのか要確認)
	$result_flag = (int)$channel->openSearch_totalResults;
	if($result_flag === 0){
		$result_message = "該当する書籍情報がありませんでした。";	
	}
}

if (isset($_POST['ndl_link'])) {
	$detail_flag = true;
	$result_flag = NULL;
	$channel_str = "http://".$_POST['ndl_link'];
}

if(isset($_POST['book_id'])) {
	$book_id_flag = true;
}
?>

<?php
require_once ("/view/partial/_header"); // ヘッダ
include_once ("/view/partial/_gNavi"); // グロナビ
?>

<?php
// ISBNサーチ
if($result_flag === 0) {
	$result_message; 	
	require_once("/view/partial/_isbn_not_found");
}
if($result_flag === 1 || $detail_flag === true){
	// OpenSearchAPIコール
	if($result_flag === 1){
		$data_path = $api -> parseIsbn($channel);
	}
	// ndl_linkからの詳細情報取得
	if($detail_flag === true){
		$data_path = $api -> exeOaiPmhReq($channel_str);
		//IL_DEBUG::pr($data_path);
	}
	
	// 書籍ID仮取得
	$action = new IL_DB();
	$new_book_id = $action->getIdCount();
	
	// 詳細情報の取得
	$dc_identifier_path = $data_path -> dc_identifier; // identifier配列
	$isbn_str = $api->objProc($dc_identifier_path, '/^978-/'); // ISBNパターンマッチ
	$isbn = str_replace("-", "", $isbn_str); // ISBN
	
	//IL_DEBUG::pr($dc_identifier_path);
	
	$title = (string)$data_path -> dc_title; // タイトル
	$dcndl_titleTranscription = (string)$data_path -> dcndl_titleTranscription; // タイトルカナ
	$dcterms_extent = (string)$data_path -> dcterms_extent; // 体裁
	$dcndl_materialType = (string)$data_path -> dcndl_materialType; // 資料区分
	$dc_publisher = (string)$data_path->dc_publisher; // 出版社
	$dcndl_seriesTitle = (string)$data_path -> dcndl_seriesTitle; // シリーズタイトル
	$dc_date = (string)$data_path->dc_date; // 出版年月日
	$dcndl_price = (string)$data_path->dcndl_price; // 価格
	$dc_subject_kigou_path = $data_path->dc_subject; // 請求記号パス
	$dc_subject_kigou = $api->objProc($dc_subject_kigou_path, '/\d{3}.\d{3}/'); // 請求記号パターンマッチ
		
	// IL_DEBUG::pr($dc_subject_kigou_path);
	require_once("/view/partial/_isbn_result");
}

// KEYWORDサーチ
if($result_flag > 1){
	// DEBUG
	// $api::pr($channel);
	$items = $channel->item;
	$item_count = 0;
	
	// table th出力
	require_once("/view/partial/_keyword_result_th");
	// table td出力
	foreach($items as $item){
	$item_count++;
	$title = (string)$item -> title; // タイトル
	$dc_publisher = (string)$item->dc_publisher[0]; // 出版社
	$dc_date = (string)$item->dcterms_issued; // 出版年
	$link = preg_replace('!http_//!', '', (string)$item -> link); // ndlリンク
	include("/view/partial/_keyword_result_td");
	}
	require_once("/view/partial/_keyword_result_table_close");
}

// 蔵書一覧から個別表示
if($book_id_flag){
	$book_id = $_POST['book_id'];
	$action = new IL_DB();
	$book_data = $action->getABookData($book_id);

	$id = $book_id; // ID
	$isbn = $book_data['isbn']; // ISBN
	$title = $book_data['title']; // 書籍名
	$dcndl_titleTranscription = $book_data['dcndl_titleTranscription']; // 書籍名カナ
	$dc_creator = $book_data['author']; // 著者
	$dcndl_creatorTranscription = $book_data['dcndl_creatorTranscription']; // 著者カナ
	$dcterms_extent = $book_data['dcterms_extent']; // 体裁
	$dcndl_materialType = $book_data['dcndl_materialType']; // 資料区分
	$dc_publisher = $book_data['dc_publisher']; // 出版社
	$dcndl_publicationPlace = $book_data['dcndl_publicationPlace']; // 出版地
	$dcndl_seriesTitle = $book_data['dcndl_seriesTitle']; // シリーズタイトル
	$dc_date = $book_data['dc_date']; // 出版年月日
	$dcndl_price = $book_data['dcndl_price']; // 価格
	$dc_subject_kigou = $book_data['dc_subject_kigou']; // 請求記号
			
	require_once("/view/partial/_zousho_result");
}
?>

<?php
require_once ("/view/partial/_footer");
?>