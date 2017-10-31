<?php

/**
 * Class CALL_API
 */
class CALL_API {

	function __construct() {
		// 定数読み込み
		include_once ('config/CONSTANTS');
		// DEBUGライブラリ読み込み
		include_once ('IL_DEBUG');
		// NOTICEを非表示
		error_reporting(E_ALL & ~E_NOTICE);
	}

    /**
     * OpenSearchAPIコール
     *
     * @return SimpleXMLElement[]
     */
    public function openSearchApi() {
		$str ="";

		// ISBN
		if (isset($_POST['isbn'])) {
			$search_method = 'isbn';
			$isbn = $this -> h($_POST['isbn']);
			$str = $this -> exeApi($isbn, $search_method);
		}

		// Keyword
		if (isset($_POST['keyword'])) {
			$search_method = 'title';
			$keyword = $this -> h($_POST['keyword']);
			$keyword = urlencode($keyword);
			$str = $this -> exeApi($keyword, $search_method);
		}

		// APIレスポンスをxml文字列取得
		$xml = simplexml_load_string($str) or die("XMLパースエラー");
		//IL_DEBUG::pr($xml);
		// 書誌情報のパス
		$channel = $xml -> channel;

		return $channel;
	}

	/**
     * ISBN検索のパース
     *
     * @param $channel
     * @return mixed
     */
	public function parseIsbn($channel) {
		// OAI-PMH dcndl_simpleリクエストパラメータ取得
		$channel_str = $channel -> item -> link;
		$data_path = $this->exeOaiPmhReq($channel_str);
		
		return $data_path;
	}

    /**
     * OAI-PMH dcndl_simpleリクエスト
     *
     * @param $channel_str
     * @return mixed
     */
	public function exeOaiPmhReq($channel_str) {
		$string = substr($channel_str, 38); // $channel_strの頭から38文字目まで削除
		$oai_pmh_str = OAI_PMH_PREFIX . $string . OAI_PMH_SUFFIX;
		$string = preg_replace('/:/', '_', file_get_contents($oai_pmh_str));

		// simplexml_load_string実行時エラー処理を有効にする
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string($string) or die("XMLパースエラー");
		$data_path = $xml -> GetRecord -> record -> metadata -> dcndl_simple_dc;

		foreach (libxml_get_errors() as $error) {
			// エラーを取得するが何もしない
			//print $error -> message . "\n";
		}
		libxml_clear_errors();	

		return $data_path;
	}
	
	/**
     * オブジェクトパスから要素取得
     *
     * @param $obj_path
     * @param $pattern
     * @return mixed
     */
    public function objProc($obj_path, $pattern) {
		$searchArray = $this->objArray($obj_path);
		$array = $this->pregArr($pattern, $searchArray);
		return $this->renumArr($array);
	}

    /**
     * APIコール実行
     *
     * @param $req
     * @param $search_method
     * @return mixed
     */
    private function exeApi($req, $search_method) {
		$req_str = OPENSEARCH_API . "&" . $search_method . "=" . $req. "&cnt=".KEYWORD_HIT_LIMIT;
		$str = $this -> xmlStringReplace($req_str);
		return $str;
	}

    /**
     * xml名前空間置換
     *
     * @param $req
     * @return mixed
     */
	private function xmlStringReplace($req) {
		$string = preg_replace('/:/', '_', file_get_contents($req));
		return $string;
	}
	
    /**
     * オブジェクトを配列変換
     *
     * @param $obj_path
     * @return array
     */
	private function objArray($obj_path){
		return (array)json_decode(json_encode($obj_path), true); // オブジェクト配列変換
	}
	
    /**
     * 正規表現で配列検索
     *
     * @param $pattern
     * @param $searchArray
     * @return array
     */
	private function pregArr($pattern, $searchArray) {
		return array_values(preg_grep($pattern, $searchArray)); // ISBN文字列検索
	}

    /**
     * 配列要素番号振り直し
     *
     * @param $array
     * @return mixed
     */
	private function renumArr($array){
		 $result = array_values($array);
		 return $result[0];
	}

    /**
     * HTMLへのプリント
     *
     * @param $str
     */
	public static function p($str) {
		echo $str;
	}

    /**
     * HTMLへの配列プリント
     *
     * @param $arr
     */
	public static function p_arr($arr) {
		$tmp = $arr;
		foreach ($arr as $d) {
			$d_str = preg_replace('/, /', ' ', $d);
			echo $d_str;
			echo ", ";
		}
	}

	// htmlspecialchars関数
	public function h($str) {
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}
}
