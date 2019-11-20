<?php
namespace App\Controller;
class Login
{
  private $db;
  public function __construct($db)
  {
    $this->db = $db;
  }

  // 사용자가 임의로 지정한 동적방식 이 프로젝트는 main() 함수에서 실행이 된다.
  public function main()
  {
    echo "로그인 체크";
    print_r($_POST);

    // 관리자 계정 정보
    $email = "bxcv1230@gmail.com";
    $password = "abcd1234";

    if($_SESSION['email']){
      echo "로그인 상태입니다.";
    }else {
      // 로그인 체크 및 저장
      if($_POST){
        if($_POST['email'] && $_POST['password']){
          $query = "SELECT * FROM mem where email='".$_POST['email']."';";
          echo $query;
          $result = $this->db->queryExecute($query);

          if($row = mysqli_fetch_object($result)){
            // 데이터베이스 조회 성공
            if($_POST['password'] == $row->password){
              // 로그인 성공
            }else {
              echo "비밀번호가 맞지 않습니다.";
              exit;
            }
          }else{
            // 조회 실패
            echo $_POST['email']."는 등록된 회원이 아닙니다.";
            exit;
          }

          echo "로그인 성공";
          $_SESSION['email'] = $_POST['email'];
          header("location:"."/Databases/");
        }else {
          echo "로그인 실패";
          echo "이메일과 비밀번호를 입력해 주세요.";
          exit;

          header("location:"."/");
        }
      }
    }
  }
}
