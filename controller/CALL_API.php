<?php
class CALL_API {

	// 検索実行時APIコール
	public function callApi() {
		// 定数読み込み
		include_once ('config/CONSTANTS');
		// DEBUGライブラリ読み込み
		include_once ('IL_DEBUG');

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
		// 書誌情報のパス
		$channel = $xml -> channel;

		// xml内容確認
		// IL_DEBUG::pr($xml);
		
		return $channel;
	}

	// ISBN検索のパース
	public function parseIsbn($channel) {
		// OAI-PMH dcndl_simpleリクエストパラメータ取得
		
		$channel_str = $channel -> item -> link;
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

		// DEBUG		
		// IL_DEBUG::pr($xml);

		return $data_path;
	}
	
	// KEYWORD検索のパース
	public function parseKeyword($channel) {
		
	}


	// APIコール実行
	private function exeApi($req, $search_method) {
		$req_str = OPENSEARCH_API . "&" . $search_method . "=" . $req;
		$str = $this -> xmlStringReplace($req_str);
		return $str;
	}

	// xml名前空間置換
	private function xmlStringReplace($req) {
		$string = preg_replace('/:/', '_', file_get_contents($req));
		return $string;
	}

	// HTMLへのプリント
	public static function p($str) {
		echo $str;
	}

	// HTMLへの配列プリント
	public static function p_arr($arr) {
		$tmp = $arr;
		foreach ($arr as $d) {
			$d_str = preg_replace('/, /', ' ', $d);
			echo $d_str;
			echo ", ";
		}
	}

	// htmlspecialchars関数
	private function h($str) {
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}
}
