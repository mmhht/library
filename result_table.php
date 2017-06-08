<?php
require_once ("controller/CALL_API.php");
$api = new CALL_API();
$channel = $api -> callApi();

// 検索方法判別フラグ(これでよいのか要確認)
$result_flag = (int)$channel->openSearch_totalResults;
?>

<?php
// ヘッダー読み込み
require_once ("/view/partial/_header");
?>

<?php
// ISBNサーチ
if($result_flag === 1){
	$data_path = $api -> parseIsbn($channel);
	// 詳細情報の取得
	$isbn = (string)$channel -> item -> dc_identifier[0]; // ISBN 
	$title = (string)$data_path -> dc_title; // タイトル
	$dcndl_titleTranscription = (string)$data_path -> dcndl_titleTranscription; // タイトルカナ
	$dcterms_extent = (string)$data_path -> dcterms_extent; // 体裁
	$dcndl_materialType = (string)$data_path -> dcndl_materialType; // 資料区分
	$dc_publisher = (string)$data_path->dc_publisher; // 出版社
	$dc_date = (string)$data_path->dc_date; // 出版年
	$dcndl_price = (string)$data_path->dcndl_price; // 価格
	$dc_subject_kigou = (string)$data_path->dc_subject[2]; // 請求記号
	require_once("/view/partial/_isbn_result");
}

// KEYWORDサーチ
if($result_flag > 1){
	// DEBUG
	// $api::pr($channel);
	$items = $channel->item;
	
	// table th出力
	require_once("/view/partial/_keyword_result_th");
	// table td出力
	foreach($items as $item){
	$title = (string)$item -> title; // タイトル
	$dc_publisher = (string)$item->dc_publisher[0]; // 出版社
	$dc_date = (string)$item->dcterms_issued; // 出版年
	include("/view/partial/_keyword_result_td");
	}
}
?>

<?php
require_once ("/view/partial/_footer");
?>