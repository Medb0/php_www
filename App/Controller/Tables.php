<?php
namespace App\Controller;
class Tables extends Controller
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
    if($uri->second() == "new"){
      print_r($_POST);
      if($_POST){
        $query .= "CREATE TABLE `".$_POST['tablename']."` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    PRIMARY KEY(`id`)
                    ) ENGINE=innodb default charset=utf8;
                ";

        $result = $this->db->queryExecute($query);

        header("location:"."/tables");
      }

      $htmlForm = file_get_contents("../Resource/table_new.html");
      echo $htmlForm;
    }else {
      $this->list();
    }

  }

  public function list()
  {
    $html = new \Module\Html\HtmlTable;

    $query = "SHOW TABLES";
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
        'name'=>"<a href='/TableInfo/".$row->Tables_in_php."'>".$row->Tables_in_php."</a>",
        'data'=>"<a href='/select/".$row->Tables_in_php."'>데이터조회</a>"
      ];
    }
    $content = $html->table($rows);
    $body = file_get_contents("../Resource/table.html");
    $body = str_replace("{{content}}",$content, $body); // 데이터 치환
    echo $body;
  }
}
