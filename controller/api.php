<?php
// OpenSearchでISBNをキーにAPIリクエストをコール
$opensearch = "http://iss.ndl.go.jp/api/opensearch";

// ISBNの場合
if(isset($_POST['isbn'])){
	$isbn = h($_POST['isbn']);
	$req_isbn = $opensearch."?isbn=".$isbn;
	$xml = simplexml_load_file($req_isbn)
		or die("XMLパースエラー"); 
}

// Keywordの場合
if(isset($_POST['keyword'])){
	$keyword = h($_POST['keyword']);
	$keyword = urlencode($keyword);
	$req_keyword = $opensearch."?title=".$keyword;
	$xml = simplexml_load_file($req_keyword)
		or die("XMLパースエラー"); 
	// $data = get_object_vars($xml);
}

// レスポンスをsimplexmlオブジェクトとして取得
// channelの要素だけパース
$data = get_object_vars($xml);
foreach($xml->channel->item as $item) {
	echo $item->title."<br />";
	echo "<a href='".$item->link."'>".$item->link."</a><br />";
	echo $item->description."<br />";
	echo $item->author;
}

// htmlspecialchars関数
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
