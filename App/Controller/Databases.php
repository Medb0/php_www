<?php
namespace App\Controller;
class Databases extends Controller
{
    private $db;
    // 생성자
    public function __construct($db)
    {
        // echo __CLASS__;
        $this->db = $db;
    }
    public function main()
    {
        $uri = new \Module\Http\Uri;
        if( $uri->second() == "new"){
          print_r($_POST);
          if($_POST){
            $query = "CREATE DATABASE ".$_POST['database'];
            $result = $this->db->queryExecute($query);
            header("location:"."/");
          }else {
            $htmlForm = file_get_contents("../Resource/database_new.html");
            echo $htmlForm;
          }
        }else{
          // DB 목록
          $this->list();
        }


    }

    public function list()
    {
      $html = new \Module\Html\HtmlTable;
      $query = "SHOW DATABASES";
      $result = $this->db->queryExecute($query);
      $count = mysqli_num_rows($result);
      $content = ""; // 초기화
      $rows = []; // 배열 초기화
      for ($i=0;$i<$count;$i++) {
          $row = mysqli_fetch_object($result);
          // $rows []= $row; // 배열 추가
          // 배열 * 배열 = 2차원 배열.
          // 키, 값 연상배열
          $rows []= [
              'num'=>$i,
              'name'=>"<a href='/Tables/".$row->Database."'>".$row->Database."</a>"
          ];
      }
      $content = $html->table($rows);
      $body = file_get_contents("../Resource/database.html");
      $body = str_replace("{{content}}",$content, $body); // 데이터 치환
      echo $body;
    }
}
