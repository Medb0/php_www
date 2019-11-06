<?php
namespace App\Controller;
/**
 *
 */
class Select
{
  private $db;
  private $httpUri;

  private $Html;

  public function __construct($db)
  {
    $this->db = $db;
    $this->httpUri = new \Module\Http\Uri();
    $this->Html = new \Module\Html\HtmlTable;
  }

  public function main()
  {
    $tableName = $this->httpUri->second();
    if($this->httpUri->third() == "new"){
      echo "새로운 데이터 입력";
      $this->newInsert($tableName);
    }else{
      $this->list($tableName);
    }
  }

  public function newInsert($tableName)
  {

    print_r($_POST);  // post로 전달된 값은 $_POST로 사용 할 수 있음

    if ($_POST) {
      $query = "INSERT INTO members (`FirstName`,`LastName`)
                            VALUE('".$_POST['firstname']."','".$_POST['lastname']."')";
      $result = $this->db->queryExecute($query);

      // 페이지 이동
      header("location:"."/select/".$tableName);
    }

    $query = "DESC ".$tableName;
    $result = $this->db->queryExecute($query);

    $count = mysqli_num_rows($result);
    $content = "";
    $rows = [];
    for ($i=0; $i<$count ; $i++) {
      $row = mysqli_fetch_object($result);
      $rows [] = $row;
    }
    $content = $this->Html->table($rows);

    $body = file_get_contents("../Resource/insert.html");
    $body = str_replace("{{content}}", $content, $body);
    echo $body;
  }

  public function list($tableName)
  {

    if ($tableName) {  // 칸이 있고 값이 있어야 실행
      $query = "SELECT * from ".$tableName; // SQL 쿼리문
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
        $content = $this->Html->table($rows);
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
