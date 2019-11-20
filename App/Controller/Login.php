<?php
namespace App\Controller;
class Login
{
  public function __construct()
  {

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
        if($_POST['email'] == $email && $_POST['password'] == $password){
          echo "로그인 성공";
          $_SESSION['email'] = $_POST['email'];
          header("location:"."/Databases/");
        }else {
          echo "로그인 실패";
          header("location:"."/");
        }
      }
    }
  }
}
