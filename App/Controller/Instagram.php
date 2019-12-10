<?php

namespace App\Controller;
class Instagram extends Controller
{
  /** 기말
  * 1. 도메인/insta <= 실행
  * 2. 테이블 구조 설정 <= 툴
  * 3. 목록(사진/글) + 시간, 조회수, 클릭횟수
  * 4. 글 작성 (사진 추가)..
  * 5. 로그인 처리(옵션)
  * 코드 => 동작 화면 캡쳐
  * 현재 문제 : 글쓰기를 하면 조회수가 지정되있지 않아서 수동으로 해야됨 => tableinfo에서 default 값을 줘야함.

  *  DB 구조를 설계하시오. ( 실습환경에서 테이블을 생성합니다)
  *  현재 실습환경에서 인스타그렘 컨트롤러를 하세요.
  *  화면 구성을 위한 리소스 html 파일을 생성합니다.
  *  필수사항 : 사진, 제목, 본문은 반드시 포함합니다.
  *  선택사항 : 좋아요, 날짜 등의 필요한 기능을 자유롭게 추가하시오.
  *  목록페이지를 출력합니다.
  *  목록에서 사진을 선택시, 상세 보기로 이동합니다.
  *  계시물을 수정 및 삭제가 가능해야 합니다.
  *  선택사항 : 로그인 기능
  */
  private $db;
  private $HttpUri;

  public function __construct($db)
  {
    $this->db = $db;
    $this->HttpUri = new \Module\Http\Uri();
  }
  public function main()
  {
    $second = $this->HttpUri->second();
    $third = $this->HttpUri->third();
    if($second == "new"){
      $this->newInsert();
    }else if(is_numeric($second)){
      if($third == "delete"){
        $this->postDelete($second);
      }else if($third == "edit"){
        $this->postEdit($second);
      }
      else{
        $this->detailView($second);
      }
    }
    else {
      $this->instagram();
    }
  }
  private function instagram()
  {
    if(isset($_POST)){
      if($_POST['img_id'] != ""){
        // if($this->prevent_multi_submit()){
        $query = "UPDATE instagram SET `likes`=`likes`+1 where id='".$_POST['img_id']."'";
        $result = $this->db->queryExecute($query);
        // }
      }
    }

    $query = "SELECT * from instagram ";
    $result = $this->db->queryExecute($query);
    $count = mysqli_num_rows($result);
    $content = "";
    // "<div class=\"container\">
    // <div class=\"row\">";
    for ($i=0;$i<$count;$i++) {
      $row = mysqli_fetch_object($result);
      // print_r($row);
      // $content .= "</div>
      // <div class=\"card\">";

      $link = $_SERVER['REQUEST_URI']."/".$row->id;

      $content .= "<div class=\"card mx-auto custom-card mt-3\" id=\"prova\">"; //1
      $content .= " <div class=\"row post-header col-12 py-2 px-3\">"; // 2
      $content .= "   <div class=\"col-6 float-left \">"; // 3
      $content .= "     <h4>".$row->title."</h4>";
      $content .= "   </div>"; // 3.
      $content .= "   <div class=\"col-6 float-right text-right post-number\">"; // 4
      $content .= "   <h4>12/14</h4>";
      $content .= "   </div>"; // 4.
      $content .= " </div>"; // 2.
      $content .= "<form name=\"detail\" method=\"post\">";
      $content .= "<input type=\"hidden\" name=\"img_id\" value=\"".$row->id."\">";
      $content .= " <a href=".$link."><img class=\"card-img\" src='/images/".$row->image."'></a>";
      $content .= "</form>";
      $content .= " <div class=\"card-body px-3\">"; // 5
      $content .= "   <h5 class=\"card-title\">".$row->likes." Likes</h5>";
      $content .= "   <p class=\"card-text\">".$row->content."</p>";
      $content .= " </div>"; // 5.
      $content .= " <div class=\"row post-header px-3 pb-3\">"; // 6
      $content .= "   <div class=\"col-1 float-left text-left\">"; // 7
      $content .= "     <i class=\"far fa-heart\" onclick=\"likeUp(".$row->id.")\" id=\"".$row->id."\"></i>";
      $content .= "   </div>"; // 7.
      $content .= "   <div class=\"col-10 float-left text-left\">Comment...</div>"; // 8 , 8.
      $content .= "   <div class=\"col-1 float-right text-right\"><i class=\"fa fa-ellipsis-v\" aria-hidden=\"true\"></i></div>"; // 9 , 9.
      $content .= " </div>"; // 6.
      $content .= "</div>"; // 1.
      // $content .= "<div class=\"card-body\" style=\"width: 40%;\">";
      // $content .= "<div> TITLE : <a href='$link'>".$row->title."</a></div>";
      // $content .= "<div><a href='$link'><img src='/images/".$row->image."' class=\"card-img-top\" width='50%'/></a></div>";
      // $content .= "<a href=\"#\" class=\"btn btn-primary\">좋아요 : ".$row->likes."</a>";
      // $content .= "<p class=\"card-text\"> CONTENT : ".$row->content."</p>";
      // $content .= "</div>";
    }

    $content .= "</div>"; // 1.

    $body = file_get_contents("../Resource/instagram.html");
    $body = str_replace("{{content}}", $content, $body);
    // $body = str_replace("{{categori}}",$this->categori(), $body);
    $body = str_replace("{{new}}","/instagram/new", $body);
    echo $body;
  }
  private function categori()
  {
    $query = "SELECT * from categori";
    $result = $this->db->queryExecute($query);
    $count = mysqli_num_rows($result);

    $cate = "";
    for($i=0;$i<$count;$i++) {
      $row = mysqli_fetch_object($result);
      $cate .= "<a href=\"#\" class=\"list-group-item\">".$row->cate."</a>";
    }
    return $cate;
  }
  private function newInsert()
  {
    if ($_POST) {
      move_uploaded_file($_FILES['image']['tmp_name'],"images/".$_FILES['image']['name']);
      $query = "INSERT INTO instagram (title , image, content, likes )
      VALUE ('".$_POST['title']."','".$_FILES['image']['name']."','".$_POST['content']."',0)";
      $result = $this->db->queryExecute($query);

      header("location:"."/instagram");
    }else{
      $body = file_get_contents("../Resource/instagram_new.html");
      $body = str_replace("{{content}}", $content, $body);
      $body = str_replace("{{new}}","/instagram/new", $body);
      echo $body;
    }
  }

  Private function detailView($uid)
  {
    // if(isset($_POST)){
    //   if($_POST['mode'] != ""){
    //     if($this->prevent_multi_submit()){
    //       $query = "UPDATE instagram SET `likes`=`likes`+1 where id='$uid'";
    //       $result = $this->db->queryExecute($query);
    //     }
    //   }
    // }
    if($_POST && $_POST['mode']=="likeup"){
      $query = "UPDATE instagram SET `likes`=`likes`+1 where id='$uid'";
      $result = $this->db->queryExecute($query);
    }
    $query = "SELECT * from instagram WHERE id=".$uid;

    $result = $this->db->queryExecute($query);
    $data = mysqli_fetch_object($result);
    // print_r($data);
    $body = file_get_contents("../Resource/instagram_view.html");
    $body = str_replace("{{id}}", $data->id, $body);
    $body = str_replace("{{title}}", $data->title, $body);
    $body = str_replace("{{likes}}", $data->likes, $body);
    $body = str_replace("{{image}}", "<img src='/images/".$data->image."' width='100%'/>", $body);
    $body = str_replace("{{content}}", $data->content, $body);
    $body = str_replace("{{categori}}",$this->categori(), $body);
    echo $body;
  }
  private function postDelete($uid)
  {
    $query = "DELETE FROM instagram WHERE id=".$uid;
    echo $query;
    $result = $this->db->queryExecute($query);
    header("location:/instagram");
  }
  private function postEdit($uid)
  {
    if ($_POST) {
      $query = "UPDATE instagram SET ";
      foreach ($_POST as $key => $value) {
        if($key == "id") continue;
        $query .= "`$key`= '".$value."',";
      }
      $query = rtrim($query, ","); // 마지막 콤마 제거
      // echo $query;
      // 조건값
      $query .= " WHERE id='".$uid."'";
      // echo $query;
      // exit;
      $result = $this->db->queryExecute($query);
      // 페이지 이동
      header("location:"."/instagram/".$uid);
    }
    $body = file_get_contents("../Resource/instagram_edit.html");
    echo $body;

  }
  function prevent_multi_submit($excl = "validator") {
    $string = "";
    foreach ($_POST as $key => $val) {
      // this test is to exclude a single variable, f.e. a captcha value
      if ($key != $excl) {
        $string .= $key . $val;
      }
    }
    if (isset($_SESSION['mode'])) {
      if ($_SESSION['mode'] === md5($string)) {
        return false;
      } else {
        $_SESSION['mode'] = md5($string);
        return true;
      }
    } else {
      $_SESSION['mode'] = md5($string);
      return true;
    }
  }
}
