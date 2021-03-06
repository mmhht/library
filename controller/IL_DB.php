<?php
class IL_DB {
	public $pdo;
	
	function __construct() {
		// DEBUG
		include_once("controller/IL_DEBUG");
  	//IL_DEBUG::pr($_POST);

			// DBコネクション確保
      try {
          $this->pdo = new PDO( PDO_DSN, DATABASE_USER, DATABASE_PASSWORD);
      } catch (PDOException $e) {
          echo 'error' . $e->getMessage();
            die();
        }
    }
 
    /**
     * 書籍データをDBに保存
     *
     * @param $data
     */
    function saveDbBookData($data){
 
        // データの保存
      $smt = $this->pdo->prepare('insert into books (isbn,dcndl_materialType,title,dcndl_titleTranscription,author,dcndl_creatorTranscription,dcterms_extent,dc_publisher,dcndl_publicationPlace,dc_date,dcndl_price,dc_subject_kigou) values(:isbn,:dcndl_materialType,:title,:dcndl_titleTranscription,:author,:dcndl_creatorTranscription,:dcterms_extent,:dc_publisher,:dcndl_publicationPlace,:dc_date,:dcndl_price,:dc_subject_kigou)');
      $smt->bindParam(':isbn',$data['isbn'], PDO::PARAM_STR);
      $smt->bindParam(':dcndl_materialType',$data['dcndl_materialType'], PDO::PARAM_STR);
      $smt->bindParam(':title',$data['title'], PDO::PARAM_STR);
      $smt->bindParam(':dcndl_titleTranscription',$data['dcndl_titleTranscription'], PDO::PARAM_STR);
      $smt->bindParam(':author',$data['author'], PDO::PARAM_STR);
      $smt->bindParam(':dcndl_creatorTranscription',$data['dcndl_creatorTranscription'], PDO::PARAM_STR);
      $smt->bindParam(':dcterms_extent',$data['dcterms_extent'], PDO::PARAM_STR);
      $smt->bindParam(':dc_publisher',$data['dc_publisher'], PDO::PARAM_STR);
      $smt->bindParam(':dcndl_publicationPlace',$data['dcndl_publicationPlace'], PDO::PARAM_STR);
      $smt->bindParam(':dc_date',$data['dc_date'], PDO::PARAM_STR);
      $smt->bindParam(':dcndl_price',$data['dcndl_price'], PDO::PARAM_STR);
      $smt->bindParam(':dc_subject_kigou',$data['dc_subject_kigou'], PDO::PARAM_STR);
      
      $smt->execute();
    }
    
    /**
     * 全書籍データをDBから読み込み
     *
     * @return array
     */
    function getDbBookData(){
 
      // データの取得
      $smt = $this->pdo->prepare('select * from books order by id ASC limit 100');
      $smt->execute();
      // 実行結果を配列に返す。
      $result = $smt->fetchAll(PDO::FETCH_ASSOC);
      
      return $result;  
    }
    
    /**
     * 個別書籍データをDBから読み込み
     *
     * @param $book_id
     * @return mixed
     */
    function getABookData($book_id){
 
      // データの取得
      $id_num = (int)$book_id;
      $sql = "select * from books where id = $id_num";
      $smt = $this->pdo->prepare($sql);
      $result = $smt->execute();
      
      // 実行結果を配列に返す
      $result = $smt->fetchAll(PDO::FETCH_ASSOC);

      return $result[0];  
    }
    
    /**
     * 登録時最新ID仮取得
     *
     * @return mixed
     */
    	function getIdCount(){
      // 次のauto_increment値
      $smt = $this->pdo->prepare('SELECT auto_increment FROM information_schema.tables WHERE table_name = "books"');
      $smt->execute();
      // 実行結果を配列に返す。
      $result = $smt->fetchAll(PDO::FETCH_ASSOC);
      
      return $result[0]['auto_increment'];
    	}
    	
}