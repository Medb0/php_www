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

    $third = $this->httpUri->third();
    if($third == "new"){
      $this->newInsert($tableName);
    }elseif ($third == "delete") {
      $this->delete($tableName);
    }elseif (is_numeric($third)) { // Uri의 3번째 값이 정수이면 데이터수정
      $this->edit($tableName , $third);
    }
    else{
      $this->list($tableName);
    }
  }

public function edit($tableName , $id) // 데이터 수정
{
  print_r($_POST);
  if($_POST){
    $query = "UPDATE ".$tableName." SET ";
    // $query .= "`FirstName`='".$_POST['FirstName']."', ";
    // $query .= "`lastName`='".$_POST['lastName']."'";

    foreach ($_POST as $key => $value) {
      if($key == "id")continue;
      $query .= "`$key`= '".$value."',";
    }
    $query = rtrim($query,",");
    $query .= " WHERE id='".$id."'";

    $result = $this->db->queryExecute($query);

    header("location:"."/select/".$tableName);
  }

  //step1. 데이터 조회
  $query = "SELECT * FROM ".$tableName." WHERE id=".$id;
  echo $query;
  $result = $this->db->queryExecute($query);
  $data = mysqli_fetch_object($result);
  print_r($data);

  $content ="<form method=\"post\">";
  $content .="<input type=\"hidden\" name=\"id\" value='".$id."'>";
  $query = "DESC ".$tableName;
  $result = $this->db->queryExecute($query);
  $count = mysqli_num_rows($result);
  $rows = [];

  for ($i=0;$i<$count;$i++) {
    $row = mysqli_fetch_object($result);
    if ($row->Field=="id") continue;

    $key = $row->Field;

    $content .=$row->Field."<input type=\"text\" name=\"".$row->Field."\" value='".$data->$key."'>";
    $content .="<br>";
  }

  $content .="<input type=\"submit\" value=\"수정\">";
  $content .="<a href='./delete/".$id."'>삭제</a>";
  $content .="</form>";

  $body = file_get_contents("../Resource/edit.html");
  $body = str_replace("{{content}}", $content, $body);
  echo $body;
}

private function delete($tableName)
{
  $fourth =  $this->httpUri->fourth();
  echo "삭제합니다.";

  $query = "DELETE FROM ".$tableName;

  $query .= " WHERE id='".$fourth."'";

  echo $query;

  $result = $this->db->queryExecute($query);

  header("location:"."/select/".$tableName);
}

  public function newInsert($tableName)
  {
    print_r($_POST);  // post로 전달된 값은 $_POST로 사용 할 수 있음

    if ($_POST) {

      $fields = "(";
      $data = "(";
      foreach ($_POST as $key => $value) {
        $fields .= "`".$key."` ,";
        $data .= "'".$value."' ,";
      }
      $fields = rtrim($fields, ",");  // 마지막 콤마 제거
      $data = rtrim($data, ",");  // 마지막 콤마 제거
      $fields .= ")";
      $data .= ")";

      $query = "INSERT INTO ".$tableName. $fields ." VALUES ".$data;

      echo "<br>";
      $result = $this->db->queryExecute($query);

      // 페이지 이동
      header("location:"."/select/".$tableName);
    }

    $content ="<form method=\"post\">";
    // $content .="<input type=\"text\" name=\"firstname\">";
    // $content .="<input type=\"text\" name=\"lastname\">";

    $query = "DESC ".$tableName;
    $result = $this->db->queryExecute($query);

    $count = mysqli_num_rows($result);
    $rows = [];
    for ($i=0;$i<$count;$i++) {
      $row = mysqli_fetch_object($result);
      if ($row->Field=="id") continue;
      $content .=$row->Field."<input type=\"text\" name=\"".$row->Field."\">";
      $content .="<br>";
    }

    $content .="<input type=\"submit\" value=\"확인\">";
    $content .="</form>";

    $body = file_get_contents("../Resource/insert.html");
    $body = str_replace("{{content}}", $content, $body);
    echo $body;
  }

  public function list($tableName)
  {

    if ($tableName) {  // 칸이 있고 값이 있어야 실행
      $query = "SELECT * from ".$tableName; // SQL 쿼리문
      $result = $this->db->queryExecute($query);
      $total = mysqli_num_rows($result);

      echo "전체갯수 = ".$total;

      $lines = 5;
      $start = $_GET['start'];

      $query = "SELECT * from ".$tableName; // SQL 쿼리문
      $query .= " LIMIT ".$start.",".$lines;
      $result = $this->db->queryExecute($query);

      $content = "";
      $rows = [];

      $count = mysqli_num_rows($result);
      if($count){
        // 0 보다 큰값 = true

        for ($i=0; $i<$count ; $i++) {
          $row = mysqli_fetch_object($result); // 객체로 반환
          // $rows [] = $row;
          $arr = [];  // 기존의 배열, 새로운 배열이 계속 추가 되기 때문에 초기화해준다.
          foreach ($row as $key => $value) {
            // 초기화된 배열에, $key 값을 가지는 프로퍼티에 $value 값을 저장

            if($key == "id"){
              $value = "<a href='./".$tableName."/".$value."'>".$value."</a>";
            }
            $arr[$key] = $value;  // 연상 배열
          }
          $rows [] = $arr;
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


    $totalPages = $total / $lines;  // 페이지수

    $content .= "<nav aria-label=\"Page navigation example\">
                 <ul class=\"pagination\">
                 <li class=\"page-item\"><a class=\"page-link\" href=\"#\">Previous</a></li>";
    for ($i=0,$j=1; $i <$totalPages ; $i++) {
      $content .="<li class=\"page-item\"><a class=\"page-link\" href=\"?start=$i\">".$j."</a></li>";
    }
    $content .="<li class=\"page-item\"><a class=\"page-link\" href=\"#\">Next</a></li>
    </ul>
    </nav>";

    $body = file_get_contents("../Resource/select.html");
    $body = str_replace("{{content}}", $content, $body);
    $body = str_replace("{{new}}", $tableName."/new", $body);
    echo $body;
  }
}
