<?php
namespace App\Controller;
/**
 *
 */
class Select
{
  private $db;
  public function __construct($db)
  {
    $this->db = $db;
  }

  public function main()
  {
    $html = new \Module\Html\HtmlTable;

    $uri = $_SERVER['REQUEST_URI'];
    $uris = explode("/", $uri);

    if (isset($uris[2]) && $uris[2]) {  // 칸이 있고 값이 있어야 실행
      $query = "SELECT * from".$uris[2]; // SQL 쿼리문
      $result = $this->db->queryExecute($query);

      $content = "";
      $rows = [];

      $count = mysqli_num_rows($result);
      if($count){
        // 0 보다 큰값 = true

        for ($i=0; $i<$count ; $i++) {
          $row = mysqli_fetch_object($result); // 객체로 반환
          $rows [] = $row;
        }
        $content = $html->table($rows);
      }else {
        // 데이터가 없음

        $content = "데이터 없음";
      }
    }
    else {
        $content = "선택된 테이블이 없습니다.";
    }

    $body = file_get_contents("../Resource/select.html");
    $body = str_replace("{{content}}", $content, $body);
    echo $body;
  }
}
