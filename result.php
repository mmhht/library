<?php
include_once ("controller/CALL_API.php");
$api = new CALL_API();
$channel = $api -> callApi();
$data_path = $api -> parseApi($channel);

// 詳細情報の取得
$title = (string)$data_path -> dc_title; // タイトル
$dcndl_titleTranscription = (string)$data_path -> dcndl_titleTranscription; // タイトルカナ
$dcterms_extent = (string)$data_path -> dcterms_extent; // 体裁
$dcndl_materialType = (string)$data_path -> dcndl_materialType; // 資料区分
$dc_publisher = (string)$data_path->dc_publisher; // 出版社
$dc_date = (string)$data_path->dc_date; // 出版年
$dcndl_price = (string)$data_path->dcndl_price; // 価格
$dc_subject_kigou = (string)$data_path->dc_subject[2]; // 請求記号

// ヘッダー読み込み
include_once ("/view/partial/_header");

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			図書ID
		</div>
		<div class="col-md-2"></div>
		<div class="col-md-2">
			書籍JANコード
		</div>
		<div class="col-md-2"></div>
		<div class="col-md-2">
			資料区分
		</div>
		<div class="col-md-2"><?php $api::p($dcndl_materialType); ?></div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			タイトル
		</div>
		<div class="col-md-10"><?php $api::p($title); ?></div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			書名カナ
		</div>
		<div class="col-md-10"><?php $api::p($dcndl_titleTranscription); ?></div>
	</div>
</div>
<div class="container-fluid"></div>
	<div class="row">
		<div class="col-md-2">
			著者
		</div>
		<div class="col-md-10"><?php $api::p_arr($data_path->dc_creator); ?></div>
	</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			著者カナ
		</div>
		<div class="col-md-10"><?php $api::p_arr($data_path->dcndl_creatorTranscription); ?></div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			体裁
		</div>
		<div class="col-md-10"><?php $api::p($dcterms_extent); ?></div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			出版社
		</div>
		<div class="col-md-5"><?php $api::p($dc_publisher); ?></div>
	</div>
	<div class="row">
		<div class="col-md-2">
			出版地
		</div>
		<div class="col-md-3"><?php $api::p_arr($data_path->dcndl_publicationPlace); ?></div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			シリーズ
		</div>
		<div class="col-md-5"><?php //$api::p($dcndl_titleTranscription); ?></div>
	</div>
	<div class="row">
		<div class="col-md-2">
			出版年
		</div>
		<div class="col-md-3"><?php $api::p($dc_date); ?></div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			件名
		</div>
		<div class="col-md-5"><?php //$api::p($dcndl_titleTranscription); ?></div>
	</div>
	<div class="row">
		<div class="col-md-2">
			価格
		</div>
		<div class="col-md-3"><?php $api::p($dcndl_price); ?></div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">予備分類</div>
		<div class="col-md-2"><?php //$api::p($dcndl_titleTranscription); ?></div>
		<div class="col-md-4"><?php //api::p($dcndl_titleTranscription); ?></div>
		<div class="col-md-2">請求記号</div>
		<div class="col-md-2"><?php $api::p($dc_subject_kigou); ?></div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">NDC分類</div>
		<div class="col-md-2"><?php //$api::p($dcndl_titleTranscription); ?></div>
		<div class="col-md-4"><?php //$api::p($dcndl_titleTranscription); ?></div>
		<div class="col-md-4"><?php //$api::p($dcndl_titleTranscription); ?></div>
	</div>
</div>
<?php
include_once ("/view/partial/_footer");
?>